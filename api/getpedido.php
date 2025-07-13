<?php

include("../../online/bd/conexion.php");
$objeto = new Conexion();
$conn = $objeto->Conectar();

// Función para normalizar montos
function normalizarMonto($monto)
{
    $montoLimpio = str_replace(['$', '.'], '', $monto);
    return (float)$montoLimpio;
}

function manejarNumOrden($id, $ruta, $conn)
{
    $pedidos = [];
    $totalPrecioTodosLosPedidos = 0;
    $total_precioproductos = 0;
    $totalPagado = 0;

    // Consulta SQL corregida con el JOIN a la tabla procesos
    $sql = "SELECT p.*, c.*, d.*, pr.NombreProceso,
              COALESCE(pg.total_pagado, 0) AS total_pagado,
              COALESCE(SUM(CAST(REPLACE(REPLACE(d.precio, '$', ''), '.', '') AS SIGNED)), 0) AS total_precio
            FROM pedido p
            INNER JOIN pedido_detalle d ON p.num_orden = d.num_orden
            INNER JOIN clientes c ON p.rut_cliente = c.rut
            LEFT JOIN procesos pr ON d.estadopedido = pr.idProceso
            LEFT JOIN (
              SELECT num_orden, SUM(CAST(REPLACE(REPLACE(monto, '$', ''), '.', '') AS SIGNED)) AS total_pagado
              FROM pagos
              GROUP BY num_orden
            ) pg ON p.num_orden = pg.num_orden
            WHERE d.estadopedido NOT IN ('100', '404') AND p.num_orden = ? AND d.ruta_asignada = ?
            GROUP BY p.num_orden, d.id, p.rut_cliente, pr.NombreProceso
            ORDER BY d.num_orden, d.id, p.rut_cliente ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->bindParam(2, $ruta, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $pedidoId = $row['num_orden'];

        if (!isset($pedidos[$pedidoId])) {
            $valorDespacho = floatval($row['despacho']);
            $totalPrecioTodosLosPedidos += $valorDespacho;
            $pedidos[$pedidoId] = [
                'num_orden' => $row['num_orden'],
                'orden_ext' => $row['orden_ext'],
                'estadopedido' => $row['estadopedido'],
                'nombre_estado' => $row['NombreProceso'], // Añadido
                'vendedor' => $row['vendedor'],
                'despacho' => $row['despacho'],
                'referencia' => $row['detalle_entrega'],
                'fecha_ingreso' => $row['fecha_ingreso'],
                'total_pagado' => $row['total_pagado'],
                'total_productos' => $row['total_precio'],
                'detalles' => $row['detalle_entrega'],
                'total_precio' => $row['total_precio'] + $valorDespacho,
                'mododepago' => $row['mododepago'],
                'cliente' => [
                    'rut' => $row['rut_cliente'],
                    'nombre' => $row['nombre'],
                    'telefono' => $row['telefono'],
                    'direccion' => $row['direccion'],
                    'lugar_venta' => $row['instagram']
                ],
                'detalle_pedidos' => [],
            ];
        }

        $total_precioproductos += $row['total_precio'];
        $totalPrecioTodosLosPedidos += $row['total_precio'];
        $totalPagado = $row['total_pagado'];

        $pedidos[$pedidoId]['detalle_pedidos'][] = [
            'id' => $row['id'],
            'modelo' => $row['modelo'],
            'tamano' => $row['tamano'],
            'alturabase' => $row['alturabase'],
            'estadopedido' => $row['estadopedido'],
            'nombre_estado' => $row['NombreProceso'], // Añadido
            'tapicero_id' => $row['tapicero_id'],
            'tela' => $row['tipotela'],
            'color' => $row['color'],
            'cantidad' => $row['cantidad'],
            'precio' => $row['precio'],
            'abono' => $row['abono'],
            'referencia' => $row['detalle_entrega'],
            'detalles' => $row['detalle_entrega'],
            'tipo_boton' => $row['tipo_boton'],
            'anclaje' => $row['anclaje'],
            'comentarios' => $row['comentarios'],
            'cod_ped_anterior' => $row['cod_ped_anterior'],
        ];
    }

    return [$pedidos, $totalPrecioTodosLosPedidos, $totalPagado, $total_precioproductos];
}

function manejarDefault($id, $conn)
{
    // Devuelve valores vacíos o predeterminados para evitar errores
    return [[], 0, 0, 0];
}

// Lógica principal
$pedidos = [];
$totalPrecioTodosLosPedidos = 0;
$totalPagado = 0;
$total_precioproductos = 0;

if (isset($_GET['method'])) {
    if ($_GET['method'] == 'num_orden' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $ruta = $_POST['ruta'];
        [$pedidos, $totalPrecioTodosLosPedidos, $totalPagado, $total_precioproductos] = manejarNumOrden($id, $ruta, $conn);
    }
} else {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        [$pedidos, $totalPrecioTodosLosPedidos, $totalPagado, $total_precioproductos] = manejarDefault($id, $conn);
    }
}

// Enviar respuesta
enviarRespuesta($pedidos, $totalPrecioTodosLosPedidos, $totalPagado, $total_precioproductos);

function enviarRespuesta($pedidos, $totalPrecioTodosLosPedidos, $totalPagado, $total_precioproductos)
{
    $respuesta = [
        'pedidos' => array_values($pedidos), // Re-indexar el array para asegurar que sea un array JSON
        'totalPrecioTodosLosPedidos' => $totalPrecioTodosLosPedidos,
        'totalPagado' => $totalPagado,
        'total_precioproductos' => $total_precioproductos,
    ];
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}