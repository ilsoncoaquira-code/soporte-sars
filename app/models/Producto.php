<?php
// app/models/Producto.php

class Producto {
    private $db;
    private $table = 'productos';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los productos
    public function all() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener productos activos
    public function getActivos() {
        $query = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar producto por ID
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Buscar productos por categoría
    public function findByCategoria($categoria) {
        $query = "SELECT * FROM {$this->table} WHERE categoria = :categoria AND activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar productos
    public function search($term) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE activo = 1 AND 
                 (nombre LIKE :term OR descripcion LIKE :term OR categoria LIKE :term) 
                 ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$term}%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear nuevo producto
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

    // Actualizar producto
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

    // Eliminar producto (cambio de estado)
    public function delete($id) {
        $query = "UPDATE {$this->table} SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Reducir stock
    public function reducirStock($id, $cantidad) {
        $query = "UPDATE {$this->table} SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Aumentar stock
    public function aumentarStock($id, $cantidad) {
        $query = "UPDATE {$this->table} SET stock = stock + :cantidad WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener categorías únicas
    public function getCategorias() {
        $query = "SELECT DISTINCT categoria FROM {$this->table} WHERE categoria IS NOT NULL ORDER BY categoria";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obtener productos con bajo stock
    public function getBajoStock($limite = 10) {
        $query = "SELECT * FROM {$this->table} WHERE stock <= :limite AND activo = 1 ORDER BY stock ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Contar productos activos
    public function countActivos() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE activo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Obtener productos paginados
    public function paginate($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        // Total
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table} WHERE activo = 1";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute();
        $total = $countStmt->fetch()['total'];
        
        // Datos
        $query = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY nombre LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
?>