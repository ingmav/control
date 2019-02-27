var RestXML = new RESTful2v("controlador.php");
$(document).ready(function()
{
	$("#control").find("a").click();
	actualizaDatagrid();
	actualizaConfiguracion();
});

function guardarXML()
{
	var data = new FormData();
	jQuery.each($('input[type=file]')[0].files, function(i, file) {
	    data.append('archivo', file);
	});
	data.append('accion', "guardaXml");
	data.append('pedido', $("#formXml #add_pedido").val());
	data.append('recibo', $("#formXml #add_recibo").val());
	data.append('departamento', $("#formXml #add_departamento").val());
	
	jQuery.ajax({
	    url: 'controlador.php',
	    data: data,
	    cache: false,
	    contentType: false,
	    processData: false,
	    type: 'POST',
	    success: function(data){
	        $.each(data, function(index, value)
	        {
	        	$("#agregar").modal('hide');
	        	actualizaDatagrid();
	        	$("#agregar").find("input").val("");
	        	window.open("descarga.php?file_name="+value.nombre_archivo+"&id="+value.id, "_blank");
	        });
	    }
	});
}

function actualizaDatagrid()
{
	var parametros = "";
	var lista = $("#data");
	lista.html("");
	RestXML.get(null, "accion=index"+parametros,{
        _success: function(response){
        	if(response.length == 0)
        	{
        		lista.html("<tr><td colspan='7'>NO HAY DATOS...</td></tr>");
        	}else
        	{
	            $.each(response, function(index,value)
	        	{	
	        		linea = $("<tr></tr>");
					var campos = "";
					
			        campos += "<td>"+value['INDEX']+"</td>";
			        campos += "<td>"+value['FACTURA']+"</td>";
			        campos += "<td>"+value['PEDIDO']+"</td>";
			        campos += "<td>"+value['GLN_LIVERPOOL']+"</td>";
					campos += "<td>"+value['GLN_NEXPRINT']+"</td>";
					campos += "<td>"+value['MONTO']+"</td>";
					campos += "<td align='center'><i class='fa fa-download fa-2x' style='cursor:pointer' onclick=\"descargar_archivo("+value['INDEX']+",'"+value['ARCHIVO']+"')\"></i></td>";
					campos += "<td align='center'><i class='fa fa-close fa-2x' style='cursor:pointer; color:red' onclick=\"eliminar_registro("+value['INDEX']+")\"></i></td>";
					linea.append(campos);
					
					lista.append(linea);
	        	});        
	        }
        }
    },'Guardando');
}

function actualizaConfiguracion()
{
	var parametros = "";
	RestXML.get(null, "accion=cargar_configuracion"+parametros,{
        _success: function(response){
        	
            $.each(response, function(index,value)
        	{	
        		$("#config_no_proveedor").val(value['NO_PROVEEDOR']);		
				$("#config_gln_liverpool").val(value['GLN_LIVERPOOL']);		
				$("#config_gln_nexprint").val(value['GLN_PROVEEDOR']);	
				$("#config_no_departamento").val(value['NO_DEPARTAMETNO']);	
        	});        
	        
        }
    },'Guardando');
}

function actualiza_configuracion()
{
	var parametros =  $("#formConfiguracion").serialize();;
	RestXML.get(null, "accion=actualiza_configuracion&"+parametros,{
        _success: function(response){
           $("#configuracion").modal('hide');
        }
    },'Guardando');
}

function descargar_archivo(id, nombre)
{
	window.open("descarga.php?file_name="+nombre+"&id="+id, "_blank");
}

function eliminar_registro(id)
{
	if(confirm("¿Realmente desea eliminar el registro?"))
	{
		var parametros = "&id="+id;
		RestXML.get(null, "accion=eliminar_registro"+parametros,{
	        _success: function(response){
	            actualizaDatagrid();   
	        }
	    },'Guardando');
	}
}