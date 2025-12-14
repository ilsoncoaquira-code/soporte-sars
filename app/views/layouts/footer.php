    </div> <!-- Cierre del container -->

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-tools me-2"></i> Soporte SARS</h6>
                    <p class="mb-0 small">Sistema Integral de Reservas, Ventas y Soporte Técnico</p>
                    <p class="mb-0 small">© 2025 Codecrafters - Ingeniería de Software I</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Contacto</h6>
                    <p class="mb-0 small"><i class="fas fa-envelope me-2"></i> info@soportesars.com</p>
                    <p class="mb-0 small"><i class="fas fa-phone me-2"></i> +51 987 654 321</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
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
                if (!confirm('¿Está seguro de eliminar este registro?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Formato de fecha
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        }
        
        // Formato de moneda
        function formatCurrency(amount) {
            return new Intl.NumberFormat('es-PE', {
                style: 'currency',
                currency: 'PEN'
            }).format(amount);
        }
    </script>
</body>
</html>