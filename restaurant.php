<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restaurante Delicias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            background-image:image('images/blog-image-5.jpg');
            background-size: cover;
            background-position: center;
            height: 60vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            animation: fadeIn 2s ease-in-out;
        }
        .category-title {
            margin-top: 2rem;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 1s forwards;
        }
        .carousel-item img {
            object-fit: cover;
            height: 300px;
            animation: zoomIn 1s ease-in;
        }
        .img-fluid {
            animation: fadeInUp 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(1.1);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<header class="hero">
    <h1>Bienvenidos a Restaurante Delicias</h1>
</header>

<main class="container my-5">
    <section>
        <div class="category-title">Entrantes</div>
        <div id="entrantesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://source.unsplash.com/800x300/?starter,food" class="d-block w-100" alt="Entrante 1">
                </div>
                <div class="carousel-item">
                    <img src="https://source.unsplash.com/800x300/?tapas,food" class="d-block w-100" alt="Entrante 2">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#entrantesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#entrantesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </section>

    <section>
        <div class="category-title">Platos Principales</div>
        <div class="row g-3">
            <div class="col-md-4">
                <img src="https://source.unsplash.com/400x300/?main-course,food" class="img-fluid rounded" alt="Plato Principal 1">
            </div>
            <div class="col-md-4">
                <img src="https://source.unsplash.com/400x300/?meat,food" class="img-fluid rounded" alt="Plato Principal 2">
            </div>
            <div class="col-md-4">
                <img src="https://source.unsplash.com/400x300/?pasta,food" class="img-fluid rounded" alt="Plato Principal 3">
            </div>
        </div>
    </section>

    <section>
        <div class="category-title">Postres</div>
        <div id="postresCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://source.unsplash.com/800x300/?dessert,cake" class="d-block w-100" alt="Postre 1">
                </div>
                <div class="carousel-item">
                    <img src="https://source.unsplash.com/800x300/?ice-cream,dessert" class="d-block w-100" alt="Postre 2">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#postresCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#postresCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </section>
</main>

<footer class="text-center py-4 bg-light">
    <p class="mb-0">&copy; 2025 Restaurante Delicias. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


