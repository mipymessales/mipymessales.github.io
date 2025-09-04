<?php
$restaurantId=$_SESSION['idrestaurant'];
$username=$_SESSION['user'];
global $availableIds;
?>
    <meta charset="UTF-8">
    <title>Ofertas</title>
    <style>
        .tabs {
    display: flex;
    cursor: pointer;
    /*background-color:  var(--gray-base);*/
            color: var(--sidebar-link-color);
            padding: 10px;
        }

        .tab {
    margin-right: 15px;
            padding: 10px;
           /* border: 1px solid #ccc;*/
        }
        .tab.active {
            background: var(--main-header-bg);
            color: var(--sidebar-link-active-color);
            border: var(--gray-tint-50) 1px dashed;
            width: fit-content;
        }

        .tab:hover {
            color: var(--sidebar-link-active-color);
            cursor: pointer;
        }
        .label-info {
            background: var(--gray-base);
            padding: 10px;
            color: #fff; }
        label {
            display: contents;
            margin-bottom: 0.5rem;
        }

        #contenido {
           margin-top: 5px;
        }
        select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 12px;
        }

        option {
            padding: 5px;
        }

        @media (max-width: 580px) {
            .phone-view {
                flex: 0 0 auto !important;
                width: 30% !important;
            }
        }

        .accordion {
            /* max-width: 600px;*/
            margin: 30px auto;
           /* padding: 0 15px;*/
        }

        .accordion-header {
            background-color: #eee;
            color: black;
            width: 100%;
            text-align: left;
            padding: 15px;
            font-size: 1rem;
            border: none;
            outline: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 5px;
        }

        .accordion-header:hover {
            background-color: rgba(144, 146, 152, 0.55);
        }

        .accordion-content {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease;
            background: white;
          /*  padding: 0 15px;*/
            border-left: 3px solid rgba(144, 146, 152, 0.55);
            border-radius: 5px;
        }

        .accordion-content p {
            margin: 15px 0;
            font-size: 0.95rem;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .accordion-header {
                font-size: 0.95rem;
                padding: 12px;
            }

            .accordion-content p {
                font-size: 0.9rem;
            }
        }
    </style>

<style>
    /* ===== Estilos Accordion ===== */
    /* ===== Estilo productos ===== */
    .producto-item.card {
        display:flex;
        justify-content:space-between;
        align-items:start;
        background:#fff;
        padding:12px;
        border:1px solid #eee;
        border-radius:10px;
        margin-bottom:10px;
        transition:0.2s;
    }
    .producto-item.card:hover {
        background:#f9f9f9;
    }
    .info {
        display:flex;
        flex-direction:column;
    }
    .nombre {
        font-weight:600;
        font-size:12px;
        margin-left:5px;
    }
    .precio-unit {
        font-size:12px;
        color:#888;
    }

    /* ===== Controles ===== */
    .acciones {
        display:flex;
        align-items:center;
        gap:8px;
    }
    .btn-cantidad {
        width:32px;
        height:32px;
        border:none;
        border-radius:6px;
        background:#4CAF50;
        color:#fff;
        font-size:18px;
        cursor:pointer;
        transition:0.2s;
    }
    .btn-cantidad:hover {
        background:#45a049;
    }
    .acciones input {
        width:40px;
        text-align:center;
        font-size:14px;
        border:1px solid #ccc;
        border-radius:5px;
        padding:3px;
    }
    .precio {
        font-weight:bold;
        font-size:14px;
        color:#333;
    }

    /* ===== Total General ===== */
    .total-general {

        padding:15px;
        background:#f1f1f1;
        border-radius:10px;
        text-align:center;
        font-size:18px;
        font-weight:bold;
        box-shadow:0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 15px;
    }
</style>

