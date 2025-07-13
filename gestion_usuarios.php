<?php 
/**
 * Gestión de Usuarios
 * RespaldosChile Admin System
 */

session_start();
if (!isset($_SESSION["s_usuario"]) || $_SESSION["privilegios"] < 20) {
    header("Location: ../login.php");
    exit();
}

// Configuración de la página
$pageTitle = "Gestión de Usuarios - RespaldosChile";
$pageDescription = "Administración de usuarios del sistema, roles y permisos";
$currentModule = "usuarios";

// CSS específico de la página
$pageCSS = '<link rel="stylesheet" href="assets/css/modules/usuarios.css?v=' . time() . '">';

// Scripts específicos de la página
$pageScripts = '<script src="assets/js/gestion-usuarios.js?v=' . time() . '"></script>';

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
                        <i class="fas fa-users-cog me-2 text-primary"></i>
                        Gestión de Usuarios
                    </h1>
                    <p class="page-subtitle">
                        Administración de usuarios del sistema, roles y permisos • Usuario: <strong><?= htmlspecialchars($usuario_actual) ?></strong>
                    </p>
                </div>

                <!-- Tarjetas de estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-users-cog"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-total-usuarios">-</div>
                        <div class="stat-label">Total Usuarios</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-administradores">-</div>
                        <div class="stat-label">Administradores</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon info">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-operadores">-</div>
                        <div class="stat-label">Operadores</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="stat-value" id="stat-activos-hoy">-</div>
                        <div class="stat-label">Activos hoy</div>
                    </div>
                </div>

                <!-- Panel de controles -->
                <div class="control-panel">
                    <div class="control-header">
                        <h3 class="control-title">
                            <i class="fas fa-tools me-2"></i>
                            Herramientas de Administración
                        </h3>
                        <div class="control-actions">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshTable">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                            
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                                <i class="fas fa-user-plus"></i> Nuevo Usuario
                            </button>
                            
                            <button type="button" class="btn btn-outline-info btn-sm" id="btnAuditLog">
                                <i class="fas fa-history"></i> Log de Auditoría
                            </button>
                            
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnExportUsuarios">
                                <i class="fas fa-download"></i> Exportar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla principal -->
                <div class="table-container">
                    <div class="table-wrapper">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Tabla de Usuarios del Sistema</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTableUsers" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>RUT</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th width="140">Acciones</th>
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
    include 'views/modal_nuevo_usuario.php';
    include 'views/modal_editar_usuario.php';
    ?>

<?php 
// Incluir footer centralizado
include 'views/footer.php'; 
?>