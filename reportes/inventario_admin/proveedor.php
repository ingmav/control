<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


$arreglo1 = ver_pagos_proveedor();

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Compras a Proveedores")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Compras Anuales")
    ->setKeywords("reporte de cuentas por cobrar")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Historico de Compras Anuales";
$titulosColumnas = array("FACTURA", "FECHA COMPRA", "PROVEEDOR", "FAMILIA", "ARTICULO", "CANTIDAD", "PRECIO UNITARIO", "PRECIO TOTAL");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:H1');

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
    ->setCellValue('H3',  $titulosColumnas[7]);

$i = 4;
$index = 0;
foreach($arreglo1 as $key => $value)
{
    foreach($value as $key2 => $value2)
    {
        /*print_r($value[$key2]);
        echo "<br><br>";*/
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value[$key2]['FACTURA'])
            ->setCellValue('B'.$i,  $value[$key2]['FECHA'])
            ->setCellValue('C'.$i,  $value[$key2]['PROVEEDOR'])
            ->setCellValue('D'.$i,  $value[$key2]['FAMILIA'])
            ->setCellValue('E'.$i,  $value[$key2]['ARTICULO'])
            ->setCellValue('F'.$i,  $value[$key2]['CANTIDAD'])
            ->setCellValue('G'.$i,  $value[$key2]['PRECIO_UNITARIO'])
            ->setCellValue('H'.$i,  $value[$key2]['PRECIO']);

            $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
            $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
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
        'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
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


$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);

for($i = 'A'; $i <= 'H'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('COMPRAS_PROVEEDOR');

$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteComprasProveedor.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php



function ver_pagos_proveedor()
{
    $conexion = new conexion_nexos($_SESSION['empresa']);

    $query =  "select
 MI.factura_compra,
 MI.fecha_factura,
 MP.nombre,
 MF.descripcion AS FAMILIA,
 MA.nombre_articulo AS ARTICULO,
 MI.CANTIDAD,
 MI.PRECIO_UNITARIO,
 MI.precio_compra
  from MS_INVENTARIO MI, MS_PROVEEDOR MP, MS_ARTICULOS MA, ms_familia MF
 where MI.ms_proveedor_id=MP.id AND MI.ms_articulo_id=MA.id
 and MA.ms_familia_id = MF.id
and MI.fecha_factura >= '".date("Y").".01.01'
and MF.ID!=16
AND MI.fecha_factura>'2017-06-01'
ORDER BY MI.FECHA_FACTURA, MF.DESCRIPCION, MI.FACTURA_COMPRA, MA.NOMBRE_ARTICULO";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $arreglo1[$index]['FACTURA'] =  utf8_decode($row->FACTURA_COMPRA);
        $arreglo1[$index]['FECHA']  = $row->FECHA_FACTURA;

        $arreglo1[$index]['PROVEEDOR'] = utf8_decode($row->NOMBRE);
        $arreglo1[$index]['ARTICULO'] = utf8_decode($row->ARTICULO);
        $arreglo1[$index]['FAMILIA'] = utf8_decode($row->FAMILIA);
        $arreglo1[$index]['CANTIDAD'] = $row->CANTIDAD;
        $arreglo1[$index]['PRECIO_UNITARIO'] = $row->PRECIO_UNITARIO;
        $arreglo1[$index]['PRECIO'] = $row->PRECIO_COMPRA;
    }

    $conexion = null;
    return array("data" => $arreglo1);
        
}
?>