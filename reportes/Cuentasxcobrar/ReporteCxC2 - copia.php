<?php


include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$fecha_maxima = "";

$fechalimite = strtotime ( '-60 day' , strtotime ( date('Y-m-d') ) ) ;

$arreglo1 = ver_pagos(1, $fechalimite);
$arreglo2 = ver_pagos(2, $fechalimite);
$arreglo3 = array_merge($arreglo1['data'], $arreglo2['data']);

$fecha_maxima_reporte = ($arreglo1['max_fecha'] > $arreglo2['max_fecha']) ? $arreglo1['max_fecha'] : $arreglo2['max_fecha'];

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
    ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Cuentas por Cobrar")
    ->setSubject("Reporte Excel")
    ->setDescription("Reporte de Cuentas por Cobrar")
    ->setKeywords("reporte de cuentas por cobrar")
    ->setCategory("Reporte MicrosipWeb");

$tituloReporte = "Reporte Cuentas por Cobrar";
$titulosColumnas = array('FOLIO', 'CLIENTE', 'DESCRIPCIÓN', 'FACTURACIÓN', 'VENCIMIENTO', "FINALIZACION", "MONTO TOTAL", "ANTICIPO", "SALDO TOTAL", "MONTO POR COBRAR", "FECHA DE COBRO", "SISTEMA");


$objPHPExcel->setActiveSheetIndex(0)
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

$importe_general    = 0;
$operador_si        = 0;
$operador_no        = 0;
$por_depositar      = 0;
$vencido_cxc        = 0;
$vencido_sin_cxc    = 0;
$anticipos          = 0;
$no_registrado      = 0;


$fecha_vencimiento_sistema  = strtotime(date("Y-m-d 23:59:59"));
$fecha_inicio_sistema       = strtotime(date("Y-m-d 00:00:00"));

/*Creación de array con rango de fechas*/

$fecha_proximo_domingo  = final_semana($fecha_vencimiento_sistema);
$fecha_anterior_lunes   = inicio_semana($fecha_inicio_sistema);

$fecha_proximo_lunes    = ($fecha_proximo_domingo + 1);
$proximo_dia            = $fecha_vencimiento_sistema + 1;
$tiempo_semana          = 604800;

$arreglo_semanas_proyeccion     = array();
$variable_comprobacion          = $fecha_proximo_domingo;
$fecha_inicial                  = $fecha_anterior_lunes;
$fecha_final                    = $fecha_proximo_domingo;

do
{
    $variable_comprobacion = $fecha_final;
    $index = count($arreglo_semanas_proyeccion);
    $arreglo_semanas_proyeccion[$index]['fecha_inicial']    = $fecha_inicial;
    $arreglo_semanas_proyeccion[$index]['fecha_final']      = $fecha_final;
    $arreglo_semanas_proyeccion[$index]['acumulado']        = 0;

    $fecha_inicial  = ($fecha_final + 1);
    $fecha_final    = ($fecha_final + $tiempo_semana);

}while($fecha_maxima_reporte > $variable_comprobacion);



/*Fin de arreglo de rango de fechas*/
foreach($arreglo3 as $key => $value)
{
    $finalizado = "NO";
    $sistema = "SI";
    if($value['FINALIZADO'] == 1)
        $finalizado = "SI";

    if($value['CONCEPTO_CC'] == 8)
    {
        $sistema = "NO";
        $finalizado = "NO";
    }

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

        $importe_general    += $value['IMPORTE'];
        $anticipos          += $value['ANTICIPO'];
        

        if($finalizado == "SI")
            $operador_si += $value['TOTAL'];
        else if($finalizado == "NO")
            $operador_no += $value['TOTAL'];

        $por_depositar += $value['DEPOSITO'];

        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode("#,##0.00");

        if($value['FECHA_DEPOSITO'] != ""){
            $no_registrado      += ($value['IMPORTE'] - $value['ANTICIPO'] - $value['DEPOSITO']);
            $fecha_deposito = strtotime(date($value['FECHA_DEPOSITO']." 00:00:01"));
            if($fecha_vencimiento_sistema >= $fecha_deposito){
               $vencido_cxc += $value['DEPOSITO'];
            }else{
                $index = 0;
                while($arreglo_semanas_proyeccion[$index]['fecha_final'] < $fecha_deposito)
                {
                    $index++;
                }

                
                $arreglo_semanas_proyeccion[$index]['acumulado'] += $value['DEPOSITO'];
            }
        }
        
        if($value['FECHA_DEPOSITO'] == "")
            if($fecha_vencimiento_sistema >= strtotime(date($value['FECHA_VENCIMIENTO']." 00:00:01",time())))
               $vencido_sin_cxc += $value['IMPORTE'];
           
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



// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('CuentasxCobrar');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
//$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
$j = 1;
$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('D1:G1')->applyFromArray($estiloTituloColumnas);
        $objPHPExcel->getActiveSheet()->setTitle('RESUMEN');
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:B1');
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('D1:G1');

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "RESUMEN");

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D'.$j,  "PROYECCIÓN");        

