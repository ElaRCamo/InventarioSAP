<?php
include_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['bitacoraDatos']) && is_array($inputData['bitacoraDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['bitacoraDatos'] as $registroBitacora) {
            // Validar y asignar valores
            $NumeroParte = isset($registroBitacora['NumeroParte']) ? trim($registroBitacora['NumeroParte']) : null;
            $FolioMarbete = isset($registroBitacora['FolioMarbete']) ? trim($registroBitacora['FolioMarbete']) : null;
            $StorageBin = isset($registroBitacora['StorageBin']) ? trim($registroBitacora['StorageBin']) : null;
            $StorageType = isset($registroBitacora['StorageType']) ? trim($registroBitacora['StorageType']) : null;
            $Area = isset($registroBitacora['Area']) ? trim($registroBitacora['Area']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($FolioMarbete === null || $Area === null ) {
                $errores[] = "Faltan datos para el registro FolioMarbete: $FolioMarbete, NumeroParte: $NumeroParte, Area: $Area, StorageType: $StorageType, StorageBin: $StorageBin ";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción
                $respuestaInsert = insertarRegistrosBitacora($NumeroParte, $FolioMarbete, $StorageBin, $StorageType, $Area);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro FolioMarbete: $FolioMarbete. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla Parte fueron actualizados correctamente.");
        } else {
            $respuesta = array("status" => 'error', "message" => "Se encontraron errores al insertar los registros.", "detalles" => $errores);
        }
    } else {
        $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
    }
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

echo json_encode($respuesta);


function insertarRegistrosBitacora($NumeroParte, $FolioMarbete, $StorageBin, $StorageType, $Area) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    $fechaHoy = date('Y-m-d H:i:s');

    if($NumeroParte === null){
        $NumeroParte = "";
    }
    if($StorageBin === null){
        $StorageBin = "";
    }
    if($StorageType === null){
        $StorageType = "";
    }

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Bitacora_Inventario` WHERE `FolioMarbete` = ?");
        $consultaExistente->bind_param("s", $FolioMarbete);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateParte = $conex->prepare("UPDATE `Bitacora_Inventario` SET `NumeroParte` = ?, `StorageBin` = ?, `StorageType` = ?, `Area` = ?, `Fecha` = ? WHERE `FolioMarbete` = ?");
            $updateParte->bind_param("ssssss", $NumeroParte, $StorageBin, $StorageType, $Area, $fechaHoy,$FolioMarbete );
            $resultado = $updateParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con FolioMarbete: ' . $FolioMarbete);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateParte->close();

        } else {
            // Si no existe, insertar el nuevo registro
            $insertParte = $conex->prepare("INSERT INTO `Parte` (`NumeroParte`, `StorageBin`, `StorageType`, `Area`, `Fecha`, `FolioMarbete`) 
                                            VALUES (?, ?, ?, ?, ?, ?)");
            $insertParte->bind_param("ssssss", $NumeroParte, $StorageBin, $StorageType, $Area, $fechaHoy, $FolioMarbete);

            $resultado = $insertParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con FolioMarbete: ' . $FolioMarbete);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
            }

            $insertParte->close();
        }

        $consultaExistente->close();

    } catch (Exception $e) {
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }

    return $respuesta;
}
?>