<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."controllers/cifrado.php";
include_once ROOT_DIR."controllers/Host.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST) ) {


$imagess=$_POST["foto"]??"blank1.jpg";

$target_dir = "../images/"; //directorio en el que se subira
$target_file = $target_dir .  basename($_FILES["image"]["name"]);//se añade el directorio y el nombre del archivo
$uploadOk = 1;//se añade un valor determinado en 1
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Comprueba si el archivo de imagen es una imagen real o una imagen falsa
//if(isset($_POST["submit"])) {//detecta el boton
$msg="";

//ImageResize(250, 250,    basename($_FILES["image"]["name"]));

if($_FILES["image"]["tmp_name"]!=null)
    $check = getimagesize($_FILES["image"]["tmp_name"]);
else{
    if (empty($imagess))
        $check=false;
}


if($check !== false) {//si es falso es una imagen y si no lanza error
    //echo "Archivo es una imagen- " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    $msg.= "El archivo no es una imagen </br>";
    $uploadOk = 0;
}
//}
// Comprobar si el archivo ya existe
if (file_exists($target_file)) {
    //$uploadOk = 0;//si existe lanza un valor en 0
    if(!empty(basename($_FILES["image"]["name"]))){
        //  $uploadOk=1;
        $imagess=basename($_FILES["image"]["name"]);
    }

}else{
// Comprueba el peso
    if ($_FILES["image"]["size"] > 500000) {
        $msg.=  "El archivo es muy pesado </br>";
        $uploadOk = 0;
    }
// Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $msg.=  "Solo, JPG, JPEG, PNG & GIF Estan soportados </br>";
        $uploadOk = 0;
    }
//Comprueba si $ uploadOk se establece en 0 por un error


    if ($uploadOk == 0) {
        $msg.=  "El archivo no se subio </br>";
// si todo está bien, intenta subir el archivo
    } else {

        // include_once "imageclass.php";

        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            // Ok para continuar
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $imagess=basename($_FILES["image"]["name"]);
                echo "El archivo ". basename( $_FILES["image"]["name"]). " Se subio correctamente";
            } else {
                $msg.=  "Error al cargar el archivo";

            }
        } else {
            $msg.=  "Error al subir la imagen: " . $_FILES["image"]["error"]."</br>";
        }


    }
}

include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;

$nombrep=$_POST['nombrep'];
$ci=$_POST['ci'];
$tel=$_POST['tel'];
$user=empty($_POST['user'])?'':$_POST['user'];
$pass=empty($_POST['pass'])?'':bin2hex($_POST['pass']);
$rol=2;
$activo=1;

    $sentencia = $base_de_datos->prepare("INSERT INTO trabajador (nombre,nombre_usuario,contrasena_usuario,phone ,activo ,ci ,id_rol_usuario ,foto) VALUES (:nombre,:nombre_usuario,:contrasena_usuario,:phone ,:activo ,:ci ,:id_rol_usuario ,:foto)");
    $passS = password_hash($pass, PASSWORD_DEFAULT); // hash seguro
    $sentencia->bindParam(':nombre', $nombrep);
    $sentencia->bindParam(':nombre_usuario', $user);
    $sentencia->bindParam(':contrasena_usuario', $passS);
    $sentencia->bindParam(':phone', $tel);
    $sentencia->bindParam(':activo', $activo);
    $sentencia->bindParam(':ci', $ci);
    $sentencia->bindParam(':id_rol_usuario', $rol);
    $sentencia->bindParam(':foto', $imagess);
    try {
        $sentencia->execute();
        header('Location: ' . Host::getHOSTNAME()."/index.php?section=users");
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

}