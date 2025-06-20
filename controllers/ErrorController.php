<?php

class ErrorController
{
    public function index() {
        // Aquí podrías cargar datos del modelo y luego mostrar la vista
        defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
        include_once ROOT_DIR."controllers/Host.php";
        header('Location: ' . Host::getHOSTNAME()."templates/404.php");
    }

    public function login() {
        // Ejemplo de otra acción
        defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
        require_once ROOT_DIR.'login.php';
    }
}