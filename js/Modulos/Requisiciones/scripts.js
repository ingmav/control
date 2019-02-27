/**
 * Created by SALUD on 18/05/15.
 */
var Obj_auxiliar;
var paginacion = 1;
var realizados = 0;
var id_regitros_articulos = 0;
$("#guardaRequisicion").on("click", function()
{
    var folio = $("#foliorequisicion").val();
    if(!$("#Message").is(":visible"))
    {
    
        if(folio.replace(/\s/g, "").length > 0)
        {

            if(parseInt(folio) > 0)
            {
              if($("#id").val() > 0)
              {
                  var variable = "accion=update&"+$("#FormRequisicion").serialize();
                  RestFullRequest("_Rest/Requisiciones.php", variable, "agregaRegistro", 1);
              }else
              {
                  var variable = "accion=save&"+$("#FormRequisicion").serialize();
                  RestFullRequest("_Rest/Requisiciones.php", variable, "agregaRegistro", 1);
              }
          }else{
            if($("#tipo_documento").val()==4)
            {
               if($("#id").val() > 0)
              {
                  var variable = "accion=update&"+$("#FormRequisicion").serialize();
                  RestFullRequest("_Rest/Requisiciones.php", variable, "agregaRegistro", 1);
              }else
              {
                  var variable = "accion=save&"+$("#FormRequisicion").serialize();
                  RestFullRequest("_Rest/Requisiciones.php", variable, "agregaRegistro", 1);
              } 
          }else
            alert("Debe de ingresar el numero de factura o remisión1");  
          }
        }else {
          alert("Debe de ingresar el numero de factura o remisión2");
        }
    }else
    {
        alert("ESPERE UN MOMENTO POR FAVOR, SE ESTA EJECUTANDO UNA PETICIÓN AL SERVIDOR");
    }
});

$("#articulo").on("keyup", function(index, value)
{
    Obj_auxiliar = $(this).parent().find(".caja");
    
    if($(this).val().replace(/\s/g, "").length)
    {
        Obj_auxiliar.css("visibility", "visible");
        var variable = "accion=autocomplete_articulos&query="+$(this).val().trim();
        RestFullRequest("_Rest/General.php", variable, "listar_articulos", 0);
    }else
    {
        Obj_auxiliar.css("visibility", "hidden");
    }
});

function listar_articulos(Response){
    //var caja = $(".caja");
    Obj_auxiliar.html("");
    $.each(Response, function(index, value)
    {
        Obj_auxiliar.append("<div class='caja_registros' onclick=\"cargaDatos('"+value.IDARTICULO+"', '"+value.IDSUBARTICULO+"', '"+value.NOMBRE+"', $(this));\">"+value.NOMBRE+"</div>");   
    });
}

function cargaDatos(id, id_sub, nombre, obj)
{
    $("#articulo").val(nombre);
    $("#articulo_id").val(id);
    $("#subarticulo_id").val(id_sub);
    Obj_auxiliar.css("visibility", "hidden");
    
}

function verifica_tipo(Obj)
{
    if($(Obj).val()==4)
    {
        var variable = "accion=calcula_folio";;
        var respuesta = RestFullRequest("_Rest/Requisiciones.php", variable, "calcula_folio", 0);

        $("#tab_datos_requisicion").css("visibility", "visible");
        $("#foliorequisicion").val("0");
    }else{
        $("#tab_datos_requisicion").css("visibility", "hidden");
        $("#tab_datos_venta_requisicion").css("visibility", "hidden");  
        $("#foliorequisicion").val("");
    }
}

function calcula_folio(response)
{
    $("#FormRequisicion #foliorequisicion").val(response.PAGINADOR+1);
}

$("#proveedor").on("keyup", function(index, value)
{
     Obj_auxiliar = $(this).parent().find(".caja");
    if($(this).val().replace(/\s/g, "").length)
    {
       
        Obj_auxiliar.css("visibility", "visible");
        var variable = "accion=autocomplete_proveedor&query="+$(this).val().trim();
        var respuesta = RestFullRequest("_Rest/General.php", variable, "listar_articulos_proveedor", 0);
    }else
    {
        Obj_auxiliar.css("visibility", "hidden");
    }
});

