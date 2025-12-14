<?php $page_title = "Tickets de Soporte"; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3"><i class="fas fa-headset me-2"></i> Tickets de Soporte</h1>
        <p class="text-muted">Gestión de solicitudes de soporte técnico</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo $config['base_url']; ?>tickets/crear" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nuevo Ticket
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Mis Tickets</h5>
    </div>
    <div class="card-body">
        <?php if(empty($tickets)): ?>
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <h5>No hay tickets registrados</h5>
                <p class="text-muted">Crea tu primer ticket de soporte haciendo clic en "Nuevo Ticket"</p>
                <a href="<?php echo $config['base_url']; ?>tickets/crear" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-2"></i> Crear Primer Ticket
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
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tickets as $ticket): ?>
                        <tr>
                            <?php if($userRole == 'admin'): ?>
                                <td><?php echo htmlspecialchars($ticket['cliente']); ?></td>
                            <?php endif; ?>
                            <td>
                                <strong><?php echo htmlspecialchars($ticket['titulo']); ?></strong><br>
                                <small class="text-muted">
                                    <?php echo strlen($ticket['descripcion']) > 50 ? 
                                          substr($ticket['descripcion'], 0, 50) . '...' : 
                                          $ticket['descripcion']; ?>
                                </small>
                            </td>
                            <td>
                                <?php 
                                $tipo_badge = [
                                    'hardware' => 'danger',
                                    'software' => 'info',
                                    'general' => 'secondary'
                                ][$ticket['tipo']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $tipo_badge; ?>">
                                    <?php echo ucfirst($ticket['tipo']); ?>
                                </span>
                            </td>
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
                            <td>
                                <?php echo date('d/m/Y', strtotime($ticket['fecha_creacion'])); ?><br>
                                <small class="text-muted"><?php echo date('H:i', strtotime($ticket['fecha_creacion'])); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo $config['base_url']; ?>tickets/<?php echo $ticket['id']; ?>" 
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
        <?php endif; ?>
    </div>
</div>