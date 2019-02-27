<?php

include("../../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Tablero de procesos de mostrador")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Tablero de procesos de mostrador")
    ->setKeywords("reporte de tablero de procesos de mostrador")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Tablero de Procesos de Mostrador";
$titulosColumnas = array('FOLIO', 'FECHA', 'CLIENTE / DESCRIPCIÓN', 'DISEÑO', 'IMPRESIÓN', "PREPARACIÓN", "INSTALACIÓN", "ENTREGA");


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
$arreglo = datos();

/*Fin de arreglo de rango de fechas*/
foreach($arreglo as $key => $value)
{

    $texto_descripcion = $value['NOMBRE_CLIENTE'];

    $texto_descripcion .= $value['DESCRIPCION'];

    foreach ($value['MATERIALES'] as $key2 => $value2) {
        $texto_descripcion .= "\n".$value2['NOMBRE']." (".(Float)round($value2['UNIDADES'],2).")";
    }
     
     $diseno = "";
     $impresion = "";
     $preparacion = "";
     $instalacion = "";
     $entrega =  "";   
    if($value['GF_DISENO'] == 1)
    {
        $diseno = "NO";
        if($value['DISENO_GF'] == 2)
            $diseno = "SI";
    }
    if($value['GF_IMPRESION'] == 1)
    {
        $impresion = "NO";
        if($value['IMPRESION_GF'] == 2)
            $impresion = "SI";
    }

    if($value['GF_PREPARACION'] == 1)
    {
        $preparacion = "NO";
        if($value['PREPARACION_GF'] == 2)
            $preparacion = "SI";
    }

    if($value['GF_INSTALACION'] == 1)
    {
        $instalacion = "NO";
        if($value['INSTALACION_GF'] == 2)
            $instalacion = "SI";
    }

    if($value['GF_ENTREGA'] == 1)
    {
        $entrega = "NO";
        if($value['ENTREGA_GF'] == 2)
            $entrega = "SI";
    }
        
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $value['FECHA'])
        ->setCellValue('C'.$i,  $texto_descripcion)
        ->setCellValue('D'.$i,  $diseno)
        ->setCellValue('E'.$i,  $impresion)
        ->setCellValue('F'.$i,  $preparacion)
        ->setCellValue('G'.$i,  $instalacion)
        ->setCellValue('H'.$i,  $entrega);

    $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setWrapText(true);     
   
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
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

