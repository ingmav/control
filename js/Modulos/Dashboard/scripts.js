$(document).ready(function(){
	var variable = "accion=datosGenerales";
    RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaGeneral");
});

$("#ver_info_levantamiento, #ver_info_cotizacion, #ver_info_factura, #ver_info_material, #ver_info_cuentas, #ver_info_actividad_fin").on("click", function()
{
	$("#table_modal1").html("");
	$("#table_modal1").html("<h3><i class='fa fa-circle-o-notch fa-spin'></i> ESPERE UN MOMENTO, ESTAMOS TRABAJANDO</h3>");
	$("#myStructure").modal("show");
	switch($(this).data("indice"))
	{

		case 1: 
			$("#tituloModal").text("Información de Levantamientos");
			var variable = "accion=datosLevantamiento";
    		RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaLevantamiento");
		break;
		case 2: 
			$("#tituloModal").text("Información de Cotizaciones");
			var variable = "accion=datosCotizacion";
    		RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaCotizacion");
		break;
		case 3: 
			$("#tituloModal").text("Información de Facturas");
			var variable = "accion=datosFacturacion";
    		RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaFacturacion");
		break;
		case 4: 
			$("#tituloModal").text("Información de Requisición de Materiales");
			var variable = "accion=datosRequisicion";
    		RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaRequisicion");
		break;
		case 5: 
			$("#tituloModal").text("Información de Cuentas por Cobrar");
			var variable = "accion=datosCxC";
    		RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaCxC");
		break;
		case 6: 
			$("#tituloModal").text("Información de Actividades Finalizadas");
			var variable = "accion=datosFinalizados";
    		RestFullRequest("_Rest/Dashboard.php", variable, "RespuestaFinalizados");
		break;
		default: console.log("no entro"); break;
	}
});


function RespuestaGeneral(Response)
{
	console.log(Response);
	$("#cont_levantamiento").text(Response.levantamiento);
	$("#cont_cotizaciones").text(Response.cotizacion);
	$("#cont_facturas").text(Response.facturacion);
	$("#cont_requerimientos").text(Response.requerimientos);
	$("#cont_cxc").text(Response.cxc);
	$("#cont_actividades").text(Response.finalizados);
}

function RespuestaLevantamiento(Response)
{
	$("#table_modal1").html("");
	var modal = $("#table_modal1");
	var titulo = $("<table class='table table-striped table-bordered table-hover dataTable no-footer' id='table_registros'><tr><td>ID</td><td>FECHA LEVANTAMIENTO</td><td>CLIENTE</td><td>EMPLEADO</td><td>DESCRIPCION</td><td>ESTATUS</td></tr></table>");

	modal.append(titulo);
	var linea = "";
	$.each(Response[0], function(index, value)
	{
		var color = "";
		if(Date.parse(value['LEVANTAMIENTO.FECHALEVANTAMIENTO']) < (Date.parse(Response[1]['date'])))
			color = "class='danger'";	
		
			linea = "<tr "+color+">";
			linea += "<td>"+value['LEVANTAMIENTO.ID']+"</td>";
			linea += "<td>"+value['LEVANTAMIENTO.FECHALEVANTAMIENTO']+"</td>";
			linea += "<td>"+value['LEVANTAMIENTO.NOMBRECLIENTE']+"</td>";
			linea += "<td>"+value['LEVANTAMIENTO.EMPLEADO']+"</td>";
			linea += "<td>"+value['LEVANTAMIENTO.DESCRIPCION']+"</td>";
			linea += "<td>"+value['LEVANTAMIENTOESTATUS.LEVANTAMIENTODESCRIPCION']+"</td>";
			$("#table_registros").append(linea);
		
	});	
}

function RespuestaCotizacion(Response)
{
	$("#table_modal1").html("");
	var modal = $("#table_modal1");
	var titulo = $("<table class='table table-striped table-bordered table-hover dataTable no-footer' id='table_registros'><tr><td>ID</td><td>FECHA COTIZACION</td><td>CLIENTE</td><td>EMPLEADO</td><td>DESCRIPCION</td><td>ESTATUS</td></tr></table>");

	modal.append(titulo);
	var linea = "";
	$.each(Response[0], function(index, value)
	{
		var color = "";
		if(Date.parse(value['COTIZACIONES.FECHA']) < (Date.parse(Response[1]['date'])))
			color = "class='danger'";	
		
			linea = "<tr "+color+">";
			linea += "<td>"+value['COTIZACIONES.ID']+"</td>";
			linea += "<td>"+value['COTIZACIONES.FECHA']+"</td>";
			linea += "<td>"+value['COTIZACIONES.NOMBRECLIENTE']+"</td>";
			linea += "<td>"+value['OPERADOR.ALIAS']+"</td>";
			linea += "<td>"+value['COTIZACIONES.DESCRIPCION']+"</td>";
			linea += "<td>"+value['COTIZACIONESESTATUS.COTIZACIONDESCRIPCION']+"</td>";
			$("#table_registros").append(linea);
		
	});	
}

