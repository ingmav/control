		function actualizaDatagrid()
		{

			var variable = "accion=index&"+$("#datagridCaja").serialize();
            RestFullRequest("_Rest/Caja.php", variable, "datagridCaja");
		}

		/*$("#formAgregaCaja #empresa").on("change", function()
		{
            if($("#formAgregaCaja #folio") != "" || $("#formAgregaCaja #descripcion") != "")
            {
                //var variable = "accion=cargacotizacion&"+$("#formAgregaCaja").serialize();
                //RestFullRequest("_Rest/Caja.php", variable, "rellenaCaja");    
            }
			
		});*/

		/*$("#formAgregaCaja #folio").on("blur", function()
		{

			var variable = "accion=cargacotizacion&"+$("#formAgregaCaja").serialize();
            RestFullRequest("_Rest/Caja.php", variable, "rellenaCaja");
		});

        $("#formAgregaCaja #tipo").on("change", function()
        {
            if($("#formAgregaCaja #folio") != "" || $("#formAgregaCaja #descripcion") != "")
            {
                var variable = "accion=cargacotizacion&"+$("#formAgregaCaja").serialize();
                RestFullRequest("_Rest/Caja.php", variable, "rellenaCaja");
            }
        });*/
		
		function rellenaCaja(Response)
		{
			$("#formAgregaCaja #descripcion").val(Response[0]['DOCTOS_VE.DESCRIPCION']);
			$("#formAgregaCaja #importe").val(Response[0]['DOCTOS_VE.IMPORTE_NETO']);
			$("#formAgregaCaja #cliente").val(Response[0]['CLIENTES.NOMBRE']);
		}

		function datagridCaja(response)
		{
			console.log(response);
			var contador = 0;
			var datagrid = $("#data");
			//actualizaProcesos();
			
			datagrid.find("tr").remove();
			var bandera = 0;
			$.each(response, function(index, value)
			{
				
				linea = $("<tr></tr>");
				var campos = "";
				
				campos+="<td>"+value['CAJA.FOLIO']+"-"+value['CAJA.TIPODOCTO']+"</td>";
				campos+="<td>"+value['CAJA.FECHA']+"</td>";
				campos+="<td>"+value['CAJA.CLIENTE']+"</td>";
				
				campos+="<td>"+value['CAJA.DESCRIPCION']+"</td>";
                campos+="<td>"+value['CAJA.TIPOLOGIA']+"</td>";
				
				campos+="<td align='right'>"+value['CAJA.IMPORTE']+"</td>";
                if(value['CAJA.TIPODOCTO'] != "S")
                    if(value['CAJA.TIPODOCTO'] != "T")    
				        campos += "<td align='center'><input type='checkbox' name='id[]' value='"+value['CAJA.ID']+"'></td>";
                    else
                        campos += "<td align='center'></td>";
                else
                    campos += "<td align='center'></td>";
				linea.append(campos);
				datagrid.append(linea);
				bandera=1;
				

				//if(value['total'] >= 0)
					
				contador++;
			});
            console.log(contador);
            //linea_final = "<tr><td  colspan='5'></td><td>"+response[0]['total']+"</td><td colspan='3'></td></tr>";
			//xdatagrid.append(linea_final);
			if(contador == 0)
				datagrid.append("<tr><td colspan='3'></td><td>$ 0.00</td><td colspan='3'></td></tr>");
		}
		
		$(document).ready(function(e) {
			actualizaDatagrid();
			verificaInicioCaja();
            actualizaVentas();
            $("#control").find("a").click();
            //setInterval(actualizaDatagrid,  900000);
        });

        function reporte()
        {
            $("#datagridCaja").attr("action","ReporteCaja.php");
            $("#datagridCaja").attr("method","POST");
            $("#datagridCaja").attr("target","_blank");
            $("#datagridCaja").submit();
            $("#datagridCaja").attr("action","");
            $("#datagridCaja").attr("method","");
            $("#datagridCaja").attr("target","");
            //$("#FormDatagrid").attr("src","").attr("target","");
        }

        function guardarCaja()
        {
        	var variable = "accion=agregaCaja&"+$("#formAgregaCaja").serialize();
            RestFullRequest("_Rest/Caja.php", variable, "actualizaDatagrid", 1);
            $("#agregar").modal("hide");
            $("#formAgregaCaja #importeTotal").html("$ 0.00");
            $("#formAgregaCaja #importeAnticipo").html("$ 0.00");
            $("#formAgregaCaja #importeSaldo").html("$ 0.00");
        }

        function sustraerCaja()
        {
        	var variable = "accion=sustraerCaja&"+$("#formSustraeCaja").serialize();
            RestFullRequest("_Rest/Caja.php", variable, "actualizaDatagrid");
            $("#sustraer").modal("hide");
        }

        function verificaInicioCaja()
        {
        	var variable = "accion=verificaInicioCaja";
            RestFullRequest("_Rest/Caja.php", variable, "validaInicioCaja");
        }

        function validaInicioCaja(Response)
        {
        	
        	if(Response.PAGINADOR == 0)
        	{
        		$("#agregarcaja").hide();
        		$("#cancelacaja").hide();
        		$("#disminuircaja").hide();
                $("#reportecaja").hide();
        	}else
        	{
        		$("#iniciarcaja").hide();
        		$("#agregarcaja").show();
        		$("#cancelacaja").show();
        		$("#disminuircaja").show();
                $("#reportecaja").show();
        	}
        	actualizaDatagrid();
        }

        function inicializaCaja()
        {
        	var variable = "accion=inicializarCaja";
            RestFullRequest("_Rest/Caja.php", variable, "verificaInicioCaja");
        }

        $( "#foliocaja" ).keypress(function(e) {
           
            if(e.keyCode == 13)
            {
                var variable = "accion=importe&empresa="+$("#empresa").val()+"&tipo="+$("#tipo").val()+"&folio="+$("#foliocaja").val()+"&descripcion="+$("#descripcioncaja").val();
                RestFullRequest("_Rest/Caja.php", variable, "cargaImporte");
            }
        });

        $( "#descripcioncaja" ).keypress(function(e) {
           
            if(e.keyCode == 13)
            {
                $(this).val($(this).val().toUpperCase());
                var variable = "accion=importe&empresa="+$("#empresa").val()+"&tipo="+$("#tipo").val()+"&folio="+$("#foliocaja").val()+"&descripcion="+$("#descripcioncaja").val();
                RestFullRequest("_Rest/Caja.php", variable, "cargaImporte");
            }
        });

        $("#empresa").on("change", function()
            {
                if($("#formhelper #foliocaja").val() != "" || $("#formhelper #descripcioncaja").val() != "")
                {
                    var variable = "accion=importe&empresa="+$("#empresa").val()+"&tipo="+$("#tipo").val()+"&folio="+$("#foliocaja").val()+"&descripcion="+$("#descripcioncaja").val();
                    RestFullRequest("_Rest/Caja.php", variable, "cargaImporte");
                }   
            });

        $("#tipo").on("change", function()
            {
               
                if($("#formhelper #foliocaja").val() != "" || $("#formhelper #descripcioncaja").val() != "")
                {
                    var variable = "accion=importe&empresa="+$("#empresa").val()+"&tipo="+$("#tipo").val()+"&folio="+$("#foliocaja").val()+"&descripcion="+$("#descripcioncaja").val();
                    RestFullRequest("_Rest/Caja.php", variable, "cargaImporte");  
                } 
            });

        function cargaImporte(Response)
        {
            $("#registros").html("");
            $.each(Response, function(index, value)
            {
                var color = "";
                if((index%2)==0)
                    color = "style='background:#EFEFEF'";
                var linea = $("<div class='row' "+color+"></div>");
                var campo1 = $("<div class='col-sm-1'><input type='checkbox' name='seleccionardocumento' value='"+value['DOCTOS_VE.DOCTO_VE_ID']+"'></div>");
                var campo2 = $("<div class='col-sm-1'><label class='control-label'>"+value['DOCTOS_VE.FOLIO']+"</label></div>");
                var campo3 = $("<div class='col-sm-4'><label class='control-label'>"+value["CLIENTES.NOMBRE"]+"</label></div>");
                var campo4 = $("<div class='col-sm-4'><label class='control-label'>"+value['DOCTOS_VE.DESCRIPCION']+"</label></div>");
                var campo5 = $("<div class='col-sm-1' align='right'><label class='control-label'>"+value['DOCTOS_VE.IMPORTE_NETO']+"</label></div>");
                var campo6 = $("<div class='col-sm-1' align='right'><label class='control-label'>"+value['ANTICIPO']+"</label></div>");
                linea.append(campo1);
                linea.append(campo2);
                linea.append(campo3);
                linea.append(campo4);
                linea.append(campo5);
                linea.append(campo6);
                $("#registros").append(linea);

            });
        }


        function cancelaCaja()
        {
        	if(confirm("¿Realmente desea borrar el registro?"))
        	{
        		var variable = "accion=cancelaCaja&"+$("#datagridCaja").serialize();
            	RestFullRequest("_Rest/Caja.php", variable, "actualizaDatagrid", 2);
        	}
        		
        }
        
        function limpia(form)
        {
        	$("#"+form).find("input").val("");
            $("#"+form).find("textArea").val("");

        }

        function seleccionarDocumento()
        {
            var seleccion = 0;
            $("#formhelper #registros input[type=checkbox]:checked").each(function()
            {
                seleccion = $(this).val();
            });

            if(seleccion!=0)
            {

                $("#formAgregaCaja #docto_ve").val(seleccion);
                $("#formAgregaCaja #empresaCaja").val($("#formhelper #empresa").val());
                $("#formAgregaCaja #tipoDocumento").val($("#formhelper #tipo").val());

                var variable = "accion=consultaCaja&docto_ve="+seleccion+"&empresa="+$("#formhelper #empresa").val();
                RestFullRequest("_Rest/Caja.php", variable, "rellenaCajaConsulta");
                $("#helper").modal("hide");
                $("#registros").html("");
            }else
            alert("DEBE DE SELECCIONAR UN REGISTRO");
        }

        function rellenaCajaConsulta(Response)
        {
            $("#formAgregaCaja #folio").val(Response[0]['DOCTOS_VE.FOLIO']);
            $("#formAgregaCaja #cliente").val(Response[0]['CLIENTES.NOMBRE']);
            $("#formAgregaCaja #importe").val(Response[0]['RESTO']);
            $("#formAgregaCaja #descripcion").val(Response[0]['DOCTOS_VE.DESCRIPCION']);

            $("#formAgregaCaja #importeTotal").html("$ "+ Response[0]['DOCTOS_VE.IMPORTE_NETO']);
            $("#formAgregaCaja #importeAnticipo").html("$ "+ Response[0]['ANTICIPO']);
            $("#formAgregaCaja #importeSaldo").html("$ "+ Response[0]['RESTO']);

            
        }

        function verBuscador()
        {
            $('#helper').modal('show');
            limpia("formhelper");
            $("#formhelper #registros").html("");
        }

        function cerrar_caja()
        {
            var variable = "accion=Cajanocerrada";
            RestFullRequest("_Rest/Caja.php", variable, "actualizaDatagrid", 1);
        }