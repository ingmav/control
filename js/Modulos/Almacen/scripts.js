	var id_general = 0;

	function actualizaDatagrid()
	{

		formatea_formularios();
		$("#almacen").html("<tr><td colspan='4'>Cargando...</td></tr>");
        var familia = $("#filtro_grupo").val();
        var texto = $("#filtro_texto").val();
        var variable = "accion=index&familia="+familia+"&texto="+texto;
        RestFullRequest("_Rest/Almacen.php", variable, "datagrid");
	}

	function formatea_formularios()
	{
		$("#FORM_INSUMO").find("select").val(1).find("input").val("");
	}

	function datagrid(response)
	{
		console.log(response);
		var contador = 0;
		var datagrid = $("#almacen");

		datagrid.find("tr").remove();

		arreglo = Array();
		var total = 0;
		var subtotal = 0;
		var total_herramieintas = 0;
		var total_insumos = 0;
//console.log(response['ARTICULOS']);
		$.each(response['ARTICULOS'], function(index, value)
		{
			var campos = "";
			
            if((parseFloat(value['CANTIDAD_MINIMA']) - parseFloat(value['INVENTARIO']))  >= 0)
            	linea = $("<tr style='color: rgba(200,0,0,0.9);'>");
            else
            	linea = $("<tr>");
           	
           	
           	datos = "";
           	var datos_subtotal = "";
           	var ultima_compra = "";
           	if(value['UNITARIO'] == 1)
           	{
				campos += "<td>"+value['ARTICULO']+"</td>";
           		var residuo = value['INVENTARIO'] % value['PAQUETE'];
           		datos = "UNIDADES: <b>"+parseInt(value['INVENTARIO'] / value['PAQUETE'])+" "+value['UNIDAD_COMPRA']+"(S)</b><br>";
           		datos += "EN USO: <b>"+redondeoDecimales(residuo,2)+" "+value['UNIDAD_VENTA']+"(S)</b><br>";
           		datos += "MINIMO: <b>"+redondeoDecimales((value['CANTIDAD_MINIMA'] / value['PAQUETE']),2)+" "+value['UNIDAD_COMPRA']+"(S) </b><br>";
           		subtotal = parseFloat(value['MONTO_UNITARIO'] * value['INVENTARIO']);
           		total = parseFloat(total) + parseFloat(subtotal);
           		if(parseFloat(value['MONTO_UNITARIO']) > 0)
                    ultima_compra = redondeoDecimales((parseFloat(value['MONTO_UNITARIO']) * parseFloat(value['PAQUETE'])),2)+" 1 "+value['UNIDAD_COMPRA'];
                else
                    ultima_compra = "SIN COMPRA";
           		
           	}else if(value['UNITARIO'] == 0)
           	{
				if(value['ANCHO'] == 0 && value['LARGO'] == 0)
					var dimension = " (- x -) ";	
				else
				var dimension = " ("+value['ANCHO'] +" x "+value['LARGO']+") ";
					
				campos += "<td>"+value['ARTICULO']+dimension+"</td>";
           		
				datos = "UNIDADES: <b>"+value['REGISTROS']+" "+value['UNIDAD_COMPRA']+"(S)</b><br>";
           		if(!parseFloat(value['CANTIDAD_USO']))
           			value['CANTIDAD_USO'] = 0;

           		if(parseFloat(value['ANCHO']) == 0)
           			value['ANCHO'] = 1;

           		datos += "EN USO: <b>"+redondeoDecimales((value['CANTIDAD_USO'] / value['ANCHO']),2)+" ML</b><br>";
           		var minimo = (value['ANCHO'] * value['LARGO']);
           		if(parseFloat(minimo) > 0)
           			minimo = value['CANTIDAD_MINIMA'] / parseFloat(minimo);
           		datos += "MINIMO: <b>"+redondeoDecimales(parseFloat(minimo),2)+" "+value['UNIDAD_COMPRA']+"(S) </b><br>";
				   
				subtotal = parseFloat(value['PRECIO_UNITARIO'] * ((value['REGISTROS'] * value['ANCHO'] * value['LARGO']) + (value['CANTIDAD_USO'] / value['ANCHO'])));
           		total = parseFloat(total) + parseFloat(subtotal);
           		if(value['MONTO_METRAJE'] > 0)
                    ultima_compra = currency(value['MONTO_METRAJE'],2,[",","."])+" 1 "+value['UNIDAD_COMPRA']+" <br>"+value['DIMENSION'];
                else
                    ultima_compra = "SIN COMPRA";
           		
			}
			   
			if(value['ID_FAMILIA'] ==  16)
			{
				total_herramieintas = total_herramieintas + subtotal;
			}else{
				total_insumos = total_insumos + subtotal;
			}
			           	
           	datos_subtotal +="MONTO: <b>$ "+currency(subtotal,2,[",","."])+"</b><br>";
			datos_subtotal +="ULTIMA COMPRA: <BR><b>$ "+ultima_compra+"</b><br>";
			if(value['ANCHO'] > 0){
				datos_subtotal +="PRECIO M2: <BR><b>$ "+currency(value['PRECIO_UNITARIO'],2,[",","."])+"</b><br>";
				var valor_lineal = value['MONTO_METRAJE'] / (value['DIMENSION_UNITARIO'] / value['ANCHO']);
				datos_subtotal +="PRECIO LINEAL: <BR><b>$ "+currency(valor_lineal,2,[",","."])+"</b><br>";
			}	
           	campos += "<td>"+datos+"</td>";
           	campos += "<td>"+datos_subtotal+"</td>";
			campos += "<td>"+value['ACTUALIZACION']+"</td>";
			
			if((parseFloat(value['CANTIDAD_MINIMA']) - parseFloat(value['INVENTARIO'])  >= 0 && parseFloat(value['INVENTARIO']) <= 0))
				campos += "<td></td>";
            else
				campos += "<td><button type='button' class='btn btn-success' onclick=\"baja_manual("+value['ARTICULO_ID']+", '"+value['ARTICULO']+"')\"><i class='fa fa-caret-square-o-down'></i></button></td>";
            

            linea.append(campos);
            datagrid.append(linea);

            contador++;

            

		});

		$("#monto_inventario").text(currency(total, 2, [",","."]));
		rellena_datos(total_herramieintas, total_insumos);
		
		if(contador == 0)
			datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
	}

	$(document).ready(function()
	{

		actualizaDatagrid();
		cargaFormularios();
		$("#menu_inventario").find("a").click();
		
	});

	function rellena_datos(herramientas, insumos)
	{
		console.log(parseInt($("#monto_herramientas").text()));
		if(parseInt($("#monto_herramientas").text()) == 0 && parseInt($("#monto_insumos").text()) == 0 )
		{
			$("#monto_herramientas").text(currency(herramientas, 2, [",","."]));
			$("#monto_insumos").text(currency(insumos, 2, [",","."]));

		}
	}

	function baja_manual(id, insumo)
	{
		$("#NOMBRE_ARTICULO_MODAL").text(insumo);
		id_general = id;
		var variable = "accion=ver_inventario&id="+id;
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_tabla_inventario");
        $("#modal_baja_manual").modal("show");
	}

	function estado_unitario(valor)
	{
	if(valor == 1)
	{
		$("#FORM_INSUMO #ancho").attr("disabled", true).val("0.00");	
		$("#FORM_INSUMO #largo").attr("disabled", true).val("0.00");	
		$("#u_paquete").attr("disabled", false);	
		$("#u_paquete").attr("disabled", false);	
		$("#u_venta").val("PIEZA");	
	}else
	{
		$("#FORM_INSUMO #ancho").attr("disabled", false);	
		$("#FORM_INSUMO #largo").attr("disabled", false);
		$("#u_paquete").attr("disabled", true);	
		$("#u_venta").val("");	
	}

	}

	function cerrar_factura() {
		if($("#lista_factura tr").length > 0)
		{
			$("#FORM_ALMACEN #doble_factura").hide();
			var variable = "accion=cerrar_factura";
			RestFullRequest("_Rest/Almacen.php", variable, "actualiza_tabla_inventario",1);
			actualiza_lista_factura();
			$("#FORM_ALMACEN").find("input").val("");
			$("#FORM_ALMACEN").find("select").val(1);
			$("#modal_inventario").modal("hide");
		}else{
			alert("Debe de ingresar al menos un insumo");
		}
	}

	function actualiza_tabla_inventario(Response)
	{
		$("#lista_inventario_baja").html("");
		$("#lista_inventario_baja_activo").html("");
		var restante = 0;
		var arreglo = Array();
		
		$.each(Response['ENTERO'], function(index, value)
		{
			var lineal = 0;
			
			var dimension 	= "";
			var unidades 	= 0;
			if(value['UNITARIO'] == 0)
			{
				$("#titulo_baja").text("ACTIVAR INSUMOS");
				dimension = "( "+value['ANCHO']+" X "+value['LARGO']+" )";
				unidades = value['UNIDADES'];
			}else
			{
				$("#titulo_baja").text("BAJA PARCIAL");
				unidades = parseInt(value['CANTIDAD_RESTANTE'] / parseFloat(value['PAQUETE']));
				restante = value['CANTIDAD_RESTANTE'] % parseFloat(value['PAQUETE']);
			}
			var campos = "<tr>";
			campos += "<td>"+value['ID']+"</td>";
			campos += "<td>"+unidades+" "+value['UNIDAD_COMPRA']+"</td>";
			campos += "<td>"+value['NOMBRE_ARTICULO']+" "+dimension+"</td>";
			campos += "<td><input type='number' min='0' max='"+unidades+"' onblur='verifica_cantidad(this, "+unidades+")' name='cantidad_"+value['ID']+"' id='cantidad_"+value['ID']+"' value='"+parseFloat(lineal).toFixed( 2 )+"' class='form-control' onblur='calcula(this,"+value['ID']+", "+value['UNITARIO']+", "+value['ANCHO']+")'><input type='hidden' name='ids[]' value='"+value['ID']+"'></td>";
			campos += "<td><button type='button' class='btn btn-success' onclick='baja_completa("+value['ID']+", \"cantidad_"+value['ID']+"\")'><i class='fa fa-arrow-circle-down'></i></button></td>";
			if(value['UNITARIO'] == 0)
				campos += "<td><button type='button' class='btn btn-primary' onclick='activar_insumo("+value['ID']+", "+value['ID_INVENTARIO']+")'><i class='fa fa-check'></i></button></td>";
			else
				campos +=  "<td><button type='button' class='btn btn-primary' onclick='baja_parcial_general("+value['ID']+", "+value['ID_INVENTARIO']+")'><i class='fa fa-arrow-down'></i></button></td>";
			campos += "</tr>";

			if(unidades > 0)
				$("#lista_inventario_baja").append(campos);
			if(restante > 0)
			{
				arreglo['UNITARIO'] = value['UNITARIO'];
				arreglo['ID'] = value['ID'];
				arreglo['NOMBRE_ARTICULO'] = value['NOMBRE_ARTICULO'];
				arreglo['CANTIDAD'] = restante;
				arreglo['CANTIDAD_RESTANTE'] = restante;
				arreglo['CANTIDAD_RESTANTE'] = restante;
				Response['USO'].unshift(arreglo);
			}
			
		});
		
		//console.log(arreglo);
		
		$.each(Response['USO'], function(index, value)
		{
			var lineal = 0;
			
			var dimension 	= "";
			var unidades 	= 0;
			var ml = 0;
			if(value['UNITARIO'] == 0)
			{
				dimension = "( "+value['ANCHO']+" X "+value['LARGO']+" )";
				unidades = value['UNIDADES'];
				ml = (value['CANTIDAD_RESTANTE'] / value['ANCHO']);
			}else
			{
				unidades = value['CANTIDAD_RESTANTE'];
				ml = value['CANTIDAD_RESTANTE'];
			}
			var total = redondeoDecimales(ml, 2);
			var campos = "<tr>";
			campos += "<td>"+value['ID']+"</td>";
			campos += "<td>"+redondeoDecimales(unidades,2)+"</td>";
			campos += "<td>"+value['NOMBRE_ARTICULO']+" "+dimension+"</td>";
			campos += "<td>"+redondeoDecimales(ml, 2)+"</td>";
			campos += "<td><input type='text' name='cantidad_parcial_"+value['ID']+"' id='cantidad_parcial_"+value['ID']+"'  value='"+parseFloat(lineal).toFixed( 2 )+"' class='form-control' style='width: 85px;' onblur='calcula(this,"+value['ID']+", "+value['UNITARIO']+", "+value['ANCHO']+")'><input type='hidden' name='ids[]' value='"+value['ID']+"'></td>";
			campos += "<td>"+total+"</td>";
			if(value['UNITARIO'] == 0)
			{
				campos += "<td><button type='button' class='btn btn-danger' onclick='baja_parcial("+value['ID']+", "+value['UNITARIO']+", \""+value['ANCHO']+"\" ,\""+value['LARGO']+"\")'><i class='fa fa-minus'></i></button>";
				campos += "<button type='button' class='btn btn-success' onclick='suma_parcial("+value['ID']+", "+value['UNITARIO']+", \""+value['ANCHO']+"\" ,\""+value['LARGO']+"\")'><i class='fa fa-plus'></i></button</td>";
			}else
			{
				campos += "<td><button type='button' class='btn btn-danger' onclick='baja_parcial_unitario("+value['ID']+", "+value['UNITARIO']+")'><i class='fa fa-minus'></i></button>";
				campos += "<button type='button' class='btn btn-success' onclick='suma_parcial_unitario("+value['ID']+", "+value['UNITARIO']+")'><i class='fa fa-plus'></i></button</td>";
			}
			campos += "</tr>";


			$("#lista_inventario_baja_activo").append(campos);
		});
	}

	function activar_insumo(id, id_inventario)
	{
		if(confirm("¿Realmente desea activar el producto, para su uso?"))
		{
			var variable = "accion=activar_articulo&id="+id+"&id_inventario="+id_inventario;
	        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");	
		    
	    }
	}

	function baja_parcial(id, unitario, ancho, largo)
	{
		var cantidad = $("#cantidad_parcial_"+id).val();
		var variable = "accion=baja_parcial&id="+id+"&cantidad="+cantidad+"&unitario="+unitario+"&ancho="+ancho+"&largo="+largo;
        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");
	}

	function suma_parcial(id, unitario, ancho, largo)
	{
		
		var cantidad = $("#cantidad_parcial_"+id).val();
		var variable = "accion=suma_parcial&id="+id+"&cantidad="+cantidad+"&unitario="+unitario+"&ancho="+ancho+"&largo="+largo;
		console.log(variable);
        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");
	}

	function baja_parcial_unitario(id, unitario)
	{
		var cantidad = $("#cantidad_parcial_"+id).val();
		if(parseFloat(cantidad) > 0)
		{
			
			var variable = "accion=baja_parcial&id="+id+"&cantidad="+cantidad+"&unitario="+unitario;
	        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");
	    }else
	    	alert("Debe de ingresar una cantidad mayor a cero");
	}

	function suma_parcial_unitario(id, unitario)
	{
		var cantidad = $("#cantidad_parcial_"+id).val();
		if(parseFloat(cantidad) > 0)
		{
			
			var variable = "accion=suma_parcial&id="+id+"&cantidad="+cantidad+"&unitario="+unitario;
	        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");
	    }else
	    	alert("Debe de ingresar una cantidad mayor a cero");
	}

	function verifica_cantidad(obj, unidades)
	{
		if(obj.value > unidades)
			$(obj).val(unidades);

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

	function baja_completa(id, id_descripcion)
	{
		
		if(confirm("¿Realmente desea eliminar por completo el/las unidades de este artículo?"))
		{
			
			if(parseInt($("#"+id_descripcion).val()) > 0)
			{	
				var variable = "accion=baja_articulo&id="+id+"&cantidad_entera="+parseInt($("#"+id_descripcion).val());
		        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");	
		    }else
		    	alert("Debe de ingresar una cantidad mayoy a '0'");
	    }
	}

	function baja_parcial_general(id)
	{
		if(confirm("¿Realmente desea dar de baja parcial del producto?"))
		{
			
			if(parseFloat($("#cantidad_"+id).val()) > 0)
			{	
				var variable = "accion=baja_articulo_parcial&id="+id+"&cantidad_entera="+parseFloat($("#cantidad_"+id).val());
		        RestFullRequest("_Rest/Almacen.php", variable, "recargar_baja_manual");	
		    }else
		    	alert("Debe de ingresar una cantidad mayoy a '0'");
	    }
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
		$("#FORM_ALMACEN #doble_factura").hide();
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
		//$("#proveedor").html("");
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
		var variable = "accion=eliminar_proveedor&id="+id;
        RestFullRequest("_Rest/Almacen.php", variable, "load_lista_proveedores",2);
	}

	function load_lista_factuas(Response)
	{
		$("#lista_factura").html("");
		var total = 0;
		$.each(Response, function(index, value)
		{
			//console.log(value);
			var obj = JSON.stringify({ id: value['ID'], articulo_id: value['MS_ARTICULO_ID'], largo: value['LARGO'], ancho: value['ANCHO'], unitario: value['UNITARIO'] });
			var dimension = "";
			if(value['ANCHO'] >0 && value['LARGO']>0)
				dimension = "("+value['ANCHO']+" X "+value['LARGO']+") ";
			$("#lista_factura").append("<tr><td>"+value['FACTURA_COMPRA']+"</td><td>"+value['NOMBRE_ARTICULO']+" "+dimension+"</td><td>"+value['REGISTROS']+"</td><td>"+value['UNIDADES']+"</td><td>"+value['PRECIO_UNITARIO']+"</td><td>"+value['PRECIO_COMPRA']+"</td><td><button type='button' class='btn btn-danger' onclick='eliminar_registro("+obj+")'><i class='fa fa-close'></i></button></td></tr>");
			total = parseFloat(total) + parseFloat(value['PRECIO_COMPRA']);
		});
		$("#total_factura").text(total);
		resetFormFactura();
		actualizaDatagrid();
	}

	function eliminar_registro(obj)
	{
		console.log(obj);
		var variable = "accion=eliminar_insumo_borrador&articulo_id="+obj.articulo_id+"&largo="+obj.largo+"&ancho="+obj.ancho+"&id="+obj.id+"&unitario="+obj.unitario;
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_lista_factura",1);
	}

	function btn_guardar_inventario()
	{
		if($("#factura").val() != "" && $("#fecha_factura").val()!="" && $("#costo").val()!="" )
		{
			var variable = "accion=guardar&"+$("#FORM_ALMACEN").serialize();
			RestFullRequest("_Rest/Almacen.php", variable, "actualiza_lista_factura",1);
		}else
			alert("debe de rellenar los campos, por favor verifique");
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

	function verificar_factura()
	{
		var variable = "accion=verficar_factura&"+$("#FORM_ALMACEN").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "valida_factura");
	}

	function valida_factura(response)
	{
		if(response.numero == 1)
			$("#FORM_ALMACEN #doble_factura").show();
		else
			$("#FORM_ALMACEN #doble_factura").hide();

		//$("#FORM_DATOS #cuerpo_datos").html("");
		var datagrid = $("#FORM_DATOS #cuerpo_datos");
		$.each(response['articulos'], function(index, value)
		{
			//console.log(value);
			var linea = $("<tr></tr>");
			var celda1 = $("<td>"+value['FACTURA']+"</td>");
			var celda2 = $("<td>"+value['FECHA_FACTURA']+"</td>");
			var celda3 = $("<td>"+value['NOMBRE_ARTICULO']+"</td>");
			var celda4 = $("<td>"+value['CANTIDAD']+"</td>");
			var celda5 = $("<td>"+value['MONTO']+"</td>");
			
			linea.append(celda1);
			linea.append(celda2);
			linea.append(celda3);
			linea.append(celda4);
			linea.append(celda5);

			datagrid.append(linea);
		});
	}