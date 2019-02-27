var paginacion = 1;
var realizados = 0;


$("#Agregar").on("click", function()
{
    $("#AgregarUser").modal("show");
    $("#FormUser").find("input[type=text]").val("");
    $("#FormUser").find("input[type=checkbox]").prop("checked", false);
});

$("#eliminar").on("click", function()
{
    var id = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function()
    {
        id = $(this).val();
    });

    if(confirm("¿Realmente desea eliminar el usuario?"))
    {
        var variable = "accion=deleteUser&id="+id;
        RestFullRequest("_Rest/Admin/User.php", variable, "actualizaDatagrid");
    }
});

$("#editar").on("click", function()
{
    var id = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function()
    {
        id = $(this).val();
    });

    if(id!=0)
    {
        var variable = "accion=findUser&id="+id;
        RestFullRequest("_Rest/Admin/User.php", variable, "CargaUser");
    }
});

function CargaUser(Response)
{
    $("#AgregarUser").modal("show");
    $.each(Response,function($index, $value)
    {
        $("#id").val($value["ID"]);
        $("#nombre").val($value["NOMBRE"]);
        $("#alias").val($value["ALIAS"]);

        if($value["SHOPPING"]!=0)
            $("#caja").prop("checked","true");
        if($value["COTIZACION"]!=0)
            $("#cotizacion").prop("checked","true");
        if($value["EXTRA"]!=0)
            $("#extra").prop("checked","true");
        if($value["DOCUMENTOS"]!=0)
            $("#seleccion").prop("checked","true");
        if($value["DISENO"]!=0)
            $("#diseno").prop("checked","true");
        if($value["IMPRESION"]!=0)
            $("#impresion").prop("checked","true");
        if($value["INSTALACION"]!=0)
            $("#instalacion").prop("checked","true");
        if($value["ENTREGA"]!=0)
            $("#entrega").prop("checked","true");
        if($value["MAQUILAS"]!=0)
            $("#maquilas").prop("checked","true");
        if($value["FINALIZADOS"]!=0)
            $("#tablero").prop("checked","true");
        if($value["INVENTARIO"]!=0)
            $("#inventario").prop("checked","true");
        if($value["COBRO"]!=0)
            $("#cobros").prop("checked","true");
        if($value["CAPACIDAD"]!=0)
            $("#capacidad").prop("checked","true");

    });
}

$("#guardarUsuario").on("click",function()
{
    $("#AgregarUser").modal("hide");
    if($("#iid").val() == "")
    {
        var variable = "accion=saveUser&"+$("#FormUser").serialize();
        RestFullRequest("_Rest/Admin/User.php", variable, "actualizaDatagrid");
    }else
    {
        var variable = "accion=updateUser&"+$("#FormUser").serialize();
        RestFullRequest("_Rest/Admin/User.php", variable, "actualizaDatagrid");
    }

});
function actualizaDatagrid()
{
    var variable = "accion=index&"+$("#datagridCaja").serialize();
    RestFullRequest("_Rest/Admin/User.php", variable, "datagridUser");
}

function datagridUser(response)
{
    //actualizaProcesos();
    //paginador();
    var datagrid = $("#data");
    datagrid.find("tr").remove();
    var contador = 0;
    $.each(response, function(index, value)
    {
        linea = $("<tr></tr>");
        var campos = "";
        campos += "<td>"+value['NOMBRE']+"</td>";
        campos += "<td>"+value['ALIAS']+"</td>";
        campos += "<td><INPUT TYPE='checkbox' name='id' value='"+value['ID']+"'></td>";

        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}


$(document).ready(function(e) {
    actualizaDatagrid();
    //setInterval(actualizaDatagrid,  900000);
    $("#admin").find("a").click();
});/**
 * Created by SALUD on 14/06/15.
 */
