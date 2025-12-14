<?php include __DIR__ . '/../templates/header.php'; ?>
<h2>Editar reserva</h2>

<form method="POST" action="">
    <label>Nombre del cliente:</label>
    <input type="text" name="nombre" value="Cliente Ejemplo">

    <label>Servicio:</label>
    <input type="text" name="servicio" value="Mantenimiento PC">

    <label>Fecha:</label>
    <input type="date" name="fecha" value="2025-11-03">

    <button type="submit">Actualizar</button>
</form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
