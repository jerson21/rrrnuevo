<?php
include_once '../../online/bd/conexion.php';
$log = "log.txt";
$startTime = microtime(true);
file_put_contents($log, "Ejecutado el " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
// El resto de tu script...
// Asumimos que este script se ejecuta con PHP CLI

// Utiliza cURL o cualquier otra librería/server-side para hacer la solicitud POST
$url = 'https://m5hs7lycaonjsw7jvjljgrxneu0eyoog.lambda-url.sa-east-1.on.aws/';
$data = array('clave' => 'valor'); // Reemplaza 'clave' y 'valor' con tus datos reales

$objeto2 = new Conexion();
$conn = $objeto2->Conectar();


// Consulta para obtener la fecha más reciente
$query = "SELECT MAX(STR_TO_DATE(fecha, '%d/%m/%Y')) AS ultima_fecha FROM cartola_bancaria";
$result = $conn->query($query);

$ultimaFecha = null;
if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $ultimaFecha = $row['ultima_fecha'];
    // Asumiendo que la fecha está en formato YYYY-MM-DD debido a la conversión de STR_TO_DATE
} // Fecha de fin, por ejemplo, la fecha actual (ajustar según necesidad)
// Cierra la conexión de la base de datos

if ($ultimaFecha !== null) {
    // Convertir la fecha a formato DateTime y sumar un día
    $fechaInicio = new DateTime($ultimaFecha);
    
    
    // Formatear la fecha de inicio para la solicitud
    $startDate = $fechaInicio->format('d/m/Y'); // Cambia a 'Y-m-d' si necesitas el formato año-mes-día
    
    // Asumir un endDate, por ejemplo, un mes después del startDate o cualquier lógica que prefieras
    $endDate = $endDate = date('d/m/Y'); // Ajusta según sea necesario
}

// Configura el array de datos para la solicitud cURL
$data = array(
    'clave' => 'valor', // Aquí puedes añadir más datos según sea necesario
    'searchType' => 'dateRange',
    'startDate' => $startDate,
    'endDate' => $endDate
);

echo "consultando datos ".$startDate." Hasta ".$endDate;

// Asegúrate de cerrar la conexión si ya no es necesaria
$conn = null;

// Inicia cURL
$ch = curl_init($url);

// Configura la solicitud POST
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Desactivar la verificación de SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Ejecuta la solicitud y captura la respuesta
$response = curl_exec($ch);
if ($response === false) {
    error_log('cURL error: ' . curl_error($ch));
} else {
    //error_log('Respuesta recibida: ' . $response);
}

// Cierra el recurso cURL
curl_close($ch);

// Procesa la respuesta
if ($response) {
$responseData = json_decode($response, true);
if (!isset($responseData['data'])) {
    error_log('Datos no encontrados en la respuesta');
} else {
  //  error_log('Datos decodificados: ' . print_r($responseData['data'], true));
}
    // Asegúrate de que la respuesta contiene los datos esperados
    if (isset($responseData['data'])) {
        $data = $responseData['data'];

        // Prepara los datos para enviar al script de inserción
        // Asegúrate de que 'insertar_datos.php' esté listo para manejar la entrada como desees
       // $insertUrl = 'https://respaldoschile.cl/online/dashboard/rbot/insertar_datos.php';
        
        $insertUrl = '../api/insertar_datos.php';  // MODO PRUEBAS LOCALES.
        $insertCh = curl_init($insertUrl);
        curl_setopt($insertCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($insertCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($insertCh, CURLOPT_POST, true);
        curl_setopt($insertCh, CURLOPT_POSTFIELDS, json_encode(array('data' => $data)));

        $insertResponse = curl_exec($insertCh);
        curl_close($insertCh);

        // Procesa la respuesta de la inserción
        echo "Respuesta de inserción: " . $insertResponse . "\n";
    } else {
        echo "Datos no encontrados en la respuesta\n";
    }
} else {
    echo "Error al realizar la solicitud\n";
}

// Al final del script, calcula la duración y guarda en el log
$endTime = microtime(true);
$executionTime = $endTime - $startTime; // Duración en segundos

// Ahora también guardamos la duración de la ejecución en el log
file_put_contents($log, "Ejecutado el " . date("Y-m-d H:i:s") . " - Duración: " . $executionTime . " segundos\n", FILE_APPEND);