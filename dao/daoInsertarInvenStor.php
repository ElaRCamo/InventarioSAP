<?php
include_once('connection.php');
require_once ('funcionesInvenStorage.php');

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

                if ($id_StorageUnit === null){
                    $respuestaInsert = insertarRegistrosInventario($GrammerNo, $STLocation, $StBin, $StType, $Cantidad, $AreaCve);
                }else{
                    $respuestaInsert = insertarRegistrosStorage($id_StorageUnit, $GrammerNo, $Cantidad, $StBin, $StType);
                }

                /*$GrammerNo = $Numero_Parte
                 * $StBin = $Storage_Bin
                 * $StType = $Storage_Type
                 * */


                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro ID: $GrammerNo. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break;  // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros en la Tabla InventarioSAP y Storage Unit fueron actualizados correctamente.");
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

?>