<?php


include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$desde = $_POST['desde_cotizacion'];
$hasta = $_POST['hasta_cotizacion'];

//Nexos Empresariales
$conection = new conexion_nexos(1);

$query = "select ESTATUS, SUM(IMPORTE_NETO) IMPORTE_NETO, COUNT(*) AS CONTADOR from DOCTOS_VE WHERE TIPO_DOCTO='C' AND FECHA BETWEEN '".$desde."' and '".$hasta."' GROUP BY ESTATUS  order by ESTATUS";

$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

$totales = array();

while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
    $totales[$row->ESTATUS]['CONTADOR'] = $row->CONTADOR;
    $totales[$row->ESTATUS]['IMPORTE']  = $row->IMPORTE_NETO;
}


//calculo de fechas
$fechas_nexos = calculo_cotizaciones($conection);//arreglo de consulta proyeccion

//Calculo de datos 
  $fecha = date('Y-m-01');
  $mes_tras_anterior  = strtotime ( '-2 month' , strtotime ( $fecha ) ) ;
  
  $ano_mes_trasanterior     = gmdate("Y",$mes_tras_anterior);
  $mes_mes_trasanterior     = gmdate("m",$mes_tras_anterior); 

  $fecha_completa = $ano_mes_trasanterior."-".$mes_mes_trasanterior."-01";
  $datos_nexos = Calculo_datos_cotizaciones($conection, $fecha_completa, "NX");
  
  $conection = null;

//Nexprint
$conection2 = new conexion_nexos($_SESSION['empresa']);
$datos_nexprint = Calculo_datos_cotizaciones($conection2, $fecha_completa, "NP");


$datos_completos_empresas = array_merge($datos_nexos, $datos_nexprint);

$fechas_nexprint = calculo_cotizaciones($conection2); //arreglo de consulta proyeccion


foreach ($fechas_nexos as $key => $value) {
  
  foreach ($fechas_nexprint as $key2 => $value2) {
    if($value['inicio'] == $value2['inicio'])
    {
      foreach ($fechas_nexos[$key]['datos'] as $key3 => $value3) {
        foreach ($fechas_nexprint[$key2]['datos'] as $key4 => $value4) {
          if($fechas_nexos[$key]['datos'][$key3]['ESTATUS'] == $fechas_nexprint[$key2]['datos'][$key4]['ESTATUS'])
          {
              $fechas_nexos[$key]['datos'][$key3]['CONTADOR'] += $fechas_nexprint[$key2]['datos'][$key4]['CONTADOR'];
              $fechas_nexos[$key]['datos'][$key3]['IMPORTE'] += $fechas_nexprint[$key2]['datos'][$key4]['IMPORTE'];
          }  
        }
      }
      
    }
  }
}

$result2 = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
    $totales[$row2->ESTATUS]['CONTADOR'] += $row2->CONTADOR;
    $totales[$row2->ESTATUS]['IMPORTE']  += $row2->IMPORTE_NETO;
}

$conection2 = null;

// Se crea el objeto PHPExcel

