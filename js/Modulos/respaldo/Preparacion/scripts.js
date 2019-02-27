/**
 * Created by SALUD on 5/08/15.
 */
var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;


function datagridPendientes(response)
{

    actualizaProcesos();
    //paginador();
    var datagrid = $("#Pendientespreparacion");
    datagrid.find("tr").remove();
    var contador = 0;

    console.log(response);
    $.each(response, function(index, value)
    {

        var campos = "";
        var index = 0;
        var id;
        id = value['TABLEROPRODUCCION.ID'];

        var labelPrioridad = "";
        if(value['TABLEROPRODUCCION.PRIORIDAD'] == 1)
            labelPrioridad = "<label class='label label-warning'>PRIORIDAD</label>";

        var labelCancelacion = "";
        if(value['PRODUCCION.IDESTATUS'] == 3)
            labelCancelacion = "<label class='label label-danger'>"+value['PRODUCCION.DESCRIPCIONCANCELACION']+"</label>";

        if($("#realizados").val() == 1)
        {
            if(value['ORDENDIA.FECHAORDEN'] !="")
                var orden_dia = "<button type='button' class='btn btn-strech btn-warning' onClick='desactivaProceso("+id+", "+value['EMPRESA']+", 9)'><i class='fa fa-buysellads'></i></button>";
            else
                var orden_dia = "<button type='button' class='btn btn-strech' onClick='activaProceso("+id+", "+value['EMPRESA']+", 9)'><i class='fa fa-buysellads'></i></button>";

        }

        var preparacion = "";

        if(value['PREPARACION.COLABORADORES'] != "")
            preparacion += "<BR><div><b>PREPARADOR:</b> "+value['PREPARACION.COLABORADORES']+"</div>";
        if(value['PREPARACION.DESCRIPCIONPREPARACION'] != "")
            preparacion += "<BR><div><b>DESCRIPCIÓN:</b> "+value['PREPARACION.DESCRIPCIONPREPARACION']+"</div>";
        texto_descripcion = "";

        if(value['DOCTOS_VE.DESCRIPCION'] != value['TABLEROPRODUCCION.NOTA'])
            texto_descripcion += "<br><u>"+value['DOCTOS_VE.DESCRIPCION']+"</u><BR>"+value['TABLEROPRODUCCION.NOTA'];
        else
            texto_descripcion += "<br><u>"+value['DOCTOS_VE.DESCRIPCION']+"</u>";

        campos += "<td>"+value['NOMBREEMPRESA']+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.ESTATUS']+"<br>"+orden_dia+"</td>";
        campos += "<td>"+value['TABLEROPRODUCCION.FECHA']+"</td>";
        campos += "<td style='text-align:justify'><b style='color:blue'>"+value['CLIENTES.NOMBRE']+"</b><b> ("+parseFloat(value['DOCTOS_VE_DET.UNIDADES']).toFixed(2)+" "+value['ARTICULOS.UNIDAD_VENTA']+")</b>"+texto_descripcion+"<br>"+preparacion+""+labelCancelacion+"</td>";


        //campos += "<td>"+value['NOMBREEMPRESA']+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.ESTATUS']+"<br>"+orden_dia+" "+"</td>";
        //campos += "<td style='width:140px'>"+value['TABLEROPRODUCCION.FECHA']+"</td>";
        //campos += "<td>"+value['CLIENTES.NOMBRE']+"</td>";
        //campos += "<td>"+parseFloat(value['DOCTOS_VE_DET.UNIDADES']).toFixed(2)+" "+value['ARTICULOS.UNIDAD_VENTA']+"</td>";
        //campos += "<td>"+value['DOCTOS_VE.DESCRIPCION']+"<BR>"+value['TABLEROPRODUCCION.NOTA']+"<br>"+preparacion+"</div>"+labelCancelacion+"</td>";
        campos += "<td>"+value['OPERADOR.ALIAS']+"</td>";

        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
        var prioridad;

        contadorMensajes(id, value['EMPRESA']);

        var colaboradores = "<button type='button' class='btn btn-strech btn-default' title='INSTALACIÓN' onClick='verpreparacion("+id+", "+value['EMPRESA']+", 9);'><i class='fa fa-user-plus'></i></button>";
        var observacion = "<button type='button' class='btn btn-strech  btn-info' title='OBSERVACIONES' onClick='observaciones(this, "+value['EMPRESA']+")'><i class='fa fa-comment'></i></button>";
        var finalizar = "<button type='button' class='btn btn-strech  btn-success' title='FINALIZAR ACTIVIDAD' onClick='finalizar(this, "+value['EMPRESA']+")'><i class='fa fa-check'></i></button>";
        //var cancelar = "<button type='button' class='btn btn-strech  btn-danger' title='CANCELAR' onClick='cancelar(this, "+value['EMPRESA']+")'><i class='fa fa-close'></i></button>";

        if($("#realizados").val() == 1)
            campos += "<td>"+colaboradores+observacion+" "+finalizar+"</td>";
        else
            campos += "<td><label class='label label-success'>REALIZADO</label></td>";
        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
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
    /*if($(Response).length > 0){
        $("#preparacionid").val(Response[0]['preparacion.ID']);
        $("#colaboradorespreparacion").val(Response[0]['preparacion.COLABORADORESpreparacion']);
    }
    else
    {
        $("#preparacionid").val("");
        $("#colaboradorespreparacion").val("");
    }*/

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

function desactivaProceso(idProceso, emp, tipo)
{
    var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid");
}

function desactivaPendiente(idProceso, emp, tipo)
{
    var variable = "accion=deleteActividadPendiente&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid");
}

function Activas()
{
    if(activas == 1)
        activas = 2;
    else
        activas = 1;

    actualizaDatagrid();
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
    $("#idtablero").val($(obj).parents("tr").data("fila"));
    var variable = "accion=observaciones&empresa="+empresa+"&id="+$(obj).parents("tr").data("fila");

    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "cargaObservaciones");
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
    $("#finalizaTarea").modal("show");
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
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "cargaOperadores");
}

function guardarObservacion()
{
    var variable = "accion=saveObservacion&empresa="+empresa+"&"+$("#formObservaciones").serialize();
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid", 1);
    $("#observaciones").modal("hide");

}

function guardarTurnar()
{
    var variable = "accion=saveTurnar&empresa="+empresa+"&"+$("#formFinalizar").serialize();
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid");
    $("#finalizaTarea").modal("hide");

}

function guardar()
{
    var variable = "accion=save&empresa="+empresa+"&"+$("#formFinalizar").serialize();
    console.log(variable);
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid", 1);
    $("#finalizaTarea").modal("hide");

}

function cancelaActividad()
{
    var variable = "accion=cancelar&empresa="+empresa+"&"+$("#formCancelar").serialize();
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "actualizaDatagrid");
    $("#CancelarTarea").modal("hide");
}

function cargaObservaciones(Response)
{
    var contador = 0;
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

    var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+$("#FormLevantamiento").serialize()+"&activas="+activas+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();;;
    RestFullRequest("_Rest/PendientesPreparacion.php", variable, "datagridPendientes");
    //paginador();
}

$(document).ready(function(e) {

    actualizaDatagrid();
    $("#operacion").find("a").click();
    operadores();
});
