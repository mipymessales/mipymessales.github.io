<?php
// Obtener la URL limpia

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$segments = explode('/', $url);

$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'SaguaenlineaController';
$method = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// Cargar controlador
$controllerPath = "controllers/$controllerName.php";
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        $controllerPath = "controllers/ErrorController.php";
        $method ='index';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerName = 'ErrorController';
            $controller = new $controllerName();
            call_user_func_array([$controller, $method], $params);
        }
    }
} else {
    $controllerPath = "controllers/ErrorController.php";
    $method ='index';
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        $controllerName = 'ErrorController';
        $controller = new $controllerName();
        call_user_func_array([$controller, $method], $params);
    }
}


