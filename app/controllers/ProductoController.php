<?php
// app/controllers/ProductoController.php

class ProductoController {
    protected $config;
    protected $db;

    public function __construct($config) {
        $this->config = $config;
        $this->initDatabase();
    }

    private function initDatabase() {
        try {
            $this->db = new PDO(
                "mysql:host={$this->config['db']['host']};dbname={$this->config['db']['name']};charset=utf8",
                $this->config['db']['user'],
                $this->config['db']['pass']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    private function view($view, $data = []) {
        extract($data);
        include __DIR__ . "/../views/layouts/header.php";
        include __DIR__ . "/../views/$view.php";
        include __DIR__ . "/../views/layouts/footer.php";
    }

    public function index() {
        $categoria = $_GET['categoria'] ?? '';
        $busqueda = $_GET['busqueda'] ?? '';
        
        $query = "SELECT * FROM productos WHERE activo = 1";
        $params = [];
        
        if (!empty($categoria)) {
            $query .= " AND categoria = :categoria";
            $params[':categoria'] = $categoria;
        }
        
        if (!empty($busqueda)) {
            $query .= " AND (nombre LIKE :busqueda OR descripcion LIKE :busqueda)";
            $params[':busqueda'] = "%$busqueda%";
        }
        
        $query .= " ORDER BY nombre";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener categorías para el filtro
        $stmt = $this->db->query("SELECT DISTINCT categoria FROM productos WHERE categoria IS NOT NULL ORDER BY categoria");
        $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $this->view('productos/index', [
            'productos' => $productos,
            'categorias' => $categorias,
            'categoria' => $categoria,
            'busqueda' => $busqueda
        ]);
    }

    public function show($id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            $this->redirect('productos');
        }
        
        $this->view('productos/show', ['producto' => $producto]);
    }

    private function redirect($url) {
        header('Location: ' . $this->config['base_url'] . ltrim($url, '/'));
        exit;
    }
}
?>