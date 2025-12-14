<?php $page_title = "Panel de Administración"; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3"><i class="fas fa-cog me-2"></i> Panel de Administración</h1>
        <p class="text-muted">Gestión completa del sistema Soporte SARS</p>
    </div>
</div>

<!-- Estadísticas principales -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Usuarios Totales</h6>
                        <h2 class="card-title"><?php echo $stats['total_usuarios'] ?? 0; ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="<?php echo $config['base_url']; ?>admin/usuarios" class="text-white stretched-link small">
                    Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Ventas Hoy</h6>
                        <h2 class="card-title">S/ <?php echo number_format($stats['ventas_hoy'] ?? 0, 2); ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
                <small class="text-white-50">Ingresos del día</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Citas Hoy</h6>
                        <h2 class="card-title"><?php echo $stats['citas_hoy'] ?? 0; ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
                <small class="text-white-50">Citas programadas para hoy</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Tickets Abiertos</h6>
                        <h2 class="card-title"><?php echo $stats['tickets_abiertos'] ?? 0; ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-headset fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="<?php echo $config['base_url']; ?>tickets" class="text-white stretched-link small">
                    Atender tickets <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimos pedidos -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i> Últimos Pedidos</h5>
                <a href="#" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if(empty($ultimosPedidos)): ?>
                    <p class="text-muted text-center py-3">No hay pedidos recientes</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ultimosPedidos as $pedido): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                    <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                                    <td>
                                        <?php 
                                        $estado_badge = [
                                            'pendiente' => 'warning',
                                            'procesando' => 'info',
                                            'completado' => 'success',
                                            'cancelado' => 'danger'
                                        ][$pedido['estado']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $estado_badge; ?>">
                                            <?php echo ucfirst($pedido['estado']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Últimos tickets -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i> Tickets Recientes</h5>
                <a href="<?php echo $config['base_url']; ?>tickets" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if(empty($ultimosTickets)): ?>
                    <p class="text-muted text-center py-3">No hay tickets recientes</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Prioridad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ultimosTickets as $ticket): ?>
                                <tr>
                                    <td>#<?php echo $ticket['id']; ?></td>
                                    <td><?php echo htmlspecialchars($ticket['cliente']); ?></td>
                                    <td>
                                        <?php 
                                        $prioridad_badge = [
                                            'alta' => 'danger',
                                            'media' => 'warning',
                                            'baja' => 'success'
                                        ][$ticket['prioridad']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $prioridad_badge; ?>">
                                            <?php echo ucfirst($ticket['prioridad']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $estado_badge = [
                                            'abierto' => 'warning',
                                            'en_proceso' => 'info',
                                            'resuelto' => 'success',
                                            'cerrado' => 'secondary'
                                        ][$ticket['estado']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $estado_badge; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $ticket['estado'])); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Acciones administrativas -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i> Herramientas de Administración</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                <h5>Gestión de Usuarios</h5>
                                <p class="text-muted">Administra usuarios y permisos</p>
                                <a href="<?php echo $config['base_url']; ?>admin/usuarios" class="btn btn-outline-primary w-100">
                                    Administrar
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-box fa-3x text-success mb-3"></i>
                                <h5>Gestión de Productos</h5>
                                <p class="text-muted">Administra el catálogo de productos</p>
                                <a href="<?php echo $config['base_url']; ?>admin/productos" class="btn btn-outline-success w-100">
                                    Administrar
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                <h5>Reportes</h5>
                                <p class="text-muted">Genera reportes del sistema</p>
                                <button class="btn btn-outline-info w-100" disabled>
                                    Próximamente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>