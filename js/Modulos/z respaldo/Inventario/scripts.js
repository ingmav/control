var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 0;
var filtro = 0;
var idArticulo = 0;


function sinInventario()
{
    if(activas==1)
        activas = 0;
    else
        activas = 1;

    recargaLista();
}
function InicializaInventario()
{
	var variable = "accion=inicializainventario";
    RestFullRequest("_Rest/Inventario.php", variable, "verificaInicializacion");
}	

function datagridInventario(Response)
{
    inicializafechainventario();
	var datagrid  = $("#DatagridInventario");

    datagrid.html("");

	$.each(Response, function(index, value)
	{
		var id = value['ARTICULOSWEB.ID'];
		var campos;
		//campos += "<td>"+value['FECHACORTE']+"</td>";
		campos += "<td>"+value['ARTICULOSWEB.NOMBRE']+"</td>";
        campos += "<td>"+value['ARTICULOSWEB.UNIDAD']+"</td>";
        campos += "<td>"+value['ARTICULOSWEB.NOMBRELINEA']+"</td>";
		campos += "<td>"+value['CANTIDADINICIAL']+"</td>";
        campos += "<td>"+value['INGRESO']+"</td>";
		//campos += "<td>"+value['INVENTARIOMICRO']+"</td>";

        campos += "<td>"+value['INVENTARIOOPERACION']+"</td>";

		//value['INVENTARIOFINAL']
		campos += "<td>"+value['INVENTARIOFINALOPERACION']+"</td>";
		//campos += "<td>"+value['ARTICULOSWEB.MINIMO']+"</td>";
        var color = "";
        if(value['bandera']==0)
            color = "style=color:rgb(230,0,0)";

		linea = $("<tr data-fila='"+id+"' id='"+id+"' "+color+"></tr>");
		linea.append(campos);
		
		datagrid.append(linea);

        cargaSubArticulos(id);
	});
}

function cargaSubArticulos(id)
{

    var variable = "accion=inicializasubarticulos&id="+id;
    RestFullRequest("_Rest/Inventario.php", variable, "cargaComboSubArticulos");
}


function cargaComboSubArticulos(Response)
{
    console.log(Response);
    var contador = 0;
    var campo = "";
    //
    campo += "<table width='100%' class='table table-striped' id="+idArticulo+"><tr style='background: #ebebeb'><td width='25%'>NOMBRE</td><TD width='25%'>INVENTARIO INICIAL</TD><TD width='25%'>INVENTARIO IMPRESION</TD><TD width='25%'>INVENTARIO FINAL</TD></tr>";
    $.each(Response, function(index, value)
    {

        contador++;
        campo += "<TR><td>"+value['NOMBRE']+"</td><td>"+currency(value['CANTIDAD'],2,".")+"</td><td>"+currency(value['INVENTARIO'],2,".")+"</td><td>"+currency(value['FINAL'],2,".")+"</td></TR>";
    });
        campo +="</table>";

    if(contador > 0)
    {
        $("#"+Response[0]['IDARTICULOWEB']).append(campo);

    }

}

function agregarInventario()
{
	$("#agregarInventario").modal("show");
}

function inicializafechainventario()
{
    var variable = "accion=inicializafechainventario";
    RestFullRequest("_Rest/Inventario.php", variable, "cargafechainventario");
}

function cargafechainventario(Response)
{
    $("#tituloInventario").html("INVENTARIO NO. <span style='color:#E21800'><strong>"+Response[0][2]+"</strong></span> DEL <span style='color:#E21800'><strong>"+Response[0][0]+"</strong></span> AL <span style='color:#E21800'><strong>"+Response[0][1]+"</strong></span>");
}

function sustraerInventario()
{
	$("#sustraerInventario").modal("show");
}

/*function inicializaArticulos()
{
	var variable = "accion=inicializaarticulos";
    RestFullRequest("_Rest/Inventario.php", variable, "rellenaArticulos");
}

function rellenaArticulos(Response)
{
	$("#agregarArticulo").html("");
    $("#sustraerArticulo").html("");
	$.each(Response, function(index, value)
	{
		$("#agregarArticulo").append("<option value="+value['ID']+">"+value['NOMBRE']+"</option>");
		$("#sustraerArticulo").append("<option value="+value['ID']+">"+value['NOMBRE']+"</option>");
	});
}*/


