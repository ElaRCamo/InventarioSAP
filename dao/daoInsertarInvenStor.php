<?php
include_once('connection.php');
require_once ('funcionesInvenStorage.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['invenStorDatos']) && is_array($inputData['invenStorDatos'])) {
        $todosExitosos = true;
        $errores = [];
        $combinaciones = []; // Se inicializa para agrupar por GrammerNo, StBin, StType


        foreach ($inputData['invenStorDatos'] as $registroInventario) {
            // Validar y asignar valores
            $STLocation = isset($registroInventario['STLocation']) ? trim($registroInventario['STLocation']) : null;
            $StBin = isset($registroInventario['STBin']) ? trim($registroInventario['STBin']) : null;
            $StType = isset($registroInventario['STType']) ? trim($registroInventario['STType']) : null;
            $GrammerNo = isset($registroInventario['GrammerNo']) ? trim($registroInventario['GrammerNo']) : null;
            $Cantidad = isset($registroInventario['Cantidad']) ? trim($registroInventario['Cantidad']) : null;
            $AreaCve = isset($registroInventario['AreaCVe']) ? trim($registroInventario['AreaCVe']) : null;
            $id_StorageUnit = isset($registroInventario['Id_StorageUnit']) ? trim($registroInventario['Id_StorageUnit']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($GrammerNo === null || $STLocation === null || $Cantidad === null || $AreaCve === null) {
                $errores[] = "Faltan datos para el registro GrammerNo: $GrammerNo, STLocation: $STLocation, StBin: $StBin, StType: $StType, Cantidad: $Cantidad, AreaCve: $AreaCve";
                $todosExitosos = false;
                continue; // Saltar al siguiente registro si hay errores
            }

            // Convertir $Cantidad a float
            $Cantidad = (float)$Cantidad;

            // Si existe id_StorageUnit, se agrupan los registros
            if ($id_StorageUnit !== null && $id_StorageUnit !== '') {
                // Crear clave única para identificar la combinación
                $key = $GrammerNo . '|' . $StBin . '|' . $StType . '|' . $STLocation . '|' . $AreaCve;

                // Verificar si la combinación ya existe en el array de combinaciones
                if (!isset($combinaciones[$key])) {
                    $combinaciones[$key] = [
                        'GrammerNo' => $GrammerNo,
                        'StBin' => $StBin,
                        'StType' => $StType,
                        'AreaCve' => $AreaCve,
                        'STLocation' => $STLocation,
                        'Cantidad' => 0  // Inicializar la cantidad para la combinación
                    ];
                }

                // Sumar la cantidad correspondiente a la combinación
                $combinaciones[$key]['Cantidad'] += $Cantidad;

                // Llamar a insertarRegistrosStorage para cada registro de StorageUnit
                $respuestaInsert = insertarRegistrosStorage($id_StorageUnit, $GrammerNo, $Cantidad, $StBin, $StType);

                // Verificar respuesta de la inserción en Storage
                if (!$respuestaInsert) {
                    $errores[] = "Error al insertar en Storage: GrammerNo: $GrammerNo, StBin: $StBin, StType: $StType, Cantidad: $Cantidad, AreaCve: $AreaCve, STLocation: $STLocation";
                    $todosExitosos = false;
                    continue; // Continuar con el siguiente registro si hubo error
                }

            } else {
                // Si no existe id_StorageUnit, insertar en Inventario directamente
                $respuestaInsert = insertarRegistrosInventario($GrammerNo, $STLocation, $StBin, $StType, $Cantidad, $AreaCve);

                // Verificar respuesta de la inserción en Inventario
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro GrammerNo: $GrammerNo. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    continue; // Continuar con el siguiente registro si hubo error
                }
            }
        }

        // Insertar las combinaciones agrupadas en Inventario después de procesar todos los registros
        foreach ($combinaciones as $data) {
            $respuestaInsert = insertarRegistrosInventario(
                $data['GrammerNo'],
                $data['STLocation'],
                $data['StBin'],
                $data['StType'],
                $data['Cantidad'],
                $data['AreaCve']
            );

            // Verificar respuesta de la inserción en Inventario para combinaciones agrupadas
            if ($respuestaInsert['status'] !== 'success') {
                $errores[] = "Error al insertar en Inventario: " . implode(', ', $data);
                $todosExitosos = false;
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros fueron actualizados correctamente. :D", "detalles" => $errores);
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



/*

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['invenStorDatos']) && is_array($inputData['invenStorDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['invenStorDatos'] as $registroInventario) {
            // Validar y asignar valores
            $STLocation = isset($registroInventario['STLocation']) ? trim($registroInventario['STLocation']) : null;
            $StBin = isset($registroInventario['STBin']) ? trim($registroInventario['STBin']) : null;
            $StType = isset($registroInventario['STType']) ? trim($registroInventario['STType']) : null;
            $GrammerNo = isset($registroInventario['GrammerNo']) ? trim($registroInventario['GrammerNo']) : null;
            $Cantidad = isset($registroInventario['Cantidad']) ? trim($registroInventario['Cantidad']) : null;
            $AreaCve = isset($registroInventario['AreaCVe']) ? trim($registroInventario['AreaCVe']) : null;
            $id_StorageUnit = isset($registroInventario['Id_StorageUnit']) ? trim($registroInventario['Id_StorageUnit']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($GrammerNo === null || $STLocation === null || $Cantidad === null || $AreaCve === null) {
                $errores[] = "Faltan datos para el registro GrammerNo: $GrammerNo, STLocation: $STLocation, StBin: $StBin, StType: $StType, Cantidad: $Cantidad, AreaCve: $AreaCve ";
                $todosExitosos = false;
            } else {
                if ($id_StorageUnit !== null && $id_StorageUnit !== '') {
                    $respuestaInsert = insertarRegistrosStorage($id_StorageUnit, $GrammerNo, $Cantidad, $StBin, $StType);
                } else {
                    $respuestaInsert = insertarRegistrosInventario($GrammerNo, $STLocation, $StBin, $StType, $Cantidad, $AreaCve);
                }

                /*$GrammerNo = $Numero_Parte
                 * $StBin = $Storage_Bin
                 * $StType = $Storage_Type
                 * /

if ($respuestaInsert['status'] !== 'success') {
    $errores[] = "Error al insertar el registro ID: $GrammerNo. " . $respuestaInsert['message'];
    $todosExitosos = false;
    break;  // Salir del ciclo si ocurre un error
}
}
}

// Respuesta final si todos fueron exitosos
if ($todosExitosos) {
    $respuesta = actualizarInventario();
    //$respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla InventarioSAP y Storage Unit fueron actualizados correctamente.",  "detalles" => $errores);
} else {
    $respuesta = array("status" => 'error', "message" => "Se encontraron errores al insertar los registros.", "detalles" => $errores);
}
} else {
    $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
}
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

*/

?>