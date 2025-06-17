<?php
// === CONFIGURACIÓN ===
$nombreBase = $_GET['nombre'] ?? 'RestaurantX'; // Ej: 'RestaurantX'
$nombreLower = strtolower($nombreBase);         // Ej: 'restaurantx'
$className = $nombreBase . 'Controller';        // Ej: 'RestaurantXController'

// === ARCHIVOS ===
$controllerFile = __DIR__ . "/{$className}.php";
$plantillaVista = __DIR__ . '/restaurant.php';
$vistaDestino   = __DIR__ . "/{$nombreLower}.php";

// === CREAR CONTROLADOR ===
$contenidoControlador = <<<PHP
<?php
class $className {
    public function index() {
        // Aquí podrías cargar datos del modelo y luego mostrar la vista
        defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
        require_once ROOT_DIR . '$nombreLower.php';
    }

    public function login() {
        // Ejemplo de otra acción
        defined('ROOT_DIR') || define('ROOT_DIR', dirname(__FILE__, 2) . '/');
        require_once ROOT_DIR . 'login$nombreLower.php';
    }
}
PHP;

file_put_contents($controllerFile, $contenidoControlador);
echo "✅ Controlador creado: $controllerFile\n";

// === COPIAR Y MODIFICAR LA VISTA ===
if (file_exists($plantillaVista)) {
    $contenidoVista = file_get_contents($plantillaVista);

    // Inyectar variable al principio
    $variableExtra = "<?php \$id = 2; ?>\n\n";
    $nuevoContenido = $variableExtra . $contenidoVista;

    file_put_contents($vistaDestino, $nuevoContenido);
    echo "✅ Vista creada con variable extra: $vistaDestino\n";
} else {
    echo "❌ Plantilla no encontrada: $plantillaVista\n";
}



