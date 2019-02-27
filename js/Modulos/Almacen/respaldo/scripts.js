	var id_general = 0;

	function actualizaDatagrid()
	{

		formatea_formularios();
		var familia = $("#filtro_grupo").val();
		var variable = "accion=index&familia="+familia;
        RestFullRequest("_Rest/Almacen.php", variable, "datagrid");
	}

	function formatea_formularios()
	{
		$("#FORM_INSUMO").find("select").val(1).find("input").val("");
	}

	function datagrid(response)
	{

		var contador = 0;
		var datagrid = $("#almacen");

		datagrid.find("tr").remove();

		$.each(response['ARTICULOS'], function(index, value)
		{
            var campos = "";
            if(value['SUGERIDA'] > 0)
            	linea = $("<tr style='background-color: rgba(200,0,0,0.1);'>");
            else
            	linea = $("<tr>");
           	
           	campos += "<td>"+value['ARTICULO']+"</td>";
            campos += "<td>"+value['INVENTARIO_INICIAL']+"</td>";
            campos += "<td>"+value['MS_INVENTARIO']+"</td>";
            campos += "<td>"+value['CANTIDAD_MINIMA']+"</td>";
            
            campos += "<td>"+value['SUGERIDA']+"</td>";
            campos += "<td>"+value['ACTUALIZACION']+"</td>";
            campos += "<td><button type='button' class='btn btn-success' onclick='baja_manual("+value['ARTICULO_ID']+")'><i class='fa fa-caret-square-o-down'></i></button></td>";

            linea.append(campos);
            datagrid.append(linea);

            contador++;

		});

		$("#monto_inventario").text(response['TOTAL']);

		if(contador == 0)
			datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
	}

	$(document).ready(function()
	{
		actualizaDatagrid();
		cargaFormularios();
		$("#menu_inventario").find("a").click();
	});

	function baja_manual(id)
	{
		id_general = id;
		var variable = "accion=ver_inventario&id="+id;
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_tabla_inventario",1);
        $("#modal_baja_manual").modal("show");
	}

	function cerrar_factura() {
		var variable = "accion=cerrar_factura";
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_tabla_inventario",1);
        
	}

	function actualiza_tabla_inventario(Response)
	{
		$("#lista_inventario_baja").html("");
		$.each(Response, function(index, value)
		{
			var dimension = "--- ";
			var lineal = 0;
			if(value['ANCHO']!=0 && value['LARGO'] != 0)
				dimension = "( "+value['ANCHO']+" * "+value['LARGO']+" ) "; 


			if(value['ANCHO']!=0)
				lineal = (value['CANTIDAD_RESTANTE'] / value['ANCHO']);
			else
				lineal = (value['CANTIDAD_RESTANTE']);

			var campos = "<tr>";
			campos += "<td>"+value['ID']+"</td>";
			campos += "<td>"+value['NOMBRE_ARTICULO']+"</td>";
			campos += "<td>"+dimension+"</td>";
			campos += "<td><input type='text' name='cantidad_"+value['ID']+"' value='"+parseFloat(lineal).toFixed( 2 )+"' class='form-control' onblur='calcula(this,"+value['ID']+", "+value['UNITARIO']+", "+value['ANCHO']+")'><input type='hidden' name='ids[]' value='"+value['ID']+"'></td>";
			campos += "<td><input type='text' name='dimension_"+value['ID']+"' id='id_"+value['ID']+"' value='"+value['CANTIDAD_RESTANTE']+"' class='form-control' readonly='readonly'></td>";
			campos += "<td><button type='button' class='btn btn-success' onclick='baja_completa("+value['ID']+")'><i class='fa fa-arrow-circle-down'></i></button</td>";
			campos += "</tr>";


			$("#lista_inventario_baja").append(campos);
		});
	}

	function calcula(obj, id, unitario, ancho)
	{
		if(unitario == 1)
		{
			var valor = $(obj).val();
			$("#id_"+id).val(valor);
		}else
		{
			var valor = $(obj).val();
			var dimension = valor * ancho;


			$("#id_"+id).val(parseFloat(dimension).toFixed( 2 ));
		}
	}


	function bajas()
	{
		var variable = "accion=baja&"+$("#form_almacen").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "actualizaDatagrid");
	}

	function baja_completa(id)
	{
		var variable = "accion=baja_articulo&id="+id;
        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");	
	}

	function recargar_baja_manual()
	{
		baja_manual(id_general);
		actualizaDatagrid();
	}

	function cargaFormularios()
	{
		var variable = "accion=formularios";
        RestFullRequest("_Rest/Almacen.php", variable, "Formulario");
	}

	function agregar_inventario()
	{
		$("#modal_inventario").modal("show");
		actualiza_lista_factura();
	}

	function baja_almacen()
	{
		
		var variable = "accion=baja_almacen&"+$("#FORM_ARTICULO_BAJA").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "actualizaDatagrid", 1);
        //$("#modal_baja_manual").modal("hide");
	}

	function agregar_almacen()
	{
		$("#modal_almacen_alta").modal("show");
	}


	function transferencia(){
		$("#modal_transferencia").modal("show");		
	}

	$("#categoria").change(function()
	{
		var variable = "accion=articulos&familia="+$(this).val();
        RestFullRequest("_Rest/Almacen.php", variable, "catalogoArticulo");
	});
	
	

	function catalogoArticulo(Response)
	{
		$("#articulo").html("");
		$.each(Response['ARTICULOS'], function(index, value)
		{
			$("#articulo").append("<option value='"+value['ID']+"'>"+value['DESCRIPCION']+"</option>");
		});
	}

	function Formulario(Response)
	{
		
		$("#campo_almacen").html("");
		$("#almacen_transferencia").html("");
		$("#filtro_almacen").html("");
		$("#proveedor").html("");
		$("#categoria").html("<option value='0'>CATEGORIA</option>");
		$("#familia").html("<option value='0'>FAMILIA</option>");
		$("#filtro_grupo").html("<option value='0'>FAMILIA</option>");

		$.each(Response['ALMACENES'], function(index, value)
		{
			$("#campo_almacen").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});

		$.each(Response['ALMACENES'], function(index, value)
		{
			$("#almacen_transferencia").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});

		$.each(Response['ALMACENES'], function(index, value)
		{
			$("#filtro_almacen").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});

		$.each(Response['PROVEEDOR'], function(index, value)
		{
			$("#proveedor").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});

		$.each(Response['CATEGORIA'], function(index, value)
		{
			$("#categoria").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});

		$.each(Response['CATEGORIA'], function(index, value)
		{
			$("#familia").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});

		$.each(Response['CATEGORIA'], function(index, value)
		{
			$("#filtro_grupo").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
		});
	}

	function guarda_proveedor()
	{
		var variable = "accion=guardar_proveedor&"+$("#FORM_PROVEEDOR").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "load_lista_proveedores",1);
        
	}

	function guarda_insumo()
	{
		var variable = "accion=guardar_insumo&"+$("#FORM_INSUMO").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "limpieza_form",1);      
	}

	function limpieza_form()
	{
		$("#modal_insumo").modal("hide");
		$("#FORM_INSUMO").find("input").val("");
		$("#FORM_INSUMO").find("select").val("1");

		actualizaDatagrid();
	}

	function load_lista_proveedores()
	{
		var variable = "accion=proveedor";
        RestFullRequest("_Rest/Almacen.php", variable, "recarga_proveedores");
        cargaFormularios();
        $("#FORM_PROVEEDOR").find("input").val("");
	}

	function actualiza_lista_factura()
	{
		var variable = "accion=actualiza_lista_factura";
        RestFullRequest("_Rest/Almacen.php", variable, "load_lista_factuas");

	}

	function recarga_proveedores(Response)
	{
		$("#lista_proveedores").html("");
		$.each(Response, function(index, value)
		{
			$("#lista_proveedores").append("<tr><td>"+value['NOMBRE']+"</td><td>"+value['CONTACTO']+"</td><td>"+value['TELEFONO']+"</td><td>"+value['CONDICION_PAGO']+"</td><td><button type='button' class='btn btn-danger' onclick='eliminar_proveedor("+value['ID']+")'><i class='fa fa-close'></i></button></td></tr>");
		});
	}

	function eliminar_proveedor(id)
	{
		var variable = "accion=elimina_proveedor&id="+id;
        RestFullRequest("_Rest/Almacen.php", variable, "load_lista_proveedores",2);
	}

	function load_lista_factuas(Response)
	{
		$("#lista_factura").html("");
		var total = 0;
		$.each(Response, function(index, value)
		{
			$("#lista_factura").append("<tr><td>"+value['FACTURA_COMPRA']+"</td><td>"+value['NOMBRE_ARTICULO']+"</td><td>"+value['REGISTROS']+"</td><td>"+value['UNIDADES']+"</td><td>"+value['PRECIO_UNITARIO']+"</td><td>"+value['PRECIO_COMPRA']+"</td></tr>");
			total = parseFloat(total) + parseFloat(value['PRECIO_COMPRA']);
		});
		$("#total_factura").text(total);
		resetFormFactura();
		actualizaDatagrid();
	}

	function btn_guardar_inventario()
	{
		
		var variable = "accion=guardar&"+$("#FORM_ALMACEN").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_lista_factura",1);
	}

	function guardar()
	{
		var variable = "accion=guardar&"+$("#FORM_ALMACEN").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "resetForm");
	}


	function guardar_almacen()
	{
		var variable = "accion=guardar_almacen&"+$("#FORM_ALMACEN_ALTA").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "resetFormalmacen",1);
		
	}

	function guardar_transferencia()
	{
		var variable = "accion=guardar_transferencia&"+$("#FORM_TRANSFERENCIA").serialize()+"&"+$("#form_almacen").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "resetFormtransferencia");		
	}

	function resetFormFactura()
	{
		$("#FORM_ALMACEN #ancho").val("");
		$("#FORM_ALMACEN #largo").val("");
		$("#FORM_ALMACEN #unidad").val("");
		$("#FORM_ALMACEN #unidades").val("");
		$("#FORM_ALMACEN #costo").val("");
	}

	function resetForm(Response)
	{
		$("#FORM_ALMACEN").find("input").val("");
		actualizaDatagrid();
	}

	function resetFormalmacen(Response)
	{
		$("#FORM_ALMACEN_ALTA").find("input").val("");
		$("#modal_almacen_alta").modal("hide");
		cargaFormularios();
	}

	function resetFormtransferencia(Response)
	{
		$("#modal_transferencia").modal("hide");	
		actualizaDatagrid();
	}

	function actualizar_inventario()
	{
		var variable = "accion=actualizar_inventario";
        RestFullRequest("_Rest/Almacen.php", variable, "actualizaDatagrid");
	}