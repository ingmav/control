<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos(1);


$campos_operador = array("OPERADORDEPARTAMENTO.ID",
                         "OPERADORDEPARTAMENTO.IDOPERADOR");

    $campos = array(
        "REQUISICIONES.ID",
        "REQUISICIONES.CLIENTE",
        "REQUISICIONES.FECHA",
        "REQUISICIONES.ESTATUS",
        "REQUISICIONES.FOLIO",
        "REQUISICIONES.EMPRESA",
        "REQUISICIONES.TIPO_DOCUMENTO",
        "REQUISICIONES.FORMA_PAGO",
        "OPERADOR.ALIAS",
        "REQUISICIONES.OBSERVACION",
        "REQUISICIONES.BORRADO",
        "REQUISICIONES_ARTICULOS.FACTURA",
        "REQUISICIONES_ARTICULOS.PROVEEDOR",
        "REQUISICIONES_ARTICULOS.ARTICULO",
        "REQUISICIONES_ARTICULOS.CANTIDAD",
        "REQUISICIONES_ARTICULOS.UNIDAD",
        "REQUISICIONES_ARTICULOS.IMPORTE"
        );

    $join = array("OPERADOR","=", "OPERADOR.ID", "REQUISICIONES.IDOPERADOR", "LEFT",
                  "REQUISICIONES_ARTICULOS","=", "REQUISICIONES_ARTICULOS.REQUISICIONESID", "REQUISICIONES.ID", "LEFT");

    if(count($_POST["id"]))
    {
        $consulta = " AND REQUISICIONES.ID IN (".implode(",", $_POST["id"]).")";
    }

    $consulta .= " AND REQUISICIONES.BORRADO IS NULL ";

    $jsonREQUISICIONES = $conection->select_table_advanced($campos, "REQUISICIONES", $join,  $consulta, array("REQUISICIONES.FOLIO DESC","REQUISICIONES.FECHA DESC"), 0);

    
   // Se crea el objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    // Se asignan las propiedades del libro
    $objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
        ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
        ->setTitle("Reporte de Requerimientos de Material")
        ->setSubject("Reporte Excel")
        ->setDescription("Reporte de Requerimientos de Material")
        ->setKeywords("reporte de requerimientos de Material")
        ->setCategory("Reporte MicrosipWeb");

    $tituloReporte = "Reporte de Requerimientos de Material";
    $titulosColumnas = array('FOLIO', 'FACTURA', 'FECHA', 'CLIENTE','PROVEEDOR', 'PAGO', 'CANTIDAD', 'IMPORTE', 'MATERIAL', 'OPERADOR', 'OBSERVACIONES', "ESTATUS");


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

        /*Estilo numero de factura*/
        $styleFactura = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );

    //Se agregan los datos de los alumnos
    $i = 4;


    foreach($jsonREQUISICIONES as $key => $value)
    {
        $estatus = "PENDIENTE";
        if($value['REQUISICIONES.ESTATUS'] == 2)
            $estatus = "SURTIDO";

        $folio = "";
        if($value['REQUISICIONES.TIPO_DOCUMENTO'] != 4)
        {
            if($value['REQUISICIONES.EMPRESA'] == 1)
                $folio = "NX";
            else if($value['REQUISICIONES.EMPRESA'] == 2)
                $folio = "NP";
        }    

        if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 1)
            $folio .= "F-".$value['REQUISICIONES.FOLIO'];
        else if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 2)
            $folio .= "R-".$value['REQUISICIONES.FOLIO'];
        else if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 3)
            $folio .= "V-".$value['REQUISICIONES.FOLIO'];
        else if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 4){
            $folio .= "I-".$value['REQUISICIONES.FOLIO'];
            $value['REQUISICIONES_ARTICULOS.FACTURA'] = "N/A";
        }
        
        $pago = "";
        if($value['REQUISICIONES.FORMA_PAGO'] == 1)
            $pago = "EFECTIVO";
        else if($value['REQUISICIONES.FORMA_PAGO'] == 2)
            $pago = "CHEQUE";
        else if($value['REQUISICIONES.FORMA_PAGO'] == 3)
            $pago .= "TARJETA";
        else if($value['REQUISICIONES.FORMA_PAGO'] == 4)
            $pago .= "CREDITO";
        else if($value['REQUISICIONES.FORMA_PAGO'] == 5)
            $pago .= "TRANSFERENCIA";
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $folio)
            ->setCellValue('B'.$i,  $value['REQUISICIONES_ARTICULOS.FACTURA'])
            ->setCellValue('C'.$i,  $value['REQUISICIONES.FECHA'])
            ->setCellValue('D'.$i,  $value['REQUISICIONES.CLIENTE'])
            ->setCellValue('E'.$i,  $value['REQUISICIONES_ARTICULOS.PROVEEDOR'])
            ->setCellValue('F'.$i,  $pago)
            ->setCellValue('G'.$i,  $value['REQUISICIONES_ARTICULOS.CANTIDAD']." ".$value['REQUISICIONES_ARTICULOS.UNIDAD'])
            ->setCellValue('H'.$i,  $value['REQUISICIONES_ARTICULOS.IMPORTE'])
            ->setCellValue('I'.$i,  $value['REQUISICIONES_ARTICULOS.ARTICULO'])
            ->setCellValue('J'.$i,  $value['OPERADOR.ALIAS'])
            ->setCellValue('K'.$i,  $value['REQUISICIONES.OBSERVACION'])
            //->setCellValue('K'.$i,  $value['REQUISICIONES.BORRADO'])
            ->setCellValue('L'.$i,  $estatus);

        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode("$#,##0.00"); 
        $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleFactura);
        $objPHPExcel->getActiveSheet()->getStyle('D',$i)->getAlignment()->setWrapText(true);
        //$objPHPExcel->getActiveSheet()->getStyle('E',$i)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('G',$i)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('I',$i)->getAlignment()->setWrapText(true);
        //$objPHPExcel->getActiveSheet()->getStyle('K',$i)->getAlignment()->setWrapText(true);

        
        $i++;
    }

    /*Formato de Columna*/
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(11    );


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
            'type'      => PHPExcel_Style_Fill::FILL_SOLID,
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
            'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color'     => array('argb' => 'FFFFFFFF')
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

    $objPHPExcel->getActiveSheet()->setAutoFilter('A3:L3');

    //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

    /*for($i = 'A'; $i <= 'L'; $i++){
        $objPHPExcel->setActiveSheetIndex(0)
            ->getColumnDimension($i)->setAutoSize(TRUE);
    }*/

    // Se asigna el nombre a la hoja
    $objPHPExcel->getActiveSheet()->setTitle('REQUISICIONESMATERIALES');

    // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
    $objPHPExcel->setActiveSheetIndex(0);
    // Inmovilizar paneles


    // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="ReporteREQUISICIONESes.xlsx"');


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    ob_start();
    $objWriter->save('php://output');
    exit;
?>
