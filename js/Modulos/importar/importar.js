var cliente;
var datos_cotizacion;


$(document).ready(function(e) {
    $("#importar").find("a").click();
    $("#folio_importado").hide();
});
function verficar()
{
    $("#folio_importado").hide(); 
    var id = $("#folio").val();
    $("#DatosCotizacion").html("<tr><td colspan='6'>Cargando... <i class='fa fa-refresh fa-spin'></i></td></tr>");
    $.ajax({
        url: "http://nexprint.mx/cotiza_nex_2020/consultas/api.php?clave=!ZR7i9yZ&id="+id
    }).then(function(data) {
        
        
        var obj = JSON.parse(data);
        //console.log(obj);
        //console.log(obj.datos.clave_cliente);
        if(obj.articulos.length > 0)
        {
            //console.log("hola");
            var datos_cliente = obj.datos;
            var datos_articulos = obj.articulos;
            cliente = obj.datos;
            datos_cotizacion = obj.articulos;
            $("#cve_cliente").text(datos_cliente.clave_cliente);
            $("#nombre_cliente").text(datos_cliente.nombre_cliente);
            $("#descripcion").text(datos_cliente.descripcion);

            var lineas = $("#DatosCotizacion");
            lineas.html("");
            $.each(datos_articulos, function(index, value)
            {
                var linea = $("<tr></tr>");
                var campo1 = $("<td>"+value.clave_articulo+"</td>");
                var campo2 = $("<td>"+value.nombre_articulo+"</td>");
                var campo3 = $("<td style='text-align: center;'>"+value.total+"</td>");
                var campo4 = $("<td style='text-align: center;'>"+value.precio_unitario+"</td>");
                var campo5 = $("<td style='text-align: center;'>"+value.descuento_monto+"</td>");
                var campo6 = $("<td style='text-align: right;'>"+value.total_monto+"</td>");
                linea.append(campo1, campo2, campo3, campo4, campo5, campo6);
                lineas.append(linea);
            });

            var subtotal    = $("<tr style='font-weight: bold;'><td colspan='4'></td><td>SUBTOTAL</td><td style='text-align: right;'>"+datos_cliente.total+"</td></tr>");
            var iva         = $("<tr style='font-weight: bold;'><td colspan='4'></td><td>I.V.A.</td><td style='text-align: right;'>"+datos_cliente.iva+"</td></tr>");
            var descuento   = $("<tr style='font-weight: bold;'><td colspan='4'></td><td>DESC. GRAL.</td><td style='text-align: right;'>"+datos_cliente.tipo_descuento_gral_importe+"</td></tr>");
            var total       = $("<tr style='font-weight: bold;'><td colspan='4'></td><td>TOTAL</td><td style='text-align: right; color:red;'>"+datos_cliente.descuento_general_monto+"</td></tr>");
            var importacion = $("<tr style='font-weight: bold;'><td colspan='4'></td><td colspan='2'><button type='button' class='btn btn-primary' onclick='importacion()'>Importar Cotización</button></td></tr>");
            $("#ResumenCotizacion").html("");
            $("#ResumenCotizacion").append(subtotal,descuento, iva, total, importacion);
           
        }else{
            $("#cve_cliente").text("");
            $("#nombre_cliente").text("");
            $("#descripcion").text("");
            $("#DatosCotizacion").html("");
            $("#ResumenCotizacion").html("");
        }
    });
}

function importacion()
{
    var array_data = {cliente: cliente, datos:datos_cotizacion};
    $.ajax({
        method: "POST",
        url: "_REST/importacion.php",
        data: array_data
      })
    .done(function( msg ) {
         
         var obj = JSON.parse(msg);
         console.log(obj);
         if(obj.estatus == 0)
         {
            $("#folio_importado").hide(); 
            alert("error al cargar la cotización, por favor vuelva a intentarlo");
         }else if(obj.estatus == 1){
            $("#folio_importado").show(); 
            $("#num_folio").text(obj.folio); 
         }
         
    }).fail(function() {
        $("#folio_importado").hide(); 
        alert("error al cargar la cotización, por favor vuelva a intentarlo");
    });
}