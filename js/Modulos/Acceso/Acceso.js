
function ingresar()
{
	
	var variable = "accion=index&"+$("#Formingresar").serialize();
    RestFullRequest("_Rest/Acceso.php", variable, "Acceso");
}

function Acceso(Response)
{
	window.location = "index.php";
}