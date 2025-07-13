<?php
// Obtener información del usuario y página actual
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$usuario = $_SESSION["s_usuario"] ?? 'Usuario';
$privilegios = $_SESSION["privilegios"] ?? 0;

// Definir elementos del menú según privilegios
$menu_items = [
    [
        'id' => 'dashboard',
        'title' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'url' => 'dashboard-pedidos.php',
        'badge' => '',
        'min_privilege' => 4,
        'submenu' => []
    ],
    [
        'id' => 'pedidos',
        'title' => 'Pedidos',
        'icon' => 'fas fa-clipboard-list',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 4,
        'submenu' => [
            [
                'title' => 'Pedidos Ingresados',
                'icon' => 'fas fa-list',
                'url' => 'dashboard-pedidos.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Validar Pagos',
                'icon' => 'fas fa-credit-card',
                'url' => 'validar-pagos.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Pedidos Eliminados',
                'icon' => 'fas fa-trash-alt',
                'url' => 'pedidos-eliminados.php',
                'min_privilege' => 20
            ]
        ]
    ],
    [
        'id' => 'ventas',
        'title' => 'Ventas',
        'icon' => 'fas fa-shopping-cart',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 4,
        'submenu' => [
            [
                'title' => 'Venta en Sala',
                'icon' => 'fas fa-store',
                'url' => 'venta-sala.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Retiro en Tienda',
                'icon' => 'fas fa-hand-holding',
                'url' => 'retiro-tienda.php',
                'min_privilege' => 4
            ]
        ]
    ],
    [
        'id' => 'produccion',
        'title' => 'Producción',
        'icon' => 'fas fa-industry',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 0,
        'submenu' => [
            [
                'title' => 'Asignación Tapiceros',
                'icon' => 'fas fa-users',
                'url' => 'asignacion-tapiceros.php',
                'min_privilege' => 5
            ],
            [
                'title' => 'Control Fabricación',
                'icon' => 'fas fa-cogs',
                'url' => 'control-fabricacion.php',
                'min_privilege' => 0
            ],
            [
                'title' => 'Corte de Telas',
                'icon' => 'fas fa-cut',
                'url' => 'corte-telas.php',
                'min_privilege' => 5
            ]
        ]
    ],
    [
        'id' => 'bodega',
        'title' => 'Bodega & Stock',
        'icon' => 'fas fa-warehouse',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 4,
        'submenu' => [
            [
                'title' => 'Inventario General',
                'icon' => 'fas fa-boxes',
                'url' => 'inventario.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Stock Productos',
                'icon' => 'fas fa-chair',
                'url' => 'stock-productos.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Stock Telas',
                'icon' => 'fas fa-cut',
                'url' => 'stock-telas.php',
                'min_privilege' => 4
            ]
        ]
    ],
    [
        'id' => 'logistica',
        'title' => 'Logística',
        'icon' => 'fas fa-truck',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 4,
        'submenu' => [
            [
                'title' => 'Gestión de Rutas',
                'icon' => 'fas fa-route',
                'url' => 'rutas.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Despachos',
                'icon' => 'fas fa-shipping-fast',
                'url' => 'despachos.php',
                'min_privilege' => 4
            ],
            [
                'title' => 'Integración Starken',
                'icon' => 'fas fa-truck-loading',
                'url' => 'starken.php',
                'min_privilege' => 20
            ]
        ]
    ],
    [
        'id' => 'clientes',
        'title' => 'Clientes',
        'icon' => 'fas fa-users',
        'url' => 'gestion_clientes.php',
        'badge' => '',
        'min_privilege' => 4,
        'submenu' => []
    ],
    [
        'id' => 'reportes',
        'title' => 'Reportes',
        'icon' => 'fas fa-chart-bar',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 20,
        'submenu' => [
            [
                'title' => 'Reportes de Ventas',
                'icon' => 'fas fa-chart-line',
                'url' => 'reportes-ventas.php',
                'min_privilege' => 20
            ],
            [
                'title' => 'Reportes Financieros',
                'icon' => 'fas fa-dollar-sign',
                'url' => 'reportes-financieros.php',
                'min_privilege' => 21
            ],
            [
                'title' => 'Reportes de Producción',
                'icon' => 'fas fa-industry',
                'url' => 'reportes-produccion.php',
                'min_privilege' => 20
            ]
        ]
    ],
    [
        'id' => 'administracion',
        'title' => 'Administración',
        'icon' => 'fas fa-cog',
        'url' => '#',
        'badge' => '',
        'min_privilege' => 20,
        'submenu' => [
            [
                'title' => 'Gestión de Usuarios',
                'icon' => 'fas fa-user-cog',
                'url' => 'gestion_usuarios.php',
                'min_privilege' => 21
            ],
            [
                'title' => 'Configuración Sistema',
                'icon' => 'fas fa-cogs',
                'url' => 'configuracion.php',
                'min_privilege' => 21
            ],
            [
                'title' => 'Logs del Sistema',
                'icon' => 'fas fa-file-alt',
                'url' => 'logs.php',
                'min_privilege' => 21
            ]
        ]
    ]
];

