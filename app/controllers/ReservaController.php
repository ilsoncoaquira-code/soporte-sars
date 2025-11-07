<?php
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController {
    private $model;

    public function __construct() {
        $this->model = new Reserva();
    }

    public function listar() {
        $reservas = $this->model->obtenerTodas();
        include __DIR__ . '/../views/reservas/listar.php';
    }

    public function crear($datos) {
        $this->model->crear($datos);
        header("Location: /reservas");
    }
}
?>
