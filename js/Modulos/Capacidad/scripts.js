		function actualizaDatagrid()
		{

			var variable = "accion=index&fechaInicio="+$("#fechadesde").val()+"&fechaHasta="+$("#fechafin").val();
            RestFullRequest("_Rest/Capacidad.php", variable, "datagridCapacidad");
		}

		function datagridCapacidad(response)
		{

			var contador = 0;
			var datagrid = $("#Capacidad");

			datagrid.find("tr").remove();

			$.each(response, function(index, value)
			{
                var campos = "";
                linea = $("<tr>");
                $.each(value, function(index2, value2)
                {
                    campos+="<td>"+value2+"</td>";
                });
                 linea.append(campos);
                 datagrid.append(linea);

                 contador++;

			});

			if(contador == 0)
				datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
		}
		function reporte()
		{
			$("#formcapacidad").attr("action","reportes/capacidad/ReporteGeneral.php");
            $("#formcapacidad").attr("method","POST");
            $("#formcapacidad").attr("target","_blank");
            $("#formcapacidad").submit();
            $("#formcapacidad").attr("action","");
            $("#formcapacidad").attr("method","");
            $("#formcapacidad").attr("target","");
		}

		function reporteVentas()
		{
			$("#formcapacidad").attr("action","reportes/capacidad/ReporteVentasMensuales.php");
            $("#formcapacidad").attr("method","POST");
            $("#formcapacidad").attr("target","_blank");
            $("#formcapacidad").submit();
            $("#formcapacidad").attr("action","");
            $("#formcapacidad").attr("method","");
            $("#formcapacidad").attr("target","");
		}
		$(document).ready(function(e) {
			actualizaDatagrid();
            $("#indicadores").find("a").click();
            //setInterval(actualizaDatagrid,  900000);
        });

        function ver_ventas()
        {
            $("#myModal").modal("show");
            var variable = "accion=ventas&fechaInicio="+$("#fechadesde").val()+"&fechaHasta="+$("#fechafin").val();
            RestFullRequest("_Rest/Capacidad.php", variable, "datagridVentas");
        }

        function datagridVentas(Respone)
        {
            var contador = 0;
            var datagrid = $("#desglose");

            datagrid.find("tr").remove();

            $.each(Respone, function(index, value)
            {
                console.log(value.dia);
                var campos = "";
                linea = $("<tr>");
                campos += "<td>"+value.dia+"</td>";
                campos += "<td>"+value.EMPRESA+"</td>";
                campos += "<td>"+value.suma+"</td>";

                linea.append(campos);
                datagrid.append(linea);

                contador++;

            });

            if(contador == 0)
                datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
        }
