<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$fecha_inicio = $_POST['fecha_inicio'];
$fecha_final  = $_POST['fecha_final'];
$arreglo2 = ver_actividades($fecha_inicio, $fecha_final);
// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Productividad")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de ProductividadS")
    ->setKeywords("reporte de productividad")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Productividad Diseño ".$_POST['fecha_inicio']." al ".$_POST['fecha_final'];
$titulosColumnas = array('FOLIO', 'FECHA', 'CLIENTE', 'DESCRIPCIÓN', "TIPO", "HRS", "OPERADOR", "FINALIZADO SISTEMA", "FINALIZADO AGENDA");


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
    ->setCellValue('I3',  $titulosColumnas[8]);


/*Creación de array con rango de fechas*/



/*Fin de arreglo de rango de fechas*/
$i=4;
foreach($arreglo2['lista'] as $key => $value)
{

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $value['FECHA_EJECUCION'])
        ->setCellValue('C'.$i,  utf8_encode($value['CLIENTE']))
        ->setCellValue('D'.$i,  utf8_encode($value['DESCRIPCION']))
        ->setCellValue('E'.$i,  $value['ACTIVIDAD'])
        ->setCellValue('F'.$i,  $value['HR'])
        ->setCellValue('G'.$i,  $value['OPERADOR'])
        ->setCellValue('H'.$i,  $value['FINALIZADO'])
        ->setCellValue('I'.$i,  $value['DESCRIPCION_ESTATUS']);

           
    $i++;
}
$i++;



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

$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(7);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(35);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(35);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(30);    
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(12);    
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(30);    

$objPHPExcel->getActiveSheet()->setTitle('DESGLOSE ACTIVIDADES');
//Hoja de Resumen
$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);

$tituloReporte = "Resumén de Reporte Productividad Diseño ".$_POST['fecha_inicio']." al ".$_POST['fecha_final'];
$titulosColumnas = array('CONCEPTO');


$objPHPExcel->setActiveSheetIndex(1)
   ->mergeCells('A1:K1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1',$tituloReporte)
    ->setCellValue('A3',  $titulosColumnas[0])
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2]);


