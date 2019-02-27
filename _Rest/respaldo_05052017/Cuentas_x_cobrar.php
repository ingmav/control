<?php
include("../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos();

if($_POST["accion"] == "index")
{
    $arreglo1 = ver_pagos(1, $_POST);
    $arreglo2 = ver_pagos(2, $_POST);
    $arreglo3 = array_merge($arreglo1, $arreglo2);

    $obj = (object) $arreglo3;
    echo json_encode($obj);

}

function ver_pagos($empresa, $filtro)
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

    $arreglo3 = array();
    if(count($arreglo2) > 0)
    {

        $query3 = "select DOCTO_VE_DEST_ID from DOCTOS_VE_LIGAS WHERE DOCTO_VE_FTE_ID IN (".implode(",", $arreglo2).")";

        $result3 = ibase_query($conexion->getConexion(), $query3) or die(ibase_errmsg());

        $arreglo3 = array();

        while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
            $arreglo3[] = $row3->DOCTO_VE_DEST_ID;
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
    if(count($filtro) > 0)
    {
        $filtro_interior .= " and d1.folio like '%".$filtro['folio']."%' and c.nombre like '%".$filtro['cliente']."%'";
    }
    $query5 = "select
d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion,
(sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven) ) AS IMPORTE,
vcc.fecha_vencimiento, vcc.pctje_ven,
IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven)), 0) AS ANTICIPO,
((sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven) ), 0)) AS TOTAL
from doctos_cc d1, vencimientos_cargos_cc vcc, clientes c, importes_doctos_cc idc1
left join importes_doctos_cc idc2 on idc1.docto_cc_acr_id = idc2.docto_cc_acr_id and idc2.tipo_impte='R' and idc2.estatus!='P'
where
d1.docto_cc_id=idc1.docto_cc_id
and d1.docto_cc_id=vcc.docto_cc_id
and d1.cliente_id = c.cliente_id
and d1.naturaleza_concepto='C' and d1.cancelado='N'
".$filtro_interior."
group by d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion, vcc.fecha_vencimiento, vcc.pctje_ven
having (((sum( DISTINCT idc1.importe + idc1.impuesto) / (100 / vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) ), 0))) > 0
order by c.nombre";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("ID"=>$row5->DOCTO_CC_ID, "FOLIO"=>$row5->FOLIO, "CONCEPTO_CC"=>$row5->CONCEPTO_CC_ID,"FECHA"=>$row5->FECHA, "NOMBRE"=>utf8_encode($row5->NOMBRE), "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), "IMPORTE"=>$row5->IMPORTE, "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO, "NUMERO_COBROS"=>$row5->PCTJE_VEN, "ANTICIPO"=>$row5->ANTICIPO, "TOTAL"=>$row5->TOTAL);
    }

    $query6 = "select importes_doctos_cc.docto_cc_acr_id, doctos_cc.fecha_aplicacion, (importes_doctos_cc.importe + importes_doctos_cc.impuesto) as IMPORTE
from doctos_cc, importes_doctos_cc
where doctos_cc.docto_cc_id=importes_doctos_cc.docto_cc_id and doctos_cc.naturaleza_concepto='R' and doctos_cc.cancelado='N' and doctos_cc.estatus='P'";

    $result6 = ibase_query($conexion->getConexion(), $query6) or die(ibase_errmsg());

    $arreglo6 = array();
    $arreglo7 = array();

    while ($row6 = ibase_fetch_object ($result6, IBASE_TEXT)){
        $arreglo6[] = array("ID"=>$row6->DOCTO_CC_ACR_ID, "IMPORTE"=>$row6->IMPORTE, "FECHA"=>$row6->FECHA_APLICACION);
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