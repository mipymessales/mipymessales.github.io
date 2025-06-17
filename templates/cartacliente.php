<?php
// Inicializa la sesión
session_start();

defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
include_once ROOT_DIR."controllers/cifrado.php";
include_once ROOT_DIR."controllers/Host.php";
require_once ROOT_DIR."controllers/class.SqlInjectionUtils.php";
$path_info = $_SERVER['PATH_INFO'] ?? null;
$path_info = ltrim($path_info, '/');
if (empty($path_info)){
    $uri = $_SERVER['REQUEST_URI'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $path_info = str_replace($script_name, '', $uri);

    // Asegurarse de que esté limpio de posibles barras al inicio
    $path_info = ltrim($path_info, '/');
}

$url=cifrado::descifrar_token(htmlspecialchars($path_info),cifrado::getClaveSecreta());
$segments = explode('/', $url);

$nroMesa=$segments[0];
$idcliente=$segments[1];
global $base_de_datos;
if (isset($nroMesa) && isset($idcliente) && !SqlInjectionUtils::checkSqlInjectionAttempt($nroMesa) && !SqlInjectionUtils::checkSqlInjectionAttempt($idcliente)) {


    $checkCliente = $base_de_datos->prepare("SELECT estado_cuenta FROM cliente WHERE id=:idcliente and id_mesa=:idmesa ");
    $checkCliente->bindParam(':idcliente', $idcliente);
    $checkCliente->bindParam(':idmesa', $nroMesa);
    $checkCliente->execute();
    $resultado = $checkCliente->fetchAll(PDO::FETCH_OBJ);
    if (empty($resultado) || intval($resultado[0]->estado_cuenta)==0){
        header("Location:" .Host::getHOSTNAME(). "templates/404.php");
    }

    $nro_mesa=$nroMesa;
    $sentencia1 = $base_de_datos->prepare("SELECT cl.id as idcliente,cl.id_mesa as idmesa,cl.monto_cuenta ,s.* FROM cliente cl LEFT JOIN pedidos s ON cl.id_mesa = s.id_mesa and cl.id = s.id_cliente WHERE cl.id_mesa = ? and cl.estado_cuenta=1 and cl.id=?");

try {
    $sentencia1->execute([$nro_mesa,$idcliente]);
    //$cliente = $sentencia1->fetch(PDO::FETCH_ASSOC);

    $pedidos=array();
    $i=0;
    $pedidosrepetidos=array();
    while ($cliente = $sentencia1->fetch(PDO::FETCH_ASSOC)) {
        $categoria=$cliente['categoria'];
        $cantidad =intval($cliente['cantidad']);
       if (empty($categoria)){
           continue;
       }
       if (isset($pedidosrepetidos[$categoria][$cliente['id_plato']])){
           $pedidosrepetidos[$categoria][$cliente['id_plato']]=$pedidosrepetidos[$categoria][$cliente['id_plato']]+$cantidad;
       }else{
           $pedidosrepetidos[$categoria][$cliente['id_plato']]=$cantidad;
           $stmt = $base_de_datos->prepare("SELECT id,nombre,ingredientes,tipo,precio,disponible,valoracion,foto FROM ". $categoria ." WHERE id=:id ");
           $stmt->bindParam(':id', $cliente['id_plato']);
           $stmt->execute();
           $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
           if (!empty($resultado)){
               $resultado[]=$categoria;
               $resultado[]=$cliente['estado'];;
               $pedidos[$i] =
                   $resultado;

               $i+=1;
           }
       }


    }
  /*  foreach($this->fonts as $k=>$font)
    {

    }*/

}catch (Exception $e){
    echo  print_r($e->getTraceAsString());
}

}else{
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/">
    <title>Sales Manager</title>
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
        .deshabilitado {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
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

        // Check local storage
            var localS = localStorage.getItem('theme'),
                themeToSet = localS



        // If local storage is not set, we check the OS preference
        if (!localS) {
            themeToSet = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }

        // Set the correct theme
        document.documentElement.setAttribute('data-theme', themeToSet)
    </script>

    <!-- Reset -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-normalize@1.1.0/modern-normalize.min.css">

    <!-- Google fonts -->
    <!--<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
    <!--	<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;700&display=swap" rel="stylesheet">-->
    <link href="assets/fullcalendar/css/jquery-ui.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- <link href="assets/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/flotante.css" rel="stylesheet">
    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css" />
<!--    <link href="assets/css/dropify.min.css" rel="stylesheet">-->
    <!-- Main stylesheet -->
    <link rel="stylesheet" href="app.css">

    <style>

        .nav-menu {
            text-decoration: none;
            padding: 10px;
            color: gray;
            transition: 0.3s;
        }

        .nav-menu.active {
            background:var(--main-header-bg);
            color: #fff;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .mesa {
            width: 120px;
            height: 120px;
            background-color: #13268BB0;
            border-radius: 30%;
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

        .btn-cancelar[disabled] {
            opacity: 0.5;
            pointer-events: none;
            display: none;
        }
        .btn-add {

            font-size: 16px; /* Cambiar el tamaño de la tipografia */
            text-transform: uppercase; /* Texto en mayusculas */
            font-weight: bold; /* Fuente en negrita o bold */
            color: #ffffff; /* Color del texto */
            border-radius: 5px; /* Borde del boton */
            letter-spacing: 2px; /* Espacio entre letras */
            background-color: #0acf97; /* Color de fondo */
            padding: 18px 30px; /* Relleno del boton */
            bottom: 40px;
            right: 40px;
            transition: all 300ms ease 0ms;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-rest {

            font-size: 16px; /* Cambiar el tamaño de la tipografia */
            text-transform: uppercase; /* Texto en mayusculas */
            font-weight: bold; /* Fuente en negrita o bold */
            color: #ffffff; /* Color del texto */
            border-radius: 5px; /* Borde del boton */
            letter-spacing: 2px; /* Espacio entre letras */
            background-color: #fa5c7c; /* Color de fondo */
            padding: 18px 30px; /* Relleno del boton */
            bottom: 40px;
            right: 40px;
            transition: all 300ms ease 0ms;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<!-- SVG Definitions -->


<section id="main" style="display: contents;">
    <div id="main-header">
        <div id="main-content__container" style="display: contents!important;">

            <div id="main-header">

                <?php if (isset($_REQUEST["errorAdd"])){?>
                    <div class="alert alert-danger alert-dismissible fade show notification " style="display: block">
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
                        </button>
                        <strong>Error!</strong> Al agregar el pedido<br>Mensaje:<br> <?php ($_REQUEST["errorUpdate"])?>
                    </div>
                <?php }?>



                <div class="modal fade" id="exampleModalCenter">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" style="color: black">Comprobante ...</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="col-xl-12 col-xxl-12">
                                   <div id="show_factura"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>


            <!-- Ticker -->
                <div class="row">
                    <div class="col-sm-12">

                        <div class="card">

                            <div id="contenido_pedidos">No hay pedidos.</div>
                        </div>
                    </div>
                </div>




            <h4>Has tu pedido, que esperas!</h4>

                <div class="row">

                    <div class='col-xl-3 col-lg-6 col-sm-6'>
                    <div class="tabs">
                        <div class="tab active" id="entrantes" onclick="iniciarAutoCarga('entrantes')">Entrantes</div>
                        <div class="tab" id="platos" onclick="iniciarAutoCarga('platos')">Platos</div>
                        <div class="tab" id="postres" onclick="iniciarAutoCarga('postres')">Postres</div>
                        <div class="tab" id="bebidas" onclick="iniciarAutoCarga('bebidas')">Bebidas</div>
                    </div>
                    </div>
                    <button class="btn btn-outline-light notification-text " id="call-waiter-btn" onclick="llamarCamarero(<?php echo $nroMesa ?>)">🔔 Llamar al camarero</button>

                </div>

            <div class="content-body">

                <div class="container-fluid">
                    <p id="call-status" class="alert alert-warning notification" style="display: none"></p>
                    <div id="contenido">No hay productos.</div>

                </div>
            </div>



        </div>
        </div>
    </div> <!-- main-content -->
    <button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter" onclick="cargarFacturaMesa(<?php  echo $nroMesa?>,<?php  echo $idcliente?>)" >Factura</button>
</section> <!-- main -->


<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/moment.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/dropify.min.js"></script>
<script src="assets/js/dropify-init.js"></script>
<!-- SwiperJS JS -->
<script src="assets/js/swiper-bundle.min.js"></script>

<script>
    function llamarCamarero(mesaid){
        $.ajax({
            url: '/controllers/call_waiter.php',
            /*  headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },*/
            dataType:'json',
            method: 'POST',
            data: { table_id: mesaid,estado:'pendiente' },
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
               // console.log(data);
               if (data["status"]==="success"){
                    //location.reload();
                   const mensaje=  document.getElementById("call-status");
                   mensaje.innerText = data["message"];
                   //mensaje.classList.add("class","show");
                   mensaje.style.display='block';
                   //boton.classList.add('btn','btn-outline-warning','btn-block', 'border-0', 'text-black-50');

                   setTimeout(() => {
                       mensaje.style.display = 'none'; // Ocultar después de 5 segundos (5000 ms)
                   }, 5000);

                }

            }
        });
    }


    function addPedido(id){
      //  console.log(id);
       var value= parseInt(document.getElementById("cantidad_pedido"+id).value);
        value+=1;
        document.getElementById("cantidad_pedido"+id).value=value;
        document.getElementById("show_cantidad_pedido"+id).innerText="Cantidad pedida: "+value;
        document.getElementById("btnsubmit"+id).disabled=false;
    }
    function restPedido(id){
        var value= parseInt(document.getElementById("cantidad_pedido"+id).value);
        if (value>0){
            value-=1
            document.getElementById("cantidad_pedido"+id).value=value;
            document.getElementById("show_cantidad_pedido"+id).innerText="Cantidad pedida: "+value;
            document.getElementById("btnsubmit"+id).disabled=false;
        }
        if (value==0){
            document.getElementById("btnsubmit"+id).disabled=true;
        }
    }
    function deletePedido(idplato,idmesa,idcliente,estadopedido,cantidad,idpedidos){
        if (estadopedido==="Enviado"){
        var value= parseInt(cantidad);
        if (value>1){
           //Eliminar una fila con ese id de plato
            $.ajax({
                url: '/controllers/pedidosController.php',
                /*  headers: {
                      "Content-Type": "application/x-www-form-urlencoded",
                  },*/
                dataType:'json',
                method: 'POST',
                data: { action:'deletePedido',idplato: idplato,idmesa: idmesa,idcliente: idcliente,estadopedido:estadopedido,idpedidos:idpedidos },
                success: function(data) {
                    // document.getElementById('contenido').innerHTML =data;
                 //  console.log(data);
                    if (data["status"]==="success"){
                        //location.reload();
                        cargarPedido(idmesa,idcliente);
                    }

                }
            });
        }
        if (value==1){
          //Mostrar un dialog por si quiere eliminar el pedido completo ya que llego a 0 de cantidad

        }
        }else{
            //Mostrar dialogo de que el tiempo de cambio de ese pedido expiro y no se puede hacer nada, solo ahablar con el dependiente
            //para que lo cambie manualmente
        }
    }
    function incremnetPedido(idplato,idmesa,idcliente,estadopedido,categoria,value,idpedidos){
        var cantidad= parseInt(value);
        if (estadopedido==="Enviado"){
            $.ajax({
                url: '/controllers/pedidosController.php',
                /*  headers: {
                      "Content-Type": "application/x-www-form-urlencoded",
                  },*/
                dataType:'json',
                method: 'POST',
                data: { action:'incremnetPedido',idplato: idplato,idmesa: idmesa,idcliente: idcliente,estadopedido:estadopedido,categoria:categoria,idpedidos:idpedidos,cantidad:cantidad },
                success: function(data) {
                    // document.getElementById('contenido').innerHTML =data;
                     //console.log(data.status);
                    if (data['status']=='success'){
                       // console.log("GOOD");
                       // location.reload();
                        cargarPedido(idmesa,idcliente);
                    }


                }
            });
        }else{
            //Mostrar dialogo de que el tiempo de cambio de ese pedido expiro y no se puede hacer nada, solo ahablar con el dependiente
            //para que lo cambie manualmente
        }

    }

    var categoriaO = "entrantes";
    var idmesa = null;
    var idcliente = null;
    var intervaloActual =null;
    var intervaloActualPedido=null;
    var pedidos = JSON.parse(localStorage.getItem('pedidos')) || [];

    function agregarPlato(nombrePlato) {
      //  console.log("nombrePlato "+JSON.stringify(nombrePlato));
        const id = nombrePlato; // ID único por timestamp
        const tiempoExpira = Date.now() + 2 * 60 * 1000; // 2 minutos desde ahora

        pedidos.push({ id,idcliente, nombrePlato, tiempoExpira });
       // console.log("agregarPlato "+JSON.stringify(pedidos));
        guardarPedidos();
       // console.log("guardarPalto "+JSON.stringify(pedidos));
        mostrarPlato({ id,idcliente, nombrePlato, tiempoExpira });
    }
    function mostrarPlato(pedido) {
       // console.log("mostrarPlato");
       // console.log(JSON.stringify(pedido));
        const { id,idcliente, nombrePlato, tiempoExpira } = pedido;
            if (id==null || idcliente==null || document.getElementById(id+idcliente)==null) return;

       /* const contenedor = document.createElement('div');
        contenedor.classList.add('plato');
        contenedor.id = id;*/





        const tiempo = document.createElement('p');
        const spanContador = document.createElement('span');
        spanContador.classList.add('contador');
        tiempo.innerHTML = 'Tiempo restante para cancelar: ';
        tiempo.appendChild(spanContador);
        //contenedor.appendChild(tiempo);
       // console.log("mostrarPlato id "+id+idcliente);

        document.getElementById(id+idcliente).appendChild(tiempo);

        // Temporizador individual
        const interval = setInterval(() => {
            const tiempoRestante = Math.max(0, Math.floor((tiempoExpira - Date.now()) / 1000));

            if (tiempoRestante > 0) {
                const minutos = Math.floor(tiempoRestante / 60);
                const segundos = tiempoRestante % 60;
                spanContador.textContent = `${minutos}:${segundos < 10 ? '0' + segundos : segundos}`;
            } else {
              //  console.log('btnaddpedido***'+id+idcliente);
                document.getElementById('btnaddpedido'+id+idcliente).disabled = true;
                document.getElementById('btnrestpedido'+id+idcliente).disabled = true;
                document.getElementById('btn-'+id+idcliente).disabled = true;
                document.getElementById(id+idcliente).removeChild(tiempo);
                clearInterval(interval);
                //confirmarPlato(id);
            }
        }, 1000);
    }
    function guardarPedidos() {
        localStorage.setItem('pedidos', JSON.stringify(pedidos));
    }

    window.onload = function () {
       //localStorage.clear();
        // $('.dropify').dropify();
         categoriaO = <?php echo ($_REQUEST["categoria"])??"entrantes"; ?>;
       // console.log("categoriaO init:"+JSON.stringify(categoriaO));
          idmesa = <?php echo ($nro_mesa)??"undefined"; ?>;
          idcliente= <?php echo ($idcliente) ?? "undefined"; ?>;
       // console.log("id_mesa:"+idmesa);
       // console.log("idcliente:"+idcliente);

        if (categoriaO === "entrantes" || esNuloOVacio(categoriaO)) {

            iniciarAutoCarga('entrantes',idmesa,idcliente);
        }else{
            const elemento =(categoriaO).getAttribute("id");
            categoriaO=elemento;
           iniciarAutoCarga(elemento,idmesa,idcliente);

            const savedLink = document.querySelector(`.tab[href="${categoriaO}"]`);
            if (savedLink) {
                setActiveLinkNav(savedLink);
            }



        }

     iniciarAutoCargaPedido(idmesa,idcliente);




    };
    var datosAnteriores = null;
    //var dataS= null;
    function cargarCategoria(categoria,mesa,idcliente) {
        if (esNuloOVacio(mesa)){
            mesa = <?php echo ($nro_mesa)??"undefined"; ?>;

        }
        if (esNuloOVacio(idcliente)){
            idcliente= <?php echo ($idcliente) ?? "undefined"; ?>;
        }

        if (esNuloOVacio(categoriaO) && !esNuloOVacio(categoria)){
            categoriaO=categoria;
          //  console.log("(esNuloOVacio(categoriaO) && !esNuloOVacio(categoria)):");
        }
        if(esNuloOVacio(categoria) && !esNuloOVacio(categoriaO)){
            categoria=categoriaO;
           // console.log("esNuloOVacio(categoria) && !esNuloOVacio(categoriaO):");
        }
        if(!esNuloOVacio(categoria) && !esNuloOVacio(categoriaO) && categoria!=categoriaO){
           // console.log("!esNuloOVacio(categoria) && !esNuloOVacio(categoriaO) && categoria!==categoriaO");
            categoriaO=categoria;
        }
        //console.log("id_mesa:"+mesa);
        //console.log("idcliente:"+idcliente);
        //console.log("categoriaO:"+JSON.stringify(categoriaO));
        //console.log("categoria:"+JSON.stringify(categoria));
      //  $('#contenido').html("");
        //console.log((categoria).getAttribute("id"))

        $.ajax({
            url: '/controllers/obtener_menu_cliente.php',
            /*  headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },*/
            method: 'POST',
            data: { categoria: categoria,mesa: mesa,idcliente: idcliente },
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
                // console.log(data);
                const nuevosDatosStr = JSON.stringify(data);

                if (nuevosDatosStr === datosAnteriores) {
                   // console.log('Datos sin cambios, no se actualiza la vista.');
                }else{
                    datosAnteriores = nuevosDatosStr;
                    $('#contenido').html(data);
                }


            }
        });
    }
    var navOfertas = document.querySelectorAll('.tab');
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


    function iniciarAutoCarga(categoriaO, idmesa, idcliente) {

       if (intervaloActual !== null) {
            clearInterval(intervaloActual);
        }
        cargarCategoria(categoriaO, idmesa, idcliente);
      intervaloActual =setInterval(function () {
            cargarCategoria(categoriaO, idmesa, idcliente);
        }, 20000); // Cada 20 segundos
    }

    function iniciarAutoCargaPedido(idmesa, idcliente) {

        if (intervaloActualPedido !== null) {
            clearInterval(intervaloActualPedido);
        }
        cargarPedido(idmesa, idcliente);
      intervaloActualPedido =setInterval(function () {
            cargarPedido(idmesa, idcliente);
        }, 20000);
    }
    var datosAnterioresPedido = null;
    var datosAnterioresPedidoM=null;
    //var dataSPedido= null;
    function cargarPedido(mesa,idcliente) {
        if (esNuloOVacio(mesa)){
            mesa = <?php echo ($nro_mesa)??"undefined"; ?>;

        }
        if (esNuloOVacio(idcliente)){
            idcliente= <?php echo ($idcliente) ?? "undefined"; ?>;
        }
        $.ajax({
            url: '/controllers/obtener_pedido.php',
            /*  headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },*/
            method: 'POST',
            data: { mesa: mesa,idcliente: idcliente },
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
                // console.log(data);
                const nuevosDatosP = JSON.stringify(data);

                if (nuevosDatosP === datosAnterioresPedido) {
                    //console.log('Datos sin cambios en pedidos, no se actualiza la vista.');
                }else{
                    datosAnterioresPedido = nuevosDatosP;
                    //console.log(data);
                    $('#contenido_pedidos').html(data);
                }


            }
        });
    }
    function cargarFacturaMesa(mesa,idcliente) {
        if (esNuloOVacio(mesa)){
            mesa = <?php echo ($nro_mesa)??"undefined"; ?>;

        }
        if (esNuloOVacio(idcliente)){
            idcliente= <?php echo ($idcliente) ?? "undefined"; ?>;
        }
        $.ajax({
            url: '/controllers/obtener_pedido.php',
            /*  headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },*/
            method: 'POST',
            data: { mesa: mesa,idcliente: idcliente,f:true },
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
                // console.log(data);
                const nuevosDatosPM = JSON.stringify(data);

                if (nuevosDatosPM === datosAnterioresPedidoM) {
                  //  console.log('Datos sin cambios en factura, no se actualiza la vista.');
                }else{
                    datosAnterioresPedidoM = nuevosDatosPM;
                    //console.log(data);
                    $('#show_factura').html(data);
                }


            }
        });
    }

    var element = document.querySelector('.alert');
    if (!esNuloOVacio(element)) {
        setTimeout(() => {
            element.classList.remove('show');
            element.style.display = 'none';
        }, 6000);
    }

    function cancelarPedido(idpedidos,idcliente,idmesa){

        $.ajax({
            url: '/controllers/edit_pedido_mesa.php',
            /*  headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },*/
            dataType:'json',
            method: 'POST',
            data: { action:'delete',id:idpedidos },
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
                if (data['status']=='success'){
                    // console.log("GOOD");
                    // location.reload();
                    cargarPedido(idmesa,idcliente);
                }


            }
        });
    }
</script>
</body>
</html>