/*Fin de arreglo de rango de fechas*/
$i=3;
//print_r($arreglo2['RESUMEN']);
$j = 0;
$arreglo_operacion = array("A","B","C", "D", "E", "F", "G", "H", "I");
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue($arreglo_operacion[$j].$i++,  "CONCEPTO")
        ->setCellValue($arreglo_operacion[$j].$i++,  "TOTAL ACTIVIDADES")
        ->setCellValue($arreglo_operacion[$j].$i++,  "TOTAL EXTRAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "TOTAL SISTEMA")
        ->setCellValue($arreglo_operacion[$j].$i++,  "FINALIZADOS SISTEMAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "HORAS AGENDADAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "HORAS COTIZADAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "ACTIVIDADES PENDIENTES")
        ->setCellValue($arreglo_operacion[$j].$i++,  "ACTIVIDADES INICIADAS NO FINALIZADAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "ACTIVIDADES INICIADAS FINALIZADAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "ACTIVIDADES EN VALIDACION")
        ->setCellValue($arreglo_operacion[$j].$i++,  "ACTIVIDADES FINALIZADAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "HORAS A LA SEMANA")
        ->setCellValue($arreglo_operacion[$j].$i++,  "HORAS DEL PERIODO")
        ->setCellValue($arreglo_operacion[$j].$i++,  "HOJAS EJECUTADAS")
        ->setCellValue($arreglo_operacion[$j].$i++,  "HRS NO COMPROMETIDAS");
        

$j++;

$arreglo = verificar_rango_fechas($fecha_inicio, $fecha_final);

$horas_totales_periodos = (($arreglo['TOTAL'] - $arreglo['DOMINGOS'] - $arreglo['SABADOS']) * 9) + ($arreglo['SABADOS'] * 5);
foreach($arreglo2['RESUMEN'] as $key => $value)
{
    $i=3;

    $horas_totales = $value['HRS']+($value['MIN']/60);
    $horas_restantes = ($horas_totales_periodos - $horas_totales);
   $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['OPERADOR'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['TOTAL'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['EXTRAS'])
        ->setCellValue($arreglo_operacion[$j].$i++,  ($value['TOTAL'] - $value['EXTRAS']))
        ->setCellValue($arreglo_operacion[$j].$i++,  ($value['FINALIZADOS']))
        ->setCellValue($arreglo_operacion[$j].$i++,  $horas_totales)
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['COBRADAS'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['PENDIENTE'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['INICIADO_NO'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['INICIADO_FI'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['VALIDACION'])
        ->setCellValue($arreglo_operacion[$j].$i++,  $value['FINALIZADO'])
        ->setCellValue($arreglo_operacion[$j].$i++,  9)
        ->setCellValue($arreglo_operacion[$j].$i++,  $horas_totales_periodos)
        ->setCellValue($arreglo_operacion[$j].$i++,  $horas_totales)
        ->setCellValue($arreglo_operacion[$j].$i++,  $horas_restantes);

     $i=3;
    $j++;
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue($arreglo_operacion[$j].$i++,  "%")
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['TOTAL'] / $value['TOTAL']) * 100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['EXTRAS']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round(((($value['TOTAL'] - $value['EXTRAS'])/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['FINALIZADOS']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  "100")
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['COBRADAS'] / ($value['HRS']+($value['MIN']/60)))*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['PENDIENTE']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['INICIADO_NO']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['INICIADO_FI']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['VALIDACION']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($value['FINALIZADO']/$value['TOTAL'])*100),2))
        ->setCellValue($arreglo_operacion[$j].$i++,  "--")
        ->setCellValue($arreglo_operacion[$j].$i++,  "100")
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($horas_totales/$horas_totales_periodos)*100) ,2))
        ->setCellValue($arreglo_operacion[$j].$i++,  round((($horas_restantes/$horas_totales_periodos)*100) ,2));

     $i++;
     $j++;
    
}

$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:'.$arreglo_operacion[--$j].'3')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('A')->setWidth(40);

$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('RESUMÉN GENERAL');


/* Empieza */
$arreglo_resumen = ver_resumen($fecha_inicio, $fecha_final);

$objPHPExcel->createSheet(2);
$objPHPExcel->setActiveSheetIndex(2);

$tituloReporte = "Resumén Productividad Diseño ".$_POST['fecha_inicio']." al ".$_POST['fecha_final'];
$titulosColumnas = array('FOLIO', 'DESCRIPCION', 'HORAS', 'COTOS SERVICIO', 'COSTO FACTURA', 'SALDO');
$titulosExtras = 'ACTIVIDADES EXTRAS';
$titulosVentas = 'ACTIVIDADES FACTURADAS';


$objPHPExcel->setActiveSheetIndex(2)
   ->mergeCells('A1:F1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A1',$tituloReporte);

$i = 3;
foreach ($arreglo_resumen as $key => $value) {
    
    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$i,  $titulosExtras." ".$key);
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A'.$i.':F'.$i);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($estiloTituloColumnas);

    $i++;
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($estiloTituloColumnas);

    $objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A'.$i,  $titulosColumnas[0])
    ->setCellValue('B'.$i,  $titulosColumnas[1])
    ->setCellValue('C'.$i,  $titulosColumnas[2])
    ->setCellValue('D'.$i,  $titulosColumnas[3])
    ->setCellValue('E'.$i,  $titulosColumnas[4])
    ->setCellValue('F'.$i,  $titulosColumnas[5]);
    $total = 0;
    foreach ($value['EXTRA'] as $key2 => $value2) {
        $i++;
        $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$i,  $value2['FOLIO'])
        ->setCellValue('B'.$i,  utf8_encode($value2['DESCRIPCION']))
        ->setCellValue('C'.$i,  $value2['UNIDADES'])
        ->setCellValue('D'.$i,  $value2['PRECIO'])
        ->setCellValue('E'.$i,  "--")
        ->setCellValue('F'.$i,  "--");
        $total += $value2['PRECIO'];
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
    }
    $i++;
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('C'.$i,  "IMPORTE TOTAL")
        ->setCellValue('D'.$i,  $total);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("#,##0.00");    
        
    $i++;
    $i++;
    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$i,  $titulosVentas." ".$key);
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A'.$i.':F'.$i);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($estiloTituloColumnas);

    $i++;
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($estiloTituloColumnas);

    $objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A'.$i,  $titulosColumnas[0])
    ->setCellValue('B'.$i,  $titulosColumnas[1])
    ->setCellValue('D'.$i,  $titulosColumnas[2])
    ->setCellValue('E'.$i,  $titulosColumnas[3])
    ->setCellValue('F'.$i,  $titulosColumnas[4]);

    $total = 0;
    foreach ($value['VENTA'] as $key2 => $value2) {
       $i++;
        $horas_agendadas = round(($value2['HR'] + ($value2['MIN']/60)),2); 
        $monto_horas = ($horas_agendadas * 176 );
        $diferencia = ( $value2['PRECIO'] - $monto_horas);
        $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$i,  $value2['FOLIO'])
        ->setCellValue('B'.$i,  utf8_encode($value2['DESCRIPCION']))
        ->setCellValue('C'.$i,  $horas_agendadas)
        ->setCellValue('D'.$i,  $monto_horas)
        ->setCellValue('E'.$i,  $value2['PRECIO'])
        ->setCellValue('F'.$i,  $diferencia);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $total += $monto_horas;
    }
    $i++;
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('C'.$i,  "IMPORTE TOTAL")
        ->setCellValue('D'.$i,  $total);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("#,##0.00");    
        
    $i++;
    $i++;
    
}

