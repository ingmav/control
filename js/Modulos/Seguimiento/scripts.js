var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;

function datagridPendientes(response)
{
    var datagrid = $("#dataseguimiento");
    datagrid.find("tr").remove();
    var contador = 0;
    //console.log(response);
    $.each(response, function(index, value)
    {


        var campos = "";
        var index = 0;
        var id;
        id = value['CLIENTESCALL.ID'];


        if(value['estatus'] == "")
            value['estatus'] = "danger";


        if(value['SEGUIMIENTOACTIVO.FECHA'].trim() == "")
            var button = "<button type='button' class='btn btn-default' onclick='activaseguimiento(1, "+id+")'><i class='fa fa-frown-o'></i></button>";
        else
            var button = "<button type='button' class='btn btn-success' onclick='activaseguimiento(2, "+id+")'><i class='fa fa-smile-o'></i></button>"

        campos += "<td>"+value['CLIENTESCALL.NOMBRE']+"<br>CLASIFICACIÓN<br> <b>"+value['CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION']+" / "+value['SEGMENTOCALL.DESCRIPCIONSEGMENTO']+"</b><br>"+button+"</td>";
        campos += "<td>";
        campos += "Teléfono (1): <b>"+value['CONTACTOCLIENTESCALL.TELEFONO1']+"</b> <BR>TELEFONO (2): <b>"+value['CONTACTOCLIENTESCALL.TELEFONO2']+"</b><br>";
        campos += "Correo <b>"+value['CONTACTOCLIENTESCALL.CORREO']+"</b><br>";
        campos += "Dirección <b>"+value['CONTACTOCLIENTESCALL.DIRECCION']+"</b></td>";
        campos += "<td>"+value['ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO']+"</td>";
        //campos += "<td align='center'><button type='button' class='btn btn-"+value['estatus']+"'>"+value['FECHAMAXIMA']+"</button> </td>";
        campos += "<td align='center'><span class='label label-"+value['estatus']+"'>"+value['FECHAMAXIMA']+"</span></td>";
        campos += "<td align='center'><input type='checkbox' /></td>";

        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
        var prioridad;

        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function actualizaDatagrid()
{

    var variable = "accion=index&page="+paginacion+"&filtrocliente="+$("#clientefiltro").val()+"&filtrotipocliente="+$("#estatusfiltro").val();
    RestFullRequest("_Rest/Seguimiento.php", variable, "datagridPendientes");
    paginador();
}

function cargaCatalogos()
{
    var variable = "accion=cargaCatalogos";
    RestFullRequest("_Rest/Seguimiento.php", variable, "rellenaFormulario");
}

function rellenaFormulario(response)
{
    $.each(response, function(index, value)
    {
        $("#tipoSeguimiento").append("<option  value="+value['TIPOSEGUIMIENTO.ID']+">"+value['TIPOSEGUIMIENTO.DESCRIPCION']+"</option>");
    });


}

$("#agregarSeguimiento").on("click", function()
{
    var variable = "accion=save&"+$("#FormSeguimiento").serialize();
    RestFullRequest("_Rest/Seguimiento.php", variable, "guardarSeguimiento");
});

function guardarSeguimiento(response)
{
    $("#cliente input").not("input[type=hidden]").val("");
    $("#cliente select option:first").attr("selected", "selected");
    $("#cliente textarea").val("");

    actualizaDatagrid();

    var variable = "accion=buscarSeguimietno&id="+$("#FormSeguimiento #id").val();
    RestFullRequest("_Rest/Seguimiento.php", variable, "cargarSeguimiento");

    //actualizaDatagrid();
    //$("#cliente").modal("hide");
}

$("#btn_agregar").on("click", function()
{
    var id = 0;
    var texto = "";
    $("#dataseguimiento tr input[type=checkbox]:checked").each(function()
    {
        $("#datos").html("");
        var tabla = $("<table class='table table-bordered'></table>");
        var linea = $("<tr></tr>");
        var celda1 = $("<td>Contacto: "+$(this).parents('tr').find('td:eq(0)').html()+"</td>");
        var celda2 = $("<td>Empresa: "+$(this).parents("tr").find("td:eq(1)").html()+"</td>");
        var celda3 = $("<td>Estatus: "+$(this).parents("tr").find("td:eq(2)").html()+"</td>");
        //var celda4 = $("<td>Estatus: "+$(this).parents("tr").find("td:eq(6)").html()+"</td>");
        linea.append(celda1);
        linea.append(celda2);
        linea.append(celda3);
        //linea.append(celda4);
        tabla.append(linea);

        $("#datos").append(tabla);

        id = $(this).parents("tr").data("fila");
        /*texto = "";
        texto +="Contacto: "+$(this).parents("tr").find("td:eq(0)").html()+"<br>";
        texto +="Empresa: "+$(this).parents("tr").find("td:eq(1)").html()+"<br>";
        texto +="Segmento: "+$(this).parents("tr").find("td:eq(2)").html()+"<br>";
        texto +="Estatus: "+$(this).parents("tr").find("td:eq(6)").html()+"<br>";*/
    });
    if(id > 0)
    {
        //$("#datos").html(texto);
        $("#FormSeguimiento #id").val(id);
        var variable = "accion=buscarSeguimietno&id="+id;
        RestFullRequest("_Rest/Seguimiento.php", variable, "cargarSeguimiento");
    }else
    {
        alert("Debe de seleccionar un cliente");
    }
    /*$("#cliente").modal("show");
    $("#Titulo_Modulo").text("Seguimiento");
    $("#cliente input").val("");
    $("#cliente select option:first").attr("selected", "selected");*/

});

function cargarSeguimiento(Response)
{
    var aux = 0;
    $("#cliente").modal("show");
    $("#listaSeguimiento").html("");
    $.each(Response, function(index, value)
    {
        aux++;
        var color = "#EFEFEF";
        if(aux%2 == 0)
        color = "";

        var lista = $("<tr style = 'background-color:"+color+"'><td>Fecha: "+value['SEGUIMIENTOCLIENTESCALL.FECHACONTACTO']+"</td><td>Seguimiento: "+value['TIPOSEGUIMIENTO.DESCRIPCION']+"<br>"+value['SEGUIMIENTOCLIENTESCALL.RESULTADO']+"</td></tr>");
        $("#listaSeguimiento").append(lista);
    });


    if(aux == 0)
    {
        $("#listaSeguimiento").html("<tr><td colspan='2'>NO SE ENCUENTRAN REGISTRO</td></tr>");
    }

}

$(document).ready(function(e) {
    actualizaDatagrid();
    cargaCatalogos();
    actualizaProcesos();
    $("#call").find("a").click();
});


/*  Empieza la funcionalidad */
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
    $.each(Response, function(index, value)
    {
        $("#id").val(value["CLIENTESCALL.ID"]);
        $("#nombreEmpresa").val(value["CLIENTESCALL.NOMBREEMPRESA"]);
        $("#pagina").val(value["CLIENTESCALL.PAGINAWEB"]);
        console.log(value['CLIENTESCALL.EMAILING']);
        if(value['CLIENTESCALL.EMAILING'] == "1"){
            $("#emailing").attr("checked", true);
            console.log("entra");
        }else
            $("#emailing").attr("checked", false);


        $("#direccion").val(value["DIRECCIONCLIENTESCALL.DIRECCION"]);
        $("#observacion").val(value["CLIENTESCALL.OBSERVACION"]);
        $("#nombre").val(value["CLIENTESCALL.NOMBRE"]);
        $("#cargo").val(value["CLIENTESCALL.CARGO"]);
        $("#telefonofijo").val(value["CONTACTOCLIENTESCALL.TELEFONOFIJO"]);
        $("#extension").val(value["CONTACTOCLIENTESCALL.EXTENSION"]);
        $("#telefonomovil").val(value["CONTACTOCLIENTESCALL.TELEFONOMOVIL"]);
        $("#correo").val(value["CONTACTOCLIENTESCALL.CORREO"]);
        $("#horario").val(value["CONTACTOCLIENTESCALL.HORARIO"]);
        $("#tipoCliente").val(value["CLIENTESCALL.IDTIPOCLIENTE"]);
        $("#clase").val(value["CLIENTESCALL.IDCLASIFICACION"]);
        $("#clasificacion").val(value["CLIENTESCALL.IDSEGMENTO"]);
        $("#estatusSeguimiento").val(value["CLIENTESCALL.IDESTATUSSEGUIMIENTO"]);
    });
    $("#cliente").modal("show");
    $("#Titulo_Modulo").text("Editar Prospecto / Cliente");
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
    RestFullRequest("_Rest/Seguimiento.php", variable, "creaPaginador");

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

function activaseguimiento(valor, id)
{
    if(valor  == 1)
    {
        var variable = "accion=activaSeguimiento&id="+id;
        RestFullRequest("_Rest/Seguimiento.php", variable, "actualizaDatagrid");
    }else if(valor == 2)
    {
        var variable = "accion=desactivaSeguimiento&id="+id;
        RestFullRequest("_Rest/Seguimiento.php", variable, "actualizaDatagrid");
    }
}

$("#btn_reporte").on("click", function()
{
    window.open("reportes/call/reporte_call.php", "_black");
});


/*  */
/*  */

/**
 * Created by SALUD on 25/09/15.
 */
