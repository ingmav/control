var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;
var id_aux = 0;
	

function datagridPendientes(response)
{
	actualizaProcesos();
	//console.log(response);
	//paginador();
	var datagrid = $("#PendientesImpresion");
	datagrid.find("tr").remove();
	var contador = 0;

	console.log("\n");
	console.log(response);
	console.log("\n");

	$.each(response, function(index, value)
	{
		
		var campos = "";
		var index = 0;
		var id;
		var orden_dia = "";
		id = value['TABLEROPRODUCCION.ID'];

		var labelPrioridad = "";
		if(value['TABLEROPRODUCCION.PRIORIDAD'] == 1)
			labelPrioridad = "<label class='label label-warning'>PRIORIDAD</label>";

		var labelCancelacion = "";
		if(value['PRODUCCION.IDESTATUS'] == 3)
			labelCancelacion = "<label class='label label-danger'>RECHAZADO</label><BR>"+value['PRODUCCION.DESCRIPCIONCANCELACION'];

		//
		if($("#realizados").val() == 1)
		{
			if(value['ORDENDIA.FECHAORDEN'] !="")
			var orden_dia = "<button type='button' class='btn btn-warning btn-strech' onClick='desactivaProceso("+id+", "+value['EMPRESA']+", 3)'><i class='fa fa-spin fa-cog'></i></button>";
			else
			var orden_dia = "<button type='button' class='btn btn-strech' onClick='activaProceso("+id+", "+value['EMPRESA']+", 3)'><i class='fa fa-circle'></i></button>";
			
		}
		campos += "<td>"+value['NOMBREEMPRESA']+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.ESTATUS']+"<br>"+orden_dia+"</td>";
		campos += "<td>"+value['TABLEROPRODUCCION.FECHA']+"</td>";
		campos += "<td>"+value['CLIENTES.NOMBRE']+"</td>";
		campos += "<td>"+parseFloat(value['DOCTOS_VE_DET.UNIDADES']).toFixed(2)+" "+value['ARTICULOS.UNIDAD_VENTA']+"</td>";
		campos += "<td>"+value['NOMBREARTICULO']+"<br><br>"+value['TABLEROPRODUCCION.NOTA']+"<br>"+labelCancelacion+"</td>";
		//campos += "<td>"+value['TABLEROPRODUCCION.NOTA']+"</td>";
		campos += "<td>"+value['OPERADOR.ALIAS']+"</td>";
		
		linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
		var prioridad;

		contadorMensajes(id, value['EMPRESA']);

		/*if(value['TABLEROPRODUCCION.PRIORIDAD'] == 1)
			prioridad  = "<button type='button' class='btn btn-circle  btn-warning' title='PRIORITARIO'><i class='fa fa-exclamation'></i></button>";
		else
			prioridad  = "<button type='button' class='btn btn-circle  btn-default' title='PRIORITARIO'><i class='fa fa-exclamation'></i></button>";*/

		var observacion = "<button type='button' class='btn btn-strech  btn-info' title='OBSERVACIONES' onClick='observaciones(this, "+value['EMPRESA']+")'><i class='fa fa-comment'></i></button>";
		//var turnar = "<button type='button' class='btn btn-circle  btn-primary' title='TURNAR' onclick='turnarEmpleado(this, "+value['EMPRESA']+")'><i class='fa fa-share'></i></button>";
		var finalizar = "<button type='button' class='btn btn-strech btn-success' title='FINALIZAR ACTIVIDAD' onClick='finalizar(this, "+value['EMPRESA']+")'><i class='fa fa-check'></i></button>";
		var cancelar = "<button type='button' class='btn btn-strech btn-danger' title='CANCELAR' onClick='cancelar(this, "+value['EMPRESA']+")'><i class='fa fa-close'></i></button>";
		//var rechazo = "<button type='button' class='btn btn-circle  btn-danger' title='RECHAZADO' onClick='verObservacionesRechazo(this, "+value['EMPRESA']+")'><i class='fa fa-exclamation'></i></button>";

		//prioridad   turnar   rechazo
		if($("#realizados").val() == 1)	
			campos += "<td>"+observacion+" "+finalizar+" "+cancelar+"</td>";
		else
			campos += "<td><label class='label label-success'>REALIZADO</label></td>";

		//campos += "<td><input type='checkbox' name='impresion[]' value='"+value['TABLEROPRODUCCION.ID']+"'></td>";
		linea.append(campos);

		
		datagrid.append(linea);
		contador++;
	});
	if(contador == 0)
		datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function actualizadatagridinventario(id)
{
    id_aux = id;
    $("#registrosarticulos").html("");
    var variable = "accion=cargainventario&idproduccion="+id+"&empresa="+empresa;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargadatagridinventario");
}

function cargadatagridinventario(Response)
{
    var variable = "accion=cargainventarioutilizado&idproduccion="+id_aux+"&empresa="+empresa;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargadocumentoutilizado");

    $.each(Response, function(index, value)
    {

        texto = "<div class='row'>";
        texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBRELINEA']+"</label></div>";
        texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBREARTICULO']+"</label></div>";
        texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBRESUBARTICULO']+"</label></div>";
        texto+= "<div class='col-lg-1'><label class='control-label'>"+value['CANTIDAD']+"</label></div>";
        texto+= "<div class='col-lg-1'><label class='control-label'>"+value['MERMA']+"</label></div>";
        texto+= "<div class='col-lg-3'><label class='control-label'>"+value['MOTIVO']+"</label></div>";
        texto+= "<div class='col-lg-1'><label class='control-label'><button class='btn btn-strech btn-danger' type='button' onclick='eliminaInventario("+value['ID']+")'><i class='fa fa-close'></i></i></button></label></div>";
        texto+= "</div>";

        $("#registrosarticulos").append(texto);
    });

    $("#articulosUtilizados").html("");


}

function cargadocumentoutilizado(Response)
{
    texto = "<div class='row' style='background: #CFCFCF'>";
    texto+= "<div class='col-lg-8'><label class='control-label'>ARTICULO</label></div>";
    texto+= "<div class='col-lg-2'><label class='control-label'>UNIDADES</label></div>";
    texto+= "<div class='col-lg-2'><label class='control-label'>UTILIZADOS</label></div>";
    texto+= "</div>";
    $.each(Response, function(index, value)
    {
        var estilo = "";
        if((index%2) == 1)
            estilo = " style='background:#EFEFEF'";

        texto += "<div class='row' "+estilo+">";
        texto+= "<div class='col-lg-8'><label class='control-label'>"+value['NOMBRE']+"</label></div>";
        texto+= "<div class='col-lg-2'><label class='control-label'>"+parseFloat(value['UNIDADES'])+"</label></div>";
        texto+= "<div class='col-lg-2'><label class='control-label'>"+parseFloat(value['UTILIZADO'])+"</label></div>";
        texto+= "</div>";

        $("#articulosUtilizados").append(texto);
        texto = "";
    });
}

function eliminaInventario(id)
{
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
        var variable = "accion=deleteinventario&idinventario="+id+"&empresa="+empresa;
        RestFullRequest("_Rest/PendientesImpresion.php", variable, "refresDatagridInventario");
    }
}

