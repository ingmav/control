<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


$arreglo1 = punto_venta($_POST['fecha_inicio'], $_POST['fecha_final']);

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Insumos")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Insumos")
    ->setKeywords("reporte de Insumos")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Ventas de ".$_POST['fecha_inicio']." al ".$_POST['fecha_final'];
$titulosColumnas = array("FOLIO", "FECHA", "CLIENTE", "MATERIAL", "UNIDADES");


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


//Se agregan los datos de los alumnos
$i = 4;

/*Creación de array con rango de fechas*/
/*Fin de arreglo de rango de fechas*/

/*foreach($arreglo1 as $key => $value)
{
    foreach ($arreglo1[$key] as $key2 => $value2) {
        print_r($value2);
    }
}*/
foreach($arreglo1 as $key => $value)
{
    $total = 0;
    foreach ($arreglo1[$key] as $key2 => $value2) {
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value2['FOLIO'])
        ->setCellValue('B'.$i,  $value2['FECHA'])
        ->setCellValue('C'.$i,  $value2['NOMBRE_CLIENTE'])
        ->setCellValue('D'.$i,  $value2['NOMBRE'])
        ->setCellValue('E'.$i,  (Float)round($value2['UNIDADES'],2));
        $total += (Float)round($value2['UNIDADES'],2);
        $i++;

    }
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D'.$i,  "TOTAL")
        ->setCellValue('E'.$i,  (Float)round($total,2));
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

$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($estiloTituloColumnas);
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

