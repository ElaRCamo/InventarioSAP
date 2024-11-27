<?php
include_once('db/db_Inventario.php');

$Id_Marbete = $_POST['Id_Marbete'];
$Numero_Parte = $_POST['Numero_Parte'];
$Estatus = $_POST['Estatus'];
$StorageBin = $_POST['StorageBin'];
$Fecha = $_POST['Fecha'];
$Area = $_POST['Area'];

insertMarbeteInventario($Id_Marbete, $Numero_Parte, $Estatus, $StorageBin, $Fecha, $Area);

function insertMarbeteInventario($Id_Marbete, $Numero_Parte, $Estatus, $StorageBin, $Fecha, $Area){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Marbete_Inventario (Id_Marbete, Numero_Parte, Estatus, StorageBin, Fecha, Area) 
                       VALUES ('$Id_Marbete', '$Numero_Parte', '$Estatus', '$StorageBin', '$Fecha', '$Area');";

    $rsinsertMarbete = mysqli_query($conex, $insertRegistro);

    mysqli_close($conex);

    if (!$rsinsertMarbete) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>