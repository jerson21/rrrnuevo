<?php
$log = "log.txt";
// Registrar el inicio de la operación de inserción
file_put_contents($log, "Ejecutada la inserción el" . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

// Conexión a la base de datos
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conn = $objeto->Conectar();

// Recepción de datos en formato JSON
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data) || !isset($data['data'])) {
    echo "No se recibieron datos.";
    exit;
}

// Preparación de la consulta para insertar datos
$stmt = $conn->prepare("INSERT INTO cartola_bancaria (fecha, fecha_date, id, rut, nombre, banco, numero, monto, detalle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Iniciar la transacción
$conn->beginTransaction();

try {
    foreach ($data['data'] as $row) {
        $fecha = $row[0];
        list($dia, $mes, $año) = explode('/', $fecha);
        $fecha_formateada = "$año-$mes-$dia"; // Formato 'YYYY-MM-DD'

        // Verificar si el registro ya existe para evitar duplicados
        $query = $conn->prepare("SELECT COUNT(*) FROM cartola_bancaria WHERE id = ?");
        $query->execute([$row[1]]);
        if ($query->fetchColumn() == 0) {
            // Ejecutar la inserción si no existe el registro
            $stmt->execute([$fecha, $fecha_formateada, $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]]);
            echo "Insertando datos para ID: " . $row[1] . "\n";
        }
    }

    // Confirmar todas las inserciones si no hubo errores
    $conn->commit();
    echo "Todos los datos fueron insertados correctamente.\n";
} catch (Exception $e) {
    // Revertir la transacción si ocurre algún error durante la inserción
    $conn->rollBack();
    error_log("Error al insertar en lote: " . $e->getMessage());
    echo "Error al insertar datos.\n";
}
?>