/**
 * Created by SALUD on 25/02/16.
 */

$(document).ready(function(e) {
    actualizaDatagrid();
    $("#control").find("a").click();
});

function actualizaDatagrid()
{
    var variable = "accion=index&folio="+$("#folio").val()+"&cliente="+$("#cliente").val();
    RestFullRequest("_Rest/Cuentas_x_cobrar.php", variable, "dataGrid", 1);
}

function dataGrid(Response)
{
    //console.log(Response);
    var datagrid = $("#data");
    datagrid.html("");
    var saldo = 0;
    var deposito = 0;
    var monto = 0;
    var anticipo = 0;
    var arregloFinal = Array();
    var length = Object.keys(Response).length;
    for(var index = 0; index<length; index++)
    {
        for(var index2 = index+1; index2<length; index2++)
        {
            if(Response[index]['FECHA_VENCIMIENTO'] < Response[index2]['FECHA_VENCIMIENTO'])
            {
                var aux = Response[index];
                Response[index] = Response[index2];
                Response[index2] = aux;
            }
        }
    }
    $.each(Response, function(index, value)
    {
        var campos = "";
        var id = value['ID'];
        linea = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

        var icn_finalizado = "";
        if(value['FINALIZADO']==1)
            icn_finalizado += "<button class='btn btn-strech btn-success' type='button'><i class='fa fa-check'></i></button>";
        else
            icn_finalizado += "<button class='btn btn-strech btn-danger' type='button'><i class='fa fa-close'></i></button>";

        campos += "<td>"+icn_finalizado+" "+value['EMPRESA']+"-"+parseInt(value['FOLIO'])+"</td>";
        var dato_adicional = "";
        if(value['CONCEPTO_CC'] == 8)
            dato_adicional = "<span style='color:red'>NO ES GENERADO POR EL SISTEMA</span>";

        campos += "<td align='justify'>"+value['NOMBRE']+"<br>"+value['DESCRIPCION']+"<br>"+dato_adicional+"</td>";
        campos += "<td><b>F</b>: "+value['FECHA']+"<br><b>V</b>: "+value['FECHA_VENCIMIENTO']+"<br><b>C</b>: "+value['FECHA_DEPOSITO']+"</td>";

        //campos += "<td>F: "+value['FECHA']+" / <br>V: "+value['FECHA_VENCIMIENTO']+"</td>";
        /*if(value['FINALIZADO']==1)
            campos += "<td align='center'><button class='btn btn-strech btn-success' type='button'><i class='fa fa-check'></i></button></td>";
        else
            campos += "<td align='center'><button class='btn btn-strech btn-danger' type='button'><i class='fa fa-close'></i></button></td>";
        */
        campos += "<td>$ "+moneda(value['IMPORTE'],2, [',', "'", '.'])+"</td>";
        campos += "<td>$ "+moneda(value['ANTICIPO'],2, [',', "'", '.'])+"</td>";
        campos += "<td>$ "+moneda(value['TOTAL'],2, [',', "'", '.'])+"</td>";

        campos += "<td>$ "+moneda((value['DEPOSITO']/ (100 / value['NUMERO_COBROS'])),2, [',', "'", '.'])+"</td>";
        //campos += "<td>"+value['FECHA_DEPOSITO']+"</td>";

        linea.append(campos);
        saldo += parseFloat((value['TOTAL']));
        anticipo += parseFloat((value['ANTICIPO']));
        deposito += parseFloat(value['DEPOSITO']);
        monto += parseFloat(value['IMPORTE']);

        datagrid.append(linea);
    });
    datagrid.append("<tr><td colspan='3' align='center'>TOTAL</td><td>$ "+moneda(monto,2, [',', "'", '.'])+"</td><td>$ "+moneda(anticipo,2, [',', "'", '.'])+"</td><td>$ "+moneda(saldo,2, [',', "'", '.'])+"</td><td>$ "+moneda(deposito,2, [',', "'", '.'])+"</td></tr>");
}

function reporteCuentas()
{
    var folio = $("#folio").val();
    var cliente = $("#cliente").val();

    window.open("reportes/Cuentasxcobrar/ReporteCxC2.php?folio="+folio+"&cliente="+cliente,'_blank');
}