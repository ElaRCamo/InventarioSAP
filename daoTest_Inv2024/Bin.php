<?php
include_once('db/db_Inventario.php');

$StBin = $_POST['StBin'];
$StType = $_POST['StType'];

insertBin($StBin, $StType);

function insertBin($StBin, $StType){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Bin (StBin, StType) VALUES ('$StBin', '$StType');";
    $rsinsertBin = mysqli_query($conex, $insertRegistro);
    mysqli_close($conex);

    if (!$rsinsertBin) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>


