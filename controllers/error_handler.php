<?php
// Mostrar errores en pantalla (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Reportar todos los errores

// Ruta del archivo de log personalizado
$log_file = __DIR__ . '/my_errors.log';

// Función para registrar errores personalizados
function log_custom_error($errno, $errstr, $errfile, $errline) {
    global $log_file;

    $date = date('Y-m-d H:i:s');
    $error_message = "[$date] Error: $errstr in $errfile on line $errline (Code: $errno)\n";

    // Guardar en archivo
    error_log($error_message, 3, $log_file);

    // También mostrar en pantalla (opcional, útil para depuración)
    echo nl2br($error_message);

    // No ejecutar el manejador interno de PHP
    return true;
}

// Registrar función como manejador de errores
set_error_handler("log_custom_error");

// También capturar excepciones no atrapadas
set_exception_handler(function($exception) use ($log_file) {
    $date = date('Y-m-d H:i:s');
    $error_message = "[$date] Uncaught Exception: " . $exception->getMessage() .
        " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";

    error_log($error_message, 3, $log_file);
    echo nl2br($error_message);
});
?>

