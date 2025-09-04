<?php
define('ROOT_DIR', dirname(__FILE__, 2) . '/');
require_once ROOT_DIR . "controllers/Host.php";
include_once ROOT_DIR . "pdo/conexion.php";
require ROOT_DIR .'vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$pedidoId = $argv[1];
$nombre   = $argv[2];
$telefono = $argv[3];
$carrito  = $argv[4]; // si viene en JSON, recuerda decodificarlo
$carritoArray = json_decode($carrito, true);
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
    $body = "Teléfono 📞: " . $telefono . "\n Productos 🛒 \n" . $carritoResumen;

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