<?php
define('ROOT_DIR', dirname(__FILE__, 2) . '/');
require_once ROOT_DIR . "controllers/Host.php";
include_once ROOT_DIR . "pdo/conexion.php";
include_once ROOT_DIR."controllers/error_handler.php";
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
$direccion  = $input['direccion'] ?? '';

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
            echo "Error enviando push: " . $report->getReason();
        }
    }
    try {
        $postData = [
            'nombre' => $nombre,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'carrito' => $carritoResumen
        ];

        $ch = curl_init("https://telegram-server-henna.vercel.app/api/send-telegram");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error al contactar Vercel: ' . curl_error($ch);
        }
        curl_close($ch);
    } catch (Exception $e) {
        echo "Ocurrió un error: " . $e->getMessage();
    }
  /*  try {
    //Send telegram
    $token = TU_BOT_TOKEN; // Token de tu bot
    $chat_id = TU_CHAT_ID; // Tu chat_id
    $mensaje = "📦 *Nuevo Pedido*\n";
    $mensaje .= "👤 Nombre: $nombre\n";
    $mensaje .= "📞 Teléfono: $telefono\n";
    $mensaje .= "📍 Dirección: $direccion\n";
    $mensaje .= "🛒 Productos:\n$carritoResumen";

    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text' => $mensaje,
        'parse_mode' => 'Markdown'
    ];

    // cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Error Telegram: ' . curl_error($ch));
    }
    curl_close($ch);
    } catch (Exception $e) {

        echo "Ocurrió un error con la base de datos: " . $e->getMessage();
    }*/
}


