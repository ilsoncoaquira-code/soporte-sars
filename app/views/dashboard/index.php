<?php $page_title = "Dashboard"; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3"><i class="fas fa-home me-2"></i> Dashboard</h1>
                <p class="lead mb-0">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
                <small class="text-muted">Rol: <?php echo ucfirst($_SESSION['user_role']); ?></small>
            </div>
            <div class="text-end">
                <span class="badge bg-primary">Último acceso: Hoy</span>
            </div>
        </div>
        <hr>
    </div>
</div>

<!-- Estadísticas -->
<div class="row">
    <?php if($userRole == 'admin'): ?>
        <!-- Estadísticas para Admin -->
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Clientes</h6>
                            <h2 class="card-title"><?php echo $stats['total_clientes'] ?? 0; ?></h2>
                            <small>Total registrados</small>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Citas Pendientes</h6>
                            <h2 class="card-title"><?php echo $stats['citas_pendientes'] ?? 0; ?></h2>
                            <small>Por atender</small>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Tickets Abiertos</h6>
                            <h2 class="card-title"><?php echo $stats['tickets_abiertos'] ?? 0; ?></h2>
                            <small>Sin resolver</small>
                        </div>
                        <div>
                            <i class="fas fa-headset fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Ventas Totales</h6>
                            <h2 class="card-title">S/ <?php echo number_format($stats['ventas_totales'] ?? 0, 2); ?></h2>
                            <small>Completadas</small>
                        </div>
                        <div>
                            <i class="fas fa-chart-line fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Productos bajo stock -->
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Bajo Stock</h6>
                            <h2 class="card-title"><?php echo $stats['productos_bajo_stock'] ?? 0; ?></h2>
                            <small>Menos de 5 unidades</small>
                        </div>
                        <div>
                            <i class="fas fa-box fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Estadísticas para Cliente -->
        <div class="col-md-4 mb-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Mis Citas</h6>
                            <h2 class="card-title"><?php echo $stats['mis_citas'] ?? 0; ?></h2>
                            <small>Total agendadas</small>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Mis Tickets</h6>
                            <h2 class="card-title"><?php echo $stats['mis_tickets'] ?? 0; ?></h2>
                            <small>Solicitudes de soporte</small>
                        </div>
                        <div>
                            <i class="fas fa-headset fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Mis Pedidos</h6>
                            <h2 class="card-title"><?php echo $stats['mis_pedidos'] ?? 0; ?></h2>
                            <small>Compras realizadas</small>
                        </div>
                        <div>
                            <i class="fas fa-shopping-bag fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Citas Pendientes</h6>
                            <h2 class="card-title"><?php echo $stats['citas_pendientes'] ?? 0; ?></h2>
                            <small>Por atender</small>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Acciones rápidas -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo $config['base_url']; ?>citas/crear" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-calendar-plus me-2"></i> Nueva Cita
                    </a>
                    <a href="<?php echo $config['base_url']; ?>tickets/crear" class="btn btn-warning px-4 py-2">
                        <i class="fas fa-plus-circle me-2"></i> Nuevo Ticket
                    </a>
                    <a href="<?php echo $config['base_url']; ?>productos" class="btn btn-success px-4 py-2">
                        <i class="fas fa-store me-2"></i> Ver Productos
                    </a>
                    <a href="<?php echo $config['base_url']; ?>citas" class="btn btn-info px-4 py-2">
                        <i class="fas fa-list me-2"></i> Ver Mis Citas
                    </a>
                    <?php if($userRole == 'admin'): ?>
                        <a href="<?php echo $config['base_url']; ?>admin" class="btn btn-dark px-4 py-2">
                            <i class="fas fa-cog me-2"></i> Panel Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimas actividades (solo admin) -->
<?php if($userRole == 'admin'): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user-plus text-success me-2"></i> Nuevos Usuarios</h6>
                        <p class="text-muted">3 nuevos usuarios esta semana</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-shopping-cart text-primary me-2"></i> Ventas Hoy</h6>
                        <p class="text-muted">S/ 450.00 en ventas hoy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>