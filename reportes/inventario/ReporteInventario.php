<?php

/*
require('dompdf/dompdf_config.inc.php');
*/
/*header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteInventario.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);*/

include("../../clases/conexion.php");
require_once '../../PHPExcel/PHPExcel.php';
date_default_timezone_set('America/Mexico_City');


$conection3 = new conexion_nexos(1);

$json_last_row = $conection3->select_last_row_table("INVENTARIOCORTE", array(), " AND ID=".$_POST['seleccion_corte']);

if($json_last_row->FECHA_FIN == "")
{
    $inventario_final = calcula_inventario();
    $json_last_row->FECHA_FIN = date("Y-m-d H:i:s");
}else{
    $query2 = "SELECT AW.ID, I.IDARTICULOWEB, AW.LINEA_ARTICULO_ID,  I.INVENTARIO_INICIAL, I.INGRESO_TOTAL, I.BAJA_TOTAL, I.REAJUSTE,   I.INVENTARIO_FINAL, AW.NOMBRE, AW.UNIDAD, AW.NOMBRELINEA FROM INVENTARIO I, INVENTARIOCORTE IC, ARTICULOSWEB AW WHERE
I.IDARTICULOWEB = AW.ID
AND I.IDINVENTARIOCORTE=IC.ID AND IC.ID='$json_last_row->ID' ORDER BY AW.LINEA_ARTICULO_ID, AW.NOMBRE";

    $resultInventario = ibase_query($conection3->getConexion(), $query2) or die(ibase_errmsg());

    $inventario_final = array();
    while($rowInventario = ibase_fetch_object ($resultInventario, IBASE_TEXT))
    {
        $index = count($inventario_final);
        $inventario_final[$index]['ID']                 = $rowInventario->ID;
        $inventario_final[$index]['IDARTICULOWEB']      = $rowInventario->IDARTICULOWEB;
        $inventario_final[$index]['LINEA_ID']           = $rowInventario->LINEA_ARTICULO_ID;
        $inventario_final[$index]['INVENTARIO_INICIAL'] = $rowInventario->INVENTARIO_INICIAL;
        $inventario_final[$index]['NOMBRE']             = utf8_encode($rowInventario->NOMBRE);
        $inventario_final[$index]['UNIDAD']             = $rowInventario->UNIDAD;
        $inventario_final[$index]['LINEA']              = $rowInventario->NOMBRELINEA;
        $inventario_final[$index]['INGRESO']            = $rowInventario->INGRESO_TOTAL;
        $inventario_final[$index]['BAJA']               = $rowInventario->BAJA_TOTAL;
        $inventario_final[$index]['TOTAL']              = $rowInventario->INVENTARIO_FINAL;
        $inventario_final[$index]['REAJUSTE']           = $rowInventario->REAJUSTE;
        $inventario_final[$index]['DIFERENCIA']         = $rowInventario->INVENTARIO_FINAL - $rowInventario->REAJUSTE;


         $ingreso = $conection3->select_last_row_table("INGRESOINVENTARIO", array(), " and idarticuloweb='".$rowInventario->IDARTICULOWEB."' ORDER by FECHA DESC");
        if($ingreso->CANTIDAD!=0)
            $inventario_final[$index]['COSTO']              = ($ingreso->IMPORTE / $ingreso->CANTIDAD);
        else
            $inventario_final[$index]['COSTO']              = 0;

        $inventario_final[$index]['PRECIO_INVENTARIO']      = ($rowInventario->REAJUSTE * $inventario_final[$index]['COSTO']);
    }
}

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Inventario")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Inventario")
    ->setKeywords("reporte de inventario")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Inventario";
$titulosColumnas = array('ARTICULO', 'LINEA', 'UNIDAD', 'INGRESO', "BAJAS", "INVENTARIO FINAL", "REAJUSTE", "DIFERENCIA", "P.U.", "PRECIO PROMEDIO DE INVENTARIO");


$objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:J1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte)
    ->setCellValue('A3',  $titulosColumnas[0])
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4])
    ->setCellValue('F3',  $titulosColumnas[5])
    ->setCellValue('G3',  $titulosColumnas[6])
    ->setCellValue('H3',  $titulosColumnas[7])
    ->setCellValue('I3',  $titulosColumnas[8])
    ->setCellValue('J3',  $titulosColumnas[9]);


