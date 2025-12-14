<?php include __DIR__ . '/../templates/header.php'; ?>
<h2>Crear nueva reserva</h2>

<form method="POST" action="">
    <label>Nombre del cliente:</label>
    <input type="text" name="nombre" required>

    <label>Servicio:</label>
    <input type="text" name="servicio" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <button type="submit">Guardar</button>
</form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
