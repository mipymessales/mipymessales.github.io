<?php

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR . 'controllers/class.SqlInjectionUtils.php';

if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    require_once ROOT_DIR . 'pdo/conexion.php';
    global $base_de_datos,$availableIds;
    $idrestaurant = $_POST['idrestaurant'];
    $html='';
    if (isset($_POST['categoria']))
        $categoria = $_POST['categoria'];
    else {
        if (in_array($idrestaurant,$availableIds)) {
            $categoria = 'alimentos';
        } else
            $categoria = 'entrantes';
    }
    $data=[];

    if (hash_equals("combos",$categoria)){
        $sentencia = $base_de_datos->query('select * from '.$categoria.' where restaurantid = '.$idrestaurant.' and disponible=1 and stock >0;');
        //$sentencia->bindParam(':restid', $id);

        try {
            $pedidosList = $sentencia->fetchAll(PDO::FETCH_OBJ);
        }catch (Exception $e){
            echo  print_r($e->getTraceAsString());
        }
    }else {
        $sentencia = $base_de_datos->query('select * from '.$categoria.' where restaurantid = '.$idrestaurant.' and disponible=1 and cantidad >0;');
        //$sentencia->bindParam(':restid', $id);
        $pedidosList = $sentencia->fetchAll(PDO::FETCH_OBJ);
    }


                        if($pedidosList!=null){
                            if (hash_equals("combos",$categoria)){

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


                                    $descripcion=$listaItem->descripcion;
                                    $monto_total=$listaItem->monto_total;
                                    $precio=$listaItem->monto_descuento;
                                    $descuento=floor($listaItem->descuento);
                                    $valoracion=$listaItem->valoracion;
                                    $listadoProductos = json_decode($listaItem->productos, true);
                                    if($j<4){
                                        $html.="   <div class='col-lg-3 col-sm-6'>
                                                                <div class='card border-0'>
                                                                    <div class='image-wrapper text-center mb-2'>";

                                        if($foto!=null){
                                            $html.="<img class='img-fluid rounded-circle product-trigger' 
                                     src='/images/{$foto}' 
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";
                                        }else{
                                            $html.="<img class='img-fluid rounded-circle product-trigger' 
                                     src='/images/blank1.jpg' 
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";
                                        }
                                        $html.="</div>
                                                                    <div class='card-body'>                                                                      
                                                                        <div class='producto text-center px-3' data-nombre='{$nombre}' data-precio= '{$precio}' data-id= '{$idproducto}' data-cat='{$categoria}'>
                                                                            <h4>  {$nombre} </h4>";
                                        if ($descuento>0){
                                            $html.="<span class='ribbon ribbon__two vertical-card__menu--offer'>{$descuento}%</span>";
                                        }



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

                                        $html .= " <p class='section-title mb-2' style='font-size: 12px;color: black;
  letter-spacing: normal;'>" . $descripcion . "  </p>";



                                        if (is_array($listadoProductos)) {
                                            $html .= " <div style='text-align: left'> ";
                                            foreach ($listadoProductos as $itemArray) {
                                                foreach ($itemArray as $item) {
                                                    $name=$item["nombre"];
                                                    $cantidad=$item["cantidad"];
                                                    $preciop=$item["precio"];
                                                    $html .= "
                            <p class='mb-2' style='color: black'><strong> $name x " . $cantidad ." </strong><span>... $$preciop</span></p>";
                                                }
                                            }
                                            $html .= " </div>";
                                        }





                                        if ($descuento>0){
                                            $html .= " 
                            <p class='mb-2' style='color: #ff5722bf; text-decoration: line-through;'><span> $" . $monto_total . "   cup</span></p>";
                                        }else{
                                            $html .= " 
                            <p class='mb-2' style='color: #ff5722bf; text-decoration: line-through;'><br></p>";
                                        }


                                        $html.=" <h4 class='text-black' style='margin-top: 5px'>$ {$precio} cup </h4>
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
                                        if ($descuento>0){
                                            $html.="<span class='ribbon ribbon__two vertical-card__menu--offer'>{$descuento}%</span>";
                                        }

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

                                        $html .= " <p class='section-title mb-2' style='font-size: 12px;color: black;
  letter-spacing: normal;'>" . $descripcion . "  </p>";
                                        if (is_array($listadoProductos)) {
                                            $html .= " <div class='mb-2'> ";
                                            foreach ($listadoProductos as $itemArray) {
                                                foreach ($itemArray as $item) {
                                                    $name=$item["nombre"];
                                                    $cantidad=$item["cantidad"];
                                                    $preciop=$item["precio"];
                                                    $html .= "
                            <p class='mb-2' style='color: black'><strong> $name x " . $cantidad ." </strong><span>... $$preciop</span></p>";
                                                }
                                            }
                                            $html .= " </div>";
                                        }

                                        if ($descuento>0){
                                            $html .= " 
                            <p class='mb-2' style='color: #ff5722bf; text-decoration: line-through;'><span> $" . $monto_total . "   cup</span></p>";
                                        }else{
                                            $html .= " 
                            <p class='mb-2' style='color: #ff5722bf; text-decoration: line-through;'><br></p>";
                                        }





                                        $html.="<h4 class='text-black' style='margin-top: 5px'>$ {$precio} cup </h4>
                                                                            <button class='agregar btn btn-success'>+</button>
                                                                            <span class='cantidad'>0</span>
                                                                            <button class='quitar btn btn-danger'>−</button>


                                                                        </div>
                                                                    </div>
                                                                    <div class='image-wrapper text-center'>";
                                        if ($foto != null) {
                                            $html .= "<img class='img-fluid rounded-circle product-trigger' alt='food menu' 
                                     src='/images/{$foto}' 
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";


                                        } else {
                                            $html .= "<img class='img-fluid rounded-circle product-trigger' alt='food menu' 
                                     src='/images/blank1.jpg'  
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";
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
                                                                             $html.="<img class='img-fluid rounded-circle product-trigger' 
                                     src='/images/{$foto}' 
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";





                                                                         }else{
                                                                          $html.="<img class='img-fluid rounded-circle product-trigger' 
                                     src='/images/blank1.jpg' 
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";
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
                                                                            $html.=" <h4 class='text-black' style='margin-top: 5px'>$ {$precio} cup </h4>
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
                                                                            $html.="<h4 class='text-black' style='margin-top: 5px'>$ {$precio} cup </h4>
                                                                            <button class='agregar btn btn-success'>+</button>
                                                                            <span class='cantidad'>0</span>
                                                                            <button class='quitar btn btn-danger'>−</button>


                                                                        </div>
                                                                    </div>
                                                                    <div class='image-wrapper text-center'>";
                                                                if ($foto != null) {
                                                                    $html .= "<img class='img-fluid rounded-circle product-trigger' alt='food menu' 
                                     src='/images/{$foto}' 
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";


                                                                } else {
                                                                    $html .= "<img class='img-fluid rounded-circle product-trigger' alt='food menu' 
                                     src='/images/blank1.jpg'  
                                     data-id='{$idproducto}'
                                     data-nombre='{$nombre}'
                                     data-precio='{$precio}'
                                     data-foto='/images/{$foto}'
                                     data-cat='{$categoria}'
                                     data-valoracion='{$valoracion}'
                                     style='height: 120px;width: 120px; object-fit: cover !important; cursor:pointer'>";
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


                            }

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



