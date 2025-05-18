<?php
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
global $base_de_datos;
$pedidos = [];
if (isset($_POST['mesa']) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    $nro_mesa = $_POST['mesa'];;
    $sentencia1 = $base_de_datos->prepare("SELECT cl.id as idcliente,cl.id_mesa as idmesa,cl.monto_cuenta ,s.* FROM cliente cl LEFT JOIN pedidos s ON cl.id_mesa = s.id_mesa and cl.id = s.id_cliente WHERE cl.id_mesa = ? and cl.estado_cuenta=1 ");

    try {
        $sentencia1->execute([$nro_mesa]);
        //$cliente = $sentencia1->fetch(PDO::FETCH_ASSOC);


        $i = 0;

        while ($cliente = $sentencia1->fetch(PDO::FETCH_ASSOC)) {
            $categoria = $cliente['categoria'];
            $idcliente = $cliente['idcliente'];
            $idplato=$cliente['id_plato'];
            $idpedido=$cliente['id'];
            $cantidad=$cliente['cantidad'];
            if (empty($categoria)) {
                continue;
            }
                $stmt = $base_de_datos->prepare("SELECT nombre,precio FROM " . $categoria . " WHERE id=:id ");
                $stmt->bindParam(':id', $cliente['id_plato']);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);



                if (!empty($resultado)) {

                    $resultado[0]->id = $idpedido;
                    $resultado[0]->categoria = $categoria;
                    $resultado[0]->estado = $cliente['estado'];
                    $resultado[0]->idcliente = $idcliente;
                    $resultado[0]->idplato = $idplato;
                    $resultado[0]->cantidad = $cantidad;
                    $resultado[0]->subtotal += intval($cantidad)*floatval($resultado[0]->precio);
                    $pedidos[] =
                        $resultado[0];

                    $i += 1;
                }



        }
        header("Content-Type: application/json");
       echo json_encode($pedidos);



       // $stmt->close();
       // $base_de_datos->close();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

} else {
    die();
}

?>

