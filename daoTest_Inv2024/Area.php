<?php
include_once('db/db_Inventario.php');

$AreaCve=$_POST['AreaCve'];
$AreaNombre=$_POST['AreaNombre'];
$AreaProduccion=$_POST['AreaProduccion'];
$StLocation=$_POST['StLocation'];
$StBin=$_POST['StBin'];

registroArea($AreaCve,$AreaNombre,$AreaProduccion,$StLocation,$StBin);


function registroArea($AreaCve,$AreaNombre,$AreaProduccion,$StLocation,$StBin){
    $con = new LocalConector();
    $conex=$con->conectar();
    $insertRegistro= "INSERT INTO Area(AreaCve,AreaNombre,AreaProduccion,StLocation,StBin) VALUES ('$AreaCve','$AreaNombre','$AreaProduccion','$StLocation','$StBin');";
    $rsinsertArea=mysqli_query($conex,$insertRegistro);
    mysqli_close($conex);

    if(!$rsinsertArea){
        echo "0";
    }
    else{
        echo "1";
    }
}
?>