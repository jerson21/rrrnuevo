<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $apaterno = $_POST['apaterno'] ?? '';
    $amaterno = $_POST['amaterno'] ?? '';
    $rut = $_POST['rut'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $privilegios = $_POST['privilegios'] ?? 0;

    // Basic validation
    if (empty($usuario) || empty($nombres) || empty($apaterno) || empty($rut) || empty($correo) || empty($password)) {
        $response['message'] = 'Todos los campos obligatorios deben ser llenados.';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL statement
        $consulta = "INSERT INTO usuarios (usuario, password, nombres, apaterno, amaterno, rut, correo, privilegios) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($consulta);
        
        if ($stmt->execute([$usuario, $hashed_password, $nombres, $apaterno, $amaterno, $rut, $correo, $privilegios])) {
            $response['success'] = true;
            $response['message'] = 'Usuario creado exitosamente.';
        } else {
            $response['message'] = 'Error al crear usuario: ' . $stmt->errorInfo()[2];
        }
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);

$conexion = null;
?>
