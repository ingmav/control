<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=tableroProcesos.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

date_default_timezone_set('America/Mexico_City');
include("../../clases/conexion.php");
//require('../../dompdf/dompdf_config.inc.php');

$html = '<html style="margin-top: 0em;}">';


$arreglo = Array();
//$arreglo = (explode("_",$_POST['id'][0]));

$conection = new conexion_nexos(2);

$nexos = Array();
$nexprint = Array();

if(isset($_POST['id']))
foreach($_POST['id'] as $key => $valor)
{
    $arreglo = (explode("_",$valor));

    if($arreglo[0] == 1)
        $nexos[] = $arreglo[1];
    else if($arreglo[0] == 2)
        $nexprint[] = $arreglo[1];
}

$query =    "select 
                    TABLEROPRODUCCION.ID,
                    DOCTOS_VE.FOLIO,
                    DOCTOS_VE.FECHA,    
                    TABLEROPRODUCCION.FECHA_TERMINO,    
                    CLIENTES.NOMBRE,
                    CLIENTES.CLIENTE_ID,
                    DOCTOS_VE.DESCRIPCION,
                    GF_DISENO,
                    IIF(GF_DISENO=1, IIF(DISENO_GF=2, 1,0),0) AS ESTATUS_DISENO,
                    GF_PREPARACION,
                    IIF(GF_PREPARACION=1, IIF(PREPARACION_GF=2, 1,0),0) AS ESTATUS_PREPARACION,
                    GF_IMPRESION,
                    IIF(GF_IMPRESION=1, IIF(IMPRESION_GF=2, 1,0),0) AS ESTATUS_IMPRESION,
                    GF_INSTALACION,
                    IIF(GF_INSTALACION=1, IIF(INSTALACION_GF=2, 1,0),0) AS ESTATUS_INSTALACION,
                    GF_ENTREGA,
                    IIF(GF_ENTREGA=1, IIF(ENTREGA_GF=2, 1,0),0) AS ESTATUS_ENTREGA,
                    GF_MAQUILAS,
                    IIF(GF_MAQUILAS=1, IIF(MAQUILAS_GF=2, 1,0),0) AS ESTATUS_MAQUILAS,
                    DOCTOS_VE.TIPO_DOCTO,
                    DOCTOS_VE.ESTATUS,
                    DOCTOS_VE.IMPORTE_NETO
                    FROM 
                    TABLEROPRODUCCION,
                    CLIENTES,
                    DOCTOS_VE
                    WHERE
                    TABLEROPRODUCCION.DOCTO_VE_ID = DOCTOS_VE.DOCTO_VE_ID
                    AND DOCTOS_VE.CLIENTE_ID = CLIENTES.CLIENTE_ID
                    AND DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C' and finalizar_proceso=0 ".$condicionales;

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){

            $indice = count($json);
            $json[$indice]['ID']                    = $row->ID;
            $json[$indice]['FOLIO']                 = $row->FOLIO;
            $json[$indice]['TIPO_DOCTO']            = $row->TIPO_DOCTO;
            $json[$indice]['FECHA']                 = $row->FECHA;
            $json[$indice]['FECHA_TERMINO']         = $row->FECHA_TERMINO;
            $json[$indice]['EMPRESA']               = "NP";
            $json[$indice]['NOMBRE']                = utf8_encode($row->NOMBRE);
            $json[$indice]['CLIENTE_ID']            = $row->CLIENTE_ID;
            $json[$indice]['DESCRIPCION']           = utf8_encode($row->DESCRIPCION);
            $json[$indice]['GF_DISENO']             = $row->GF_DISENO;
            $json[$indice]['ESTATUS_DISENO']        = $row->ESTATUS_DISENO;
            $json[$indice]['GF_PREPARACION']        = $row->GF_PREPARACION;
            $json[$indice]['ESTATUS_PREPARACION']   = $row->ESTATUS_PREPARACION;
            $json[$indice]['GF_IMPRESION']          = $row->GF_IMPRESION;
            $json[$indice]['ESTATUS_IMPRESION']     = $row->ESTATUS_IMPRESION;
            $json[$indice]['GF_INSTALACION']        = $row->GF_INSTALACION;
            $json[$indice]['ESTATUS_INSTALACION']   = $row->ESTATUS_INSTALACION;
            $json[$indice]['GF_ENTREGA']            = $row->GF_ENTREGA;
            $json[$indice]['ESTATUS_ENTREGA']       = $row->ESTATUS_ENTREGA;
            $json[$indice]['GF_MAQUILAS']           = $row->GF_MAQUILAS;
            $json[$indice]['ESTATUS_MAQUILAS']      = $row->ESTATUS_MAQUILAS;
            $json[$indice]['IMPORTE_NETO']          = $row->IMPORTE_NETO;
        } 

        $contador = count($json);
        for($i = 0; $i < $contador; $i++)
        {
            $j = ($i + 1);
            for(; $j < $contador; $j++)
            {
                if($json[$i]['FECHA'] > $json[$j]['FECHA'])
                {
                    $arrayAuxiliar[0] = $json[$i];
                    $json[$i] = $json[$j];
                    $json[$j] = $arrayAuxiliar[0];
                }
            }
        }

