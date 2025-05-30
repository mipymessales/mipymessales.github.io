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
?>



<!--<link href="assets/css/switchery.min.css" rel="stylesheet"/>-->

<div class="content-body">

    <div class="container-fluid">
        <div class="row justify-content-between mb-3">
            <div class="col-12 ">
                <h2 class="page-heading">Hola <?php if(isset($_SESSION["user"]))  echo $_SESSION["user"];     ?> ,Bienvenido!</h2>
                <p class="mb-0">Your restaurent admin template</p>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <h3 class="content-heading"><span>Mesas del salón</span></h3>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="qrModalCenter">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Escan&eacute;ame !</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="contenido">El QR no se pudo crear</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success btn-ft">Hecho!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">



            <?php
            defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
            include_once ROOT_DIR."pdo/conexion.php";

            global $base_de_datos;
            $sentencia = $base_de_datos->query("select disponible as tiene_cliente,id as nro_mesa,url_login from mesa");
            $mesas = $sentencia->fetchAll(PDO::FETCH_OBJ);
            if (!$mesas) {
                #No existe
                echo "¡No existe mesas en el salon !";
                //  exit();
            }else{
            ?>

            <?php foreach($mesas as $mesa){ ?>
            <div class="col-xl-3 col-lg-6 col-sm-6">

                <?php
                $b= $mesa->tiene_cliente;
                $nro_mesa= $mesa->nro_mesa;
                $urlpedidos= $mesa->url_login;
                if($b){
                ?>
                <div class="card bg-light">

                    <?php
                    }else{

                    // echo $nro_mesa;

                    $sentencia1 = $base_de_datos->prepare("SELECT cl.id,s.id_mesa,cl.lista_pedidos,cl.estado_cuenta,cl.monto_cuenta FROM cliente cl INNER JOIN salon s ON s.id_cliente = cl.id WHERE s.id_mesa = ?;");
                    $sentencia1->execute([$nro_mesa]);
                    $cliente = $sentencia1->fetch(PDO::FETCH_OBJ);

                    ?>
                    <!-- Verifique que tiene clientes, hay que ver el cleinte en que estado esta-->

                    <!--Pedidos-->
                    <?php

                    if($cliente!=null){

                    $estado_ped=$cliente->lista_pedidos;

                    // echo $estado_ped;
                    if($estado_ped){
                    ?>

                    <div class="card text-dark bg-warning">


                        <?php }else{ ?>

                        <!--    Cerrar Cuenta-->
                        <?php
                        $estado_cerrar_cuenta = $cliente->estado_cuenta;
                        // echo $estado_ped;
                        if ($estado_cerrar_cuenta){
                        ?>

                        <div class="card text-dark bg-danger">


                            <?php }else{ ?>


                            <div class="card text-dark bg-success">

                                <?php } ?>


                                <?php
                                }
                                }else{ ?>
                                <div class="card text-dark bg-success">

                                    <?php } ?>




                                    <?php } ?>
                                    <div class="card-body mb-0">
                                        <!-- <span class="ribbon ribbon__one vertical-card__menu--status">Available <em class="ribbon-curve"></em></span>-->
                                        <?php

                                        if($b){
                                            ?>
                                            <h5 class="card-title text-dark">
                                                Mesa <?php echo $nro_mesa; ?></h5>
                                            <!--  <p class="card-text">Some quick example text to build on the card title and
                                                  make up the bulk of the card's content.</p>-->
                                            <button  class="btn btn-ft rounded-0 btn-outline-secondary">Historial</button>
                                            <button onclick="agregarCliente(<?=$nro_mesa?>)" class="btn btn-success btn-ft">Agregar clientes</button>
                                        <?php }else { ?>
                                            <h5 class="card-title text-dark ">
                                                Mesa <?php echo $mesa->nro_mesa ?></h5>
                                            <!--<p class="card-text">Some quick example text to build on the card title and
                                                make up the bulk of the card's content.</p>-->





                                            <?php
                                            if($cliente!=null){

                                                if ($estado_ped) {

                                                    ?>

                                                    <button class="btn btn-ft rounded-0 btn-outline-warning">
                                                        Listar pedidos</button>


                                                <?php } else { ?>

                                                    <!--    Cerrar Cuenta-->
                                                    <?php

                                                    // echo $estado_ped;
                                                    if ($estado_cerrar_cuenta) {
                                                        ?>

                                                        <button class="btn btn-ft rounded-0 btn-outline-danger">
                                                            Ver cuenta</button>


                                                    <?php } else { ?>


                                                        <button class="btn btn-ft rounded-0 btn-outline-success">Observar pedidos</button>


                                                    <?php } ?>


                                                    <?php
                                                }

                                            }else{
                                                ?>

                                                <button class="btn btn-ft rounded-0 btn-outline-success" onclick="window.location.href='index.php?section=pedidos'">Listar pedidos</button>
                                             <button onclick="window.open('<?php echo $urlpedidos;?>', '_blank')" class="btn btn-outline-warning btn-ft">Agregar pedido</button>

                                            <?php } ?>

                                        <?php } ?>

                                    </div>

                                    <?php

                                    if($b){
                                    ?>

                                    <div class="card-footer bg-transparent border-0 text-dark">Esperando clientes...
                                        <?php }else{

                                        if($cliente!=null){
                                        if ($estado_ped){
                                        ?>

                                        <div class="card-footer bg-transparent border-0 text-dark">Ordenando pedidos...


                                            <?php }else{ ?>

                                            <!--    Cerrar Cuenta-->
                                        <?php

                                        // echo $estado_ped;
                                        if ($estado_cerrar_cuenta){
                                        ?>

                                            <div class="card-footer bg-transparent border-0 text-dark">Cerrando cuenta...


                                                <?php }else{ ?>


                                                <div class="card-footer bg-transparent border-0 text-dark">Observando ofertas...

                                                    <?php } ?>


                                                    <?php
                                                    }
                                                    }else{ ?>

                                                    <div class="card-footer bg-transparent border-0 text-dark">Con clientes...
                                                        <?php } ?>



                                                        <?php } ?>



                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php  }?>

                                        </div>













                                    </div>


                                </div>
                                <!--**********************************
                                    Content body end
                                ***********************************-->
                                <button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter" >+</button>

                                <!-- Modal  INSERT-->

                                <div class="form-validation">
                                    <form enctype="multipart/form-data" class="form-valide" action="/controllers/salonController.php" method="POST" id="main-contact-form">
                                        <div class="modal fade" id="exampleModalCenter">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Agregando nueva mesa ...</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <div class="card-header pb-0">

                                                                <div class="row">
                                                                    <div class="col-sm-6" style="align-content: center;">
                                                                        <h4 class="card-title">Desea agregar otra mesa?</h4>
                                                                        <h6>Selecciona cantidad de sillas</h6>
                                                                        <select id="selectorSillas">
                                                                            <option value="0">0</option>
                                                                            <!-- Opciones de 1 a 8 -->
                                                                            <script>
                                                                                for (let i = 1; i <= 12; i++) {
                                                                                    document.write(`<option value="${i}">${i}</option>`);
                                                                                }
                                                                            </script>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="mesa" id="mesa"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="agregarmesa">
                                                        <input type="hidden" name="cantidad" id="cantidad">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        <button  type="submit" class="btn btn-success">Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                <!--end modal insert-->

                                <!--end modal insert pedidos-->

                                <!--**********************************
                                    Right sidebar end
                                ***********************************-->
                            </div>
                            <!--**********************************
                                Main wrapper end
                            ***********************************-->

                            <!--**********************************
                                Scripts
                            ***********************************-->

                           <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

                            <script src="assets/js/switchery.min.js"></script>
                            <script src="assets/js/switchery-init.js"></script>
                            <script src="assets/js/jquery-ui.min.js"></script>-->




