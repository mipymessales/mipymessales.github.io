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
<head><style> #carritoVisual {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 16px;
            width: 100%;
            /*max-width: 500px;*/
            background-color: #fdfdfd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        #carritoVisual h3 {
            text-align: center;
            margin-bottom: 16px;
        }

        table.factura {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.factura th,
        table.factura td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table.factura th {
            background-color: #f0f0f0;
        }

        .total-fila {
            font-weight: bold;
            text-align: right;
            background-color: #fafafa;
        }

        .vacio {
            text-align: center;
            color: #888;
        }
        html {
            scroll-behavior: smooth;
        }
        table {
            border-collapse: collapse;
            width: 100%; /* opcional */
        }

        td, th {
            padding: 10px 15px; /* ← aquí ajustas el espacio interno */
            text-align: left;   /* mejora la lectura */
            border: 0.5px solid #ccc;
        }
        #estado-container {
            margin-top: 20px;
            font-size: 1.2em;
        }

        .reloj, .dia, #estado {
            margin: 5px 0;
        }
        .oculto {
            display: none !important;
        }


        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .factura {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* Asegura que tenga un ancho mínimo */
        }

        .factura th, .factura td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f0f0f0;
        }
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

