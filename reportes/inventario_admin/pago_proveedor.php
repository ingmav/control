<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$inicio = $_GET['fecha_factura_inicio'];
$fin = $_GET['fecha_factura_fin'];

if($inicio=="" || $fin == "")
{
    echo "DEBE DE INTRODUCIR FECHA DE INICIO Y FIN, POR FAVOR";
    exit;
}
$arreglo1 = ver_pagos_proveedor($inicio, $fin);

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Pagos a Proveedores")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Pagos a Proveedores")
    ->setKeywords("reporte de cuentas por pagar")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Pagos a Proveedores";
$titulosColumnas = array("FACTURA", "FECHA COMPRA", "FECHA PAGO", "PROVEEDOR", "ARTICULOS", "MONTO", "DESCUENTO", "MONTO TOTAL", "PAGADO");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:I1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte)
    ->setCellValue('A2',"PERIODO:".$inicio." A ".$fin)
    ->setCellValue('A4',  $titulosColumnas[0])
    ->setCellValue('B4',  $titulosColumnas[1])
    ->setCellValue('C4',  $titulosColumnas[2])
    ->setCellValue('D4',  $titulosColumnas[3])
    ->setCellValue('E4',  $titulosColumnas[4])
    ->setCellValue('F4',  $titulosColumnas[5])
    ->setCellValue('G4',  $titulosColumnas[6])
    ->setCellValue('H4',  $titulosColumnas[7])
    ->setCellValue('I4',  $titulosColumnas[8]);

$i = 5;
$index = 0;

//print_r($arreglo1);

foreach($arreglo1 as $key => $value)
{
	foreach($value as $key2 => $value2)
    {
	if($value2['ID_PROVEEDOR'] == 37)
		$value2['PROVEEDOR'] = $value2['DESCRIPCION'];
    	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value2['FACTURA'])
            ->setCellValue('B'.$i,  $value2['FECHA'])
            ->setCellValue('C'.$i,  $value2['FECHA_PAGO'])
            ->setCellValue('D'.$i,  $value2['PROVEEDOR'])
            ->setCellValue('E'.$i,  $value2['ARTICULOS'])
            ->setCellValue('F'.$i,  $value2['PRECIO_TOTAL'])
            ->setCellValue('G'.$i,  $value2['DESCUENTO'])
            ->setCellValue('H'.$i,  $value2['PRECIO'])
            ->setCellValue('I'.$i,  ($value2['PAGADO'] == 1? 'PAGADO':'NO PAGADO'));

            $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
		$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
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

$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray($estiloTituloColumnas);

   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(15);
   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(15);
   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(12);
   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(45);
   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(45);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(12);    
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(12);	
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(15);	



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('COMPRAS_PROVEEDOR');

$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReportePagoProveedor.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php



function ver_pagos_proveedor($inicio, $fin)
{
    $conexion = new conexion_nexos(2);

   $query = "select mp.factura, mpr.nombre, mp.fecha_factura ,  (sum(mp.monto) - sum(mp.descuento)) as monto, sum(mp.descuento) as descuento, sum(mp.monto) as monto_total, mpr.condicion_pago, mp.ms_proveedor_id,mp.descripcion, mp.pagado  
                    from ms_pagos mp,
                    ms_proveedor mpr
                    where 
                    mpr.id!='49' and
                    mp.ms_proveedor_id=mpr.id
                    and mp.fecha_factura between '$inicio' and '$fin'
                    group by mp.factura, mpr.nombre, mp.fecha_factura, mpr.condicion_pago, mp.ms_proveedor_id, mp.descripcion, mp.pagado  
        order by mp.ms_proveedor_id, MP.fecha_factura asc";
    //and mp.pagado=0
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $arreglo1[$index]['FACTURA'] =  utf8_decode($row->FACTURA);
        $arreglo1[$index]['FECHA'] = $row->FECHA_FACTURA;

        $arreglo1[$index]['PROVEEDOR'] = utf8_decode($row->NOMBRE);

        $arreglo1[$index]['DESCRIPCION'] = utf8_decode($row->DESCRIPCION);
        $arreglo1[$index]['ARTICULOS'] = "";
        $arreglo1[$index]['PRECIO'] = $row->MONTO;
	    $arreglo1[$index]['DESCUENTO'] = $row->DESCUENTO;
	    $arreglo1[$index]['PRECIO_TOTAL'] = $row->MONTO_TOTAL;
        $arreglo1[$index]['CONDICION'] = $row->CONDICION_PAGO;
        $arreglo1[$index]['ID_PROVEEDOR'] = $row->MS_PROVEEDOR_ID;
        $arreglo1[$index]['PAGADO'] = $row->PAGADO;
	    

        $date1=date_create($arreglo1[$index]['FECHA'] );
		$date1->modify("+".$row->CONDICION_PAGO." day");

	   	$arreglo1[$index]['FECHA_PAGO'] = date_format($date1, 'Y-m-d');

        $query1 = "select   
                    ma.nombre_articulo || ' (' || mf.descripcion || ')' as nombre_articulo
                    from ms_pagos mp,
                    ms_articulos ma,
                    ms_familia mf
                    where 
                    mp.ms_articulo_id = ma.id
                    and ma.ms_familia_id = mf.id
                    and mp.factura='".$row->FACTURA."' and mp.ms_proveedor_id=".$row->MS_PROVEEDOR_ID;

        $result1 = ibase_query($conexion->getConexion(), $query1) or die(ibase_errmsg());

        while ($row2 = ibase_fetch_object ($result1, IBASE_TEXT)){
            //echo utf8_decode($row2->NOMBRE_ARTICULO);
            $arreglo1[$index]['ARTICULOS'] = $arreglo1[$index]['ARTICULOS']." - ".utf8_decode($row2->NOMBRE_ARTICULO);
        }
       
		
    }
    $arreglo_aux = array(0);
        
        for($i=0; $i < count($arreglo1); $i++)
            for($j=$i+1; $j < count($arreglo1); $j++)
            {
                if($arreglo1[$i]['FECHA_PAGO'] > $arreglo1[$j]['FECHA_PAGO'])
                {
                    $arreglo_aux[0] = $arreglo1[$i];
                    $arreglo1[$i] = $arreglo1[$j];
                    $arreglo1[$j] = $arreglo_aux[0];
                }
            }

    $conexion = null;
    return array("data" => $arreglo1);
        
}
?>
