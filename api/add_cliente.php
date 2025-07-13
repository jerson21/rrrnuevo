<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rut = $_POST['rut'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $instagram = $_POST['instagram'] ?? '';
    $correo = $_POST['correo'] ?? '';

    // Basic validation
    if (empty($rut) || empty($nombre)) {
        $response['message'] = 'RUT y Nombre son campos obligatorios.';
    } else {
        // Check if client already exists
        $consulta = "SELECT COUNT(*) FROM clientes WHERE rut = ?";
        $stmt = $conexion->prepare($consulta);
        $stmt->execute([$rut]);
        if ($stmt->fetchColumn() > 0) {
            $response['message'] = 'El cliente con este RUT ya existe.';
        } else {
            $consulta = "INSERT INTO clientes (rut, nombre, telefono, instagram, correo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            
            if ($stmt->execute([$rut, $nombre, $telefono, $instagram, $correo])) {
                $response['success'] = true;
                $response['message'] = 'Cliente creado exitosamente.';
            } else {
                $response['message'] = 'Error al crear cliente: ' . $stmt->errorInfo()[2];
            }
        }
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);

$conexion = null;
?>