function actualizaProcesos()
{
	var variable = "accion=index";
    RestFullRequest("_Rest/General.php", variable, "CargaProcesos");
}

function MessageInit()
{
    $("#Message").css("background-Color", "rgb(0,50,250))");
    $("#Message").find(".letras_Message").html("<i class='fa fa-circle-o-notch fa-spin' style='color: #FFF'></i> Cargando...");
    $("#Message").show();

}


function MessageError(message_string = '')
{
    setTimeout(function () {
        $("#Message").css("background-Color", "rgb(230,0,0)");
        if(message_string == "")
            $("#Message").find(".letras_Message").html("<i class='fa fa-close' style='color: #FFF'></i> Ocurrio un Error, Verifique sus datos y vuelva a intentarlo");
        else
            $("#Message").find(".letras_Message").html("<i class='fa fa-close' style='color: #FFF'></i> "+message_string);
        $("#Message").delay(5000).fadeOut("fast");
    },1000);
}

function MessageSuccessSave()
{
    setTimeout(function () {
        $("#Message").css("background-Color", "rgb(50,150,50)");
        $("#Message").find(".letras_Message").html("<i class='fa fa-check' style='color: #FFF'></i> Se ha guardado Correctamente el registro...");
        $("#Message").delay(2000).fadeOut("fast");
    },1000);
}

function MessageSuccessDelete()
{
    setTimeout(function () {
        $("#Message").css("background-Color", "rgb(50,150,50)");
        $("#Message").find(".letras_Message").html("<i class='fa fa-check' style='color: #FFF'></i> Se ha eliminado Correctamente el registro...");
        $("#Message").delay(2000).fadeOut("fast");
    },1000);
}

function CargaProcesos(Response)
{
    
    bandera = 0;    
    //console.log(Response);
    
    /*$("#menuDiseno").html("").append(0);
    $("#menuImpresion").html("").append(0);
    $("#menuInstalacion").html("").append(0);
    $("#menuEntrega").html("").append(0);
    $("#menuMaquilas").html("").append(0);
    $("#menuPreparacion").html("").append(0);
   
    while(Response)
    {
        id = Response[bandera]['id'];
        switch(id)
        {
            case 2:
                $("#menuDiseno").html("").append(Response[bandera]['conteo']);
            break;
            
            case 3:
                $("#menuImpresion").html("").append(Response[bandera]['conteo']);
            break;

            case 4:
                $("#menuInstalacion").html("").append(Response[bandera]['conteo']);
            break;

            case 6:
                $("#menuEntrega").html("").append(Response[bandera]['conteo']);
            break;

            case 8:
                $("#menuMaquilas").html("").append(Response[bandera]['conteo']);
            break;

            case 9:
                $("#menuPreparacion").html("").append(Response[bandera]['conteo']);
            break;

        }
        bandera++;
    }*/
    
}

function actualizaVentas()
{
	var variable = "accion=ventas";
    RestFullRequest("_Rest/General.php", variable, "CargaVentas");
}

function CargaVentas(Response)
{
	//console.log(Response);
	$.each(Response, function(index, value)
    {
    	$("#ventas_mostrador_percent").html(value['ventas_mostrador_diario_percent']+"% / "+value['ventas_mostrador_mensual_percent']+"% "+value['img_mostrador']);
    	$("#diario_ventas_mostrador").html("Ventas Diarias "+ value['ventas_mostrador_diario']+" / "+value['meta_ventas_mostrador_diario']);
    	$("#mensual_ventas_mostrador").html("Ventas Mensuales "+value['ventas_mostrador_mensual']+" / "+value['meta_ventas_mostrador_mensual']);

    	$("#ventas_servicio_percent").html(value['ventas_servicio_diario_percent']+"% / "+value['ventas_servicio_mensual_percent']+"% "+value['img_service']);
    	$("#diario_ventas_servicio").html("Ventas Diarias "+ value['ventas_servicio_diario']+" / "+value['meta_ventas_servicio_diario']);
    	$("#mensual_ventas_servicio").html("Ventas Mensuales "+value['ventas_servicio_mensual']+" / "+value['meta_ventas_servicio_mensual']);
    });
}

$(document).ready(function()
	{
		actualizaVentas();
        actualizaCalendario();
        carga_Message();
	});


function carga_Message()
{
    $("body").append("<div class='Message' id='Message'><div style='float: left' class='letras_Message'><i class='fa fa-circle-o-notch fa-spin'></i> Cargando...</div><div class='close_Message'>X</div></div>");
    $(".close_Message").on("click", function()
    {
        $(".Message").fadeOut(800);
    });

}

function actualizaCalendario()
{
    var variable = "accion=calendarioServicio";
    RestFullRequest("_Rest/General.php", variable, "CargaCalendarioServicio");

    var variable = "accion=calendarioMostrador";
    RestFullRequest("_Rest/General.php", variable, "CargaCalendarioMostrador");
}

function CargaCalendarioServicio(Response)
{
    console.log(Response);
    contador = 0;
    campo = "";
    //console.log(Response);
    //$("#calendario_servicio").html("");
    $.each(Response, function(index, value)
    {
        if(contador == 0)
            linea =  $("<tr></tr>");

        if(value['dia'] == 0)
            campo += "<td></td>";
        else
            campo += "<td "+value['clase']+" title='Saldo: $"+value['saldo']+" ( "+value['percent']+"% )'>"+value['dia']+"</td>";

        if(contador == 5)
        {
            linea.append(campo);
            $("#calendario_servicio").append(linea);
            campo = "";
            contador = 0;
        }else
            contador++;
    });
}

function CargaCalendarioMostrador(Response)
{
    contador = 0;
    campo = "";
    //console.log(Response);
    //$("#calendario_servicio").html("");
    $.each(Response, function(index, value)
    {
        if(contador == 0)
            linea =  $("<tr></tr>");

        if(value['dia'] == 0)
            campo += "<td></td>";
        else
            campo += "<td "+value['clase']+" title='Saldo: $"+value['saldo']+" ( "+value['percent']+"% )'>"+value['dia']+"</td>";

        if(contador == 5)
        {
            linea.append(campo);
            $("#calendario_mostrador").append(linea);
            campo = "";
            contador = 0;
        }else
            contador++;
    });
}

function moneda(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}


