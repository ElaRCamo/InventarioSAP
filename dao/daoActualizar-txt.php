<?php
include_once('connection.php');

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data)) {
    echo json_encode(['error' => 'No se recibieron datos']);
    exit();
}

$con = new LocalConector();
$conexion = $con->conectar();

$updatedData = [];

foreach ($data as $record) {
    $stor_bin = mysqli_real_escape_string($conexion, $record['storBin']);
    $materialParte = mysqli_real_escape_string($conexion, $record['materialNo']);

    $consP = "SELECT PrimerConteo FROM Bitacora_Inventario WHERE SorageBin = '$stor_bin' AND NumeroParte = '$materialParte'";
    $rsconsPro = mysqli_query($conexion, $consP);

    if ($rsconsPro && $row = mysqli_fetch_assoc($rsconsPro)) {
        $updatedData[] = [
            'storBin' => $stor_bin,
            'materialNo' => $materialParte,
            'PrimerConteo' => $row['PrimerConteo']
        ];
    } else {
        $updatedData[] = [
            'storBin' => $stor_bin,
            'materialNo' => $materialParte,
            'PrimerConteo' => '0'
        ];
    }
}

// Cerrar conexión
mysqli_close($conexion);

// Enviar resultados al frontend
echo json_encode($updatedData);
?>