$("#lineaArticulo").on("change", function(){
    var variable = "accion=inicializacomboarticulos&linea="+$("#lineaArticulo").val();
    RestFullRequest("_Rest/Inventario.php", variable, "cargacomboarticulos");
});

function cargacomboarticulos(Response)
{
    $("#agregarArticulo").html("");
    $("#agregarArticulo").append("<OPTION value='0'>SELECCIONE UNA OPCIÓN</OPTION>");


    $.each(Response, function(index, value)
    {
        $("#agregarArticulo").append("<option value="+value['ID']+">"+value['NOMBRE']+"</option>");
    });

    $("#agregarsubArticulo").html("");
}

$("#agregarArticulo").on("change", function()
{
    var variable = "accion=inicializasubcomboarticulos&articulo="+$("#agregarArticulo").val();
    RestFullRequest("_Rest/Inventario.php", variable, "cargasubcomboarticulos");
});

function cargasubcomboarticulos(Response)
{
    $("#agregarsubArticulo").html("");
    $("#agregarsubArticulo").append("<OPTION value='0'>SELECCIONE UNA OPCIÓN</OPTION>");


    $.each(Response, function(index, value)
    {
        $("#agregarsubArticulo").append("<option value="+value['ID']+">"+value['NOMBRE']+"</option>");
    });
}

$(document).ready(function(e) {
	
    //inicializaArticulos();
    verificaInicializacion();
    inicializafiltro();
    $("#control").find("a").click();
});

function inicializafiltro()
{
    var variable = "accion=inicializafiltro";
    RestFullRequest("_Rest/Inventario.php", variable, "cargaFiltro");
}

function cargaFiltro(Response)
{
    //$("#linefilter").append("<option VALUE='0'>TODOS</option>");
    $.each(Response, function(index, value)
    {
       $("#linefilter").append("<option VALUE='"+value['IDLINEA']+"'>"+value['NOMBRELINEA']+"</option>");
       $("#lineaArticulo").append("<option VALUE='"+value['IDLINEA']+"'>"+value['NOMBRELINEA']+"</option>");
    });
}

$("#linefilter").on("change", function()
{
    $("#DatagridInventario").html("");
    recargaLista();
});

function guardarArticulo()
{
    $("#agregarInventario").modal("hide");
	var variable = "accion=saveArticulo&"+$("#formAgregar").serialize();
    RestFullRequest("_Rest/Inventario.php", variable, "recargaLista");
}

function recargaLista()
{
    var variable = "accion=index&filtro="+$("#linefilter").val()+"&activa="+activas;
    RestFullRequest("_Rest/Inventario.php", variable, "datagridInventario");
}
function verificaInicializacion()
{
	var variable = "accion=validaInicializacion";
    RestFullRequest("_Rest/Inventario.php", variable, "Inicializar");
}

function Inicializar(Response)
{

	
	if(Response['resultado']==0)
	{
		
		$("#InventarioInicializa").show();
		$("#InventarioAgregar").hide();
		$("#InventarioSustrae").hide();
		$("#InventarioReporte").hide();
		$("#InventarioReajusta").hide();
		$("#InventarioCorte").hide();
	}else if(Response['resultado'] == 1)
	{
        recargaLista();
		$("#InventarioInicializa").hide();
		$("#InventarioAgregar").show();
		$("#InventarioSustrae").show();
		$("#InventarioReporte").show();
		$("#InventarioReajusta").hide();
		$("#InventarioCorte").show();
	}
	else if(Response['resultado'] == 2)
	{
        recargaLista();

		$("#InventarioInicializa").hide();
		$("#InventarioAgregar").hide();
		$("#InventarioSustrae").hide();
		$("#InventarioReporte").show();
		$("#InventarioReajusta").show();
		$("#InventarioCorte").hide();
	}
}

