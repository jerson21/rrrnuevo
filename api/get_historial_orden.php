<?php
/**
 * ============================================================================
 * ENDPOINT: HISTORIAL POR PRODUCTO (ARQUITECTURA CORRECTA)
 * ============================================================================
 * Historial organizado por producto individual, no por orden general
 * @version 4.1 - Corrección de errores de formateo y JSON
 * ============================================================================
 */

// Configurar manejo de errores para devolver siempre JSON válido
error_reporting(E_ERROR | E_PARSE); // Solo errores fatales
ini_set('display_errors', 0); // No mostrar errores en output

// Iniciar buffer de salida para capturar warnings
ob_start();

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
session_start();

// Parámetro de debug
$debug = isset($_GET['debug']) && $_GET['debug'] === '1';

if (!isset($_SESSION["s_usuario"])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit();
}

include("../../online/bd/conexion.php");

try {
    $numOrden = isset($_GET['num_orden']) ? $_GET['num_orden'] : (isset($_POST['num_orden']) ? $_POST['num_orden'] : '');
    
    if (empty($numOrden)) {
        throw new Exception('Número de orden requerido');
    }

    $objeto = new Conexion();
    $conn = $objeto->Conectar();
    
    $historial = [];
    $debugInfo = [];
    
    // ========================================================================
    // 1. OBTENER DATOS BÁSICOS DE LA ORDEN
    // ========================================================================
    
    $sql = "
        SELECT 
            p.num_orden,
            p.rut_cliente,
            p.fecha_ingreso,
            p.vendedor,
            p.estado,
            p.total_pagado,
            p.despacho,
            c.nombre as nombre_cliente,
            c.telefono,
            c.correo
        FROM pedido p
        LEFT JOIN clientes c ON p.rut_cliente = c.rut
        WHERE p.num_orden = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $numOrden, PDO::PARAM_STR);
    $stmt->execute();
    $orden = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$orden) {
        // La orden no existe en la base de datos
        echo json_encode([
            "success" => false,
            "message" => "No se encontró la orden {$numOrden} en la base de datos",
            "error_type" => "orden_no_encontrada",
            "timestamp" => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    // ========================================================================
    // 2. OBTENER PRODUCTOS DE LA ORDEN
    // ========================================================================
    
    $sql = "
        SELECT 
            pd.id as detalle_id,
            pd.modelo,
            pd.tamano,
            pd.tipotela,
            pd.color,
            pd.cantidad,
            pd.precio,
            pd.estadopedido,
            pd.tapicero_id,
            pd.comentarios,
            u.nombres as nombre_tapicero,
            pr.NombreProceso as estado_actual
        FROM pedido_detalle pd
        LEFT JOIN usuarios u ON pd.tapicero_id = u.id
        LEFT JOIN procesos pr ON pd.estadopedido = pr.idProceso
        WHERE pd.num_orden = ?
        ORDER BY pd.id ASC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $numOrden, PDO::PARAM_STR);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $productosIds = array_column($productos, 'detalle_id');
    
    // ========================================================================
    // 3. AGREGAR EVENTO DE CREACIÓN DE ORDEN
    // ========================================================================
    
    $nombreCliente = $orden['nombre_cliente'] ?? 'Cliente no encontrado';
    $rutCliente = $orden['rut_cliente'] ?? 'Sin RUT';
    
    if (empty($productos)) {
        // Si no hay productos, aún podemos mostrar info de la orden
        $historial[] = [
            'fecha' => $orden['fecha_ingreso'],
            'tipo' => 'orden_creada',
            'titulo' => 'Orden Creada',
            'descripcion' => "Orden #{$numOrden} creada por {$orden['vendedor']}",
            'icono' => 'fas fa-plus-circle',
            'color' => 'success',
            'usuario' => $orden['vendedor'],
            'detalle' => "Cliente: {$nombreCliente} ({$rutCliente}) | Sin productos registrados",
            'es_evento_orden' => true,
            'productos_count' => 0
        ];
        
        if ($debug) {
            $debugInfo['productos_encontrados'] = 0;
            $debugInfo['mensaje'] = 'Orden sin productos registrados';
        }
    } else {
        // Agregar evento de creación normal
        $historial[] = [
            'fecha' => $orden['fecha_ingreso'],
            'tipo' => 'orden_creada',
            'titulo' => 'Orden Creada',
            'descripcion' => "Orden #{$numOrden} creada por {$orden['vendedor']}",
            'icono' => 'fas fa-plus-circle',
            'color' => 'success',
            'usuario' => $orden['vendedor'],
            'detalle' => "Cliente: {$nombreCliente} ({$rutCliente}) | " . count($productos) . " producto(s)",
            'es_evento_orden' => true,
            'productos_count' => count($productos)
        ];
        
        if ($debug) {
            $debugInfo['productos_encontrados'] = count($productos);
            $debugInfo['productos_ids'] = $productosIds;
        }
    }
    
    // ========================================================================
    // 4. OBTENER HISTORIAL POR CADA PRODUCTO
    // ========================================================================
    
    if (!empty($productosIds)) {
        $placeholders = str_repeat('?,', count($productosIds) - 1) . '?';
        
        $sql = "
            SELECT 
                pe.idEtapa,
                pe.idPedido as detalle_id,
                pe.idProceso,
                pe.fecha,
                pe.usuario,
                pe.observacion,
                pr.NombreProceso,
                pr.detalle as detalle_proceso,
                pd.modelo,
                pd.tamano,
                pd.tipotela,
                pd.color
            FROM pedido_etapas pe
            INNER JOIN procesos pr ON pe.idProceso = pr.idProceso
            INNER JOIN pedido_detalle pd ON pe.idPedido = pd.id
            WHERE pe.idPedido IN ({$placeholders})
            ORDER BY pe.fecha ASC, pe.idEtapa ASC
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($productosIds);
        $etapas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($debug) {
            $debugInfo['etapas_encontradas'] = count($etapas);
        }
        
        // ====================================================================
        // 5. PROCESAR CADA ETAPA POR PRODUCTO
        // ====================================================================
        
        foreach ($etapas as $etapa) {
            $procesoId = $etapa['idProceso'];
            $nombreProceso = $etapa['NombreProceso'];
            $detalleProceso = $etapa['detalle_proceso'];
            $usuario = $etapa['usuario'] ?: 'Sistema';
            $observacion = $etapa['observacion'];
            
            $eventoInfo = mapearProcesoAEvento($procesoId, $nombreProceso, $detalleProceso);
            
            $descripcionCompleta = $nombreProceso;
            if ($observacion) {
                $descripcionCompleta .= " - " . $observacion;
            }
            
            // Identificación clara del producto
            $identificadorProducto = "{$etapa['modelo']} {$etapa['tamano']} - {$etapa['tipotela']} {$etapa['color']}";
            
            $historial[] = [
                'fecha' => $etapa['fecha'],
                'tipo' => $eventoInfo['tipo'],
                'titulo' => $eventoInfo['titulo'],
                'descripcion' => $descripcionCompleta,
                'icono' => $eventoInfo['icono'],
                'color' => $eventoInfo['color'],
                'usuario' => $usuario,
                'detalle' => "Producto: {$identificadorProducto}" . ($observacion ? " | Obs: " . $observacion : ''),
                'proceso_id' => $procesoId,
                'etapa_id' => $etapa['idEtapa'],
                'producto_id' => $etapa['detalle_id'],
                'producto_info' => [
                    'id' => $etapa['detalle_id'],
                    'modelo' => $etapa['modelo'],
                    'tamano' => $etapa['tamano'],
                    'tipotela' => $etapa['tipotela'],
                    'color' => $etapa['color'],
                    'identificador' => $identificadorProducto
                ],
                'es_evento_orden' => false
            ];
        }
    }
    
    // ========================================================================
    // 6. OBTENER PAGOS (NIVEL DE ORDEN)
    // ========================================================================
    
    try {
        if (tablaExiste($conn, 'pagos')) {
            $sql = "
                SELECT 
                    p.fecha_mov as fecha,
                    p.monto,
                    p.metodo_pago,
                    p.usuario,
                    p.datos_adicionales,
                    cb.rut as rut_pagador,
                    cb.nombre as nombre_pagador,
                    cb.banco,
                    cb.detalle as detalle_banco
                FROM pagos p
                LEFT JOIN cartola_bancaria cb ON p.id_cartola = cb.id
                WHERE p.num_orden = ?
                ORDER BY p.fecha_mov ASC
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $numOrden, PDO::PARAM_STR);
            $stmt->execute();
            $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($pagos as $pago) {
                $monto = limpiarNumero($pago['monto'] ?? 0);
                $monto_formateado = formatearMonto($monto);
                
                $metodo = $pago['metodo_pago'] ?? 'No especificado';
                $nombrePagador = $pago['nombre_pagador'] ?? $nombreCliente;
                $banco = $pago['banco'] ? " ({$pago['banco']})" : '';
                
                $historial[] = [
                    'fecha' => $pago['fecha'],
                    'tipo' => 'pago_agregado',
                    'titulo' => 'Pago Registrado',
                    'descripcion' => "Pago de $" . $monto_formateado . " recibido",
                    'icono' => 'fas fa-credit-card',
                    'color' => 'success',
                    'usuario' => $pago['usuario'] ?? 'Sistema de Pagos',
                    'detalle' => "Método: {$metodo} | {$nombrePagador}{$banco}" . ($pago['detalle_banco'] ? " | " . $pago['detalle_banco'] : ''),
                    'es_evento_orden' => true,
                    'monto' => $monto
                ];
            }
            
            if ($debug) {
                $debugInfo['pagos_encontrados'] = count($pagos);
            }
        }
    } catch (Exception $e) {
        if ($debug) {
            $debugInfo['error_pagos'] = $e->getMessage();
        }
        // No lanzar excepción, solo registrar
        error_log("Error obteniendo pagos: " . $e->getMessage());
    }
    
    // ========================================================================
    // 7. ORDENAMIENTO FINAL
    // ========================================================================
    
    // Ordenar por fecha ASC
    usort($historial, function($a, $b) {
        $fechaA = strtotime($a['fecha']);
        $fechaB = strtotime($b['fecha']);
        
        if ($fechaA === $fechaB) {
            // Si las fechas son iguales, orden: creada > pago > producto
            if ($a['tipo'] === 'orden_creada') return -1;
            if ($b['tipo'] === 'orden_creada') return 1;
            if ($a['tipo'] === 'pago_agregado') return -1;
            if ($b['tipo'] === 'pago_agregado') return 1;
            return 0;
        }
        
        return $fechaA - $fechaB;
    });
    
    // ========================================================================
    // 8. FORMATEAR FECHAS Y CALCULAR ESTADÍSTICAS
    // ========================================================================
    
    $totalPagos = 0;
    $montoPagado = 0;
    
    foreach ($historial as &$item) {
        try {
            $fecha = new DateTime($item['fecha']);
            $item['fecha_formateada'] = $fecha->format('d/m/Y H:i');
            $item['fecha_relativa'] = calcularTiempoRelativo($item['fecha']);
        } catch (Exception $e) {
            $item['fecha_formateada'] = $item['fecha'];
            $item['fecha_relativa'] = '';
        }
        
        // Contar pagos
        if ($item['tipo'] === 'pago_agregado') {
            $totalPagos++;
            $montoPagado += limpiarNumero($item['monto'] ?? 0);
        }
    }
    
    // ========================================================================
    // 9. RESPUESTA FINAL
    // ========================================================================
    
    $response = [
        "success" => true,
        "data" => $historial,
        "orden_info" => [
            "num_orden" => $numOrden,
            "cliente" => $nombreCliente,
            "rut_cliente" => $rutCliente,
            "fecha_ingreso" => $orden['fecha_ingreso'],
            "vendedor" => $orden['vendedor'],
            "total_pagado" => $orden['total_pagado'],
            "productos_count" => count($productos)
        ],
        "productos" => $productos,
        "estadisticas" => [
            'total_eventos' => count($historial),
            'total_pagos' => $totalPagos,
            'monto_pagado' => $montoPagado,
            'total_estados_producto' => count(array_filter($historial, function($h) { 
                return !$h['es_evento_orden']; 
            })),
            'productos_en_orden' => count($productos)
        ],
        "total" => count($historial)
    ];
    
    if ($debug) {
        $response['debug_info'] = $debugInfo;
    }
    
    // Limpiar cualquier output buffered antes de enviar JSON
    ob_clean();
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Limpiar cualquier output buffered antes de enviar error
    ob_clean();
    
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "timestamp" => date('Y-m-d H:i:s'),
        "error_file" => basename(__FILE__),
        "error_line" => $e->getLine()
    ], JSON_UNESCAPED_UNICODE);
    
    error_log('Error en get_historial_orden.php: ' . $e->getMessage());
} catch (Error $e) {
    // Capturar errores fatales de PHP (como problemas de sintaxis, etc.)
    ob_clean();
    
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor",
        "debug_message" => $debug ? $e->getMessage() : "Error interno",
        "timestamp" => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    
    error_log('Error fatal en get_historial_orden.php: ' . $e->getMessage());
}

