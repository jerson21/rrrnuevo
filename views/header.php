<?php
/**
 * Header centralizado para todas las páginas del admin
 * RespaldosChile Admin System
 */

// Configuración de colores del sistema (puede ser movido a config.php)
if (!isset($colors)) {
    $colors = [
        'primary' => '#4F46E5',    // Indigo moderno
        'secondary' => '#64748B',  // Slate gris
        'success' => '#10B981',    // Emerald
        'warning' => '#F59E0B',    // Amber
        'danger' => '#EF4444',     // Red
        'info' => '#06B6D4',       // Cyan
        'light' => '#F8FAFC',      // Slate 50
        'dark' => '#1E293B'        // Slate 800
    ];
}

// Variables por defecto si no están definidas
$pageTitle = $pageTitle ?? 'RespaldosChile Admin';
$pageDescription = $pageDescription ?? 'Sistema de gestión administrativo RespaldosChile';
$pageCSS = $pageCSS ?? '';
$pageJS = $pageJS ?? '';
$includeDataTables = $includeDataTables ?? true;
$includeSweetAlert = $includeSweetAlert ?? true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Meta tags para SEO y rendimiento -->
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="author" content="RespaldosChile Team">
    <meta name="robots" content="noindex,nofollow">
    
    <!-- Preconnect para optimización -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome 6.5.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" 
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous">
    
    <?php if ($includeDataTables): ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <?php endif; ?>
    
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <?php if ($includeSweetAlert): ?>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php endif; ?>
    
    <!-- CSS Principal del Tema -->
    <link rel="stylesheet" href="assets/css/admin-theme.css?v=<?= filemtime('assets/css/admin-theme.css') ?>">
    
    <!-- CSS específico de la página -->
    <?= $pageCSS ?>
    
    <!-- Variables CSS dinámicas (sobrescriben las del tema si es necesario) -->
    <style>
        :root {
            --primary-color: <?= $colors['primary'] ?>;
            --secondary-color: <?= $colors['secondary'] ?>;
            --success-color: <?= $colors['success'] ?>;
            --warning-color: <?= $colors['warning'] ?>;
            --danger-color: <?= $colors['danger'] ?>;
            --info-color: <?= $colors['info'] ?>;
            --light-color: <?= $colors['light'] ?>;
            --dark-color: <?= $colors['dark'] ?>;
        }
        
        /* Optimización de carga */
        * {
            box-sizing: border-box;
        }
        
        /* Smooth scroll para toda la página */
        html {
            scroll-behavior: smooth;
        }
    </style>
    
    <!-- JavaScript específico de la página (head) -->
    <?= $pageJS ?>
</head>