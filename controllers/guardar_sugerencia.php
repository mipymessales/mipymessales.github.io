<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";

require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    global $base_de_datos;
    $mensaje = $_POST['mensaje'];
    $sql = "INSERT INTO sugerencias (mensaje) VALUES (:s)";
    $stmt = $base_de_datos->prepare($sql);
    $stmt->bindParam(":s", $mensaje);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }

}
?>

