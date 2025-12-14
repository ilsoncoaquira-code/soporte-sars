<?php $page_title = "Iniciar Sesión"; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <!-- FORMA 1: Acción vacía (funciona siempre) -->
                <form method="POST" action="">
                    
                <!-- FORMA 2: URL específica (si prefieres) -->
                <!-- <form method="POST" action="<?php echo $config['base_url']; ?>index.php?url=/login"> -->
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="admin@soporte.com" autofocus value="admin@soporte.com">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required 
                                   value="admin123">
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
                        </button>
                        <a href="<?php echo $config['base_url']; ?>register" class="btn btn-outline-secondary">
                            <i class="fas fa-user-plus me-2"></i> Crear Nueva Cuenta
                        </a>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <!-- Botones de prueba rápida -->
                <div class="text-center">
                    <p class="text-muted mb-2">Prueba rápida:</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <form method="POST" action="" class="d-inline">
                            <input type="hidden" name="email" value="admin@soporte.com">
                            <input type="hidden" name="password" value="admin123">
                            <button type="submit" class="btn btn-sm btn-success">
                                Admin
                            </button>
                        </form>
                        
                        <form method="POST" action="" class="d-inline">
                            <input type="hidden" name="email" value="cliente@ejemplo.com">
                            <input type="hidden" name="password" value="cliente123">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Cliente
                            </button>
                        </form>
                        
                        <form method="POST" action="" class="d-inline">
                            <input type="hidden" name="email" value="tecnico@soporte.com">
                            <input type="hidden" name="password" value="tecnico123">
                            <button type="submit" class="btn btn-sm btn-warning">
                                Técnico
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Credenciales de prueba</h6>
                    <ul class="mb-0 ps-3">
                        <li><strong>Administrador:</strong> admin@soporte.com / admin123</li>
                        <li><strong>Cliente:</strong> cliente@ejemplo.com / cliente123</li>
                        <li><strong>Técnico:</strong> tecnico@soporte.com / tecnico123</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>