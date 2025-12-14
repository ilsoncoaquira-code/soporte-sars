<?php
// app/models/Cita.php

class Cita {
    private $db;
    private $table = 'citas';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todas las citas
    public function all() {
        $query = "SELECT c.*, u.nombre as cliente, s.nombre as servicio 
                 FROM {$this->table} c 
                 JOIN usuarios u ON c.usuario_id = u.id 
                 JOIN servicios s ON c.servicio_id = s.id 
                 ORDER BY c.fecha DESC, c.hora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar cita por ID
    public function find($id) {
        $query = "SELECT c.*, u.nombre as cliente, s.nombre as servicio, s.precio 
                 FROM {$this->table} c 
                 JOIN usuarios u ON c.usuario_id = u.id 
                 JOIN servicios s ON c.servicio_id = s.id 
                 WHERE c.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener citas de un usuario
    public function findByUser($userId) {
        $query = "SELECT c.*, s.nombre as servicio, s.precio 
                 FROM {$this->table} c 
                 JOIN servicios s ON c.servicio_id = s.id 
                 WHERE c.usuario_id = :user_id 
                 ORDER BY c.fecha DESC, c.hora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener citas pendientes
    public function getPendientes() {
        $query = "SELECT c.*, u.nombre as cliente, s.nombre as servicio 
                 FROM {$this->table} c 
                 JOIN usuarios u ON c.usuario_id = u.id 
                 JOIN servicios s ON c.servicio_id = s.id 
                 WHERE c.estado = 'pendiente' 
                 ORDER BY c.fecha, c.hora";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear nueva cita
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

    // Actualizar cita
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

    // Eliminar cita
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Verificar disponibilidad de horario
    public function checkDisponibilidad($fecha, $hora) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                 WHERE fecha = :fecha AND hora = :hora AND estado != 'cancelada'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] == 0;
    }

    // Obtener citas por fecha
    public function getByDate($fecha) {
        $query = "SELECT c.*, u.nombre as cliente, s.nombre as servicio 
                 FROM {$this->table} c 
                 JOIN usuarios u ON c.usuario_id = u.id 
                 JOIN servicios s ON c.servicio_id = s.id 
                 WHERE c.fecha = :fecha 
                 ORDER BY c.hora";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Cambiar estado de cita
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE {$this->table} SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado);
        return $stmt->execute();
    }

    // Contar citas por estado
    public function countByEstado($estado) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE estado = :estado";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}
?>