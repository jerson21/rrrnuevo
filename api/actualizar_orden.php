<?php
/**
 * API Endpoint: Actualizar Orden Completa
 * Actualiza todos los datos de una orden desde el modal de gestión
 */

session_start();
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Verificar autenticación
if (!isset($_SESSION["s_usuario"]) || $_SESSION["privilegios"] < 4) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'No tienes permisos para realizar esta acción'
    ]);
    exit;
}

// Incluir conexión a base de datos
require_once '../config/conexion.php'; // Ajusta la ruta según tu estructura

try {
    // Verificar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    // Obtener datos del POST
    $data = $_POST;
    
    // Validar número de orden
    if (empty($data['num_orden'])) {
        throw new Exception('Número de orden es requerido');
    }
    
    $numOrden = trim($data['num_orden']);
    
    // Verificar que la orden existe
    $checkSql = "SELECT num_orden, rut as rut_actual FROM pedidos WHERE num_orden = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$numOrden]);
    $ordenExistente = $checkStmt->fetch();
    
    if (!$ordenExistente) {
        throw new Exception('La orden no existe');
    }
    
    // Inicializar array para transacciones
    $transacciones = [];
    
    // ACTUALIZAR TABLA PEDIDOS (orden principal)
    $camposPedidos = [];
    $valoresPedidos = [];
    
    // Mapeo de campos permitidos para la tabla pedidos
    $camposPermitidosPedidos = [
        'vendedor' => 'vendedor',
        'nombre_cliente' => 'nombre', 
        'rut_cliente' => 'rut',
        'telefono' => 'telefono',
        'instagram' => 'instagram',
        'despacho' => 'despacho'
    ];
    
    foreach ($camposPermitidosPedidos as $campoPost => $campoDB) {
        if (isset($data[$campoPost]) && $data[$campoPost] !== '') {
            $valor = trim($data[$campoPost]);
            
            // Validaciones específicas
            switch ($campoPost) {
                case 'rut_cliente':
                    // Validar formato RUT
                    if (!preg_match('/^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/', $valor)) {
                        throw new Exception('Formato de RUT inválido');
                    }
                    break;
                    
                case 'telefono':
                    // Limpiar teléfono (solo números)
                    $valor = preg_replace('/[^0-9]/', '', $valor);
                    if (strlen($valor) < 8 || strlen($valor) > 12) {
                        throw new Exception('Formato de teléfono inválido');
                    }
                    break;
                    
                case 'despacho':
                    // Limpiar formato de números
                    $valor = floatval(str_replace(['.', ',', ' '], '', $valor));
                    break;
                    
                case 'nombre_cliente':
                    if (strlen($valor) < 2) {
                        throw new Exception('El nombre debe tener al menos 2 caracteres');
                    }
                    break;
            }
            
            $camposPedidos[] = "$campoDB = ?";
            $valoresPedidos[] = $valor;
        }
    }
    
    // ACTUALIZAR TABLA DETALLE_PEDIDOS (información de entrega)
    $camposDetalles = [];
    $valoresDetalles = [];
    $actualizarDetalles = false;
    
    $camposPermitidosDetalles = [
        'direccion' => 'direccion',
        'numero' => 'numero',
        'dpto' => 'dpto', 
        'comuna' => 'comuna'
    ];
    
    foreach ($camposPermitidosDetalles as $campoPost => $campoDB) {
        if (isset($data[$campoPost])) {
            $valor = trim($data[$campoPost]);
            $camposDetalles[] = "$campoDB = ?";
            $valoresDetalles[] = $valor;
            $actualizarDetalles = true;
        }
    }
    
    // Iniciar transacción
    $pdo->beginTransaction();
    
    try {
        // 1. ACTUALIZAR TABLA PEDIDOS
        if (!empty($camposPedidos)) {
            $camposPedidos[] = "fecha_modificacion = NOW()";
            $camposPedidos[] = "usuario_modificacion = ?";
            $valoresPedidos[] = $_SESSION["s_usuario"];
            $valoresPedidos[] = $numOrden; // Para WHERE
            
            $sqlPedidos = "UPDATE pedidos SET " . implode(', ', $camposPedidos) . " WHERE num_orden = ?";
            $stmtPedidos = $pdo->prepare($sqlPedidos);
            $resultadoPedidos = $stmtPedidos->execute($valoresPedidos);
            
            if (!$resultadoPedidos) {
                throw new Exception('Error al actualizar los datos principales de la orden');
            }
            
            $transacciones[] = "Tabla pedidos actualizada";
        }
        
        // 2. ACTUALIZAR TABLA DETALLE_PEDIDOS (solo información de entrega)
        if ($actualizarDetalles) {
            $camposDetalles[] = "fecha_modificacion = NOW()";
            $camposDetalles[] = "usuario_modificacion = ?";
            $valoresDetalles[] = $_SESSION["s_usuario"];
            $valoresDetalles[] = $numOrden; // Para WHERE
            
            $sqlDetalles = "UPDATE detalle_pedidos SET " . implode(', ', $camposDetalles) . " WHERE num_orden = ?";
            $stmtDetalles = $pdo->prepare($sqlDetalles);
            $resultadoDetalles = $stmtDetalles->execute($valoresDetalles);
            
            if (!$resultadoDetalles) {
                throw new Exception('Error al actualizar la información de entrega');
            }
            
            $transacciones[] = "Información de entrega actualizada en todos los productos";
        }
        
        // 3. REGISTRAR EN LOG DE AUDITORÍA
        try {
            $logSql = "INSERT INTO log_pedidos (num_orden, accion, datos_modificados, usuario, fecha) 
                       VALUES (?, 'UPDATE_GESTION', ?, ?, NOW())";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                $numOrden,
                json_encode([
                    'campos_modificados' => array_keys($data),
                    'datos_enviados' => $data,
                    'transacciones' => $transacciones
                ]),
                $_SESSION["s_usuario"]
            ]);
        } catch (Exception $e) {
            // Log de auditoría falló, pero no es crítico
            error_log("Error en log de auditoría: " . $e->getMessage());
        }
        
        // Confirmar transacción
        $pdo->commit();
        
        // 4. OBTENER DATOS ACTUALIZADOS
        $datosActualizadosSql = "
            SELECT p.*, 
                   COUNT(dp.id) as total_productos,
                   SUM(CASE WHEN dp.estadopedido = '9' THEN 1 ELSE 0 END) as productos_entregados
            FROM pedidos p 
            LEFT JOIN detalle_pedidos dp ON p.num_orden = dp.num_orden 
            WHERE p.num_orden = ?
            GROUP BY p.num_orden
        ";
        $datosStmt = $pdo->prepare($datosActualizadosSql);
        $datosStmt->execute([$numOrden]);
        $datosActualizados = $datosStmt->fetch(PDO::FETCH_ASSOC);
        
        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'message' => 'Orden actualizada correctamente',
            'data' => [
                'num_orden' => $numOrden,
                'transacciones_realizadas' => $transacciones,
                'campos_actualizados' => [
                    'pedidos' => count($camposPedidos) - 2, // -2 por fecha_modificacion y usuario_modificacion
                    'detalles' => $actualizarDetalles ? count($camposDetalles) - 2 : 0
                ],
                'orden_actualizada' => $datosActualizados,
                'usuario' => $_SESSION["s_usuario"],
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
        
    } catch (Exception $e) {
        // Rollback en caso de error
        $pdo->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => 'ORDER_UPDATE_ERROR',
        'debug_info' => [
            'num_orden' => $numOrden ?? 'no_proporcionado',
            'datos_recibidos' => array_keys($data ?? []),
            'usuario' => $_SESSION["s_usuario"] ?? 'no_autenticado'
        ]
    ]);
    
    error_log("Error actualizando orden {$numOrden}: " . $e->getMessage());
    
} catch (PDOException $e) {
    // Rollback si la transacción está activa
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos',
        'error_code' => 'DATABASE_ERROR'
    ]);
    
    error_log("Error PDO en actualizar_orden.php: " . $e->getMessage());
}
?>