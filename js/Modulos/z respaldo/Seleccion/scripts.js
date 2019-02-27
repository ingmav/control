		var empresa = 1;
		var paginacion = 1;
		var buscar = "";
	
		
		$( "#search" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
            	RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
				paginador();
			}
		});

		$( "#client" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
            	RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
				paginador();
			}
		});
		
		function datagridSeleccionDocumento(response)
		{

			paginador();
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				
				var campos = "";
				var index = 0;
				var id = value['DOCTOS_VE.DOCTO_VE_ID'];
				var tituloempresa = "";
				if(empresa == 1)
					tituloempresa = "NX";
				else
					tituloempresa = "NP";

				campos += "<td>"+tituloempresa+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.ESTATUS']+"</td>";
				campos += "<td>"+value['DOCTOS_VE.FECHA']+"</td>";
				campos += "<td>"+value['CLIENTES.NOMBRE']+"</td>";
				campos += "<td>"+value['DOCTOS_VE.DESCRIPCION']+"</td>";

				contadorProcesos(id);

				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				campos += "<td><button type='button' class='btn btn-circle  btn-success' onclick='procesos(this)'><i class='fa fa-cogs'></i></button></td>";

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		function contadorProcesos(id)
		{
			var variable = "accion=countProcess&empresa="+empresa+"&id="+id;
		    RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "cargaCountProcess");
		}

		function cargaCountProcess(Response)
		{
			$("#"+Response[0].ID).find("button:eq(0)").append(Response[0].count);
			if(Response[0].count > 0)
				$("#"+Response[0].ID).find("button:eq(0)").removeClass("btn-success").addClass('btn-danger');
		}

		$("#nexos").on("click", function()
		{
			empresa = 1;
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
		});
		
		$("#nexprint").on("click", function()
		{
			empresa = 2;
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
		});
		
		$("#guardarProduccion").on("click", function()
		{
			var variable = "accion=save&empresa="+empresa+"&"+$("#FormProcesos").serialize();
            RestFullRequest("_Rest/SDLista_productos.php", variable, "actualizaDatagrid");
            $("#myModal").modal("hide");
		});

		$("#guardarCerrarProduccion").on("click", function()
		{
            var contador = 0;
            /*$("#lista_productos").find("input[type=checkbox]:checked").each(function()
            {
                contador++;
            });

            if(contador > 1)
            {*/
                if(confirm("¿REALMENTE DESEA CERRAR EL DOCUMENTO?"))
                {
                    var variable = "accion=save&empresa="+empresa+"&cerrar=1&"+$("#FormProcesos").serialize();
                    RestFullRequest("_Rest/SDLista_productos.php", variable, "actualizaDatagrid");
                    $("#myModal").modal("hide");
                }
            /*}else
                alert("Debes de ingresar al menos un registro al sistema de producción");
			*/
		});

		
		function actualizaDatagrid()
		{

			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
			paginador();
		}
		
		function paginador()
		{
			var variable = "accion=counter&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
		
			RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "creaPaginador");
			
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
		
		function procesos(obj)
		{
			var id = $(obj).parents("tr").data("fila");
			$("#foliofactura").text("Folio: "+$(obj).parents("tr").find("td:eq(0)").text());
			$("#fechafactura").text("Fecha: "+$(obj).parents("tr").find("td:eq(1)").text());
			$("#clientefactura").text("Cliente: "+$(obj).parents("tr").find("td:eq(2)").text());
			$("#descripcionfactura").text("Descripción: "+$(obj).parents("tr").find("td:eq(3)").text());
			$("#DOCTO_VE_ID").val(id);

			
			$("#myModal").modal("show");
			$("#FormProcesos #lista_productos").find("tr").remove();
			var variable = "accion=index&empresa="+empresa+"&id="+id;
            RestFullRequest("_Rest/SDLista_productos.php", variable, "datagridSDLista");
		}
		
		function datagridSDLista(response)
		{
			
			var datagrid = $("#lista_productos");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				
				var campos = "";
				var index = 0;
				var id;
				
				id = value['DOCTOS_VE_DET.DOCTO_VE_DET_ID'];	
				campos += "<td>"+value['ARTICULOS.NOMBRE']+"</td>";
				campos += "<td>"+parseFloat(value['DOCTOS_VE_DET.UNIDADES']).toFixed(2)+" "+value['ARTICULOS.UNIDAD_VENTA']+"</td>";
				campos += "<td><textarea class='form-control' name='notas_"+id+"' id='notas_"+id+"' rows='3' style='resize:none'>"+value['DOCTOS_VE_DET.NOTAS']+"</textArea></td>>";
                campos += "<td><input type='date' class='form-control' style='width: 160px' name='fecha_entrega_"+id+"' value='"+value['TABLEROPRODUCCION.FECHA_ENTREGA'].substr(0,10)+"'></td>";
                campos += "<td><input type='time' class='form-control' style='width: 160px' name='hora_entrega_"+id+"' value='"+value['TABLEROPRODUCCION.FECHA_ENTREGA'].substr(11,16)+"'></td>";

				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;

				var variable = "accion=cargar&empresa="+empresa+"&id="+id;
            	RestFullRequest("_Rest/SDLista_productos.php", variable, "cargaModulos");
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		function cargaModulos(response)
		{
			if(response.contador.PAGINADOR == 0)
			{
				var disenoC = "<td><input type='checkbox' name='diseno_"+response.identificador+"' value='1' disabled='disabled'></td>";
				//var programacionC = "<td><input type='checkbox' name='programacion_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var impresionC = "<td><input type='checkbox' name='impresion_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var instalacionC = "<td><input type='checkbox' name='instalacion_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var entregaC = "<td><input type='checkbox' name='entrega_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var maquilasC = "<td><input type='checkbox' name='maquilas_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var prioridadC = "<td><input type='checkbox' name='prioridad_"+response.identificador+"' value='1' disabled='disabled'></td>";

				$("#"+response.identificador).append("<td><input type='checkbox' name='procesos[]' value='"+response.identificador+"' onclick='activarProceso(this)'>"+disenoC+impresionC+maquilasC+instalacionC+entregaC+"</td>");
			}else
			{
				var verificador = "";
				var diseno = "disabled='disabled'";
				var impresion = "disabled='disabled'";
				var instalacion = "disabled='disabled'";
				var entrega = "disabled='disabled'";
				//var programacion = "disabled='disabled'";
				var maquilas = "disabled='disabled'";
				//var prioridad = "disabled='disabled'";

				$("#notas_"+response.identificador).val($("#notas_"+response.identificador).val()+response.data[0].NOTA);
				if(response.data[0].VERIFICADOR == 1)
				{
					verificador = "checked=checked";
					diseno = "";
					//programacion = "";
					impresion = "";
					instalacion = "";
					entrega = "";
					maquilas = "";
				//	prioridad = "";
				}

				if(response.data[0].DISENO == 1)
					diseno = "checked=checked";
				/*if(response.data[0].PROGRAMACION == 1)
					programacion = "checked=checked";*/
				if(response.data[0].IMPRESION == 1)
					impresion = "checked=checked";
				if(response.data[0].INSTALACION == 1)
					instalacion = "checked=checked";
				if(response.data[0].ENTREGA == 1)
					entrega = "checked=checked";
				if(response.data[0].MAQUILAS == 1)
					maquilas = "checked=checked";
				//if(response.data[0].PRIORIDAD == 1)
				//	prioridad = "checked=checked";

				var disenoC = "<td><input type='checkbox' name='diseno_"+response.identificador+"' value='1'  "+diseno+"></td>";
				//var programacionC = "<td><input type='checkbox' name='programacion_"+response.identificador+"' value='1'  "+programacion+"></td>";
				var impresionC = "<td><input type='checkbox' name='impresion_"+response.identificador+"' value='1' "+impresion+"></td>";
				var instalacionC = "<td><input type='checkbox' name='instalacion_"+response.identificador+"' value='1' "+instalacion+"></td>";
				var entregaC = "<td><input type='checkbox' name='entrega_"+response.identificador+"' value='1' "+entrega+"></td>";
				var maquilasC = "<td><input type='checkbox' name='maquilas_"+response.identificador+"' value='1' "+maquilas+"></td>";
				//var prioridadC = "<td><input type='checkbox' name='prioridad_"+response.identificador+"' value='1' "+prioridad+"></td>";

				$("#"+response.identificador).append("<td><input type='checkbox' name='procesos[]' value='"+response.identificador+"' "+verificador+" onclick='activarProceso(this)'>"+disenoC+impresionC+maquilasC+instalacionC+entregaC+"</td>");
				//$("#"+response.identificador).append("<td><input type='checkbox' name='procesos[]' value='"+response.identificador+"' "+verificador+" onclick='activarProceso(this)'></td><td><input type='checkbox' name='diseno_"+response.identificador+"' value='1' "+diseno+" ></td><td><input type='checkbox' name='impresion_"+response.identificador+"' value='1' "+impresion+"></td><td><input type='checkbox' name='instalacion_"+response.identificador+"' value='1' "+instalacion+"></td><td><input type='checkbox' name='prioridad_"+response.identificador+"' value='1' "+prioridad+"></td>");
			}
		}
		function activarProceso(Obj)
		{
			if($(Obj).is(":checked"))
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(7) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(8) input").prop("disabled", false)
				fila.find("td:eq(9) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(10) input").prop("disabled", false);
				fila.find("td:eq(11) input").prop("disabled", false);
				//fila.find("td:eq(10) input").prop("disabled", false);
					
			}else
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(7) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(8) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(9) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(10) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(11) input").prop("disabled", true).prop("checked",false);
				//fila.find("td:eq(10) input").prop("disabled", true).prop("checked",false);

			}
		}
		$(document).ready(function(e) {
			actualizaDatagrid();
            actualizaProcesos();
            $("#operacion").find("a").click();
			//setInterval(actualizaDatagrid,  900000);
        });