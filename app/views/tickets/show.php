<?php $page_title = "Ticket #" . $ticket['id']; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3"><i class="fas fa-ticket-alt me-2"></i> Ticket #<?php echo $ticket['id']; ?></h1>
        <p class="text-muted">Conversación y seguimiento del ticket de soporte</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Información del ticket -->
        <div class="card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Información del Ticket</h5>
                <div>
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
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h5><?php echo htmlspecialchars($ticket['titulo']); ?></h5>
                        <p class="text-muted">Creado por: <?php echo htmlspecialchars($ticket['cliente']); ?></p>
                    </div>
                    <div class="col-md-4 text-end">
                        <small class="text-muted">
                            <?php echo date('d/m/Y H:i', strtotime($ticket['fecha_creacion'])); ?>
                        </small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <h6><i class="fas fa-tag me-2"></i> Tipo</h6>
                        <p>
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
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fas fa-exclamation-circle me-2"></i> Prioridad</h6>
                        <p>
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
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fas fa-user-tie me-2"></i> Técnico asignado</h6>
                        <p>
                            <?php if($ticket['tecnico_asignado']): ?>
                                <span class="badge bg-info">Técnico asignado</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Pendiente de asignación</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-file-alt me-2"></i> Descripción</h6>
                    <div class="alert alert-light">
                        <?php echo nl2br(htmlspecialchars($ticket['descripcion'])); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mensajes -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i> Conversación</h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if(empty($mensajes)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aún no hay mensajes en este ticket</p>
                    </div>
                <?php else: ?>
                    <?php foreach($mensajes as $mensaje): ?>
                        <div class="mb-3 <?php echo $mensaje['usuario_id'] == $_SESSION['user_id'] ? 'text-end' : ''; ?>">
                            <div class="d-flex <?php echo $mensaje['usuario_id'] == $_SESSION['user_id'] ? 'justify-content-end' : ''; ?>">
                                <div class="<?php echo $mensaje['usuario_id'] == $_SESSION['user_id'] ? 'bg-primary text-white' : 'bg-light'; ?> 
                                            rounded p-3" style="max-width: 70%;">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="<?php echo $mensaje['usuario_id'] == $_SESSION['user_id'] ? 'text-white-50' : 'text-muted'; ?>">
                                            <?php echo htmlspecialchars($mensaje['usuario_nombre']); ?>
                                        </small>
                                        <small class="<?php echo $mensaje['usuario_id'] == $_SESSION['user_id'] ? 'text-white-50' : 'text-muted'; ?>">
                                            <?php echo date('H:i', strtotime($mensaje['fecha'])); ?>
                                        </small>
                                    </div>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($mensaje['mensaje'])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <form method="POST" action="#">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Escribe un mensaje..." required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Acciones -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i> Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if($ticket['estado'] == 'abierto' && $_SESSION['user_role'] == 'admin'): ?>
                        <button class="btn btn-info">
                            <i class="fas fa-user-tie me-2"></i> Asignar a mí
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-play-circle me-2"></i> En proceso
                        </button>
                    <?php elseif($ticket['estado'] == 'en_proceso' && $_SESSION['user_role'] == 'admin'): ?>
                        <button class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i> Marcar como resuelto
                        </button>
                    <?php elseif($ticket['estado'] == 'resuelto'): ?>
                        <button class="btn btn-secondary">
                            <i class="fas fa-times-circle me-2"></i> Cerrar ticket
                        </button>
                    <?php endif; ?>
                    
                    <a href="<?php echo $config['base_url']; ?>tickets" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Información adicional -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Información</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>ID Ticket</span>
                        <strong>#<?php echo $ticket['id']; ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Fecha creación</span>
                        <small><?php echo date('d/m/Y', strtotime($ticket['fecha_creacion'])); ?></small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Tiempo transcurrido</span>
                        <small>2 días</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Mensajes</span>
                        <span class="badge bg-primary"><?php echo count($mensajes); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>