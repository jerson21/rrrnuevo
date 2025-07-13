<?php
include("../../online/bd/conexion.php");
$objeto = new Conexion();
$conn = $objeto->Conectar();

if (isset($_POST['id_detalle']) && isset($_POST['estado_id'])) {
    $id_detalle = $_POST['id_detalle'];
    $estado_id = $_POST['estado_id'];

    try {
        $stmt = $conn->prepare("UPDATE pedido_detalle SET estadopedido = ? WHERE id = ?");
        $stmt->bindParam(1, $estado_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_detalle, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Estado actualizado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo actualizar el estado o el estado ya era el mismo."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error de base de datos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
}
?>