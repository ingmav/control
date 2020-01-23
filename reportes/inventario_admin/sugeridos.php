<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


$arreglo1 = ver_sugeridos();


// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Compras a Proveedores")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Compras a Proveedores")
    ->setKeywords("reporte de cuentas por cobrar")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Compras a Proveedores";
$titulosColumnas = array("FAMILIA", "ARTICULO", "INVENTARIO ACTUAL", "UNIDAD", "P. UNITARIO", "$ INVENTARIO", "SUGERIDO", "UNIDAD", "$ INVERSIÓN");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:G1');

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

$i = 4;
$index = 0;
foreach($arreglo1 as $key => $value)
{
    //print_r($value);

    foreach($value as $key2 => $value2)
    {
        $inventario = 0;
        $sugerido   = 0;
        $precio     = 0;
        if($value[$key2]['UNITARIO'] == 1)
        {
            $inventario = ($value[$key2]['INVENTARIO'] / $value[$key2]['PAQUETE']);
            if($value[$key2]['SUGERIDO'] > $value[$key2]['INVENTARIO'])
            {
                $sugerido   = (($value[$key2]['SUGERIDO'] - $value[$key2]['INVENTARIO']) / $value[$key2]['PAQUETE']);
                $precio     = round(($sugerido * $value[$key2]['MONTO_UNITARIO']),2);
            }
            else
            {
                $sugerido   = 0;
                $precio     = 0;
            }
        }else
        {
            if($value[$key2]['LARGO'] == 0)
                $value[$key2]['LARGO'] = 1;
            if($value[$key2]['ANCHO'] == 0)
                $value[$key2]['ANCHO'] = 1;  
                  
            $inventario = ($value[$key2]['INVENTARIO'] / ($value[$key2]['LARGO'] * $value[$key2]['ANCHO']));
            if($value[$key2]['SUGERIDO'] > $value[$key2]['INVENTARIO'])
            {
                $sugerido   = (($value[$key2]['SUGERIDO'] - $value[$key2]['INVENTARIO']) / ($value[$key2]['LARGO'] * $value[$key2]['ANCHO']));
                $precio     = round(($sugerido * $value[$key2]['MONTO_UNITARIO']),2);
            }
            else
            {
                $sugerido   = 0;
                $precio     = 0;
            }
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value[$key2]['FAMILIA'])
            ->setCellValue('B'.$i,  $value[$key2]['ARTICULO'])
            ->setCellValue('C'.$i,  $inventario)
            ->setCellValue('D'.$i,  $value[$key2]['UNIDAD_COMPRA'])
            ->setCellValue('E'.$i,  $value[$key2]['MONTO_UNITARIO'])
            ->setCellValue('F'.$i, ($inventario * $value[$key2]['MONTO_UNITARIO']))
            ->setCellValue('G'.$i,  $sugerido)
            ->setCellValue('H'.$i,  $value[$key2]['UNIDAD_COMPRA'])
            
            ->setCellValue('I'.$i,  $precio);

            $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
            $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
            $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
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
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloColumnas);
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

/*for($i = 'A'; $i <= 'E'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}*/

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(50);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(15);



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('INVENTARIO E INVERSIÓN SUGERIDO');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
$j = 1;


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteSugeridos.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php



function ver_sugeridos()
{
    $conection2 = new conexion_nexos(2);
    
    $query = "select
MA.ID,
MF.DESCRIPCION AS FAMILIA,
MA.NOMBRE_ARTICULO,
MA.CANTIDAD_MINIMA,
MA.UNITARIO,
AVG(MI.ANCHO) AS ANCHO,
AVG(MI.LARGO) AS LARGO,
MA.unidad_venta,
MA.unidad_compra,
MA.PAQUETE,
MA.CANTIDAD_MINIMA,
SUM(MI.CANTIDAD_RESTANTE) AS CANTIDAD_RESTANTE,
AVG(MI.PRECIO_UNITARIO) AS MONTO,
MA.ACTUALIZACION,
(select count(*) from ms_inventario where ms_articulo_id=ma.id and activo=0 AND ESTATUS_INVENTARIO=0) AS REGISTROS,
(select sum(cantidad_restante) from ms_inventario where ms_articulo_id=ma.id and activo=1 AND ESTATUS_INVENTARIO=0) AS CANTIDAD_USO,
(SELECT first 1 precio_compra from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as MONTO_METRAJE,
(SELECT first 1 precio_unitario cantidad from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as MONTO_UNITARIO,
(SELECT first 1 '( ' || ancho || ' X ' || largo || ') ' from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as DIMENSION,
(SELECT first 1 cantidad from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as DIMENSION_UNITARIO
from
MS_ARTICULOS MA
LEFT JOIN MS_INVENTARIO MI ON  MA.ID = MI.MS_ARTICULO_ID AND MI.ESTATUS_INVENTARIO=0 AND MI.TIPO!='B',
MS_FAMILIA MF

WHERE MA.ESTATUS=0
AND MA.MS_FAMILIA_ID=MF.ID
AND MF.ID NOT IN (16, 7, 15)
AND MA.EXPORTABLE = 0
".$consulta_filtro."
GROUP BY MF.DESCRIPCION, MA.NOMBRE_ARTICULO, MA.CANTIDAD_MINIMA, MA.UNITARIO, MA.ANCHO, MA.LARGO, MA.unidad_venta,  MA.unidad_compra,  MA.PAQUETE, MA.ID, MA.ACTUALIZACION
ORDER BY MF.DESCRIPCION, MA.NOMBRE_ARTICULO";
        

        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        $total_precio_unitario = 0;
        $arreglo = array();
        
        
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
            

            $indice = count($json);

            
            $json[$indice]['INVENTARIO']                = floatval($row->CANTIDAD_RESTANTE);
            $json[$indice]['ARTICULO_ID']               = $row->ID;
            $json[$indice]['PAQUETE']                   = $row->PAQUETE;
            $json[$indice]['UNITARIO']                  = $row->UNITARIO;
            $json[$indice]['UNIDAD_VENTA']              = $row->UNIDAD_VENTA;
            $json[$indice]['UNIDAD_COMPRA']             = $row->UNIDAD_COMPRA;
            $json[$indice]['CANTIDAD_USO']              = floatval($row->CANTIDAD_USO);
            $json[$indice]['ANCHO']                     = floatval($row->ANCHO);
            $json[$indice]['LARGO']                     = floatval($row->LARGO);
            $json[$indice]['MONTO_METRAJE']             = floatval($row->MONTO_METRAJE);
            $json[$indice]['MONTO_UNITARIO']            = floatval($row->MONTO_UNITARIO);
            $json[$indice]['SUGERIDO']                  = floatval($row->CANTIDAD_MINIMA);
            $json[$indice]['REGISTROS']                 = $row->REGISTROS;
            $json[$indice]['CANTIDAD_USO']              = $row->CANTIDAD_USO;
            $json[$indice]['ARTICULO']                  = utf8_encode($row->NOMBRE_ARTICULO);
            $json[$indice]['FAMILIA']                   = utf8_encode($row->FAMILIA);
            $json[$indice]['ACTUALIZACION']             = $row->ACTUALIZACION;
            $json[$indice]['CANTIDAD_MINIMA']           = $row->CANTIDAD_MINIMA;
            $json[$indice]['DIMENSION']                 = $row->DIMENSION;
            $json[$indice]['DIMENSION_UNITARIO']        = $row->DIMENSION_UNITARIO;
            //$json[$indice]['PRECIO_UNITARIO']         = $row->PRECIO_UNITARIO;    
            $json[$indice]['PRECIO_UNITARIO']           = floatval($row->MONTO);    
            

            $query_inventario_calculado = "select sum(unidades) as unidades from
    (SELECT sum(dpd.unidades) as unidades FROM doctos_pv dp, doctos_pv_det dpd, ms_relacion mr, ms_articulos ma
    where dp.docto_pv_id=dpd.docto_pv_id
    and dp.fecha_hora_creacion>ma.actualizacion
    and dpd.articulo_id=mr.articulo_id
    and mr.ms_articulo_id=ma.id
    and  MR.ms_articulo_id=".$row->ID."
    and MR.ms_tipo_baja_id=1
    and ma.estatus=0
    and dp.tipo_docto in('V')
    and dp.estatus!='C'
    union all
    SELECT sum(dvd.unidades) as unidades FROM doctos_ve dv, doctos_ve_det dvd, ms_relacion mr, ms_articulos ma
    where dv.docto_ve_id=dvd.docto_ve_id
    and dv.fecha_hora_creacion>ma.actualizacion
    and dvd.articulo_id=mr.articulo_id
    and mr.ms_articulo_id=ma.id
    and  MR.ms_articulo_id=".$row->ID."
    and MR.ms_tipo_baja_id=1
    and ma.estatus=0
    and dv.tipo_docto in('F')
    and dv.estatus!='C' ) x";
            
            $result_calculado = ibase_query($conection2->getConexion(), $query_inventario_calculado) or die(ibase_errmsg());
            
            while ($row_calculado = ibase_fetch_object ($result_calculado, IBASE_TEXT)){
                $json[$indice]['INVENTARIO'] -= $row_calculado->UNIDADES;
                
            }
        }
        $count = 0;
        
        $j = 1;

        
        
        $conection1 = null;
        $conection2 = null;
        return array("data" => $json);
            
}
?>