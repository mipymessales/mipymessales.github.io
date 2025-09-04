<?php
// Inicializa la sesión
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,1).'/');
require_once ROOT_DIR ."controllers/class.SqlInjectionUtils.php";
include_once ROOT_DIR."controllers/Host.php";
if (SqlInjectionUtils::checkSqlInjectionAttempt($_POST)){
    header("Location:" .   Host::getHOSTNAME(). "templates/404.php");
    exit();
}

?>



<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Mipymes Sales</title>

        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <link href="assets/css/bootstrap-icons.css" rel="stylesheet">

        <link href="assets/css/templatemo-tiya-golf-club.css" rel="stylesheet">
        <style>
            .shadow-lg {
                box-shadow: 0 1rem 3rem rgb(19, 19, 19) !important;
                background-color: white;
            }
            .separador-hasta-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 30px;
                flex-wrap: wrap;
                margin: 40px 0;
            }

            .separador-hasta-text {
                font-size: 2.5rem;
                font-weight: bold;
                margin: 0 25px;
                white-space: nowrap;
                user-select: none;
                text-align: center;
            }

            .linea-horizontal,
            .linea-vertical {
                animation: cambio-color 3s ease-in-out infinite;
                position: relative;
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

            /* Animaciones */
            @keyframes dibujar-linea {
                0%, 100% {
                    stroke-dashoffset: 180;
                }
                50% {
                    stroke-dashoffset: 0;
                }
            }

            @keyframes cambio-color {
                0%, 100% {
                    color: #000000;
                }
                50% {
                    color: #F2CC8F;
                }
            }

            /* RESPONSIVE: Cambia a columna + muestra líneas verticales */
            @media (max-width: 768px) {
                .separador-hasta-wrapper {
                    /*flex-direction: column;*/
                    gap: 0px;
                }

                .linea-horizontal {
                    display: none;
                }

                .linea-vertical {
                    display: block;
                }
                .timeline-container .vertical-scrollable-timeline .list-progress {
                    height: 75%;
                }
            }
            .custom-block-overlay-text {
                position: relative;
                z-index: 2;
            }
            .hundido{
                padding: 20px;
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85));
                box-shadow: inset 5px 5px 10px rgba(162, 162, 162, 0.7), inset -5px -5px 10px rgba(255, 255, 255, 0.5);
                color: #FFF;
            }
        </style>
<!--

TemplateMo 587 Tiya Golf Club

https://templatemo.com/tm-587-tiya-golf-club

