<?php
session_start();
if (!isset($_SESSION["usuario_id"])) header("Location: login.php");
include("db_connection.php");
?>

<h1>Productos</h1>

<?php
$res = $conn->query("SELECT * FROM productos");
while ($p = $res->fetch_assoc()) {
    echo "
    <div>
        <img src='{$p['imagen_url']}' width='150'><br>
        <b>{$p['nombre']}</b><br>
        {$p['descripcion']}<br>
        S/ {$p['precio']}<br>

        <form action='agregar_carrito.php' method='POST'>
            <input type='hidden' name='producto_id' value='{$p['id']}'>
            Cantidad: <input type='number' name='cantidad' value='1' min='1' max='{$p['cantidad']}' required>
            <button>Agregar al Carrito</button>
        </form>
    </div><hr>
    ";
}
?>

<a href="carrito.php">Ver Carrito</a>
<a href="logout.php">Cerrar sesión</a>
