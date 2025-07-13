<?php
/**
 * ============================================================================
 * ENDPOINT: OBTENER DETALLE DE PRODUCTO INDIVIDUAL
 * ============================================================================
 * Versión simplificada para obtener datos de un producto específico
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
    // Obtener ID del producto
    $productoId = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : '');
    
    if (empty($productoId)) {
        throw new Exception('ID de producto requerido');
    }

    $objeto = new Conexion();
    $conn = $objeto->Conectar();

    // Consulta para obtener datos del producto
    $sql = "
        SELECT 
            pd.*,
            p.num_orden,
            p.rut_cliente,
            p.vendedor,
            p.fecha_ingreso,
            c.nombre as cliente_nombre,
            c.telefono as cliente_telefono
        FROM pedido_detalle pd
        INNER JOIN pedido p ON pd.num_orden = p.num_orden
        INNER JOIN clientes c ON p.rut_cliente = c.rut
        WHERE pd.id = ?
    ";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparando consulta');
    }

    $stmt->bindParam(1, $productoId, PDO::PARAM_INT);
    
    if (!$stmt->execute()) {
        throw new Exception('Error ejecutando consulta');
    }

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        throw new Exception('Producto no encontrado');
    }

    // Formatear respuesta
    $response = [
        "success" => true,
        "data" => [
            // Datos básicos del producto
            "id" => $producto['id'],
            "num_orden" => $producto['num_orden'],
            "modelo" => $producto['modelo'],
            "tamano" => $producto['tamano'],
            "cantidad" => $producto['cantidad'],
            "precio" => $producto['precio'],
            
            // Material y características
            "material" => $producto['tipotela'], // Mapear tipotela a material
            "tipotela" => $producto['tipotela'], // Mantener original también
            "color" => $producto['color'],
            "tipo_boton" => !empty($producto['tipo_boton']) ? $producto['tipo_boton'] : 'Normal',
            "anclaje" => $producto['anclaje'],
            "altura_base" => $producto['alturabase'], // Mapear alturabase
            "alturabase" => $producto['alturabase'], // Mantener original
            
            // Estado y asignación
            "estado" => $producto['estadopedido'], // Mapear estadopedido
            "estadopedido" => $producto['estadopedido'], // Mantener original
            "tapicero_id" => $producto['tapicero_id'],
            
            // Información de entrega
            "direccion" => $producto['direccion'],
            "numero" => $producto['numero'],
            "dpto" => $producto['dpto'],
            "comuna" => $producto['comuna'],
            "metodo_entrega" => $producto['metodo_entrega'],
            "detalle_entrega" => $producto['detalle_entrega'],
            
            // Observaciones
            "comentarios" => $producto['comentarios'],
            "detalles_fabricacion" => $producto['detalles_fabricacion'],
            
            // Información del pedido
            "pedido" => [
                "rut_cliente" => $producto['rut_cliente'],
                "cliente_nombre" => $producto['cliente_nombre'],
                "cliente_telefono" => $producto['cliente_telefono'],
                "vendedor" => $producto['vendedor'],
                "fecha_ingreso" => $producto['fecha_ingreso']
            ],
            
            // Metadatos
            "ultima_modificacion" => date('Y-m-d H:i:s')
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "timestamp" => date('Y-m-d H:i:s')
    ]);
    
    error_log('Error en get_producto_detalle.php: ' . $e->getMessage());
}
?>