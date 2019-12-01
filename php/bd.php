<?php
$nombreServer = "localhost";
$usuario = "root";
$contraseña = "";
$baseDatos = "registros_comidas";

$bd_conexion = new mysqli($nombreServer, $usuario, $contraseña, $baseDatos);
if($bd_conexion->connect_error){
	header("HTTP/1.0 503 Service Unavailable");
	die("error de conexion:  ".$bd_conexion->error);

}
$bd_conexion->query("SET NAMES utf8"); 
?>