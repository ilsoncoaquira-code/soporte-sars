<?php
// app/controllers/TicketController.php

class TicketController {
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
            // Admin ve todos los tickets
            $stmt = $this->db->query("
                SELECT t.*, u.nombre as cliente 
                FROM tickets t 
                JOIN usuarios u ON t.usuario_id = u.id 
                ORDER BY t.fecha_creacion DESC
            ");
        } else {
            // Cliente ve solo sus tickets
            $stmt = $this->db->prepare("
                SELECT * FROM tickets 
                WHERE usuario_id = :user_id 
                ORDER BY fecha_creacion DESC
            ");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('tickets/index', [
            'tickets' => $tickets,
            'userRole' => $userRole
        ]);
    }

    public function create() {
        $this->requireAuth();
        $this->view('tickets/create');
    }

    public function store() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $tipo = $_POST['tipo'] ?? 'general';
        $prioridad = $_POST['prioridad'] ?? 'media';
        
        if (empty($titulo) || empty($descripcion)) {
            $_SESSION['error'] = 'Título y descripción son requeridos';
            $this->redirect('tickets/crear');
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO tickets (usuario_id, titulo, descripcion, tipo, prioridad, estado) 
            VALUES (:user_id, :titulo, :descripcion, :tipo, :prioridad, 'abierto')
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':prioridad', $prioridad);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Ticket creado exitosamente. Nos pondremos en contacto pronto.';
            $this->redirect('tickets');
        } else {
            $_SESSION['error'] = 'Error al crear el ticket';
            $this->redirect('tickets/crear');
        }
    }

    public function show($id) {
        $this->requireAuth();
        
        $stmt = $this->db->prepare("
            SELECT t.*, u.nombre as cliente 
            FROM tickets t 
            JOIN usuarios u ON t.usuario_id = u.id 
            WHERE t.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$ticket) {
            $_SESSION['error'] = 'Ticket no encontrado';
            $this->redirect('tickets');
        }
        
        // Verificar permisos
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        
        if ($userRole !== 'admin' && $ticket['usuario_id'] != $userId) {
            $_SESSION['error'] = 'No tienes permiso para ver este ticket';
            $this->redirect('tickets');
        }
        
        // Obtener mensajes del ticket
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre as usuario_nombre 
            FROM mensajes_tickets m 
            JOIN usuarios u ON m.usuario_id = u.id 
            WHERE m.ticket_id = :ticket_id 
            ORDER BY m.fecha ASC
        ");
        $stmt->bindParam(':ticket_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('tickets/show', [
            'ticket' => $ticket,
            'mensajes' => $mensajes
        ]);
    }
}
?>