$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);

$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('A')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('B')->setWidth(40);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('C')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('D')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('E')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('F')->setWidth(20);

$objPHPExcel->setActiveSheetIndex(2);
/*---------------------------------------*/

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteProductividadDiseño.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
?>
<?php


function ver_actividades($fecha_inicio, $fecha_final)
{
    $conexion = new conexion_nexos(1);

    $query = "select
a.empresa,
a.docto_ve_id,
a.docto_ve_det_id,
a.hr,
a.minuto,
a.operador,
a.iddepartamento,
a.fecha,
a.arreglo,
a.folio,
a.cliente,
a.descripcion,
a.entrega,
a.realizado,
a.observacion,
a.estatus,
o.nombre
from agenda a,
operadordepartamento od,
operador o
where a.fecha  between '".$fecha_inicio."' and '".$fecha_final."'
and a.iddepartamento=2
and a.operador=od.id
and od.idoperador=o.id
and empresa=0";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    
    $arreglo_extra = array();
    $arreglo_operadores = array();
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $arreglo_extra[$indice1]['HR'] = $row->HR + round(($row->MINUTO/60),2);
        $arreglo_extra[$indice1]['OPERADOR'] = $row->NOMBRE;
        $arreglo_extra[$indice1]['FECHA_INGRESO'] = $row->FECHA;
        $arreglo_extra[$indice1]['FECHA_EJECUCION'] = $row->FECHA;
        $arreglo_extra[$indice1]['FOLIO'] = $row->FOLIO;
        $arreglo_extra[$indice1]['CLIENTE'] = $row->CLIENTE;
        $arreglo_extra[$indice1]['DESCRIPCION'] = $row->DESCRIPCION;
        $arreglo_extra[$indice1]['OBSERVACION'] = $row->OBSERVACION;
        $arreglo_extra[$indice1]['ESTATUS'] = $row->ESTATUS;
        $arreglo_extra[$indice1]['ACTIVIDAD'] = "EXTRA";
        $arreglo_extra[$indice1]['FINALIZADO'] = "---";
        $arreglo_extra[$indice1]['FINALIZADO_ID'] = "0";
        $arreglo_extra[$indice1]['COBRADAS'] = "0";
        $arreglo_extra[$indice1]['OBSERVACION'] = "";
        $arreglo_extra[$indice1]['UNIDADES'] = "0";
        $arreglo_extra[$indice1]['EXTRAS'] = "1";

        //if($arreglo_operadores[])


    }

    $conexion2 = new conexion_nexos(2);
    $query = "select
