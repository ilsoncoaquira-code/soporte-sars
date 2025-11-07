CREATE DATABASE IF NOT EXISTS soporte_sars;
USE soporte_sars;

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cliente VARCHAR(100),
    fecha DATE,
    servicio VARCHAR(100)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100),
    password VARCHAR(255)
);
