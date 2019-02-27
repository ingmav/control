var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;
	
		
$( "#search" ).keypress(function(e) {
	if(e.keyCode == 13)
	{
		paginacion = 1;
		buscar = $("#search").val();
		var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&page="+paginacion;
    	RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
		paginador();
	}
});

function buscarRealizados()
{
	var variable = "accion=index&empresa="+empresa+"&realizados="+$("#realizados").val()+"&fecha="+$("#fecha").val()+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "datagridPendientes");
	
}

function datagridPendientes(response)
{
	var datagrid = $("#PendientesDiseno");
	datagrid.find("tr").remove();
	var contador = 0;
	console.log(response);
	$.each(response, function(index, value)
	{
		
		var campos = "";
		var index = 0;
		var id;
		var orden_dia = "";
		id 					= value['IDPRODUCCION'];
		idtablero 			= value['IDTABLERO'];
		idtableroproduccion = value['ID']; 

		var labelPrioridad = "";
		var labelCancelacion = "";
		
		var btn = "";
        var icono = "";
        if(value['ACTIVACION'] == "" || value['ACTIVACION']==0)
        {
            btn = "default";
            icono = "pause";    
        }else
        {
            btn = "warning";
            icono = "check-square-o";    
        }

        if(value['EMPRESA'] !=3 )
            campos += "<td><button class='btn  btn-"+btn+"' type='button' onclick='cambia_estatus(this, "+value['EMPRESA']+")'><i class='fa fa-"+icono+"'></i> "+value['FOLIO']+"</button></td>";
        else
            campos += "<td><button class='btn  btn-"+btn+"' type='button' onclick='cambia_estatus_mostrador(this)'><i class='fa fa-"+icono+"'></i> "+value['FOLIO']+"</button></td>";
        
		texto_descripcion = "";

		texto_descripcion = value['DESCRIPCION'];
		if(texto_descripcion.length > 0)
			texto_descripcion += "<br>";

		$.each(value['MATERIALES'], function(index2, value2)
        {
            texto_descripcion += "- "+value2['NOMBRE']+" ("+parseFloat(value2['UNIDADES'],2)+") <br>";
        });

		
		campos += "<td><font style='background-color:green;color:white'>"+value['FECHA']+"</font><br><font style='background-color:red;color:white'>"+value['F_ENTREGA']+"</font></td>";
		
		campos += "<td style='text-align:justify'><b style='color:blue'>"+value['NOMBRE_CLIENTE']+"</b><br>"+value['DESCRIPCION']+"</td>";

		
		campos += "<td>"+value['NOMBRE_OPERADOR']+"</td>";
		if(value['EMPRESA']!=3)
          linea = $("<tr data-fila='"+id+"' data-tablero='"+idtablero+"'  data-empresa='"+value['EMPRESA']+"' data-id='"+idtableroproduccion+"' style='color:blue;font-weight:bold;background: #e1e1e1;'></tr>");
            else
		  linea = $("<tr data-fila='"+id+"' data-tablero='"+idtablero+"'  data-empresa='"+value['EMPRESA']+"' data-id='"+id+"' style='color:blue;font-weight:bold;background: bisque;'></tr>");
		
        
		var observacion = "<button type='button' class='btn btn-strech  btn-info' title='OBSERVACIONES' onClick='observaciones(this, "+value['EMPRESA']+")'>"+value['CONTADOR_MESSAGE']+"<i class='fa fa-comment'></i></button>";
		var finalizar = "<button type='button' class='btn btn-strech btn-success' title='FINALIZAR ACTIVIDAD' onClick='finalizar(this, "+value['EMPRESA']+")'><i class='fa fa-check'></i></button>";
		
		//prioridad   turnar   rechazo
		if($("#realizados").val() == 1)	
        {
            if(value['EMPRESA'] !=3 )
			    campos += "<td>"+observacion+" "+finalizar+"</td>";
            else
                campos += "<td>"+observacion+" "+finalizar+"</td>";
        }
		else
			campos += "<td><label class='label label-success'>REALIZADO</label></td>";

		linea.append(campos);
		datagrid.append(linea);

		var linea2 = $("<tr></tr>");
		campo_unico = $("<td colspan='5'>"+texto_descripcion+"</td>");
		var linea3 = $("<tr></tr>");
		campo_blanco = $("<td colspan='5'></td>");
		
		linea2.append(campo_unico);
		datagrid.append(linea2);

		linea3.append(campo_blanco);
		datagrid.append(linea3);
		contador++;
	});
	if(contador == 0)
		datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function cambia_estatus(obj, empresa)
    {
        btn_seleccionador = obj;
        $(obj).find("i").removeClass("fa-pause");
        $(obj).find("i").removeClass("fa-check-square-o");
        $(obj).find("i").addClass("fa-circle-o-notch fa-spin");

        var variable = "accion=activarActividad&id="+$(obj).parents("tr").data("tablero")+"&EMPRESA="+empresa;
        RestFullRequest("_Rest/GENERAL.php", variable, "ActivaActividadMostrador");
        
    }

    function cambia_estatus_mostrador(obj)
    {
        btn_seleccionador = obj;
        $(obj).find("i").removeClass("fa-pause");
        $(obj).find("i").removeClass("fa-check-square-o");
        $(obj).find("i").addClass("fa-circle-o-notch fa-spin");

        var variable = "accion=activarActividad&id="+$(obj).parents("tr").data("fila");
        RestFullRequest("_Rest/PendientesPv.php", variable, "ActivaActividadMostrador");
        
    }

    function ActivaActividadMostrador(response)
{
    console.log(response);
    $(btn_seleccionador).find("i").removeClass("fa-circle-o-notch fa-spin");
    if(response.data == 1)
    {
        $(btn_seleccionador).removeClass("btn-default");
        $(btn_seleccionador).addClass("btn-warning");
        $(btn_seleccionador).find("i").removeClass("fa-pause");
        $(btn_seleccionador).find("i").addClass("fa-check-square-o");     
    }else if(response.data == 0)
    {
        $(btn_seleccionador).addClass("btn-default");
        $(btn_seleccionador).removeClass("btn-warning");
        $(btn_seleccionador).find("i").addClass("fa-pause");
        $(btn_seleccionador).find("i").removeClass("fa-check-square-o");

    }
}

    function ActivaActividad(response)
	{
	    console.log(response);
	    $(btn_seleccionador).find("i").removeClass("fa-circle-o-notch fa-spin");
	    if(response.data == 1)
	    {
	        $(btn_seleccionador).removeClass("btn-default");
	        $(btn_seleccionador).addClass("btn-warning");
	        $(btn_seleccionador).find("i").removeClass("fa-pause");
	        $(btn_seleccionador).find("i").addClass("fa-check-square-o");     
	    }else if(response.data == 0)
	    {
	        $(btn_seleccionador).addClass("btn-default");
	        $(btn_seleccionador).removeClass("btn-warning");
	        $(btn_seleccionador).find("i").addClass("fa-pause");
	        $(btn_seleccionador).find("i").removeClass("fa-check-square-o");

	    }
	}
/*function datagridPendientes(response)
{
	//actualizaProcesos();
	
	console.log(response);
	var datagrid = $("#PendientesDiseno");
	datagrid.find("tr").remove();
	var contador = 0;

	$.each(response, function(index, value)
	{
		
		var campos = "";
		var index = 0;
		var id;
		id = value['TABLEROPRODUCCION.ID'];
		var labelPrioridad = "";
		var orden_dia = "";
		var pendientes = "";

		if(value['TABLEROPRODUCCION.PRIORIDAD'] == 1)
			labelPrioridad = "<label class='label label-warning'>PRIORIDAD</label>";

		var labelCancelacion = "";
		if(value['PRODUCCION.IDESTATUS'] == 3)
			labelCancelacion = "<label class='label label-danger'>RECHAZADO</label><BR>"+value['PRODUCCION.DESCRIPCIONCANCELACION'];

		if($("#realizados").val() == 1)
		{
			if(value['ORDENDIA.FECHAORDEN'] !="")
			var orden_dia = "<button type='button' class='btn btn-strech btn-warning' title='ACTIVA' onClick='desactivaProceso("+id+", "+value['EMPRESA']+", 2)'><i class='fa fa-buysellads'></i></button>";
			else
			var orden_dia = "<button type='button' class='btn btn-strech'  title='ACTIVA' onClick='activaProceso("+id+", "+value['EMPRESA']+", 2)'><i class='fa fa-buysellads'></i></button>";
			
			if(value['ORDENPENDIENTE.FECHAPENDIENTE'] !="")
				var pendientes = "<button type='button' class='btn btn-strech btn-success'  title='PENDIENTE' onClick='desactivaPendiente("+id+", "+value['EMPRESA']+", 2)'><i class='fa fa-pinterest-p'></i></button>";
			else
				var pendientes = "<button type='button' class='btn btn-strech' title='PENDIENTE'  onClick='activaPendiente("+id+", "+value['EMPRESA']+", 2)'><i class='fa fa-pinterest-p'></i></button>";	
		}

		texto_descripcion = "";


		if(value['DOCTOS_VE.DESCRIPCION'] != value['TABLEROPRODUCCION.NOTA'])
			texto_descripcion += "<u>"+value['DOCTOS_VE.DESCRIPCION']+"</u>"+"<BR>"+value['TABLEROPRODUCCION.NOTA'];
		else
			texto_descripcion += "<u>"+value['DOCTOS_VE.DESCRIPCION']+"</u>";

		campos += "<td>"+value['NOMBREEMPRESA']+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.ESTATUS']+"<br>"+orden_dia+" "+pendientes+"</td>";
		campos += "<td>"+value['TABLEROPRODUCCION.FECHA']+"</td>";
		campos += "<td style='text-align:justify'><b style='color:blue'>"+value['CLIENTES.NOMBRE']+"</b><b> ("+parseFloat(value['DOCTOS_VE_DET.UNIDADES']).toFixed(2)+" "+value['ARTICULOS.UNIDAD_VENTA']+")</b><br>"+texto_descripcion+"<br>"+labelCancelacion+"</td>";
		//campos += "<td>"+parseFloat(value['DOCTOS_VE_DET.UNIDADES']).toFixed(2)+" "+value['ARTICULOS.UNIDAD_VENTA']+"</td>";
		//campos += "<td>"+texto_descripcion+"<br>"+labelCancelacion+"</td>";
		
		campos += "<td>"+value['OPERADOR.ALIAS']+"</td>";
		
		linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

		//contadorMensajes(id, value['EMPRESA']);
		var prioridad;
		var cancelacion;
		
		if($("#realizados").val() == 1)		
			campos += "<td><button type='button' class='btn btn-strech btn-info' title='OBSERVACIONES' onClick='observaciones(this, "+value['EMPRESA']+")'>"+value['CONTADOR_MESSAGE']+"<i class='fa fa-comment'></i></button> <button type='button' class='btn btn-strech btn-success' title='FINALIZAR ACTIVIDAD' onClick='finalizar(this, "+value['EMPRESA']+")'><i class='fa fa-check'></i></button></td>";
		else
			campos += "<td><label class='label label-success'>REALIZADO</label></td>";
		linea.append(campos);
		
		datagrid.append(linea);
		contador++;
	});
	if(contador == 0)
		datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}*/

function activaProceso(idProceso, emp, tipo)
{
	var variable = "accion=saveActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid");

    var variable = "accion=deleteActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid");
}

