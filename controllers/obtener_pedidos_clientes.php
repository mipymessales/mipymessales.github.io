<?php
session_start();
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
include_once ROOT_DIR."controllers/error_handler.php";
global $base_de_datos;
/*$sentencia = $base_de_datos->query("select * from cliente where restaurantid=: idre and estado_cuenta =:estado ;");*/
$idrestaurant=!isset($_POST['idrestaurant'])?$_SESSION['idrestaurant']:$_POST['idrestaurant'];
/*$sentencia->bindParam(':idre', $idrestaurant);
$estado=1;
$sentencia->bindParam(':estado', $estado);
$sentencia->execute();*/


$checkCliente = $base_de_datos->prepare("SELECT * FROM cliente WHERE restaurantid=:idres and estado_cuenta=1 ");
$checkCliente->bindParam(':idres', $idrestaurant);

$checkCliente->execute();
$mesas = $checkCliente->fetchAll(PDO::FETCH_OBJ);






$primerIdMesa=-1;
$html="
<style>
             .tabs {
    display: flex;
    cursor: pointer;
    /*background-color:  var(--gray-base);*/
            color: var(--sidebar-link-color);
            padding: 10px;
        }
           .tab {
    margin-right: 15px;
            padding: 10px;
           /* border: 1px solid #ccc;*/
        }
        .tab.active {
            background: var(--main-header-bg);
            color: var(--sidebar-link-active-color);
            border: var(--gray-tint-50) 1px dashed;
            width: fit-content;
        }

        .tab:hover {
            color: var(--sidebar-link-active-color);
            cursor: pointer;
        }
         @media (max-width: 580px) {
            .phone-view {
                flex: 0 0 auto !important;
                width: 30% !important;
            }
        }
       /* @media (min-width: 576px) {
          .col-sm-2 {
            flex: 0 0 auto;
            max-width: 30%;
          }*/
}
</style>
";
if (!$mesas) {
    #No existe
    $html.= "<script type='text/javascript'>
                          idcliente='$primerIdMesa';
                    </script>";
    $html.=  "<h1>No existen pedidos !</h1>";
    echo $html;
   exit();
}else{
    $i=1;
    $html.=  "<div class='custom-tab-4' id='tabstyle'>
        <div class='nav nav-tabs'><div class='row'> ";
 foreach($mesas as $mesa){
    $nro_mesa= $mesa->id;

     if ($i==1){
         $html.=  " <div class='col-xl-2 col-sm-2 col-xxl-2 phone-view' ><div class='tab active' id='mesa$nro_mesa' onclick=iniciarAutoCargaPedidoCliente('$nro_mesa',this)>Pedido de $mesa->full_name";
         $primerIdMesa=$nro_mesa;
         $html.=   "<input type='hidden' name='idmesap' id='idmesap' value='$primerIdMesa'> </div> </div>";
     }else{
         $html.=   "<div class='col-xl-2 col-sm-2 col-xxl-2 phone-view' ><div class='tab' id='mesa$nro_mesa' onclick=iniciarAutoCargaPedidoCliente('$nro_mesa',this)>Pedido de $mesa->full_name";

     $html.=   "  </div></div>";

     }

     $i++;
 }
    $html.=  "</div></div> </div>";
}
$html.=   "<script type='text/javascript'>
       idcliente=$primerIdMesa;
</script>";

echo $html;

?>



