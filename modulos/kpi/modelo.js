var RestPunto = new RESTful2v("controlador.php");
$(document).ready(function()
{
	$("#control").find("a").click();
	
	cargar_lista_trabajador();
});

$("#guardar_trabajador").on("click", function()
{
	var data = $("#form_trabajadores").serialize();
	RestPunto.get(null, "accion=guardar_trabajador&"+data,{
        _success: function(response){
    		cargar_lista_trabajador();
    		$("#form_trabajadores")[0].reset();
        },
        _error: function(response)
        {
            alert("Ocurrio un error al cargar la lista, por favor cierre la ventana y vuelva a intentarlo");
        }
    },'Guardando');
});


function cargar_lista_trabajador()
{
	var parametros = "";
	var lista = $("#data");
	lista.html("");
	RestPunto.get(null, "accion=get_trabajadores"+parametros,{
        _success: function(response){
        	if(response.length == 0)
        	{
        		lista.html("<tr><td colspan='7'>NO HAY DATOS...</td></tr>");
        	}else
        	{
	            $.each(response, function(index,value)
	        	{	
	        		var linea = "<tr>";
	        		var campo1 = "<td>"+value['NOMBRE']+"</td>";
	        		var campo2 = "<td>"+value['TIPO_TRABAJADOR']+"</td>";
	        		var campo3 = "<td>"+value['HORARIO']+"</td>";
	        		var campo4 = "<td>"+value['SALARIO']+"</td>";
	        		var campo5 = "<td>"+value['SALARIO']+"</td>";
	        		var campo6 = "<td>"+value['SALARIO']+"</td>";
	        		var campo7 = "<td><button type='button' class='btn btn-info' onclick='editar_trabajador("+value['INDEX']+")'><i class='fa fa-edit'></i></button><button type='button' class='btn btn-danger'><i class='fa fa-arrow-circle-down'></i></button></td>";
	        		lista.append(linea+campo1+campo2+campo3+campo4+campo5+campo6+campo7+"</tr>");
	        	});        
	        }
        }
    },'Guardando');
}

