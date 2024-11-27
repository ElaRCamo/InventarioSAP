<?php
include_once('db/db_Inventario.php');

$Id_Usuario = $_POST['Id_Usuario'];
$Nomina = $_POST['Nomina'];
$Nombre = $_POST['Nombre'];
$User = $_POST['User'];
$Password = $_POST['Password'];
$Rol = $_POST['Rol'];
$Estatus = $_POST['Estatus'];
$Area = $_POST['Area'];

insertUsuarios($Id_Usuario, $Nomina, $Nombre, $User, $Password, $Rol, $Estatus, $Area);

function insertUsuarios($Id_Usuario, $Nomina, $Nombre, $User, $Password, $Rol, $Estatus, $Area){
    $con = new LocalConector();
    $conex = $con->conectar();

    $insertRegistro = "INSERT INTO Usuarios (Id_Usuario, Nomina, Nombre, User, Password, Rol, Estatus, Area) 
                       VALUES ('$Id_Usuario', '$Nomina', '$Nombre', '$User', '$Password', '$Rol', '$Estatus', '$Area');";

    $rsinsertUsuarios = mysqli_query($conex, $insertRegistro);
    mysqli_close($conex);

    if (!$rsinsertUsuarios) {
        echo "0"; // Error
    } else {
        echo "1"; // Éxito
    }
}
?>