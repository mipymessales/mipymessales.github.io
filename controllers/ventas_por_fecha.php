<?php

header('Content-Type: application/json');
$fecha = $_GET['fecha'] ?? null;

if (!$fecha) {
    echo json_encode([]);
    exit;
}

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;

// Consulta de ventas agrupadas por mesa y categoría
$stmt = $base_de_datos->prepare("SELECT cl.id as idcliente,cl.id_mesa as idmesa ,s.* FROM cliente cl LEFT JOIN pedidos s ON cl.id_mesa = s.id_mesa and cl.id = s.id_cliente  WHERE s.fecha BETWEEN ? AND ? and cl.estado_cuenta=0 ");
try {
    $stmt->execute([
        "$fecha 00:00:00",
        "$fecha 23:59:59"
    ]);
} catch (Exception $e) {
    echo print_r($e->getTraceAsString());
}


$ventasAgrupadas = [];
$total_general = 0;

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $categoria = $row['categoria'];
    if (empty($categoria)) {
        continue;
    }
    $cantidad =intval($row['cantidad']);
    $mesa = $row['idmesa'];

    $stmt = $base_de_datos->prepare("SELECT id,nombre,ingredientes,tipo,precio,disponible,valoracion,foto FROM " . $categoria . " WHERE id=:id limit 1");
    $stmt->bindParam(':id', $row['id_plato']);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
    if (!empty($resultado)) {

        if (!isset($ventasAgrupadas[$mesa])) {
            $ventasAgrupadas[$mesa] = [
                'categorias' => [],
                'total_mesa' => 0
            ];
            //$ventasAgrupadas[$mesa]['categorias'][$categoria]=$categoria; // {"mesas":{"1":{"categorias":{"entrantes":{"cantidad":6,"subtotal":2058},"bebidas":{"cantidad":10,"subtotal":1900}},"total_mesa":4558}},"total_general":4558}
            $cantidadC= $cantidad;
            $subtotal= $cantidadC*floatval($resultado[0]->precio);
            $ventasAgrupadas[$mesa]['categorias'][$categoria]['cantidad']=$cantidadC;
            $ventasAgrupadas[$mesa]['categorias'][$categoria]['subtotal']=$subtotal;
            $ventasAgrupadas[$mesa]['total_mesa'] += $subtotal;
            $total_general += $subtotal;
        }else{
            if (isset($ventasAgrupadas[$mesa]['categorias'][$categoria])) {
               $cantidadC=$cantidad;
                //$ventasAgrupadas[$mesa]['categorias'][$categoria]=$categoria;
                $subtotal= $cantidadC*floatval($resultado[0]->precio);
                $ventasAgrupadas[$mesa]['categorias'][$categoria]['cantidad']+=$cantidadC;
                $ventasAgrupadas[$mesa]['categorias'][$categoria]['subtotal']+=$subtotal;
                $ventasAgrupadas[$mesa]['total_mesa'] += $subtotal;
                $total_general += $subtotal;
            }else{
                $cantidadC= $cantidad;
                $subtotal= $cantidadC*floatval($resultado[0]->precio);
                $ventasAgrupadas[$mesa]['categorias'][$categoria]['cantidad']=$cantidadC;
                $ventasAgrupadas[$mesa]['categorias'][$categoria]['subtotal']=$subtotal;
                $ventasAgrupadas[$mesa]['total_mesa'] += $subtotal;
                $total_general += $subtotal;
            }
        }
    }
}

echo json_encode([
    'mesas' => $ventasAgrupadas,
    'total_general' => $total_general
]);
