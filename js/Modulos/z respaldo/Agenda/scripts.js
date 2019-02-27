var id_general = 1;
var identificador = 1;
var lista_operadores = [];

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

    var div_gen = $("#contenedor_general > div:first").next();
    //console.log(div_gen.html());
    $.each(response.TAREAS, function(index, value)
    {
        if(value['CLIENTES.NOMBRE'])
        {
            var cliente = value['CLIENTES.NOMBRE'];
            var descripcion = value['DOCTOS_VE.DESCRIPCION'];
            var entrega = value['TABLEROPRODUCCION.FECHA_ENTREGA'];
            var folio = value['DOCTOS_VE.FOLIO'];
            var empresa = response['IDEMPRESA'];
            var docto_ve_id = value['DOCTOS_VE.DOCTO_VE_ID'];
            var docto_ve_det_id = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];
             var colour = color();
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
            var colour = value['AGENDA.COLOR'];

            time = (( hrs + min) * 4.54);
            general = value['AGENDA.ARREGLO'];
            id_general = general;
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
            var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_extra(this)'><i class='fa fa-edit'></i></button>";

        var texto = "<div class='agendado'>Cliente "+cliente+"<br><div class='texto_tiempo'></div>Descripción "+descripcion+"<br>Entrega "+entrega+"</div>";

        var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
        var div_funciones = "<div class='funciones'>"+btn_editar+btn_eliminar+"</div>";


        var div_contenedor = "<div style='overflow:hidden; width: "+time+"%; position: relative; "+colour+"' class='agenda_usuario' data-access='no' data-cliente='"+cliente+"' data-entrega='"+entrega+"' data-folio="+folio+" data-id_general="+general+" data-desc='"+descripcion+"'  data-identificador="+identificador+"  data-docto_ve_id="+docto_ve_id+" data-docto_ve_det_id="+docto_ve_det_id+"  data-empresa="+empresa+" data-operador='"+$(this)+"' data-id='"+1+"' data-hour="+hr+"  data-min="+min+">"+texto+div_funciones+"</div>";

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
    //console.log(response);
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

        var linea = "";
        var id = value['DOCTOS_VE.DOCTO_VE_ID'];
        var id_det = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];
        var empresa = value['EMPRESA'];
        var folio = value['DOCTOS_VE.FOLIO'];
        var cliente = value['CLIENTES.NOMBRE'];
        var fecha = value['TABLEROPRODUCCION.FECHA_ENTREGA'];
        var desc = value['DOCTOS_VE.DESCRIPCION'];
        linea += "<div class='tarea' data-folio='"+folio+"' data-id='"+id+"'  data-id_det='"+id_det+"' data-empresa='"+empresa+"'><b>FOLIO</b> "+folio+"<br><b>CLIENTE</b> "+cliente+"<br><b>ENTREGA</b> "+fecha+"<br><b>DESCRIPCIÓN</b> "+desc+"<div class='agendar'><button type='button' class='btn btn-primary' onclick='ver_factura(this)' ><i class='fa fa-calendar'></i></button></div></div>";


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
   //if(contador == 0)
     //   datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
    var variable = "accion=iniciadas&dia="+$("#dia").val()+"&fechaHasta="+$("#fechafin").val()+"&departamento="+$("#departamento").val();
    RestFullRequest("_Rest/Agenda.php", variable, "datagridAgendaIniciadas");
}

$(document).ready(function()
{
    $("#operacion").find("a").click();
    actualizaDatagrid();
});



function AgregarTarea()
{
    $("#AddTask").modal();
    $("#formTarea").find("input").val("");
    AgregaOperadores($("#formTarea #addoperador"), 0);
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

    var operador = $("#formTarea #addoperador").val();
    var hrs = (parseInt($("#formTarea #hr").val()) * 2);
    var min = (parseInt($("#formTarea #min").val()) / 30);
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
                var hour = $("#formTarea #hr").val();
                var min = $("#formTarea #min").val();
                var descripcion = $("#formTarea #descripcion").val();
                var desc = "<div class='agendado'>"+$("#formTarea #descripcion").val()+"</div><div class='texto_tiempo'></div>";
                var btn_editar = "<button type='button' class='btn btn-primary btn-strech' onclick='edit_extra(this)'><i class='fa fa-edit'></i></button>";
                var btn_eliminar = "<button type='button' class='btn btn-danger btn-strech' onclick='eliminar_extra(this);'><i class='fa fa-close'></i></button>";
                var div_funciones = "<div class='funciones'>"+btn_editar+btn_eliminar+"</div>";
                var div_contenedor = "<div style='overflow:hidden; width: "+time+"%; position: relative;"+backg+"'  class='agenda_usuario' data-access='no' class='ui-draggable ui-draggable-handle' data-operador='"+$(this)+"' data-identificador='"+identificador+"' data-id_general='"+general+"' data-desc='"+descripcion+"' data-hour="+hour+"  data-min="+min+">"+desc+div_funciones+"</div>";
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
    //console.log(Response);
    $.each(Response.TAREAS, function(index, value)
    {
        var folio = parseInt(value['DOCTOS_VE.FOLIO']);
        var id = value['DOCTOS_VE.DOCTO_VE_ID'];
        var id_det = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];
        var empresa = Response['IDEMPRESA'];
        var cliente = value['CLIENTES.NOMBRE'];
        var entrega = value['TABLEROPRODUCCION.FECHA_ENTREGA'];
        var desc = value['TABLEROPRODUCCION.FECHA_ENTREGA'];

        var div_contenedor = "<div class='tarea ui-draggable ui-draggable-handle' data-folio='"+folio+"' data-id='"+id+"' data-id_det='"+id_det+"' data-empresa='"+empresa+"'>";
        var texto = "<b>FOLIO</b> "+folio+"<br><b>CLIENTE</b> "+cliente+"<br><b>ENTREGA</b> "+entrega+"<br><b>DESCRIPCIÓN</b> "+value['DOCTOS_VE.DESCRIPCION'];
        var btn = "<div class='agendar'><button type='button' class='btn btn-primary' onclick='ver_factura(this)'><i class='fa fa-calendar'></i></button></div></div>";

        lista.append(div_contenedor+texto+btn);

    });

}

