<?php
// app/models/User.php

class User {
    private $db;
    private $table = 'usuarios';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los usuarios
    public function all() {
        $query = "SELECT * FROM {$this->table} ORDER BY fecha_registro DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar usuario por ID
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Buscar usuario por email
    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Autenticar usuario
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user) {
            $hashedPassword = hash('sha256', $password);
            if ($hashedPassword === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    // Crear nuevo usuario
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

    // Actualizar usuario
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

    // Eliminar usuario
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Contar usuarios por rol
    public function countByRole($role) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE rol = :role";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Obtener usuarios paginados
    public function paginate($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        // Total
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table}";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute();
        $total = $countStmt->fetch()['total'];
        
        // Datos
        $query = "SELECT * FROM {$this->table} ORDER BY fecha_registro DESC LIMIT :limit OFFSET :offset";
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