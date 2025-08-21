<?php
session_start();
$ruta=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/transferencia_transfermovil.png";
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;

$restaurantid=$_SESSION['idrestaurant'];
$sentencia = $base_de_datos->prepare("SELECT * FROM admin WHERE restaurantid =:usuario");
$sentencia->bindParam(':usuario', $restaurantid);

try {
    $sentencia->execute();
} catch (Exception $e) {
    echo print_r($e->getTraceAsString());
}
$resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);


if (isset($_POST['action']) && hash_equals($_POST['action'],'update') && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    $incorrecta = "";

    /*IMG logic*/

    $imagess = $_POST["imagebd"] ?? "blank1.jpg";

    $target_dir = str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/"; //directorio en el que se subira
    $target_file = $target_dir . basename($_FILES["image"]["name"]);//se añade el directorio y el nombre del archivo
    $uploadOk = 1;//se añade un valor determinado en 1
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Comprueba si el archivo de imagen es una imagen real o una imagen falsa
//if(isset($_POST["submit"])) {//detecta el boton


    //ImageResize(250, 250,    basename($_FILES["image"]["name"]));

    if ($_FILES["image"]["tmp_name"] != null)
        $check = getimagesize($_FILES["image"]["tmp_name"]);
    else {
        if (empty($imagess))
            $check = false;
    }


    if ($check !== false) {//si es falso es una imagen y si no lanza error
        //echo "Archivo es una imagen- " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $incorrecta .= "El archivo no es una imagen </br>";
        $uploadOk = 0;
    }
//}
// Comprobar si el archivo ya existe
    if (file_exists($target_file)) {
        //$uploadOk = 0;//si existe lanza un valor en 0
        if (!empty(basename($_FILES["image"]["name"]))) {
            //  $uploadOk=1;
            $imagess = basename($_FILES["image"]["name"]);
        }

    } else {
// Comprueba el peso
     /*   if ($_FILES["image"]["size"] > 500000) {
            $incorrecta .= "El archivo es muy pesado </br>";
            $uploadOk = 0;
        }*/
        if ($_FILES["image"]["size"] > 500000) {
            // COMPRIMIR si es muy pesada
            $source = $_FILES["image"]["tmp_name"];
            $calidad = 70; // puedes ajustar entre 0 (máxima compresión) y 100 (sin compresión)

            // Detecta el tipo de imagen y crea desde origen
            switch ($imageFileType) {
                case 'jpeg':
                case 'jpg':
                    $image = imagecreatefromjpeg($source);
                    break;
                case 'png':
                    $image = imagecreatefrompng($source);
                    // Convertimos PNG a JPG para comprimir
                    break;
                case 'gif':
                    $image = imagecreatefromgif($source);
                    break;
                default:
                    $incorrecta .= "Tipo de imagen no soportado para compresión </br>";
                    $uploadOk = 0;
                    break;
            }

            // Si la imagen fue creada correctamente
            if (isset($image)) {
                // Comprime la imagen y reemplaza el archivo temporal original
                imagejpeg($image, $source, $calidad); // Guardamos en el mismo tmp_name
                imagedestroy($image); // Liberar memoria
                // NOTA: Ahora el archivo en $_FILES["image"]["tmp_name"] ya está comprimido
            }
        }
// Permitir ciertos formatos de archivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $incorrecta .= "Solo, JPG, JPEG, PNG & GIF Estan soportados </br>";
            $uploadOk = 0;
        }
//Comprueba si $ uploadOk se establece en 0 por un error


        if ($uploadOk == 0) {
            $incorrecta .= "El archivo no se subio </br>";
// si todo está bien, intenta subir el archivo
        } else {

            // include_once "imageclass.php";

            if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                // Ok para continuar
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $imagess = basename($_FILES["image"]["name"]);
                  //  echo "El archivo " . basename($_FILES["image"]["name"]) . " Se subio correctamente";
                } else {
                    $incorrecta .= "Error al cargar el archivo";

                }
            } else {
                $incorrecta .= "Error al subir la imagen: " . $_FILES["image"]["error"] . "</br>";
            }


        }
    }


    /*END IMG logic*/


    $stm = $base_de_datos->prepare("UPDATE restaurant_info set nombre=:nombre, telefono=:telefono, direccion=:direccion, horario= :horario, ubicacion=:ubicacion, foto_portada=:foto_portada WHERE id=:id ");

    $stm->bindParam(':nombre', $_POST['nombrerestaurant']);
    $stm->bindParam(':telefono', $_POST["telefonocontacto"]);
    $stm->bindParam(':direccion', $_POST["direccion"]);
  //  $h = json_encode($_POST["horariolaborarl"]);
    $stm->bindParam(':horario', $_POST["horariolaborarl"]);
    $stm->bindParam(':ubicacion', $_POST["ubicacion"]);
    $stm->bindParam(':foto_portada', $imagess);
    $stm->bindParam(':id', $restaurantid);

    try{
        if ($stm->execute()){
            if (isset($_POST['password']) && isset($_POST['confirmpassword']) && hash_equals($_POST['password'],$_POST['confirmpassword']) && !password_verify($_POST['password'],$resultado[0]->contrasena)){
                $sentencia = $base_de_datos->prepare("UPDATE admin set contrasena =:contrasena WHERE restaurantid=:id ");
                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash seguro
                $sentencia->bindParam(':contrasena', $pass);
                $sentencia->bindParam(':id', $restaurantid);
                $sentencia->execute();

            }
            header('Location: ' . Host::getHOSTNAME()."panel.php?section=configuracion");
            //exit;
        }
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
    }



}


