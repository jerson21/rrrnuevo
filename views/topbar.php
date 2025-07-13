<?php
// Obtener información del usuario y configuración
$usuario = $_SESSION["s_usuario"] ?? 'Usuario';
$privilegios = $_SESSION["privilegios"] ?? 0;
$avatar_url = $_SESSION["avatar"] ?? '';

// Obtener información de la página actual
$page_title = '';
$page_breadcrumbs = [];

switch (basename($_SERVER['PHP_SELF'], '.php')) {
    case 'dashboard-pedidos':
        $page_title = 'Gestión de Pedidos';
        $page_breadcrumbs = ['Dashboard', 'Pedidos Ingresados'];
        break;
    case 'validar-pagos':
        $page_title = 'Validación de Pagos';
        $page_breadcrumbs = ['Pedidos', 'Validar Pagos'];
        break;
    case 'venta-sala':
        $page_title = 'Venta en Sala';
        $page_breadcrumbs = ['Ventas', 'Venta en Sala'];
        break;
    case 'produccion':
        $page_title = 'Control de Producción';
        $page_breadcrumbs = ['Producción', 'Control'];
        break;
    default:
        $page_title = 'RespaldosChile';
        $page_breadcrumbs = ['Dashboard'];
}

// Configuración de notificaciones (esto vendría de la base de datos)
$notifications_count = 0; // Se actualizará vía JavaScript
$quick_actions = [
    [
        'title' => 'Nuevo Pedido',
        'icon' => 'fas fa-plus',
        'url' => 'nuevo-pedido.php',
        'color' => 'primary',
        'privilege' => 4
    ],
    [
        'title' => 'Buscar Cliente',
        'icon' => 'fas fa-search',
        'url' => '#',
        'color' => 'info',
        'privilege' => 4,
        'modal' => 'buscarCliente'
    ],
    [
        'title' => 'Reporte Diario',
        'icon' => 'fas fa-chart-line',
        'url' => 'reporte-diario.php',
        'color' => 'success',
        'privilege' => 20
    ]
];
?>

