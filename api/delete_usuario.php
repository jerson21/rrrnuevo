<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        $response['message'] = 'ID de usuario no proporcionado.';
    } else {
        $consulta = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($consulta);

        if ($stmt->execute([$id])) {
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Usuario eliminado exitosamente.';
            } else {
                $response['message'] = 'No se encontró el usuario o no se pudo eliminar.';
            }
        } else {
            $response['message'] = 'Error al eliminar usuario: ' . $stmt->errorInfo()[2];
        }
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);

$conexion = null;
?>
