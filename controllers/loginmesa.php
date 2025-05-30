<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');

require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";

// Obtén la sección actual (por ejemplo, desde la URL)


if (isset($_POST['usuario']) && isset($_POST['contra']) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    include_once ROOT_DIR."pdo/conexion.php";
    include_once ROOT_DIR."controllers/cifrado.php";
    include_once ROOT_DIR."controllers/Host.php";
    global $base_de_datos;
    $user = $_POST['usuario'];
    $pass = $_POST['contra'];
    $mesa=$_REQUEST["mesaid"];
// Consulta segura


    $sentencia = $base_de_datos->prepare("SELECT contrasena FROM mesa WHERE usuario =:usuario and id =:id");
    $sentencia->bindParam(':usuario', $user);
    $sentencia->bindParam(':id', $mesa);
    try {
        $sentencia->execute();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }


    if ($sentencia->rowCount() === 1) {
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        if (password_verify($pass, $resultado[0]->contrasena)) {

            header('Location: panel.php?section=salon');
        } else {
            $incorrecta="¡Contraseña incorrecta!";
        }
    } else {
        $incorrecta="¡Usuario no encontrado!";
    }
}