function activaPendiente(idProceso, emp, tipo)
{
	var variable = "accion=saveActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid");

    var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid");
}

function desactivaProceso(idProceso, emp, tipo)
{
	var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid");
}

function desactivaPendiente(idProceso, emp, tipo)
{
	var variable = "accion=deleteActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid");
}

function Activas()
{
	if(activas == 1)
		activas = 2;
	else
		activas = 1;

	actualizaDatagrid();	
}

/*function contadorMensajes(id, emp)
{
	empresa = emp;
	var variable = "accion=countMessaje&empresa="+empresa+"&id="+id;
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "cargaCountMessaje");
}

function cargaCountMessaje(Response)
{
	
	if(Response[0].count > 0)
		$("#"+Response[0].ID).find("button:eq(2)").removeClass("btn-info").addClass("btn-primary");	
	$("#"+Response[0].ID).find("button:eq(2)").append(Response[0].count);
}*/

function observaciones(obj, emp)
{
    empresa = emp;
    
    if($(obj).parents("tr").data("empresa") != 3)
    {
    	$("#idtablero").val($(obj).parents("tr").data("id"));
        var variable = "accion=observaciones&EMPRESA="+empresa+"&id="+$(obj).parents("tr").data("id")+"&departamento=2";
        
        RestFullRequest("_Rest/General.php", variable, "cargaObservaciones");    
    }else
    {
        empresa = 3;
        $("#id").val($(obj).parents("tr").data("fila"));// Es aqui
        var variable = "accion=observaciones&id="+$(obj).parents("tr").data("fila")+"&departamento=2";
        
        RestFullRequest("_Rest/PendientesPv.php", variable, "cargaObservaciones");
    }
}

