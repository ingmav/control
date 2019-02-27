<?php
include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$variable_inicial = 0;
$variable_final   = 0;

$arreglo_meses = array(
    1 => "ENERO",
    2 => "FEBRERO",
    3 => "MARZO",
    4 => "ABRIL",
    5 => "MAYO",
    6 => "JUNIO",
    7 => "JULIO",
    8 => "AGOSTO",
    9 => "SEPTIEMBRE",
    10 => "OCTUBRE",
    11 => "NOVIEMBRE",
    12 => "DICIEMBRE"
);

if ($_POST['fechadesde'] > $_POST['fechafin']) {
    $variable_inicial = $_POST['fechafin'];
    $variable_final   = $_POST['fechadesde'];
} else {
    $variable_inicial = $_POST['fechadesde'];
    $variable_final   = $_POST['fechafin'];
}

$anio_inicial     = date("Y", strtotime($variable_inicial));
$anio_final       = date("Y", strtotime($variable_final));
$variable_inicial = date("n", strtotime($variable_inicial));
$variable_final   = date("n", strtotime($variable_final));



$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Ventas Mensuales")->setSubject("Reporte Excel")->setDescription("Reporte de Ventas Mensuales")->setKeywords("reporte de ventas mensuales")->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Ventas Mensuales";

$titulosColumnas = array(
    'EMPRESA',
    'FOLIO',
    'CLIENTE',
    'DESCRIPCIÓN',
    'FECHA FACTURACIÓN',
    'ESTATUS',
    "IMPORTE VENTA",
    "IMPUESTO VENTA",
    "USUARIO CANCELÓ",
    "HORA CANCELACION",
    "FECHA VENTA",
    "IMPORTE VENTA (CON IVA)",
    //"FECHA PAGO",
    //"IMPORTE PAGO (CON IVA)",
    "ESTATUS APLICACION",
    "MONTO APLICADO",
    "MONTO RESTANTE"
    //"FECHA DE APLICACION"
);

$estiloTituloReporte = array(
    'font' => array(
        'name' => 'Verdana',
        'bold' => true,
        'italic' => false,
        'strike' => false,
        'size' => 14,
        'color' => array(
            'rgb' => 'FFFFFF'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FF555555'
        )
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_NONE
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

$estiloTituloColumnas = array(
    'font' => array(
        'name' => 'Arial',
        'bold' => true,
        'size' => 9,
        'color' => array(
            'rgb' => 'FFFFFF'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'E21800'
        )
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => '143860'
            )
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => '143860'
            )
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap' => TRUE
    )
);

$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray(array(
    'font' => array(
        'name' => 'Arial',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FFFFFFFF'
        )
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => '3a2a47'
            )
        )
    )
));

