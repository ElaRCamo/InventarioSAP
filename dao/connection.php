<?php

class LocalConector{
    private $host = "127.0.0.1:3306";
    private $usuario = "u909553968_UserInventario";
    private $clave = "Grammer2024Inventario";
    private $db = "u909553968_Inventario";

    public function conectar(){
        $conexion = mysqli_connect($this->host, $this->usuario, $this->clave, $this->db);
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        //echo "conexion exitosa";
        return $conexion;
    }
}