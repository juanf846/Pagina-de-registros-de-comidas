<?php
//verifica si existe la variable GET idregistro, si no existe, redirige a la pantalla de registros
if(!isset($_GET["idregistro"])){
?>
	<script>
		window.open("registros.php","_self");
	</script>
<?php
}
$id_registro = $_GET["idregistro"];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/style.css"/>
	</script>
</head>
<body>
	<div class="container">
		<?php require("php/usuario_login.php");
		//si el usuario no está logueado, lo redirige al login
		if($login_nombre_usuario==""){
		?>
			<script>
				window.open("http://localhost/Calculadora_alco/login.php","_self");
			</script>
		<?php
		}
		?>
		<div class="burbuja">
			<?php 
				//consulta si el usuario logueado es el mismo que le dueño del registro
				$consulta = $bd_conexion->prepare("SELECT Id_usuario FROM registro WHERE Id_registro = ?");
				$consulta->bind_param("i",$id_registro);
				$consulta->execute();
				//si no lo es, lo redirige a la pantalla de registros
				if(!$consulta->fetch()){
					?>
					<script>
						window.open("http://localhost/Calculadora_alco/registros.php","_self");
					</script>
					<?php
					die();
				}
				//cierra la consulta
				unset($consulta);
				//comprobación 1, que no se haya pasado o le falten creditos.
				//el valor normal de creditos es de 30
				$consulta = $bd_conexion->prepare("SELECT SUM(Cred_porcion*Cantidad) FROM registro_comida JOIN comida ON comida.Id_comida = registro_comida.Id_comida WHERE Id_registro = ? Group BY Id_registro");
				$consulta->bind_param("i",$id_registro);
				$consulta->execute();
				$consulta->bind_result($creditos);
				$consulta->fetch();
				if(!$creditos){
					$creditos=0;
				}
				if($creditos < 20 || $creditos > 40){
				//comprobacion de falta o sobra de creditos grave (pasando los +-10 creditos)
					?>
					<div class="alert alert-danger">
  						<strong>Grave!</strong> Tenes que consumir 30 creditos, consumiste <?php echo $creditos; ?>.
					</div>

					<?php
				}else if($creditos < 23 || $creditos > 33){
				//comprobacion de falta o sobra de creditos leve (pasando los +-3 creditos)
					?>
					<div class="alert alert-warning">
  						<strong>Leve!</strong> Tenes que consumir 30 creditos, consumiste <?php echo $creditos; ?>.
					</div>
					<?php
				}else{
				//comprobación de falta o sobra de creditos correcta
					?>
					<div class="alert alert-success">
  						<strong>Bien!</strong> Consumiste la cantidad justa de creditos.
					</div>
					<?php
				}
				//comprobacion 2, si comió en los 6 momentos del dia
				for($i=0;$i<6;$i++){
					unset($consulta);
					//cuenta la cantidad de comidas de un momento
					$consulta=$bd_conexion->prepare("SELECT COUNT(registro_comida.Id_momento), momento.Nombre FROM registro_comida JOIN momento ON momento.Id_momento = registro_comida.Id_momento WHERE Id_registro = ? AND registro_comida.Id_momento = ?");
					echo $bd_conexion->error;
					$consulta->bind_param("ii",$id_registro,$i);
					$consulta->execute();
					$consulta->bind_result($cantidad,$momentoNombre);
					$consulta->fetch();
					//si hay 0 comidas, entonces no comio en este momento
					if($cantidad==0){
						?>
						<div class="alert alert-danger">
  							<strong>Grave!</strong> No comiste nada en el/la <?php echo $momentoNombre;?>
						</div>
						<?php
					}
				}
			?>
		</div>
	</div>
</body>
</html>

