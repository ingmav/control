/**
 * Created by SALUD on 18/05/15.
 */
var paginacion = 1;
var realizados = 0;
$("#guardaRequisicion").on("click", function()
{

    if($("#id").val() > 0)
    {
        var variable = "accion=update&"+$("#FormRequisicion").serialize();
        RestFullRequest("_Rest/Requisiciones.php", variable, "agregaRegistro");
    }else
    {
        var variable = "accion=save&"+$("#FormRequisicion").serialize();
        RestFullRequest("_Rest/Requisiciones.php", variable, "agregaRegistro");
    }
});

function requisicionesrealizadas()
{
    if(realizados == 0)
    {
        realizados = 1;
        actualizaDatagrid();
    }else
    {
        realizados = 0;
        actualizaDatagrid();
    }
}

function agregaRegistro(response)
{
    $("#myModal").modal("hide");
    actualizaDatagrid();
}

$("#agregar").on("click", function()
{
    $("#myModal").modal("show");
    limpiaForm($("#FormRequisicion"));
});

$("#borrar").on("click", function()
{
    var contador = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
        contador++;
    });

    if(contador > 0)
    {
        if(confirm("¿REALMENTE DESEA ELIMINAR EL/LOS REGISTROS?"))
        {
            var variable = "accion=eliminar&"+$("#FormDatagrid").serialize();
            RestFullRequest("_Rest/Requisiciones.php", variable, "actualizaDatagrid");
        }
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

$("#modificar").on("click", function()
{

    var contador = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
        contador++;
    });


    if(contador > 0)
    {
        var variable = "accion=modificar&"+$("#FormDatagrid").serialize();
        RestFullRequest("_Rest/Requisiciones.php", variable, "CargaRequisicion");
        $("#myModal").modal("show");
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

function CargaRequisicion(response)
{
    $("#id").val(response[0].ID);
    $("#fechaSolicitud").val(response[0].FECHA);
    $("#material").val(response[0].MATERIAL);
    $("#cantidad").val(currency(response[0].CANTIDAD,2,"."));
    $("#medida").val(response[0].UNIDADMEDIDA);
    $("#importe").val(currency(response[0].IMPORTE,2,"."));
    $("#proveedor").val(response[0].PROVEEDOR);
    $("#cliente").val(response[0].CLIENTE);
    $("#estatus option[value="+response[0].ESTATUS+"]").prop("selected", true);

}

function actualizaDatagrid()
{
    var variable = "accion=index&"+$("#FormCotizacion").serialize()+"&realizados="+realizados+"&page="+paginacion+"&clientefiltro="+$("#clientefiltro").val()+"&estatusfiltro="+$("#estatusfiltro").val();;
    RestFullRequest("_Rest/Requisiciones.php", variable, "datagridCotizacion");
}
function pages(Obj, value)
{
    $(".pagination li").removeClass("active");
    $(Obj).addClass("active");
    paginacion = value;
    actualizaDatagrid();
}



function limpiaForm(Formulario)
{
    $(Formulario).find('select').each(function(index, element) {
        $(this).find("option").eq(0).prop('selected', true);
    });

    $(Formulario).find("input[type=text]").val("");
    $(Formulario).find("textArea").val("");
    $(Formulario).find("input[type=text]").val("");
    $(Formulario).find("input").val("");
}

function datagridCotizacion(response)
{
    actualizaProcesos();
    paginador();
    var datagrid = $("#data");
    datagrid.find("tr").remove();
    var contador = 0;
    $.each(response, function(index, value)
    {
        linea = $("<tr></tr>");
        var campos = "";
        campos += "<td>"+value['REQUISICION.ID']+"</td>";
        campos += "<td>"+value['REQUISICION.FECHA']+"</td>";
        campos += "<td>"+value['REQUISICION.PROVEEDOR']+"</td>";
        campos += "<td>"+currency(value['REQUISICION.CANTIDAD'],2,".")+" "+value['REQUISICION.UNIDADMEDIDA']+" "+value['REQUISICION.MATERIAL']+"</td>";
        campos += "<td>"+currency(value['REQUISICION.IMPORTE'], 2, ".")+"</td>";

        campos += "<td>"+value['REQUISICION.CLIENTE']+"</td>";
        var Estatus = "PENDIENTE";
        if(value['REQUISICION.ESTATUS'] == 2)
            Estatus = "REALIZADO";

        campos += "<td>"+Estatus+"</td>";



        //console.log(value['Cotizacion.ID']);
        campos += "<td align='center'><input type='checkbox' name='id[]' value='"+value['REQUISICION.ID']+"'></td>";
        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
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

function paginador()
{
    var variable = "accion=counter&empresa=1&realizados="+realizados;
    RestFullRequest("_Rest/Requisiciones.php", variable, "creaPaginador");

}

$(document).ready(function(e) {
    actualizaDatagrid();
    //setInterval(actualizaDatagrid,  900000);
    $("#control").find("a").click();

});

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

function reporte()
{

    $("#FormDatagrid").attr("action","ReporteRequerimientos.php");
    $("#FormDatagrid").attr("method","POST");
    $("#FormDatagrid").attr("target","_blank");
    $("#FormDatagrid").submit();
    $("#FormDatagrid").attr("action","");
    $("#FormDatagrid").attr("method","");
    $("#FormDatagrid").attr("target","");
    //$("#FormDatagrid").attr("src","").attr("target","");
}