//Se agregan los datos de los alumnos
$i = 4;


foreach($inventario_final as $key => $value)
{
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['NOMBRE'])
        ->setCellValue('B'.$i,  $value['LINEA'])
        ->setCellValue('C'.$i,  $value['UNIDAD'])
        ->setCellValue('D'.$i,  $value['INGRESO'])
        ->setCellValue('E'.$i,  $value["BAJA"])
        ->setCellValue('F'.$i,  $value['TOTAL'])
        ->setCellValue('G'.$i,  $value['REAJUSTE'])
        ->setCellValue('H'.$i,  $value['DIFERENCIA'])
        ->setCellValue('I'.$i,  $value['COSTO'])
        ->setCellValue('J'.$i,  $value['PRECIO_INVENTARIO']);
    $i++;
}

$estiloTituloReporte = array(
    'font' => array(
        'name'      => 'Verdana',
        'bold'      => true,
        'italic'    => false,
        'strike'    => false,
        'size' =>14,
        'color'     => array(
            'rgb' => 'FFFFFF'
        )
    ),
    'fill' => array(
        'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
        'color'	=> array('argb' => 'FF555555')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_NONE
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation'   => 0,
        'wrap'          => TRUE
    )
);

$estiloTituloColumnas = array(
    'font' => array(
        'name'      => 'Arial',
        'bold'      => true,
        'size' =>9,
        'color'     => array(
            'rgb' => 'FFFFFF'
        )
    ),
    'fill' 	=> array(
        'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'E21800'
        )
    ),
    'borders' => array(
        'top'     => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '143860'
            )
        ),
        'bottom'     => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '143860'
            )
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap'          => TRUE
    ));

$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray(
    array(
        'font' => array(
            'name'      => 'Arial',
            'color'     => array(
                'rgb' => '000000'
            )
        ),
        'fill' 	=> array(
            'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
            'color'		=> array('argb' => 'FFFFFFFF')
        ),
        'borders' => array(
            'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN ,
                'color' => array(
                    'rgb' => '3a2a47'
                )
            )
        )
    ));

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($estiloTituloColumnas);
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

