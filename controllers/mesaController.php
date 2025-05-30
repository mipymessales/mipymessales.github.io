<?php

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once "cifrado.php";
include_once "Host.php";
require_once "class.SqlInjectionUtils.php";
if (isset($_POST["mesa"]) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST) ) {
    require_once ROOT_DIR."library/phpqrcode/qrlib.php"; // Asegúrate de tener la librería
// 1. Generar usuario y contraseña aleatorios
    $nroMesa=$_POST["mesa"];
    $usuario_generado = 'mesa_'.$nroMesa;//'user_' . rand(1000, 9999);
    $contrasena_generada = bin2hex(random_bytes(4)); // 8 caracteres aleatorios

// 2. (Opcional) Guardar en base de datos para validar después
    require_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("UPDATE mesa set user=:usuario, password=:password WHERE id=:idmesa");
    $sentencia->bindParam(':usuario', $usuario_generado);
    $sentencia->bindParam(':password', $contrasena_generada);
    $sentencia->bindParam(':idmesa', $nroMesa);
   
    try {
        $sentencia->execute();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

// 3. Crear URL de login automático
//$loginURL = "httpss://tusitio.com/login.php?user=$usuario&pass=$contrasena";
   // $loginURL = "https://localhost/mesa.php?mesa=$nroMesa&user=$usuario_generado&pass=$contrasena_generada";

    $loginURL = Host::getHOSTNAME()."mesa.php/".cifrado::cifrar_url("$nroMesa/$usuario_generado/$contrasena_generada",cifrado::getClaveSecreta());


// 4. Generar el código QR
    //$nombreArchivo = 'qr_mesa1' . time() . '.png';
    $nombreArchivo = 'qr_mesa'.$nroMesa.'.png';
    $target_file=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/".$nombreArchivo;
    $rutaCompleta = $target_file;

    QRcode::png($loginURL, $rutaCompleta, QR_ECLEVEL_L, 4);

// 5. Mostrar resultados
    echo "<div class='container'>";
    echo "<h3>QR de login generado para la Mesa # $nroMesa </h3>";
    echo "<p style='margin: 10px 0 0 0;'><strong>Usuario:</strong> $usuario_generado <br><strong>Contraseña:</strong> $contrasena_generada</p>";
    //echo "<p><strong>Contraseña:</strong> $contrasena_generada</p>";
    $srcImg="/images/".$nombreArchivo;
    echo "<img style='height: 287px;'  alt='Menu' src='$srcImg' />";
    echo "<p class='mb-2' style='margin: 10px 0 0 0;'>$loginURL</p>";
    echo "</div>";
}else{
    die();
}