<?php

require_once "../app/controllers/SystemController.php";

// Crear el controlador
$controller = new SystemController();

// Llamar al método principal (acción)
$controller->index();

?>