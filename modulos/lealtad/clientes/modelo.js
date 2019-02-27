var RestPunto = new RESTful2v("controlador.php");

$(document).ready(function()
{
	$("#lealtad").find("a").click();
});

function cargar_lista(empresa = null)
{
    var datagrid = $("#data");
    datagrid.html("<tr><td colspan='5'>Cargando...</td></tr>");
    var parametros = "";
    
    if(empresa != null)
        parametros = parametros+"&empresa="+empresa;
	
    RestPunto.get(null, "accion=get_lista"+parametros,{
        _success: function(response){
            datagrid.html("");
            var band = 0;
            $.each(response, function(index,value)
        	{
        		var linea = $("<tr></tr>");
        		var campo1 = $("<td>"+value['ID']+"</td>");
        		var campo2 = $("<td>"+value['CLAVE']+"</td>");
                var campo3 = $("<td>"+value['NOMBRE']+"</td>");
        		var campo4 = $("<td>"+value['CUENTAS']+"</td>");
                var campo5 = $("<td><button type='button' class='btn btn-info' onclick='ver_rel_cliente("+value['ID']+")'><i class='fa fa-share'></i></button></td>");
        		var campo6 = $("<td><button type='button' class='btn btn-warning' onclick='editar("+value['ID']+")'><i class='fa fa-edit'></i></button></td>");
        		linea.append(campo1).append(campo2).append(campo3).append(campo4).append(campo5).append(campo6);
        		datagrid.append(linea);	
                band = 1;
        	});
            if(band == 0)
                datagrid.html("<tr><td colspan='5'>No se encontraron resultados</td></tr>");
            
        }
    },'Guardando');
}

function buscar()
{
    cargar_lista($("#filtro_empresa").val());
}

$("#filtro_empresa").keypress(function(e) {

   if(e.which == 13) {
    e.preventDefault();
      cargar_lista($(this).val());
   }
});

function ver_rel_cliente(id)
{
    var datagrid = $("#data_clientes");
    var datagrid2 = $("#data_clientes_activos");
    var parametros = "&id="+id;

    $("#form_clientes #clave").text("");
    $("#form_clientes #nombre").text("");
    $("#form_clientes #fecha_nacimiento").text("");
    $("#form_clientes #id").val(0);

    $("#rel_clientes").modal("show");
    RestPunto.get(null, "accion=get_rel_cliente"+parametros,{
        _success: function(response){
            datagrid.html("");
            var band = 0;

            $("#form_clientes #clave").text(response['CLIENTE'][0].CLAVE);
            $("#form_clientes #nombre").text(response['CLIENTE'][0].NOMBRE);
            $("#form_clientes #fecha_nacimiento").text(response['CLIENTE'][0].FECHA_NACIMIENTO);
            $("#form_clientes #id").val(response['CLIENTE'][0].ID);

            $.each(response['CLIENTES'], function(index,value)
            {
                var linea = $("<tr></tr>");
                var campo1 = $("<td>"+value['CLAVE']+"</td>");
                var campo2 = $("<td>"+value['NOMBRE']+"</td>");
                var total_puntos = parseFloat(value['IMPORTE']) * 0.01;
                var campo3 = $("<td>"+total_puntos.toFixed(2)+"</td>");
                var campo5 = $("<td><button type='button' class='btn btn-info' onclick='relacionar("+value['ID']+", "+response['CLIENTE'][0].ID+")'><i class='fa fa-share-alt'></i></button></td>");
                linea.append(campo1).append(campo2).append(campo3).append(campo5);
                datagrid.append(linea); 
                band = 1;
            });
            if(band == 0)
                datagrid.html("<tr><td colspan='5'>No se encontraron resultados</td></tr>");

            datagrid2.html("");
            var band = 0;
            $.each(response['REL_CLIENTES'], function(index,value)
            {
                var linea = $("<tr></tr>");
                var campo1 = $("<td>"+value['CLAVE']+"</td>");
                var campo2 = $("<td>"+value['NOMBRE']+"</td>");
                var total_puntos = parseFloat(value['IMPORTE']) * 0.01;
                linea.append(campo1).append(campo2);
                datagrid2.append(linea); 
                band = 1;
            });
            if(band == 0)
                datagrid2.html("<tr><td colspan='5'>No se encontraron resultados</td></tr>");
            
        }
    },'Guardando');   


}

function relacionar(id_cliente, id_cliente_principal)
{
    confirm("¿Realmente desea relacionar la cuenta con el cliente seleccionado?")
    {
        var parametros = "&id_cliente_relacion="+id_cliente+"&id_cliente_principal="+id_cliente_principal;

        RestPunto.get(null, "accion=set_rel_cliente"+parametros,{
            _success: function(response){
                console.log(response);
                ver_rel_cliente(response);
            }
        },'Guardando');   
    }
}

function ver_form_cliente()
{
    $('#add_clientes').modal('show');
    $("#form_cliente #id").val(0);
    $("#form_cliente #nombre").val("");
    $("#form_cliente #clave").val("");
    $("#form_cliente #fecha_nacimiento").val("");
}
function editar(id)
{
    var parametros = "id="+id;

    RestPunto.get(null, "accion=search_cliente&"+parametros,{
        _success: function(response){
            console.log(response);
            $("#form_cliente #id").val(response[0].ID);
            $("#form_cliente #nombre").val(response[0].NOMBRE);
            $("#form_cliente #clave").val(response[0].CLAVE);
            $("#form_cliente #fecha_nacimiento").val(response[0].FECHA_NACIMIENTO);
            $('#add_clientes').modal('show');
        }
    },'Guardando');   
}

function guardar_cliente()
{
    var parametros = $("#form_cliente").serialize();

    RestPunto.get(null, "accion=save_rel_cliente&"+parametros,{
        _success: function(response){
            alert("SE HA EDITADO/GUARDADO CORRECTAMENTE EL CLIENTE");
            $('#add_clientes').modal('hide');
            $("#form_cliente #id").val(0);
            $("#form_cliente #nombre").val("");
            $("#form_cliente #clave").val("");
            $("#form_cliente #fecha_nacimiento").val("");
            buscar();
        }
    },'Guardando');   
}

function actualizar_clientes()
{
    confirm("¿Realmente desea actualizar la lista de clientes?")
    {
        var parametros = "";

        RestPunto.get(null, "accion=actualizar_clientes"+parametros,{
            _success: function(response){
                var nombre = "Se han incorporado nuevos clientes, los cuales son los siguientes:\n\n";
                if(response.length > 0)
                    $.each(response, function(index,value)
                    {
                        nombre += value['NOMBRE']+"\n";
                    });
                else
                    nombre = "No hay nuevos clientes a quien incorporar";
                alert(nombre);
                buscar();
            }
        },'Guardando');   
    }   
}