a.empresa,
a.docto_ve_id,
a.docto_ve_det_id,
a.hr,
a.minuto,
a.operador,
a.iddepartamento,
a.fecha,
a.arreglo,
a.folio,
a.cliente,
a.descripcion,
a.entrega,
a.realizado,
a.observacion,
a.estatus,
o.nombre
from agenda a,
operadordepartamento od,
operador o
where a.fecha  between '".$fecha_inicio."' and '".$fecha_final."'
and a.iddepartamento=2
and a.operador=od.id
and od.idoperador=o.id
and empresa=2";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $arreglo_extra[$indice1]['HR'] = $row->HR;
        $arreglo_extra[$indice1]['MIN'] = $row->MINUTO;
        $arreglo_extra[$indice1]['OPERADOR'] = utf8_decode($row->NOMBRE);
        $arreglo_extra[$indice1]['FECHA_INGRESO'] = $row->FECHA;
        $arreglo_extra[$indice1]['FECHA_EJECUCION'] = $row->FECHA;
        $arreglo_extra[$indice1]['FOLIO'] = $row->FOLIO;
        $arreglo_extra[$indice1]['CLIENTE'] = $row->CLIENTE;
        $arreglo_extra[$indice1]['DESCRIPCION'] = $row->DESCRIPCION;
        $arreglo_extra[$indice1]['OBSERVACION'] = utf8_decode($row->OBSERVACION);
        $arreglo_extra[$indice1]['FINALIZADO'] = "NO";
        $arreglo_extra[$indice1]['FINALIZADO_ID'] = "0";
        $arreglo_extra[$indice1]['ESTATUS'] = $row->ESTATUS;
        $arreglo_extra[$indice1]['ACTIVIDAD'] = "VENTAS ADMINISTRACION";
        $arreglo_extra[$indice1]['EXTRAS'] = "0";

        $query2 = "select
        IIF(DISENO_GF!=2 , 'NO', 'SI') as terminado,
        DISENO_GF,
        (SELECT SUM(UNIDADES) FROM DOCTOS_VE_DET WHERE DOCTO_VE_DET_ID=".$row->DOCTO_VE_DET_ID." AND ARTICULO_ID=13983 GROUP BY ARTICULO_ID) AS UNIDADES,
        (select FIRST 1 OBSERVACION from tableroobservacion where idtableroproduccion=tableroproduccion.ID ORDER BY ID DESC) AS OBSERVACION
		from TABLEROPRODUCCION 
		where
		docto_ve_id=".$row->DOCTO_VE_ID."
		and docto_ve_det_id=".$row->DOCTO_VE_DET_ID;

		$result2 = ibase_query($conexion2->getConexion(), $query2) or die(ibase_errmsg());

		while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
             $arreglo_extra[$indice1]['FINALIZADO'] = $row2->TERMINADO; 
             $arreglo_extra[$indice1]['FINALIZADO_ID'] = $row2->DISENO_GF;

             if($row2->DISENO_GF == 2)
                 $arreglo_extra[$indice1]['FINALIZADO_ID'] = 1;
        
        
             $arreglo_extra[$indice1]['UNIDADES'] = $row2->UNIDADES;    
			 $arreglo_extra[$indice1]['OBSERVACION'] = utf8_encode($row2->OBSERVACION);	
    	}

    }

        $query = "select