<!-- Topbar Navigation -->
<header class="topbar" id="topbar">
    <div class="topbar-container">
        
        <!-- Left Section -->
        <div class="topbar-left">
            <!-- Menu Toggle -->
            <button type="button" class="menu-toggle" id="menu-toggle" title="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <ol class="breadcrumb mb-0">
                    <?php foreach ($page_breadcrumbs as $index => $breadcrumb): ?>
                        <li class="breadcrumb-item <?= $index === count($page_breadcrumbs) - 1 ? 'active' : '' ?>">
                            <?php if ($index === count($page_breadcrumbs) - 1): ?>
                                <?= htmlspecialchars($breadcrumb) ?>
                            <?php else: ?>
                                <a href="#" class="breadcrumb-link"><?= htmlspecialchars($breadcrumb) ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            
            <!-- Page Title (visible en móviles) -->
            <h1 class="page-title-mobile d-md-none">
                <?= htmlspecialchars($page_title) ?>
            </h1>
        </div>
        
        <!-- Center Section -->
        <div class="topbar-center">
            <!-- Search Bar -->
            <div class="search-container">
                <form class="search-form" id="globalSearchForm">
                    <div class="input-group">
                        <span class="input-group-text search-icon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control search-input" 
                               id="globalSearch"
                               placeholder="Buscar pedidos, clientes, productos..."
                               autocomplete="off">
                        <button type="button" class="btn btn-outline-secondary search-filter" 
                                data-bs-toggle="dropdown" aria-expanded="false" title="Filtros de búsqueda">
                            <i class="fas fa-filter"></i>
                        </button>
                        
                        <!-- Dropdown de filtros -->
                        <ul class="dropdown-menu dropdown-menu-end search-filters">
                            <li><h6 class="dropdown-header">Buscar en:</h6></li>
                            <li>
                                <a class="dropdown-item" href="#" data-filter="pedidos">
                                    <i class="fas fa-clipboard-list me-2"></i>Pedidos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-filter="clientes">
                                    <i class="fas fa-users me-2"></i>Clientes
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-filter="productos">
                                    <i class="fas fa-box me-2"></i>Productos
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-filter="todo">
                                    <i class="fas fa-globe me-2"></i>Todo
                                </a>
                            </li>
                        </ul>
                    </div>
                </form>
                
                <!-- Search Results -->
                <div class="search-results" id="searchResults">
                    <!-- Se llena dinámicamente -->
                </div>
            </div>
        </div>
        
        <!-- Right Section -->
        <div class="topbar-right">
            
            <!-- Quick Actions -->
            <div class="quick-actions d-none d-lg-flex">
                <?php foreach ($quick_actions as $action): ?>
                    <?php if ($privilegios >= $action['privilege']): ?>
                        <button type="button" 
                                class="btn btn-outline-<?= $action['color'] ?> btn-sm quick-action-btn"
                                <?= isset($action['modal']) ? 'data-bs-toggle="modal" data-bs-target="#modal' . $action['modal'] . '"' : 'onclick="window.location.href=\'' . $action['url'] . '\'"' ?>
                                title="<?= htmlspecialchars($action['title']) ?>">
                            <i class="<?= $action['icon'] ?>"></i>
                            <span class="d-none d-xl-inline ms-1"><?= htmlspecialchars($action['title']) ?></span>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Notifications -->
            <div class="dropdown notifications-dropdown">
                <button type="button" 
                        class="btn btn-link notification-toggle" 
                        id="notificationsDropdown"
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        title="Notificaciones">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </button>
                
                <div class="dropdown-menu dropdown-menu-end notifications-menu" aria-labelledby="notificationsDropdown">
                    <div class="notifications-header">
                        <h6 class="mb-0">Notificaciones</h6>
                        <button type="button" class="btn btn-sm btn-link mark-all-read" id="markAllRead">
                            Marcar todas como leídas
                        </button>
                    </div>
                    
                    <div class="notifications-list" id="notificationsList">
                        <div class="notification-item text-center text-muted py-3">
                            <i class="fas fa-bell-slash mb-2"></i>
                            <div>No hay notificaciones</div>
                        </div>
                    </div>
                    
                    <div class="notifications-footer">
                        <a href="notificaciones.php" class="btn btn-sm btn-primary w-100">
                            Ver todas las notificaciones
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="dropdown settings-dropdown">
                <button type="button" 
                        class="btn btn-link settings-toggle" 
                        id="settingsDropdown"
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        title="Configuración">
                    <i class="fas fa-cog"></i>
                </button>
                
                <div class="dropdown-menu dropdown-menu-end settings-menu" aria-labelledby="settingsDropdown">
                    <h6 class="dropdown-header">Configuración</h6>
                    
                    <!-- Theme Toggle -->
                    <div class="dropdown-item theme-toggle">
                        <div class="d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-moon me-2"></i>Modo Oscuro
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="darkModeToggle">
                            </div>
                        </div>
                    </div>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a class="dropdown-item" href="configuracion.php">
                        <i class="fas fa-cogs me-2"></i>Configuración del Sistema
                    </a>
                    
                    <?php if ($privilegios >= 21): ?>
                    <a class="dropdown-item" href="logs.php">
                        <i class="fas fa-file-alt me-2"></i>Logs del Sistema
                    </a>
                    <?php endif; ?>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a class="dropdown-item" href="ayuda.php">
                        <i class="fas fa-question-circle me-2"></i>Ayuda y Soporte
                    </a>
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="dropdown user-dropdown">
                <button type="button" 
                        class="btn btn-link user-toggle" 
                        id="userDropdown"
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php if ($avatar_url): ?>
                                <img src="<?= htmlspecialchars($avatar_url) ?>" alt="Avatar" class="avatar-img">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="user-details d-none d-md-block">
                            <div class="user-name"><?= htmlspecialchars($usuario) ?></div>
                            <div class="user-status">En línea</div>
                        </div>
                        <i class="fas fa-chevron-down user-arrow d-none d-md-inline"></i>
                    </div>
                </button>
                
                <div class="dropdown-menu dropdown-menu-end user-menu" aria-labelledby="userDropdown">
                    <div class="user-menu-header">
                        <div class="user-avatar-large">
                            <?php if ($avatar_url): ?>
                                <img src="<?= htmlspecialchars($avatar_url) ?>" alt="Avatar" class="avatar-img">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="user-info-detailed">
                            <div class="user-name"><?= htmlspecialchars($usuario) ?></div>
                            <div class="user-email"><?= htmlspecialchars($_SESSION['email'] ?? 'usuario@respaldoschile.cl') ?></div>
                            <div class="user-role">
                                <?php
                                $roles = [
                                    0 => 'Tapicero',
                                    4 => 'Vendedor', 
                                    5 => 'Supervisor',
                                    20 => 'Administrador',
                                    21 => 'Super Admin'
                                ];
                                echo $roles[$privilegios] ?? 'Usuario';
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a class="dropdown-item" href="perfil.php">
                        <i class="fas fa-user me-2"></i>Mi Perfil
                    </a>
                    
                    <a class="dropdown-item" href="configuracion-personal.php">
                        <i class="fas fa-user-cog me-2"></i>Configuración Personal
                    </a>
                    
                    <a class="dropdown-item" href="mi-actividad.php">
                        <i class="fas fa-history me-2"></i>Mi Actividad
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <a class="dropdown-item text-danger" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </a>
                </div>
            </div>
            
        </div>
        
    </div>
</header>

<!-- Estilos del Topbar -->
<style>
.topbar {
    background: white;
    height: var(--topbar-height);
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 900;
}

