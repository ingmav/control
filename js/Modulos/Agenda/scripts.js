var id_general = 1;
var identificador = 1;
var lista_operadores = [];
var obj_general;
var obj_borrador;

function color()
{

    var color1 = color_claro();
    var color2 = color_claro();
    var color3 = color_claro();

    return "background-color:rgba("+color1+","+color2+","+color3+",0.3)";
}


function color_claro()
{
    var color;
    do
    {
        color = (Math.random() * 100);
    }while(Math.floor(color) < 66);
    color = (color/100) * 256;
    return Math.floor(color);
}

function actualizaDatagrid()
{

    $("#contenedor_general > div").not(".encabezado_agenda").remove();

    var variable = "accion=index&dia="+$("#dia").val()+"&fechaHasta="+$("#fechafin").val()+"&departamento="+$("#departamento").val();
    RestFullRequest("_Rest/Agenda.php", variable, "datagridAgenda");
}

function datagridAgendaIniciadas(response)
{
    /*console.log(response);*/
    var div_gen = $("#contenedor_general > div:first").next();
    var estatus = "";
    var id = 0;
    //console.log(div_gen.html());
    $.each(response.TAREAS, function(index, value)
    {
        btn_activar = "";
        if(value['CLIENTES.NOMBRE'])
        {
            var cliente = value['CLIENTES.NOMBRE'];
            var descripcion = value['DOCTOS_VE.DESCRIPCION'];
            var entrega = value['TABLEROPRODUCCION.FECHA_ENTREGA'];
            var folio = value['DOCTOS_VE.FOLIO'];
            var empresa = response['IDEMPRESA'];
            var docto_ve_id = value['DOCTOS_VE.DOCTO_VE_ID'];
            var docto_ve_det_id = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];
            var colour = "background-color:rgba(244, 203, 233, 0.298039)";
            //estatus = "<div class='estatus estatus_no_realizados'></div>";
        }
        var time;

        if(value['AGENDA.OPERADOR'])
        {
            var cliente = value['AGENDA.CLIENTE'];
            var descripcion = value['AGENDA.DESCRIPCION'];
            var entrega = value['AGENDA.ENTREGA'];
            var folio = value['AGENDA.FOLIO'];
            var empresa = value['AGENDA.EMPRESA'];
            var docto_ve_id = value['AGENDA.DOCTO_VE_ID'];
            var docto_ve_det_id = value['AGENDA.DOCTO_VE_DET_ID'];

            var hrs = (parseInt(value['AGENDA.HR']) * 2);
            var hr =  parseInt(value['AGENDA.HR']);
            var min = (parseFloat(value['AGENDA.MINUTO']) / 30);
            var minutos = value['AGENDA.MINUTO'];

            var hr1 =  parseInt(value['AGENDA.HR1']);
            var minutos1 = value['AGENDA.MINUTO1'];
            var observacion = "'"+value['AGENDA.OBSERVACION']+"'";
            var estatus = value['AGENDA.ESTATUS'];

            //var colour = value['AGENDA.COLOR'];
            //var colour = color(value['AGENDA.ESTATUS']);
            if(value['AGENDA.ESTATUS'] == 1)
                var colour = "background-color:rgba(244, 203, 233, 0.298039)";
            else if(value['AGENDA.ESTATUS'] == 2)
                var colour = "background-color:rgba(235, 89, 0, 0.298039)";
            else if(value['AGENDA.ESTATUS'] == 3)
                var colour = "background-color:rgba(255, 206, 8, 0.298039)";
            else if(value['AGENDA.ESTATUS'] == 4)
                var colour = "background-color:rgba(50, 82, 240, 0.298039)";
            else if(value['AGENDA.ESTATUS'] == 5)
                var colour = "background-color:rgba(27, 158, 42, 0.298039)";
            id = value['AGENDA.ID'];

            time = (( hrs + min) * 4.54);
            general = value['AGENDA.ARREGLO'];
            id_general = general;

            /*if(value['AGENDA.REALIZADO'] == 1)
            {
                estatus = "<div class='estatus estatus_no_realizados'></div>";
                if(docto_ve_det_id == 0)
                    btn_activar = "<button type='button' class='btn btn-success btn-strech' onclick='valida_actividad(this)'><i class='fa fa-check'></i></button>";
            }else
            {
                estatus = "<div class='estatus estatus_realizados'></div>";
            }*/

        }else
        {
            var hr = 2;
            var min = 0;
            time = 18.16;

            general = id_general++;
        }

        if(docto_ve_id > 0)
            var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_factura(this)'><i class='fa fa-edit'></i></button>";
        else
        {
            var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_extra(this)'><i class='fa fa-edit'></i></button>";
        }

        var texto = "<div class='agendado'>("+folio+") Cliente "+cliente+"<br><div class='texto_tiempo'></div>Descripción "+descripcion+"<br>Entrega "+entrega+"</div>";

        var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
        var div_funciones = "<div class='funciones'>"+btn_activar+btn_editar+btn_eliminar+"</div>";

        var div_contenedor = "<div style='overflow:hidden; width: "+time+"%; position: relative; "+colour+"' data-id='"+id+"' id='"+id+"' class='agenda_usuario' data-access='no' data-cliente='"+cliente+"' data-entrega='"+entrega+"' data-folio="+folio+" data-id_general="+general+" data-desc='"+descripcion+"'  data-identificador="+identificador+"  data-docto_ve_id="+docto_ve_id+" data-docto_ve_det_id="+docto_ve_det_id+"  data-empresa="+empresa+" data-operador='"+$(this)+"' data-id='"+identificador+"' data-hour="+hr+"  data-min="+minutos+" data-hour1="+hr1+"  data-min1="+minutos1+" data-obs="+observacion+"  data-estatus="+estatus+">"+texto+div_funciones+"</div>";

        if(value['AGENDA.OPERADOR'])
        {
            $("#contenedor_general div[data-fila="+value['AGENDA.OPERADOR']+"] .contenedor1").append(div_contenedor);
        }else
        {
            $(div_gen).find(".contenedor1").append(div_contenedor);
        }
        identificador++;


        //contador++;
    });
    $(".contenedor1").droppable({
        drop : function( event, ui ) {
            setTimeout(calcula_tiempo_objetos, 1000);
        }
    }).sortable({
        revert: true,
        connectWith: ".contenedor1"
    });

    calcula_tiempo_objetos();
}

