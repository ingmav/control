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
            	RestFullRequest("_Rest/cobros.php", variable, "datagridSeleccionDocumento");
				//paginador();
			}
		});

		$( "#client" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page=1"+"&pagados="+pagados;
            	RestFullRequest("_Rest/cobros.php", variable, "datagridSeleccionDocumento");
				//paginador();
			}
		});

		
		function datagridSeleccionDocumento(response)
		{
			actualizaProcesos();
			console.log(response);
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
				
				campos += "<td>"+value['NOMBREEMPRESA']+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.TIPO_DOCTO']+"</td>";
				campos += "<td>"+value['DOCTOS_VE.FECHA']+"</td>";
				campos += "<td>"+value['MAX']+"</td>";
				campos += "<td>"+value['CLIENTES.NOMBRE']+"</td>";
				
				campos += "<td>"+value['DOCTOS_VE.DESCRIPCION']+"</td>";
				campos += "<td>$ "+value['DOCTOS_VE.IMPORTE_NETO']+"</td>";


				linea.append(campos);

				datagrid.append(linea);
				contador++;
				
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
		function actualizaDatagrid()
		{

			var variable = "accion=index&empresa="+empresa+"&desde="+$("#desde").val()+"&hasta="+$("#hasta").val()+"&page="+paginacion+"&pagados="+pagados+"&cliente="+$("#cliente").val()+"&folio="+$("#folio").val();
			
            RestFullRequest("_Rest/Cobros.php", variable, "datagridSeleccionDocumento");
			//paginador();
		}
		
		function paginador()
		{
			var variable = "accion=counter&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&pagados="+pagados;
			RestFullRequest("_Rest/Cobros.php", variable, "creaPaginador");
			
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
			$("#clientefactura").html("CLIENTE: <input type='text'  class='form-control' value='"+$(obj).parents("tr").find("td:eq(3)").text()+"'>");
			$("#descripcionfactura").html("DESCRIPCIÓN: <input type='text'  class='form-control' value='"+$(obj).parents("tr").find("td:eq(4)").text()+"'>");
			$("#DOCTO_VE_ID").val(id);
			$("#CLIENTE").val($(obj).parents("tr").find("td:eq(3)").text());
			$("#DESC").val($(obj).parents("tr").find("td:eq(4)").text());
			$("#CLIENTE_ID").val(cliente);
			$("#CLAVE_CLIENTE").val(clave);
			$("#myModal").modal("show");

			var variable = "accion=email&empresa="+empresa+"&docto_ve_id="+id;
            RestFullRequest("_Rest/Cobros.php", variable, "cargaEmail");
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
            $("#control").find("a").click();
			//setInterval(actualizaDatagrid,  900000);
        });

        $("#nexos").on("click", function()
		{
			empresa = 1;
			paginacion = 1;
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&pagados="+pagados;
            RestFullRequest("_Rest/Cobros.php", variable, "datagridSeleccionDocumento");
		});
		
		$("#nexprint").on("click", function()
		{
			empresa = 2;
			paginacion = 1;
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&pagados="+pagados;
            RestFullRequest("_Rest/Cobros.php", variable, "datagridSeleccionDocumento");
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
            RestFullRequest("_Rest/Cobros.php", variable, "CerrarPago");
		});

		 $("#noEnviar").on("click", function()
		{
			var variable = "accion=savePay&id="+$("#DOCTO_VE_ID").val()+"&empresa="+empresa+"&correo="+$("#correo").val()+"&CLAVE_CLIENTE="+$("#CLAVE_CLIENTE").val();
            RestFullRequest("_Rest/Cobros.php", variable, "CerrarPago");
		});
		

		function CerrarPago()
		{
			$("#myModal").modal("hide");
			var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+"&pagados="+pagados;
            RestFullRequest("_Rest/Cobros.php", variable, "datagridSeleccionDocumento");
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
            	RestFullRequest("_Rest/Cobros.php", variable, "datagridSeleccionDocumento");
				paginador();
		}