	var empresa = 1;
		var paginacion = 1;
		var buscar = "";
		var pagados = 1;
	
		
		$( "#search" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				buscar = $("#search").val();
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&page=1&pagados="+pagados;
            	RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
				paginador();
			}
		});

		$( "#client" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page=1"+"&pagados="+pagados;
            	RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
				paginador();
			}
		});

		
		function datagridSeleccionDocumento(response)
		{
			//actualizaProcesos();
			//console.log(response);
			//paginador();
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			
		
			$.each(response, function(index, value)
			{


				var campos = "";
				var id = value['DOCTOS_VE.DOCTO_VE_ID'];
				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
				colorEstatus = "success";
				
				campos += "<td>"+value['NOMBREEMPRESA']+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'].substr(1))+"-"+value['DOCTOS_VE.TIPO_DOCTO']+"</td>";
				campos += "<td>"+value['DOCTOS_VE.FECHA']+"<br>"+value['TABLEROPRODUCCION.FECHA_TERMINO']+"</td>";
				//campos += "<td>"+value['MAX']+"</td>";
				campos += "<td><b style='color:blue'><div class='cliente'>"+value['CLIENTES.NOMBRE']+"</div></b><div class='descripcion'>"+value['DOCTOS_VE.DESCRIPCION']+"</div></td>";
				
				//campos += "<td>"+value['DOCTOS_VE.DESCRIPCION']+"</td>";
				var total = (parseFloat(value['DOCTOS_VE.IMPORTE_NETO']) + parseFloat(value['DOCTOS_VE.TOTAL_IMPUESTOS']));
				// + 
				campos += "<td>$ "+currency(total, 2, [',','.'])+"</td>";
				//campos += "<td>"+value['procesosRealizados']+" / "+value['procesos']+"</td>";
				if(pagados == 1)
				{
					/*if(value['DOCTOS_VE.TIPO_DOCTO']=="R" && value['DOCTOS_VE_LIGAS.DOCTO_VE_DEST_ID']=="")
						campos += "<td><button type='button' class='btn btn-danger' onclick=\"pagado(this, "+value['EMPRESA']+", "+value['CLIENTES.CLIENTE_ID']+", '"+value['DOCTOS_VE.CLAVE_CLIENTE']+"')\">NO FACTURADO </button></td>";
					else*/
						campos += "<td><button type='button' class='btn btn-success' onclick=\"pagado(this, "+value['EMPRESA']+", "+value['CLIENTES.CLIENTE_ID']+", '"+value['DOCTOS_VE.CLAVE_CLIENTE']+"')\">ENVIAR CORREO</button></td>";
				}
				else
					campos += "<td><button type='button' class='btn btn-success' onclick=\"pagado(this, "+value['EMPRESA']+", "+value['CLIENTES.CLIENTE_ID']+", '"+value['DOCTOS_VE.CLAVE_CLIENTE']+"')\">REENVIAR CORREO</button></td>";
				
				linea.append(campos);

				datagrid.append(linea);
				contador++;
				
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		function actualizaDatagrid()
		{
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&pagados="+pagados;
			
            RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
			//paginador();
		}
		
		function paginador()
		{
			var variable = "accion=counter&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&pagados="+pagados;
			RestFullRequest("_Rest/Satisfaccion.php", variable, "creaPaginador");
			
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

		function pagado(obj, emp, cliente, clave)
		{

			empresa = emp;
			var id = $(obj).parents("tr").data("fila");
			$("#doctofactura").html("ID:<input type='text' class='form-control' value='"+id+"' readonly='readonly'>");
			$("#foliofactura").html("FOLIO:<input type='text' name='REFERENCIA' class='form-control' value='"+$(obj).parents("tr").find("td:eq(0)").text()+"' readonly='readonly'>");
			//$("#fechafactura").html("FECHA: <input type='text'  class='form-control' value='"+$(obj).parents("tr").find("td:eq(1)").text()+"'>");
			$("#clientefactura").html("CLIENTE: <input type='text'  class='form-control' value='"+$(obj).parents("tr").find(".cliente").text()+"'>");
			$("#descripcionfactura").html("DESCRIPCIÓN: <input type='text'  class='form-control' value='"+$(obj).parents("tr").find(".descripcion").text()+"'>");
			$("#DOCTO_VE_ID").val(id);
			$("#CLIENTE").val($(obj).parents("tr").find(".cliente").text());
			$("#DESC").val($(obj).parents("tr").find(".descripcion").text());
			$("#CLIENTE_ID").val(cliente);
			$("#CLAVE_CLIENTE").val(clave);
			$("#myModal").modal("show");

			var variable = "accion=email&empresa="+empresa+"&docto_ve_id="+id;
            RestFullRequest("_Rest/Satisfaccion.php", variable, "cargaEmail");
		}

		function cargaEmail(Response)
		{
			$("#correo").val("");
			var email = "";
			$.each(Response, function(index, value)
			{
				email =value['CORREO'];
			});
			$("#correo").val(email);
		}

	
		
		function pages(Obj, value)
		{
			$(".pagination li").removeClass("active");
			$(Obj).addClass("active");
			paginacion = value;
			actualizaDatagrid();
		}
		
		$(document).ready(function(e) {
			actualizaDatagrid();
            $("#call").find("a").click();
			//setInterval(actualizaDatagrid,  900000);
        });

        $("#nexos").on("click", function()
		{
			empresa = 1;
			paginacion = 1;
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&pagados="+pagados;
            RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
		});
		
		$("#nexprint").on("click", function()
		{
			empresa = 2;
			paginacion = 1;
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&pagados="+pagados;
            RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
		});

		 $("#Enviar").on("click", function()
		{

            $("#myModal").modal("hide");
            $("#FormProcesos").attr("target", "_blank");
            $("#FormProcesos").attr("action", "http://www.nexprint.mx/Encuesta/IngresarEncuesta.php");
            
            
            $("#FormProcesos").submit();
            $("#FormProcesos").attr("target", "");
            $("#FormProcesos").attr("action", "");
			
            var variable = "accion=savePay&id="+$("#DOCTO_VE_ID").val()+"&empresa="+empresa+"&correo="+$("#correo").val()+"&CLAVE_CLIENTE="+$("#CLAVE_CLIENTE").val();
            RestFullRequest("_Rest/Satisfaccion.php", variable, "CerrarPago");
		});

		 $("#noEnviar").on("click", function()
		{
			var variable = "accion=savePay&id="+$("#DOCTO_VE_ID").val()+"&empresa="+empresa+"&correo="+$("#correo").val()+"&CLAVE_CLIENTE="+$("#CLAVE_CLIENTE").val();
            RestFullRequest("_Rest/Satisfaccion.php", variable, "CerrarPago");
		});
		

		function CerrarPago()
		{
			$("#myModal").modal("hide");
			var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+"&pagados="+pagados;
            RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
		}

		function pay()
		{
			console.log(pagados);
			if(pagados == 2)
				pagados = 1;
			else
				pagados = 2;

			paginacion = 1;
				buscar = $("#search").val();
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&pagados="+pagados;
            	RestFullRequest("_Rest/Satisfaccion.php", variable, "datagridSeleccionDocumento");
				paginador();
		}

		function currency(value, decimals, separators) {
		    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
		    separators = separators || ['.', "'", ','];
		    var number = (parseFloat(value) || 0).toFixed(decimals);
		    if (number.length <= (4 + decimals))
		        return number.replace('.', separators[separators.length - 1]);
		    var parts = number.split(/[-.]/);
		    value = parts[parts.length > 1 ? parts.length - 2 : 0];
		    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
		        separators[separators.length - 1] + parts[parts.length - 1] : '');
		    var start = value.length - 6;
		    var idx = 0;
		    while (start > -3) {
		        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
		            + separators[idx] + result;
		        idx = (++idx) % 2;
		        start -= 3;
		    }
		    return (parts.length == 3 ? '-' : '') + result;
		}