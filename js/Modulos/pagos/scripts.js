    var id_general = 0;

    function actualizaDatagrid()
    {

        var proveedor = $("#filtro_grupo").val();
        var variable = "accion=index&proveedor="+proveedor;
        RestFullRequest("_Rest/Pagos.php", variable, "datagrid");
    }

    function crear_reporte()
    {
        //console.log($("#FORM_REPORTE").serialize());
        window.open('reportes/inventario_admin/pago_proveedor.php?'+$("#FORM_REPORTE").serialize(), '_blank');
    }

    function formatea_formularios()
    {
        $("#FORM_INSUMO").find("select").val(1).find("input").val("");
        
    }

    function datagrid(response)
    {

        var contador = 0;
        var datagrid = $("#cuenta");

        datagrid.find("tr").remove();

        arreglo = Array();
        arreglo2 = Array();

       
        $.each(response['PAGOS'], function(index, value)
        {
            var campos = "";
            if(value['VENCIDO'] == 1)
                linea = $("<tr style='background-color:rgba(200,50,50,0.1)'>");
            else
                linea = $("<tr>");
            campos += "<td>"+value['FACTURA']+"<br><button type='button' class='btn btn-danger' onclick=\"eliminar('"+value['MS_PROVEEDOR_ID']+"', '"+value['FACTURA']+"')\"><i class='fa fa-close'></i></button></td>";

            if(value['MS_PROVEEDOR_ID'] !=  37)
                campos += "<td>"+value['PROVEEDOR']+"</td>";
            else
                campos += "<td>"+value['DESCRIPCION']+"</td>";
            campos += "<td>"+value['FECHA']+"</td>";
            campos += "<td>"+value['FECHA_PAGO']+"</td>";

            var btn_pagar = "<button type='button' class='btn btn-success' onclick=\"pagado('"+value['MS_PROVEEDOR_ID']+"', '"+value['FACTURA']+"')\"><i class='fa fa-money'></i></button>";
            var btn_descuento = "<button type='button' class='btn btn-warning' onclick=\"descuento('"+value['MS_PROVEEDOR_ID']+"', '"+value['FACTURA']+"')\"><i class='fa fa-chevron-circle-down'></i></button>";
            var btn_informacion = "<button type='button' class='btn btn-primary' onclick=\"informacion('"+value['MS_PROVEEDOR_ID']+"', '"+value['FACTURA']+"')\"><i class='fa fa-info-circle'></i></button>";

            campos += "<td><label style='color:#093; font-weight:bold'>+ "+value['PRECIO_TOTAL']+"</label><BR><label style='color:#903; font-weight:bold'> - "+value['DESCUENTO']+"</label><BR><label style='color:#000; font-weight:bold'>"+value['PRECIO']+"</label></td>";
            campos += "<td>"+btn_pagar+btn_descuento+btn_informacion+"</td>";

            linea.append(campos);
            datagrid.append(linea);

            contador++;

        });

        $("#monto_inventario").text(response['TOTAL']);
        $("#monto_inventario_vencido").text(response['TOTAL_VENCIDO']);

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

    function historial()
    {
        $("#modal_historial").modal("show");
        var f = new Date();
        
        var dia  = f.getDate(); 
        var mes  = (f.getMonth() +1);
        var anio = f.getFullYear();
        if(mes<10)
            mes = "0"+mes;
        
        $("#historial_fin").val(anio+"-"+mes+"-"+dia);

        var dia  = '01'; 
        var mes  = (f.getMonth() +1);
        var anio = f.getFullYear();
        
        if(parseInt(mes) <= 2)
        {
            if(parseInt(mes) ==2)
                mes = 12;
            else if(parseInt(mes) ==1)
                mes = 11;
                
            anio = parseInt(anio)-1;
        }else
        {
            mes = parseInt(mes) - 2;
        }
        if(mes<10)
            mes = "0"+mes;
        
        console.log(anio+"-"+mes+"-"+dia);
        $("#historial_inicio").val(anio+"-"+mes+"-"+dia);
        
    }

    function buscar_historial()
    {

        var variable = "accion=historial&"+$("#FORM_HISTORIAL").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "carga_historial");
    }

    function carga_historial(response)
    {
        console.log(response);
        var datagrid = $("#tabla_historial");
        datagrid.html("");
        $.each(response, function(index, value)
        {
            var campos = "";
            var linea = $("<tr></tr>");
            campos += "<td>"+value['FACTURA']+"</td>";
            campos += "<td>"+value['FECHA_FACTURA']+"</td>";
            campos += "<td>"+value['NOMBRE']+"</td>";
            campos += "<td>+ "+value['SUBTOTAL']+"<br>- "+value['DESCUENTO']+"<br>= "+value['TOTAL']+"</td>";
            campos += "<td>"+value['FECHA_PAGADO']+"</td>";
            campos += "<td><button type='button' class='btn btn-primary btn_informacion'  onclick='info_historial(this, "+value['MS_PROVEEDOR_ID']+", \""+value['FACTURA']+"\", \""+value['FECHA_FACTURA']+"\")'><i class='fa fa-info'></i></button><button type='button' class='btn btn-warning btn_desactiva_informacion' style='display: none;' onclick='desactiva_info()'><i class='fa fa-info'></i></button></td>";
            
            
            linea.append(campos);
            datagrid.append(linea);

            contador++;

        });
    }

    function desactiva_info()
    {
        $(".btn_informacion").show();
        $(".btn_desactiva_informacion").hide();
        $("#tabla_historial").find("tr").show();
        $("#tabla_info_general").hide();
    }

    function info_historial(obj, proveedor, factura, fecha)
    {
        $(".btn_informacion").hide();
        $(".btn_desactiva_informacion").show();
        $(obj).parents("tbody").find("tr").hide();
        $(obj).parents("tr").show();
        $("#tabla_info_general").show();
        var variable = "accion=info_historial&proveedor="+proveedor+"&factura="+factura+"&fecha="+fecha;
        RestFullRequest("_Rest/Almacen.php", variable, "carga_info_historial");
    }

    function carga_info_historial(response)
    {
        var datagrid = $("#tabla_info_historiaL");
        datagrid.html("");
        $.each(response, function(index, value)
        {
            var campos = "";
            var linea = $("<tr></tr>");
            campos += "<td>"+value['ARTICULO']+"</td>";
            campos += "<td>"+value['CANTIDAD']+"</td>";
            campos += "<td>"+value['SUBTOTAL']+"</td>";
            linea.append(campos);
            datagrid.append(linea);

            contador++;

        });
    }

    function Formulario(Response)
    {
        
        $("#campo_almacen").html("");
        $("#almacen_transferencia").html("");
        $("#filtro_almacen").html("");
        $("#proveedor").html("");
        $("#categoria").html("<option value='0'>CATEGORIA</option>");
        $("#familia").html("<option value='0'>FAMILIA</option>");
        $("#historial_proveedor").html("<option value='0'>PROVEEDOR</option>");
        $("#filtro_grupo").html("<option value='0'>PROVEEDOR</option>");

        $.each(Response['ALMACENES'], function(index, value)
        {
            $("#campo_almacen").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

        $.each(Response['ALMACENES'], function(index, value)
        {
            $("#filtro_almacen").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

        $.each(Response['PROVEEDOR'], function(index, value)
        {
            $("#proveedor").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
            $("#filtro_grupo").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
            $("#historial_proveedor").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

        $.each(Response['CATEGORIA'], function(index, value)
        {
            $("#categoria").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

        $.each(Response['CATEGORIA'], function(index, value)
        {
            $("#familia").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

       
        /*$.each(Response['CATEGORIA'], function(index, value)
        {
            $("#filtro_grupo").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });*/
    }

    $("#categoria").change(function()
    {
        var variable = "accion=articulos&familia="+$(this).val();
        RestFullRequest("_Rest/Almacen.php", variable, "catalogoArticulo");
    });

    function catalogoArticulo(Response)
    {
        $("#articulo").html("<option value='0'>ARTICULOS</option>");
        $.each(Response['ARTICULOS'], function(index, value)
        {
            $("#articulo").append("<option value='"+value['ID']+"'>"+value['DESCRIPCION']+"</option>");
        });
    }
    
    function pagado(proveedor, factura)
    {
        if(confirm("¿DESEA PAGAR ESTA FACTURA?"))
        {
            var variable = "accion=pagar&proveedor="+proveedor+"&factura="+factura;
            RestFullRequest("_Rest/Pagos.php", variable, "actualizaDatagrid");
        }
    }

    function descuento(proveedor, factura)
    {
        $("#FORM_DESCUENTO").find("#id_proveedor").val(proveedor);
        $("#FORM_DESCUENTO").find("#factura").val(factura);
        $("#FORM_DESCUENTO").find("#monto").val("0.00");
        $("#modal_descuento").modal("show");

        var variable = "accion=carga_monto&"+$("#FORM_DESCUENTO").serialize();
        RestFullRequest("_Rest/Pagos.php", variable, "carga_monto");
    
    }

    function carga_monto(response)
    {
        $("#FORM_DESCUENTO").find("#monto").val(response[0]);
    }

    function guardar_factura()
    {
        var variable = "accion=guardar_factura&"+$("#FORM_FACTURA").serialize();
        RestFullRequest("_Rest/Pagos.php", variable, "limpiar_formulario", 1);
    }

    function cerrar_factura() {
        var variable = "accion=cerrar_factura";
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_tabla_inventario",1);
        actualiza_lista_factura();
        $("#FORM_ALMACEN").find("input").val("");
        $("#FORM_ALMACEN").find("select").val(1);
        
    }

    function actualiza_tabla_inventario(Response)
    {
        $("#lista_inventario_baja").html("");
        $.each(Response, function(index, value)
        {
            var dimension = "--- ";
            var lineal = 0;
            if(value['ANCHO']!=0 && value['LARGO'] != 0)
                dimension = "( "+value['ANCHO']+" * "+value['LARGO']+" ) "; 


            if(value['ANCHO']!=0)
                lineal = (value['CANTIDAD_RESTANTE'] / value['ANCHO']);
            else
                lineal = (value['CANTIDAD_RESTANTE']);

            var campos = "<tr>";
            campos += "<td>"+value['ID']+"</td>";
            campos += "<td>"+value['NOMBRE_ARTICULO']+"</td>";
            campos += "<td>"+dimension+"</td>";
            campos += "<td><input type='text' name='cantidad_"+value['ID']+"' value='"+parseFloat(lineal).toFixed( 2 )+"' class='form-control' onblur='calcula(this,"+value['ID']+", "+value['UNITARIO']+", "+value['ANCHO']+")'><input type='hidden' name='ids[]' value='"+value['ID']+"'></td>";
            campos += "<td><input type='text' name='dimension_"+value['ID']+"' id='id_"+value['ID']+"' value='"+value['CANTIDAD_RESTANTE']+"' class='form-control' readonly='readonly'></td>";
            campos += "<td><button type='button' class='btn btn-success' onclick='baja_completa("+value['ID']+")'><i class='fa fa-arrow-circle-down'></i></button</td>";
            campos += "</tr>";


            $("#lista_inventario_baja").append(campos);
        });
    }

    function limpiar_formulario(response)
    {
        $("#FORM_FACTURA").find("input").val("");
        $("#modal_factura").modal("hide");
        actualizaDatagrid();
    }

    function guardar_descuento()
    {
        var variable = "accion=guardar_descuento&"+$("#FORM_DESCUENTO").serialize();
        RestFullRequest("_Rest/Pagos.php", variable, "actualizaDatagrid", 1);   
    }

    function eliminar(proveedor, factura)
    {
        if(confirm("¿DESEA ELIMINAR ESTA FACTURA?"))
        {
            var variable = "accion=eliminar&proveedor="+proveedor+"&factura="+factura;
            RestFullRequest("_Rest/Pagos.php", variable, "actualizaDatagrid", 1);
        }
    }

    function informacion(proveedor, factura)
    {
        $("#modal_informacion").modal("show");
        $("#proveedor_id").val(proveedor);
        $("#factura_id").val(factura);
        var variable = "accion=informacion&proveedor="+proveedor+"&factura="+factura;
        RestFullRequest("_Rest/Pagos.php", variable, "ver_informacion");
    }

    function ver_informacion(response)
    {
        var factura = "";
        var fecha = "";
        var proveedor = "";
        var descuento = 0;
        var monto_general = 0;
        $("#lista_articulos").html("");
        $.each(response, function(index, value)
        {
            factura     = value['FACTURA'];
            fecha       = value['FECHA'];
            proveedor   = value['PROVEEDOR'];

            var linea = $("<tr></tr>");
            if(value['MS_PROVEEDOR_ID'] == 37)
                var campo1 = $("<td>"+value['DESCRIPCION']+"</td>");
            else
                var campo1 = $("<td>"+value['FAMILIA']+" - "+value['ARTICULO']+"</td>");

            var campo2 = $("<td>"+value['CANTIDAD']+"</td>");
            var campo3 = $("<td>"+value['MONTO']+"</td>");

            linea.append(campo1);
            linea.append(campo2);
            linea.append(campo3);
            $("#lista_articulos").append(linea);

            monto_general = monto_general + parseFloat(value['MONTO']);
            descuento = descuento + value['DESCUENTO'];
        });
        $("#FORM_INFO #factura").val(factura);
        $("#FORM_INFO #fecha").val(fecha);
        $("#FORM_INFO #proveedor").val(proveedor);
        
        monto_general = parseFloat(monto_general).toFixed(2);    
        descuento = parseFloat(descuento).toFixed(2);    
        var total = parseFloat(monto_general - descuento).toFixed(2); 
        monto_general = parseFloat(monto_general).toFixed(2);    
        
        $("#FORM_INFO #subtotal").text(monto_general);
        $("#FORM_INFO #descuento").text(descuento);
        $("#FORM_INFO #total").text(total);
    }

    function btn_guardar_inventario()
    {
        
        var variable = "accion=guardar&"+$("#FORM_ALMACEN").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "actualiza_lista_factura",1);
    }

    function actualiza_lista_factura()
    {
        var variable = "accion=actualiza_lista_factura";
        RestFullRequest("_Rest/Almacen.php", variable, "load_lista_factuas");

    }

    function verificar_factura()
    {
        var variable = "accion=verficar_factura&"+$("#FORM_ALMACEN").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "valida_factura");
    }

    function valida_factura(response)
    {
        if(response.numero == 1)
            $("#FORM_ALMACEN #doble_factura").show();
        else
            $("#FORM_ALMACEN #doble_factura").hide();

        //$("#FORM_DATOS #cuerpo_datos").html("");
        var datagrid = $("#FORM_DATOS #cuerpo_datos");
        $.each(response['articulos'], function(index, value)
        {
            //console.log(value);
            var linea = $("<tr></tr>");
            var celda1 = $("<td>"+value['FACTURA']+"</td>");
            var celda2 = $("<td>"+value['FECHA_FACTURA']+"</td>");
            var celda3 = $("<td>"+value['NOMBRE_ARTICULO']+"</td>");
            var celda4 = $("<td>"+value['CANTIDAD']+"</td>");
            var celda5 = $("<td>"+value['MONTO']+"</td>");
            
            linea.append(celda1);
            linea.append(celda2);
            linea.append(celda3);
            linea.append(celda4);
            linea.append(celda5);

            datagrid.append(linea);
        });
    }

    function load_lista_proveedores()
    {
        $("#FORM_PROVEEDOR").find("input").val("");
        cargaFormularios();
    }

    function guarda_proveedor()
    {
        var variable = "accion=guardar_proveedor&"+$("#FORM_PROVEEDOR").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "load_lista_proveedores",1);
        
    }

    function load_lista_factuas(Response)
    {
        $("#lista_factura").html("");
        var total = 0;
        $.each(Response, function(index, value)
        {
            $("#lista_factura").append("<tr><td>"+value['FACTURA_COMPRA']+"</td><td>"+value['NOMBRE_ARTICULO']+"</td><td>"+value['REGISTROS']+"</td><td>"+value['UNIDADES']+"</td><td>"+value['PRECIO_UNITARIO']+"</td><td>"+value['PRECIO_COMPRA']+"</td></tr>");
            total = parseFloat(total) + parseFloat(value['PRECIO_COMPRA']);
        });
        $("#total_factura").text(total);
        //resetFormFactura();
        actualizaDatagrid();
    }

    function agregar_inventario()
    {
        $("#modal_factura").modal("show");
        $("#FORM_ALMACEN #doble_factura").hide();
        actualiza_lista_factura();
    }

    function cerrar_factura() {
        $("#FORM_ALMACEN #doble_factura").hide();
        var variable = "accion=cerrar_factura";
        RestFullRequest("_Rest/Almacen.php", variable, "actualizaDatagrid",1);
        $("#FORM_ALMACEN").find("input").val("");
        $("#FORM_ALMACEN").find("select").val(1);
         $("#modal_factura").modal("hide");
        
    }

    function guarda_folio()
    {
        $("#modal_informacion").hide();
        var variables = $("#FORM_INFO").serialize();
        var variable = "accion=guarda_folio&"+variables;
        RestFullRequest("_Rest/Almacen.php", variable, "actualizaDatagrid",1);
    }