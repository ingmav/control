		var empresa = 1;
		var paginacion = 1;
		var buscar = "";
		var seleccionador = false;
	
		function seleccion_multiple()
		{
			var digital = $("#digital").hasClass("active");
			var gf = $("#gran_formato").hasClass("active");

			seleccionador = !seleccionador;
			if(digital == true)
			{
				if(seleccionador == true)
				{
					$(".combos_seleccion").prop('checked', true);
				}else{
					$(".combos_seleccion").prop('checked', false);
				}
			}else if(gf == true)
			{
				if(seleccionador == true)
				{
					$(".combos_seleccion_gf").prop('checked', true);
				}else{
					$(".combos_seleccion_gf").prop('checked', false);
				}
			}
		}

		function eliminacion()
		{
			var digital = $("#digital").hasClass("active");
			var gf = $("#gran_formato").hasClass("active");
			if(confirm("¿Realmente esta seguro de eliminar este registro?"))
			{
				if(digital == true)
				{
					var variable = "accion=delete_multiple_pv&empresa="+empresa+"&cerrar=1";
					
					$.each($(".combos_seleccion"), function(index, value)
					{
						if($(this).is(':checked'))
						{
							var valor = $(value).val();
							//console.log(valor);
							variable = variable +"&arr[]="+valor;
						}
					});
					//console.log(variable);
					RestFullRequest("_Rest/SDLista_productos.php", variable, "digital");
				}else if(gf == true)
				{
					var variable = "accion=delete_multiple_pv_gf&empresa="+empresa+"&cerrar=1";
					
					$.each($(".combos_seleccion_gf"), function(index, value)
					{
						if($(this).is(':checked'))
						{
							var valor = $(value).val();
							//console.log(valor);
							variable = variable +"&arr[]="+valor;
						}
					});
					//console.log(variable);
					RestFullRequest("_Rest/SDLista_productos.php", variable, "gran_formato");
				}
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
				campos += "<td style='text-align:center'><input type='checkbox' class='combos_seleccion' name='mostrador["+id+"]' value='"+id+"'></td>";

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

		function datagridSelecciongf(response)
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

				campos += "<td><button type='button' class='btn btn-circle  btn-success' onclick='procesosgf(this)'><i class='fa fa-cogs'></i></button></td>";
				campos += "<td style='text-align:center'><input type='checkbox' class='combos_seleccion_gf' name='mostrador_gf["+id+"]' value='"+id+"'></td>";

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		$("#guardarCerrarProduccionPV").on("click", function()
		{
            var contador = 0;
           
            if(confirm("¿REALMENTE DESEA CERRAR EL DOCUMENTO?"))
            {
                var variable = "accion=save_pv&empresa="+empresa+"&cerrar=1&"+$("#FormProcesosPv").serialize();
                RestFullRequest("_Rest/SDLista_productos.php", variable, "digital", 1);
                $("#ProcesosPv").modal("hide");
            }
       });

		$("#guardarCerrarProducciongf").on("click", function()
		{
            var contador = 0;
           
            if(confirm("¿REALMENTE DESEA CERRAR EL DOCUMENTO?"))
            {
                var variable = "accion=savegf&empresa="+empresa+"&cerrar=1&"+$("#FormProcesosgf").serialize();
                RestFullRequest("_Rest/SDLista_productos.php", variable, "gran_formato", 1);
                $("#procesosgf").modal("hide");
            }
       });

		$("#gran_formato").on("click", function()
		{
			gran_formato();
		});

		function gran_formato()
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td colspan='4'>Cargando procesos <i class='fa fa-spinner fa-spin'></i></td></tr>");

			empresa = 2;
			var variable = "accion=index&empresa="+empresa+"&page="+1;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSelecciongf");
		}

		$("#digital").on("click", function()
		{
			digital();
		});

		function digital()
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			datagrid.append("<tr><td colspan='4'>Cargando procesos <i class='fa fa-spinner fa-spin'></i></td></tr>");

			empresa = 3;
			var variable = "accion=index_mostrador&empresa="+empresa;
            RestFullRequest("_Rest/SeleccionDocumentos.php", variable, "datagridSeleccionpv");
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
		
		function procesosgf(obj)
		{
			$("#procesosgf").modal("show");
			var id = $(obj).parents("tr").data("fila");
			$("#foliofacturaGF").text("Folio: "+$(obj).parents("tr").find("td:eq(0)").text());
			$("#fechafacturaGF").text("Fecha: "+$(obj).parents("tr").find("td:eq(1)").text());
			$("#clientefacturaGF").text("Cliente: "+$(obj).parents("tr").find("td:eq(2)").text());
			$("#DOCTO_VE_ID").val(id);

			$("#FormProcesosgf #lista_productos_gf").find("tr").remove();
			var variable = "accion=index&empresa="+empresa+"&id="+id;
            RestFullRequest("_Rest/SDLista_productos.php", variable, "datagridSDListaGF");
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
		
		

		function datagridSDListaPV(response)
		{
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
				}else
				{
					var f = new Date();
					fecha   = (f.getFullYear())+"-"+pad((f.getMonth() +1))+"-"+pad(f.getDate(),2);
					hora   	= "20:00:00";
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
                	
                	if(value['GF_ENTREGA'] == 1)
                	  	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1' checked='checked'></td>";
                	  else
                	  	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1'></td>";
                	
                	campos +="<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProcesopv(this)' checked='checked'>"+disenoC+impresionC+preparacionC+entregaC+"</td>";

                }else
                {
                	var disenoC = "<td><input type='checkbox' name='diseno_"+id+"' value='1' disabled='disabled'></td>";
                	var impresionC = "<td><input type='checkbox' name='impresion_"+id+"' value='1' disabled='disabled'></td>";
                	var preparacionC = "<td><input type='checkbox' name='preparacion_"+id+"' value='1' disabled='disabled'></td>";
                	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1' disabled='disabled'></td>";
                	
                	campos +="<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProcesopv(this)'>"+disenoC+impresionC+preparacionC+entregaC+"</td>";
                }

            
				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;

			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

		function pad (n, length) {
		   return ("0" + n).slice (-length);
		}

		function datagridSDListaGF(response)
		{
			var datagrid = $("#lista_productos_gf");
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

				}else
				{
					var f = new Date();
					fecha   = (f.getFullYear())+"-"+pad((f.getMonth() +1))+"-"+pad(f.getDate(),2);
					fecha   = sumaFecha(1);
					hora   	= "12:00:00";
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
                	
                	campos +="<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProcesogf(this)' checked='checked'>"+disenoC+impresionC+preparacionC+instalacionC+entregaC+"</td>";

                }else
                {
                	var disenoC = "<td><input type='checkbox' name='diseno_"+id+"' value='1' disabled='disabled'></td>";
                	var impresionC = "<td><input type='checkbox' name='impresion_"+id+"' value='1' disabled='disabled'></td>";
                	var preparacionC = "<td><input type='checkbox' name='preparacion_"+id+"' value='1' disabled='disabled'></td>";
                	var instalacionC = "<td><input type='checkbox' name='instalacion_"+id+"' value='1' disabled='disabled'></td>";
                	var entregaC = "<td><input type='checkbox' name='entrega_"+id+"' value='1' disabled='disabled'></td>";
                	
                	campos +="<td><input type='checkbox' name='procesos[]' value='"+id+"' onclick='activarProcesogf(this)'>"+disenoC+impresionC+preparacionC+instalacionC+entregaC+"</td>";
                }
            
				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;

			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

		function sumaFecha(d, fecha)
		{
		 var Fecha = new Date();
		 var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
		 var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
		 var aFecha = sFecha.split(sep);
		 var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
		 fecha= new Date(fecha);
		 fecha.setDate(fecha.getDate()+parseInt(d));
		 var anno=fecha.getFullYear();
		 var mes= fecha.getMonth()+1;
		 var dia= fecha.getDate();
		 mes = (mes < 10) ? ("0" + mes) : mes;
		 dia = (dia < 10) ? ("0" + dia) : dia;
		 var fechaFinal = anno+"-"+mes+"-"+dia;
		 //var fechaFinal = dia+sep+mes+sep+anno;
		 return (fechaFinal);
		 }
		
		
		function activarProcesopv(Obj)
		{
			if($(Obj).is(":checked"))
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(7) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(8) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(9) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(10) input").prop("disabled", false).prop("checked",true);
				
					
			}else
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(7) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(8) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(9) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(10) input").prop("disabled", true).prop("checked",false);
					

			}
		}

		function activarProcesogf(Obj)
		{
			if($(Obj).is(":checked"))
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(7) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(8) input").prop("disabled", false).prop("checked",true);
				fila.find("td:eq(9) input").prop("disabled", false);
				fila.find("td:eq(10) input").prop("disabled", false).prop("checked",true);
				
					
			}else
			{
				var fila = $(Obj).parents("tr");
				fila.find("td:eq(6) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(7) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(8) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(9) input").prop("disabled", true).prop("checked",false);
				fila.find("td:eq(10) input").prop("disabled", true).prop("checked",false);
					

			}
		}
		$(document).ready(function(e) {
			//actualizaDatagridpv();
			digital();
            //actualizaProcesos();
            $("#mostrador").find("a").click();
			//setInterval(actualizaDatagrid,  900000);
        });