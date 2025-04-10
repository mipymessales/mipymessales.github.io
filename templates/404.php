<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>404 Not Found</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
            font-family: 'Poppins', sans-serif;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
            text-align: center;
        }

        .container {
            max-width: 600px;
        }

        h1 {
            font-size: 10rem;
            margin: 0;
            animation: float 2s ease-in-out infinite;
        }

        h2 {
            font-size: 2rem;
            margin: 10px 0;
        }

        p {
            font-size: 1.1rem;
            margin: 20px 0 30px;
        }

        a.button {
            text-decoration: none;
            background: #fff;
            color: #6a11cb;
            padding: 14px 30px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease;
        }

        a.button:hover {
            background: #f1f1f1;
            transform: translateY(-2px);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .bg-bubble {
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            filter: blur(50px);
            animation: bubble 20s infinite ease-in-out;
        }

        .bg-bubble:nth-child(1) {
            top: 10%;
            left: 15%;
        }

        .bg-bubble:nth-child(2) {
            bottom: 10%;
            right: 20%;
            animation-delay: 5s;
        }

        @keyframes bubble {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-20px) scale(1.1);
            }
        }
    </style>
</head>
<body>
<div class="bg-bubble"></div>
<div class="bg-bubble"></div>

<div class="container">
    <h1>404</h1>
    <h2>Oops! Página no encontrada</h2>
    <p>Tal parece que la página que buscas no ha sido encontrada o fue movida.</p>
    <a href="dashboard.php" class="button">Regresar a inicio</a>
</div>
</body>
</html>

