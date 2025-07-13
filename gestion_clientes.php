<?php 
/**
 * Gestión de Clientes
 * RespaldosChile Admin System
 */

session_start();
if (!isset($_SESSION["s_usuario"]) || $_SESSION["privilegios"] < 4) {
    header("Location: ../login.php");
    exit();
}

// Configuración de la página
$pageTitle = "Gestión de Clientes - RespaldosChile";
$pageDescription = "Administración de la base de datos de clientes";
$currentModule = "clientes";

// CSS específico de la página
$pageCSS = '<link rel="stylesheet" href="assets/css/modules/clientes.css?v=' . time() . '">';

// Scripts específicos de la página
$pageScripts = '<script src="assets/js/gestion-clientes.js?v=' . time() . '"></script>';

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
                        <i class="fas fa-users me-2 text-primary"></i>
                        Gestión de Clientes
                    </h1>
                    <p class="page-subtitle">
                        Administración de la base de datos de clientes • Usuario: <strong><?= htmlspecialchars($usuario_actual) ?></strong>
                    </p>
                </div>

                <!-- Tarjetas de estadísticas (opcional para clientes) -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-total-clientes">-</div>
                        <div class="stat-label">Total Clientes</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-clientes-activos">-</div>
                        <div class="stat-label">Activos</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon info">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-nuevos-mes">-</div>
                        <div class="stat-label">Nuevos este mes</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-con-pedidos">-</div>
                        <div class="stat-label">Con Pedidos</div>
                    </div>
                </div>

                <!-- Panel de controles -->
                <div class="control-panel">
                    <div class="control-header">
                        <h3 class="control-title">
                            <i class="fas fa-filter me-2"></i>
                            Filtros y Acciones
                        </h3>
                        <div class="control-actions">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshTable">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                            
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                                <i class="fas fa-plus"></i> Nuevo Cliente
                            </button>
                            
                            <?php if ($privilegios >= 20): ?>
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnExportClientes">
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
                            <h6 class="m-0 font-weight-bold text-primary">Tabla de Clientes</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTableClientes" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>RUT</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Instagram</th>
                                        <th>Correo</th>
                                        <th width="120">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos se cargan vía JavaScript -->
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
    include 'views/modal_nuevo_cliente.php';
    include 'views/modal_editar_cliente.php';
    ?>

<?php 
// Incluir footer centralizado
include 'views/footer.php'; 
?>