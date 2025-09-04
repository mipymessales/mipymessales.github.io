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
global $availableIds;
$availableIds=[1,2];

global $availableCombos;
$availableCombos=[2];
/*Push*/
define('VAPID_PUBLIC_KEY', 'BPCWRVWc3IqGGoFJno3BhYn5e9-YSObP6RKw5wD3V31RWqBl7RDIKbu7wS_PDtHJGFVy50c1UskStJhA7MWy29I');
define('VAPID_PRIVATE_KEY', 'MhckwSTucZXQM55GYQ3MZuveAPDFLnq9auhzboelz8w');
define('VAPID_SUBJECT', 'mailto:mipymessalesmanager@gmail.com');
define('TU_BOT_TOKEN','8357574899:AAHbMjfmuKuM5EC2z7XvIeTJgxPiodHfXXE');
define('TU_CHAT_ID',1333036016);


try {
    global $base_de_datos;
    $base_de_datos = new PDO("mysql:host=$rutaServidor;port=$puerto;dbname=$nombreBaseDeDatos;charset=utf8mb4", $usuario, $contraseña);
    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $base_de_datos->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    //Descomentar las lineas en php.ini de xammp
    //extension=pdo_pgsql
    //extension=pgsql
    echo "Ocurrió un error con la base de datos: " . $e->getMessage();
}