function quitarArticulo()
{
    $("#sustraerInventario").modal("hide");
	var variable = "accion=disminurArticulo&"+$("#formSustraer").serialize();
    RestFullRequest("_Rest/Inventario.php", variable, "recargaLista");
}

function corteInventario()
{
	var variable = "accion=reajuste";
    RestFullRequest("_Rest/Inventario.php", variable, "verificaCorte");
}
 
 function verificaCorte()
 {
 	$("#InventarioInicializa").hide();
	$("#InventarioAgregar").hide();
	$("#InventarioSustrae").hide();
	$("#InventarioReporte").show();
	$("#InventarioReajusta").show();
	$("#InventarioCorte").hide();
 }
function openreajuste()
{
	$("#reajustar").modal("show");
	var variable = "accion=fillarticulos";
    RestFullRequest("_Rest/Inventario.php", variable, "rellenarReajuste");
}

function rellenarReajuste(Response)
{
	var contador = 0;
    console.log(Response);
	$("#reajustearticulos").html("");
	$.each(Response, function(index, value)
	{
		var color = "";
		
		if((contador%2) ==0)
			color = "style='background:#EFEFEF'";

        var reajuste =0;
        if(value['REAJUSTE']>0)
            reajuste = value['REAJUSTE'];
        else
            reajuste = value['INVENTARIO'];

		//$("#reajustearticulos").append("<tr "+color+"><td>"+value['ARTICULOSWEB.NOMBRE']+"</td><td>"+value['ARTICULOSWEB.UNIDAD']+"</td><td>"+value['ARTICULOSWEB.NOMBRELINEA']+"</td><td><input type='text' name='cantidad_"+value['ARTICULOSWEB.ID']+"' value='"+reajuste+"'></td><td><input type='checkbox' name='ARTICULOSWEB[]' value='"+value['ARTICULOSWEB.ID']+"' checked='checked'></td></tr>");
		$("#reajustearticulos").append("<tr "+color+"><td>"+value['ARTICULOSWEB.NOMBRE']+"</td><td>"+value['ARTICULOSWEB.UNIDAD']+"</td><td>"+value['ARTICULOSWEB.NOMBRELINEA']+"</td><td><input type='text' name='cantidad_"+value['ARTICULOSWEB.ID']+"' value='"+value['INVENTARIOFINALOPERACION']+"'></td><td><input type='checkbox' name='ARTICULOSWEB[]' value='"+value['ARTICULOSWEB.ID']+"' checked='checked'></td></tr>");
		contador++;
	});
}

function agregarReajuste()
{
	$("#reajustar").modal("hide");
    $("#reajustarsubarticulos").modal("show");

	var variable = "accion=addReajuste&"+$("#formReajuste").serialize();
    RestFullRequest("_Rest/Inventario.php", variable, "cargasubarticulos");
    //verificaInicializacion();
}

function cargasubarticulos()
{
    var variable = "accion=cargasubarticulos";
    RestFullRequest("_Rest/Inventario.php", variable, "rellenasubarticulos");
}

function rellenasubarticulos(Response)
{
    var datagrid  = $("#reajustesubarticulos");

    datagrid.html("");

    $.each(Response, function(index, value)
    {
        var id = value['ID'];
        var campos;

        campos += "<td>"+value['NOMBREARTICULO']+"</td>";
        campos += "<td>"+value['NOMBRE']+"</td>";
        campos += "<td><input type='text' name='subarticulo_"+value['ID']+"' value='"+value['CANTIDAD']+"'></td>";
        campos += "<td><input type='checkbox' name='ARTICULOSWEB[]' value='"+value['ID']+"' checked='checked'></td>";

        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
        linea.append(campos);

        datagrid.append(linea);
    });
}

function agregarsubarticulos()
{
    var variable = "accion=guardasubarticulos&"+$("#formReajustesub").serialize();
    RestFullRequest("_Rest/Inventario.php", variable, "finalizacorte");
}

function finalizacorte(Response)
{
    $("#reajustarsubarticulos").modal("hide");
    verificaInicializacion();
    recargaLista();
}

function enviarReporte()
{
	window.open("ReporteInventario.php", "_blank");
}

function currency(value, decimals, separators) {
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