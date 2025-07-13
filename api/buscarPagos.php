<?php
if(!isset($_SESSION)) { session_start(); }
$user = $_SESSION["s_usuario"];

include_once '../../online/bd/conexion.php';
$objeto = new Conexion();
$conn = $objeto->Conectar();

// Captura el criterio y el valor de búsqueda enviados por AJAX
$criterio = isset($_GET['criterio']) ? $_GET['criterio'] : '';
$valor = isset($_GET['valor']) ? $_GET['valor'] : '';
$id_cartola = isset($_GET['id_cartola']) ? $_GET['id_cartola'] : '';  //ID CARTOLA
$funcion = isset($_GET['funcion']) ? $_GET['funcion'] : '';
$monto = isset($_GET['monto']) ? $_GET['monto'] : '';
$metodo_pago = isset($_GET['metodo_pago']) ? $_GET['metodo_pago'] : '';
$ordenAsoc = isset($_GET['num_orden']) ? $_GET['num_orden'] : '';

//DATOS ADICIONALES
$banco = isset($_GET['banco']) ? $_GET['banco'] : '';
$rut = isset($_GET['rut']) ? $_GET['rut'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$datos_adicionales = array('banco' => $banco,'nombre' => $nombre);
$datos_adicionales_json = json_encode($datos_adicionales);
$datos_adicionales_json;  

    if (!empty($funcion) && $funcion == "asociarPago") 
    {
        $consultaUpdate = "UPDATE cartola_bancaria SET orden_asoc = :ordenAsoc WHERE id = :idParaActualizar";
        $stmt = $conn->prepare($consultaUpdate);
        
        // Vincular parámetros
        $stmt->bindParam(':ordenAsoc', $ordenAsoc, PDO::PARAM_INT);
        $stmt->bindParam(':idParaActualizar', $id_cartola, PDO::PARAM_INT);
        
        // Ejecutar la consulta
        $stmt->execute();

            if ($stmt->rowCount() > 0) 
            {
                $fechaIngreso = date("Y-m-d H:i:s"); // Fecha y hora actuales        
                $consultaInsert = "INSERT INTO pagos (num_orden,metodo_pago,id_cartola,datos_adicionales,monto, usuario, fecha_mov) VALUES (:numOrden,:metodo_pago,:idCartola,:datos_adicionales,:monto, :usuario, :fechaIngreso)";
                
                $stmtInsert = $conn->prepare($consultaInsert);
                
                // Vincular parámetros para la inserción
                $stmtInsert->bindParam(':numOrden', $ordenAsoc, PDO::PARAM_INT);
                $stmtInsert->bindParam(':metodo_pago', $metodo_pago);
                $stmtInsert->bindParam(':idCartola', $id_cartola);
                $stmtInsert->bindParam(':datos_adicionales', $datos_adicionales_json);
                $stmtInsert->bindParam(':monto', $monto);
                $stmtInsert->bindParam(':usuario', $user);
                $stmtInsert->bindParam(':fechaIngreso', $fechaIngreso);

                // Ejecutar la consulta de inserción
                if ($stmtInsert->execute()) { $resultado = json_encode(["success" => true, "message" => "Inserción realizada con éxito"]);  }
                else { $resultado = json_encode(["success" => false, "message" => "Error al insertar en pagos_orden"]);  }
            }
    }
    
// ACA ABAJO SE DEBE HACER UNA CONDICIÓN PARA QUE NO ACTUALICE LA CARTOLA SI ES QUE NO ES ELIMINACIÓN DE PAGO CON CARTOLA.

    elseif (!empty($funcion) && $funcion == "eliminarPago") {
        $consultaDelete = "DELETE FROM pagos WHERE id = :valor";
        $stmtDelete = $conn->prepare($consultaDelete);
        $stmtDelete->bindParam(':valor', $valor, PDO::PARAM_INT);
        $stmtDelete->execute();
        
        if ($stmtDelete->rowCount() > 0) {
            $consultaUpdate = "UPDATE cartola_bancaria SET orden_asoc = 0 WHERE id = :idParaActualizar";
            $stmtUpdate = $conn->prepare($consultaUpdate);
            $stmtUpdate->bindParam(':idParaActualizar', $id_cartola, PDO::PARAM_INT);
            $stmtUpdate->execute();
            
            // Aquí podrías agregar una validación adicional para confirmar la actualización.
            $resultado = json_encode(["success" => true, "message" => "Pago eliminado y cartola actualizada con éxito"]);
        } else {
            $resultado = json_encode(["success" => false, "message" => "Error al eliminar el pago"]);
        }
    }



if (!empty($criterio) && !empty($valor)) {
    if ($criterio == "rut") {
        $consulta = "SELECT id, fecha, rut, nombre, banco, numero, monto, detalle, orden_asoc FROM cartola_bancaria WHERE rut = :valor AND (orden_asoc = '0' OR orden_asoc IS NULL)  ORDER BY fecha DESC";
    }
    
    elseif ($criterio == "numeroTransaccion") {
        $consulta = "SELECT id, fecha, rut, nombre, banco, numero, monto, detalle, orden_asoc FROM cartola_bancaria WHERE CAST(id AS CHAR) LIKE :valor
        AND (orden_asoc = '0' OR orden_asoc IS NULL)  ORDER BY fecha DESC";
    }
    elseif ($criterio == "rutTercero") {
        $consulta = "SELECT id, fecha, rut, nombre, banco, numero, monto, detalle, orden_asoc FROM cartola_bancaria WHERE rut = :valor AND (orden_asoc = '0' OR orden_asoc IS NULL) ORDER BY fecha DESC";
    }

    elseif ($criterio == "pagados") {
        $consulta = "SELECT id, fecha, rut, nombre, banco, numero, monto, detalle, orden_asoc FROM cartola_bancaria WHERE id = :valor AND (orden_asoc = '0' OR orden_asoc IS NULL) ORDER BY fecha DESC";
        
    }
    elseif ($criterio == "por_n_orden") {
        $consulta = "SELECT 
        po.id AS id,
        c.id As id_cartola,
        COALESCE(c.id, po.id) AS id_mostrar,
        COALESCE(c.fecha, po.fecha_mov) AS fecha,
        COALESCE(c.rut, '') AS rut, 
        COALESCE(c.nombre, '') AS nombre,
        COALESCE(c.banco, '') AS banco,
        COALESCE(c.numero, po.metodo_pago) AS numero,
        COALESCE(c.monto, po.monto) AS monto,
        COALESCE(c.detalle, '') AS detalle        
    FROM 
        pagos po
    LEFT JOIN 
        cartola_bancaria c ON po.id_cartola = c.id
    WHERE 
        po.num_orden = :valor
    ORDER BY 
        fecha DESC";
    }
        elseif ($criterio == "nombreTercero") {
        // Modificación para buscar por partes del nombre
        $palabrasClave = explode(' ', $valor);
        $consulta = "SELECT id, fecha, rut, nombre, banco, numero, monto, detalle, orden_asoc FROM cartola_bancaria WHERE (orden_asoc = '0' OR orden_asoc IS NULL)  AND ";
        $condiciones = [];
        foreach ($palabrasClave as $index => $palabra) {
            $condiciones[] = "nombre LIKE :valor_$index";
        }
        $consulta .= implode(' AND ', $condiciones);
        $consulta .= " ORDER BY fecha DESC";

        $stmt = $conn->prepare($consulta);

        // Vincular parámetros para cada palabra clave
        foreach ($palabrasClave as $index => $palabra) {
            $stmt->bindValue(":valor_$index", "%$palabra%");
        }
    } else {
        echo json_encode("criterio no conocido"); // Devuelve un array vacío si el criterio no es reconocido
        exit;
    }
    
    if ($criterio != "nombreTercero") {
        // Solo preparar y vincular para rut, ya que para nombreTercero se hace en el bucle
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
    }

    if ($criterio == "numeroTransaccion") {
        $valorBusqueda = $valor; // Este es el valor que estás buscando.
        $valorParametro = "%" . $valorBusqueda . "%"; // Añades los comodines alrededor del valor de búsqueda.


        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':valor', $valorParametro, PDO::PARAM_STR);
    }


    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} else {
    // Devuelve un mensaje de error o un array vacío si no se proporcionan criterio y valor
    echo json_encode($resultado);
}
?>