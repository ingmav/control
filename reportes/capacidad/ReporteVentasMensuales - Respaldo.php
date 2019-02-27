<?php
include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

  //print_r($_POST);
  $variable_inicial = 0;
  $variable_final = 0;

  $arreglo_meses =array(
                        1=> "ENERO",
                        2=>"FEBRERO",
                        3=>"MARZO",
                        4=>"ABRIL",
                        5=>"MAYO",
                        6=>"JUNIO",
                        7=>"JULIO",
                        8=>"AGOSTO",
                        9=>"SEPTIEMBRE",
                        10=>"OCTUBRE",
                        11=>"NOVIEMBRE",
                        12=>"DICIEMBRE");

  if($_POST['fechadesde'] > $_POST['fechafin'])
  {
    $variable_inicial = $_POST['fechafin'];
    $variable_final = $_POST['fechadesde'];
  }else {
    $variable_inicial = $_POST['fechadesde'];
    $variable_final = $_POST['fechafin'];
  }

  $variable_inicial  = date("n", strtotime($variable_inicial));
  $variable_final    = date("n", strtotime($variable_final));
  $anio_inicial       = date("Y", strtotime($variable_inicial));
  $anio_final         = date("Y", strtotime($variable_final));


  $objPHPExcel = new PHPExcel();

  // Se asignan las propiedades del libro
  $objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
      ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
      ->setTitle("Reporte Ventas Mensuales")
      ->setSubject("Reporte Excel")
      ->setDescription("Reporte de Ventas Mensuales")
      ->setKeywords("reporte de ventas mensuales")
      ->setCategory("Reporte MicrosipWeb");

  $tituloReporte = "Reporte Ventas Mensuales";

  $titulosColumnas = array('FOLIO', 'CLIENTE', 'DESCRIPCIÓN', 'FECHA FACTURACIÓN', 'ESTATUS', "IMPORTE VENTA", "IMPUESTO VENTA", "USUARIO CANCELÓ", "HORA CANCELACION", "FECHA VENTA", "IMPORTE VENTA (CON IVA)", "FECHA PAGO", "IMPORTE PAGO (CON IVA)");

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

