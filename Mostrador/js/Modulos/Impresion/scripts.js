var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;
var id_aux = 0;
var btn_seleccionador;
	

function datagridPendientes(response)
{
	var datagrid = $("#PendientesImpresion");
	datagrid.find("tr").remove();
	var contador = 0;
	console.log(response);
	$.each(response, function(index, value)
	{
		
		var campos = "";
		var index = 0;
		var id;
		var orden_dia = "";
		id 			= value['IDPRODUCCION'];
		idtablero 	= value['IDTABLERO'];

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

		$.each(value['MATERIALES'], function(index2, value2)
        {
            texto_descripcion += "<br>- "+value2['NOMBRE']+" ("+parseFloat(value2['UNIDADES'],2)+")";
        });

		
		campos += "<td><font style='background-color:green;color:white'>"+value['FECHA']+"</font><br><font style='background-color:red;color:white'>"+value['F_ENTREGA']+"</font></td>";
		
		campos += "<td style='text-align:justify'><b style='color:blue'>"+value['NOMBRE_CLIENTE']+"</b></td>";

		
		campos += "<td>"+value['NOMBRE_OPERADOR']+"</td>";
		if(value['EMPRESA']!=3)
          linea = $("<tr data-fila='"+id+"' data-tablero='"+idtablero+"'  data-empresa='"+value['EMPRESA']+"' id='"+id+"' style='color:blue;font-weight:bold;background: #e1e1e1;'></tr>");
            else
		  linea = $("<tr data-fila='"+id+"' data-tablero='"+idtablero+"'  data-empresa='"+value['EMPRESA']+"' id='"+id+"' style='color:blue;font-weight:bold;background: bisque;'></tr>");
		
        
		var observacion = "<button type='button' class='btn btn-strech  btn-info' title='OBSERVACIONES' onClick='observaciones(this, "+value['EMPRESA']+")'>"+value['CONTADOR_MESSAGE']+"<i class='fa fa-comment'></i></button>";
		var finalizar = "<button type='button' class='btn btn-strech btn-success' title='FINALIZAR ACTIVIDAD' onClick='finalizar(this, "+value['EMPRESA']+")'><i class='fa fa-check'></i></button>";
		var cancelar = "<button type='button' class='btn btn-strech btn-danger' title='CANCELAR' onClick='cancelar(this, "+value['EMPRESA']+")'><i class='fa fa-close'></i></button>";
        
        campos += "<td>"+observacion+" "+finalizar+" "+cancelar+"</td>";
        
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
    texto+= "<div class='col-sm-8'><label class='control-label'>ARTICULO</label></div>";
    texto+= "<div class='col-sm-2'><label class='control-label'>UNIDADES</label></div>";
    //texto+= "<div class='col-lg-2'><label class='control-label'>UTILIZADOS</label></div>";
    texto+= "</div>";
    $.each(Response, function(index, value)
    {
        var estilo = "";
        if((index%2) == 1)
            estilo = " style='background:#EFEFEF'";

        texto += "<div class='row' "+estilo+">";
        texto+= "<div class='col-sm-8'><label class='control-label'>"+value['NOMBRE']+"</label></div>";
        texto+= "<div class='col-sm-2'><label class='control-label'>"+parseFloat(value['UNIDADES'])+"</label></div>";
        //texto+= "<div class='col-lg-2'><label class='control-label'>"+parseFloat(value['UTILIZADO'])+"</label></div>";
        texto+= "</div>";

        $("#articulosUtilizados").append(texto);
        texto = "";
    });
}

function eliminaInventario(id)
{
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
        var variable = "accion=deleteinventario&idinventario="+id;
        RestFullRequest("_Rest/PendientesPv.php", variable, "refresDatagridInventario");
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


function verObservacionesRechazo(id, emp)
{
	empresa = emp;
	var variable = "accion=vercancelacion&empresa="+empresa+"&id="+$(id).parents("tr").data("fila");
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaCancelacion");
}

function observaciones(obj, emp)
{
	empresa = emp;

    empresa = 3;
    $("#id").val($(obj).parents("tr").data("fila"));// Es aqui
    var variable = "accion=observaciones&id="+$(obj).parents("tr").data("fila")+"&departamento=3";
    RestFullRequest("_Rest/PendientesPv.php", variable, "cargaObservaciones");
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
    
    $("#finalizaTarea").modal("show");
    $("#finalizaTarea textArea").val("");
    $("#idtablerofinalizar").val($(obj).parents("tr").data("tablero"));
    $("#idfinalizar").val($(obj).parents("tr").data("tablero"));
    $("#idproduccion").val($(obj).parents("tr").data("tablero"));
    actualizadatagridinventario($(obj).parents("tr").data("fila"));
}

function cancelar(obj, emp)
{
    empresa = emp;
    $("#idtablerocancelar").val($(obj).parents("tr").data("tablero"));
    $("#CancelarTarea").modal("show");
}



function operadores()
{
	var variable = "accion=operadores&empresa="+empresa+"&"+$("#FormLevantamiento").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaOperadores");
}

function guardarObservacion()
{

    var variable = "accion=saveObservacion&"+$("#formObservaciones").serialize()+"&departamento=3";
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
    $("#observaciones").modal("hide");
}

function guardarTurnar()
{
    console.log($("#formFinalizar").serialize());
    var variable = "accion=saveTurnar&empresa="+2+"&"+$("#formFinalizar").serialize()+"&departamento=3";
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid", 1);
    $("#finalizaTarea").modal("hide");
}

function guardar()
{
    var variable = "accion=save&empresa="+2+"&"+$("#formFinalizar").serialize()+"&departamento=3";
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
    $("#finalizaTarea").modal("hide");
}

function verificarinventario(Response)
{
    
        actualizaDatagrid();
        $("#finalizaTarea").modal("hide");
    

}

function cancelaActividad()
{
    var variable = "accion=cancelar&"+$("#formCancelar").serialize()+"&departamento=3";
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
    $("#CancelarTarea").modal("hide");

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
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "datagridPendientes");
	//paginador();
}

$(document).ready(function(e) {

	actualizaDatagrid();

	//setInterval(actualizaDatagrid,  900000);
    $("#mostrador").find("a").click();
	operadores();
    inicializafiltro();
});


function btnagregaInventario()
{
    if($("#agregarsubArticulo").val() == 0 && $("#agregarsubArticulo option").size() > 1 )
    {
        alert("Debe de seleccionar el tipo de sub articulo, para continuar");
    }else
    {
        if($("#cantidad").val() != "" && $("#merma").val() != "" && $("#lineaArticulo").val!=0 && $("#agregarArticulo").val!=0)
        {
            
            var variable = "accion=saveInventario&"+$("#formInventario").serialize()+"&empresa="+2;
            RestFullRequest("_Rest/PendientesPv.php", variable, "refresDatagridInventario");

            $("#formInventario #cantidad, #merma, #motivo").val("");
            $("#formInventario #cantidad, #merma").val("0");
            $("#lineaArticulo").val(0);
            $("#lineaArticulo").change();
        }else
        {
            alert("Debe de ingresar en el articulo, la cantidad y la merma de producto");
        }
    }
   
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
