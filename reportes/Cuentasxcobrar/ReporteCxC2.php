<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$fecha_maxima = "";
$fechalimite = strtotime ( '-60 day' , strtotime ( date('Y-m-d')." 00:00:00" ) ) ;

//$arreglo1 = ver_pagos(1, $fechalimite);
$arreglo2 = ver_pagos(2, $fechalimite);
//$arreglo3 = array_merge($arreglo1['data'], $arreglo2['data']);
$arreglo3 = $arreglo2['data'];
//$arreglo4 = array_merge($arreglo1['facturas_problematicas'], $arreglo2['facturas_problematicas']);
$arreglo4 = $arreglo2['facturas_problematicas'];


//$fecha_maxima_reporte = ($arreglo1['max_fecha'] > $arreglo2['max_fecha']) ? $arreglo1['max_fecha'] : $arreglo2['max_fecha'];
$fecha_maxima_reporte = $arreglo2['max_fecha'];


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
$titulosColumnas = array('FOLIO', 'CLIENTE', 'DESCRIPCIÓN', 'FACTURACIÓN', 'VENCIMIENTO', "DIAS EN PROCESO", "FINALIZACION", "MONTO TOTAL", "ANTICIPO", "SALDO TOTAL", "MONTO POR COBRAR", "FECHA DE COBRO", "SISTEMA");


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
    ->setCellValue('L3',  $titulosColumnas[11])
    ->setCellValue('M3',  $titulosColumnas[12]);


//Se agregan los datos de los alumnos
$i = 4;

