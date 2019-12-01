<?php
$login_no_render = true; 	//esta variable es para que no muestre el menú
require("usuario_login.php");
$datos = json_decode($_POST["datos"]); 	//decodifica el JSON
$esEditar = false;			//variable para verificar si el registro es nuevo o se está modificando uno existente
$fecha=0;
//si se recibio la variable POST fecha
if(isset($_POST["fecha"])){
	$fecha = $_POST["fecha"];
	//se remplaza el numero 1-5 por una fecha
	switch ($fecha) {
		case 0:
		die("17;error la fecha no está especificada");
		break;
		case 1:
		$fecha=date("Y-m-d",strtotime('1 day'));
		break;
		case 2:
		$fecha=date("Y-m-d");
		break;
		case 3:
		$fecha=date("Y-m-d",strtotime('-1 day'));
		break;
		case 4:
		$fecha=date("Y-m-d",strtotime('-2 day'));
		break;
		case 5:
		$fecha=date("Y-m-d",strtotime('-3 day'));
		break;
		default:
		die("18;error la fecha no está especificada");
		break;
	}
	$esEditar=false;
}else if(isset($_POST["editar"])){
	$esEditar=true;
}else{
	die("19;error, no es nuevo ni edición");
}
if($datos==NULL){		//Si no se pudo parsear el JSON, redirige a registros
?>
<script>
	window.open("registros.php","_self");
</script>
<?php
}
//for que recorre los momentos
for($i = 0 ; $i<sizeof($datos);$i++){
	//remplaza nombre momento por id_momento
	$sql = "SELECT Id_momento FROM momento WHERE Nombre = '".$datos[$i]->nombre."'";
	$resultados = $bd_conexion->query($sql);
	//si hay un sql error
	if($bd_conexion->error){
		die($bd_conexion->error);
	}
	if($resultados->num_rows!=0){	//Si se encontró la comida en la BD
		$datos[$i]->nombre = $resultados->fetch_array()[0];
	}else{
		die("14;error: ".$datos[$i]->nombre." no existe en la BD");
	}
	$momentoNombre = $datos[$i]->nombre;
	$momentoComidas = $datos[$i]->comidas;
	//for que recorre las comidas
	for($j=0;$j<sizeof($momentoComidas);$j++){
		if($momentoComidas[$j]->nombre!=""){	
			if($momentoComidas[$j]->cantidad==""){
				die("11;La cantidad de ".$momentoComidas[$j]->nombre." no es valida");
			}else{
				//select para obtener el id de comida a partir del nombre
				$sql = "SELECT Id_comida FROM comida WHERE Nombre = '".$momentoComidas[$j]->nombre."'";
				$resultados = $bd_conexion->query($sql);
				if($bd_conexion->error){
					die($bd_conexion->error);
				}
				
				
				if($resultados->num_rows!=0){
					//La comida está en la BD
					$row = $resultados->fetch_array();
					$momentoComidas[$j]->nombre = $row[0];
				}else{
					die("12;La comida ".$momentoComidas[$j]->nombre." no es valida");
				}
			}
		}else{
			if($momentoComidas[$j]->cantidad!=""){
				die("13;La cantidad ".$momentoComidas[$j]->cantidad." de ".$momentoNombre." no tiene una comida");
			}
		}
	}
}
if($esEditar == FALSE){
	//nuevo registro
	//verifica que no exista otro registro en la BD con la misma fecha
	unset($consulta);
	$consulta = $bd_conexion->prepare("SELECT COUNT(Id_registro) FROM registro WHERE Fecha = ? AND Id_usuario = ?");
	$consulta->bind_param("si",$fecha,$login_id_usuario);
	$consulta->execute();
	$consulta->bind_result($cantidad);
	$consulta->fetch();
	if($cantidad==0){	//si no hay duplicados
		unset($consulta);
		//crea el registro en la tabla registro
		$sql = "INSERT INTO registro VALUES(null,STR_TO_DATE('".$fecha."','%Y-%c-%d'),".$login_id_usuario.")";
		if (!$bd_conexion->query($sql)) {
			die("15;Error: " . $sql . "<br>" . $bd_conexion->error." puede que el registro de esta fecha ya exista");
		}
		$id_registro = $bd_conexion->insert_id;
	}else{
		die("111;Error, el registro ".$fecha." ya existe");
	}
}else{
	//edita registro
	$sql = "SELECT Id_registro FROM registro WHERE Id_registro = ".$_POST["editar"];
	if (!$bd_conexion->query($sql)) {
		die("110;Error: " . $sql . "<br>" . $bd_conexion->error." puede que el registro de esta fecha no exista");
	}else{
		$id_registro = $_POST["editar"];
		$sql = "DELETE FROM registro_comida WHERE Id_registro = ".$id_registro;
		$bd_conexion->query($sql);
	}
}
//for para recorrer los momentos
for($i = 0 ; $i<sizeof($datos);$i++){
	$id_momento = $datos[$i]->nombre;
	$momentoComidas = $datos[$i]->comidas;
	//for para recorrer las comidas de cada momento
	for($j=0;$j<sizeof($momentoComidas);$j++){
		$id_comida=$momentoComidas[$j]->nombre;
		$comidaCantidad=$momentoComidas[$j]->cantidad;
		if(is_numeric($id_comida)){
			$sql = "INSERT INTO registro_comida VALUES(".$id_registro.",".$id_comida.",".$comidaCantidad.",".$id_momento.")";
			if (!$bd_conexion->query($sql)) {
				die("16;Error: " . $sql . "<br>" . $bd_conexion->error);
			}
		}
	}
}
echo '0;';
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	Redirigiendo...
	<script>
	window.open("resultados.php?idregistro="+<?php echo '"'.$id_registro.'"';?>,"_self");
	</script>
</body>
</html>