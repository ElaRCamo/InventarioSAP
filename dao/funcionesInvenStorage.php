<?php
include_once('connection.php');
function insertarRegistrosStorage($id_StorageUnit, $Numero_Parte, $Cantidad, $Storage_Bin, $Storage_Type) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `Storage_Unit` WHERE `Id_StorageUnit` = ?");
        $consultaExistente->bind_param("i", $id_StorageUnit);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateStorage = $conex->prepare("UPDATE `Storage_Unit` SET `Numero_Parte` = ?, `Cantidad` = ?, `Storage_Bin` = ?, `Storage_Type` = ? WHERE `id_StorageUnit` = ?");
            $updateStorage->bind_param("ssssi", $Numero_Parte, $Cantidad, $Storage_Bin, $Storage_Type, $id_StorageUnit);
            $resultado = $updateStorage->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con Storage_Unit: ' . $id_StorageUnit);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }
            $updateStorage->close();

        } else {
            // Si no existe, insertar el nuevo registro
            $insertStorage = $conex->prepare("INSERT INTO `Storage_Unit` (`id_StorageUnit`,`Numero_Parte`, `Cantidad`, `Storage_Bin`, `Storage_Type`) 
                                            VALUES (?, ?, ?, ?, ?)");
            $insertStorage->bind_param("issss", $id_StorageUnit, $Numero_Parte, $Cantidad, $Storage_Bin, $Storage_Type);

            $resultado = $insertStorage->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con Storage_Unit: ' . $id_StorageUnit);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro insertado correctamente.');
            }
            $insertStorage->close();
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


function insertarRegistrosInventario($GrammerNo, $STLocation, $StBin, $StType, $Cantidad, $AreaCve) {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    if($StBin === null){
        $StBin = "";
    }

    if($StType === null){
        $StType = "";
    }

    try {
        // Consultar si el registro ya existe
        $consultaExistente = $conex->prepare("SELECT * FROM `InventarioSap` WHERE `GrammerNo` = ? AND StBin = ? AND StType = ? AND STLocation = ?");
        $consultaExistente->bind_param("sssi", $GrammerNo,$StBin, $StType, $STLocation);
        $consultaExistente->execute();
        $consultaExistente->store_result();

        if ($consultaExistente->num_rows > 0) {
            // Si ya existe, se actualiza el registro
            $updateInventario = $conex->prepare("UPDATE `InventarioSap` SET `Cantidad` = ?, `AreaCve` = ? WHERE `GrammerNo` = ? AND StBin = ? AND StType = ? AND STLocation = ?");
            $updateInventario->bind_param("sssssi",  $Cantidad, $AreaCve, $GrammerNo, $StBin, $StType, $STLocation);
            $resultado = $updateInventario->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro con GrammerNo: ' . $GrammerNo . ', StBin: '. $StBin . ', StType:'. $StType .', STLocation: '.$STLocation);
            } else {
                $conex->commit();
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado correctamente.');
            }

            $updateInventario->close();

        } else {

            // Si no existe, insertar el nuevo registro
            $insertParte = $conex->prepare("INSERT INTO  `InventarioSap` (`STLocation`, `STBin`, `STType`, `GrammerNo`, `Cantidad`, `AreaCve`)
                                            VALUES (?, ?, ?, ?, ?, ?)");
            $insertParte->bind_param("isssss", $STLocation, $StBin, $StType, $GrammerNo, $Cantidad, $AreaCve);

            $resultado = $insertParte->execute();

            if (!$resultado) {
                $conex->rollback();
                $respuesta = array('status' => 'error', 'message' => 'Error en la BD al insertar el registro con GrammerNo: ' . $GrammerNo. ', StBin: '. $StBin . ', StType:'. $StType .', STLocation: '.$STLocation);
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


function actualizarInventario() {
    $conex = null; // Inicializar la variable para evitar errores en el catch
    try {
        $con = new LocalConector();
        $conex = $con->conectar();

        // Iniciar transacción
        $conex->begin_transaction();

        // Subconsulta para calcular la suma
        $querySum = "
            SELECT 
                s.Storage_Bin, 
                s.Storage_Type, 
                s.Numero_Parte, 
                SUM(s.Cantidad) AS totalCantidad 
            FROM 
                Storage_Unit s 
            JOIN 
                InventarioSap i 
            ON 
                s.Numero_Parte = i.GrammerNo 
                AND s.Storage_Bin = i.STBin 
                AND s.Storage_Type = i.STType 
            GROUP BY 
                s.Storage_Bin, s.Storage_Type, s.Numero_Parte
        ";

        // Actualizar los valores en InventarioSap
        $updateQuery = "
            UPDATE InventarioSap i
            JOIN (
                SELECT 
                    s.Storage_Bin, 
                    s.Storage_Type, 
                    s.Numero_Parte, 
                    SUM(s.Cantidad) AS totalCantidad 
                FROM 
                    Storage_Unit s 
                JOIN 
                    InventarioSap i 
                ON 
                    s.Numero_Parte = i.GrammerNo 
                    AND s.Storage_Bin = i.STBin 
                    AND s.Storage_Type = i.STType 
                GROUP BY 
                    s.Storage_Bin, s.Storage_Type, s.Numero_Parte
            ) suSummary
            ON 
                i.STBin = suSummary.Storage_Bin 
                AND i.STType = suSummary.Storage_Type 
                AND i.GrammerNo = suSummary.Numero_Parte
            SET 
                i.Cantidad = suSummary.totalCantidad
        ";

        // Ejecutar consulta
        if (!$conex->query($updateQuery)) {
            throw new Exception("Error al actualizar InventarioSap: " . $conex->error);
        }

        // Confirmar la transacción
        $conex->commit();
        $respuesta = array('status' => 'success', 'message' => "Los valores se actualizaron correctamente.");
    } catch (Exception $e) {
        if ($conex && $conex->errno) { // Verificar si $conex está definido y conectado
            $conex->rollback();
        }
        $respuesta = array('status' => 'error', 'message' => "Error: " . $e->getMessage());
    }

    return $respuesta;
}



/*
 * SELECT DEL UPDATE
 *
 * SELECT
    i.STBin,
    i.STType,
    i.GrammerNo,
    suSummary.totalCantidad
FROM
    InventarioSap i
JOIN (
    SELECT
        s.Storage_Bin,
        s.Storage_Type,
        s.Numero_Parte,
        SUM(s.Cantidad) AS totalCantidad
    FROM
        Storage_Unit s
    JOIN
        InventarioSap i
    ON
        s.Numero_Parte = i.GrammerNo
        AND s.Storage_Bin = i.STBin
        AND s.Storage_Type = i.STType
    GROUP BY
        s.Storage_Bin, s.Storage_Type, s.Numero_Parte
) AS suSummary
ON
    i.STBin = suSummary.Storage_Bin
    AND i.STType = suSummary.Storage_Type
    AND i.GrammerNo = suSummary.Numero_Parte;

 */


?>