function valida_actividad(Obj)
{
    if(confirm("¿Realmente Deseas Validar esta actividad?"))
    {
        var id = $(Obj).parents(".agenda_usuario").data("id");

        var variable = "accion=validaActividad&fecha="+$("#dia").val()+"&departamento="+$("#departamento").val()+"&id="+id;
        RestFullRequest("_Rest/Agenda.php", variable, "cambiaColor", 1);
    }
}

function cambiaColor(Response)
{
    if(Response.resultado == 1)
    {
        $("#"+Response.id).find(".estatus").removeClass("estatus_no_realizados").addClass("estatus_realizados");
    }
}

function calcula_tiempo_objetos()
{

    $("#contenedor_general > div").not(":first").each(function()
    {
        $(this).find(".tiempo").each(function()
        {
            var horas_1 = 9;
            var minutos_1 = 0;
            $(this).find(".agenda_usuario").each(function()
            {
                var horas_2 = horas_1 + parseInt($(this).data("hour"));
                var minutos_2 = parseInt($(this).data("min"));

                var minutos_totales = (minutos_1 + minutos_2);
                if(minutos_totales >= 60)
                {
                    horas_entero = parseInt(minutos_totales / 60);
                    horas_2 += horas_entero;
                    minutos_2 = minutos_totales - 60;
                }else
                {
                    minutos_2 =  minutos_totales;
                }

                $(this).find(".texto_tiempo").html("Tiempo:"+$(this).data("hour")+"hrs"+" "+$(this).data("min")+" min de "+horas_1+":"+minutos_1+ " - "+horas_2+":"+minutos_2);
                horas_1 = horas_2;
                minutos_1 = minutos_2;

            });

        });
    });
}

function datagridAgenda(response)
{
    var contador = 0;
    var datagrid = $("#contenidoAgenda #contenedor_general");
    var dataoperadores = $("#formTarea #addoperador");
    var dataoperadores2 = $("#formTareaFactura #addoperador");


    dataoperadores.html("");
    dataoperadores2.html("");
    lista_operadores = [];
    $.each(response.OPERADORES, function(index, value)
    {
        
        var campos = "";
        var lista_operador;
        linea = $("<div data-fila='"+value['OPERADORDEPARTAMENTO.ID']+"'></div>");
        campos+="<div><div class='titulo'><div style='width: 100%'>"+value['OPERADOR.NOMBRE']+"</div></div><div class='tiempo contenedor1'></div></div>";
        linea.append(campos);
        datagrid.append(linea);
        var linea_operador = {};
        linea_operador['id'] = value['OPERADORDEPARTAMENTO.ID'];
        linea_operador['operador'] = value['OPERADOR.NOMBRE'];
        lista_operadores.push(linea_operador);
    
        //dataoperadores.append("<option value='"+value['OPERADORDEPARTAMENTO.ID']+"'>"+value['OPERADOR.NOMBRE']+"</option>");
        //dataoperadores2.append("<option value='"+value['OPERADORDEPARTAMENTO.ID']+"'>"+value['OPERADOR.NOMBRE']+"</option>");
    });

    var datagrid2 = $(".lista_tareas");
    datagrid2.html("");

    $.each(response.TAREAS, function(index, value)
    {
        if(value['EMPRESA'] != 3)
        {
            var linea = "";
            var id = value['DOCTOS_VE.DOCTO_VE_ID'];
            var id_det = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];
            var empresa = value['EMPRESA'];
            var folio = value['DOCTOS_VE.FOLIO'];
            var cliente = value['CLIENTES.NOMBRE'];
            var fecha = value['TABLEROPRODUCCION.FECHA_ENTREGA'];
            var desc = value['DOCTOS_VE.DESCRIPCION'];
            linea += "<div class='tarea tarea_corporativo' data-folio='"+folio+"' data-id='"+id+"'  data-id_det='"+id_det+"' data-empresa='"+empresa+"'><b>FOLIO</b> "+folio+"<br><b>CLIENTE</b> "+cliente+"<br><b>ENTREGA</b> "+fecha+"<br><b>DESCRIPCIÓN</b> "+desc+"<div class='agendar'><button type='button' class='btn btn-primary' onclick='ver_factura(this)' ><i class='fa fa-calendar'></i></button></div></div>";
        }else
        {
             var linea = "";
            var id = value['DOCTOS_PV.DOCTO_PV_ID'];
            var id_det = value['DOCTOS_PV_DET.DOCTO_PV_DET_ID'];
            var empresa = value['EMPRESA'];
            var folio = value['DOCTOS_PV.FOLIO'];
            var cliente = value['CLIENTES.NOMBRE'];
            var fecha = value['PRODUCCIONPV.F_ENTREGA'];
            var desc = value['DOCTOS_PV.DESCRIPCION'];
            linea += "<div class='tarea tarea_mostrador' data-folio='"+folio+"' data-id='"+id+"'  data-id_det='"+id_det+"' data-empresa='"+empresa+"'><b>FOLIO</b> "+folio+"<br><b>CLIENTE</b> "+cliente+"<br><b>ENTREGA</b> "+fecha+"<br><b>DESCRIPCIÓN</b> "+desc+"<div class='agendar'><button type='button' class='btn btn-primary' onclick='ver_factura(this)' ><i class='fa fa-calendar'></i></button></div></div>";
            
        }      

        datagrid2.append(linea);
    });

    $(".tarea").draggable({revert:"invalid", helper: "clone"});

    $(".contenedor1").droppable({
        drop : function( event, ui ) {
            setTimeout(calcula_tiempo_objetos, 2000);
        }
    }).sortable({
        revert: true,
        connectWith: ".contenedor1"
        });
   
   var datagrid3 = $(".lista_borrador");
    datagrid3.html("");
    linea = "<div class='row' style='background-color:#428bca; color:#FFF; margin-left: 0px;width: 100%;'><div class='col-sm-1'><b>FOLIO</b></div><div class='col-sm-2'><b>ENTREGA</b></div><div class='col-sm-4'><b>CLIENTE</b></div><div class='col-sm-3'><b>DESCRIPCIÓN</b></div><div class='col-sm-1'></div></div>";
    datagrid3.append(linea);    
    $.each(response.BORRADOR, function(index, value)
    {
        var color = "";
        if((index%2) == 0)
            color = "background-color: #cfcfcf;";
        var linea = "";
        var registro = value['ID'];
        var id = value['DOCTO_VE_ID'];
        var id_det = value['DOCTO_VE_DET_ID'];
        var empresa = value['EMPRESA'];
        var folio = value['FOLIO'];
        var cliente = value['CLIENTE'];
        var fecha = value['ENTREGA'];
        
        var desc = value['DESCRIPCION'];
        linea += "<div class='row' style='margin-left: 0px;width: 100%; padding:5px 0px;"+color+"' data-registro='"+registro+"' data-folio='"+folio+"' data-id='"+id+"'  data-id_det='"+id_det+"' data-empresa='"+empresa+"'><div class='col-sm-1'>"+folio+"</div><div class='col-sm-2'>"+fecha+"</div><div class='col-sm-4'>"+cliente+"</div><div class='col-sm-3'>"+desc+"</div><div class='col-sm-1'><button class='btn btn-default' type='button' onclick='regresar_agenda(this)'><i class='fa fa-calendar'></i></button></div></div>";
        
        datagrid3.append(linea);
    });

    if(response.BORRADOR.length == 0)
    {
        datagrid3.append("<div class='row' style='background-color: #CFCFCF;width: 100%;margin-left: 0px;padding: 5px;'><div class='col-sm-12'>No hay Registros</div></div>");
    }

    var variable = "accion=iniciadas&dia="+$("#dia").val()+"&fechaHasta="+$("#fechafin").val()+"&departamento="+$("#departamento").val();
    RestFullRequest("_Rest/Agenda.php", variable, "datagridAgendaIniciadas");
}

