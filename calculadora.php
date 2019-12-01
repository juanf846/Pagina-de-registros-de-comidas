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
		<?php require("php/usuario_login.php");
		//si el usuario no está logueado, lo lleva a la pantalla de login
		if($login_nombre_usuario==""){
			?>
			<script>
				window.open("login.php","_self");
			</script>
			<?php
		}

		if(!isset($_GET["editarId"])){
			//verifica si editarId esta definido, si no verifica que fecha lo esté
			//si es numerico verifica que tambien esté entre el rango valido, si falla, lleva a la pantalla de registros
			if(is_numeric($_GET["fecha"])){
				if($_GET["fecha"]<1 || $_GET["fecha"]>5){
					?>
					<script>
						window.open("http://localhost/Calculadora_alco/registros.php","_self");
					</script>
					<?php
				}
			}else{
				?>
				<script>
					window.open("http://localhost/Calculadora_alco/registros.php","_self");
				</script>
				<?php
			}
		}else{
			//consulta si el usuario logueado es el mismo que el dueño del registro
			$consulta = $bd_conexion->prepare("SELECT Id_usuario FROM registro WHERE Id_registro = ?");
			$consulta->bind_param("i",$_GET["editarId"]);
			$consulta->execute();
			if(!$consulta->fetch()){
				//si no hay registros significa que el registro no existe o no pertenece al usuario actual
				?>
				<script>
					window.open("http://localhost/Calculadora_alco/registros.php","_self");
				</script>
				<?php
				die();
			}
			//array con todos los momentos del dia
			$momentoComida[]="Desayuno";
			$momentoComida[]="Media mañana";
			$momentoComida[]="Almuerzo";
			$momentoComida[]="Media tarde";
			$momentoComida[]="Merienda";
			$momentoComida[]="Cena";
			//for para recorrer los elementos
			for($i=0;$i<6;$i++){
				unset($consulta);
				//la consulta SQL obtiene todos los datos de un registro y de un momento especifico para cargar el formulario en modo edición 
				$consulta = $bd_conexion->prepare("SELECT comida.Nombre, Cant_porcion, unidad_cant_porcion.Nombre, Cantidad, Cred_porcion FROM registro_comida JOIN comida ON comida.Id_comida = registro_comida.Id_comida JOIN unidad_cant_porcion ON unidad_cant_porcion.Id_unidad_cant_porcion = comida.Id_unidad_cant_porcion WHERE Id_registro = ? AND Id_momento = ? ORDER BY Id_momento ASC");
				$consulta->bind_param("ii",$_GET["editarId"],$i);
				//si no puede realizar la consulta envia un error 503
				if(!$consulta->execute()){		
					header("HTTP/1.0 503 Service Unavailable");
					die("Error 503 Servicio no disponible");
				}
				$consulta->bind_result($nombreComida, $cantidadPorcionComida, $unidadComida, $cantidadComida, $creditosComida);
				$momentoActual = "";
				echo '<div class="burbuja" id="comidas"><h4>'.$momentoComida[$i].'</h4>';
				while ($consulta->fetch()) {
					//imprime una comida
					echo '<div>';
					echo '<div class="row text-md-center small">';
					echo '	<div class="col-md-3">';
					echo '		<p class="font-weight-bold d-inline d-md-block">Nombre: </p>';
					echo '		<input type="text" class="form-control form-control-sm d-inline d-md-block w-md-auto" onkeyup="onChangeNombre(this)" onfocusout="ocultarDesplegable(this)" value="'.$nombreComida.'"/>';
					echo '		<div class="buscador-desplegable d-none"></div>';
					echo '	</div>';
					echo '	<div class="col-md-2">';
					echo '		<p class="font-weight-bold d-inline d-md-block">Cantidad por porción: </p>';
					echo '		<p class="d-inline d-md-block">'.$cantidadPorcionComida.' '.$unidadComida.'</p>';
					echo '	</div>';
					echo '	<div class="col-md-2">';
					echo '		<p class="font-weight-bold d-inline d-md-block">Porciones: </p>';
					echo '		<input type="text" class="form-control form-control-sm d-inline d-md-block" onkeyup="onChangePorcion(this)" value="'.$cantidadComida.'"/>';
					echo '	</div>';
					echo '	<div class="col-md-2">';
					echo '		<p class="font-weight-bold d-inline d-md-block">Cantidad total: </p>';
					echo '		<p class="d-inline d-md-block">'.$cantidadPorcionComida*$cantidadComida.'</p>';
					echo '	</div>';
					echo '	<div class="col-md-1">';
					echo '		<p class="font-weight-bold d-inline d-md-block" style="margin-top:-10px;">Creditos porcion: </p>';
					echo '		<p class="d-inline d-md-block">'.$creditosComida.'</p>';
					echo '	</div>';
					echo '	<div class="col-md-1" >';
					echo '		<p class="font-weight-bold d-inline d-md-block" style="margin-top:-10px;">Creditos totales: </p>';
					echo '		<p class="d-inline d-md-block">'.$creditosComida*$cantidadComida.'</p>';
					echo '	</div>';
					echo '	<div class="col-md-1">';
					echo '		<img src="img/quitar.png" width="40px" onclick="quitarComida(this);" />';
					echo '	</div>';
					echo '	</div>';
					echo '	<hr/>';
					echo '</div>';
				}
				//imprime el boton de agregar comida 
				echo '<div class="row text-md-center">';
				echo '	<div class="col-md-11">';
				echo '	</div>';
				echo '<div class="col-md-1">';
				echo '	<img src="img/agregar.png" width="40px" onclick="agregarComida(this)"/>';
				echo '</div>';
				echo '</div>';
				if($i<5){
					echo '</div>';
				}

			}
			$editar=true;
		}
		//si no es editar, genera una pantalla para llenar las comidas
		if(!isset($editar)){
			$editar = false;
		}
		if(!$editar){
		?>
		<!-- Fin: barra de menu -->
		<!-- Inicio: Desayuno -->
		<div class="burbuja" id="comidas">
			<h4>Desayuno</h4>
			<?php
			function generarCodigo($momento){
				echo'
				<div>
				<div class="row text-md-center small">
				<div class="col-md-3">
				<p class="font-weight-bold d-inline d-md-block">Nombre: </p>
				<input type="text" class="form-control form-control-sm d-inline d-md-block w-md-auto" onkeyup="onChangeNombre(this)" onfocusout="ocultarDesplegable(this)"/>
				<div class="buscador-desplegable d-none">
				</div>
				</div>
				<div class="col-md-2">
				<p class="font-weight-bold d-inline d-md-block">Cantidad por porción: </p>
				<p class="d-inline d-md-block"></p>
				</div>
				<div class="col-md-2">
				<p class="font-weight-bold d-inline d-md-block">Porciones: </p>
				<input type="text" class="form-control form-control-sm d-inline d-md-block" onkeyup="onChangePorcion(this)" />
				</div>
				<div class="col-md-2">
				<p class="font-weight-bold d-inline d-md-block">Cantidad total: </p>
				<p class="d-inline d-md-block"></p>
				</div>
				<div class="col-md-1">
				<p class="font-weight-bold d-inline d-md-block" style="margin-top:-10px;">Creditos porcion: </p>
				<p class="d-inline d-md-block"></p>
				</div>
				<div class="col-md-1" >
				<p class="font-weight-bold d-inline d-md-block" style="margin-top:-10px;">Creditos totales: </p>
				<p class="d-inline d-md-block"></p>
				</div>
				<div class="col-md-1">
				<!-- botones de agregar/quitar comidas-->
				<img src="img/quitar.png" width="40px" onclick="quitarComida(this);" />
				</div>
				</div>
				<hr/>
				</div>	
				<div class="row text-md-center">
				<div class="col-md-11">
				</div>
				<div class="col-md-1">
				<img src="img/agregar.png" width="40px" onclick="agregarComida(this)"/>
				</div> 
				</div>
				';
			}
				generarCodigo(0);
			?>


		</div>
		<!-- Fin: Desayuno -->
		<!-- Inicio: Media mañana -->
		<div class="burbuja">
			<h4>Media mañana</h4>
			<?php generarCodigo(1) ?>
		</div>
		<!--Fin: media mañana -->
		<!-- Inicio: Almuerzo -->
		<div class="burbuja">
			<h4>Almuerzo</h4>
			<?php generarCodigo(2) ?>
		</div>
		<!--Fin: almmuerzo -->
		<!-- Inicio: Media tarde -->
		<div class="burbuja">
			<h4>Media tarde</h4>
			<?php generarCodigo(3) ?>
		</div>
		<!--Fin: Media tarde -->
		<!-- Inicio: Merienda -->
		<div class="burbuja">
			<h4>Merienda</h4>
			<?php generarCodigo(4) ?>
		</div>
		<!--Fin: Merienda -->

		<!-- Inicio: Cena -->
		<div class="burbuja">
			<h4>Cena</h4>
			<?php generarCodigo(5) ?>
		</div>
		<!--Fin: Cena -->
		<?php
		}else{
			echo '</div>';
		}
		?>
		<!--boton de subir-->
		<div>
			<div class="burbuja form-inline">
				<button class="btn btn-primary derecha" onclick="subirFormulario(<?php 
					if(!$editar){
						echo $_GET["fecha"];
						echo ",false";
					}else{
						echo $_GET["editarId"];
						echo ",true";
					}
					?>)">Aceptar</button>
			</div>
		</div>
	</div>
</body>
</html>
