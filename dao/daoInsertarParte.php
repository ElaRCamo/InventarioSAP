<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['parteDatos']) && is_array($inputData['parteDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['parteDatos'] as $registroParte) {
            // Validar y asignar valores
            $GrammerNo = isset($registroParte['GrammerNo']) ? trim($registroParte['GrammerNo']) : null;
            $Descripcion = isset($registroParte['Descripcion']) ? trim($registroParte['Descripcion']) : null;
            $UM = isset($registroParte['UM']) ? trim($registroParte['UM']) : null;
            $ProfitCtr = isset($registroParte['ProfitCtr']) ? trim($registroParte['ProfitCtr']) : null;
            $Costo = isset($registroParte['Costo']) ? (float)trim($registroParte['Costo']) : null;
            $Por = isset($registroParte['Por']) ? trim($registroParte['Por']) : null;

            // Validar datos
            if (empty($GrammerNo) || empty($Descripcion) || empty($UM) || empty($ProfitCtr) || $Costo === null || empty($Por)) {
                $errores[] = "Faltan datos para el registro ID: $GrammerNo.";
                $todosExitosos = false;
            } else {
                // Llamar a la función de actualización
                $respuestaInsert = insertarRegistrosParte($GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro ID: $GrammerNo. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        $respuesta = $todosExitosos
            ? array("status" => 'success', "message" => "Todos los registros en la Tabla Parte fueron insertados correctamente.")
            : array("status" => 'error', "message" => "Se encontraron errores al insertar los registros.", "detalles" => $errores);
    } else {
        $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
    }
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

echo json_encode($respuesta);

function insertarRegistrosParte($GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Inicializar respuesta por defecto como exitosa
    $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');

    $conex->begin_transaction();

    try {
        $insertParte = $conex->prepare("INSERT INTO `Parte` (`GrammerNo`, `Descripcion`, `UM`, `ProfitCtr`, `Costo`, `Por`) VALUES (?, ?, ?, ?, ?, ?)");
        $insertParte->bind_param("ssssfi", $GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por);
        $resultado = $insertParte->execute();

        if (!$resultado) {
            $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con GrammerNo: ' . $GrammerNo);
            $conex->rollback();
        } else {
            $conex->commit();
        }
    } catch (Exception $e) {
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => 'Error de transacción: ' . $e->getMessage());
    } finally {
        $conex->close();
    }

    return $respuesta;
}
?>