<?php
include_once('db/db_Inventario.php');

$Id_StorageUnit = $_POST['Id_StorageUnit'];
$Numero_Parte = $_POST['Numero_Parte'];
$Cantidad = $_POST['Cantidad'];
$Storage_Bin = $_POST['Storage_Bin'];
$Storage_Type = $_POST['Storage_Type'];
$Estatus = $_POST['Estatus'];
$FolioMarbete = $_POST['FolioMarbete'];
$Conteo = $_POST['Conteo'];

insertStorageUnit($Id_StorageUnit, $Numero_Parte, $Cantidad, $Storage_Bin, $Storage_Type, $Estatus, $FolioMarbete, $Conteo);

function insertStorageUnit($Id_StorageUnit, $Numero_Parte, $Cantidad, $Storage_Bin, $Storage_Type, $Estatus, $FolioMarbete, $Conteo){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Storage_Unit (Id_StorageUnit, Numero_Parte, Cantidad, Storage_Bin, Storage_Type, Estatus, FolioMarbete, Conteo) 
                       VALUES ('$Id_StorageUnit', '$Numero_Parte', '$Cantidad', '$Storage_Bin', '$Storage_Type', '$Estatus', '$FolioMarbete', '$Conteo');";

    $rsinsertStorageUnit = mysqli_query($conex, $insertRegistro);
    mysqli_close($conex);

    if (!$rsinsertStorageUnit) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>