<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
include_once ROOT_DIR."controllers/error_handler.php";
global $base_de_datos;
$sentencia = $base_de_datos->query("select * from mesa where disponible=0");
$mesas = $sentencia->fetchAll(PDO::FETCH_OBJ);
$callwaiter=array();
$primerIdMesa=-1;
$html="
<style>
   .tabs {
    display: flex;
    cursor: pointer;
            color: var(--sidebar-link-color);
            padding: 10px;
        }

        .tab {
    margin-right: 15px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .tab.active {
            background: var(--main-header-bg);
            color: var(--sidebar-link-active-color);
            border: var(--gray-tint-50) 1px dashed;
        }

        .tab:hover {
            color: var(--sidebar-link-active-color);
        }
</style>
";
if (!$mesas) {
    #No existe
    $html.= "<script type='text/javascript'>
                          idmesa='$primerIdMesa';
                    </script>";
    $html.=  "<h1>No existen pedidos en el salon !</h1>";
    echo $html;
   exit();
}else{
    $i=1;
    $html.=  "<div class='tabs'> ";
 foreach($mesas as $mesa){
    $nro_mesa= $mesa->id;

    $result = $base_de_datos->prepare("SELECT * FROM waiter_calls WHERE mesaid =:idmesa and status='pendiente' ORDER BY created_at DESC limit 1");
    $result->bindParam(':idmesa', $nro_mesa);
    $result->execute();
    $llamada = $result->fetchAll(PDO::FETCH_OBJ);



     if ($i==1){
         $html.=   "<div class='tab active' id='mesa$nro_mesa' onclick=iniciarAutoCargaPedidoMesa('$nro_mesa',this)>Mesa $nro_mesa";
               if (!empty($llamada)) {
                  $callwaiter[$nro_mesa]=$nro_mesa;
                   $html.=   "<span id='span$nro_mesa'>🛎️</span>";
                }
         $primerIdMesa=$nro_mesa;
         $html.=   "<input type='hidden' name='idmesap' id='idmesap' value='$primerIdMesa'> </div>";
     }else{
         $html.=   "<div class='tab' id='mesa$nro_mesa' onclick=iniciarAutoCargaPedidoMesa('$nro_mesa',this)>Mesa $nro_mesa";
               if (!empty($llamada)) {
                  $callwaiter[$nro_mesa]=$nro_mesa;
                $html.=   "<span id='span$nro_mesa'>🛎️</span>";
                }
     $html.=   "  </div>";

     }

     $i++;  }
    $html.=  "</div> ";
}
$data=json_encode($callwaiter);
$html.=   "<script type='text/javascript'>
    var callwaiter=$data;
       idmesa=$primerIdMesa;
</script>";

echo $html;

?>