<!--        <link href="../assets/css/dropify.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<link href="../assets/css/restaurant.css" rel="stylesheet">-->
    <div id="main-header">

        <?php if (isset($_REQUEST["errorUpdate"])){?>
        <div class="alert alert-danger alert-dismissible fade show notification " style="display: block">
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
            </button>
            <strong>Error!</strong> Al actualizar los datos<br>Mensaje:<br> <?php ($_REQUEST["errorUpdate"])?>
        </div>
        <?php }elseif (isset($_REQUEST["errorInsert"])){?>
        <div class="alert alert-danger alert-dismissible fade show notification"  style="display: block">
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
            </button>
            <strong>Error!</strong> Al insertar los datos<br>Mensaje:<br> <?php echo ($_REQUEST["errorInsert"]);?></div>
        </div>
        <?php }elseif (isset($_REQUEST["errorDelete"])){?>
            <div class="alert alert-danger alert-dismissible fade show notification"  style="display: block">
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
                </button>
                <strong>Error!</strong> Al eliminar los datos<br>Mensaje:<br> <?php ($_REQUEST["errorDelete"])?>
            </div>
        <?php }?>

        <h1>Selecciona una categoría para ver los productos.</h1>
<div class="custom-tab-4" id="tabstyle">
        <div class="nav nav-tabs">
            <?php if (in_array($restaurantId,$availableIds)){ ?>
                    <div class="row">
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab active" id="alimentos" onclick="cargarCategoria('alimentos','<?php echo $restaurantId;?>',true)">Alimentos</div>
                        </div>
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab" id="bebidas" onclick="cargarCategoria('bebidas','<?php echo $restaurantId;?>',true)">Bebidas</div>
                        </div>
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab" id="carnicos" onclick="cargarCategoria('carnicos','<?php echo $restaurantId;?>',true)">Cárnicos</div>
                        </div>
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab" id="confituras" onclick="cargarCategoria('confituras','<?php echo $restaurantId;?>',true)">Confituras</div>
                        </div>
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab" id="embutidos" onclick="cargarCategoria('embutidos','<?php echo $restaurantId;?>',true)">Embutidos</div>
                        </div>
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab" id="condimentos" onclick="cargarCategoria('condimentos','<?php echo $restaurantId;?>',true)">Condimentos</div>
                        </div>
                        <?php global $availableCombos; if (in_array($restaurantId,$availableCombos)){ ?>
                            <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                                <div class="tab" id="aseo" onclick="cargarCategoria('aseo','<?php echo $restaurantId;?>',true)">Aseo</div>
                            </div>
                        <div class="col-xl-2 col-sm-2 col-xxl-2 phone-view" >
                            <div class="tab" id="combos" onclick="cargarCategoria('combos','<?php echo $restaurantId;?>',true)">Combos</div>
                        </div>
                        <?php }?>
                    </div>
            <?php }else{ ?>
                <div class="tab active" id="entrantes" onclick="cargarCategoria('entrantes','<?php echo $restaurantId;?>')">Entrantes</div>
                <div class="tab" id="platos" onclick="cargarCategoria('platos','<?php echo $restaurantId;?>')">Platos</div>
                <div class="tab" id="postres" onclick="cargarCategoria('postres','<?php echo $restaurantId;?>')">Postres</div>
                <div class="tab" id="bebidas" onclick="cargarCategoria('bebidas','<?php echo $restaurantId;?>')">Bebidas</div>
            <?php } ?>
        </div>