function turnarEmpleado(obj, emp)
{
	empresa = emp;
	$("#finalizaTarea textArea")
	$("#idtablerooperador").val($(obj).parents("tr").data("tablero"));
	$("#finalizaTarea").modal("show");

}

function finalizar(obj, emp)
{
	empresa = emp;

	$("#finalizaTarea textArea").val("");

 	$("#idtablerofinalizar").val($(obj).parents("tr").data("tablero"));
	$("#finalizaTarea").modal("show");
}

function operadores()
{
	var variable = "accion=operadores&empresa="+empresa+"&"+$("#formOperadores").serialize();
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "cargaOperadores");
}

function guardarObservacion()
{
	if(empresa!=3)
    {
    	var variable = "accion=saveObservacion&empresa="+empresa+"&"+$("#formObservaciones").serialize();
        RestFullRequest("_Rest/PendientesDiseno.php", variable, "actualizaDatagrid", 1);
        $("#observaciones").modal("hide");
    }else
    {
        var variable = "accion=saveObservacion&"+$("#formObservaciones").serialize()+"&departamento=2";
        RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
        $("#observaciones").modal("hide");
    }
	
}

function guardarTurnar()
{
	if(empresa!=3)
	{
		var variable = "accion=saveTurnar&empresa="+empresa+"&departamento=2&"+$("#formFinalizar").serialize();
	    RestFullRequest("_Rest/General.php", variable, "actualizaDatagrid");
	}else
	{
		var variable = "accion=saveTurnar&empresa="+empresa+"&"+$("#formFinalizar").serialize()+"&departamento=2";
	    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
	}
    $("#finalizaTarea").modal("hide");
	
}

