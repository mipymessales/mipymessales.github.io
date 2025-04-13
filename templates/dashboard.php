<!--<link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">-->
<!-- Custom Stylesheet -->

<link href="../assets/css/style.css" rel="stylesheet">
<link href="../assets/css/flotante.css" rel="stylesheet">
<link href="../assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- Dropify -->
<!--<link rel="stylesheet" href="../../assets/plugins/dropify/dist/css/dropify.min.css">-->

    <div class="container-fluid">
        <div class="row justify-content-between mb-3">
            <div class="col-12 ">
                <h2 class="page-heading">Hola <?php if(isset($_SESSION["user"]))  echo $_SESSION["user"];     ?> ,Bienvenido!</h2>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <h3 class="content-heading">Mesas del salón</h3>
            </div>
        </div>

        <div class="row">



            <?php
            include_once "../pdo/conexion.php";

            global $base_de_datos;
            $sentencia = $base_de_datos->query("select disponible as tiene_cliente,id as nro_mesa from mesa");
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
                if(!$b){
                ?>
                <div class="card bg-light">

                    <?php
                    }else{

                    // echo $nro_mesa;

                    $sentencia1 = $base_de_datos->prepare("SELECT cl.id,s.id as id_mesa,cl.lista_pedidos,cl.estado_cuenta,cl.monto_cuenta FROM cliente cl INNER JOIN mesa s ON s.id = cl.id_mesa WHERE s.id_mesa = ?;");
                    $sentencia1->execute([$nro_mesa]);
                    $cliente = $sentencia1->fetch(PDO::FETCH_OBJ);

                    ?>
                    <!-- Verifique que tiene clientes, hay que ver el cleinte en que estado esta-->

                    <!--Pedidos-->
                    <?php

                    if($cliente!=null){

                    $estado_ped=$cliente->estado_cuenta;

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

                                        if(!$b){
                                            ?>
                                            <h5 class="card-title text-dark">
                                                Mesa <?php echo $nro_mesa; ?></h5>
                                            <!--  <p class="card-text">Some quick example text to build on the card title and
                                                  make up the bulk of the card's content.</p>-->
                                            <a href="javascript:void()" class="btn btn-ft rounded-0 btn-outline-secondary">Historial</a>
                                            <a href="javascript:void()" onclick="agregarCliente(<?=$nro_mesa?>)" class="btn btn-success btn-ft">Agregar clientes</a>
                                        <?php }else { ?>
                                            <h5 class="card-title text-dark ">
                                                Mesa <?php echo $mesa->nro_mesa ?></h5>
                                            <!--<p class="card-text">Some quick example text to build on the card title and
                                                make up the bulk of the card's content.</p>-->





                                            <?php
                                            if($cliente!=null){

                                                if ($estado_ped) {

                                                    ?>

                                                    <a href="javascript:void()" class="btn btn-ft rounded-0 btn-outline-warning">
                                                        Listar pedidos</a>


                                                <?php } else { ?>

                                                    <!--    Cerrar Cuenta-->
                                                    <?php

                                                    // echo $estado_ped;
                                                    if ($estado_cerrar_cuenta) {
                                                        ?>

                                                        <a href="javascript:void()" class="btn btn-ft rounded-0 btn-outline-danger">
                                                            Ver cuenta</a>


                                                    <?php } else { ?>


                                                        <a href="javascript:void()" class="btn btn-ft rounded-0 btn-outline-success">Observar pedidos</a>


                                                    <?php } ?>


                                                    <?php
                                                }

                                            }else{
                                                ?>

                                                <a href="javascript:void()" class="btn btn-ft rounded-0 btn-outline-success">Listar</a>
                                                <a href="javascript:void()" onclick="agregarPedidos(<?=$nro_mesa?>)" class="btn btn-outline-warning btn-ft">Agregar</a>

                                            <?php } ?>

                                        <?php } ?>

                                    </div>

                                    <?php

                                    if(!$b){
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
                                    <form enctype="multipart/form-data" class="form-valide" action="../controllers/salonController.php" method="POST" id="main-contact-form">
                                        <div class="modal fade" id="exampleModalCenter">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Agregando nueva mesa ...</h5>
                                                        <h2>Selecciona cantidad de sillas</h2>
                                                        <select id="selectorSillas">
                                                            <option value="0">0</option>
                                                            <!-- Opciones de 1 a 8 -->
                                                            <script>
                                                                for (let i = 1; i <= 8; i++) {
                                                                    document.write(`<option value="${i}">${i}</option>`);
                                                                }
                                                            </script>
                                                        </select>

                                                        <div class="mesa" id="mesa"></div>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <div class="card-header pb-0">
                                                                <h4 class="card-title">Desea agregar otra mesa?</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="agregarmesa">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        <button  type="submit" class="btn btn-success">Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                <!--end modal insert-->


                                <?php $array=["entrantes","platos","postres","bebidas"]; ?>

                                <!-- Modal  Insertar Pedidos-->

                                <div class="form-validation">
                                    <form enctype="multipart/form-data" class="form-valide" action="../controllers/salonController.php" method="POST" id="main-contact-form">

                                        <div class="modal fade" id="pedidoModalCenter">
                                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 100%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Agregando nuevo pedido ...</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">


                                                            <?php foreach($array as $arraypedidos){

                                                                $sentencia = $base_de_datos->query("select * from ".$arraypedidos." where disponible=1;");
                                                                $pedidosList = $sentencia->fetchAll(PDO::FETCH_OBJ);

                                                                if($pedidosList!=null){
                                                                    ?>
                                                                    <div class="col-xl-4 col-lg-6 col-xxl-6">
                                                                        <div class="card top_menu_widget">
                                                                            <div class="card-body">
                                                                                <h4 class="card-title"> <?php echo $arraypedidos;  ?></h4>

                                                                                <?php foreach($pedidosList as $listaItem){

                                                                                    $foto=$listaItem->foto;
                                                                                    $nombre=$listaItem->nombre;
                                                                                    $precio=$listaItem->precio;
                                                                                    $valoracion=$listaItem->valoracion;
                                                                                    ?>

                                                                                    <div class="media border-bottom pt-3 pb-3">
                                                                                        <?php if($foto!=null){?>

                                                                                            <?php
                                                                                            $target_file=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/RestaurantDashboard/main/images/".$foto;
                                                                                            if (file_exists($target_file)) {  ?>
                                                                                                <img width="50" height="50" alt="#" class="mr-3" src="../images/<?php echo $foto;?>">
                                                                                            <?php  }else{?>
                                                                                                <input type="file" class="dropify" name="image" id="image" data-default-file="../images/blank1.jpg" />
                                                                                            <?php }  ?>



                                                                                        <?php }else{?>
                                                                                            <input type="file" class="dropify" name="image" id="image" data-default-file="../images/blank1.jpg" />

                                                                                        <?php }?>

                                                                                        <div class="media-body">
                                                                                            <h5 class="mb-1 mt-sm-1 mt-0"> <?php echo $nombre; ?></h5>
                                                                                            <span> <?php echo $valoracion; ?></span>
                                                                                        </div>
                                                                                        <h5 class="badge-lighten-primary">$ <?php echo $precio; ?> </h5>
                                                                                    </div>
                                                                                <?php } ?>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } }?>






                                                        </div>



                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="nromesa_pedido" id="nromesa_pedido">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        <button  type="submit" class="btn btn-success">Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                <!--end modal insert pedidos-->



                                <?php
                                include_once "pie.php";?>




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
                            <script src="../assets/js/common.min.js"></script>
                            <script src="../assets/js/custom.min.js"></script>
                            <script src="../assets/js/settings.js"></script>
                            <script src="../assets/js/quixnav.js"></script>
                            <script src="../assets/js/styleSwitcher.js"></script>
                            <script type="application/javascript">

                                const mesa = document.getElementById('mesa');
                                const selector = document.getElementById('selectorSillas');

                                selector.addEventListener('change', function () {
                                    // Limpiar sillas anteriores
                                    mesa.innerHTML = '';

                                    const cantidad = parseInt(this.value);

                                    for (let i = 0; i < cantidad; i++) {
                                        const angle = (360 / cantidad) * i;
                                        const rad = angle * (Math.PI / 180);
                                        const x = 60 * Math.cos(rad);
                                        const y = 60 * Math.sin(rad);

                                        const silla = document.createElement('div');
                                        silla.classList.add('silla');
                                        silla.style.left = `calc(50% + ${x}px - 20px)`;
                                        silla.style.top = `calc(50% + ${y}px - 20px)`;

                                        mesa.appendChild(silla);

                                        // Forzar animación
                                        setTimeout(() => silla.classList.add('visible'), 10);
                                    }
                                });

                                function  agregarPedidos(nro_mesa){

                                    document.getElementById("nromesa_pedido").value=nro_mesa;
                                    $("#pedidoModalCenter").modal('show');



                                }
                                function agregarCliente(nro_mesa){



                                }

                            </script>

                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        text-align: center;
                                    }

                                    .mesa {
                                        width: 150px;
                                        height: 150px;
                                        background-color: #8B4513;
                                        border-radius: 50%;
                                        margin: 100px auto;
                                        position: relative;
                                    }

                                    .silla {
                                        width: 40px;
                                        height: 40px;
                                        background-color: #ccc;
                                        border-radius: 10px;
                                        position: absolute;
                                        opacity: 0;
                                        transform: scale(0);
                                        transition: all 0.5s ease;
                                    }

                                    .silla.visible {
                                        opacity: 1;
                                        transform: scale(1);
                                    }

                                    select {
                                        padding: 10px;
                                        font-size: 16px;
                                    }
                                </style>