</div>


            <div class="container-fluid">

        <div id="contenido">No hay productos.</div>

        </div>




    </div>

    <button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter">Insertar Producto</button>
    <button class="btn-flotante-c" data-toggle="modal" data-target="#exampleModalCenterCombo">Insertar Combo</button>
    <div class="form-validation">
        <form enctype="multipart/form-data" class="form-valide" action="controllers/categoriaController.php" method="POST" id="main-contact-form">
            <div class="modal fade" id="exampleModalCenter">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: black">Agregando nueva elemento ...</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title" style="color: black">Inserte los datos del elemento</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <input type="file" class="dropify" name="image" data-height="200" id="image" data-default-file="/images/blank1.jpg" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-form-label">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="nombrep">Elige la categoría a la que pertenece: <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <select name="categ" id="categ">
                                                        <option value="alimentos">Alimentos</option>
                                                        <option value="bebidas">Bebidas</option>
                                                        <option value="carnicos">Carnicos</option>
                                                        <option value="confituras">Confituras</option>
                                                        <option value="embutidos">Embutidos</option>
                                                        <option value="condimentos">Condimentos</option>
                                                        <?php global $availableCombos; if (in_array($restaurantId,$availableCombos)){ ?>
                                                            <option value="aseo">Aseo</option>
                                                        <?php }?>


                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="nombrep">Nombre <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="nombrep" name="nombrep" placeholder="Inserte el nombre de la bebida..">
                                                </div>
                                            </div>

                                            <?php   if(!in_array($restaurantId,$availableIds)){ ?>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="ingredientes">Ingredientes <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" id="ingredientes" name="ingredientes" rows="5" placeholder="Breve descripción de los ingrendientes separados por coma ( , )"></textarea>
                                                </div>
                                            </div>
                                            <?php  } ?>


                                        </div>
                                        <div class="col-12">

                                            <?php   if(in_array($restaurantId,$availableIds)){ ?>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="cantidad">Cantidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="10">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="preciocompra">Precio compra <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="preciocompra" name="preciocompra" placeholder="$21">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="precioventa">Precio venta <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="precioventa" name="precioventa" placeholder="$30">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="preciotranferencia">Precio por tranferencia <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="preciotranferencia" name="preciotranferencia" placeholder="$40">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="expira">Fecha de caducidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="date" class="form-control" id="expira" name="expira" placeholder="">
                                                    </div>
                                                </div>
                                            <?php    }else{ ?>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="precio">Precio <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="precio" name="precio" placeholder="$21.60">
                                                    </div>
                                                </div>

                                            <?php } ?>

                                            <h4 class="card-title mt-5">Estado de disponibilidad </h4>
                                            <div class="basic-form">

                                                <label class="radio-inline">
                                                    <input type="radio" name="radio" id="radio" value="d" checked="true"> Disponible</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="radio" id="radio" value="a"> Agotado</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="idrestaurant" id="idrestaurant" value="<?php echo $restaurantId;?>">
                            <input type="hidden" name="insertar" id="insertar" value="1">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button  type="submit" class="btn btn-success">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="form-validation">
        <form enctype="multipart/form-data" class="form-valide" action="controllers/comboController.php" method="POST" id="main-contact-form">
            <div class="modal fade" id="exampleModalCenterCombo">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: black">Agregando nuevo combo ...</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title" style="color: black">Inserte los datos del combo</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <input type="file" class="dropify" name="imagecombo" data-height="200" id="imagecombo" data-default-file="/images/blank1.jpg" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">

                                            <div class="accordion">
                                                <?php
                                                $array=["alimentos","bebidas","carnicos","embutidos","confituras","condimentos","aseo"];
                                                global $base_de_datos;
                                                ?>

                                                <?php foreach($array as $arraypedidos) { ?>
                                                    <div class="accordion-item">
                                                        <button type="button" class="accordion-header">
                                                            <?php echo ucfirst(strtolower($arraypedidos)); ?>
                                                        </button>
                                                        <div class="accordion-content">
                                                            <?php
                                                            $stmt = $base_de_datos->prepare("SELECT id as idplato,nombre,precioventa,preciocompra
                                                FROM $arraypedidos 
                                                WHERE restaurantid=:id");
                                                            $stmt->bindParam(':id',$restaurantId);
                                                            $stmt->execute();

                                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                $id = $row["idplato"];
                                                                $nombre = $row["nombre"];
                                                                $precio = $row["precioventa"];
                                                                $compra = $row["preciocompra"];
                                                                ?>

                                                                <div class="producto-item card"
                                                                     data-id="<?= $id ?>"
                                                                     data-precio="<?= $precio ?>"
                                                                     data-compra="<?= $compra ?>"
                                                                     data-nombre="<?= htmlspecialchars($nombre) ?>"
                                                                     data-categoria="<?= $arraypedidos ?>">



                                                                    <div class="acciones">
                                                                        <input type="checkbox" class="check-producto" id="prod<?= $id ?>" />
                                                                        <label for="prod<?= $id ?>" class="nombre"><?= $nombre ?></label>
                                                                        <span class="precio-unit">Precio: $<?= number_format($precio,2) ?></span>
                                                                        <button type="button" class="btn-cantidad btn-menos" data-id="<?= $id ?>">−</button>
                                                                        <input type="text" id="cantidad<?= $id ?>" value="0" readonly />
                                                                        <button type="button" class="btn btn-danger btn-decrement btn-mas" data-id="<?= $id ?>">+</button>
                                                                        <span class="precio" id="precio<?= $id ?>">$0.00</span>
                                                                    </div>
                                                                </div>

                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <!-- TOTAL GENERAL -->
                                            <div class="total-general">

                                                <p class="display-3 mb-0" style="font-size: 1.5rem !important;"> Total sin descuento: <span id="totalBruto">$0.00</span></p><br>
                                                <div class="form-group row">

                                                    <label class="col-lg-4 col-form-label" for="descuento">Descuento (%): <span class="text-danger">*</span><br>
                                                    </label><br>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control" id="descuento"  value="0" min="0" max="100" name="descuento" placeholder="10%">
                                                    </div>
                                                </div>
                                                <p class="display-3 mb-0" style="font-size: 1.5rem !important;">Total con descuento: <span id="totalCarrito">$0.00</span></p><br>
                                                <p class="display-3 mb-0" style="font-size: 1.5rem !important;">Ganancia: <span id="ganancia">$0.00</span></p><br>

                                            </div>



                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-12">

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="nombrep">Nombre <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="nombrep" name="nombrep" placeholder="Inserte el nombre del combo..">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="descripcion">Descripcion <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Inserte breve descripcion..">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="cantidad">Cantidad <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="10">
                                                </div>
                                            </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="expira">Fecha de caducidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="date" class="form-control" id="expira" name="expira" placeholder="">
                                                    </div>
                                                </div>


                                            <h4 class="card-title mt-5">Estado de disponibilidad </h4>
                                            <div class="basic-form">

                                                <label class="radio-inline">
                                                    <input type="radio" name="radio" id="radio" value="d" checked="true"> Disponible</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="radio" id="radio" value="a"> Agotado</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="idrestaurant" id="idrestaurant" value="<?php echo $restaurantId;?>">
                            <input type="hidden" name="montototal" id="montototal" value="0">
                            <input type="hidden" name="montototaldescuento" id="montototaldescuento" value="0">
                            <input type="hidden" name="insertar" id="insertar" value="1">
                            <!-- Input oculto con el JSON -->
                            <input type="hidden" name="carrito" id="carritoJSON">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button  type="submit" class="btn btn-success">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
<!-- <script src="../assets/js/jquery-3.6.0.min.js"></script>
 <script src="../assets/js/dropify.min.js"></script>
<script src="../assets/js/dropify-init.js"></script>-->

<script>



    // Construir JSON del carrito
    function actualizarCarritoJSON() {
        let carrito = {};

        document.querySelectorAll(".producto-item").forEach(prod => {
            let id = prod.dataset.id;
            let nombre = prod.dataset.nombre;
            let precio = parseFloat(prod.dataset.precio);
            let categoria = prod.dataset.categoria;
            let cantidad = parseInt(document.getElementById("cantidad"+id).value);

            let checked = document.getElementById("prod"+id).checked;

            if (checked && cantidad > 0) {
                if (!carrito[categoria]) carrito[categoria] = [];
                carrito[categoria].push({
                    id: parseInt(id),
                    nombre: nombre,
                    precio: precio,
                    cantidad: cantidad
                });
            }
        });

        document.getElementById("carritoJSON").value = JSON.stringify(carrito, null, 2);
    }

    function actualizarTotales() {
        let totalVenta = 0;
        let totalCompra = 0;

        document.querySelectorAll(".producto-item").forEach(prod => {
            let id = prod.dataset.id;
            let cantidad = parseInt(document.getElementById("cantidad"+id).value);
            if (cantidad > 0 && document.getElementById("prod"+id).checked) {
                let precioVenta = parseFloat(prod.dataset.precio);
                let precioCompra = parseFloat(prod.dataset.compra);

                totalVenta += precioVenta * cantidad;
                totalCompra += precioCompra * cantidad;
            }
        });

        // Descuento ingresado por el usuario
        let descuentoPorc = parseFloat(document.getElementById("descuento").value) || 0;
        let descuento = (totalVenta * descuentoPorc) / 100;
        let totalConDescuento = totalVenta - descuento;

        // Ganancia = venta - compra
        let ganancia = totalConDescuento - totalCompra;

        // Mostrar resultados
        document.getElementById("totalBruto").innerText = "$" + totalVenta.toFixed(2);
        document.getElementById("montototal").value = totalVenta.toFixed(2);

        document.getElementById("totalCarrito").innerText = "$" + totalConDescuento.toFixed(2);
        document.getElementById("montototaldescuento").value =totalConDescuento.toFixed(2);

        document.getElementById("ganancia").innerText = "$" + ganancia.toFixed(2);
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Botón +
        document.querySelectorAll(".btn-mas").forEach(btn => {
            btn.addEventListener("click", () => {
                let id = btn.dataset.id;
                let input = document.getElementById("cantidad"+id);
                let chk = document.getElementById("prod"+id);
                let cantidad = parseInt(input.value) + 1;
                input.value = cantidad;

                if (!chk.checked) chk.checked = true;

                let precioUnit = parseFloat(document.querySelector(`.producto-item[data-id='${id}']`).dataset.precio);
                document.getElementById("precio"+id).innerText = "$" + (cantidad * precioUnit).toFixed(2);

                actualizarTotales();
                actualizarCarritoJSON();
            });
        });

        // Botón -
        document.querySelectorAll(".btn-menos").forEach(btn => {
            btn.addEventListener("click", () => {
                let id = btn.dataset.id;
                let input = document.getElementById("cantidad"+id);
                let chk = document.getElementById("prod"+id);
                let cantidad = Math.max(0, parseInt(input.value) - 1);
                input.value = cantidad;

                let precioUnit = parseFloat(document.querySelector(`.producto-item[data-id='${id}']`).dataset.precio);
                document.getElementById("precio"+id).innerText = "$" + (cantidad * precioUnit).toFixed(2);

                if (cantidad === 0) chk.checked = false;

                actualizarTotales();
                actualizarCarritoJSON();
            });
        });

        // Checkbox manual
        document.querySelectorAll(".check-producto").forEach(chk => {
            chk.addEventListener("change", () => {
                let id = chk.id.replace("prod","");
                let input = document.getElementById("cantidad"+id);
                let precioUnit = parseFloat(document.querySelector(`.producto-item[data-id='${id}']`).dataset.precio);

                if (chk.checked) {
                    if (parseInt(input.value) === 0) {
                        input.value = 1;
                        document.getElementById("precio"+id).innerText = "$" + precioUnit.toFixed(2);
                    }
                } else {
                    input.value = 0;
                    document.getElementById("precio"+id).innerText = "$0.00";
                }

                actualizarTotales();
                actualizarCarritoJSON();
            });
        });

        // Escuchar cambios en descuento
        document.getElementById("descuento").addEventListener("input", () => {
            actualizarTotales();
        });
    });


</script>

<script>
    function esNuloOVacio(v) {
        if (v === null || v === undefined) return true;

        // Strings
        if (typeof v === "string") {
            const trimmed = v.trim().toLowerCase();
            return trimmed === "" || trimmed === "undefined" || trimmed === "null";
        }

        // Arrays
        if (Array.isArray(v)) return v.length === 0;

        // HTML Elements
        if (v instanceof HTMLElement) {
            // Revisa contenido visible y atributos clave
            const contenido = v.textContent?.trim() || v.value?.trim() || v.innerHTML?.trim();
            const href = v.getAttribute?.("href");
            const src = v.getAttribute?.("src");
            return (!contenido && !href && !src);
        }

        // Objetos planos
        if (typeof v === "object") return Object.keys(v).length === 0;

        return false; // Para tipos como número, booleano, etc.
    }
    var username="<?php echo $username;?>";
    var idrestaurant="<?php echo $restaurantId;?>";
    window.onload = function () {
        $('.dropify').dropify();
        const categoriaO = <?php echo ($_REQUEST["categoria"])??"undefined"; ?>;
       // cargarCategoria(categoriaO);
        if (esNuloOVacio(categoriaO)) {
            if (idrestaurant == 1 || idrestaurant==2){
                cargarCategoria('alimentos',idrestaurant,true);
            }else{
                cargarCategoria('entrantes',idrestaurant);
            }

        }else{
            const elemento =(categoriaO).getAttribute("id");
            //console.log("el:"+elemento);
            cargarCategoria(elemento,idrestaurant,true);
            setActiveLinkNav(categoriaO);
        }

    };
    function cargarCategoria(categoria,idrestaurant,flag) {
        //mostrar el boton flotante del combo
        

        $('#contenido').html("");
        //console.log((categoria).getAttribute("id"))

        $.ajax({
            url: '/controllers/obtener_ofertas.php',
          /*  headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },*/
            method: 'POST',
            data: { categoria: categoria,idrestaurant:idrestaurant },
            success: function(data) {
               // document.getElementById('contenido').innerHTML =data;
               // console.log(data);
           $('#contenido').html(data);
            }
        });
    }
    const navOfertas = document.querySelectorAll('.tab');
    // Función para establecer el enlace activo
    function setActiveLinkNav(link) {
        navOfertas.forEach(nav => nav.classList.remove('active'));
        link.classList.add('active');
        localStorage.setItem('navOfertas', link.getAttribute('id')); // Guarda el href como identificador
    }
    // Al hacer clic en un enlace, se guarda como activo
    navOfertas.forEach(link => {
        link.addEventListener('click', function(e) {
            setActiveLinkNav(this);
        });
    });
    // Al cargar la página, revisa si hay un enlace guardado
    window.addEventListener('DOMContentLoaded', () => {
        const savedHref = localStorage.getItem('navOfertas');
        if (savedHref) {
            const savedLink = document.querySelector(`.tab[href="${savedHref}"]`);
            if (savedLink) {
                setActiveLinkNav(savedLink);
            }
        }
    });
    const element = document.querySelector('.alert');
    if (!esNuloOVacio(element)) {
        setTimeout(() => {
            element.classList.remove('show');
            element.style.display = 'none';
        }, 6000);
    }


    document.querySelectorAll('.accordion-header').forEach(header => {
        // Solo actualizar si ya está expandido
        const content = header.nextElementSibling;
        if (content.classList.contains("expanded")) {
            content.style.maxHeight = "100%";
        }
    });
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const isOpen = content.style.maxHeight;

            // Cierra todos
            document.querySelectorAll('.accordion-content').forEach(c => {
                c.style.maxHeight = null;
            });

            // Si estaba cerrado, lo abre
            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    });


        // Acordeón toggle
   /*     document.querySelectorAll(".accordion-header").forEach(header=>{
        header.addEventListener("click",()=>{
            let content = header.nextElementSibling;
            content.style.display = (content.style.display==="block") ? "none" : "block";
        });
    });*/


</script>