// Función para verificar si el usuario tiene acceso
function hasAccess($min_privilege, $user_privileges) {
    return $user_privileges >= $min_privilege;
}

// Función para verificar si es la página actual
function isCurrentPage($url, $current_page) {
    $page_name = basename($url, '.php');
    return $page_name === $current_page;
}

// Función para verificar si un submenu contiene la página actual
function hasCurrentPage($submenu, $current_page) {
    foreach ($submenu as $item) {
        if (isCurrentPage($item['url'], $current_page)) {
            return true;
        }
    }
    return false;
}
?>

<!-- Sidebar Navigation -->
<nav class="sidebar" id="sidebar">
    
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="dashboard-pedidos.php" class="sidebar-brand text-decoration-none">
            <img src="../online/img/logoblanco.png" alt="RespaldosChile" class="sidebar-logo">
            <div class="sidebar-brand-text">
                <div class="brand-title">RespaldosChile</div>
                <div class="brand-subtitle">Sistema de Gestión</div>
            </div>
        </a>
    </div>
    
    <!-- Sidebar Navigation -->
    <div class="sidebar-nav">
        <div class="nav-section">
            
            <?php foreach ($menu_items as $item): ?>
                <?php if (hasAccess($item['min_privilege'], $privilegios)): ?>
                    
                    <?php 
                    $isActive = isCurrentPage($item['url'], $current_page) || hasCurrentPage($item['submenu'], $current_page);
                    $hasSubmenu = !empty($item['submenu']);
                    $menuId = 'menu-' . $item['id'];
                    ?>
                    
                    <div class="nav-item <?= $hasSubmenu ? 'has-submenu' : '' ?> <?= $isActive ? 'active' : '' ?>">
                        
                        <!-- Item principal -->
                        <a href="<?= $hasSubmenu ? '#' : $item['url'] ?>" 
                           class="nav-link <?= $isActive ? 'active' : '' ?>"
                           <?= $hasSubmenu ? 'data-bs-toggle="collapse" data-bs-target="#' . $menuId . '" aria-expanded="' . ($isActive ? 'true' : 'false') . '"' : '' ?>>
                            
                            <div class="nav-link-content">
                                <i class="nav-icon <?= $item['icon'] ?>"></i>
                                <span class="nav-text"><?= $item['title'] ?></span>
                                
                                <?php if ($item['badge']): ?>
                                    <span class="nav-badge badge bg-danger"><?= $item['badge'] ?></span>
                                <?php endif; ?>
                                
                                <?php if ($hasSubmenu): ?>
                                    <i class="nav-arrow fas fa-chevron-right"></i>
                                <?php endif; ?>
                            </div>
                        </a>
                        
                        <!-- Submenu -->
                        <?php if ($hasSubmenu): ?>
                            <div id="<?= $menuId ?>" class="nav-submenu collapse <?= $isActive ? 'show' : '' ?>">
                                <div class="submenu-content">
                                    <?php foreach ($item['submenu'] as $subitem): ?>
                                        <?php if (hasAccess($subitem['min_privilege'], $privilegios)): ?>
                                            <a href="<?= $subitem['url'] ?>" 
                                               class="submenu-link <?= isCurrentPage($subitem['url'], $current_page) ? 'active' : '' ?>">
                                                <i class="submenu-icon <?= $subitem['icon'] ?>"></i>
                                                <span class="submenu-text"><?= $subitem['title'] ?></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                <?php endif; ?>
            <?php endforeach; ?>
            
        </div>
        
        <!-- Sección de usuario -->
        <div class="nav-section nav-section-bottom">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($usuario) ?></div>
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
            
            <!-- Acciones de usuario -->
            <div class="user-actions">
                <a href="perfil.php" class="action-link" title="Mi Perfil">
                    <i class="fas fa-user-cog"></i>
                </a>
                <a href="configuracion.php" class="action-link" title="Configuración">
                    <i class="fas fa-cog"></i>
                </a>
                <a href="../logout.php" class="action-link text-danger" title="Cerrar Sesión">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
        
    </div>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="version-info">
            <small class="text-muted">v2.1.0</small>
        </div>
    </div>
    
