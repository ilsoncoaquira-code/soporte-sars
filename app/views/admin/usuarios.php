<?php $page_title = "Gestión de Usuarios"; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3"><i class="fas fa-users me-2"></i> Gestión de Usuarios</h1>
        <p class="text-muted">Administra los usuarios del sistema</p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
            <i class="fas fa-user-plus me-2"></i> Nuevo Usuario
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Usuarios (<?php echo $totalUsuarios; ?>)</h5>
        <div class="input-group" style="width: 300px;">
            <input type="text" class="form-control" placeholder="Buscar usuario...">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($usuarios)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
                <h5>No hay usuarios registrados</h5>
                <p class="text-muted">Crea el primer usuario del sistema</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td>#<?php echo $usuario['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['telefono'] ?? 'No registrado'); ?></td>
                            <td>
                                <?php 
                                $rol_badge = [
                                    'admin' => 'danger',
                                    'tecnico' => 'warning',
                                    'cliente' => 'primary'
                                ][$usuario['rol']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $rol_badge; ?>">
                                    <?php echo ucfirst($usuario['rol']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?><br>
                                <small class="text-muted"><?php echo date('H:i', strtotime($usuario['fecha_registro'])); ?></small>
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
            <nav aria-label="Paginación de usuarios">
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

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i> Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="telefono">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rol *</label>
                        <select class="form-control" name="rol" required>
                            <option value="cliente">Cliente</option>
                            <option value="tecnico">Técnico</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>