/*$campos = array("TABLEROPRODUCCION.ID",
    "DOCTOS_VE.FOLIO",
    "TABLEROPRODUCCION.FECHA",
    "DOCTOS_VE.FECHA as FECHA_FACTURACION",
    "CLIENTES.NOMBRE",
    "CLIENTES.CLIENTE_ID",
    "DOCTOS_VE.DESCRIPCION",
    "TABLEROPRODUCCION.PRIORIDAD",
    "TABLEROPRODUCCION.DISENO",
    "TABLEROPRODUCCION.PREPARACION",
    "TABLEROPRODUCCION.IMPRESION",
    "TABLEROPRODUCCION.INSTALACION",
    "TABLEROPRODUCCION.ENTREGA",
    "TABLEROPRODUCCION.MAQUILAS",
    "TABLEROPRODUCCION.NOTA",
    "TABLEROPRODUCCION.FECHA_ENTREGA",
    "DOCTOS_VE.TIPO_DOCTO",
    "DOCTOS_VE.ESTATUS",
    "DOCTOS_VE.IMPORTE_NETO");

$join = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID",
    "CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID");

$condicionales1 = " DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C'
							AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)";

if(count($nexos) > 0)
    $condicionales1.= " AND TABLEROPRODUCCION.ID IN (".implode(",", $nexos).") ";

if(isset($_POST['folio']))
{
    $buscar = (int)$_POST['folio'];
    $condicionales1.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
}

if(isset($_POST['cliente']))
{
    $condicionales1.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['cliente'])."%'";
}

$order = array("DOCTOS_VE.FECHA DESC, DOCTOS_VE.FOLIO DESC");

$conection2 = new conexion_nexos(1);

$ciclos = count($join) / 4;

for($i = 0; $i < $ciclos; $i++)
{
    $joins .= " LEFT JOIN ".$join[($i * 4)]." ON ".$join[(($i * 4) + 2)]." ".$join[(($i * 4) + 1)]." ".$join[(($i * 4) + 3)];
}
$query = "select $page ".implode(",",$campos)." from TABLEROPRODUCCION $joins WHERE $condicionales1 order by ".implode(",", $order);

$result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

$count = count($campos);
$contador = 0;
$json = array();
while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
    $indice = count($json);
    $json[$indice]['TABLEROPRODUCCION.ID'] = $row->ID;
    $json[$indice]['DOCTOS_VE.FOLIO'] = $row->FOLIO;
    $json[$indice]['TABLEROPRODUCCION.FECHA'] = $row->FECHA;
    $json[$indice]['FECHA_FACTURACION'] = $row->FECHA_FACTURACION;
    $json[$indice]['CLIENTES.NOMBRE'] = $row->NOMBRE;
    $json[$indice]['CLIENTES.CLIENTE_ID'] = $row->CLIENTE_ID;
    $json[$indice]['DOCTOS_VE.DESCRIPCION'] = utf8_decode($row->DESCRIPCION);
    $json[$indice]['TABLEROPRODUCCION.PRIORIDAD'] = $row->PRIORIDAD;
    $json[$indice]['TABLEROPRODUCCION.DISENO'] = $row->DISENO;
    $json[$indice]['TABLEROPRODUCCION.PREPARACION'] = $row->PREPARACION;
    $json[$indice]['TABLEROPRODUCCION.IMPRESION'] = $row->IMPRESION;
    $json[$indice]['TABLEROPRODUCCION.INSTALACION'] = $row->INSTALACION;
    $json[$indice]['TABLEROPRODUCCION.MAQUILAS'] = $row->MAQUILAS;
    $json[$indice]['TABLEROPRODUCCION.NOTA'] = utf8_decode($row->NOTA);
    $json[$indice]['TABLEROPRODUCCION.FECHA_ENTREGA'] = $row->FECHA_ENTREGA;
    $json[$indice]['DOCTOS_VE.TIPO_DOCTO'] = $row->TIPO_DOCTO;
    $json[$indice]['DOCTOS_VE.ESTATUS'] = $row->ESTATUS;
    $json[$indice]['DOCTOS_VE.IMPORTE_NETO'] = $row->IMPORTE_NETO;

}


//$json = $conection2->select_table($campos, "TABLEROPRODUCCION", $join, $condicionales1, $order, 0);

$contador = 0;
foreach($json as $arreglo)
{
    $json2 = $conection2->statusproduccion($query, $arreglo['TABLEROPRODUCCION.ID']);

    //print_r($json2);
    //break;

    $cProcesos = $arreglo["TABLEROPRODUCCION.DISENO"] + $arreglo["TABLEROPRODUCCION.PREPARACION"] + $arreglo["TABLEROPRODUCCION.IMPRESION"] + $arreglo["TABLEROPRODUCCION.INSTALACION"] + $arreglo["TABLEROPRODUCCION.ENTREGA"] + $arreglo["TABLEROPRODUCCION.MAQUILAS"];
    $cProcesosRealizados = 0;
    if($json2[0][0]=="")
        $json2[0][0] = 0;
    if($json2[0][1]=="")
        $json2[0][1] = 0;
    if($json2[0][2]=="")
        $json2[0][2] = 0;
    if($json2[0][3]=="")
        $json2[0][3] = 0;
    if($json2[0][4]=="")
        $json2[0][4] = 0;
    if($json2[0][5]=="")
        $json2[0][5] = 0;


    if($json2[0][0]=="2")
        $cProcesosRealizados++;
    if($json2[0][1]=="2")
        $cProcesosRealizados++;
    if($json2[0][2]=="2")
        $cProcesosRealizados++;
    if($json2[0][3]=="2")
        $cProcesosRealizados++;
    if($json2[0][4]=="2")
        $cProcesosRealizados++;
    if($json2[0][5]=="2")
        $cProcesosRealizados++;

    $json[$contador]["NOMBREEMPRESA"] = "NX";
    $json[$contador]["IDEMPRESA"] = 1;
    $json[$contador]["ESTATUSDISENO"] = $json2[0][0];
    $json[$contador]["ESTATUSPREPARACION"] = $json2[0][1];
    $json[$contador]["ESTATUSIMPRESION"] = $json2[0][2];
    $json[$contador]["ESTATUSINSTALACION"] = $json2[0][3];
    $json[$contador]["ESTATUSENTREGA"] = $json2[0][4];
    $json[$contador]["ESTATUSMAQUILAS"] = $json2[0][5];

    if($cProcesos == $cProcesosRealizados)
        $json[$contador]["REALIZADOS"] =1;
    else
        $json[$contador]["REALIZADOS"] =0;

    if($json2[0][0]!=2 && $json2[0][1] !=2 && $json2[0][2] !=2 && $json2[0][3] != 2 && $json2[0][4]!=2 && $json2[0][5]!=2)
        $json[$contador]["INICIADA"] = 0;
    else
        $json[$contador]["INICIADA"] = 1;
    $contador++;
}

$conection3 = new conexion_nexos(2);


$condicionales2 = " DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C'
							AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)";

if(count($nexprint) > 0)
    $condicionales2.= " AND TABLEROPRODUCCION.ID IN (".implode(",", $nexprint).") ";

if(isset($_POST['folio']))
{
    $buscar = (int)$_POST['folio'];
    $condicionales2.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
}

if(isset($_POST['cliente']))
{
    $condicionales2.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['cliente'])."%'";
}

//eMPIEZA
$query = "select $page ".implode(",",$campos)." from TABLEROPRODUCCION $joins WHERE $condicionales2 order by ".implode(",", $order);

$result = ibase_query($conection3->getConexion(), $query) or die(ibase_errmsg());

$count = count($campos);
$contador = 0;
$json3 = array();
while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
    $indice = count($json3);
    $json3[$indice]['TABLEROPRODUCCION.ID'] = $row->ID;
    $json3[$indice]['DOCTOS_VE.FOLIO'] = $row->FOLIO;
    $json3[$indice]['TABLEROPRODUCCION.FECHA'] = $row->FECHA;
    $json3[$indice]['FECHA_FACTURACION'] = $row->FECHA_FACTURACION;
    $json3[$indice]['CLIENTES.NOMBRE'] = $row->NOMBRE;
    $json3[$indice]['CLIENTES.CLIENTE_ID'] = $row->CLIENTE_ID;
    $json3[$indice]['DOCTOS_VE.DESCRIPCION'] = utf8_decode($row->DESCRIPCION);
    $json3[$indice]['TABLEROPRODUCCION.PRIORIDAD'] = $row->PRIORIDAD;
    $json3[$indice]['TABLEROPRODUCCION.DISENO'] = $row->DISENO;
    $json3[$indice]['TABLEROPRODUCCION.PREPARACION'] = $row->PREPARACION;
    $json3[$indice]['TABLEROPRODUCCION.IMPRESION'] = $row->IMPRESION;
    $json3[$indice]['TABLEROPRODUCCION.INSTALACION'] = $row->INSTALACION;
    $json3[$indice]['TABLEROPRODUCCION.MAQUILAS'] = $row->MAQUILAS;
    $json3[$indice]['TABLEROPRODUCCION.NOTA'] = utf8_decode($row->NOTA);
    $json3[$indice]['TABLEROPRODUCCION.FECHA_ENTREGA'] = $row->FECHA_ENTREGA;
    $json3[$indice]['DOCTOS_VE.TIPO_DOCTO'] = $row->TIPO_DOCTO;
    $json3[$indice]['DOCTOS_VE.ESTATUS'] = $row->ESTATUS;
    $json3[$indice]['DOCTOS_VE.IMPORTE_NETO'] = $row->IMPORTE_NETO;

}

//TERMINA
//$json3 = $conection3->select_table($campos, "TABLEROPRODUCCION", $join, $condicionales2, $order, 0);

$contador = 0;
foreach($json3 as $arreglo)
{
    $json4 = $conection3->statusproduccion($query, $arreglo['TABLEROPRODUCCION.ID']);

    $cProcesos = $arreglo["TABLEROPRODUCCION.DISENO"] + $arreglo["TABLEROPRODUCCION.PREPARACION"] + $arreglo["TABLEROPRODUCCION.IMPRESION"] + $arreglo["TABLEROPRODUCCION.INSTALACION"] + $arreglo["TABLEROPRODUCCION.ENTREGA"] + $arreglo["TABLEROPRODUCCION.MAQUILAS"];
    $cProcesosRealizados = 0;

    if($json4[0][0]=="")
        $json4[0][0] = 0;
    if($json4[0][1]=="")
        $json4[0][1] = 0;
    if($json4[0][2]=="")
        $json4[0][2] = 0;
    if($json4[0][3]=="")
        $json4[0][3] = 0;
    if($json4[0][4]=="")
        $json4[0][4] = 0;
    if($json4[0][5]=="")
        $json4[0][5] = 0;

    if($json4[0][0]=="2")
        $cProcesosRealizados++;
    if($json4[0][1]=="2")
        $cProcesosRealizados++;
    if($json4[0][2]=="2")
        $cProcesosRealizados++;
    if($json4[0][3]=="2")
        $cProcesosRealizados++;
    if($json4[0][4]=="2")
        $cProcesosRealizados++;
    if($json4[0][5]=="2")
        $cProcesosRealizados++;

    $json3[$contador]["NOMBREEMPRESA"] = "NP";
    $json3[$contador]["IDEMPRESA"] = 2;
    $json3[$contador]["ESTATUSDISENO"] = $json4[0][0];
    $json3[$contador]["ESTATUSPREPARACION"] = $json4[0][1];
    $json3[$contador]["ESTATUSIMPRESION"] = $json4[0][2];
    $json3[$contador]["ESTATUSINSTALACION"] = $json4[0][3];
    $json3[$contador]["ESTATUSENTREGA"] = $json4[0][4];
    $json3[$contador]["ESTATUSMAQUILAS"] = $json4[0][5];

    if($cProcesos == $cProcesosRealizados)
        $json3[$contador]["REALIZADOS"] =1;
    else
        $json3[$contador]["REALIZADOS"] =0;

    if($json4[0][0]!=2 && $json4[0][1] !=2 && $json4[0][2] !=2 && $json4[0][3] != 2 && $json4[0][4]!=2  && $json4[0][5]!=2)
        $json3[$contador]["INICIADA"] = 0;
    else
        $json3[$contador]["INICIADA"] = 1;
    $contador++;
}

$json5 = Array();
$json5 = array_merge($json3, $json);


$count = 0;
$counterArray = count($json5);

//echo $contador;
$arrayAuxiliar = Array();
$j = 1;

for($i = 0; $i < $counterArray; $i++)
{
    $j = ($i + 1);

    for(; $j < $counterArray; $j++)
    {
        //echo $json5[$i]['DOCTOS_VE.FECHA'];
        if($json5[$i]['TABLEROPRODUCCION.FECHA'] > $json5[$j]['TABLEROPRODUCCION.FECHA'])
        {

            $arrayAuxiliar[0] = $json5[$i];
            $json5[$i] = $json5[$j];
            $json5[$j] = $arrayAuxiliar[0];
        }else if($json5[$i]['TABLEROPRODUCCION.FECHA'] == $json5[$j]['TABLEROPRODUCCION.FECHA'])
        {
            if($json5[$i]['TABLEROPRODUCCION.PRIORIDAD'] < $json5[$j]['TABLEROPRODUCCION.PRIORIDAD'])
            {
                $arrayAuxiliar[0] = $json5[$i];
                $json5[$i] = $json5[$j];
                $json5[$j] = $arrayAuxiliar[0];
            }
        }
    }
}

$auxIniciadas = array();


if($_POST['iniciadas'] == 1)
{

    for($i = 0; $i < count($json5); $i++)
    {
        if($json5[$i]["INICIADA"] == 0)
            $auxIniciadas[] = $json5[$i];
    }
    $json5 = $auxIniciadas;
}

if($_POST['realizadas'] == 1)
{
    for($i = 0; $i < count($json5); $i++)
    {
        if($json5[$i]["REALIZADOS"] == 1)
            $auxIniciadas[] = $json5[$i];
    }
    $json5 = $auxIniciadas;
}

$textoPendiente;
if($_POST['estatus']!=0)
{

    $validadorPendiete = "";
    switch($_POST['estatus'])
    {
        case 1: $validadorPendiete = "ESTATUSDISENO"; $textoPendiete = "PENDIENTES: DISEÑO"; break;
        case 2: $validadorPendiete = "ESTATUSIMPRESION"; $textoPendiete = "PENDIENTES: IMPRESION"; break;
        case 3: $validadorPendiete = "ESTATUSMAQUILAS"; $textoPendiete = "PENDIENTES:MAQUILAS"; break;
        case 4: $validadorPendiete = "ESTATUSINSTALACION"; $textoPendiete = "PENDIENTES: INSTALACION"; break;
        case 5: $validadorPendiete = "ESTATUSENTREGA"; $textoPendiete = "PENDIENTES: ENTREGA"; break;
        case 6: $validadorPendiete = "ESTATUSPREPARACION"; $textoPendiete = "PENDIENTES: PREPARACION"; break;
    }

    for($i = 0; $i < count($json5); $i++)
    {
        if($json5[$i][$validadorPendiete] == "1" || $json5[$i][$validadorPendiete] == "3")
            $auxIniciadas[] = $json5[$i];
    }
    $json5 = $auxIniciadas;

}
$textoFolio = "";
if($_POST['folio'] != "")
    $textoFolio = "FOLIO: ".$_POST['folio'];

$textoCliente = "";
if($_POST['cliente'] != "")
    $textoCliente = "CLIENTE: ".$_POST['cliente'];
*/

