<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user'])) {
    // Borra todas las variables de sesión
    session_unset();

    // Destruye la sesión
    session_destroy();

    // Redirige al login
    header("Location: login");
    exit();
} else {
    // Si no hay sesión iniciada, redirige igual (o muestra un mensaje)
    header("Location: login");
    exit();
}
