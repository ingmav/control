		var empresa = 1;
		var paginacion = 1;
		var buscar = "";
		var noStarted = 0;
		var finish = 0;
		var pTablero = 0;
        var folio_s = "";
        var fecha_s = "";
        var cliente_s = "";
        var descrip_s = "";
        var estatus_tablero = 0;
        var obj_tablero;


		
		$( "#search" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				buscar = $("#search").val();
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish+"&pTablero="+pTablero+"&eTablero="+estatus_tablero;
            	RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
				paginador();
			}
		});

		$( "#client" ).keypress(function(e) {
			if(e.keyCode == 13)
			{
				paginacion = 1;
				
				var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish+"&pTablero="+pTablero+"&eTablero="+estatus_tablero;
            	RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
				paginador();
			}
		});

        function reporte_tablero()
        {
            $("#filter_tablero").submit();
        }

		function datagridSeleccionDocumento(response)
		{
			var vlength = 0;
			var datagrid = $("#data");
			datagrid.html("");
			var contador = 0;
            var pestana = 0;
            obj_tablero = response;

            $.each(response, function(index, value)
            {
                var id = value['ID'];
                var linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
                var campos = "";
                campos += "<td>"+value['EMPRESA']+value['TIPO_DOCTO']+"-"+parseInt(value['FOLIO'])+"<br><button type='button' class='btn btn-strech btn-danger' onclick='desbloquear("+id+",2)'><i class='fa fa-close'></i></button><button type='button' class='btn btn-strech btn-info' onclick='verInformacion("+value['DOCTO_VE_ID']+",2,"+index+")'><i class='fa fa-info'></i></button></td>";
                campos += "<td>"+value['FECHA']+"<BR><span class='label label-danger'>"+value['FECHA_ENTREGA_TRABAJO']+"</span></td>";
                campos += "<td>"+value['NOMBRE']+"<br>"+value['DESCRIPCION']+"</td>";
                
                var estatus = "btn-default";
                var btn_diseno = "";    
                var estatus_general = 0;
                var validador_general = 0;
                if(value['GF_DISENO']==1){
                    if(value['ESTATUS_DISENO'] == 1){
                        estatus = "btn-success";
                        validador_general++;
                    }
                    else
                        estatus = "btn-danger";

                    btn_diseno = "<button type='button'  class='btn "+estatus+" btn-strech'><span class='fa fa-check '></span></button>";
                    estatus_general++;
                }
                campos += "<td>"+btn_diseno+"</td>";
                
                var estatus = "btn-default";
                var btn_impresion = "";    
                if(value['GF_IMPRESION']==1){
                    if(value['ESTATUS_IMPRESION'] == 1){
                        estatus = "btn-success";
                        validador_general++;
                    }
                    else
                        estatus = "btn-danger";
                    
                    estatus_general++;
                    btn_impresion = "<button type='button'  class='btn "+estatus+" btn-strech'><span class='fa fa-check '></span></button>";
                }
                campos += "<td>"+btn_impresion+"</td>";

                var estatus = "btn-default";
                var btn_maquilas = "";    
                if(value['GF_MAQUILAS']==1){
                    if(value['ESTATUS_MAQUILAS'] == 1){
                        estatus = "btn-success";
                        validador_general++;
                    }
                    else
                        estatus = "btn-danger";

                    estatus_general++;
                    btn_maquilas = "<button type='button'  class='btn "+estatus+" btn-strech'><span class='fa fa-check '></span></button>";
                }
                campos += "<td>"+btn_maquilas+"</td>";

                var estatus = "btn-default";
                var btn_preparacion = "";    
                if(value['GF_PREPARACION']){
                    if(value['ESTATUS_PREPARACION'] == 1)
                    {
                        estatus = "btn-success";
                        validador_general++;
                    }
                    else
                        estatus = "btn-danger";

                    estatus_general++;
                    btn_preparacion = "<button type='button'  class='btn "+estatus+" btn-strech'><span class='fa fa-check '></span></button>";
                }
                campos += "<td>"+btn_preparacion+"</td>";

                var estatus = "btn-default";
                var btn_instalacion = "";    
                if(value['GF_INSTALACION']){
                    if(value['ESTATUS_INSTALACION'] == 1){
                        estatus = "btn-success";
                        validador_general++;
                    }
                    else
                        estatus = "btn-danger";

                    estatus_general++;
                    btn_instalacion = "<button type='button'  class='btn "+estatus+" btn-strech'><span class='fa fa-check '></span></button>";
                }
                campos += "<td>"+btn_instalacion+"</td>";

                var estatus = "btn-default";
                var btn_entrega = "";    
                if(value['GF_ENTREGA']){
                    if(value['ESTATUS_ENTREGA'] == 1){
                        estatus = "btn-success";
                        validador_general++;
                    }
                    else
                        estatus = "btn-danger";

                    estatus_general++;
                    btn_entrega = "<button type='button'  class='btn "+estatus+" btn-strech'><span class='fa fa-check '></span></button>";
                }
                campos += "<td>"+btn_entrega+"</td>";

                if(estatus_general == validador_general)
                    campos += "<td><input type='checkbox' name='proceso_tablero[]' value='"+id+"'></td>";
                else
                    campos += "<td></td>";
                
                if(pTablero == 0)
                {
                    if(estatus_tablero == 0)
                    {
                        linea.append(campos);
                        datagrid.append(linea);                        
                    }else if(estatus_tablero == 1 && validador_general == 0)
                    {
                        linea.append(campos);
                        datagrid.append(linea);         
                    }else if(estatus_tablero == 2 && validador_general == estatus_general)
                    {
                        linea.append(campos);
                        datagrid.append(linea);  
                    }
                }else{
                    linea.append(campos);
                    datagrid.append(linea);  
                }
                
            });
            /*if(response['monto_total'] > 0)
                $("#Desglose_Monto").text("$ "+moneda(response['monto_total'], 2, [',', "'", '.']));
            
            vlength = response['pagesnum'];
			$.each(response, function(index, value)
			{
                if(pestana<20)
                {

                    if(index != "pagesnum" && index!= "monto_total")
                    {

                        var campos = "";
                        var id = value['TABLEROPRODUCCION.ID'];
                        var idEmpresa = value['IDEMPRESA'];

                        var color;
                        if(value['RESTANTE_ENTREGA'] == 0 || value['RESTANTE_ENTREGA'] < 0)
                            color = "danger'";
                        if(value['RESTANTE_ENTREGA'] == 1)
                            color = "warning";
                        if(value['RESTANTE_ENTREGA'] > 1)
                            color = "success'";


                        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");
                        colorEstatus = "success";

                        costo = moneda(value['DOCTOS_VE.IMPORTE_NETO'], 2, [',', "'", '.']);
                        var dato_monto = "";
                        
                        if(costo > 0)
                            dato_monto = "<b>Monto: $"+costo+"</b>";    
                        campos += "<td>"+value['NOMBREEMPRESA']+"-"+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-<span class='"+colorEstatus+"'>"+value['DOCTOS_VE.ESTATUS']+"</span><br><button type='button' class='btn btn-strech btn-primary' onclick=\"verInformacion("+id+","+idEmpresa+", '"+value['NOMBREEMPRESA']+"-"+value['DOCTOS_VE.TIPO_DOCTO']+"-"+parseInt(value['DOCTOS_VE.FOLIO'])+"-"+value['DOCTOS_VE.ESTATUS']+"', '"+value['TABLEROPRODUCCION.FECHA']+"','"+value['CLIENTES.NOMBRE']+"', '"+value['DOCTOS_VE.DESCRIPCION']+"')\"><i class='fa fa-info'></i></buttton>"+" "+"<button type='button' class='btn btn-strech btn-danger' onclick='desbloquear("+id+","+idEmpresa+")'><i class='fa fa-close'></i></buttton></td>";
                        campos += "<td>  "+value['TABLEROPRODUCCION.FECHA']+"<BR><label class='label label-"+color+"'> "+value['TABLEROPRODUCCION.FECHA_ENTREGA']+"</label><br><br>"+dato_monto+"</td>";
                        //campos += "<td>"+value['CLIENTES.NOMBRE']+"</td>";

                        var notas = "";
                        if(value['TABLEROPRODUCCION.NOTA'] !="")
                            notas = "<br>-"+value['TABLEROPRODUCCION.NOTA'];

                        campos += "<td>"+value['CLIENTES.NOMBRE']+"<br>"+value['DOCTOS_VE.DESCRIPCION']+notas+"</td>";

                        indice = 0;

                        var estatusdiseno = "danger";
                        var estatusimpresion = "danger";
                        var estatusinstalacion = "danger";
                        var estatusentrega = "danger";
                        var estatusprogramacion = "danger";
                        var estatusmaquilas = "danger";
                        var estatuspreparacion = "danger";
                        var numeroProcesos  = value['produccion'];


                        $.each(value['produccion'], function(index2, value2)
                        {
                            switch(value2['PRODUCCION.IDDEPARTAMENTO'])
                            {
                                case '2': if(value2['PRODUCCION.IDESTATUS']==2){ estatusdiseno = "success"; numeroProcesos--;}else{ estatusdiseno = "danger"; } break;
                                case '3': if(value2['PRODUCCION.IDESTATUS']==2){ estatusimpresion = "success"; numeroProcesos--; }else{ estatusimpresion = "danger"; } break;
                                case '4': if(value2['PRODUCCION.IDESTATUS']==2){ estatusinstalacion = "success"; numeroProcesos--; }else{ estatusinstalacion = "danger"; } break;
                                case '6': if(value2['PRODUCCION.IDESTATUS']==2){ estatusentrega = "success"; numeroProcesos--; }else{ estatusentrega = "danger"; } break;
                                case '7': if(value2['PRODUCCION.IDESTATUS']==2){ estatusprogramacion = "success"; numeroProcesos--; }else{ estatusprogramacion = "danger"; } break;
                                case '8': if(value2['PRODUCCION.IDESTATUS']==2){ estatusmaquilas = "success"; numeroProcesos--; }else{ estatusmaquilas = "danger"; } break;
                                case '9': if(value2['PRODUCCION.IDESTATUS']==2){ estatuspreparacion = "success"; numeroProcesos--; }else{ estatuspreparacion = "danger"; } break;
                            }
                            indice++;
                        });

                        if(value['TABLEROPRODUCCION.DISENO'] == 1)
                            campos += "<td style='max-width: 27px'><button type='button'  class='btn btn-"+estatusdiseno+" btn-strech'><span class='fa fa-check '></span></button></td>";
                        else
                            campos += "<td></td>";

                        if(value['TABLEROPRODUCCION.IMPRESION'] == 1)
                            campos += "<td style='max-width: 27px'><button type='button'  class='btn btn-"+estatusimpresion+" btn-strech' id><span class='fa fa-check '></span></button></td>";
                        else
                            campos += "<td></td>";

                        if(value['TABLEROPRODUCCION.MAQUILAS'] == 1)
                            campos += "<td style='max-width: 27px'><button type='button'  class='btn btn-"+estatusmaquilas+" btn-strech'><span class='fa fa-check '></span></button></td>";
                        else
                            campos += "<td></td>";

                        if(value['TABLEROPRODUCCION.PREPARACION'] == 1)
                            campos += "<td style='max-width: 27px'><button type='button'  class='btn btn-"+estatuspreparacion+" btn-strech' ><span class='fa fa-check '></span></button></td>";
                        else
                            campos += "<td></td>";

                        if(value['TABLEROPRODUCCION.INSTALACION'] == 1)
                            campos += "<td style='max-width: 27px'><button type='button'  class='btn btn-"+estatusinstalacion+" btn-strech' ><span class='fa fa-check '></span></button></td>";
                        else
                            campos += "<td></td>";
                        if(value['TABLEROPRODUCCION.ENTREGA'] == 1)
                            campos += "<td style='max-width: 27px'><button type='button'  class='btn btn-"+estatusentrega+" btn-strech' ><span class='fa fa-check'></span></button></td>";
                            //campos += "<td><span class='fa fa-check'></span></td>";
                        else
                            campos += "<td></td>";

                        if(value['TERMINADO'] == 1)
                            campos+= "<td style='max-width: 27px'><input type='checkbox' name='id_finalizar[]' value='"+idEmpresa+"_"+id+"'> </td>";
                        else
                            campos+= "<td></td>";


                        linea.append(campos);

                        datagrid.append(linea);
                        contador++;
                        pestana++;
                        }
                    }
				});

            creaPaginador(vlength);
				if(contador == 0)
					datagrid.append("<tr><td colspan='11'>NO SE ENCUENTRAN REGISTROS</td></tr>");*/
			
		}
		
		function verInformacion(id, emp, index)
		{
            //, folio, fecha, cliente, descripcion
            console.log(obj_tablero[index]['ID']);
            $("#panel_informacion").html("");
            folio_s = obj_tablero[index]['FOLIO'];
            fecha_s = obj_tablero[index]['FECHA'];
            cliente_s = $.trim(obj_tablero[index]['CLIENTE']);
            descrip_s = $.trim(obj_tablero[index]['DESCRIPCION']).replace("\n"," ");
			$("#informacion").modal("show");
			var variable = "accion=informacion&empresa="+emp+"&id="+obj_tablero[index]['ID'];
            RestFullRequest("_Rest/Finalizados.php", variable, "CargaInformacion");

            var variable = "accion=informacion_extra&empresa="+emp+"&id="+id;
            RestFullRequest("_Rest/Finalizados.php", variable, "CargaInformacionExtra");
		}

        function CargaInformacionExtra(Response)
        {
            panelInformacionExtra = "";

            panelInformacionExtra +="<div class='row' style='color: #EF1800'><div class='col-sm-2'>"+folio_s+"</div><div class='col-sm-2'>"+fecha_s+"</div><div class='col-sm-4'>"+cliente_s+"</div><div class='col-sm-4'>"+descrip_s+"</div></div>";
            panelInformacionExtra +="<div class='row' style='background: #CFCFCF'><div class='col-sm-4'>ARTICULO</div><div class='col-sm-2'>UNIDAD</div><div class='col-sm-4'>DESCRIPCION</div></div>";
            $.each(Response, function(index, value)
            {
                var estilo = "";
                if(index%2==0)
                    estilo = " style='background:#EFEFEF'";
                panelInformacionExtra +="<div class='row' "+estilo+"><div class='col-sm-4'>"+value['ARTICULOS.NOMBRE']+"</div><div class='col-sm-2'>"+parseFloat(value['DOCTOS_VE_DET.UNIDADES'])+" "+value['ARTICULOS.UNIDAD_VENTA']+"</div><div class='col-sm-4'>"+value['DOCTOS_VE_DET.NOTAS']+"</div></div>";

            });

            $("#panel_informacion").append(panelInformacionExtra);//Aqui empieza
        }

        function CargaInformacion(Response)
		{
            var tabla = $("<table class='table table-striped table-bordered'></table>");
            var linea_1 = $("<tr><td>Diseno</td><td>Impresión</td><td>Preparación</td><td>Entrega</td><td>Instalación</td><td>Maquilas</td></tr>");
            tabla.append(linea_1);
            
            var linea_2 = $("<tr></tr>");
            var contador = 0;
            var diseno = $("<td align='center'>-</td>");
            var impresion = $("<td align='center'>-</td>");
            var preparacion = $("<td align='center'>-</td>");
            var instalacion = $("<td align='center'>-</td>");
            var entrega = $("<td align='center'>-</td>");
            var maquilas = $("<td align='center'>-</td>");
            
            /*var impresion = $("<td align='center'><i class='fa fa-close'></i></td>");
            var maquilas = $("<td align='center'><i class='fa fa-close'></i></td>");
            var preparacion = $("<td align='center'><i class='fa fa-close'></i></td>");
            var instalacion = $("<td align='center'><i class='fa fa-close'></i></td>");
            var entrega = $("<td align='center'><i class='fa fa-close'></i></td>");*/
            
			if(Response[0].GF_DISENO == 1)
			{
                if(Response[0].DISENO_GF == 2)                    
                    diseno = $("<td align='center'><i class='fa fa-check'></i></td>");
                else    
                    diseno = $("<td align='center'><i class='fa fa-close'></i></td>");
                    //$("#panel_informacion").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label class='control-label' id='label-diseno'>DISEÑO:</label></div><div class='col-sm-4'><div class='form-group'><label class='control-label'>NO REALIZADO</label></div></div>");
            }
            if(Response[0].GF_IMPRESION == 1)
			{
                if(Response[0].DISENO_GF == 2)                    
                    impresion = $("<td align='center'><i class='fa fa-check'></i></td>");
                else    
                    impresion= $("<td align='center'><i class='fa fa-close'></i></td>");
                
                
            	
				//$("#panel_informacion").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label class='control-label' id='label-impresion'>IMPRESIÓN:  </label></div><div class='col-sm-4'><div class='form-group'><label class='control-label'></label></div></div>");
			}
			if(Response[0].GF_PREPARACION == 1)
			{
                if(Response[0].DISENO_GF == 2)                    
                    preparacion = $("<td align='center'><i class='fa fa-check'></i></td>");
                else    
                    preparacion = $("<td align='center'><i class='fa fa-close'></i></td>");
                
                
				//$("#panel_informacion").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label class='control-label' id='label-preparacion'>PREPARACION:  </label></div><div class='col-sm-4'><div class='form-group'><label class='control-label'></label></div></div>");
            }
            if(Response[0].GF_ENTREGA == 1)
			{
                if(Response[0].DISENO_GF == 2)                    
                    entrega = $("<td align='center'><i class='fa fa-check'></i></td>");
                else    
                    entrega = $("<td align='center'><i class='fa fa-close'></i></td>");
                
               
				//$("#panel_informacion").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label class='control-label' id='label-entrega'>ENTREGA:  </label></div><div class='col-sm-4'><div class='form-group'><label class='control-label'></label></div></div>");
			}
			if(Response[0].GF_INSTALACION == 1)
			{
                if(Response[0].DISENO_GF == 2)                    
                    instalacion = $("<td align='center'><i class='fa fa-check'></i></td>");
                else    
                    instalacion = $("<td align='center'><i class='fa fa-close'></i></td>");
                
                
				//$("#panel_informacion").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label class='control-label' id='label-instalacion'>INSTALACION:  </label></div><div class='col-sm-4'><div class='form-group'><label class='control-label'></label></div></div>");
			}
			
			
            if(Response[0].GF_MAQUILAS == 1)
			{
                if(Response[0].DISENO_GF == 2)                    
                    maquilas = $("<td align='center'><i class='fa fa-check'></i></td>");
                else    
                    maquilas = $("<td align='center'><i class='fa fa-close'></i></td>");
                
               
				//$("#panel_informacion").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label class='control-label' id='label-maquilas'>MAQUILAS:  </label></div><div class='col-sm-4'><div class='form-group'><label class='control-label'></label></div></div>");
			}
            linea_2.append(diseno);
            
            linea_2.append(impresion);
            linea_2.append(preparacion);
            linea_2.append(entrega);
            linea_2.append(instalacion);
            linea_2.append(maquilas);
            	
            tabla.append(linea_2);
            $("#panel_informacion").append(tabla);
			/*for (var i = 0; i < (Response[1].contador); i++) 
			{
				var index = i+2;
				

				switch(Response[index]['PRODUCCION.IDDEPARTAMENTO'])
				{
					case "2":
						if(Response[index]['PRODUCCION.IDESTATUS']==2)
							$("#panel_informacion #label-diseno").html("DISEÑO: "+Response[index]['OPERADOR.ALIAS']+" <i class='fa fa-clock-o'></i> <i>"+Response[index]['PRODUCCION.FECHA']+"</i>");
						else
							$("#panel_informacion #label-diseno").html("DISEÑO: NO REALIZADO");
					break;
					case "3":
						if(Response[index]['PRODUCCION.IDESTATUS']==2)
							$("#panel_informacion #label-impresion").html("IMPRESIÓN: "+Response[index]['OPERADOR.ALIAS']+" <i class='fa fa-clock-o'></i> <i>"+Response[index]['PRODUCCION.FECHA']+"</i>");
						else
							$("#panel_informacion #label-impresion").html("IMPRESIÓN: NO REALIZADO");
					break;	
					case "4":
						if(Response[index]['PRODUCCION.IDESTATUS']==2)
							$("#panel_informacion #label-instalacion").html("INSTALACIÓN: "+Response[index]['OPERADOR.ALIAS']+" <i class='fa fa-clock-o'></i> <i>"+Response[index]['PRODUCCION.FECHA']+"</i>	");
						else
							$("#panel_informacion #label-instalacion").html("INSTALACIÓN: NO REALIZADO");
					break;	
					
					case "6":
						if(Response[index]['PRODUCCION.IDESTATUS']==2)
							$("#panel_informacion #label-entrega").html("ENTREGA: "+Response[index]['OPERADOR.ALIAS']+" <i class='fa fa-clock-o'></i> <i>"+Response[index]['PRODUCCION.FECHA']+"</i>	");
						else
							$("#panel_informacion #label-entrega").html("ENTREGA: NO REALIZADO");
					break;	
					case "7":
						if(Response[index]['PRODUCCION.IDESTATUS']==2)
							$("#panel_informacion #label-preparacion").html("PREPARACION: "+Response[index]['OPERADOR.ALIAS']+" <i class='fa fa-clock-o'></i> <i>"+Response[index]['PRODUCCION.FECHA']+"</i>	");
						else
							$("#panel_informacion #label-preparacion").html("PREPARACION: NO REALIZADO");
					break;	
					case "8":
						if(Response[index]['PRODUCCION.IDESTATUS']==2)
							$("#panel_informacion #label-maquilas").html("MAQUILAS: "+Response[index]['OPERADOR.ALIAS']+" <i class='fa fa-clock-o'></i> <i>"+Response[index]['PRODUCCION.FECHA']+"</i>	");
						else
							$("#panel_informacion #label-maquilas").html("MAQUILAS: NO REALIZADO");
					break;		
				}
				
			}*/
		}

		function actualizaDatagrid()
		{
			var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish+"&pTablero="+pTablero;
            RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
			//paginador();
		}
		
		function paginador()
		{
			var variable = "accion=counter&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&iniciadas="+noStarted+"&realizadas="+finish;
			RestFullRequest("_Rest/Finalizados.php", variable, "creaPaginador");
			
		}
		
		function creaPaginador(numpages)
		{

			$(".pagination").find("li").remove();
			var paginas = Math.ceil((numpages / 20));
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

		function finalizarDocumento(id, tipo, emp)
		{
			//empresa = emp;
            if(confirm("¿REALMENTE DESEA FINALIZAR EL/LOS DOCUMENTO(S)?"))
            {

                var variable = "accion=finzalizar&"+$("#filter_tablero").serialize();
                RestFullRequest("_Rest/Finalizados.php", variable, "verificacion", 1);
            }
			/*if(tipo == 1)
			{
				if(confirm("¿REALMENTE DESEA CANCELAR EL DOCUMENTO?"))
				{
					
					var variable = "accion=finzalizar&empresa="+emp+"&page="+paginacion+"&buscar="+$("#search").val()+"&id="+id+"&tipo="+tipo;
		            RestFullRequest("_Rest/Finalizados.php", variable, "verificacion");
				}
			}else if(tipo == 2)
			{
				if(confirm("¿REALMENTE DESEA FINALIZAR EL DOCUMENTO?"))
				{
					
					var variable = "accion=finzalizar&empresa="+emp+"&page="+paginacion+"&buscar="+$("#search").val()+"&id="+id+"&tipo="+tipo;
		            RestFullRequest("_Rest/Finalizados.php", variable, "verificacion");
				}
			}*/
			
		}

		function verificacion(Response)
		{
			if(Response.error == 1)
			{
				alert("No tiene permisos  para realizar esta acción, por favor contacte a su administrador");
			}else
				actualizaDatagrid();
		}
		
		function pages(Obj, value)
		{
			$(".pagination li").removeClass("active");
			$(Obj).addClass("active");
			paginacion = value;
			actualizaDatagrid();
		}
		
		$(document).ready(function(e) {
            //actualizaProcesos();
			actualizaDatagrid();
			//setInterval(actualizaDatagrid,  900000);
        });

        function noIniciadas()
        {
        	finish = 0;
        	$("#pendientesTablero").prop("value",0);
        	if(noStarted == 0)
        	{
        		paginacion = 1;
        		noStarted = 1;
        		var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted;
            	RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
        	}else
        	{
        		paginacion = 1;
        		noStarted = 0;
        		var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted;
           		RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
        	}
        }

        function desbloquear(id, emp)
        {
        	if(confirm("¿Realmente desea Abrir nuevamente esta documento?"))
        	{
        		var variable = "accion=abrirdocumento&empresa="+emp+"&id="+id;
		        RestFullRequest("_Rest/Finalizados.php", variable, "verificacion", 2);
        	}
        }

        function porFinalizar()
        {
        	noStarted = 0;
        	$("#pendientesTablero").prop("value",0);
        	if(finish == 0)
        	{
        		paginacion = 1;
        		finish = 1;

        		var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish;
            	RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
        	}else
        	{
        		paginacion = 1;
        		finish = 0;
        		var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish;
           		RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
        	}
        }

        function tableroPendientes(value)
        {
        	finish = 0;
        	noStarted = 0;
        	pTablero = value;
        	paginacion = 1;

    		var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish+"&pTablero="+pTablero+"&eTablero="+estatus_tablero;
       		RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
        }

        function estatusPendientes(value)
        {
            estatus_tablero = value;
            var variable = "accion=index&empresa="+empresa+"&buscar="+$("#search").val()+"&client="+$("#client").val()+"&page="+paginacion+"&iniciadas="+noStarted+"&realizadas="+finish+"&pTablero="+pTablero+"&eTablero="+estatus_tablero;
            RestFullRequest("_Rest/Finalizados.php", variable, "datagridSeleccionDocumento");
        }

        function verificar(obj)
        {
            if( $(obj).prop('checked') ) {

                $("input[type=checkbox]").prop('checked', 'checked');
            }else{
                $("input[type=checkbox]").prop('checked', '');
            }
        }


