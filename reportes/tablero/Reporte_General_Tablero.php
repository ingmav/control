<?php
/**
 * Created by PhpStorm.
 * User: SALUD
 * Date: 2/12/15
 * Time: 18:55
 */


/*header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteMayores ventas.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

date_default_timezone_set('America/Mexico_City');*/
include("clases/conexion.php");

$campos = array("TABLEROPRODUCCION.ID",
    "DOCTOS_VE.FOLIO",
    "TABLEROPRODUCCION.FECHA",
    "CLIENTES.NOMBRE",
    "CLIENTES.CLIENTE_ID",
    "DOCTOS_VE.DESCRIPCION",
    //"TABLEROPRODUCCION.PRIORIDAD",
    "TABLEROPRODUCCION.DISENO",
    "TABLEROPRODUCCION.PREPARACION",
    "TABLEROPRODUCCION.IMPRESION",
    "TABLEROPRODUCCION.INSTALACION",
    "TABLEROPRODUCCION.ENTREGA",
    "TABLEROPRODUCCION.MAQUILAS",
    "TABLEROPRODUCCION.NOTA",
    "TABLEROPRODUCCION.FECHA_ENTREGA",
    "DOCTOS_VE.TIPO_DOCTO",
    "DOCTOS_VE.ESTATUS");

$join = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID","UNION",
    "CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID","UNION");

$condicionales = " AND DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C'
							AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)";

if(isset($_POST['buscar']))
{
    $buscar = (int)$_POST['buscar'];
    $condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
}

if(isset($_POST['client']))
{
    $condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
}

if(isset($_POST['pTablero']))
{
    switch($_POST['pTablero'])
    {
        case 1:
            $condicionales.= " AND TABLEROPRODUCCION.DISENO=1";
            break;
        case 2:
            $condicionales.= " AND TABLEROPRODUCCION.IMPRESION=1";
            break;
        case 3:
            $condicionales.= " AND TABLEROPRODUCCION.MAQUILAS=1";
            break;
        case 4:
            $condicionales.= " AND TABLEROPRODUCCION.INSTALACION=1";
            break;
        case 5:
            $condicionales.= " AND TABLEROPRODUCCION.ENTREGA=1";
            break;
        case 6:
            $condicionales.= " AND TABLEROPRODUCCION.PREPARACION=1";
            break;

    }

}

$order = array("DOCTOS_VE.FECHA DESC, DOCTOS_VE.FOLIO DESC");

$conection2 = new conexion_nexos(1);
$json = $conection2->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

$contador = 0;
foreach($json as $arreglo)
{
    $json[$contador]["NOMBREEMPRESA"] = "NX";
    $json[$contador]["IDEMPRESA"] = 1;
    $campos2 = array("PRODUCCION.IDDEPARTAMENTO",
        "PRODUCCION.IDESTATUS");
    $json2 = $conection2->select_table_advanced($campos2, "PRODUCCION", array(), " AND PRODUCCION.IDTABLEROPRODUCCION = ".$arreglo["TABLEROPRODUCCION.ID"], array("PRODUCCION.IDDEPARTAMENTO"), 0);
    $counter = 0;
    $counter_check = 0;
    $counter_check2 = 0;

    $datetime1 = new DateTime(substr($json[$contador]['TABLEROPRODUCCION.FECHA'],0,10));
    $datetime2 = new DateTime(substr($json[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'],0,10));
    $interval = $datetime2->diff($datetime1);
    $json[$contador]['RESTANTE_ENTREGA'] =  $interval->format('%a');

    foreach($json2 as $production)
    {
        if($production['PRODUCCION.IDESTATUS'] == 2)
            $counter_check++;
        else  if($production['PRODUCCION.IDESTATUS'] == 1 || $production['PRODUCCION.IDESTATUS'] == 3)
            $counter_check2++;
        $counter++;
    }

    $json[$contador]["produccion"] = $json2;
    if($counter_check == $counter)
        $json[$contador]['TERMINADO'] = 1;
    else
        $json[$contador]['TERMINADO'] = 0;

    if($counter_check2 == $counter)
        $json[$contador]['NO_INICIADO'] = 1;
    else
        $json[$contador]['NO_INICIADO'] = 0;

    $contador++;
}


$conection3 = new conexion_nexos(2);

$json3 = $conection3->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

$contador = 0;
foreach($json3 as $arreglo)
{
    $json3[$contador]["NOMBREEMPRESA"] = "NP";
    $json3[$contador]["IDEMPRESA"] = 2;
    $campos2 = array("PRODUCCION.IDDEPARTAMENTO",
        "PRODUCCION.IDESTATUS");
    $json2 = $conection2->select_table_advanced($campos2, "PRODUCCION", array(), " AND PRODUCCION.IDTABLEROPRODUCCION = ".$arreglo["TABLEROPRODUCCION.ID"], array("PRODUCCION.IDDEPARTAMENTO"), 0);
    $json3[$contador]["produccion"] = $json2;


    $counter = 0;
    $counter_check = 0;
    $counter_check2 = 0;

    $datetime1 = new DateTime(substr($json3[$contador]['TABLEROPRODUCCION.FECHA'],0,10));
    $datetime2 = new DateTime(substr($json3[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'],0,10));
    $interval = $datetime2->diff($datetime1);
    $json3[$contador]['RESTANTE_ENTREGA'] =  $interval->format('%a');

    foreach($json2 as $production)
    {

        if($production['PRODUCCION.IDESTATUS'] == 2)
            $counter_check++;
        if($production['PRODUCCION.IDESTATUS'] == 1 || $production['PRODUCCION.IDESTATUS'] == 3)
            $counter_check2++;
        $counter++;
    }


    if($counter_check == $counter)
        $json3[$contador]['TERMINADO'] = 1;
    else
        $json3[$contador]['TERMINADO'] = 0;


    if($counter_check2 == $counter)
        $json3[$contador]['NO_INICIADO'] = 1;
    else
        $json3[$contador]['NO_INICIADO'] = 0;
    $contador++;
}


$json4 = array_merge($json3, $json);

if($_POST['iniciadas'] == 1)
{

    for($i = 0; $i < count($json4); $i++)
    {
        if($json4[$i]["NO_INICIADO"] == 1)
            $auxIniciadas[] = $json4[$i];
    }
    $json4 = $auxIniciadas;
}

if($_POST['realizadas'] == 1)
{
    for($i = 0; $i < count($json4); $i++)
    {
        if($json4[$i]["TERMINADO"] == 1)
            $auxIniciadas[] = $json4[$i];
    }
    $json4 = $auxIniciadas;
}

$contador = count($json4);
for($i = 0; $i < $contador; $i++)
{
    $j = ($i + 1);
    for(; $j < $contador; $j++)
    {
        if($json4[$i]['RESTANTE_ENTREGA'] > $json2[$j]['RESTANTE_ENTREGA'])
        {
            $arrayAuxiliar[0] = $json4[$i];
            $json4[$i] = $json4[$j];
            $json4[$j] = $arrayAuxiliar[0];
        }
    }
}

print_r($json4);
?>