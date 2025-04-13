<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 05/09/2021
 * Time: 1:04
 */



include_once "../pdo/conexion.php";

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
    $nroMesa=4;
    $sentencia->bindParam(':nroMesa',$nroMesa);
    $d=0;
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
window.location=('../templates/dashboard.php');

</script>";
}



   // header("Location: ../template/bebidasc.php");
}




?>