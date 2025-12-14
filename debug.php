<?php
session_start();
echo "<h1>🔧 DEBUG PAGE</h1>";

// Probar rutas
$url = '/login';
$method = 'GET';

echo "<h3>Probando ruta: $url</h3>";

// Rutas definidas
$routes = [
    'GET' => [
        '/' => ['DashboardController', 'index'],
        '/login' => ['AuthController', 'login'],
        '/register' => ['AuthController', 'register'],
        '/dashboard' => ['DashboardController', 'index']
    ]
];

if (isset($routes[$method][$url])) {
    echo "✅ Ruta ENCONTRADA en routes array<br>";
    list($controller, $action) = $routes[$method][$url];
    echo "Controlador: $controller<br>";
    echo "Método: $action<br>";
    
    // Verificar si existe el archivo
    $controllerFile = __DIR__ . "/../app/controllers/$controller.php";
    if (file_exists($controllerFile)) {
        echo "✅ Archivo del controlador EXISTE<br>";
        
        // Incluir y probar
        require_once $controllerFile;
        if (class_exists($controller)) {
            echo "✅ Clase $controller EXISTE<br>";
            
            // Crear instancia de prueba
            $config = ['base_url' => 'http://localhost/soporte_sars_mvc/public/'];
            $instance = new $controller($config);
            
            if (method_exists($instance, $action)) {
                echo "✅ Método $action EXISTE<br>";
                echo "<h3 class='text-success'>🎉 ¡TODO CORRECTO!</h3>";
                echo "<p>El sistema debería funcionar. <a href='../login'>Acceder a /login</a></p>";
            } else {
                echo "❌ Método $action NO existe en $controller";
            }
        } else {
            echo "❌ Clase $controller NO existe después de incluir";
        }
    } else {
        echo "❌ Archivo $controllerFile NO existe";
    }
} else {
    echo "❌ Ruta NO encontrada en routes array";
}
?>