-->
    </head>
    
    <body>

        <main>

            <nav class="navbar navbar-expand-lg">                
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="index.html">
                        <img src="images/logo.png" class="navbar-brand-image img-fluid" alt="Tiya Golf Club">
                        <!--<span class="navbar-brand-text">
                            Tiya
                            <small>Golf Club</small>
                        </span>-->
                    </a>

                    <div class="d-lg-none ms-auto me-3">
                        <a class="btn custom-btn custom-border-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Member Login</a>
                    </div>
    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-lg-auto">
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_1">Inicio</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_2">Sobre nosotros</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_3">Como funciona</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_4">Módulos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_5">Contacto</a>
                            </li>

                          <!--  <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>

                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                    <li><a class="dropdown-item" href="event-listing.html">Event Listing</a></li>

                                    <li><a class="dropdown-item" href="event-detail.html">Event Detail</a></li>
                                </ul>
                            </li>-->
                        </ul>

                        <div class="d-none d-lg-block ms-lg-3">
                            <a class="btn custom-btn custom-border-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Iniciar sesión</a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">                
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel" style="color: #F2CC8F">Entrar al panel</h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="background-color: #F2CC8F;"></button>
                </div>
                
                <div class="offcanvas-body d-flex flex-column">
                    <form class="custom-form member-login-form" action="#" method="post" role="form">

                        <div class="member-login-form-body">
                            <div class="mb-4">
                                <label class="form-label mb-2" for="member-login-number" style="color: #F2CC8F">Usuario</label>

                                <input type="text" name="member-login-number" id="member-login-number" class="form-control" placeholder="Usuario" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label mb-2" for="member-login-password" style="color: #F2CC8F">Contraseña</label>

                                <input type="password" name="member-login-password" id="member-login-password" pattern="[0-9a-zA-Z]{4,10}" class="form-control" placeholder="Contraseña" required="">
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                              
                                <label class="form-check-label" for="flexCheckDefault" style="color: #F2CC8F">
                                    Remember me
                                </label>
                            </div>

                            <div class="col-lg-5 col-md-7 col-8 mx-auto">
                                <button type="submit" class="form-control">Login</button>
                            </div>

                            <div class="text-center my-4">
                                <a href="#">Olvidé la contraseña?</a>
                            </div>
                        </div>
                    </form>

                    <div class="mt-auto mb-5">
                        <p>
                            <strong class="text-white me-3">Alguna duda?</strong>

                            <a href="mipymessales@gmail.com" class="contact-link" style="color: #F2CC8F">
                                mipymessales@gmail.com
                            </a>
                        </p>
                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#F2CC8F" fill-opacity="1" d="M0,224L34.3,192C68.6,160,137,96,206,90.7C274.3,85,343,139,411,144C480,149,549,107,617,122.7C685.7,139,754,213,823,240C891.4,267,960,245,1029,224C1097.1,203,1166,181,1234,160C1302.9,139,1371,117,1406,106.7L1440,96L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
            </div>
            

            <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">

                <div class="section-overlay"></div>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#358FE6" fill-opacity="1" d="M0,224L34.3,192C68.6,160,137,96,206,90.7C274.3,85,343,139,411,144C480,149,549,107,617,122.7C685.7,139,754,213,823,240C891.4,267,960,245,1029,224C1097.1,203,1166,181,1234,160C1302.9,139,1371,117,1406,106.7L1440,96L1440,0L1405.7,0C1371.4,0,1303,0,1234,0C1165.7,0,1097,0,1029,0C960,0,891,0,823,0C754.3,0,686,0,617,0C548.6,0,480,0,411,0C342.9,0,274,0,206,0C137.1,0,69,0,34,0L0,0Z"></path></svg>

                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12 mb-5 mb-lg-0">
                            <h3 class="text-white">Gestiona tus ventas</h3>

                            <h3 class="cd-headline rotate-1 text-white mb-4 pb-2">
                                <span>de forma</span>
                                <span class="cd-words-wrapper">
                                    <b class="is-visible">Fácil</b>
                                    <b>Segura</b>
                                    <b>Lifestyle</b>
                                </span>
                            </h3>

                            <div class="custom-btn-group">
                                <a href="#section_2" class="btn custom-btn smoothscroll me-3">Nuestra historia</a>
                                <a class="link smoothscroll" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Iniciar sesión</a>
                            </div>

                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="ratio ratio-16x9">
                                <video id="miVideo" width="560" height="315" autoplay muted loop>
                                    <source src="media/videom.mp4" type="video/mp4">
                                    Tu navegador no soporta la reproducción de video.
                                </video>

                            </div>
                        </div>

                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,224L34.3,192C68.6,160,137,96,206,90.7C274.3,85,343,139,411,144C480,149,549,107,617,122.7C685.7,139,754,213,823,240C891.4,267,960,245,1029,224C1097.1,203,1166,181,1234,160C1302.9,139,1371,117,1406,106.7L1440,96L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
            </section>
            <section style="margin-top: -200px">
                <div class="container">
                    <div class="row justify-content-start align-items-center">

                        <!-- Recuadro 1 -->
                        <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                            <div class="custom-block bg-white shadow-lg">
                                <a href="#">
                                    <div class="d-flex">
                                        <div>
                                            <h5 class="mb-2" style="font-weight: bold">Desde productos en línea</h5>
                                            <p class="mb-0">Gestiona, vende y crece: obten más visualizaciones de tus productos, actualizaciones en tiempo real de tu catálogo, todo en un solo lugar.</p>
                                        </div>
                                    </div>
                                    <img src="/images/marketing.png" class="custom-block-image img-fluid" alt="">
                                </a>
                            </div>
                        </div>

                        <!-- Definición de flechas -->
                        <svg style="display: none;">
                            <defs>
                                <!-- Flecha derecha -->
                                <marker id="flecha" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="10" markerHeight="10" orient="auto">
                                    <path d="M 0 0 L 10 5 L 0 10 z" fill="currentColor" />
                                </marker>

                                <!-- Flecha hacia abajo -->
                                <marker id="flecha-abajo" viewBox="0 0 10 10" refX="5" refY="7" markerWidth="10" markerHeight="10" orient="auto">
                                    <path d="M 0 0 L 5 10 L 10 0 z" fill="currentColor" />
                                </marker>
                            </defs>
                        </svg>

                        <!-- Contenido -->
                        <div class="col-lg-2 d-flex justify-content-center align-items-center mb-4 mb-lg-0">
                            <div style="width: 100%">
                                <!-- CONTENIDO -->
                                <div class="separador-hasta-wrapper">

                                    <!-- Línea izquierda -->
                                    <div class="linea-horizontal">
                                        <svg viewBox="0 0 180 30">
                                            <defs>
                                                <marker id="flecha-h" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="10" markerHeight="10" orient="auto">
                                                    <path d="M 0 0 L 10 5 L 0 10 z" fill="currentColor" />
                                                </marker>
                                            </defs>
                                            <line x1="0" y1="15" x2="180" y2="15"  marker-end="url(#flecha-h)" />
                                        </svg>
                                    </div>
                                    <div class="linea-vertical">
                                        <svg viewBox="0 0 60 180">
                                            <defs>
                                                <marker id="flecha-v" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="10" markerHeight="10" orient="">
                                                    <path d="M 0 0 L 5 10 L 10 0 z" fill="currentColor" />
                                                </marker>



                                            </defs>
                                            <line x1="15" y1="0" x2="15" y2="180" marker-end="url(#flecha-v)" />
                                        </svg>
                                    </div>

                                    <!-- Texto central -->
                                    <div class="separador-hasta-text">hasta</div>

                                    <!-- Línea derecha (horizontal invertida o vertical normal) -->
                                    <div class="linea-horizontal" style="transform: scaleX(-1);">
                                        <svg viewBox="0 0 180 30">
                                            <defs>
                                                <marker id="flecha-ho" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="10" markerHeight="10" orient="auto">
                                                    <path d="M 0 0 L 10 5 L 0 10 z" fill="currentColor" />
                                                </marker>
                                            </defs>
                                            <line x1="0" y1="15" x2="180" y2="15" marker-end="url(#flecha-ho)" />
                                        </svg>
                                    </div>
                                    <div class="linea-vertical">
                                        <svg viewBox="0 0 60 180">
                                            <defs>
                                                <marker id="flecha-ve" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="10" markerHeight="10" orient="">
                                                    <path d="M 0 0 L 5 10 L 10 0 z" fill="currentColor" />
                                                </marker>



                                            </defs>
                                            <line x1="15" y1="0" x2="15" y2="180" marker-end="url(#flecha-ve)" />
                                        </svg>
                                    </div>

                                </div>

                            </div>
                        </div>



                        <!-- Recuadro 2 -->
                        <div class="col-lg-5 col-12">
                            <div class="custom-block custom-block-overlay shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <img src="/images/finance.png" class="custom-block-image img-fluid" alt="">

                                    <div class="custom-block-overlay-text d-flex">
                                        <div>
                                            <h5 class="mb-2" style="color: var(--secondary-color);font-weight: bold">Ventas , cierres, gastos y más.</h5>
                                            <p class="text-black" style="color: var(--secondary-color)">Tu negocio digital, bajo control: lleva tus gastos y cierres del día sin complicaciones</p>
                                            <a href="#section_3" class="btn custom-btn mt-2 mt-lg-3" style="background: rgba(43, 35, 35, 0.18)">Me interesa</a>
                                        </div>
                                    </div>

                               <!--     <div class="social-share d-flex">
                                        <p class="me-4">Share:</p>
                                        <ul class="social-icon">
                                            <li class="social-icon-item">
                                                <a href="#" class="social-icon-link bi-twitter"></a>
                                            </li>
                                            <li class="social-icon-item">
                                                <a href="#" class="social-icon-link bi-facebook"></a>
                                            </li>
                                            <li class="social-icon-item">
                                                <a href="#" class="social-icon-link bi-pinterest"></a>
                                            </li>
                                        </ul>
                                        <a href="#" class="custom-icon bi-bookmark ms-auto"></a>
                                    </div>-->

                                    <div class="section-overlay-card"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>







            <section class="about-section section-padding" id="section_2">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-12 col-12 text-center">
                            <h2 class="mb-lg-5 mb-4">Sobre nosotros</h2>
                        </div>

                        <div class="col-lg-5 col-12 me-auto mb-4 mb-lg-0 ">
                            <h3 class="mb-3">Mipymes Sales</h3>

                            <p><strong>Nuestra empresa</strong> se especializa en el desarrollo de soluciones digitales a medida para pequeñas y medianas empresas. Diseñamos y construimos aplicaciones web y móviles que facilitan la gestión, control y digitalización de productos para ventas en línea. </p>

                            <p>Nuestro compromiso es ayudar a los negocios a modernizarse, optimizar sus procesos y alcanzar nuevos niveles de eficiencia a través de herramientas tecnológicas intuitivas, seguras y adaptadas a sus necesidades reales.</p>
                        </div>
                        <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                            <div class="custom-block bg-white">
                                <a href="#">
                                    <div class="d-flex">
                                        <div>
                                            <h5 class="mb-2" style="font-weight: bold">Desde productos en línea</h5>
                                            <p class="mb-0">Gestiona, vende y crece: obten más visualizaciones de tus productos, actualizaciones en tiempo real de tu catálogo, todo en un solo lugar.</p>
                                        </div>
                                    </div>
                                    <img src="/images/marketing.png" class="custom-block-image img-fluid" alt="">
                                </a>
                            </div>
                        </div>

                       <!-- <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-0">
                            <div class="member-block">
                                <div class="member-block-image-wrap">
                                    <img src="images/members/portrait-young-handsome-businessman-wearing-suit-standing-with-crossed-arms-with-isolated-studio-white-background.jpg" class="member-block-image img-fluid" alt="">

                                    <ul class="social-icon">
                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-twitter"></a>
                                        </li>

                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-whatsapp"></a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="member-block-info d-flex align-items-center">
                                    <h4>Michael</h4>

                                    <p class="ms-auto">Founder</p>
                                </div>
                            </div>
                        </div>-->

                  <!--      <div class="col-lg-3 col-md-6 col-12">
                            <div class="member-block">
                                <div class="member-block-image-wrap">
                                    <img src="images/members/successful-asian-lady-boss-red-blazer-holding-clipboard-with-documens-pen-working-looking-happy-white-background.jpg" class="member-block-image img-fluid" alt="">

                                    <ul class="social-icon">
                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-linkedin"></a>
                                        </li>
                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-twitter"></a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="member-block-info d-flex align-items-center">
                                    <h4>Sandy</h4>

                                    <p class="ms-auto">Co-Founder</p>
                                </div>
                            </div>
                        </div>-->

                    </div>
                </div>

            </section>


            <section class="timeline-section section-padding" id="section_3">
                <div class="container">
                    <div class="row">

                        <div class="col-12 text-center">

                            <h2 class="mb-lg-5 mb-4">Como funciona ?</h2>
                        </div>

                        <div class="col-lg-10 col-12 mx-auto">
                            <div class="timeline-container">
                                <ul class="vertical-scrollable-timeline" id="vertical-scrollable-timeline">
                                    <div class="list-progress">
                                        <div class="inner"></div>
                                    </div>

                                    <li>
                                        <h4 class="mb-3">Ofertas</h4>

                                        <p style="color: var(--secondary-color);">Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis, cumque magnam? Sequi, cupiditate quibusdam alias illum sed esse ad dignissimos libero sunt, quisquam numquam aliquam? Voluptas, accusamus omnis?</p>

                                        <div class="icon-holder">
                                           <!-- <i class="bi-search"></i>-->
                                            <svg style="width: 42px;height: 42px;">
                                                <symbol id="icon-posts" viewBox="0 0 20 20">
                                                    <path d="M2.50001 10H7.5C7.72102 10 7.93298 9.9122 8.08926 9.75592C8.24554 9.59964 8.33334 9.38768 8.33334 9.16667V4.16667C8.33334 3.94565 8.24554 3.73369 8.08926 3.57741C7.93298 3.42113 7.72102 3.33333 7.5 3.33333H2.50001C2.27899 3.33333 2.06703 3.42113 1.91075 3.57741C1.75447 3.73369 1.66667 3.94565 1.66667 4.16667V9.16667C1.66667 9.38768 1.75447 9.59964 1.91075 9.75592C2.06703 9.9122 2.27899 10 2.50001 10ZM3.33334 5H6.66667V8.33333H3.33334V5ZM10.8333 6.66667H17.5C17.721 6.66667 17.933 6.57887 18.0893 6.42259C18.2455 6.26631 18.3333 6.05435 18.3333 5.83333C18.3333 5.61232 18.2455 5.40036 18.0893 5.24408C17.933 5.0878 17.721 5 17.5 5H10.8333C10.6123 5 10.4004 5.0878 10.2441 5.24408C10.0878 5.40036 10 5.61232 10 5.83333C10 6.05435 10.0878 6.26631 10.2441 6.42259C10.4004 6.57887 10.6123 6.66667 10.8333 6.66667V6.66667ZM10.8333 15H2.50001C2.27899 15 2.06703 15.0878 1.91075 15.2441C1.75447 15.4004 1.66667 15.6123 1.66667 15.8333C1.66667 16.0543 1.75447 16.2663 1.91075 16.4226C2.06703 16.5789 2.27899 16.6667 2.50001 16.6667H10.8333C11.0544 16.6667 11.2663 16.5789 11.4226 16.4226C11.5789 16.2663 11.6667 16.0543 11.6667 15.8333C11.6667 15.6123 11.5789 15.4004 11.4226 15.2441C11.2663 15.0878 11.0544 15 10.8333 15ZM17.5 11.6667H2.50001C2.27899 11.6667 2.06703 11.7545 1.91075 11.9107C1.75447 12.067 1.66667 12.279 1.66667 12.5C1.66667 12.721 1.75447 12.933 1.91075 13.0893C2.06703 13.2455 2.27899 13.3333 2.50001 13.3333H17.5C17.721 13.3333 17.933 13.2455 18.0893 13.0893C18.2455 12.933 18.3333 12.721 18.3333 12.5C18.3333 12.279 18.2455 12.067 18.0893 11.9107C17.933 11.7545 17.721 11.6667 17.5 11.6667ZM17.5 8.33333H10.8333C10.6123 8.33333 10.4004 8.42113 10.2441 8.57741C10.0878 8.73369 10 8.94565 10 9.16667C10 9.38768 10.0878 9.59964 10.2441 9.75592C10.4004 9.9122 10.6123 10 10.8333 10H17.5C17.721 10 17.933 9.9122 18.0893 9.75592C18.2455 9.59964 18.3333 9.38768 18.3333 9.16667C18.3333 8.94565 18.2455 8.73369 18.0893 8.57741C17.933 8.42113 17.721 8.33333 17.5 8.33333Z"/>
                                                </symbol>
                                                <use xlink:href="#icon-posts" ></use>
                                            </svg>
                                        </div>
                                    </li>

                                    <li>
                                        <h4 class=" mb-3">Pedidos</h4>

                                        <p style="color: var(--secondary-color);">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint animi necessitatibus aperiam repudiandae nam omnis est vel quo, nihil repellat quia velit error modi earum similique odit labore. Doloremque, repudiandae?</p>

                                        <div class="icon-holder">
                                            <i>🚴‍♂️</i>
                                        </div>
                                    </li>

                                    <li>
                                        <h4 class=" mb-3">Ventas</h4>

                                        <p style="color: var(--secondary-color);">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Animi vero quisquam, rem assumenda similique voluptas distinctio, iste est hic eveniet debitis ut ducimus beatae id? Quam culpa deleniti officiis autem?</p>

                                        <div class="icon-holder">
                                            <i>$</i>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-5">
                            <p class="text-white">
                                Quieres saber más?
                                <a href="mailto:mipymessales@gmail.com?subject=Dudas%20sobre%20el%20servicio&body=Hola%2C%20tengo%20una%20pregunta%20acerca%20de..."
                                   class="btn custom-btn custom-border-btn ms-3">
                                    Enviar correo con tus dudas
                                </a>

                            </p>
                        </div>
                    </div>
                </div>
            </section>




            <section class="explore-section" id="section_4" >
                <svg viewBox="0 0 1440 320" style="display:block; transform: rotate(180deg)">
                    <path fill="#000000B3" d="M0,64L48,85.3C96,107,192,149,288,160C384,171,480,149,576,154.7C672,160,768,192,864,213.3C960,235,1056,245,1152,224C1248,203,1344,149,1392,122.7L1440,96V320H1392C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320H0V64Z"></path>
                </svg>

                <div class="container">
                    <div class="row">

                        <div class="col-12 text-center">
                            <h2 class="mb-4">Módulos</h2>
                        </div>

                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="design-tab" data-bs-toggle="tab" data-bs-target="#design-tab-pane" type="button" role="tab" aria-controls="design-tab-pane" aria-selected="true">Ofertas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="marketing-tab" data-bs-toggle="tab" data-bs-target="#marketing-tab-pane" type="button" role="tab" aria-controls="marketing-tab-pane" aria-selected="false">Web principal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance-tab-pane" type="button" role="tab" aria-controls="finance-tab-pane" aria-selected="false">Pedidos</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ventas-tab" data-bs-toggle="tab" data-bs-target="#ventas-tab-pane" type="button" role="tab" aria-controls="ventas-tab-pane" aria-selected="false">Ventas</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="music-tab" data-bs-toggle="tab" data-bs-target="#music-tab-pane" type="button" role="tab" aria-controls="music-tab-pane" aria-selected="false">Cierres</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education-tab-pane" type="button" role="tab" aria-controls="education-tab-pane" aria-selected="false">Configuración</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="container">
                    <div class="row">

                        <div class="col-12">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="design-tab-pane" role="tabpanel" aria-labelledby="design-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12 mb-4 mb-lg-0">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Ofertas</h5>

                                                            <p class="mb-0">Tendrás un menú donde podrás agregar, editar o eliminar los productos que deseas mostrar a tus clientes para la venta.</p>
                                                        </div>
                                                    </div>

                                                    <img src="images/ofertas.png" class="custom-block-image img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="marketing-tab-pane" role="tabpanel" aria-labelledby="marketing-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-0">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Productos</h5>

                                                            <p class="mb-0">Los productos saldrán organizados por categorías y sin preocuparte por actualizar el inventario: la aplicación lo hace todo por ti, de forma automática. </p>
                                                        </div>
                                                    </div>

                                                    <img src="images/menu.png" class="custom-block-image img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="custom-block custom-block-overlay shadow-lg">
                                                <div class="d-flex flex-column h-100">
                                                    <img src="images/pedidos.png" class="custom-block-image img-fluid" alt="">

                                                    <div class="custom-block-overlay-text d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Pedidos</h5>

                                                            <p class="mb-0">Los clientes podrán realizar pedidos en línea a través de un formulario simple. En este formulario se verificará en tiempo real la disponibilidad de cada producto seleccionado, y recibirás una orden con todos los datos necesarios para gestionarla a tu manera.</p>

                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="finance-tab-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0">   <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12 mb-4 mb-lg-0">
                                        <div class="custom-block bg-white shadow-lg">
                                            <a href="#">
                                                <div class="d-flex">
                                                    <div>
                                                        <h5 class="mb-2">Pedidos</h5>

                                                        <p class="mb-0">Gestiona fácilmente los pedidos realizados por tus clientes en la web. Podrás revisar cada solicitud, modificar cantidades, eliminar productos y aprobarlos según la disponibilidad en tu inventario.</p>
                                                    </div>

                                                    <span class="badge bg-finance rounded-pill ms-auto">30</span>
                                                </div>

                                                <img src="images/pedidossales.png" class="custom-block-image img-fluid" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="tab-pane fade" id="ventas-tab-pane" role="tabpanel" aria-labelledby="ventas-tab" tabindex="0">   <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12 mb-4 mb-lg-0">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Ventas</h5>

                                                            <p class="mb-0">No necesitarás una libreta como inventario, la aplicación guardará todas las ventas del día, la disponibilidad de cada producto y cuanto estás generando de ganancia de manera automática. </p>
                                                        </div>

                                                        <span class="badge bg-finance rounded-pill ms-auto">30</span>
                                                    </div>

                                                    <img src="images/ventas.png" class="custom-block-image img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="music-tab-pane" role="tabpanel" aria-labelledby="music-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12 mb-4 mb-lg-3">
                                            <div class="custom-block bg-white shadow-lg">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Cierres</h5>

                                                            <p class="mb-0">Resumen de ventas detallado por categoría y cantidad de productos con sus precio de compra/venta y ganancia.</p>
                                                        </div>
                                                    </div>

                                                    <img src="images/cierres.png" class="custom-block-image img-fluid" alt="">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-12 mb-4 mb-lg-3">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Cierre diario y mesual</h5>

                                                            <p class="mb-0">Accede a las ventas de cualquier día o mes al instante, con solo un clic.</p>
                                                        </div>
                                                        <span class="badge bg-education rounded-pill ms-auto">📈</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-lg-8 col-md-8 col-12">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Gastos</h5>

                                                            <p class="mb-0">Podrás registrar tus gastos diarios, como pagos a trabajadores, domicilios, arrendamiento del local, entre otros, y visualizar la ganancia real de tus ventas.</p>
                                                        </div>
                                                        <span class="badge bg-education rounded-pill ms-auto">💰</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="education-tab-pane" role="tabpanel" aria-labelledby="education-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-3">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Cierre diario y mesual</h5>

                                                            <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                                        </div>

                                                        <span class="badge bg-education rounded-pill ms-auto">80</span>
                                                    </div>

                                                    <img src="images/topics/undraw_Graduation_re_gthn.png" class="custom-block-image img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="custom-block bg-white shadow-lg">
                                                <a href="#">
                                                    <div class="d-flex">
                                                        <div>
                                                            <h5 class="mb-2">Gastos</h5>

                                                            <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                                        </div>

                                                        <span class="badge bg-education rounded-pill ms-auto">75</span>
                                                    </div>

                                                    <img src="images/topics/undraw_Educator_re_ju47.png" class="custom-block-image img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
            </section>




            <section class="faq-section section-padding">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12">
                            <h2 class="mb-4">Dudas y Preguntas Frecuentes</h2>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-5 col-12">
                            <img src="images/faq_graphic.jpg" class="img-fluid" alt="FAQs">
                        </div>

                        <div class="col-lg-6 col-12 m-auto">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Que pasa si el dispositivo donde tengo la información se rompe?
                                        </button>
                                    </h2>

                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Todo la informacion queda guardada en la nube. <strong>Puedes acceder a ella desde cualquier otro dispositivo (Celular o Computadora)</strong> solo tienes que tener acceso a Internet y entrar a   <a style="color: var(--link-hover-color);font-weight: bold"> <?php echo Host::getHOSTNAME()."nombre_de_tu_pagina"?>  </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Puedo modificar mi sitio a mi gusto, agregar otra categoría para la venta, insertar otro método de pago, etc?
                                        </button>
                                    </h2>

                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Claro, <strong>ajustamos el sitio a tu negocio</strong> y hacemos que el proceso de gestión sea más fácil para tí.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Qué garantía de soporte tiene, si necesito agregar una nueva funcionalidad o arreglar algun error en la aplicación?
                                        </button>
                                    </h2>

                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Depués de haber adquirido el producto tendrás un plazo de un mes para probarlo y revizar que todo este bien, <code>( no para agregar otra nueva funcionalidad )</code>, se supone que usted dió toda la descripción necesaria para la realización de la aplicación en la primera etapa de desarrollo. Si algo está mal por nuestra parte, lo solucionaremos sin cosoto adicional.<br> Después de haber cumplido el plazo del mes y desea arreglar/agregar/eliminar algo, deberá pagar un monto de acuerdo con el costo de la implementación requerida.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <section class="contact-section section-padding" id="section_5">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-5 col-12">
                            <form id="pedidosForm" method="post" class="custom-form contact-form" role="form">
                                <h2 class="mb-4 pb-2">Tienes dudas ?</h2>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Nombre" required="">
                                            
                                            <label for="floatingInput">Nombre</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-12"> 
                                        <div class="form-floating">
                                            <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" required="">
                                            
                                            <label for="floatingInput">Email</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="message" name="message" placeholder="Mensaje"></textarea>
                                            
                                            <label for="floatingTextarea">Mensaje</label>
                                        </div>


                                    </div>

                                    <div class="col-lg-4 col-md-4 col-12 d-flex justify-content-center align-items-center">

                                        <div class="form-floating">
                                            <img id="captchaimgr" src="controllers/captcha.php?from=r" alt="CAPTCHA">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-12 d-flex justify-content-center align-items-center" style="height: auto">
                                        =
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-12 d-flex justify-content-center align-items-center" style="margin-bottom: 10px">

                                        <div class="form-floating" >
                                            <input type="text" name="captchar" placeholder="Ingresa el texto de la imagen" class="form-control text-black" style="margin-bottom: 0px !important;" required>
                                            <label for="floatingInput">Ingresa el texto de la imagen</label>
                                        </div>

                                    </div>
                                    <button type="submit" class="form-control">Enviar</button>
                                </div>
                            </form>
                            <p id="reservationMessage" class="mt-2"></p>
                        </div>

                                <div class="col-lg-5 col-12 ms-auto">
                                    <ul class="social-icon mt-lg-5 mt-3 mb-4">
                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-instagram"></a>
                                        </li>

                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-twitter"></a>
                                        </li>

                                        <li class="social-icon-item">
                                            <a href="#" class="social-icon-link bi-whatsapp"></a>
                                        </li>
                                    </ul>
                                    <p class="copyright-text">Design: <a rel="nofollow" href="https://templatemo.com" target="_blank">TemplateMo</a></p>

                                </div>



                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <div class="container">

            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#212529" fill-opacity="1" d="M0,224L34.3,192C68.6,160,137,96,206,90.7C274.3,85,343,139,411,144C480,149,549,107,617,122.7C685.7,139,754,213,823,240C891.4,267,960,245,1029,224C1097.1,203,1166,181,1234,160C1302.9,139,1371,117,1406,106.7L1440,96L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
        </footer>


        <!-- JAVASCRIPT FILES -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery.sticky.js"></script>
        <script src="assets/js/click-scroll.js"></script>
        <script src="assets/js/animated-headline.js"></script>
        <script src="assets/js/modernizr.js"></script>
        <script src="assets/js/customazi.js"></script>
        <script>
            const video = document.getElementById('miVideo');
            video.addEventListener('contextmenu', function (e) {
                e.preventDefault();
            });


            // RESERVAS
            document.getElementById('pedidosForm').addEventListener('submit', (e) => {
                e.preventDefault();

                    const formData = new FormData(e.target);
                    fetch('controllers/guardar_pedido.php', {
                        method: 'POST',
                        dataType: 'json',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {

                            //console.log((data));

                            if (data["status"] === 'success') {
                                document.getElementById('reservationMessage').textContent = "✅ ¡Pedido recibido!";
                                document.getElementById('reservationMessage').style.color = 'green';
                                e.target.reset();
                                // grecaptcha.reset();
                            } else if (data["status"] === 'RECAPTCHA_FAILED') {
                                // console.log("Captcha no verificado por el servidor.");
                                document.getElementById('reservationMessage').textContent = "❌ Captcha no verificado por el servidor.";
                                document.getElementById('reservationMessage').style.color = 'red';
                                recargarCaptcha('r');
                            } else if (data["status"] === 'RATE_LIMITED') {
                                // console.log("Has enviado demasiadas solicitudes. Intenta en un minuto.");
                                document.getElementById('reservationMessage').textContent = "❌ Has enviado demasiadas solicitudes. Intenta en un minuto.";
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

        </script>
    </body>
</html>