a.empresa,
a.docto_ve_id,
a.docto_ve_det_id,
a.hr,
a.minuto,
a.operador,
a.iddepartamento,
a.fecha,
a.arreglo,
a.folio,
a.cliente,
a.descripcion,
a.entrega,
a.realizado,
a.observacion,
a.estatus,
o.nombre
from agenda a,
operadordepartamento od,
operador o
where a.fecha  between '".$fecha_inicio."' and '".$fecha_final."'
and a.iddepartamento=2
and a.operador=od.id
and od.idoperador=o.id
and empresa=3";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $arreglo_extra[$indice1]['HR'] = $row->HR;
        $arreglo_extra[$indice1]['MIN'] = $row->MINUTO;
        $arreglo_extra[$indice1]['OPERADOR'] = utf8_decode($row->NOMBRE);
        $arreglo_extra[$indice1]['FECHA'] = $row->FECHA;
        $arreglo_extra[$indice1]['FOLIO'] = $row->FOLIO;
        $arreglo_extra[$indice1]['CLIENTE'] = utf8_decode($row->CLIENTE);
        $arreglo_extra[$indice1]['DESCRIPCION'] = utf8_decode($row->DESCRIPCION);
        $arreglo_extra[$indice1]['OBSERVACION'] = utf8_decode($row->OBSERVACION);
        $arreglo_extra[$indice1]['FINALIZADO'] = "NO";
        $arreglo_extra[$indice1]['FINALIZADO_ID'] = "0";
        $arreglo_extra[$indice1]['EXTRAS'] = "0";

        $arreglo_extra[$indice1]['ESTATUS'] = $row->ESTATUS;
        $arreglo_extra[$indice1]['ACTIVIDAD'] = "VENTAS MOSTRADOR";

        $query2 = "select
        IIF(DISENO_GF!=2 , 'NO', 'SI') as terminado,
        DISENO_GF,
        (SELECT SUM(UNIDADES) FROM DOCTOS_PV_DET WHERE DOCTO_PV_DET_ID=".$row->DOCTO_VE_DET_ID." AND ARTICULO_ID=13983 GROUP BY ARTICULO_ID) AS UNIDADES,
        (select FIRST 1 OBSERVACION from pvobservacion where docto_pv_id=".$row->DOCTO_VE_ID." ORDER BY ID DESC) AS OBSERVACION
        
		from PRODUCCIONPV
		where
		docto_pv_id=".$row->DOCTO_VE_ID."
		and docto_pv_det_id=".$row->DOCTO_VE_DET_ID;

		$result2 = ibase_query($conexion2->getConexion(), $query2) or die(ibase_errmsg());

		while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
			 $arreglo_extra[$indice1]['FINALIZADO'] = $row2->TERMINADO;
             if($row2->DISENO_GF == 2)
             $arreglo_extra[$indice1]['FINALIZADO_ID'] = 1;
        
             $arreglo_extra[$indice1]['UNIDADES'] = $row2->UNIDADES;    
             $arreglo_extra[$indice1]['OBSERVACION'] = utf8_encode($row2->OBSERVACION); 	
    	}

    }

    $arreglo_operadores = array();

    foreach ($arreglo_extra as $key => $value) {
        $pendiente = 0;
        $iniciado_no = 0;
        $iniciado_fi = 0;
        $validacion = 0;
        $finalizado = 0;
        
        switch($value['ESTATUS'])
    	{
    		case 1:
    		$arreglo_extra[$key]['DESCRIPCION_ESTATUS'] = "PENDIENTE";
            $pendiente++;
    		break;
    		case 2:
    		$arreglo_extra[$key]['DESCRIPCION_ESTATUS'] = "INICIADO - NO FINALIZADO";
            $iniciado_no++;
    		break;
    		case 3:
    		$arreglo_extra[$key]['DESCRIPCION_ESTATUS'] = "INICIADO - FINALIZADO PARCIALMENTE";
            $iniciado_fi++;
    		break;
    		case 4:
    		$arreglo_extra[$key]['DESCRIPCION_ESTATUS'] = "EN VALIDACION";
            $validacion++;
    		break;
    		case 5:
    		$arreglo_extra[$key]['DESCRIPCION_ESTATUS'] = "FINALIZADO";
            $finalizado++;
    		break;
    	}

        if($arreglo_operadores[$arreglo_extra[$key]['OPERADOR']])
        {
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['TOTAL'] += 1;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['FINALIZADOS'] += $arreglo_extra[$key]['FINALIZADO_ID'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['COBRADAS'] += $arreglo_extra[$key]['UNIDADES'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['HRS'] += $arreglo_extra[$key]['HR'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] += $arreglo_extra[$key]['MIN'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['PENDIENTE'] += $pendiente;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['INICIADO_NO'] += $iniciado_no;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['INICIADO_FI'] += $iniciado_fi;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['VALIDACION'] += $validacion;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['FINALIZADO'] += $finalizado;
            if($arreglo_extra[$key]['EXTRAS'] == 1)
                $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['EXTRAS'] += 1;

            if(($arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] / 60) >=1)
            {
                $hrs = intval(($arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] / 60));
                $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['HRS'] += $hrs;
                $min_restantes = $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] - ($hrs * 60);
                $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] = $min_restantes;
            }
        }else
        {
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['OPERADOR'] = $arreglo_extra[$key]['OPERADOR'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['TOTAL'] = 1;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['FINALIZADOS'] = $arreglo_extra[$key]['FINALIZADO_ID'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['COBRADAS'] += $arreglo_extra[$key]['COBRADAS'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['HRS'] = $arreglo_extra[$key]['HR'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] = $arreglo_extra[$key]['MIN'];
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['PENDIENTE'] = $pendiente;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['INICIADO_NO'] = $iniciado_no;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['INICIADO_FI'] = $iniciado_fi;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['VALIDACION'] = $validacion;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['FINALIZADO'] = $finalizado;
            $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['EXTRAS'] = 0;
            if($arreglo_extra[$key]['EXTRAS'] == 1)
                $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['EXTRAS'] += 1;

            if(($arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] / 60) >=1)
            {
                $hrs = intval(($arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] / 60));
                $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['HRS'] += $hrs;
                $min_restantes = $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] - ($hrs * 60);
                $arreglo_operadores[$arreglo_extra[$key]['OPERADOR']]['MIN'] = $min_restantes;
            }
            
        }

    	$bandera = 0;
    	
    }
    $conexion  = null;
    $conexion2 = null;

	return array("lista" =>$arreglo_extra, "RESUMEN"=>$arreglo_operadores);        
}

