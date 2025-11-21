<?php
session_start();
include("db_connection.php");

$usuario = $_SESSION["usuario_id"];
$total = $_POST["total"];
$tarjeta = $_POST["tarjeta"];

if (strlen($tarjeta) != 16) die("Tarjeta inválida");

// Registrar compra
$conn->query("INSERT INTO compras (usuario_id, total, estado)
              VALUES ($usuario, $total, 'Pagado')");

// Descontar stock
$sql = "SELECT * FROM carrito WHERE usuario_id = $usuario";
$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
    $conn->query("UPDATE productos
                  SET cantidad = cantidad - {$row['cantidad']}
                  WHERE id = {$row['producto_id']}");
}

// Vaciar carrito
$conn->query("DELETE FROM carrito WHERE usuario_id = $usuario");

echo "<h1>Pago exitoso</h1>";
echo "Has pagado S/ $total<br>";
echo "<a href='compra.php'>Volver a la tienda</a>";
?>
