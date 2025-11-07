<?php
require_once __DIR__ . '/../models/Soporte.php';

class SoporteController {
    public function index() {
        $soporte = new Soporte();
        $resultado = $soporte->listar();
        include __DIR__ . '/../views/soporte/tickets.php';
    }
}
?>