$index_hoja = 0;
while ($anio_inicial <= $anio_final) {
    while ($variable_inicial <= $variable_final) {
        $arreglo_mes = Ventas($variable_inicial, $anio_inicial);
        $objPHPExcel->createSheet($index_hoja);
        $objPHPExcel->setActiveSheetIndex($index_hoja);
        $objPHPExcel->setActiveSheetIndex($index_hoja)->mergeCells('A1:N1');
        $objPHPExcel->getActiveSheet()->setTitle('VENTAS_' . $arreglo_meses[$variable_inicial] . "_" . $anio_inicial);
        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex($index_hoja)->setCellValue('A1', $tituloReporte)->setCellValue('A3', $titulosColumnas[0])->setCellValue('B3', $titulosColumnas[1])->setCellValue('C3', $titulosColumnas[2])->setCellValue('D3', $titulosColumnas[3])->setCellValue('E3', $titulosColumnas[4])->setCellValue('F3', $titulosColumnas[5])
        ->setCellValue('G3', $titulosColumnas[6])->setCellValue('H3', $titulosColumnas[7])->setCellValue('I3', $titulosColumnas[8])->setCellValue('J3', $titulosColumnas[9])->setCellValue('K3', $titulosColumnas[10])->setCellValue('L3', $titulosColumnas[11])->setCellValue('M3', $titulosColumnas[12])->setCellValue('N3', $titulosColumnas[13])
        ->setCellValue('O3', $titulosColumnas[14]);

        $j = 4;

        foreach ($arreglo_mes as $key => $value) {
          $estatus = "NORMAL";
          $estatus_aplicacion = "NORMAL";
          $monto_cobrado = 0;
          if ($value['ESTATUS'] == "C")
              $estatus = "CANCELADO";

          if($value['ESTADO_APLICACION'] != "N"){
            $estatus_aplicacion = "PENDIENTE";
            $monto_cobrado = $value['IMPORTE_PAGADO'];
          }else {
            $monto_cobrado = $value['IMPORTE_PAGADO'];
          }

            $objPHPExcel->setActiveSheetIndex($index_hoja)
                        ->setCellValue('A' . $j, $value['EMPRESA'])
                        ->setCellValue('B' . $j, $value['FOLIO'])
                        ->setCellValue('C' . $j, $value['NOMBRE'])
                        ->setCellValue('D' . $j, $value['DESCRIPCION'])
                        ->setCellValue('E' . $j, $value['FECHA_VENTA'])
                        ->setCellValue('F' . $j, $estatus)
                        ->setCellValue('G' . $j, $value['IMPORTE_NETO'])
                        ->setCellValue('H' . $j, $value['TOTAL_IMPUESTOS'])
                        ->setCellValue('I' . $j, $value['USUARIO_CANCELACION'])
                        ->setCellValue('J' . $j, $value['FECHA_HORA_CANCELACION'])
                        ->setCellValue('K' . $j, $value['FECHA_COBRO'])
                        ->setCellValue('L' . $j, $value['IMPORTE_COBRO'])
                        //->setCellValue('M' . $j, $value['FECHA_PAGO'])
                        //->setCellValue('N' . $j, $value['IMPORTE_PAGO'])
                        ->setCellValue('M' . $j, $estatus_aplicacion)
                        ->setCellValue('N' . $j, $value['IMPORTE_PAGADO'])
                        ->setCellValue('O' . $j, $value['IMPORTE_RESTANTE']);

            $j++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($estiloTituloColumnas);

        for ($k = 'A'; $k <= 'O'; $k++) {
            $objPHPExcel->setActiveSheetIndex($index_hoja)->getColumnDimension($k)->setAutoSize(TRUE);
        }

        $variable_inicial += 1;
        $index_hoja++;
    }
    $anio_inicial++;
}

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteVentasMensuales.xlsx"');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
?>

<?php
function Ventas($mes, $anio)
{

    $ultimo_dia_mes = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
    $conexion       = new conexion_nexos(1);

    $mes_completo = str_pad($mes, 2, '0', STR_PAD_LEFT);
    /*$query = "select
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
SUM(IDC2.IMPORTE) AS IMPORTE_PAGO,
MAX(IDC2.FECHA) AS FECHA_PAGO,
MAX(DC.FECHA_APLICACION) AS FECHA_APLICACION,
MAX(DC.ESTATUS) AS ESTADO_APLICACION
from DOCTOS_VE DV
JOIN clientes C ON C.CLIENTE_ID = DV.CLIENTE_ID
LEFT JOIN DOCTOS_ENTRE_SIS DES ON DES.DOCTO_FTE_ID=DV.DOCTO_VE_ID AND DES.CLAVE_SIS_DEST='CC' AND DES.CLAVE_SIS_FTE='VE'
LEFT JOIN IMPORTES_DOCTOS_CC IDC ON IDC.DOCTO_CC_ACR_ID=DES.DOCTO_DEST_ID AND IDC.TIPO_IMPTE='C'
LEFT JOIN IMPORTES_DOCTOS_CC IDC2 ON IDC.DOCTO_CC_ID=IDC2.DOCTO_CC_ACR_ID AND IDC2.TIPO_IMPTE='R' AND IDC2.ESTATUS!='P'
LEFT JOIN DOCTOS_CC DC ON DC.DOCTO_CC_ID = IDC2.DOCTO_CC_ID
where DV.FECHA between '" . $anio . "-" . $mes_completo . "-01' and '" . $anio . "-" . $mes_completo . "-" . $ultimo_dia_mes . "'
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
IDC2.docto_cc_acr_id";
*/
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
SUM(IDC2.IMPORTE) AS IMPORTE_PAGADO,
SUM(IDC3.IMPORTE) AS IMPORTE_RESTANTE
from DOCTOS_VE DV
JOIN clientes C ON C.CLIENTE_ID = DV.CLIENTE_ID
LEFT JOIN DOCTOS_ENTRE_SIS DES ON DES.DOCTO_FTE_ID=DV.DOCTO_VE_ID AND DES.CLAVE_SIS_DEST='CC' AND DES.CLAVE_SIS_FTE='VE'
LEFT JOIN IMPORTES_DOCTOS_CC IDC ON IDC.DOCTO_CC_ACR_ID=DES.DOCTO_DEST_ID AND IDC.TIPO_IMPTE='C'
LEFT JOIN IMPORTES_DOCTOS_CC IDC2 ON IDC.DOCTO_CC_ID=IDC2.DOCTO_CC_ACR_ID AND IDC2.TIPO_IMPTE='R' AND IDC2.ESTATUS!='P'
LEFT JOIN IMPORTES_DOCTOS_CC IDC3 ON IDC.DOCTO_CC_ID=IDC3.DOCTO_CC_ACR_ID AND IDC3.TIPO_IMPTE='R' AND IDC3.ESTATUS='P'
LEFT JOIN DOCTOS_CC DC ON DC.DOCTO_CC_ID = IDC2.DOCTO_CC_ID AND DC.ESTATUS!='P'
where DV.FECHA between '" . $anio . "-" . $mes_completo . "-01' and '" . $anio . "-" . $mes_completo . "-" . $ultimo_dia_mes . "'
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
IDC2.docto_cc_acr_id,
IDC3.docto_cc_acr_id";
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    $index = 0;
    while ($row = ibase_fetch_object($result, IBASE_TEXT)) {
      $aplicacion = "N";
      if(is_null($row->IMPORTE_PAGADO))
      {
        $row->IMPORTE_PAGADO = 0;
        $aplicacion = "P";

      }
      if(is_null($row->IMPORTE_RESTANTE) || $row->IMPORTE_RESTANTE==0)
        $row->IMPORTE_RESTANTE = ($row->IMPORTE_COBRO-$row->IMPORTE_PAGADO);

      if($row->IMPORTE_RESTANTE > 0)
        $aplicacion = "P";

        $index                                      = count($arreglo1);
        $arreglo1[$index]['EMPRESA']                = "NX";
        $arreglo1[$index]['FOLIO']                  = (int) $row->FOLIO;
        $arreglo1[$index]['NOMBRE']                 = utf8_encode($row->NOMBRE);
        $arreglo1[$index]['DESCRIPCION']            = utf8_encode($row->DESCRIPCION);
        $arreglo1[$index]['FECHA_VENTA']            = $row->FECHA_VENTA;
        $arreglo1[$index]['ESTATUS']                = $row->ESTATUS;
        $arreglo1[$index]['IMPORTE_NETO']           = $row->IMPORTE_NETO;
        $arreglo1[$index]['TOTAL_IMPUESTOS']        = $row->TOTAL_IMPUESTOS;
        $arreglo1[$index]['USUARIO_CANCELACION']    = $row->USUARIO_CANCELACION;
        $arreglo1[$index]['FECHA_HORA_CANCELACION'] = $row->FECHA_HORA_CANCELACION;
        $arreglo1[$index]['FECHA_COBRO']            = $row->FECHA_COBRO;
        $arreglo1[$index]['IMPORTE_COBRO']          = $row->IMPORTE_COBRO;
        $arreglo1[$index]['IMPORTE_PAGADO']         = $row->IMPORTE_PAGADO;
        $arreglo1[$index]['IMPORTE_RESTANTE']       = $row->IMPORTE_RESTANTE;
        $arreglo1[$index]['ESTADO_APLICACION']      = $aplicacion ;
      }

      $query_notas = "select DOCTOS_CC.folio,
CLIENTES.nombre,
DOCTOS_CC.descripcion,
DOCTOS_CC.FECHA AS FECHA_VENTA,
DOCTOS_CC.CANCELADO AS ESTATUS,
IDC1.IMPORTE AS IMPORTE_NETO,
IDC1.IMPUESTO AS TOTAL_IMPUESTOS,
DOCTOS_CC.USUARIO_CANCELACION,
DOCTOS_CC.FECHA_HORA_CANCELACION,
DOCTOS_CC.FECHA AS FECHA_COBRO,
(IDC1.IMPORTE + IDC1.IMPUESTO) AS IMPORTE_COBRO,
SUM(IDC2.IMPORTE) AS IMPORTE_PAGADO
from DOCTOS_CC
LEFT JOIN IMPORTES_DOCTOS_CC IDC1 ON DOCTOS_CC.DOCTO_CC_ID = IDC1.DOCTO_CC_ID
LEFT JOIN CLIENTES ON DOCTOS_CC.cliente_id = CLIENTES.cliente_id
LEFT JOIN IMPORTES_DOCTOS_CC IDC2 ON DOCTOS_CC.DOCTO_CC_ID = IDC2.DOCTO_CC_ACR_ID AND IDC2.TIPO_IMPTE='R' AND IDC2.CANCELADO='N'
WHERE  1=1
AND DOCTOS_CC.FECHA between '" . $anio . "-" . $mes_completo . "-01' and '" . $anio . "-" . $mes_completo . "-" . $ultimo_dia_mes . "'
and DOCTOS_CC.CONCEPTO_CC_ID='8'
GROUP BY
DOCTOS_CC.folio, CLIENTES.nombre, DOCTOS_CC.descripcion,
DOCTOS_CC.FECHA,
DOCTOS_CC.CANCELADO,
IDC1.IMPORTE,
IDC1.IMPUESTO,
DOCTOS_CC.USUARIO_CANCELACION,
DOCTOS_CC.FECHA_HORA_CANCELACION";

$result1_notas = ibase_query($conexion->getConexion(), $query_notas) or die(ibase_errmsg());

$arreglo1_notas = array();

$index = 0;
while ($row1_notas = ibase_fetch_object($result1_notas, IBASE_TEXT)) {
  $aplicacion = "N";
  if(is_null($row1_notas->IMPORTE_PAGADO))
  {
    $row1_notas->IMPORTE_PAGADO = 0;
    $aplicacion = "P";
  }

  $row1_notas->IMPORTE_RESTANTE = ($row1_notas->IMPORTE_COBRO-$row1_notas->IMPORTE_PAGADO);

  if($row1_notas->IMPORTE_RESTANTE > 0)
    $aplicacion = "P";

    if($row1_notas->ESTATUS == "S")
      $row1_notas->ESTATUS = "C";
    $index                                      = count($arreglo1_notas);
    $arreglo1_notas[$index]['EMPRESA']                = "NXNT";
    $arreglo1_notas[$index]['FOLIO']                  = (int) $row1_notas->FOLIO;
    $arreglo1_notas[$index]['NOMBRE']                 = utf8_encode($row1_notas->NOMBRE);
    $arreglo1_notas[$index]['DESCRIPCION']            = utf8_encode($row1_notas->DESCRIPCION);
    $arreglo1_notas[$index]['FECHA_VENTA']            = $row1_notas->FECHA_VENTA;
    $arreglo1_notas[$index]['ESTATUS']                = $row1_notas->ESTATUS;
    $arreglo1_notas[$index]['IMPORTE_NETO']           = $row1_notas->IMPORTE_NETO;
    $arreglo1_notas[$index]['TOTAL_IMPUESTOS']        = $row1_notas->TOTAL_IMPUESTOS;
    $arreglo1_notas[$index]['USUARIO_CANCELACION']    = $row1_notas->USUARIO_CANCELACION;
    $arreglo1_notas[$index]['FECHA_HORA_CANCELACION'] = $row1_notas->FECHA_HORA_CANCELACION;
    $arreglo1_notas[$index]['FECHA_COBRO']            = $row1_notas->FECHA_COBRO;
    $arreglo1_notas[$index]['IMPORTE_COBRO']          = $row1_notas->IMPORTE_COBRO;
    $arreglo1_notas[$index]['IMPORTE_PAGADO']         = $row1_notas->IMPORTE_PAGADO;
    $arreglo1_notas[$index]['IMPORTE_RESTANTE']       = $row1_notas->IMPORTE_RESTANTE;
    $arreglo1_notas[$index]['ESTADO_APLICACION']      = $aplicacion ;
  }
  /*falta unirlo*/
  $arreglo1 = array_merge($arreglo1, $arreglo1_notas);

    $conexion2 = new conexion_nexos(2);

    $result2 = ibase_query($conexion2->getConexion(), $query) or die(ibase_errmsg());

    $arreglo2 = array();

    $index = 0;
    while ($row2 = ibase_fetch_object($result2, IBASE_TEXT)) {
      $aplicacion = "N";
      if(is_null($row2->IMPORTE_PAGADO))
      {
        $row2->IMPORTE_PAGADO = 0;
        $aplicacion = "P";

      }
      if(is_null($row2->IMPORTE_RESTANTE) || $row2->IMPORTE_RESTANTE==0)
        $row2->IMPORTE_RESTANTE = ($row2->IMPORTE_COBRO-$row2->IMPORTE_PAGADO);

      if($row2->IMPORTE_RESTANTE > 0)
        $aplicacion = "P";

        $index                                      = count($arreglo2);
        $arreglo2[$index]['EMPRESA']                = "NP";
        $arreglo2[$index]['FOLIO']                  = (int) $row2->FOLIO;
        $arreglo2[$index]['NOMBRE']                 = utf8_encode($row2->NOMBRE);
        $arreglo2[$index]['DESCRIPCION']            = utf8_encode($row2->DESCRIPCION);
        $arreglo2[$index]['FECHA_VENTA']            = $row2->FECHA_VENTA;
        $arreglo2[$index]['ESTATUS']                = $row2->ESTATUS;
        $arreglo2[$index]['IMPORTE_NETO']           = $row2->IMPORTE_NETO;
        $arreglo2[$index]['TOTAL_IMPUESTOS']        = $row2->TOTAL_IMPUESTOS;
        $arreglo2[$index]['USUARIO_CANCELACION']    = $row2->USUARIO_CANCELACION;
        $arreglo2[$index]['FECHA_HORA_CANCELACION'] = $row2->FECHA_HORA_CANCELACION;
        $arreglo2[$index]['FECHA_COBRO']            = $row2->FECHA_COBRO;
        $arreglo2[$index]['IMPORTE_COBRO']          = $row2->IMPORTE_COBRO;
        $arreglo2[$index]['IMPORTE_PAGADO']         = $row2->IMPORTE_PAGADO;
        $arreglo2[$index]['IMPORTE_RESTANTE']       = $row2->IMPORTE_RESTANTE;
        $arreglo2[$index]['ESTADO_APLICACION']      = $aplicacion;
    }

    $result2_notas = ibase_query($conexion->getConexion(), $query_notas) or die(ibase_errmsg());

    $arreglo2_notas = array();

    $index = 0;
    while ($row2_notas = ibase_fetch_object($result2_notas, IBASE_TEXT)) {
      $aplicacion = "N";
      if(is_null($row2_notas->IMPORTE_PAGADO))
      {
        $row2_notas->IMPORTE_PAGADO = 0;
        $aplicacion = "P";
      }

      $row2_notas->IMPORTE_RESTANTE = ($row2_notas->IMPORTE_COBRO-$row2_notas->IMPORTE_PAGADO);

      if($row2_notas->IMPORTE_RESTANTE > 0)
        $aplicacion = "P";

        if($row2_notas->ESTATUS == "S")
          $row2_notas->ESTATUS = "C";

        $index                                      = count($arreglo2_notas);
        $arreglo2_notas[$index]['EMPRESA']                = "NPNT";
        $arreglo2_notas[$index]['FOLIO']                  = (int) $row2_notas->FOLIO;
        $arreglo2_notas[$index]['NOMBRE']                 = utf8_encode($row2_notas->NOMBRE);
        $arreglo2_notas[$index]['DESCRIPCION']            = utf8_encode($row2_notas->DESCRIPCION);
        $arreglo2_notas[$index]['FECHA_VENTA']            = $row2_notas->FECHA_VENTA;
        $arreglo2_notas[$index]['ESTATUS']                = $row2_notas->ESTATUS;
        $arreglo2_notas[$index]['IMPORTE_NETO']           = $row2_notas->IMPORTE_NETO;
        $arreglo2_notas[$index]['TOTAL_IMPUESTOS']        = $row2_notas->TOTAL_IMPUESTOS;
        $arreglo2_notas[$index]['USUARIO_CANCELACION']    = $row2_notas->USUARIO_CANCELACION;
        $arreglo2_notas[$index]['FECHA_HORA_CANCELACION'] = $row2_notas->FECHA_HORA_CANCELACION;
        $arreglo2_notas[$index]['FECHA_COBRO']            = $row2_notas->FECHA_COBRO;
        $arreglo2_notas[$index]['IMPORTE_COBRO']          = $row2_notas->IMPORTE_COBRO;
        $arreglo2_notas[$index]['IMPORTE_PAGADO']         = $row2_notas->IMPORTE_PAGADO;
        $arreglo2_notas[$index]['IMPORTE_RESTANTE']       = $row2_notas->IMPORTE_RESTANTE;
        $arreglo2_notas[$index]['ESTADO_APLICACION']      = $aplicacion ;
      }

      $arreglo2 = array_merge($arreglo2, $arreglo2_notas);

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
where PV.FECHA between '" . $anio . "-" . $mes_completo . "-01' and '" . $anio . "-" . $mes_completo . "-" . $ultimo_dia_mes . "'
AND PV.ESTATUS!='C'
AND PV.TIPO_DOCTO='V'";

    $result3 = ibase_query($conexion2->getConexion(), $query2) or die(ibase_errmsg());

    $arreglo3 = array();

    $index = 0;
    while ($row3 = ibase_fetch_object($result3, IBASE_TEXT)) {
        $index                                      = count($arreglo3);
        $arreglo3[$index]['EMPRESA']                = "NPM";
        $arreglo3[$index]['FOLIO']                  = substr($row3->FOLIO, 0, 1) . (int) substr($row3->FOLIO, 1);
        $arreglo3[$index]['NOMBRE']                 = utf8_encode($row3->NOMBRE);
        $arreglo3[$index]['DESCRIPCION']            = utf8_encode($row3->PERSONA);
        $arreglo3[$index]['FECHA_VENTA']            = $row3->FECHA_VENTA;
        $arreglo3[$index]['ESTATUS']                = $row3->ESTATUS;
        $arreglo3[$index]['IMPORTE_NETO']           = $row3->IMPORTE_NETO;
        $arreglo3[$index]['TOTAL_IMPUESTOS']        = $row3->TOTAL_IMPUESTOS;
        $arreglo3[$index]['USUARIO_CANCELACION']    = $row3->USUARIO_CANCELACION;
        $arreglo3[$index]['FECHA_HORA_CANCELACION'] = $row3->FECHA_HORA_CANCELACION;
        $arreglo3[$index]['FECHA_COBRO']            = $row3->FECHA_VENTA;
        $arreglo3[$index]['IMPORTE_COBRO']          = ($row3->IMPORTE_NETO + $row3->TOTAL_IMPUESTOS);
        $arreglo3[$index]['IMPORTE_PAGADO']           = ($row3->IMPORTE_NETO + $row3->TOTAL_IMPUESTOS);
        $arreglo3[$index]['IMPORTE_RESTANTE']       = 0;
        $arreglo3[$index]['ESTADO_APLICACION']      = "N";
      }


    $arreglo4 = array_merge($arreglo1, $arreglo2, $arreglo3);
    return $arreglo4;
}

?>