?>
<style>
    #tabla-gastos {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    #tabla-gastos th, #tabla-gastos td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }
    #tabla-gastos th {
        background-color: #f4f4f4;
    }
    #tabla-gastos tr:hover {
        background-color: #f9f9f9;
    }
    button {
        margin: 2px;
        padding: 5px 10px;
        border: none;
        background-color: #3e95cd;
        color: white;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover {
        background-color: #3276b1;
    }
    .accordion {
       /* max-width: 600px;*/
        margin: 30px auto;
        padding: 0 15px;
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
        padding: 0 15px;
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
    table {
        border-collapse: collapse;
        width: 100%; /* opcional */
    }

    td, th {
        padding: 10px 15px; /* ← aquí ajustas el espacio interno */
        text-align: left;   /* mejora la lectura */
        border: 0.5px solid #ccc;
    }
    .icon-eye,
    .icon-eye-off {
        display: none;
    }
    .toggle-password.show .icon-eye-off {
        display: inline;
    }
    .toggle-password:not(.show) .icon-eye {
        display: inline;
    }
    .tabla-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* scroll más suave en iOS */
        scrollbar-width: thin;
        width: 100%;
        position: relative;
        z-index: 1;
    }
</style>
<div id="loader">
    <div class="spinner"></div>
    <div>Procesando datos...</div>
</div>
<svg style="width: 10% !important;height:  10% !important;">
    <use xlink:href="#icon-user"></use>
</svg>
<h1><?php echo $resultado[0]->usuario;?></h1>
<div class="row">

    <div class="col-sm-12">
    <div class="accordion">
        <div class="accordion-item">
            <button class="accordion-header">Configuración del sitio</button>
            <div class="accordion-content">
                <hr>
                <h4 id="titulo-modal">Visitar sitio</h4>

                <a class="btn btn-success" href="<?php echo $sitioRestaurant;?>" style="display: grid;
  color: white;
  font-weight: 700;"><?php echo $sitioRestaurant;?></a>
                <form enctype="multipart/form-data" action="/panel?section=configuracion" method="POST" id="formHorario" onsubmit="mostrarLoader()">

                    <div class="h-100" style="margin-bottom: 20px">


                                <div class="row justify-content-center h-100">
                                    <div class="col-md-6">
                                        <div class="form-input-content">


                                                <h3 class="section-title">Información del Restaurant: <?php echo $nombreRestaurant;?></h3>
                                                <div class="form-group mb-4">
                                                    <p>Foto de portada</p>

                                                    <?php
                                                    $target_file=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/".$foto_portadaRestaurant;
                                                    if (file_exists($target_file)) {  ?>
                                                        <input type="file" class="dropify" name="image" id="image" data-default-file="/images/<?php echo $foto_portadaRestaurant;?>" />
                                                    <?php  }else{?>
                                                        <input type="file" class="dropify" name="image" id="image" data-default-file="/images/blank1.jpg" />
                                                    <?php }  ?>


                                                </div>
                                                <div class="form-group mb-4">
                                                    <input type="text" class="form-control rounded-0 bg-transparent"
                                                           name="nombrerestaurant" placeholder="Nombre del Restaurant" value="<?php echo $nombreRestaurant;?>">
                                                </div>


                                                <div class="form-group mb-4">
                                                    <input type="text" class="form-control rounded-0 bg-transparent"
                                                           name="telefonocontacto" placeholder="Teléfono de contacto" value="<?php echo $telefonoRestaurant;?>">
                                                </div>
                                                <div class="form-group mb-4 input-group">
                                                    <input type="text" class="form-control rounded-0 bg-transparent" id="direccion"
                                                           name="direccion" placeholder="Direcci&oacute;n" value="<?php echo $direccionRestaurant;?>">
                                                </div>

                                                <div class="form-group mb-4  text-left">
                                                    <p>Horario</p><br>
                                                    <div class="container-fluid tabla-scroll">
                                                    <table>
                                                        <thead>
                                                        <tr>
                                                            <th>Día</th>
                                                            <th>Hora Apertura</th>
                                                            <th>Hora Cierre</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="horario-table"></tbody>
                                                    </table>
                                                    </div>
                                                    <input type="hidden" id="jsonInput" name="schedule">
                                                    <br>
                                                </div>
                                                <div class="form-group mb-4 text-left">
                                                    <p>Ubicacion en Google Maps (Latitud Longitud)</p><br>
                                                    <input type="text" class="form-control rounded-0 bg-transparent" id="ubicacion"
                                                           name="ubicacion" placeholder="Ej de formato: 22°48'33.5 N 80°04'25.0W" value="<?php echo $ubicacionRestaurant;?>">
                                                </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-input-content">
                                            <div class="card-body">
                                                <h3 class="section-title">Credenciales para administrar el sitio</h3>
                                                <p class="text-left">Usuario</p><br>
                                                <div class="form-group mb-4">
                                                    <input type="text" class="form-control rounded-0 bg-transparent" name="username"
                                                           placeholder="Username" value="<?php echo $resultado[0]->usuario;?>" disabled>
                                                </div>

                                                <p class="text-left">Contraseña</p><br>

                                                <div class="form-group mb-4 input-group">
                                                  <!--  <input type="password" id="password" name="password" placeholder="Contraseña">
                                                    <i class="fa-solid fa-eye toggle-password" data-target="password"></i>-->
                                                                                                <input type="password" id="password" name="password">
                                                                                                <span class="toggle-password" data-target="password">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                                                                                         stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                                                      <path d="M17.94 17.94A10.94 10.94 0 0112 19c-5 0-9.27-3.11-11-7
                                                               1.21-2.77 3.29-5.05 5.88-6.25M10.58 10.58a3 3 0 004.24 4.24M6.1 6.1
                                                               L1 1m22 22L2 2" style="fill: none"/>
                                                    </svg>

                                            </span>


                                                </div>
                                                <p class="text-left">Confirmar contraseña</p><br>
                                                <div class="form-group mb-4 input-group">
                                                    <input type="password" id="confirmpassword" name="confirmpassword"
                                                           placeholder="Confirmar contraseña">
                                                    <span class="toggle-password" data-target="confirmpassword">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                                                      <path d="M17.94 17.94A10.94 10.94 0 0112 19c-5 0-9.27-3.11-11-7
                                                               1.21-2.77 3.29-5.05 5.88-6.25M10.58 10.58a3 3 0 004.24 4.24M6.1 6.1
                                                               L1 1m22 22L2 2"  style="fill: none"/>
                                                    </svg>
                                                </div>
                                                <span id="mensaje" style="color:red;"></span><br><br>


                                                <div class="form-group ml-3 mb-5">
                                                    <!--<input id="checkbox1" type="checkbox">-->
                                                    <!--<label class="label-checkbox ml-2 mb-0" for="checkbox1">Recordar contraseña</label>-->
                                                    <?php if (isset($incorrecta)) { ?>
                                                        <label class="alert-danger"><?php echo $incorrecta; ?></label>

                                                    <?php } ?>


                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>

                            <input type="hidden" name="horariolaborarl" id="horariolaborarl">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="imagebd" value="<?php echo $foto_portadaRestaurant;?>">
                            <button class="btn-warning" type="submit">Actualizar</button>

                    </div>

                </form>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">Configuración de Gastos</button>
            <div class="accordion-content" id="gastos-lista">

                <!-- Modal -->
                <div  id="modal-gasto" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%);
     background:white; padding:20px; border:1px solid #ccc; z-index:999;">
                    <h4 id="titulo-modal">Nuevo Gasto</h4>
                    <form id="form-gasto">
                        <input type="hidden" id="gasto-id" />
                        <label>Fecha: <input type="date" id="gasto-fecha" required></label><br>
                        <label>Concepto: <input type="text" id="gasto-concepto" required></label><br>
                        <label>Monto: <input type="number" id="gasto-monto" step="0.01" required></label><br>
                        <input type="hidden" id="idrestaurant" name="idrestaurant" value="<?php echo $restaurantid; ?>">

                        <button type="submit">Guardar</button>
                        <button type="button" onclick="cerrarModal()">Cancelar</button>
                    </form>
                </div>
                <hr>
                <h4>Lista de Gastos</h4>
                <button class="btn btn-success" onclick="abrirModalNuevo()">Agregar Nuevo Gasto</button>
                <div class="container-fluid tabla-scroll">
                <table id="tabla-gastos" border="1">
                    <thead>
                    <tr><th>Concepto</th><th>Monto</th><th>Acción</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-header">Trabajadores</button>
            <div class="accordion-content">
                <hr>
                <h4>Listado de Trabajadores</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Agregar Nuevo Trabajador</button>
                <div class="content-body">
                <div class="container-fluid">
                    <?php



                    $sentencia = $base_de_datos->query("select * from trabajador where restaurantid='.$restaurantid.';");
                    $trabajadores = $sentencia->fetchAll(PDO::FETCH_OBJ);


                    if (!$trabajadores) {
                        #No existe
                        // echo "¡No existe bebidas en el salon !";

                        ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" >Ups!!!. No se encontraron trabajadores.</h4>


                                        <div class="alert alert-warning alert-dismissible fade show">
                                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
                                            </button>
                                            <strong>Alerta!</strong> No hay trabajadores agregados al sistema!. Click en nuevo para insertar un trabajador <a data-toggle="modal" data-target="#exampleModalCenter" href="#exampleModalCenter">Nuevo</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php

                    }else{    ?>
                        <div class="row">
                            <?php   foreach($trabajadores as $trabajador) {

                                $nombre= $trabajador->nombre;
                                $telefono= $trabajador->phone;
                                $ci= $trabajador->ci;
                                $direccion= $trabajador->valoracion;


                                $usuario=$trabajador->nombre_usuario;
                                $contrasena=$trabajador->contrasena_usuario;
                                $disponible=$trabajador->activo;
                                $b=1;

                                $chequeo=1;
                                if($usuario=="Sin usuario"){
                                    $chequeo=0;
                                }

                                $foto= $trabajador->foto;
                                $id_cocinero= $trabajador->id;
                                $idrol= $trabajador->id_rol_usuario;
                                // echo $valoracion;
                                ?>



                                <div class="col-sm-6 col-lg-4 col-xl-2 col-xxl-4">
                                    <div class="card">
                                        <?php if($disponible){?>
                                            <span class="ribbon ribbon__three_disp vertical-card__menu--status">Activo <em
                                                        class="ribbon-curve"></em></span>
                                        <?php }else{?>
                                            <span class="ribbon ribbon__three vertical-card__menu--status">Inactivo <em
                                                        class="ribbon-curve"></em></span>
                                        <?php }?>
                                        <div class="card-body">
                                            <div class="text-center">
                                                <?php if($foto!=null){?>
                                                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="/images/<?php echo $foto;?>" width="80" height="80" alt="">
                                                <?php }else{?>
                                                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="/images/blank1.jpg" width="80" height="80" alt="">
                                                <?php }?>

                                                <h4 class="mb-0"><?php echo $nombre?></h4>
                                                <p class="text-muted mb-0"><strong class="text-dark mr-4 text-left">CI :</strong><?php echo $ci;?></p>
                                                <p class="text-muted mb-0"><strong class="text-dark mr-4 text-left">Teléfono :</strong><?php echo $telefono;?></p>

                                            </div>
                                        </div>
                                        <div class="card-footer border-0 bg-white pb-4">
                                            <div class="button-group ">
                                                <button data-toggle="modal" data-target="#exampleModalEDIT<?php echo $id_cocinero;?>" class="btn btn-warning text-white" style="margin: 4px">Editar</button>
                                                <button data-toggle="modal" data-target="#exampleModalDELETE<?php echo $id_cocinero;?>" class="btn btn-danger text-white" style="margin: 4px">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                include "modal_editartrabajador.php";
                                include "modal_eliminartrabajador.php";
                            }?>

                        </div>
                    <?php  }?>





                </div>
            </div>
            </div>
        </div>

        <div class="accordion-item">
            <button class="accordion-header">Usuarios bloqueados</button>
            <div class="accordion-content" id="gastos-lista">
                <hr>
                <h4>Lista de Ip</h4>
                <?php

                $archivo_bloqueo = ROOT_DIR.'controllers/bloqueos.txt';
                $bloqueados = [];
                if (file_exists($archivo_bloqueo)) {
                    $bloqueados = json_decode(file_get_contents($archivo_bloqueo), true);
                }
                ?>
                <div class="container-fluid tabla-scroll">
                    <table id="tabla-gastos" border="1">
                        <thead>
                        <tr><th>Ip</th><th>Estado</th><th>Fecha</th><th>Acción</th></tr>
                        </thead>
                        <tbody>
                        <?php  foreach ($bloqueados as $item=>$tmp){



                        ?>
                        <tr>
                            <td><?php echo $item  ?></td>
                            <td><?php echo $tmp['bloqueado']  ?></td>
                            <td><?php $fecha = date("d/m/Y", $tmp['fecha']);  echo $fecha;  ?></td>
                            <?php if ($tmp['bloqueado']){ ?>
                                <td><button class="btn btn-success" onclick="editarbloqueo(<?php echo "'".$item."'" ?>,<?php echo "'".$restaurantid."'" ?>)">Desbloquear ip</button></td>

                            <?php
                            }else{
                            ?>
                                <td><button class="btn btn-warning" onclick="editarbloqueo(<?php echo "'".$item."'" ?>,<?php echo "'".$restaurantid."'" ?>)">Bloquear ip</button></td>
                            <?php
                            }
                            ?>
                        </tr>

                        <?php
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
    </div>




<!--
    <div class="col-xl-4">
<div class="form-validation">
    <form enctype="multipart/form-data" class="form-valide" action="controllers/qr_pago.php" method="POST">

        <?php /*if (!file_exists($ruta)){  */?>
            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h5 class="card-title">Configura tu QR para transferir dinero a tu cuenta</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-form-label">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="nro_tarjeta">Nro cuenta <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="nro_tarjeta" name="nro_tarjeta" value="" placeholder="Inserte el nro de su tarjeta..">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="telefono">Nro tel&eacute;fono <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <?php /*if (empty($resultado[0]->telefono)){*/?>
                                    <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="Nro de telefono para confirmar la transferencia" value=""></input>
                                    <?php /*}else{ */?>
                                        <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="<?php /*echo $resultado[0]->telefono;*/?>" value="<?php /*echo $resultado[0]->telefono;*/?>"></input>
                                    <?php /*} */?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" name="agregar" id="agregar">
                    <button type="button" class="btn btn-dark text-white" data-dismiss="modal">Cancelar</button>
                    <button  type="submit" class="btn btn-warning text-white">Agregar</button>
                </div>
            </div>
            <?php /* }else{ */?>

            <div class="card mb-3">


                <div class="card-header pb-0">
                    <h5 class="card-title">Cuenta de Transfermovil</h5>
                </div>
                <div class="card horizontal-card__menu mb-0 horizontal">
                    <div class="horizontal-card__menu--image">
                        <img src="/mipymessales/images/transferencia_transfermovil.png"  alt="menu">
                    </div>
                    <div class="card-body">
                        <div id="mostrar">
                        <h4 class="horizontal-card__menu--title d-flex justify-content-between">Datos de la cuenta</h4>

                        <p class="d-flex justify-content-between">Nro cuenta: <?php /*echo $resultado[0]->nro_cuenta;*/?></p>

                        <p class="d-flex justify-content-between">Tel&eacute;fono: <?php /*echo $resultado[0]->telefono;*/?></p>
                        </div>


                        <div id="actualizar" style="display: none">
                            <form enctype="multipart/form-data" class="form-valide" action="controllers/qr_pago.php" method="POST">
                            <div class="col-12 col-form-label">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="nro_tarjeta">Nro cuenta <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="nro_tarjeta" name="nro_tarjeta" value="" placeholder="Inserte el nro de su tarjeta..">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="telefono">Nro tel&eacute;fono <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-8">
                                        <?php /*if (empty($resultado[0]->telefono)){*/?>
                                            <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="Nro de telefono para confirmar la transferencia" value=""></input>
                                        <?php /*}else{ */?>
                                            <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="<?php /*echo $resultado[0]->telefono;*/?>" value="<?php /*echo $resultado[0]->telefono;*/?>"></input>
                                        <?php /*} */?>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>



                    </div>
                </div>

                <div class="card-footer">
                    <button  onclick="editarCuenta()" class="btn btn-warning text-white" >Editar</button>
                </div>
            </div>
        <?php /*} */?>


    </form>
</div>

    </div>-->

</div>
<!--<button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter" >Nuevo</button>-->
<div class="form-validation">
    <form enctype="multipart/form-data" class="form-valide" action="controllers/trabajadorController.php" method="POST" id="main-contact-form">
        <div class="modal fade bd-example-modal-lg" id="exampleModalCenter">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: black">Agregando nuevo trabajador ...</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">



                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title" style="color: black">Inserte los datos del trabajador</h4>
                            </div>
                            <div class="card-body">





                                <div class="media media-reply">



                                    <!-- <img class="mr-0 mr-lg-3 rounded-circle" src="../images/blank1.jpg" width="50" height="50" alt="Generic placeholder image">-->

                                    <input type="file" class="dropify mr-0 mr-lg-3 rounded-circle" name="image"  height="50" id="image" data-default-file="" />




                                </div>
                                <div class="media-body">
                                    <div class="d-lg-flex justify-content-between mb-2">


                                        <div class="basic-form col-12">
                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="nombrep">Nombre <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="nombrep" name="nombrep" placeholder="Inserte el nombre del trabajador..">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="ci">Carnet Indentidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" maxlength="11" id="ci" name="ci" placeholder="Inserte el CI del trabajador..">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="tel">Teléfono <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Inserte el teléfono del trabajador..">
                                                    </div>
                                                </div>
                                            </div>

                                            <!--  <div class="col-form-label">
                                                  <div class="form-group row">
                                                      <label class="col-lg-4 col-form-label" for="dir">Dirección <span class="text-danger">*</span>
                                                      </label>
                                                      <div class="col-lg-8">
                                                          <input type="text" class="form-control" id="dir" name="dir" placeholder="Inserte la dirección del trabajador..">
                                                      </div>
                                                  </div>
                                              </div>-->

                                            <!--   <div class="col-form-label">
                                                   <div class="form-group row">
                                                       <label class="col-lg-4 col-form-label" for="exp">Experiencia <span class="text-danger">*</span>
                                                       </label>
                                                       <div class="col-lg-8">
                                                           <input type="text" class="form-control" id="exp" name="exp" placeholder="Inserte los años de experiencia del trabajador..">
                                                       </div>
                                                   </div>
                                               </div>-->

                                            <h4 class="card-title mt-5" style="color: black">Estado de disponibilidad </h4>
                                            <div class="basic-form">

                                                <label class="radio-inline">
                                                    <input type="radio" name="radio" id="radio" value="d" checked="true"> Activo</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="radio" id="radio" value="a"> Inactivo</label>

                                            </div>

                                            <h4 class="card-title mt-5" style="color: black">Selecciona los accesos al panel de administración </h4>
                                            <div class="basic-form">
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="ofertas"> Ofertas</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="pedidoscliente"> Pedidos</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="ventasproducto"> Ventas</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="cierresventa"> Cierres</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="configuracion"> Configuración del sitio</label>


                                            </div>

                                            <div id="accordion-faq" class="accordion">
                                                <div class="card">
                                                    <div class="card-header">


                                                        <div class="form-check form-check-inline">

                                                            <label class="form-check-label">
                                                                <h5 class="mb-0 collapsed c-pointer" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1" style="color: black"><i class="fa" aria-hidden="true"></i>
                                                                    <input class="form-check-input" name="acceso" id="acceso" type="checkbox">
                                                                    Permitir acceso al sitio web
                                                                </h5>

                                                            </label>
                                                        </div>


                                                    </div>
                                                    <div id="collapseOne1" class="collapse" data-parent="#accordion-faq">
                                                        <div class="card-body">

                                                            <div class="col-12 col-form-label">
                                                                <div class="form-group row">
                                                                    <label class="col-lg-4 col-form-label" for="user">Usuario<span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-lg-8">
                                                                        <input type="text" class="form-control" id="user" name="user" placeholder="Inserte el usuario del trabajador..">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-form-label">
                                                                <div class="form-group row">
                                                                    <label class="col-lg-4 col-form-label" for="pass">Contraseña<span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-lg-8">
                                                                        <input type="text" class="form-control" id="pass" name="pass" placeholder="Inserte la contraseña del trabajador..">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>



                                            </div>



                                        </div>
                                    </div>



                                </div>



                            </div>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="idrestaurant" name="idrestaurant" value="<?php echo $restaurantid; ?>">
                        <input type="hidden" id="action" name="action" value="insert">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button  type="submit" class="btn btn-success">Agregar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<!--<script src="../assets/js/dropify.min.js"></script>
<script src="../assets/js/dropify-init.js"></script>-->
<script>

    window.onload = function () {
        $('.dropify').dropify();
    };

document.getElementById("loader").style.display = "none";


    function cargarGastos() {
        fetch('controllers/config_gastos.php')
            .then(r => r.json())
            .then(gastos => {
                const tbody = document.querySelector('#tabla-gastos tbody');
                tbody.innerHTML = '';
                gastos.forEach(g => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${g.concepto}</td>
                    <td>$${parseFloat(g.monto).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-warning" onclick='abrirModalEditar(${JSON.stringify(g)})'>Editar</button>
                        <button class="btn btn-danger" onclick='eliminarGasto(${g.id})'>Eliminar</button>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            });

        document.querySelectorAll('.accordion-header').forEach(header => {
            // Solo actualizar si ya está expandido
            const content = header.nextElementSibling;
            if (content.classList.contains("expanded")) {
                content.style.maxHeight = "100%";
            }
        });
    }
    function editarbloqueo(ip,restaurantid) {
        fetch('controllers/config_bloqueo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ip,restaurantid})
        }).then(() => {
            location.reload();
           // Swal.fire('Guardado', 'Bloqueo actualizado con éxito', 'success');

        });
    }

    function abrirModalNuevo() {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        const fechaHoy = `${yyyy}-${mm}-${dd}`;
        Swal.fire({
            title: 'Nuevo Gasto',
            html: `
            <input type="date" id="swal-fecha" class="swal2-input" placeholder="Fecha" value="${fechaHoy}">
            <input type="text" id="swal-concepto" class="swal2-input" placeholder="Concepto">
            <input type="number" id="swal-monto" class="swal2-input" placeholder="Monto" step="0.01">
        `,
            confirmButtonText: 'Guardar',
            showCancelButton: true,
            preConfirm: () => {
                // Establecer valor en el input
                const fecha = document.getElementById('swal-fecha').value;
                const concepto = document.getElementById('swal-concepto').value.trim();
                const monto = document.getElementById('swal-monto').value;

                if (!fecha || !concepto || !monto) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                return { fecha, concepto, monto };
            }
        }).then(res => {
            if (res.isConfirmed) {
                fetch('controllers/config_gastos.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(res.value)
                }).then(() => {
                    Swal.fire('Guardado', 'Gasto registrado con éxito', 'success');
                    cargarGastos();
                });
            }
        });
    }

    function abrirModalEditar(gasto) {
        Swal.fire({
            title: 'Editar Gasto',
            html: `
            <input type="date" id="swal-fecha" class="swal2-input" value="${gasto.fecha}">
            <input type="text" id="swal-concepto" class="swal2-input" value="${gasto.concepto}">
            <input type="number" id="swal-monto" class="swal2-input" value="${gasto.monto}" step="0.01">
        `,
            confirmButtonText: 'Actualizar',
            showCancelButton: true,
            preConfirm: () => {
                const fecha = document.getElementById('swal-fecha').value;
                const concepto = document.getElementById('swal-concepto').value.trim();
                const monto = document.getElementById('swal-monto').value;

                if (!fecha || !concepto || !monto) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                return { id: gasto.id, fecha, concepto, monto };
            }
        }).then(res => {
            if (res.isConfirmed) {
                fetch('controllers/config_gastos.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(res.value)
                }).then(() => {
                    Swal.fire('Actualizado', 'Gasto modificado correctamente', 'success');
                    cargarGastos();
                });
            }
        });
    }


    function cerrarModal() {
        document.getElementById('modal-gasto').style.display = 'none';
    }

    document.getElementById('form-gasto').addEventListener('submit', e => {
        e.preventDefault();
        const id = document.getElementById('gasto-id').value;
        const payload = {
            fecha: document.getElementById('gasto-fecha').value,
            concepto: document.getElementById('gasto-concepto').value,
            monto: document.getElementById('gasto-monto').value,
        };

        const method = id ? 'PUT' : 'POST';
        if (id) payload.id = id;

        fetch('controllers/config_gastos.php', {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        }).then(() => {
            cerrarModal();
            cargarGastos();
        });
    });

    function eliminarGasto(id) {
        Swal.fire({
            title: '¿Eliminar gasto?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('controllers/config_gastos.php', {
                    method: 'DELETE',
                    body: new URLSearchParams({ id })
                }).then(() => {
                    Swal.fire('Eliminado', 'El gasto ha sido eliminado', 'success');
                    cargarGastos();
                });
            }
        });
    }


    cargarGastos();
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



