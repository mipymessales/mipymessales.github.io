
<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 26/08/2021
 * Time: 10:21
 */
global $_SESSION;
/*$basePath = realpath(dirname(__FILE__),"");
echo $_SERVER['REQUEST_URI']*/
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";

global $base_de_datos;
$sentencia = $base_de_datos->query("select id as nro_mesa from mesa");
$mesas = $sentencia->fetchAll(PDO::FETCH_OBJ);
?>

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-intro-title">Mesas del salon</h4>
                    <div id="external-events" class="my-3">
                       <!-- <p>Mesas del salon</p>-->
                        <?php foreach($mesas as $mesa){    $nro_mesa= $mesa->nro_mesa; ?>
                        <div class="external-event text-dark fc-event" data-class="bg-primary" data-event='{"title":"Mesa <?php echo $nro_mesa; ?>","id":"<?php echo $nro_mesa; ?>"}'><i class="fa fa-move"></i>Mesa <?php echo $nro_mesa; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div id="calendar" class="app-fullcalendar"></div>
                </div>
            </div>
        </div>
    </div>

