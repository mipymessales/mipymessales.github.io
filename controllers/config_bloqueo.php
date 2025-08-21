<?php
session_start();
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";

require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";


header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);
$restaurantid= isset($_POST['restaurantid'])?intval($_POST['restaurantid']):intval($_SESSION['idrestaurant']) ; // o el valor desde sesión
if ($method === 'POST' && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    $ip = $input['ip'];
    $archivo_bloqueo = 'bloqueos.txt';
    $bloqueados = [];
    if (file_exists($archivo_bloqueo)) {
        $bloqueados = json_decode(file_get_contents($archivo_bloqueo), true);
    }
    // Chequear bloqueos
    if ($bloqueados[$ip]['bloqueado']) {
        $bloqueados[$ip] = ['bloqueado' => 0, 'fecha' => time(),"restaurantid"=>$restaurantid];
    }else{
        $bloqueados[$ip] = ['bloqueado' => 1, 'fecha' => time(),"restaurantid"=>$restaurantid];
    }
    file_put_contents($archivo_bloqueo, json_encode($bloqueados));
    echo json_encode(["success" => true]);
    exit;
}



