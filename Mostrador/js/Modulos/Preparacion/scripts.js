/**
 * Created by SALUD on 5/08/15.
 */
var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;


function datagridPendientes(response)
{
    var datagrid = $("#Pendientespreparacion");
    datagrid.find("tr").remove();
    var contador = 0;
    console.log(response);
    $.each(response, function(index, value)
    {
        
        var campos = "";
        var index = 0;
        var id;
        var orden_dia = "";
        id          = value['IDPRODUCCION'];
        idtablero   = value['IDTABLERO'];

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

function cambia_estatus(obj, empresa)
{

    btn_seleccionador = obj;
    $(obj).find("i").removeClass("fa-pause");
    $(obj).find("i").removeClass("fa-check-square-o");
    $(obj).find("i").addClass("fa-circle-o-notch fa-spin");

    var variable = "accion=activarActividad&id="+$(obj).parents("tr").data("fila")+"&EMPRESA="+empresa;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "ActivaActividad");
    
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
    console.log("--"+response);
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

function activaProceso(idProceso, emp, tipo)
{
    var variable = "accion=saveActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid");
}


function verpreparacion(idProceso, emp, tipo)
{

    $("#formpreparacion input").val("");
    $("#formpreparacion textarea").val("");
    $("#preparacionidproduccion").val(idProceso);
    $("#preparacionemp").val(emp);
    $("#preparaciondepartamento").val(tipo);
    var variable = "accion=preparacion&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "cargapreparacion");
}

function cargapreparacion(Response)
{
    $("#modalpreparacion").modal("show");

    if($(Response[0]).length > 0)
    {
        $("#colaboradores").val(Response[0]['PREPARACION.COLABORADORES']);
        $("#descripcionpreparacion").val(Response[0]['PREPARACION.DESCRIPCIONPREPARACION']);
        $("#preparacionid").val(Response[0]['PREPARACION.ID']);
    }
}


function guardaPreparacion()
{
    $("#modalpreparacion").modal("hide");
    var variable = "accion=savepreparacion&"+$("#formpreparacion").serialize();
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid", 1);
}

function eliminarPreparacion()
{
    $("#modalpreparacion").modal("hide");
    var variable = "accion=deletepreparacion&"+$("#formpreparacion").serialize();
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid", 2);

}


function buscarRealizados()
{
    var variable = "accion=index&empresa="+empresa+"&realizados="+$("#realizados").val()+"&fecha="+$("#fecha").val()+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();;;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "datagridPendientes");

}

function contadorMensajes(id, emp)
{
    empresa = emp;
    var variable = "accion=countMessaje&empresa="+empresa+"&id="+id;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "cargaCountMessaje");
}

function cargaCountMessaje(Response)
{
    if(Response[0].count > 0)
        $("#"+Response[0].ID).find("button:eq(2)").removeClass("btn-info").addClass("btn-primary");
    $("#"+Response[0].ID).find("button:eq(2)").append(Response[0].count);
}

function observaciones(obj, emp)
{
    empresa = emp;
    
    empresa = 3;
    $("#id").val($(obj).parents("tr").data("fila"));// Es aqui
    var variable = "accion=observaciones&id="+$(obj).parents("tr").data("fila")+"&departamento=9";
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
    $("#idtablerofinalizar").val($(obj).parents("tr").data("tablero"));
    $("#id").val($(obj).parents("tr").data("tablero"));
    $("#finalizaTarea").modal("show");
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
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "cargaOperadores");
}

function guardarObservacion()
{
    
    var variable = "accion=saveObservacion&"+$("#formObservaciones").serialize()+"&departamento=9";
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
    $("#observaciones").modal("hide");
}

function guardarTurnar()
{
        var variable = "accion=saveTurnar&empresa="+2+"&"+$("#formFinalizar").serialize()+"&departamento=9";
        RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid", 1);
        $("#finalizaTarea").modal("hide");
}


function guardar()
{
    var variable = "accion=save&empresa="+2+"&"+$("#formFinalizar").serialize()+"&departamento=9";
    RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
    $("#finalizaTarea").modal("hide");

}


function cancelaActividad()
{
    console.log("entra2");
    var variable = "accion=cancelar&"+$("#formCancelar").serialize()+"&departamento=9";
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
        $("#descripcionobservaciones").append("<tr><td> > "+value['DGOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['DGOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
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

    var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+$("#FormLevantamiento").serialize()+"&activas="+activas+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();;;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "datagridPendientes");
    //paginador();
}

$(document).ready(function(e) {

    actualizaDatagrid();
    $("#mostrador").find("a").click();
    operadores();
});
