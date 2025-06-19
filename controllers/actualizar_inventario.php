<?php
header('Content-Type: application/json');
session_start();
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
require_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";

global $base_de_datos;
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    $productos = $_POST['productos'] ?? [];

    if (!is_array($productos)) {
        echo json_encode(["error" => "Parámetros inválidos."]);
        exit;
    }

    $inventarioActualizado = [];

    foreach ($productos as $item) {
        // Prevención básica
        $id = isset($item['id']) ? intval($item['id']) : 0;
        $categoria = isset($item['categoria']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $item['categoria']) : '';
        $idrestaurant = isset($_POST['restaurantid']) ?$_POST['restaurantid'] :intval($_SESSION['idrestaurant']);

        if (!$id || !$categoria || !$idrestaurant) continue;

        // Consulta SQL segura
        $query = "SELECT id, nombre, cantidad, disponible FROM `$categoria` 
              WHERE id = :id AND restaurantid = :restaurantid LIMIT 1";

        $stmt = $base_de_datos->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":restaurantid", $idrestaurant, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $inventarioActualizado[$categoria][] = $result;
            }
        } catch (Exception $e) {
            error_log("Error al consultar inventario: " . $e->getMessage());
        }
    }

    echo json_encode($inventarioActualizado);
}