function edit_extra(obj)
{
    var div = $(obj).parent().parent();

    var desc = div.data("desc");
    var hour = div.data("hour");
    var min = div.data("min");
    //var id_general = div.data("id_general");

    AgregaOperadores($("#formTarea #addoperador"), div.data("id_general"));

    $("#formTarea #descripcion").val(desc);
    $("#formTarea #hr").val(hour);
    $("#formTarea #min").val(min);
    $("#formTarea #id_general").val(div.data("id_general"));
    $("#formTarea #identificador").val(div.data("identificador"));
    $("#formTarea #idOperador").val(div.parent().parent().parent().data("fila"));
    $("#formTarea #color").val($(obj).parents(".agenda_usuario").css("background-color"));

    $("#AddTask").modal("show");
}

function EditarTarea()
{
    var hrs = (parseInt($("#formTarea #hr").val()) * 2);
    var min = (parseInt($("#formTarea #min").val()) / 30);
    var time = ((hrs + min) * 4.54);

    var descripcion = $("#formTarea #descripcion").val();

    var hour = $("#formTarea #hr").val();
    var minu = $("#formTarea #min").val();
    var desc = "<div class='agendado'>"+descripcion+"<div class='texto_tiempo'></div></div>";
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
                    $(this).html(desc+div_funciones);
                    $(this).data("desc", descripcion);
                    $(this).data("hour", hour);
                    $(this).data("min", minu);
                }
                $(this).css("width", time+"%" );
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
        form.find("#folio").val(parseInt(value['DOCTOS_VE.FOLIO']));
        form.find("#cliente").val(value['CLIENTES.NOMBRE']);
        form.find("#descripcion").val(value['DOCTOS_VE.DESCRIPCION']);
        form.find("#descripcion").val(value['DOCTOS_VE.DESCRIPCION']);
        form.find("#nota").val(value['TABLEROPRODUCCION.NOTA']);
        form.find("#docto_ve_id").val(Response['DOCTO_VE_ID']);
        form.find("#docto_ve_det_id").val(Response['DOCTO_VE_DET_ID']);
        form.find("#empresa").val(Response['IDEMPRESA']);
        form.find("#entrega").val(value['TABLEROPRODUCCION.FECHA_ENTREGA']);

    });
    $("#AddFactura").modal("show");
}

function guardarTareaFactura()
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
        //console.log($("#contenedor_general div").data("fila"));
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
                        var div_contenedor = "<div style='overflow:hidden; width: "+time+"%; position: relative;"+backg+"' class='agenda_usuario' data-access='no' data-folio="+folio+" data-cliente='"+cliente+"' data-entrega='"+entrega+"' data-id_general="+general+" data-desc='"+descripcion+"'  data-identificador="+identificador2+"  data-docto_ve_id="+docto_ve_id+" data-docto_ve_det_id="+docto_ve_det_id+"  data-empresa="+empresa+" class='ui-draggable ui-draggable-handle' data-operador='"+$(this)+"' data-id='"+1+"' data-hour="+hr+"  data-min="+min+">"+texto+div_funciones+"</div>";

                        $(this).find(".contenedor1").append(div_contenedor);
                        identificador++;
                });

            }
        });
        contador++;
    });
    if(contador == 0)
        GuardarEdicionFactura();
    else
    {
        $(".lista_tareas div[data-id='"+docto_ve_id+"'][data-id_det='"+docto_ve_det_id+"'][data-empresa='"+empresa+"']").remove();
        $("#AddFactura").modal("hide");
        calcula_tiempo_objetos();
    }
}



function edit_factura(obj)
{
    var div = $(obj).parent().parent();

    var desc = div.data("desc");
    var hour = div.data("hour");
    var min = div.data("min");
    var empresa = div.data("empresa");
    var id = div.data("docto_ve_id");
    var id_det = div.data("docto_ve_det_id");
    var entrega = div.data("entrega");

    //var id_general = div.data("id_general");

    AgregaOperadores($("#formTareaFactura #addoperador"), div.data("id_general"));

    $("#formTareaFactura #descripcion").val(desc);
    $("#formTareaFactura #hr").val(hour);
    $("#formTareaFactura #min").val(min);
    $("#formTareaFactura #id_general").val(div.data("id_general"));
    $("#formTareaFactura #identificador").val(div.data("identificador"));
    $("#formTareaFactura #idOperador").val(div.parent().parent().parent().data("fila"));
    $("#formTareaFactura #entrega").val(entrega);
    $("#formTareaFactura #color").val($(obj).parents(".agenda_usuario").css("background-color"));

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
        //console.log($(this).data("id_general"));
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
                agenda.general = $(this).data("id_general");
                agenda.descripcion = $(this).data("desc");
                agenda.folio = 0;
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
    RestFullRequest("_Rest/Agenda.php", myObject, "GuardaAgenda");
}

function GuardaAgenda(Response)
{
    console.log(Response);
}

function reporteAgenda()
{
    window.open("reportes/agenda/reporte.php?fecha="+$("#dia").val()+"&departamento="+$("#departamento").val(),"_blank");
}

