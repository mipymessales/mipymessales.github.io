<?php

session_start();

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
require_once ROOT_DIR."controllers/error_handler.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $archivo_log = 'intentos.txt';
    $archivo_bloqueo = 'bloqueos.txt';
    $captcha_usuario = $_POST['captchar'] ?? '';
    $captcha_sesion = $_SESSION['captchar'] ?? '';

// Leer intentos por IP
    $intentos = [];
    if (file_exists($archivo_log)) {
        $intentos = json_decode(file_get_contents($archivo_log), true);
    }
    $bloqueados = [];
    if (file_exists($archivo_bloqueo)) {
        $bloqueados = json_decode(file_get_contents($archivo_bloqueo), true);
    }
    if (!isset($intentos[$ip])) {
        $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];
    }

    // Chequear bloqueos
    if ($bloqueados[$ip]['bloqueado']) {
        echo json_encode([
            "status" => "RATE_LIMITED",
            "message" => "El pedido no se ha registrado porque ha usado la aplicación de forma inadecuada. Contacte con soporte: mipymessalesmanager@gmail.com"
        ]);
        exit;
    }

// Limitar intentos
    if ($intentos[$ip]['intentos'] >= 5 && (time() - $intentos[$ip]['ultimo']) < 300) {
        echo json_encode([
            "status" => "RATE_LIMITED",
            "message" => "Demasiados intentos fallidos. Intenta más tarde."
        ]);
        exit;
    }

// Verificar CAPTCHA
    if (strcasecmp($captcha_usuario, $captcha_sesion) === 0) {
       // echo "CAPTCHA correcto. Formulario procesado.";
        $intentos[$ip] = ['intentos' => 0, 'ultimo' => time()];

        global $base_de_datos;
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $carrito= json_decode($_POST['carrito']);
        if (empty($carrito)){
            echo json_encode(["status" => "error"]);
            exit();
        }
        $restaurantid= $_POST['restaurantid'];
        $correo= $_POST['correo'];

        //{"Aceite girasol":{"cantidad":2,"precio":450,"id":"1","categoria":"alimentos"},"ron":{"cantidad":1,"precio":10,"id":"8","categoria":"bebidas"}}


        $sentencia = $base_de_datos->prepare("INSERT INTO cliente (full_name, restaurantid,id_mesa) VALUES (:nombre, :restaurantid,0)");
        $sentencia->bindParam(':nombre', $nombre);
        $sentencia->bindParam(':restaurantid', $restaurantid);

        try {
            if ($sentencia->execute()){
                $idcliente = $base_de_datos->lastInsertId();

                foreach ($carrito as $item){
                    $sql = "INSERT INTO pedidoscliente (id_cliente, id_plato, nombre, cantidad,precioventa, telefono, direccion, correo, restaurantid, categoria, ip) 
        VALUES (:idcliente, :idplato, :nombre, :cantidad,:precioventa, :telefono, :direccion, :correo, :restaurantid, :categoria,:ip) ";

                    $stmt = $base_de_datos->prepare($sql);
                    $stmt->bindParam(":idcliente", $idcliente);
                    $stmt->bindParam(":nombre", $nombre);

                    $stmt->bindParam(":idplato", $item->id);
                    $stmt->bindParam(":cantidad", $item->cantidad);
                    $stmt->bindParam(":precioventa", $item->precio);
                    $stmt->bindParam(":categoria",  $item->categoria);

                    $stmt->bindParam(":telefono", $telefono);
                    $stmt->bindParam(":direccion", $direccion);
                    $stmt->bindParam(":correo", $correo);
                    $stmt->bindParam(":restaurantid",  $restaurantid);
                    $stmt->bindParam(":ip",  $ip);

                    $stmt->execute();
                }

                if (true) {
                    echo json_encode(["status" => "success"]);
                } else {
                    echo json_encode(["status" => "error"]);
                }
            }

        }catch (Exception $e){
                echo  print_r($e->getTraceAsString());
                //header('Location: ' . Host::getHOSTNAME()."templates/404.php");
            }





    } else {
        $intentos[$ip]['intentos']++;
        $intentos[$ip]['ultimo'] = time();
        echo json_encode(["status" => "RECAPTCHA_FAILED"]);
    }

// Guardar log actualizado
    file_put_contents($archivo_log, json_encode($intentos));
}
?>

