<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 05/09/2021
 * Time: 1:04
 */



defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR."pdo/conexion.php";
if (isset($_POST["agregarmesa"]) ) {
global $base_de_datos;

   /* $sentencia1 = $base_de_datos->query("select max(id) as nro_mesa from mesa");
    $entrantes1 = $sentencia1->fetch(PDO::FETCH_OBJ);
    $ad=$entrantes1->nro_mesa;
     $nroMesa=1;
    if ($ad>0) {
        $nroMesa=$nroMesa+ $ad;
    }*/
    $sentencia = $base_de_datos->prepare("INSERT INTO mesa (capacidad, disponible) VALUES (:nroMesa, :tiene_clientes)");
    $nroMesa=$_POST["cantidad"];
    $sentencia->bindParam(':nroMesa',$nroMesa);
    $d=1;
    $sentencia->bindParam(':tiene_clientes',$d,PDO::PARAM_BOOL);
    $sentencia->execute();
    if (!$sentencia) {
        #No existe
        echo "¡Error al agregar la mesa!";
        //  exit();
    }else{

        // $_SESSION["user"] = $userlogueado;
        # Redireccionar a la lista

        echo "<script type='text/javascript'>
window.location=('../panel.php');

</script>";
}



   // header("Location: ../template/bebidasc.php");
}
if (isset($_POST["mesa"]) ) {
    $id_mesa = $_POST['mesa'];
    $sentencia = $base_de_datos->prepare("UPDATE mesa	SET disponible=0 WHERE id=:id_c");
    $sentencia->bindParam(':id_c',$id_mesa);
    $sentencia->execute();
    if (!$sentencia) {
        echo "¡La mesa no esta disponible en estos momentos !";
    }else{
        echo "¡Se agregaron clientes a la mesa  !";
    }

}
if (isset($_POST["delete_mesa"]) ) {
    $id_mesa = $_POST['delete_mesa'];
    $sentencia = $base_de_datos->prepare("DELETE FROM mesa WHERE id=:id_mesa");
    $sentencia->bindParam(':id_mesa',$id_mesa);
    $sentencia->execute();
    if (!$sentencia) {
        echo "¡La mesa no esta disponible en estos momentos !";
    }else{
        echo "¡Se eliminó la mesa  !";
    }

}





?>