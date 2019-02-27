<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$arreglo1 = ver_cotizaciones(1, $_GET['tipo']);
$arreglo2 = ver_cotizaciones(2, $_GET['tipo']);

$arreglo3 = array_merge($arreglo1, $arreglo2);
//var_dump($arreglo1);
// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Close Sales")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Close Sales")
    ->setKeywords("reporte de close sales")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Close Sales";
$titulosColumnas = array('FOLIO', 'CLIENTE', 'DESCRIPCIÓN', 'FECHA DE COTIZACIÓN', 'FECHA DE ACTUALIZACION', "MONTO TOTAL", "OPERADOR", "ESTATUS");


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

//Se agregan los datos de los alumnos
$i = 4;


foreach($arreglo3 as $key => $value)
{
    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['EMPRESA'])
        ->setCellValue('B'.$i,  $value['NOMBRE'])
        ->setCellValue('C'.$i,  $value['DESCRIPCION'])
        ->setCellValue('D'.$i,  $value['FECHA'])
        ->setCellValue('E'.$i,  $value['MODIFICADO_AL'])
        ->setCellValue('F'.$i,  $value['IMPORTE'])
        ->setCellValue('G'.$i,  $value['OPERADOR'])
        ->setCellValue('H'.$i,  $value['ESTATUS_SEGUIMIENTO']);
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

$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);


for($i = 'A'; $i <= 'H'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('CloseSales');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteCS.xlsx"');
//header('Content-Disposition: attachment;filename="Reportedealumnos.xlsx"');
//header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
?>

<?php
function ver_cotizaciones($empresa, $filtroestatus = 0)
{
    $conexion = new conexion_nexos($empresa);

    /*if($filtroestatus == 0 || $filtroestatus == "")
        $condicionales.="  AND (SEGUIMIENTOCOTIZACION.IDESTATUS IS NULL OR SEGUIMIENTOCOTIZACION.IDESTATUS=0) ";
    else
        $condicionales.=" AND SEGUIMIENTOCOTIZACION.IDESTATUS=".$filtroestatus;
*/
    $candado = " AND DOCTOS_VE.FECHA>='2016-01-01'";
    $condicionales .= " AND  DOCTOS_VE.TIPO_DOCTO='C' AND DOCTOS_VE.ESTATUS='P' ".$candado;

    $query1 = "select 
             DOCTOS_VE.DOCTO_VE_ID,
             DOCTOS_VE.ESTATUS,
             DOCTOS_VE.IMPORTE_NETO,
             DOCTOS_VE.TOTAL_IMPUESTOS,
             DOCTOS_VE.FOLIO,
             SEGUIMIENTOCOTIZACION.MODIFICADO_AL,
             DOCTOS_VE.FECHA, 
             CLIENTES.NOMBRE, 
             DOCTOS_VE.DESCRIPCION, 
             DOCTOS_VE.TIPO_DOCTO,
             OPERADOR.ALIAS,
             SEGUIMIENTOCOTIZACION.IDESTATUS 
             from 
             DOCTOS_VE
             LEFT JOIN SEGUIMIENTOCOTIZACION ON SEGUIMIENTOCOTIZACION.DOCTO_VE_ID = DOCTOS_VE.DOCTO_VE_ID
             LEFT JOIN OPERADOR ON OPERADOR.ID = SEGUIMIENTOCOTIZACION.IDOPERADOR,
             CLIENTES
             WHERE CLIENTES.CLIENTE_ID = DOCTOS_VE.CLIENTE_ID
             ".$candado." ".$condicionales." ORDER BY DOCTOS_VE.FECHA DESC";

    $result = ibase_query($conexion->getConexion(), $query1) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $arreglo1[] = $row;
    }
    
    $arreglo_final = array();
    foreach ($arreglo1 as $key=> $value) {
        $indice = count($arreglo_final);
        
        if($empresa == 1)
            $arreglo_final[$indice]['EMPRESA'] = "NX-".intval($value->FOLIO);
        else if($empresa == 2)
            $arreglo_final[$indice]['EMPRESA'] = "NP-".intval($value->FOLIO);

        //$arreglo_final[$indice]['ESTATUS'] = $value->ESTATUS;
        $arreglo_final[$indice]['IMPORTE'] = $value->IMPORTE_NETO + $value->TOTAL_IMPUESTOS;
         
        $arreglo_final[$indice]['MODIFICADO_AL'] = $value->MODIFICADO_AL;
        $arreglo_final[$indice]['FECHA'] = $value->FECHA;
        $arreglo_final[$indice]['NOMBRE'] = utf8_encode($value->NOMBRE);
        $arreglo_final[$indice]['DESCRIPCION'] = utf8_encode($value->DESCRIPCION);
        //$arreglo_final[$indice]['TIPO'] = $value->TIPO_DOCTO;
        if($value->ALIAS)
            $arreglo_final[$indice]['OPERADOR'] = $value->ALIAS;
            else
            $arreglo_final[$indice]['OPERADOR'] = "";
        
        $arreglo_final[$indice]['ESTATUS_SEGUIMIENTO'] = "PENDIENTE";
        if($value->IDESTATUS || $value->IDESTATUS == 0)
        {
            if($value->IDESTATUS == 1)
                $arreglo_final[$indice]['ESTATUS_SEGUIMIENTO'] = "CANCELADO";
            else if($value->IDESTATUS == 2)
                $arreglo_final[$indice]['ESTATUS_SEGUIMIENTO'] = "EN GESTION";
            else if($value->IDESTATUS == 3)
                $arreglo_final[$indice]['ESTATUS_SEGUIMIENTO'] = "AUTORIZADO";
        
        }
    }
    return $arreglo_final;
}
?>