$importe_general        = 0;
$operador_si            = 0;
$operador_no            = 0;
$vencido_cxc            = 0;
$anticipos              = 0;
$no_registrado          = 0;
$vencido_facturado      = 0;
$vencido_no_facturado   = 0;
$cxc_60_dias            = 0;
$factura_si_60_dias     = 0;
$factura_no_60_dias     = 0;


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
    $arreglo_semanas_proyeccion[$index]['fecha_inicial']                            = $fecha_inicial;
    $arreglo_semanas_proyeccion[$index]['fecha_final']                              = $fecha_final;
    $arreglo_semanas_proyeccion[$index]['acumulado']                                = 0;
    $arreglo_semanas_proyeccion[$index]['acumulado_factura_finalizado']             = 0;
    $arreglo_semanas_proyeccion[$index]['acumulado_factura_no_finalizado']          = 0;

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

    $datetime1 = date_create($value['FECHA']);
    $datetime2 = date_create(date("Y-m-d"));
    $interval = date_diff($datetime1, $datetime2);
    $dias_proceso = $interval->format('%a');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['EMPRESA']."-".intval(substr($value['FOLIO'],1)))
        ->setCellValue('B'.$i,  $value['NOMBRE'])
        ->setCellValue('C'.$i,  $value['DESCRIPCION'])
        ->setCellValue('D'.$i,  $value['FECHA'])
        ->setCellValue('E'.$i,  $value['FECHA_VENCIMIENTO'])
        ->setCellValue('F'.$i,  $dias_proceso)
        ->setCellValue('G'.$i,  $finalizado)
        ->setCellValue('H'.$i,  $value['IMPORTE'])
        ->setCellValue('I'.$i,  $value['ANTICIPO'])
        ->setCellValue('J'.$i,  $value['TOTAL'])
        ->setCellValue('K'.$i,  $value['DEPOSITO'])
        ->setCellValue('L'.$i,  $value['FECHA_DEPOSITO'])
        ->setCellValue('M'.$i,  $sistema);

        $importe_general    += $value['IMPORTE'];
        $anticipos          += $value['ANTICIPO'];
        

        if($finalizado == "SI")
            $operador_si += $value['TOTAL'];
        else if($finalizado == "NO")
            $operador_no += $value['TOTAL'];


        $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getNumberFormat()->setFormatCode("#,##0.00");

        if($value['FECHA_DEPOSITO'] != ""){
            $no_registrado      += ($value['IMPORTE'] - $value['ANTICIPO'] - $value['DEPOSITO']);
            $fecha_deposito = strtotime(date($value['FECHA_DEPOSITO']." 00:00:01"));

           if($fecha_anterior_lunes > $fecha_deposito){
                if($fecha_deposito < $fechalimite)
                    $cxc_60_dias += $value['DEPOSITO']; 
                else        
                    $vencido_cxc += $value['DEPOSITO'];
            }else{
                $index = 0;
                while($arreglo_semanas_proyeccion[$index]['fecha_final'] < $fecha_deposito)
                {
                    $index++;
                }
                $arreglo_semanas_proyeccion[$index]['acumulado'] += $value['DEPOSITO'];
            }
        }else
        {
            if($finalizado == "SI")
            {
                $fecha_vencimiento_finalizado = strtotime(date($value['FECHA_VENCIMIENTO']." 00:00:01"));

               if($fecha_anterior_lunes > $fecha_vencimiento_finalizado){


                if($fecha_vencimiento_finalizado < $fechalimite)
                    $factura_si_60_dias += $value['TOTAL']; 
                else 
                   $vencido_facturado += $value['TOTAL'];
                }else{
                    $index = 0;
                    while($arreglo_semanas_proyeccion[$index]['fecha_final'] < $fecha_vencimiento_finalizado)
                    {
                        $index++;
                    }
                    $arreglo_semanas_proyeccion[$index]['acumulado_factura_finalizado'] += $value['TOTAL'];
                }
            }else if($finalizado == "NO")
            {
                $fecha_vencimiento_proyeccion = strtotime(date($value['FECHA_VENCIMIENTO']." 00:00:01"));


               if($fecha_anterior_lunes > $fecha_vencimiento_proyeccion){
                    if($fecha_vencimiento_proyeccion < $fechalimite)
                        $factura_no_60_dias += $value['TOTAL']; 
                    else 
                       $vencido_no_facturado += $value['TOTAL'];
                }else{
                    $index = 0;
                    
                    while($arreglo_semanas_proyeccion[$index]['fecha_final'] < $fecha_vencimiento_proyeccion)
                    {
                        $index++;
                    }


                    $arreglo_semanas_proyeccion[$index]['acumulado_factura_no_finalizado'] += $value['TOTAL'];
                    
                
                }
            }
        }

           
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
$objPHPExcel->getActiveSheet()->getStyle('D1:I1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('D2:I2')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('K1:N1')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('K2:N2')->applyFromArray($estiloTituloColumnas);
        $objPHPExcel->getActiveSheet()->setTitle('RESUMEN');
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:B1');
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('D1:I1');
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('K1:N1');

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "RESUMEN");

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D'.$j,  "PROYECCIÓN"); 

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('K'.$j,  "FACTURAS VENCIDAS MÁS DE 60 DIAS DE ATRASO");                

$j++;

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "SUB TOTAL CARTERA")
        ->setCellValue('B'.$j,  $importe_general);   

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D'.$j,  "SEMANA")
        ->setCellValue('E'.$j,  "DE")
        ->setCellValue('F'.$j,  "HASTA")
        ->setCellValue('G'.$j,  "FACTURAS CON FECHA DE DEPÓSITO")             
        ->setCellValue('H'.$j,  "FACTURAS FINALIZADOS SIN FECHA DE COBRO")             
        ->setCellValue('I'.$j,  "FACTURAS NO FINALIZADOS SIN FECHA DE COBRO");  

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('K'.$j,  "FOLIO")
        ->setCellValue('L'.$j,  "CLIENTE")
        ->setCellValue('M'.$j,  "VENCIMIENTO")
        ->setCellValue('N'.$j,  "MONTO");                   

$j++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "ANTICIPOS")
        ->setCellValue('B'.$j,  $anticipos)
        ->setCellValue('D'.$j,  "VENCIDOS + 60 DÍAS");

