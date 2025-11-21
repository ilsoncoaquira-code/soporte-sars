<?php
session_start();
if (!isset($_SESSION["usuario_id"])) header("Location: login.php");
include("db_connection.php");

$usuario = $_SESSION["usuario_id"];

$sql = "SELECT carrito.id, productos.nombre, productos.precio, carrito.cantidad
        FROM carrito
        JOIN productos ON carrito.producto_id = productos.id
        WHERE carrito.usuario_id = $usuario";

$res = $conn->query($sql);

echo "<h1>Carrito</h1>";

$total = 0;

while ($c = $res->fetch_assoc()) {
    $subtotal = $c["precio"] * $c["cantidad"];
    $total += $subtotal;

    echo "
    <div>
        {$c['nombre']} - S/ {$c['precio']} x {$c['cantidad']}  
        = <b>S/ $subtotal</b><br><hr>
    </div>
    ";
}

echo "<h2>Total: S/ $total</h2>";

echo "
<form action='pago.php' method='POST'>
    <input type='hidden' name='total' value='$total'>
    <button>Proceder al Pago</button>
</form>";
?>

<a href="compra.php">Seguir comprando</a>
