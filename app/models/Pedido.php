<?php
// app/models/Pedido.php

class Pedido {
    private $db;
    private $table = 'pedidos';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los pedidos
    public function all() {
        $query = "SELECT p.*, u.nombre as cliente 
                 FROM {$this->table} p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 ORDER BY p.fecha_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar pedido por ID
    public function find($id) {
        $query = "SELECT p.*, u.nombre as cliente, u.email as cliente_email 
                 FROM {$this->table} p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener pedidos de un usuario
    public function findByUser($userId) {
        $query = "SELECT p.* FROM {$this->table} p 
                 WHERE p.usuario_id = :user_id 
                 ORDER BY p.fecha_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear nuevo pedido
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

    // Actualizar pedido
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

    // Eliminar pedido
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Cambiar estado del pedido
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE {$this->table} SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado);
        return $stmt->execute();
    }

    // Obtener detalles de un pedido
    public function getDetalles($pedidoId) {
        $query = "SELECT d.*, p.nombre as producto_nombre, p.descripcion as producto_descripcion 
                 FROM detalles_pedido d 
                 JOIN productos p ON d.producto_id = p.id 
                 WHERE d.pedido_id = :pedido_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pedido_id', $pedidoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Agregar detalle a pedido
    public function agregarDetalle($data) {
        $query = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) 
                 VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pedido_id', $data['pedido_id'], PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $data['producto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $data['cantidad'], PDO::PARAM_INT);
        $stmt->bindParam(':precio_unitario', $data['precio_unitario']);
        return $stmt->execute();
    }

    // Obtener ventas totales
    public function getVentasTotales() {
        $query = "SELECT COALESCE(SUM(total), 0) as total FROM {$this->table} WHERE estado = 'completado'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Obtener ventas del mes actual
    public function getVentasMes() {
        $query = "SELECT COALESCE(SUM(total), 0) as total 
                 FROM {$this->table} 
                 WHERE estado = 'completado' 
                 AND MONTH(fecha_pedido) = MONTH(CURRENT_DATE()) 
                 AND YEAR(fecha_pedido) = YEAR(CURRENT_DATE())";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Obtener pedidos por estado
    public function getByEstado($estado) {
        $query = "SELECT p.*, u.nombre as cliente 
                 FROM {$this->table} p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 WHERE p.estado = :estado 
                 ORDER BY p.fecha_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Contar pedidos por estado
    public function countByEstado($estado) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE estado = :estado";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Obtener últimos pedidos
    public function getUltimos($limit = 5) {
        $query = "SELECT p.*, u.nombre as cliente 
                 FROM {$this->table} p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 ORDER BY p.fecha_pedido DESC 
                 LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>