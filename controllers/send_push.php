<?php
define('ROOT_DIR', dirname(__FILE__, 2) . '/');
require_once ROOT_DIR . "controllers/Host.php";
include_once ROOT_DIR . "pdo/conexion.php";
require ROOT_DIR .'vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Leer JSON del body
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    exit("Datos inválidos");
}

$pedido_id = $input['pedido_id'] ?? null;
$nombre    = $input['nombre'] ?? '';
$telefono  = $input['telefono'] ?? '';
$carritoArray   = $input['carrito'] ?? '[]'; // llega como JSON string

/*Push*/
global $base_de_datos;
$res = $base_de_datos->prepare("Select token from tokens_admin;");
$res->execute();
$subscriptions = [];

while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    $tokentmp=$row['token'];
    $subscriptions[] = json_decode($tokentmp, true);
}
if (!empty($subscriptions) && sizeof($subscriptions)>0){
    $auth = [
        'VAPID' => [
            'subject' => VAPID_SUBJECT,
            'publicKey' => VAPID_PUBLIC_KEY,
            'privateKey' => VAPID_PRIVATE_KEY,
        ],
    ];

    $webPush = new WebPush($auth);



// Resumir carrito para notificación
    $items = [];
    foreach ($carritoArray as $categoria => $productos) {
        $items[] = $categoria . " x " . $productos['cantidad']."\n";
    }
    $carritoResumen = implode(" ", $items);
    // Preparar notificación
    $title = "Nuevo pedido de " . $nombre;
    $body = " 📞Teléfono \n" . $telefono . "\n 🛒Productos \n" . $carritoResumen;

    foreach ($subscriptions as $sub) {
        $subscription = Subscription::create($sub);
        $webPush->queueNotification(
            $subscription,
            json_encode([
                "title" => $title,
                "body" => $body,
                "url" => Host::getHOSTNAME()."login"
            ])
        );
    }

    foreach ($webPush->flush() as $report) {
        if (!$report->isSuccess()) {
            error_log("Error enviando push: " . $report->getReason());
        }
    }
}