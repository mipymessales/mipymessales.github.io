<?php
$restaurantId=$_SESSION['idrestaurant'];
$username=$_SESSION['user'];
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
            border: 1px solid #ccc;
        }
        .tab.active {
            background: var(--main-header-bg);
            color: var(--sidebar-link-active-color);
            border: var(--gray-tint-50) 1px dashed;
        }

        .tab:hover {
            color: var(--sidebar-link-active-color);
            cursor: pointer;
        }
        .label-info {
            background: var(--gray-base);
            padding: 10px;
            color: #fff; }

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

    </style>
        <link href="assets/css/dropify.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/restaurant.css" rel="stylesheet">
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
            <?php if ($restaurantId==1){ ?>
                <div class="tab active" id="alimentos" onclick="cargarCategoria('alimentos','<?php echo $restaurantId;?>',true)">Alimentos</div>
                <div class="tab" id="bebidas" onclick="cargarCategoria('bebidas','<?php echo $restaurantId;?>',true)">Bebidas</div>
                <div class="tab" id="carnicos" onclick="cargarCategoria('carnicos','<?php echo $restaurantId;?>',true)">Cárnicos</div>
                <div class="tab" id="confituras" onclick="cargarCategoria('confituras','<?php echo $restaurantId;?>',true)">Confituras</div>
                <div class="tab" id="embutidos" onclick="cargarCategoria('embutidos','<?php echo $restaurantId;?>',true)">Embutidos</div>
                <div class="tab" id="condimentos" onclick="cargarCategoria('condimentos','<?php echo $restaurantId;?>',true)">Condimentos</div>
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

    <button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter" >Insertar</button>
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

                                            <?php   if($restaurantId!=1){ ?>
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

                                            <?php   if($restaurantId==1){ ?>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="cantidad">Cantidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="10">
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
   <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/dropify.min.js"></script>
    <script src="assets/js/dropify-init.js"></script>-->
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
            if (idrestaurant == 1){
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


</script>

