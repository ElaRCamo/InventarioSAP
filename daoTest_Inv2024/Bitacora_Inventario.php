<?php
include_once('db/db_Inventario.php');

$Id_Bitacora = $_POST['Id_Bitacora'];
$NumeroParte = $_POST['NumeroParte'];
$FolioMarbete = $_POST['FolioMarbete'];
$Fecha = $_POST['Fecha'];
$Usuario = $_POST['Usuario'];
$UsuarioVerificacion = $_POST['UsuarioVerificacion'];
$Estatus = $_POST['Estatus'];
$PrimerConteo = $_POST['PrimerConteo'];
$SegundoConteo = $_POST['SegundoConteo'];
$TercerConteo = $_POST['TercerConteo'];
$SegFolio = $_POST['SegFolio'];
$UserSeg = $_POST['UserSeg'];
$Comentario = $_POST['Comentario'];
$StorageBin = $_POST['StorageBin'];
$StorageType = $_POST['StorageType'];
$Area = $_POST['Area'];

insertBitacora($Id_Bitacora, $NumeroParte, $FolioMarbete, $Fecha, $Usuario, $UsuarioVerificacion, $Estatus, $PrimerConteo, $SegundoConteo, $TercerConteo, $SegFolio, $UserSeg, $Comentario, $StorageBin, $StorageType, $Area);

function insertBitacora($Id_Bitacora, $NumeroParte, $FolioMarbete, $Fecha, $Usuario, $UsuarioVerificacion, $Estatus, $PrimerConteo, $SegundoConteo, $TercerConteo, $SegFolio, $UserSeg, $Comentario, $StorageBin, $StorageType, $Area){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Bitacora_Inventario (Id_Bitacora, NumeroParte, FolioMarbete, Fecha, Usuario, UsuarioVerificacion, Estatus, PrimerConteo, SegundoConteo, TercerConteo, SegFolio, UserSeg, Comentario, StorageBin, StorageType, Area) 
                       VALUES ('$Id_Bitacora', '$NumeroParte', '$FolioMarbete', '$Fecha', '$Usuario', '$UsuarioVerificacion', '$Estatus', '$PrimerConteo', '$SegundoConteo', '$TercerConteo', '$SegFolio', '$UserSeg', '$Comentario', '$StorageBin', '$StorageType', '$Area');";

    $rsinsertBitacora = mysqli_query($conex, $insertRegistro);
    mysqli_close($conex);

    if (!$rsinsertBitacora) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>