-- soporte_sars.sql

CREATE DATABASE IF NOT EXISTS soporte_sars;
USE soporte_sars;

-- Tabla citas
CREATE TABLE citas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    fecha DATETIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'completada') DEFAULT 'pendiente',
    notas TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla productos
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    categoria VARCHAR(50),
    imagen_url VARCHAR(255),
    activo BOOLEAN DEFAULT true,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos de prueba
INSERT INTO citas (usuario_id, servicio, fecha, estado) VALUES
(1, 'Reparación de laptop', '2025-12-10 10:00:00', 'confirmada'),
(1, 'Instalación de software', '2025-12-12 14:30:00', 'pendiente');

INSERT INTO productos (nombre, descripcion, precio, stock, categoria) VALUES
('Mouse Gaming RGB', 'Mouse inalámbrico con retroiluminación RGB', 59.99, 25, 'Periféricos'),
('Teclado Mecánico', 'Teclado mecánico 60% con switches azules', 89.99, 15, 'Periféricos'),
('Monitor 24" Full HD', 'Monitor LED 24 pulgadas 144Hz', 199.99, 8, 'Monitores');