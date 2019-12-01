<?php
require "bd.php";
$login_nombre_usuario = ""; //variable que almacena el nombre del usuario logueado
$login_id_usuario = "";		//variable que almacena el id del usuario logueado
$login_error = "";			//mensaje de error

//cookies
if(isset($_COOKIE["token"]) && isset($_COOKIE["token_usuario"])){
	$token = $_COOKIE["token"]; //id_token
	$token_usuario = $_COOKIE["token_usuario"];	//nombre de usuario
}else{
	$token = "";
}
if ($token ==""){
	//no logueado
	$login_nombre_usuario = "";
	$login_id_usuario = "";
	$login_error = "usuario no logueado";
}else{
	//cookie detectada
	$login_error = "token detectada";
	//comprueba si la token es valida
	$sql="SELECT usuario.Nombre, Fecha_expira, usuario.Id_usuario FROM token LEFT JOIN usuario on usuario.Id_usuario = token.Id_usuario WHERE Id_token = $token AND Valido = 1";
	$resultado = $bd_conexion->query($sql);
	// si el token es valido
	if($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		//obtiene los datos y los carga en las variables
		$login_nombre_usuario = $row[0];
		$fecha_expira_token = $row[1];
		$login_id_usuario = $row[2];
		//detecta si la token está expirada
		if(time()>strtotime($fecha_expira_token)){
			//token expirada
			//elimina la cookie y actualiza el valido a 0
			$sql="UPDATE token SET Valido = '0' WHERE Id_token = '$token'";
			$bd_conexion->query($sql);
			$login_nombre_usuario = "";
			$login_id_usuario = "";
			setcookie("token","",time()+(7*24*60*60));
			setcookie("token_usuario","",time()+(7*24*60*60));
			$login_error="token expirada";
		}
	}else{
		//token invalida
		$login_error = "token invalida";
		$login_nombre_usuario = "";
		$login_id_usuario = "";
		setcookie("token","",-1);
		setcookie("token_usuario","",-1);
	}
}
unset($token);
unset($token_usuario);
//si login_no_render es true entonces no muestra el menú
if(!isset($login_no_render)){
	//imprime el menu 
?>
<nav class="navbar navbar-expand-md bg-gris-oscuro">
	<ul class="navbar-nav mr-auto">
		<li class="nav-item">
			<a class="nav-link" href="tabla_alimentos.php">Tabla de alimentos</a>
		</li>
	</ul>
	<?php
	if($login_nombre_usuario==""){
		?>
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link text-right" href="login.php">Login</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-right" href="register.php">Registrarse</a>
			</li>
		</ul>
		<?php 
	}else{
		?>
		<ul class="navbar-nav">
			<li class="nav-item" style="border-right: 1px solid #888888">
				<a class="nav-link text-right" href="#"><?php echo "$login_nombre_usuario" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-right" href="registros.php">Registros</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-right" href="logout.php">Salir</a>
			</li>
		</ul>

		<?php
	}
	?>
</nav>
<?php 
}
?>