/*  echo "<table>";
    echo "<tr><td>EMPRESA</td><td>FOLIO</td><td>CLIENTE</td><td>DESCRIPCION</td><td>FECHA</td><td>ESTATUS</td><td>IMPORTE VENTA</td>";
    echo "<td>IMPUESTO VENTA</td><td>USUARIO CANCELO</td><td>HORA CANCELACION</td><td>FECHA VENTA</td><td>IMPORTE VENTA (CON IVA)</td><td>FECHA PAGO</td><td>IMPORTE PAGO (CON IVA)</td><tr>";*/
  $index_hoja = 0;
  while($anio_inicial <= $anio_final)
  {
    while($variable_inicial<=$variable_final)
    {
      $arreglo_mes = Ventas($variable_inicial, $anio_inicial);
      for($i = 0; $i < count($arreglo_mes); $i++)
      {
        $objPHPExcel->createSheet($i);
        $objPHPExcel->setActiveSheetIndex($i);
        $objPHPExcel->setActiveSheetIndex($i)->mergeCells('A1:N1');
        $objPHPExcel->getActiveSheet()->setTitle('Ventas_'.$arreglo_meses[$i]."_".$anio_inicial);

        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex($i)
            ->setCellValue('A1',  $tituloReporte)
            ->setCellValue('A3',  $titulosColumnas[0])
            ->setCellValue('B3',  $titulosColumnas[1])
            ->setCellValue('C3',  $titulosColumnas[2])
            ->setCellValue('D3',  $titulosColumnas[3])
            ->setCellValue('E3',  $titulosColumnas[4])
            ->setCellValue('F3',  $titulosColumnas[5])
            ->setCellValue('G3',  $titulosColumnas[6])
            ->setCellValue('H3',  $titulosColumnas[7])
            ->setCellValue('I3',  $titulosColumnas[8])
            ->setCellValue('J3',  $titulosColumnas[9])
            ->setCellValue('K3',  $titulosColumnas[10])
            ->setCellValue('L3',  $titulosColumnas[11])
            ->setCellValue('M3',  $titulosColumnas[12])
            ->setCellValue('N3',  $titulosColumnas[13]);


        //Se agregan los datos
        $j = 4;


        foreach($arreglo_mes as $key => $value)
        {
            $estatus = "NORMAL";
            if($value['ESTATUS'] == "C")
              $estatus = "CANCELADO";
            $objPHPExcel->setActiveSheetIndex($i)
                ->setCellValue('A'.$j,  $value['EMPRESA'])
                ->setCellValue('B'.$j,  $value['FOLIO'])
                ->setCellValue('C'.$j,  $value['NOMBRE'])
                ->setCellValue('D'.$j,  $value['DESCRIPCION'])
                ->setCellValue('E'.$j,  $value['FECHA_VENTA'])
                ->setCellValue('F'.$j,  $estatus)
                ->setCellValue('G'.$j,  $value['IMPORTE_NETO'])
                ->setCellValue('H'.$j,  $value['TOTAL_IMPUESTOS'])
                ->setCellValue('I'.$j,  $value['USUARIO_CANCELACION'])
                ->setCellValue('J'.$j,  $value['FECHA_HORA_CANCELACION'])
                ->setCellValue('K'.$j,  $value['FECHA_COBRO'])
                ->setCellValue('L'.$j,  $value['IMPORTE_COBRO'])
                ->setCellValue('M'.$j,  $value['FECHA_PAGO'])
                ->setCellValue('N'.$j,  $value['IMPORTE_PAGO']);
              }
            $j++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->applyFromArray($estiloTituloColumnas);
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

        for($k = 'A'; $k <= 'N'; $k++){
            $objPHPExcel->setActiveSheetIndex($i)
                ->getColumnDimension($k)->setAutoSize(TRUE);
        }

        // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        /*echo "<tr><td>".$arreglo_mes[$i]['EMPRESA']."</td>";
        echo "<td>".$arreglo_mes[$i]['FOLIO']."</td>";
        echo "<td>".$arreglo_mes[$i]['NOMBRE']."</td>";
        echo "<td>".$arreglo_mes[$i]['DESCRIPCION']."</td>";
        echo "<td>".$arreglo_mes[$i]['FECHA_VENTA']."</td>";
        echo "<td>".$arreglo_mes[$i]['ESTATUS']."</td>";
        echo "<td>".$arreglo_mes[$i]['IMPORTE_NETO']."</td>";
        echo "<td>".$arreglo_mes[$i]['TOTAL_IMPUESTOS']."</td>";
        echo "<td>".$arreglo_mes[$i]['USUARIO_CANCELACION']."</td>";
        echo "<td>".$arreglo_mes[$i]['FECHA_HORA_CANCELACION']."</td>";
        echo "<td>".$arreglo_mes[$i]['FECHA_COBRO']."</td>";
        echo "<td>".$arreglo_mes[$i]['IMPORTE_COBRO']."</td>";
        echo "<td>".$arreglo_mes[$i]['FECHA_PAGO']."</td>";
        echo "<td>".$arreglo_mes[$i]['IMPORTE_PAGO']."</td></tr>";*/
      }
      $index_hoja++;
      $variable_inicial += 1;
    }
    $anio_inicial++;
  }
  //echo "</table>";

  header('Content-type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename="ReporteCxC.xlsx"');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  ob_end_clean();
  ob_start();
  $objWriter->save('php://output');
  exit;

  // Se crea el objeto PHPExcel

    /*$objPHPExcel->createSheet($i);
    $objPHPExcel->setActiveSheetIndex($i);
    $objPHPExcel->setActiveSheetIndex($i)
       ->mergeCells('A1:L1');
       $objPHPExcel->getActiveSheet()->setTitle('CuentasxCobrar'.$i);*/

  //$objPHPExcel->setActiveSheetIndex(0);
  /*$objPHPExcel->setActiveSheetIndex(0)
     ->mergeCells('A1:L1');

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
      ->setCellValue('J3',  $titulosColumnas[9])
      ->setCellValue('K3',  $titulosColumnas[10])
      ->setCellValue('L3',  $titulosColumnas[11]);


  //Se agregan los datos de los alumnos
  $i = 4;


  foreach($arreglo3 as $key => $value)
  {
      $finalizado = "NO";
      $sistema = "SI";
      if($value['FINALIZADO'] == 1)
          $finalizado = "SI";

      if($value['CONCEPTO_CC'] == 8)
          $sistema = "NO";

      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i,  $value['EMPRESA']."-".intval($value['FOLIO']))
          ->setCellValue('B'.$i,  $value['NOMBRE'])
          ->setCellValue('C'.$i,  $value['DESCRIPCION'])
          ->setCellValue('D'.$i,  $value['FECHA'])
          ->setCellValue('E'.$i,  $value['FECHA_VENCIMIENTO'])
          ->setCellValue('F'.$i,  $finalizado)
          ->setCellValue('G'.$i,  $value['IMPORTE'])
          ->setCellValue('H'.$i,  $value['ANTICIPO'])
          ->setCellValue('I'.$i,  $value['TOTAL'])
          ->setCellValue('J'.$i,  $value['DEPOSITO'])
          ->setCellValue('K'.$i,  $value['FECHA_DEPOSITO'])
          ->setCellValue('L'.$i,  $sistema);
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

  $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($estiloTituloReporte);
  $objPHPExcel->getActiveSheet()->getStyle('A3:L3')->applyFromArray($estiloTituloColumnas);
  //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

  for($i = 'A'; $i <= 'L'; $i++){
      $objPHPExcel->setActiveSheetIndex(0)
          ->getColumnDimension($i)->setAutoSize(TRUE);
  }

*/
  // Se asigna el nombre a la hoja
  //$objPHPExcel->getActiveSheet()->setTitle('CuentasxCobrar');

  // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
  //$objPHPExcel->setActiveSheetIndex(0);
  // Inmovilizar paneles
  //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
  //$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

  // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
  //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  /*header('Content-type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename="ReporteCxC.xlsx"');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  ob_end_clean();
  ob_start();
  $objWriter->save('php://output');
  exit;*/
