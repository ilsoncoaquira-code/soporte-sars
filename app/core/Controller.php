<?php
// app/core/Controller.php

abstract class Controller {
    protected $config;
    protected $db;

    public function __construct($config) {
        $this->config = $config;
        $this->initDatabase();
    }

    private function initDatabase() {
        require_once __DIR__ . '/../models/Database.php';
        $this->db = Database::getInstance($this->config['db']);
    }

    protected function view($view, $data = []) {
        // Extraer variables para la vista
        extract($data);
        
        // Incluir header
        require_once __DIR__ . "/../views/layouts/header.php";
        
        // Incluir vista específica
        $viewPath = __DIR__ . "/../views/{$view}.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            $this->error("Vista no encontrada: {$view}");
        }
        
        // Incluir footer
        require_once __DIR__ . "/../views/layouts/footer.php";
    }

    protected function redirect($url) {
        header('Location: ' . $this->config['base_url'] . ltrim($url, '/'));
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function getUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role']
            ];
        }
        return null;
    }

    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = 'Debe iniciar sesión para acceder a esta página';
            $this->redirect('login');
        }
    }

    protected function requireAdmin() {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'admin') {
            $this->error('Acceso denegado. Se requiere rol de administrador.');
        }
    }

    protected function requireGuest() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
    }

    protected function error($message, $code = 500) {
        http_response_code($code);
        die("<h2>Error {$code}</h2><p>{$message}</p>");
    }

    protected function success($message, $data = null) {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    protected function fail($message, $errors = null) {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }

    protected function validateRequired($fields, $data) {
        $errors = [];
        foreach ($fields as $field) {
            if (empty($data[$field] ?? '')) {
                $errors[$field] = "El campo {$field} es requerido";
            }
        }
        return empty($errors) ? null : $errors;
    }

    protected function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = $this->sanitize($value);
            }
        } else {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }

    protected function uploadFile($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf']) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $this->fail('Error al subir el archivo');
        }

        // Verificar tipo
        if (!in_array($file['type'], $allowedTypes)) {
            return $this->fail('Tipo de archivo no permitido');
        }

        // Verificar tamaño (máximo 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return $this->fail('El archivo es demasiado grande (máximo 5MB)');
        }

        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadPath = __DIR__ . '/../../public/uploads/' . $filename;

        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $this->success('Archivo subido correctamente', [
                'filename' => $filename,
                'path' => '/uploads/' . $filename,
                'original_name' => $file['name'],
                'size' => $file['size'],
                'type' => $file['type']
            ]);
        }

        return $this->fail('Error al guardar el archivo');
    }
}
?>