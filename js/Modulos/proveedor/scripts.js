$(document).ready(function()
	{
		//actualizaDatagrid();
		//cargaFormularios();
		$("#menu_inventario").find("a").click();
		load_lista_proveedores();
	});

function load_lista_proveedores()
{
	$("#FORM_PROVEEDOR").find("input").val("");
	var variable = "accion=proveedor";
    RestFullRequest("_Rest/Almacen.php", variable, "recarga_proveedores");
    //cargaFormularios();
    
}


function recarga_proveedores(Response)
{
	$("#lista_proveedores").html("");
	$.each(Response, function(index, value)
	{
		console.log(value['CUENTA']);
		var linea 	= "<tr>";
		celda_1 	= "<td>"+value['NOMBRE']+"</td>";	
		celda_2 	= "<td>"+value['CONTACTO']+"</td>";	
		celda_3 	= "<td>"+value['TELEFONO']+"</td>";	
		celda_4 	= "<td>"+value['CONDICION_PAGO']+"</td>";	
		celda_5 	= "<td><p>"+value['CUENTA']+"</p></td>";	
		celda_6 	= "<td><button type='button' class='btn btn-danger' onclick='eliminar_proveedor("+value['ID']+")'><i class='fa fa-close'></i></button><button type='button' class='btn btn-primary' onclick='editar_proveedor("+value['ID']+")'><i class='fa fa-edit'></i></button></td>";	

		linea		+= celda_1+celda_2+celda_3+celda_4+celda_5+celda_6;
		linea		+= "</tr>";

		$("#lista_proveedores").append(linea);
	});
}

function eliminar_proveedor(id)
{
	if(confirm("¿Realmente desea eliminar el proveedor?"))
	{
		var variable = "accion=eliminar_proveedor&id="+id;
	    RestFullRequest("_Rest/Almacen.php", variable, "load_lista_proveedores",2);
	}
}

$("#agregar").on("click", function()
{
	$("#modal_proveedor").modal("show");
	$("#FORM_PROVEEDOR").find("input").val("");
});

function guarda_proveedor()
{
	var variable = "accion=guardar_proveedor&"+$("#FORM_PROVEEDOR").serialize();
    RestFullRequest("_Rest/Almacen.php", variable, "load_lista_proveedores",1);
    if($("#FORM_PROVEEDOR #id").val() > 0)
    	$("#modal_proveedor").modal("hide");
    
}

function editar_proveedor(id)
{
	$("#modal_proveedor").modal("show");
	var variable = "accion=carga_proveedor&id="+id;
    RestFullRequest("_Rest/Almacen.php", variable, "cargando_datos");
}

function cargando_datos(response)
{
	 var formulario = $("#FORM_PROVEEDOR");
	 var resultado = response[0];
	 
	 formulario.find("#id").val(resultado.ID);
	 formulario.find("#nombre_proveedor").val(resultado.NOMBRE);
	 formulario.find("#direccion").val(resultado.DIRECCION);
	 formulario.find("#telefono").val(resultado.TELEFONO);
	 formulario.find("#condicion").val(resultado.CONDICION_PAGO);
	 formulario.find("#contacto").val(resultado.CONTACTO);
	 formulario.find("#descripcion").val(resultado.DESCRIPCION);
	 formulario.find("#cuenta").val(resultado.CUENTA);
}
