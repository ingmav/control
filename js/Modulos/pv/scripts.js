var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;
var id_aux = 0;
var btn_seleccionador;
	
		
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
    console.log(variable);
    RestFullRequest("_Rest/PendientesPv.php", variable, "datagridPendientes");
	
}


function reporte()
{
    window.open("reportes/pv/ReportePV.php",'_blank');
}

function datagridPendientes(response)
{

	var datagrid = $("#PendientesPV");
	datagrid.find("tr").remove();
	var contador = 0;
    /*console.log("entra");
    console.log(response);
    console.log("sale");*/
	$.each(response, function(index, value)
	{
		
		var campos = "";
		var index = 0;
		var id;
		id = value['ID'];
		var labelPrioridad = "";
		var orden_dia = "";
		var pendientes = "";
        var folio = "";
        folio = value['FOLIO'].substr(1);
        folio = parseInt(folio);
        folio = "A"+folio;

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
        var campo_activacion = "<button class='btn  btn-"+btn+"' onclick='cambia_estatus(this)'><i class='fa fa-"+icono+"'></i> "+folio+"</button>";
		campos += "<td>"+campo_activacion+"</td>";
		campos += "<td>"+value['FECHA']+"</td>";
		campos += "<td>"+value['NOMBRE_CLIENTE'];
		$.each(value['MATERIALES'], function(index2, value2)
        {
            campos += "<br>"+value2['NOMBRE']+" ("+parseFloat(value2['UNIDADES'],2)+")";
        });
        campos += "</td>";
        //campos += value['DOCTO_PV_DET.MATERIALES']+"<BR>"+value['PRODUCCIONPV.DESCRIPCION']+"</td>";
		campos += "<td> $ "+(parseFloat(value['IMPORTE_NETO'],2) + parseFloat(value['TOTAL_IMPUESTOS'],2)).toFixed(2)+"</td>";
		campos += "<td>"+value['NOMBRE_OPERADOR']+"</td>";
        
        if(value['IDESTATUS'] == null || value['IDESTATUS']==1)
            campos += "<td><button type='button' class='btn btn-primary btn-strech' onclick='observacion(this)'><i class='fa fa-comment'></i>"+value['OBSERVACIONES']+" <button type='button' class='btn btn-success btn-strech' style='margin:0px 0px 0px 5px' onclick='finalizar(this)'><i class='fa fa-check'></i></button><button type='button' class='btn btn-danger btn-strech' style='margin:0px 5px' onclick='cancelacion(this)'><i class='fa fa-close'></i></button></td>";
        else if(value['IDESTATUS'] == "2")
            campos += "<td>FINALIZADO</td>";
        else if(value['IDESTATUS'] == "3")
            campos += "<td>CANCELADO</td>";

        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

	var prioridad;
		var cancelacion;

		linea.append(campos);
		
		datagrid.append(linea);
		contador++;
	});
	if(contador == 0)
		datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function cambia_estatus(obj)
{
    btn_seleccionador = obj;
    $(obj).find("i").removeClass("fa-pause");
    $(obj).find("i").removeClass("fa-check-square-o");
    $(obj).find("i").addClass("fa-circle-o-notch fa-spin");

    var variable = "accion=activarActividad&id="+$(obj).parents("tr").data("fila");
    RestFullRequest("_Rest/PendientesPv.php", variable, "ActivaActividad");
    
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
/*function activaProceso(idProceso, emp, tipo)
{
	var variable = "accion=saveActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");

    var variable = "accion=deleteActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
}

function activaPendiente(idProceso, emp, tipo)
{
	var variable = "accion=saveActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");

    var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
}

function desactivaProceso(idProceso, emp, tipo)
{
	var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
}

function desactivaPendiente(idProceso, emp, tipo)
{
	var variable = "accion=deleteActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
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
    RestFullRequest("_Rest/PendientesPv.php", variable, "cargaCountMessaje");
}

function cargaCountMessaje(Response)
{
	
	if(Response[0].count > 0)
		$("#"+Response[0].ID).find("button:eq(2)").removeClass("btn-info").addClass("btn-primary");	
	$("#"+Response[0].ID).find("button:eq(2)").append(Response[0].count);
}*/

function observacion(obj, emp)
{
    empresa = emp;
	$("#id").val($(obj).parents("tr").data("fila"));
	var variable = "accion=observaciones&id="+$(obj).parents("tr").data("fila");
	//console.log(variable);
    RestFullRequest("_Rest/PendientesPv.php", variable, "cargaObservaciones");
}

/*function turnarEmpleado(obj, emp)
{
	empresa = emp;
	$("#finalizaTarea textArea")
	$("#idtablerooperador").val($(obj).parents("tr").data("fila"));
	$("#finalizaTarea").modal("show");

}
*/
function finalizar(obj)
{
    $("#finalizaTarea").modal("show");
    $("#finalizaTarea textArea").val("");
    $("#idfinalizar").val($(obj).parents("tr").data("fila"));
    $("#idproduccion").val($(obj).parents("tr").data("fila"));
    actualizadatagridinventario($(obj).parents("tr").data("fila"));
    
}

function eliminaInventario(id)
{
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
        var variable = "accion=deleteinventario&idinventario="+id;
        RestFullRequest("_Rest/PendientesPv.php", variable, "refresDatagridInventario");
    }
}

function actualizadatagridinventario(id)
{
    id_aux = id;
    $("#registrosarticulos").html("");
    var variable = "accion=cargainventario&idpv="+id;
    RestFullRequest("_Rest/PendientesPv.php", variable, "cargadatagridinventario");
}

function cargadatagridinventario(Response)
{
    var variable = "accion=cargainventarioutilizado&idpv="+id_aux;
    RestFullRequest("_Rest/PendientesPv.php", variable, "cargadocumentoutilizado");

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
    //texto+= "<div class='col-lg-2'><label class='control-label'>UTILIZADOS</label></div>";
    texto+= "</div>";
    $.each(Response, function(index, value)
    {
        var estilo = "";
        if((index%2) == 1)
            estilo = " style='background:#EFEFEF'";

        texto += "<div class='row' "+estilo+">";
        texto+= "<div class='col-lg-8'><label class='control-label'>"+value['NOMBRE']+"</label></div>";
        texto+= "<div class='col-lg-2'><label class='control-label'>"+parseFloat(value['UNIDADES'])+"</label></div>";
        //texto+= "<div class='col-lg-2'><label class='control-label'>"+parseFloat(value['UTILIZADO'])+"</label></div>";
        texto+= "</div>";

        $("#articulosUtilizados").append(texto);
        texto = "";
    });
}

function cancelacion(obj)
{
   // $("#id").val($(obj).parents("tr").data("fila"))
    //$("#detallesCancelacion").html($(obj).parents("tr").html());
    $("#detallesCancelacion").html("");
    var linea1 = $("<tr><td>FOLIO</td><td>"+$(obj).parents("tr").find("td:eq(0)").html()+"</td></tr>");
    var linea2 = $("<tr><td>FECHA</td><td>"+$(obj).parents("tr").find("td:eq(1)").html()+"</td></tr>");
    var linea3 = $("<tr><td>CLIENTE</td><td>"+$(obj).parents("tr").find("td:eq(2)").html()+"</td></tr>");
    var linea4 = $("<tr><td>MATERIALES</td><td>"+$(obj).parents("tr").find("td:eq(3)").html()+"</td></tr>");
    var linea5 = $("<tr><td>MONTO</td><td>"+$(obj).parents("tr").find("td:eq(4)").html()+"</td></tr>");
    var linea6 = $("<tr><td colspan='2'><input type='hidden' name='id' value='"+$(obj).parents("tr").data("fila")+"'></td></tr>");


    $("#detallesCancelacion").append(linea1);
    $("#detallesCancelacion").append(linea2);
    $("#detallesCancelacion").append(linea3);
    $("#detallesCancelacion").append(linea4);
    $("#detallesCancelacion").append(linea5);
    $("#detallesCancelacion").append(linea6);
    $("#Cancelacion").modal("show");
}

function cancelar()
{
    var variable = "accion=cancelar&"+$("#formCancelacion").serialize();
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid", 1);
    $("#Cancelacion").modal("hide");
}

function operadores()
{
	var variable = "accion=operadores&empresa="+empresa+"&"+$("#formOperadores").serialize();
    RestFullRequest("_Rest/PendientesPv.php", variable, "cargaOperadores");
}

function guardarObservacion()
{
	var variable = "accion=saveObservacion&"+$("#formObservaciones").serialize();
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
    $("#observaciones").modal("hide");
	
}

function guardarTurnar()
{
	var variable = "accion=saveTurnar&empresa="+empresa+"&"+$("#formFinalizar").serialize();
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid", 1);
    $("#finalizaTarea").modal("hide");
	
}

function guardar()
{
	var variable = "accion=save&empresa="+empresa+"&"+$("#formFinalizar").serialize();
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
    $("#finalizaTarea").modal("hide");
	
}

function cargaObservaciones(Response)
{
	var contador = 0;
    console.log(Response);
	$("#descripcionobservaciones").find("tr").remove();
	$("#observaciones").modal("show");
	$.each(Response, function(index, value)
	{
		$("#descripcionobservaciones").append("<tr><td> > "+value['PVOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['PVOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
		contador++;
	});
	if(contador < 1)
		$("#descripcionobservaciones").append("<tr><td>NO EXISTEN OBSERVACIONES</td></tr>");	
	$("#descripcionobservaciones").append("<tr><td colspan='2'><textarea style='resize:none' class='form-control' name='observacion'></textarea></td></tr>");
	
}

function cargaOperadores(Response)
{
    //console.log(Response);
	$.each(Response, function(index, value)
	{
		$("#selectEmpleado").append('<option value='+value['OPERADORDEPARTAMENTO.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		$("#EmpleadoFinalizar").append('<option value='+value['OPERADORDEPARTAMENTO.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		
	});
}

function actualizaDatagrid()
{

	//var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+$("#FormLevantamiento").serialize()+"&activas="+activas+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    var variable = "accion=index";
    RestFullRequest("_Rest/PendientesPv.php", variable, "datagridPendientes");
	//paginador();
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

function btnagregaInventario()
{
    if($("#agregarsubArticulo").val() == 0 && $("#agregarsubArticulo option").size() > 1 )
    {
        alert("Debe de seleccionar el tipo de sub articulo, para continuar");
    }else
    {
        if($("#cantidad").val() != "" && $("#merma").val() != "" && $("#lineaArticulo").val!=0 && $("#agregarArticulo").val!=0)
        {
            var variable = "accion=saveInventario&"+$("#formInventario").serialize()+"&empresa="+empresa;
            RestFullRequest("_Rest/PendientesPv.php", variable, "refresDatagridInventario");

            $("#formInventario #cantidad, #merma, #motivo").val("");
            $("#lineaArticulo").val(0);
            $("#lineaArticulo").change();
        }else
        {
            alert("Debe de ingresar en el articulo, la cantidad y la merma de producto");
        }
    }
}

function refresDatagridInventario()
{
    actualizadatagridinventario($("#idproduccion").val());
}

$(document).ready(function(e) {

	actualizaDatagrid();
	//setInterval(actualizaDatagrid,  900000);
	operadores();
    inicializafiltro();
});