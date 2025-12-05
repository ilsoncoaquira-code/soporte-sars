-- soporte_sars_corregido.sql
CREATE DATABASE IF NOT EXISTS soporte_sars CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE soporte_sars;

-- Eliminar tablas si existen (para pruebas)
DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS productos;

-- Tabla citas
CREATE TABLE citas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    fecha DATETIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'completada') DEFAULT 'pendiente',
    notas TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla productos
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    categoria VARCHAR(50),
    imagen_url VARCHAR(255),
    activo TINYINT(1) DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de prueba para citas
INSERT INTO citas (usuario_id, servicio, fecha, estado, notas) VALUES
(1, 'Reparación de laptop', '2025-12-10 10:00:00', 'confirmada', 'Pantalla rota, necesita reemplazo'),
(2, 'Instalación de software', '2025-12-12 14:30:00', 'pendiente', 'Instalar Windows 11 y Office'),
(1, 'Limpieza interna', '2025-12-15 11:00:00', 'pendiente', 'Limpieza de ventiladores y cambio de pasta térmica');

-- Datos de prueba para productos (CORREGIDO: sin 'categoria' en la lista de columnas)
INSERT INTO productos (nombre, descripcion, precio, stock, categoria) VALUES
('Mouse Gaming RGB', 'Mouse inalámbrico con retroiluminación RGB, 6 botones programables', 59.99, 25, 'Periféricos'),
('Teclado Mecánico Redragon', 'Teclado mecánico 60% con switches azules, retroiluminación RGB', 89.99, 15, 'Periféricos'),
('Monitor 24" Full HD', 'Monitor LED 24 pulgadas 144Hz, 1ms, FreeSync', 199.99, 8, 'Monitores'),
('Disco SSD 500GB', 'Unidad de estado sólido, lectura 550MB/s, escritura 500MB/s', 49.99, 30, 'Almacenamiento'),
('Memoria RAM 8GB DDR4', 'Módulo de memoria 8GB DDR4 3200MHz', 34.99, 50, 'Memoria');

-- Verificar creación
SELECT '✅ Base de datos y tablas creadas exitosamente' as Mensaje;
SELECT COUNT(*) as Total_Citas FROM citas;
SELECT COUNT(*) as Total_Productos FROM productos;