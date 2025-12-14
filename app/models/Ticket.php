<?php
// app/models/Ticket.php

class Ticket {
    private $db;
    private $table = 'tickets';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los tickets
    public function all() {
        $query = "SELECT t.*, u.nombre as cliente 
                 FROM {$this->table} t 
                 JOIN usuarios u ON t.usuario_id = u.id 
                 ORDER BY t.fecha_creacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar ticket por ID
    public function find($id) {
        $query = "SELECT t.*, u.nombre as cliente 
                 FROM {$this->table} t 
                 JOIN usuarios u ON t.usuario_id = u.id 
                 WHERE t.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener tickets de un usuario
    public function findByUser($userId) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE usuario_id = :user_id 
                 ORDER BY fecha_creacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener tickets abiertos
    public function getAbiertos() {
        $query = "SELECT t.*, u.nombre as cliente 
                 FROM {$this->table} t 
                 JOIN usuarios u ON t.usuario_id = u.id 
                 WHERE t.estado = 'abierto' 
                 ORDER BY t.prioridad DESC, t.fecha_creacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear nuevo ticket
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

    // Actualizar ticket
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

    // Eliminar ticket
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Asignar técnico a ticket
    public function asignarTecnico($ticketId, $tecnicoId) {
        $query = "UPDATE {$this->table} SET tecnico_asignado = :tecnico_id, estado = 'en_proceso' WHERE id = :ticket_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ticket_id', $ticketId, PDO::PARAM_INT);
        $stmt->bindParam(':tecnico_id', $tecnicoId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Cerrar ticket
    public function cerrar($ticketId) {
        $query = "UPDATE {$this->table} SET estado = 'cerrado', fecha_cierre = NOW() WHERE id = :ticket_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ticket_id', $ticketId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener mensajes de un ticket
    public function getMensajes($ticketId) {
        $query = "SELECT m.*, u.nombre as usuario_nombre 
                 FROM mensajes_tickets m 
                 JOIN usuarios u ON m.usuario_id = u.id 
                 WHERE m.ticket_id = :ticket_id 
                 ORDER BY m.fecha ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ticket_id', $ticketId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Agregar mensaje a ticket
    public function agregarMensaje($data) {
        $query = "INSERT INTO mensajes_tickets (ticket_id, usuario_id, mensaje) 
                 VALUES (:ticket_id, :usuario_id, :mensaje)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ticket_id', $data['ticket_id'], PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $data['usuario_id'], PDO::PARAM_INT);
        $stmt->bindParam(':mensaje', $data['mensaje']);
        return $stmt->execute();
    }

    // Contar tickets por estado
    public function countByEstado($estado) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE estado = :estado";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}
?>