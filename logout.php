<?php
require "php/bd.php";
//obtiene las cookies
$token = $_COOKIE["token"];
$token_usuario = $_COOKIE["token_usuario"];
//confirma que existan las cookies
if ($token !=""){
	//token detectada
	//busca la cookie en la BD
	$consulta=$bd_conexion->prepare("SELECT Id_token FROM token WHERE Id_token = ? AND Valido = 1");
	$consulta->bind_param("i",$token);
	$consulta->execute();
	//es necesario hacer bind_result para que num_rows de el numero real
	$consulta->bind_result($variableInutil);
	if($consulta->num_rows > 0){
		$consulta2 = $bd_conexion->prepare("UPDATE token SET Valido = 0 WHERE Id_token = ?");
		$consulta2->bind_param("i",$token);
		if($consulta2->execute()){
			echo "Deslogueado correctamente";
		}
	}	
}
//elimina las cookies
setcookie("token","",time()+(7*24*60*60));
setcookie("token_usuario","",time()+(7*24*60*60));
//redirige a login
?>
<script>
	window.open("login.php","_self");
</script>