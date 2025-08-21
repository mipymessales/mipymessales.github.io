<?php $id = 2; ?>
<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 1) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
global $base_de_datos;
$stmt = $base_de_datos->prepare("SELECT * FROM restaurant_info WHERE id=:id limit 1");
$stmt->bindParam(':id', $id);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
if (!empty($resultado)) {
    $nombreRestaurant = $resultado[0]->nombre;
    $telefonoRestaurant = $resultado[0]->telefono;
    $direccionRestaurant = $resultado[0]->direccion;
    $horarioRestaurant = json_decode($resultado[0]->horario,true);
    $ubicacionRestaurant = $resultado[0]->ubicacion;
    $ubicacionRestaurant = str_replace("�", "°", $ubicacionRestaurant);
    $foto_portadaRestaurant = $resultado[0]->foto_portada;
    $activo = $resultado[0]->activo;
}
if (intval($activo)!=1){
    include_once ROOT_DIR."controllers/Host.php";
    header('Location: ' . Host::getHOSTNAME()."templates/404.php");
    var_dump("No activo-> ".$activo);
    exit;
}

$sentenc = $base_de_datos->prepare("SELECT monto FROM gastos WHERE restaurantid=:id and (concepto='Domicilio' || concepto='domicilio') limit 1");
$sentenc->bindParam(':id', $id);
$sentenc->execute();
$gastos = $sentenc->fetchAll(PDO::FETCH_OBJ);
$monto=0;
if (!empty($gastos)) {
    $monto = $gastos[0]->monto;
}