$objPHPExcel = new PHPExcel();
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
        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('argb' => 'FF555555')
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
    'fill'  => array(
        'type'    => PHPExcel_Style_Fill::FILL_SOLID,
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

$estiloInformacionCotizacion = array(
    'font' => array(
        'name'      => 'Arial',
        'bold'      => false,
        'size' =>9,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill'  => array(
        'type'    => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'EFEFEF'
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap'          => TRUE
    ));

$estiloInformacion = new PHPExcel_Style();
$estiloInformacionEfectividad = new PHPExcel_Style();
$estiloInformacionMes = new PHPExcel_Style();
$estiloInformacion->applyFromArray(
  array(
      'font' => array(
          'name'      => 'Arial',
          'color'     => array(
              'rgb' => '000000'
          )
      ),
      'fill'  => array(
          'type'    => PHPExcel_Style_Fill::FILL_SOLID,
          'color'   => array('argb' => 'FFFFFFFF')
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





$estiloEfectividad = array(
    'font' => array(
        'name'      => 'Verdana',
        'bold'      => false,
        'italic'    => false,
        'strike'    => false,
        'size'      =>9,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'FFFF99')
    )
    
);

$estiloInformacionMes = array(
    'font' => array(
        'name'      => 'Verdana',
        'bold'      => false,
        'italic'    => false,
        'strike'    => false,
        'size'      =>9,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('argb' => 'FFFFA8A8')
    )
    
);
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Cotizaciones")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Cotizaciones")
    ->setKeywords("reporte de Cotizaciones")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Cotizaciones del ".$desde." al ".$hasta;
$titulosColumnas = array('CONCEPTO', 'IMPORTE', 'COTIZACIONES');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',  $tituloReporte)
    ->setCellValue('A2',  $titulosColumnas[0])
    ->setCellValue('B2',  $titulosColumnas[1])
    ->setCellValue('C2',  $titulosColumnas[2]);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($estiloTituloColumnas);

for($i = 'A'; $i <= 'C'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

//Fin de arreglo de rango de fechas
$i = 3;
$iteraciones = 0;
$total_importe = 0;
$total_cantidad = 0;
$concepto = "";

$importe_cerradas = 0;
  $importe_canceladas = 0;
  
  $cantidad_cerradas = 0;
  $cantidad_canceladas = 0;

foreach($totales as $key => $value)
{
  
  if($key == "C")
  {
    $importe_canceladas += $value['IMPORTE'];
    $cantidad_canceladas += $value['CONTADOR'];
    $concepto = "CANCELADAS";
  }
  else if($key ==  "E")
  {
    $importe_cerradas += $value['IMPORTE'];
    $cantidad_cerradas += $value['CONTADOR'];
    
    $concepto = "CERRADAS";
  }
  else if($key ==  "P")
    $concepto = "PENDIENTE";

  $total_importe += $value['IMPORTE'];
  $total_cantidad += $value['CONTADOR'];
  
  $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i,  $concepto)
      ->setCellValue('B'.$i,  $value['IMPORTE'])
      ->setCellValue('C'.$i,  $value['CONTADOR']);

      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
   
    $i++; 
    $iteraciones++;


}
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  "TOTAL COTIZACIONES")
        ->setCellValue('B'.$i,  $total_importe)
        ->setCellValue('C'.$i,  $total_cantidad);

$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");

$i++;
$percent_efectividad_importe = 0;
$percent_efectividad_cantidad = 0;
$percent_cancelacion_importe = 0;
$percent_cancelacion_cantidad = 0;

if($total_cantidad > 0)
{
  $percent_efectividad_importe  = ($importe_cerradas / $total_importe);
  $percent_efectividad_cantidad = ($cantidad_cerradas / $total_cantidad );
  $percent_cancelacion_importe  = ($importe_canceladas / $total_importe);
  $percent_cancelacion_cantidad = ($cantidad_canceladas / $total_cantidad );  
}

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  "% EFETIVIDAD")
        ->setCellValue('B'.$i,  number_format(($percent_efectividad_importe * 100) , 2)." %")
        ->setCellValue('C'.$i,  number_format(($percent_efectividad_cantidad * 100), 2)." %");
$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode("#,##0.00");

 $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($estiloEfectividad);//ESTILO AMARILLO

$i++;
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  "% CANCELACIÓN")
        ->setCellValue('B'.$i,  number_format(($percent_cancelacion_importe * 100), 2)." %")
        ->setCellValue('C'.$i,  number_format(($percent_cancelacion_cantidad * 100), 2)." %");

 $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($estiloEfectividad);//ESTILO AMARILLO
$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode("#,##0.00");


//Resumen de cotizaciones
$tituloReporte_resumen = "Resumen de Cotizaciones";
$titulosColumnas_resumen = array('DESDE', 'HASTA', 'CONCEPTO', "SOLICITADAS", "CERRADAS", "%", "PENDIENTES", "%", "CANCELADAS", "%");

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('E1',  $tituloReporte_resumen)
    ->setCellValue('E2',  $titulosColumnas_resumen[0])
    ->setCellValue('F2',  $titulosColumnas_resumen[1])
    ->setCellValue('G2',  $titulosColumnas_resumen[2])
    ->setCellValue('H2',  $titulosColumnas_resumen[3])
    ->setCellValue('I2',  $titulosColumnas_resumen[4])
    ->setCellValue('J2',  $titulosColumnas_resumen[5])
    ->setCellValue('K2',  $titulosColumnas_resumen[6])
    ->setCellValue('L2',  $titulosColumnas_resumen[7])
    ->setCellValue('M2',  $titulosColumnas_resumen[8])
    ->setCellValue('N2',  $titulosColumnas_resumen[9]);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E1:N1');
