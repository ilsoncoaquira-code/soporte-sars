<?php
// index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Soporte Sars - API</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .card h3 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .endpoint {
            background: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #667eea;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5a67d8;
        }
        .swagger-link {
            background: #85ea2d;
            color: #333;
        }
        .swagger-link:hover {
            background: #7cd827;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚀 Sistema Integral de Reservas, Ventas y Soporte Técnico</h1>
        <h2>Soporte Sars - API REST</h2>
        <p>Documentación y pruebas de la API interna</p>
    </div>
    
    <div class="container">
        <div class="card">
            <h3>📖 Documentación Swagger</h3>
            <p>Documentación interactiva completa de todos los endpoints de la API.</p>
            <a href="swagger-ui/" class="btn swagger-link" target="_blank">Abrir Swagger UI</a>
        </div>
        
        <div class="card">
            <h3>📅 API de Citas</h3>
            <div class="endpoint">GET /api/citas</div>
            <div class="endpoint">POST /api/citas</div>
            <div class="endpoint">GET /api/citas?id={id}</div>
            <div class="endpoint">PUT /api/citas?id={id}</div>
            <div class="endpoint">DELETE /api/citas?id={id}</div>
            <a href="api/citas" class="btn">Probar Endpoints</a>
        </div>
        
        <div class="card">
            <h3>🛒 API de Productos</h3>
            <div class="endpoint">GET /api/productos</div>
            <div class="endpoint">POST /api/productos</div>
            <div class="endpoint">GET /api/productos?id={id}</div>
            <div class="endpoint">PUT /api/productos?id={id}</div>
            <div class="endpoint">DELETE /api/productos?id={id}</div>
            <a href="api/productos" class="btn">Probar Endpoints</a>
        </div>
        
        <div class="card">
            <h3>⚙️ Repositorios Genéricos</h3>
            <p>Implementación de patrones de reutilización:</p>
            <ul>
                <li>GenericRepository.php (Clase base)</li>
                <li>CitaRepository.php (Especialización)</li>
                <li>ProductoRepository.php (Especialización)</li>
            </ul>
            <a href="#" class="btn" onclick="alert('Revisa el código fuente en /repositories/');">Ver Código</a>
        </div>
        
        <div class="card">
            <h3>🧪 Herramientas de Prueba</h3>
            <p>Prueba la API con:</p>
            <ul>
                <li>Swagger UI (Interactivo)</li>
                <li>Postman o Insomnia</li>
                <li>curl desde terminal</li>
            </ul>
            <a href="https://www.postman.com/downloads/" class="btn" target="_blank">Descargar Postman</a>
        </div>
        
        <div class="card">
            <h3>📊 Estado del Sistema</h3>
            <p>Información del proyecto:</p>
            <ul>
                <li><strong>Base de datos:</strong> MySQL</li>
                <li><strong>Lenguaje:</strong> PHP 7.4+</li>
                <li><strong>Documentación:</strong> OpenAPI 3.0</li>
                <li><strong>Práctica:</strong> 12 - APIs Internas</li>
            </ul>
        </div>
    </div>
    
    <footer style="margin-top: 40px; text-align: center; color: #666;">
        <p>Equipo Codecrafters - Ingeniería de Software I - © 2025 Soporte Sars</p>
    </footer>
</body>
</html>