<?php
/*header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteCxC.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);*/
include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');


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

$conection = new conexion_nexos($_SESSION['empresa']);
$desde = $_POST['desde_mostrador'];
$hasta = $_POST['hasta_mostrador'];

$query = "select MAX(HORA) MAXIMA_HORA from DOCTOS_PV WHERE TIPO_DOCTO='V' AND ESTATUS!='C' AND FECHA BETWEEN '".$desde."' and '".$hasta."'";

//numero de dias, transcurridos
$datetime1 = new DateTime($desde);
$datetime2 = new DateTime($hasta);
$interval = $datetime1->diff($datetime2);
$numero_dias = $interval->format('%a');
$numero_dias++;


$numero_domingos = count(contarDomingos($desde,$hasta));

$numero_dias_habiles = $numero_dias - $numero_domingos;
//Numero de días, transcurridos fin

$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

$arreglo1 = array();

while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
    $maxima_hora = $row;
}

$hora_maxima_venta = $maxima_hora->MAXIMA_HORA;
$hora_maxima_venta = (substr($hora_maxima_venta,0, 2) + 1);

///Calcula intervalos
$arreglo_montos = array();
$monto_9_2 = array("cantidad"=>0, "monto"=>0);
$monto_2_adelante = array("cantidad"=>0, "monto"=>0);
$datos_rosh = array("hora"=>9, "cantidad"=>0, "monto"=>0);
for($hora = 9; $hora< $hora_maxima_venta; $hora++)
{

	$query = "select SUM(IMPORTE_NETO) IMPORTE, COUNT(*) CANTIDAD from DOCTOS_PV WHERE TIPO_DOCTO='V' AND ESTATUS!='C' AND FECHA BETWEEN '".$desde."' and '".$hasta."'
			  and HORA BETWEEN '".$hora.":00' and  '".$hora.":59'";

	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$arreglo1 = array();

	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
	    $indice = count($arreglo_montos);
	    $arreglo_montos[$indice]['hora'] = $hora;
	    $arreglo_montos[$indice]['monto'] = $row->IMPORTE;
	    $monto_acumulado += $row->IMPORTE;
	    $arreglo_montos[$indice]['cantidad'] = $row->CANTIDAD;
	    $cantidad_acumulado += $row->CANTIDAD;

        if($hora < 14)
        {
            $monto_9_2['monto'] += $row->IMPORTE;
            $monto_9_2['cantidad'] += $row->CANTIDAD;
        }
        else
        {
            $monto_2_adelante['monto'] += $row->IMPORTE;
            $monto_2_adelante['cantidad'] += $row->CANTIDAD;
        }

        if($datos_rosh['cantidad'] < $row->CANTIDAD)
        {
            $datos_rosh['hora'] = $hora;
            $datos_rosh['monto'] = $row->IMPORTE;
            $datos_rosh['cantidad'] = $row->CANTIDAD;
        }
	}

}
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

$tituloReporte = "Reporte Mostrador del ".$desde." al ".$hasta;
$titulosColumnas = array('DESDE', 'HASTA', 'VENTAS', 'IMPORTE', 'PROM. VENTA', 'RESUMEN DE OPERACIONES');
$titulosColumnas2 = array('CONCEPTO', "CANTIDAD", "MONTO");

/* Configuracion*/
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($estiloTituloColumnas);

for($i = 'A'; $i <= 'E'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:J2');
$objPHPExcel->getActiveSheet()->getStyle('G3:J3')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setAutoSize(TRUE);

/*Fin configuracion*/
// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',  $tituloReporte)
    ->setCellValue('A2',  $titulosColumnas[0])
    ->setCellValue('B2',  $titulosColumnas[1])
    ->setCellValue('C2',  $titulosColumnas[2])
    ->setCellValue('D2',  $titulosColumnas[3])
    ->setCellValue('E2',  $titulosColumnas[4])
    ->setCellValue('G2',  $titulosColumnas[5])
;

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('G3',  $titulosColumnas2[0])
    ->setCellValue('H3',  $titulosColumnas2[1])
    ->setCellValue('I3',  $titulosColumnas2[2])

;

/*Fin de arreglo de rango de fechas*/
$i = 3;
$iteraciones = 0;
foreach($arreglo_montos as $key => $value)
{

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['hora'].":00")
        ->setCellValue('B'.$i,  $value['hora'].":59")
        ->setCellValue('C'.$i,  $value['cantidad'])
        ->setCellValue('D'.$i,  $value['monto'])
        ->setCellValue('E'.$i,  ($value['monto'] / $value['cantidad']));

        $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");
    $i++; 
    $iteraciones++;
}

$i++;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('C'.$i,  "SUBTOTAL")
->setCellValue('D'.$i,  $monto_acumulado);

$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");

$i++;

$promedio_cantidad = floor(($cantidad_acumulado / $iteraciones));

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('C'.$i,  "VENTA PROMEDIO")
->setCellValue('D'.$i,  ($monto_acumulado / $numero_dias_habiles));

$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");

$i = 4;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('G'.$i,  "De 9 a 2 pm")
->setCellValue('H'.$i,  $monto_9_2['cantidad'])
->setCellValue('I'.$i,  $monto_9_2['monto']);



$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");

$i++;
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('G'.$i,  "De 2 a 8 pm")
->setCellValue('H'.$i,  $monto_2_adelante['cantidad'])
->setCellValue('I'.$i,  $monto_2_adelante['monto']);

$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");

$i++;
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('G'.$i,  "De ".$datos_rosh['hora'].":00 a ".$datos_rosh['hora'].":59 ")
->setCellValue('H'.$i,  $datos_rosh['cantidad'])
->setCellValue('I'.$i,  $datos_rosh['monto'])
->setCellValue('J'.$i,  "Hora Rosh");

$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");

$i++;
$cantidad_total = ($monto_2_adelante['cantidad'] + $monto_9_2['cantidad']);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('G'.$i,  "Total de Transacciones")
->setCellValue('H'.$i,  $cantidad_total);




$i++;
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('G'.$i,  "Transacciones promedio/DIARIAS")
->setCellValue('H'.$i,  ($cantidad_total / ($numero_dias_habiles)))
->setCellValue('I'.$i,  ($monto_acumulado / ($cantidad_total)));

$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("$ #,##0.00");


///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Reporte_Mostrador.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////


//Funcion para saber cuantos domingos existen
/*Funcion que devuelve los dias domingo que caen entre 2 fechas*/
function contarDomingos($fechaInicio,$fechaFin)
{
 $dias=array();
 $fecha1=date($fechaInicio);
 $fecha2=date($fechaFin);
 $fechaTime=strtotime("-1 day",strtotime($fecha1));//Les resto un dia para que el next sunday pueda evaluarlo en caso de que sea un domingo
 $fecha=date("Y-m-d",$fechaTime);
 while($fecha <= $fecha2)
 {
  $proximo_domingo=strtotime("next Sunday",$fechaTime);
  $fechaDomingo=date("Y-m-d",$proximo_domingo);
  if($fechaDomingo <= $fechaFin)
  { 
   $dias[$fechaDomingo]=$fechaDomingo;
  }
  else
  {
   break;
  }
  $fechaTime=$proximo_domingo;
  $fecha=date("Y-m-d",$proximo_domingo);
 }
 return $dias;
}//fin de domingos
//
?>
