<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$arreglo = backup();


// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("PRODUCCION") //Autor
    ->setLastModifiedBy("PRODUCCION") //Ultimo usuario que lo modificó
    ->setTitle("Backup CRM")
    ->setSubject("Reporte CRM")
    ->setDescription("Reporte de CRM")
    ->setKeywords("reporte CRM")
    ->setCategory("Reporte PRODUCCION");

$tituloReporte = "Reporte CRM";
$titulosColumnas = array('ID', 'CLIENTE', 'TIPO', 'CLASE', 'CLASIFICACIÓN', "ESTATUS", "PAGINA WEB", "CONTACTO PRINCIPAL", "SEGUNDO CONTACTO", "TELÉFONO 1", "TELÉFONO 2", "CORREO", "EMAILING");


$objPHPExcel->setActiveSheetIndex(0)
   ->mergeCells('A1:M1');

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


foreach($arreglo as $key => $value)
{
    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $value['ID'])
        ->setCellValue('B'.$i,  $value['CLIENTE'])
        ->setCellValue('C'.$i,  $value['TIPO'])
        ->setCellValue('D'.$i,  $value['CLASE'])
        ->setCellValue('E'.$i,  $value['SEGMENTO'])
        ->setCellValue('F'.$i,  $value['ESTATUS'])
        ->setCellValue('G'.$i,  $value['PAGINA'])
        ->setCellValue('H'.$i,  $value['CONTACTO1'])
        ->setCellValue('I'.$i,  $value['CONTACTO2'])
        ->setCellValue('J'.$i,  $value['TELEFONO1'])
        ->setCellValue('K'.$i,  $value['TELEFONO2'])
        ->setCellValue('L'.$i,  $value['CORREO'])
        ->setCellValue('M'.$i,  $value['EMAILING']);
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

$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:M3')->applyFromArray($estiloTituloColumnas);


for($i = 'A'; $i <= 'M'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)
        ->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Backup CRM');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="BackupCRM.xlsx"');
//header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
ob_start();
$objWriter->save('php://output');
exit;
?>

<?php
function backup()
{
    $conexion = new conexion_nexos($_SESSION['empresa']);

    $query1 = "select  
    CLIENTESCALL.ID AS ID,
    TIPOCLIENTESCALL.DESCRIPCION AS TIPO,
    CLIENTESCALL.NOMBRE AS CLIENTE,
    CLIENTESCALL.PAGINAWEB AS PAGINA,
    
    CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION CLASE,
    SEGMENTOCALL.DESCRIPCIONSEGMENTO AS SEGMENTO,
    CONTACTOCLIENTESCALL.CONTACTO1 AS CONTACTO1,
    CONTACTOCLIENTESCALL.CONTACTO2 AS CONTACTO2,
    CONTACTOCLIENTESCALL.DIRECCION AS DIRECCION,
    CONTACTOCLIENTESCALL.TELEFONO1 AS TELEFONO1,
    CONTACTOCLIENTESCALL.TELEFONO2 AS TELEFONO2,
    CONTACTOCLIENTESCALL.CORREO AS CORREO,
    CONTACTOCLIENTESCALL.HORARIO AS HORARIO,
    CONTACTOCLIENTESCALL.EMAILING AS EMAILING,
    ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO AS ESTATUS
    from CLIENTESCALL
    left join CONTACTOCLIENTESCALL on CONTACTOCLIENTESCALL.IDCLIENTESCALL = CLIENTESCALL.ID,
    TIPOCLIENTESCALL ,
    CLASIFICACIONCALL ,
    SEGMENTOCALL ,

    ESTATUSSEGUIMIENTO
    WHERE CLIENTESCALL.BORRADO IS NULL
    AND TIPOCLIENTESCALL.ID = CLIENTESCALL.IDTIPOCLIENTE
    AND CLASIFICACIONCALL.ID = CLIENTESCALL.IDCLASIFICACION
    AND SEGMENTOCALL.ID = CLIENTESCALL.IDSEGMENTO

    AND CLIENTESCALL.IDESTATUSSEGUIMIENTO = ESTATUSSEGUIMIENTO.ID
    order by CLIENTESCALL.nombre asc";

    $result = ibase_query($conexion->getConexion(), $query1) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        $arreglo1[$index]['ID'] = $row->ID;
        $arreglo1[$index]['TIPO'] = $row->TIPO;
        $arreglo1[$index]['CLIENTE'] = utf8_encode($row->CLIENTE);
        $arreglo1[$index]['PAGINA'] = $row->PAGINA;
        $arreglo1[$index]['CLASE'] = $row->CLASE;
        $arreglo1[$index]['SEGMENTO'] = $row->SEGMENTO;
        $arreglo1[$index]['CONTACTO1'] = utf8_encode($row->CONTACTO1);
        $arreglo1[$index]['CONTACTO2'] = utf8_encode($row->CONTACTO2);
        $arreglo1[$index]['DIRECCION'] = utf8_encode($row->DIRECCION);
        $arreglo1[$index]['TELEFONO1'] = utf8_encode($row->TELEFONO1);
        $arreglo1[$index]['TELEFONO2'] = utf8_encode($row->TELEFONO2);
        $arreglo1[$index]['CORREO'] = $row->CORREO;
        $arreglo1[$index]['HORARIO'] = $row->HORARIO;
        $arreglo1[$index]['EMAILING'] = $row->EMAILING;
        $arreglo1[$index]['ESTATUS'] = $row->ESTATUS;
    }
    return $arreglo1;
}
?>