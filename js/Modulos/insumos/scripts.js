$(document).ready(function()
{
	cargar_formulario();
	$("#menu_inventario").find("a").click();
	load_lista_insumos();
});

function load_lista_insumos()
{
	$("#FORM_INSUMOS").find("input").val("");
	var variable = "accion=insumos&familia="+$("#catalogo").val();
    RestFullRequest("_Rest/insumos.php", variable, "recarga_insumos");
    //cargaFormularios();
    
}

function recarga_insumos(response)
{
	$("#lista_insumos").html("");
	console.log(response);
	$.each(response, function(index, value)
	{
		var linea 	= "<tr>";
		celda_1 	= "<td>"+value['MS_FAMILIA.DESCRIPCION']+"</td>";	
		celda_2 	= "<td>"+value['MS_ARTICULOS.NOMBRE_ARTICULO']+"</td>";	
		celda_3 	= "<td>"+value['MS_ARTICULOS.CANTIDAD_MINIMA']+"</td>";	
		if(value['MS_ARTICULOS.UNITARIO'] == 1)
			celda_4 	= "<td>PIEZA</td>";	
		else
			celda_4 	= "<td>M2</td>";	
		celda_5 	= "<td><button type='button' class='btn btn-danger' onclick='eliminar_insumo("+value['MS_ARTICULOS.ID']+")'><i class='fa fa-close'></i></button><button type='button' class='btn btn-primary' onclick='editar_insumo("+value['MS_ARTICULOS.ID']+")'><i class='fa fa-edit'></i></button></td>";	

		linea		+= celda_1+celda_2+celda_3+celda_4+celda_5;
		linea		+= "</tr>";

		$("#lista_insumos").append(linea);
	});
}

function eliminar_insumo(id)
{
	if(confirm("¿Realmente desea eliminar el proveedor?"))
	{
		var variable = "accion=eliminar_insumo&id="+id;
	    RestFullRequest("_Rest/insumos.php", variable, "load_lista_insumos",2);
	}
}

function cargar_formulario()
{
	var variable = "accion=formularios";
    RestFullRequest("_Rest/Almacen.php", variable, "Formulario");
}

function Formulario(Response)
{
	
	$("#familia").html("<option value='0'>FAMILIA</option>");
	$("#catalogo").html("<option value='0'>FAMILIA</option>");
	
	$.each(Response['CATEGORIA'], function(index, value)
	{
		$("#familia").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		$("#catalogo").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
	});

	
}

function guarda_insumo()
{
	var variable = "accion=guardar_insumo&"+$("#FORM_INSUMO").serialize();
    RestFullRequest("_Rest/Almacen.php", variable, "limpieza_form",1);      
}

function estado_unitario(valor)
{
	if(valor == 1)
	{
		$("#ancho").attr("disabled", true).val("0.00");	
		$("#largo").attr("disabled", true).val("0.00");	
		$("#u_paquete").attr("disabled", false);	
		$("#u_paquete").attr("disabled", false);	
		$("#u_venta").val("PIEZA");	
	}else
	{
		$("#ancho").attr("disabled", false);	
		$("#largo").attr("disabled", false);
		$("#u_paquete").attr("disabled", true);	
		$("#u_venta").val("");	
	}

}

function limpieza_form()
{
	$("#modal_insumo").modal("hide");
	$("#FORM_INSUMO").find("input").val("");
	$("#FORM_INSUMO").find("select").val("1");

	load_lista_insumos();
}

function editar_insumo(id)
{
	$("#modal_insumo").modal("show");
	var variable = "accion=carga_insumo&id="+id;
    RestFullRequest("_Rest/insumos.php", variable, "cargando_datos");
}

function cargando_datos(response)
{
	 var formulario = $("#FORM_INSUMO");
	 var resultado = response[0];
	 formulario.find("input").val("");
	 formulario.find("#id").val(resultado.ID);
	 formulario.find("#familia").val(resultado.MS_FAMILIA_ID);
	 formulario.find("#insumo").val(resultado.NOMBRE_ARTICULO);
	 formulario.find("#minimo").val(resultado.CANTIDAD_MINIMA);
	 formulario.find("#unitario").val(resultado.UNITARIO);
	 formulario.find("#ancho").val(resultado.ANCHO);
	 formulario.find("#u_paquete").val(resultado.PAQUETE);
	 formulario.find("#u_venta").val(resultado.UNIDAD_VENTA);
	 formulario.find("#u_compra").val(resultado.UNIDAD_COMPRA);
	 formulario.find("#largo").val(resultado.LARGO);

	 if(resultado.UNITARIO == 1)
	 {
	 	$("#ancho").attr("disabled", true);	
	 	$("#largo").attr("disabled", true);	
		$("#u_venta").val("PIEZA").attr("readonly", true);	
		$("#u_paquete").attr("disabled", false);	
		
	 }else
	 {
	 	$("#ancho").attr("disabled", false);	
	 	$("#largo").attr("disabled", false);		
	 	$("#u_paquete").attr("disabled", true);	
		$("#u_venta").val(resultado.UNIDAD_VENTA).attr("disabled", false);	
		
	 }
	 
}

$("#agregar").on("click", function()
{
	$("#modal_insumo").modal("show");
	$("#FORM_INSUMO").find("input").val("");
	$("#unitario").val(1);
	$("#ancho").attr("disabled", true).val("0.00");	
 	$("#largo").attr("disabled", true).val("0.00");	
	$("#u_venta").val("PIEZA").attr("readonly", true);	
	//$("#u_venta");
});



