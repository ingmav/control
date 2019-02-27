<?php

include("../../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$arreglo2 = ver_facturas($_POST['al'], $_POST['tipo']);

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Mesual")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte Mensual")
    ->setKeywords("reporte Mensual")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Mensual de Facturas al ".$_POST['al'];
$titulosColumnas = array('FOLIO', 'FECHA', 'VENCIMIENTO', 'NOMBRE CLIENTE', 'TIPO', "IMPORTE", "IVA", "TOTAL", "PAGO", "ADEUDO");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:I1');

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
        ->setCellValue('C'.$i,  $value['FECHA_VENCIMIENTO'])
        ->setCellValue('D'.$i,  $value['NOMBRE'])
        ->setCellValue('E'.$i,  $value['TIPO'])
        ->setCellValue('F'.$i,  $value['IMPORTE_NETO'])
        ->setCellValue('G'.$i,  $value['IVA'])
        ->setCellValue('H'.$i,  $value['TOTAL'])
        ->setCellValue('I'.$i,  $value['PAGO'])
        ->setCellValue('J'.$i,  $value['ADEUDO']);

        
        $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
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
$objPHPExcel->getActiveSheet()->setTitle('Facturación Mensual');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre

$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteMensualFacturas.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php


function ver_facturas($al, $filtro)
{
    $max_date = 0;
    $array_facturas_problematicas = array();
    $conexion = new conexion_nexos(2);

    $filtro_ventas = "";
    $filtro_notas  = "";

    if($filtro == 1)
    {
        $filtro_notas = " and 1=0";
        $filtro_ventas = " and dv.cliente_id!=1714 ";
    }
    $query5 = "select cliente_id, id, folio, fecha, nombre, tipo,fecha_vencimiento, importe_neto, impuesto, importe,  pago, adeudo from
    (
    select
    dv.cliente_id,
    dv.docto_ve_id as id,
    dv.folio,
    dv.fecha,
    c.nombre,
    'VENTA' AS tipo,
    vcc.fecha_vencimiento,
    idc1.importe as importe_neto,
    idc1.impuesto as impuesto,
    (sum(idc1.importe + idc1.impuesto) / count(*)) as importe,
    IIF(sum(idc2.importe) IS null, 0, sum(idc2.importe))   as pago ,
    ((sum(idc1.importe + idc1.impuesto) / count(*)) - IIF(sum(idc2.importe) IS null, 0, sum(idc2.importe))) as adeudo
     from doctos_ve dv, clientes c, doctos_entre_sis des, doctos_cc dc
    left join importes_doctos_cc idc1 on idc1.docto_cc_id=dc.docto_cc_id and idc1.tipo_impte='C' and idc1.estatus='N'
    left join importes_doctos_cc idc2 on idc1.docto_cc_acr_id=idc2.docto_cc_acr_id and idc2.tipo_impte='R' and idc2.estatus='N' and idc2.fecha <= '".$al."' and idc2.cancelado!='S'
    , vencimientos_cargos_cc vcc
    where dv.fecha<='".$al."'
    ".$filtro_ventas."
    and vcc.docto_cc_id = dc.docto_cc_id
    and dv.docto_ve_id=des.docto_fte_id and des.clave_sis_fte='VE'
    and des.docto_dest_id=dc.docto_cc_id
    and dv.tipo_docto='F'
    and dv.estatus!='C'
    and dv.cliente_id=c.cliente_id
    group by dv.cliente_id, dv.docto_ve_id, dv.folio, dv.fecha,vcc.fecha_vencimiento, c.nombre, idc1.importe, idc1.impuesto
    having ((sum(idc1.importe + idc1.impuesto) / count(*)) - IIF(sum(idc2.importe) IS null, 0, sum(idc2.importe))) > 0
    
    union all
    
    select
    dc.cliente_id,
    dc.docto_cc_id as id, dc.folio, dc.fecha, c.nombre,
    'NOTA DE CARGO' as tipo,
    vcc.fecha_vencimiento,
    idc1.importe as importe_neto,
    idc1.impuesto as impuesto,
    (sum(idc1.importe + idc1.impuesto) / count(*)) as importe,
    IIF(sum(idc2.importe) IS null, 0, sum(idc2.importe))   as pago ,
    ((sum(idc1.importe + idc1.impuesto) / count(*)) - IIF(sum(idc2.importe) IS null, 0, sum(idc2.importe))) as adeudo
     from doctos_cc dc
    left join importes_doctos_cc idc1 on idc1.docto_cc_id=dc.docto_cc_id and idc1.tipo_impte='C' and idc1.estatus='N'
    left join importes_doctos_cc idc2 on idc1.docto_cc_acr_id=idc2.docto_cc_acr_id and idc2.tipo_impte='R' and idc2.estatus='N' and idc2.fecha <= '".$al."' and idc2.cancelado!='S'
    , vencimientos_cargos_cc vcc
    ,clientes c
    where
    dc.fecha<='".$al."'
    ".$filtro_notas."
    and vcc.docto_cc_id = dc.docto_cc_id
    and dc.concepto_cc_id=8
    and dc.cancelado!='S'
    and dc.cliente_id=c.cliente_id
    
    group by dc.cliente_id, dc.docto_cc_id, dc.folio, dc.fecha,vcc.fecha_vencimiento, c.nombre, idc1.importe, idc1.impuesto
    having ((sum(idc1.importe + idc1.impuesto) / count(*)) - IIF(sum(idc2.importe) IS null, 0, sum(idc2.importe))) > 0)
    order by nombre, fecha";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("FOLIO"=>substr($row5->FOLIO,0,1).(int)substr($row5->FOLIO,1), 
                            "FECHA"=>$row5->FECHA,
                            "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO,
                            "NOMBRE"=>utf8_encode($row5->NOMBRE), 
                            "IMPORTE_NETO"=>$row5->IMPORTE_NETO, 
                            "IVA"=>$row5->IMPUESTO, 
                            "TOTAL"=>$row5->IMPORTE, 
                            "ADEUDO"=>$row5->ADEUDO, 
                            "PAGO"=>$row5->PAGO, 
                            "TIPO"=>$row5->TIPO);
    }

    

    $conexion = null;
    return $arreglo5;
        
}


?>