<?php
$conn = new mysqli("localhost", "root", "root", "soporte");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
