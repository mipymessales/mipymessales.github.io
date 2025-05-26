<?php
// call_waiter.php
header('Content-Type: application/json');
include_once "error_handler.php";
//$input = json_decode(file_get_contents('php://input'), true);
$table_id = intval($_POST['table_id']);
$estado = $_POST['estado'];

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;
// Insertar llamada
$stmt = $base_de_datos->prepare("REPLACE INTO waiter_calls (mesaid, status, created_at) VALUES (:id, :estado, NOW())");
$stmt->bindParam(':id', $table_id);
$stmt->bindParam(':estado', $estado);


if ($stmt->execute()) {
    if (hash_equals($estado,'pendiente')) {
        echo json_encode(["status" => "success","message" => "El camarero ha sido llamado"]);
    }else{
        echo json_encode(["status" => "success","message" => "El camarero ha sido llamado"]);
    }
} else {
    echo json_encode(["status" => "error"]);
}

?>