function mostrarLoader() {
    document.getElementById("loader").style.display = "flex";
}

document.querySelectorAll('.toggle-password').forEach(function (iconWrapper) {
    iconWrapper.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const svg = this.querySelector('svg');
        const isVisible = input.type === "text";

        // Cambiar tipo de input
        input.type = isVisible ? "password" : "text";

        // Cambiar contenido del SVG
        if (!isVisible) {
            // Ojo abierto
            svg.innerHTML = `
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
              <circle cx="12" cy="12" r="3"/>
            `;
        } else {
            // Ojo cerrado
            svg.innerHTML = `
              <path d="M17.94 17.94A10.94 10.94 0 0112 19c-5 0-9.27-3.11-11-7
                       1.21-2.77 3.29-5.05 5.88-6.25M10.58 10.58a3 3 0 004.24 4.24M6.1 6.1
                       L1 1m22 22L2 2"/>
            `;
        }
        svg.style.fill="none";
        svg.style.width="auto";
        svg.style.height="auto";
    });
});



//Horarios
const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

// Horario por defecto
let schedule = {
    'Lunes': ['07:00', '19:00'],
    'Martes': ['07:00', '19:00'],
    'Miércoles': ['07:00', '19:00'],
    'Jueves': ['07:00', '19:00'],
    'Viernes': ['07:00', '19:00'],
    'Sábado': ['07:00', '19:00'],
    'Domingo': ['07:00', '19:00']
};

