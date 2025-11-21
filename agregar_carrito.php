<?php
session_start();
include("db_connection.php");

$usuario = $_SESSION["usuario_id"];
$producto = $_POST["producto_id"];
$cantidad = $_POST["cantidad"];

$sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad)
        VALUES ($usuario, $producto, $cantidad)";

$conn->query($sql);

header("Location: carrito.php");
?>
