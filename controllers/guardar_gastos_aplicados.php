<?php
session_start();
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
header("Content-Type: application/json");

$post = json_decode(file_get_contents("php://input"), true);

if (!SqlInjectionUtils::checkSqlInjectionAttempt($post)) {

    $fecha = $post["fecha"];
    $gastos = $post["gastos"];
    $restaurantid = $_SESSION['idrestaurant'];

    try {
        global $base_de_datos;
        $db = $base_de_datos;

        foreach ($gastos as $g) {
            // Buscar gasto_id exacto
            $stmt = $db->prepare("SELECT id FROM gastos WHERE fecha = ? AND concepto = ? AND monto = ?");
            $stmt->execute([$g["fecha"], $g["concepto"], $g["monto"]]);
            $gasto = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$gasto) continue;

            $gasto_id = $gasto["id"];

            if ($g["aplicado"]) {
                // Insertar si no existe
                $insert = $db->prepare("INSERT IGNORE INTO gastos_aplicados (gasto_id, fecha, restaurantid) VALUES (?, ?, ?)");
                $insert->execute([$gasto_id, $fecha, $restaurantid]);
            } else {
                // Eliminar si existe
                $delete = $db->prepare("DELETE FROM gastos_aplicados WHERE gasto_id = ? AND fecha = ? AND restaurantid = ?");
                $delete->execute([$gasto_id, $fecha, $restaurantid]);
            }
        }

        echo json_encode(["success" => true, "message" => "Gastos aplicados actualizados"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error al actualizar gastos aplicados", "error" => $e->getMessage()]);
    }

}
