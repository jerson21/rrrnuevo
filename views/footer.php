<?php
/**
 * Footer centralizado con scripts para todas las páginas del admin
 * RespaldosChile Admin System
 */

// Variables por defecto si no están definidas
$includeDataTables = $includeDataTables ?? true;
$pageScripts = $pageScripts ?? '';
$debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';
?>

    <!-- Scripts Core -->
    
    <!-- jQuery 3.7.0 -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" 
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    
    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <?php if ($includeDataTables): ?>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <?php endif; ?>
    
    <!-- Scripts del sistema (en orden de dependencias) -->
    <script src="assets/js/shared/constants.js"></script>
    <script src="assets/js/shared/ui-components.js"></script>
    <script src="assets/js/shared/api-client.js"></script>
    
    <!-- Script de inicialización global -->
    <script>
        $(document).ready(function() {
            // Configuración global de la página
            window.PAGE_CONFIG = {
                usuario: '<?= htmlspecialchars($_SESSION["s_usuario"] ?? '') ?>',
                privilegios: <?= intval($_SESSION["privilegios"] ?? 0) ?>,
                modulo: '<?= $currentModule ?? 'admin' ?>'
            };
            
            // Configurar sidebar toggle
            $('#menu-toggle').on('click', function() {
                $('#content-wrapper').toggleClass('expanded');
                $('.sidebar').toggleClass('collapsed');
            });
            
            // Configurar tooltips globales
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Configurar popovers globales
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            console.log('✅ Sistema inicializado correctamente');
        });
        
        // Manejo de errores globales
        window.addEventListener('error', function(e) {
            console.error('Error global capturado:', e.error);
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast('Se produjo un error inesperado', 'error');
            }
        });
        
        // Manejo de promesas rechazadas
        window.addEventListener('unhandledrejection', function(e) {
            console.error('Promise rechazada no manejada:', e.reason);
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast('Error de conexión o procesamiento', 'error');
            }
        });
        
        // Detectar cambios de conectividad
        window.addEventListener('online', function() {
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast('Conexión restablecida', 'success');
            }
        });
        
        window.addEventListener('offline', function() {
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast('Sin conexión a internet', 'warning');
            }
        });
    </script>
    
    <!-- Scripts específicos de la página - AHORA AL FINAL -->
    <?= $pageScripts ?>
    
    <?php if ($debugMode): ?>
    <!-- Scripts de debug (solo en desarrollo) -->
    <script>
        console.log('🔧 Modo DEBUG activado');
        console.log('Usuario:', '<?= htmlspecialchars($_SESSION["s_usuario"] ?? '') ?>');
        console.log('Privilegios:', <?= intval($_SESSION["privilegios"] ?? 0) ?>);
        
        // Log de eventos DataTables para debugging
        $(document).on('init.dt', function(e, settings) {
            console.log('DataTable inicializada:', settings.sTableId);
        });
        
        $(document).on('xhr.dt', function(e, settings, json, xhr) {
            console.log('Datos recibidos de API:', json);
        });
        
        // Debug del modal
        $(document).on('shown.bs.modal', function(e) {
            console.log('Modal mostrado:', e.target.id);
        });
        
        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const perfData = performance.timing;
                    const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                    console.log('🚀 Tiempo de carga de página:', pageLoadTime + 'ms');
                }, 0);
            });
        }
    </script>
    <?php endif; ?>

</body>
</html>