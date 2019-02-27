function actualizaDatagrid()
{
	var variable = "accion=index&filtroarticulo="+$("#articulofiltro").val()+"&filtro_grupo="+$("#filtro_grupo").val();
    RestFullRequest("_Rest/inventario_microsip.php", variable, "datagrid");
}

function datagrid(response)
{
	
	var datagrid = $("#datagrid");
	datagrid.find("tr").remove();
	contador = 0;
	$.each(response, function(index, value)
	{
		
		linea = $("<tr data-fila='"+value['ID']+"'></tr>");
		var campos = "";
		campos += "<td>"+value['CLAVE']+"</td>";
        campos += "<td>"+value['NOMBRE']+"</td>";
        var color  = "";
        if(parseFloat(value['CANTIDAD_INSUMOS']) > 0)
        	color = "btn-primary";
        campos += "<td align='center'><button class='btn btn-strech "+color+"'>"+value['CANTIDAD_INSUMOS']+"</button></td>";
        campos += "<td align='center'><button class='btn btn-strech btn-primary' type='button' onclick='relacionar(this, "+value['ID']+")'><i>i</i></button></td>";
        campos += "<td align='center'><button class='btn btn-strech btn-danger' type='button' onclick='eliminar("+value['ID']+")'><i class='fa fa-close'></i></button></td>";
   
		linea.append(campos);
		
		datagrid.append(linea);
		contador++;
	});
	if(contador == 0)
		datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function relacionar(obj, id)
{
	$("#acciones").modal("show");
	$("#dato_articulo").text($(obj).parents("tr").find("td:eq(1)").text());
	$("#id_articulo").val(id);


	var variable = "accion=carga_insumos&ID="+id;
    RestFullRequest("_Rest/inventario_microsip.php", variable, "insumos_relacionados");
}

function eliminar(id)
{
	var variable = "accion=eliminar_articulo&ID="+id;
    RestFullRequest("_Rest/inventario_microsip.php", variable, "actualizaDatagrid");
}

function insumos_relacionados(response)
{
	
	var datagrid = $("#tabla_datos");
	datagrid.find("tr").remove();
	contador = 0;
	$.each(response, function(index, value)
	{
		
		linea = $("<tr data-fila='"+value['ID']+"'></tr>");
		var campos = "";
		campos += "<td>"+value['ID']+"</td>";
        campos += "<td>"+value['NOMBRE']+"</td>";
        campos += "<td>"+value['CANTIDAD']+"</td>";
        campos += "<td>"+value['BAJA']+"</td>";
        campos += "<td><input type='checkbox' name='bajas[]' value="+value['ID']+"></td>";
        
		linea.append(campos);
		
		datagrid.append(linea);
		contador++;
	});
	if(contador == 0)
		datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");

	actualizaDatagrid();
}

function agregar_insumo()
{
	$("#add_insumos").modal("show");
}

function carga_catalogo_grupo()
{
	var variable = "accion=catalogo_grupos";
    RestFullRequest("_Rest/inventario_microsip.php", variable, "carga_grupo_insumos");
	
}

function guardaInsumo()
{
	
	var variable = "accion=agregar_insumo_catalogo&"+$("#form_insumo").serialize();
    RestFullRequest("_Rest/inventario_microsip.php", variable, "actualiza_insumo");
}

function actualiza_insumo(response)
{
	$("#form_insumo")[0].reset();
	$("#add_insumos").modal("hide");
	carga_catalogo_grupo();
}

function carga_grupo_insumos(response)
{
	$("#grupo_insumo").html("");
	$("#baja_insumo").html("");
	$("#filtro_grupo").html("<option value=''>TODOS</option>");
        
    $.each(response['GRUPOS_INSUMOS'], function(index, value)
    {
        $("#grupo_insumo").append("<option value='"+value['ID']+"'>"+value['DESCRIPCION']+"</option>");
    });

        
    $.each(response['LINEAS'], function(index, value)
    {
        $("#filtro_grupo").append("<option value='"+value['ID']+"'>"+value['DESCRIPCION']+"</option>");
    });

    $.each(response['TIPO_BAJA'], function(index, value)
    {
        $("#baja_insumo").append("<option value='"+value['ID']+"'>"+value['DESCRIPCION']+"</option>");
    });

}

function relacionar_articulo_insumo()
{
	var variable = "accion=relacion_articulo&"+$("#form").serialize();
    RestFullRequest("_Rest/inventario_microsip.php", variable, "actualiza_datos");
	
}

function quitar_articulo_insumo()
{
	var variable = "accion=quitar_articulo&"+$("#form").serialize();
    RestFullRequest("_Rest/inventario_microsip.php", variable, "actualiza_datos");
	
}


function actualiza_datos(response)
{

	var variable = "accion=carga_insumos&ID="+response['ID'];
    RestFullRequest("_Rest/inventario_microsip.php", variable, "insumos_relacionados");
}

$(document).ready(function(e) {
	actualizaDatagrid();
	carga_catalogo_grupo();
    $("#menu_inventario").find("a").click();
});