function RespuestaRequisicion(Response)
{
	$("#table_modal1").html("");
	console.log(Response);
	var modal = $("#table_modal1");
	var titulo = $("<table class='table table-striped table-bordered table-hover dataTable no-footer' id='table_registros'><tr><td>ID / FOLIO</td><td>DATOS</td><td>EMPLEADO</td><td>IMPORTE</td></tr></table>");

	modal.append(titulo);
	var linea = "";
	$.each(Response[0], function(index, value)
	{
		var color = "";
		if(Date.parse(value['REQUISICION.FECHA']) < (Date.parse(Response[1]['date'])))
			color = "class='danger'";	
		
			linea = "<tr "+color+">";
			linea += "<td>"+value['REQUISICION.ID']+"/"+value['REQUISICION.FOLIO']+" </td>";
			linea += "<td>Solicitud:"+value['REQUISICION.FECHA']+"<BR>Proveedor: "+value['REQUISICION.PROVEEDOR']+"<BR>MATERIAL:"+value['REQUISICION.MATERIAL']+" ("+value['REQUISICION.MATERIAL'] +" "+value['REQUISICION.UNIDADMEDIDA']+")</td>";
			linea += "<td>"+value['OPERADOR.ALIAS']+"</td>";
			linea += "<td>"+currency(value['REQUISICION.IMPORTE'],2, [",", '.'])+"</td>";
			$("#table_registros").append(linea);
		
	});	
}

function RespuestaFacturacion(Response)
{
	$("#table_modal1").html("");
	console.log(Response);
	var modal = $("#table_modal1");
	var titulo = $("<table class='table table-striped table-bordered table-hover dataTable no-footer' id='table_registros'><tr><td>FOLIO</td><td>DATOS</td><td>OPERADOR</td><td>IMPORTE</td></tr></table>");

	modal.append(titulo);
	var linea = "";
	$.each(Response, function(index, value)
	{
		var color = "";
		var subarticulos = "<br><b>Articulos:</b><br>";
		$.each(Response[index]['SUBARTICULOS'], function(index2, value2)
		{
			subarticulos += " * "+value2['ARTICULOS.NOMBRE']+" ("+currency(value2['DOCTOS_VE_DET.UNIDADES'],2, ["", '.'])+" UNIDADES)<br>"; 	
		});
		
		linea = "<tr>";
		linea += "<td>"+value['NOMBREEMPRESA']+parseInt(value['DOCTOS_VE.FOLIO'])+" </td>";
		linea += "<td>CLIENTE: "+value['CLIENTES.NOMBRE']+"<BR>Descripcion: "+value['DOCTOS_VE.DESCRIPCION']+subarticulos+"</td>";
		linea += "<td>"+value['DOCTOS_VE.USUARIO_ULT_MODIF']+"</td>";
		linea += "<td>"+currency((value['DOCTOS_VE.IMPORTE_NETO'] + value['DOCTOS_VE.TOTAL_IMPUESTOS']) ,2, [",", '.'])+"</td>";
		$("#table_registros").append(linea);
	
	});	
}

function RespuestaCxC(Response)
{
	$("#table_modal1").html("");
	console.log(Response);
	var modal = $("#table_modal1");
	var titulo = $("<table class='table table-striped table-bordered table-hover dataTable no-footer' id='table_registros'><tr><td>FOLIO_SISTEMA</td><td>FECHAS_IMPORTARTES_SISTEMA</td><td widht='300px'>DATOS</td><td>IMPORTES</td></tr></table>");

	modal.append(titulo);
	var linea = "";
	$.each(Response, function(index, value)
	{
		var color = "";			
		
		linea = "<tr>";
		var boton = "";
		if(value['FINALIZADO'] == 1)
			boton = "<button type='button' class='btn btn-strech btn-success'><i class='fa fa-check'></i></button>";	
		else if(value['FINALIZADO'] == 0)
			boton = "<button type='button' class='btn btn-strech btn-danger'><i class='fa fa-close'></i></button>";	

		linea += "<td>"+boton+value['EMPRESA']+parseInt(value['FOLIO'])+" </td>";
		linea += "<td><B>FACTURADO:</B>"+value['FECHA']+"<BR><B>VENCIMIENTO:</B> "+value['FECHA_VENCIMIENTO']+"<BR><B>DEPÓSITO:</B> "+value['FECHA_DEPOSITO']+" </td>";
		linea += "<td>CLIENTE: "+value['NOMBRE']+"<BR>Descripcion: "+value['DESCRIPCION']+"</td>";
		linea += "<td>IMPORTE:"+currency((value['IMPORTE']) ,2, [",", '.'])+"<BR>";
		linea += "ANTICIPO:"+currency((value['ANTICIPO']) ,2, [",", '.'])+"<BR>";
		linea += "FALTANTE:"+currency((value['DEPOSITO']) ,2, [",", '.'])+"</td>";
		$("#table_registros").append(linea);
	
	});	
}

function RespuestaFinalizados(Response)
{
	$("#table_modal1").html("");
	console.log(Response);
	var modal = $("#table_modal1");
	var titulo = $("<table class='table table-striped table-bordered table-hover dataTable no-footer' id='table_registros'><tr><td>FOLIO</td><td>CLIENTE</td><td>DESCRIPCION</td><td>IMPORTES</td></tr></table>");

	modal.append(titulo);
	var linea = "";
	$.each(Response, function(index, value)
	{
		linea = "<tr>";
		linea += "<tr><td>"+value['EMPRESA']+parseInt(value['FOLIO'])+" </td>";
		linea += "<td>"+value['NOMBRE']+"</td>";
		linea += "<td>"+value['DESCRIPCION']+"</td>";
		linea += "<td>"+currency((value['IMPORTE_NETO'] + value['TOTAL_IMPUESTOS']) ,2, [",", '.'])+"</td></tr>";
		$("#table_registros").append(linea);
	
	});	
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