// Si viene desde PHP, reemplazar
<?php if ($horarioRestaurant): ?>
schedule = <?php echo json_encode($horarioRestaurant, JSON_UNESCAPED_UNICODE); ?>;
<?php endif; ?>

// Generar la tabla
const tbody = document.getElementById('horario-table');
for (const dia of dias) {
    const horas = schedule[dia] || ['07:00', '19:00'];
    tbody.innerHTML += `
        <tr>
          <td>${dia}</td>
          <td><input type="time" value="${horas[0]}" onchange="actualizarHorario('${dia}', 0, this.value)"></td>
          <td><input type="time" value="${horas[1]}" onchange="actualizarHorario('${dia}', 1, this.value)"></td>
        </tr>
      `;
}

function actualizarHorario(dia, index, value) {
    if (!schedule[dia]) schedule[dia] = ['07:00', '19:00'];
    schedule[dia][index] = value;
    document.getElementById("jsonInput").value = JSON.stringify(schedule);
    document.getElementById("horariolaborarl").value = JSON.stringify(schedule);

}

// Inicializar input oculto
document.getElementById("jsonInput").value = JSON.stringify(schedule);
document.getElementById("horariolaborarl").value = JSON.stringify(schedule);



const form = document.getElementById("formHorario");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmpassword");
const mensaje = document.getElementById("mensaje");

form.addEventListener("submit", function (e) {
    if (password.value !== confirmPassword.value && confirmPassword.value!=='') {
        e.preventDefault(); // evita el envío del formulario
        mensaje.textContent = "⚠️ Las contraseñas no coinciden.";
    } else {
        mensaje.textContent = "";
    }
});
confirmPassword.addEventListener("input", function () {
    if (password.value !== confirmPassword.value && confirmPassword.value!=='') {
        mensaje.textContent = "⚠️ Las contraseñas no coinciden.";
    } else {
        mensaje.textContent = "";
    }
});

</script>