for($i = 'A'; $i <= 'H'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('REPORTE TABLERO DE MOSTRADOR');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

/*REmisiones Canceladas*/



///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Reporte_TAB_MOS.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////

function datos()
{
    $conection = new conexion_nexos(2);

        $query = "select
        PRODUCCIONPV.DOCTO_PV_ID,
        PRODUCCIONPV.DOCTO_PV_DET_ID,
        DOCTOS_PV.FOLIO,
        DOCTOS_PV.FECHA,
        (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
        DOCTOS_PV.DESCRIPCION,
        PRODUCCIONPV.GF_DISENO,
        PRODUCCIONPV.DISENO_GF,
        PRODUCCIONPV.GF_IMPRESION,
        PRODUCCIONPV.IMPRESION_GF,
        PRODUCCIONPV.GF_PREPARACION,
        PRODUCCIONPV.PREPARACION_GF,
        PRODUCCIONPV.GF_ENTREGA,
        PRODUCCIONPV.ENTREGA_GF,
        PRODUCCIONPV.GF_INSTALACION,
        PRODUCCIONPV.INSTALACION_GF,
        ((PRODUCCIONPV.GF_DISENO + PRODUCCIONPV.GF_IMPRESION + PRODUCCIONPV.GF_PREPARACION + PRODUCCIONPV.GF_ENTREGA + PRODUCCIONPV.GF_INSTALACION) - ((PRODUCCIONPV.DISENO_GF + PRODUCCIONPV.IMPRESION_GF + PRODUCCIONPV.PREPARACION_GF + PRODUCCIONPV.ENTREGA_GF + PRODUCCIONPV.INSTALACION_GF) / 2)) AS SUMA_PROCESOS
        from DOCTOS_PV
        INNER JOIN PRODUCCIONPV ON PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND (PRODUCCIONPV.FINALIZAR_PROCESO=0) order by DOCTOS_PV.FOLIO, DOCTOS_PV.FECHA";
        
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
            $indice = count($json_mostrador);
            $json_mostrador[$indice]['ID']              = $row->DOCTO_PV_ID;
            $json_mostrador[$indice]['ID_DET']          = "1_".$row->DOCTO_PV_DET_ID;
            $json_mostrador[$indice]['FOLIO']           = "A".(int)substr($row->FOLIO,1);
            $json_mostrador[$indice]['FECHA']           = $row->FECHA;
            $json_mostrador[$indice]['NOMBRE_CLIENTE']  = utf8_encode($row->NOMBRE_CLIENTE);
            $json_mostrador[$indice]['DESCRIPCION']     = utf8_encode($row->DESCRIPCION);
            $json_mostrador[$indice]['EMPRESA']         = 3;
            $json_mostrador[$indice]['GF_DISENO']       = $row->GF_DISENO;
            $json_mostrador[$indice]['DISENO_GF']       = $row->DISENO_GF;
            $json_mostrador[$indice]['GF_IMPRESION']    = $row->GF_IMPRESION;
            $json_mostrador[$indice]['IMPRESION_GF']    = $row->IMPRESION_GF;
            $json_mostrador[$indice]['GF_PREPARACION']  = $row->GF_PREPARACION;
            $json_mostrador[$indice]['PREPARACION_GF']  = $row->PREPARACION_GF;
            $json_mostrador[$indice]['GF_ENTREGA']      = $row->GF_ENTREGA;
            $json_mostrador[$indice]['ENTREGA_GF']      = $row->ENTREGA_GF;
            $json_mostrador[$indice]['GF_INSTALACION']  = $row->GF_INSTALACION;
            $json_mostrador[$indice]['INSTALACION_GF']  = $row->INSTALACION_GF;
            $json_mostrador[$indice]['PROCESOS']        = $row->SUMA_PROCESOS;


        }

        $index = 0;
        $json_selected = array();
        while($index < count($json_mostrador))
        {
            $campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
                            "CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json_mostrador[$index]['ID'];
            $condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149, 2143) 
                                 AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12')";
            
            //echo $condicionales;
            $json2 = $conection->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

            if(count($json2) > 0)
            {

                $indice_selected = count($json);
                $json[$indice_selected] = $json_mostrador[$index];  
                $json[$indice_selected]['MATERIALES'] = $json2; 
              
            }
            //Fin observaciones
            $index++;
        }
        
        $query = "select
        PRODUCCION_DG.DOCTO_PV_ID,
        PRODUCCION_DG.DOCTO_PV_DET_ID,
        DOCTOS_PV.FOLIO,
        DOCTOS_PV.FECHA,
        (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
        DOCTOS_PV.DESCRIPCION,
        PRODUCCION_DG.GF_DISENO,
        PRODUCCION_DG.DISENO_GF,
        PRODUCCION_DG.GF_IMPRESION,
        PRODUCCION_DG.IMPRESION_GF,
        PRODUCCION_DG.GF_PREPARACION,
        PRODUCCION_DG.PREPARACION_GF,
        PRODUCCION_DG.GF_ENTREGA,
        PRODUCCION_DG.ENTREGA_GF,
        PRODUCCION_DG.GF_INSTALACION,
        PRODUCCION_DG.INSTALACION_GF,
        ((PRODUCCION_DG.GF_DISENO + PRODUCCION_DG.GF_IMPRESION + PRODUCCION_DG.GF_PREPARACION + PRODUCCION_DG.GF_ENTREGA + PRODUCCION_DG.GF_INSTALACION) - ((PRODUCCION_DG.DISENO_GF + PRODUCCION_DG.IMPRESION_GF + PRODUCCION_DG.PREPARACION_GF + PRODUCCION_DG.ENTREGA_GF + PRODUCCION_DG.INSTALACION_GF) / 2)) AS SUMA_PROCESOS
        from DOCTOS_PV
        INNER JOIN PRODUCCION_DG ON PRODUCCION_DG.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND (PRODUCCION_DG.FINALIZAR_PROCESO=0) order by DOCTOS_PV.FOLIO,DOCTOS_PV.FECHA";
        
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
            $indice = count($json_mostrador);
            $json_mostrador[$indice]['ID']              = $row->DOCTO_PV_ID;
            $json_mostrador[$indice]['ID_DET']          = "2_".$row->DOCTO_PV_DET_ID;
            $json_mostrador[$indice]['FOLIO']           = "A".(int)substr($row->FOLIO,1);
            $json_mostrador[$indice]['FECHA']           = $row->FECHA;
            $json_mostrador[$indice]['NOMBRE_CLIENTE']  = utf8_encode($row->NOMBRE_CLIENTE);
            $json_mostrador[$indice]['DESCRIPCION']     = utf8_encode($row->DESCRIPCION);
            $json_mostrador[$indice]['EMPRESA']         = 3;
            $json_mostrador[$indice]['GF_DISENO']       = $row->GF_DISENO;
            $json_mostrador[$indice]['DISENO_GF']       = $row->DISENO_GF;
            $json_mostrador[$indice]['GF_IMPRESION']    = $row->GF_IMPRESION;
            $json_mostrador[$indice]['IMPRESION_GF']    = $row->IMPRESION_GF;
            $json_mostrador[$indice]['GF_PREPARACION']  = $row->GF_PREPARACION;
            $json_mostrador[$indice]['PREPARACION_GF']  = $row->PREPARACION_GF;
            $json_mostrador[$indice]['GF_ENTREGA']      = $row->GF_ENTREGA;
            $json_mostrador[$indice]['ENTREGA_GF']      = $row->ENTREGA_GF;
            $json_mostrador[$indice]['GF_INSTALACION']  = $row->GF_INSTALACION;
            $json_mostrador[$indice]['INSTALACION_GF']  = $row->INSTALACION_GF;
            $json_mostrador[$indice]['PROCESOS']        = $row->SUMA_PROCESOS;


        }

        $index = 0;
        $json_selected = array();
        while($index < count($json_mostrador))
        {
            $campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
                            "CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json_mostrador[$index]['ID'];
            $condicionales2 .= " AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149, 2143) 
                                 OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12'))";
            
            //echo $condicionales;
            $json2 = $conection->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

            if(count($json2) > 0)
            {

                $indice_selected = count($json);
                $json[$indice_selected] = $json_mostrador[$index];  
                $json[$indice_selected]['MATERIALES'] = $json2; 
              
            }
            //Fin observaciones
            $index++;
        }

        $contador = count($json);
        for($i = 0; $i < $contador; $i++)
        {
            $j = ($i + 1);
            for(; $j < $contador; $j++)
            {
                if($json[$i]['FECHA'] > $json[$j]['FECHA'])
                {
                    
                    $arrayAuxiliar[0] = $json[$i];
                    $json[$i] = $json[$j];  
                    $json[$j] = $arrayAuxiliar[0];
                }
            }
        }
        $conection = null;

        return $json;
}
?>
