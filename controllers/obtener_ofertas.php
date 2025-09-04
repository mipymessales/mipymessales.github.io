<?php

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";

if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    require_once ROOT_DIR . "pdo/conexion.php";
    global $base_de_datos;
    global $availableIds;
    global $availableCombos;
    $idrestaurant = $_POST['idrestaurant'];
    if (isset($_POST['categoria']))
        $categoria = $_POST['categoria'];
    else {
        if (in_array($idrestaurant,$availableIds)) {
            $categoria = 'alimentos';
        } else
            $categoria = 'entrantes';
    }
    if (in_array($idrestaurant,$availableCombos) && isset($_POST['categoria']) && hash_equals($categoria,"combos")){
        $stmt = $base_de_datos->prepare("SELECT id,nombre,productos,descripcion,disponible,stock,descuento,monto_total,monto_descuento,valoracion,foto,expira FROM combos WHERE restaurantid= " . $idrestaurant . "; ");
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo print_r($e->getTraceAsString());
        }
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);;
        $a = "<div class='row'>";
        if (!empty($resultado) && isset($resultado)) {
            foreach ($resultado as $fila) {
                $id_bebida = $fila->id;
                $disponible = $fila->disponible;
                $foto = $fila->foto;
                $nombre = $fila->nombre;
                $descripcion = $fila->descripcion;
                $monto_total = $fila->monto_total;
                $monto_descuento = $fila->monto_descuento;
                $descuento = $fila->descuento;
                $cantidad = $fila->stock;
                $expira = $fila->expira;
                $ganancia=$monto_total-$monto_descuento;
                $valoracion = $fila->valoracion;

                $a .= "

     <div class='col-xl-3 col-sm-6 col-xxl-6'>
            <div class='card vertical-card__menu' style=' border: 1px solid #aca9a9 !important;'>";


                if ($disponible && $cantidad > 0) {
                    $a .= "<span class='ribbon ribbon__three_disp vertical-card__menu--status'>Disponible <em
                        class='ribbon-curve'></em></span>";
                } else {
                    $a .= "<span class='ribbon ribbon__three vertical-card__menu--status'>Agotado <em
                            class='ribbon-curve'></em></span>";
                }


                if ($foto != null) {
                    $a .= "<div class='card-header p-0'>
                        <div class='vertical-card__menu--image'>
                         <span class='ribbon ribbon__two vertical-card__menu--offer'>{$descuento}%</span>
                        ";


                    $target_file = str_replace("'\'", "/", $_SERVER['DOCUMENT_ROOT']) . "/images/" . $foto;
                    if (file_exists($target_file)) {
                        $a .= " <img src='/images/" . $foto . " ' style='height: 250px;' alt='No hay fotos' > ";//width: 259px;padding: 10px;

                    } else {
                        $a .= " <img src='/images/blank1.jpg'  style='height: 250px;'  alt=''>";
                    }


                    $a .= "</div> ";
                    $a .= "</div>";
                } else {
                    $a .= "  <div class='card-header p-0'> ";
                    $a .= " <div class='vertical-card__menu--image'>
                    <span class='ribbon ribbon__two vertical-card__menu--offer'>{$descuento}%</span>
                            <img src='/images/blank1.jpg'  style='height: 250px;'  alt=''>
                        </div>
                    </div> ";
                }

                if ($disponible && $cantidad > 0) {
                    $a .= "   <div class='card-body' >";
                } else {
                    $a .= "   <div class='card-body' style='background: #fa5c7c;' >";
                }


                $a .= "
                    <div class='vertical-card__menu--desc'>
                        <div class='d-flex justify-content-between'>
                            <h4 class='vertical-card__menu--title' style='color: black'> " . ucfirst(strtolower($nombre)) . ".</h4>";

                if (($valoracion) > 0) {
                    $a .= "   <div class='vertical-card__menu--rating c-pointer'>";


                    if (($valoracion) == 1) {
                        $a .= "       <span class='icon'>★</span>
                                        ";
                    }

                    if (($valoracion) == 2) {
                        $a .= "       <span class='icon'>★★</span>
                                        ";
                    }

                    if (($valoracion) == 3) {
                        $a .= "       <span class='icon'>★★★</span>
                                        ";
                    }

                    if (($valoracion) == 4) {
                        $a .= "       <span class='icon'>★★★★</span>
                                        ";
                    }

                    if (($valoracion) == 5) {
                        $a .= "       <span class='icon'>★★★★★</span>
                                        ";
                    }

                    $a .= "  </div>";
                }
                $a .= "
                        </div> ";

                $listadoProductos = json_decode($fila->productos, true);
                if (is_array($listadoProductos)) {
                    $a .= " <div class='card-footer'> ";
                    foreach ($listadoProductos as $itemArray) {
                        foreach ($itemArray as $item) {
                            $name=$item["nombre"];
                            $cantidad=$item["cantidad"];
                            $precio=$item["precio"];
                            $a .= "
                            <p class='mb-2' style='color: black'><strong> $name x " . $cantidad ." </strong><span> a $precio pesos</span></p>";
                        }
                    }
                    $a .= " </div>";
                }


                $a .= " <p class='mb-2' style='color: black'><strong>Cantidad: </strong>" . $cantidad . "  </p>";
                $a .= " <p class='mb-2' style='color: black'><strong>Descripcion: </strong>" . $descripcion . "  </p>";

                $a .= " 
                            <p class='mb-2' style='color: black'><strong>Precio total:</strong><span> $" . $monto_total . "   cup</span></p>
                             <p class='mb-2' style='color: black' ><strong>Descuento:</strong><span> $" . $descuento . "   %</span></p>
        <p class='mb-2' style='color: black' ><strong>Precio con descuento:</strong><span> $" . $monto_descuento . "   cup</span></p>";


                // $a.= "  </div>";
                $a .= "  </div>";
                $a .= "  </div>";
                $a .= "   <div class='card-footer d-flex justify-content-between align-items-center'>
    
                    <div class='button-group'>
                        <div class='btn-group-vertical'>
                                <button data-toggle='modal' data-target='#exampleModalComboDELETE" . $id_bebida . "' class='btn btn-danger text-white' style='margin: 4px'>Eliminar</button>";

                $a .= "  </div>";


                $a .= "  </div>";


                $a .= "  </div>";
                $a .= "  </div>";

                $a .= "  </div>";

             /*   include ROOT_DIR."controllers/modal_editarcombo.php";*/
                include ROOT_DIR."controllers/modal_eliminarcombo.php";
            }
        }else {
                $a .= "<div class='col-sm-12 mb-2'><p style='font-weight: 700;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: .5rem;'><span> ⚠️ </span>No hay elementos en esta categoría.</p></div>";
            }
            $a .= "  </div>";
       echo $a;
        exit;
    }










    $stmt = $base_de_datos->prepare("SELECT id,nombre,ingredientes,tipo,precioventa,disponible,preciocompra,preciotransferencia,valoracion,cantidad,expira,foto FROM " . $categoria . " WHERE restaurantid= " . $idrestaurant . "; ");