function ver_resumen($fecha_inicio, $fecha_final)
{
    $conexion = new conexion_nexos(1);

    $query = "select
a.operador,
o.nombre,
a.cliente,
a.descripcion,
sum(a.hr) as hr,
sum(a.minuto) as minuto
from agenda a,
operadordepartamento od,
operador o
where a.fecha  between '".$fecha_inicio."' and '".$fecha_final."'
and a.iddepartamento=2
and a.operador=od.id
and od.idoperador=o.id
and empresa=0
group by a.operador,o.nombre, a.descripcion,a.cliente";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    
    $arreglo_extra = array();
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $horas_totales = $row->HR + round(($row->MINUTO / 60),2);
        $arreglo_extra[$indice1]['HR'] = $row->HR;
        $arreglo_extra[$indice1]['MIN'] = $row->MINUTO;
        $arreglo_extra[$indice1]['OPERADOR'] = $row->NOMBRE;
        $arreglo_extra[$indice1]['CLIENTE'] = $row->CLIENTE;
        $arreglo_extra[$indice1]['DESCRIPCION'] = $row->DESCRIPCION;
        $arreglo_extra[$indice1]['ACTIVIDAD'] = "1";
        $arreglo_extra[$indice1]['FOLIO'] = "--";
        $arreglo_extra[$indice1]['UNIDADES'] = $horas_totales;
        $arreglo_extra[$indice1]['PRECIO'] = round(($horas_totales * 176),2);

        
    }

    $conexion2 = new conexion_nexos(2);
    $query = "select
