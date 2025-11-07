<?php include __DIR__ . '/../templates/header.php'; ?>
<h2>Registro de usuario</h2>

<form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Contraseña:</label>
    <input type="password" name="password" required>

    <button type="submit">Registrarse</button>
</form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
