<?php
include_once('db/db_Inventario.php');

$GrammerNo = $_POST['GrammerNo'];
$PVB = $_POST['PVB'];

insertUbicaciones($GrammerNo, $PVB);

function insertUbicaciones($GrammerNo, $PVB){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Ubicaciones (GrammerNo, PVB) 
                       VALUES ('$GrammerNo', '$PVB');";

    $rsinsertUbicaciones = mysqli_query($conex, $insertRegistro);
    mysqli_close($conex);

    if (!$rsinsertUbicaciones) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>