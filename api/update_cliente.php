<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $original_rut = $_POST['original_rut'] ?? '';
    $rut = $_POST['rut'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $instagram = $_POST['instagram'] ?? '';
    $correo = $_POST['correo'] ?? '';

    // Basic validation
    if (empty($original_rut) || empty($rut) || empty($nombre)) {
        $response['message'] = 'RUT original, RUT y Nombre son campos obligatorios.';
    } else {
        // Check if new RUT already exists and is not the original RUT
        if ($original_rut !== $rut) {
            $consulta = "SELECT COUNT(*) FROM clientes WHERE rut = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->execute([$rut]);
            if ($stmt->fetchColumn() > 0) {
                $response['message'] = 'El nuevo RUT ya está registrado para otro cliente.';
                echo json_encode($response);
                $conexion = null;
                exit();
            }
        }

        $consulta = "UPDATE clientes SET rut=?, nombre=?, telefono=?, instagram=?, correo=? WHERE rut=?";
        $stmt = $conexion->prepare($consulta);
        
        if ($stmt->execute([$rut, $nombre, $telefono, $instagram, $correo, $original_rut])) {
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Cliente actualizado exitosamente.';
            } else {
                $response['message'] = 'No se realizó ningún cambio o el cliente no fue encontrado.';
            }
        } else {
            $response['message'] = 'Error al actualizar cliente: ' . $stmt->errorInfo()[2];
        }
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);

$conexion = null;
?>