var paginacion = 1;
var realizados = 0;


		$("#guardarActividad").on("click", function()
		{
			if($("#id").val() > 0)
			{
				var variable = "accion=update&"+$("#FormActividad").serialize();
				RestFullRequest("_Rest/Extra.php", variable, "agregaRegistro", 1);
			}else
			{
				var variable = "accion=save&"+$("#FormActividad").serialize();
				RestFullRequest("_Rest/Extra.php", variable, "agregaRegistro", 1);
			}
		});

		$("#buscar").on("click", function()
		{
			actualizaDatagrid();
		});

		function agregaRegistro(response)
		{
			$("#AgregarExtra").modal("hide");
			actualizaDatagrid();
		}
		
		$("#Agregar").on("click", function()
		{
			$("#AgregarExtra").modal("show");
			limpiaForm($("#FormActividad"));
		});
		
		$("#eliminar").on("click", function()
		{
			var contador = 0;
			$("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
                contador++;
            });
			
			if(contador > 0)
			{
				if(confirm("¿REALMENTE DESEA ELIMINAR EL/LOS REGISTROS?"))
				{
					var variable = "accion=eliminar&"+$("#FormDatagrid").serialize();
					RestFullRequest("_Rest/Extra.php", variable, "actualizaDatagrid",2);
				}
			}else
				alert("DEBE DE SELECCIONAR UN REGISTRO");
		});
		
		$("#reporte").on("click", function()
		{
			$("#FormDatagrid").attr("action", "reportes/extras/ReporteActividad.php");
			$("#FormDatagrid").attr("target", "_blank");
			$("#FormDatagrid").attr("method", "post");
			$("#FormDatagrid").submit();
			$("#FormDatagrid").attr("action", "");
			$("#FormDatagrid").attr("target", "");
		});

		$("#editar").on("click", function()
		{
			
			var contador = 0;
			$("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
                contador++;
            });
			
			if(contador > 0)
			{
				var variable = "accion=modificar&"+$("#FormDatagrid").serialize();
				RestFullRequest("_Rest/Extra.php", variable, "CargaActividad");
				$("#AgregarExtra").modal("show");
			}else
				alert("DEBE DE SELECCIONAR UN REGISTRO");
		});
		
		function CargaActividad(response)
		{
			$("#id").val(response[0].ID);
			$("#fechaActividad").val(response[0].FECHA);
			$("#desdeActividad").val(response[0].DE);
			$("#hastaActividad").val(response[0].A);
            $("#responsable").val(response[0].RESPONSABLE);
            $("#colaboradores").val(response[0].COLABORADORES);
            $("#actividad").val(response[0].ACTIVIDAD);
            $("#reviso").val(response[0].REVISO);

	
		}
		
		function actualizaDatagrid()
		{

			var variable = "accion=index&"+$("#FormBuscar").serialize();
            RestFullRequest("_Rest/Extra.php", variable, "datagridCotizacion");
		}

        function paginador()
        {

            var variable = "accion=counter";
            RestFullRequest("_Rest/Extra.php", variable, "creaPaginador");
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
		
		function cargaOperador(response)
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
				$("#reviso").append("<option value='"+arreglo[0]+"'>"+arreglo[1]+"</option>");
				
			});
			
		}
		
		function limpiaForm(Formulario)
		{
			$(Formulario).find('select').each(function(index, element) {
               $(this).find("option").eq(0).prop('selected', true); 
            });
			
			$(Formulario).find("input[type=text]").val("");
			$(Formulario).find("textArea").val("");
			$(Formulario).find("input[type=text]").val("");
			$(Formulario).find("input[type=date]").val("");
		}
		
		function datagridCotizacion(response)
		{
			console.log(response);
			actualizaProcesos();
            paginador();
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				linea = $("<tr data-fila='"+value['ACTIVIDADEXTRA.ID']+"'></tr>");
				var campos = "";
				campos += "<td>"+value['ACTIVIDADEXTRA.FECHA']+"<BR>"+value['ACTIVIDADEXTRA.DE']+" - "+value['ACTIVIDADEXTRA.A']+"</td>";
                //campos += "<td>"+value['ACTIVIDADEXTRA.DE']+" - "+value['ACTIVIDADEXTRA.A']+"</td>";
                campos += "<td><b style='color:RED;'>"+value['ACTIVIDADEXTRA.RESPONSABLE']+"</b><BR><b style='color:BLUE;'>"+value['ACTIVIDADEXTRA.COLABORADORES']+"</b><BR><b>"+value['ACTIVIDADEXTRA.ACTIVIDAD']+"</b></td>";
                //campos += "<td>"+value['ACTIVIDADEXTRA.ACTIVIDAD']+"</td>";
                campos += "<td>"+value['OPERADOR.NOMBRE']+"</td>";

                clase = "default";
                if(value['OBSERVACIONES'] > 0)
                	clase = "primary";
                campos += "<td><button type='button' class='btn btn-strech btn-"+clase+"' title='OBSERVACIONES' onclick='observaciones(this)'><i class='fa fa-comment'></i>"+value['OBSERVACIONES']+"</button></td>";
                campos += "<td><INPUT TYPE='checkbox' name='id[]' value='"+value['ACTIVIDADEXTRA.ID']+"'></td>";

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

       
		$(document).ready(function(e) {
			actualizaDatagrid();
            setInterval(actualizaDatagrid,  900000);
            $("#operacion").find("a").click();
            
            var variable = "accion=cargaOperador";
            RestFullRequest("_Rest/Extra.php", variable, "cargaOperador");
        });

        function observaciones(obj)
		{	
			id = $(obj).parents("tr").data("fila");
			$("#formObservaciones #idactividad").val(id);
			var variable = "accion=observaciones&id="+id;
		    RestFullRequest("_Rest/Extra.php", variable, "cargaObservaciones");
		}

		function cargaObservaciones(Response)
		{
			var contador = 0;
			$("#formObservaciones #descripcionobservaciones").find("tr").remove();
			$(" #observaciones").modal("show");
			$.each(Response, function(index, value)
			{
				$("#formObservaciones #descripcionobservaciones").append("<tr><td> > "+value['ACTIVIDADEXTRANOTAS.NOTA']+"</td><td> > "+value['OPERADOR.ALIAS']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['ACTIVIDADEXTRANOTAS.FECHA']+"</i></span></td></tr>");
				contador++;
			});
			if(contador < 1)
				$("#formObservaciones #descripcionobservaciones").append("<tr><td>NO EXISTEN OBSERVACIONES</td></tr>");	
			$("#formObservaciones #descripcionobservaciones").append("<tr><td colspan='3'><textarea style='resize:none' class='form-control' name='observaciones'></textarea></td></tr>");
		}

		function guardarObservacion()
		{
			var variable = "accion=saveObservacion&"+$("#formObservaciones").serialize();
		    
		    RestFullRequest("_Rest/Extra.php", variable, "actualizaDatagrid", 1);
		    $("#observaciones").modal("hide");	
		}