<?php

include_once '../pdo/conexion.php';

global $base_de_datos;

$categoria = $_POST['categoria'] ?? '';

$stmt = $base_de_datos->prepare('SELECT id,nombre,ingredientes,tipo,precio,disponible,valoracion,foto FROM '. $categoria .' ');
//$stmt->bind_param('s', $categoria);
$stmt->execute();
$resultado = $stmt->get_result();
$a="";
if ($resultado->num_rows > 0) {
   /* echo '<ul>';
    while ($fila = $resultado->fetch_assoc()) {
        echo '<li>' . htmlspecialchars($fila['nombre']) . ' - $' . number_format($fila['precio'], 2) . '</li>';
    }
    echo '</ul>';*/

    while ($fila = $resultado->fetch_assoc()) {
        $id_bebida= $fila['id'];
        $disponible= $fila['disponible'];
        $foto= $fila['foto'];
        $nombre= $fila['nombre'];
        $ingredientes= $fila['ingredientes'];
        $tipo= $fila['tipo'];
        $precio= $fila['precio'];
        $valoracion= $fila['valoracion'];
        $a.= "
    <div class='col-sm-6'>
            <div class='card vertical-card__menu'>";


               if($disponible){
                   $a.=  "<span class='ribbon ribbon__three_disp vertical-card__menu--status'>Disponible <em
                        class='ribbon-curve'></em></span>";
               }else{
                 $a.=   "<span class='ribbon ribbon__three vertical-card__menu--status'>Agotado <em
                            class='ribbon-curve'></em></span>";
                }


                if($foto!=null){
                   $a.= "<div class='card-header p-0'>
                        <div class='vertical-card__menu--image'>";

                        

                            $target_file=str_replace(''\'','/', $_SERVER['DOCUMENT_ROOT']).'/RestaurantDashboard/main/images/'.$foto;
                            if (file_exists($target_file)) {
                                $a.= " <img src=' ../images/". $foto." ' alt='No hay fotos'> ";
                              }else{
                                $a.= " <img src='../images/blank1.jpg'  alt='Menu'>";
                                }  


                        $a.= "</div> ";
                    $a.= "</div>";
                }else{
                    $a.= "  <div class='card-header p-0'> ";
                    $a.= " <div class='vertical-card__menu--image'>
                            <img src='../images/blank1.jpg'  alt='Menu'>
                        </div>
                    </div> ";
                }





    $a.= "   <div class='card-body'>
                    <div class='vertical-card__menu--desc'>
                        <div class='d-flex justify-content-between'>
                            <h5 class='vertical-card__menu--title'> ". $nombre .".</h5>";

                            $a.= "<div class='vertical-card__menu--fav'>
                                <a href='javascript:void()'><i class='fa fa-heart-o'></i></a>
                            </div>
                        </div> ";
                       $a.= " <p>". $ingredientes."  </p>";

                      $a.= "   <div class='d-flex justify-content-between align-items-center'>
                            <h2 class='vertical-card__menu--price'>$<span>". $precio."   cup</span></h2>";


                          if(($valoracion)>0){
                              $a.= "   <div class='vertical-card__menu--rating c-pointer'>";



                           if(($valoracion)==1){ 
                             $a.= "    <span class='icon'><i class='fa fa-star'></i></span>";
                                }

                               if(($valoracion)==2){
                                   $a.= "   <span class='icon'><i class='fa fa-star'></i></span>
                                 <span class='icon'><i class='fa fa-star'></i></span>";
                              }

                                 if(($valoracion)==3){ 
                                   $a.= "      <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>";
                                }

                                 if(($valoracion)==4){
                                     $a.= "       <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>";
                              }

                                 if(($valoracion)==5){ 
                                  $a.= "       <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star'></i></span>
                                        <span class='icon'><i class='fa fa-star-o'></i></span>";
                                }

                              $a.= "  </div>";
                            } 
                           $a.= "  </div>";
                      $a.= "  </div>";
                   $a.= "  </div>";
                $a.= "   <div class='card-footer d-flex justify-content-between align-items-center'>
    
                    <div class='button-group'>
                        <div class='btn-group-vertical'>

                                <button data-toggle='modal' data-target='#exampleModalEDIT". $id_bebida."' class='btn btn-warning text-white' style='margin: 4px'>Editar</button>


                                <button data-toggle='modal' data-target='#exampleModalDELETE". $id_bebida."' class='btn btn-danger text-white' style='margin: 4px'>Eliminar</button>";

                          $a.= "  </div>";


                     $a.= "  </div>";



                   $a.= "  </div>";
               $a.= "  </div>";

         $a.= "  </div>";
    
    
      }
    
} else {
    $a.= 'No hay elementos en esta categoría.';
}
echo $a;

$stmt->close();
$base_de_datos->close();

