<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/style.css"/>
	<script src="js/registros.js">
	</script>
</head>
<body>
	<div class="container">
		<!-- Inicio: barra de menu -->
		<?php require("php/usuario_login.php");
		if($login_nombre_usuario==""){
			?>
			<script>
				window.open("login.php","_self");
			</script>
			<?php
		}
		?>
		<!-- Fin: barra de menu -->
		<!-- Inicio: burbuja de nuevo registro-->
		<div class="row burbuja">
			<div class="col-lg-6 ">
				<div>
					<h2>Nuevo</h2>
				</div>
				<div>
					<form action="calculadora.php" class="form-inline" >
							<?php
							//obtiene los ultimos 5 registros para comparar con el desplegable de nuevo registro 
							$consulta = $bd_conexion->prepare("SELECT Fecha FROM registro WHERE Id_usuario = ? ORDER BY Fecha DESC LIMIT 5");
							$consulta->bind_param("i",$login_id_usuario);
							if(!$consulta->execute()){
								echo $bd_conexion->error;
							}
							$consulta->bind_result($fechaRegistroActual);
							$consulta->fetch();
							//vector con los ultimos 5 registros
							for($i=0;$i<5;$i++){
								$fechasRegistros[$i] = date("d-m",strtotime($fechaRegistroActual));
								$consulta->fetch();
							}
							?>
							<select name="fecha" class="margen-auto" onchange="onChangeSelect(this);">
							<?php
							//vector con las fechas del desplegable
							$fechasRegistros2 = [date("d-m",strtotime('+1 day')),
							date("d-m",time()),
							date("d-m",strtotime('-1 day')),
							date("d-m",strtotime('-2 day')),
							date("d-m",strtotime('-3 day'))];
							echo "<option value=0>---SELECCIONE UNA FECHA---</option>";
							//compara las fechas, si hay un registro en el desplegable y en uno de la BD con la misma fecha entonces no muestra el del desplegable
							for($j =0;$j<5;$j++){
								unset($fechaRegistroActual);
								$fechaRegistroActual = $fechasRegistros2[$j];
								$mostrarFecha = true;
								for($i=0;$i<5;$i++){
									//echo $fechaRegistroActual." ".$fechasRegistros[$i]."<br/>salto";
									if($fechaRegistroActual == $fechasRegistros[$i]){
										$mostrarFecha=false;
									}
								}
								if($mostrarFecha){
									echo "<option value=".($j+1).">".$fechaRegistroActual."</option>";
								}
							}
							unset($fechaRegistroActual);
							unset($mostrarFecha);
							unset($fechasRegistros);
							unset($fechasRegistros2);
							unset($consulta);

							?>
						</select>
						<input class="btn btn-disabled margen-auto" type="button" value="Nuevo Registro"/>
					</form>
				</div>
			</div>
			
		</div>
		<!--Fin: burbuja nuevo-->
		<?php
		//obtiene todos los registros de la BD y los muestra en la pagina
		$sql="SELECT Id_registro, Fecha FROM registro";
		$sql = $sql . " WHERE Id_usuario = $login_id_usuario";
		$sql = $sql . " ORDER BY Fecha DESC";
		$resul = $bd_conexion->query($sql);
		$fechaActual = array('0','0','0');
		$primero = true;
		if($resul != false && $resul->num_rows>0){
			while($row = $resul->fetch_array()){
				$idRegistro = $row[0];
				$fecha = explode("-",$row[1]);
				//si el mes es diferente entonces:
				if($fecha[1] != $fechaActual[1]){
					//si no es el primero, cierra la burbuja anterior
					if(!$primero){
						echo '</div>';	
					}
					//dependiendo del numero de mes, se elige el nombre
					switch ($fecha[1]) {
						case '1':
							$nombreFecha = "Enero";
							break;
						case '2':
							$nombreFecha = "Febrero";
							break;
						case '3':
							$nombreFecha = "Marzo";
							break;
						case '4':
							$nombreFecha = "Abril";
							break;
						case '5':
							$nombreFecha = "Mayo";
							break;
						case '6':
							$nombreFecha = "Junio";
							break;
						case '7':
							$nombreFecha = "Julio";
							break;
						case '8':
							$nombreFecha = "Agosto";
							break;
						case '9':
							$nombreFecha = "Septiembre";
							break;
						case '10':
							$nombreFecha = "Octubre";
							break;
						case '11':
							$nombreFecha = "Noviembre";
							break;
						case '12':
							$nombreFecha = "Diciembre";
							break;
					}
					//se abre la burbuja y se le agrega el numero del mes
					echo '<div class="burbuja">
					<h4 class="d-block">'.$nombreFecha.' '.$fecha[0].'</h4>';
				}
				//fecha actual es la fecha actual, se usa para saber si el mes actual es diferente al anterior
				$fechaActual = $fecha;

				//imprime un registro
				echo '
				<div class="row">
				<div class="col-4">
				<p>dia '.$fecha[2].'</p>
				</div>
				<div class="col-8">
				<a class="btn btn-primary" href="resultados.php?idregistro='.$idRegistro.'" >Ver Resultados</a>';
				//Si es el primero, le agrega los botones de borrar y editar
				if($primero){
					echo '<a class="btn btn-primary" href="calculadora.php?editarId='.$idRegistro.'">Editar</a>';
					echo '<button class="btn btn-danger" onClick="eliminarRegistro('.$idRegistro.');">Eliminar</button>';
					$primero=false;
				}
				echo '</div>
				</div>';
				
			}
			echo '</div>';
		}else{
			//echo "error no hay registros";
			//echo $bd_conexion->error;
		}
		?>
	</div>
</body>
</html>
