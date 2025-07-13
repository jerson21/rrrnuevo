<?php
if(!isset($_SESSION)) 
{ 
session_start();

}

include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conn = $objeto->Conectar();

$user = $_SESSION["s_usuario"];

$identificacion = isset($_GET['identificacion']) ? $_GET['identificacion'] : '';
$num_orden = isset($_GET['num_orden']) ? $_GET['num_orden'] : '';
$tipoPago = isset($_GET['tipoPago']) ? $_GET['tipoPago'] : '';
$monto = isset($_GET['monto']) ? $_GET['monto'] : '';



 $fechaIngreso = date("Y-m-d H:i:s"); // Fecha y hora actuales
 $datos_adicionales = array('identificacion' => $identificacion);
 $datos_adicionales_json = json_encode($datos_adicionales);
    
 $consultaInsert = "INSERT INTO pagos (num_orden,metodo_pago,datos_adicionales,monto, usuario, fecha_mov) VALUES (:numOrden,:metodo_pago,:datos_adicionales,:monto, :usuario, :fechaIngreso)";
 
 $stmtInsert = $conn->prepare($consultaInsert);


 // Vincular parámetros para la inserción
 $stmtInsert->bindParam(':numOrden', $num_orden, PDO::PARAM_INT);
 $stmtInsert->bindParam(':metodo_pago', $tipoPago);
 $stmtInsert->bindParam(':datos_adicionales', $datos_adicionales_json);
 $stmtInsert->bindParam(':monto', $monto);
 $stmtInsert->bindParam(':usuario', $user);
 $stmtInsert->bindParam(':fechaIngreso', $fechaIngreso);




 // Ejecutar la consulta de inserción
 if ($stmtInsert->execute()) {
    echo json_encode(["success" => true, "message" => "Inserción realizada con éxito"]);
 } else {
    echo json_encode(["success" => false, "message" => "Error al insertar en pagos_orden"]);
 }


 
// header('Content-Type: application/json');
// echo json_encode($respuesta);

?>