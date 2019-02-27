var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;

function datagridPendientes(response)
{
    var datagrid = $("#dataprospectos");
    datagrid.find("tr").remove();
    var contador = 0;

    $.each(response, function(index, value)
    {

        var campos = "";
        var index = 0;
        var id;
        id = value['CLIENTESCALL.ID'];

        var botonInfo = "<LABEL type='button' class='label label-default'>"+value['ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO']+"</label>";
        campos += "<td>"+value['CLIENTESCALL.NOMBRE']+"<br><button type='button' class='btn btn-strech btn-primary' onclick='cambiaTipo("+id+")'><i class='fa fa-user'></i></button>"+botonInfo+" <BR>"+value['CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION']+" / "+value['SEGMENTOCALL.DESCRIPCIONSEGMENTO']+"</td>";
        //campos += "<td>"++"</td>";
        campos += "<td>";
        campos += "CONTACTO: "+value['CONTACTOCLIENTESCALL.CONTACTO1']+"<BR> TELEFONO (1): "+value['CONTACTOCLIENTESCALL.TELEFONO1']+" <BR>TELEFONO (2): "+value['CONTACTOCLIENTESCALL.TELEFONO2']+"<br>";
        campos += "Correo: "+value['CONTACTOCLIENTESCALL.CORREO']+"<br>";
        campos += ""+value['CONTACTOCLIENTESCALL.DIRECCION']+"</td>";

        if(value['CONTACTOCLIENTESCALL.EMAILING'] == 1)
            campos += "<td align='center'><i class='fa fa-check ' style='color: #00dd00'></i></td>";
        else
            campos += "<td align='center'><i class='fa fa-close' style='color: #E21800'></i></td>";
        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
        var prioridad;

        campos +="<td align='center'><input type='checkbox'></td>";


        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function actualizaDatagrid()
{
    var variable = "accion=index&page="+paginacion+"&filtrocliente="+$("#clientefiltro").val()+"&filtrotipocliente="+$("#estatusfiltro").val()+"&filtrosegmento="+$("#segmentofiltro").val();
    RestFullRequest("_Rest/Prospectos.php", variable, "datagridPendientes");
    paginador();
}

function cargaCatalogos()
{
    var variable = "accion=cargaCatalogos";
    RestFullRequest("_Rest/Prospectos.php", variable, "rellenaFormulario");
}

function rellenaFormulario(response)
{
    $.each(response[0], function(index, value)
    {
        $("#tipoCliente").append("<option  value="+value['TIPOCLIENTESCALL.ID']+">"+value['TIPOCLIENTESCALL.DESCRIPCION']+"</option>");
    });

    $.each(response[1], function(index, value)
    {
        $("#clase").append("<option  value="+value['CLASIFICACIONCALL.ID']+">"+value['CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION']+"</option>");
    });
    $.each(response[2], function(index, value)
    {
        $("#clasificacion").append("<option  value="+value['SEGMENTOCALL.ID']+">"+value['SEGMENTOCALL.DESCRIPCIONSEGMENTO']+"</option>");
        $("#segmentofiltro").append("<option  value="+value['SEGMENTOCALL.ID']+">"+value['SEGMENTOCALL.DESCRIPCIONSEGMENTO']+"</option>");
    });

    $.each(response[3], function(index, value)
    {
        $("#estatusSeguimiento").append("<option  value="+value['ESTATUSSEGUIMIENTO.ID']+">"+value['ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO']+"</option>");
    });

    $.each(response[4], function(index, value)
    {
        $("#t_cliente").append("<option  value="+value['TIPOS_CLIENTES.TIPO_CLIENTE_ID']+">"+value['TIPOS_CLIENTES.NOMBRE']+"</option>");
    });
}

$("#btn_email").on("click", function()
{
    var variable = "accion=lista_email";
    RestFullRequest("_Rest/Prospectos.php", variable, "VerLista");

});

$("#btn_descargar").on("click", function()
{
    window.open("reportes/closesales/backup_crm.php", "_blank");
});

function VerLista(Response)
{
    var lista = "";
    $.each(Response, function(index, value)
    {
        lista += value['CONTACTOCLIENTESCALL.CORREO']+";";
    });
    $("#lista_email").append(lista);
    $("#envio_email").modal("show");
}

$("#btn_update").on("click", function()
{
    $("#actualiza_lista").modal("show");
});

$("#actualiza_crm").on("click", function()
{
    var variable = "accion=update_crm";
    RestFullRequest("_Rest/Prospectos.php", variable, "update_crm");
});

function update_crm(Response)
{
    console.log(Response);
}

$("#btn_backup").on("click", function()
{
    window.open("")
});

$("#guardarCliente").on("click", function()
{
    if($("#id").val() >0)
    {
        var variable = "accion=update&"+$("#FormProspecto").serialize();
        RestFullRequest("_Rest/Prospectos.php", variable, "guardarUsuario");
    }else
    {
        var variable = "accion=save&"+$("#FormProspecto").serialize();
        RestFullRequest("_Rest/Prospectos.php", variable, "guardarUsuario");
    }

});

$("#eliminarContacto").on("click", function()
{
    if($("#num_contacto").val() > 0)
    {
        var variable = "accion=deleteContacto&"+$("#FormProspecto").serialize();
        RestFullRequest("_Rest/Prospectos.php", variable, "guardarContacto");
    }else
    {
        alert("DEBE DE SELECCIONAR UN CONTACTO");
    }
});

$("#guardarContacto").on("click", function()
{
    if($("#num_contacto").val() > 0)
    {
        var variable = "accion=saveContacto&"+$("#FormProspecto").serialize();
        RestFullRequest("_Rest/Prospectos.php", variable, "guardarContacto");
    }else
    {
        var variable = "accion=saveContacto&"+$("#FormProspecto").serialize();
        RestFullRequest("_Rest/Prospectos.php", variable, "guardarContacto");
    }
});

function guardarContacto(Response)
{
    alert("SE A REALIZADO CORRECTAMENTE LA TRANSACCIÓN");

    var variable = "accion=cargaCliente&id="+$("#id").val();
    RestFullRequest("_Rest/Prospectos.php", variable, "recargarCliente");
}

function guardarUsuario(response)
{
    actualizaDatagrid();
    $("#cliente").modal("hide");
}

$("#btn_agregar").on("click", function()
{
    $("#cliente").modal("show");
    $("#Titulo_Modulo").text("Agregar Prospecto / Cliente");
    $("#cliente input").val("");
    $("#cliente select option:first").attr("selected", "selected");
    $("#num_contacto").html("<option value='0'>NUEVO CONTACTO</option>");
    $("#cliente textarea").val("");

});
$(document).ready(function(e) {
    actualizaDatagrid();
    cargaCatalogos();
    actualizaProcesos();
    $("#call").find("a").click();
});


/*  Empieza la funcionalidad */
$("#btn_mail").on("click", function()
{
    var id = "0";
    var contador = 0;
    $("#dataprospectos tr input[type=checkbox]:checked").each(function()
    {
        id = id+","+$(this).parents("tr").data("fila");
        contador++;
    });


    if(contador == 0)
    {
        var variable = "accion=seleccion_mail&tipo=1&filtrocliente="+$("#clientefiltro").val()+"&filtrotipocliente="+$("#estatusfiltro").val()+"&filtrosegmento="+$("#segmentofiltro").val();
        RestFullRequest("_Rest/Prospectos.php", variable, "obtener_mail");
    }else
    {
        var variable = "accion=seleccion_mail&tipo=2&ids="+id;
        RestFullRequest("_Rest/Prospectos.php", variable, "obtener_mail");
    }
    
});

function obtener_mail(response)
{   
    alert("Espere un momento, mientras se obtiene los registros");
    var correos = "";
    var contador_correos = 0;
    $.each(response, function(index, value)
    {
        correos = correos + value['CONTACTOCLIENTESCALL.CORREO']+";";
        contador_correos++;
    });
    if(contador_correos>0){
        console.log('mailto:' + 'mostrador@nexprint.mx?bcc='+correos);
        window.location = 'mailto:' + 'mostrador@nexprint.mx?bcc='+correos;
    }
    else
        alert("No hay correos disponibles en su seleccion, vuelva a intentarlo");
}

$("#editarCliente").on("click", function()
{
    var id;
    $("#dataprospectos tr input[type=checkbox]:checked").each(function()
    {
        id = $(this).parents("tr").data("fila");
    });

    var variable = "accion=cargaCliente&id="+id;
    RestFullRequest("_Rest/Prospectos.php", variable, "cargarCliente");
});

function cargarCliente(Response)
{
    console.log(Response);
    $("#num_contacto").html("");
    $.each(Response, function(index, value)
    {
        $("#id").val(value["CLIENTESCALL.ID"]);
        $("#nombreCliente").val(value["CLIENTESCALL.NOMBRE"]);
        $("#pagina").val(value["CLIENTESCALL.PAGINAWEB"]);
        $("#clase").val(value["CLIENTESCALL.IDCLASIFICACION"]);
        $("#clasificacion").val(value["CLIENTESCALL.IDSEGMENTO"]);
        $("#estatusSeguimiento").val(value["CLIENTESCALL.IDESTATUSSEGUIMIENTO"]);
        $("#tipoCliente").val(value["CLIENTESCALL.IDTIPOCLIENTE"]);
        $("#t_cliente").val(value["CLIENTESCALL.IDTIPOCLIENTEMICRO"]);

        $("#num_contacto").append("<option value=0>CONTACTO NUEVO</option>");

        $.each(value["CONTACTO"], function(index2, value2)
        {
            //console.log(value2);
            $("#num_contacto").append("<option value="+value2['CONTACTOCLIENTESCALL.ID']+">CONTACTO "+value2['CONTACTOCLIENTESCALL.CONTACTO1']+"</option>");
           if(value2['CONTACTOCLIENTESCALL.PRINCIPAL'] == 1)
           {
               $("#contacto1").val(value2["CONTACTOCLIENTESCALL.CONTACTO1"]);
               $("#contacto2").val(value2["CONTACTOCLIENTESCALL.CONTACTO2"]);
               $("#telefono1").val(value2["CONTACTOCLIENTESCALL.TELEFONO1"]);
               $("#telefono2").val(value2["CONTACTOCLIENTESCALL.TELEFONO2"]);
               $("#correo").val(value2["CONTACTOCLIENTESCALL.CORREO"]);
               $("#horario").val(value2["CONTACTOCLIENTESCALL.HORARIO"]);
               $("#direccion").val(value2["CONTACTOCLIENTESCALL.DIRECCION"]);

               if(value2['CONTACTOCLIENTESCALL.EMAILING'] == 1)
                   $("#emailing").val(1);
               else
                   $("#emailing").val(0);

               if(value2['CONTACTOCLIENTESCALL.PRINCIPAL'] == 1)
                   $("#principal").val(1);
               else
                   $("#principal").val(0);

               $("#num_contacto").val(value2["CONTACTOCLIENTESCALL.ID"]);

           }
           //console.log(value2['CONTACTOCLIENTESCALL.CONTACTO']);
        });

    });

   // $("#num_contacto").val(0);
    //$("#num_contacto").change();

    $("#cliente").modal("show");
    $("#Titulo_Modulo").text("Editar Prospecto / Cliente");
}

function recargarCliente(Response)
{
    //console.log(Response);
    $("#num_contacto").html("");
    $.each(Response, function(index, value)
    {
        $("#num_contacto").append("<option value=0>CONTACTO NUEVO</option>");

        $.each(value["CONTACTO"], function(index2, value2)
        {
            //console.log(value2);
            $("#num_contacto").append("<option value="+value2['CONTACTOCLIENTESCALL.ID']+">CONTACTO "+value2['CONTACTOCLIENTESCALL.CONTACTO1']+"</option>");
        });

    });

     $("#num_contacto").val(0);
    $("#num_contacto").change();
}

$("#num_contacto").on("change", function()
{
    var variable = "accion=cargaContacto&id="+$("#id").val()+"&contacto="+$(this).val();
    RestFullRequest("_Rest/Prospectos.php", variable, "cargarContacto");
});


function cargarContacto(Response)
{
    var band = 0;
    $.each(Response, function(index2, value2)
    {
        $("#contacto1").val(value2["CONTACTOCLIENTESCALL.CONTACTO1"]);
        $("#contacto2").val(value2["CONTACTOCLIENTESCALL.CONTACTO2"]);
        $("#telefono1").val(value2["CONTACTOCLIENTESCALL.TELEFONO1"]);
        $("#telefono2").val(value2["CONTACTOCLIENTESCALL.TELEFONO2"]);
        $("#correo").val(value2["CONTACTOCLIENTESCALL.CORREO"]);
        $("#horario").val(value2["CONTACTOCLIENTESCALL.HORARIO"]);
        $("#direccion").val(value2["CONTACTOCLIENTESCALL.DIRECCION"]);

        if(value2['CONTACTOCLIENTESCALL.EMAILING'] == 1)
            $("#emailing").val(1);
        else
            $("#emailing").val(0);

        if(value2['CONTACTOCLIENTESCALL.PRINCIPAL'] == 1)
            $("#principal").val(1);
        else
            $("#principal").val(0);

        //$("#num_contacto").val(value2["CONTACTOCLIENTESCALL.ID"]);
        band = 1;
    });

    if(band == 0)
    {
        $("#contacto1").val("");
        $("#contacto2").val("");
        $("#telefono1").val("");
        $("#telefono2").val("");
        $("#correo").val("");
        $("#horario").val("");
        $("#direccion").val("");
        $("#emailing").val(1);
        $("#principal").val(1);

    }
}
$("#bajaCliente").on("click", function()
{
    if(confirm("¿Realmente Desea dar de Baja el Prospecto / Cliente?"))
    {
        var id;
        $("#dataprospectos tr input[type=checkbox]:checked").each(function()
        {
            id = $(this).parents("tr").data("fila");
        });

        var variable = "accion=bajaCliente&id="+id;
        RestFullRequest("_Rest/Prospectos.php", variable, null);

        alert("SE HA DADO DE BAJA CORRECTAMENTE EL PROSPECTO / CLIENTE");
        actualizaDatagrid();
    }
});

function paginador()
{
    var variable = "accion=counter&page="+1+"&filtrocliente="+$("#clientefiltro").val()+"&filtrotipocliente="+$("#estatusfiltro").val();

    RestFullRequest("_Rest/Prospectos.php", variable, "creaPaginador");

}

function creaPaginador(Response)
{
    $(".pagination").find("li").remove();
    var paginas = Math.ceil((Response.PAGINADOR / 20));
    var contador = 1;
    while(contador <= paginas)
    {
        if(contador == paginacion)
            $(".pagination").append("<li class='paginate_button active' onclick=\"pages(this, "+contador+");\"><a>"+contador+"</a></li>");
        else
            $(".pagination").append("<li class='paginate_button' onclick=\"pages(this, "+contador+");\"><a>"+contador+"</a></li>");
        contador++;
    }
}

function pages(Obj, value)
{
    $(".pagination li").removeClass("active");
    $(Obj).addClass("active");
    paginacion = value;
    actualizaDatagrid();
}

function cambiaTipo(id)
{
    if(confirm("¿Realmente desea editar este prospecto / cliente?"))
    {
        var variable = "accion=actualizaTipo&idcliente="+id;
        RestFullRequest("_Rest/Prospectos.php", variable, "actualizaDatagrid");
    }
}

/*  */

/**
 * Created by SALUD on 25/09/15.
 */
