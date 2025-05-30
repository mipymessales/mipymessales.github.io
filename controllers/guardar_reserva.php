<?php

session_start();

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
require_once ROOT_DIR."controllers/error_handler.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $archivo_log = 'intentos.txt';
    $captcha_usuario = $_POST['captchar'] ?? '';
    $captcha_sesion = $_SESSION['captchar'] ?? '';

// Leer intentos por IP
    $intentos = [];
    if (file_exists($archivo_log)) {
        $intentos = json_decode(file_get_contents($archivo_log), true);
    }
    if (!isset($intentos[$ip])) {
        $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];
    }

// Limitar intentos
    if ($intentos[$ip]['intentos'] >= 5 && (time() - $intentos[$ip]['ultimo']) < 300) {
        echo json_encode([
            "status" => "RATE_LIMITED",
            "message" => "Demasiados intentos fallidos. Intenta más tarde."
        ]);
        exit;
    }

// Verificar CAPTCHA
    if (strcasecmp($captcha_usuario, $captcha_sesion) === 0) {
       // echo "CAPTCHA correcto. Formulario procesado.";
        $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];

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
    } else {
        $intentos[$ip]['intentos']++;
        $intentos[$ip]['ultimo'] = time();
        echo json_encode(["status" => "RECAPTCHA_FAILED"]);
    }

// Guardar log actualizado
    file_put_contents($archivo_log, json_encode($intentos));
}
?>