?>

<?php
function Ventas($mes, $anio)
{

    $ultimo_dia_mes = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));
    $conexion = new conexion_nexos(1);

    $query = "select
DV.FOLIO,
C.NOMBRE,
DV.DESCRIPCION,
DV.FECHA AS FECHA_VENTA,
DV.ESTATUS,
DV.IMPORTE_NETO,
DV.TOTAL_IMPUESTOS,
DV.USUARIO_CANCELACION,
DV.FECHA_HORA_CANCELACION,
IDC.FECHA AS FECHA_COBRO,
(IDC.IMPORTE +IDC.IMPUESTO) AS IMPORTE_COBRO,
IDC2.FECHA AS FECHA_PAGO,
IDC2.IMPORTE AS IMPORTE_PAGO
from DOCTOS_VE DV
JOIN clientes C ON C.CLIENTE_ID = DV.CLIENTE_ID
LEFT JOIN DOCTOS_ENTRE_SIS DES ON DES.DOCTO_FTE_ID=DV.DOCTO_VE_ID AND DES.CLAVE_SIS_DEST='CC' AND DES.CLAVE_SIS_FTE='VE'
LEFT JOIN IMPORTES_DOCTOS_CC IDC ON IDC.DOCTO_CC_ACR_ID=DES.DOCTO_DEST_ID AND IDC.TIPO_IMPTE='C'
LEFT JOIN IMPORTES_DOCTOS_CC IDC2 ON IDC.DOCTO_CC_ID=IDC2.DOCTO_CC_ACR_ID AND IDC2.TIPO_IMPTE='R'
where DV.FECHA between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$ultimo_dia_mes."'
AND DV.TIPO_DOCTO='F'
GROUP BY
DV.FOLIO,
DV.DESCRIPCION,
DV.FECHA,
C.nombre,
DV.ESTATUS,
DV.IMPORTE_NETO,
DV.TOTAL_IMPUESTOS,
DV.USUARIO_CANCELACION,
DV.FECHA_HORA_CANCELACION,
IDC.FECHA,
IDC.IMPORTE,
IDC.IMPUESTO,
IDC2.FECHA,
IDC2.IMPORTE,
IDC2.docto_cc_acr_id,
IDC.importe,
IDC.impuesto";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    $index = 0;
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
      $index = count($arreglo1);
      $arreglo1[$index]['EMPRESA'] =                "NX";
      $arreglo1[$index]['FOLIO'] =                  (int) $row->FOLIO;
      $arreglo1[$index]['NOMBRE'] =                 utf8_encode($row->NOMBRE);
      $arreglo1[$index]['DESCRIPCION'] =            utf8_encode($row->DESCRIPCION);
      $arreglo1[$index]['FECHA_VENTA'] =            $row->FECHA_VENTA;
      $arreglo1[$index]['ESTATUS'] =                $row->ESTATUS;
      $arreglo1[$index]['IMPORTE_NETO'] =           $row->IMPORTE_NETO;
      $arreglo1[$index]['TOTAL_IMPUESTOS'] =        $row->TOTAL_IMPUESTOS;
      $arreglo1[$index]['USUARIO_CANCELACION'] =    $row->USUARIO_CANCELACION;
      $arreglo1[$index]['FECHA_HORA_CANCELACION'] = $row->FECHA_HORA_CANCELACION;
      $arreglo1[$index]['FECHA_COBRO'] =            $row->FECHA_COBRO;
      $arreglo1[$index]['IMPORTE_COBRO'] =          $row->IMPORTE_COBRO;
      $arreglo1[$index]['FECHA_PAGO'] =             $row->FECHA_PAGO;
      $arreglo1[$index]['IMPORTE_PAGO'] =           $row->IMPORTE_PAGO;
    }

    $conexion2 = new conexion_nexos(2);

    $result2 = ibase_query($conexion2->getConexion(), $query) or die(ibase_errmsg());

    $arreglo2 = array();

    $index = 0;
    while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
      $index = count($arreglo2);
      $arreglo2[$index]['EMPRESA'] =                "NP";
      $arreglo2[$index]['FOLIO'] =                  (int) $row2->FOLIO;
      $arreglo2[$index]['NOMBRE'] =                 utf8_encode($row2->NOMBRE);
      $arreglo2[$index]['DESCRIPCION'] =            utf8_encode($row2->DESCRIPCION);
      $arreglo2[$index]['FECHA_VENTA'] =            $row2->FECHA_VENTA;
      $arreglo2[$index]['ESTATUS'] =                $row2->ESTATUS;
      $arreglo2[$index]['IMPORTE_NETO'] =           $row2->IMPORTE_NETO;
      $arreglo2[$index]['TOTAL_IMPUESTOS'] =        $row2->TOTAL_IMPUESTOS;
      $arreglo2[$index]['USUARIO_CANCELACION'] =    $row2->USUARIO_CANCELACION;
      $arreglo2[$index]['FECHA_HORA_CANCELACION'] = $row2->FECHA_HORA_CANCELACION;
      $arreglo2[$index]['FECHA_COBRO'] =            $row2->FECHA_COBRO;
      $arreglo2[$index]['IMPORTE_COBRO'] =          $row2->IMPORTE_COBRO;
      $arreglo2[$index]['FECHA_PAGO'] =             $row2->FECHA_PAGO;
      $arreglo2[$index]['IMPORTE_PAGO'] =           $row2->IMPORTE_PAGO;
    }

    $query2 = "select
