<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,1).'/');
include_once ROOT_DIR."controllers/cifrado.php";
include_once ROOT_DIR."controllers/Host.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
$path_info = $_SERVER['PATH_INFO'] ?? null;
$path_info = ltrim($path_info, '/');
if (empty($path_info)){
    $uri = $_SERVER['REQUEST_URI'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $path_info = str_replace($script_name, '', $uri);

    // Asegurarse de que esté limpio de posibles barras al inicio
    $path_info = ltrim($path_info, '/');
}

$url=cifrado::descifrar_token(htmlspecialchars($path_info),cifrado::getClaveSecreta());
$segments = explode('/', $url);

$nroMesa=$segments[0];
$usuario_generado=$segments[1];
$contrasena_generada=$segments[2];

if (isset($usuario_generado) && isset($contrasena_generada) && isset($nroMesa)
    && !SqlInjectionUtils::checkSqlInjectionAttempt($usuario_generado) && !SqlInjectionUtils::checkSqlInjectionAttempt($contrasena_generada)
    && !SqlInjectionUtils::checkSqlInjectionAttempt($nroMesa)){
    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;


    $sentencia = $base_de_datos->prepare("SELECT user,password FROM mesa WHERE id =:idmesa and disponible= 1");
    $sentencia->bindParam(':idmesa', $nroMesa);

    try {
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        if (!empty($resultado) && hash_equals($usuario_generado, $resultado[0]->user) && hash_equals($contrasena_generada, $resultado[0]->password)){

                $sentencia = $base_de_datos->prepare("INSERT INTO cliente (full_name, id_mesa) VALUES ('Cliente', :id_mesa)");
                $sentencia->bindParam(':id_mesa', $nroMesa);

                try{
                    $sentencia->execute();
                    $idcliente=$base_de_datos->lastInsertId();
                    $stm = $base_de_datos->prepare("UPDATE mesa set disponible=0 WHERE id=:idmesa");
                    $stm->bindParam(':idmesa', $nroMesa);
                    $stm->execute();




// Redirigir a la URL limpia
                    header('Location: ' . Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$nroMesa/$idcliente",cifrado::getClaveSecreta()));


                    //header("Location: templates/cartacliente.php?mesa=".$idmesa."&idcliente=".$idcliente);
                }catch (Exception $e){
                    echo  print_r($e->getTraceAsString());
                    header("Location:" .   Host::getHOSTNAME(). "templates/404.php");
                }


        }else{
            header("Location:" .Host::getHOSTNAME(). "templates/404.php");
        }

    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
        header("Location:".   Host::getHOSTNAME()."templates/404.php");
    }

}else{
    die();
}