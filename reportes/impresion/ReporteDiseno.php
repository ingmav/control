<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


$arreglo1 = punto_venta();

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Diseño")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Diseño")
    ->setKeywords("reporte de Diseño")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Diseño";
$titulosColumnas = array("FOLIO", "ACTIVADO", "FECHA", "CLIENTES","MATERIAL", "UNIDADES", "DESCRIPCION", "OPERADOR");


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

/*Creación de array con rango de fechas*/



/*Fin de arreglo de rango de fechas*/

foreach($arreglo1 as $key => $value)
{
    $activado = "NO";
    if($value['ACTIVACION'] == 1)
        $activado = "SI";
    foreach ($value['MATERIALES'] as $key2 => $value2) {
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $activado)
        ->setCellValue('C'.$i,  $value['FECHA'])
        ->setCellValue('D'.$i,  $value['NOMBRE_CLIENTE'])
        ->setCellValue('E'.$i,  $value2['NOMBRE'])
        ->setCellValue('F'.$i,  (Float)round($value2['UNIDADES'],2))
        ->setCellValue('G'.$i,  $value['DESCRIPCION'])
        ->setCellValue('H'.$i,  $value['NOMBRE_OPERADOR']);
        $i++;

        //$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setWrapText(true);

       //$value['NOMBRE_CLIENTE'].= "\n".$value2['NOMBRE']." (".(Float)round($value2['UNIDADES'],2).")";
    }
    
    /*$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $activado)
        ->setCellValue('C'.$i,  $value['FECHA'])
        ->setCellValue('D'.$i,  $value['NOMBRE_CLIENTE'])
        ->setCellValue('E'.$i,  $value['DESCRIPCION'])
        ->setCellValue('F'.$i,  $value['NOMBRE_OPERADOR']);*/

        
        //$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setWrapText(true);    
        //$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setWrapText(true);    
    
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
$objPHPExcel->getActiveSheet()->setTitle('IMPRESION PROCESOS');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
$j = 1;

///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteDiseno.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php


