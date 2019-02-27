<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos(1);


$campos_operador = array("OPERADORDEPARTAMENTO.ID",
                         "OPERADORDEPARTAMENTO.IDOPERADOR");

    $campos = array(
        "GARANTIA.ID",
        "GARANTIA.CLIENTE",
        "GARANTIA.FECHA",
        "GARANTIA.MATERIAL",
        "GARANTIA.FOLIO",
        "OPERADOR.ALIAS",
        "GARANTIA.CANTIDAD",
        "GARANTIA.UNIDADMEDIDA",
        "GARANTIA.MOTIVO",
        "GARANTIA.BORRADO");

    $join = array("OPERADOR","=", "OPERADOR.ID", "GARANTIA.IDOPERADOR");

    if(count($_POST["id"]))
    {
        $consulta = " AND GARANTIA.ID IN (".implode(",", $_POST["id"]).")";
    }else
    {
        $consulta .=" and GARANTIA.FECHA BETWEEN '".$_POST['fecha_inicio']."' and '".$_POST['fecha_fin']."' and (GARANTIA.CLIENTE LIKE '%".$_POST['clientefiltro']."%' or  GARANTIA.FOLIO LIKE '%".$_POST['clientefiltro']."%')";
    }
    $jsonGARANTIA = $conection->select_table($campos, "GARANTIA", $join,  $consulta, array("GARANTIA.FECHA DESC"), 0);

    //print_r($jsonGARANTIA);
 
    // Se crea el objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    // Se asignan las propiedades del libro
    $objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
        ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
        ->setTitle("Reporte de Garantias")
        ->setSubject("Reporte Excel")
        ->setDescription("Reporte de Garantias")
        ->setKeywords("reporte de Garantias")
        ->setCategory("Reporte MicrosipWeb");

    $tituloReporte = "Reporte de Garantias";
    $titulosColumnas = array('FOLIO', 'FECHA', 'CLIENTE', 'MATERIAL', 'CANTIDAD', 'MOTIVO', 'OPERADOR', "FECHA BORRADO");


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


    foreach($jsonGARANTIA as $key => $value)
    {
        $estatus = "PENDIENTE";
        if($value['GARANTIA.ESTATUS'] == 2)
            $estatus = "REALIZADO";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value['GARANTIA.FOLIO'])
            ->setCellValue('B'.$i,  $value['GARANTIA.FECHA'])
            ->setCellValue('C'.$i,  $value['GARANTIA.CLIENTE'])
            ->setCellValue('D'.$i,  $value['GARANTIA.MATERIAL'])
            ->setCellValue('E'.$i,  number_format($value['GARANTIA.CANTIDAD'],2)." (".$value['GARANTIA.UNIDADMEDIDA'].")")
            ->setCellValue('F'.$i,  $value['GARANTIA.MOTIVO'])
            ->setCellValue('G'.$i,  $value['OPERADOR.ALIAS'])
            ->setCellValue('H'.$i,  $value['GARANTIA.BORRADO']);
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

    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);
    //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

    for($i = 'A'; $i <= 'H'; $i++){
        $objPHPExcel->setActiveSheetIndex(0)
            ->getColumnDimension($i)->setAutoSize(TRUE);
    }

    // Se asigna el nombre a la hoja
    $objPHPExcel->getActiveSheet()->setTitle('GARANTIA');

    // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
    $objPHPExcel->setActiveSheetIndex(0);
    // Inmovilizar paneles
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="ReporteGarantia.xlsx"');


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    ob_start();
    $objWriter->save('php://output');
    exit;
?>
