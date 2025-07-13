<?php
header('Content-Type: application/json');
include_once '../../online/bd/conexion.php';

$response = ['success' => false, 'message' => 'Número de orden no proporcionado.', 'data' => null];

if (isset($_POST['num_orden'])) {
    $num_orden = $_POST['num_orden'];

    try {
        $objeto = new Conexion();
        $conexion = $objeto->Conectar();

        // Consulta principal para obtener los datos de la orden y del cliente
        $consulta_principal = "SELECT p.*, c.nombre, c.instagram, c.telefono, pd.direccion, pd.numero, pd.dpto, pd.comuna FROM pedido p JOIN clientes c ON p.rut_cliente = c.rut JOIN pedido_detalle pd ON p.num_orden = pd.num_orden WHERE p.num_orden = :num_orden";
        $resultado_principal = $conexion->prepare($consulta_principal);
        $resultado_principal->bindParam(':num_orden', $num_orden);
        $resultado_principal->execute();
        $orden_data = $resultado_principal->fetch(PDO::FETCH_ASSOC);

        if ($orden_data) {
            // Consulta para obtener los detalles de los productos de la orden
            $consulta_detalles = "SELECT * FROM pedido_detalle WHERE num_orden = :num_orden";
            $resultado_detalles = $conexion->prepare($consulta_detalles);
            $resultado_detalles->bindParam(':num_orden', $num_orden);
            $resultado_detalles->execute();
            $detalles_data = $resultado_detalles->fetchAll(PDO::FETCH_ASSOC);

            // Consulta para obtener los pagos asociados a la orden
            $consulta_pagos = "SELECT * FROM pagos p LEFT JOIN cartola_bancaria c ON p.id_cartola = c.id WHERE num_orden = :num_orden"; // Asumiendo que tienes una tabla de pagos
            $resultado_pagos = $conexion->prepare($consulta_pagos);
            $resultado_pagos->bindParam(':num_orden', $num_orden);
            $resultado_pagos->execute();
            $pagos_data = $resultado_pagos->fetchAll(PDO::FETCH_ASSOC);

            // Combinar todos los datos en una sola respuesta
            $orden_data['detalles'] = $detalles_data;
            $orden_data['pagos'] = $pagos_data;

            $response['success'] = true;
            $response['message'] = 'Datos de la orden obtenidos con éxito.';
            $response['data'] = $orden_data;
        } else {
            $response['message'] = 'No se encontró ninguna orden con el número proporcionado.';
        }

    } catch (Exception $e) {
        $response['message'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
    }
} 

echo json_encode($response);
?>
