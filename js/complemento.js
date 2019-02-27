function cerrarSesion()
{	
	var variable = "accion=cerrar";
    RestFullRequest("_Rest/Acceso.php", variable, "Cerrar");
}

function Cerrar(Response)
{
	console.log(Response+"hola");
	window.location = "index.php";
}


// JavaScript Document