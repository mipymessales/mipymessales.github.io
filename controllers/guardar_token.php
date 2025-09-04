<?php
session_start();
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR."controllers/error_handler.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";

$data = json_decode(file_get_contents("php://input"), true);
if (!SqlInjectionUtils::checkSqlInjectionAttempt($data)) {
    global $base_de_datos;
    try {
        $userId=$_SESSION['userId'];
        $stmt = $base_de_datos->prepare("INSERT INTO tokens_admin (user_id, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token=?");
        $token = json_encode($data);
        $stmt->execute([$userId, $token, $token]);
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
    }
}




