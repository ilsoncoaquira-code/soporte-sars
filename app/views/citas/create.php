<?php $page_title = "Nueva Cita"; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3"><i class="fas fa-calendar-plus me-2"></i> Agendar Nueva Cita</h1>
        <p class="text-muted">Complete el formulario para agendar un servicio</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i> Formulario de Cita</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $config['base_url']; ?>citas">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="servicio_id" class="form-label">Servicio *</label>
                            <select class="form-control" id="servicio_id" name="servicio_id" required>
                                <option value="">Seleccione un servicio</option>
                                <?php foreach($servicios as $servicio): ?>
                                    <option value="<?php echo $servicio['id']; ?>">
                                        <?php echo htmlspecialchars($servicio['nombre']); ?> 
                                        - S/ <?php echo number_format($servicio['precio'], 2); ?>
                                        <?php if($servicio['duracion']): ?>
                                            (<?php echo $servicio['duracion']; ?> min)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Seleccione el servicio que requiere</div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" 
                                   required>
                            <div class="form-text">Disponible en los próximos 30 días</div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="hora" class="form-label">Hora *</label>
                            <select class="form-control" id="hora" name="hora" required>
                                <option value="">Seleccione hora</option>
                                <?php 
                                // Horario de atención: 9AM a 6PM
                                for($h = 9; $h <= 18; $h++): 
                                    $hora_str = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                                ?>
                                    <option value="<?php echo $hora_str; ?>"><?php echo $hora_str; ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="form-text">Horario de atención: 9:00 - 18:00</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="4" 
                                  placeholder="Describa brevemente el problema o necesidad específica..."></textarea>
                        <div class="form-text">Opcional: información adicional para el técnico</div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i> Información importante</h6>
                        <ul class="mb-0">
                            <li>La cita será confirmada dentro de las 24 horas</li>
                            <li>Puede cancelar o reagendar con 24 horas de anticipación</li>
                            <li>Llegue 10 minutos antes de su cita programada</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $config['base_url']; ?>citas" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calendar-check me-2"></i> Agendar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>