a.folio,    
a.docto_ve_id,
a.docto_ve_det_id,
a.operador,
o.nombre,
a.cliente,
a.descripcion,
sum(a.hr) as hr,
sum(a.minuto) as minuto
from agenda a,
operadordepartamento od,
operador o
where a.fecha  between '".$fecha_inicio."' and '".$fecha_final."'
and a.iddepartamento=2
and a.operador=od.id
and od.idoperador=o.id
and empresa=2
group by a.operador,o.nombre, a.descripcion,a.cliente, a.docto_ve_id, a.docto_ve_det_id, a.folio";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $arreglo_extra[$indice1]['HR'] = $row->HR;
        $arreglo_extra[$indice1]['MIN'] = $row->MINUTO;
        $arreglo_extra[$indice1]['OPERADOR'] = utf8_decode($row->NOMBRE);
        $arreglo_extra[$indice1]['FOLIO'] = $row->FOLIO;
        $arreglo_extra[$indice1]['CLIENTE'] = $row->CLIENTE;
        $arreglo_extra[$indice1]['DESCRIPCION'] = $row->DESCRIPCION;
        $arreglo_extra[$indice1]['ACTIVIDAD'] = "2";
        
        $query2 = "select
        (SELECT SUM(UNIDADES) FROM DOCTOS_VE_DET WHERE DOCTO_VE_ID=".$row->DOCTO_VE_ID." AND ARTICULO_ID in (13983, 19395) GROUP BY ARTICULO_ID) AS UNIDADES,
        (SELECT SUM(PRECIO_TOTAL_NETO) FROM DOCTOS_VE_DET WHERE DOCTO_VE_ID=".$row->DOCTO_VE_ID." AND ARTICULO_ID in (13983, 19395) GROUP BY ARTICULO_ID) AS PRECIO_TOTAL_NETO
        from TABLEROPRODUCCION 
        where
        docto_ve_id=".$row->DOCTO_VE_ID;

        $result2 = ibase_query($conexion2->getConexion(), $query2) or die(ibase_errmsg());

        while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
             $arreglo_extra[$indice1]['UNIDADES'] = $row2->UNIDADES;    
             $arreglo_extra[$indice1]['PRECIO'] = $row2->PRECIO_TOTAL_NETO;    
        }

    }

       $query = "select
