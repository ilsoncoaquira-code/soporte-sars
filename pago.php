<?php
session_start();
if (!isset($_SESSION["usuario_id"])) header("Location: login.php");

$total = $_POST["total"];
?>

<h1>Pago</h1>

<form action="procesar_pago.php" method="POST">
    Total a pagar: <b>S/ <?= $total ?></b><br>
    Número de tarjeta (16 dígitos): 
    <input type="text" name="tarjeta" maxlength="16" required><br>

    <input type="hidden" name="total" value="<?= $total ?>">
    <button>Pagar</button>
</form>
