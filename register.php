<?php
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES ('$nombre','$correo','$password')";
    $conn->query($sql);

    header("Location: login.php");
}
?>

<form method="POST">
    Nombre: <input type="text" name="nombre" required><br>
    Correo: <input type="email" name="correo" required><br>
    Contraseña: <input type="password" name="password" required><br>
    <button type="submit">Registrar</button>
</form>