function refresDatagridInventario()
{
    actualizadatagridinventario($("#idproduccion").val());
}

function buscarRealizados()
{
	var variable = "accion=index&empresa="+empresa+"&realizados="+$("#realizados").val()+"&fecha="+$("#fecha").val()+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "datagridPendientes");
}	

function activaProceso(idProceso, emp, tipo)
{
	var variable = "accion=saveActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
}

function desactivaProceso(idProceso, emp, tipo)
{
	var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
}

function Activas()
{
	if(activas == 1)
		activas = 2;
	else
		activas = 1;

	actualizaDatagrid();	
}

function verObservacionesRechazo(id, emp)
{
	empresa = emp;
	var variable = "accion=vercancelacion&empresa="+empresa+"&id="+$(id).parents("tr").data("fila");
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaCancelacion");
}


function contadorMensajes(id, emp)
{
	empresa = emp;
	var variable = "accion=countMessaje&empresa="+empresa+"&id="+id;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaCountMessaje");
}

function cargaCountMessaje(Response)
{
	if(Response[0].count > 0)
		$("#"+Response[0].ID).find("button:eq(1)").removeClass("btn-info").addClass("btn-primary");	
	$("#"+Response[0].ID).find("button:eq(1)").append(Response[0].count);
}

function observaciones(obj, emp)
{
	empresa = emp;
	$("#idtablero").val($(obj).parents("tr").data("fila"));
	var variable = "accion=observaciones&empresa="+empresa+"&id="+$(obj).parents("tr").data("fila");
	
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaObservaciones");
}

function turnarEmpleado(obj, emp)
{
	empresa = emp;
	$("#idtablerofinalizar").val($(obj).parents("tr").data("fila"));
	$("#finalizaTarea").modal("show");


}

