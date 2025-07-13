<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();


$modulo = $_POST['modulo'];

if($modulo =="dashboard"){
$consulta = "SELECT * FROM pedido p 
INNER JOIN pedido_detalle d ON p.num_orden = d.num_orden
INNER JOIN clientes c ON p.rut_cliente = c.rut
WHERE d.estadopedido NOT IN ('100', '404') AND (d.ruta_asignada = 0 OR d.ruta_asignada = '') AND d.metodo_entrega != 'Retiro en tienda' 
ORDER BY  d.num_orden,d.id,p.rut_cliente DESC";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data=$resultado->fetchAll(PDO::FETCH_ASSOC);
}

if($modulo == "dashboardretiro"){

    $consulta = "SELECT * FROM pedido p 
INNER JOIN pedido_detalle d ON p.num_orden = d.num_orden
INNER JOIN clientes c ON p.rut_cliente = c.rut
WHERE d.estadopedido NOT IN ('100', '404') AND d.metodo_entrega = 'Retiro en tienda' AND (d.ruta_asignada = 0 OR d.ruta_asignada = '') 
ORDER BY p.rut_cliente ASC";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data=$resultado->fetchAll(PDO::FETCH_ASSOC);
}


$pedidosOrganizados = [];

foreach ($data as $pedido) {
    $numOrden = $pedido['num_orden'];

    // Verificar si ya existe un pedido con este número de orden en el array resultante
    if (!isset($pedidosOrganizados[$numOrden])) {
        // Si no existe, crear un nuevo elemento en el array resultante
        $pedidosOrganizados[$numOrden] = $pedido;
        $pedidosOrganizados[$numOrden]['detalles'] = [];
    }
    
    // Agregar detalles a la subarray de detalles
    $detalle = [
        'id' => $pedido['id'],
        'modelo' => $pedido['modelo'],
        'tamano' => $pedido['tamano'],
        'alturabase' => $pedido['alturabase'],
        'estadopedido' => $pedido['estadopedido'],
        'tapicero_id' => $pedido['tapicero_id'],
        'material' => $pedido['tipotela'],
        'color' => $pedido['color'],
        'cantidad' => $pedido['cantidad'],
        'precio' => $pedido['precio'],
        'abono' => $pedido['abono'],
        'mododepago' => $pedido['mododepago'],
        'tipo_boton' => $pedido['tipo_boton'],
        'detalle_entrega' => $pedido['detalle_entrega'],
        'anclaje' => $pedido['anclaje'],
        'comentarios' => $pedido['comentarios'],
        'detalles_fabricacion' => $pedido['detalles_fabricacion'],
        'cod_ped_anterior' => $pedido['cod_ped_anterior'],
        
        // Agregar más campos según sea necesario
    ];

    $pedidosOrganizados[$numOrden]['detalles'][] = $detalle;
}

// $pedidosOrganizados ahora contendrá la estructura deseada
$response = ['data' => array_values($pedidosOrganizados)]; // Convertir a array de valores para asegurar el formato correcto
header('Content-Type: application/json');
echo json_encode($response);