for($i = 'A'; $i <= 'J'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Inventario');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteInventario.xlsx"');
//header('Content-Disposition: attachment;filename="Reportedealumnos.xlsx"');
//header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
/*$query3 = "SELECT IDARTICULOWEB, SUM(CANTIDAD) as CANTIDAD FROM INGRESOINVENTARIO RAW WHERE fecha between '".$json_last_row->FECHA_INICIO."' and '".$json_last_row->FECHA_FIN."'  GROUP BY IDARTICULOWEB";
$query5 = "SELECT idarticuloweb, sum(cantidad) as unidades, sum(merma + 0) as merma from inventarioimpresion where fecha between '".$json_last_row->FECHA_INICIO."' and '".$json_last_row->FECHA_FIN."' GROUP BY idarticuloweb";

$resultIngresos = ibase_query($conection3->getConexion(), $query3) or die(ibase_errmsg());
$resultInventario = ibase_query($conection3->getConexion(), $query2) or die(ibase_errmsg());

$resultInventariooperaciones = ibase_query($conection3->getConexion(), $query5) or die(ibase_errmsg());


$inventariooperaciones = array();
while($row = ibase_fetch_object ($resultInventariooperaciones, IBASE_TEXT))
{
    $index = count($inventariooperaciones);
    $inventariooperaciones[$index]['IDARTICULOWEB'] = $row->IDARTICULOWEB;
    $inventariooperaciones[$index]['INVENTARIO'] 	 = $row->UNIDADES;
    $inventariooperaciones[$index]['MERMA'] 	     = $row->MERMA;
}


$conection2 = new conexion_nexos(2);

$resultInventariooperaciones2 = ibase_query($conection2->getConexion(), $query5) or die(ibase_errmsg());
$inventariooperaciones2 = array();
while($row = ibase_fetch_object ($resultInventariooperaciones2, IBASE_TEXT))
{
    $index = count($inventariooperaciones2);
    $inventariooperaciones2[$index]['IDARTICULOWEB'] = $row->IDARTICULOWEB;
    $inventariooperaciones2[$index]['INVENTARIO'] 	 = $row->UNIDADES;
    $inventariooperaciones2[$index]['MERMA'] 	     = $row->MERMA;
}

$ingresos = array();
while($rowIngresos = ibase_fetch_object ($resultIngresos, IBASE_TEXT))
{
    $index = count($ingresos);
    $ingresos[$index]['IDARTICULOWEB'] = $rowIngresos->IDARTICULOWEB;
    $ingresos[$index]['INVENTARIO']    = $rowIngresos->CANTIDAD;
}

$egresos = array();


$inventarioActual = array();

while($rowInventario = ibase_fetch_object ($resultInventario, IBASE_TEXT))
{
    $index = count($inventarioActual);
    $inventarioActual[$index]['IDARTICULOWEB'] = $rowInventario->IDARTICULOWEB;
    $inventarioActual[$index]['INVENTARIO']    = $rowInventario->INVENTARIO;
    $inventarioActual[$index]['REAJUSTE']    = $rowInventario->REAJUSTE;
    $inventarioActual[$index]['FECHACORTE']    = $rowInventario->FECHA_INICIO;
}

foreach ($inventariooperaciones as $key => $value) {

    foreach ($inventariooperaciones2 as $key2 => $value2) {

        if($value['IDARTICULOWEB'] == $value2['IDARTICULOWEB'])
        {
            $inventariooperaciones[$key]['INVENTARIO'] += $value2['INVENTARIO'];
            $inventariooperaciones[$key]['MERMA'] += $value2['MERMA'];
            unset($inventariooperaciones2[$key2]);
        }
    }
}
$inventarioparcial = Array();
$inventarioparcial = array_merge($inventariooperaciones, $inventariooperaciones2);


$conection3 = new conexion_nexos(1);



$campos = array("ARTICULOSWEB.ID","ARTICULOSWEB.NOMBRE", "ARTICULOSWEB.UNIDAD", "ARTICULOSWEB.MINIMO", "ARTICULOSWEB.NOMBRELINEA");

if($filter!=0)
{
    $condicionales.= " and linea_articulo_id=".$filter;
}
$join = array();

$condicionales .= " AND ID NOT IN (111,121,272) ";

$order = array("ARTICULOSWEB.NOMBRELINEA","ARTICULOSWEB.NOMBRE" );

$json = $conection3->select_table($campos, "ARTICULOSWEB", $join, $condicionales, $order, 1);

    $html .= "";

    $html .= "Inventario de ".$json_last_row->FECHA_INICIO."' a '".$json_last_row->FECHA_FIN;

    $html .= "<table style='font-size:9pt'>";
    $html .= "<tbody>";
    $html .= utf8_decode("<thead><tr><td width='520px'>ARTICULO</td><td>LINEA</td><td>INICIAL</td><td>INGRESO</td><td>BAJAS</td><td>TOTAL</td><td>VALOR ULTIMA COMPRA</td><td>VALOR INVENTARIO</td></tr></thead>");

    $contador = 0;
    foreach ($json as $key => $value) {

        $registro = "";

        $condicionales = " AND IDARTICULOWEB=".$value['ARTICULOSWEB.ID']. " AND IDSUBARTICULOWEB=0";
        $valor = $conection3->select_last_row_table("INGRESOINVENTARIO", array(), $condicionales." ORDER BY FECHA DESC");


        $json[$key]['REAJUSTE'] = 0;
        $json[$key]['CANTIDADINICIAL'] = 0;
        $json[$key]['INGRESO'] = 0;
        $json[$key]['EGRESOS'] = 0;
        $json[$key]['INVENTARIOMICRO'] = 0;
        $json[$key]['INVENTARIOOPERACION'] = 0;
        $json[$key]['VALOR_UNITARIO'] = $valor->IMPORTE;


        foreach ($ingresos as $key4 => $value4) {
            if($value['ARTICULOSWEB.ID'] == $value4['IDARTICULOWEB']){
                $json[$key]['INVENTARIO'] += $value4['INVENTARIO'];
                $json[$key]['INGRESO'] += $value4['INVENTARIO'];
                $json[$key]['INVENTARIOFINAL'] += $value4['INVENTARIO'];
                $json[$key]['INVENTARIOFINALOPERACION'] += $value4['INVENTARIO'];
           }
        }
        foreach ($inventarioparcial as $key5 => $value5) {
            if($value['ARTICULOSWEB.ID'] == $value5['IDARTICULOWEB']){
                $json[$key]['INVENTARIOOPERACION'] += $value5['INVENTARIO'];
                $json[$key]['INVENTARIOFINALOPERACION'] -= ($value5['INVENTARIO'] + $value5['MERMA']);
                $json[$key]['MERMA'] = $value5['MERMA'];
            }else
            {
                $json[$key]['INVENTARIOOPERACION'] += 0;
                $json[$key]['MERMA'] += 0;
            }
        }

        foreach ($inventarioActual as $key3 => $value3) {
            if($value['ARTICULOSWEB.ID'] == $value3['IDARTICULOWEB']){
                $json[$key]['INVENTARIO'] += ($value3['INVENTARIO'] + $value3['REAJUSTE']); //reajuste

                $json[$key]['REAJUSTE'] = $value3['REAJUSTE'];
                $json[$key]['FECHACORTE'] = $value3['FECHACORTE'];
                $json[$key]['CANTIDADINICIAL'] += $value3['INVENTARIO'];
                $json[$key]['INVENTARIOFINAL'] += $value3['INVENTARIO'];
                $json[$key]['INVENTARIOFINALOPERACION'] += $value3['INVENTARIO'];
                unset($inventarioActual[$key3]);
            }
        }


        if($json[$key]['CANTIDADINICIAL']<=0)
            $json[$key]['bandera'] = 0;
        else
            $json[$key]['bandera'] = 1;

            $color = "";
            if(($contador%2)==0)
                $color = " style='background:#EFEFEF'";

            $registro .= "<tr $color>";

            $registro .= "<td>".utf8_decode(ucwords(strtolower($value['ARTICULOSWEB.NOMBRE'])))."</td>";
            //$registro .= "<td>".$json[$key]["ARTICULOSWEB.UNIDAD"]."</td>";
            $registro .= "<td>".$json[$key]["ARTICULOSWEB.NOMBRELINEA"]."</td>";
            $registro .= "<td align='right'>".number_format($json[$key]['CANTIDADINICIAL'],2,".",",")."</td>";
            $registro .= "<td align='right'>".number_format($json[$key]['INGRESO'],2,".",",")."</td>";
            $registro .= "<td align='right'>".number_format(($json[$key]['INVENTARIOOPERACION'] + $json[$key]['MERMA']),2,".",",")."</td>";
            $registro .= "<td align='right'>".number_format($json[$key]['INVENTARIOFINALOPERACION'],2,".",",")."</td>";
            $registro .= "<td align='right'>".number_format($json[$key]['VALOR_UNITARIO'],2,".",",")."</td>";
            $registro .= "<td align='right'>".number_format(($json[$key]['INVENTARIOFINALOPERACION'] * $json[$key]['VALOR_UNITARIO']),2,".",",")."</td>";

            $registro .= "</tr>";
            $html .= $registro;
            $contador++;

    }

		$html .= "</tbody></table>";


$query1 = "SELECT idsubarticuloweb, sum(cantidad  + merma) as unidades from inventarioimpresion where fecha between '".$json_last_row->FECHA_INICIO."' and '".$json_last_row->FECHA_FIN."'  GROUP BY idsubarticuloweb  order by idsubarticuloweb";

$conection2 = new conexion_nexos(2);
$inventariooperaciones2 = array();

$resultInventariooperaciones2 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
while($row = ibase_fetch_object ($resultInventariooperaciones2, IBASE_TEXT))
{
    $index = count($inventariooperaciones2);
    $inventariooperaciones2[$index]['IDSUBARTICULOWEB'] = $row->IDSUBARTICULOWEB;
    $inventariooperaciones2[$index]['UNIDADES'] 	    = $row->UNIDADES;
}

$conection = new conexion_nexos(1);

$json_fecha = $conection->select_max_table("FECHA_INICIO", "INVENTARIOCORTE", array(), "");

$resultInventariooperaciones = ibase_query($conection->getConexion(), $query1) or die(ibase_errmsg());

$inventariooperaciones = array();
while($row = ibase_fetch_object ($resultInventariooperaciones, IBASE_TEXT))
{
    $index = count($inventariooperaciones);
    $inventariooperaciones[$index]['IDSUBARTICULOWEB'] = $row->IDSUBARTICULOWEB;
    $inventariooperaciones[$index]['UNIDADES'] 	       = $row->UNIDADES;
}

foreach ($inventariooperaciones as $key => $value) {

    foreach ($inventariooperaciones2 as $key2 => $value2) {

        if($value['IDSUBARTICULOWEB'] == $value2['IDSUBARTICULOWEB'])
        {
            $inventariooperaciones[$key]['UNIDADES'] += $value2['UNIDADES'];
            unset($inventariooperaciones2[$key2]);
        }
   }
}
$inventarioparcial = Array();
$inventarioparcial = array_merge($inventariooperaciones, $inventariooperaciones2);




$campos = array("ID","IDARTICULOWEB","NOMBRE", "CANTIDAD");

$join = array();

$condicionales = "" ;

$order = array("IDARTICULOWEB", "NOMBRE");

$json = $conection->select_table($campos, "SUBARTICULOSWEB", $join, $condicionales, $order, 1);

foreach($json  as $key => $index)
{
    $json[$key]["INVENTARIO"] = 0;
    $json[$key]["FINAL"] = $json[$key]['CANTIDAD'];

    foreach($inventarioparcial as $key2 => $index2)
    {
        if($index2['IDSUBARTICULOWEB'] == $json[$key]['ID'])
        {
            $condicionales = " AND IDARTICULOWEB='".$index['IDARTICULOWEB']."' AND IDSUBARTICULOWEB='".$index2['IDSUBARTICULOWEB']."'";
            $valor = $conection->select_last_row_table("INGRESOINVENTARIO", array(), $condicionales." ORDER BY FECHA DESC");

            $json[$key]['VALOR_UNITARIO'] = $valor->IMPORTE;

            $json[$key]["INVENTARIO"] = $index2['UNIDADES'];
            $json[$key]["FINAL"] = $json[$key]['CANTIDAD'] - $index2['UNIDADES'];
        }
    }
}

$html .= "<br>";
$html .= "SUB ARTICULOS WEB";
$html .= "<br>";

$html .= "<table style='font-size:9pt' width='100%''>";


$html .= utf8_decode("<thead STYLE='background: #CFCFCF'><tr><td>ARTICULO</td><td>INVENTARIO INICIAL</td><td>INVENTARIO IMPRESION</td><td>TOTAL</td><td>VALOR UNITARIO</td><td>VALOR INVENTARIO</td></tr></thead>");
$html .= "<tbody>";
$contador = 0;
foreach($json  as $key => $index)
{
    $color = "";
    if(($contador%2)==0)
        $color = " style='background:#EFEFEF'";

    $html .= "<tr $color>";
    $html .= "<td>".$index['NOMBRE']."</td>";
    $html .= "<td align='right'>".number_format($index['CANTIDAD'],2,".",",")."</td>";
    $html .= "<td align='right'>".number_format($index['INVENTARIO'],2,".",",")."</td>";
    $html .= "<td align='right'>".number_format($index['FINAL'],2,".",",")."</td>";
    $html .= "<td align='right'>".number_format($index['VALOR_UNITARIO'],2,".",",")."</td>";
    $html .= "<td align='right'>".number_format(($index['VALOR_UNITARIO'] * $index['FINAL']),2,".",",")."</td>";
    $html .= "</tr>";
    $contador++;
}

$html .= "</tbody></table>";


$html .= "<br>";
$html .= "INGRESO DE ARTÍCULOS";
$campos = array("INGRESOINVENTARIO.PROVEEDOR", "ARTICULOSWEB.NOMBRE", "INGRESOINVENTARIO.FECHA", "INGRESOINVENTARIO.CANTIDAD", "INGRESOINVENTARIO.IMPORTE", "INGRESOINVENTARIO.IDSUBARTICULOWEB");
$join = array("ARTICULOSWEB","=", "ARTICULOSWEB.ID", "INGRESOINVENTARIO.IDARTICULOWEB", "LEFT");
$json_ingreso = $conection->select_table_advanced($campos, "INGRESOINVENTARIO", $join, "", array("INGRESOINVENTARIO.FECHA ASC"), 0);


$html .= "<table style='font-size:9pt' width='100%''>";


$html .= utf8_decode("<thead STYLE='background: #CFCFCF'><tr><td>FECHA</td><td>PROVEEDOR</td><td>ARTICULO</td><td>SUBARTICULOS</td><td>CANTIDAD</td><td>IMPORTE</td></tr></thead>");
$html .= "<tbody>";
$contador = 0;
foreach($json_ingreso  as $key => $index)
{

    $campos = array("NOMBRE");
    $join = array();
    $json_ingreso_sub = $conection->select_table_advanced($campos, "SUBARTICULOSWEB", $join, " and ID=".$index["INGRESOINVENTARIO.IDSUBARTICULOWEB"], array(), 0);

    $color = "";
    if(($contador%2)==0)
        $color = " style='background:#EFEFEF'";

    $html .= "<tr $color>";
    $html .= "<td>".$index['INGRESOINVENTARIO.FECHA']."</td>";
    $html .= "<td align='right'>".$index['INGRESOINVENTARIO.PROVEEDOR']."</td>";
    $html .= "<td align='right'>".$index['ARTICULOSWEB.NOMBRE']."</td>";
    $html .= "<td align='right'>".$json_ingreso_sub[0]['NOMBRE']."</td>";
    $html .= "<td align='right'>".$index['INGRESOINVENTARIO.CANTIDAD']."</td>";
    $html .= "<td align='right'>".number_format($index['INGRESOINVENTARIO.IMPORTE'],2,".",",")."</td>";
    $html .= "</tr>";
    $contador++;
}

$html .= "</tbody></table>";
$html .= "<body></html>";
*/



