<?php
class DashboardController {
    protected $config;
    protected $db;
    
    public function __construct($config) {
        $this->config = $config;
        $this->connectDB();
    }
    
    private function connectDB() {
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
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->config['base_url'] . 'login');
            exit;
        }
        
        // Obtener estadísticas
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        $stats = [];
        
        if ($userRole === 'admin') {
            // Estadísticas para admin
            $queries = [
                'total_usuarios' => "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'cliente'",
                'citas_pendientes' => "SELECT COUNT(*) as total FROM citas WHERE estado = 'pendiente'",
                'tickets_abiertos' => "SELECT COUNT(*) as total FROM tickets WHERE estado = 'abierto'",
                'ventas_totales' => "SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE estado = 'completado'"
            ];
            
            foreach ($queries as $key => $query) {
                $stmt = $this->db->query($query);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats[$key] = $result['total'];
            }
        } else {
            // Estadísticas para cliente
            $queries = [
                'mis_citas' => "SELECT COUNT(*) as total FROM citas WHERE usuario_id = :user_id",
                'mis_tickets' => "SELECT COUNT(*) as total FROM tickets WHERE usuario_id = :user_id",
                'mis_pedidos' => "SELECT COUNT(*) as total FROM pedidos WHERE usuario_id = :user_id"
            ];
            
            foreach ($queries as $key => $query) {
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats[$key] = $result['total'];
            }
        }
        
        // Mostrar vista
        $this->view('dashboard/index', [
            'stats' => $stats,
            'userRole' => $userRole
        ]);
    }
    
    private function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../views/$view.php";
        
        if (file_exists($viewPath)) {
            // Incluir header
            $headerPath = __DIR__ . "/../views/layouts/header.php";
            if (file_exists($headerPath)) {
                include $headerPath;
            }
            
            // Incluir vista
            include $viewPath;
            
            // Incluir footer
            $footerPath = __DIR__ . "/../views/layouts/footer.php";
            if (file_exists($footerPath)) {
                include $footerPath;
            }
        } else {
            die("Vista no encontrada: $view");
        }
    }
}
?>