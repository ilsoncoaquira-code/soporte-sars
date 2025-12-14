<?php
// app/core/Model.php

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getInstance();
    }

    // Métodos CRUD básicos

    public function all($orderBy = null, $orderDir = 'ASC') {
        $query = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy} {$orderDir}";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function update($id, $data) {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "{$key} = :{$key}";
        }
        $set = implode(', ', $set);
        
        $query = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Métodos de consulta

    public function where($conditions, $params = []) {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        $whereClause = implode(' AND ', $where);
        
        $query = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findWhere($conditions, $params = []) {
        $result = $this->where($conditions, $params);
        return $result[0] ?? null;
    }

    public function count($conditions = null, $params = []) {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if ($conditions) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
            $whereClause = implode(' AND ', $where);
            $query .= " WHERE {$whereClause}";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function paginate($page = 1, $perPage = 10, $conditions = null, $params = [], $orderBy = null, $orderDir = 'ASC') {
        $offset = ($page - 1) * $perPage;
        
        // Contar total
        $total = $this->count($conditions, $params);
        
        // Construir consulta principal
        $query = "SELECT * FROM {$this->table}";
        
        if ($conditions) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
            }
            $whereClause = implode(' AND ', $where);
            $query .= " WHERE {$whereClause}";
        }
        
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy} {$orderDir}";
        }
        
        $query .= " LIMIT :limit OFFSET :offset";
        
        // Preparar y ejecutar
        $stmt = $this->db->prepare($query);
        
        // Vincular parámetros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => $offset + count($data)
        ];
    }

    // Métodos de relación

    public function hasMany($relatedModel, $foreignKey = null, $localKey = null) {
        $foreignKey = $foreignKey ?? $this->table . '_id';
        $localKey = $localKey ?? $this->primaryKey;
        
        $model = new $relatedModel();
        return $model->where([$foreignKey => $this->{$localKey}]);
    }

    public function belongsTo($relatedModel, $foreignKey = null, $ownerKey = null) {
        $foreignKey = $foreignKey ?? str_replace('_id', '', $this->table) . '_id';
        $ownerKey = $ownerKey ?? 'id';
        
        $model = new $relatedModel();
        return $model->find($this->{$foreignKey});
    }

    // Métodos de validación

    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleParts = explode('|', $rule);
            
            foreach ($ruleParts as $part) {
                if ($part === 'required' && empty($value)) {
                    $errors[$field][] = "El campo {$field} es requerido";
                }
                
                if ($part === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "El campo {$field} debe ser un email válido";
                }
                
                if (strpos($part, 'min:') === 0 && !empty($value)) {
                    $min = explode(':', $part)[1];
                    if (strlen($value) < $min) {
                        $errors[$field][] = "El campo {$field} debe tener al menos {$min} caracteres";
                    }
                }
                
                if (strpos($part, 'max:') === 0 && !empty($value)) {
                    $max = explode(':', $part)[1];
                    if (strlen($value) > $max) {
                        $errors[$field][] = "El campo {$field} no debe exceder {$max} caracteres";
                    }
                }
                
                if ($part === 'numeric' && !empty($value) && !is_numeric($value)) {
                    $errors[$field][] = "El campo {$field} debe ser numérico";
                }
            }
        }
        
        return empty($errors) ? null : $errors;
    }

    // Métodos de transacción

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    // Métodos de utilidad

    protected function lastInsertId() {
        return $this->db->lastInsertId();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
?>