    function actualizaDatagrid()
    {
        $("#almacen").html("<tr><td colspan='4'>Cargando...</td></tr>");
        var familia = $("#filtro_grupo").val();
        var texto = $("#filtro_texto").val();
        var variable = "accion=index&familia="+familia+"&texto="+texto;
        RestFullRequest("_Rest/Almacen.php", variable, "datagrid");
    }

    function datagrid(response)
    {
        var contador = 0;
        var datagrid = $("#almacen");

        datagrid.find("tr").remove();
        arreglo = Array();
        var total = 0;
        var subtotal = 0;

        $.each(response['ARTICULOS'], function(index, value)
        {
            if(value['FAMILIA'] != "HERRAMIENTAS")
            {
                var campos = "";
                if(value['SUGERIDA'] > 0)
                    linea = $("<tr style='background-color: rgba(200,0,0,0.1);'>");
                else
                    linea = $("<tr>");
                
                campos += "<td>"+value['ARTICULO']+"</td>";
                datos = "";
                var datos_subtotal = "";
                var ultima_compra = "";
                if(value['UNITARIO'] == 1)
                {
                    var residuo = value['INVENTARIO'] % value['PAQUETE'];
                    datos = "UNIDADES: <b>"+parseInt(value['INVENTARIO'] / value['PAQUETE'])+" "+value['UNIDAD_COMPRA']+"(S)</b><br>";
                    datos += "EN USO: <b>"+redondeoDecimales(residuo,2)+" "+value['UNIDAD_VENTA']+"(S)</b><br>";
                    datos += "MINIMO: <b>"+redondeoDecimales((value['CANTIDAD_MINIMA'] / value['PAQUETE']),2)+" "+value['UNIDAD_COMPRA']+"(S) </b><br>";
                    subtotal = parseFloat(value['PRECIO_UNITARIO'] * value['INVENTARIO']);
                    total = parseFloat(total) + parseFloat(subtotal);
                    if(parseFloat(value['MONTO_UNITARIO']) > 0)
                        ultima_compra = redondeoDecimales((parseFloat(value['MONTO_UNITARIO']) * parseFloat(value['PAQUETE'])),2)+" 1 "+value['UNIDAD_COMPRA'];
                    else
                        ultima_compra = "SIN COMPRA";

                    
                }else if(value['UNITARIO'] == 0)
                {
                    datos = "UNIDADES: <b>"+value['REGISTROS']+" "+value['UNIDAD_COMPRA']+"(S)</b><br>";
                    if(!parseFloat(value['CANTIDAD_USO']))
                        value['CANTIDAD_USO'] = 0;

                    if(parseFloat(value['ANCHO']) == 0)
                        value['ANCHO'] = 1;

                    datos += "EN USO: <b>"+redondeoDecimales((value['CANTIDAD_USO'] / value['ANCHO']),2)+" ML</b><br>";
                    var minimo = (value['ANCHO'] * value['LARGO']);
                    if(parseFloat(minimo) > 0)
                        minimo = value['CANTIDAD_MINIMA'] / parseFloat(minimo);
                    datos += "MINIMO: <b>"+redondeoDecimales(parseFloat(minimo),2)+" "+value['UNIDAD_COMPRA']+"(S) </b><br>";
                    subtotal = parseFloat(value['PRECIO_UNITARIO'] * ((value['REGISTROS'] * value['ANCHO'] * value['LARGO']) + (value['CANTIDAD_USO'] / value['ANCHO'])));
                    total = parseFloat(total) + parseFloat(subtotal);
                    if(value['MONTO_METRAJE'] > 0)
                        ultima_compra = currency(value['MONTO_METRAJE'],2,[",","."])+" 1 "+value['UNIDAD_COMPRA'];
                    else
                        ultima_compra = "SIN COMPRA";
                    
                }
                            
                //datos_subtotal +="MONTO: <b>$ "+currency(subtotal,2,[",","."])+"</b><br>";
                //datos_subtotal +="ULTIMA COMPRA: <BR><b>$ "+ultima_compra+"</b><br>";
                campos += "<td>"+datos+"</td>";
                //campos += "<td>"+datos_subtotal+"</td>";
                campos += "<td>"+value['ACTUALIZACION']+"</td>";
                //campos += "<td><button type='button' class='btn btn-success' onclick='baja_manual("+value['ARTICULO_ID']+")'><i class='fa fa-caret-square-o-down'></i></button></td>";

                linea.append(campos);
                datagrid.append(linea);

                contador++;
            }
        });

        if(contador == 0)
            datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
    }

    $(document).ready(function()
    {
        actualizaDatagrid();
        cargaFormularios();
        $("#menu_inventario").find("a").click();
    });

    function cargaFormularios()
    {
        var variable = "accion=formularios";
        RestFullRequest("_Rest/Almacen.php", variable, "Formulario");
    }

    function Formulario(Response)
    {
        
        $("#filtro_grupo").html("<option value='0'>FAMILIA</option>");

        $.each(Response['CATEGORIA'], function(index, value)
        {
            $("#filtro_grupo").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });
    }


   