<?php
require_once __DIR__ . '/../config/database.php';

class Reserva {
    private $conn;
    private $table = "reservas";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function obtenerTodas() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($data) {
        $query = "INSERT INTO " . $this->table . " (nombre_cliente, fecha, servicio) VALUES (:nombre_cliente, :fecha, :servicio)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_cliente", $data['nombre_cliente']);
        $stmt->bindParam(":fecha", $data['fecha']);
        $stmt->bindParam(":servicio", $data['servicio']);
        return $stmt->execute();
    }
}
?>
