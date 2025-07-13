<?php
include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

header('Content-Type: application/json');

$consulta = "SELECT id, usuario, nombres, apaterno, amaterno, rut, correo, privilegios FROM usuarios";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data = $resultado->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
