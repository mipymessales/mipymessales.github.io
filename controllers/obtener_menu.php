<?php

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    require_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos,$availableIds;
    $idrestaurant = $_POST['idrestaurant'];
    if (isset($_POST['categoria']))
        $categoria = $_POST['categoria'];
    else {
        if (in_array($idrestaurant,$availableIds)) {
            $categoria = 'alimentos';
        } else
            $categoria = 'entrantes';
    }


    if (in_array($idrestaurant,$availableIds)) {
        $stmt = $base_de_datos->prepare("SELECT id,nombre,ingredientes,tipo,precioventa,disponible,preciocompra,preciotransferencia,valoracion,cantidad,expira,foto FROM " . $categoria . " WHERE restaurantid= " . $idrestaurant . "; ");
    } else {
        $stmt = $base_de_datos->prepare("SELECT id,nombre,ingredientes,tipo,precio,disponible,valoracion,foto FROM " . $categoria . " WHERE restaurantid= " . $idrestaurant . "; ");
    }

//$stmt->bind_param('s', $categoria);
    try {
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }


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

     <div class='col-xl-3 col-lg-6 col-sm-6'>
            <div class='card vertical-card__menu' style=' border: 1px solid #aca9a9 !important;'>";


            if ($disponible) {
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
                    $a .= " <img src='/images/" . $foto . " ' style='height: 287px;' alt='No hay fotos' > ";//width: 259px;padding: 10px;

                } else {
                    $a .= " <img src='/images/blank1.jpg'  style='height: 287px;'  alt=''>";
                }


                $a .= "</div> ";
                $a .= "</div>";
            } else {
                $a .= "  <div class='card-header p-0'> ";
                $a .= " <div class='vertical-card__menu--image'>
                            <img src='/images/blank1.jpg'  style='height: 287px;'  alt=''>
                        </div>
                    </div> ";
            }


            $a .= "   <div class='card-body'>
                    <div class='vertical-card__menu--desc'>
                        <div class='d-flex justify-content-between'>
                            <h5 class='vertical-card__menu--title'> " . $nombre . ".</h5>";

            $a .= "
                        </div> ";


            if (in_array($idrestaurant,$availableIds)) {
                $a .= "   <div class='mb-2'>
                            <p class='mb-2'>Cantidad:<span>" . $cantidad . "</span></p>";
                $a .= "   
                            <p class='mb-2'>Precio de compra: $<span>" . $preciocompra . "</span></p>";
                $a .= "   
                            <p class='mb-2'>Precio de venta: $<span>" . $precioventa . "</span></p>";
                $a .= "   
                            <p class='mb-2'>Precio por transferencia: $<span>" . $preciotransferencia . "</span></p>";
                $a .= " 
                            <p class='mb-2'>Expira: <span>" . $expira . "</span></p> </div>";

            } else {
                $a .= " <p class='mb-2'>" . $ingredientes . "  </p>";
                $a .= "   <div class='d-flex justify-content-between align-items-center'>
                            <h2 class='vertical-card__menu--price'>$<span>" . $precio . "   cup</span></h2>";

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
            }

            $a .= "  </div>";
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

            include "../controllers/modal_editarcategoria.php";
            include "../controllers/modal_eliminarcategoria.php";
        }

    } else {
        $a .= "<div class='col-xl-3 col-lg-6 col-sm-6'>No hay productos en esta categoría.</div>";
    }
    $a .= "  </div>";
    echo $a;
}
//$stmt->close();
//$base_de_datos->close();

