<?php
class SaguaenlineaController {
    public function index() {
        // Aquí podrías cargar datos del modelo y luego mostrar la vista
        defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
        require_once ROOT_DIR . 'saguaenlinea.php';
    }

    public function login() {
        // Ejemplo de otra acción
        defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
        require_once ROOT_DIR . 'login.php';
    }
}