		var empresa = 1;
		var paginacion = 1;
		var buscar = "";
	
		function datagrid(response)
		{
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				console.log(response);
				var campos = "";
				var index = 0;
				var id = value['ID_DET'];
				var tituloempresa = "";
				
				campos += "<td>"+value['FOLIO']+"</td>";
				campos += "<td>"+value['FECHA']+"</td>";
				campos += "<td>"+value['NOMBRE_CLIENTE']+"";

				texto_descripcion = "";

				notas = value['DESCRIPCION'];
				
				if(notas!="")
				{
					//console.log("maro");
					campos += "<br><span style='background-color:#cfcfcf'>"+notas+"</span>";
				}
				//texto_descripcion = value['DESCRIPCION'];

				$.each(value['MATERIALES'], function(index2, value2)
		        {
		            texto_descripcion += "<br>- "+value2['NOMBRE']+" ("+parseFloat(value2['UNIDADES'],2)+")";
		        });
				campos += " "+texto_descripcion+"</td>";

				linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

				if(value['GF_DISENO'] == 1)
					if(value['DISENO_GF'] == 2)
						//campos += "<td><i class='fa fa-check-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-success btn-strech'><span class='fa fa-check'></span></button></td>";
					else
						//campos += "<td><i class='fa fa-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-danger btn-strech'><span class='fa fa-check'></span></button></td>";
				else
					campos += "<td></td>";	
				if(value['GF_IMPRESION'] == 1)
					if(value['IMPRESION_GF'] == 2)
						//campos += "<td><i class='fa fa-check-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-success btn-strech'><span class='fa fa-check'></span></button></td>";
					else
						//campos += "<td><i class='fa fa-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-danger btn-strech'><span class='fa fa-check'></span></button></td>";
				else
					campos += "<td></td>";	
				if(value['GF_PREPARACION'] == 1)
					if(value['PREPARACION_GF'] == 2)
						//campos += "<td><i class='fa fa-check-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-success btn-strech'><span class='fa fa-check'></span></button></td>";
					else
						//campos += "<td><i class='fa fa-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-danger btn-strech'><span class='fa fa-check'></span></button></td>";
				else
					campos += "<td></td>";	
				if(value['GF_INSTALACION'] == 1)
					if(value['INSTALACION_GF'] == 2)
						//campos += "<td><i class='fa fa-check-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-success btn-strech'><span class='fa fa-check'></span></button></td>";
					else
						//campos += "<td><i class='fa fa-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-danger btn-strech'><span class='fa fa-check'></span></button></td>";
				else
					campos += "<td></td>";	

				if(value['GF_ENTREGA'] == 1)
					if(value['ENTREGA_GF'] == 2)
						//campos += "<td><i class='fa fa-check-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-success btn-strech'><span class='fa fa-check'></span></button></td>";
					else
						//campos += "<td><i class='fa fa-square'></i></td>";
						campos += "<td><button type='button' class='btn btn-danger btn-strech'><span class='fa fa-check'></span></button></td>";
				else
					campos += "<td></td>";	

				if(parseFloat(value['PROCESOS']) == 0)
					campos += "<td><input type='checkbox' name='procesos[]' value='"+value['ID_DET']+"'></td>";

				else
					campos += "<td></td>";

				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='7'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}

		function tablero()
		{
			var variable = "accion=index&estatus="+$("#estatus_general").val();
            RestFullRequest("_Rest/Tablero_produccion.php", variable, "datagrid",0);
		}

		function finalizar()
		{
			var variable = "accion=finalizar&"+$("#form_tablero").serialize();
            RestFullRequest("_Rest/Tablero_produccion.php", variable, "tablero",1);
		}    

		function reporte_tablero()
		{
			//console.log("entra");
			window.open("../reportes/tablero/mostrador/reporte_tablero.php", '_blank');
		}

		function cambia_estatus_general(value)
		{
			tablero();
		}

		
		$(document).ready(function(e) {
			$("#mostrador").find("a").click();
			tablero();
        });

        function verificar(obj)
        {
            if( $(obj).prop('checked') ) {

                $("input[type=checkbox]").prop('checked', 'checked');
            }else{
                $("input[type=checkbox]").prop('checked', '');
            }
        }