$objPHPExcel->getActiveSheet()->getStyle('E1:N1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('E2:N2')->applyFromArray($estiloTituloColumnas);

for($i = 'E'; $i <= 'N'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

$i = 3;    

for($j = (count($fechas_nexos)-1); $j>=0; $j--)
{

  $total_solicitudes = 0;
  $monto_total       = 0;
  $total_pendientes  = array(0,0);
  $total_cerradas    = array(0,0);
  $total_canceladas  = array(0,0);

  foreach ($fechas_nexos[$j]['datos'] as $key => $value) {

      if($fechas_nexos[$j]['datos'][$key]['ESTATUS'] == "P")
      {
        $total_pendientes[0] = $fechas_nexos[$j]['datos'][$key]['CONTADOR'];
        $total_pendientes[1] = $fechas_nexos[$j]['datos'][$key]['IMPORTE'];
      }
      else if($fechas_nexos[$j]['datos'][$key]['ESTATUS'] == "E")
      {
        $total_cerradas[0] = $fechas_nexos[$j]['datos'][$key]['CONTADOR'];
        $total_cerradas[1] = $fechas_nexos[$j]['datos'][$key]['IMPORTE'];
      }
      else if($fechas_nexos[$j]['datos'][$key]['ESTATUS'] == "C")
      {
        $total_canceladas[0] = $fechas_nexos[$j]['datos'][$key]['CONTADOR'];
        $total_canceladas[1] = $fechas_nexos[$j]['datos'][$key]['IMPORTE'];
      }
      $total_solicitudes += $fechas_nexos[$j]['datos'][$key]['CONTADOR'];
      $monto_total       += $fechas_nexos[$j]['datos'][$key]['IMPORTE'];
  }

  if($total_solicitudes == 0)
  {
      $count_total_cerradas   = 0;
      $count_total_pendientes = 0;
      $count_total_canceladas = 0;
  }else
  {
      $count_total_cerradas   = (($total_cerradas[0] / $total_solicitudes) * 100);
      $count_total_pendientes = (($total_pendientes[0] / $total_solicitudes) * 100);
      $count_total_canceladas = (($total_canceladas[0] / $total_solicitudes) * 100);
  }

  $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E'.$i,  $fechas_nexos[$j]['inicio'])
        ->setCellValue('F'.$i,  $fechas_nexos[$j]['fin'])
        ->setCellValue('G'.$i,  "CANTIDAD")
        ->setCellValue('H'.$i,  $total_solicitudes)
        ->setCellValue('I'.$i,  $total_cerradas[0])
        ->setCellValue('J'.$i,  $count_total_cerradas)
        ->setCellValue('K'.$i,  $total_pendientes[0])
        ->setCellValue('L'.$i,  $count_total_pendientes)
        ->setCellValue('M'.$i,  $total_canceladas[0])
        ->setCellValue('N'.$i,  $count_total_canceladas);
  
   
  /*if(substr($fechas_nexos[$j]['inicio'],0,7) == date("Y-m"))
    $objPHPExcel->getActiveSheet()->getStyle('E'.$i.':N'.$i)->applyFromArray($estiloInformacionMes); 
  else*/
    $objPHPExcel->getActiveSheet()->getStyle('E'.$i.':N'.$i)->applyFromArray($estiloInformacionCotizacion); 

  $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode("#,##0.00");     
  $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode("#,##0.00");     
  $objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getNumberFormat()->setFormatCode("#,##0.00");     

   $i++;

   if($monto_total == 0)
  {
      $monto_total_cerradas   = 0;
      $monto_total_pendientes = 0;
      $monto_total_canceladas = 0;
  }else
  {
      $monto_total_cerradas   = (($total_cerradas[1] / $monto_total ) * 100);
      $monto_total_pendientes = (($total_pendientes[1] / $monto_total) * 100);
      $monto_total_canceladas = (($total_canceladas[1] / $monto_total) * 100);
  }
   $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('G'.$i,  "MONTO")
        ->setCellValue('H'.$i,  $monto_total)
        ->setCellValue('I'.$i,  $total_cerradas[1])
        ->setCellValue('J'.$i,  $monto_total_cerradas)
        ->setCellValue('K'.$i,  $total_pendientes[1])
        ->setCellValue('L'.$i,  $monto_total_pendientes)
        ->setCellValue('M'.$i,  $total_canceladas[1])     
        ->setCellValue('N'.$i,  $monto_total_canceladas);     

        

  $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
  $objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
  $i++;
}

$objPHPExcel->getActiveSheet()->setTitle('RESUMEN');

$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);

$objPHPExcel->getActiveSheet()->setTitle('DESGLOSE');
$tituloReporte = "Reporte de 2 meses hacia atrás a la fecha";
$titulosColumnas = array('FOLIO', 'FECHA', 'ESTATUS', 'NOMBRE', 'DESCRIPCIÓN', 'IMPORTE', 'IVA', 'TOTAL', 'FECHA SEGUIMIENTO', 'DESCRIPCIÓN SEGUIMIENTO');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1',  $tituloReporte)
    ->setCellValue('A2',  $titulosColumnas[0])
    ->setCellValue('B2',  $titulosColumnas[1])
    ->setCellValue('C2',  $titulosColumnas[2])
    ->setCellValue('D2',  $titulosColumnas[3])
    ->setCellValue('E2',  $titulosColumnas[4])
    ->setCellValue('F2',  $titulosColumnas[5])
    ->setCellValue('G2',  $titulosColumnas[6])
    ->setCellValue('H2',  $titulosColumnas[7])
    ->setCellValue('I2',  $titulosColumnas[8])
    ->setCellValue('J2',  $titulosColumnas[9]);

$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:I1');
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($estiloTituloColumnas);

for($i = 'A'; $i <= 'I'; $i++){
  if($i!= 'D' && $i!='E')
  {
    $objPHPExcel->setActiveSheetIndex(1)
        ->getColumnDimension($i)->setAutoSize(TRUE);
  }
}

///////////////////////////////////////////////////////// AQUI ES------
$aux = array();
foreach ($datos_completos_empresas as $key => $row) {
    $aux[$key] = $row['FECHA'];
}


$auxiliar = array();
$bandera = 1;
for($var1=0;$var1<(count($aux)-1)&&$bandera==1;$var1++)

  {

  $bandera=0;
  for($var2=0;$var2<(count($aux)-$var1-1);$var2++)
    {
 
    if($aux[$var2]>$aux[$var2+1])
      {
        $bandera=1; 
        $auxiliar = $datos_completos_empresas[$var2];
        $datos_completos_empresas[$var2] = $datos_completos_empresas[$var2 + 1];
        $datos_completos_empresas[$var2 + 1] = $auxiliar;
        
        $auxiliar = $aux[$var2];
        $aux[$var2] = $aux[$var2 + 1];
        $aux[$var2 + 1] = $auxiliar;
        
      }
    }
  }


$j = 3;
for($i = 0; $i<(count($datos_completos_empresas)-1); $i++)
{

  $ESTATUS = "";
  if("C" == $datos_completos_empresas[$i]['ESTATUS'])
  {
      $ESTATUS = "CANCELADA";
  }else if("P" == $datos_completos_empresas[$i]['ESTATUS'])
  {
    $ESTATUS = "PENDIENTE";
  }else if("E" == $datos_completos_empresas[$i]['ESTATUS'])
  {
      $ESTATUS = "CERRADA";
  }



  $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  $datos_completos_empresas[$i]['FOLIO'])
        ->setCellValue('B'.$j,  $datos_completos_empresas[$i]['FECHA'])
        ->setCellValue('C'.$j,  $ESTATUS)
        ->setCellValue('D'.$j,  utf8_encode($datos_completos_empresas[$i]['NOMBRE']))
        ->setCellValue('E'.$j,  utf8_encode($datos_completos_empresas[$i]['DESCRIPCION']))
        ->setCellValue('F'.$j,  $datos_completos_empresas[$i]['IMPORTE_NETO'])
        ->setCellValue('G'.$j,  $datos_completos_empresas[$i]['TOTAL_IMPUESTOS'])
        ->setCellValue('H'.$j,  ($datos_completos_empresas[$i]['IMPORTE_NETO'] + $datos_completos_empresas[$i]['TOTAL_IMPUESTOS']))
        ->setCellValue('I'.$j,  $datos_completos_empresas[$i]['FECHA_OBSERVACION'])
        ->setCellValue('J'.$j,  utf8_encode($datos_completos_empresas[$i]['DESCRIPCION_OBSERVACION']));
  
  //$objPHPExcel->getActiveSheet()->getStyle('E'.$i.':N'.$i)->applyFromArray($estiloInformacionCotizacion); 
  $objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode("$ #,##0.00");     
  $objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode("$ #,##0.00");     
  $objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode("$ #,##0.00");  
  $j++;   
}

