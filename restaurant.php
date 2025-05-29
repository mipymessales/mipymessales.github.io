<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,1).'/');
include_once ROOT_DIR."pdo/conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restaurante - Inicio</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/restaurant.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
        }
        .hero-section {
            position: relative;
            width: 100%;
            height: 70vh;
            overflow: hidden;
        }
        .hero-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .hero-section img.active {
            opacity: 1;
        }
        .overlay-box {
            position: relative;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.85);
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            text-align: center;
        }
        .overlay-box h2 {
            margin-bottom: 1rem;
        }
        .overlay-box ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 1.5rem;
        }
        .overlay-box ul li {
            margin-bottom: 0.5rem;
            font-style: italic;
            color: #333;
        }
        .carousel-inner img {
            height: 300px;
            object-fit: cover;
        }
        @media (max-width: 768px) {
            .overlay-box {
                width: 90%;
                padding: 1rem;
            }
            este id #tabstyle menor de 360px cambiar a custom-tab-2 sino dejarlo en custom-tab-4
        }
        section {
            margin: 2rem auto;
            width: 100%;
        }
        iframe{
            width: 100%;
            height: 396px;
        }
        input, textarea {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #ff5722;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e64a19;
        }

    </style>
</head>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<body>

<!-- Slider de fondo con caja de autenticación y comentarios -->
<div class="hero-section">
    <img src="images/granny-menu6.jpg" class="active" alt="Slide 1">
    <img src="images/granny-menu11.jpg" alt="Slide 2">
    <img src="images/granny-menu6.jpg" alt="Slide 3">

    <div class="overlay-box">
        <h2>Comentarios recientes</h2>
        <ul>
            <li>"Excelente atención y sabor inigualable"</li>
            <li>"El ambiente es muy acogedor, volveré pronto"</li>
            <li>"Los postres son una delicia, 10/10"</li>
        </ul>
        <a href="/mipymessales/mesa.php"  class="btn btn-success">Sentarse en la mesa</a>
    </div>
</div>

