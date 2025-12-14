<?php
require_once __DIR__ . '/../models/Venta.php';

class VentaController {
    public function index() {
        $venta = new Venta();
        $resultado = $venta->listar();
        include __DIR__ . '/../views/ventas/catalogo.php';
    }
}
?>
