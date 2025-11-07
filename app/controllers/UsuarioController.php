<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    public function index() {
        $usuario = new Usuario();
        $resultado = $usuario->listar();
        include __DIR__ . '/../views/usuarios/login.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $usuario->registrar($_POST['nombre'], $_POST['email'], $_POST['password']);
            echo "<p>Registro exitoso. Ahora puede iniciar sesión.</p>";
        } else {
            include __DIR__ . '/../views/usuarios/registro.php';
        }
    }
}
?>