a.folio,    
a.docto_ve_id,
a.docto_ve_det_id,
a.operador,
o.nombre,
a.cliente,
a.descripcion,
sum(a.hr) as hr,
sum(a.minuto) as minuto
from agenda a,
operadordepartamento od,
operador o
where a.fecha  between '".$fecha_inicio."' and '".$fecha_final."'
and a.iddepartamento=2
and a.operador=od.id
and od.idoperador=o.id
and empresa=3
group by a.operador,o.nombre, a.descripcion,a.cliente, a.docto_ve_id, a.docto_ve_det_id, a.folio";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $arreglo_extra[$indice1]['HR'] = $row->HR;
        $arreglo_extra[$indice1]['MIN'] = $row->MINUTO;
        $arreglo_extra[$indice1]['OPERADOR'] = utf8_decode($row->NOMBRE);
        $arreglo_extra[$indice1]['FOLIO'] = $row->FOLIO;
        $arreglo_extra[$indice1]['CLIENTE'] = $row->CLIENTE;
        $arreglo_extra[$indice1]['DESCRIPCION'] = $row->DESCRIPCION;
        $arreglo_extra[$indice1]['ACTIVIDAD'] = "3";

        $query2 = "select
        (SELECT SUM(UNIDADES) FROM DOCTOS_PV_DET WHERE DOCTO_PV_ID=".$row->DOCTO_VE_ID." AND ARTICULO_ID in (13983, 19395) GROUP BY ARTICULO_ID) AS UNIDADES,
        (SELECT SUM(PRECIO_TOTAL_NETO) FROM DOCTOS_PV_DET WHERE DOCTO_PV_ID=".$row->DOCTO_VE_ID." AND ARTICULO_ID in (13983, 19395) GROUP BY ARTICULO_ID) AS PRECIO_TOTAL_NETO
        from PRODUCCIONPV
        where
        docto_pv_id=".$row->DOCTO_VE_ID;

        $result2 = ibase_query($conexion2->getConexion(), $query2) or die(ibase_errmsg());

        while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
             $arreglo_extra[$indice1]['UNIDADES'] = $row2->UNIDADES;    
             $arreglo_extra[$indice1]['PRECIO'] = $row2->PRECIO_TOTAL_NETO;    
        }

    }

    $arreglo_operadores = array();

    foreach ($arreglo_extra as $key => $value) {
        $indice  = $arreglo_extra[$key]['OPERADOR'];
        if($arreglo_operadores[$indice])
        {
            if($value['ACTIVIDAD'] == 1)
            {
                $count = count($arreglo_operadores[$indice]['EXTRA']);
                $arreglo_operadores[$indice]['EXTRA'][$count]['HR'] = $value['HR'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['MIN'] = $value['MIN'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['OPERADOR'] = $value['OPERADOR'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['FOLIO'] = $value['FOLIO'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['CLIENTE'] = $value['CLIENTE'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['DESCRIPCION'] = $value['DESCRIPCION'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['UNIDADES'] = $value['UNIDADES'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['PRECIO'] = $value['PRECIO'];   
                
            }else
            {
                $count = count($arreglo_operadores[$indice]['VENTA']);
                $arreglo_operadores[$indice]['VENTA'][$count]['HR'] = $value['HR'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['MIN'] = $value['MIN'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['OPERADOR'] = $value['OPERADOR'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['FOLIO'] = $value['FOLIO'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['CLIENTE'] = $value['CLIENTE'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['DESCRIPCION'] = $value['DESCRIPCION'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['UNIDADES'] = $value['UNIDADES'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['PRECIO'] = $value['PRECIO'];
            }
        }else
        {
            if($value['ACTIVIDAD'] == 1)
            {
                $count = count($arreglo_operadores[$indice]['EXTRA']);
                $arreglo_operadores[$indice]['EXTRA'][$count]['HR'] = $value['HR'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['MIN'] = $value['MIN'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['OPERADOR'] = $value['OPERADOR'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['FOLIO'] = $value['FOLIO'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['CLIENTE'] = $value['CLIENTE'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['DESCRIPCION'] = $value['DESCRIPCION'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['UNIDADES'] = $value['UNIDADES'];   
                $arreglo_operadores[$indice]['EXTRA'][$count]['PRECIO'] = $value['PRECIO'];   
                
            }else
            {
                $count = count($arreglo_operadores[$indice]['VENTA']);
                $arreglo_operadores[$indice]['VENTA'][$count]['HR'] = $value['HR'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['MIN'] = $value['MIN'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['OPERADOR'] = $value['OPERADOR'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['FOLIO'] = $value['FOLIO'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['CLIENTE'] = $value['CLIENTE'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['DESCRIPCION'] = $value['DESCRIPCION'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['UNIDADES'] = $value['UNIDADES'];   
                $arreglo_operadores[$indice]['VENTA'][$count]['PRECIO'] = $value['PRECIO'];
            }
            
        }

        $bandera = 0;
        
    }
    $conexion  = null;
    $conexion2 = null;

    return $arreglo_operadores;        
}

function verificar_rango_fechas($fecha1, $fecha2)
{

    $starDate = new DateTime($fecha1);
    $endDate = new DateTime($fecha2);
    $interval = $starDate->diff($endDate);
    $numberOfDays = $interval->format('%d');
    $numberOfDays += 1;
    $sabados = 0;
    $domingos = 0;
    for($i = 1; $i <= $numberOfDays; $i++){

         if($starDate->format('l')== 'Saturday'){
            $sabados++;
        }
        if($starDate->format('l')== 'Sunday'){
            $domingos++;
        }
            
         $starDate->modify("+1 days");
                    
    }

    return array("TOTAL"=>$numberOfDays, "SABADOS"=>$sabados, "DOMINGOS"=>$domingos);
}
?>