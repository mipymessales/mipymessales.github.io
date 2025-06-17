<?php
// Inicializa la sesión
session_start();

include_once "pdo/conexion.php";
// Obtén la sección actual (por ejemplo, desde la URL)
require_once "controllers/class.SqlInjectionUtils.php";

if (!SqlInjectionUtils::checkSqlInjectionAttempt($_POST) && isset($_POST['usuario']) && isset($_POST['contra'])){
    global $base_de_datos;
    $user = $_POST['usuario'];
    $pass = $_POST['contra'];

// Consulta segura


    $sentencia = $base_de_datos->prepare("SELECT id,contrasena,restaurantid FROM admin WHERE usuario =:usuario");
    $sentencia->bindParam(':usuario', $user);

    try {
        $sentencia->execute();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }


    if ($sentencia->rowCount() === 1) {
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
       $contrasena= $resultado[0]->contrasena;
        if (password_verify($pass,$contrasena)) {
            $tiempo_vida = 1800;
            ini_set('session.gc_maxlifetime', $tiempo_vida);
            session_set_cookie_params($tiempo_vida);
            $_SESSION['user'] = $user;
            $_SESSION['userrolid'] = 33333;
            $_SESSION['idrestaurant'] = $resultado[0]->restaurantid;
            header("Location: /panel");
            exit;
            //header('Location: panel');
        } else {
            $incorrecta="¡Contraseña incorrecta!";
        }
    } else {
        $sentencia = $base_de_datos->prepare("SELECT id_rol_usuario as id,contrasena_usuario as contrasena,restaurantid FROM trabajador WHERE nombre_usuario =:usuario");
        $sentencia->bindParam(':usuario', $user);

        try {
            $sentencia->execute();
        } catch (Exception $e) {
            echo print_r($e->getTraceAsString());
        }

        if ($sentencia->rowCount() === 1) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
            $contrasena= $resultado[0]->contrasena;
            if (password_verify(bin2hex($pass),$contrasena)) {
                $tiempo_vida = 1800;
                ini_set('session.gc_maxlifetime', $tiempo_vida);
                session_set_cookie_params($tiempo_vida);
                $_SESSION['user'] = $user;
                $_SESSION['userrolid'] = $resultado[0]->id;
                $_SESSION['idrestaurant'] = $resultado[0]->restaurantid;
                header("Location: /panel");
                exit;
                //header('Location: panel');
            } else {
                $incorrecta="¡Contraseña incorrecta!";
                header("Location: /login");
            }
        }else{
            $incorrecta="¡Usuario no encontrado!";
            header("Location: /login");
        }
    }
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


        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        select {
            padding: 10px;
            font-size: 16px;
        }
        .input-wrapper {
            position: relative;
            width: 100%;
            /*max-width: 300px;*/
        }

        .input-wrapper input {
            width: 100%;
            padding-right: 40px; /* espacio para el ícono */
            padding: 10px;
            font-size: 16px;
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

<body class="h-100">
    <div class="login-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-5">
                    <div class="form-input-content">
                        <div class="card card-login">
                            <div class="text-center my-3" style="padding: 20px 10px 10px 10px;">
                                <img src="images/logo.png" width="100%" height="100%" alt="">
                            </div>
                            <div class="card-body">
                                <form action="login.php" method="POST">
                                    <div class="form-group mb-4 input-wrapper">
                                        <input type="text"  name="usuario" id="usuario" placeholder="Usuario">
                                    </div>
                                 <!--   <div class="form-group mb-4">
                                        <input type="password" class="form-control rounded-0 bg-transparent" name="contra" id="contra" placeholder="Contraseña">
                                    </div>-->
                                    <div class="form-group mb-4 input-wrapper">
                                        <!--  <input type="password" id="password" name="password" placeholder="Contraseña">
                                          <i class="fa-solid fa-eye toggle-password" data-target="password"></i>-->


                                        <input type="password" id="contra" name="contra" placeholder="Contraseña">
                                        <span class="toggle-password" data-target="contra">
    <!-- SVG Ojo abierto -->
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         viewBox="0 0 24 24" class="icon-eye">
      <path d="M1 12C3 7 7.5 4 12 4s9 3 11 8c-2 5-6.5 8-11 8S3 17 1 12z"/>
      <circle cx="12" cy="12" r="3"/>
    </svg>
  </span>



                                    </div>
                                    <div class="form-group ml-3 mb-5">
                                        <!--<input id="checkbox1" type="checkbox">-->
                                        <!--<label class="label-checkbox ml-2 mb-0" for="checkbox1">Recordar contraseña</label>-->
                                        <?php  if(isset($incorrecta)){ ?>
                                            <label class="alert-danger"><?php echo $incorrecta;?></label>

                                        <?php  }  ?>


                                    </div>
                                    <button class="btn btn-primary btn-block border-0" type="submit">Entrar</button>
                                </form>
                            </div>
                            <div class="card-footer text-center border-0 pt-0">
                                <p class="mb-1">No tienes una cuenta creada ?</p>
                                <h6><a href="/register">Crear nueva cuenta</a></h6>
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
<script>
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
        });
    });
</script>
</body>


</html>