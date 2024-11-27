<?php
include_once('db/db_Inventario.php');

$GrammerNo = $_POST['GrammerNo'];
$Descripcion = $_POST['Descripcion'];
$UM = $_POST['UM'];
$ProfitCtr = $_POST['ProfitCtr'];
$Costo = $_POST['Costo'];
$Por = $_POST['Por'];

insertParte($GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por);

function insertParte($GrammerNo, $Descripcion, $UM, $ProfitCtr, $Costo, $Por){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Parte (GrammerNo, Descripcion, UM, ProfitCtr, Costo, Por) 
                       VALUES ('$GrammerNo', '$Descripcion', '$UM', '$ProfitCtr', '$Costo', '$Por');";

    $rsinsertParte = mysqli_query($conex, $insertRegistro);

    mysqli_close($conex);

    if (!$rsinsertParte) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>