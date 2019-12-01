<?php 
require("php/bd.php");
$mostrar=true;
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
//loguea al usuario si la condición se cumple
if($continuar){
	//lo busca en la BD
	$sql = "SELECT Id_usuario FROM usuario WHERE Nombre = \"".$_POST['nombre']."\" AND Contra = \"".$_POST['contra']."\"";
	$resultados = $bd_conexion->query($sql);
	if($resultados->num_rows==0){
		$general_error= "El usuario o la contraseña son incorrectos";
	}else{
		//crea una token de acceso en la BD
		$idUsuario = $resultados->fetch_array()[0];
		$sql = "INSERT INTO token(Fecha_creacion,Fecha_expira,Id_usuario,Valido) VALUES(STR_TO_DATE('".date("d-m-Y")."','%d-%m-%Y'),STR_TO_DATE('".date("d-m-Y",time()+(7*24*60*60))."','%d-%m-%Y'), ".$idUsuario.",1)";
		$resultados = $bd_conexion->query($sql);
		//agrega las cookies
		setcookie("token",$bd_conexion->insert_id,time()+(7*24*60*60));
		setcookie("token_usuario",$_POST["nombre"],time()+(7*24*60*60));
		//redirige al index
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="refresh" content="0;URL='index.php'">
		</head>
		<body>
			<p>Redireccionando...</p>
		</body>
		<?php
		$mostrar=false;
	}
}
if($mostrar){
	//muestra la pantalla de login
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
			<form action="login.php" method="POST">
				<h2 class="text-center">Login</h2>
				<div class="form-group">
					<label for="nombre">Nombre:</label>
					<input type="text" class="form-control" name="nombre"<?php if(isset($_POST["nombre"]) AND $_POST["nombre"]!=""){echo "value=".$_POST['nombre'];} ?> />
					<?php 
					if($nombre_error!=""){
						echo '<div class="alert alert-danger">'.$nombre_error.'</div>';
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
				<input type="submit" value="Aceptar" class="btn btn-primary"/>
					<?php 
					if($general_error!=""){
						echo '<div class="alert alert-danger">'.$general_error.'</div';
					}?>
				<p>¿No tienes cuenta? <a href="register.php">Registrate</a></p> 
			</form>
		</div>
	</body>
	<?php
}
?>
</html>