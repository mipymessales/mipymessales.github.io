<?php

defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
global $base_de_datos;
$pedidos = array();
if (isset($_POST['mesa']) && isset($_POST['idcliente']) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    $nro_mesa = $_POST['mesa'];;
    $idcliente = $_POST['idcliente'];
    $sentencia1 = $base_de_datos->prepare("SELECT cl.id as idcliente,cl.id_mesa as idmesa,cl.monto_cuenta ,s.* FROM cliente cl LEFT JOIN pedidos s ON cl.id_mesa = s.id_mesa and cl.id = s.id_cliente WHERE cl.id_mesa = ? and cl.estado_cuenta=1 and cl.id=? order by s.fecha desc ");

    try {
        $sentencia1->execute([$nro_mesa, $idcliente]);
        //$cliente = $sentencia1->fetch(PDO::FETCH_ASSOC);
        $i = 0;
        while ($cliente = $sentencia1->fetch(PDO::FETCH_ASSOC)) {
            $categoria = $cliente['categoria'];
            $cantidad =intval($cliente['cantidad']);
            echo "<script type='text/javascript'>
     console.log({$cantidad});

</script>";
            if (empty($categoria)) {
                continue;
            }
                $stmt = $base_de_datos->prepare("SELECT id,nombre,ingredientes,tipo,precio,disponible,valoracion,foto FROM " . $categoria . " WHERE id=:id ");
                $stmt->bindParam(':id', $cliente['id_plato']);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
                if (!empty($resultado)) {
                    $resultado[] = $categoria;
                    $resultado[] = $cliente['estado'];
                    $resultado[0]->cantidad=$cantidad;
                    $resultado[0]->idpedidos=$cliente['id'];
                    $pedidos[$i] =
                        $resultado;

                    $i += 1;
                }



        }
        /*  foreach($this->fonts as $k=>$font)
       {

       }*/

    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }

} else {
    die();
}

?>
<style>
    .swiper-pagination-bullet {
        background-color: #000000; /* Color de puntos inactivos */
        opacity: 1;
    }

    .swiper-pagination-bullet-active {
        background-color: #ccc; /* Color del punto activo */
    }

</style>

