<?php
	require "bd.php";
	if($_GET["tipo"]=="1"){		//devuelve una lista de 5 comidas (LIKE %a%)
		$texto = $_GET["a"];
		if($texto!=""){
			$sql = "SELECT * FROM comida WHERE Nombre LIKE \"%$texto%\" LIMIT 5";
			$resultado = $bd_conexion->query($sql);
			echo $bd_conexion->error;
			if ($resultado->num_rows > 0) {
				echo "<ul>";
				while($row = $resultado->fetch_assoc()) {
					echo "<li onClick='onClickBuscador(this);'>";
					echo $row["Nombre"];
					echo "</li>";
				}	
				echo "</ul>";
			}
			
		}
	}else if($_GET["tipo"]=="2"){	//devuelve toda la información necesaria sobre 1 comida
		$texto = $_GET["a"];
		if($texto!=""){
			$sql = "SELECT comida.Nombre As nombreComida, unidad_cant_porcion.Nombre As porcion, comida.Cant_porcion, Cred_porcion ".
			"FROM comida JOIN unidad_cant_porcion ON comida.Id_unidad_cant_porcion = unidad_cant_porcion.Id_unidad_cant_porcion ".
			"WHERE comida.Nombre = \"$texto\"";
			$resultado = $bd_conexion->query($sql);
			echo $bd_conexion->error;
			if ($resultado->num_rows > 0) {
				while($row = $resultado->fetch_assoc()) {
					echo "true";
					echo ";";
					echo $row["Cant_porcion"];
					echo " ";
					echo $row["porcion"];
					echo ";";
					echo $row["Cred_porcion"];
					echo ";";
					echo $sql;
				}	
				//envia los datos en formato "cantidad porcion;Creditos"	
			}else{
				echo "false";
				echo ";";
			}
		}
	}
?>