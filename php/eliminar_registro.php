<?php
$login_no_render = true; 	//esta variable es para que no muestre el menú
require("usuario_login.php");	
//si la variable idregistro existe y es numerica
if(isset($_GET["idregistro"]) AND is_numeric($_GET["idregistro"])){
	unset($consulta);
	//busca si el registro pertenece al usuario logueado
	$consulta = $bd_conexion->prepare("SELECT Id_registro FROM registro WHERE Id_registro = ? AND Id_usuario = ?");
	$consulta->bind_param("ii",$_GET["idregistro"],$login_id_usuario);
	if($consulta->execute()){
		//es necesario hacer bind_result para que num_rows muestre el numero real
		$consulta->bind_result($variableInutil);
		//si el registro pertenece al usuario, lo elimina
		if(!$consulta->num_rows!=0){
			unset($consulta);
			//elimina las comidas del registro
			$consulta = $bd_conexion->prepare("DELETE FROM registro_comida WHERE Id_registro = ?");
			$consulta->bind_param("i",$_GET["idregistro"]);
			if($consulta->execute()){
				unset($consulta);
				//elimina el registro
				$consulta = $bd_conexion->prepare("DELETE FROM registro WHERE Id_registro = ?");
				$consulta->bind_param("i",$_GET["idregistro"]);
				if($consulta->execute()){
					echo "0;Registro borrado exitosamente";
				}else{
					echo "14;error al borrar el registro";
				}
			}else{
				echo "13;error al borrar el registro";
			}
		}else{
			echo "12;error, el registro no existe en la base de datos o no pertenece al usuario logueado";
			echo ";".$_GET["idregistro"].";".$login_id_usuario;
			echo ";".$consulta->num_rows;
		}
	}else{
		echo "11;error al borrar el registro";
	}
}


?>