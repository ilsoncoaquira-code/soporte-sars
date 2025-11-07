<?php
require_once __DIR__ . '/../config/database.php';

class Soporte {
    private $conn;
    private $table = "tickets";

    public $id;
    public $cliente;
    public $asunto;
    public $descripcion;
    public $estado;
    public $fecha_creacion;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
