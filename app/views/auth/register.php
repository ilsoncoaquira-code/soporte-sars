<?php $page_title = "Registro de Usuario"; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Crear Nueva Cuenta</h4>
            </div>
            <div class="card-body">
                <!-- CAMBIA ESTO: action vacío en lugar de URL completa -->
                <form method="POST" action="">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre Completo *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-text">Este será tu usuario para ingresar al sistema</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i> Registrar Cuenta
                        </button>
                        <a href="<?php echo $config['base_url']; ?>login" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i> Ya tengo una cuenta
                        </a>
                    </div>
                </form>
                
                <!-- OPCIÓN: Botón de prueba rápida -->
                <hr class="my-3">
                <div class="text-center">
                    <p class="text-muted mb-2">¿Solo quieres probar?</p>
                    <form method="POST" action="">
                        <input type="hidden" name="nombre" value="Usuario Prueba">
                        <input type="hidden" name="email" value="prueba@test.com">
                        <input type="hidden" name="password" value="prueba123">
                        <input type="hidden" name="telefono" value="999888777">
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            Crear cuenta de prueba
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>