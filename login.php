<?php
session_start();
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["usuario_id"] = $user["id"];
        header("Location: compra.php");
    } else {
        echo "Credenciales incorrectas";
    }
}
?>

<form method="POST">
    Correo: <input type="email" name="correo" required><br>
    Contraseña: <input type="password" name="password" required><br>
    <button type="submit">Ingresar</button>
</form>
