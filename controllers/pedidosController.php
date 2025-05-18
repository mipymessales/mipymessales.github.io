<?php
$errorAdd="";
$msg="";
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
include_once ROOT_DIR."controllers/cifrado.php";
include_once ROOT_DIR."controllers/Host.php";

if (isset($_POST["action"]) && hash_equals($_POST["action"],"incremnetPedido") && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    $id_plato=$_POST["idplato"];
    $categoria=$_POST["categoria"];
    $idmesa=$_POST["idmesa"];
    $idcliente=$_POST["idcliente"];
    $idpedidos=$_POST["idpedidos"];



    $query="INSERT INTO pedidos (id_cliente, id_mesa, id_plato, categoria) VALUES (:id_cliente, :id_mesa, :id_plato, :categoria);";
    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare($query);
    $sentencia->bindParam(':id_mesa', $idmesa);
    $sentencia->bindParam(':id_cliente', $idcliente);
    $sentencia->bindParam(':id_plato', $id_plato);
    $sentencia->bindParam(':categoria', $categoria);
    try{
        if ($sentencia->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
      //  header('Location: ' . Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$idmesa/$idcliente",cifrado::getClaveSecreta()));
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
        header('Location: ' . Host::getHOSTNAME()."templates/404.php");
    }


}elseif (isset($_POST["action"]) && hash_equals($_POST["action"],"deletePedido") && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    $id_plato=$_POST["idplato"];
    $estadopedido=$_POST["estadopedido"];
    $idmesa=$_POST["idmesa"];
    $idcliente=$_POST["idcliente"];
    $idpedidos=$_POST["idpedidos"];
    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $stmt = $base_de_datos->prepare("DELETE FROM pedidos WHERE id_plato = :idplato and id_mesa = :idmesa and id_cliente = :idcliente and estado = :estadopedido limit 1");
    $stmt->bindParam(':idplato', $id_plato);
    $stmt->bindParam(':idmesa', $idmesa);
    $stmt->bindParam(':idcliente', $idcliente);
    $stmt->bindParam(':estadopedido', $estadopedido);

    try{
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
       // header('Location: ' . Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$idmesa/$idcliente",cifrado::getClaveSecreta()));
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
        header('Location: ' . Host::getHOSTNAME()."templates/404.php");
    }

}elseif (isset($_POST["action"]) && hash_equals($_POST["action"],"deleteAllPedido") && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    $id_plato=$_POST["idplato"];
    $estadopedido=$_POST["estadopedido"];
    $idmesa=$_POST["idmesa"];
    $idcliente=$_POST["idcliente"];

    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $stmt = $base_de_datos->prepare("DELETE FROM pedidos WHERE id_plato = :idplato and id_mesa = :idmesa and id_cliente = :idcliente and estado = :estadopedido ");
    $stmt->bindParam(':idplato', $id_plato);
    $stmt->bindParam(':idmesa', $idmesa);
    $stmt->bindParam(':idcliente', $idcliente);
    $stmt->bindParam(':estadopedido', $estadopedido);

    try{
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
      //  header('Location: ' . Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$idmesa/$idcliente",cifrado::getClaveSecreta()));
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
        header('Location: ' . Host::getHOSTNAME()."templates/404.php");
    }

}else{
    if (isset($_POST["id_categoria"]) && isset($_POST["idmesa"]) && isset($_POST["idcliente"]) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){

        $id_plato=$_POST["id_categoria"];
        $categoria=$_POST["categoria"];
        $idmesa=$_POST["idmesa"];
        $idcliente=$_POST["idcliente"];
        $valor="cantidad_pedido".$categoria.$id_plato.$idcliente;
        $cantidad_pedido=$_POST[$valor];
        include_once ROOT_DIR."pdo/conexion.php";
        global $base_de_datos;

        //$query="INSERT INTO pedidos (id_cliente, id_mesa, id_plato, categoria) VALUES (:id_cliente, :id_mesa, :id_plato, :categoria);";

            $query="INSERT INTO pedidos (id_cliente, id_mesa, id_plato, categoria,cantidad) VALUES (:id_cliente, :id_mesa, :id_plato, :categoria, :cantidad);";

        $sentencia = $base_de_datos->prepare($query);
        $sentencia->bindParam(':id_mesa', $idmesa);
        $sentencia->bindParam(':id_cliente', $idcliente);
        $sentencia->bindParam(':id_plato', $id_plato);
        $sentencia->bindParam(':categoria', $categoria);
        $sentencia->bindParam(':cantidad', $cantidad_pedido);

        try{
            $sentencia->execute();
            header('Location: ' . Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$idmesa/$idcliente",cifrado::getClaveSecreta()));
        }catch (Exception $e){
            echo  print_r($e->getTraceAsString());
            header('Location: ' . Host::getHOSTNAME()."templates/404.php");
        }
    }
}