$año_actual = date("Y");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title> <?php echo $nombreRestaurant; ?> </title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/restaurant.css" rel="stylesheet">
    <link href="assets/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-icons.scss" rel="stylesheet">
    <link href="assets/css/bootstrap-grid.css" rel="stylesheet">
    <link href="assets/css/dropify.min.css" rel="stylesheet">

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
            height: 300px;
            overflow: hidden;
        }
        @media (max-width: 600px) {
            .hero-section {
                height: 200px;
            }
            .py-5 {
                padding-top: 0 !important;
            }
            .hero{
                min-height: auto!important;
            }
            .hero h1 {
                margin-top: 200px !important;
            }
            /*    este id #tabstyle menor de*/

            /*360px cambiar a custom-tab-2 sino dejarlo en custom-tab-4*/
        }
        @media (max-width: 350px) {

            .hero h1 {
                margin-top:100px !important;
            }
            /*    este id #tabstyle menor de*/

            /*360px cambiar a custom-tab-2 sino dejarlo en custom-tab-4*/
        }
        @media (min-width: 450px) {

            .hero h1 {
                margin-top:180px !important;
            }
            /*    este id #tabstyle menor de*/

            /*360px cambiar a custom-tab-2 sino dejarlo en custom-tab-4*/
        }

        .hero-section img {
            width: 100%;
            /*height: 40vh;*/
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

        /*    este id #tabstyle menor de*/

        /*360px cambiar a custom-tab-2 sino dejarlo en custom-tab-4*/
        }

        section {
            /*margin: 2rem auto;*/
            width: 100%;
        }

        iframe {
            width: 100%;
            height: 485px;
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
            /*background: color-mix(in srgb, #444444, transparent 60%);*/
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
            border: 6px solid color-mix(in srgb, #ccc, transparent 85%);
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
            color: color-mix(in srgb, #332b2b, transparent 20%);
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
            background-color: #ff5722bf;
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
            background:
                    url("images/<?php echo $foto_portadaRestaurant;?>"),
                    #f4f4f4;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: top center;
        }
        .hero:before {
            content: "";
            background: color-mix(in srgb, #e3eaef, transparent 30%);
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
            color: #ff5722bf;
        }
        .section-title {
            color: #ff5722bf;
        }
        .custom-tab-4 .nav-link.active {
            border-right: 2px solid #ff5722bf;
        }
        .hero p {
            color: color-mix(in srgb, #ff5722bf, transparent 0%);
            margin: 5px 0 30px 0;
            font-size: 20px;
            font-weight: 400;
        }

        .hero .btn-get-started {
            color: #000000;
            background: #fafbfe;
            font-family: 'Figtree', sans-serif;
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
            background: #ff5722bf;
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
        @media (max-width: 990px) {
            .hero h1 {
                font-size: 24px;
                line-height: 48px;
                margin-top: 300px !important;
            }
        }
        @media (min-width: 840px) {
            .hero h1 {
                font-size: 38px;
                line-height: 60px;
                margin-top: 150px !important;
            }
        }
        @media (max-width: 640px) {
            .hero h1 {
                font-size: 18px;
                line-height: 36px;
                margin-top: 185px !important;
                text-align: center;
            }
            .display-3 {
                font-size: 26px;
                font-weight: bold;
            }
            section h2 {
                font-size: 26px!important;
                text-align: center!important;
            }
            section h2 span {
                font-size: 20px!important;
            }
            section h4 {
                text-align: left !important;
            }
            td h2 {
                font-size: 20px;
                text-align: center;
            }

            .hero p {
                font-size: 12px;
                line-height: 24px;
                margin-bottom: 30px;
            }

            .hero .btn-get-started,
            .hero .btn-watch-video {
                font-size: 13px;
            }
        }


        @media (min-width: 992px) {
            .hero {
                background-size: cover;
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
        #carritoVisual {
            width: 100%;
            /*max-width: 500px;*/
            background-color: #fdfdfd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        #carritoVisual h3 {
            text-align: center;
            margin-bottom: 16px;
        }

        table.factura {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.factura th,
        table.factura td {
           /* border: 1px solid #ccc;*/
            padding: 5px;
            text-align: left;
        }

        table.factura th {
            background-color: #f0f0f0;
        }

        .total-fila {
            font-weight: bold;
            text-align: right;
            background-color: #fafafa;
        }

        .vacio {
            text-align: center;
            color: #888;
        }
        html {
            scroll-behavior: smooth;
        }
        table {
            border-collapse: collapse;
            width: 100%; /* opcional */
        }

        td, th {
           /* padding: 10px 15px;*/ /* ← aquí ajustas el espacio interno */
            text-align: left;   /* mejora la lectura */
           /* border: 0.5px solid #ccc;*/
        }
        #estado-container {
            margin-top: 20px;
            font-size: 1.2em;
        }

        .reloj, .dia, #estado {
            margin: 5px 0;
        }
        .oculto {
            display: none !important;
        }


        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .factura {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* Asegura que tenga un ancho mínimo */
        }

        .factura th, .factura td {
            padding: 5px;
           /* border: 1px solid #ddd;*/
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f0f0f0;
        }

/*Hero*/
        .hero {
            display: flex;
            flex-wrap: wrap;
            min-height: 75vh;
            width: 100%;
        }

        /* Contenido de texto */
        .hero-content {
            flex: 1;
            /*padding: 40px;*/
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Imagen de fondo como bloque */

        /* En pantallas grandes: imagen a la derecha */
        @media (min-width: 768px) {
            .hero {
                flex-direction: row;
                background-size: contain;
                min-height: auto;
            }
        }

        /* En móviles: imagen arriba */
        @media (max-width: 767px) {
            .hero {
                flex-direction: column;
                min-height: auto;
            }

            .hero-img {
                width: 100%;
                height: 200px;
                background-position: top center;
                background-size: contain;
            }
        }
        @media (max-width: 900px) {
            .col-sm-6 {
                flex: 0 0 auto;
                width: 100%;
            }
        }
        .nav-tabs {
            display: flex;
            justify-content: center;
        }

        .nav-tabs {
            border-bottom: 1px solid #ecf3f2;
            margin-bottom: 40px;
            justify-content: center;
        }

        .nav-tabs .nav-link {
            border-radius: 0;
            border-top: 0;
            border-right: 0;
            border-left: 0;
            color: var(--p-color);
            font-family: var(--title-font-family);
            font-size: var(--btn-font-size);
            font-weight: var(--font-weight-medium);
            padding: 15px 25px;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:first-child {
            margin-right: 20px;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active,
        .nav-tabs .nav-link:focus,
        .nav-tabs .nav-link:hover {
            border-bottom-color: var(--link-hover-color);;
            color: var(--link-hover-color);
            font-weight: bold;
            letter-spacing: 0.3rem;
        }
        .section-title {
            margin-top: 10px !important;
        }
    </style>

<!-- ==== UI ENHANCEMENTS: styles injected ==== -->
<style>
  :root {
    --acc:#0ea5e9; /* accent */
    --acc-2:#22c55e; /* success */
    --danger:#ef4444;
    --text:#111827;
    --muted:#6b7280;
    --bg:#f8fafc;
    --card:#ffffff;
  }
  /* Smoother fonts */
  html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
  body { background: var(--bg); color: var(--text); }
  /* Product cards */
/*  .producto {
    background: var(--card);
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    overflow: hidden;
  }*/

  .producto .title, .producto h3, .producto h4 { font-weight: 700; letter-spacing: .2px; }
  .producto .precio, .producto .price { font-weight: 700; }
  .producto .actions, .producto .buttons { display:flex; align-items:center; gap:.5rem; }
/*  .producto .agregar, .producto .quitar {
    border: 0;
    border-radius: 12px;
    padding: .55rem .8rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 8px 16px rgba(0,0,0,.08);
    transition: transform .15s ease, box-shadow .15s ease, opacity .2s ease;
  }
  .producto .agregar { background: var(--acc-2); color: white; }
  .producto .quitar { background: var(--danger); color: white; }
  .producto .agregar:active, .producto .quitar:active { transform: translateY(1px); box-shadow: 0 4px 10px rgba(0,0,0,.1); }*/
  .producto .cantidad {
    min-width: 1.75rem;
    display:inline-flex; align-items:center; justify-content:center;
    background: #eef2ff;
    color:#4338ca;
    border-radius: 999px; padding:.15rem .5rem; font-weight:700;
  }
  /* Responsive grid helpers (fallback if not already) */
  .grid-productos, .productos-grid {
    display:grid; gap: 14px;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  }
  @media (max-width: 768px) {
    .grid-productos, .productos-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .producto { border-radius: 14px; }
    .producto .agregar, .producto .quitar { padding:.5rem .7rem; }
  }
  @media (max-width: 480px) {
    .grid-productos, .productos-grid { grid-template-columns: 1fr; }
  }

  /* Floating cart button (bottom-left) */
  #floatingCartWrapper {
    position: fixed;
    right: 16px;
    bottom: 16px;
    z-index: 9999;
  }
  #floatingCartBtn {
    width: 58px; height: 58px;
    border-radius: 50%;
    background: var(--text);
    color: white;
    display: inline-flex; align-items:center; justify-content:center;
    box-shadow: 0 12px 28px rgba(0,0,0,.25);
    cursor: pointer;
    transition: transform .18s ease, box-shadow .18s ease;
    position: relative;
  }
  #floatingCartBtn:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(0,0,0,.28); }
  #floatingCartCount {
    position:absolute; top:-6px; right:-6px;
    min-width: 22px; height: 22px; padding: 0 6px;
    background: var(--acc-2); color:#fff; border-radius: 999px;
    font-size: 12px; font-weight: 800; display:flex; align-items:center; justify-content:center;
    box-shadow: 0 8px 16px rgba(34,197,94,.4);
  }
  /* Modal above the floating cart */
  #floatingCartModal {
    position: absolute;
    right: 0;
    bottom: 72px; /* above the button */
    width: min(92vw, 380px);
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 16px 44px rgba(0,0,0,.25);
    overflow: hidden;
    transform-origin: bottom right;
    transform: scale(.98);
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s ease, transform .2s ease;
  }
  #floatingCartModal.show {
    opacity: 1; transform: scale(1);
    pointer-events: auto;
  }
  #floatingCartHeader {
    padding: 12px 14px;
    font-weight: 800; letter-spacing:.2px;
    background: linear-gradient(90deg, #111827, #1f2937);
    color: #fff;
  }
  #floatingCartBody { max-height: 50vh; overflow:auto; padding: 10px 12px; }
  #floatingCartBody ul { list-style: none; margin:0; padding:0; }
  #floatingCartBody li { display:flex; align-items:center; justify-content:space-between; padding: 8px 6px; border-bottom: 1px dashed #e5e7eb; }
  #floatingCartFooter { padding: 10px 12px; display:flex; align-items:center; justify-content:space-between; background:#f9fafb; }
  #floatingCartFooter .total { font-weight: 900; font-size: 1rem; }
  #floatingCartClose { background: transparent; border:0; color:#fff; cursor:pointer; font-size: 20px; line-height: 1; }

  /* Make existing #carritoVisual look nicer (if used on page) */
  #carritoVisual {
    border-radius: 12px;
    background: #ffffff;
    padding: 10px 12px;
    box-shadow: 0 8px 22px rgba(0,0,0,.08);
  }
  /* Horizontal line SVG */
  .linea-horizontal svg {
      width: 200px;
      height: 20px;
  }

  .linea-horizontal svg line {
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-dasharray: 180;
      stroke-dashoffset: 180;
      animation: dibujar-linea 3s ease-in-out infinite;
  }

  /* Vertical line SVG (mobile) */
  .linea-vertical {
      display: none;
  }

  .linea-vertical svg {
      width: 30px;
      height: 100px;
  }

  .linea-vertical svg line {
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-dasharray: 180;
      stroke-dashoffset: 180;
      animation: dibujar-linea 3s ease-in-out infinite;
  }
</style>
</head>
<body>


<!-- Hero Section -->
<section id="hero " class="hero section bg-light">
    <div class="container hero-content">
        <div class="row gy-4">

            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                <h1 style="text-align: center">Bienvenido a <span><?php echo $nombreRestaurant; ?></span></h1>
                <p style="color: #332b2b;font-size: 16px;
  font-weight: 300;
  line-height: 1.2;text-align: center">"Sabores que cuentan historias. Ven y descubre la experiencia gastronómica que
                    mereces."</p>

                <div class="text-center">
                    <a href="#menu" class="btn-get-started">Ver ofertas!</a>
                    <!--                    <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>-->
                </div>
            </div>
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                <div class="hero-section" id="hero-section">
                </div>

                <section id="testimonials" class="testimonials section dark-background bg-light">
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
                                     <!--   <img src="images/blank1.jpg" class="testimonial-img" alt="">-->
                                        <h3></h3>
                                       <!-- <h4>Ceo &amp; Founder</h4>-->
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span></span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">

                                        <h3></h3>

                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span></span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">

                                        <h3></h3>

                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span></span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">

                                        <h3></h3>

                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span></span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">

                                        <h3></h3>

                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span></span>
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
</section>
<!-- Slider de fondo con caja de autenticación y comentarios -->


<!-- /Testimonials Section -->
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#e3eaef" fill-opacity="1" d="M0,224L34.3,192C68.6,160,137,96,206,90.7C274.3,85,343,139,411,144C480,149,549,107,617,122.7C685.7,139,754,213,823,240C891.4,267,960,245,1029,224C1097.1,203,1166,181,1234,160C1302.9,139,1371,117,1406,106.7L1440,96L1440,0L1405.7,0C1371.4,0,1303,0,1234,0C1165.7,0,1097,0,1029,0C960,0,891,0,823,0C754.3,0,686,0,617,0C548.6,0,480,0,411,0C342.9,0,274,0,206,0C137.1,0,69,0,34,0L0,0Z"></path></svg>
<!-- Ofertas del restaurante -->
<div class="container-fluid menu px-0" id="menu">

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
                        <?php $array=["alimentos","bebidas","carnicos","embutidos","confituras","condimentos"]; global $base_de_datos;$i=0;?>
                        <?php foreach($array as $arraypedidos) {
                            ?>
                            <?php    if($i==0){ ?>
                                <li class="nav-item"><a class="nav-link active" style="text-align: end;" data-toggle="tab" onclick="iniciarAutoCarga('<?php echo $id; ?>','<?php echo $arraypedidos; ?>',true)"><?php echo ucfirst(strtolower($arraypedidos)); ?></a>
                            <?php }else{ ?>
                                <li class="nav-item"><a class="nav-link " style="text-align: end;" data-toggle="tab" onclick="iniciarAutoCarga('<?php echo $id; ?>','<?php echo $arraypedidos; ?>',true)"><?php echo ucfirst(strtolower($arraypedidos)); ?></a>
                            <?php } ?>

                            </li>
                            <?php $i+=1; } ?>
                    </ul>
                </div>
                <div class="col-md-10 col-12">
                    <div class="tab-content tab-content-default">

                        <div id="contenido">Cargando ofertas...</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        </div>-->
        </div>
   <!-- </div>-->



<div class="col-12">
    <div class="row">
        <div class="col-sm-6">
            <!-- 2. Horarios y Ubicación Dinámica -->
            <section id="location-hours" class="p-4 bg-light rounded shadow mb-4">
                <h2>📍 Dónde Estamos</h2>
                <p><strong>Dirección:</strong><?php echo $direccionRestaurant;?></p>
                <div class="mt-3">
                    <h4>🕒 Horarios</h4>
                    <div id="estado-container">
                        <div id="reloj" class="reloj">⏰ 00:00:00</div>
                        <div id="dia-actual" class="dia">📅 Día: Lunes</div>
                        <div id="estado" class="cerrado">Cargando estado...</div>
                    </div>
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
                <div class="mt-3">

                    <iframe id="mapa" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <script>
                        var direccion = <?php echo json_encode($ubicacionRestaurant); ?>;
                       // console.log(direccion);
                        const mapaUrl = "https://www.google.com/maps?q=" + encodeURIComponent(direccion) + "&output=embed";
                        document.getElementById("mapa").src = mapaUrl;
                    </script>



                </div>

            </section>
        </div>
        <div class="col-sm-6">
            <!-- 3. Sistema de Reservas Online -->
            <section id="reservations" class="p-4 bg-white rounded shadow mb-4">
                <h2>🚴‍♂️ Has tu pedido, contamos con domicilio <span>por un costo de <?php echo $monto;?> pesos!</span> </h2>
                <form id="pedidosForm">
                    <div>
                        <strong>Nombre:</strong><br>
                        <input type="text" name="nombre" id="nombre" required><br>
                    </div>
                    <div>
                        <strong>Teléfono:</strong><br>
                        <input type="tel" name="telefono" id="telefono" required><br>
                    </div>
                    <div>
                        <strong>Correo:</strong><br>
                        <input type="email" name="correo" id="correo" required><br>
                    </div>
                    <div>
                        <strong>Direccion:</strong><br>
                        <input type="text" name="direccion" id="direccion" required><br>
                    </div>
                    <strong>¿Qué deseas comprar?</strong>
                    <p>Selecciona los productos desde<span class="display-3 mb-0" style="font-size: 12px;font-weight: bold"><a href="#menu" class="btn-get-started"> Nuestro menú!</a> </span></p>
                    <div id="carritoVisual">Carrito: <strong>(vacío)</strong></div>
                    <input type="hidden" id="carrito" name="carrito" value=""><br>
                    <strong>Captcha de seguridad</strong><br>
                    <img id="captchaimgr" src="controllers/captcha.php?from=r" alt="CAPTCHA">
                    <input type="text" name="captchar" placeholder="Ingresa el texto de la imagen" required>
                    <button type="submit" class="mt-2">Realizar pedido</button><br><br>
                    <div class="alert-warning" style="padding: 10px"><h5 class="section-title" style="font-weight: bold">¡Importante!</h5><p>Tras confirmar su pedido, las cantidades están sujetas a cambios según el stock disponible y la demanda en línea de otros clientes. </p></div>
                </form>
                <p id="reservationMessage" class="mt-2"></p>
            </section>
        </div>
    </div>

</div>



<footer class="container-fluid  text-center py-4 bg-light">
    <p class="mb-0">&copy; <?php echo $año_actual." ".$nombreRestaurant; ?> . Todos los derechos reservados.</p>
</footer>


<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/swiper-bundle.min.js"></script>
<!--<script src="assets/js/bootstrap-icons.json"></script>-->
    <script src="assets/js/dropify.min.js"></script>
    <script src="assets/js/dropify-init.js"></script>
<script>
    // Slider automático de imágenes

    let index = 0;
    var restaurantId = <?php echo $id;?>;
    var dataProduct={};
    var intervaloActual =null;
    var intervaloSlide=null;
    var intervaloHero=null;
    var allProductData = {};
    var domicilio =  <?php echo $monto;?>;
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





    function cargarCategoria(idrestaurant,categoria,flag) {
        CacheCategoriaProductos.cargarCategoria(idrestaurant, categoria,flag);
    }







    function actualizarHero(product) {

        const contenedor = document.getElementById("hero-section");
        //console.log("actualizarHero . "+JSON.stringify(product));

        // 🔄 Limpiar imágenes anteriores
                contenedor.innerHTML = "";
        product.forEach((producto, index) => {
            const img = document.createElement("img");
            img.src = "images/" + producto.foto + "?v=" + Date.now();
            img.alt = "Slide " + (index+1);
            if (index === 0) img.classList.add("active"); // solo el primero con clase active
            contenedor.appendChild(img);
        });
        if (esNuloOVacio(product)){
            for (var i=1;i<6;i++){
                console.log("Product es nulo");
                const img = document.createElement("img");
                img.src = "images/abarrotes.png";
                img.alt = "Slide " + (i);
                if (i === 1) img.classList.add("active"); // solo el primero con clase active
                contenedor.appendChild(img);
            }

        }
            const slides = document.querySelectorAll('.hero-section img');
            // //console.log(slides);
            // console.log("InDEX: "+index);
           if (typeof intervaloSlide !== 'undefined' && intervaloSlide !== null) clearInterval(intervaloSlide);
            index=1;
            intervaloSlide =setInterval(() => {
                if (slides.hasOwnProperty(index)){
                    //slides[index].classList.remove('active');
                    slides.forEach(nav => nav.classList.remove('active'));
                    index = (index+1) % slides.length;
                    slides[index].classList.add('active');
                }
            }, 5000);



    }
    function iniciarAutoCarga(idrestaurant,categoria,flag) {

        if (intervaloActual !== null) {
            clearInterval(intervaloActual);
        }
        cargarCategoria(idrestaurant,categoria,flag);
        intervaloActual =setInterval(function () {
            cargarCategoria(idrestaurant,categoria,false);
        }, 5000); // Cada 2 segundos
    }

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
    function normalizar(str) {
        return str
            .normalize("NFD")                 // separa tilde de letra
            .replace(/[\u0300-\u036f]/g, "")  // elimina tildes
            .trim();                          // elimina espacios
    }
    // Ejecutar al cargar la página
    window.addEventListener('DOMContentLoaded', updateTabClass);

    // Ejecutar cuando se redimensiona la ventana
    window.addEventListener('resize', updateTabClass);

    //Horarios


    const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

    // Este objeto será reemplazado por el horario desde PHP si existe
    let schedule = {
        'Lunes': ['07:00', '19:00'],
        'Martes': ['07:00', '19:00'],
        'Miércoles': ['07:00', '19:00'],
        'Jueves': ['07:00', '19:00'],
        'Viernes': ['07:00', '19:00'],
        'Sábado': ['07:00', '19:00'],
        'Domingo': ['07:00', '19:00']
    };

    // Sobrescribe con los datos desde PHP (de tu variable $horarioGuardado)
    <?php if ($horarioRestaurant): ?>
     schedule = JSON.parse('<?php echo json_encode($horarioRestaurant, JSON_UNESCAPED_UNICODE); ?>');
    <?php endif; ?>
    //console.log("Schedule real:", schedule);
    //console.log("Tipo de schedule:", typeof schedule);
    // Función para normalizar claves (quita tildes y espacios)
    function normalizar(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
    }

    // Generamos un objeto con claves normalizadas para comparación segura
    const horarioNormalizado = {};
    for (let key in schedule) {
        horarioNormalizado[normalizar(key)] = schedule[key];
    }
    //console.log("horarioNormalizado "+JSON.stringify(horarioNormalizado));
    const tbody = document.getElementById('horario-table');

    for (const dia of dias) {
        const clave = normalizar(dia);
        const horas = Array.isArray(horarioNormalizado[clave]) && horarioNormalizado[clave].length === 2
            ? horarioNormalizado[clave]
            : ['07:00', '19:00'];

        var horaH=parseInt(horas[1])-12;

        tbody.innerHTML += `
      <tr>
        <td>${dia}</td>
        <td>${horas[0]} am</td>
        <td>${horaH}:00 pm</td>
      </tr>
    `;
    }

    function actualizarHorario(dia, index, value) {
        if (!schedule[dia]) schedule[dia] = ['07:00', '19:00'];
        schedule[dia][index] = value;
        document.getElementById("jsonInput").value = JSON.stringify(schedule);
    }

    // Inicializar el input oculto con JSON para enviar en formulario
    //document.getElementById("jsonInput").value = JSON.stringify(schedule);

    // Verificar si el restaurante está abierto en tiempo real
       function actualizarRelojYEstado() {
        const ahora = new Date();
        const hora = ahora.toTimeString().substring(0, 8); // HH:MM:SS
        const diaIndex = ahora.getDay();
        const diaActual = dias[diaIndex];

        // Actualiza reloj y día
        document.getElementById("reloj").textContent = "⏰ " + hora;
        document.getElementById("dia-actual").textContent = "📅 Día: " + diaActual;

        // Verifica estado del local
        const horaActual = hora.substring(0, 5); // solo HH:MM
        const clave = normalizar(diaActual);
        const horarioHoy = horarioNormalizado[clave];
        const estado = document.getElementById("estado");

        if (Array.isArray(horarioHoy) && horarioHoy.length === 2) {
            const [apertura, cierre] = horarioHoy;
            if (horaActual >= apertura && horaActual <= cierre) {
                estado.textContent = "✅ ¡Estamos Abiertos!";
                estado.classList.add("abierto");
                estado.classList.remove("cerrado");
            } else {
                estado.textContent = "❌ Estamos Cerrados.";
                estado.classList.add("cerrado");
                estado.classList.remove("abierto");
            }
        } else {
            estado.textContent = "⛔ Horario no disponible.";
            estado.classList.add("cerrado");
            estado.classList.remove("abierto");
        }
    }

    // Actualiza cada segundo
    actualizarRelojYEstado();
    setInterval(actualizarRelojYEstado, 1000);


//Fin horarios





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
      /*  const schedule = {
            'Lunes': [7, 19],
            'Martes': [7, 19],
            'Miércoles': [7, 19],
            'Jueves': [7, 19],
            'Viernes': [7, 19],
            'Sábado': [7, 19],
            'Domingo': [7, 19]
        };*/





        // Verifica si el reCAPTCHA está resuelto
        function captchaResuelto() {
            return grecaptcha.getResponse().trim() !== "";
        }

        // RESERVAS
        document.getElementById('pedidosForm').addEventListener('submit', (e) => {
            e.preventDefault();


            const inputCarrito = document.getElementById("carrito");

            let carritoData = {};

            try {
                carritoData = JSON.parse(inputCarrito.value);
            } catch (e) {
               console.error("El valor del input no es JSON válido:", e);
            }

// Verificar si está vacío
            console.log((JSON.stringify(carritoData)));
            const estaVacio = Object.keys(carritoData).length === 0;

            if (estaVacio) {
                document.getElementById('reservationMessage').textContent = "❌ Carrito vacío, agrega al menos un producto.";
                document.getElementById('reservationMessage').style.color = 'red';
            } else {
                const formData = new FormData(e.target);
                formData.append('restaurantid', restaurantId);
               console.log((JSON.stringify(formData)));
                fetch('controllers/guardar_pedido.php', {
                    method: 'POST',
                    dataType: 'json',
                    body:  formData
                })
                    .then(res => res.json())
                    .then(data => {

                      console.log((data));

                        if (data["status"] === 'success') {
                            document.getElementById('reservationMessage').textContent = "✅ ¡Pedido recibido!";
                            document.getElementById('reservationMessage').style.color = 'green';
                            const inputCarrito = document.getElementById('carrito');
                            const carritoVisual = document.getElementById('carritoVisual');



                            //

                            // Obtener contenido HTML del carrito
                            var contenidoHtml = carritoVisual.innerHTML;
                            contenidoHtml+='<div class="alert-warning" style="padding: 10px"><h5 class="section-title" style="font-weight: bold">¡Importante!</h5><p>Tras confirmar su pedido, las cantidades están sujetas a cambios según el stock disponible y la demanda en línea de otros clientes. </p></div>';

// Obtener datos del cliente
                            const nombre = document.getElementById("nombre").value.trim();
                            const telefono = document.getElementById("telefono").value.trim();
                            const correo = document.getElementById("correo").value.trim();
                            const direccion = document.getElementById("direccion").value.trim();
// const observaciones = document.getElementById("observaciones").value.trim(); // si lo necesitas

// Validación básica
                            if (!esNuloOVacio(correo)) {
                                console.log("Enviar correo a > " + correo);

                                // Preparar parámetros
                                const params = `contenido=${encodeURIComponent(contenidoHtml)}&nombre=${encodeURIComponent(nombre)}&telefono=${encodeURIComponent(telefono)}&correo=${encodeURIComponent(correo)}&direccion=${encodeURIComponent(direccion)}`;

                                const xhr = new XMLHttpRequest();
                                xhr.open("POST", "controllers/enviar_pedido.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4) {
                                        try {
                                            const respuesta = JSON.parse(xhr.responseText);
                                            if (xhr.status === 200 && respuesta.success) {
                                                console.log(respuesta.message);
                                            } else {
                                                console.error("Error en respuesta:", respuesta.message);
                                            }
                                        } catch (e) {
                                            console.error("Respuesta no válida del servidor:", xhr.responseText);
                                        }
                                    }
                                };

                                xhr.send(params);
                            }













                            carritoVisual.innerHTML = 'Carrito: <strong>(vacío)</strong>';
                            inputCarrito.value='';
                            recargarCaptcha('r');
                            carrito = {};
                            console.log("¡Pedido recibido! con carrito :"+JSON.stringify(carrito));
                           // const cantidadSpan = producto.querySelector('.cantidad');
                            document.querySelectorAll('span.cantidad').forEach(el => el.textContent = 0);
                            // if (cantidadSpan) cantidadSpan.textContent = 0;
                            inicializarEventosCarrito();
                            actualizarCarrito();
                            e.target.reset();
                            // grecaptcha.reset();
                        } else if (data["status"] === 'RECAPTCHA_FAILED') {
                            // console.log("Captcha no verificado por el servidor.");
                            document.getElementById('reservationMessage').textContent = "❌ Captcha no verificado por el servidor.";
                            document.getElementById('reservationMessage').style.color = 'red';
                            recargarCaptcha('r');
                        } else if (data["status"] === 'RATE_LIMITED') {
                            // console.log("Has enviado demasiadas solicitudes. Intenta en un minuto.");
                            document.getElementById('reservationMessage').textContent = "❌ "+data["message"];
                            document.getElementById('reservationMessage').style.color = 'red';
                            e.target.reset();
                            recargarCaptcha('r');
                        } else {
                            document.getElementById('reservationMessage').textContent = "❌ Error al guardar el pedido.";
                            document.getElementById('reservationMessage').style.color = 'red';
                            // console.log("❌ Error al guardar el pedido.");
                            e.target.reset();
                            recargarCaptcha('r');
                        }
                    });

            }


        });
    });

    function recargarCaptcha(place) {
        const vieja = document.getElementById('captchaimg' + place);
        const nueva = document.createElement('img');
        nueva.id = 'captchaimg' + place;
        nueva.alt = 'CAPTCHAZ';
        nueva.src = 'controllers/captcha.php?t=' + Date.now() + "&from=" + place;
        nueva.width = 120;
        nueva.height = 40;

        vieja.parentNode.replaceChild(nueva, vieja);
    }

    /**
     * Init swiper sliders
     */
 /*   function initSwiper(categoria) {
        document.querySelectorAll(".init-swiper").forEach(function (swiperElement) {
            let config = JSON.parse(
                swiperElement.querySelector(".swiper-config").innerHTML.trim()
            );

            if (swiperElement.classList.contains("swiper-slide")) {
                initSwiperWithCustomPagination(swiperElement, config);
            } else {
                new Swiper(swiperElement, config);
            }
        });
    }*/
    function capitalizarPrimeraLetra(texto) {
        return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
    }

    function initSwiper(categoria) {
        // Mensajes de marketing por categoría
        const mensajesMarketing = {
            alimentos: [
                "Alimentos frescos y seleccionados para tu mesa.",
                "Productos esenciales para tu cocina diaria.",
                "Lleva a casa lo mejor del campo, directo y natural.",
                "Confía en nosotros para tu despensa de calidad."
            ],
            bebidas: [
                "Refresca tu día con nuestras bebidas naturales y deliciosas.",
                "Hidratación con sabor y calidad garantizada.",
                "La bebida ideal para cada momento.",
                "Encuentra tu bebida favorita al mejor precio."
            ],
            carnicos: [
                "Cortes de carne frescos y de excelente calidad.",
                "Carnes seleccionadas para una mejor alimentación.",
                "Frescura y sabor en cada pieza.",
                "Calidad garantizada en cada corte de carne."
            ],
            confituras: [
                "Disfruta de nuestras galletas, dulces y confituras listas para saborear.",
                "El toque dulce que no puede faltar en tu día.",
                "Variedad de productos dulces para todos los gustos.",
                "Satisface tu antojo con nuestras confituras listas para consumir."
            ],
            embutidos: [
                "Embutidos de calidad, perfectos para tus comidas o picadas.",
                "Sabores clásicos que combinan con todo.",
                "La mejor selección de embutidos listos para servir.",
                "Calidad, sabor y practicidad en cada producto."
            ],
            condimentos: [
                "Potencia el sabor de tus comidas con nuestros condimentos.",
                "El ingrediente que transforma cada receta.",
                "Condimentos seleccionados para realzar tus platos.",
                "Una pizca de calidad en cada preparación."
            ]
        };


        const mensajes = mensajesMarketing[categoria.toLowerCase()] || [];

        document.querySelectorAll(".swiper-slide").forEach(function (slide) {
            // Actualizar el título
            const h3 = slide.querySelector("h3");
            if (h3) h3.textContent = capitalizarPrimeraLetra(categoria);

            // Agregar mensaje aleatorio
            const p = slide.querySelector("p span");
            if (p && mensajes.length > 0) {
                const mensajeRandom = mensajes[Math.floor(Math.random() * mensajes.length)];
                p.textContent = mensajeRandom;
            }
        });

        // 2. Inicializar los swipers
        document.querySelectorAll(".init-swiper").forEach(function (swiperElement) {
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


    // window.addEventListener("load", initSwiper);
    var carrito = {}; // Mantén este fuera para que se conserve entre llamadas

    const inputCarrito = document.getElementById('carrito');
    const carritoVisual = document.getElementById('carritoVisual');
    var areNewElement=false;

    function inicializarEventosCarrito() {
        const productos = document.querySelectorAll('.producto');
        //console.log("inicializarEventosCarrito "+JSON.stringify(productos));
        productos.forEach(producto => {
            const nombre = producto.dataset.nombre;
            const precio = parseFloat(producto.dataset.precio);
            const idproducto = producto.dataset.id;
            const cat = producto.dataset.cat;

            const btnAgregar = producto.querySelector('.agregar');
            const btnQuitar = producto.querySelector('.quitar');
            const cantidadSpan = producto.querySelector('.cantidad');
           if (carrito.hasOwnProperty(nombre)){
             //   console.log(JSON.stringify(carrito));
              //  console.log("carrito[nombre] "+carrito[nombre]+",  carrito[nombre].cantidad "+ carrito[nombre].cantidad);
                cantidadSpan.textContent = carrito[nombre].cantidad;
            }
            btnAgregar.addEventListener('click', () => {
                if (!carrito[nombre]) {
                    carrito[nombre] = { cantidad: 1, precio: precio, id: idproducto, categoria: cat };
                } else {
                    carrito[nombre].cantidad += 1;
                }
              cantidadSpan.textContent = carrito[nombre].cantidad;
                areNewElement=true;
                actualizarCarrito();
            });

            btnQuitar.addEventListener('click', () => {
                if (carrito[nombre]) {
                    carrito[nombre].cantidad -= 1;
                    if (carrito[nombre].cantidad <= 0) {
                        delete carrito[nombre];
                        cantidadSpan.textContent = 0;
                    } else {
                        cantidadSpan.textContent = carrito[nombre].cantidad;
                    }
                    areNewElement=true;
                    actualizarCarrito();
                }
            });
        });
    }


    function actualizarCarrito() {
        let carritoValido = {};
        let totalGeneral = 0;
       // let domicilio = 50;
        let html = `<div class="table-responsive">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family: Arial, sans-serif; background-color: #ffffff; padding: 10px;">
  <tr><td align="center">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:max-content;border-collapse:collapse;">
      <tr><td>
        <h2 style="text-align:center;margin:0 0 20px;">Resumen del Pedido</h2>
        <table cellpadding="0" cellspacing="0" border="1" width="100%" style="border-collapse:collapse;font-size:14px;">
          <thead>
            <tr style="background-color:#f2f2f2;">
              <th align="left" style="padding:8px;">Producto</th>
              <th align="center" style="padding:8px;">Cantidad</th>
              <th align="right" style="padding:8px;">Precio Unitario</th>
              <th align="right" style="padding:8px;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
`;

        for (const [producto, datos] of Object.entries(carrito)) {
            const subtotal = datos.cantidad * datos.precio;
            const categoria = datos.categoria || '';
            const invArr = Array.isArray(allProductData[categoria]) ? allProductData[categoria] : [];
            const item = invArr.find(p => +p.id === +datos.id);

            let advertencia = '';
            let bg = '';
            let valido = false;

            if (!item) {
                advertencia = `<span style="color:orange;">( » Chequeando disponibilidad...)</span>`;
                bg = '#fff8e1';
            } else if (+item.disponible !== 1 || +item.cantidad === 0) {
                advertencia = `<span style="color:red;">(❌ No disponible)</span>`;
                bg = '#ffe6e6';
            } else if (+item.cantidad < datos.cantidad) {
                advertencia = `<span style="color:orange;">(⚠ Stock insuficiente, rebaje la cantidad a: ${item.cantidad})</span>`;
                bg = '#fff8e1';
            } else {
                valido = true;
            }

            if (valido) totalGeneral += subtotal, carritoValido[producto] = datos;

            html += `
    <tr style="background-color:${bg};">
      <td style="padding:8px;">${producto} ${advertencia}</td>
      <td align="center" style="padding:8px;">${datos.cantidad}</td>
      <td align="right" style="padding:8px;">$${datos.precio.toFixed(2)}</td>
      <td align="right" style="padding:8px;">$${subtotal.toFixed(2)}</td>
    </tr>`;
        }

        totalGeneral += domicilio;

        html += `
    <tr style="background-color:#f2f2f2;">
      <td colspan="3" align="right" style="padding:8px;"><strong>Domicilio</strong></td>
      <td align="right" style="padding:8px;"><strong>$${domicilio.toFixed(2)}</strong></td>
    </tr>
    <tr style="background-color:#e2e2e2;">
      <td colspan="3" align="right" style="padding:8px;"><strong>Total</strong></td>
      <td align="right" style="padding:8px;"><strong>$${totalGeneral.toFixed(2)}</strong></td>
    </tr>
          </tbody>
        </table>
      </td></tr>
    </table>
  </td></tr>
</table></div>`;


        // Mostrar HTML completo (con advertencias)
        carritoVisual.innerHTML = html;

        // Guardar solo los válidos
        inputCarrito.value = JSON.stringify(carritoValido);
    }

    function sonObjetosIguales(a, b) {
        if (a === b) return true;

        if (typeof a !== typeof b || a === null || b === null) return false;

        if (Array.isArray(a)) {
            if (!Array.isArray(b) || a.length !== b.length) return false;
            return a.every((val, i) => sonObjetosIguales(val, b[i]));
        }

        if (typeof a === "object") {
            const clavesA = Object.keys(a).sort();
            const clavesB = Object.keys(b).sort();
            if (!sonObjetosIguales(clavesA, clavesB)) return false;

            return clavesA.every(key => sonObjetosIguales(a[key], b[key]));
        }

        return false;
    }



    function renderCarritoConDatosActualizados() {
        const productos = Object.values(carrito).map(c => ({
            id: c.id,
            categoria: c.categoria
        }));

        $.ajax({
            url: 'controllers/actualizar_inventario.php',
            type: 'POST',
            data: { productos: productos,restaurantid:restaurantId },
            success: function (data) {
                if (data.error) {
                   // console.error(data.error);
                    return;
                }
                // Verificar si el inventario realmente cambió

                    allProductData = data; // actualizar el inventario global
                    actualizarCarrito(); // solo se llama si hubo cambios

            },
            error: function (xhr, status, error) {
               // console.error("Error al actualizar inventario:", error);
            }
        });
    }



    window.onload = function () {
        $('.dropify').dropify();
        iniciarAutoCarga(restaurantId,"alimentos",true);

    };
    //let heroIntervalIniciado = false;
    //Logica de Cargar productos mejorada
    const CacheCategoriaProductos = (() => {
        function getClave(idrestaurant, categoria) {
            return `productos_restaurant${idrestaurant}_categoria${categoria}`;
        }

        function esNuloOVacio(valor) {
            return valor === null || valor === undefined || (Array.isArray(valor) && valor.length === 0);
        }

        function mostrarDesdeCache(idrestaurant, categoria,flag) {
            const clave = getClave(idrestaurant, categoria);
            const datos = localStorage.getItem(clave);
            if (!datos) return false;

            try {
                const parsed = JSON.parse(datos);
                //allProductData[categoria] = parsed.data;
                if (parsed && Array.isArray(parsed.data) && parsed.data.length > 0) {
                   // console.warn(`parsed.data no vacío la categoría: ${categoria}`);
                    allProductData[categoria] = parsed.data;
                } else {
                    allProductData[categoria] = []; // objeto vacío por defecto
                  //  console.warn(`parsed.data vacío o inválido para la categoría: ${categoria}`);
                }

                //console.log("mostrarDesdeCache :"+JSON.stringify(parsed));
                $('#contenido').html(parsed.html);
                inicializarEventosCarrito();
                actualizarCarrito();



                if (flag) {
                    //console.log("Entre a flag "+flag);
                    //heroIntervalIniciado = true;
                    actualizarHero(parsed.data);
                    initSwiper(categoria);
                        // Ejecutar por primera vez
                }

                document.querySelector(".swiper-wrapper").classList.remove("oculto");
                document.getElementById("testimonials").classList.remove("oculto");
                return true;
            } catch (e) {
               // console.warn("Error al parsear localStorage:", e);
                return false;
            }
        }

        function guardarEnCache(idrestaurant, categoria, data) {
            const clave = getClave(idrestaurant, categoria);
            localStorage.setItem(clave, JSON.stringify(data));
        }

        function cargarCategoria(idrestaurant, categoria,flag) {
            const clave = getClave(idrestaurant, categoria);
            const datosCache = localStorage.getItem(clave);
            let datosAnteriores = datosCache ? JSON.parse(datosCache).data : null;
           // heroIntervalIniciado = false;
            // Intentar mostrar desde cache primero
            if (flag){
                mostrarDesdeCache(idrestaurant, categoria,flag);
            }


            // Luego llamar al servidor
            $.ajax({
                url: '/controllers/mostrar_ofertas.php',
                dataType: 'json',
                method: 'POST',
                data: { idrestaurant, categoria },
                success: function(data) {
                    if (data["status"] === "success") {
                        const nuevosDatos = JSON.stringify(data["data"]);
                        allProductData[categoria] = data["data"];
                        if (JSON.stringify(datosAnteriores) === nuevosDatos) {
                          //  console.log('Datos sin cambios, no se actualiza la vista.');
                            if (!esNuloOVacio(document.getElementById('section-title'))) {
                                document.getElementById('section-title').innerText = 'En estos momentos no tenemos ' + categoria + ' disponibles!';
                            }
                          //  console.log(JSON.stringify(carrito));
                            renderCarritoConDatosActualizados();
                        } else {
                          //  console.log('Actualizar la vista.');
                            $('#contenido').html(data["html"]);
                            inicializarEventosCarrito();
                            actualizarCarrito();
                            if (esNuloOVacio(data["data"])) {

                                if (flag) {
                                   // heroIntervalIniciado = true;
                                    if (typeof intervaloHero !== 'undefined' && intervaloHero !== null) clearInterval(intervaloHero);
                                    // Ejecutar por primera vez
                                        actualizarHero([]);
                                        initSwiper(categoria);
                                    // Repetir cada 30 segundos
                                    intervaloHero=   setInterval(() => {
                                            actualizarHero([]);
                                            initSwiper(categoria);
                                        }, 30000);
                                }

                                //const contenedor = document.getElementById("hero-section");
                                //contenedor.innerHTML = "";
                               // if (typeof intervaloSlide !== 'undefined' && intervaloSlide !== null) clearInterval(intervaloSlide);
                               // document.querySelector(".swiper-wrapper").classList.add("oculto");
                            } else {
                                if (flag) {
                                    //heroIntervalIniciado = true;
                                    if (typeof intervaloHero !== 'undefined' && intervaloHero !== null) clearInterval(intervaloHero);
                                        // Ejecutar por primera vez
                                        actualizarHero(data["data"]);
                                        initSwiper(categoria);

                                        // Repetir cada 30 segundos
                                    intervaloHero=  setInterval(() => {
                                            actualizarHero(data["data"]);
                                            initSwiper(categoria);
                                        }, 30000);


                                }

                                document.querySelector(".swiper-wrapper").classList.remove("oculto");
                                document.getElementById("testimonials").classList.remove("oculto");
                            }

                            // Guardar en cache
                            guardarEnCache(idrestaurant, categoria, {
                                data: data["data"],
                                html: data["html"]
                            });
                        }
                    }
                }
            });
        }

        return {
            cargarCategoria
        };
    })();



/*    /!*Chequear carrito*!/
    const inputCarrito = document.getElementById("carrito");

    let carritoData = {};

    try {
        carritoData = JSON.parse(inputCarrito.value);
    } catch (e) {
        console.error("El valor del input no es JSON válido:", e);
    }*/

</script>

<!-- ==== UI ENHANCEMENTS: floating cart injected ==== -->
<div id="floatingCartWrapper" aria-live="polite">
  <div id="floatingCartModal" role="dialog" aria-modal="false" aria-labelledby="floatingCartHeader">
    <div id="floatingCartHeader">
      <span>Tu carrito</span>
      <button id="floatingCartClose" title="Cerrar">&times;</button>
    </div>
    <div id="floatingCartBody">
      <ul id="floatingCartList"></ul>
    </div>
    <div id="floatingCartFooter">
      <span>Artículos: <strong id="floatingCartItems">0</strong></span>
      <span>Domicilio: <strong id="floatingDomicilio">$ <?php echo $monto?></strong></span>
      <span class="total">Total: <strong id="floatingCartTotal">$0.00</strong></span>
    </div>
  </div>

  <button id="floatingCartBtn" title="Ver carrito" aria-haspopup="dialog" aria-controls="floatingCartModal">
    <!-- inline cart icon -->
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M3 3h2l.4 2M7 13h9l3-7H6.4M7 13L6 6M7 13l-1.2 6H19M7 13h12M10 21a1 1 0 1 0 0-2a1 1 0 0 0 0 2Zm8 0a1 1 0 1 0 0-2a1 1 0 0 0 0 2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span id="floatingCartCount">0</span>
  </button>
</div>

<!-- ==== UI ENHANCEMENTS: floating cart JS injected ==== -->
<script>
(function(){
  // Safe access to existing variables/functions
  const $id = (x)=>document.getElementById(x);
  let autoHideTimer = null;

  function currency(n){
    try { return new Intl.NumberFormat('es-ES',{style:'currency', currency:'CUP'}).format(n); }
    catch(e){ return '$' + (Math.round(n*100)/100).toFixed(2); }
  }

  // Toggle modal visibility
  function showCartModal(){
    clearTimeout(autoHideTimer);
    $id('floatingCartModal').classList.add('show');
    // Auto-hide after 5 seconds
    autoHideTimer = setTimeout(hideCartModal, 5000);
      areNewElement=false;
  }
  function hideCartModal(){
    $id('floatingCartModal').classList.remove('show');
  }

  // Update floating cart UI from global `carrito`
  function updateFloatingCartFromCarrito(){

    try {
      if (typeof carrito === 'undefined') return;
      const list = $id('floatingCartList');
      const countEl = $id('floatingCartCount');
      const itemsEl = $id('floatingCartItems');
      const itemsDomicilio = $id('floatingDomicilio');

      const totalEl = $id('floatingCartTotal');
      list.innerHTML = '';

      let totalItems = 0;
      let sum = 0;

      Object.entries(carrito).forEach(([nombre, datos]) => {

          const categoria = datos.categoria || '';
          const invArr = Array.isArray(allProductData[categoria]) ? allProductData[categoria] : [];
          const item = invArr.find(p => +p.id === +datos.id);

          let advertencia = '';
          let bg = '';
          const li = document.createElement('li');
          if (!item) {
             return;
          } else if (+item.disponible !== 1 || +item.cantidad === 0) {
              advertencia = `<span style="color:red;">(❌ No disponible)</span>`;
              bg = '#ffe6e6';

              li.innerHTML = `<span style="background-color:${bg};">${nombre} ${advertencia}</span>`;
              list.appendChild(li);
              return;
          } else if (+item.cantidad < datos.cantidad) {
              advertencia = `<span style="color:orange;">(⚠ Stock insuficiente, rebaje la cantidad a: ${item.cantidad})</span>`;
              bg = '#fff8e1';
              li.innerHTML = `<span style="background-color:${bg};">${nombre} ${advertencia}</span>`;
              list.appendChild(li);
              return;
          }









        totalItems += (datos.cantidad || 0);
        sum += (datos.cantidad || 0) * (datos.precio || 0);

        li.innerHTML = `<span>${nombre} &times; ${datos.cantidad}</span><strong>${currency(datos.precio)}</strong>`;
        list.appendChild(li);
      });


      countEl.textContent = totalItems;
      itemsEl.textContent = totalItems;
      itemsDomicilio.textContent=domicilio;
      totalEl.textContent = currency(sum+domicilio);
      console.log(" areNewElement= "+ areNewElement);
      // Briefly show modal on update
      if (totalItems > 0 && areNewElement) showCartModal();
    } catch(e){ console.error(e); }
  }

  // Expose for debugging
  window.updateFloatingCartFromCarrito = updateFloatingCartFromCarrito;

  // Click handlers
  $id('floatingCartBtn')?.addEventListener('click', function(){
    const modal = $id('floatingCartModal');
    if (modal.classList.contains('show')) hideCartModal(); else showCartModal();
  });
  $id('floatingCartClose')?.addEventListener('click', hideCartModal);

  // Close when clicking outside the modal (but not the button)
  document.addEventListener('click', function(ev){
    const wrap = $id('floatingCartWrapper');
    if (!wrap) return;
    if (!wrap.contains(ev.target)) hideCartModal();
  });

  // Wrap original actualizarCarrito to also refresh floating UI + keep hidden input in sync
  const original = (typeof actualizarCarrito === 'function') ? actualizarCarrito : null;
  if (original) {
    window.actualizarCarrito = function(){
      try {
        original.apply(this, arguments);
      } finally {
        // Keep hidden input #carrito and #carritoVisual are already handled in original code;
        // here we just update floating UI.
          updateFloatingCartFromCarrito();

      }
    };
  } else {
    // If not found, periodically try to update from carrito anyway
   // setInterval(updateFloatingCartFromCarrito, 1000);
  }

  // On DOM ready: tiny responsive tweaks (optional)
 document.addEventListener('DOMContentLoaded', function(){
    updateFloatingCartFromCarrito();
  });

  // On resize (mobile friendliness): no-op but placeholder for future hooks
  window.addEventListener('resize', function(){ /* layout could adapt if needed */ });

})();
</script>
</body>
</html>






