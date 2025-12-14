<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte SARS - <?php echo $page_title ?? 'Sistema'; ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 60px;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        
        .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .stat-card {
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: white;
            padding: 15px 0;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo $config['base_url']; ?>">
                <i class="fas fa-tools me-2"></i>Soporte SARS
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Usuario logueado -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $config['base_url']; ?>dashboard">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $config['base_url']; ?>citas">
                                <i class="fas fa-calendar-alt me-1"></i> Citas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $config['base_url']; ?>tickets">
                                <i class="fas fa-headset me-1"></i> Tickets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $config['base_url']; ?>productos">
                                <i class="fas fa-shopping-cart me-1"></i> Productos
                            </a>
                        </li>
                        
                        <?php if($_SESSION['user_role'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $config['base_url']; ?>admin">
                                    <i class="fas fa-cog me-1"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-user-circle me-2"></i> Mi Perfil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?php echo $config['base_url']; ?>logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Visitante -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $config['base_url']; ?>login">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $config['base_url']; ?>register">
                                <i class="fas fa-user-plus me-1"></i> Registro
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Mensajes Flash -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['info'])): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['info']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>