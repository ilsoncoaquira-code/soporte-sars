<?php
// app/core/Router.php

class Router {
    private $routes = [];
    private $config;
    private $middlewareStack = [];

    public function __construct($config) {
        $this->config = $config;
    }

    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    private function addRoute($method, $path, $handler, $middleware) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover base path si existe
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath != '/') {
            $requestUri = str_replace($basePath, '', $requestUri);
        }
        
        $requestUri = rtrim($requestUri, '/') ?: '/';

        foreach ($this->routes as $route) {
            // Convertir patrón con parámetros
            $pattern = $this->convertToRegex($route['path']);
            
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remover el match completo
                
                // Ejecutar middleware si existe
                if (!$this->runMiddleware($route['middleware'])) {
                    return;
                }
                
                // Extraer controlador y método
                list($controllerName, $methodName) = explode('@', $route['handler']);
                
                // Incluir y ejecutar controlador
                $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    
                    // Pasar configuración al controlador
                    $controller = new $controllerName($this->config);
                    
                    // Llamar al método con parámetros
                    call_user_func_array([$controller, $methodName], $matches);
                    return;
                } else {
                    $this->showError("Controlador no encontrado: {$controllerName}");
                    return;
                }
            }
        }
        
        // No se encontró la ruta
        $this->showError("Ruta no encontrada: {$requestUri}");
    }

    private function convertToRegex($path) {
        // Reemplazar :param por regex
        $pattern = preg_replace('/:([a-zA-Z0-9_]+)/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function runMiddleware($middlewares) {
        foreach ($middlewares as $middleware) {
            switch ($middleware) {
                case 'auth':
                    if (!isset($_SESSION['user_id'])) {
                        $this->redirect('login');
                        return false;
                    }
                    break;
                    
                case 'admin':
                    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                        http_response_code(403);
                        $this->showError("Acceso denegado. Se requiere rol de administrador.");
                        return false;
                    }
                    break;
                    
                case 'guest':
                    if (isset($_SESSION['user_id'])) {
                        $this->redirect('dashboard');
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    private function redirect($path) {
        header('Location: ' . $this->config['base_url'] . ltrim($path, '/'));
        exit;
    }

    private function showError($message) {
        http_response_code(404);
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error - Soporte SARS</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <style>
                body { padding: 50px; background-color: #f8f9fa; }
                .error-container { max-width: 600px; margin: 0 auto; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <div class='card shadow'>
                    <div class='card-header bg-danger text-white'>
                        <h4 class='mb-0'><i class='fas fa-exclamation-triangle'></i> Error</h4>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title'>$message</h5>
                        <p class='card-text'>La página solicitada no pudo ser encontrada.</p>
                        <div class='mt-4'>
                            <a href='{$this->config['base_url']}' class='btn btn-primary'>
                                <i class='fas fa-home'></i> Ir al inicio
                            </a>
                            <a href='{$this->config['base_url']}login' class='btn btn-outline-secondary'>
                                <i class='fas fa-sign-in-alt'></i> Iniciar sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>