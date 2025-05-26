<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,1).'/');
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
$usuario_generado=$segments[1];
$contrasena_generada=$segments[2];

if (isset($usuario_generado) && isset($contrasena_generada) && isset($nroMesa)
    && !SqlInjectionUtils::checkSqlInjectionAttempt($usuario_generado) && !SqlInjectionUtils::checkSqlInjectionAttempt($contrasena_generada)
    && !SqlInjectionUtils::checkSqlInjectionAttempt($nroMesa)){
    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;


    $sentencia = $base_de_datos->prepare("SELECT user,password FROM mesa WHERE id =:idmesa and disponible= 1");
    $sentencia->bindParam(':idmesa', $nroMesa);

    try {
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        if (!empty($resultado) && hash_equals($usuario_generado, $resultado[0]->user) && hash_equals($contrasena_generada, $resultado[0]->password)){

                $sentencia = $base_de_datos->prepare("INSERT INTO cliente (full_name, id_mesa) VALUES ('Cliente', :id_mesa)");
                $sentencia->bindParam(':id_mesa', $nroMesa);

                try{
                    $sentencia->execute();
                    $idcliente=$base_de_datos->lastInsertId();
                    $stm = $base_de_datos->prepare("UPDATE mesa set disponible=0, url_login=:url WHERE id=:idmesa");
                    $stm->bindParam(':idmesa', $nroMesa);
                    $url=Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$nroMesa/$idcliente",cifrado::getClaveSecreta());
                    $stm->bindParam(':url', $url);
                    $stm->execute();




// Redirigir a la URL limpia
                    header('Location: ' . $url);

                    //header("Location: templates/cartacliente.php?mesa=".$idmesa."&idcliente=".$idcliente);
                }catch (Exception $e){
                    echo  print_r($e->getTraceAsString());
                    header("Location:" .   Host::getHOSTNAME(). "templates/404.php");
                }


        }else{
            header("Location:" .Host::getHOSTNAME(). "templates/404.php");
        }

    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
        header("Location:".   Host::getHOSTNAME()."templates/404.php");
    }

}else{

    if (isset($_POST['login']) && isset($_POST['usuario']) && isset($_POST['contra']) && !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
        include_once ROOT_DIR."pdo/conexion.php";
        include_once ROOT_DIR."controllers/cifrado.php";
        include_once ROOT_DIR."controllers/Host.php";
        global $base_de_datos;
        $user = $_POST['usuario'];
        $pass = $_POST['contra'];
        $mesa=$_REQUEST["mesaid"];
// Consulta segura


        $sentencia = $base_de_datos->prepare("SELECT password FROM mesa WHERE user =:usuario and id =:id");
        $sentencia->bindParam(':usuario', $user);
        $sentencia->bindParam(':id', $mesa);
        try {
            $sentencia->execute();
        } catch (Exception $e) {
            echo print_r($e->getTraceAsString());
        }


        if ($sentencia->rowCount() === 1) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
            if (hash_equals($pass, $resultado[0]->password)) {

                $stmc = $base_de_datos->prepare("INSERT INTO cliente (full_name, id_mesa) VALUES ('Cliente', :id_mesa)");
                $stmc->bindParam(':id_mesa', $mesa);

                try{
                    $stmc->execute();
                    $idcliente=$base_de_datos->lastInsertId();
                    $stm = $base_de_datos->prepare("UPDATE mesa set disponible=0, url_login=:url WHERE id=:idmesa");
                    $stm->bindParam(':idmesa', $mesa);
                    $url=Host::getHOSTNAME()."templates/cartacliente.php/".cifrado::cifrar_url("$mesa/$idcliente",cifrado::getClaveSecreta());
                    $stm->bindParam(':url', $url);
                    $stm->execute();




// Redirigir a la URL limpia
                    header('Location: ' . $url);


                    //header("Location: templates/cartacliente.php?mesa=".$idmesa."&idcliente=".$idcliente);
                }catch (Exception $e){
                    echo  print_r($e->getTraceAsString());
                    header("Location:" .   Host::getHOSTNAME(). "templates/404.php");
                }
            } else {
                $incorrecta="¡Contraseña incorrecta!";
            }
        } else {
            $incorrecta="¡Usuario no encontrado!";
        }
    }

    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $sentencia = $base_de_datos->query("select * from mesa where disponible=1");
    $mesas = $sentencia->fetchAll(PDO::FETCH_OBJ);
    if (!$mesas) {
        echo "<h1>No existen mesas disponibles en el salon !</h1>";
    }else{


    ?>


    <!DOCTYPE html>
    <html lang="en" class="h-100">


    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Restaurant X</title>
        <!-- Favicon icon -->

        <!-- Custom Stylesheet -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="app.css">
        <link href="assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <style>


            body {
                font-family: Arial, sans-serif;
                text-align: center;
            }
            select {
                padding: 10px;
                font-size: 16px;
            }

        </style>
    </head>

    <body class="h-100">
    <div class="login-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-5">
                    <div class="form-input-content">
                        <div class="card card-login">
                            <div class="text-center my-3" style="padding: 20px 10px 10px 10px;">
                                <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="images/logo.png"width="180" height="180" alt="">
                            </div>
                            <div class="card-body">
                                <form action="mesa.php" method="POST">
                                    <div class="form-group row">
                                        <label class="col-lg-12 col-form-label" for="nombrep">Elige la mesa: <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-12">
                                            <select class="btn btn-primary btn-ft dropdown-toggle" name="mesaid" id="mesaid">

        <?php foreach($mesas as $mesa){
            $nro_mesa= $mesa->id;
            ?>

                                                <option value="<?php echo $nro_mesa?>">Mesa <?php echo $nro_mesa?></option>
        <?php }
        ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control rounded-0 bg-transparent" name="usuario" id="usuario" placeholder="Usuario de la mesa">
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="password" class="form-control rounded-0 bg-transparent" name="contra" id="contra" placeholder="Contraseña para acceder a la mesa">
                                    </div>

                                    <div class="form-group ml-3 mb-5">
                                        <!--<input id="checkbox1" type="checkbox">-->
                                        <!--<label class="label-checkbox ml-2 mb-0" for="checkbox1">Recordar contraseña</label>-->
                                        <?php  if(isset($incorrecta)){ ?>
                                            <label class="alert-danger"><?php echo $incorrecta;?></label>

                                        <?php  }  ?>


                                    </div>
                                    <input type="hidden" id="login" name="login" value="true">
                                    <button class="btn btn-primary btn-block border-0" type="submit">Entrar</button>
                                </form>
                            </div>
                            <div class="card-footer text-center border-0 pt-0">
                                <p class="mb-1">No tienes credenciales ?</p>
                                <h6><a href="notificar.php">Notificar al administrador</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
    <!-- Custom script -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    </body>


    </html>






    <?php }
} ?>