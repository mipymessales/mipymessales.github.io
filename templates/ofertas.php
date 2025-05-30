
    <meta charset="UTF-8">
    <title>Menú con Tabs</title>
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
        }
        .label-info {
            background: var(--gray-base);
            padding: 10px;
            color: #fff; }

        #contenido {
           /* margin-top: 20px;*/
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
        <div class="tabs">
            <div class="tab active" id="entrantes" onclick="cargarCategoria('entrantes')">Entrantes</div>
            <div class="tab" id="platos" onclick="cargarCategoria('platos')">Platos</div>
            <div class="tab" id="postres" onclick="cargarCategoria('postres')">Postres</div>
            <div class="tab" id="bebidas" onclick="cargarCategoria('bebidas')">Bebidas</div>
        </div>
        <div class="content-body">

            <div class="container-fluid">

        <div id="contenido">No hay productos.</div>

        </div>
        </div>



    </div>

    <button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter" >Insertar</button>
    <div class="form-validation">
        <form enctype="multipart/form-data" class="form-valide" action="controllers/categoriaController.php" method="POST" id="main-contact-form">
            <div class="modal fade" id="exampleModalCenter">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregando nueva elemento ...</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title">Inserte los datos del elemento</h4>
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
                                                        <option value="entrantes">Entrantes</option>
                                                        <option value="platos">Platos</option>
                                                        <option value="postres">Postres</option>
                                                        <option value="bebidas">Bebidas</option>

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

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="ingredientes">Ingredientes <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" id="ingredientes" name="ingredientes" rows="5" placeholder="Breve descripción de los ingrendientes separados por coma ( , )"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="precio">Precio <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="precio" name="precio" placeholder="$21.60">
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
        return v === null || v === undefined || v === "";
    }
    window.onload = function () {
        $('.dropify').dropify();
        const categoriaO = <?php echo ($_REQUEST["categoria"])??"undefined"; ?>;
       // cargarCategoria(categoriaO);
        if (esNuloOVacio(categoriaO)) {
            cargarCategoria('entrantes');
        }else{
            const elemento =(categoriaO).getAttribute("id");
            console.log("el:"+elemento);
            cargarCategoria(elemento);
            setActiveLinkNav(categoriaO);
        }

    };
    function cargarCategoria(categoria) {
        $('#contenido').html("");
        //console.log((categoria).getAttribute("id"))

        $.ajax({
            url: '/controllers/obtener_menu.php',
          /*  headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },*/
            method: 'POST',
            data: { categoria: categoria },
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

