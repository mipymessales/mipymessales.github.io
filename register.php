<?php
// Inicializa la sesión
session_start();

// Obtén la sección actual (por ejemplo, desde la URL)

if (!empty($_POST["username"]) && !empty($_POST["telefono"])  && !empty($_POST["password"])  && !empty($_POST["confirmpassword"])  && !empty($_POST["adminpassword"]) ){
if (hash_equals($_POST["adminpassword"], "123ok")){
    defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,1).'/');
    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("INSERT INTO admin (telefono, usuario,contrasena) VALUES (:telefono, :usuario, :contrasena)");
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash seguro
    $sentencia->bindParam(':telefono', $_POST['telefono']);
    $sentencia->bindParam(':usuario', $_POST["username"]);
    $sentencia->bindParam(':contrasena', $pass);
    try{
        $sentencia->execute();
        $tiempo_vida = 1800;
        ini_set('session.gc_maxlifetime', $tiempo_vida);
        session_set_cookie_params($tiempo_vida);
        $_SESSION['user'] = $_POST["username"];
        header('Location: index.php?section=salon');
    }catch (Exception $e){
        echo  print_r($e->getTraceAsString());
    }

}else{
    $incorrecta="¡Contraseña de administrador incorrecta, no puede realizar la acción de registro!";
}


}else{
    $incorrecta="¡Rellena todos los campos para un registro satisfactorio !";
}


?>


<!DOCTYPE html>
<html lang="en" class="h-100">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Manager</title>
    <!-- Favicon icon -->

    <!-- Custom Stylesheet -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
    <link href="assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        #loader {
            display: none;
            position: fixed;
            z-index: 999;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: sans-serif;
        }

        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        form {
            margin-top: 100px;
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        select {
            padding: 10px;
            font-size: 16px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding-right: 35px;
            padding: 10px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }

    </style>
    
</head>

<body>
<div id="loader">
    <div class="spinner"></div>
    <div>Procesando datos...</div>
</div>
    <div class="h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-5">
                    <div class="form-input-content">
                        <div class="card card-login">
                            <div class="text-center my-3" style="padding: 20px 10px 10px 10px;">
                                <img src="images/logo.png" width="100%" height="100%" alt="">
                            </div>
                            <div class="card-body">
                                <form action="register.php" method="POST" onsubmit="mostrarLoader()">
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control rounded-0 bg-transparent" name="username" placeholder="Username">
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control rounded-0 bg-transparent" name="telefono" placeholder="Teléfono">
                                    </div>



                                    <div class="form-group mb-4 input-group">
                                        <input type="password" id="password" name="password"  placeholder="Contraseña">
                                        <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                                    </div>

                                    <div class="form-group mb-4 input-group">
                                        <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirmar contraseña">
                                        <i class="fa-solid fa-eye toggle-password" data-target="confirmpassword"></i>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="label-checkbox ml-2 mb-0" for="adminpassword">Contraseña de confirmaci&oacute;n y valid&eacute;z</label>
                                        <input type="password" class="form-control rounded-0 bg-transparent" id="adminpassword" name="adminpassword" placeholder="Contraseña de administración">
                                    </div>

                                    <div class="form-group ml-3 mb-5">
                                        <!--<input id="checkbox1" type="checkbox">-->
                                        <!--<label class="label-checkbox ml-2 mb-0" for="checkbox1">Recordar contraseña</label>-->
                                        <?php  if(isset($incorrecta)){ ?>
                                            <label class="alert-danger"><?php echo $incorrecta;?></label>

                                        <?php  }  ?>


                                    </div>
                                    <button class="btn-primary btn-lg btn-block border-0" type="submit">Registrar</button>
                                </form>
                            </div>
                            <div class="card-footer text-center border-0 pt-0">
                                <p class="mb-1">Ya tienes una cuenta ?</p>
                                <h6><a href="login.php">Loguearme</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        document.getElementById("loader").style.display = "none";
    });
    function mostrarLoader() {
        document.getElementById("loader").style.display = "flex";
    }
    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const isVisible = input.type === "text";

            input.type = isVisible ? "password" : "text";
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    });
</script>
</body>

</html>