$j++;
$total_cartera = ($importe_general - $anticipos);
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$j,  "TOTAL CARTERA")
        ->setCellValue('B'.$j,  $total_cartera)        
        ->setCellValue('D'.$j,  "VENCIDOS");        

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D3',  "VENCIDO + 60 DÍAS")
        ->setCellValue('E3',  "--")
        ->setCellValue('F3',  "--")
        ->setCellValue('G3',  $cxc_60_dias)
        ->setCellValue('H3',  $factura_si_60_dias) 
        ->setCellValue('I3',  $factura_no_60_dias ); 
        $objPHPExcel->getActiveSheet()->getStyle('G3')->getNumberFormat()->setFormatCode("$#,##0.00");    
        $objPHPExcel->getActiveSheet()->getStyle('H3')->getNumberFormat()->setFormatCode("$#,##0.00");    
        $objPHPExcel->getActiveSheet()->getStyle('I3')->getNumberFormat()->setFormatCode("$#,##0.00");

$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D4',  "VENCIDOS")
        ->setCellValue('E4',  "--")
        ->setCellValue('F4',  "--")
        ->setCellValue('G4',  $vencido_cxc)
        ->setCellValue('H4',  $vencido_facturado) 
        ->setCellValue('I4',  $vencido_no_facturado ); 
        $objPHPExcel->getActiveSheet()->getStyle('G4')->getNumberFormat()->setFormatCode("$#,##0.00");    
        $objPHPExcel->getActiveSheet()->getStyle('H4')->getNumberFormat()->setFormatCode("$#,##0.00");    
        $objPHPExcel->getActiveSheet()->getStyle('I4')->getNumberFormat()->setFormatCode("$#,##0.00");

$count_subtotal = 0;
$acumulado_cxc = $cxc_60_dias + $vencido_cxc;
$acumulado_fxc = $factura_si_60_dias + $vencido_facturado;
$acumulado_fxf = $factura_no_60_dias + $vencido_no_facturado;


for($x = 5; $x<=(count($arreglo_semanas_proyeccion)+4); $x++)
{
    $index = $x-5;
    $semana = $x-4;
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('D'.$x,  "SEMANA ".$semana)
        ->setCellValue('E'.$x,  date("Y-m-d", $arreglo_semanas_proyeccion[$index]['fecha_inicial']))
        ->setCellValue('F'.$x,  date("Y-m-d", $arreglo_semanas_proyeccion[$index]['fecha_final']))
        ->setCellValue('G'.$x,  $arreglo_semanas_proyeccion[$index]['acumulado'])
        ->setCellValue('H'.$x,  $arreglo_semanas_proyeccion[$index]['acumulado_factura_finalizado']) 
        ->setCellValue('I'.$x,  $arreglo_semanas_proyeccion[$index]['acumulado_factura_no_finalizado']); 

    $objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getNumberFormat()->setFormatCode("$#,##0.00");    
    $objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getNumberFormat()->setFormatCode("$#,##0.00");    
    $objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode("$#,##0.00");
    $count_subtotal = $x;

    $acumulado_cxc += $arreglo_semanas_proyeccion[$index]['acumulado'];
    $acumulado_fxc += $arreglo_semanas_proyeccion[$index]['acumulado_factura_finalizado'];
    $acumulado_fxf += $arreglo_semanas_proyeccion[$index]['acumulado_factura_no_finalizado'];
}

$acumuldo_vencimiento_facturas = 0;
$count_problemas = 0;
for($index = 0; $index<(count($arreglo4)); $index++)
{
    $x = $index+3;
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('K'.$x,  $arreglo4[$index]['EMPRESA']."-".intval($arreglo4[$index]['FOLIO']))
        ->setCellValue('L'.$x,  $arreglo4[$index]['NOMBRE'])
        ->setCellValue('M'.$x,  $arreglo4[$index]['FECHA_VENCIMIENTO'])
        ->setCellValue('N'.$x,  $arreglo4[$index]['TOTAL']); 

    $objPHPExcel->getActiveSheet()->getStyle('N'.$x)->getNumberFormat()->setFormatCode("$#,##0.00"); 
    $acumuldo_vencimiento_facturas += $arreglo4[$index]['TOTAL'];
    $count_problemas = $x;
}
$count_problemas++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('M'.$count_problemas,  "TOTAL")
        ->setCellValue('N'.$count_problemas,  $acumuldo_vencimiento_facturas); 