$html .= "TABLERO DE PROCESOS  $textoFolio  $textoCliente  ".utf8_decode($textoPendiete)."  ".date("Y/m/d");
$html .="<table>";
$contador = 0;
$html .="<tr style='background: #CFCFCF'><td width='80px'>FOLIO</td><td width='70px'>Fecha Finalizado</td><td width='70px'>FACTURADO</td><td width='70px'>DIAS DE PROCESO</td><td width='70px'>Fecha Entrega</td><td width='200px'>Cliente</td><td width='700px'>Descripcion</td><td width='10px'>Di</td><td width='10px'>Im</td><td width='10px'>Pr</td><td width='10px'>In</td><td width='10px'>En</td><td width='10px'>Ma</td><td width='10px'>IMPORTE</td></tr>";


$contador = count($json5);
/*for($i = 0; $i < $contador; $i++)
{
    $j = ($i + 1);
    for(; $j < $contador; $j++)
    {
        if($json5[$i]['TABLEROPRODUCCION.FECHA_ENTREGA'] < $json5[$j]['TABLEROPRODUCCION.FECHA_ENTREGA'])
        {
            $arrayAuxiliar[0] = $json5[$i];
            $json5[$i] = $json5[$j];
            $json5[$j] = $arrayAuxiliar[0];
        }
    }
}*/
$importe_neto = 0;

