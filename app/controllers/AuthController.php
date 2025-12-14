<?php
// app/controllers/AuthController.php - VERSIÓN COMPLETA Y CORREGIDA

class AuthController {
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

    private function redirect($url) {
        header('Location: ' . $this->config['base_url'] . ltrim($url, '/'));
        exit;
    }

    // ==================== MÉTODO LOGIN ====================
    public function login() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        $this->view('auth/login');
    }

    // ==================== MÉTODO AUTHENTICATE ====================
    public function authenticate() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son requeridos';
            $this->redirect('login');
        }
        
        // Buscar usuario
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verificar contraseña (SHA256 como en la BD)
            $hashedPassword = hash('sha256', $password);
            
            if ($hashedPassword === $user['password']) {
                // Iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];
                
                $_SESSION['success'] = '¡Bienvenido ' . $user['nombre'] . '!';
                $this->redirect('dashboard');
            } else {
                $_SESSION['error'] = 'Contraseña incorrecta';
                $this->redirect('login');
            }
        } else {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('login');
        }
    }

    // ==================== MÉTODO REGISTER ====================
    public function register() {
        $this->view('auth/register');
    }

    // ==================== MÉTODO STORE (guardar registro) ====================
    public function store() {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        
        // Validaciones básicas
        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Nombre, email y contraseña son requeridos';
            $this->redirect('register');
        }
        
        // Verificar si el email ya existe
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'El email ya está registrado';
            $this->redirect('register');
        }
        
        // Crear usuario
        $hashedPassword = hash('sha256', $password);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password, telefono, rol) 
                                   VALUES (:nombre, :email, :password, :telefono, 'cliente')");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':telefono', $telefono);
        
        if ($stmt->execute()) {
            $userId = $this->db->lastInsertId();
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'cliente';
            $_SESSION['success'] = '¡Registro exitoso! Bienvenido ' . $nombre;
            $this->redirect('dashboard');
        } else {
            $_SESSION['error'] = 'Error al registrar usuario';
            $this->redirect('register');
        }
    }

    // ==================== MÉTODO LOGOUT (¡ESTE ES EL QUE FALTA!) ====================
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Borrar cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir al login
        $this->redirect('login');
    }

} // Fin de la clase AuthController
?>