<?php $page_title = "Detalle de Cita #" . $cita['id']; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3"><i class="fas fa-calendar-day me-2"></i> Cita #<?php echo $cita['id']; ?></h1>
        <p class="text-muted">Detalles completos de la cita programada</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Información de la Cita</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2 text-primary"></i> Cliente</h6>
                        <p><?php echo htmlspecialchars($cita['cliente']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-tools me-2 text-primary"></i> Servicio</h6>
                        <p>
                            <strong><?php echo htmlspecialchars($cita['servicio']); ?></strong><br>
                            <span class="text-muted">S/ <?php echo number_format($cita['precio'], 2); ?></span>
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar me-2 text-primary"></i> Fecha</h6>
                        <p><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-clock me-2 text-primary"></i> Hora</h6>
                        <p><?php echo date('h:i A', strtotime($cita['hora'])); ?></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2 text-primary"></i> Estado</h6>
                        <p>
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
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar-plus me-2 text-primary"></i> Fecha Creación</h6>
                        <p><?php echo date('d/m/Y H:i', strtotime($cita['fecha_creacion'])); ?></p>
                    </div>
                </div>
                
                <?php if(!empty($cita['notas'])): ?>
                <div class="row">
                    <div class="col-md-12">
                        <h6><i class="fas fa-sticky-note me-2 text-primary"></i> Notas</h6>
                        <div class="alert alert-light">
                            <?php echo nl2br(htmlspecialchars($cita['notas'])); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if($cita['estado'] == 'pendiente'): ?>
                        <button class="btn btn-success">
                            <i class="fas fa-check me-2"></i> Confirmar Cita
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Reagendar
                        </button>
                        <button class="btn btn-danger">
                            <i class="fas fa-times me-2"></i> Cancelar
                        </button>
                    <?php elseif($cita['estado'] == 'confirmada'): ?>
                        <button class="btn btn-primary">
                            <i class="fas fa-check-double me-2"></i> Marcar como Completada
                        </button>
                        <button class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Reagendar
                        </button>
                    <?php elseif($cita['estado'] == 'completada'): ?>
                        <button class="btn btn-info">
                            <i class="fas fa-file-invoice me-2"></i> Generar Factura
                        </button>
                    <?php endif; ?>
                    
                    <a href="<?php echo $config['base_url']; ?>citas" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Historial</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Creada</span>
                        <small class="text-muted"><?php echo date('d/m H:i', strtotime($cita['fecha_creacion'])); ?></small>
                    </li>
                    <?php if($cita['estado'] != 'pendiente'): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Confirmada</span>
                            <small class="text-muted">25/12 10:30</small>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>