$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(D)->setWidth(40);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(E)->setWidth(40);


//Final de resumen  

///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Reporte_Cotizaciones.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////




function calculo_cotizaciones($conection)
{
  $bandera = 0;
  $mes_actual = date("m");
  $lunes_anterior = inicio_semana(strtotime(date("Y-m-d\T00:00:00\Z")));
  $siguiente_domingo = $lunes_anterior + (((6*24*60*60))) ;
  $fecha_validar = $lunes_anterior;
  $fechas = array();
  $indice = count($fechas);
  $fechas [$indice]['inicio'] = gmdate("Y-m-d", $lunes_anterior);
  $fechas [$indice]['fin']    = gmdate("Y-m-d", $siguiente_domingo);
  do
  { 
    $nuevo_lunes = $fecha_validar-((7*24*60*60)) ;

    $mes_calculado =  date ( 'm' , $nuevo_lunes );

    if($mes_calculado != $mes_actual){
      $bandera = 1;
      $indice = count($fechas);
      $fechas [$indice]['inicio'] = date("Y-m-01");
      $fechas [$indice]['fin']    = gmdate("Y-m-d", $fecha_validar-((1*24*60*60))) ;
    }
    else
    {
        $indice = count($fechas);
        $fechas [$indice]['inicio'] = gmdate("Y-m-d", $nuevo_lunes);
        $fechas [$indice]['fin']    = gmdate("Y-m-d", $nuevo_lunes + (((6*24*60*60)))) ;
        $fecha_validar = $nuevo_lunes;
    }
  }while($bandera == 0);

  $fecha = date('Y-m-01');
  $mes_anterior = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
  $mes_tras_anterior  = strtotime ( '-2 month' , strtotime ( $fecha ) ) ;
  $mes_resto  = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;

  $ano_mes_anterior   = gmdate("Y",$mes_anterior); 
  $mes_mes_anterior    = gmdate("m",$mes_anterior);  

  $ano_mes_trasanterior     = gmdate("Y",$mes_tras_anterior);
  $mes_mes_trasanterior     = gmdate("m",$mes_tras_anterior); 

  $ano_resto     = gmdate("Y",$mes_resto);
  $mes_resto     = gmdate("m",$mes_resto); 

  //Añadimos los meses anteriores
  $dia_mes_anterior     = getUltimoDiaMes($ano_mes_anterior, $mes_mes_anterior);
  $dia_mes_trasanterior = getUltimoDiaMes($ano_mes_trasanterior, $mes_mes_trasanterior);
  $dia_mes_resto = getUltimoDiaMes($ano_resto, $mes_resto);

  $fecha_inicio_antetior  = $ano_mes_anterior."-".$mes_mes_anterior."-01";
  $fecha_fin_anterior     = $ano_mes_anterior."-".$mes_mes_anterior."-".$dia_mes_anterior;

  $fecha_inicio_trasantetior     = $ano_mes_trasanterior."-".$mes_mes_trasanterior."-01";
  $fecha_fin_trasantetior        = $ano_mes_trasanterior."-".$mes_mes_trasanterior."-".$dia_mes_trasanterior;

  $fecha_inicio_resto       = $ano_resto."-01-01";
  $fecha_fin_resto          = $ano_resto."-".$mes_resto."-".$dia_mes_resto;

  $fechas[] = array("inicio"=>$fecha_inicio_antetior, "fin"=>$fecha_fin_anterior);
  $fechas[] = array("inicio"=>$fecha_inicio_trasantetior, "fin"=>$fecha_fin_trasantetior);
  $fechas[] = array("inicio"=>$fecha_inicio_resto, "fin"=>$fecha_fin_resto);

  foreach ($fechas as $key => $value) {
    
    $query = "select ESTATUS, SUM(IMPORTE_NETO) IMPORTE_NETO, COUNT(*) AS CONTADOR from DOCTOS_VE WHERE TIPO_DOCTO='C' AND FECHA BETWEEN '".$value['inicio']."' and '".$value['fin']."' GROUP BY ESTATUS  order by ESTATUS";

    $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

    $totales = array();

    $datos = array();

    $datos[0] = array("ESTATUS"=>"C", "CONTADOR"=>0, "IMPORTE"=>0);
    $datos[1] = array("ESTATUS"=>"E", "CONTADOR"=>0, "IMPORTE"=>0);
    $datos[2] = array("ESTATUS"=>"P", "CONTADOR"=>0, "IMPORTE"=>0);

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
      //$indice = count($datos);
      if($row->ESTATUS == "C")
        $indice = 0;
      if($row->ESTATUS == "E")
        $indice = 1;
      if($row->ESTATUS == "P")
        $indice = 2;
  
      $datos[$indice]['ESTATUS'] = $row->ESTATUS;
      $datos[$indice]['CONTADOR'] = $row->CONTADOR;
      $datos[$indice]['IMPORTE'] = $row->IMPORTE_NETO;
    }
      $fechas[$key]['datos'] = $datos;
    
  }


  return $fechas;
}

