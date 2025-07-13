<?php
/**
 * ============================================================================
 * API: GET TAPICEROS - OBTENER LISTA DE TAPICEROS
 * ============================================================================
 * Obtiene la lista de tapiceros activos del sistema
 * @version 1.0
 * @author RespaldosChile Team
 * ============================================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

session_start();

// Verificar autenticación
if (!isset($_SESSION["s_usuario"])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'No autorizado'
    ]);
    exit;
}

// Incluir configuración de base de datos
include("../../online/bd/conexion.php");

try {
    $objeto = new Conexion();
    $conn = $objeto->Conectar();
    
    // Obtener parámetros opcionales
    $incluir_inactivos = isset($_GET['incluir_inactivos']) && $_GET['incluir_inactivos'] === 'true';
    $formato = isset($_GET['formato']) ? $_GET['formato'] : 'completo';
    
    // Construir consulta base
    $sql = "
        SELECT 
            id,
            usuario,
            nombres,
            apaterno,
            amaterno,
            rut,
            correo,
            privilegios,
            s_diario,
            pago,
            CONCAT(nombres, ' ', apaterno) as nombre_completo,
            CONCAT(nombres, ' ', apaterno, ' ', amaterno) as nombre_completo_largo
        FROM usuarios 
        WHERE privilegios = 0
    ";
    
    // Agregar filtro de activos si es necesario
    if (!$incluir_inactivos) {
        $sql .= " AND usuario IS NOT NULL AND usuario != ''";
    }
    
    $sql .= " ORDER BY nombres ASC, apaterno ASC";
    
    // USAR PDO CORRECTAMENTE
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparando consulta');
    }
    
    if (!$stmt->execute()) {
        throw new Exception('Error ejecutando consulta');
    }
    
    // USAR fetchAll PARA PDO
    $tapiceros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear según el tipo solicitado
    $resultado = [];
    
    foreach ($tapiceros as $tapicero) {
        switch ($formato) {
            case 'simple':
                $resultado[] = [
                    'id' => $tapicero['id'],
                    'nombre' => $tapicero['nombre_completo']
                ];
                break;
                
            case 'select':
                $resultado[] = [
                    'value' => $tapicero['id'],
                    'text' => $tapicero['nombre_completo'],
                    'data' => [
                        'rut' => $tapicero['rut'],
                        'usuario' => $tapicero['usuario']
                    ]
                ];
                break;
                
            case 'completo':
            default:
                $resultado[] = [
                    'id' => $tapicero['id'],
                    'usuario' => $tapicero['usuario'],
                    'nombres' => $tapicero['nombres'],
                    'apaterno' => $tapicero['apaterno'],
                    'amaterno' => $tapicero['amaterno'],
                    'nombre_completo' => $tapicero['nombre_completo'],
                    'nombre_completo_largo' => $tapicero['nombre_completo_largo'],
                    'rut' => $tapicero['rut'],
                    'correo' => $tapicero['correo'],
                    'salario_diario' => $tapicero['s_diario'],
                    'forma_pago' => $tapicero['pago'],
                    'privilegios' => $tapicero['privilegios']
                ];
                break;
        }
    }
    
    // Obtener estadísticas adicionales si se solicita
    $estadisticas = null;
    if (isset($_GET['incluir_estadisticas']) && $_GET['incluir_estadisticas'] === 'true') {
        $estadisticas = obtenerEstadisticasTapiceros($conn);
    }
    
    echo json_encode([
        'success' => true,
        'data' => $resultado,
        'count' => count($resultado),
        'estadisticas' => $estadisticas,
        'formato' => $formato,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
    // Log del error para debugging
    error_log('Error en get_tapiceros.php: ' . $e->getMessage());
}

/**
 * Obtener estadísticas de trabajo de tapiceros
 */
function obtenerEstadisticasTapiceros($conn) {
    try {
        // Productos asignados por tapicero
        $sql_asignados = "
            SELECT 
                u.id,
                u.nombres,
                u.apaterno,
                COUNT(pd.id) as productos_asignados,
                COUNT(CASE WHEN pd.estadopedido = '5' THEN 1 END) as fabricando,
                COUNT(CASE WHEN pd.estadopedido = '6' THEN 1 END) as terminados,
                COUNT(CASE WHEN pd.estadopedido IN ('7','8','9') THEN 1 END) as entregados
            FROM usuarios u
            LEFT JOIN pedido_detalle pd ON u.id = pd.tapicero_id
            WHERE u.privilegios = 0
            GROUP BY u.id, u.nombres, u.apaterno
            ORDER BY productos_asignados DESC
        ";
        
        $stmt = $conn->prepare($sql_asignados);
        if (!$stmt) {
            throw new Exception('Error preparando consulta de estadísticas');
        }
        
        $stmt->execute();
        $estadisticas_por_tapicero = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Estadísticas generales
        $sql_generales = "
            SELECT 
                COUNT(DISTINCT pd.tapicero_id) as tapiceros_con_trabajo,
                COUNT(pd.id) as total_productos_asignados,
                COUNT(CASE WHEN pd.estadopedido = '5' THEN 1 END) as total_fabricando,
                COUNT(CASE WHEN pd.estadopedido = '6' THEN 1 END) as total_listos,
                AVG(CASE WHEN pd.tapicero_id IS NOT NULL THEN 1 ELSE 0 END) * 100 as porcentaje_asignacion
            FROM pedido_detalle pd
            WHERE pd.estadopedido IN ('2','3','4','5','6','7','8','9')
        ";
        
        $stmt = $conn->prepare($sql_generales);
        if (!$stmt) {
            throw new Exception('Error preparando consulta de estadísticas generales');
        }
        
        $stmt->execute();
        $estadisticas_generales = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'por_tapicero' => $estadisticas_por_tapicero,
            'generales' => $estadisticas_generales,
            'fecha_calculo' => date('Y-m-d H:i:s')
        ];
        
    } catch (Exception $e) {
        return [
            'error' => 'No se pudieron calcular las estadísticas: ' . $e->getMessage()
        ];
    }
}

/**
 * Obtener tapiceros con carga de trabajo actual
 */
function obtenerCargaTrabajo($conn) {
    try {
        $sql = "
            SELECT 
                u.id,
                u.nombres,
                u.apaterno,
                COUNT(pd.id) as productos_pendientes,
                COUNT(CASE WHEN pd.estadopedido = '5' THEN 1 END) as fabricando_ahora,
                COUNT(CASE WHEN pd.estadopedido IN ('2','3','4') THEN 1 END) as por_fabricar
            FROM usuarios u
            LEFT JOIN pedido_detalle pd ON u.id = pd.tapicero_id 
                AND pd.estadopedido IN ('2','3','4','5')
            WHERE u.privilegios = 0
            GROUP BY u.id, u.nombres, u.apaterno
            ORDER BY productos_pendientes ASC, u.nombres ASC
        ";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return [];
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        return [];
    }
}
?>