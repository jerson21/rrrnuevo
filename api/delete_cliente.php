<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rut = $_POST['rut'] ?? '';

    if (empty($rut)) {
        $response['message'] = 'RUT de cliente no proporcionado.';
    } else {
        $consulta = "DELETE FROM clientes WHERE rut = ?";
        $stmt = $conexion->prepare($consulta);

        if ($stmt->execute([$rut])) {
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Cliente eliminado exitosamente.';
            } else {
                $response['message'] = 'No se encontró el cliente o no se pudo eliminar.';
            }
        } else {
            $response['message'] = 'Error al eliminar cliente: ' . $stmt->errorInfo()[2];
        }
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);

$conexion = null;
?>