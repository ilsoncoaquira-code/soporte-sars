<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Sistema - Soporte Sars</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --text-color: #333;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 16px;
            opacity: 0.8;
        }
        
        .status-summary {
            display: flex;
            justify-content: center;
            padding: 20px;
            background-color: var(--light-color);
            border-bottom: 1px solid #ddd;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .status-operational {
            background-color: var(--success-color);
            color: white;
        }
        
        .status-degraded {
            background-color: var(--warning-color);
            color: white;
        }
        
        .status-down {
            background-color: var(--danger-color);
            color: white;
        }
        
        .status-icon {
            margin-right: 8px;
            font-size: 16px;
        }
        
        .components {
            padding: 25px;
        }
        
        .component {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: var(--border-radius);
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--secondary-color);
            transition: var(--transition);
        }
        
        .component:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .component-info {
            flex: 1;
        }
        
        .component-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .component-status {
            font-size: 14px;
            color: #666;
        }
        
        .component-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .indicator-operational {
            background-color: var(--success-color);
        }
        
        .indicator-degraded {
            background-color: var(--warning-color);
        }
        
        .indicator-down {
            background-color: var(--danger-color);
        }
        
        .last-updated {
            text-align: center;
            padding: 15px;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        
        .history-link {
            text-align: center;
            padding: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--secondary-color);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: bold;
            transition: var(--transition);
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        @media (max-width: 600px) {
            .component {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .component-status {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Estado del Sistema</h1>
            <div class="subtitle">Soporte Sars</div>
        </header>
        
        <div class="status-summary">
            <div class="status-badge status-operational">
                <span class="status-icon">✓</span>
                Sistema Operativo
            </div>
        </div>
        
        <div class="components">
            <div class="component">
                <div class="component-info">
                    <div class="component-name">Servicio Web</div>
                    <div class="component-status">Servicio principal de la aplicación</div>
                </div>
                <div class="component-status">
                    <span class="component-indicator indicator-operational"></span>
                    <?= $data["service"] ?>
                </div>
            </div>
            
            <div class="component">
                <div class="component-info">
                    <div class="component-name">Base de Datos</div>
                    <div class="component-status">Sistema de almacenamiento de datos</div>
                </div>
                <div class="component-status">
                    <span class="component-indicator indicator-operational"></span>
                    <?= $data["database"] ?>
                </div>
            </div>
            
            <div class="component">
                <div class="component-info">
                    <div class="component-name">API Externa</div>
                    <div class="component-status">Servicios de terceros</div>
                </div>
                <div class="component-status">
                    <span class="component-indicator indicator-degraded"></span>
                    Rendimiento Degradado
                </div>
            </div>
        </div>
        
        <div class="history-link">
            <a href="#" class="btn">Ver Historial de Estado</a>
        </div>
        
        <div class="last-updated">
            Última actualización: <?= date("d/m/Y H:i:s") ?>
        </div>
    </div>
</body>
</html>