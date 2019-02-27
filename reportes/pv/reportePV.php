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
    ->setTitle("Reporte Mostrador")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Mostrador")
    ->setKeywords("reporte de mostrador")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Mostrador";
$titulosColumnas = array("FOLIO", "ACTIVADO", "FECHA", "CLIENTES / MATERIALES", "MONTO", "OPERADOR");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:F1');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte)
    ->setCellValue('A3',  $titulosColumnas[0])
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4])
    ->setCellValue('F3',  $titulosColumnas[5]);


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
       $value['NOMBRE_CLIENTE'].= "\n".$value2['NOMBRE']." (".(Float)round($value2['UNIDADES'],2).")";
    }
    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  "A-".intval(substr($value['FOLIO'],1)))
        ->setCellValue('B'.$i,  $activado)
        ->setCellValue('C'.$i,  $value['FECHA'])
        ->setCellValue('D'.$i,  $value['NOMBRE_CLIENTE'])
        ->setCellValue('E'.$i,  $value['IMPORTE_NETO'])
        ->setCellValue('F'.$i,  $value['NOMBRE_OPERADOR']);

        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setWrapText(true);    
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

$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

for($i = 'A'; $i <= 'F'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('MOSTRADOR PROCESOS');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
$j = 1;

///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteMostrador.xlsx"');


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
    date_default_timezone_set('America/Mexico_City');
    
    $conection = new conexion_nexos(2);


    $candado = "";

    $campos = array("DOCTOS_PV.DOCTO_PV_ID",
                    "DOCTOS_PV.FOLIO",
                    "DOCTOS_PV.FECHA",
                    "CLIENTES.NOMBRE",
                    "DOCTOS_PV.IMPORTE_NETO",
                    "DOCTOS_PV.TOTAL_IMPUESTOS",
                    "OPERADOR.ALIAS",
                    "PRODUCCIONPV.IDESTATUS",
                    "PRODUCCIONPV.DESCRIPCION",
                    "PRODUCCIONPV.ACTIVACION"
                    );
    
    $join = array(
                  "PRODUCCIONPV", "=", "PRODUCCIONPV.DOCTO_PV_ID", "DOCTOS_PV.DOCTO_PV_ID", "LEFT",
                  "OPERADORDEPARTAMENTO","=", "PRODUCCIONPV.IDOPERADOR", "OPERADORDEPARTAMENTO.ID", "LEFT",
                  "OPERADOR","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR", "LEFT",
                  "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_PV.CLIENTE_ID", "LEFT",
                  );

    $condicionales = "";
    $condicionales2 = "";
    $order = array();
    
    $condicionales = " DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' ";

    $condicionales2 .= " AND (PRODUCCIONPV.IDESTATUS IS NULL OR PRODUCCIONPV.IDESTATUS=1 )";

    $condicionales.= $condicionales2;

    
    $query = "select
    DOCTOS_PV.DOCTO_PV_ID,
    DOCTOS_PV.FOLIO,
    DOCTOS_PV.FECHA,
    (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
    DOCTOS_PV.IMPORTE_NETO,
    DOCTOS_PV.TOTAL_IMPUESTOS,
    (SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = OPERADORDEPARTAMENTO.IDOPERADOR) AS NOMBRE_OPERADOR,
    PRODUCCIONPV.IDESTATUS,
    PRODUCCIONPV.DESCRIPCION,
    PRODUCCIONPV.ACTIVACION
    from DOCTOS_PV
    LEFT JOIN PRODUCCIONPV ON PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
    LEFT JOIN OPERADORDEPARTAMENTO ON PRODUCCIONPV.IDOPERADOR = OPERADORDEPARTAMENTO.ID
    WHERE ".$condicionales;
    
    $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
    $json = array();
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $indice = count($json);
        $json[$indice]['ID']                = $row->DOCTO_PV_ID;
        $json[$indice]['FOLIO']             = $row->FOLIO;
        $json[$indice]['FECHA']             = $row->FECHA;
        $json[$indice]['NOMBRE_CLIENTE']    = utf8_encode($row->NOMBRE_CLIENTE);
        $json[$indice]['IMPORTE_NETO']      = $row->IMPORTE_NETO + $row->TOTAL_IMPUESTOS;
        $json[$indice]['NOMBRE_OPERADOR']   = ($row->NOMBRE_OPERADOR != null) ? $row->NOMBRE_OPERADOR : '';
        $json[$indice]['IDESTATUS']         = $row->IDESTATUS;
        $json[$indice]['DESCRIPCION']       = utf8_encode($row->DESCRIPCION);
        $json[$indice]['ACTIVACION']        = $row->ACTIVACION;
    }

    
    //$json = $conection->select_table_advanced($campos, "DOCTOS_PV", $join, $condicionales, $order, 0);
    
    $index = 0;
    $json_selected = array();
    while($index < count($json))
    {
        $campos2 = array("NOMBRE",
            "UNIDADES"
        );

        $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
                        "CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

        
        $order2 = array();
        $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json[$index]['ID'];
        /*$condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149) 
                             AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05')";*/
        
        /*$condicionales2 .= " AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149) 
                             OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05'))";*/
        //echo $condicionales;
        $json2 = $conection->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

        if(count($json2) > 0)
        {

            $indice_selected = count($json_selected);
            $json_selected[$indice_selected] = $json[$index];   
            $json_selected[$indice_selected]['MATERIALES'] = $json2;    
            
            //Observaciones
            
            $join_observacion = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "PVOBSERVACION.DOCTO_PV_ID");
            
            $condicionales_observacion = " AND PVOBSERVACION.DOCTO_PV_ID=".$json[$index]['ID'];

            $json_observacion = $conection->counter("PVOBSERVACION", $join_observacion, $condicionales_observacion, 0);

            $json_selected[$indice_selected]['OBSERVACIONES'] = $json_observacion->PAGINADOR;   
        }
        //Fin observaciones
        $index++;
    }

    $conection = null;  
    return $json_selected;
        
    
}


?>