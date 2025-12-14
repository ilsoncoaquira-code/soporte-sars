<?php $page_title = "Carrito de Compras"; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3"><i class="fas fa-shopping-cart me-2"></i> Carrito de Compras</h1>
        <p class="text-muted">Revisa y modifica los productos en tu carrito</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo $config['base_url']; ?>productos" class="btn btn-outline-primary">
            <i class="fas fa-store me-2"></i> Seguir comprando
        </a>
    </div>
</div>

<?php if(empty($cartItems)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h5>Tu carrito está vacío</h5>
            <p class="text-muted">Agrega productos para comenzar tu compra</p>
            <a href="<?php echo $config['base_url']; ?>productos" class="btn btn-primary mt-2">
                <i class="fas fa-store me-2"></i> Ir al catálogo
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Productos en el carrito (<?php echo count($cartItems); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php foreach($cartItems as $item): ?>
                    <div class="row mb-4 pb-4 border-bottom">
                        <div class="col-md-3">
                            <?php if(!empty($item['imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($item['imagen']); ?>" 
                                     class="img-fluid rounded" 
                                     alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                            <?php else: ?>
                                <div class="bg-light rounded py-4 text-center">
                                    <i class="fas fa-box fa-3x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-1"><?php echo htmlspecialchars($item['nombre']); ?></h5>
                            <p class="text-muted mb-2">SKU: PROD-<?php echo str_pad($item['id'], 5, '0', STR_PAD_LEFT); ?></p>
                            
                            <div class="d-flex align-items-center">
                                <form method="POST" action="<?php echo $config['base_url']; ?>cart/add/<?php echo $item['id']; ?>" 
                                      class="me-3" style="width: 120px;">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" name="cantidad" 
                                               value="<?php echo $item['cantidad']; ?>" 
                                               min="1" max="<?php echo $item['stock']; ?>">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </form>
                                
                                <a href="<?php echo $config['base_url']; ?>cart/remove/<?php echo $item['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-3 text-end">
                            <h5 class="text-success">S/ <?php echo number_format($item['precio'], 2); ?></h5>
                            <p class="mb-1">x <?php echo $item['cantidad']; ?> unidades</p>
                            <h5 class="text-primary">S/ <?php echo number_format($item['subtotal'], 2); ?></h5>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Resumen de compra</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-3">
                        <dt class="col-6">Subtotal</dt>
                        <dd class="col-6 text-end">S/ <?php echo number_format($total, 2); ?></dd>
                        
                        <dt class="col-6">Envío</dt>
                        <dd class="col-6 text-end text-success">Gratis</dd>
                        
                        <dt class="col-6">Descuento</dt>
                        <dd class="col-6 text-end text-danger">S/ 0.00</dd>
                    </dl>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0">Total</h5>
                        <h3 class="text-primary mb-0">S/ <?php echo number_format($total, 2); ?></h3>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <form method="POST" action="<?php echo $config['base_url']; ?>cart/checkout">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-lock me-2"></i> Proceder al pago
                            </button>
                        </form>
                        
                        <a href="<?php echo $config['base_url']; ?>cart" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar carrito
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <h6><i class="fas fa-shield-alt me-2"></i> Compra segura</h6>
                        <small class="text-muted">
                            <i class="fas fa-lock text-success me-1"></i>
                            Tu información está protegida con encriptación SSL
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="fas fa-truck me-2"></i> Envío gratuito</h6>
                    <small class="text-muted">Todos los pedidos tienen envío gratis a nivel nacional</small>
                    
                    <h6 class="mt-3"><i class="fas fa-undo me-2"></i> Devoluciones</h6>
                    <small class="text-muted">30 días para cambios o devoluciones</small>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>