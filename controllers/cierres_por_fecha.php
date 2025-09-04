<?php
session_start();
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."controllers/auth_check.php";
include_once ROOT_DIR."pdo/conexion.php";
header('Content-Type: application/json');
$fecha = $_POST['fecha'] ?? date("Y-m-d");
$restaurantid= isset($_POST['restaurantid'])?intval($_POST['restaurantid']):intval($_SESSION['idrestaurant']) ;
if (!$fecha) {
    echo json_encode([]);
    exit;
}

$post = json_decode(file_get_contents("php://input"), true);


require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($post)) {


    $tipo = $post['tipo'];

    if ($tipo === "dia") {
        $desde = "{$post['fecha']} 00:00:00";
        $hasta = "{$post['fecha']} 23:59:59";
    } else {
        $desde = "{$post['desde']} 00:00:00";
        $hasta = "{$post['hasta']} 23:59:59";
    }

    $sql = "
  SELECT v.categoria, v.id_producto,v.transferencia, v.precioactual,
    SUM(CASE WHEN DATE(v.fecha) BETWEEN DATE(?) AND DATE(?) THEN v.cantidad ELSE 0 END) AS venta_dia,
    SUM(v.cantidad) AS venta_mes
  FROM ventas v
  WHERE v.restaurantid = ? AND v.fecha BETWEEN ? AND ?
  GROUP BY v.categoria, v.id_producto, v.transferencia,v.precioactual
";
    global $base_de_datos;
    $stmt = $base_de_datos->prepare($sql);
    $stmt->execute([$desde, $hasta, $restaurantid, $desde, $hasta]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($rows as $r) {
        $cat = $r['categoria'];
        $pid = $r['id_producto'];
        $precioactual = $r['precioactual'];
        if (hash_equals("combos",$cat)){
            $qry = $base_de_datos->prepare("SELECT nombre,monto_total as precioventa,monto_descuento as preciotransferencia,monto_descuento as preciocompra FROM `$cat` WHERE id = ?");
        }else{
            $qry = $base_de_datos->prepare("SELECT nombre, precioventa, preciotransferencia,preciocompra FROM `$cat` WHERE id = ?");
        }

        $qry->execute([$pid]);
        $prod = $qry->fetch(PDO::FETCH_ASSOC);
        if (!$prod) continue;
        if (hash_equals("combos",$cat)){
            $precio =floatval($prod['precioventa']);
        }else
        $precio = floatval($precioactual);

        $data[$cat][] = [
            'nombre' => $prod['nombre'],
            'venta_dia' => intval($r['venta_dia']),
            'venta_mes' => intval($r['venta_mes']),
            'precio_unitario' => $precio,
            'precio_compra' => floatval($prod['preciocompra']),
            'transferencia' => $r['transferencia'],
        ];
    }

// Obtener gastos según rango de fechas
    $sqlGastos = "SELECT id, concepto, monto, fecha FROM gastos WHERE restaurantid = ?";
    $stmtG = $base_de_datos->prepare($sqlGastos);
    $stmtG->execute([$restaurantid]);
    $gastos = $stmtG->fetchAll(PDO::FETCH_ASSOC);


// Obtener gastos aplicados de esa fecha
    $gastosAplicados = [];
    $stmt = $base_de_datos->prepare("SELECT gasto_id,COUNT(gasto_id) AS cant_mes FROM gastos_aplicados WHERE restaurantid = ? AND fecha BETWEEN DATE(?) AND DATE(?)  GROUP BY gasto_id,fecha ORDER BY gasto_id,fecha ");
    $stmt->execute([$restaurantid, $desde, $hasta]);
    $repeticiones = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        /* $gastosAplicados[] = [
             'gasto_id'=>  $row['gasto_id'],
             'cant_mes' => intval($row['cant_mes']),
         ];*/
        $gastosAplicados[] = $row['gasto_id'];
        if (isset($repeticiones[$row['gasto_id']])) {
            $repeticiones[$row['gasto_id']] += $row['cant_mes'];
        } else {
            $repeticiones[$row['gasto_id']] = $row['cant_mes'];
        }
    }

// Marcar cada gasto con "aplicado"
    foreach ($gastos as &$gasto) {
        /*    if (in_array($gasto['id'], $gastosAplicados)){
                $gasto['aplicado'] =true;
                $gasto['cant_mes'] +=1;
            }else{
                $gasto['aplicado'] =false;
            }*/
        if (in_array($gasto['id'], $gastosAplicados)) {
            $gasto['aplicado'] = true;
            $gasto['cantmes'] = $repeticiones[$gasto['id']];
        } else {
            $gasto['aplicado'] = false;
            $gasto['cantmes'] = 0;
        }

        // $gasto['aplicado'] = in_array($gasto['id'], $gastosAplicados["gasto_id"]);
    }


// Añadir gastos al resultado
    echo json_encode([
        'data' => $data,
        'gastos' => $gastos
    ]);

}

