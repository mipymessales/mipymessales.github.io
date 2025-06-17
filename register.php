<?php
// Inicializa la sesión
session_start();

// Obtén la sección actual (por ejemplo, desde la URL)
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 1) . '/');

require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
if (!empty($_POST["username"]) && !empty($_POST["telefono"]) && !empty($_POST["password"]) && !empty($_POST["confirmpassword"]) && !empty($_POST["adminpassword"]) &&
    !SqlInjectionUtils::checkSqlInjectionAttempt($_POST)) {
    if (hash_equals($_POST["adminpassword"], "123ok")) {
        include_once ROOT_DIR . "pdo/conexion.php";
        global $base_de_datos;
        try {
                $incorrecta = "";

                /*IMG logic*/

                $imagess = $_POST["foto"] ?? "blank1.jpg";

                $target_dir = "/images/"; //directorio en el que se subira
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
                    if ($_FILES["image"]["size"] > 500000) {
                        $incorrecta .= "El archivo es muy pesado </br>";
                        $uploadOk = 0;
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
                                echo "El archivo " . basename($_FILES["image"]["name"]) . " Se subio correctamente";
                            } else {
                                $incorrecta .= "Error al cargar el archivo";

                            }
                        } else {
                            $incorrecta .= "Error al subir la imagen: " . $_FILES["image"]["error"] . "</br>";
                        }


                    }
                }


                /*END IMG logic*/


                $stm = $base_de_datos->prepare("INSERT INTO restaurant_info (nombre, telefono, direccion, horario, ubicacion, foto_portada) VALUES (:nombre, :telefono, :direccion, :horario, :ubicacion, :foto_portada) ");

                $stm->bindParam(':nombre', $_POST['nombrerestaurant']);
                $stm->bindParam(':telefono', $_POST["telefonocontacto"]);
                $stm->bindParam(':direccion', $_POST["direccion"]);
                $h = json_encode($_POST["horariolaborarl"]);
                $stm->bindParam(':horario', $h);
                $stm->bindParam(':ubicacion', $_POST["ubicacion"]);
                $stm->bindParam(':foto_portada', $imagess);
                if ($stm->execute()) {
                    $idrestaurant=$base_de_datos->lastInsertId();

                    $sentencia = $base_de_datos->prepare("INSERT INTO admin (telefono, usuario,contrasena,restaurantid) VALUES (:telefono, :usuario, :contrasena,:restaurantid)");
                    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash seguro
                    $sentencia->bindParam(':telefono', $_POST['telefono']);
                    $sentencia->bindParam(':usuario', $_POST["username"]);
                    $sentencia->bindParam(':contrasena', $pass);
                    $sentencia->bindParam(':restaurantid', $idrestaurant);
                    $sentencia->execute();

                    /*Generar Controlador*/

                    // === CONFIGURACIÓN ===

                    $nombreBase = trim($_POST['nombrerestaurant']);
                    $nombreBase = preg_replace('/[^a-zA-Z0-9]/', '', $nombreBase);
                    $nombreBase = ucfirst(strtolower($nombreBase));
                    $nombreLower = strtolower($nombreBase);         // Ej: 'restaurantx'
                    $className = $nombreBase . 'Controller';        // Ej: 'RestaurantXController'

// === ARCHIVOS ===
                    $controllerFile = "controllers/{$className}.php";
                    $plantillaVista ='restaurant.php';
                    $vistaDestino ="{$nombreLower}.php";

// === CREAR CONTROLADOR ===
                    $contenidoControlador = <<<PHP
<?php
class $className {
    public function index() {
        // Aquí podrías cargar datos del modelo y luego mostrar la vista
        defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
        require_once ROOT_DIR . '$nombreLower.php';
    }

    public function login() {
        // Ejemplo de otra acción
        defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
        require_once ROOT_DIR . 'login.php';
    }
}
PHP;

                    file_put_contents($controllerFile, $contenidoControlador);
                    echo "✅ Controlador creado: $controllerFile\n";

// === COPIAR Y MODIFICAR LA VISTA ===
                    if (file_exists($plantillaVista)) {
                        $contenidoVista = file_get_contents($plantillaVista);

                        // Inyectar variable al principio
                        $variableExtra = "<?php \$id = $idrestaurant; ?>\n\n";
                        $nuevoContenido = $variableExtra . $contenidoVista;

                        file_put_contents($vistaDestino, $nuevoContenido);
                        echo "✅ Vista creada con variable extra: $vistaDestino\n";
                    } else {
                        echo "❌ Plantilla no encontrada: $plantillaVista\n";
                    }

                    /*Generar Controlador*/

                    $tiempo_vida = 1800;
                    ini_set('session.gc_maxlifetime', $tiempo_vida);
                    session_set_cookie_params($tiempo_vida);
                    $_SESSION['user'] = $_POST["username"];
                    $_SESSION['userrolid'] = 33333;
                    $_SESSION['idrestaurant'] = $idrestaurant;
                    header("Location: https/panel");
                }


        } catch (Exception $e) {
            echo print_r($e->getTraceAsString());
        }

    } else {
        $incorrecta = "¡Contraseña de administrador incorrecta, no puede realizar la acción de registro!";
        // header('Location: /mipymessales/register');
    }


} else {
    $incorrecta = "¡Rellena todos los campos para un registro satisfactorio !";
    // header('Location: /mipymessales/register');
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
    <link href="assets/css/dropify.min.css" rel="stylesheet">
    <style>
        #loader {
            display: none;
            position: fixed;
            z-index: 999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        form {
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

        .section-title {
            font-family: "Arial", sans-serif;
            justify-content: center;
            font-weight: 300;
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        input[type="time"] {
            width: 100%;
            padding: 5px;
            font-size: 1rem;
        }

        .error {
            background-color: #ffe0e0;
        }

        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            font-size: 1.1rem;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Diseño responsive para móviles */
        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                background: #f0f0f0;
                border-radius: 8px;
                padding: 10px;
            }

            td {
                padding: 10px 0;
                text-align: right;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                text-align: left;
                font-weight: bold;
            }
        }

    </style>

</head>

<body>
<div id="loader">
    <div class="spinner"></div>
    <div>Procesando datos...</div>
</div>
<form action="register.php" method="POST" id="formHorario" onsubmit="mostrarLoader()">

    <div class="container h-100" style="margin-bottom: 20px">
        <div class="text-center my-3" style="padding: 20px 10px 10px 10px;">
            <img src="images/logo.png" width="100%" height="100%" alt="">
        </div>
        <div class="h-100">
            <div class="container h-100">
                <div class="row justify-content-center h-100">
                    <div class="col-md-6">
                        <div class="form-input-content">

                            <div class="card-body">
                                <h3 class="section-title">Información del Restaurant</h3>
                                <div class="form-group mb-4">
                                    <p>Foto de portada</p>
                                    <input type="file" class="dropify" name="foto" id="foto"
                                           data-default-file="https/images/blank1.jpg"/>
                                </div>
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control rounded-0 bg-transparent"
                                           name="nombrerestaurant" placeholder="Nombre del Restaurant">
                                </div>


                                <div class="form-group mb-4">
                                    <input type="text" class="form-control rounded-0 bg-transparent"
                                           name="telefonocontacto" placeholder="Teléfono de contacto">
                                </div>
                                <div class="form-group mb-4 input-group">
                                    <input type="text" class="form-control rounded-0 bg-transparent" id="direccion"
                                           name="direccion" placeholder="Direcci&oacute;n">
                                </div>

                                <div class="form-group mb-4  text-left">
                                    <p>Horario</p><br>
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Hora inicio</th>
                                            <th>Hora fin</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablaHorarios"></tbody>
                                    </table>
                                </div>
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control rounded-0 bg-transparent" id="ubicacion"
                                           name="ubicacion" placeholder="Ubicacion en Google Maps">
                                </div>
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
                    <div class="col-md-6">
                        <div class="form-input-content">
                            <div class="card-body">
                                <h3 class="section-title">Credenciales para administrar el sitio</h3>
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control rounded-0 bg-transparent" name="username"
                                           placeholder="Username">
                                </div>
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control rounded-0 bg-transparent" name="telefono"
                                           placeholder="Teléfono">
                                </div>


                                <div class="form-group mb-4 input-group">
                                    <input type="password" id="password" name="password" placeholder="Contraseña">
                                    <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                                </div>

                                <div class="form-group mb-4 input-group">
                                    <input type="password" id="confirmpassword" name="confirmpassword"
                                           placeholder="Confirmar contraseña">
                                    <i class="fa-solid fa-eye toggle-password" data-target="confirmpassword"></i>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-checkbox ml-2 mb-0" for="adminpassword">Contraseña de confirmaci&oacute;n
                                        y valid&eacute;z</label>
                                    <input type="password" class="form-control rounded-0 bg-transparent"
                                           id="adminpassword" name="adminpassword"
                                           placeholder="Contraseña de administración">
                                </div>

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
            </div>
            <div class="text-center border-0 pt-0" style="margin-bottom: 15px">
                <p class="mb-1">Ya tienes una cuenta ?</p>
                <h6><a href="/login">Loguearme</a></h6>
            </div>
            <input type="hidden" name="horariolaborarl" id="horariolaborarl">
            <button class="btn-primary btn-lg btn-block border-0" type="submit">Registrar</button>
        </div>

    </div>

</form>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/dropify.min.js"></script>
<script src="assets/js/dropify-init.js"></script>
<script>
    $(document).ready(function () {
        document.getElementById("loader").style.display = "none";
        const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        const tabla = document.getElementById('tablaHorarios');

        dias.forEach(dia => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
        <td data-label="Día">${dia}</td>
        <td data-label="Hora inicio"><input type="time" name="inicio[${dia}]" required></td>
        <td data-label="Hora fin"><input type="time" name="fin[${dia}]" required></td>
      `;
            tabla.appendChild(fila);
        });
    });
    document.getElementById('formHorario').addEventListener('submit', function (e) {
        let valido = true;
        const schedule = {};
        dias.forEach(dia => {
            const inicio = document.querySelector(`input[name="inicio[${dia}]"]`);
            const fin = document.querySelector(`input[name="fin[${dia}]"]`);
            // Convertir hora a entero (hora en formato 24h)
            const hInicio = inicio ? parseInt(inicio.split(':')[0]) : 0;
            const hFin = fin ? parseInt(fin.split(':')[0]) : 0;
            inicio.classList.remove('error');
            fin.classList.remove('error');

            if (inicio.value && fin.value && inicio.value >= fin.value) {
                inicio.classList.add('error');
                fin.classList.add('error');
                valido = false;
            }
            schedule[dia] = [hInicio, hFin];
        });

        if (!valido) {
            e.preventDefault();
            alert('Corrige los horarios: la hora de fin debe ser mayor a la de inicio.');
        }
        document.getElementById('horariolaborarl').value = schedule;
    });

    function mostrarLoader() {
        document.getElementById("loader").style.display = "flex";
    }

    document.querySelectorAll('.toggle-password').forEach(function (icon) {
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