<?php 
require("php/bd.php");
$nombre_error="";//error del campo nombre
$contra_error="";//error del campo contra
$general_error="";//error que no corresponde a un campo especifico (usuario o contraseña incorrectos)
$continuar = true;
//comprueba que todos los campos esten completos
if(isset($_POST["nombre"])){
	if($_POST["nombre"]==""){
		$nombre_error="Este campo no puede estar vacio";
		$continuar=false;
	}	
}else{
	$continuar=false;
}

if(isset($_POST["contra"])){
	if($_POST["contra"]==""){
		$contra_error="Este campo no puede estar vacio";
		$continuar=false;
	}else
	if(strlen($_POST["contra"])<8){
		$contra_error="La contraseña es muy corta";
		$continuar=false;
	}	
}else{
	$continuar=false;
}

if(isset($_POST["contra2"])){
	if($_POST["contra2"]==""){
		$contra2_error="Este campo no puede estar vacio";
		$continuar=false;
	}else
	if(strlen($_POST["contra2"])<8){
		$contra2_error="La contraseña es muy corta";
		$continuar=false;
	}else
	if($_POST["contra"]!=$_POST["contra2"]){
		$contra2_error="Las contraseñas deben ser iguales";
		$continuar=false;
	}
}else{
	$continuar=false;
}
//si los campos estan bien, intenta registrar al usuario
if($continuar){
	//busca si ya exister un usuario con este nombre
	$consulta = $bd_conexion->prepare("SELECT COUNT(Nombre) FROM usuario WHERE Nombre = ?");
	$consulta->bind_param("s",$_POST["nombre"]);
	$consulta->execute();
	$consulta->bind_result($cantidad);
	$consulta->fetch();
	//si el nombre esta disponible lo registra
	if($cantidad==0){
		unset($consulta);
		$consulta = $bd_conexion->prepare("INSERT INTO usuario(Nombre,Contra) VALUES(?,?) ");
		$consulta->bind_param("ss",$_POST["nombre"],$_POST["contra"]);
		//si lo pudo registrar, crea un token de acceso
		if($consulta->execute()){
			$idUsuario = $consulta->insert_id;
			unset($consulta);
			$consulta=$bd_conexion->prepare("INSERT INTO token(Fecha_creacion,Fecha_expira,Id_usuario,Valido) VALUES(STR_TO_DATE(?,'%d-%m-%Y'),STR_TO_DATE(?,'%d-%m-%Y'), ?,1)");
			$consulta->bind_param("ssi",$fechaActual,$fechaExpira,$idUsuario);
			$fechaActual = date("d-m-Y",time());
			$fechaExpira = date("d-m-Y",time()+(7*24*60*60));
			if(!$consulta->execute()){
				die("12;error, ".$bd_conexion->error." ; idUsuario: ".$idUsuario);
			}
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<meta http-equiv="refresh" content="0;URL='index.php'">
			</head>
			<body>
				<p>Redireccionando...</p>
				<script>
					<?php
					echo "document.cookie = 'token=".$bd_conexion->insert_id."';";
					echo "document.cookie = 'token_usuario=".$_POST["nombre"]."';";

					?>
				</script>
			</body>

			<?php
			die();
		}else{
			die("11;error, ".$bd_conexion->error);
		}
	}else{
		$general_error="El usuario ya está registrado";
	}
}
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<meta charset="utf-8"/> 
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
		<link rel="stylesheet" href="css/style.css"/>
		<title></title>
	</head>
	<body>
		<div class="burbuja login-centrado">
			<form action="register.php" method="POST">
				<h2 class="text-center">Registrarse</h2>
				<div class="form-group">
					<label for="nombre">Nombre:</label>
					<input type="text" class="form-control" name="nombre"<?php if(isset($_POST["nombre"]) AND $_POST["nombre"]!=""){echo "value=".$_POST['nombre'];} ?> />
					<?php 
					if($nombre_error!=""){
						echo '<div class="alert alert-danger">'.$nombre_error.'</div';
					}?>
				</div>
				<div class="form-group">
					<label for="contra">Contraseña:</label>
					<input type="password" class="form-control" name="contra" />
					<?php 
					if($contra_error!=""){
						echo '<div class="alert alert-danger">'.$contra_error.'</div';
					}?>
				</div>
				<div class="form-group">
					<label for="contra2">Repita la contraseña:</label>
					<input type="password" class="form-control" name="contra2" />
					<?php 
					if(isset($contra2_error) && $contra2_error!=""){
						echo '<div class="alert alert-danger">'.$contra2_error.'</div';
					}?>
				</div>
				<input type="submit" value="Aceptar" class="btn btn-primary"/>
					<?php 
					if(isset($general_error) && $general_error!=""){
						echo '<div class="alert alert-danger">'.$general_error.'</div';
					}?>
			</form>
		</div>
	</body>
</html>