<?php
// Inicializa la sesión
session_start();

include_once "pdo/conexion.php";
// Obtén la sección actual (por ejemplo, desde la URL)


if (isset($_POST['usuario']) && isset($_POST['contra'])){
    global $base_de_datos;
    $user = $_POST['usuario'];
    $pass = $_POST['contra'];

// Consulta segura


    $sentencia = $base_de_datos->prepare("SELECT contrasena FROM admin WHERE usuario =:usuario");
    $sentencia->bindParam(':usuario', $user);

    try {
        $sentencia->execute();
    } catch (Exception $e) {
        echo print_r($e->getTraceAsString());
    }


    if ($sentencia->rowCount() === 1) {
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        if (password_verify($pass, $resultado[0]->contrasena)) {
            $tiempo_vida = 1800;
            ini_set('session.gc_maxlifetime', $tiempo_vida);
            session_set_cookie_params($tiempo_vida);
            $_SESSION['user'] = $user;
            header("Location: /panel");
            exit;
            //header('Location: panel');
        } else {
            $incorrecta="¡Contraseña incorrecta!";
        }
    } else {
        $incorrecta="¡Usuario no encontrado!";
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
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control rounded-0 bg-transparent" name="usuario" id="usuario" placeholder="Usuario">
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="password" class="form-control rounded-0 bg-transparent" name="contra" id="contra" placeholder="Contraseña">
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
                                <h6><a href="register.php">Crear nueva cuenta</a></h6>
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