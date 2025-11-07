<?php
require_once __DIR__ . '/../config/database.php';

class Venta {
    private $conn;
    private $table = "ventas";

    public $id;
    public $producto;
    public $cantidad;
    public $precio;
    public $fecha;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