<?php
$a = " ";
if (!empty($pedidos) && count($pedidos) > 0) {
    $a .= "<h3 class='content-heading'>Lista de pedidos</h3>";
    $a .= "<h5 class='align-content-center'>
  Los pedidos tendrán un margen de 2 minutos para su cancelación, despues de haber llegado este tiempo, su pedido llegara a la cocina
  donde será visto y aprobado para su preparación.</h5>";
    $a .= " <div class='swiper' style='  max-width: 100%;
        margin: auto;
        width: 100%;
        height: 250px;'>
  <div class='swiper-wrapper'>"; ?>

    <?php for ($i = 0; $i < count($pedidos); $i++) {
        $a .= "  <div class='swiper-slide' style=' background: white;
        border-radius: 12px;
        padding: 20px;
        width: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>"; ?>

        <?php
        $idcategoria = $pedidos[$i][0]->id;
        $idplato = $pedidos[$i][0]->id_plato;
        $categoriapedido = $pedidos[$i][1];
        $foto = $pedidos[$i][0]->foto;
        $estado = $pedidos[$i][2];
        $nombre = $pedidos[$i][0]->nombre;
        $ingredientes = $pedidos[$i][0]->ingredientes;
        $precio = $pedidos[$i][0]->precio;
        $valoracion = $pedidos[$i][0]->valoracion;
        $value = $pedidos[$i][0]->cantidad;
        $idpedidos= $pedidos[$i][0]->idpedidos;


        //id,nombre,ingredientes,tipo,precio,disponible,valoracion,foto


        $a .= "  <div class='card horizontal-card__menu mb-0 horizontal' style='border: none!important;box-shadow:none!important;'>"; ?>

        <?php if (hash_equals($estado, 'Enviado')) {
            $a .= "<span class='ribbon ribbon__oneestatus vertical-card__menu--status' style='background: #ffbc00;'>$estado<em class='ribbon-curve' style='  border-top: 11px solid #ffbc00;border-bottom: 10px solid #ffbc00; height: 5px !important;'></em></span>";
        } else {
            $a .= "  <span class='ribbon ribbon__one vertical-card__menu--status'>$estado<em class='ribbon-curve'></em></span>";
        } ?>

        <?php $a .= "<div class='horizontal-card__menu--image' style='flex-basis: auto;max-width: none;height: 150px;'>
 <img  src='images/$foto' alt=''>"; ?>
        <?php if (hash_equals($estado, 'Enviado')) {
            $a .= "<div class='btn btn-rest' id='btnrestpedido{$categoriapedido}{$idcategoria}{$idcliente}' style='position: relative;right: auto;padding: 10px 20px' onclick=deletePedido('$idcategoria','$nro_mesa','$idcliente','$estado','$value','$idpedidos')>-</div>
 <div class='btn btn-add' id='btnaddpedido{$categoriapedido}{$idcategoria}{$idcliente}' style='position: relative;right: auto;;padding: 10px 20px' onclick=incremnetPedido('$idcategoria','$nro_mesa','$idcliente','$estado','$categoriapedido','$idpedidos')>+</div>
";
        } else {
            $a .= "<div class='btn btn-rest' id='btnrestpedido{$categoriapedido}{$idcategoria}{$idcliente}' style='display: none'>-</div>
 <div class='btn btn-add' id='btnaddpedido{$categoriapedido}{$idcategoria}{$idcliente}' style='display: none'>+</div>
";
        } ?>

        <?php $a .= "</div>"; ?>
        <?php $a .= "  <div class='card-body'>

 <div class='d-flex justify-content-between'>
  <h4 class='horizontal-card__menu--title'>($value) $nombre</h4>
 
 </div>
 <p class='mb-2'>$ingredientes</p>
 <div class='d-flex justify-content-between align-items-center'>
  <h4 class='horizontal-card__menu--price'>$<span> $precio</span></h4>
  <div class='horizontal-card__menu--rating c-pointer'>";
        if ($valoracion == 1) {
            $a .= "<span class='icon'><i class='fa fa-star'></i></span>";
        } elseif ($valoracion == 2) {
            $a .= "<span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>";
        } elseif ($valoracion == 3) {
            $a .= "<span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>";
        } elseif ($valoracion == 4) {
            $a .= "  <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>";
        } elseif ($valoracion == 5) {
            $a .= " <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star'></i></span>
 <span class='icon'><i class='fa fa-star-o'></i></span>";
        }
        $a .= "</div>
 </div>";

        ?>


        <?php if (hash_equals($estado, 'Enviado')) { ?>
            <?php $a .= " <div class='horizontal-card__menu--footer d-flex justify-content-between align-items-center'>
  <div class='vertical-card__menu--button'>
<button class='btn btn-danger btn-cancelar' id='btn-{$categoriapedido}{$idcategoria}{$idcliente}' onclick=cancelarPedido('$categoriapedido','$idcategoria','$idcliente')>Cancelar pedido</button>

  </div>
 </div>
 <div id='{$categoriapedido}{$idcategoria}{$idcliente}'  class='btn' style='margin-left: 5px'></div>"; ?>
        <?php    } ?>



        <?php $a .= "</div>
  </div>


 </div>"; ?>
        <?php } ?>
    <?php
    $a .= " </div>

  <!-- Botones -->
  <div class='swiper-button-prev' style='color: #ccc'></div>
  <div class='swiper-button-next' style='color: #ccc'></div>

  <!-- Paginación -->
  <div class='swiper-pagination' style='color: black'></div>
 </div>";
}

echo "<script type='text/javascript'>
    new Swiper('.swiper', {
            loop: true,
            allowTouchMove: false,  // desactiva swipe
            watchOverflow: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                600: {
                    slidesPerView: 2
                },
                900: {
                    slidesPerView: 3
                }
            }
        });
        pedidos.forEach(mostrarPlato);
</script>";


echo $a;

//$stmt->close();
//$base_de_datos->close();

?>