<?php
// app/controllers/AdminController.php

class AdminController {
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

    private function requireAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('login');
        }
    }

    private function redirect($url) {
        header('Location: ' . $this->config['base_url'] . ltrim($url, '/'));
        exit;
    }

    private function view($view, $data = []) {
        extract($data);
        include __DIR__ . "/../views/layouts/header.php";
        include __DIR__ . "/../views/$view.php";
        include __DIR__ . "/../views/layouts/footer.php";
    }

    public function index() {
        $this->requireAdmin();
        
        // Estadísticas para admin
        $queries = [
            'total_usuarios' => "SELECT COUNT(*) as total FROM usuarios",
            'usuarios_hoy' => "SELECT COUNT(*) as total FROM usuarios WHERE DATE(fecha_registro) = CURDATE()",
            'citas_hoy' => "SELECT COUNT(*) as total FROM citas WHERE fecha = CURDATE()",
            'tickets_abiertos' => "SELECT COUNT(*) as total FROM tickets WHERE estado = 'abierto'",
            'productos_bajo_stock' => "SELECT COUNT(*) as total FROM productos WHERE stock < 5",
            'ventas_hoy' => "SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE DATE(fecha_pedido) = CURDATE()"
        ];
        
        $stats = [];
        foreach ($queries as $key => $query) {
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats[$key] = $result['total'];
        }
        
        // Últimos pedidos
        $stmt = $this->db->query("
            SELECT p.*, u.nombre as cliente 
            FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha_pedido DESC 
            LIMIT 5
        ");
        $ultimosPedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Últimos tickets
        $stmt = $this->db->query("
            SELECT t.*, u.nombre as cliente 
            FROM tickets t 
            JOIN usuarios u ON t.usuario_id = u.id 
            ORDER BY t.fecha_creacion DESC 
            LIMIT 5
        ");
        $ultimosTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('admin/index', [
            'stats' => $stats,
            'ultimosPedidos' => $ultimosPedidos,
            'ultimosTickets' => $ultimosTickets
        ]);
    }

    public function usuarios() {
        $this->requireAdmin();
        
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Total de usuarios
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM usuarios");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPaginas = ceil($total / $limit);
        
        // Usuarios paginados
        $stmt = $this->db->prepare("
            SELECT * FROM usuarios 
            ORDER BY fecha_registro DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('admin/usuarios', [
            'usuarios' => $usuarios,
            'paginaActual' => $page,
            'totalPaginas' => $totalPaginas,
            'totalUsuarios' => $total
        ]);
    }

    public function productos() {
        $this->requireAdmin();
        
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Total de productos
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM productos");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPaginas = ceil($total / $limit);
        
        // Productos paginados
        $stmt = $this->db->prepare("
            SELECT * FROM productos 
            ORDER BY nombre 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('admin/productos', [
            'productos' => $productos,
            'paginaActual' => $page,
            'totalPaginas' => $totalPaginas,
            'totalProductos' => $total
        ]);
    }
}
?>