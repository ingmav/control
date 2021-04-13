<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$arreglo2 = ver_ventas();

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte VENTAS-TABLERO")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Ventas Tablero")
    ->setKeywords("reporte de ventas tablero")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Ventas Tablero";
$titulosColumnas = array('FOLIO', 'FECHA', 'CLIENTE', 'DESCRIPCION', 'MONTO (SIN IVA)');


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:E1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte)
    ->setCellValue('A3',  $titulosColumnas[0])
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4]);

/*Fin de arreglo de rango de fechas*/
$i=4;
$total = 0;
foreach($arreglo2 as $key => $value)
{
	
	$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $value['FECHA'])
        ->setCellValue('C'.$i,  $value['NOMBRE'])
        ->setCellValue('D'.$i,  $value['DESCRIPCION'])
        ->setCellValue('E'.$i,  $value['MONTO']);
  	
  	$total += $value['MONTO'];	
  	$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode("$#,##0.00");
    $i++;
}

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D'.$i,  "TOTAL")
        ->setCellValue('E'.$i,  $total);
  	
  	$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode("$#,##0.00");




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

$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($estiloTituloColumnas);



$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(70);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(60);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(20);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteVentasTablero.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php


function ver_ventas()
{
    $conexion = new conexion_nexos($_SESSION['empresa']);

    $query = "
select FOLIO, FECHA, MONTO, NOMBRE, DESCRIPCION FROM
(select
'A'  || CAST(substring(dp.folio from 2) as int) AS FOLIO,
dp.fecha,
dp.importe_neto as monto,
 c.nombre,
 dp.descripcion
 from produccionpv pp,
doctos_pv dp,
clientes c
where pp.finalizar_proceso=0
and pp.docto_pv_id=dp.docto_pv_id
and dp.estatus!='C'
and dp.cliente_id=c.cliente_id
union all
select
'NP' || dv.tipo_docto|| '-' || CAST(dv.folio as int) AS FOLIO,
dv.fecha,
(dv.importe_neto) as monto,
c.nombre,
dv.descripcion
 from
tableroproduccion t,
doctos_ve dv,
clientes c
where t.docto_ve_id=dv.docto_ve_id
and t.finalizar_proceso=0
and dv.estatus!='C'
and dv.cliente_id=c.cliente_id)
order by fecha";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

   	 $arreglo_extra = array();
 
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice1 = count($arreglo_extra);

        $arreglo_extra[$indice1]['FOLIO'] = $row->FOLIO;
        $arreglo_extra[$indice1]['FECHA'] = $row->FECHA;
        $arreglo_extra[$indice1]['NOMBRE'] = utf8_encode($row->NOMBRE);
        $arreglo_extra[$indice1]['DESCRIPCION'] = utf8_encode($row->DESCRIPCION);
        $arreglo_extra[$indice1]['MONTO'] = $row->MONTO;
        
        

    }

    $conexion  = null;
    
	return $arreglo_extra	;        
}
?>