$objPHPExcel->getActiveSheet()->getStyle('N'.$count_problemas)->getNumberFormat()->setFormatCode("$#,##0.00"); 
   

$count_subtotal++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('F'.$count_subtotal,  "SUBTOTAL")
        ->setCellValue('G'.$count_subtotal,  $acumulado_cxc)
        ->setCellValue('H'.$count_subtotal,  $acumulado_fxc) 
        ->setCellValue('I'.$count_subtotal,  $acumulado_fxf ); 

$objPHPExcel->getActiveSheet()->getStyle('G'.$count_subtotal)->getNumberFormat()->setFormatCode("$#,##0.00");    
$objPHPExcel->getActiveSheet()->getStyle('H'.$count_subtotal)->getNumberFormat()->setFormatCode("$#,##0.00");    
$objPHPExcel->getActiveSheet()->getStyle('I'.$count_subtotal)->getNumberFormat()->setFormatCode("$#,##0.00");

$count_subtotal++;
$objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('H'.$count_subtotal,  "TOTAL")
        ->setCellValue('I'.$count_subtotal,  ($acumulado_fxf + $acumulado_fxc + $acumulado_cxc) );  
$objPHPExcel->getActiveSheet()->getStyle('I'.$count_subtotal)->getNumberFormat()->setFormatCode("$#,##0.00");               


for($h = 2; $h<=7; $h++)
{
    $objPHPExcel->getActiveSheet()->getStyle('B'.$h)->getNumberFormat()->setFormatCode("$#,##0.00");

}

$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(D)->setWidth(20);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(E)->setWidth(12);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(F)->setWidth(12);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(G)->setWidth(16);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(H)->setWidth(16);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(I)->setWidth(18);

$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(K)->setWidth(12);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(L)->setWidth(12);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(M)->setWidth(12);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension(N)->setWidth(12);

for($i = 'A'; $i <= 'B'; $i++){
    $objPHPExcel->setActiveSheetIndex(1)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

/*REmisiones Canceladas*/
$objPHPExcel->createSheet(2);
$objPHPExcel->setActiveSheetIndex(2);

$objPHPExcel->getActiveSheet()->setTitle('REMISIONES');
$objPHPExcel->setActiveSheetIndex(2)
   ->mergeCells('A1:Q1');

$REMISIONES_NEXOS = cargaRemisiones(1);
$REMISIONES_NEXPRINT = cargaRemisiones(2);

$REMISIONES_COMPLETO = array_merge($REMISIONES_NEXOS, $REMISIONES_NEXPRINT);

$tituloReporte = "Remisiones Canceladas";
$titulosColumnas = array('FOLIO', 'FECHA', 'CLIENTE', 'IMPORTE TOTAL', 'INICIALIZADO', 'DISENO', 'FINALIZADO', "IMPRESION", "FINALIZADO", "MAQUILAS", "FINALIZADO", "PREPARACION", "FINALIZADO", "INSTALACION", "FINALIZADO", "ENTREGA", 'FINALIZADO', 'TERMINADO');


/*$objPHPExcel->setActiveSheetIndex(2)
   ->mergeCells('A1:L1');*/

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(2)
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
    ->setCellValue('L3',  $titulosColumnas[11])
    ->setCellValue('M3',  $titulosColumnas[12])
    ->setCellValue('N3',  $titulosColumnas[13])
    ->setCellValue('O3',  $titulosColumnas[14])
    ->setCellValue('P3',  $titulosColumnas[15])
    ->setCellValue('Q3',  $titulosColumnas[16])
    ->setCellValue('R3',  $titulosColumnas[17]);
$i = 4;
foreach ($REMISIONES_COMPLETO as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$i,  $value['FOLIO'])
        ->setCellValue('B'.$i,  $value['FECHA'])
        ->setCellValue('C'.$i,  $value['NOMBRE'])
        ->setCellValue('D'.$i,  $value['IMPORTE_TOTAL'])
        ->setCellValue('E'.$i,  $value['INICIALIZADO'])
        ->setCellValue('F'.$i,  $value['DISENO'])
        ->setCellValue('G'.$i,  $value['F_DISENO'])
        ->setCellValue('H'.$i,  $value['IMPRESION'])
        ->setCellValue('I'.$i,  $value['F_IMPRESION'])
        ->setCellValue('J'.$i,  $value['MAQUILAS'])
        ->setCellValue('K'.$i,  $value['F_MAQUILAS'])
        ->setCellValue('L'.$i,  $value['PREPARACION'])
        ->setCellValue('M'.$i,  $value['F_PREPARACION'])
        ->setCellValue('N'.$i,  $value['INSTALACION'])
        ->setCellValue('O'.$i,  $value['F_INSTALACION'])
        ->setCellValue('P'.$i,  $value['ENTREGA'])
        ->setCellValue('Q'.$i,  $value['F_ENTREGA'])
        ->setCellValue('R'.$i,  $value['FINALIZADO']);
        $i++;
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode("$#,##0.00"); 
}    
    
