console.log("Calculadora.js cargado");
var textDivComida = '<div class="row text-md-center small">'+
						'<div class="col-md-3">'+
							'<p class="font-weight-bold d-inline d-md-block">Nombre: </p>'+
							'<input type="text" class="form-control form-control-sm d-inline d-md-block w-md-auto" onkeyup="onChangeNombre(this)" onfocusout="ocultarDesplegable(this)"/>'+
							'<div class="buscador-desplegable d-none">'+
							'</div>'+
						'</div>'+
						'<div class="col-md-2">'+
							'<p class="font-weight-bold d-inline d-md-block">Cantidad por porción: </p>'+
							'<p class="d-inline d-md-block"></p>'+
						'</div>'+
						'<div class="col-md-2">'+
							'<p class="font-weight-bold d-inline d-md-block">Porciones: </p>'+
							'<input type="text" class="form-control form-control-sm d-inline d-md-block" onkeyup="onChangePorcion(this)" />'+
						'</div>'+
						'<div class="col-md-2">'+
							'<p class="font-weight-bold d-inline d-md-block">Cantidad total: </p>'+
							'<p class="d-inline d-md-block"></p>'+
						'</div>'+
						'<div class="col-md-1">'+
							'<p class="font-weight-bold d-inline d-md-block" style="margin-top:-10px;">Creditos porcion: </p>'+
							'<p class="d-inline d-md-block"></p>'+
						'</div>'+
						'<div class="col-md-1" >'+
							'<p class="font-weight-bold d-inline d-md-block" style="margin-top:-10px;">Creditos totales: </p>'+
							'<p class="d-inline d-md-block"></p>'+
						'</div>'+
						'<div class="col-md-1">'+
							'<img src="img/quitar.png" width="40px" onclick="quitarComida(this);" />'+
						'</div>'+
					'</div>'+
					'<hr/>';
var xmlhttp;
var xmlhttp2;

var datos = [];

function onChangeNombre(obj){ // Funcion que muestra el desplegable de alimentos
	//obj es el input
	if(xmlhttp != null){
		xmlhttp.abort();
	}
	if(obj.value.length >1){
		if (window.XMLHttpRequest) {
			xmlhttp=new XMLHttpRequest();
		} else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (this.readyState==4 && this.status==200) {
				buscador = obj.parentElement.children.item(2);
				buscador.innerHTML=this.responseText;
				buscador.className="buscador-desplegable";
			}	
		}
		xmlhttp.open("GET","php/leerBD.php?tipo=1&a="+obj.value,true);
		xmlhttp.send();
		
		getAdicionalComida(obj);
	}else{
		ocultarDesplegable(obj);
	}
}
function onChangePorcion(obj){
	//obj es el input de porciones
	//es el p del div cantidad por porcion
	var porcionMinima = obj.parentElement.previousElementSibling.children.item(1).innerHTML;
	//es el p del div cantidad total
	var porcionTotal = obj.parentElement.nextElementSibling.children.item(1);
	//es le p del div creditos por porcion
	var creditosPorcion = parseFloat(obj.parentElement.nextElementSibling.nextElementSibling.children.item(1).innerHTML);
	//es le p del div creditos totales
	var creditosTotales = obj.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children.item(1);
	if(porcionMinima!=""){
		var porcionMultiplicador = parseFloat(obj.value);
		//cantidad
		if(porcionMultiplicador){
			var cantidadIndex = porcionMinima.indexOf(" "); //index del espacio en el string de cantidad+tipo porcion que se muestra
			var cantidadMinima = porcionMinima.substring(0,cantidadIndex); //cantidad del string anterior
			var tipoPocionTotal = porcionMinima.substring(cantidadIndex,porcionMinima.length);//tipo del string anterior

			//p de cantidad total
			porcionTotal.innerHTML = (porcionMultiplicador*cantidadMinima)+" "+tipoPocionTotal;
		}else{
			porcionTotal = "";

		}
		//creditos
		if(porcionMultiplicador){
			creditosTotales.innerHTML = porcionMultiplicador*creditosPorcion;
		}else{
			creditosTotales.innerHTML = "";
		}
	}
}

