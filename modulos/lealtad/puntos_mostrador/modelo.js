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
        		var campo2 = $("<td>"+value['NOMBRE']+"</td>");
        		var total = value['TOTAL'];
        		total = ((total * 0.01) - value['UTILIZADOS']) + parseFloat(value['MOSTRADOR']);
        		var campo3 = $("<td>"+total.toFixed(2)+"</td>");
        		//var disponible = total; 
        		//var campo4 = $("<td>"+disponible.toFixed(2)+"</td>");
                var campo5 = $("<td><button type='button' class='btn btn-info' onclick=\"sumar_puntos("+value['ID']+", \'"+value['NOMBRE']+"\', "+total.toFixed(2)+")\"><i class='fa fa-plus'></i></button></td>");
        		var campo6 = $("<td><button type='button' class='btn btn-info' onclick=\"aplicar_descuento("+value['ID']+", \'"+value['NOMBRE']+"\', "+total.toFixed(2)+")\"><i class='fa fa-minus'></i></button></td>");
        		linea.append(campo1).append(campo2).append(campo3).append(campo5).append(campo6);
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

function aplicar_descuento(id, nombre, descuento)
{
    
    if(descuento < 0)
        alert("NO SE PUEDEN APLICAR PUNTOS NEXPRINT, PORQUÉ TIENE SALDO NEGATIVO");
    else
    {
        $("#form_descuento #nombre_empresa").text(nombre);
        $("#form_descuento #descuento_empresa").text(descuento);
        $("#form_descuento #descuento_total").val(descuento);
        var parametros = "&id="+id+"&total_puntos="+descuento;
        var datagrid = $("#form_descuento #data_pedidos");
        datagrid.html("<tr><td colspan='5'>Cargando..</td></tr>");
        $("#descuento").modal("show");

        RestPunto.get(null, "accion=get_ventas_descuento"+parametros,{
            _success: function(response){
                
                datagrid.html("");
                var band = 0;
                $.each(response, function(index,value)
                {
                    var linea = $("<tr></tr>");
                    var campo1 = $("<td>"+value['FOLIO']+"</td>");
                    var total = value['TOTAL'];
                    var campo3 = $("<td>"+total+"</td>");
                    var campo4 = $("<td><input type='number' style='width: 80px;' name='descuento_"+value['ID']+"'  id='descuento_"+value['ID']+"' class='form-control' max='"+value['MAX_PUNTOS']+"' value="+value['MAX_PUNTOS']+" onchange='cambio_puntos("+total+", this.value, "+value['ID']+")'></td>");
                    var descuento_puntos = (parseFloat(total) -  value['MAX_PUNTOS']); 
                    var campo5 = $("<td id='linea_"+value['ID']+"'>"+descuento_puntos.toFixed(2)+"</td>");

                    var campo6 = $("<td><button type='button' class='btn btn-info' onclick=\"aplicar_descuento_puntos("+id+",'"+value['ID']+"')\"><i class='fa fa-check-square'></i></button></td>");
                    linea.append(campo1).append(campo3).append(campo4).append(campo5).append(campo6);
                    datagrid.append(linea); 
                    band = 1;
                });
                if(band == 0)
                    datagrid.html("<tr><td colspan='5'>No se encontraron resultados</td></tr>");
                
            },
            _error: function(response)
            {
                datagrid.html("");
                alert("Ocurrio un error al cargar sus pedidos, por favor cierre la ventana y vuelva a intentarlo");
            }
        },'Guardando');
    }
}

function sumar_puntos(id, nombre, puntos)
{
    $("#sumar_puntos").modal("show");

    $("#form_suma #nombre_empresa").text(nombre);
    $("#form_suma #puntos_empresa").text(puntos);
    var parametros = "&id="+id+"&total_puntos="+puntos;
    var datagrid = $("#form_suma #data_ventas");
    datagrid.html("<tr><td colspan='5'>Cargando..</td></tr>");
    
    RestPunto.get(null, "accion=get_ventas"+parametros,{
        _success: function(response){
            
            datagrid.html("");
            var band = 0;
            $.each(response, function(index,value)
            {
                var linea = $("<tr></tr>");
                var campo1 = $("<td>"+value['FOLIO']+"</td>");
                //var campo2 = $("<td>"+value['DESCRIPCION']+"</td>");
                var total = value['TOTAL'];
                var campo3 = $("<td>"+total+"</td>");
                var campo4 = $("<td>"+value['PORCENTAJE']+"</td>");
                var campo5 = $("<td><button type='button' class='btn btn-info' onclick=\"aplicar_suma_puntos("+id+",'"+value['ID']+"')\"><i class='fa fa-check-square'></i></button></td>");
                linea.append(campo1).append(campo3).append(campo4).append(campo5);
                datagrid.append(linea); 
                band = 1;
            });
            if(band == 0)
                datagrid.html("<tr><td colspan='5'>No se encontraron resultados</td></tr>");
            
        },
        _error: function(response)
        {
            datagrid.html("");
            alert("Ocurrio un error al cargar sus pedidos, por favor cierre la ventana y vuelva a intentarlo");
        }
    },'Guardando');
    
}

function aplicar_suma_puntos(cliente_id, id)
{
    if(confirm("¿REALMENTE DESEA APLICAR LOS PUNTOS AL CLIENTE?"))
    {
        var parametros = $("#form_suma").serialize();
        parametros = parametros+"&id="+id+"&cliente_id="+cliente_id;
        $(".cargando").css({'display': 'block'});    
        $(".informacion").css({'display': 'none'});    
        
        RestPunto.get(null, "accion=aplicar_puntos&"+parametros,{
            _success: function(response){
                
                alert("SE HA APLICADO LOS PUNTOS AL CLIENTE SELECCIONADO");
                $("#sumar_puntos").modal("hide");
                buscar();
                $(".cargando").css({'display': 'none'});    
                $(".informacion").css({'display': 'block'});    
            
            },
            _error:function(response){
                
                console.log(response.responseText);
                alert(response.responseText);
                $(".cargando").css({'display': 'none'});    
                $(".informacion").css({'display': 'block'});    
            
            }

        },'Guardando');
    }
}

function aplicar_descuento_puntos(cliente_id, id)
{
    if(confirm("¿REALMENTE DESEA APLICAR LOS PUNTOS AL CLIENTE?"))
    {
        var parametros = $("#form_descuento").serialize();
        parametros = parametros+"&id="+id+"&cliente_id="+cliente_id;
        $(".cargando").css({'display': 'block'});    
        $(".informacion").css({'display': 'none'});    
        
        RestPunto.get(null, "accion=descontar_puntos&"+parametros,{
            _success: function(response){
                
                alert("SE HA DESCONTADO LOS PUNTOS AL CLIENTE SELECCIONADO");
                $("#descuento").modal("hide");
                buscar();
                $(".cargando").css({'display': 'none'});    
                $(".informacion").css({'display': 'block'});    
            
            },
            _error:function(response){
                
                console.log(response.responseText);
                alert(response.responseText);
                $(".cargando").css({'display': 'none'});    
                $(".informacion").css({'display': 'block'});    
            
            }

        },'Guardando');
    }
}