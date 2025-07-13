<?php 
/**
 * Dashboard Principal - Gestión de Pedidos
 * RespaldosChile Admin System v2.0
 */

session_start();
if (!isset($_SESSION["s_usuario"]) || $_SESSION["privilegios"] < 4) {
    header("Location: ../login.php");
    exit();
}

// Configuración de la página
$pageTitle = "Gestión de Pedidos - RespaldosChile";
$pageDescription = "Sistema completo de seguimiento y gestión de pedidos";
$currentModule = "dashboard";

// CSS específico del dashboard
$pageCSS = '<link rel="stylesheet" href="assets/css/modules/dashboard-pedidos.css?v=' . time() . '">';

$pageScripts = '
<script src="assets/js/modules/dashboard/pedidos-ingresados.js?v=' . time() . '"></script>
<script src="assets/js/modules/dashboard/modal-historial-orden.js?v=' . time() . '"></script>';

// Variables para el header
$usuario_actual = $_SESSION["s_usuario"];
$privilegios = $_SESSION["privilegios"];

// Incluir header centralizado
include 'views/header.php';
?>

<body>
    <div class="main-wrapper">
        <!-- Incluir Sidebar -->
        <?php include 'views/sidebar.php'; ?>
        
        <div class="content-wrapper" id="content-wrapper">
            <!-- Incluir Topbar -->
            <?php include 'views/topbar.php'; ?>
            
            <main class="page-content">
                
                <!-- Header de la página -->
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        Gestión de Pedidos Ingresados
                    </h1>
                    <p class="page-subtitle">
                        Sistema completo de seguimiento y gestión de pedidos • Usuario: <strong><?= htmlspecialchars($usuario_actual) ?></strong>
                    </p>
                </div>
                
                <!-- Tarjetas de estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-total">-</div>
                        <div class="stat-label">Total Pedidos</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-pendientes">-</div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon info">
                                <i class="fas fa-cogs"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-fabricando">-</div>
                        <div class="stat-label">Fabricando</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-entregados">-</div>
                        <div class="stat-label">Entregados</div>
                    </div>
                </div>
                
                <!-- Panel de controles -->
                <div class="control-panel">
                    <div class="control-header">
                        <h3 class="control-title">
                            <i class="fas fa-sliders-h me-2"></i>
                            Controles de Vista
                        </h3>
                        <div class="control-actions">
                            <div class="toggle-container">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="alwaysExpandDetails">
                                    <label class="form-check-label" for="alwaysExpandDetails">
                                        Mantener detalles expandidos
                                    </label>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshTable">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                            
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnToggleAll">
                                <i class="fas fa-expand-arrows-alt"></i> Expandir Todo
                            </button>
                            
                            <?php if ($privilegios >= 20): ?>
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnExportData">
                                <i class="fas fa-download"></i> Exportar
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Tabla principal -->
                <div class="table-container">
                    <div class="table-wrapper">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Gestión de Pedidos</h6>
                            <div class="header-actions">
                                <span class="badge bg-primary" id="badge-total-rows">0 registros</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="pedidosTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Estado</th>
                                        <th>RUT Cliente</th>
                                        <th>Cliente</th>
                                        <th>Dirección</th>
                                        <th>Contacto</th>
                                        <th>Total</th>
                                        <th>Forma Pago</th>
                                        <th width="140">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos se cargan vía AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </main>
        </div>
    </div>
    
    <!-- Modales -->
    <?php 
    include 'views/modal_gestion_orden.php';
    include 'views/modal_editar_producto.php';
    ?>

   <!-- Script de inicialización -->
<script>
// Esperar a que jQuery esté disponible
(function waitForjQuery() {
    if (typeof $ !== 'undefined' && typeof jQuery !== 'undefined') {
        $(document).ready(function() {
            // Variables globales de la página
            window.PAGE_CONFIG = {
                usuario: '<?= htmlspecialchars($usuario_actual) ?>',
                privilegios: <?= intval($privilegios) ?>,
                modulo: 'dashboard'
            };
            
            // Verificar que los modales estén disponibles después de cargar
            setTimeout(function() {
                const modalGestion = typeof abrirModalGestionOrden === 'function';
                const modalProducto = typeof abrirModalEditarProducto === 'function';
                
                if (modalGestion && modalProducto) {
                    console.log('✅ Modales correctamente cargados:', {
                        gestionOrden: modalGestion,
                        editarProducto: modalProducto
                    });
                } else {
                    console.warn('⚠️ Algunos modales no están disponibles:', {
                        gestionOrden: modalGestion,
                        editarProducto: modalProducto
                    });
                }
            }, 1000);
            
            console.log('✅ Dashboard v2.0 inicializado correctamente');
        });
    } else {
        // jQuery no está listo, reintentar en 50ms
        setTimeout(waitForjQuery, 50);
    }
})();
</script>

<?php 
// Incluir footer centralizado
include 'views/footer.php'; 
?>