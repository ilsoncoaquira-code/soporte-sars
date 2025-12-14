// Scripts para Soporte SARS

document.addEventListener('DOMContentLoaded', function() {
    
    // Cerrar automáticamente alerts después de 5 segundos
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Confirmar antes de eliminar
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
    
    // Formatear fechas
    document.querySelectorAll('.format-date').forEach(element => {
        const dateString = element.textContent;
        if (dateString) {
            const date = new Date(dateString);
            element.textContent = date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    });
    
    // Formatear moneda
    document.querySelectorAll('.format-currency').forEach(element => {
        const amount = parseFloat(element.textContent);
        if (!isNaN(amount)) {
            element.textContent = new Intl.NumberFormat('es-PE', {
                style: 'currency',
                currency: 'PEN'
            }).format(amount);
        }
    });
    
    // Validación de formularios
    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    
                    // Crear mensaje de error si no existe
                    if (!input.nextElementSibling?.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Este campo es requerido';
                        input.parentNode.appendChild(errorDiv);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    const errorDiv = input.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Mostrar alerta general
                if (!form.querySelector('.alert-danger')) {
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger mt-3';
                    alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Por favor complete todos los campos requeridos';
                    form.prepend(alert);
                }
            }
        });
    });
    
    // Contador de caracteres en textareas
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const maxLength = parseInt(textarea.getAttribute('maxlength'));
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        counter.textContent = `0/${maxLength}`;
        
        textarea.parentNode.appendChild(counter);
        
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            counter.textContent = `${length}/${maxLength}`;
            
            if (length > maxLength * 0.9) {
                counter.classList.remove('text-muted');
                counter.classList.add('text-warning');
            } else {
                counter.classList.remove('text-warning');
                counter.classList.add('text-muted');
            }
            
            if (length > maxLength) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        });
    });
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Auto-calcular totales en carrito
    document.querySelectorAll('.cart-quantity').forEach(input => {
        input.addEventListener('change', function() {
            const row = this.closest('tr');
            const price = parseFloat(row.querySelector('.unit-price').textContent.replace('S/', ''));
            const quantity = parseInt(this.value);
            const totalCell = row.querySelector('.item-total');
            
            if (!isNaN(price) && !isNaN(quantity)) {
                const total = price * quantity;
                totalCell.textContent = `S/${total.toFixed(2)}`;
                updateCartTotal();
            }
        });
    });
    
    function updateCartTotal() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-total').forEach(cell => {
            const total = parseFloat(cell.textContent.replace('S/', ''));
            if (!isNaN(total)) {
                subtotal += total;
            }
        });
        
        const tax = subtotal * 0.18; // 18% IGV
        const total = subtotal + tax;
        
        document.querySelector('.cart-subtotal').textContent = `S/${subtotal.toFixed(2)}`;
        document.querySelector('.cart-tax').textContent = `S/${tax.toFixed(2)}`;
        document.querySelector('.cart-total').textContent = `S/${total.toFixed(2)}`;
    }
    
    // Filtro en tiempo real en tablas
    document.querySelectorAll('.table-filter').forEach(input => {
        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const table = this.closest('.card').querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
    
    // Modal de confirmación personalizado
    window.confirmModal = function(title, message, callback) {
        const modalHtml = `
            <div class="modal fade" id="confirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-warning" id="confirmButton">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remover modal existente
        const existingModal = document.getElementById('confirmModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Agregar nuevo modal
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
        
        document.getElementById('confirmButton').addEventListener('click', function() {
            modal.hide();
            if (typeof callback === 'function') {
                callback();
            }
        });
    };
    
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inicializar popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});