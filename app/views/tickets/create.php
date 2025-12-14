<?php $page_title = "Nuevo Ticket de Soporte"; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3"><i class="fas fa-plus-circle me-2"></i> Nuevo Ticket</h1>
        <p class="text-muted">Complete el formulario para crear un nuevo ticket de soporte</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-headset me-2"></i> Formulario de Ticket</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $config['base_url']; ?>tickets">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título *</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required
                               placeholder="Ej: Problema con mi computadora al iniciar">
                        <div class="form-text">Describa brevemente el problema</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción detallada *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required
                                  placeholder="Describa el problema con el mayor detalle posible:
1. ¿Qué estaba haciendo cuando ocurrió?
2. ¿Qué mensajes de error aparecen?
3. ¿Desde cuándo ocurre el problema?"></textarea>
                        <div class="form-text">Mientras más detallada sea la descripción, más rápido podremos ayudarle</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de problema *</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="">Seleccione tipo</option>
                                <option value="hardware">Hardware (equipos, componentes)</option>
                                <option value="software">Software (programas, sistema operativo)</option>
                                <option value="general" selected>General (consultas, asesoría)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prioridad" class="form-label">Prioridad *</label>
                            <select class="form-control" id="prioridad" name="prioridad" required>
                                <option value="baja">Baja (puede esperar varios días)</option>
                                <option value="media" selected>Media (resolver en 24-48 horas)</option>
                                <option value="alta">Alta (necesita atención inmediata)</option>
                            </select>
                            <div class="form-text">Seleccione según la urgencia del problema</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i> Información importante</h6>
                        <ul class="mb-0">
                            <li>Nuestro tiempo de respuesta promedio es de 12 horas</li>
                            <li>Puede adjuntar capturas de pantalla después de crear el ticket</li>
                            <li>Mantendremos comunicación por este mismo sistema</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo $config['base_url']; ?>tickets" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-paper-plane me-2"></i> Crear Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>