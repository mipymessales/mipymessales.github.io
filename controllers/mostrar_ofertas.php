<?php

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR . 'controllers/class.SqlInjectionUtils.php';

if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    require_once ROOT_DIR . 'pdo/conexion.php';
    global $base_de_datos;
    $idrestaurant = $_POST['idrestaurant'];
    $html='';
    if (isset($_POST['categoria']))
        $categoria = $_POST['categoria'];
    else {
        if ($idrestaurant == 1) {
            $categoria = 'alimentos';
        } else
            $categoria = 'entrantes';
    }
    $data=[];


                        $sentencia = $base_de_datos->query('select * from '.$categoria.' where restaurantid = '.$idrestaurant.' and disponible=1 and cantidad >0;');
                        //$sentencia->bindParam(':restid', $id);
                        $pedidosList = $sentencia->fetchAll(PDO::FETCH_OBJ);

                        if($pedidosList!=null){



                            $html.="  <div class='tab-pane fade show ' id='{$categoria}' role='tabpanel'>";

                              $html.="  <div class='row'>
                                    <div class='col-xl-12'>
                                        <div class='top_menu_widget'>
                                            <div class='card-body'>
                                                <div class='row'>";
                                                     $j=0; foreach($pedidosList as $listaItem){
                                                        $data[]=$listaItem;
                                                        $idproducto=$listaItem->id;
                                                        $foto=$listaItem->foto;
                                                        $nombre=$listaItem->nombre;
                                                        $precio=$listaItem->precioventa;
                                                        $valoracion=$listaItem->valoracion;


                                                            if($j<4){
                                                                $html.="   <div class='col-lg-3 col-sm-6'>
                                                                <div class='card border-0'>
                                                                    <div class='image-wrapper text-center mb-2'>";

                                                                         if($foto!=null){
                                                                             $html.="    <img class='img-fluid rounded-circle' src='/images/{$foto}' style='height: 120px;width: 120px; object-fit: cover !important'>";
                                                                         }else{
                                                                          $html.="    <img class='img-fluid rounded-circle' src='/images/blank1.jpg' alt='food menu' style='height: 120px;width: 120px; object-fit: cover !important'>";
                                                                         }
                                                                $html.="</div>
                                                                    <div class='card-body'>
                                                                        <div class='producto text-center px-3' data-nombre='{$nombre}' data-precio= '{$precio}' data-id= '{$idproducto}' data-cat='{$categoria}'>
                                                                            <h4>  {$nombre} </h4>";
                                                                              if(($valoracion)==1){
                                                                                  $html.="  <span class='icon'>★</span>";
                                                                             }

                                                                              if(($valoracion)==2){
                                                                               $html.="  <span class='icon'>★★</span>";
                                                                             }

                                                                              if(($valoracion)==3){
                                                                                  $html.="   <span class='icon'>★★★</span>";
                                                                             }

                                                                              if(($valoracion)==4){
                                                                               $html.="  <span class='icon'>★★★★</span>";
                                                                             }

                                                                              if(($valoracion)==5 || empty($valoracion)){
                                                                                  $html.="   <span class='icon'>★★★★★</span>";
                                                                             }
                                                                            $html.=" <h4 class='text-primary' style='margin-top: 5px'>$ {$precio} </h4>
                                                                            <button class='agregar btn btn-success'>+</button>
                                                                            <span class='cantidad'>0</span>
                                                                            <button class='quitar btn btn-danger'>−</button>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>";
                                                         }else{
                                                                $html.="<div class='col-lg-3 col-sm-6'>
                                                                <div class='card border-0'>
                                                                    <div class='card-body'>
                                                                        <div class='producto text-center px-3' data-nombre='{$nombre}' data-precio= '{$precio}' data-id= '{$idproducto}' data-cat='{$categoria}'>
                                                                            <h4> {$nombre} </h4>";
                                                                              if(($valoracion)==1){
                                                                                  $html.=" <span class='icon'>★</span>";
                                                                             }

                                                                              if(($valoracion)==2){
                                                                              $html.="  <span class='icon'>★★</span>";
                                                                             }

                                                                              if(($valoracion)==3){
                                                                                  $html.="   <span class='icon'>★★★</span>";
                                                                             }

                                                                              if(($valoracion)==4){
                                                                              $html.="  <span class='icon'>★★★★</span>";
                                                                             }

                                                                              if(($valoracion)==5 || empty($valoracion)){
                                                                                  $html.="  <span class='icon'>★★★★★</span>";
                                                                             }
                                                                            $html.="<h4 class='text-primary' style='margin-top: 5px'>$ {$precio} pesos </h4>
                                                                            <button class='agregar btn btn-success'>+</button>
                                                                            <span class='cantidad'>0</span>
                                                                            <button class='quitar btn btn-danger'>−</button>


                                                                        </div>
                                                                    </div>
                                                                    <div class='image-wrapper text-center'>";
                                                                         if($foto!=null){
                                                                             $html.="<img class='img-fluid rounded-circle' src='/images/{$foto}' style='height: 120px;width: 120px; object-fit: cover !important'>";
                                                                         }else{
                                                                          $html.="  <img class='img-fluid rounded-circle' src='/images/blank1.jpg' alt='food menu' style='height: 120px;width: 120px; object-fit: cover !important'>";
                                                                         }
                                                                $html.="  </div>
                                                                </div>
                                                            </div>";
                                                         }
                                                         $j++;
                                                        if($j==8) $j=0;

                                                    }
                                               $html.=" </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }else{
                            $html.=" <h5 id='section-title' class='section-title' style='font-size: 10px;'>En estos momentos no tenemos  {$categoria}  disponibles!</h5>";
                        }

    $html.="  </div>";


                      $html.="  </div>
                    </div>
               ";
    

    //echo $html;
    echo json_encode(["status" => "success","html" =>$html,"data" =>$data]);
    
}



