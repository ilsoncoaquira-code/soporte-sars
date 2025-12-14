<?php $page_title = "Gestión de Productos"; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3"><i class="fas fa-boxes me-2"></i> Gestión de Productos</h1>
        <p class="text-muted">Administra el catálogo de productos</p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
            <i class="fas fa-plus me-2"></i> Nuevo Producto
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Productos (<?php echo $totalProductos; ?>)</h5>
        <div class="d-flex gap-2">
            <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Buscar producto...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <select class="form-control" style="width: 150px;">
                <option>Todos</option>
                <option>Activos</option>
                <option>Inactivos</option>
                <option>Sin stock</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($productos)): ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h5>No hay productos registrados</h5>
                <p class="text-muted">Agrega productos al catálogo</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($productos as $producto): ?>
                        <tr>
                            <td>#<?php echo $producto['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if(!empty($producto['imagen_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                             class="rounded me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo strlen($producto['descripcion'] ?? '') > 50 ? 
                                                  substr($producto['descripcion'], 0, 50) . '...' : 
                                                  ($producto['descripcion'] ?? 'Sin descripción'); ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($producto['categoria'] ?? 'General'); ?></td>
                            <td>S/ <?php echo number_format($producto['precio'], 2); ?></td>
                            <td>
                                <?php if($producto['stock'] > 10): ?>
                                    <span class="badge bg-success"><?php echo $producto['stock']; ?></span>
                                <?php elseif($producto['stock'] > 0): ?>
                                    <span class="badge bg-warning"><?php echo $producto['stock']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Agotado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($producto['activo']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <?php if($totalPaginas > 1): ?>
            <nav aria-label="Paginación de productos">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $paginaActual == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $paginaActual - 1; ?>">Anterior</a>
                    </li>
                    
                    <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo $paginaActual == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $paginaActual == $totalPaginas ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Nuevo Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" class="form-control" name="categoria" 
                                   placeholder="Ej: Periféricos, Componentes, Software">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" class="form-control" name="precio" step="0.01" min="0" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock inicial</label>
                            <input type="number" class="form-control" name="stock" min="0" value="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">URL de imagen</label>
                        <input type="url" class="form-control" name="imagen_url" 
                               placeholder="https://ejemplo.com/imagen.jpg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>