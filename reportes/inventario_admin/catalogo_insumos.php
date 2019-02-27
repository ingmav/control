<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


$arreglo1 = ver_proveedor();
//print_r($arreglo1);

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte ARTICULOS")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de  ARTICULOS")
    ->setKeywords("reporte")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte de ARTICULOS";
$titulosColumnas = array("FAMILIA", "NOMBRE", "MINIMO", "COMPRA", "ANCHO", "LARGO", "VENTA");


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
    ->setCellValue('G3',  $titulosColumnas[6]);

$i = 4;
$index = 0;
foreach($arreglo1 as $key => $value)
{
    foreach($value as $key2 => $value2)
    {
       
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value[$key2]['FAMILIA'])
            ->setCellValue('B'.$i,  $value[$key2]['NOMBRE'])
            ->setCellValue('C'.$i,  $value[$key2]['MINIMO'])
            ->setCellValue('D'.$i,  $value[$key2]['UNIDAD_COMPRA'])
            ->setCellValue('E'.$i,  $value[$key2]['ANCHO'])
            ->setCellValue('F'.$i,  $value[$key2]['LARGO'])
            ->setCellValue('G'.$i,  $value[$key2]['UNIDAD_VENTA']);

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

$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($estiloTituloColumnas);
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);


// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('CATALOGO_ARTICULOS');

$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteArticulos.xlsx"');


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
    $conexion = new conexion_nexos(2);

    $query =  "select
 MF.DESCRIPCION AS FAMILIA,
 MA.NOMBRE_ARTICULO AS NOMBRE,
 MA.CANTIDAD_MINIMA,
 MA.ANCHO,
 MA.LARGO,
 MA.UNIDAD_VENTA,
 MA.UNIDAD_COMPRA,
 MA.PAQUETE,
 MA.UNITARIO
  from MS_ARTICULOS MA, MS_FAMILIA MF
  where MA.MS_FAMILIA_ID = MF.ID
  AND MA.ESTATUS=0
  ORDER BY MF.DESCRIPCION, MA.NOMBRE_ARTICULO";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $arreglo1[$index]['FAMILIA']    = utf8_decode($row->FAMILIA);
        $arreglo1[$index]['NOMBRE']     = utf8_decode($row->NOMBRE);
        
        if($row->UNITARIO == 1)
            $arreglo1[$index]['MINIMO'] = ($row->CANTIDAD_MINIMA / $row->PAQUETE);
        else
        {
            if(floatval($row->ANCHO) == 0 || floatval($row->LARGO) == 0)
                $arreglo1[$index]['MINIMO'] = 0;
            else
                $arreglo1[$index]['MINIMO'] = ($row->CANTIDAD_MINIMA / ($row->ANCHO));
        }

        $arreglo1[$index]['ANCHO']          = $row->ANCHO;
        $arreglo1[$index]['LARGO']          = $row->LARGO;
        $arreglo1[$index]['UNIDAD_VENTA']   = $row->UNIDAD_VENTA;
        $arreglo1[$index]['UNIDAD_COMPRA']  = $row->UNIDAD_COMPRA;
        $arreglo1[$index]['PAQUETE']        = $row->PAQUETE;
     }

    $conexion = null;
    return array("data" => $arreglo1);
        
}
?>