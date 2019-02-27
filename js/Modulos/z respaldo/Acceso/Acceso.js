
function ingresar()
{
	
	var variable = "accion=index&empresa=1&"+$("#Formingresar").serialize();
    RestFullRequest("_Rest/Acceso.php", variable, "Acceso");
}

function Acceso(Response)
{
	window.location = "index.php";
}