function regresar_agenda(obj)
{
    $(".btn_guardar_borrador").hide();
    $(".btn_guardar_principal").show();
    var div = $(obj).parent().parent();
    obj_borrador = div;
    
    if(parseInt(div.data('empresa')) == 0)
    {
        $("#formTarea").find("input").val("");
        $("#formTarea").find("textarea").val("");
        $("#formTarea #registro").val(div.data('registro'));
        AgregaOperadores($("#formTarea #addoperador"), 0);
        $("#AddTask").modal("show");
        var empresa = div.data("empresa");
        var registro = div.data("registro");

        var variable = "accion=show_tarea&registro="+registro;
        RestFullRequest("_Rest/Agenda.php", variable, "rellenaDatosExtra");

        
    }else
    {
        $("#formTareaFactura input").val("");
        $("#formTareaFactura text").val("");
        $("#formTareaFactura #registro").val(div.data('registro'));
        var empresa = div.data("empresa");
        var id = div.data("id");
        var id_det = div.data("id_det");

        AgregaOperadores($("#formTareaFactura #addoperador"),0);

        var variable = "accion=show&empresa="+empresa+"&id="+id+"&id_det="+id_det+"&departamento="+$("#departamento").val();
        RestFullRequest("_Rest/Agenda.php", variable, "rellenaDatosFactura");
        $("#AddFactura").modal("show");
    }
    //console.log(div.data('registro'));
}

$(document).ready(function()
{
    $("#operacion").find("a").click();
    actualizaDatagrid();
});


function rellenaDatosExtra(response)
{
    $("#formTarea #folio").val(response[0].FOLIO);
    $("#formTarea #descripcion").val(response[0].DESCRIPCION);
    $("#formTarea #observacion").val(response[0].OBSERVACION);

}

function AgregarTarea()
{
    $("#AddTask").modal();
    $("#formTarea").find("input").val("");
    $("#formTarea").find("textarea").val("");
    AgregaOperadores($("#formTarea #addoperador"), 0);

    $(".btn_guardar_principal").show();
    $(".btn_guardar_borrador").hide();
};


function AgregaOperadores(Obj, Arreglo)
{
   console.log(lista_operadores);

    $(Obj).find("option").remove();

    $.each(lista_operadores, function(index, value)
    {
        var bandera = 0;
        $("#contenedor_general > div[data-fila="+value['id']+"] .contenedor1 div").each(function()
        {
            //console.log($(this).data("id_general"));
            if($(this).data("id_general") == Arreglo)
            {
                bandera++;
            }
        });

        if(bandera == 0)
            $(Obj).append("<option value="+value['id']+">"+value['operador']+"</option>");
    });
}