// Finalizar buffer de salida si aún está activo
if (ob_get_level()) {
    ob_end_flush();
}

// ========================================================================
// FUNCIONES AUXILIARES
// ========================================================================


// ========================================================================
// FUNCIONES AUXILIARES CORREGIDAS
// ========================================================================

function limpiarNumero($valor) {
    // Función para limpiar y convertir valores a números de manera segura
    if (is_numeric($valor)) {
        return floatval($valor);
    }
    
    if (is_string($valor)) {
        // Remover todo excepto números, puntos y guiones
        $limpio = preg_replace('/[^0-9.-]/', '', $valor);
        
        // Si queda algo, convertir a float, sino devolver 0
        if ($limpio !== '' && is_numeric($limpio)) {
            return floatval($limpio);
        }
    }
    
    return 0.0;
}

function formatearMonto($monto) {
    // CORRECCIÓN: Aplicar la misma lógica que en JavaScript
    $numeroLimpio = limpiarNumero($monto);
    
    // Si el número tiene menos de 4 dígitos, probablemente está en miles
    // Por ejemplo: 56 -> 56000, pero 15000 -> 15000 (ya está correcto)
    if ($numeroLimpio > 0 && $numeroLimpio < 1000) {
        $numeroLimpio = $numeroLimpio * 1000;
    }
    
    return number_format($numeroLimpio, 0, ',', '.');
}

