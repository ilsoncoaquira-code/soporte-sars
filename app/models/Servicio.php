<?php
// app/models/Servicio.php

class Servicio {
    private $db;
    private $table = 'servicios';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los servicios
    public function all() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener servicios activos
    public function getActivos() {
        $query = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar servicio por ID
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Crear nuevo servicio
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute() ? $this->db->lastInsertId() : false;
    }

    // Actualizar servicio
    public function update($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(', ', $set);
        
        $query = "UPDATE {$this->table} SET {$set} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
    }

    // Eliminar servicio (cambio de estado)
    public function delete($id) {
        $query = "UPDATE {$this->table} SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Activar servicio
    public function activate($id) {
        $query = "UPDATE {$this->table} SET activo = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Contar servicios activos
    public function countActivos() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE activo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Obtener servicio más popular
    public function getMasPopular() {
        $query = "SELECT s.*, COUNT(c.id) as total_citas 
                 FROM servicios s 
                 LEFT JOIN citas c ON s.id = c.servicio_id 
                 WHERE s.activo = 1 
                 GROUP BY s.id 
                 ORDER BY total_citas DESC 
                 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener servicios con precio en rango
    public function getByPrecioRange($min, $max) {
        $query = "SELECT * FROM {$this->table} WHERE activo = 1 AND precio BETWEEN :min AND :max ORDER BY precio";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':min', $min);
        $stmt->bindParam(':max', $max);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>