function guardarTarea()
{

   

  if($('#folio').val()!="" && $('#folio').val()!="0")
  {
    if($("#formTarea #hr").val()!="" && $("#formTarea #min").val()!="" && $("#formTarea #addoperador").val()!=0)
    {
         if(parseInt($("#formTarea #registro").val()) > 0)
        {
            var variable = "accion=eliminar_borrador&registro="+$("#formTarea #registro").val();
            RestFullRequest("_Rest/Agenda.php", variable, "actualiza_lista_borrador_delete");
        }

        var folio = $("#formTarea #folio").val();
        var operador = $("#formTarea #addoperador").val();
        var hrs = (parseInt($("#formTarea #hr").val()) * 2);
        var min = (parseInt($("#formTarea #min").val()) / 30);
        var minutos = $("#formTarea #min").val();
        var observacion = $("#formTarea #observacion").val();
        var estado_tarea = $("#formTarea #estatus").val();
        var time = ((hrs + min) * 4.54);
        var general = 0;
        var contador = 0;

        if($("#formTarea #id_general").val() > 0)
        {
            general = $("#formTarea #id_general").val();
            var backg = "background-color:"+ $("#formTarea").find("#color").val();
        }else
        {
            general = id_general++;
            var backg = color();
        }


        if(!$.isEmptyObject(operador))
        $.each(operador, function(index, value)
        {
            $("#contenedor_general > div").each(function()
            {
                if($(this).data("fila") == value)
                 {
                    var estatus = "<div class='estatus'></div>";
                    var hour = $("#formTarea #hr").val();
                    var min = $("#formTarea #min").val();
                    var descripcion = $("#formTarea #descripcion").val();
                    var desc = "<div class='agendado'>("+folio+") "+$("#formTarea #descripcion").val()+"</div><div class='texto_tiempo'></div>";
                    var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_extra(this)'><i class='fa fa-edit'></i></button>";
                    var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
                    var div_funciones = "<div class='funciones'>"+btn_editar+btn_eliminar+"</div>";
                    var div_contenedor = "<div style='overflow:hidden; width: "+time+"%; position: relative;"+backg+"'  class='agenda_usuario' data-access='no' class='ui-draggable ui-draggable-handle' data-operador='"+$(this)+"' data-identificador='"+identificador+"' data-folio='"+folio+"' data-id_general='"+general+"' data-desc='"+descripcion+"' data-hour="+hour+"  data-min="+minutos+" data-hour2="+hour+"  data-min2="+minutos+" data-obs='"+observacion+"' data-estatus="+estado_tarea+">"+desc+div_funciones+estatus+"</div>";
                    $(this).find(".contenedor1").append(div_contenedor);
                    identificador++;
                 }
            });
            contador++;
        });
        if(contador == 0)
        {
            EditarTarea();
        }else
        {
            $("#AddTask").modal("hide");
            calcula_tiempo_objetos();
        }
    }else
    {
        alert("Debe de ingresar el tiempo y el operador");
    }
  }else {
    alert("Debe de ingresar un folio, por que si no se va a perder tus datos guardados");
  }
}

function guardarTareaBorrador()
{
      var valores = $("#formTareaFactura").serialize();
      var departamento = $("#departamento").val();
      var dia = $("#dia").val();
      
      var variable = "accion=guardaBorrador&departamento="+departamento+"&fecha="+dia+"&"+valores;
      if(parseInt($("#formTareaFactura #idOperador").val()) > 0)
      {
        
          RestFullRequest("_Rest/Agenda.php", variable, "actualiza_lista");
      }
      else
      {
          RestFullRequest("_Rest/Agenda.php", variable, "elimina_lista");
      }

}

function actualiza_borrador()
{
    var departamento = $("#departamento").val();
    var dia = $("#dia").val();
    var variable = "accion=lista_borrador&departamento="+departamento+"&fecha="+dia;
    RestFullRequest("_Rest/Agenda.php", variable, "actualiza_lista_borrador");
}

function actualiza_lista_borrador(response)
{
    var datagrid3 = $(".lista_borrador");
    datagrid3.html("");
    linea = "<div class='row' style='background-color:#428bca; color:#FFF; margin-left: 0px;width: 100%;'><div class='col-sm-1'><b>FOLIO</b></div><div class='col-sm-2'><b>ENTREGA</b></div><div class='col-sm-4'><b>CLIENTE</b></div><div class='col-sm-3'><b>DESCRIPCIÓN</b></div><div class='col-sm-1'></div></div>";
    datagrid3.append(linea);    
    $.each(response, function(index, value)
    {
        
        var linea = "";
        var color = "";
        if((index%2) == 0)
            color = "background-color: #cfcfcf;";

        var registro = value['ID'];
        var id = value['DOCTO_VE_ID'];
        var id_det = value['DOCTO_VE_DET_ID'];
        var empresa = value['EMPRESA'];
        var folio = value['FOLIO'];
        var cliente = value['CLIENTE'];
        var fecha = value['ENTREGA'];
        
        var desc = value['DESCRIPCION'];
        linea += "<div class='row' style='margin-left: 0px;width: 100%; padding:5px 0px;"+color+"' data-registro='"+registro+"' data-folio='"+folio+"' data-id='"+id+"'  data-id_det='"+id_det+"' data-empresa='"+empresa+"'><div class='col-sm-1'>"+folio+"</div><div class='col-sm-2'>"+fecha+"</div><div class='col-sm-4'>"+cliente+"</div><div class='col-sm-3'>"+desc+"</div><div class='col-sm-1'><button class='btn btn-default' type='button' onclick='regresar_agenda(this)'><i class='fa fa-calendar'></i></button></div></div>";
        
        //linea += "<div class='row' style='margin-left: 0px;width: 100%; padding:5px 0px;' data-registro='"++"' data-folio='"+folio+"' data-id='"+id+"'  data-id_det='"+id_det+"' data-empresa='"+empresa+"'><div class='col-sm-1'>"+folio+"</div><div class='col-sm-2'>"+fecha+"</div><div class='col-sm-4'>"+cliente+"</div><div class='col-sm-3'>"+desc+"</div><div class='col-sm-1'><button class='btn btn-default' type='button' onclick='regresar_agenda(this)'><i class='fa fa-calendar'></i></button></div></div>";
        
        datagrid3.append(linea);
    });

    if(response.length == 0)
    {
        datagrid3.append("<div class='row' style='background-color: #CFCFCF;width: 100%;margin-left: 0px;padding: 5px;'><div class='col-sm-12'>No hay Registros</div></div>");
    }
}

