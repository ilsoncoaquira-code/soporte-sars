<?php
// app/controllers/CitaController.php

class CitaController {
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

    private function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
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
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        
        if ($userRole === 'admin') {
            // Admin ve todas las citas
            $stmt = $this->db->query("
                SELECT c.*, u.nombre as cliente, s.nombre as servicio 
                FROM citas c 
                JOIN usuarios u ON c.usuario_id = u.id 
                JOIN servicios s ON c.servicio_id = s.id 
                ORDER BY c.fecha DESC, c.hora DESC
            ");
        } else {
            // Cliente ve solo sus citas
            $stmt = $this->db->prepare("
                SELECT c.*, s.nombre as servicio, s.precio 
                FROM citas c 
                JOIN servicios s ON c.servicio_id = s.id 
                WHERE c.usuario_id = :user_id 
                ORDER BY c.fecha DESC, c.hora DESC
            ");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('citas/index', [
            'citas' => $citas,
            'userRole' => $userRole
        ]);
    }

    public function create() {
        $this->requireAuth();
        
        // Obtener servicios disponibles
        $stmt = $this->db->query("SELECT * FROM servicios WHERE activo = 1 ORDER BY nombre");
        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('citas/create', ['servicios' => $servicios]);
    }

    public function store() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $servicioId = $_POST['servicio_id'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $notas = $_POST['notas'] ?? '';
        
        if (empty($servicioId) || empty($fecha) || empty($hora)) {
            $_SESSION['error'] = 'Todos los campos son requeridos';
            $this->redirect('citas/crear');
        }
        
        // Verificar disponibilidad
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM citas 
            WHERE fecha = :fecha AND hora = :hora AND estado != 'cancelada'
        ");
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total'] > 0) {
            $_SESSION['error'] = 'La hora seleccionada no está disponible';
            $this->redirect('citas/crear');
        }
        
        // Crear cita
        $stmt = $this->db->prepare("
            INSERT INTO citas (usuario_id, servicio_id, fecha, hora, notas, estado) 
            VALUES (:user_id, :servicio_id, :fecha, :hora, :notas, 'pendiente')
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':servicio_id', $servicioId);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':notas', $notas);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Cita agendada exitosamente';
            $this->redirect('citas');
        } else {
            $_SESSION['error'] = 'Error al agendar la cita';
            $this->redirect('citas/crear');
        }
    }

    public function show($id) {
        $this->requireAuth();
        
        $stmt = $this->db->prepare("
            SELECT c.*, u.nombre as cliente, s.nombre as servicio, s.precio 
            FROM citas c 
            JOIN usuarios u ON c.usuario_id = u.id 
            JOIN servicios s ON c.servicio_id = s.id 
            WHERE c.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cita) {
            $_SESSION['error'] = 'Cita no encontrada';
            $this->redirect('citas');
        }
        
        // Verificar permisos
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        
        if ($userRole !== 'admin' && $cita['usuario_id'] != $userId) {
            $_SESSION['error'] = 'No tienes permiso para ver esta cita';
            $this->redirect('citas');
        }
        
        $this->view('citas/show', ['cita' => $cita]);
    }
}
?>