function tablaExiste($conn, $nombreTabla) {
    try {
        $sql = "SHOW TABLES LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombreTabla]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

function mapearProcesoAEvento($procesoId, $nombreProceso, $detalleProceso) {
    $mapeo = [
        1 => ['tipo' => 'pedido_aceptado', 'titulo' => 'Pedido Aceptado', 'icono' => 'fas fa-check-circle', 'color' => 'success'],
        2 => ['tipo' => 'enviado_fabricacion', 'titulo' => 'Enviado a Fabricación', 'icono' => 'fas fa-arrow-right', 'color' => 'warning'],
        3 => ['tipo' => 'tela_cortada', 'titulo' => 'Tela Cortada', 'icono' => 'fas fa-cut', 'color' => 'primary'],
        4 => ['tipo' => 'armado_esqueleto', 'titulo' => 'Corte y Armado de Esqueleto', 'icono' => 'fas fa-hammer', 'color' => 'primary'],
        5 => ['tipo' => 'fabricando', 'titulo' => 'Fabricando', 'icono' => 'fas fa-cogs', 'color' => 'info'],
        6 => ['tipo' => 'fabricado', 'titulo' => 'Producto Fabricado', 'icono' => 'fas fa-check-double', 'color' => 'success'],
        7 => ['tipo' => 'despacho_iniciado', 'titulo' => 'Despacho Iniciado', 'icono' => 'fas fa-box-open', 'color' => 'success'],
        8 => ['tipo' => 'cargado_camion', 'titulo' => 'Cargado en Camión', 'icono' => 'fas fa-truck', 'color' => 'success'],
        9 => ['tipo' => 'producto_entregado', 'titulo' => 'Producto Entregado', 'icono' => 'fas fa-handshake', 'color' => 'success'],
        10 => ['tipo' => 'devuelto_error_fabricacion', 'titulo' => 'Devuelto por Error de Fabricación', 'icono' => 'fas fa-exclamation-triangle', 'color' => 'danger'],
        11 => ['tipo' => 'devuelto_disconformidad', 'titulo' => 'Devuelto por Disconformidad', 'icono' => 'fas fa-thumbs-down', 'color' => 'danger'],
        12 => ['tipo' => 'devuelto_falla_carga', 'titulo' => 'Devuelto por Falla en Carga', 'icono' => 'fas fa-truck-loading', 'color' => 'danger'],
        13 => ['tipo' => 'devuelto_otro_motivo', 'titulo' => 'Devuelto por Otro Motivo', 'icono' => 'fas fa-question-circle', 'color' => 'danger'],
        14 => ['tipo' => 'devuelto_garantia', 'titulo' => 'Devuelto por Garantía', 'icono' => 'fas fa-shield-alt', 'color' => 'warning'],
        15 => ['tipo' => 'devuelto_cliente_no_contesta', 'titulo' => 'Cliente No Contesta', 'icono' => 'fas fa-phone-slash', 'color' => 'warning'],
        16 => ['tipo' => 'cliente_confirma', 'titulo' => 'Cliente Confirma Recepción', 'icono' => 'fas fa-user-check', 'color' => 'info'],
        17 => ['tipo' => 'solicita_factura', 'titulo' => 'Solicita Factura', 'icono' => 'fas fa-file-invoice', 'color' => 'info'],
        18 => ['tipo' => 'producto_eliminado', 'titulo' => 'Producto Eliminado', 'icono' => 'fas fa-trash', 'color' => 'danger'],
        19 => ['tipo' => 'reagendar', 'titulo' => 'Reagendar', 'icono' => 'fas fa-calendar-alt', 'color' => 'warning'],
        20 => ['tipo' => 'reemitido', 'titulo' => 'Re-emitido', 'icono' => 'fas fa-redo', 'color' => 'danger']
    ];
    
    return $mapeo[$procesoId] ?? [
        'tipo' => 'proceso_generico',
        'titulo' => $nombreProceso,
        'icono' => 'fas fa-circle',
        'color' => 'secondary'
    ];
}

function calcularTiempoRelativo($fecha) {
    try {
        $tiempo = time() - strtotime($fecha);
        
        if ($tiempo < 60) return 'Hace ' . $tiempo . ' segundos';
        if ($tiempo < 3600) return 'Hace ' . round($tiempo/60) . ' minutos';
        if ($tiempo < 86400) return 'Hace ' . round($tiempo/3600) . ' horas';
        if ($tiempo < 2592000) return 'Hace ' . round($tiempo/86400) . ' días';
        return 'Hace ' . round($tiempo/2592000) . ' meses';
    } catch (Exception $e) {
        return 'Tiempo desconocido';
    }
}
?>