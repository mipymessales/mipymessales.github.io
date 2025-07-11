<?php
// Conexión a la base de datos
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
                $stmt = $base_de_datos->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");

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
        $sentencia = $base_de_datos->prepare("DELETE FROM pedidos WHERE id=:idpedidos");
        $sentencia->bindParam(':idpedidos',$id_pedidos);
        if ($sentencia->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }

    }  elseif ($action === 'updatecantidad')  {
        $cantidad = $_POST['cantidad'];
        $id_pedidos = $_POST['id'];
        $sentencia = $base_de_datos->prepare("UPDATE pedidos SET cantidad = :cantidad WHERE id = :idpedidos");
        $sentencia->bindParam(':cantidad',$cantidad);
        $sentencia->bindParam(':idpedidos',$id_pedidos);
        if ($sentencia->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }

    }elseif ($action === 'cerrarcuenta')  {
        $id_cliente = $_POST['idcliente'];
        $id_mesa = $_POST['idmesa'];
        $sentencia = $base_de_datos->prepare("UPDATE cliente SET estado_cuenta = :estado WHERE id = :id");
        $sentencia->bindParam(':id',$id_cliente);
        $estadocuenta=0;
        $sentencia->bindParam(':estado',$estadocuenta);


        try {
        if ($sentencia->execute()) {

            $stm = $base_de_datos->prepare("UPDATE mesa SET disponible = :disponible,user=:user,password=:password WHERE id = :id");
            $stm->bindParam(':id',$id_mesa);
            $vacio=null;
            $stm->bindParam(':user',$vacio);
            $stm->bindParam(':password',$vacio);
            $disponible=1;
            $stm->bindParam(':disponible',$disponible);

            if ($stm->execute()) {
                echo json_encode(["status" => "success"]);
            }else{
                echo json_encode(["status" => "error"]);
            }
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

