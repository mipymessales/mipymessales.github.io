<?php
include '../controllers/auth_check.php';
require_once "../library/phpqrcode/qrlib.php";
if(isset($_POST['nro_tarjeta']) && isset($_POST['telefono'])){
    $trasnfermovil="TRANSFERMOVIL_ETECSA,TRANSFERENCIA,".$_POST['nro_tarjeta'].",".$_POST['telefono'];
    $ruta=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/transferencia_transfermovil.png";
    QRcode::png($trasnfermovil, $ruta, QR_ECLEVEL_L, 4);

    $srcImg="/images/transferencia_transfermovil.png";
    echo "<img style='height: 287px;'  alt='Menu' src='$srcImg' />";

    defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
    require_once ROOT_DIR."pdo/conexion.php";
       global $base_de_datos;
        $sentencia = $base_de_datos->prepare("UPDATE admin SET telefono=:telefono, nro_cuenta=:nro_cuenta WHERE usuario=:usuario");
        $sentencia->bindParam(':telefono', $_POST['telefono']);
        $sentencia->bindParam(':nro_cuenta', $_POST['nro_tarjeta']);
        $sentencia->bindParam(':usuario', $_SESSION['user']);
    try{
        $sentencia->execute();
        header('Location: ../index.php?section=configuracion');
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
    }


}
