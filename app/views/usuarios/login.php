<?php include __DIR__ . '/../templates/header.php'; ?>
<h2>Iniciar sesión</h2>

<form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Contraseña:</label>
    <input type="password" name="password" required>

    <button type="submit">Ingresar</button>
</form>

<p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
<?php include __DIR__ . '/../templates/footer.php'; ?>