foreach($json as $key => $value)
{
    $estilo = "";
    if($contador%2==0)
        $estilo = "background:#EFEFEF";
    $contador++;

    $estatusDiseno = "";
    $estatusPreparacion = "";
    $estatusImpresion = "";
    $estatusInstalacion = "";
    $estatusEntrega = "";
    $estatusMaquilas = "";
    /*
    if($value["TABLEROPRODUCCION.DISENO"] == 1)
    {
        $estatusDiseno = "S";
        if($value["ESTATUSDISENO"] == 1)
            $estatusDiseno = "X";
    }

    if($value["TABLEROPRODUCCION.PREPARACION"] == 1)
    {
        $estatusPreparacion = "S";
        if($value["ESTATUSPREPARACION"] == 1)
            $estatusPreparacion = "X";
    }

    if($value["TABLEROPRODUCCION.IMPRESION"] == 1)
    {
        $estatusImpresion = "S";
        if($value["ESTATUSIMPRESION"] == 1)
            $estatusImpresion = "X";
    }

    if($value["TABLEROPRODUCCION.INSTALACION"] == 1)
    {
        $estatusInstalacion = "S";
        if($value["ESTATUSINSTALACION"] == 1)
            $estatusInstalacion = "X";
    }

    if($value["TABLEROPRODUCCION.ENTREGA"] == 1)
    {
        $estatusEntrega = "S";
        if($value["ESTATUSENTREGA"] == 1)
            $estatusEntrega = "X";
    }

    if($value["TABLEROPRODUCCION.MAQUILAS"] == 1)
    {
        $estatusMaquilas = "S";
        if($value["ESTATUSMAQUILAS"] == 1)
            $estatusMaquilas = "X";
    }*/


    if($value['GF_DISENO'] == 1)
    {
        $estatusDiseno = "X";
        if($value["ESTATUS_DISENO"] == 1)
            $estatusDiseno = "S";
    }

    if($value['GF_PREPARACION']== 1)
    {
        $estatusPreparacion = "X";
        if($value["ESTATUS_PREPARACION"] == 1)
            $estatusPreparacion = "S";
    }

    if($value['GF_IMPRESION'] == 1)
    {
        $estatusImpresion = "X";
        if($value["ESTATUS_IMPRESION"] == 1)
            $estatusImpresion = "S";
    }

    if($value['GF_INSTALACION'] == 1)
    {
        $estatusInstalacion = "X";
        if($value["ESTATUS_INSTALACION"] == 1)
            $estatusInstalacion = "S";
    }

    if($value['GF_ENTREGA'] == 1)
    {
        $estatusEntrega = "X";
        if($value["ESTATUS_ENTREGA"] == 1)
            $estatusEntrega = "S";
    }

    if($value['GF_MAQUILAS'] == 1)
    {
        $estatusMaquilas = "X";
        if($value["ESTATUS_MAQUILAS"] == 1)
            $estatusMaquilas = "S";
    }

    //Calculo de diferencia
    $datetime1 = date_create(substr($value["FECHA_TERMINO"],0,10));
    $datetime2 = date_create(date("Y-m-d"));
    $interval = date_diff($datetime1, $datetime2);
    $dias_proceso = $interval->format('%a');
    //Fin del calculo

    $html .= "<tr style='font-size: 11px; $estilo'>";
    $html .= "<td>".$value['EMPRESA'].$value["TIPO_DOCTO"]."-".(int)substr($value["FOLIO"],1)."</td>";
    $html .= "<td>".substr($value["FECHA_TERMINO"],0,10)."</td>";
    $html .= "<td>".substr($value["FECHA"],0,10)."</td>";
    $html .= "<td>".$dias_proceso."</td>";
    $html .= "<td>".substr($value["FECHA"],0,10)."</td>";
    $html .= "<td>".$value["NOMBRE"]."</td>";
    $html .= "<td>".$value["DESCRIPCION"]."</td>";
    $html .= "<td>".$estatusDiseno."</td>";

    $html .= "<td>".$estatusImpresion."</td>";
    $html .= "<td>".$estatusPreparacion."</td>";
    $html .= "<td>".$estatusInstalacion."</td>";
    $html .= "<td>".$estatusEntrega."</td>";
    $html .= "<td>".$estatusMaquilas."</td>";
    $html .= "<td>$ ".number_format($value["IMPORTE_NETO"],2, ".", ",")."</td>";
    $importe_neto += $value["IMPORTE_NETO"];

    $html .= "</tr>";
}

//$html .="<tr><td colspan='11' align='center'>TOTAL</td><td>$ ".number_format($importe_neto, 2, ".", ",")."</td></tr>";

$html."</table>";

$html .= "<body></html>";
echo $html;
/*$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper("legal","landscape");
$dompdf->render();


$dompdf->stream('my.pdf',array('Attachment'=>0));*/
?>