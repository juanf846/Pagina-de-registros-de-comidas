<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/style.css"/>
	<script src="js/calculadora.js">
	</script>
</head>
<body>
	<div class="container">
		<!-- Inicio: barra de menu -->
		<?php require("php/usuario_login.php");?>
		<!-- Fin: barra de menu -->
		<!-- Inicio: busqueda -->
		<div class="burbuja">
			<div class="row">
				<div class="col-7">
				</div>
				<div class="col-5">
					<div>
						<h2 class="text-right">Buscar</h2>
					</div>
					<div>
						<form action="tabla_alimentos.php" class="form-inline">
							<input type="text" class="form-control margen-auto" name="b" <?php if(isset($_GET["b"])){echo "value=".$_GET["b"];} ?> />
							<input type="submit" class="btn btn-primary margen-auto" value="Buscar"/>
							<a class="btn btn-danger margen-auto" href="tabla_alimentos.php">Restablecer</a>

						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Fin: busqueda -->
		<?php
		// obtiene los datos de todas las comidas
		$sql="SELECT comida.Nombre, comida.Cred_porcion, comida.Cant_porcion, unidad_cant_porcion.Nombre 
		FROM comida JOIN unidad_cant_porcion ON comida.Id_unidad_cant_porcion = unidad_cant_porcion.Id_unidad_cant_porcion ";
		if(isset($_GET["b"]) && $_GET["b"]!=""){
			$sql = $sql . "WHERE comida.Nombre LIKE \"%".strtolower($_GET["b"])."%\" ";
		}
		$sql = $sql . "ORDER BY comida.Nombre ASC";
		$resul = $bd_conexion->query($sql);
		$letra = "";
		if($resul->num_rows>0){
			while($row = $resul->fetch_array()){
				$nombre = ucfirst($row[0]);
				$creditos = $row[1];
				$cantPorcion = $row[2];
				$unidadPorcion = $row[3];
				$nuevaLetra = $nombre[0];
				if($letra != $nuevaLetra){
					if($letra != ""){
						echo '</div>';
					}
					$letra=$nuevaLetra;
					echo '<div class="burbuja">
					<h4 class="d-block">'.$letra.'</h4>
					<div class="row">
					<div class="col-6">
					<p><b>Nombre</b></p>
					</div>
					<div class="col-3">
					<p><b>Porción</b></p>
					</div>
					<div class="col-3">
					<p><b>Creditos</b></p>
					</div>
					</div>';
				}
				echo '<div class="row">
				<div class="col-6">
				<p>'.$nombre.'</p>
				</div>
				<div class="col-3">
				<p>'.$cantPorcion.' '.$unidadPorcion.'</p>
				</div>
				<div class="col-3">
				<p>'.$creditos.'</p>
				</div>	
				</div>';
			}
			echo '</div>';
		}else{
			echo "error no hay registros";
			echo $bd_conexion->error;
		}
		?>
	</div>
</body>
</html>