function listar_articulos_proveedor(Response){

    Obj_auxiliar.html("");
    $.each(Response, function(index, value)
    {
        Obj_auxiliar.append("<div class='caja_registros' onclick=\"cargaDatosProveedor('"+value.NOMBRE+"', $(this));\">"+value.NOMBRE+"</div>");   
    });
}

function cargaDatosProveedor(nombre, obj)
{
    $("#proveedor").val(nombre);
    Obj_auxiliar.css("visibility", "hidden");
    
}
function requisicionesrealizadas()
{
    if(realizados == 0)
    {
        realizados = 1;
        actualizaDatagrid();
    }else
    {
        realizados = 0;
        actualizaDatagrid();
    }
}

function agregaRegistro(response)
{
    $("#myModal").modal("hide");
    actualizaDatagrid();
}

$("#agregar").on("click", function()
{
    reset_formulario();
    $("#tab_datos_generales_form").find("a").click();
    $("#myModal").modal("show");
    //limpiaForm($("#FormRequisicion"));
});

function reset_formulario()
{
    $("#FormRequisicion").find("input").val("");
    $("#FormRequisicion").find("select").val(1);
    $("#registros_articuos_requisicion").html("<tr data-id='NODATA'><td colspan='6'>NO SE ENCUENTRAN RESULTADOS</td></tr>");
    $("#resultado_articulos_requisiciones").html("<tr><td colspan='4'>TOTAL</td><td id='monto_resultado'>$ 0.00</td><td></td></tr>");
    $("#tab_datos_requisicion").css("visibility", "hidden");
    $("#tab_datos_venta_requisicion").css("visibility", "hidden");
}
$("#borrar").on("click", function()
{
    var contador = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
        contador++;
    });

    if(contador > 0)
    {
        if(confirm("¿REALMENTE DESEA ELIMINAR EL/LOS REGISTROS?"))
        {
            var variable = "accion=eliminar&"+$("#FormDatagrid").serialize();
            RestFullRequest("_Rest/Requisiciones.php", variable, "actualizaDatagrid", 2);
        }
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

$("#surtido").on("click", function()
{
    var contador = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
        contador++;
    });

    if(contador > 0)
    {
        if(confirm("¿REALMENTE DESEA SURTIR EL/LOS REGISTROS?"))
        {
            var variable = "accion=surtir&"+$("#FormDatagrid").serialize();
            RestFullRequest("_Rest/Requisiciones.php", variable, "actualizaDatagrid", 2);
        }
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

$("#validado").on("click", function()
{
    var contador = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
        contador++;
    });

    if(contador > 0)
    {
        if(confirm("¿REALMENTE DESEA VALIDAR EL/LOS REGISTROS?"))
        {
            var variable = "accion=validar&"+$("#FormDatagrid").serialize();
            RestFullRequest("_Rest/Requisiciones.php", variable, "actualizaDatagrid", 2);
        }
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

$("#modificar").on("click", function()
{

    var contador = 0;
    $("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
        contador++;
    });


    if(contador > 0)
    {
        var variable = "accion=modificar&"+$("#FormDatagrid").serialize();
        RestFullRequest("_Rest/Requisiciones.php", variable, "CargaRequisicion");
        $("#tab_datos_generales_form").find("a").click();
        $("#myModal").modal("show");
    }else
        alert("DEBE DE SELECCIONAR UN REGISTRO");
});

function CargaRequisicion(response)
{

    $("#tab_datos_requisicion").css("visibility", "hidden");
    $("#tab_datos_venta_requisicion").css("visibility", "hidden");  

    $("#id").val(response[0].ID);
    $("#empresa").val(response[0].EMPRESA);
    $("#tipo_documento").val(response[0].TIPO_DOCUMENTO);
    $("#foliorequisicion").val(response[0].FOLIO);
    $("#fechaSolicitud").val(response[0].FECHA);
    $("#cliente").val(response[0].CLIENTE);
    $("#observacion").val(response[0].OBSERVACION);
    $("#estatus").val(response[0].ESTATUS);
    $("#forma_pago").val(response[0].FORMA_PAGO);
    
    
    $("#registros_articuos_requisicion").html("");
    $.each(response[0].ARTICULOS, function(index, value)
    {
       
       id_regitros_articulos++;
        if($("#registros_articuos_requisicion tr").data("id") == "NODATA")
            $("#registros_articuos_requisicion").html("");
        var tabla = $("#registros_articuos_requisicion");
        var linea = $("<tr data-id='"+id_regitros_articulos+"'></tr>");
        var proveedor = value.PROVEEDOR;
        var nombre_proveedor = "<input type='hidden' name='proveedor[]' id='proveedor_"+id_regitros_articulos+"' value='"+proveedor+"'>";
        var identificador = "<input type='hidden' name='identificador[]' id='identificador_"+id_regitros_articulos+"' value='"+value.ID+"'>";

        var registro_1 = $("<td>"+proveedor+nombre_proveedor+"</td>");
        
        var articulo = value.ARTICULO;
        var articulo_id = value.ARTICULO_ID;
        var sub_articulo_id = value.SUBARTICULO_ID;

        var proveedor_factura = value.FACTURA;
        var nombre_proveedor_factura = "<input type='hidden' name='factura_proveedor[]' id='factura_proveedor_"+id_regitros_articulos+"' value='"+proveedor_factura+"'>";
        
        var registro_2 = $("<td>"+proveedor_factura+nombre_proveedor_factura+"</td>");


        var nombre_articulo = "<input type='hidden' name='nombre_articulo[]' id='articulo_"+id_regitros_articulos+"' value='"+articulo+"'>";
        var id_articulo = "<input type='hidden' name='id_articulo[]' id='id_articulo_"+id_regitros_articulos+"' value='"+articulo_id+"'>";
        var id_sub_articulo = "<input type='hidden' name='id_sub_articulo[]' id='id_sub_articulo_"+id_regitros_articulos+"' value='"+sub_articulo_id+"'>";
        
        var registro_3 = $("<td>"+articulo+nombre_articulo+id_articulo+id_sub_articulo+"</td>");

        var cantidad = value.CANTIDAD;
        var cantidad_valor = "<input type='hidden' name='cantidad[]' id='cantidad_"+id_regitros_articulos+"' value='"+cantidad+"'>";
        
        var registro_4 = $("<td>"+cantidad+cantidad_valor+"</td>");
        
        var unidad = value.UNIDAD;
        var unidad_valor = "<input type='hidden' name='unidad[]' id='unidad_"+id_regitros_articulos+"' value='"+unidad+"'>";
        
        var registro_5 = $("<td>"+unidad+unidad_valor+"</td>");

        var importe = value.IMPORTE;
        var importe_valor = "<input type='hidden' name='importe[]' id='importe_"+id_regitros_articulos+"' value='"+moneda(importe, 2, ['', "", '.'])+"'>";
        
        var registro_6 = $("<td>"+moneda(importe, 2, [',', ",", '.'])+importe_valor+"</td>");
        var boton_eliminar = "<button type='button' class='btn btn-danger' onclick='elimina_registro(this)'><i class='fa fa-trash'></i></button>";
        var boton_editar = "<button type='button' class='btn btn-primary' onclick='edita_registro(this)'><i class='fa fa-edit'></i></button>";
        var registro_7 = $("<td>"+boton_eliminar+" "+boton_editar+identificador+"</td>");
        linea.append(registro_1);
        linea.append(registro_2);
        linea.append(registro_3);
        linea.append(registro_4);
        linea.append(registro_5);
        linea.append(registro_6);
        linea.append(registro_7);
        tabla.append(linea);
    });
    calcula_total_articulos();

    if(response[0].TIPO_DOCUMENTO==4){
        $("#tab_datos_requisicion").css("visibility", "visible");
    }
    else
        $("#foliorequisicion").blur();    
    
}

function actualizaDatagrid()
{
    var variable = "accion=index&"+$("#FormCotizacion").serialize()+"&realizados="+realizados+"&page="+paginacion+"&clientefiltro="+$("#clientefiltro").val()+"&estatusfiltro="+$("#estatusfiltro").val()+"&mesfiltro="+$("#mesfiltro").val()+"&foliofiltro="+$("#foliofiltro").val();
    RestFullRequest("_Rest/Requisiciones.php", variable, "datagridCotizacion");
}
function pages(Obj, value)
{
    $(".pagination li").removeClass("active");
    $(Obj).addClass("active");
    paginacion = value;
    actualizaDatagrid();
}



/*function limpiaForm(Formulario)
{
    $(Formulario).find('select').each(function(index, element) {
        $(this).find("option").eq(0).prop('selected', true);
    });

    $(Formulario).find("input[type=text]").val("");
    $(Formulario).find("textArea").val("");
    $(Formulario).find("input[type=text]").val("");
    $(Formulario).find("input").val("");
}*/

function datagridCotizacion(response)
{
    //paginador();
    creaPaginador(response.CONTADOR);
    var datagrid = $("#data");
    datagrid.find("tr").remove();
    var contador = 0;

    $.each(response.DATOS, function(index, value)
    {
      var pago = "";
       switch(value['FORMA_PAGO'])
       {
            case "1": pago = "EFECTIVO"; break;
            case "2": pago = "CHEQUE"; break;
            case "3": pago = "TARJETA"; break;
            case "4": pago = "CREDITO"; break;
            case "5": pago = "TRANSFERENCIA"; break;
       }

       var folio = "";
       if(value['TIPO_DOCUMENTO'] != 4)
       {
           if(value['EMPRESA'] == 1)
                folio = "NX"
            else if(value['EMPRESA'] == 2)
                folio = "NP";
        }

        if(value['TIPO_DOCUMENTO'] == 1)
            folio += "F-";
        else if(value['TIPO_DOCUMENTO'] == 2)
            folio += "R-";
        else if(value['TIPO_DOCUMENTO'] == 3)
            folio += "V-A";
        else if(value['TIPO_DOCUMENTO'] == 4)
            folio += "I-";

        linea = $("<tr></tr>");
        var campos = "";
        campos += "<td>"+folio+value['FOLIO']+"</td>";
        campos += "<td>"+value['FECHA']+"</td>";
        //campos += "<td>OPERADOR: "+value['OPERADOR.ALIAS']+"<br><span style='background-color: #CFCFCF'>PROVEEDOR: "+value['REQUISICIONES.PROVEEDOR']+"</span><br>";
        campos += "<td>OPERADOR: "+value['ALIAS']+"<br><span style='background-color: #CFCFCF'>";
        //campos += "CANTIDAD: "+currency(value['REQUISICIONES.CANTIDAD'],2,".")+" "+value['REQUISICIONES.UNIDADMEDIDA']+" "+value['REQUISICIONES.MATERIAL']+"<br>";
        campos += "CLIENTE: "+value['CLIENTE']+"</span>";
        campos += "<BR>PROVEEDOR: "+value['PROVEEDOR']+"</td>";

        campos += "<td>"+currency(value['SUMA'], 2, ".")+"<br><b>("+pago+")</b></td>";

        var Estatus = "PENDIENTE";
        if(value['ESTATUS'] == 2)
            Estatus = "SURTIDO";
        if(value['ESTATUS'] == 3)
            Estatus = "VALIDADO";


        campos += "<td>"+Estatus+"</td>";

        //console.log(value['Cotizacion.ID']);
        campos += "<td align='center'><input type='checkbox' name='id[]' value='"+value['ID']+"'></td>";
        linea.append(campos);

        datagrid.append(linea);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function creaPaginador(Response)
{
    console.log(Response);
    $(".pagination").find("li").remove();
    var paginas = Math.ceil((Response / 20));
  
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

/*function paginador()
{
    var variable = "accion=counter&empresa=1&realizados="+realizados+"&page="+paginacion+"&clientefiltro="+$("#clientefiltro").val()+"&estatusfiltro="+$("#estatusfiltro").val()+"&mesfiltro="+$("#mesfiltro").val();
    RestFullRequest("_Rest/Requisiciones.php", variable, "creaPaginador");

}*/

$(document).ready(function(e) {
    actualizaDatagrid();
    //setInterval(actualizaDatagrid,  900000);
    $("#control").find("a").click();

});

function currency(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}

function reporte()
{

    $("#FormDatagrid").attr("action","reportes/requerimientos/ReporteRequerimientos.php");
    $("#FormDatagrid").attr("method","POST");
    $("#FormDatagrid").attr("target","_blank");
    $("#FormDatagrid").submit();
    $("#FormDatagrid").attr("action","");
    $("#FormDatagrid").attr("method","");
    $("#FormDatagrid").attr("target","");
}

function reporte2()
{

    $("#FormDatagrid").attr("action","reportes/requerimientos/ReporteRequerimientos2.php");
    $("#FormDatagrid").attr("method","POST");
    $("#FormDatagrid").attr("target","_blank");
    $("#FormDatagrid").submit();
    $("#FormDatagrid").attr("action","");
    $("#FormDatagrid").attr("method","");
    $("#FormDatagrid").attr("target","");
}

$("#agregar_articulo").on("click", function()
{
    //console.log($("#FormRequisicion #id").val());
    if($("#FormRequisicion #idregistroarticulo").val() == 0)
    {
        id_regitros_articulos++;
        if($("#registros_articuos_requisicion tr").data("id") == "NODATA")
            $("#registros_articuos_requisicion").html("");

        var tabla = $("#registros_articuos_requisicion");
        var linea = $("<tr data-id='"+id_regitros_articulos+"'></tr>");
        var proveedor = $("#FormRequisicion #proveedor").val();
        var nombre_proveedor = "<input type='hidden' name='proveedor[]' id='proveedor_"+id_regitros_articulos+"' value='"+proveedor+"'>";
        var identificador = "<input type='hidden' name='identificador[]' id='identificador_"+id_regitros_articulos+"' value='0'>";

        var registro_1 = $("<td>"+proveedor+nombre_proveedor+"</td>");
        

        var proveedor_factura = $("#FormRequisicion #factura_proveedor").val();
        var nombre_proveedor_factura = "<input type='hidden' name='factura_proveedor[]' id='factura_proveedor_"+id_regitros_articulos+"' value='"+proveedor_factura+"'>";
        
        var registro_2 = $("<td>"+proveedor_factura+nombre_proveedor_factura+"</td>");
        
        var articulo = $("#FormRequisicion #articulo").val();
        var articulo_id = $("#FormRequisicion #articulo_id").val();
        var sub_articulo_id = $("#FormRequisicion #subarticulo_id").val();
        
        var nombre_articulo = "<input type='hidden' name='nombre_articulo[]' id='articulo_"+id_regitros_articulos+"' value='"+articulo+"'>";
        var id_articulo = "<input type='hidden' name='id_articulo[]' id='id_articulo_"+id_regitros_articulos+"' value='"+articulo_id+"'>";
        var id_sub_articulo = "<input type='hidden' name='id_sub_articulo[]' id='id_sub_articulo_"+id_regitros_articulos+"' value='"+sub_articulo_id+"'>";
        
        var registro_3 = $("<td>"+articulo+nombre_articulo+id_articulo+id_sub_articulo+"</td>");

        var cantidad = $("#FormRequisicion #cantidad").val();
        var cantidad_valor = "<input type='hidden' name='cantidad[]' id='cantidad_"+id_regitros_articulos+"' value='"+cantidad+"'>";
        
        var registro_4 = $("<td>"+cantidad+cantidad_valor+"</td>");
        
        var unidad = $("#FormRequisicion #unidad").val();
        var unidad_valor = "<input type='hidden' name='unidad[]' id='unidad_"+id_regitros_articulos+"' value='"+unidad+"'>";
        
        var registro_5 = $("<td>"+unidad+unidad_valor+"</td>");

        var importe = $("#FormRequisicion #importe").val();
        var importe_valor = "<input type='hidden' name='importe[]' id='importe_"+id_regitros_articulos+"' value='"+moneda(importe, 2, ['', "", '.'])+"'>";
        
        var registro_6 = $("<td>"+moneda(importe, 2, [',', ",", '.'])+importe_valor+"</td>");
        var boton_eliminar = "<button type='button' class='btn btn-danger' onclick='elimina_registro(this)'><i class='fa fa-trash'></i></button>";
        var boton_editar = "<button type='button' class='btn btn-primary' onclick='edita_registro(this)'><i class='fa fa-edit'></i></button>";
        var registro_7 = $("<td>"+boton_eliminar+" "+boton_editar+identificador+"</td>");
        linea.append(registro_1);
        linea.append(registro_2);
        linea.append(registro_3);
        linea.append(registro_4);
        linea.append(registro_5);
        linea.append(registro_6);
        linea.append(registro_7);
        tabla.append(linea);
    }else{
        
        $("#registros_articuos_requisicion tr").each(function()
        {
            if($(this).data("id") == $("#FormRequisicion #idregistroarticulo").val())
            {
                var linea = $(this);
                var id_registro_edicion = linea.data("id");
                var proveedor = $("#FormRequisicion #proveedor").val();
                var nombre_proveedor = "<input type='hidden' name='proveedor[]' id='proveedor_"+id_registro_edicion+"' value='"+proveedor+"'>";
                //var identificador = "<input type='hidden' name='identificador[]' id='identificador_"+id_registro_edicion+"' value='0'>";

                linea.find("td:nth-child(1)").html(""+proveedor+nombre_proveedor+"");
                
                var factura = $("#FormRequisicion #factura_proveedor").val();
                var nombre_factura = "<input type='hidden' name='factura_proveedor[]' id='factura_proveedor_"+id_registro_edicion+"' value='"+factura+"'>";
                
                linea.find("td:nth-child(2)").html(""+factura+nombre_factura+"");    

                var articulo = $("#FormRequisicion #articulo").val();
                var articulo_id = $("#FormRequisicion #articulo_id").val();
                var sub_articulo_id = $("#FormRequisicion #subarticulo_id").val();
                
                var nombre_articulo = "<input type='hidden' name='nombre_articulo[]' id='articulo_"+id_registro_edicion+"' value='"+articulo+"'>";
                var id_articulo = "<input type='hidden' name='id_articulo[]' id='id_articulo_"+id_registro_edicion+"' value='"+articulo_id+"'>";
                var id_sub_articulo = "<input type='hidden' name='id_sub_articulo[]' id='id_sub_articulo_"+id_registro_edicion+"' value='"+sub_articulo_id+"'>";
                
                linea.find("td:nth-child(3)").html(articulo+nombre_articulo+id_articulo+id_sub_articulo);

                var cantidad = $("#FormRequisicion #cantidad").val();
                var cantidad_valor = "<input type='hidden' name='cantidad[]' id='cantidad_"+id_registro_edicion+"' value='"+cantidad+"'>";
                
                linea.find("td:nth-child(4)").html(cantidad+cantidad_valor);
                
                var unidad = $("#FormRequisicion #unidad").val();
                var unidad_valor = "<input type='hidden' name='unidad[]' id='unidad_"+id_registro_edicion+"' value='"+unidad+"'>";
                
                linea.find("td:nth-child(5)").html(unidad+unidad_valor);

                var importe = $("#FormRequisicion #importe").val();
                var importe_valor = "<input type='hidden' name='importe[]' id='importe_"+id_registro_edicion+"' value='"+importe+"'>";
                
                linea.find("td:nth-child(6)").html(moneda(importe, 2, [',', ",", '.'])+importe_valor);
            }
        });
    }
    reset_articulos();
    calcula_total_articulos();
});

function reset_articulos()
{
    $("#FormRequisicion #primera_linea input").val("");
    $("#FormRequisicion #segunda_linea input").val("");
}

function  elimina_registro(Obj)
{
    confirm("¿Realmente desea eliminar este articulo?")
    {
        $(Obj).parents("tr").remove();
    }
    calcula_total_articulos();
    if($("#registros_articuos_requisicion tr").length == 0)
    {
        $("#registros_articuos_requisicion").append("<tr><td colspan='6'>NO SE ENCUENTRAN RESULTADOS</td></tr>");
    }
}

function edita_registro(Obj)
{
    console.log(Obj);
    var id_linea = $(Obj).parents("tr").data("id");
    $("#FormRequisicion #idregistroarticulo").val(id_linea);
    $("#FormRequisicion #proveedor").val($("#proveedor_"+id_linea).val());
    $("#FormRequisicion #factura_proveedor").val($("#factura_proveedor_"+id_linea).val());
    $("#FormRequisicion #articulo").val($("#articulo_"+id_linea).val());
    $("#FormRequisicion #articulo_id").val($("#id_articulo_"+id_linea).val());
    $("#FormRequisicion #subarticulo_id").val($("#id_sub_articulo_"+id_linea).val());
    $("#FormRequisicion #cantidad").val($("#cantidad_"+id_linea).val());
    $("#FormRequisicion #unidad").val($("#unidad_"+id_linea).val());
    $("#FormRequisicion #importe").val($("#importe_"+id_linea).val());
}

function calcula_total_articulos()
{
    var subtotal = 0;
    $("#registros_articuos_requisicion tr").each(function()
    {
        subtotal += parseFloat($(this).find("td:nth-child(6)").find("input").val());
    });
    $("#monto_resultado").text("$"+moneda(subtotal, 2, [',', ",", '.']));
}

function Verifica_Folio(folio)
{
    if($(folio).val() > 0 && $("#tipo_documento").val()!=4)
    {
        var folio_interno = $(folio).val();
        var tipo_documento = $("#tipo_documento").val();
        var empresa = $("#empresa").val();

        var variable = "accion=consulta_folio&empresa="+empresa+"&tipo_documento="+tipo_documento+"&folio="+folio_interno;
        RestFullRequest("_Rest/Requisiciones.php", variable, "resultado_consulta");
    }
}

function resultado_consulta(Response)
{
    //$("#FormRequisicion #cliente").val("");
    
    if(Response[0].length > 0)
    {
        $("#tab_datos_requisicion").css("visibility", "visible");
        $("#tab_datos_venta_requisicion").css("visibility", "visible");

        //$("#FormRequisicion #cliente").val(Response[0][0]['CLIENTES.NOMBRE']);
        var tabla = $("#registros_articuos_requisicion_ventas");
        tabla.html("<tr><td>NO SE ENCONTRARON REGISTROS</td></tr>");
    
        var linea = "";
        $.each(Response[1], function(index, value)
        {
            
            linea += "<tr>";
            linea += "<td>"+value['NOMBRE']+"</td>";
            linea += "<td>"+value['UNIDAD']+"</td>";
            linea += "<td>"+value['PRECIO']+"</td><tr>";

        });
        tabla.html(linea);
    }else{
        $("#tab_datos_requisicion").css("visibility", "hidden");
        $("#tab_datos_venta_requisicion").css("visibility", "hidden");
        alert("NO SE ENCUENTRA EL FOLIO SOLICITADO, VUELVA A INTENTARLO CON UN FOLIO CORRECTO");

    }
}