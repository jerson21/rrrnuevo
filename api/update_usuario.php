<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $apaterno = $_POST['apaterno'] ?? '';
    $amaterno = $_POST['amaterno'] ?? '';
    $rut = $_POST['rut'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? ''; // New password, can be empty
    $privilegios = $_POST['privilegios'] ?? 0;

    // Basic validation
    if (empty($id) || empty($usuario) || empty($nombres) || empty($apaterno) || empty($rut) || empty($correo)) {
        $response['message'] = 'Todos los campos obligatorios deben ser llenados.';
    } else {
        $query = "UPDATE usuarios SET usuario=?, nombres=?, apaterno=?, amaterno=?, rut=?, correo=?, privilegios=?";
        $params = [$usuario, $nombres, $apaterno, $amaterno, $rut, $correo, $privilegios];

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", password=?";
            $params[] = $hashed_password;
        }

        $query .= " WHERE id=?";
        $params[] = $id;

        $stmt = $conexion->prepare($query);
        
        if ($stmt->execute($params)) {
            $response['success'] = true;
            $response['message'] = 'Usuario actualizado exitosamente.';
        } else {
            $response['message'] = 'Error al actualizar usuario: ' . $stmt->errorInfo()[2];
        }
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);

$conexion = null;
?>
