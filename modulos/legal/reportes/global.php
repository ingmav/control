<?php

include("../../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$arreglo2 = ver_facturas(2, $_POST['desde'], $_POST['hasta']);

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Facturas")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte Facturas")
    ->setKeywords("reporte Facturas")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Global de Facturas";
$titulosColumnas = array('FOLIO', 'FECHA', 'CLAVE CLIENTE', 'NOMBRE CLIENTE', 'DESCRIPCION', "IMPORTE", "IVA", "TOTAL", "ESTATUS", 'MÓDULO');


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


/*Fin de arreglo de rango de fechas*/

$i = 4;
foreach($arreglo2 as $key => $value)
{
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $value['FECHA'])
        ->setCellValue('C'.$i,  $value['CLAVE_CLIENTE'])
        ->setCellValue('D'.$i,  $value['CLIENTE'])
        ->setCellValue('E'.$i,  $value['DESCRIPCION'])
        ->setCellValue('F'.$i,  $value['IMPORTE_NETO'])
        ->setCellValue('G'.$i,  $value['IVA'])
        ->setCellValue('H'.$i,  $value['TOTAL'])
        ->setCellValue('I'.$i,  $value['ESTATUS'])
        ->setCellValue('J'.$i,  $value['VENTAS']);

        $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
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

for($i = 'A'; $i <= 'J'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Facturación Global');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre

$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteGlobalFacturas.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php


function ver_facturas($empresa, $inicio, $fin)
{
    $max_date = 0;
    $array_facturas_problematicas = array();
    $conexion = new conexion_nexos($empresa);

    $query5 = "select folio, fecha, clave_cliente, cliente, descripcion, importe_neto, total_impuestos, total, estatus, ventas from
(select
dv.folio,
dv.fecha,
dv.clave_cliente,
c.nombre  as cliente,
dv.descripcion,
dv.importe_neto,
dv.total_impuestos,
(dv.importe_neto + dv.total_impuestos) as total,
IIF(dv.estatus='C', 'CANCELADO', 'VIGENTE') as estatus,
'ADMINISTRACION' as ventas
 from doctos_ve dv, clientes c
where
dv.cliente_id=c.cliente_id
and dv.tipo_docto='F'
and dv.fecha between '".$inicio."' and '".$fin."'
UNION ALL
select
dv.folio,
dv.fecha,
dv.clave_cliente,
c.nombre as cliente,
dv.descripcion,
dv.importe_neto,
dv.total_impuestos,
(dv.importe_neto + dv.total_impuestos) as total,
IIF(dv.estatus='C', 'CANCELADO', 'VIGENTE') as estatus,
'MOSTRADOR' as ventas
 from doctos_pv dv, clientes c
where
dv.cliente_id=c.cliente_id
and dv.tipo_docto='F'
and dv.fecha between '".$inicio."' and '".$fin."'
)
order by folio desc";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("FOLIO"=>$row5->FOLIO, 
                            "FECHA"=>$row5->FECHA,
                            "CLAVE_CLIENTE"=>$row5->CLAVE_CLIENTE, 
                            "CLIENTE"=>utf8_encode($row5->CLIENTE), 
                            "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), 
                            "IMPORTE_NETO"=>$row5->IMPORTE_NETO, 
                            "IVA"=>$row5->TOTAL_IMPUESTOS, 
                            "TOTAL"=>$row5->TOTAL, 
                            "ESTATUS"=>$row5->ESTATUS, 
                            "VENTAS"=>$row5->VENTAS);
    }

    

    $conexion = null;
    return $arreglo5;
        
}


?>