function finalizar(obj, emp)
{
	empresa = emp;
	$("#idtablerofinalizar").val($(obj).parents("tr").data("fila"));
    $("#idproduccion").val($(obj).parents("tr").data("fila"));
	$("#finalizaTarea").modal("show");
    actualizadatagridinventario($(obj).parents("tr").data("fila"));

}

function cancelar(obj, emp)
{
	empresa = emp;
	$("#idtablerocancelar").val($(obj).parents("tr").data("fila"));
	$("#CancelarTarea").modal("show");

}



function operadores()
{
	var variable = "accion=operadores&empresa="+empresa+"&"+$("#FormLevantamiento").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaOperadores");
}

function guardarObservacion()
{
	var variable = "accion=saveObservacion&empresa="+empresa+"&"+$("#formObservaciones").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
    $("#observaciones").modal("hide");
	
}

function guardarTurnar()
{
	var variable = "accion=saveTurnar&empresa="+empresa+"&"+$("#formFinalizar").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
    $("#finalizaTarea").modal("hide");
	
}

function guardar()
{
	var variable = "accion=save&empresa="+empresa+"&"+$("#formFinalizar").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "verificarinventario");
}

function verificarinventario(Response)
{
    console.log(Response);
    if(Response[0]['Respuesta'] == 0)
    {
        alert("Debe de reducir de inventario para poder finalizar la actividad");
    }else if(Response[0]['Respuesta'] == 1)
    {
        alert("Es la ultima actividad de este documento, debe de finalizar todo el inventario para poder continuar");
    }else
    {
        actualizaDatagrid();
        $("#finalizaTarea").modal("hide");
    }

}

function cancelaActividad()
{
	var variable = "accion=cancelar&empresa="+empresa+"&"+$("#formCancelar").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
    $("#CancelarTarea").modal("hide");
}

function cargaObservaciones(Response)
{
	var contador = 0;
	console.log(Response);
	$("#descripcionobservaciones").find("tr").remove();
	$("#observaciones").modal("show");
	$.each(Response, function(index, value)
	{
		$("#descripcionobservaciones").append("<tr><td> > "+value['TABLEROOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['TABLEROOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
		contador++;
	});
	if(contador < 1)
		$("#descripcionobservaciones").append("<tr><td>NO EXISTEN OBSERVACIONES</td></tr>");	
	$("#descripcionobservaciones").append("<tr><td colspan='2'><textarea style='resize:none' class='form-control' name='observacion'></textarea></td></tr>");
	
}

function cargaOperadores(Response)
{
	$.each(Response, function(index, value)
	{
		$("#selectEmpleado").append('<option value='+value['OPERADORDEPARTAMENTO.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		$("#EmpleadoFinalizar").append('<option value='+value['OPERADORDEPARTAMENTO.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		
	});
}

function actualizaDatagrid()
{

	var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+$("#FormLevantamiento").serialize()+"&activas="+activas+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "datagridPendientes");
	//paginador();
}

$(document).ready(function(e) {

	actualizaDatagrid();
	//setInterval(actualizaDatagrid,  900000);
    $("#operacion").find("a").click();
	operadores();
    inicializafiltro();
});


function btnagregaInventario()
{

    var variable = "accion=saveInventario&"+$("#formInventario").serialize()+"&empresa="+empresa;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "refresDatagridInventario");

    $("#formInventario #cantidad, #merma, #motivo").val("");
    $("#lineaArticulo").val(0);
    $("#lineaArticulo").change();
}

function ReporteImpresion()

{
	$("#repImpresion").attr("action", "ReporteImpresion.php?tipo=3");
	$("#repImpresion").attr("target", "_blank");
	$("#repImpresion").submit();
	$("#repImpresion").attr("action", "");
	$("#repImpresion").attr("target", "");
}

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

function inicializafiltro()
{
    var variable = "accion=inicializafiltro";
    RestFullRequest("_Rest/Inventario.php", variable, "cargaFiltro");
}

function cargaFiltro(Response)
{
    $("#linefilter").append("<option VALUE='0'>TODOS</option>");
    $.each(Response, function(index, value)
    {
        $("#linefilter").append("<option VALUE='"+value['IDLINEA']+"'>"+value['NOMBRELINEA']+"</option>");
        $("#lineaArticulo").append("<option VALUE='"+value['IDLINEA']+"'>"+value['NOMBRELINEA']+"</option>");
    });
}
