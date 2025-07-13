<?php
/**
 * ============================================================================
 * ENDPOINT: EDITAR PRODUCTO - VERSIÓN SIMPLIFICADA
 * ============================================================================
 * Permite actualizar todos los campos de un producto específico
 * @version 1.0
 * @author RespaldosChile Team
 * ============================================================================
 */

header('Content-Type: application/json');
session_start();

// Verificar sesión
if (!isset($_SESSION["s_usuario"])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit();
}

include("../../online/bd/conexion.php");

try {
    // Verificar que sea POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Obtener datos del producto
    $productoId = isset($_POST['id']) ? $_POST['id'] : '';
    
    if (empty($productoId)) {
        throw new Exception('ID de producto requerido');
    }

    $objeto = new Conexion();
    $conn = $objeto->Conectar();

    // Preparar campos para actualizar
    $camposActualizar = [];
    $valores = [];
    
    // Lista de campos editables con sus tipos
    $camposPermitidos = [
        'modelo',
        'tamano', 
        'cantidad',
        'tipotela',
        'color',
        'precio',
        'tipo_boton',
        'anclaje',
        'alturabase',
        'estadopedido',
        'tapicero_id',
        'direccion',
        'numero',
        'dpto',
        'comuna',
        'metodo_entrega',
        'detalle_entrega',
        'comentarios',
        'detalles_fabricacion'
    ];

    // Construir consulta dinámicamente
    foreach ($camposPermitidos as $campo) {
        if (isset($_POST[$campo])) {
            $camposActualizar[] = "$campo = ?";
            $valores[] = $_POST[$campo];
        }
    }

    if (empty($camposActualizar)) {
        throw new Exception('No hay campos para actualizar');
    }

    // Agregar ID al final
    $valores[] = $productoId;

    // Construir y ejecutar consulta
    $sql = "UPDATE pedido_detalle SET " . implode(', ', $camposActualizar) . " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error preparando consulta');
    }

    // Ejecutar con los valores
    if (!$stmt->execute($valores)) {
        throw new Exception('Error ejecutando consulta');
    }

    if ($stmt->rowCount() === 0) {
        // Verificar si el producto existe
        $checkStmt = $conn->prepare("SELECT id FROM pedido_detalle WHERE id = ?");
        $checkStmt->execute([$productoId]);
        
        if ($checkStmt->rowCount() === 0) {
            throw new Exception('Producto no encontrado');
        } else {
            // El producto existe pero no hubo cambios (datos iguales)
            echo json_encode([
                "success" => true,
                "message" => "Producto sin cambios (datos iguales a los actuales)",
                "producto_id" => $productoId,
                "campos_revisados" => count($camposActualizar),
                "timestamp" => date('Y-m-d H:i:s')
            ]);
            exit;
        }
    }

    // Log de la actividad
    $usuario = $_SESSION["s_usuario"];
    
    // Respuesta exitosa
    echo json_encode([
        "success" => true,
        "message" => "Producto actualizado correctamente",
        "producto_id" => $productoId,
        "campos_actualizados" => count($camposActualizar),
        "usuario" => $usuario,
        "timestamp" => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "timestamp" => date('Y-m-d H:i:s')
    ]);
    
    // Log del error
    error_log('Error en editar_producto.php: ' . $e->getMessage());
}
?>