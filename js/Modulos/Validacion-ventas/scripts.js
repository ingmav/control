var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;

function datagridPendientes(response)
{
    console.log(response);
    var datagrid = $("#datagridValidacionVenta");
    datagrid.find("tr").remove();
    var contador = 0;

    $.each(response, function(index, value)
    {

        var campos = "";
        var campos2 = "";
        var campos3 = "";
        var index = 0;
        var id, empresa;
        id = value['ID'];
        empresa = value['EMPRESA'];

        campos += "<td>"+value['FOLIO']+"</td>";
        campos += "<td>"+value['TITULO']+"</td>";
        campos += "<td>$ "+moneda(value['IMPORTE'], 2, [",", "."])+"</td>";
        linea = $("<tr data-fila='"+id+"' id='"+id+"' style='background-color: rgba(0,28,200,0.2)'></tr>");
        
        campos +="<td><input type='checkbox' name='venta[]' value='"+empresa+"_"+id+"'></td>";
        linea.append(campos);

        datagrid.append(linea);

        linea2 = $("<tr></tr>");
        campos2 = $("<td colspan='4'></td>");

        linea2.append(campos2);
        datagrid.append(linea2);

        var table = $("<table class='table table-hover dataTable no-footer'></table>");
        linea3 = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

        campos3 +="<th>ARTICULO</th>";
        campos3 +="<th>P/ VENTA</th>";
        campos3 +="<th>UNIDAD</th>";
        
        campos3 +="<th>ARTICULO MS</th>";
        campos3 +="<th>P/UNITARIO MS</th>";
        campos3 +="<th>UNIDAD MS</th>";
        campos3 +="<th>PRECIO MS</th>";
        campos3 +="<th>DISPONIBILIDAD</th>";
        
        linea3.append(campos3);
        table.append(linea3);
        var linea3 = "";
        var campos3 = "";
        var venta_det_id , venta_id;

        $.each(value['INSUMOS'], function(indice, valor)
        {
            var id_insumo     = valor['ID'];
            venta_det_id  = valor['ID_VENTA_DET'];
            venta_id      = valor['ID_VENTA'];

            linea3 = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

            campos3 +="<td>"+valor['ARTICULO']+"</td>";
            campos3 +="<td>"+valor['PRECIO']+"</td>";
            campos3 +="<td>"+valor['UNIDADES']+"</td>";
            var precio = 0.00;
            var unidades = 0;
            
            if(valor['PRECIO_UNITARIO'] == null)
                  valor['PRECIO_UNITARIO'] = 0;

              if(valor['DISPONIBILIDAD'] == null)
                  valor['DISPONIBILIDAD'] = 0;

            if(valor['GRUPO_ARTICULO'] != '')
            {
                campos3 +="<td>"+valor['GRUPO_ARTICULO']+"</td>";

                campos3 +="<td><input type='text' id='precio_unitario_"+id+"_"+id_insumo+"' name='precio_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+valor['PRECIO_UNITARIO']+"'></td>";
                campos3 +="<td><input type='text' id='unidades_"+id+"_"+id_insumo+"' name='unidades_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+(valor['CANTIDAD_RELACION'] * valor['UNIDADES'])+"'><input type='hidden' id='unidades_"+id+"_"+id_insumo+"' name='detalle["+empresa+"_"+venta_id+"][]' value='"+venta_det_id+"'><input type='hidden' name='descripcion_articulo_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['ARTICULO']+"'><input type='hidden' name='precio_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['PRECIO']+"'><input type='hidden' name='unidad_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+(valor['CANTIDAD_RELACION'] * valor['UNIDADES'])+"'><input type='hidden' name='articulo_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+id_insumo+"'></td>";
                campos3 +="<td id='precio_total_"+id+"_"+id_insumo+"'>"+currency(((valor['CANTIDAD_RELACION'] * valor['UNIDADES']) * (valor['PRECIO_UNITARIO'])), 2, [",","."])+"</td>";
                campos3 +="<td>"+valor['DISPONIBILIDAD']+"</td>";
                     //linea3.append($(""));
                //linea3.append($("<td><input type='text' id='unidades_"+id+"_"+id_insumo+"' name='unidades_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+unidades+"'><input type='hidden' id='unidades_"+id+"_"+id_insumo+"' name='detalle["+empresa+"_"+venta_id+"][]' value='"+venta_det_id+"'><input type='hidden' name='descripcion_articulo_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['ARTICULO']+"'><input type='hidden' name='precio_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['PRECIO']+"'><input type='hidden' name='unidad_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+(valor['CANTIDAD'] * valor['UNIDADES'])+"'></td>"));
                //linea3.append($("<td id='precio_total_"+id+"_"+id_insumo+"'>"+currency((precio * unidades), 2, [",","."])+"</td>"));
 
                
                //linea3.append($("<td><input type='text' id='precio_unitario_"+id+"_"+id_insumo+"' name='precio_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+precio+"'></td>"));
                //linea3.append($("<td><input type='text' id='unidades_"+id+"_"+id_insumo+"' name='unidades_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+unidades+"'><input type='hidden' id='unidades_"+id+"_"+id_insumo+"' name='detalle["+empresa+"_"+venta_id+"][]' value='"+venta_det_id+"'><input type='hidden' name='descripcion_articulo_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['ARTICULO']+"'><input type='hidden' name='precio_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['PRECIO']+"'><input type='hidden' name='unidad_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+(valor['CANTIDAD'] * valor['UNIDADES'])+"'></td>"));
                //linea3.append($("<td id='precio_total_"+id+"_"+id_insumo+"'>"+currency((precio * unidades), 2, [",","."])+"</td>"));

            
                /*campos3 +="<td>"+(valor['CANTIDAD'] * valor['UNIDADES'])+"</td>";
                var select = $("<select class='form-control' style='width:300px' name='articulo_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='verifica_precio("+id+","+id_insumo+", this.value)'></select>");    
                $.each(valor['ARTICULOS_WEB'], function(indice2, valor2)
                {
                    if(valor2['ID'] == valor['AUTOMATICO']){
                        var opcion = $("<option value='"+valor2['ID']+"' selected='selected'>"+valor2['NOMBRE']+"</option>");
                        precio = valor2['PRECIO'];
                    }
                    else
                        var opcion = $("<option value='"+valor2['ID']+"'>"+valor2['NOMBRE']+"</option>");
                    select.append(opcion);
                });
                
                var option = $("<td></td>");
                option.append(select);*/
            }
            else
            {
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
            }
                
            /*if(valor['CANTIDAD'])
            {
                campos3 +="<td>"+(valor['CANTIDAD'] * valor['UNIDADES'])+"</td>";
                var select = $("<select class='form-control' style='width:300px' name='articulo_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='verifica_precio("+id+","+id_insumo+", this.value)'></select>");    
                $.each(valor['ARTICULOS_WEB'], function(indice2, valor2)
                {
                    if(valor2['ID'] == valor['AUTOMATICO']){
                        var opcion = $("<option value='"+valor2['ID']+"' selected='selected'>"+valor2['NOMBRE']+"</option>");
                        precio = valor2['PRECIO'];
                    }
                    else
                        var opcion = $("<option value='"+valor2['ID']+"'>"+valor2['NOMBRE']+"</option>");
                    select.append(opcion);
                });
                
                var option = $("<td></td>");
                option.append(select);
            }
            else
            {
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
                campos3 +="<td>N/A</td>";
                campos3 +="<td>N/A</td>";
                campos3 +="<td>N/A</td>";
            }

            unidades = currency(valor['UNIDADES'], 2, ["."]);

            //campos3 +="<td>"+currency(valor['UNIDADES'], 2, ["."])+"</td>";
            */
            linea3.append(campos3);

            if(precio > 0)
                precio = precio;
            else
                precio =  0.00;
            if(valor['CANTIDAD'])
            {
                linea3.append(option);
                linea3.append($("<td><input type='text' id='precio_unitario_"+id+"_"+id_insumo+"' name='precio_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+precio+"'></td>"));
                linea3.append($("<td><input type='text' id='unidades_"+id+"_"+id_insumo+"' name='unidades_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+unidades+"'><input type='hidden' id='unidades_"+id+"_"+id_insumo+"' name='detalle["+empresa+"_"+venta_id+"][]' value='"+venta_det_id+"'><input type='hidden' name='descripcion_articulo_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['ARTICULO']+"'><input type='hidden' name='precio_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['PRECIO']+"'><input type='hidden' name='unidad_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+(valor['CANTIDAD'] * valor['UNIDADES'])+"'></td>"));
                linea3.append($("<td id='precio_total_"+id+"_"+id_insumo+"'>"+currency((precio * unidades), 2, [",","."])+"</td>"));

            }

            
            table.append(linea3);
            var linea3 = "";
            var campos3 = "";
        });

        campos2.append(table);

        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}


function reporte()
{
    $("#ReporteInsumos").modal("show");
}

function calcula_total(id, id_insumo)
{
    $("#precio_total_"+id+"_"+id_insumo).text(currency(($("#precio_unitario_"+id+"_"+id_insumo).val() * $("#unidades_"+id+"_"+id_insumo).val()), 2, [",","."]));
}

function verifica_precio(id, id_insumo, value)
{
    //console.log(value);
    var variable = "accion=recalcula_precio&id="+id+"&id_insumo="+id_insumo+"&valor="+value;
    RestFullRequest("_Rest/validacion_venta.php", variable, "cambia_precio");   
}

function cambia_precio(response)
{
    var id = response['ID'];
    var id_insumo = response['ID_INSUMO'];
    $("#precio_unitario_"+id+"_"+id_insumo).val(response['PRECIO']);
    $("#precio_total_"+id+"_"+id_insumo).text(currency(($("#precio_unitario_"+id+"_"+id_insumo).val() * $("#unidades_"+id+"_"+id_insumo).val()), 2, [",","."]));   
}

function actualizaDatagrid()
{
    var variable = "accion=index";
    RestFullRequest("_Rest/validacion_venta.php", variable, "datagridPendientes");
    
}

function validar()
{
    var variable = "accion=validar&"+$("#datagridValidacion").serialize(); 
    RestFullRequest("_Rest/validacion_venta.php", variable, "validacion");
   
}

function validacion(response)
{
    //console.log(response);
    actualizaDatagrid();
}

function checador(obj)
{
    if( $(obj).prop('checked') ) {

        $("input[type=checkbox]").prop('checked', 'checked');
    }else{
        $("input[type=checkbox]").prop('checked', '');
    }
}


$(document).ready(function(e) {
    actualizaDatagrid();
    $("#validacion_venta").find("a").click();
});


