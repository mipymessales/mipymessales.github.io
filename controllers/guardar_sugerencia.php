<?php

session_start();

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
require_once ROOT_DIR."controllers/error_handler.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";


if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $archivo_log = 'intentos.txt';
    $captcha_usuario = $_POST['captchas'] ?? '';
    $captcha_sesion = $_SESSION['captchas'] ?? '';

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
        header('Content-Type: application/json');
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
        $mensaje = $_POST['mensaje'];
        $imagess=$_REQUEST["userimage"]??"blank1.jpg";

        $target_dir = "../images/"; //directorio en el que se subira
        $target_file = $target_dir .  basename($_FILES["userimage"]["name"]);//se añade el directorio y el nombre del archivo
        $uploadOk = 1;//se añade un valor determinado en 1
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Comprueba si el archivo de imagen es una imagen real o una imagen falsa
//if(isset($_POST["submit"])) {//detecta el boton


        //ImageResize(250, 250,    basename($_FILES["image"]["name"]));

        if($_FILES["userimage"]["tmp_name"]!=null)
            $check = getimagesize($_FILES["userimage"]["tmp_name"]);
        else{
            if (empty($imagess))
                $check=false;
        }


        if($check !== false) {//si es falso es una imagen y si no lanza error
            //echo "Archivo es una imagen- " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
//}
// Comprobar si el archivo ya existe
        if (file_exists($target_file)) {
            //$uploadOk = 0;//si existe lanza un valor en 0
            if(!empty(basename($_FILES["userimage"]["name"]))){
                //  $uploadOk=1;
                $imagess=basename($_FILES["userimage"]["name"]);
            }

        }else{
// Comprueba el peso
            if ($_FILES["userimage"]["size"] > 500000) {
                $uploadOk = 0;
            }
// Permitir ciertos formatos de archivo
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                $uploadOk = 0;
            }
//Comprueba si $ uploadOk se establece en 0 por un error


            if ($uploadOk == 0) {
// si todo está bien, intenta subir el archivo
            } else {

                // include_once "imageclass.php";

                if ($_FILES["userimage"]["error"] === UPLOAD_ERR_OK) {
                    // Ok para continuar
                    if (move_uploaded_file($_FILES["userimage"]["tmp_name"], $target_file)) {
                        $imagess=basename($_FILES["userimage"]["name"]);
                       // echo "El archivo ". basename( $_FILES["image"]["name"]). " Se subio correctamente";
                    } else {
                    }
                } else {
                }


            }
        }
        $sql = "INSERT INTO sugerencias (mensaje,userimage,restaurantid) VALUES (:s,:userimage,:restaurantId)";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->bindParam(":s", $mensaje);
        $stmt->bindParam(":userimage", $imagess);
        $stmt->bindParam(":restaurantId", $_POST['restaurantId']);


        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(["status" => "success"]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(["status" => "error"]);
        }
    } else {
        $intentos[$ip]['intentos']++;
        $intentos[$ip]['ultimo'] = time();
        header('Content-Type: application/json');
        echo json_encode(["status" => "RECAPTCHA_FAILED"]);
    }

// Guardar log actualizado
    file_put_contents($archivo_log, json_encode($intentos));
}
?>

