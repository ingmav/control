		var paginacion = 1;
        var realizados = 0;
        var filtroEstatus = 0;	
		
		$( "#numeroCotizacion" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				buscar = $("#numeroCotizacion").val();
                var variable = "accion=index&buscar="+$("#numeroCotizacion").val()+"&filtroEstatus="+filtroEstatus+"&page="+paginacion+"&empresa="+$("#empresa").val();
		    	RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "datagridCotizacion");
				paginador();
			}
		});
		function actualizaDatagrid()
		{
			//paginador();

			var variable = "accion=index&buscar="+$("#numeroCotizacion").val()+"&filtroEstatus="+filtroEstatus+"&page="+paginacion+"&empresa="+$("#empresa").val();
            RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "datagridCotizacion");
		}
		function pages(Obj, value)
        {
            $(".pagination li").removeClass("active");
            $(Obj).addClass("active");
            paginacion = value;
            actualizaDatagrid();
        }

        function cambiaBuscador(valor)
        {
        	filtroEstatus = valor;
        	actualizaDatagrid();
        }
		
		function CargaEstatus(response)
		{
			
			$.each(response, function(index, value)
			{
				
				var contador = 0;
				var arreglo = Array();
				$.each(value, function(index2, value2)
				{
					arreglo[contador] = value2;	
					contador++;	
				});
				$("#estatus").append("<option value='"+arreglo[0]+"'>"+arreglo[1]+"</option>");
				
			});
			
		}
		
		function paginador()
		{

			var variable = "accion=counter&buscar="+$("#numeroCotizacion").val()+"&filtroEstatus="+filtroEstatus+"&empresa="+$("#empresa").val();
			RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "creaPaginador");	
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

		function datagridCotizacion(response)
		{
            console.log(response);
			//actualizaProcesos();
            paginador();
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				var id = value['DOCTOS_VE.DOCTO_VE_ID'];
				linea = $("<tr id='filaCotizacion_"+id+"'></tr>");
				var campos = "";	

				var combo = "<SELECT class='form-control' id='cotizacion_"+id+"' onchange='cambiaEstatus("+id+",this.value, "+value['EMPRESA']+");'><OPTION VALUE='0'>ESTATUS</OPTION><OPTION VALUE='1'>CANCELADO</OPTION><OPTION VALUE='2'>EN GESTION</OPTION><OPTION VALUE='3'>AUTORIZADO</OPTION></SELECT>";
				campos += "<td>"+combo+"</td>";
                campos += "<td>"+value['NOMBREEMPRESA']+(parseInt(value['DOCTOS_VE.FOLIO']))+"</td>";
                campos += "<td>"+value['DOCTOS_VE.FECHA']+" / "+value['SEGUIMIENTOCOTIZACION.MODIFICADO_AL']+"</td>";
                campos += "<td><i style='color:#E21800'>"+value['CLIENTES.NOMBRE']+"</i> /<BR><b>"+value['DOCTOS_VE.DESCRIPCION']+"</b></td>";
                //campos += "<td>"+value['DOCTOS_VE.DESCRIPCION']+"</td>";
                campos += "<td>"+value['OPERADOR.ALIAS']+"</td>";
                campos += "<td>$ "+value['DOCTOS_VE.IMPORTE_NETO']+"</td>";
                campos += "<td><button type='button' class='btn btn-strech btn-default' title='OBSERVACIONES' onClick='observaciones(this, "+id+", "+value['EMPRESA']+")'><i class='fa fa-comment'></i></td>";
               
               linea.append(campos);
				
				contadorMensajes(id, value['EMPRESA']);

				datagrid.append(linea);
				contador++;

				if(value['SEGUIMIENTOCOTIZACION.IDESTATUS']!="" && value['SEGUIMIENTOCOTIZACION.IDESTATUS']!=0)
				{
					if(value['SEGUIMIENTOCOTIZACION.IDESTATUS']==1)
						clase = "danger";
					else if(value['SEGUIMIENTOCOTIZACION.IDESTATUS'] == 2)
						clase = "info";
					else if(value['SEGUIMIENTOCOTIZACION.IDESTATUS'] == 3)
						clase = "success";
					$("#filaCotizacion_"+id).addClass(clase);
					$("#cotizacion_"+id).val(value['SEGUIMIENTOCOTIZACION.IDESTATUS']);
				}
				
				
				
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

		function reporte()
		{

			window.open('reportes/closesales/ReporteCloseSales.php?tipo='+$("#estatus").val()+"&empresa="+$("#empresa").val(),'_blank')
		}
		function cambiaBuscador(valor)
		{
			filtroEstatus = valor;
			actualizaDatagrid();
		}

		function observaciones(obj, id, emp)
		{
			empresa = emp;
			$("#iddocto_ve_id").val(id);

			var variable = "accion=observaciones&empresa="+empresa+"&id="+id;
			RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "cargaObservaciones");
		}

		function cargaObservaciones(Response)
		{
			var contador = 0;
			$("#descripcionobservaciones").find("tr").remove();
			$("#observaciones").modal("show");
			$.each(Response, function(index, value)
			{
				$("#descripcionobservaciones").append("<tr><td> > "+value['DESCRIPCION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['FECHA']+"</i></span></td></tr>");
				contador++;
			});
			if(contador < 1)
				$("#descripcionobservaciones").append("<tr><td>NO EXISTEN OBSERVACIONES</td></tr>");	
			$("#descripcionobservaciones").append("<tr><td colspan='2'><textarea style='resize:none' class='form-control' name='observacion'></textarea></td></tr>");
			
		}

		function guardarObservacion()
		{
			var variable = "accion=saveObservacion&empresa="+empresa+"&"+$("#formObservaciones").serialize();
		    RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "actualizaDatagrid");
		    $("#observaciones").modal("hide");
			
		}

		function contadorMensajes(id, emp)
		{
			empresa = emp;
			var variable = "accion=countMessaje&empresa="+empresa+"&id="+id;
		    RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "cargaCountMessaje");
		}

		function cargaCountMessaje(Response)
		{
			if(Response[0].count > 0)
				$("#filaCotizacion_"+Response[0].ID).find("button:eq(0)").removeClass("btn-info").addClass("btn-primary");	
			$("#filaCotizacion_"+Response[0].ID).find("button:eq(0)").append(Response[0].count);
		}

		function cambiaEstatus(id, valor, empresa)
		{
			var clase = ""; 
			if(valor==1)
				clase = "danger";
			else if(valor == 2)
				clase = "info";
			else if(valor == 3)
				clase = "success";
			$("#filaCotizacion_"+id).removeClass("danger").removeClass("info").removeClass("success").addClass(clase);

			var variable = "accion=cambiaEstatus&empresa="+empresa+"&id="+id+"&valor="+valor;
            RestFullRequest("_Rest/SeguimientoCotizacion.php", variable, "actualizaDatagrid");
		}
		
        function creaPaginador(Response)
        {
            console.log(Response);
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

        function ventana_ventas()
        {
            $("#ventas").modal("show");
        }

       	$(document).ready(function(e) {
			actualizaDatagrid();
			actualizaProcesos();
            $("#call").find("a").click();
            //setInterval(actualizaDatagrid,  900000);
        });