$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:Q3')->applyFromArray($estiloTituloColumnas);
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

for($i = 'A'; $i <= 'Q'; $i++){
    $objPHPExcel->setActiveSheetIndex(2)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}    


$objPHPExcel->setActiveSheetIndex(0);
///////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteCxC.xlsx"');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;

///////////////////////////////////////////////////////////////////////////////////////////
?>
<?php
function cargaRemisiones($empresa)
{
    $conexion = new conexion_nexos($empresa);

    $query = "select 
 DV.FOLIO,
 DV.FECHA,
 (DV.importe_neto + DV.total_impuestos) AS IMPORTE_TOTAL,
 C.nombre,
 TB.diseno,
 (SELECT IIF ((P.idestatus = 2), 'SI', 'NO') FROM PRODUCCION P WHERE P.iddepartamento=2 AND P.idtableroproduccion = TB.ID) AS F_DISENO,
 TB.impresion,
 (SELECT IIF ((P.idestatus = 2), 'SI', 'NO') FROM PRODUCCION P WHERE P.iddepartamento=3 AND P.idtableroproduccion = TB.ID) AS F_IMPRESION,
 TB.maquilas,
 (SELECT IIF ((P.idestatus = 2), 'SI', 'NO') FROM PRODUCCION P WHERE P.iddepartamento=8 AND P.idtableroproduccion = TB.ID) AS F_MAQUILAS,
 TB.preparacion,
 (SELECT IIF ((P.idestatus = 2), 'SI', 'NO') FROM PRODUCCION P WHERE P.iddepartamento=9 AND P.idtableroproduccion = TB.ID) AS F_PREPARACION,
 TB.instalacion,
 (SELECT IIF ((P.idestatus = 2), 'SI', 'NO') FROM PRODUCCION P WHERE P.iddepartamento=4 AND P.idtableroproduccion = TB.ID) AS F_INSTACION,
 TB.entrega,
 (SELECT IIF ((P.idestatus = 2), 'SI', 'NO') FROM PRODUCCION P WHERE P.iddepartamento=6 AND P.idtableroproduccion = TB.ID) AS F_ENTREGA,
 (SELECT IIF (COUNT(*)>0, 'SI', 'NO') FROM DOCUMENTOSFINALIZADOS DF WHERE DF.idtableroproduccion = TB.id) AS FINALIZADO,
 (TB.id) AS INICIALIZADO
 from doctos_ve DV
left join tableroproduccion TB ON DV.docto_ve_id = TB.docto_ve_id,
clientes C
WHERE
DV.cliente_id = C.cliente_id
AND DV.tipo_docto='R'
AND DV.estatus='C' AND DV.fecha >= '".date("Y").".01.01'
ORDER BY DV.FOLIO";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $name_empresa = "NX-";
        if($empresa == 2)
            $name_empresa = "NP-";

        if($row->DISENO == 1 && $row->F_DISENO==""  || $row->DISENO == 0 && $row->F_DISENO=="")
                $row->F_DISENO = "NO";
        if($row->IMPRESION == 1 && $row->F_IMPRESION=="" || $row->IMPRESION == 0 && $row->F_IMPRESION=="")
            $row->F_IMPRESION = "NO";
        if($row->MAQUILAS == 1 && $row->F_MAQUILAS=="" || $row->MAQUILAS == 0 && $row->F_MAQUILAS=="")
            $row->F_MAQUILAS = "NO";
        if($row->PREPARACION == 1 && $row->F_PREPARACION=="" || $row->PREPARACION == 0 && $row->F_PREPARACION=="")
            $row->F_PREPARACION = "NO";
        if($row->INSTALACION == 1 && $row->F_INSTALACION=="" || $row->INSTALACION == 0 && $row->F_INSTALACION=="")
            $row->F_INSTALACION = "NO";   
        if($row->ENTREGA == 1 && $row->F_ENTREGA=="" || $row->ENTREGA == 0 && $row->F_ENTREGA=="")
            $row->F_ENTREGA = "NO"; 
        if($row->FINALIZADO == "")
            $row->FINALIZADO = "NO";       

        if($row->DISENO == 1)
            $row->DISENO = "SI";
        else
            $row->DISENO = "NO";
        if($row->IMPRESION == 1)
            $row->IMPRESION = "SI";
        else
            $row->IMPRESION = "NO";
        if($row->MAQUILAS == 1)
            $row->MAQUILAS = "SI";
        else
            $row->MAQUILAS = "NO";
        if($row->PREPARACION == 1)
            $row->PREPARACION = "SI";
        else
            $row->PREPARACION = "NO";
        if($row->INSTALACION == 1)
            $row->INSTALACION = "SI";
        else
            $row->INSTALACION = "NO";
        if($row->ENTREGA == 1)
            $row->ENTREGA = "SI";
        else
            $row->ENTREGA = "NO";   

        if($row->INICIALIZADO != null)
            $row->INICIALIZADO = "SI";
        else                  
            $row->INICIALIZADO = "NO";    

        $arreglo1[$index]['FOLIO']          = $name_empresa.(int)$row->FOLIO;
        $arreglo1[$index]['FECHA']  = $row->FECHA;
        $arreglo1[$index]['IMPORTE_TOTAL']  = $row->IMPORTE_TOTAL;
        $arreglo1[$index]['NOMBRE']         = $row->NOMBRE;
        $arreglo1[$index]['DISENO']         = $row->DISENO;
        $arreglo1[$index]['F_DISENO']       = $row->F_DISENO;
        $arreglo1[$index]['IMPRESION']      = $row->IMPRESION;
        $arreglo1[$index]['F_IMPRESION']    = $row->F_IMPRESION;
        $arreglo1[$index]['MAQUILAS']       = $row->MAQUILAS;
        $arreglo1[$index]['F_MAQUILAS']     = $row->F_MAQUILAS;
        $arreglo1[$index]['PREPARACION']    = $row->PREPARACION;
        $arreglo1[$index]['F_PREPARACION']  = $row->F_PREPARACION;
        $arreglo1[$index]['INSTALACION']    = $row->INSTALACION;
        $arreglo1[$index]['F_INSTALACION']  = $row->F_INSTALACION;
        $arreglo1[$index]['ENTREGA']        = $row->ENTREGA;
        $arreglo1[$index]['F_ENTREGA']      = $row->F_ENTREGA;
        $arreglo1[$index]['FINALIZADO']     = $row->FINALIZADO;
        $arreglo1[$index]['INICIALIZADO']   = $row->INICIALIZADO;
    }
    
    $conexion = null;
    return $arreglo1;
}


