<?php
// index.php - COLOCAR EN LA RAIZ del proyecto
session_start();

// ==================== CONFIGURACIÓN ====================
$config = [
    'base_url' => 'http://localhost/soporte_sars_mvc/',
    'db' => [
        'host' => 'localhost',
        'name' => 'soporte_sars_db',
        'user' => 'root',
        'pass' => 'root'
    ]
];

// ==================== OBTENER URL ====================
$url = $_GET['url'] ?? '/';
$url = '/' . trim($url, '/');
if ($url === '//') $url = '/';

$method = $_SERVER['REQUEST_METHOD'];

// ==================== RUTAS ====================
$routes = [
    'GET' => [
        '/' => ['DashboardController', 'index'],
        '/login' => ['AuthController', 'login'],
        '/register' => ['AuthController', 'register'],
        '/logout' => ['AuthController', 'logout'],
        '/dashboard' => ['DashboardController', 'index'],
        '/citas' => ['CitaController', 'index'],
        '/citas/crear' => ['CitaController', 'create'],
        '/productos' => ['ProductoController', 'index'],
        '/tickets' => ['TicketController', 'index'],
        '/tickets/crear' => ['TicketController', 'create'],
        '/cart' => ['CartController', 'index'],
        '/admin' => ['AdminController', 'index'],
        '/admin/usuarios' => ['AdminController', 'usuarios'],
        '/admin/productos' => ['AdminController', 'productos']
    ],
    'POST' => [
        '/login' => ['AuthController', 'authenticate'],
        '/register' => ['AuthController', 'store'],
        '/citas' => ['CitaController', 'store'],
        '/tickets' => ['TicketController', 'store']
    ]
];

// ==================== EJECUTAR RUTA ====================
if (isset($routes[$method][$url])) {
    list($controllerName, $action) = $routes[$method][$url];
    
    // Incluir controlador - RUTA CORREGIDA
    $controllerFile = __DIR__ . "/app/controllers/$controllerName.php";
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        // Crear instancia del controlador
        $controller = new $controllerName($config);
        
        if (method_exists($controller, $action)) {
            // Ejecutar el método del controlador
            $controller->$action();
        } else {
            showError("Método '$action' no encontrado en $controllerName");
        }
    } else {
        showError("Controlador '$controllerName.php' no encontrado");
    }
} else {
    showError("Página no encontrada: $url", 404);
}

// ==================== FUNCIÓN DE ERROR ====================
function showError($mensaje, $codigo = 500) {
    http_response_code($codigo);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error <?php echo $codigo; ?> - Soporte SARS</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-danger text-white">
                            <h4 class="mb-0">Error <?php echo $codigo; ?></h4>
                        </div>
                        <div class="card-body">
                            <h5><?php echo htmlspecialchars($mensaje); ?></h5>
                            <p class="mt-3">
                                <a href="/" class="btn btn-primary">Ir al Inicio</a>
                                <a href="/login" class="btn btn-outline-secondary">Iniciar Sesión</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>