.topbar-container {
    height: 100%;
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

/* Left Section */
.topbar-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.menu-toggle {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #64748b;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.menu-toggle:hover {
    background-color: #f1f5f9;
    color: #1e293b;
}

.breadcrumb-nav {
    display: none;
}

@media (min-width: 768px) {
    .breadcrumb-nav {
        display: block;
    }
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
}

.breadcrumb-item {
    color: #64748b;
}

.breadcrumb-item.active {
    color: #1e293b;
    font-weight: 600;
}

.breadcrumb-link {
    color: #3b82f6;
    text-decoration: none;
}

.breadcrumb-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.page-title-mobile {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

/* Center Section */
.topbar-center {
    flex: 1;
    max-width: 600px;
    margin: 0 auto;
}

.search-container {
    position: relative;
    width: 100%;
}

.search-form {
    width: 100%;
}

.search-input {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    background-color: #f9fafb;
    transition: all 0.2s ease;
}

.search-input:focus {
    background-color: white;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
    background-color: transparent;
    border-right: none;
    color: #6b7280;
}

.search-filter {
    border-left: none;
    border-color: #d1d5db;
}

.search-filters {
    min-width: 200px;
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.search-results.show {
    display: block;
}

/* Right Section */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.quick-actions {
    display: flex;
    gap: 0.5rem;
    margin-right: 1rem;
}

.quick-action-btn {
    border-radius: 6px;
    font-size: 0.8125rem;
    padding: 0.375rem 0.75rem;
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    transform: translateY(-1px);
}

/* Notifications */
.notification-toggle,
.settings-toggle,
.user-toggle {
    background: none;
    border: none;
    color: #64748b;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    position: relative;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.notification-toggle:hover,
.settings-toggle:hover,
.user-toggle:hover {
    background-color: #f1f5f9;
    color: #1e293b;
}

.notification-badge {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    background-color: #ef4444;
    color: white;
    font-size: 0.625rem;
    padding: 0.125rem 0.375rem;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    font-weight: 600;
}

.notifications-menu,
.settings-menu,
.user-menu {
    min-width: 300px;
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
}

.notifications-header {
    padding: 1rem;
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: between;
}

.notifications-header h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 600;
    flex: 1;
}

.mark-all-read {
    padding: 0;
    font-size: 0.75rem;
    color: #3b82f6;
    text-decoration: none;
}

.mark-all-read:hover {
    text-decoration: underline;
}

.notifications-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f8fafc;
}

.notifications-footer {
    padding: 0.75rem;
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

/* Settings Menu */
.theme-toggle {
    cursor: pointer;
}

.theme-toggle:hover {
    background-color: #f8fafc;
}

/* User Menu */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(45deg, #3b82f6, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    overflow: hidden;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-details {
    text-align: left;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.2;
}

.user-status {
    font-size: 0.75rem;
    color: #10b981;
    line-height: 1.2;
}

.user-arrow {
    font-size: 0.75rem;
    color: #6b7280;
    transition: transform 0.2s ease;
}

.user-toggle[aria-expanded="true"] .user-arrow {
    transform: rotate(180deg);
}

.user-menu-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    text-align: center;
}

.user-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    overflow: hidden;
}

.user-info-detailed .user-name {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.user-email {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.8);
    margin-bottom: 0.25rem;
}

.user-role {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Responsive */
@media (max-width: 768px) {
    .topbar-container {
        padding: 0 1rem;
    }
    
    .topbar-center {
        order: 3;
        flex-basis: 100%;
        margin-top: 0.5rem;
    }
    
    .quick-actions {
        display: none;
    }
    
    .user-details {
        display: none;
    }
    
    .notifications-menu,
    .settings-menu,
    .user-menu {
        min-width: 250px;
    }
}

@media (max-width: 576px) {
    .search-input {
        font-size: 16px; /* Evitar zoom en iOS */
    }
}

/* Animaciones */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-menu.show {
    animation: slideDown 0.2s ease;
}

/* Loading states */
.topbar-loading {
    position: relative;
}

.topbar-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    animation: loading 2s linear infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
</style>

<!-- JavaScript del Topbar -->


<!-- Estilos adicionales para modo oscuro -->
<style>
.dark-mode .topbar {
    background: #1e293b;
    border-bottom-color: #374151;
}

.dark-mode .search-input {
    background-color: #374151;
    border-color: #4b5563;
    color: #f9fafb;
}

.dark-mode .search-input:focus {
    background-color: #4b5563;
    border-color: #3b82f6;
}

.dark-mode .breadcrumb-item {
    color: #9ca3af;
}

.dark-mode .breadcrumb-item.active {
    color: #f9fafb;
}

.dark-mode .notification-toggle,
.dark-mode .settings-toggle,
.dark-mode .user-toggle {
    color: #9ca3af;
}

.dark-mode .notification-toggle:hover,
.dark-mode .settings-toggle:hover,
.dark-mode .user-toggle:hover {
    background-color: #374151;
    color: #f9fafb;
}

.dark-mode .menu-toggle {
    color: #9ca3af;
}

.dark-mode .menu-toggle:hover {
    background-color: #374151;
    color: #f9fafb;
}

.dark-mode .user-name {
    color: #f9fafb;
}

.dark-mode .page-title-mobile {
    color: #f9fafb;
}

.notification-dot {
    width: 8px;
    height: 8px;
    background-color: #3b82f6;
    border-radius: 50%;
    margin-top: 0.25rem;
}

.search-result-item {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.search-result-item:hover {
    background-color: #f8fafc;
}
</style>