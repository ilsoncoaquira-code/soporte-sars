<?php
// setup-database.php - VERSION CORREGIDA
echo "🔧 Configurando base de datos Soporte SARS...\n\n";

// Configuración de MySQL para XAMPP
$host = 'localhost';
$user = 'root';
$pass = 'root';  // Primero prueba sin contraseña
$dbname = 'soporte_sars';

// Leer el archivo SQL
$sql_file = 'soporte_sars.sql';
if (!file_exists($sql_file)) {
    die("❌ No se encuentra el archivo: $sql_file\n");
}

$sql_content = file_get_contents($sql_file);
if (!$sql_content) {
    die("❌ No se pudo leer $sql_file\n");
}

// Intentar conectar (primero sin contraseña, luego con 'root')
try {
    echo "🔌 Intentando conectar a MySQL...\n";
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado a MySQL\n";
} catch (PDOException $e) {
    // Si falla, intentar con contraseña 'root'
    try {
        echo "⚠️  Falló sin contraseña. Intentando con contraseña 'root'...\n";
        $pass = 'root';
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✅ Conectado a MySQL (con contraseña)\n";
    } catch (PDOException $e2) {
        die("❌ No se pudo conectar a MySQL. Error: " . $e2->getMessage() . "\n" .
            "📋 Asegúrate de:\n" .
            "1. Tener XAMPP con MySQL iniciado\n" .
            "2. Probar contraseñas: vacía o 'root'\n" .
            "3. Verificar que MySQL esté corriendo en el puerto 3306\n");
    }
}

try {
    // Crear base de datos
    echo "📁 Creando base de datos '$dbname'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    echo "✅ Base de datos lista\n";
    
    // Separar y ejecutar comandos SQL
    echo "📝 Ejecutando comandos SQL...\n";
    
    // Eliminar comentarios y líneas vacías
    $lines = explode("\n", $sql_content);
    $clean_sql = '';
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (!empty($trimmed) && substr($trimmed, 0, 2) !== '--' && substr($trimmed, 0, 2) !== '/*') {
            $clean_sql .= $trimmed . "\n";
        }
    }
    
    // Separar por punto y coma
    $commands = explode(';', $clean_sql);
    $success_count = 0;
    $error_count = 0;
    
    foreach ($commands as $command) {
        $command = trim($command);
        if (!empty($command)) {
            // Mostrar solo el primer fragmento del comando
            $display = substr($command, 0, 50);
            if (strlen($command) > 50) {
                $display .= '...';
            }
            
            try {
                $pdo->exec($command);
                echo "   ✅ $display\n";
                $success_count++;
            } catch (PDOException $e) {
                // Ignorar errores de "tabla ya existe"
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "   ⚠️  Advertencia en: $display\n";
                    echo "      " . $e->getMessage() . "\n";
                    $error_count++;
                } else {
                    echo "   ℹ️  $display (ya existe)\n";
                }
            }
        }
    }
    
    echo "\n📊 Resultado: $success_count comandos ejecutados, $error_count advertencias\n";
    
    // Verificar tablas creadas
    echo "\n📋 Verificando tablas...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "✅ Tablas creadas:\n";
        foreach ($tables as $table) {
            // Contar registros en cada tabla
            $count_stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "   • $table ($count registros)\n";
        }
    } else {
        echo "⚠️  No se encontraron tablas\n";
    }
    
    // Probar conexión con la API
    echo "\n🧪 Probando configuración para API...\n";
    
    // Verificar que config/database.php existe
    if (file_exists('config/database.php')) {
        require_once 'config/database.php';
        try {
            $test_pdo = getPDO();
            $test_stmt = $test_pdo->query("SELECT 1 as test");
            $result = $test_stmt->fetch();
            if ($result['test'] == 1) {
                echo "✅ Configuración de API correcta\n";
            }
        } catch (Exception $e) {
            echo "⚠️  API config: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 ¡Base de datos configurada exitosamente!\n";
    
} catch (Exception $e) {
    die("❌ Error crítico: " . $e->getMessage() . "\n");
}
?>