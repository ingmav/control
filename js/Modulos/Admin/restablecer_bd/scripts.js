function actualizaDatagrid(response)
{

    var variable = "accion=index&"+$("#datagridCaja").serialize();
    RestFullRequest("_Rest/Admin/RestablecerDB.php", variable, "datagridDB");
}

function datagridDB(response)
{
    //console.log(response);
    var datagrid = $("#data");
    datagrid.find("tr").remove();
    var contador = 0;
    $.each(response, function(index, value)
    {
        linea = $("<tr></tr>");
        var campos = "";
        campos += "<td>"+value['DEPARTAMENTO']+"</td>";
        campos += "<td>"+value['CONTEO'] / 2+"</td>";
    
        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
    {
        datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
        if(($("#btn_restablecer").length > 0) == true)
            $("#btn_restablecer").remove(); 
    }
    else if(contador != 0)
    {
        if(($("#btn_restablecer").length > 0) == false)
            $("#FormDatagrid").append("<button type='button' class='btn btn-primary' id='btn_restablecer' onclick='restablecer()'>Restablecer</button>");
        
    }
}


$(document).ready(function(e) {
    actualizaDatagrid();
    $("#admin").find("a").click();
});

function restablecer()
{
    var variable = "accion=reestablecer";
    RestFullRequest("_Rest/Admin/RestablecerDB.php", variable, "actualizaDatagrid", 1);
}