$j++;

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "SUB TOTAL CARTERA")
        ->setCellValue('B'.$j,  $importe_general);   

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D'.$j,  "SEMANA")
        ->setCellValue('E'.$j,  "DE")
        ->setCellValue('F'.$j,  "HASTA")
        ->setCellValue('G'.$j,  "ACUMULADO");             

$j++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "ANTICIPOS")
        ->setCellValue('B'.$j,  $anticipos);

$j++;
$total_cartera = ($importe_general - $anticipos);
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "TOTAL CARTERA")
        ->setCellValue('B'.$j,  $total_cartera);        

$j++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "SIN REGISTRO DE COBRO")
        ->setCellValue('B'.$j,  $no_registrado);

$j++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "FINALIZADO, PARA COBRO")
        ->setCellValue('B'.$j,  $operador_si);  

$j++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "NO FINALIZADO, PARA COBRO")
        ->setCellValue('B'.$j,  $operador_no);




for($x = 2; $x<=(count($arreglo_semanas_proyeccion)+1); $x++)
{
    $index = $x-2;
    $semana = $x-1;
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D'.$x,  "SEMANA ".$semana)
        ->setCellValue('E'.$x,  date("Y-m-d", $arreglo_semanas_proyeccion[$index]['fecha_inicial']))
        ->setCellValue('F'.$x,  date("Y-m-d", $arreglo_semanas_proyeccion[$index]['fecha_final']))
        ->setCellValue('G'.$x,  $arreglo_semanas_proyeccion[$index]['acumulado']); 

    $objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getNumberFormat()->setFormatCode("$#,##0.00");    
}
for($h = 2; $h<13; $h++)
{
    $objPHPExcel->getActiveSheet()->getStyle('B'.$h)->getNumberFormat()->setFormatCode("$#,##0.00");

}

for($i = 'A'; $i <= 'B'; $i++){
    $objPHPExcel->setActiveSheetIndex(1)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}
// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteCxC.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
?>
<?php
function ver_pagos($empresa, $fecha_limite)
{
    $max_date = 0;
    $min_date = 0;
    $conexion = new conexion_nexos($empresa);

    $query = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $arreglo1[] = $row->DOCTO_VE_ID;
    }


    $query2 = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='R' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";

    $result2 = ibase_query($conexion->getConexion(), $query2) or die(ibase_errmsg());

    $arreglo2 = array();

    while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
        $arreglo2[] = $row2->DOCTO_VE_ID;
    }

    if(count($arreglo2) > 0)
    {

        $query3 = "select DOCTO_VE_DEST_ID from DOCTOS_VE_LIGAS WHERE DOCTO_VE_FTE_ID IN (".implode(",", $arreglo2).")";

        $result3 = ibase_query($conexion->getConexion(), $query3) or die(ibase_errmsg());

        $arreglo3 = array();

        while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
            $arreglo3[] = $row3->DOCTO_VE_DEST_ID;
        }

        $diferencia_remision_factura = count($arreglo2) - count($arreglo3);

        $arreglo1 = array_merge($arreglo1, $arreglo3);
    }


    $query4 = "select DOCTO_DEST_ID from DOCTOS_ENTRE_SIS WHERE CLAVE_SIS_DEST='CC' AND CLAVE_SIS_FTE='VE' AND DOCTO_FTE_ID IN (".implode(",", $arreglo1).")";

    $result4 = ibase_query($conexion->getConexion(), $query4) or die(ibase_errmsg());

    $arreglo4 = Array();

    while ($row4 = ibase_fetch_object ($result4, IBASE_TEXT)){
        $arreglo4[] = $row4->DOCTO_DEST_ID;
    }
    $filtro_interior = "";
    $filtro_interior .= " and d1.folio like '%".$_GET['folio']."%' and c.nombre like '%".$_GET['cliente']."%'";

    $query5 = "select