function calcula_inventario()
{
    $conection3 = new conexion_nexos(1);

    $jsonid = $conection3->select_max_table("ID", "INVENTARIOCORTE", array(), "");
    $fecha_inicio = $conection3->select_max_table("FECHA_INICIO", "INVENTARIOCORTE", array(), "");

    $query2 = "SELECT AW.ID, AW.LINEA_ARTICULO_ID, I.IDARTICULOWEB, I.INVENTARIO_INICIAL, IC.FECHA_INICIO, AW.NOMBRE AS ARTICULO, AW.UNIDAD, AW.NOMBRELINEA FROM INVENTARIO I, INVENTARIOCORTE IC, ARTICULOSWEB AW WHERE I.IDARTICULOWEB = AW.ID AND I.IDINVENTARIOCORTE=IC.ID AND IC.ID='".$jsonid."' ORDER BY AW.LINEA_ARTICULO_ID";

    $query3 = "SELECT IDARTICULOWEB, SUM(CANTIDAD) as CANTIDAD FROM INGRESOINVENTARIO RAW WHERE fecha>'".$fecha_inicio."' GROUP BY IDARTICULOWEB";

    $query5 = "SELECT idarticuloweb, sum(cantidad + merma) as unidades, sum(merma + 0) as merma from inventarioimpresion where fecha>='".$fecha_inicio."'  GROUP BY idarticuloweb";

    $resultIngresos = ibase_query($conection3->getConexion(), $query3) or die(ibase_errmsg());

    $resultInventariooperaciones1 = ibase_query($conection3->getConexion(), $query5) or die(ibase_errmsg());

    $ingresoInventario = array();
    $buscador_ingresos = array();
    while($row = ibase_fetch_object ($resultIngresos, IBASE_TEXT))
    {
        $index = count($ingresoInventario);
        $ingresoInventario[$index]['IDARTICULOWEB']         = $row->IDARTICULOWEB;
        $ingresoInventario[$index]['CANTIDAD'] 	            = $row->CANTIDAD;
        $buscador_ingresos[]                                = $row->IDARTICULOWEB;
    }

    $inventariooperacionesNX = array();
    $buscador_baja1 = array();
    while($row = ibase_fetch_object ($resultInventariooperaciones1, IBASE_TEXT))
    {
        $index = count($inventariooperacionesNX);
        $inventariooperacionesNX[$index]['IDARTICULOWEB']           = $row->IDARTICULOWEB;
        $inventariooperacionesNX[$index]['INVENTARIO_INICIAL'] 	    = $row->UNIDADES;
        $inventariooperacionesNX[$index]['MERMA'] 	                = $row->MERMA;
        $buscador_baja1[]                                           = $row->IDARTICULOWEB;
    }

    $conection2 = new conexion_nexos(2);
    $buscador_baja2 = array();
    $resultInventariooperaciones2 = ibase_query($conection2->getConexion(), $query5) or die(ibase_errmsg());
    $inventariooperacionesNP = array();

    while($row = ibase_fetch_object ($resultInventariooperaciones2, IBASE_TEXT))
    {
        $index = count($inventariooperacionesNP);
        $inventariooperacionesNP[$index]['IDARTICULOWEB']         = $row->IDARTICULOWEB;
        $inventariooperacionesNP[$index]['INVENTARIO_INICIAL'] 	= $row->UNIDADES;
        $inventariooperacionesNP[$index]['MERMA'] 	            = $row->MERMA;
        $buscador_baja2[]                                       = $row->IDARTICULOWEB;;
    }

    $conection3 = new conexion_nexos(1);
    $resultInventario = ibase_query($conection3->getConexion(), $query2) or die(ibase_errmsg());
    $inventario_inicio = array();
    while($row = ibase_fetch_object ($resultInventario, IBASE_TEXT))
    {
        $index = count($inventario_inicio);
        $inventario_inicio[$index]['ID']                    = $row->ID;
        $inventario_inicio[$index]['IDARTICULOWEB']         = $row->IDARTICULOWEB;
        $inventario_inicio[$index]['LINEA_ID']              = $row->LINEA_ARTICULO_ID;
        $inventario_inicio[$index]['INVENTARIO_INICIAL'] 	= $row->INVENTARIO_INICIAL;
        $inventario_inicio[$index]['FECHA_INICIO'] 	        = $row->FECHA_INICIO;
        $inventario_inicio[$index]['NOMBRE'] 	            = utf8_encode($row->ARTICULO);
        $inventario_inicio[$index]['UNIDAD'] 	            = $row->UNIDAD;
        $inventario_inicio[$index]['LINEA'] 	            = $row->NOMBRELINEA;

        if(in_array($row->IDARTICULOWEB, $buscador_ingresos))
        {
            $inventario_inicio[$index]['INGRESO'] = $ingresoInventario[array_search($row->IDARTICULOWEB, $buscador_ingresos)]['CANTIDAD'];
        }else
            $inventario_inicio[$index]['INGRESO'] = 0.0;

        $baja_total = 0;
        if(in_array($row->IDARTICULOWEB, $buscador_baja1))
        {
            $index = array_search($row->IDARTICULOWEB, $buscador_baja1);
            $baja_total += $inventariooperacionesNX[$index]['CANTIDAD'] + $inventariooperacionesNX[$index]['MERMA'];
        }

        if(in_array($row->IDARTICULOWEB, $buscador_baja2))
        {
            $index = array_search($row->IDARTICULOWEB, $buscador_baja2);
            $baja_total += $inventariooperacionesNP[$index]['CANTIDAD'] + $inventariooperacionesNP[$index]['MERMA'];
        }

        $inventario_inicio[$index]['BAJA'] = $baja_total;

        $inventario_inicio[$index]['TOTAL'] = (($inventario_inicio[$index]['INVENTARIO_INICIAL'] + $inventario_inicio[$index]['INGRESO']) - $inventario_inicio[$index]['BAJA']);
    }

    return $inventario_inicio;
}
?>