function Calculo_datos_cotizaciones($conexion, $inicio, $empresa)
{
    
    $query = "select 
    DOCTOS_VE.FOLIO,
    DOCTOS_VE.FECHA,
    CLIENTES.NOMBRE,
    DOCTOS_VE.ESTATUS,
    DOCTOS_VE.DESCRIPCION,
    DOCTOS_VE.IMPORTE_NETO,
    DOCTOS_VE.TOTAL_IMPUESTOS,
    (select FIRST 1 observacioncotizacion.FECHA from observacioncotizacion
where observacioNCOtizacion.docto_ve_id=doctos_ve.docto_ve_id ORDER BY observacioncotizacion.FECHA DESC) as FECHA_OBSERVACION,
(select FIRST 1 observacioncotizacion.descripcion from observacioncotizacion
where observacioNCOtizacion.docto_ve_id=doctos_ve.docto_ve_id ORDER BY observacioncotizacion.FECHA DESC) as DESCRIPCION_OBSERVACION
    from DOCTOS_VE, CLIENTES WHERE 
    DOCTOS_VE.CLIENTE_ID = CLIENTES.CLIENTE_ID
    AND DOCTOS_VE.TIPO_DOCTO='C' 
    AND DOCTOS_VE.FECHA BETWEEN '".substr($inicio, 0,4)."-01-01' and '".$inicio."' and DOCTOS_VE.ESTATUS = 'P'
    
    order by DOCTOS_VE.FECHA";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $totales = array();

    $datos = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
      $indice = count($datos);
      $datos[$indice]['FOLIO']                    = $empresa."-".(int) $row->FOLIO;
      $datos[$indice]['FECHA']                    = $row->FECHA;
      $datos[$indice]['NOMBRE']                   = $row->NOMBRE;
      $datos[$indice]['ESTATUS']                  = $row->ESTATUS;
      $datos[$indice]['DESCRIPCION']              = $row->DESCRIPCION;
      $datos[$indice]['IMPORTE_NETO']             = $row->IMPORTE_NETO;
      $datos[$indice]['TOTAL_IMPUESTOS']          = $row->TOTAL_IMPUESTOS;
      $datos[$indice]['FECHA_OBSERVACION']        = $row->FECHA_OBSERVACION;
      $datos[$indice]['DESCRIPCION_OBSERVACION']  = $row->DESCRIPCION_OBSERVACION;
      
    }

    $query = "select 
    DOCTOS_VE.FOLIO,
    DOCTOS_VE.FECHA,
    CLIENTES.NOMBRE,
    DOCTOS_VE.ESTATUS,
    DOCTOS_VE.DESCRIPCION,
    DOCTOS_VE.IMPORTE_NETO,
    DOCTOS_VE.TOTAL_IMPUESTOS,
        (select FIRST 1 observacioncotizacion.FECHA from observacioncotizacion
where observacioNCOtizacion.docto_ve_id=doctos_ve.docto_ve_id ORDER BY observacioncotizacion.FECHA DESC) as FECHA_OBSERVACION,
(select FIRST 1 observacioncotizacion.descripcion from observacioncotizacion
where observacioNCOtizacion.docto_ve_id=doctos_ve.docto_ve_id ORDER BY observacioncotizacion.FECHA DESC) as DESCRIPCION_OBSERVACION
    from DOCTOS_VE, CLIENTES WHERE 
    DOCTOS_VE.CLIENTE_ID = CLIENTES.CLIENTE_ID
    AND DOCTOS_VE.TIPO_DOCTO='C' 
    AND DOCTOS_VE.FECHA BETWEEN '".$inicio."' and '".date("Y-m-d")."'
    order by DOCTOS_VE.FECHA";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    //$totales = array();

    //$datos = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
      $indice = count($datos);
      $datos[$indice]['FOLIO']                    = $empresa."-".(int)$row->FOLIO;
      $datos[$indice]['FECHA']                    = $row->FECHA;
      $datos[$indice]['NOMBRE']                   = $row->NOMBRE;
      $datos[$indice]['ESTATUS']                  = $row->ESTATUS;
      $datos[$indice]['DESCRIPCION']              = $row->DESCRIPCION;
      $datos[$indice]['IMPORTE_NETO']             = $row->IMPORTE_NETO;
      $datos[$indice]['TOTAL_IMPUESTOS']          = $row->TOTAL_IMPUESTOS;
      $datos[$indice]['FECHA_OBSERVACION']        = $row->FECHA_OBSERVACION;
      $datos[$indice]['DESCRIPCION_OBSERVACION']  = $row->DESCRIPCION_OBSERVACION;
      
    }
      return $datos;
}

