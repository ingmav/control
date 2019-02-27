/**
 * Created by SALUD on 18/05/15.
 */
var paginacion = 1;
var realizados = 0;
$("#guardagarantia").on("click", function()
{
    var folio = $("#foliogarantia").val();
    
    if(folio.replace(/\s/g, "").length > 0 )
    {
      if($("#id").val() > 0)
      {
          var variable = "accion=update&"+$("#FormGarantia").serialize();
          RestFullRequest("_Rest/Garantia.php", variable, "agregaRegistro", 1);
      }else
      {
          var variable = "accion=save&"+$("#FormGarantia").serialize();
          RestFullRequest("_Rest/Garantia.php", variable, "agregaRegistro", 1);
      }
    }else {
      alert("Debe de ingresar el numero de factura o remisión");
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
    limpiaForm($("#FormGarantia"));
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
            RestFullRequest("_Rest/Garantia.php", variable, "actualizaDatagrid", 2);
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
        RestFullRequest("_Rest/Garantia.php", variable, "CargaGarantia");
        $("#myModal").modal("show");
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

function CargaGarantia(response)
{
    $("#id").val(response[0].ID);
    $("#foliogarantia").val(response[0].FOLIO);
    $("#material").val(response[0].MATERIAL);
    $("#cantidad").val(response[0].CANTIDAD);
    $("#medida").val(response[0].UNIDADMEDIDA);
    $("#cliente").val(response[0].CLIENTE);
    $("#motivo").val(response[0].MOTIVO);
    $("#monto").val(response[0].MONTO);

}

function actualizaDatagrid()
{
    var variable = "accion=index&"+$("#FormCotizacion").serialize()+"&page="+paginacion+"&clientefiltro="+$("#clientefiltro").val()+"&fecha_inicio="+$("#fecha_inicio").val()+"&fecha_fin="+$("#fecha_fin").val();
    RestFullRequest("_Rest/Garantia.php", variable, "datagridCotizacion");
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
    //console.log(response);
    //actualizaProcesos();
    paginador();
    var datagrid = $("#data");
    datagrid.find("tr").remove();
    var contador = 0;
    $.each(response, function(index, value)
    {
      var pago = "";
       

        linea = $("<tr></tr>");
        var campos = "";
        campos += "<td>"+value['GARANTIA.FOLIO']+"</td>";
        campos += "<td>"+value['GARANTIA.FECHA']+"</td>";
        campos += "<td>"+value['GARANTIA.CLIENTE']+"</td>";
        campos += "<td>"+value['GARANTIA.CANTIDAD']+" "+value['GARANTIA.UNIDADMEDIDA']+" "+value['GARANTIA.MATERIAL']+"</td>";
        campos += "<td>"+value['OPERADOR.ALIAS']+"</td>";

        campos += "<td align='center'><input type='checkbox' name='id[]' value='"+value['GARANTIA.ID']+"'></td>";
        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function creaPaginador(Response)
{
    //console.log(Response);
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
    var variable = "accion=index&"+$("#FormCotizacion").serialize()+"&page="+paginacion+"&clientefiltro="+$("#clientefiltro").val()+"&fecha_inicio="+$("#fecha_inicio").val()+"&fecha_fin="+$("#fecha_fin").val();
    RestFullRequest("_Rest/Garantia.php", variable, "creaPaginador");

}

$(document).ready(function(e) {
    actualizaDatagrid();
    //setInterval(actualizaDatagrid,  900000);
    $("#operacion").find("a").click();

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

    $("#buscador").attr("action","reportes/garantia/ReporteGarantias.php");
    $("#buscador").attr("method","POST");
    $("#buscador").attr("target","_blank");
    $("#buscador").submit();
    $("#buscador").attr("action","");
    $("#buscador").attr("method","");
    $("#buscador").attr("target","");
    //$("#FormDatagrid").attr("src","").attr("target","");
}