function guardarTareaBorradorExtra()
{
      var valores = $("#formTarea").serialize();
      var departamento = $("#departamento").val();
      var dia = $("#dia").val();
      var variable = "accion=guardaBorradorExtra&departamento="+departamento+"&fecha="+dia+"&"+valores;
      RestFullRequest("_Rest/Agenda.php", variable, "actualiza_lista",1);

}

function elimina_lista(response)
{
    $(".lista_tareas div[data-id='"+response.docto_ve_id+"'][data-id_det='"+response.docto_ve_det_id+"'][data-empresa='"+response.empresa+"']").remove();
    actualiza_lista_tareas();
}

function actualiza_lista()
{
    actualiza_borrador();
    $("#AddTask").modal("hide");
    $("#AddFactura").modal("hide");
    $(obj_general).remove();
}

function actualiza_lista_tareas()
{
    actualiza_borrador();
    $("#AddTask").modal("hide");
    $("#AddFactura").modal("hide");
}

function eliminar_extra(obj)
{
    if(confirm("¿Realmente desea eliminar la tarea?"))
    {
        var div = $(obj).parent().parent();
        //
        if(div.data("empresa"))
        {
            var empresa = div.data("empresa");

            var id = div.data("docto_ve_id");
            var id_det = div.data("docto_ve_det_id");

            if(busca_id(div.data('id_general')) > 1)
            {
                div.remove();
            }else
            {
                var variable = "accion=show&empresa="+empresa+"&id="+id+"&id_det="+id_det+"&departamento="+$("#departamento").val();
                RestFullRequest("_Rest/Agenda.php", variable, "devolverFactura");
                div.remove();
            }


        }else
        {
            div.remove();
        }
    }
}

function devolverFactura(Response)
{
    var lista = $(".lista_tareas");
    console.log(Response);
    $.each(Response.TAREAS, function(index, value)
    {
        if(Response['IDEMPRESA']!= 3)
        {
            var folio = parseInt(value['DOCTOS_VE.FOLIO']);
            var id = value['DOCTOS_VE.DOCTO_VE_ID'];
            var id_det = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];
            var empresa = Response['IDEMPRESA'];
            var cliente = value['CLIENTES.NOMBRE'];
            var entrega = value['TABLEROPRODUCCION.FECHA_ENTREGA'];
            var desc = value['TABLEROPRODUCCION.FECHA_ENTREGA'];

            var div_contenedor = "<div class='tarea tarea_corporativo ui-draggable ui-draggable-handle' data-folio='"+folio+"' data-id='"+id+"' data-id_det='"+id_det+"' data-empresa='"+empresa+"'>";
            var texto = "<b>FOLIO</b> "+folio+"<br><b>CLIENTE</b> "+cliente+"<br><b>ENTREGA</b> "+entrega+"<br><b>DESCRIPCIÓN</b> "+value['DOCTOS_VE.DESCRIPCION'];
            var btn = "<div class='agendar'><button type='button' class='btn btn-primary' onclick='ver_factura(this)'><i class='fa fa-calendar'></i></button></div></div>";
        }else
        {
            var folio = value['DOCTOS_PV.FOLIO'];
            var id = value['DOCTOS_PV.DOCTO_PV_ID'];
            var id_det = value['DOCTOS_PV_DET.DOCTO_PV_DET_ID'];
            var empresa = Response['IDEMPRESA'];
            var cliente = value['CLIENTES.NOMBRE'];
            var entrega = value['PRODUCCIONPV.F_ENTREGA'];
            var desc = value['PRODUCCIONPV.F_ENTREGA'];

            var div_contenedor = "<div class='tarea tarea_mostrador ui-draggable ui-draggable-handle' data-folio='"+folio+"' data-id='"+id+"' data-id_det='"+id_det+"' data-empresa='"+empresa+"'>";
            var texto = "<b>FOLIO</b> "+folio+"<br><b>CLIENTE</b> "+cliente+"<br><b>ENTREGA</b> "+entrega+"<br><b>DESCRIPCIÓN</b> "+value['DOCTOS_PV.DESCRIPCION'];
            var btn = "<div class='agendar'><button type='button' class='btn btn-primary' onclick='ver_factura(this)'><i class='fa fa-calendar'></i></button></div></div>";
        }    
        lista.append(div_contenedor+texto+btn);

    });

}