function punto_venta()
{
    $candado = "";


        $query1 = "select
            TABLEROPRODUCCION.ID AS IDTABLERO,
            TABLEROPRODUCCION.ACTIVACION,
            DOCTOS_VE.FOLIO AS FOLIO,
            DOCTOS_VE.TIPO_DOCTO AS TIPO_DOCTO,
            DOCTOS_VE.DOCTO_VE_ID AS DOCTO_VE_ID,
            TABLEROPRODUCCION.FECHA AS FECHA,
            (select first 1  nombre from clientes where clientes.cliente_id=doctos_ve.cliente_id) as NOMBRE_CLIENTE,
            (select first 1 alias from operador where operador.id=tableroproduccion.operador_impresion_gf) as NOMBRE_OPERADOR,
            doctos_ve.descripcion || ASCII_CHAR(13) || TABLEROPRODUCCION.NOTA as DESCRIPCION,
            (select count(*) from TABLEROOBSERVACION where TABLEROOBSERVACION.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND IDDEPARTAMENTO=3) as CONTADOR_MESSAGE
            from TABLEROPRODUCCION,
            DOCTOS_VE_DET,
            DOCTOS_VE
            WHERE
            DOCTOS_VE_DET.DOCTO_VE_DET_ID = TABLEROPRODUCCION.DOCTO_VE_DET_ID
            AND DOCTOS_VE.DOCTO_VE_ID = TABLEROPRODUCCION.DOCTO_VE_ID
            
            AND TABLEROPRODUCCION.GF_DISENO=1  AND TABLEROPRODUCCION.DISENO_GF!=2 

            AND DOCTOS_VE.ESTATUS!='C'";
        
        $json = array();
        
        $conection2 = new conexion_nexos($_SESSION['empresa']);
        $result = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
        
        while ($row2 = ibase_fetch_object ($result, IBASE_TEXT)){
            $indice = count($json);
            $row2->NOMBRE_OPERADOR = ($row2->NOMBRE_OPERADOR == null) ? '' : $row2->NOMBRE_OPERADOR;
            $json[$indice]['IDTABLERO']         = $row2->IDTABLERO;
            $json[$indice]['EMPRESA']           = 2;
            $json[$indice]['DOCTO_VE_ID']       = $row2->DOCTO_VE_ID;
            $json[$indice]['TIPO_DOCTO']        = $row2->TIPO_DOCTO;            
            $json[$indice]['FOLIO']             = "NP-".$row2->TIPO_DOCTO."-".(int)substr($row2->FOLIO,1);
            $json[$indice]['FECHA']             = $row2->FECHA;
            $json[$indice]['NOMBRE_CLIENTE']    = utf8_encode($row2->NOMBRE_CLIENTE);
            $json[$indice]['NOMBRE_OPERADOR']   = $row2->NOMBRE_OPERADOR;
            $json[$indice]['DESCRIPCION']       = utf8_encode($row2->DESCRIPCION);
            $json[$indice]['IDESTATUS']         = $row2->IDESTATUS;
            $json[$indice]['CONTADOR_MESSAGE']  = $row2->CONTADOR_MESSAGE;
            $json[$indice]['ACTIVACION']        = $row2->ACTIVACION;
            //Arreglo articulos
            $campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID",
                            "CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");
            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_VE_DET.DOCTO_VE_ID=".$json[$indice]['DOCTO_VE_ID'];
            
            $json2 = $conection2->select_table($campos2, "DOCTOS_VE_DET", $join2, $condicionales2, $order2, 0);
            $json[$indice]['MATERIALES'] = $json2;  
            
            //Termina arreglo articulos
        }

        $query = "select
        PRODUCCIONPV.DOCTO_PV_DET_ID,
        DOCTOS_PV.DOCTO_PV_ID,
        DOCTOS_PV.FOLIO,
        DOCTOS_PV.FECHA,
        PRODUCCIONPV.F_ENTREGA,
        (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
        DOCTOS_PV.IMPORTE_NETO,
        DOCTOS_PV.TOTAL_IMPUESTOS,
        (SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = PRODUCCIONPV.OPERADOR_DISENO_GF) AS NOMBRE_OPERADOR,
        PRODUCCIONPV.IDESTATUSIMPRESION,
        PRODUCCIONPV.NOTAS_PROCESO,
        PRODUCCIONPV.ACTIVACION,
        (select count(*) from PVOBSERVACION where DOCTOS_PV.DOCTO_PV_ID = PVOBSERVACION.DOCTO_PV_ID AND PVOBSERVACION.IDDEPARTAMENTO=2) as CONTADOR_MESSAGE
        from DOCTOS_PV,
        PRODUCCIONPV
        
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        AND PRODUCCIONPV.DISENO_GF!=2 AND PRODUCCIONPV.GF_DISENO=1 ".$consulta2;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
            $indice = count($json_mostrador);
            $json_mostrador[$indice]['IDTABLERO']       = $row->DOCTO_PV_DET_ID;
            $json_mostrador[$indice]['IDPRODUCCION']    = $row->DOCTO_PV_ID;
            $json_mostrador[$indice]['FOLIO']           = "A".(int)substr($row->FOLIO,1);
            $json_mostrador[$indice]['FECHA']           = $row->FECHA;
            $json_mostrador[$indice]['F_ENTREGA']       = $row->F_ENTREGA;
            $json_mostrador[$indice]['NOMBRE_CLIENTE']  = utf8_encode($row->NOMBRE_CLIENTE);
            $json_mostrador[$indice]['NOMBRE_OPERADOR'] = ($row->NOMBRE_OPERADOR != null) ? $row->NOMBRE_OPERADOR : '';
            $json_mostrador[$indice]['DESCRIPCION']     = ($row->NOTAS_PROCESO != null) ? $row->NOTAS_PROCESO:'';
            $json_mostrador[$indice]['IDESTATUS']       = $row->IDESTATUSIMPRESION;
            $json_mostrador[$indice]['CONTADOR_MESSAGE']= $row->CONTADOR_MESSAGE;
            $json_mostrador[$indice]['ACTIVACION']      = $row->ACTIVACION;
            $json_mostrador[$indice]['EMPRESA']         = 3;
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
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json_mostrador[$index]['IDPRODUCCION'];
            //$condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (1849,1954,6346,2048)";
            $condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149, 2143) 
                                 AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12')";
            
            //echo $condicionales;
            $json2 = $conection2->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

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

        
        
        /*Fin Mostrador*/
        $conection = null;
        $conection2 = null;
        return $json;    
    
}


?>