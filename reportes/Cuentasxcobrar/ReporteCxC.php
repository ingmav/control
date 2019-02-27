<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteCxC.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
include("../../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');

$arreglo1 = ver_pagos(1);
$arreglo2 = ver_pagos(2);
$arreglo3 = array_merge($arreglo1, $arreglo2);

?>
<table>
    <tr>
        <td colspan="9" align="center" style="font-weight: bold">-- CUENTAS POR COBRAR --</td>
    </tr>
    <tr style="background-color: #CCC">
        <td>Folio</td>
        <td>Cliente</td>
        <td>Descripción</td>
        <td>Fecha de Creación</td>
        <td>Fecha de Vencimiento</td>
        <td>Finalizado</td>
        <td>Monto por Depósitar</td>
        <td>Fecha de Depósito</td>
        <td>Monto Total</td>
    </tr>
    <?php
        $contador = 0;

        foreach($arreglo3 as $key => $value)
        {
            echo "<tr>";
            echo "<td>".$value['EMPRESA']."-".(int)$value['FOLIO']."</td>";
            echo "<td>".$value['NOMBRE']."</td>";
            echo "<td>".$value['DESCRIPCION']."</td>";
            echo "<td>".$value['FECHA']."</td>";
            echo "<td>".$value['FECHA_VENCIMIENTO']."</td>";

            if($value['FINALIZADO'] == 1)
                echo "<td>SI</td>";
            else if($value['FINALIZADO'] == 0)
                echo "<td>NO</td>";

            echo "<td>$ ".number_format(($value['DEPOSITO']/ (100 / $value['NUMERO_COBROS'])),2,".",",")."</td>";
            echo "<td>".$value['FECHA_DEPOSITO']."</td>";
            echo "<td>$ ".number_format(($value['IMPORTE'] / (100 / $value['NUMERO_COBROS'])),2,".",",")."</td>";

            echo "</tr>";
            $contador++;
        }
    ?>
</table>
<?php
function ver_pagos($empresa)
{
    $conexion = new conexion_nexos($empresa);

    $query = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $arreglo1[] = $row->DOCTO_VE_ID;
    }

    $query2 = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='R' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";

    $result2 = ibase_query($conexion->getConexion(), $query2) or die(ibase_errmsg());

    $arreglo2 = array();

    while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
        $arreglo2[] = $row2->DOCTO_VE_ID;
    }

    if(count($arreglo2) > 0)
    {

        $query3 = "select DOCTO_VE_DEST_ID from DOCTOS_VE_LIGAS WHERE DOCTO_VE_FTE_ID IN (".implode(",", $arreglo2).")";

        $result3 = ibase_query($conexion->getConexion(), $query3) or die(ibase_errmsg());

        $arreglo3 = array();

        while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
            $arreglo3[] = array("DOCTO_VE_ID"=>$row->DOCTO_VE_ID);
        }

        $diferencia_remision_factura = count($arreglo2) - count($arreglo3);

        $arreglo1 = array_merge($arreglo1, $arreglo3);
    }

    $query4 = "select DOCTO_DEST_ID from DOCTOS_ENTRE_SIS WHERE CLAVE_SIS_DEST='CC' AND CLAVE_SIS_FTE='VE' AND DOCTO_FTE_ID IN (".implode(",", $arreglo1).")";

    $result4 = ibase_query($conexion->getConexion(), $query4) or die(ibase_errmsg());

    $arreglo4 = array();

    while ($row4 = ibase_fetch_object ($result4, IBASE_TEXT)){
        $arreglo4[] = $row4->DOCTO_DEST_ID;
    }
    $filtro_interior = "";
    $filtro_interior .= " and doctos_cc.folio like '%".$_GET['folio']."%' and clientes.nombre like '%".$_GET['cliente']."%'";

    $query5 = "select doctos_cc.docto_cc_id, doctos_cc.folio, doctos_cc.fecha, clientes.nombre, doctos_cc.descripcion, sum(importes_doctos_cc.importe+ importes_doctos_cc.impuesto) AS IMPORTE, vencimientos_cargos_cc.fecha_vencimiento, vencimientos_cargos_cc.pctje_ven from doctos_cc, clientes, importes_doctos_cc, vencimientos_cargos_cc
    where doctos_cc.docto_cc_id not in
    (select ids.docto_cc_acr_id from doctos_cc d, importes_doctos_cc ids where d.docto_cc_id=ids.docto_cc_id and d.naturaleza_concepto='R' and ids.estatus!='P')
    and doctos_cc.docto_cc_id=importes_doctos_cc.docto_cc_id and clientes.cliente_id = doctos_cc.cliente_id and doctos_cc.docto_cc_id=vencimientos_cargos_cc.docto_cc_id and doctos_cc.naturaleza_concepto='C' and doctos_cc.cancelado='N' ".$filtro_interior."
    group by doctos_cc.docto_cc_id,doctos_cc.folio, doctos_cc.fecha, clientes.nombre, doctos_cc.descripcion, doctos_cc.folio, vencimientos_cargos_cc.fecha_vencimiento, vencimientos_cargos_cc.pctje_ven
    order by clientes.nombre ";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("ID"=>$row5->DOCTO_CC_ID, "FOLIO"=>$row5->FOLIO,"FECHA"=>$row5->FECHA, "NOMBRE"=>utf8_encode($row5->NOMBRE), "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), "IMPORTE"=>$row5->IMPORTE, "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO, "NUMERO_COBROS"=>$row5->PCTJE_VEN);
    }

    $query6 = "select importes_doctos_cc.docto_cc_acr_id, doctos_cc.fecha, (importes_doctos_cc.importe + importes_doctos_cc.impuesto) as IMPORTE
    from doctos_cc, importes_doctos_cc
    where doctos_cc.docto_cc_id=importes_doctos_cc.docto_cc_id and doctos_cc.naturaleza_concepto='R' and doctos_cc.cancelado='N' and doctos_cc.estatus='P'";

    $result6 = ibase_query($conexion->getConexion(), $query6) or die(ibase_errmsg());

    $arreglo6 = array();
    $arreglo7 = array();

    while ($row6 = ibase_fetch_object ($result6, IBASE_TEXT)){
        $arreglo6[] = array("ID"=>$row6->DOCTO_CC_ACR_ID, "IMPORTE"=>$row6->IMPORTE, "FECHA"=>$row6->FECHA);
        $arreglo7[] = $row6->DOCTO_CC_ACR_ID;
    }

    foreach ($arreglo5 as $key=> $value) {
        if($empresa == 1)
            $arreglo5[$key]['EMPRESA'] = "NX";
        else if($empresa == 2)
            $arreglo5[$key]['EMPRESA'] = "NP";

        if(in_array($arreglo5[$key]['ID'], $arreglo4))
        {
            $arreglo5[$key]['FINALIZADO'] = 0;
        }else{
            $arreglo5[$key]['FINALIZADO'] = 1;
        }

        if(in_array($arreglo5[$key]['ID'], $arreglo7))
        {
            $index = array_search($arreglo5[$key]['ID'], $arreglo7);
            $arreglo5[$key]['DEPOSITO'] = $arreglo6[$index]['IMPORTE'];
            $arreglo5[$key]['FECHA_DEPOSITO'] = $arreglo6[$index]['FECHA'];
        }else{
            $arreglo5[$key]['DEPOSITO'] = 0;
            $arreglo5[$key]['FECHA_DEPOSITO'] = "";
        }
    }
    return $arreglo5;
}
?>