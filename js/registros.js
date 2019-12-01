function onChangeSelect(obj){
	var botonSubmit = obj.nextElementSibling;
	if(obj.value != 0){
		botonSubmit.classList.remove("btn-disabled");
		botonSubmit.classList.add("btn-primary");
		botonSubmit.type = "submit";
	}else{
		botonSubmit.classList.remove("btn-primary");
		botonSubmit.classList.add("btn-disabled");
		botonSubmit.type = "button";
	}
}
var xmlhttp;
function eliminarRegistro(id){
	var respuesta = confirm("¿Quiere eliminar el registro?");
	if(respuesta){
		if(xmlhttp != null){
			xmlhttp.abort();
		}
		if (window.XMLHttpRequest) {
			xmlhttp=new XMLHttpRequest();
		} else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (this.readyState==4 && this.status==200) {
				var respuesta = this.responseText.split(";");
				console.log("recibido");
				if(respuesta[0].charAt(0)=='0'){
					console.log(this.responseText);
					alert(respuesta[1]);
					location.reload();
				}else{
					console.log("Error codigo: "+respuesta[0]);
					console.log("Texto completo: "+this.responseText);
					alert(respuesta[1]);
				}
			}	
		}
		xmlhttp.open("GET","php/eliminar_registro.php?idregistro="+id,true);
		xmlhttp.send();	

	}
}