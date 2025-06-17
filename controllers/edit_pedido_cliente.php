<?php
// Conexión a la base de datos
session_start();
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
global $base_de_datos;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    $action = $_POST['action'] ?? '';
    if ($action === 'update') {
        $id = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if ($id && $estado !== null) {
            try {
                // Preparar la consulta para actualizar (ajusta nombre de tabla y columnas)
                $stmt = $base_de_datos->prepare("UPDATE pedidoscliente SET estado = :estado WHERE id = :id");

                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "Registro actualizado correctamente";
                } else {
                    http_response_code(500);
                    echo "Error al actualizar el registro";
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo "Error en la base de datos: " . $e->getMessage();
            }
        } else {
            http_response_code(400);
            echo "Faltan parámetros para la actualización";
        }
    }  elseif ($action === 'delete')  {
        $id_pedidos = $_POST['id'];
        $sentencia = $base_de_datos->prepare("DELETE FROM pedidoscliente WHERE id=:idpedidos");
        $sentencia->bindParam(':idpedidos',$id_pedidos);
        if ($sentencia->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }

    }  elseif ($action === 'updatecantidad')  {
        $cantidad = $_POST['cantidad'];
        $id_pedidos = $_POST['id'];
        $sentencia = $base_de_datos->prepare("UPDATE pedidoscliente SET cantidad = :cantidad WHERE id = :idpedidos");
        $sentencia->bindParam(':cantidad',$cantidad);
        $sentencia->bindParam(':idpedidos',$id_pedidos);
        if ($sentencia->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }

    }elseif ($action === 'cerrarcuenta')  {

        $idcliente = $_POST['idcliente'];
        $data = ($_POST['data']);
        $idrestaurant=empty($_POST['idrestaurant'])?$_SESSION['idrestaurant']:$_POST['idrestaurant'];
        $trans= 0;
        foreach ($data as $cliente) {
        $categoria = $cliente['categoria'];

        if (empty($categoria) || !hash_equals($cliente['estado'],"Aprobado")) {
            $id_pedidos = $cliente['id'];
            $sentencia = $base_de_datos->prepare("DELETE FROM pedidoscliente WHERE id=:idpedidos");
            $sentencia->bindParam(':idpedidos',$id_pedidos);
           $sentencia->execute();
        }else{
            $idplato=$cliente['idplato'];
            $sql = "INSERT INTO ventas (restaurantid, id_producto, cantidad, precioactual, subtotal, categoria,transferencia) 
        VALUES (:restaurantid, :idplato, :cantidad, :precioactual, :subtotal, :categoria,:transferencia) ";
            $stmt = $base_de_datos->prepare($sql);
            $stmt->bindParam(":restaurantid",  $idrestaurant);
            $stmt->bindParam(":idplato", $idplato);
            $stmt->bindParam(":cantidad", $cliente['cantidad']);
            $stmt->bindParam(":precioactual", $cliente['precio']);
            $sub=intval($cliente['cantidad'])*doubleval($cliente['precio']);
            $stmt->bindParam(":subtotal", $sub);
            $stmt->bindParam(":categoria",  $categoria);
            $stmt->bindParam(":transferencia",  $trans);
            try {
                $stmt->execute();
            }catch (Exception $e){
                echo  print_r($e->getTraceAsString());
            }
        }
    }
        $sentencia = $base_de_datos->prepare("UPDATE cliente SET estado_cuenta = :estado WHERE id = :id");
        $sentencia->bindParam(':id',$idcliente);
        $estadocuenta=0;
        $sentencia->bindParam(':estado',$estadocuenta);

        try {
        if ($sentencia->execute()) {

            echo json_encode(["status" => "success"]);

        } else {
            echo json_encode(["status" => "error"]);
        }
        }catch (Exception $e){
            echo  print_r($e->getTraceAsString());
        }
    }else{
        http_response_code(400);
        echo "Acción no válida";
    }

} else {
    http_response_code(405);
    echo "Método no permitido";
}
?>