</nav>

<!-- Sidebar Overlay (para móviles) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Estilos del Sidebar -->
<style>
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
    z-index: 1000;
    transition: transform 0.3s ease;
    box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.sidebar.collapsed {
    transform: translateX(-100%);
}

/* Header del Sidebar */
.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
}

.sidebar-logo {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
}

.sidebar-brand-text {
    flex: 1;
}

.brand-title {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 0.125rem;
}

.brand-subtitle {
    font-size: 0.75rem;
    opacity: 0.8;
    font-weight: 400;
}

/* Navegación */
.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-section {
    padding: 0 1rem;
}

.nav-section + .nav-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.nav-section-bottom {
    margin-top: auto;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: block;
    padding: 0.75rem 1rem;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    position: relative;
}

.nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    color: white;
    transform: translateX(2px);
}

.nav-link.active {
    background-color: rgba(59, 130, 246, 0.2);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.nav-link-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.nav-icon {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.nav-text {
    flex: 1;
    font-weight: 500;
    font-size: 0.875rem;
}

.nav-badge {
    font-size: 0.625rem;
    padding: 0.125rem 0.375rem;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
}

.nav-arrow {
    font-size: 0.75rem;
    transition: transform 0.2s ease;
    flex-shrink: 0;
}

.nav-link[aria-expanded="true"] .nav-arrow {
    transform: rotate(90deg);
}

/* Submenu */
.nav-submenu {
    margin-top: 0.25rem;
}

.submenu-content {
    background-color: rgba(0,0,0,0.2);
    border-radius: 6px;
    padding: 0.5rem 0;
    margin-left: 2.5rem;
}

.submenu-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s ease;
    font-size: 0.8125rem;
}

.submenu-link:hover {
    background-color: rgba(255,255,255,0.1);
    color: white;
}

.submenu-link.active {
    background-color: rgba(59, 130, 246, 0.3);
    color: white;
    font-weight: 600;
}

.submenu-icon {
    width: 16px;
    text-align: center;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.submenu-text {
    flex: 1;
}

/* Información del usuario */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: rgba(255,255,255,0.05);
    border-radius: 8px;
    margin-bottom: 1rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(45deg, #3b82f6, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    color: white;
    font-size: 0.875rem;
    margin-bottom: 0.125rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.7);
}

/* Acciones del usuario */
.user-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.action-link {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background-color: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.action-link:hover {
    background-color: rgba(255,255,255,0.2);
    color: white;
    transform: translateY(-1px);
}

.action-link.text-danger:hover {
    background-color: #dc3545;
    color: white;
}

/* Footer del Sidebar */
.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    text-align: center;
    flex-shrink: 0;
}

.version-info {
    color: rgba(255,255,255,0.5);
}

/* Overlay para móviles */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Scrollbar personalizado */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
}

/* Animaciones */
@keyframes slideInLeft {
    from {
        transform: translateX(-20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.nav-item {
    animation: slideInLeft 0.3s ease forwards;
}

.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.15s; }
.nav-item:nth-child(3) { animation-delay: 0.2s; }
.nav-item:nth-child(4) { animation-delay: 0.25s; }
.nav-item:nth-child(5) { animation-delay: 0.3s; }
.nav-item:nth-child(6) { animation-delay: 0.35s; }
.nav-item:nth-child(7) { animation-delay: 0.4s; }
.nav-item:nth-child(8) { animation-delay: 0.45s; }
.nav-item:nth-child(9) { animation-delay: 0.5s; }

/* Estados de hover mejorados */
.nav-item.has-submenu:hover .nav-arrow {
    color: #3b82f6;
}

.nav-link:hover .nav-icon {
    color: #3b82f6;
    transform: scale(1.1);
}

/* Indicador de página activa */
.nav-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background-color: #3b82f6;
    border-radius: 0 2px 2px 0;
}
</style>

