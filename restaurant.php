<?php

session_start();

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
    <link href="assets/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-icons.scss" rel="stylesheet">
    <link href="assets/css/bootstrap-grid.css" rel="stylesheet">
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
            height: 250px;
            overflow: hidden;
        }
        .hero-section img {
            width: 100%;
            height: 40vh;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            padding: 0;
            margin: 0;
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
            /*margin: 2rem auto;*/
            width: 100%;
        }
        iframe{
            width: 100%;
            height: 600px;
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
        /*--------------------------------------------------------------
        # Testimonials Section
        --------------------------------------------------------------*/
        .testimonials {
            padding: 20px 0;
            position: relative;
        }

        .testimonials:before {
            content: "";
           background: color-mix(in srgb,#444444, transparent 60%);
            position: absolute;
            inset: 0;
            z-index: 2;
        }

        .testimonials .testimonials-bg {
            position: absolute;
            inset: 0;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .testimonials .container {
            position: relative;
            z-index: 3;
        }

        .testimonials .testimonials-carousel,
        .testimonials .testimonials-slider {
            overflow: hidden;
        }

        .testimonials .testimonial-item {
            text-align: center;
        }

        .testimonials .testimonial-item .testimonial-img {
            width: 100px;
            border-radius: 50%;
            border: 6px solid color-mix(in srgb,#ccc, transparent 85%);
            margin: 0 auto;
        }

        .testimonials .testimonial-item h3 {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }

        .testimonials .testimonial-item h4 {
            font-size: 14px;
            margin: 0 0 15px 0;
            color: color-mix(in srgb,#332b2b, transparent 20%);
        }

        .testimonials .testimonial-item .stars {
            margin-bottom: 15px;
        }

        .testimonials .testimonial-item .stars i {
            color: #ffc107;
            margin: 0 1px;
        }

        .testimonials .testimonial-item .quote-icon-left,
        .testimonials .testimonial-item .quote-icon-right {
            color: color-mix(in srgb, #ccc, transparent 40%);
            font-size: 26px;
            line-height: 0;
        }

        .testimonials .testimonial-item .quote-icon-left {
            display: inline-block;
            left: -5px;
            position: relative;
        }

        .testimonials .testimonial-item .quote-icon-right {
            display: inline-block;
            right: -5px;
            position: relative;
            top: 10px;
            transform: scale(-1, -1);
        }

        .testimonials .testimonial-item p {
            font-style: italic;
            margin: 0 auto 15px auto;
        }

        .testimonials .swiper-wrapper {
            height: auto;
        }

        .testimonials .swiper-pagination {
            margin-top: 20px;
            position: relative;
        }

        .testimonials .swiper-pagination .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background-color: color-mix(in srgb, #727c59, transparent 50%);
            opacity: 0.5;
        }

        .testimonials .swiper-pagination .swiper-pagination-bullet-active {
            background-color: #fafbfe;
            opacity: 1;
        }

        @media (min-width: 992px) {
            .testimonials .testimonial-item p {
                width: 80%;
            }
        }
        /*--------------------------------------------------------------
# Hero Section
--------------------------------------------------------------*/
        .hero {
            width: 100%;
            min-height: 75vh;
            position: relative;
            padding: 60px 0;
            display: flex;
            align-items: center;
           background: url("images/granny-menu11.jpg") top left;
            background-size: cover;
        }

        .hero:before {
            content: "";
           background: color-mix(in srgb, #fafbfe, transparent 30%);
            position: absolute;
            bottom: 0;
            top: 0;
            left: 0;
            right: 0;
        }

        .hero .container {
            position: relative;
        }

        .hero h1 {
            margin: 0;
            font-size: 48px;
            font-weight: 700;
            line-height: 56px;
        }

        .hero h1 span {
            color: #0acf97;
        }

        .hero p {
            color: color-mix(in srgb, #fafbfe, transparent 30%);
            margin: 5px 0 30px 0;
            font-size: 20px;
            font-weight: 400;
        }

        .hero .btn-get-started {
            color: #000000;
            background: #fafbfe;
            font-family:'Figtree', sans-serif;
            font-weight: 400;
            font-size: 16px;
            letter-spacing: 1px;
            display: inline-block;
            padding: 12px 30px;
            border-radius: 4px;
            transition: 0.5s;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
        }

        .hero .btn-get-started:hover {
            color: #fafbfe;
            background: #0acf97;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
        }

        .hero .btn-watch-video {
            font-size: 16px;
            transition: 0.5s;
            margin-left: 25px;
            color: #fafbfe;
            font-weight: 600;
        }

        .hero .btn-watch-video i {
            color: #0acf97;
            font-size: 32px;
            transition: 0.3s;
            line-height: 0;
            margin-right: 8px;
        }

        .hero .btn-watch-video:hover {
            color: #0acf97;
        }

        .hero .btn-watch-video:hover i {
            color: color-mix(in srgb, #0acf97, transparent 15%);
        }

        .hero .animated {
            animation: up-down 2s ease-in-out infinite alternate-reverse both;
        }

        @media (max-width: 640px) {
            .hero h1 {
                font-size: 28px;
                line-height: 36px;
            }

            .hero p {
                font-size: 18px;
                line-height: 24px;
                margin-bottom: 30px;
            }

            .hero .btn-get-started,
            .hero .btn-watch-video {
                font-size: 13px;
            }
        }

        @keyframes up-down {
            0% {
                transform: translateY(10px);
            }

            100% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>


<!-- Hero Section -->
<section id="hero " class="hero section light-background ">

    <div class="container">
        <div class="row gy-4">

            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                <h1>Bienvenido a <span>RestaurantX</span></h1>
                <p style="color: #332b2b">"Sabores que cuentan historias. Ven y descubre la experiencia gastronómica que mereces."</p>

                <div class="d-flex">
                    <a href="/mipymessales/mesa" class="btn-get-started">Sentarse a la mesa</a>
<!--                    <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>-->
                </div>
            </div>
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out" >

                <div class="hero-section">

                    <img  src="images/granny-menu6.jpg" class="active" alt="Slide 1">
                    <img src="images/granny-menu11.jpg" alt="Slide 2">
                    <img src="images/granny-menu6.jpg" alt="Slide 3">

                </div>
                <section id="testimonials" class="testimonials section dark-background" style=" border: 1px solid #6c757d;">

                    <!-- <img src="#" class="testimonials-bg" alt="">-->

                    <div class="container" data-aos="fade-up" data-aos-delay="100">

                        <div class="swiper init-swiper">
                            <script type="application/json" class="swiper-config">
                                {
                                    "loop": true,
                                    "speed": 600,
                                    "autoplay": {
                                        "delay": 5000
                                    },
                                    "slidesPerView": "auto",
                                    "pagination": {
                                        "el": ".swiper-pagination",
                                        "type": "bullets",
                                        "clickable": true
                                    }
                                }
                            </script>

                            <div class="swiper-wrapper">

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <img src="images/blank1.jpg" class="testimonial-img" alt="">
                                        <h3>Saul Goodman</h3>
                                        <h4>Ceo &amp; Founder</h4>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <img src="images/blank1.jpg" class="testimonial-img" alt="">
                                        <h3>Sara Wilsson</h3>
                                        <h4>Designer</h4>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <img src="images/blank1.jpg" class="testimonial-img" alt="">
                                        <h3>Jena Karlis</h3>
                                        <h4>Store Owner</h4>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <img src="images/blank1.jpg" class="testimonial-img" alt="">
                                        <h3>Matt Brandon</h3>
                                        <h4>Freelancer</h4>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <img src="images/blank1.jpg" class="testimonial-img" alt="">
                                        <h3>John Larson</h3>
                                        <h4>Entrepreneur</h4>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                            </div>
                            <div class="swiper-pagination"></div>
                        </div>

                    </div>

                </section>
            </div>
        </div>

    </div>

</section><!-- /Hero Section -->
<!-- Slider de fondo con caja de autenticación y comentarios -->



<!-- /Testimonials Section -->

<!-- Ofertas del restaurante -->
<div class="container-fluid menu py-5 px-0">
    <div class="mb-5 text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px; margin: auto;">
        <h1 class="display-3 mb-0">Nuestro menú</h1>
        <h5 class="section-title">¡Ideal para el disfrute de la familia!</h5>
    </div>
    <div class="tab-class text-center tabs">
<!--        <div class="card">-->
<!--            <div class="card-body">-->
                <h3 class="card-title">Ofertas del día</h3>

                <div class="custom-tab-4" id="tabstyle">
                    <div class="row">
                        <div class="col-md-2 col-12">
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
                        <div class="col-md-10 col-12">
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
                                                                                    <span class="icon">★</span>
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

                                                                                <?php  if(($valoracion)==5 || empty($valoracion)){ ?>
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
                                                                                    <span class="icon">★</span>
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

                                                                                <?php  if(($valoracion)==5 || empty($valoracion)){ ?>
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
<!--        </div>-->
<!--    </div>-->
</div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row counter-wrapper">
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">147</h4>
                            <span>Ordenes</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">83</h4>
                            <span>Platos</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">64</h4>
                            <span>Clientes</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="card-body text-center">
                            <h4 class="counter">12345</h4>
                            <span>Comentarios</span>
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
                                <label>Captcha de seguridad</label><br>
                                <img id="captchaimgr" src="controllers/captcha.php?from=r" alt="CAPTCHA">
                                <input type="text" name="captchar" placeholder="Ingresa el texto de la imagen" required>
                                <button type="submit" class="mt-2">Reservar</button>
                            </form>
                            <p id="reservationMessage" class="mt-2"></p>
                        </section>

                        <!-- 10. Encuesta / Sugerencias -->
                        <section id="suggestions" class="p-4 bg-light rounded shadow">
                            <h2>💬 Ayúdanos a mejorar</h2>
                            <form id="suggestionForm">
                                <label>¿Qué te gustaría ver en el menú o mejorar?</label><br>
                                <textarea name="mensaje" required rows="4" cols="50"></textarea><br>
                                <label>Captcha de seguridad</label><br>
                                <img id="captchaimgs" src="controllers/captcha.php?from=s" alt="CAPTCHA">
                                <input type="text" name="captchas" placeholder="Ingresa el texto de la imagen" required>

                                <button type="submit" class="mt-2">Enviar sugerencia</button>
                            </form>
                            <p id="suggestionMessage" class="mt-2"></p>
                        </section>
                    </div>
        </div>

    </div>


    <footer class="text-center py-4 bg-light">
        <p class="mb-0">&copy; 2025 Nombre Restaurante . Todos los derechos reservados.</p>
    </footer>


    <script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/bootstrap-icons.json"></script>

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

                    console.log((data));

                    if (data["status"] === 'success') {
                        document.getElementById('reservationMessage').textContent = "✅ ¡Reserva recibida!";
                        document.getElementById('reservationMessage').style.color = 'green';
                        e.target.reset();
                        recargarCaptcha('r');
                       // grecaptcha.reset();
                    } else if (data["status"] === 'RECAPTCHA_FAILED') {
                        console.log("Captcha no verificado por el servidor.");
                        document.getElementById('reservationMessage').textContent = "❌ Captcha no verificado por el servidor.";
                        document.getElementById('reservationMessage').style.color = 'red';
                        recargarCaptcha('r');
                    } else if (data["status"] === 'RATE_LIMITED') {
                        console.log("Has enviado demasiadas solicitudes. Intenta en un minuto.");
                        document.getElementById('reservationMessage').textContent = "❌ Has enviado demasiadas solicitudes. Intenta en un minuto.";
                        document.getElementById('reservationMessage').style.color = 'red';
                        e.target.reset();
                        recargarCaptcha('r');
                    } else {
                        document.getElementById('reservationMessage').textContent = "❌ Error al guardar la reserva.";
                        document.getElementById('reservationMessage').style.color = 'red';
                        console.log("❌ Error al guardar la reserva.");
                        e.target.reset();
                        recargarCaptcha('r');
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
            //const mensaje = e.target.querySelector('textarea').value;
           // const formData = new FormData();
            // formData.append('mensaje', mensaje);
            const formData = new FormData(e.target);

            fetch('controllers/guardar_sugerencia.php', {
                dataType:'json',
                method: 'POST',
                body: formData
            })
                .then(res => res.json()) //response.json()
                .then(data => {
                    if (data["status"] === "success") {
                        document.getElementById('suggestionMessage').textContent = "🙌 ¡Gracias por tu sugerencia!";
                        document.getElementById('suggestionMessage').style.color = 'green';
                        e.target.reset();
                        recargarCaptcha('s');
                       // grecaptcha.reset();
                    } else if (data["status"] === 'RECAPTCHA_FAILED') {
                        console.log("Captcha no verificado por el servidor.");
                        document.getElementById('suggestionMessage').textContent = "❌ Captcha no verificado por el servidor.";
                        document.getElementById('suggestionMessage').style.color = 'red';
                        recargarCaptcha('s');
                    } else if (data["status"] === 'RATE_LIMITED') {
                        console.log("Has enviado demasiadas sugerencias. Intenta luego.");
                        document.getElementById('suggestionMessage').textContent = "❌ Has enviado demasiadas sugerencias. Intenta luego.";
                        document.getElementById('suggestionMessage').style.color = 'red';
                        e.target.reset();
                        recargarCaptcha('s');
                    } else {
                        console.log("Error al enviar la sugerencia.");
                        document.getElementById('suggestionMessage').textContent = "❌ Error al enviar la sugerencia.";
                        document.getElementById('suggestionMessage').style.color = 'red';
                        e.target.reset();
                        recargarCaptcha('s');
                    }
                });
        });


    });
    function recargarCaptcha(place) {
        const vieja = document.getElementById('captchaimg'+place);
        const nueva = document.createElement('img');
        nueva.id = 'captchaimg'+place;
        nueva.alt = 'CAPTCHAZ';
        nueva.src = 'controllers/captcha.php?t=' + Date.now()+"&from="+place;
        nueva.width = 120;
        nueva.height = 40;

        vieja.parentNode.replaceChild(nueva, vieja);
    }
    /**
     * Init swiper sliders
     */
    function initSwiper() {
        document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
            let config = JSON.parse(
                swiperElement.querySelector(".swiper-config").innerHTML.trim()
            );

            if (swiperElement.classList.contains("swiper-slide")) {
                initSwiperWithCustomPagination(swiperElement, config);
            } else {
                new Swiper(swiperElement, config);
            }
        });
    }

    window.addEventListener("load", initSwiper);
</script>
</body>
</html>