function edit_extra(obj)
{
    var div = $(obj).parent().parent();
    obj_general =div;

    $(".btn_guardar_borrador").show();
    $(".btn_guardar_principal").show();

    var desc = div.data("desc");
    var hour = div.data("hour");
    var min = div.data("min");
    var hour1 = div.data("hour1");
    var min1 = div.data("min1");
    var folio = div.data("folio");
    //var id_general = div.data("id_general");

    AgregaOperadores($("#formTarea #addoperador"), div.data("id_general"));

    $("#formTarea #descripcion").val(desc);
    $("#formTarea #folio").val(folio);
    $("#formTarea #hr").val(hour);
    $("#formTarea #min").val(min);
    $("#formTarea #hr2").val(hour1);
    $("#formTarea #min2").val(min1);
    $("#formTarea #observacion").val(div.data("obs"));
    $("#formTarea #estatus").val(div.data("estatus"));
    $("#formTarea #id_general").val(div.data("id_general"));
    $("#formTarea #identificador").val(div.data("identificador"));
    $("#formTarea #idOperador").val(div.parent().parent().parent().data("fila"));
    $("#formTarea #color").val($(obj).parents(".agenda_usuario").css("background-color"));

    $("#AddTask").modal("show");
}

function EditarTarea()
{
    var descripcion = $("#formTarea #descripcion").val();
    var folio = $("#formTarea #folio").val();

    var desc = "<div class='agendado'>("+folio+") "+descripcion+"<div class='texto_tiempo'></div></div>";
    var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_extra(this)'><i class='fa fa-edit'></i></button>";
    var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
    var div_funciones = "<div class='funciones'>"+btn_editar+btn_eliminar+"</div>";

    $("#contenedor_general > div").each(function()
    {
        if($(this).data("fila") == $("#formTarea #idOperador").val())
        {
            $(this).find(".contenedor1 > div").each(function()
            {
                if($(this).data("identificador") == $("#formTarea #identificador").val())
                {


                    var hrs = (parseInt($("#formTarea #hr").val()) * 2);
                    var min = (parseInt($("#formTarea #min").val()) / 30);
                    var time = ((hrs + min) * 4.54);
                    var minu = $("#formTarea #min").val();
                    var hour = $("#formTarea #hr").val();
                    var minu1 = $("#formTarea #min2").val();
                    var hour1 = $("#formTarea #hr2").val();
                    var obs = $("#formTarea #observacion").val();
                    var estatus = $("#formTarea #estatus").val();
                    var folio = $("#formTarea #folio").val();

                    $(this).html(desc+div_funciones);
                    $(this).data("desc", descripcion);
                    $(this).data("folio", folio);
                    $(this).data("hour", hour);
                    $(this).data("min", minu);
                    $(this).data("hour1", hour1);
                    $(this).data("min1", minu1);

                    $(this).data("obs", obs);
                    $(this).data("estatus", estatus);
                    $(this).css("width", time+"%" );

                }
            });
        }
    });
    $("#AddTask").modal("hide");
    calcula_tiempo_objetos();
}

function ver_factura(obj)
{
    $("#formTareaFactura input").val("");
    $("#formTareaFactura text").val("");
    var empresa = $(obj).parent().parent().data("empresa");
    var id = $(obj).parent().parent().data("id");
    var id_det = $(obj).parent().parent().data("id_det");

    $(".btn_guardar_principal").show();
    $(".btn_guardar_borrador").hide();

    AgregaOperadores($("#formTareaFactura #addoperador"),0);

    var variable = "accion=show&empresa="+empresa+"&id="+id+"&id_det="+id_det+"&departamento="+$("#departamento").val();
    RestFullRequest("_Rest/Agenda.php", variable, "rellenaDatosFactura");
}

function rellenaDatosFactura(Response)
{
    //console.log(Response);
    var form = $("#formTareaFactura");
    $.each(Response.TAREAS, function(index, value)
    {
        if(Response['IDEMPRESA'] != 3)
        {
            form.find("#folio").val(parseInt(value['DOCTOS_VE.FOLIO'].substr(1)));
            form.find("#cliente").val(value['CLIENTES.NOMBRE']);
            form.find("#descripcion").val(value['DOCTOS_VE.DESCRIPCION']);
            form.find("#descripcion").val(value['DOCTOS_VE.DESCRIPCION']);
            form.find("#nota").val(value['TABLEROPRODUCCION.NOTA']);
            form.find("#docto_ve_id").val(Response['DOCTO_VE_ID']);
            form.find("#docto_ve_det_id").val(Response['DOCTO_VE_DET_ID']);
            form.find("#empresa").val(Response['IDEMPRESA']);
            form.find("#entrega").val(value['TABLEROPRODUCCION.FECHA_ENTREGA']);
        }else
        {
            form.find("#folio").val(value['DOCTOS_PV.FOLIO']);
            form.find("#cliente").val(value['CLIENTES.NOMBRE']);
            form.find("#descripcion").val(value['DOCTOS_PV.DESCRIPCION']);
            form.find("#nota").val(value['PRODUCCIONPV.NOTAS_PROCESO']);
            form.find("#docto_ve_id").val(Response['DOCTO_VE_ID']);
            form.find("#docto_ve_det_id").val(Response['DOCTO_VE_DET_ID']);
            form.find("#empresa").val(Response['IDEMPRESA']);
            form.find("#entrega").val(value['PRODUCCIONPV.F_ENTREGA']);
        }    
    });
    $("#AddFactura").modal("show");
}

function actualiza_lista_borrador_delete()
{
    obj_borrador.remove();
     actualiza_borrador();
}