function getUltimoDiaMes($elAnio,$elMes) {
  return date("d",(mktime(0,0,0,$elMes+1,1,$elAnio)-1));
}

function final_semana($fecha_actual)
{
        $unix = $fecha_actual; /// esto nos convierte la fecha de hoy en Unix
        switch (date("w")) /// segun el dia le damos un valor en segundos a $dia
        {
                case 0:
                    $dia = 0;
                    break;
                case 1:
                    $dia = 518400;
                break;
           case 2:
                    $dia = 432000;
                    break;
                case 3:
                    $dia = 345600;
                    break;
           case 4:
                    $dia = 259200;
                    break;
           case 5:
                    $dia = 172800;
                    break;
                case 6:
                    $dia = 86400;
                    break;
        }//switch
        $final_semana = ($unix + $dia); ///sumamos la fecha de hoy con $dia y nos dara la fecha del domingo proximo
        //$domingo_proximo = date("Y-m-d h:i:s",$final_semana); ///pasamos la fecha en unix a el formato normal
        return $final_semana;
}

function inicio_semana($fecha_actual)
{
        $unix = $fecha_actual; /// esto nos convierte la fecha de hoy en Unix
        switch (date("w")) /// segun el dia le damos un valor en segundos a $dia
        {
                case 1:
                    $dia = 0;
                    break;
                case 0:
                    $dia = 518400;
                break;
                case 6:
                    $dia = 432000;
                    break;
                case 5:
                    $dia = 345600;
                    break;
                case 4:
                    $dia = 259200;
                    break;
                case 3:
                    $dia = 172800;
                    break;
                case 2:
                    $dia = 86400;
                    break;
        }//switch
        $final_semana = ($unix - $dia); ///sumamos la fecha de hoy con $dia y nos dara la fecha del domingo proximo
        return $final_semana;
}
?>
