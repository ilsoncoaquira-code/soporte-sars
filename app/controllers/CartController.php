<?php
// app/controllers/CartController.php

class CartController {
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
        
        // Inicializar carrito en sesión si no existe
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $cartItems = [];
        $total = 0;
        
        // Obtener detalles de productos en el carrito
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                $subtotal = $product['precio'] * $quantity;
                $total += $subtotal;
                
                $cartItems[] = [
                    'id' => $product['id'],
                    'nombre' => $product['nombre'],
                    'precio' => $product['precio'],
                    'cantidad' => $quantity,
                    'subtotal' => $subtotal,
                    'imagen' => $product['imagen_url'],
                    'stock' => $product['stock']
                ];
            }
        }
        
        $this->view('productos/cart', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function add($productId) {
        $this->requireAuth();
        
        $productId = intval($productId);
        $quantity = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
        
        if ($quantity < 1) {
            $_SESSION['error'] = 'Cantidad inválida';
            $this->redirect('productos');
        }
        
        // Verificar producto y stock
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            $_SESSION['error'] = 'Producto no encontrado';
            $this->redirect('productos');
        }
        
        // Verificar stock
        $cartQuantity = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId] : 0;
        $totalQuantity = $cartQuantity + $quantity;
        
        if ($totalQuantity > $product['stock']) {
            $_SESSION['error'] = 'No hay suficiente stock disponible';
            $this->redirect('productos');
        }
        
        // Agregar al carrito
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        $_SESSION['success'] = 'Producto agregado al carrito';
        $this->redirect('productos');
    }

    public function remove($productId) {
        $this->requireAuth();
        
        $productId = intval($productId);
        
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = 'Producto eliminado del carrito';
        }
        
        $this->redirect('cart');
    }

    public function checkout() {
        $this->requireAuth();
        
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'El carrito está vacío';
            $this->redirect('cart');
        }
        
        $userId = $_SESSION['user_id'];
        $total = 0;
        
        // Calcular total y verificar stock
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $this->db->prepare("SELECT precio, stock FROM productos WHERE id = :id");
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product || $product['stock'] < $quantity) {
                $_SESSION['error'] = 'Stock insuficiente para el producto ID: ' . $productId;
                $this->redirect('cart');
            }
            
            $total += $product['precio'] * $quantity;
        }
        
        // Iniciar transacción
        $this->db->beginTransaction();
        
        try {
            // Crear pedido
            $stmt = $this->db->prepare("
                INSERT INTO pedidos (usuario_id, total, estado, metodo_pago) 
                VALUES (:user_id, :total, 'pendiente', 'transferencia')
            ");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':total', $total);
            $stmt->execute();
            $pedidoId = $this->db->lastInsertId();
            
            // Crear detalles y actualizar stock
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                // Obtener precio actual
                $stmt = $this->db->prepare("SELECT precio FROM productos WHERE id = :id");
                $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Insertar detalle
                $stmt = $this->db->prepare("
                    INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) 
                    VALUES (:pedido_id, :producto_id, :cantidad, :precio)
                ");
                $stmt->bindParam(':pedido_id', $pedidoId);
                $stmt->bindParam(':producto_id', $productId);
                $stmt->bindParam(':cantidad', $quantity);
                $stmt->bindParam(':precio', $product['precio']);
                $stmt->execute();
                
                // Actualizar stock
                $stmt = $this->db->prepare("
                    UPDATE productos SET stock = stock - :cantidad WHERE id = :id
                ");
                $stmt->bindParam(':cantidad', $quantity);
                $stmt->bindParam(':id', $productId);
                $stmt->execute();
            }
            
            // Confirmar transacción
            $this->db->commit();
            
            // Limpiar carrito
            $_SESSION['cart'] = [];
            
            $_SESSION['success'] = '¡Pedido realizado exitosamente! ID del pedido: ' . $pedidoId;
            $this->redirect('dashboard');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Error al procesar el pedido: ' . $e->getMessage();
            $this->redirect('cart');
        }
    }
}
?>