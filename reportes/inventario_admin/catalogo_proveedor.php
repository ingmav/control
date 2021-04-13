<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


$arreglo1 = ver_proveedor();

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Proveedores")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de  Proveedores")
    ->setKeywords("reporte")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte de Proveedores";
$titulosColumnas = array("NOMBRE", "DIRECCION", "TELEFONO", "CONTACTO", "C. DE PAGO", "NO CUENTA");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:F1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte)
    ->setCellValue('A3',  $titulosColumnas[0])
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4])
    ->setCellValue('F3',  $titulosColumnas[5]);

$i = 4;
$index = 0;
foreach($arreglo1 as $key => $value)
{
    foreach($value as $key2 => $value2)
    {
        /*print_r($value[$key2]);
        echo "<br><br>";*/
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value[$key2]['NOMBRE'])
            ->setCellValue('B'.$i,  $value[$key2]['DIRECCION'])
            ->setCellValue('C'.$i,  $value[$key2]['TELEFONO'])
            ->setCellValue('D'.$i,  $value[$key2]['CONTACTO'])
            ->setCellValue('E'.$i,  $value[$key2]['CONDICION'])
            ->setCellValue('F'.$i,  $value[$key2]['CUENTA']);

            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $i++;          
    }
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

$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);


$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle('F22')->getAlignment()->setWrapText(true);


// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('COMPRAS_PROVEEDOR');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteProveedor.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php



function ver_proveedor()
{
    $conexion = new conexion_nexos($_SESSION['empresa']);

    $query =  "select
 nombre,
 direccion,
 telefono,
 condicion_pago,
 contacto,
 descripcion,
 cuenta
  from MS_PROVEEDOR
  where deleted is null";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $arreglo1[$index]['NOMBRE'] =  utf8_decode($row->NOMBRE);
        $arreglo1[$index]['DIRECCION'] = $row->DIRECCION;

        $arreglo1[$index]['TELEFONO'] = utf8_decode($row->TELEFONO);
        $arreglo1[$index]['CONDICION'] = utf8_decode($row->CONDICION_PAGO);
        $arreglo1[$index]['CUENTA'] = utf8_decode(str_replace("\n", "", $row->CUENTA));
        $arreglo1[$index]['CONTACTO'] = $row->CONTACTO;
     }

    $conexion = null;
    return array("data" => $arreglo1);
        
}
?>