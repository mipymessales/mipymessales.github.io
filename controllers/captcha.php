<?php

session_start();

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
// Generar código aleatorio
$caracteres = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$codigo = '';
for ($i = 0; $i < 5; $i++) {
    $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
}

// Guardar en sesión
if (isset($_REQUEST["from"]) && !empty($_REQUEST["from"])){
    if (hash_equals($_REQUEST["from"],'s')){
        $_SESSION['captchas'] = $codigo;
    }elseif (hash_equals($_REQUEST["from"],'r')){
        $_SESSION['captchar'] = $codigo;
    }

}
//$_SESSION['captcha'] = $codigo;

// Crear imagen
$imagen = imagecreatetruecolor(120, 40);
$fondo = imagecolorallocate($imagen, 240, 240, 240);
$texto = imagecolorallocate($imagen, 0, 0, 0);
imagefilledrectangle($imagen, 0, 0, 120, 40, $fondo);

// Añadir texto
imagettftext($imagen, 20, 0, 10, 30, $texto, ROOT_DIR . 'assets/font/Lato-HaiIta-webfont.ttf', $codigo);

// Enviar encabezado e imagen
header('Content-type: image/png');
imagepng($imagen);
imagedestroy($imagen);
?>