PV.FOLIO,
C.NOMBRE,
PV.PERSONA,
PV.FECHA AS FECHA_VENTA,
PV.ESTATUS,
PV.IMPORTE_NETO,
PV.TOTAL_IMPUESTOS,
PV.USUARIO_CANCELACION,
PV.FECHA_HORA_CANCELACION
from DOCTOS_PV PV
JOIN clientes C ON C.CLIENTE_ID = PV.CLIENTE_ID
where PV.FECHA between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$ultimo_dia_mes."'
AND PV.TIPO_DOCTO='V'";

    $result3 = ibase_query($conexion2->getConexion(), $query2) or die(ibase_errmsg());

    $arreglo3 = array();

    $index = 0;
    while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
      $index = count($arreglo3);
      $arreglo3[$index]['EMPRESA'] =                "NPM";
      $arreglo3[$index]['FOLIO'] =                  substr($row3->FOLIO, 0,1).(int) substr($row3->FOLIO, 1);
      $arreglo3[$index]['NOMBRE'] =                 utf8_encode($row3->NOMBRE);
      $arreglo3[$index]['DESCRIPCION'] =            utf8_encode($row3->PERSONA);
      $arreglo3[$index]['FECHA_VENTA'] =            $row3->FECHA_VENTA;
      $arreglo3[$index]['ESTATUS'] =                $row3->ESTATUS;
      $arreglo3[$index]['IMPORTE_NETO'] =           $row3->IMPORTE_NETO;
      $arreglo3[$index]['TOTAL_IMPUESTOS'] =        $row3->TOTAL_IMPUESTOS;
      $arreglo3[$index]['USUARIO_CANCELACION'] =    $row3->USUARIO_CANCELACION;
      $arreglo3[$index]['FECHA_HORA_CANCELACION'] = $row3->FECHA_HORA_CANCELACION;

    }


    $arreglo4 = array_merge($arreglo1, $arreglo2, $arreglo3);
    return $arreglo4;
}

?>
