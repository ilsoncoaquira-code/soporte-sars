<?php include __DIR__ . '/../templates/header.php'; ?>
<h2>Nuevo ticket de soporte</h2>

<form method="POST" action="">
    <label>Cliente:</label>
    <input type="text" name="cliente" required>

    <label>Asunto:</label>
    <input type="text" name="asunto" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required></textarea>

    <button type="submit">Enviar ticket</button>
</form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
