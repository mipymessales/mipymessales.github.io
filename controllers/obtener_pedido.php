<?php
try {
session_start();
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR."controllers/error_handler.php";
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
global $base_de_datos;
$pedidos = [];
if (isset($_POST['idcliente']) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    $idcliente = $_POST['idcliente'];
    $idrestaurant=empty($_POST['idrestaurant'])?$_SESSION['idrestaurant']:$_POST['idrestaurant'];
    //Poner con 3 minutos de atraso para mostrar los pedidos de los clientes en el panel de administracion
    $sentencia1 = $base_de_datos->prepare("SELECT * FROM pedidoscliente WHERE restaurantid= ? and id_cliente= ? ");
    $html='';
    try {
        $sentencia1->execute([$idrestaurant,$idcliente]);
        //$cliente = $sentencia1->fetch(PDO::FETCH_ASSOC);



        $subtotal= 0;
        while ($cliente = $sentencia1->fetch(PDO::FETCH_ASSOC)) {
            $categoria = $cliente['categoria'];
           // $idcliente = $cliente['id_cliente'];
            $idplato=$cliente['id_plato'];
            $idpedido=$cliente['id'];
            $cantidad=$cliente['cantidad'];
            $ip=$cliente['ip'];
            if (empty($categoria)) {
                continue;
            }
            $stmt = $base_de_datos->prepare("SELECT nombre,cantidad as cantidadproducto,precioventa as precio FROM " . $categoria . " WHERE id= ".$idplato);
           // $stmt->bindParam(':id', $idplato);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);



            if (!empty($resultado)) {

                $resultado[0]->id = $idpedido;
                $resultado[0]->categoria = $categoria;
                $resultado[0]->estado = $cliente['estado'];
                $resultado[0]->idcliente = $idcliente;
                $resultado[0]->idplato = $idplato;
                $resultado[0]->cantidad = $cantidad;
                $resultado[0]->ip = $ip;
              //  $subtotal+= intval($cantidad)*floatval($resultado[0]->precio);
                $resultado[0]->subtotal= intval($cantidad)*floatval($resultado[0]->precio);
                $pedidos[] =
                    $resultado[0];


            }
                if (empty($html)){
                    $html.="<div class='card-body'  style='background: white;
  padding: 5px;'>
                   Teléfono: <span style='
  padding: 5px;'>".$cliente['telefono']."</span><br>
                  Correo: <span style='
  padding: 5px;'>".$cliente['correo']." </span><br>
    Dirección: <span style='
  padding: 5px;'>".$cliente['direccion']." </span><br>
                </div>";
                }


        }
        header("Content-Type: application/json");

        echo json_encode(["status" => "success","html" =>$html,"data" =>($pedidos)]);
        //echo json_encode($pedidos);



        // $stmt->close();
        // $base_de_datos->close();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

} else {
    die();
}
} catch (Exception $e) {
    echo print_r($e->getTraceAsString());
}
?>