function guardarTareaFactura()
{
    if(parseInt($("#formTareaFactura #registro").val()) > 0)
    {
        var variable = "accion=eliminar_borrador&registro="+$("#formTareaFactura #registro").val();
        RestFullRequest("_Rest/Agenda.php", variable, "actualiza_lista_borrador_delete");
        /*obj_borrador.remove();*/    
    }

    if($("#formTareaFactura #hr").val()!="" && $("#formTareaFactura #min").val()!="")
    {
        var formulario = $("#formTareaFactura");
        var operador = $("#formTareaFactura #addoperador").val();
        var entrega = $("#formTareaFactura #entrega").val();


        var docto_ve_id = $("#formTareaFactura #docto_ve_id").val();
        var docto_ve_det_id = $("#formTareaFactura #docto_ve_det_id").val();
        var empresa = $("#formTareaFactura #empresa").val();
        var hrs = (parseInt($("#formTareaFactura #hr").val()) * 2);
        var min = (parseInt($("#formTareaFactura #min").val()) / 30);
        var time = ((hrs + min) * 4.54);

        var folio = formulario.find("#folio").val();
        var cliente = formulario.find("#cliente").val();
        var descripcion = formulario.find("#descripcion").val();
        var nota = formulario.find("#nota").val();


        var hr = formulario.find("#hr").val();
        var min = formulario.find("#min").val();
        var hr1 = formulario.find("#hr").val();
        var min1 = formulario.find("#min").val();
        var observacion = "'"+formulario.find("#observacion").val()+"'";
        var estatus = formulario.find("#estatus").val();
        var general;
        var identificador2;
        var contador = 0;


        if($("#formTareaFactura #id_general").val() > 0)
        {
            general = $("#formTareaFactura #id_general").val();
            identificador = $("#formTareaFactura #identificador").val();
            var backg = "background-color:"+formulario.find("#color").val();
        }else{
            general = ++id_general;
            var backg  = color();
        }
        identificador2 = identificador;

        if(!$.isEmptyObject(operador))
        $.each(operador, function(index, value)
        {
            $("#contenedor_general > div").each(function()
            {
                if($(this).data("fila") == value)
                {
                    $(this).find("div").each(function()
                    {
                        var texto = "<div class='agendado'>Cliente "+cliente+"<br><div class='texto_tiempo'></div>Descripción "+descripcion+"<br>Entrega "+entrega+"</div>";
                        var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_factura(this)'><i class='fa fa-edit'></i></button>";
                        var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
                        var div_funciones = "<div class='funciones'>"+btn_editar+btn_eliminar+"</div>";
                        var div_contenedor = "<div style='overflow:hidden; width: "+time+"%; position: relative;"+backg+"' class='agenda_usuario' data-access='no' data-folio="+folio+" data-cliente='"+cliente+"' data-entrega='"+entrega+"' data-id_general="+general+" data-desc='"+descripcion+"'  data-identificador="+identificador2+"  data-docto_ve_id="+docto_ve_id+" data-docto_ve_det_id="+docto_ve_det_id+"  data-empresa="+empresa+" class='ui-draggable ui-draggable-handle' data-operador='"+$(this)+"' data-id='"+1+"' data-hour="+hr+"  data-min="+min+"  data-hour1="+hr1+"  data-min1="+min1+"  data-obs="+observacion+"  data-estatus="+estatus+">"+texto+div_funciones+"</div>";

                        $(this).find(".contenedor1").append(div_contenedor);
                        identificador++;
                    });
                }
            });
            contador++;
        });
        if(contador == 0)
        {
            
            GuardarEdicionFactura();
        }else
        {
            $(".lista_tareas div[data-id='"+docto_ve_id+"'][data-id_det='"+docto_ve_det_id+"'][data-empresa='"+empresa+"']").remove();
            $("#AddFactura").modal("hide");
            calcula_tiempo_objetos();
        }
    }else
    {
        alert("Debe de ingresar el tiempo de realización de la tarea");
    }
}



function edit_factura(obj)
{
    var div = $(obj).parent().parent();
    obj_general = div;

    $(".btn_guardar_borrador").show();
    $(".btn_guardar_principal").show();

    $("#formTareaFactura input").val("");
    $("#formTareaFactura textarea").val("");


    var desc = div.data("desc");
    var hour = div.data("hour");
    var min = div.data("min");
    var hour1 = div.data("hour1");
    var min1 = div.data("min1");
    var observacion = div.data("obs");
    var estatus = div.data("estatus");
    var empresa = div.data("empresa");
    var id = div.data("docto_ve_id");
    var id_det = div.data("docto_ve_det_id");
    var entrega = div.data("entrega");

    AgregaOperadores($("#formTareaFactura #addoperador"), div.data("id_general"));

    $("#formTareaFactura #descripcion").val(desc);
    $("#formTareaFactura #hr").val(hour);
    $("#formTareaFactura #min").val(min);
    $("#formTareaFactura #hr2").val(hour1);
    $("#formTareaFactura #min2").val(min1);
    $("#formTareaFactura #observacion").val(observacion);
    $("#formTareaFactura #estatus").val(estatus);
    $("#formTareaFactura #id_general").val(div.data("id_general"));
    $("#formTareaFactura #identificador").val(div.data("identificador"));
    $("#formTareaFactura #idOperador").val(div.parent().parent().parent().data("fila"));
    $("#formTareaFactura #entrega").val(entrega);
    $("#formTareaFactura #color").val($(obj).parents(".agenda_usuario").css("background-color"));
    $("#formTareaFactura #cliente").val(div.data("cliente"));
    $("#formTareaFactura #folio").val(div.data("folio"));

    var variable = "accion=show&empresa="+empresa+"&id="+id+"&id_det="+id_det+"&departamento="+$("#departamento").val();
    RestFullRequest("_Rest/Agenda.php", variable, "rellenaDatosFactura");

    $("#AddFactura").modal("show");
}

