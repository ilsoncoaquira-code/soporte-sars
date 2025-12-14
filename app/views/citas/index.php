<?php $page_title = "Gestión de Citas"; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3"><i class="fas fa-calendar-alt me-2"></i> Citas</h1>
        <p class="text-muted">Gestión de citas y reservas de servicios</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo $config['base_url']; ?>citas/crear" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nueva Cita
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Citas</h5>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filtrar
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="?estado=todas">Todas</a></li>
                <li><a class="dropdown-item" href="?estado=pendiente">Pendientes</a></li>
                <li><a class="dropdown-item" href="?estado=confirmada">Confirmadas</a></li>
                <li><a class="dropdown-item" href="?estado=completada">Completadas</a></li>
                <li><a class="dropdown-item" href="?estado=cancelada">Canceladas</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($citas)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5>No hay citas registradas</h5>
                <p class="text-muted">Agenda tu primera cita haciendo clic en "Nueva Cita"</p>
                <a href="<?php echo $config['base_url']; ?>citas/crear" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-2"></i> Crear Primera Cita
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <?php if($userRole == 'admin'): ?>
                                <th>Cliente</th>
                            <?php endif; ?>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($citas as $cita): ?>
                        <tr>
                            <?php if($userRole == 'admin'): ?>
                                <td><?php echo htmlspecialchars($cita['cliente']); ?></td>
                            <?php endif; ?>
                            <td>
                                <strong><?php echo htmlspecialchars($cita['servicio']); ?></strong>
                                <?php if(isset($cita['precio'])): ?>
                                    <br><small class="text-muted">S/ <?php echo number_format($cita['precio'], 2); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($cita['fecha'])); ?>
                                <br><small class="text-muted"><?php echo $cita['notas'] ? 'Con notas' : 'Sin notas'; ?></small>
                            </td>
                            <td><?php echo date('h:i A', strtotime($cita['hora'])); ?></td>
                            <td>
                                <?php 
                                $badge_class = [
                                    'pendiente' => 'warning',
                                    'confirmada' => 'success',
                                    'completada' => 'primary',
                                    'cancelada' => 'danger'
                                ][$cita['estado']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $badge_class; ?>">
                                    <?php echo ucfirst($cita['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo $config['base_url']; ?>citas/<?php echo $cita['id']; ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($userRole == 'admin'): ?>
                                    <button class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación (si aplica) -->
            <nav aria-label="Paginación de citas">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>