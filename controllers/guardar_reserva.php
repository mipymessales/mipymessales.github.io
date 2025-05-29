<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    global $base_de_datos;
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $personas = $_POST['personas'];

    $sql = "INSERT INTO reservas (nombre, telefono, fecha, hora, personas) 
        VALUES (:nombre, :telefono, :fecha, :hora, :personas)";

    $stmt = $base_de_datos->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->bindParam(":hora",  $hora);
    $stmt->bindParam(":personas", $personas);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>

