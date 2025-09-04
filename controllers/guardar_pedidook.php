<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

// Configuración
define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
require_once ROOT_DIR . "controllers/error_handler.php";

$response = ["status" => "error", "message" => "Error desconocido"];

try {
    // --- Seguridad básica ---
    if (SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
        $response = ["status" => "error", "message" => "Hay datos incorrectos en el formulario, por favor revisálo "];
        echo json_encode($response);
        exit;
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    $archivo_log = 'intentos.txt';
    $archivo_bloqueo = 'bloqueos.txt';

    // Cargar intentos y bloqueos
    $intentos = file_exists($archivo_log) ? json_decode(file_get_contents($archivo_log), true) : [];
    $bloqueados = file_exists($archivo_bloqueo) ? json_decode(file_get_contents($archivo_bloqueo), true) : [];

    if (!isset($intentos[$ip])) {
        $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];
    }

    // Chequear bloqueo directo
    if (!empty($bloqueados[$ip]['bloqueado'])) {
        echo json_encode([
            "status" => "RATE_LIMITED",
            "message" => "El pedido no se ha registrado porque ha usado la aplicación de forma inadecuada. Contacte con soporte."
        ]);
        exit;
    }

    // Limitar intentos por IP
    if ($intentos[$ip]['intentos'] >= 5 && (time() - $intentos[$ip]['ultimo']) < 300) {
        echo json_encode([
            "status" => "RATE_LIMITED",
            "message" => "Demasiados intentos fallidos. Intenta más tarde."
        ]);
        exit;
    }

    // --- Validación Captcha ---
    $captcha_usuario = $_POST['captchar'] ?? '';
    $captcha_sesion = $_SESSION['captchar'] ?? '';

    if (strcasecmp($captcha_usuario, $captcha_sesion) !== 0) {
        // captcha incorrecto
        $intentos[$ip]['intentos']++;
        $intentos[$ip]['ultimo'] = time();
        file_put_contents($archivo_log, json_encode($intentos));
        echo json_encode(["status" => "RECAPTCHA_FAILED", "message" => "Captcha inválido"]);
        exit;
    }

    // --- Captcha correcto ---
    $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];
    file_put_contents($archivo_log, json_encode($intentos));

    // --- Datos del pedido ---
    global $base_de_datos;
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $restaurantid = intval($_POST['restaurantid'] ?? 0);

    $carrito = json_decode($_POST['carrito'] ?? '[]', true);

    if (empty($carrito)) {
        echo json_encode(["status" => "error", "message" => "Carrito vacío"]);
        exit;
    }

    // --- Guardar pedido rápido ---
    $sql = "INSERT INTO pedidos_pendientes (restaurantid, nombre, telefono, correo, direccion, carrito, ip, estado, fecha) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDIENTE', NOW())";
    $stmt = $base_de_datos->prepare($sql);
    $stmt->execute([$restaurantid, $nombre, $telefono, $correo, $direccion, json_encode($carrito),$ip]);
    $pedido_id = $base_de_datos->lastInsertId();

    //exec("php " . ROOT_DIR . "controllers/send_push.php {$pedido_id} {$nombre} {$telefono} {$carritoArg} > /dev/null 2>&1 &");

    // --- Responder inmediatamente ---
    $response = [
        "status" => "success",
        "message" => "Pedido recibido",
        "pedido_id" => $pedido_id,
        "nombre" => $nombre,
        "telefono" => $telefono,
        "carrito" => $carrito,

    ];


    echo json_encode($response);
    exit;

} catch (Throwable $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
    echo json_encode($response);
    exit;
}
