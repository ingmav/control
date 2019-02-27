    function actualizaDatagrid()
    {
        console.log($("#filtro_general").serialize());
        var variable = "accion=index&"+$("#filtro_general").serialize();
        RestFullRequest("_Rest/inventario_visual.php", variable, "datagrid");
    }

    function datagrid(response)
    {
        console.log(response);
        var contador = 0;
        var datagrid = $("#inventario");

        datagrid.find("tr").remove();

        $.each(response, function(index, value)
        {
            var campos = "";
            linea = $("<tr>");
            

            campos += "<td>"+value['ARTICULO']+"</td>";
            //campos += "<td>"+value['COMPROMETIDO']+"</td>";
            campos += "<td>"+value['MS_INVENTARIO']+"</td>";
           // var disponible = parseFloat(value['MS_INVENTARIO']) - parseFloat(value['MS_INVENTARIO']);
            //campos += "<td>"+value['DISPONIBLE']+"</td>";
            
            //campos += "<td><input type='checkbox' name='bajas[]' value='"+value['ID']+"'></td>";
            linea.append(campos);
            datagrid.append(linea);

            contador++;

        });

        if(contador == 0)
            datagrid.append("<tr><td colspan='3'>NO HAY DATOS QUE MOSTRAR</td></tr>");
    }

    $(document).ready(function()
    {
        actualizaDatagrid();
        $("#menu_inventario").find("a").click();
        //cargaFormularios();
    });

    function bajas()
    {
        var variable = "accion=baja&"+$("#form_almacen").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "actualizaDatagrid");
    }

    function cargaFormularios()
    {
        var variable = "accion=formularios";
        RestFullRequest("_Rest/Almacen.php", variable, "Formulario");
    }

    function agregar_inventario()
    {
        $("#modal_inventario").modal("show");
    }

    function agregar_almacen()
    {
        $("#modal_almacen_alta").modal("show");
    }


    function transferencia(){
        $("#modal_transferencia").modal("show");        
    }

    function Formulario(Response)
    {
        $("#articulo").html("");
        $("#campo_almacen").html("");
        $("#almacen_transferencia").html("");
        $("#filtro_almacen").html("");

        $.each(Response['ARTICULOS'], function(index, value)
        {
            $("#articulo").append("<option value='"+value['ID']+"'>"+value['DESCRIPCION']+"</option>");
        });

        $.each(Response['ALMACENES'], function(index, value)
        {
            $("#campo_almacen").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

        $.each(Response['ALMACENES'], function(index, value)
        {
            $("#almacen_transferencia").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });

        $.each(Response['ALMACENES'], function(index, value)
        {
            $("#filtro_almacen").append("<option value='"+value['ID']+"'>"+value['NOMBRE']+"</option>");
        });
    }

    function guardar()
    {
        var variable = "accion=guardar&"+$("#FORM_ALMACEN").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "resetForm");
        
    }


    function guardar_almacen()
    {
        var variable = "accion=guardar_almacen&"+$("#FORM_ALMACEN_ALTA").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "resetFormalmacen");
        
    }

    function guardar_transferencia()
    {
        var variable = "accion=guardar_transferencia&"+$("#FORM_TRANSFERENCIA").serialize()+"&"+$("#form_almacen").serialize();
        RestFullRequest("_Rest/Almacen.php", variable, "resetFormtransferencia");
        
    }

    function resetForm(Response)
    {
        $("#FORM_ALMACEN").find("input").val("");
        actualizaDatagrid();
    }

    function resetFormalmacen(Response)
    {
        $("#FORM_ALMACEN_ALTA").find("input").val("");
        $("#modal_almacen_alta").modal("hide");
        cargaFormularios();
    }

    function resetFormtransferencia(Response)
    {
        $("#modal_transferencia").modal("hide");    
        actualizaDatagrid();
    }