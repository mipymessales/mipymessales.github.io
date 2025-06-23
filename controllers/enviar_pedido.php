<?php
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
require_once ROOT_DIR . "controllers/Mailer.php";
require_once ROOT_DIR . "controllers/error_handler.php";
// Verifica campos esperados

// Limpia y prepara
$contenido = $_POST['contenido'];
$nombre = htmlspecialchars($_POST['nombre']);
$telefono = htmlspecialchars($_POST['telefono']);
$correo = htmlspecialchars($_POST['correo']);
$direccion = nl2br(htmlspecialchars($_POST['direccion']));
//$observaciones = nl2br(htmlspecialchars($_POST['observaciones']));

$style="<html>
<head><style> 
        .alert-warning {
    color: #856200;
    background-color: #fff2cc;
    border-color: #ffecb8; }
        .section-title {
    color: #856200;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: .5rem;
}
        </style></head>";
// Cuerpo del mensaje
$contenido=$style."<body>".$contenido."</body>
</html>";
$mensaje = "
<h2>Nuevo Pedido</h2>
<p><strong>Nombre:</strong> {$nombre}</p>
<p><strong>Teléfono:</strong> {$telefono}</p>
<p><strong>Dirección:</strong> {$direccion}</p>
<hr>
<h3>Resumen del pedido</h3>
{$contenido}
";

// Enviar correo
try {
    $correoDestino = $correo; // reemplaza por tu correo real
    $mailer = new Mailer($correoDestino, "Nuevo pedido de $nombre");

    if ($mailer->enviarPedido($mensaje)) {
        echo json_encode(["success" => true, "message" => "Pedido enviado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al enviar el pedido.", "error" => error_get_last()]);
    }
} catch (Exception $e) {
    echo print_r($e->getTraceAsString());
    //header('Location: ' . Host::getHOSTNAME()."templates/404.php");
}
?>