d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion,
(sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven) ) AS IMPORTE,
vcc.fecha_vencimiento, vcc.pctje_ven,
IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven)), 0) AS ANTICIPO,
((sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven) ), 0)) AS TOTAL
from doctos_cc d1, vencimientos_cargos_cc vcc, clientes c, importes_doctos_cc idc1
left join importes_doctos_cc idc2 on idc1.docto_cc_acr_id = idc2.docto_cc_acr_id and idc2.tipo_impte='R' and idc2.estatus!='P'
where
d1.docto_cc_id=idc1.docto_cc_id
and d1.docto_cc_id=vcc.docto_cc_id
and d1.cliente_id = c.cliente_id
and d1.naturaleza_concepto='C' and d1.cancelado='N'
".$filtro_interior."
group by d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion, vcc.fecha_vencimiento, vcc.pctje_ven
having (((sum( DISTINCT idc1.importe + idc1.impuesto) / (100 / vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) ), 0))) > 0
order by c.nombre";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("ID"=>$row5->DOCTO_CC_ID, "FOLIO"=>$row5->FOLIO, "CONCEPTO_CC"=>$row5->CONCEPTO_CC_ID,"FECHA"=>$row5->FECHA, "NOMBRE"=>utf8_encode($row5->NOMBRE), "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), "IMPORTE"=>$row5->IMPORTE, "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO, "NUMERO_COBROS"=>$row5->PCTJE_VEN, "ANTICIPO"=>$row5->ANTICIPO, "TOTAL"=>$row5->TOTAL);
    }

    $query6 = "select importes_doctos_cc.docto_cc_acr_id, doctos_cc.fecha_aplicacion, (importes_doctos_cc.importe + importes_doctos_cc.impuesto) as IMPORTE
    from doctos_cc, importes_doctos_cc
    where doctos_cc.docto_cc_id=importes_doctos_cc.docto_cc_id and doctos_cc.naturaleza_concepto='R' and doctos_cc.cancelado='N' and doctos_cc.estatus='P'";

    $result6 = ibase_query($conexion->getConexion(), $query6) or die(ibase_errmsg());

    $arreglo6 = array();
    $arreglo7 = array();

    while ($row6 = ibase_fetch_object ($result6, IBASE_TEXT)){
        $arreglo6[] = array("ID"=>$row6->DOCTO_CC_ACR_ID, "IMPORTE"=>$row6->IMPORTE, "FECHA"=>$row6->FECHA_APLICACION);
        $arreglo7[] = $row6->DOCTO_CC_ACR_ID;
    }

    foreach ($arreglo5 as $key=> $value) {


        if($empresa == 1)
            $arreglo5[$key]['EMPRESA'] = "NX";
        else if($empresa == 2)
            $arreglo5[$key]['EMPRESA'] = "NP";

        if(count($arreglo4) >  0)
            if(in_array($arreglo5[$key]['ID'], $arreglo4))
            {
                $arreglo5[$key]['FINALIZADO'] = 0;
            }else{
                $arreglo5[$key]['FINALIZADO'] = 1;
            }

        if(in_array($arreglo5[$key]['ID'], $arreglo7))
        {
            $index = array_search($arreglo5[$key]['ID'], $arreglo7);
            $arreglo5[$key]['DEPOSITO'] = $arreglo6[$index]['IMPORTE'];
            $arreglo5[$key]['FECHA_DEPOSITO'] = $arreglo6[$index]['FECHA'];
        }else{
            $arreglo5[$key]['DEPOSITO'] = 0;
            $arreglo5[$key]['FECHA_DEPOSITO'] = "";
        }

        if($arreglo5[$key]['DEPOSITO']!=0)
        {
            $arreglo5[$key]['DEPOSITO'] = ($arreglo5[$key]['DEPOSITO'] / (100/$arreglo5[$key]['NUMERO_COBROS']));
        }
        if($arreglo5[$key]['FECHA_DEPOSITO'] != "")
        {
            $valor_fecha = strtotime($arreglo5[$key]['FECHA_DEPOSITO']." 23:59:58");

            if($max_date < $valor_fecha)
                $max_date = $valor_fecha;
 
            if($min_date > $valor_fecha)
            {
                if($valor_fecha > $fecha_limite)
                    $min_date = $valor_fecha;
            }
        }else
        {
              $valor_aux_limite = strtotime($arreglo5[$key]['FECHA_VENCIMIENTO']." 23:59:59"); 
              if($min_date > $valor_aux_limite)
                {
                if($valor_aux_limite > $fecha_limite)
                    $min_date = $valor_aux_limite;
                }
        }
            
    
    return array("data" => $arreglo5, "max_fecha" => $max_date);
    }
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