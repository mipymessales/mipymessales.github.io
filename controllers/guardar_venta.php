<?php

session_start();

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
require_once ROOT_DIR."controllers/error_handler.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
        global $base_de_datos;
        $carrito= json_decode($_POST['carrito']);
        if (empty($carrito)){
            echo json_encode(["status" => "error"]);
            exit();
        }
        $restaurantid= $_POST['restaurantid'];


        //{"Aceite girasol":{"cantidad":2,"precio":450,"id":"1","categoria":"alimentos"},"ron":{"cantidad":1,"precio":10,"id":"8","categoria":"bebidas"}}


        try {

             /*
              INSERT INTO `ventas` (`id`, `restaurantid`, `id_producto`, `cantidad`, `precioactual`, `subtotal`, `fecha`, `categoria`) VALUES (NULL, '', '', '', '', '', CURRENT_TIMESTAMP, '')
              */

                foreach ($carrito as $item){


                    $cantidadrestante=intval($item->cantidadp)-intval($item->cantidad);
                    $categoria=$item->categoria;
                    $idp=$item->id;

                    $sentencia = $base_de_datos->prepare("UPDATE ".$categoria." SET  cantidad=:cantidad WHERE id=:idp");
                    $sentencia->bindParam(':cantidad',$cantidadrestante);
                    $sentencia->bindParam(':idp',$idp);
                    if ($sentencia->execute()){

                        $sql = "INSERT INTO ventas (restaurantid, id_producto, cantidad, precioactual, subtotal, categoria,transferencia) 
        VALUES (:restaurantid, :idplato, :cantidad, :precioactual, :subtotal, :categoria,:transferencia) ";
                        $stmt = $base_de_datos->prepare($sql);
                        $stmt->bindParam(":restaurantid",  $restaurantid);
                        $stmt->bindParam(":idplato", $item->id);
                        $stmt->bindParam(":cantidad", $item->cantidad);
                        $stmt->bindParam(":precioactual", $item->precio);
                        $sub=intval($item->cantidad)*doubleval($item->precio);
                        $stmt->bindParam(":subtotal", $sub);
                        $stmt->bindParam(":categoria",  $categoria);
                        $stmt->bindParam(":transferencia",  $item->transferencia);

                        $stmt->execute();
                    }


                }
                echo json_encode(["status" => "success"]);
        }catch (Exception $e){
            echo json_encode(["status" => "error", "msg"=>print_r($e->getTraceAsString())]);
                //header('Location: ' . Host::getHOSTNAME()."templates/404.php");
            }
}
?>

