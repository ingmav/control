<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$arreglo = backup();


// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("PRODUCCION") //Autor
    ->setLastModifiedBy("PRODUCCION") //Ultimo usuario que lo modificó
    ->setTitle("Backup CXP")
    ->setSubject("Reporte CXP")
    ->setDescription("Reporte de CXP")
    ->setKeywords("reporte CXP")
    ->setCategory("Reporte PRODUCCION");

$tituloReporte = "Reporte CXP";
$titulosColumnas = array('ID', 'FECHA FACTURA', 'FACTURA', 'PROVEEDOR', 'MONTO','DESCUENTO', 'ARTICULO', "LARGO", "ANCHO", "PAGADO", "FECHA PAGADO");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:K1');

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
    ->setCellValue('J3',  $titulosColumnas[9])
    ->setCellValue('K3',  $titulosColumnas[10]);

//Se agregan los datos de los alumnos
$i = 4;


foreach($arreglo as $key => $value)
{
    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['ID'])
        ->setCellValue('B'.$i,  $value['FECHA_FACTURA'])
        ->setCellValue('C'.$i,  $value['FACTURA'])
        ->setCellValue('D'.$i,  $value['PROVEEDOR'])
        ->setCellValue('E'.$i,  $value['MONTO'])
        ->setCellValue('F'.$i,  $value['DESCUENTO'])
        ->setCellValue('G'.$i,  $value['ARTICULO'])
        ->setCellValue('H'.$i,  $value['LARGO'])
        ->setCellValue('I'.$i,  $value['ANCHO'])
        ->setCellValue('J'.$i,  $value['PAGADO'])
        ->setCellValue('K'.$i,  $value['FECHA_PAGADO']);
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

$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:K3')->applyFromArray($estiloTituloColumnas);


for($i = 'A'; $i <= 'K'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Backup CXP');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="BackupCXP.xlsx"');
//header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
?>

<?php
function backup()
{
    $conexion = new conexion_nexos($_SESSION['empresa']);

    $query1 = "SELECT
    MP.id_pago as ID,
    MP.factura AS FACTURA,
    MPR.nombre AS PROVEEDOR,
    MP.fecha_factura AS FECHA_FACTURA,
    MP.monto AS MONTO,
    IIF(MP.pagado = 1, 'PAGADO', 'NO PAGADO') AS PAGADO,
    MA.nombre_articulo AS ARTICULO,
    MP.fecha_pagado AS FECHA_PAGADO,
    MP.largo AS LARGO,
    MP.ancho AS ANCHO,
    MP.descuento AS DESCUENTO
    FROM
    MS_PAGOS MP,
    ms_proveedor MPR,
    MS_ARTICULOS MA
    WHERE MP.ms_proveedor_id=MPR.id
    AND MP.ms_articulo_id=MA.id
    ORDER BY MP.fecha_factura";

    $result = ibase_query($conexion->getConexion(), $query1) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $arreglo1[$index]['ID'] = $row->ID;
        $arreglo1[$index]['FACTURA'] = $row->FACTURA;
        $arreglo1[$index]['PROVEEDOR'] = utf8_encode($row->PROVEEDOR);
        $arreglo1[$index]['FECHA_FACTURA'] = $row->FECHA_FACTURA;
        $arreglo1[$index]['MONTO'] = $row->MONTO;
        $arreglo1[$index]['PAGADO'] = $row->PAGADO;
        $arreglo1[$index]['ARTICULO'] = utf8_encode($row->ARTICULO);
        $arreglo1[$index]['FECHA_PAGADO'] = $row->FECHA_PAGADO;
        $arreglo1[$index]['LARGO'] = $row->LARGO;
        $arreglo1[$index]['ANCHO'] = $row->ANCHO;
        $arreglo1[$index]['DESCUENTO'] = $row->DESCUENTO;

    }
    return $arreglo1;
}
?>