function ver_pagos($empresa, $fecha_limte)
{
    $max_date = 0;
    $array_facturas_problematicas = array();
    $conexion = new conexion_nexos($empresa);

    //$query = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";
    $query = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.FINALIZAR_PROCESO=0  ";
    
    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $arreglo1[] = $row->DOCTO_VE_ID;
    }

    
    //$query2 = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='R' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";
    $query2 = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='R' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.FINALIZAR_PROCESO=0  ";

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

    

    if(count($arreglo1) > 0)
    {
        $query4 = "select DOCTO_DEST_ID from DOCTOS_ENTRE_SIS WHERE CLAVE_SIS_DEST='CC' AND CLAVE_SIS_FTE='VE' AND DOCTO_FTE_ID IN (".implode(",", $arreglo1).")";

        $result4 = ibase_query($conexion->getConexion(), $query4) or die(ibase_errmsg());

        $arreglo4 = Array();

        while ($row4 = ibase_fetch_object ($result4, IBASE_TEXT)){
            $arreglo4[] = $row4->DOCTO_DEST_ID;
        }
    }

    
    $filtro_interior = "";
    $filtro_interior .= " and d1.folio like '%".$_GET['folio']."%' and c.nombre like '%".$_GET['cliente']."%'";

    /*$query5 = "select doctos_cc.docto_cc_id, doctos_cc.folio, doctos_cc.fecha, clientes.nombre, doctos_cc.descripcion, sum(importes_doctos_cc.importe+ importes_doctos_cc.impuesto) AS IMPORTE, vencimientos_cargos_cc.fecha_vencimiento, vencimientos_cargos_cc.pctje_ven from doctos_cc, clientes, importes_doctos_cc, vencimientos_cargos_cc
    where doctos_cc.docto_cc_id not in
    (select ids.docto_cc_acr_id from doctos_cc d, importes_doctos_cc ids where d.docto_cc_id=ids.docto_cc_id and d.naturaleza_concepto='R' and ids.estatus!='P')
    and doctos_cc.docto_cc_id=importes_doctos_cc.docto_cc_id and clientes.cliente_id = doctos_cc.cliente_id and doctos_cc.docto_cc_id=vencimientos_cargos_cc.docto_cc_id and doctos_cc.naturaleza_concepto='C' and doctos_cc.cancelado='N' ".$filtro_interior."
    group by doctos_cc.docto_cc_id,doctos_cc.folio, doctos_cc.fecha, clientes.nombre, doctos_cc.descripcion, doctos_cc.folio, vencimientos_cargos_cc.fecha_vencimiento, vencimientos_cargos_cc.pctje_ven
    order by clientes.nombre ";*/

    /*$query5 = "select
d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion,
(sum( DISTINCT idc1.importe + idc1.impuesto) / (100 / vcc.pctje_ven)) AS IMPORTE,
vcc.fecha_vencimiento, vcc.pctje_ven,
IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven)), 0) AS ANTICIPO,
((sum( DISTINCT idc1.importe + idc1.impuesto) / (100 / vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven) ), 0)) AS TOTAL
from doctos_cc d1, vencimientos_cargos_cc vcc, clientes c, importes_doctos_cc idc1
left join importes_doctos_cc idc2 on idc1.docto_cc_acr_id = idc2.docto_cc_acr_id and idc2.tipo_impte='R' and idc2.estatus!='P' and idc2.cancelado!='S'
where
d1.docto_cc_id=idc1.docto_cc_id
and d1.docto_cc_id=vcc.docto_cc_id
and d1.cliente_id = c.cliente_id
and d1.naturaleza_concepto='C' and d1.cancelado='N'
".$filtro_interior."
and d1.FECHA >='01-01-2019'
group by d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion, vcc.fecha_vencimiento, vcc.pctje_ven
having (((sum( DISTINCT idc1.importe + idc1.impuesto) / (100 / vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) ), 0))) > 0
order by c.nombre";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("ID"=>$row5->DOCTO_CC_ID, "FOLIO"=>$row5->FOLIO, "CONCEPTO_CC"=>$row5->CONCEPTO_CC_ID,"FECHA"=>$row5->FECHA, "NOMBRE"=>utf8_encode($row5->NOMBRE), "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), "IMPORTE"=>$row5->IMPORTE, "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO, "NUMERO_COBROS"=>$row5->PCTJE_VEN, "ANTICIPO"=>$row5->ANTICIPO, "TOTAL"=>$row5->TOTAL);
    }*/

    $query5 = "select
    d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion,
    (sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven) ) AS IMPORTE,
    vcc.fecha_vencimiento, vcc.pctje_ven,
    0 AS ANTICIPO,
    0 AS TOTAL,
    idc1.docto_cc_acr_id
    from doctos_cc d1, vencimientos_cargos_cc vcc, clientes c, importes_doctos_cc idc1
    where
    d1.docto_cc_id=idc1.docto_cc_id
    and d1.docto_cc_id=vcc.docto_cc_id
    and d1.cliente_id = c.cliente_id
    and d1.naturaleza_concepto='C' and d1.cancelado='N'
    and d1.FECHA >='01-01-2019'
    
    group by d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion, vcc.fecha_vencimiento, vcc.pctje_ven,idc1.docto_cc_acr_id
    order by c.nombre";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $anticipo_2 = 0;
        $query5_1 = "select
        (sum(idc2.importe + idc2.impuesto) / ( 100 /  ".$row5->PCTJE_VEN.")) AS ANTICIPO
        from importes_doctos_cc idc2
        where
        idc2.docto_cc_acr_id  = ".$row5->DOCTO_CC_ACR_ID." and idc2.tipo_impte='R' and idc2.estatus!='P' and idc2.cancelado!='S'";
        
        $result5_1 = ibase_query($conexion->getConexion(), $query5_1) or die(ibase_errmsg());
        
        while ($row5_1 = ibase_fetch_object ($result5_1, IBASE_TEXT)){
            $anticipo_2 = $row5_1->ANTICIPO;
        }
        //echo $row5->IMPORTE."--".$anticipo_2." = ".($row5->IMPORTE - $anticipo_2)."<br>"; 
        if(($row5->IMPORTE - $anticipo_2) > 0)
        {
            $total = $row5->IMPORTE - $anticipo_2;
            $arreglo5[] = array("ID"=>$row5->DOCTO_CC_ID, "FOLIO"=>$row5->FOLIO, "CONCEPTO_CC"=>$row5->CONCEPTO_CC_ID,"FECHA"=>$row5->FECHA, "NOMBRE"=>utf8_encode($row5->NOMBRE), "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), "IMPORTE"=>$row5->IMPORTE, "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO, "NUMERO_COBROS"=>$row5->PCTJE_VEN, "ANTICIPO"=>$anticipo_2, "TOTAL" => $total);//, "TOTAL"=>$row5->TOTAL);
        }
    }

    //print_r($arreglo5);
    //return array("data" => $arreglo5);
    //exit;
    

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

            if($valor_fecha < $fecha_limte)
                $array_facturas_problematicas[] = $arreglo5[$key];
        }else
        {
            $fecha_vencimiento_evaluar = strtotime($arreglo5[$key]['FECHA_VENCIMIENTO']." 00:00:00");
            
            if($max_date < $fecha_vencimiento_evaluar)
                $max_date = $fecha_vencimiento_evaluar;

            if($fecha_vencimiento_evaluar < $fecha_limte)
                $array_facturas_problematicas[] = $arreglo5[$key];
        }
            
    }

    $conexion = null;
    return array("data" => $arreglo5, "max_fecha" => $max_date, "facturas_problematicas"=> $array_facturas_problematicas);
        
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