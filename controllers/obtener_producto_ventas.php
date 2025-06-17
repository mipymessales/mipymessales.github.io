<?php
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
global $base_de_datos;
$pedidos = [];
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {

    try {

        // $i = 0;

  $array=["alimentos","bebidas","carnicos","embutidos","confituras","condimentos"];
  global $base_de_datos;
        $idrestaurant=empty($_POST['idrestaurant'])?$_SESSION['idrestaurant']:$_POST['idrestaurant'];

        foreach($array as $categoria) {

            if (empty($categoria)) {
                continue;
            }
                $stmt = $base_de_datos->prepare("SELECT id as idplato,nombre,precioventa,preciotransferencia,cantidad as cantidadproducto FROM " . $categoria . " WHERE restaurantid=:id ");
                $stmt->bindParam(':id',$idrestaurant);
                $stmt->execute();
               // $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


                $row['categoria'] = $categoria;
                $row['cantidad'] = 0;
                $row['subtotal']=0;
                $row['tipoSeleccionado']= "Efectivo";
                    $pedidos[] =
                        $row;

                   // $i += 1;
                }



        }
        header("Content-Type: application/json");
       echo json_encode($pedidos);



       // $stmt->close();
       // $base_de_datos->close();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

} else {
    die();
}

?>