function guardar()
{
	if(empresa!=3)
	{
		var variable = "accion=save&empresa="+empresa+"&departamento=2&"+$("#formFinalizar").serialize();
	    RestFullRequest("_Rest/General.php", variable, "actualizaDatagrid", 1);
	}else
	{
		var variable = "accion=save&empresa="+empresa+"&"+$("#formFinalizar").serialize()+"&departamento=2";
	    RestFullRequest("_Rest/PendientesPV.php", variable, "actualizaDatagrid", 1);
	}
	$("#finalizaTarea").modal("hide");
	
}

function cargaObservaciones(Response)
{
	var contador = 0;
	$("#descripcionobservaciones").find("tr").remove();
	$("#observaciones").modal("show");
	$.each(Response, function(index, value)
	{
        if(value['TABLEROOBSERVACION.OBSERVACION'])
            $("#descripcionobservaciones").append("<tr><td> > "+value['TABLEROOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['TABLEROOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
        else
            $("#descripcionobservaciones").append("<tr><td> > "+value['PVOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['PVOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
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
		$("#selectEmpleado").append('<option value='+value['OPERADOR.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		$("#EmpleadoFinalizar").append('<option value='+value['OPERADOR.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		
	});
}

function actualizaDatagrid()
{

	var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+$("#FormLevantamiento").serialize()+"&activas="+activas+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    RestFullRequest("_Rest/PendientesDiseno.php", variable, "datagridPendientes");
	//paginador();
}

$(document).ready(function(e) {

	actualizaDatagrid();
	//actualizaProcesos();
    $("#operacion").find("a").click();
	//setInterval(actualizaDatagrid,  900000);
	operadores();
});