<?php $page_title = $producto['nombre']; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo $config['base_url']; ?>productos">
                        <i class="fas fa-store me-1"></i> Productos
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo htmlspecialchars($producto['nombre']); ?>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <?php if(!empty($producto['imagen_url'])): ?>
                    <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                         class="img-fluid rounded" 
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                         style="max-height: 400px;">
                <?php else: ?>
                    <div class="bg-light rounded py-5">
                        <i class="fas fa-box fa-6x text-secondary"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <?php if(!empty($producto['categoria'])): ?>
                    <span class="badge bg-info mb-3"><?php echo htmlspecialchars($producto['categoria']); ?></span>
                <?php endif; ?>
                
                <h1 class="h3 mb-3"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                
                <div class="mb-4">
                    <h2 class="text-success">S/ <?php echo number_format($producto['precio'], 2); ?></h2>
                    
                    <?php if($producto['stock'] > 0): ?>
                        <p class="text-success mb-1">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Disponible</strong> - <?php echo $producto['stock']; ?> unidades en stock
                        </p>
                        <small class="text-muted">¡Últimas unidades disponibles!</small>
                    <?php else: ?>
                        <p class="text-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Agotado</strong>
                        </p>
                        <small class="text-muted">Próximamente tendremos más stock</small>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($producto['descripcion'])): ?>
                    <div class="mb-4">
                        <h5 class="mb-2"><i class="fas fa-file-alt me-2"></i> Descripción</h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="mb-4">
                    <h5 class="mb-2"><i class="fas fa-shipping-fast me-2"></i> Información de envío</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-check text-success me-2"></i> Envío gratuito a todo el país</li>
                        <li><i class="fas fa-check text-success me-2"></i> Entrega en 2-5 días hábiles</li>
                        <li><i class="fas fa-check text-success me-2"></i> Garantía de 12 meses</li>
                    </ul>
                </div>
                
                <div class="border-top pt-3">
                    <?php if($producto['stock'] > 0): ?>
                        <form method="POST" action="<?php echo $config['base_url']; ?>cart/add/<?php echo $producto['id']; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label for="cantidad" class="col-form-label">Cantidad:</label>
                                </div>
                                <div class="col-auto">
                                    <div class="input-group" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                        <input type="number" class="form-control text-center" 
                                               id="cantidad" name="cantidad" value="1" 
                                               min="1" max="<?php echo $producto['stock']; ?>">
                                        <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-cart-plus me-2"></i> Añadir al carrito
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="fas fa-cart-plus me-2"></i> Producto agotado
                        </button>
                        <div class="mt-2">
                            <button class="btn btn-outline-primary w-100">
                                <i class="fas fa-bell me-2"></i> Notificarme cuando esté disponible
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="<?php echo $config['base_url']; ?>productos" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i> Seguir comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Características -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i> Especificaciones</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Categoría</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($producto['categoria'] ?? 'General'); ?></dd>
                    
                    <dt class="col-sm-4">SKU</dt>
                    <dd class="col-sm-8">PROD-<?php echo str_pad($producto['id'], 5, '0', STR_PAD_LEFT); ?></dd>
                    
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <?php if($producto['activo']): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </dd>
                    
                    <dt class="col-sm-4">Garantía</dt>
                    <dd class="col-sm-8">12 meses</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
    function increaseQuantity() {
        const input = document.getElementById('cantidad');
        const max = parseInt(input.max);
        let value = parseInt(input.value);
        if (value < max) {
            input.value = value + 1;
        }
    }
    
    function decreaseQuantity() {
        const input = document.getElementById('cantidad');
        const min = parseInt(input.min);
        let value = parseInt(input.value);
        if (value > min) {
            input.value = value - 1;
        }
    }
</script>