for($i = 'A'; $i <= 'E'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('VENTAS');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
$j = 1;

///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteInsumos.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php


function punto_venta($inicio, $fin)
{
    $candado = "";

        $query1 = "select
            DOCTOS_VE.FOLIO AS FOLIO,
            DOCTOS_VE.TIPO_DOCTO AS TIPO_DOCTO,
            DOCTOS_VE.DOCTO_VE_ID AS DOCTO_VE_ID,
            DOCTOS_VE.FECHA AS FECHA,
            (select first 1  nombre from clientes where clientes.cliente_id=doctos_ve.cliente_id) as NOMBRE_CLIENTE,
            DOCTOS_VE_DET.UNIDADES,
            ARTICULOS.NOMBRE,
            ARTICULOS.ARTICULO_ID
            from 
            DOCTOS_VE_DET,
            DOCTOS_VE,
            ARTICULOS
            WHERE
            DOCTOS_VE_DET.ARTICULO_ID = ARTICULOS.ARTICULO_ID
            AND DOCTOS_VE.DOCTO_VE_ID = DOCTOS_VE_DET.DOCTO_VE_ID
            AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C' AND DOCTOS_VE.DOCTO_VE_ID  NOT IN (SELECT DOCTOS_VE_LIGAS.DOCTO_VE_DEST_ID FROM DOCTOS_VE_LIGAS, DOCTOS_VE WHERE DOCTOS_VE_LIGAS.DOCTO_VE_FTE_ID=DOCTOS_VE.DOCTO_VE_ID AND DOCTOS_VE.TIPO_DOCTO='R')
            and FECHA BETWEEN '".$inicio."' and '".$fin."'";
        
        $json = array();
        
        $conection2 = new conexion_nexos($_SESSION['empresa']);
        $result = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
        
        while ($row2 = ibase_fetch_object ($result, IBASE_TEXT)){

            $arreglo = array();

            
            $arreglo['DOCTO_VE_ID']       = $row2->DOCTO_VE_ID;
            $arreglo['TIPO_DOCTO']        = $row2->TIPO_DOCTO;            
            $arreglo['FOLIO']             = "NP-".$row2->TIPO_DOCTO."-".(int)$row2->FOLIO;
            $arreglo['FECHA']             = $row2->FECHA;
            $arreglo['NOMBRE_CLIENTE']    = utf8_encode($row2->NOMBRE_CLIENTE);
            $arreglo['UNIDADES']          = utf8_encode($row2->UNIDADES);
            $arreglo['NOMBRE']            = utf8_encode($row2->NOMBRE);
            $json[$row2->ARTICULO_ID][] = $arreglo;
                        

                        /*$indice = count($json);
            $json[$indice]['DOCTO_VE_ID']       = $row2->DOCTO_VE_ID;
            $json[$indice]['TIPO_DOCTO']        = $row2->TIPO_DOCTO;            
            $json[$indice]['FOLIO']             = "NP-".$row2->TIPO_DOCTO."-".(int)$row2->FOLIO;
            $json[$indice]['FECHA']             = $row2->FECHA;
            $json[$indice]['NOMBRE_CLIENTE']    = utf8_encode($row2->NOMBRE_CLIENTE);
            $json[$indice]['UNIDADES']    = utf8_encode($row2->UNIDADES);
            $json[$indice]['NOMBRE']    = utf8_encode($row2->NOMBRE);*/
            //Arreglo articulos
            /*$campos2 = array(
                "ARTICULO_ID",
                "NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID",
                            "CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");
            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_VE_DET.DOCTO_VE_ID=".$json[$indice]['DOCTO_VE_ID'];
            
            $json2 = $conection2->select_table($campos2, "DOCTOS_VE_DET", $join2, $condicionales2, $order2, 0);
            $json[$indice]['MATERIALES'] = $json2;*/

            
            
            //Termina arreglo articulos
        }

        /*Mostrador*/
        $query1 = "select
            DOCTOS_PV.FOLIO AS FOLIO,
            DOCTOS_PV.TIPO_DOCTO AS TIPO_DOCTO,
            DOCTOS_PV.DOCTO_PV_ID AS DOCTO_PV_ID,
            DOCTOS_PV.FECHA AS FECHA,
            (select first 1  nombre from clientes where clientes.cliente_id=doctos_pv.cliente_id) as NOMBRE_CLIENTE,
            DOCTOS_PV_DET.UNIDADES,
            ARTICULOS.NOMBRE,
            ARTICULOS.ARTICULO_ID
            
            from 
            DOCTOS_PV_DET,
            DOCTOS_PV,
            ARTICULOS
            WHERE
            ARTICULOS.ARTICULO_ID = DOCTOS_PV_DET.ARTICULO_ID
            AND DOCTOS_PV.DOCTO_PV_ID = DOCTOS_PV_DET.DOCTO_PV_ID
            AND DOCTOS_PV.ESTATUS!='C' 
            and DOCTOS_PV.TIPO_DOCTO='V'
            and FECHA BETWEEN '".$inicio."' and '".$fin."'";
        
        
        $result = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
        
        while ($row2 = ibase_fetch_object ($result, IBASE_TEXT)){
            $arreglo = array();

            
            $arreglo['DOCTO_VE_ID']       = $row2->DOCTO_PV_ID;
            $arreglo['TIPO_DOCTO']        = $row2->TIPO_DOCTO;            
            $arreglo['FOLIO']             = "NP-".$row2->TIPO_DOCTO."-A".(int)substr($row2->FOLIO,1);
            $arreglo['FECHA']             = $row2->FECHA;
            $arreglo['NOMBRE_CLIENTE']    = utf8_encode($row2->NOMBRE_CLIENTE);
            $arreglo['UNIDADES']          = utf8_encode($row2->UNIDADES);
            $arreglo['NOMBRE']            = utf8_encode($row2->NOMBRE);
            $json[$row2->ARTICULO_ID][] = $arreglo;

            /*$indice = count($json);
            $json[$indice]['DOCTO_VE_ID']       = $row2->DOCTO_PV_ID;
            $json[$indice]['TIPO_DOCTO']        = $row2->TIPO_DOCTO;            
            $json[$indice]['FOLIO']             = "NP-".$row2->TIPO_DOCTO."-A".(int)substr($row2->FOLIO,1);
            $json[$indice]['FECHA']             = $row2->FECHA;
            $json[$indice]['NOMBRE_CLIENTE']    = utf8_encode($row2->NOMBRE_CLIENTE);
            //Arreglo articulos
            /*$campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
                            "CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");
            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json[$indice]['DOCTO_VE_ID'];
            
            $json2 = $conection2->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);
            $json[$indice]['MATERIALES'] = $json2; */ 
            
            //Termina arreglo articulos
        }
        
        
        /*Fin Mostrador*/
        $conection = null;
        $conection2 = null;
        return $json;    
    
}


?>