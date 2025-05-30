<?php
session_start();

$ip = $_SERVER['REMOTE_ADDR'];
$archivo_log = 'intentos.txt';
$captcha_usuario = $_POST['captcha'] ?? '';
$captcha_sesion = $_SESSION['captcha'] ?? '';

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
    die('Demasiados intentos fallidos. Intenta más tarde.');
}

// Verificar CAPTCHA
if (strcasecmp($captcha_usuario, $captcha_sesion) === 0) {
    echo "CAPTCHA correcto. Formulario procesado.";
    $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];
} else {
    echo "CAPTCHA incorrecto.";
    $intentos[$ip]['intentos']++;
    $intentos[$ip]['ultimo'] = time();
}

// Guardar log actualizado
file_put_contents($archivo_log, json_encode($intentos));
?>

