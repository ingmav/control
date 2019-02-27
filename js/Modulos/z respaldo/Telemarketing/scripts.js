var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;

function datagridPendientes(response)
{
    console.log(response);
    var datagrid = $("#dataTelemarketing");
    datagrid.find("tr").remove();
    var contador = 0;

    $.each(response, function(index, value)
    {

        var campos = "";
        var index = 0;
        var id;
        id = value['FECHASEGUIMIENTOCALL.ID'];

        //campos += "<td>"+value['TIPOCLIENTESCALL.DESCRIPCION']+"</td>";
        campos += "<td>"+value['FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO']+"</td>";
        campos += "<td>"+value['FECHASEGUIMIENTOCALL.CLIENTESCALL']+"</td>";

        //campos += "<td>TELEFONO (1): "+value['CONTACTOCLIENTESCALL.TELEFONO1']+" <BR>TELEFONO (2): "+value['CONTACTOCLIENTESCALL.TELEFONO2']+"</td>";
        campos += "<td>"+value["FECHASEGUIMIENTOCALL.DESCRIPCION"]+"</td>";
        campos += "<td>"+value["FECHASEGUIMIENTOCALL.OPERADOR"]+"</td>";

        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
        var prioridad;

        campos +="<td><input type='checkbox'></td>"


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
    RestFullRequest("_Rest/Telemarketing.php", variable, "datagridPendientes");
    paginador();
}

function cargaCatalogos()
{
    var variable = "accion=cargaCatalogos";
    RestFullRequest("_Rest/Telemarketing.php", variable, "rellenaFormulario");
}

function rellenaFormulario(response)
{

    $.each(response, function(index, value)
    {
        $("#clientecall").append("<option  value="+value['CLIENTESCALL.ID']+">"+value['CLIENTESCALL.NOMBRE']+"</option>");
    });


}

$("#guardarCita").on("click", function()
{
    if($("#id").val() >0)
    {
        var variable = "accion=update&"+$("#FormTelemarketing").serialize();
        RestFullRequest("_Rest/Telemarketing.php", variable, "guardarCita");
    }else
    {
        var variable = "accion=save&"+$("#FormTelemarketing").serialize();
        RestFullRequest("_Rest/Telemarketing.php", variable, "guardarCita");
    }

});

function guardarCita(response)
{
    actualizaDatagrid();
    $("#cliente").modal("hide");
}

$("#btn_agregar").on("click", function()
{
    $("#cliente").modal("show");
    $("#Titulo_Modulo").text("Telemarketing");
    $("#cliente input").val("");
    $("#cliente select option:first").attr("selected", "selected");

});
$(document).ready(function(e) {
    actualizaDatagrid();
    //cargaCatalogos();
    actualizaProcesos();
    $("#call").find("a").click();
});


/*  Empieza la funcionalidad */
$("#editarCliente").on("click", function()
{
    var id;
    $("#dataTelemarketing tr input[type=checkbox]:checked").each(function()
    {
        id = $(this).parents("tr").data("fila");
    });

    var variable = "accion=cargaCliente&id="+id;
    RestFullRequest("_Rest/Telemarketing.php", variable, "cargarCliente");
});

function cargarCliente(Response)
{

    $.each(Response, function(index, value)
    {
        $("#id").val(value["FECHASEGUIMIENTOCALL.ID"]);
        $("#fecha").val(value["FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO"]);
        $("#operador").val(value["FECHASEGUIMIENTOCALL.OPERADOR"]);
        $("#clientecall").val(value["FECHASEGUIMIENTOCALL.CLIENTESCALL"]);
        $("#descripcion").val(value["FECHASEGUIMIENTOCALL.DESCRIPCION"]);

    });
    $("#cliente").modal("show");
    $("#Titulo_Modulo").text("Editar Prospecto / Cliente");
}

$("#bajaCliente").on("click", function()
{
    if(confirm("¿Realmente Desea dar de Baja el Registro?"))
    {
        var id;
        $("#dataTelemarketing tr input[type=checkbox]:checked").each(function()
        {
            id = $(this).parents("tr").data("fila");
        });

        var variable = "accion=bajaCliente&id="+id;
        RestFullRequest("_Rest/Telemarketing.php", variable, null);

        alert("SE HA DADO DE BAJA CORRECTAMENTE EL REGISTRO");
        actualizaDatagrid();
    }
});

function paginador()
{
    var variable = "accion=counter&page="+1+"&filtrocliente="+$("#clientefiltro").val()+"&filtrotipocliente="+$("#estatusfiltro").val();

    RestFullRequest("_Rest/Telemarketing.php", variable, "creaPaginador");

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
/*  */

/**
 * Created by SALUD on 25/09/15.
 */