function GuardarEdicionFactura()
{

    var hrs = (parseInt($("#formTareaFactura #hr").val()) * 2);
    var min = (parseInt($("#formTareaFactura #min").val()) / 30);
    var time = ((hrs + min) * 4.54);

    var descripcion = $("#formTareaFactura #descripcion").val();
    var cliente = $("#formTareaFactura #cliente").val();


    var hour = $("#formTareaFactura #hr").val();
    var minu = $("#formTareaFactura #min").val();
    var hour1 = $("#formTareaFactura #hr2").val();
    var minu1 = $("#formTareaFactura #min2").val();
    var obs = $("#formTareaFactura #observacion").val();
    var estatus = $("#formTareaFactura #estatus").val();
    //var desc = desc+" "+hour+" Hrs "+minu+" Min";
    var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_factura(this)'><i class='fa fa-edit'></i></button>";
    var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
    var div_funciones = "<div class='funciones'>"+btn_editar+btn_eliminar+"</div>";

    $("#contenedor_general > div").each(function()
    {
        if($(this).data("fila") == $("#formTareaFactura #idOperador").val())
        {
            $(this).find(".contenedor1 > div").each(function()
            {

                if($(this).data("identificador") == $("#formTareaFactura #identificador").val())
                {
                    var desc = "<div class='agendado'>Cliente "+cliente+"<br><div class='texto_tiempo'></div>"+"Descripción "+descripcion+"<br>Entrega "+$(this).data("entrega")+"</div>";
                    $(this).html(desc+div_funciones);
                    $(this).data("desc", descripcion);
                    $(this).data("hour", hour);
                    $(this).data("min", minu);
                    $(this).data("hour1", hour1);
                    $(this).data("min1", minu1);
                    $(this).data("obs", obs);
                    $(this).data("estatus", estatus);
                    $(this).css("width", time+"%" );
                }

            });
        }
    });
    $("#AddFactura").modal("hide");
    calcula_tiempo_objetos();
}

function busca_id(id)
{
    var contador = 0;
    $("#contenedor_general .contenedor1 > div").each(function()
    {
        if($(this).data("id_general") == id)
            contador++;
    });
    return contador;
}

function GuardaBD()
{
    //var variable = "accion=delete";
    var myObject = new Object();
    var contador = 3;
    var index = new Object();
    myObject[0] = index.accion = "guardaDB";
    myObject[1] = index.fecha = $("#dia").val();
    myObject[2] = index.departamento = $("#departamento").val();
    $("#contenedor_general >  div").next().each(function()
    {
        $(this).find(".contenedor1 > div").each(function()
        {
            var agenda = new Object();
            if($(this).data("empresa"))
            {
                agenda.empresa = $(this).data("empresa");
                agenda.operador = $(this).parent().parent().parent().data("fila");
                agenda.docto_ve_id = $(this).data("docto_ve_id");
                agenda.docto_ve_det_id = $(this).data("docto_ve_det_id");
                agenda.hour = $(this).data("hour");
                agenda.minute = $(this).data("min");
                agenda.hour1 = $(this).data("hour1");
                agenda.minute1 = $(this).data("min1");
                agenda.observacion = $(this).data("obs");
                agenda.estatus = $(this).data("estatus");

                agenda.general = $(this).data("id_general");
                agenda.descripcion = $(this).data("desc");
                agenda.folio = $(this).data("folio");
                agenda.cliente = $(this).data("cliente");
                agenda.entrega = $(this).data("entrega");
                agenda.color = $(this).css("background-color");
                myObject[contador] = agenda;
            }else
            {
                agenda.empresa = 0;
                agenda.docto_ve_id = 0;
                agenda.docto_ve_det_id = 0;
                agenda.operador = $(this).parent().parent().parent().data("fila");
                agenda.hour = $(this).data("hour");

                agenda.minute = $(this).data("min");
                agenda.hour1 = $(this).data("hour1");
                agenda.minute1 = $(this).data("min1");
                agenda.observacion = $(this).data("obs");
                agenda.estatus = $(this).data("estatus");
                agenda.general = $(this).data("id_general");
                agenda.descripcion = $(this).data("desc");
                agenda.folio = $(this).data("folio");
                agenda.cliente = "ACTIVIDAD EXTRA";
                agenda.entrega = "";
                agenda.color = $(this).css("background-color");
                myObject[contador] = agenda;
            }
            contador++;
            //console.log($(this).data("docto_ve_id"));
        });
  });

    //console.log(myObject);
    //console.log($("#dia").val());
    var variable = "accion=guardaDB&campos="+myObject;
    RestFullRequest("_Rest/Agenda.php", myObject, "GuardaAgenda", 1);
}

function GuardaAgenda(Response)
{
    console.log(Response);
}

function reporteAgenda()
{
    window.open("reportes/agenda/reporte.php?fecha="+$("#dia").val()+"&departamento="+$("#departamento").val(),"_blank");
}

function recarga_eficiencia()
{
    var variable = "accion=verificaEficiencia&departamento="+$("#departamento").val()+"&fecha="+$("#dia").val();
    RestFullRequest("_Rest/Agenda.php", variable, "Eficiencia");
}

function Eficiencia(Response)
{
    var total = Response.total.PAGINADOR;
    var realizados = Response.realizados.PAGINADOR;
    if(total ==0 && realizados == 0)
        var percent = 0;
    else
        var percent = parseFloat((realizados / total) * 100).toFixed(2);
    $("#indicador_eficiencia").text(realizados +" / "+total+" ("+percent+" %)");
}

function ver_Actividades_Extras()
{
    $("#ReporteExtras").modal("show");
}