function onClickBuscador(obj){//funcion que pone el texto del desplegable en el campo para escribir
	//obj es el input
	parentDiv = obj.parentElement.parentElement.parentElement;
	parentDiv.children.item(1).value=obj.innerHTML;
	parentDiv.children.item(2).className="buscador-desplegable d-none";
	getAdicionalComida(parentDiv.children.item(1));
}

function getAdicionalComida(obj){
	//obj es el input
	if(xmlhttp2 != null){
		xmlhttp2.abort();
	}
	if (window.XMLHttpRequest) {
		xmlhttp2=new XMLHttpRequest();
	} else {
		xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp2.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200 && this.responseText!="") {
			var respuesta = this.responseText.split(";");
			if(respuesta[0]=="true"){
				var porcion = respuesta[1];
				obj.parentElement.parentElement.children.item(1).
				children.item(1).innerHTML=porcion;
				var creditos = respuesta[2];
				obj.parentElement.parentElement.children.item(4).
				children.item(1).innerHTML=creditos;
				ocultarDesplegable(obj);
			}else{
				obj.parentElement.parentElement.children.item(1).
				children.item(1).innerHTML="";
				obj.parentElement.parentElement.children.item(4).
				children.item(1).innerHTML="";
			}
		}	
	}
	xmlhttp2.open("GET","php/leerBD.php?tipo=2&a="+obj.value,true);
	xmlhttp2.send();	
}

function agregarComida(obj){ // Funcion que agrega otro elemento a la tabla
	//obj es el boton agregar
	var objAgregar = obj.parentElement.parentElement;
	var newElement = document.createElement("DIV");
	newElement.innerHTML=textDivComida;
	objAgregar.insertAdjacentElement("beforebegin",newElement);
}
function quitarComida(obj){// Funcion que quita un elemento de la tabla
	obj.parentElement.parentElement.parentElement.outerHTML = "";
}

function ocultarDesplegable(obj){//funcion que oculta el desplegable
	setTimeout(function(){ 
		obj.parentElement.children.item(2).innerHTML="";
		obj.parentElement.children.item(2).className="buscador-desplegable d-none"; }, 200);
}

function subirFormulario(parametro,esEditar){
	console.log("preparando...");
	datos = [];
	var divMomento = document.getElementById("comidas");
	for(var i=0;i<divMomento.parentElement.children.length-2;i++){
		//String con el nombre del momento actual
		var nombreMomento = divMomento.children.item(0).innerHTML;
		//Vector donde se almacenan las comidas 
		var comidas = [];
		//Div de la comida actual
		var divComida = divMomento.children.item(1);
		for(var j=0;j<divMomento.children.length-2;j++){
			//nombre de la comida actual
			var nombreComida = divComida.children.item(0).children.item(0).
			children.item(1).value; 
			//cantidad de la comida actual
			var cantidadComida = divComida.children.item(0).children.item(2).
			children.item(1).value; 
			if(nombreComida!=""){
				if(cantidadComida==""){
					alert(nombreComida+" no tiene cantidad de porciones");
					return;
				}
			}
			//lo guarda en un objeto
			var dato = {nombre:nombreComida,cantidad:cantidadComida};
			//lo agrega al vector de comidas del momento actual
			comidas.push(dato);

			//siguiente div de comida
			divComida = divComida.nextElementSibling;
		}
		//lo guarda en un objeto
		var momento = {nombre:nombreMomento,comidas:comidas};
		//lo guarda al vector de momentos
		datos.push(momento);
		divMomento=divMomento.nextElementSibling;
	}
	//envia el JSON
	var formularioHttp;
	if (window.XMLHttpRequest) {
		formularioHttp=new XMLHttpRequest();
	} else {
		formularioHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	formularioHttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			var respuesta = this.responseText.split(";");
			console.log("recibido");
			if(respuesta[0].charAt(0)=='0'){
				console.log(this.responseText);
				document.write(this.responseText.slice(2));
			}else{
				console.log("Error codigo: "+respuesta[0]);
				alert(respuesta[1]);
				console.log("Error completo: "+this.responseText);
			}
		}
	}

	formularioHttp.open("POST", "php/guardar_registro.php", true);
	formularioHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	if(!esEditar){
		formularioHttp.send("datos="+JSON.stringify(datos)+"&fecha="+parametro);
	}else{
		formularioHttp.send("datos="+JSON.stringify(datos)+"&editar="+parametro);
	}
	console.log("enviando...");
}
