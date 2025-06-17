<?php

class CartaController
{
    public function index() {
        // Aquí podrías cargar datos del modelo y luego mostrar la vista
        defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
        require_once ROOT_DIR . 'templates/cartacliente.php';
    }
}