<!-- Ofertas del restaurante -->
<div class="container-fluid menu py-5 px-0">
    <div class="mb-5 text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px; margin: auto;">
        <h1 class="display-3 mb-0">Nuestro menú</h1>
        <h5 class="section-title">¡Ideal para el disfrute de la familia!</h5>
    </div>
    <div class="tab-class text-center tabs">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Ofertas del día</h3>

                <div class="custom-tab-4" id="tabstyle">
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <ul class="nav nav-tabs">
                                <?php $array=["entrantes","platos","postres","bebidas"]; global $base_de_datos;$i=0;?>
                                <?php foreach($array as $arraypedidos) {
                                    ?>
                                <?php    if($i==0){ ?>
                                        <li class="nav-item"><a class="nav-link active" style="text-align: end;" data-toggle="tab" href="#<?php echo $arraypedidos; ?>"><?php echo $arraypedidos; ?></a>
                                    <?php }else{ ?>
                                        <li class="nav-item"><a class="nav-link " style="text-align: end;" data-toggle="tab" href="#<?php echo $arraypedidos; ?>"><?php echo $arraypedidos; ?></a>
                                        <?php } ?>

                                </li>
                                    <?php $i+=1; } ?>
                            </ul>
                        </div>
                        <div class="col-md-9 col-12">
                            <div class="tab-content tab-content-default">




                                    <?php $i=0; foreach($array as $arraypedidos){

                                        $sentencia = $base_de_datos->query("select * from ".$arraypedidos." where disponible=1;");
                                        $pedidosList = $sentencia->fetchAll(PDO::FETCH_OBJ);

                                        if($pedidosList!=null){
                                            ?>


                                    <?php    if($i==0){ ?>
                                    <div class="tab-pane fade show active" id="<?php echo $arraypedidos; ?>" role="tabpanel">
                                        <?php }else{ ?>
                                        <div class="tab-pane fade show " id="<?php echo $arraypedidos; ?>" role="tabpanel">
                                            <?php } ?>
                                        <div class="row">
                                        <div class="col-xl-12">
                                                <div class="card top_menu_widget">
                                                    <div class="card-body">
                                                        <div class="row">
                                                        <?php $j=0; foreach($pedidosList as $listaItem){

                                                            $foto=$listaItem->foto;
                                                            $nombre=$listaItem->nombre;
                                                            $precio=$listaItem->precio;
                                                            $valoracion=$listaItem->valoracion;
                                                            ?>

                                                        <?php    if($j<4){ ?>
                                                                <div class="col-lg-3 col-sm-6">
                                                                    <div class="card border-0">
                                                                        <div class="image-wrapper text-center mb-2">

                                                                            <?php if($foto!=null){?>
                                                                                <img class="img-fluid rounded-circle" src="images/<?php echo $foto;?>" style="height: 120px;width: 120px" alt="food menu">
                                                                            <?php }else{?>
                                                                                <img class="img-fluid rounded-circle" src="images/blank1.jpg" alt="food menu" style="height: 120px;width: 120px">
                                                                            <?php }?>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="text-center px-3">
                                                                                <h4> <?php echo $nombre; ?></h4>
                                                                                <?php  if(($valoracion)==1){ ?>
                                                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==2){ ?>
                                                                                    <span class='icon'>★★</span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==3){ ?>
                                                                                    <span class='icon'>★★★</span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==4){ ?>
                                                                                    <span class='icon'>★★★★</span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==5){ ?>
                                                                                    <span class='icon'>★★★★★</span>
                                                                                <?php }?>
                                                                                <h4 class="text-primary" style="margin-top: 5px">$<?php echo $precio; ?></h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }else{ ?>
                                                                <div class="col-lg-3 col-sm-6">
                                                                    <div class="card border-0">
                                                                        <div class="card-body">
                                                                            <div class="text-center pt-3">
                                                                                <h4><?php echo $nombre; ?></h4>
                                                                                <?php  if(($valoracion)==1){ ?>
                                                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==2){ ?>
                                                                                    <span class='icon'>★★</span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==3){ ?>
                                                                                    <span class='icon'>★★★</span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==4){ ?>
                                                                                    <span class='icon'>★★★★</span>
                                                                                <?php }?>

                                                                                <?php  if(($valoracion)==5){ ?>
                                                                                    <span class='icon'>★★★★★</span>
                                                                                <?php }?>
                                                                                <h4 class="text-primary" style="margin-top: 5px">$<?php echo $precio; ?></h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="image-wrapper text-center">
                                                                            <?php if($foto!=null){?>
                                                                                <img class="img-fluid rounded-circle" src="images/<?php echo $foto;?>" style="height: 120px;width: 120px" alt="food menu">
                                                                            <?php }else{?>
                                                                                <img class="img-fluid rounded-circle" src="images/blank1.jpg" alt="food menu" style="height: 120px;width: 120px">
                                                                            <?php }?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        <?php $j++;
                                                        if($j==8) $j=0;

                                                        } ?>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <?php $i+=1; } }?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row counter-wrapper">
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">147</h4>
                            <span>Orders</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">83</h4>
                            <span>Delivered</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">64</h4>
                            <span>Pending</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">12345</h4>
                            <span>Earned</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
                    <div class="col-sm-6">
                        <!-- 2. Horarios y Ubicación Dinámica -->
                        <section id="location-hours" class="p-4 bg-light rounded shadow mb-4">
                            <h2>📍 Dónde Estamos</h2>
                            <p><strong>Dirección:</strong> Calle Falsa 123, Ciudad Gastronómica</p>
                            <div class="container-fluid">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1948.2618895643138!2d-80.07383536066456!3d22.808811210970273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d51dc751dff889%3A0x67af0f662f166f6e!2sSagua%20la%20Grande%2C%20Cuba!5e0!3m2!1ses!2smx!4v1748379013035!5m2!1ses!2smx" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="mt-3">
                                <h4>🕒 Horarios</h4>
                                <ul id="schedule" class="list-unstyled"></ul>
                                <p id="status" class="fw-bold mt-2"></p>
                            </div>
                        </section>
                    </div>
                    <div class="col-sm-6">
                        <!-- 3. Sistema de Reservas Online -->
                        <section id="reservations" class="p-4 bg-white rounded shadow mb-4">
                            <h2>📅 Reserva tu Mesa</h2>
                            <form id="reservationForm">
                                <div>
                                    <label>Nombre:</label><br>
                                    <input type="text" name="nombre" id="nombre" required><br>
                                </div>
                                <div>
                                    <label>Teléfono:</label><br>
                                    <input type="tel" name="telefono" id="telefono" required><br>
                                </div>
                                <div>
                                    <label>Fecha:</label><br>
                                    <input type="date" name="fecha" id="fecha"  required><br>
                                </div>
                                <div>
                                    <label>Hora:</label><br>
                                    <input type="time" name="hora" id="hora" required><br>
                                </div>
                                <div>
                                    <label>Número de personas:</label><br>
                                    <input type="number" min="1" name="personas" id="personas" required><br>
                                </div>
                              <!--  <div class="g-recaptcha" data-sitekey="6LfP80srAAAAAFQk0DfEWWJuDeeq0YzOa2pzQ3zF"></div>-->
                                <button type="submit" class="mt-2">Reservar</button>
                            </form>
                            <p id="reservationMessage" class="mt-2"></p>
                        </section>

                        <!-- 10. Encuesta / Sugerencias -->
                        <section id="suggestions" class="p-4 bg-light rounded shadow">
                            <h2>💬 Ayúdanos a mejorar</h2>
                            <form id="suggestionForm">
                                <label>¿Qué te gustaría ver en el menú o mejorar?</label><br>
                                <textarea required rows="4" cols="50"></textarea><br>
                              <!--  <div class="g-recaptcha" data-sitekey="6LfP80srAAAAAFQk0DfEWWJuDeeq0YzOa2pzQ3zF"></div>-->

                                <button type="submit" class="mt-2">Enviar sugerencia</button>
                            </form>
                            <p id="suggestionMessage" class="mt-2"></p>
                        </section>
                    </div>
        </div>

    </div>

    <footer class="text-center py-4 bg-light">
        <p class="mb-0">&copy; 2025 Restaurante Delicias. Todos los derechos reservados.</p>
    </footer>



    <script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script>
    // Slider automático de imágenes
    const slides = document.querySelectorAll('.hero-section img');
    let index = 0;
    setInterval(() => {
        slides[index].classList.remove('active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('active');
    }, 4000);
    function updateTabClass() {
        const tabElement = document.getElementById('tabstyle');
        if (!tabElement) return; // Previene errores si el elemento no existe

        if (window.innerWidth <= 768) {
            // Móvil
            tabElement.classList.remove('custom-tab-4');
            tabElement.classList.add('custom-tab-2');
        } else {
            // Desktop
            tabElement.classList.remove('custom-tab-2');
            tabElement.classList.add('custom-tab-4');
        }
    }

    // Ejecutar al cargar la página
    window.addEventListener('DOMContentLoaded', updateTabClass);

    // Ejecutar cuando se redimensiona la ventana
    window.addEventListener('resize', updateTabClass);

    /*Reservas*/
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.counter');
        const duration = 5000; // Duración total de la animación en milisegundos

        counters.forEach(counter => {
            const target = +counter.innerText; // Valor final
            let start = 0;
            const increment = target / (duration / 16); // Aproximadamente 60fps (~16ms por frame)

            function updateCounter() {
                start += increment;
                if (start < target) {
                    counter.innerText = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target; // Asegura que termine exacto
                }
            }

            counter.innerText = '0'; // Inicializa a 0
            updateCounter();
        });



        // 2. Mostrar horarios y estado
        const schedule = {
            'Lunes': [12, 22],
            'Martes': [0, 0],
            'Miércoles': [12, 22],
            'Jueves': [12, 22],
            'Viernes': [12, 23],
            'Sábado': [12, 23],
            'Domingo': [12, 18]
        };

        const scheduleList = document.getElementById('schedule');
        const status = document.getElementById('status');
        const now = new Date();
        const dayName = now.toLocaleDateString('es-ES', { weekday: 'long' });
        const hour = now.getHours();

        for (let [day, hours] of Object.entries(schedule)) {
            const li = document.createElement('li');
            li.textContent = `${day}: ${hours[0]}:00 - ${hours[1]}:00`;
            scheduleList.appendChild(li);
        }

        const todayHours = schedule[dayName.charAt(0).toUpperCase() + dayName.slice(1)];
        if (todayHours && hour >= todayHours[0] && hour < todayHours[1]) {
            status.textContent = '✅ Estamos Abiertos Ahora';
            status.style.color = 'green';
        } else {
            status.textContent = '❌ Cerrado en este momento';
            status.style.color = 'red';
        }


        // Verifica si el reCAPTCHA está resuelto
        function captchaResuelto() {
            return grecaptcha.getResponse().trim() !== "";
        }

        // RESERVAS
        document.getElementById('reservationForm').addEventListener('submit', (e) => {
            e.preventDefault();

           /* if (!captchaResuelto()) {
                alert("Por favor, resuelve el CAPTCHA antes de enviar.");
                return;
            }*/

            const formData = new FormData(e.target);

            fetch('controllers/guardar_reserva.php', {
                method: 'POST',
                dataType:'json',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data["status"] === 'success') {
                        document.getElementById('reservationMessage').textContent = "✅ ¡Reserva recibida!";
                        e.target.reset();
                       // grecaptcha.reset();
                    } else if (data === 'RECAPTCHA_FAILED') {
                        console.log("Captcha no verificado por el servidor.");
                    } else if (data === 'RATE_LIMITED') {
                        console.log("Has enviado demasiadas solicitudes. Intenta en un minuto.");
                    } else {
                        document.getElementById('suggestionMessage').textContent = "❌ Error al guardar la reserva.";
                        document.getElementById('suggestionMessage').style.color = 'red';
                        console.log("❌ Error al guardar la reserva.");
                    }
                });
        });

        // SUGERENCIAS
        document.getElementById('suggestionForm').addEventListener('submit', (e) => {
            e.preventDefault();

        /*   if (!captchaResuelto()) {
                alert("Por favor, resuelve el CAPTCHA antes de enviar.");
                return;
            }
*/
            const mensaje = e.target.querySelector('textarea').value;
            const formData = new FormData();
            formData.append('mensaje', mensaje);
            //formData.append('g-recaptcha-response', grecaptcha.getResponse());

            fetch('controllers/guardar_sugerencia.php', {
                dataType:'json',
                method: 'POST',
                body: formData
            })
                .then(res => res.json()) //response.json()
                .then(data => {
                    if (data["status"] === "success") {
                        document.getElementById('suggestionMessage').textContent = "🙌 ¡Gracias por tu sugerencia!";
                        e.target.reset();
                       // grecaptcha.reset();
                    } else if (data === 'RECAPTCHA_FAILED') {
                        console.log("Captcha no verificado por el servidor.");
                    } else if (data === 'RATE_LIMITED') {
                        console.log("Has enviado demasiadas sugerencias. Intenta luego.");
                    } else {
                        console.log("Error al enviar la sugerencia.");
                        document.getElementById('suggestionMessage').textContent = "❌ Error al enviar la sugerencia.";
                        document.getElementById('suggestionMessage').style.color = 'red';
                        e.target.reset();
                    }
                });
        });


    });

</script>
</body>
</html>






