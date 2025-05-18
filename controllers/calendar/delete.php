<?php
// delete.php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,3).'/');
include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $stmt = $base_de_datos->prepare("DELETE FROM events WHERE id = :id");
    $stmt->bindParam(':id', $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

