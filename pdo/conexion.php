<?php
$usuario = "if0_38726532";
$contraseña = "ZauvXbCUiigd";
$nombreBaseDeDatos = "if0_38726532_restaurant_uno";
$rutaServidor = "sql310.infinityfree.com";
$puerto = "3306";

/*$usuario = "root";
$contraseña = "123ok";
$nombreBaseDeDatos = "if0_38726532_restaurant_uno";
$rutaServidor = "localhost";
$puerto = "3306";*/
try {
    global $base_de_datos;
    $base_de_datos = new PDO("mysql:host=$rutaServidor;port=$puerto;dbname=$nombreBaseDeDatos", $usuario, $contraseña);
    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $base_de_datos->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    //Descomentar las lineas en php.ini de xammp
    //extension=pdo_pgsql
    //extension=pgsql
    echo "Ocurrió un error con la base de datos: " . $e->getMessage();
}