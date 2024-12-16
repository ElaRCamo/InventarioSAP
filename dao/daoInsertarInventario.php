<?php
include_once('connection.php');
require_once('funcionesInvenStorage.php');


// Lógica principal para manejar la solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['inventarioDatos']) && is_array($inputData['inventarioDatos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['inventarioDatos'] as $registroInventario) {
            // Validar y asignar valores de los registros
            $noWarehouse = isset($registroInventario['noWarehouse']) ? trim($registroInventario['noWarehouse']) : null;
            $inventoryRec = isset($registroInventario['inventoryRec']) ? trim($registroInventario['inventoryRec']) : null;
            $inventoryItem = isset($registroInventario['inventoryItem']) ? trim($registroInventario['inventoryItem']) : null;
            $quant = isset($registroInventario['quant']) ? trim($registroInventario['quant']) : null;
            $inventoryPage = isset($registroInventario['inventoryPage']) ? trim($registroInventario['inventoryPage']) : null;
            $storageType = isset($registroInventario['storageType']) ? trim($registroInventario['storageType']) : null;
            $storageBin = isset($registroInventario['storageBin']) ? trim($registroInventario['storageBin']) : null;
            $binPos = isset($registroInventario['binPos']) ? trim($registroInventario['binPos']) : null;
            $noMaterial = isset($registroInventario['noMaterial']) ? trim($registroInventario['noMaterial']) : null;
            $plant = isset($registroInventario['plant']) ? trim($registroInventario['plant']) : null;
            $batch = isset($registroInventario['batch']) ? trim($registroInventario['batch']) : null;
            $storageUnit = isset($registroInventario['storageUnit']) ? trim($registroInventario['storageUnit']) : null;
            $counted = isset($registroInventario['counted']) ? trim($registroInventario['counted']) : null;
            $baseUnit = isset($registroInventario['baseUnit']) ? trim($registroInventario['baseUnit']) : null;
            $totalStorage = isset($registroInventario['totalStorage']) ? trim($registroInventario['totalStorage']) : null;

            // Validar que los datos esenciales no sean nulos o vacíos
            if ($noMaterial === null || $storageType === null || $storageBin === null) {
                $errores[] = "Faltan datos para el registro NoMaterial: $noMaterial, StorageType: $storageType, StorageBin: $storageBin";
                $todosExitosos = false;
            } else {
                // Llamar a la función de inserción con los datos del registro
                $respuestaInsert = insertarRegistrosInventory($noWarehouse, $inventoryRec, $inventoryItem, $quant, $inventoryPage,
                    $storageType, $storageBin, $binPos, $noMaterial, $plant, $batch,
                    $storageUnit, $counted, $baseUnit, $totalStorage);
                if ($respuestaInsert['status'] !== 'success') {
                    $errores[] = "Error al insertar el registro NoMaterial: $noMaterial. " . $respuestaInsert['message'];
                    $todosExitosos = false;
                    break; // Salir del ciclo si ocurre un error
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los registros fueron insertados correctamente.");
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
// Función para insertar un registro en la tabla Inventory
function insertarRegistrosInventory($noWarehouse, $inventoryRec, $inventoryItem, $quant, $inventoryPage,
                                     $storageType, $storageBin, $binPos, $noMaterial, $plant, $batch,
                                     $storageUnit, $counted, $baseUnit, $totalStorage) {
    global $conn;

    // Preparar la consulta SQL
    $query = "INSERT INTO Inventory (noWarehouse, inventoryRec, inventoryItem, quant, inventoryPage, 
            storageType, storageBin, binPos, noMaterial, plant, batch, storageUnit, counted, baseUnit, totalStorage)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar declaración
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        return array('status' => 'error', 'message' => 'Error al preparar la consulta: ' . $conn->error);
    }

    // Enlace de parámetros y ejecución de la consulta
    $stmt->bind_param('ssiiisssiiiisis',
        $noWarehouse, $inventoryRec, $inventoryItem, $quant, $inventoryPage,
        $storageType, $storageBin, $binPos, $noMaterial, $plant, $batch, $storageUnit, $counted, $baseUnit, $totalStorage
    );

    // Ejecutar la consulta
    if ($stmt->execute()) {
        return array('status' => 'success', 'message' => 'Registro insertado correctamente');
    } else {
        return array('status' => 'error', 'message' => 'Error al insertar el registro: ' . $stmt->error);
    }
}

?>