<?php
session_start();
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";

require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";


    header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);
$restaurantid= isset($_POST['restaurantid'])?intval($_POST['restaurantid']):intval($_SESSION['idrestaurant']) ; // o el valor desde sesión
global $base_de_datos;
if ($method === 'GET' && !SqlInjectionUtils::checkSqlInjectionAttempt($_GET)) {
    $stmt = $base_de_datos->prepare("SELECT * FROM gastos WHERE restaurantid = ? ORDER BY fecha DESC");
    $stmt->execute([$restaurantid]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($method === 'POST' && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    $fecha = $input['fecha'];
    $concepto = $input['concepto'];
    $monto = floatval($input['monto']);

    $stmt = $base_de_datos->prepare("INSERT INTO gastos (fecha, concepto, monto, restaurantid) VALUES (?, ?, ?, ?)");
    $stmt->execute([$fecha, $concepto, $monto, $restaurantid]);
    echo json_encode(["success" => true]);
    exit;
}

if ($method === 'PUT' && !SqlInjectionUtils::checkSqlInjectionAttempt($input)) {
    $id = $input['id'];
    $fecha = $input['fecha'];
    $concepto = $input['concepto'];
    $monto = floatval($input['monto']);

    $stmt = $base_de_datos->prepare("UPDATE gastos SET fecha = ?, concepto = ?, monto = ? WHERE id = ? AND restaurantid = ?");
    $stmt->execute([$fecha, $concepto, $monto, $id, $restaurantid]);
    echo json_encode(["success" => true]);
    exit;

}
if ($method === 'DELETE' && !SqlInjectionUtils::checkSqlInjectionAttempt($input)) {
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id = intval($delete_vars['id']);

    $stmt = $base_de_datos->prepare("DELETE FROM gastos WHERE id = ? AND restaurantid = ?");
    $stmt->execute([$id, $restaurantid]);
    echo json_encode(["success" => true]);
    exit;
}

