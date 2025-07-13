<?php
include_once '../../online/bd/conexion.php'; // Se mantiene si en algún otro flujo se requiera la conexión
$log = "log.txt";
$startTime = microtime(true);
file_put_contents($log, "Ejecutado el " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

// Este script se invoca vía AJAX. Se espera recibir un JSON con el parámetro "mes".
// Ejemplo de datos recibidos:
// {
//   "mes": "01"
// }

// Obtener el JSON de entrada
$input = file_get_contents("php://input");
$dataInput = json_decode($input, true);

// Validar que se recibió el parámetro "mes"
if (!isset($dataInput['mes'])) {
    echo "Error: No se recibió el parámetro 'mes'.";
    exit;
}

$mes = $dataInput['mes'];

// Construir el array de datos para enviar a la función Lambda (solo se busca por mes)
$data = array(
    'clave'      => 'valor', // Reemplaza 'clave' y 'valor' según corresponda
    'searchType' => 'dateRange',
    'mes'        => $mes
);

echo "Consultando datos para el mes: " . $mes . "\n";

// URL de la función Lambda
$url = 'https://2jjszgxsvu4fgd24zj3t5piisy0nnypo.lambda-url.sa-east-1.on.aws/';

// Inicia cURL para hacer la solicitud POST a Lambda
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
if ($response === false) {
    error_log('cURL error: ' . curl_error($ch));
}
curl_close($ch);

// Procesa la respuesta de Lambda
if ($response) {
    $responseData = json_decode($response, true);
    if (!isset($responseData['data'])) {
        error_log('Datos no encontrados en la respuesta');
    }
    if (isset($responseData['data'])) {
        $data = $responseData['data'];

        // Prepara los datos para enviar al script de inserción
        // Asegúrate de que 'insertar_datos.php' esté listo para manejar la entrada como desees
       //  $insertUrl = 'https://respaldoschile.cl/online/dashboard/rbot/insertar_datos.php';  // PRODUCCIÓN
        $insertUrl = '../api/insertar_datos.php';  // MODO PRUEBAS LOCALES.
        $insertCh = curl_init($insertUrl);
        curl_setopt($insertCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($insertCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($insertCh, CURLOPT_POST, true);
        curl_setopt($insertCh, CURLOPT_POSTFIELDS, json_encode(array('data' => $data)));

        $insertResponse = curl_exec($insertCh);
        curl_close($insertCh);

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
file_put_contents($log, "Ejecutado el " . date("Y-m-d H:i:s") . " - Duración: " . $executionTime . " segundos\n", FILE_APPEND);
?>