//$stmt->bind_param('s', $categoria);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

    $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);;
    $a = "<div class='row'>";
    if (!empty($resultado) && isset($resultado)) {
        /* echo '<ul>';
         while ($fila = $resultado->fetch_assoc()) {
             echo '<li>' . htmlspecialchars($fila['nombre']) . ' - $' . number_format($fila['precio'], 2) . '</li>';
         }
         echo '</ul>';*/

        foreach ($resultado as $fila) {
            $id_bebida = $fila->id;
            $disponible = $fila->disponible;
            $foto = $fila->foto;
            $nombre = $fila->nombre;
            $ingredientes = $fila->ingredientes;
            $tipo = $fila->tipo;
            if (in_array($idrestaurant,$availableIds)) {
                $precioventa = $fila->precioventa;
                $preciocompra = $fila->preciocompra;
                $preciotransferencia = $fila->preciotransferencia;
                $cantidad = $fila->cantidad;
                $expira = $fila->expira;
            } else {
                $precio = $fila->precio;
            }

            $valoracion = $fila->valoracion;
            $a .= "

     <div class='col-xl-3 col-sm-6 col-xxl-6'>
            <div class='card vertical-card__menu' style=' border: 1px solid #aca9a9 !important;'>";


            if ($disponible && $cantidad > 0) {
                $a .= "<span class='ribbon ribbon__three_disp vertical-card__menu--status'>Disponible <em
                        class='ribbon-curve'></em></span>";
            } else {
                $a .= "<span class='ribbon ribbon__three vertical-card__menu--status'>Agotado <em
                            class='ribbon-curve'></em></span>";
            }


            if ($foto != null) {
                $a .= "<div class='card-header p-0'>
                        <div class='vertical-card__menu--image'>";


                $target_file = str_replace("'\'", "/", $_SERVER['DOCUMENT_ROOT']) . "/images/" . $foto;
                if (file_exists($target_file)) {
                    $a .= " <img src='/images/" . $foto . " ' style='height: 250px;' alt='No hay fotos' > ";//width: 259px;padding: 10px;

                } else {
                    $a .= " <img src='/images/blank1.jpg'  style='height: 250px;'  alt=''>";
                }


                $a .= "</div> ";
                $a .= "</div>";
            } else {
                $a .= "  <div class='card-header p-0'> ";
                $a .= " <div class='vertical-card__menu--image'>
                            <img src='/images/blank1.jpg'  style='height: 250px;'  alt=''>
                        </div>
                    </div> ";
            }

            if ($disponible && $cantidad > 0) {
                $a .= "   <div class='card-body' >";
            } else {
                $a .= "   <div class='card-body' style='background: #fa5c7c;' >";
            }


            $a .= "
                    <div class='vertical-card__menu--desc'>
                        <div class='d-flex justify-content-between'>
                            <h4 class='vertical-card__menu--title' style='color: black'> " . ucfirst(strtolower($nombre)) . ".</h4>";
            if (($valoracion) > 0) {
                $a .= "   <div class='vertical-card__menu--rating c-pointer'>";


                if (($valoracion) == 1) {
                    $a .= "       <span class='icon'>★</span>
                                        ";
                }

                if (($valoracion) == 2) {
                    $a .= "       <span class='icon'>★★</span>
                                        ";
                }

                if (($valoracion) == 3) {
                    $a .= "       <span class='icon'>★★★</span>
                                        ";
                }

                if (($valoracion) == 4) {
                    $a .= "       <span class='icon'>★★★★</span>
                                        ";
                }

                if (($valoracion) == 5) {
                    $a .= "       <span class='icon'>★★★★★</span>
                                        ";
                }

                $a .= "  </div>";
            }
            $a .= "
                        </div> ";
            $a .= " <p class='mb-2' style='color: black'><strong>Cantidad: </strong>" . $cantidad . "  </p>";

            $a .= " 
                            <p class='mb-2' style='color: black'><strong>Precio de compra:</strong><span> $" . $preciocompra . "   cup</span></p>
                             <p class='mb-2' style='color: black' ><strong>Precio de venta:</strong><span> $" . $precioventa . "   cup</span></p>
        <p class='mb-2' style='color: black' ><strong>Precio de transferencia:</strong><span> $" . $preciotransferencia . "   cup</span></p>";


            // $a.= "  </div>";
            $a .= "  </div>";
            $a .= "  </div>";
            $a .= "   <div class='card-footer d-flex justify-content-between align-items-center'>
    
                    <div class='button-group'>
                        <div class='btn-group-vertical'>

                                <button data-toggle='modal' data-target='#exampleModalEDIT" . $id_bebida . "' class='btn btn-warning text-white' style='margin: 4px'>Editar</button>


                                <button data-toggle='modal' data-target='#exampleModalDELETE" . $id_bebida . "' class='btn btn-danger text-white' style='margin: 4px'>Eliminar</button>";

            $a .= "  </div>";


            $a .= "  </div>";


            $a .= "  </div>";
            $a .= "  </div>";

            $a .= "  </div>";

            include ROOT_DIR."controllers/modal_editarcategoria.php";
            include ROOT_DIR."controllers/modal_eliminarcategoria.php";
        }

    } else {
        $a .= "<div class='col-sm-12 mb-2'><p style='font-weight: 700;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: .5rem;'><span> ⚠️ </span>No hay elementos en esta categoría.</p></div>";
    }
    $a .= "  </div>";
    echo $a;
}

