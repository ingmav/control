		var empresa = 2;
		var paginacion = 1;
		var buscar = "";
	
		
		$( "#search" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
            	RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
				//paginador();
			}
		});

		$( "#client" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+1;
            	RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
				//paginador();
			}
		});
		
		function datagridSeleccionDocumento(response)
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				var campos = "";
				var index = 0;
				var id = value['DOCTOS_VE.DOCTO_VE_ID'];
				var tituloempresa = "";
				//if(empresa == 1)
				//	tituloempresa = "NX";
				//else
					tituloempresa = "NP";

				campos += "<td>"+tituloempresa+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'].substr(1))+"-"+value['DOCTOS_VE.ESTATUS']+"</td>";
				campos += "<td>"+value['DOCTOS_VE.FECHA']+"</td>";
				campos += "<td>"+value['CLIENTES.NOMBRE']+"<BR>";
				campos += ""+value['DOCTOS_VE.DESCRIPCION']+"</td>";

				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				campos += "<td><button type='button' class='btn btn-circle  btn-success' onclick='procesos(this)'><i class='fa fa-cogs'></i></button></td>";
				campos += "<td><input type='checkbox' name='folio[]' value='"+value['DOCTOS_VE.DOCTO_VE_ID']+"'></td>";

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
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td>Cargando procesos</td></tr>");
			empresa = 1;
			var variable = "accion=index&empresa="+empresa+"&page="+1;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
		});
		
		$("#nexprint").on("click", function()
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td colspan='4'>Cargando procesos <i class='fa fa-spinner fa-spin'></i></td></tr>");

			empresa = 2;
			var variable = "accion=index&empresa="+empresa+"&page="+1;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
		});

		$("#pv").on("click", function()
		{
			
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td colspan='4'>Cargando procesos <i class='fa fa-spinner fa-spin'></i></td></tr>");

			empresa = 3;
			var variable = "accion=index_mostrador&empresa="+empresa;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionpv");
		});
		
		function eliminarFolios()
		{
			if(confirm("¿Realmente desea eliminar estos folios?"))
			{
				var lista = $("#lista").serialize();
				var variable = "accion=eliminar_folios&empresa="+2+"&"+lista;
				RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "actualizaDatagrid");
			}
		}

		function datagridSeleccionpv(response)
		{
			
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				
				var campos = "";
				var index = 0;
				var id = value['ID'];
				var tituloempresa = "";
				
				campos += "<td>"+value['FOLIO']+"</td>";
				campos += "<td>"+value['FECHA']+"</td>";
				campos += "<td>"+value['NOMBRE_CLIENTE']+"";
				texto_descripcion = "";

				texto_descripcion = value['DESCRIPCION'];

				$.each(value['MATERIALES'], function(index2, value2)
		        {
		            texto_descripcion += "<br>- "+value2['NOMBRE']+" ("+parseFloat(value2['UNIDADES'],2)+")";
		        });
				campos += " "+texto_descripcion+"</td>";
				
				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				campos += "<td><button type='button' class='btn btn-circle  btn-success' onclick='procesosPV(this)'><i class='fa fa-cogs'></i></button></td>";

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		$("#guardarProduccion").on("click", function()
		{
			var variable = "accion=save&empresa="+empresa+"&"+$("#FormProcesos").serialize();
            RestFullRequest("_Rest/SDLista_productos.php", variable, "actualizaDatagrid",1);
            $("#myModal").modal("hide");
		});

		$("#guardarCerrarProduccion").on("click", function()
		{
            var contador = 0;
           
            if(confirm("¿REALMENTE DESEA CERRAR EL DOCUMENTO?"))
            {
                var variable = "accion=save&empresa="+empresa+"&cerrar=1&"+$("#FormProcesos").serialize();
                RestFullRequest("_Rest/SDLista_productos.php", variable, "actualizaDatagrid", 1);
                $("#myModal").modal("hide");
            }
       });
		
	   $("#CerrarProduccion").on("click", function()
		{
            var contador = 0;
           
            if(confirm("¿REALMENTE DESEA CERRAR EL DOCUMENTO SIN INGRESAR A PRODUCCIÓN?"))
            {
                var variable = "accion=save&empresa="+empresa+"&cerrar=1&"+$("#FormProcesos").serialize()+"&close=1";
                RestFullRequest("_Rest/SDLista_productos.php", variable, "actualizaDatagrid", 1);
                $("#myModal").modal("hide");
            }
       });

		$("#guardarCerrarProduccionPV").on("click", function()
		{
            var contador = 0;
           
            var variable = "accion=save_pv&cerrar=1&"+$("#FormProcesosPv").serialize();
            RestFullRequest("_Rest/SDLista_productos.php", variable, "actualizaDatagridpv", 1);
            $("#ProcesosPv").modal("hide");
            
       });

		
		function actualizaDatagrid()
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td colspan='4'>Cargando procesos <i class='fa fa-spinner fa-spin'></i></td></tr>");

			var variable = "accion=index&empresa="+empresa+"&page="+paginacion;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionDocumento");
			//paginador();
		}

		function actualizaDatagridpv()
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td colspan='4'>Cargando procesos <i class='fa fa-spinner fa-spin'></i></td></tr>");

			empresa = 3;
			var variable = "accion=index_mostrador&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val();
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionpv");
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

		function procesosPV(obj)
		{
			var id = $(obj).parents("tr").data("fila");
			$("#foliofacturaPv").text("Folio: "+$(obj).parents("tr").find("td:eq(0)").text());
			$("#fechafacturaPv").text("Fecha: "+$(obj).parents("tr").find("td:eq(1)").text());
			$("#clientefacturaPv").text("Cliente: "+$(obj).parents("tr").find("td:eq(2)").text());
			$("#DOCTO_PV_ID").val(id);

			
			$("#ProcesosPv").modal("show");
			$("#FormProcesosPv #lista_productos").find("tr").remove();
			var variable = "accion=index_Pv&empresa="+empresa+"&id="+id;
            RestFullRequest("_Rest/SDLista_productos.php", variable, "datagridSDListaPV");
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

				var estatus_diseno 		= "";
				var estatus_maquilas 	= "";
				var estatus_impresion 	= "";
				var estatus_preparacion = "";
				var estatus_instalacion = "";
				var estatus_entrega 	= "";
				var contador_procesos 	= "";
				var estatus_proceso     = "disabled='disabled'";
				var activacion_proceso  = "";

				if(value['TABLEROPRODUCCION.GF_DISENO'] == 1)
				{
					estatus_diseno = " checked='checked'";
					contador_procesos++;
				}
				if(value['TABLEROPRODUCCION.GF_MAQUILAS'] == 1)
				{
					estatus_maquilas = " checked='checked'";
					contador_procesos++;
				}
				if(value['TABLEROPRODUCCION.GF_IMPRESION'] == 1)
				{
					estatus_impresion = " checked='checked'";
					contador_procesos++;
				}
				if(value['TABLEROPRODUCCION.GF_PREPARACION'] == 1)
				{
					estatus_preparacion = " checked='checked'";
					contador_procesos++;
				}
				if(value['TABLEROPRODUCCION.GF_INSTALACION'] == 1)
				{
					estatus_instalacion = " checked='checked'";
					contador_procesos++;
				}
				if(value['TABLEROPRODUCCION.GF_ENTREGA'] == 1)
				{
					estatus_entrega = " checked='checked'";
					contador_procesos++;
				}

				if(contador_procesos > 0){
					estatus_proceso 	= "";
					activacion_proceso 	= " checked='checked'";
				}

				var disenoC = "<td><input type='checkbox' name='diseno_"+id+"' value='1' "+estatus_proceso+estatus_diseno+"></td>";
				var impresionC = "<td><input type='checkbox' name='impresion_"+id+"' value='1'  "+estatus_proceso+" "+estatus_impresion+"></td>";
				var instalacionC = "<td><input type='checkbox' name='instalacion_"+id+"' value='1'  "+estatus_proceso+estatus_instalacion+"></td>";
				var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1'  "+estatus_proceso+estatus_entrega+"></td>";
				var preparacionC = "<td><input type='checkbox' name='preparacion_"+id+"' value='1'  "+estatus_proceso+estatus_preparacion+"></td>";
				var maquilasC = "<td><input type='checkbox' name='maquilas_"+id+"' value='1'  "+estatus_proceso+estatus_maquilas+"></td>";
				

				var procesos = "<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProceso(this)' "+activacion_proceso+">"+disenoC+impresionC+preparacionC+maquilasC+instalacionC+entregaC+"</td>";

				linea.append(campos);
				linea.append(procesos);
				datagrid.append(linea);
				contador++;

				//var variable = "accion=cargar&empresa="+empresa+"&id="+id;
            	//RestFullRequest("_Rest/SDLista_productos.php", variable, "cargaModulos");
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

		function datagridSDListaPV(response)
		{
			console.log(response);
			var datagrid = $("#lista_productos_pv");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				
				var campos = "";
				var index = 0;
				var id;
				var fecha 	= "";
				var hora 	= "";
				id = value['DET_ID'];	

				if(value['F_ENTREGA'])
				{
					fecha 	= value['F_ENTREGA'].substr(0,10);
					hora 	= value['F_ENTREGA'].substr(11,16);

				}
				campos += "<td>"+value['NOMBRE']+"</td>";
				campos += "<td>"+parseFloat(value['UNIDADES']).toFixed(2)+" "+value['UNIDAD_VENTA']+"</td>";
				campos += "<td><textarea class='form-control' name='notas_"+id+"' id='notas_"+id+"' rows='3' style='resize:none'>"+value['NOTAS']+"</textArea></td>>";
                campos += "<td><input type='date' class='form-control' style='width: 160px' name='fecha_entrega_"+id+"' value='"+fecha+"'></td>";
                campos += "<td><input type='time' class='form-control' style='width: 160px' name='hora_entrega_"+id+"' value='"+hora+"'></td>";

                var activado = parseInt(value['GF_DISENO'])+parseInt(value['GF_IMPRESION'])+parseInt(value['GF_PREPARACION'])+parseInt(value['GF_ENTREGA'])+parseInt(value['GF_INSTALACION']);
                if(activado>0)
                {	if(value['GF_DISENO'] == 1)
                		var disenoC = "<td><input type='checkbox' name='diseno_"+id+"' value='1' checked='checked'></td>";
                		else
                		var disenoC = "<td><input type='checkbox' name='diseno_"+id+"' value='1'></td>";
                	
                	if(value['GF_IMPRESION'] == 1)
                		var impresionC = "<td><input type='checkbox' name='impresion_"+id+"' value='1' checked='checked'></td>";
                		else
                			var impresionC = "<td><input type='checkbox' name='impresion_"+id+"' value='1'></td>";
                	
                	if(value['GF_PREPARACION'] == 1)
                	  	var preparacionC = "<td><input type='checkbox' name='preparacion_"+id+"' value='1' checked='checked'></td>";
                	  else
                	  	var preparacionC = "<td><input type='checkbox' name='preparacion_"+id+"' value='1'></td>";
                	
                	if(value['GF_INSTALACION'] == 1)
                	   	var instalacionC = "<td><input type='checkbox' name='instalacion_"+id+"' value='1' checked='checked'></td>";
                	   else
                	   	var instalacionC = "<td><input type='checkbox' name='instalacion_"+id+"' value='1'></td>";

                	if(value['GF_ENTREGA'] == 1)
                	  	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1' checked='checked'></td>";
                	  else
                	  	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1'></td>";
                	
                	campos +="<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProcesopv(this)' checked='checked'>"+disenoC+impresionC+preparacionC+instalacionC+entregaC+"</td>";

                }else
                {
                	var disenoC = "<td><input type='checkbox' name='diseno_"+id+"' value='1' disabled='disabled'></td>";
                	var impresionC = "<td><input type='checkbox' name='impresion_"+id+"' value='1' disabled='disabled'></td>";
                	var preparacionC = "<td><input type='checkbox' name='preparacion_"+id+"' value='1' disabled='disabled'></td>";
                	var instalacionC = "<td><input type='checkbox' name='instalacion_"+id+"' value='1' disabled='disabled'></td>";
                	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1' disabled='disabled'></td>";
                	
                	campos +="<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProcesopv(this)'>"+disenoC+impresionC+preparacionC+instalacionC+entregaC+"</td>";
                }

            
				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;

				//var variable = "accion=cargar&empresa="+empresa+"&id="+id;
            	//RestFullRequest("_Rest/SDLista_productos.php", variable, "cargaModulos");
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		function cargaModulos(response)
		{
			if(response.contador.PAGINADOR == 0)
			{
				var disenoC = "<td><input type='checkbox' name='diseno_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var impresionC = "<td><input type='checkbox' name='impresion_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var instalacionC = "<td><input type='checkbox' name='instalacion_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var entregaC = "<td><input type='checkbox' name='entrega_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var preparacionC = "<td><input type='checkbox' name='preparacion_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var maquilasC = "<td><input type='checkbox' name='maquilas_"+response.identificador+"' value='1' disabled='disabled'></td>";
				var prioridadC = "<td><input type='checkbox' name='prioridad_"+response.identificador+"' value='1' disabled='disabled'></td>";

				$("#"+response.identificador).append("<td><input type='checkbox' name='procesos[]' value='"+response.identificador+"' onclick='activarProceso(this)'>"+disenoC+impresionC+preparacionC+maquilasC+instalacionC+entregaC+"</td>");
			}else
			{
				var verificador = "";
				var diseno = "disabled='disabled'";
				var impresion = "disabled='disabled'";
				var instalacion = "disabled='disabled'";
				var entrega = "disabled='disabled'";
				var preparacion = "disabled='disabled'";
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
					preparacion = "";
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
				if(response.data[0].PREPARACION == 1)
					preparacion = "checked=checked";
				//if(response.data[0].PRIORIDAD == 1)
				//	prioridad = "checked=checked";

				var disenoC = "<td><input type='checkbox' name='diseno_"+response.identificador+"' value='1'  "+diseno+"></td>";
				//var programacionC = "<td><input type='checkbox' name='programacion_"+response.identificador+"' value='1'  "+programacion+"></td>";
				var impresionC = "<td><input type='checkbox' name='impresion_"+response.identificador+"' value='1' "+impresion+"></td>";
				var preparacionC = "<td><input type='checkbox' name='preparacion_"+response.identificador+"' value='1' "+preparacion+"></td>";
				var instalacionC = "<td><input type='checkbox' name='instalacion_"+response.identificador+"' value='1' "+instalacion+"></td>";
				var entregaC = "<td><input type='checkbox' name='entrega_"+response.identificador+"' value='1' "+entrega+"></td>";
				var maquilasC = "<td><input type='checkbox' name='maquilas_"+response.identificador+"' value='1' "+maquilas+"></td>";
				//var prioridadC = "<td><input type='checkbox' name='prioridad_"+response.identificador+"' value='1' "+prioridad+"></td>";

				$("#"+response.identificador).append("<td><input type='checkbox' name='procesos[]' value='"+response.identificador+"' "+verificador+" onclick='activarProceso(this)'>"+disenoC+impresionC+preparacionC+maquilasC+instalacionC+entregaC+"</td>");
				
			}
		}
		function activarProceso(Obj)
		{
			if($(Obj).is(":checked"))
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(7) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(8) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(9) input").prop("disabled", false);
				fila.find("td:eq(10) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(11) input").prop("disabled", false);
				
					
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

		function activarProcesopv(Obj)
		{
			if($(Obj).is(":checked"))
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(7) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(8) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(9) input").prop("disabled", false);
				fila.find("td:eq(10) input").prop("disabled", false).prop("checked",true);
				
				//fila.find("td:eq(10) input").prop("disabled", false);
					
			}else
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(7) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(8) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(9) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(10) input").prop("disabled", true).prop("checked",false);
				
				//fila.find("td:eq(10) input").prop("disabled", true).prop("checked",false);

			}
		}
		$(document).ready(function(e) {
			actualizaDatagrid();
            //actualizaProcesos();
            $("#operacion").find("a").click();
			//setInterval(actualizaDatagrid,  900000);
        });