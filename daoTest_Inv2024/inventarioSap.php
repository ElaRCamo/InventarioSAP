<?php
include_once('db/db_Inventario.php');

$STLocation = $_POST['STLocation'];
$STBin = $_POST['STBin'];
$STType = $_POST['STType'];
$GrammerNo = $_POST['GrammerNo'];
$Cantidad = $_POST['Cantidad'];
$AreaCve = $_POST['AreaCve'];

insertInventarioSap($STLocation, $STBin, $STType, $GrammerNo, $Cantidad, $AreaCve);

function insertInventarioSap($STLocation, $STBin, $STType, $GrammerNo, $Cantidad, $AreaCve){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO InventarioSap (STLocation, STBin, STType, GrammerNo, Cantidad, AreaCve) 
                       VALUES ('$STLocation', '$STBin', '$STType', '$GrammerNo', '$Cantidad', '$AreaCve');";

    $rsinsertInventarioSap = mysqli_query($conex, $insertRegistro);
    mysqli_close($conex);

    if (!$rsinsertInventarioSap) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>