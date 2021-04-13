<?php

include("../../clases/conexion.php");

/** Se agrega la libreria PHPExcel */
require_once '../../PHPExcel/PHPExcel.php';

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos(1);


$campos_operador = array("OPERADORDEPARTAMENTO.ID",
                         "OPERADORDEPARTAMENTO.IDOPERADOR");
    $consulta = "";
    $campos = array(
        "REQUISICIONES.ID",
        "REQUISICIONES.CLIENTE",
        "REQUISICIONES.FECHA",
        "REQUISICIONES.ESTATUS",
        "REQUISICIONES.FOLIO",
        "REQUISICIONES.EMPRESA",
        "REQUISICIONES.TIPO_DOCUMENTO",
        "REQUISICIONES.FORMA_PAGO",
        "OPERADOR.ALIAS"
        );

    $join = array("OPERADOR","=", "OPERADOR.ID", "REQUISICIONES.IDOPERADOR", "LEFT");
                  //"REQUISICIONES_ARTICULOS","=", "REQUISICIONES_ARTICULOS.REQUISICIONESID", "REQUISICIONES.ID", "LEFT");

    if(count($_POST["id"]))
    {
        $consulta = " AND REQUISICIONES.ID IN (".implode(",", $_POST["id"]).")";
    }
    //
    $consulta .=" AND REQUISICIONES.ESTATUS=1";
    
    $datosNexos6 = extraeDatosInterno($conection, $arreglo, $campos, $join, $consulta);
    $datosNexos1 = extraeDatos($conection, 1, $arreglo, $campos, $join, 1, $consulta);
    $datosNexos2 = extraeDatos($conection, 1, $arreglo, $campos, $join, 2, $consulta);
    $datosNexos3 = extraeDatos($conection, 2, $arreglo, $campos, $join, 1, $consulta);
    $datosNexos4 = extraeDatos($conection, 2, $arreglo, $campos, $join, 2, $consulta);
    $datosNexos5 = extraeDatos($conection, 2, $arreglo, $campos, $join, 3, $consulta);
    
    $datosNexos = array_merge($datosNexos1, $datosNexos2, $datosNexos3, $datosNexos4, $datosNexos5, $datosNexos6);
   
    $objPHPExcel = new PHPExcel();

    // Se asignan las propiedades del libro
    $objPHPExcel->getProperties()->setCreator("MicrosipWeb") //Autor
        ->setLastModifiedBy("MicrosipWeb") //Ultimo usuario que lo modificó
        ->setTitle("Reporte de Requerimientos de Material-Ventas")
        ->setSubject("Reporte Excel")
        ->setDescription("Reporte de Requerimientos de Material-Ventas")
        ->setKeywords("reporte de requerimientos de Material-Ventas")
        ->setCategory("Reporte MicrosipWeb");

    $tituloReporte = "Reporte de Requerimientos de Material";
    $titulosColumnas = array('FOLIO', 'FECHA', 'CLIENTE', 'FORMA DE PAGO', 'OPERADOR', 'OBSERVACIONES', "ESTATUS");    
            

    $objPHPExcel->setActiveSheetIndex(0)
       ->mergeCells('A1:G1');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',$tituloReporte);  
    
    //Se agregan los datos de los alumnos
    $i = 3;

    $tipo = 0;
    $folio = 0;
    $folio_pivote = 0;
    $cambio_variable = 0;
    $empresa_pivote = "";
    $ejecution = 0;

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

        $estiloSubTitulo = array(
                'font' => array(
                    'name'      => 'Arial',
                    'color'     => array(
                        'rgb' => '000000'
                    )
                ),
                'fill'  => array(
                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'ff4b4b'
                    )
                ),
                'borders' => array(
                    'left'     => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN ,
                        'color' => array(
                            'rgb' => '3a2a47'
                        )
                    )
                )
            );

        $estiloEgreso = array(
                'font' => array(
                    'name'      => 'Arial',
                    'color'     => array(
                        'rgb' => '000000'
                    )
                ),
                'fill'  => array(
                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'EFEFEF'
                    )
                ),
                'borders' => array(
                    'left'     => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN ,
                        'color' => array(
                            'rgb' => '3a2a47'
                        )
                    )
                )
            );

        $estiloIngreso = array(
                'font' => array(
                    'name'      => 'Arial',
                    'color'     => array(
                        'rgb' => '000000'
                    )
                ),
                'fill'  => array(
                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'EFEFEF'
                    )
                ),
                'borders' => array(
                    'left'     => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN ,
                        'color' => array(
                            'rgb' => '3a2a47'
                        )
                    )
                )
            );

            $estiloIngreso2 = array(
                'font' => array(
                    'name'      => 'Arial',
                    'color'     => array(
                        'rgb' => '000000'
                    )
                ),
                'fill'  => array(
                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'BFBFBF'
                    )
                ),
                'borders' => array(
                    'left'     => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN ,
                        'color' => array(
                            'rgb' => '3a2a47'
                        )
                    )
                )
            );

          
    $totalFinal = 0;        
    foreach($datosNexos as $key => $value)
    {
        $concepto = "INGRESO";


        $estatus = "PENDIENTE";
        if($value['REQUISICIONES.ESTATUS'] == 2)
            $estatus = "SURTIDO";

        $folio = "";
        if($value['REQUISICIONES.EMPRESA'] == 1)
            $folio = "NX";
        else if($value['REQUISICIONES.EMPRESA'] == 2)
            $folio = "NP";

        if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 1)
            $folio .= "F-".$value['REQUISICIONES.FOLIO'];
        else if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 2)
            $folio .= "R-".$value['REQUISICIONES.FOLIO'];
        else if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 3)
            $folio .= "V-".$value['REQUISICIONES.FOLIO'];
        else if($value['REQUISICIONES.TIPO_DOCUMENTO'] == 4)
        {
            $folio .= "I-".$value['REQUISICIONES.FOLIO'];
            $concepto = "EGRESO";
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
        
        //Configuracion de variables 
        


        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $titulosColumnas[0])
        ->setCellValue('B'.$i, $titulosColumnas[1])
        ->setCellValue('C'.$i, $titulosColumnas[2])
        ->setCellValue('D'.$i, $titulosColumnas[3])
        ->setCellValue('E'.$i, $titulosColumnas[4])
        ->setCellValue('F'.$i, $titulosColumnas[5])
        ->setCellValue('G'.$i, $titulosColumnas[6]);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloTituloColumnas);
        $i++;


        $tipo = $value['REQUISICIONES.TIPO_DOCUMENTO'];    
        
        //Requisicion General
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $folio)
            ->setCellValue('B'.$i,  $value['REQUISICIONES.FECHA'])
            ->setCellValue('C'.$i,  utf8_encode($value['REQUISICIONES.CLIENTE']))
            ->setCellValue('D'.$i,  $pago)
            ->setCellValue('E'.$i,  $value['OPERADOR.ALIAS'])
            ->setCellValue('F'.$i,  utf8_encode($value['REQUISICIONES.OBSERVACION']))
            ->setCellValue('G'.$i,  $estatus);
        
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloIngreso);
        $i++;

        //Subtitulos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  "#")
            ->setCellValue('B'.$i,  "PROVEEDOR")
            ->setCellValue('C'.$i,  "CANTIDAD")
            ->setCellValue('D'.$i,  "MATERIAL")
            ->setCellValue('E'.$i,  "IMPORTE");
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloSubTitulo);    
        $i++;

        $monto_subtotal = 0;

        //Ventas de la Requisicion
        for($z = 0; $z < count($value['ARTICULOS_VENTAS']); $z++)
        {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $folio)
            ->setCellValue('B'.$i,  "N/A")
            ->setCellValue('C'.$i,  $value['ARTICULOS_VENTAS'][$z]->UNIDADES)
            ->setCellValue('D'.$i,  utf8_encode($value['ARTICULOS_VENTAS'][$z]->NOMBRE))
            ->setCellValue('E'.$i,  $value['ARTICULOS_VENTAS'][$z]->PRECIO_TOTAL_NETO)
            ->setCellValue('F'.$i,  "+");
            $monto_subtotal += $value['ARTICULOS_VENTAS'][$z]->PRECIO_TOTAL_NETO;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloIngreso);
            $i++;    
            $totalFinal += $value['ARTICULOS_VENTAS'][$z]->PRECIO_TOTAL_NETO;
            
        }  

        //Requisiciones
        for($j = 0; $j < count($value['ARTICULOS_REQUISICION']); $j++)
        {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $value['ARTICULOS_REQUISICION'][$j]->FACTURA)
            ->setCellValue('B'.$i,  utf8_encode($value['ARTICULOS_REQUISICION'][$j]->PROVEEDOR))
            ->setCellValue('C'.$i,  $value['ARTICULOS_REQUISICION'][$j]->CANTIDAD)
            ->setCellValue('D'.$i,  utf8_encode($value['ARTICULOS_REQUISICION'][$j]->ARTICULO))
            ->setCellValue('E'.$i,  $value['ARTICULOS_REQUISICION'][$j]->IMPORTE)
            ->setCellValue('F'.$i,  "-");
            $monto_subtotal -= $value['ARTICULOS_REQUISICION'][$j]->IMPORTE;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloIngreso2);
            $i++;    
            $totalFinal -= $value['ARTICULOS_VENTAS'][$z]->PRECIO_TOTAL_NETO;
        } 

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  "")
            ->setCellValue('B'.$i,  "")
            ->setCellValue('C'.$i,  "")
            ->setCellValue('D'.$i,  "SUBTOTAL")
            ->setCellValue('E'.$i,  $monto_subtotal);
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloSubTitulo);
            $i++;       

    }

    $i++;    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  "")
        ->setCellValue('B'.$i,  "")
        ->setCellValue('C'.$i,  "")
        ->setCellValue('D'.$i,  "TOTAL")
        ->setCellValue('E'.$i,  $totalFinal);
        
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estiloSubTitulo);
        $i++;               


 

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

    $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estiloTituloReporte);
    //$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($estiloTituloColumnas);
    //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:J4".($i-1));

    for($i = 'A'; $i <= 'G'; $i++){
    if($i != 'C')
        $objPHPExcel->setActiveSheetIndex(0)
            ->getColumnDimension($i)->setAutoSize(TRUE);
    }

    // Se asigna el nombre a la hoja
    $objPHPExcel->getActiveSheet()->setTitle('REQUISICIONESMATERIALES');

    // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
    $objPHPExcel->setActiveSheetIndex(0);
    // Inmovilizar paneles


    // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="ReporteREQUISICIONESes.xlsx"');


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    ob_start();
    $objWriter->save('php://output');
    exit; 

    function extraeDatos($conexion, $Empresa, $arreglo, $campos, $join, $tipo, $consulta)
    {
        $conection = $conexion;

        if($Empresa == 1)
        {
            $consulta = " AND REQUISICIONES.EMPRESA=1 ";
            if($tipo == 1)
                $consulta .=" AND REQUISICIONES.TIPO_DOCUMENTO=1 ";
            if($tipo == 2)        
                $consulta .=" AND  REQUISICIONES.TIPO_DOCUMENTO=2 ";
            
        }
        if($Empresa == 2)
        {
            $consulta = " AND REQUISICIONES.EMPRESA=2 ";    
            if($tipo == 1)
                $consulta .=" AND  REQUISICIONES.TIPO_DOCUMENTO=1 ";
            if($tipo == 2)        
                $consulta .=" AND  REQUISICIONES.TIPO_DOCUMENTO=2 ";
            if($tipo == 3)        
                $consulta .=" AND  REQUISICIONES.TIPO_DOCUMENTO=3 ";
        }   
        


        $tipoDocumento = "";
        
        $ciclos = count($join) / 5;
        $unions = "";
        $whereUnions = "";
        $joins = "";

        
        for($i = 0; $i < $ciclos; $i++)
        {
            if($join[($i * 5) + 4] == "LEFT")
                $joins .= " LEFT JOIN ".$join[($i * 5)]." ON ".$join[(($i * 5) + 2)]." ".$join[(($i * 5) + 1)]." ".$join[(($i * 5) + 3)];
            else if($join[($i * 5) + 4] == "UNION")
            {
                $unions .= ", ".$join[($i * 5)]." ";
                $whereUnions .= " AND ".$join[(($i * 5) + 2)]." ".$join[(($i * 5) + 1)]." ".$join[(($i * 5) + 3)]." ";
            }
        }

        $query = "select ".implode(",",$campos)." from REQUISICIONES $unions $joins WHERE REQUISICIONES.BORRADO IS NULL $whereUnions $consulta ";
            
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $count = count($campos);
        $contador = 0;
        $arreglo = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){

            $arreglo_auxiliar = array();
            $indice = count($arreglo);

            foreach($campos as $index => $valor)
            {
                $nombre = explode(".", $valor);

                if($nombre[1])
                    $arreglo_auxiliar[$valor] = utf8_encode($row->$nombre[1]);
                else
                    $arreglo_auxiliar[$valor] = utf8_encode($row->$valor);
                $contador++;
            }
                
            $campos2 = array("PROVEEDOR",
                            "ARTICULO",
                            "CANTIDAD",
                            "UNIDAD",
                            "IMPORTE",
                            "FACTURA");

            $query2 = "select ".implode(",", $campos2)." from REQUISICIONES_ARTICULOS where REQUISICIONESID=".$row->ID;
            $result2 = ibase_query($conection->getConexion(), $query2) or die(ibase_errmsg());
            $arreglo_requerimientos = array();    
            while($row2 = ibase_fetch_object ($result2, IBASE_TEXT))
            {
                $indice1 = count($arreglo_requerimientos);
                $arreglo_requerimientos[$indice1] = $row2;
            
            }

            //$arreglo[$indice] = $arreglo_auxiliar;
            //$arreglo[$indice]["ARTICULOS_REQUISICION"] = $arreglo_requerimientos;

            if($Empresa == 1)
            {
                $conection2 = $conexion;
            }
            if($Empresa == 2)
            {
                $conection2 = new conexion_nexos($_SESSION['empresa']);    
            }       
            if($tipo == 3)
            {
                $campos2 = array("ARTICULOS.NOMBRE",
                "DOCTOS_PV_DET.UNIDADES",
                "DOCTOS_PV_DET.PRECIO_TOTAL_NETO");

                $query3 = "select ".implode(",", $campos2)." from DOCTOS_PV, DOCTOS_PV_DET, ARTICULOS 
                where 
                DOCTOS_PV_DET.ARTICULO_ID=ARTICULOS.ARTICULO_ID AND
                DOCTOS_PV.DOCTO_PV_ID=DOCTOS_PV_DET.DOCTO_PV_ID    
                AND DOCTOS_PV_DET.ROL!='C' AND DOCTOS_PV_DET.PRECIO_TOTAL_NETO>0 and 
                DOCTOS_PV.FOLIO = 'A".str_pad($row->FOLIO, 8, "0", STR_PAD_LEFT)."' AND DOCTOS_PV.TIPO_DOCTO='V' ";
    
            }else if($tipo != 3)
            {
                $campos2 = array("ARTICULOS.NOMBRE",
                            "DOCTOS_VE_DET.UNIDADES",
                            "DOCTOS_VE_DET.PRECIO_TOTAL_NETO");

                if($tipo == 1)
                    $consulta_venta .=" AND DOCTOS_VE.TIPO_DOCTO='F' ";
                if($tipo == 2)        
                    $consulta_venta .=" AND  DOCTOS_VE.TIPO_DOCTO='R' ";

                $query3 = "select ".implode(",", $campos2)." from DOCTOS_VE, DOCTOS_VE_DET, ARTICULOS 
                where 
                DOCTOS_VE_DET.ARTICULO_ID=ARTICULOS.ARTICULO_ID AND
                DOCTOS_VE.DOCTO_VE_ID=DOCTOS_VE_DET.DOCTO_VE_ID    
                AND DOCTOS_VE_DET.ROL!='C' AND DOCTOS_VE_DET.PRECIO_TOTAL_NETO>0 and 
                DOCTOS_VE.FOLIO = '".str_pad($row->FOLIO, 9, "0", STR_PAD_LEFT)."' ".$consulta_venta;
    
            }
            
            $result3 = ibase_query($conection2->getConexion(), $query3) or die(ibase_errmsg());
             
            $arreglo_ventas = array();    
            while($row3 = ibase_fetch_object ($result3, IBASE_TEXT))
            {
                $indice1 = count($arreglo_ventas);
                $arreglo_ventas[$indice1] = $row3;
            
            } 
            $arreglo[$indice] = $arreglo_auxiliar;
            $arreglo[$indice]["ARTICULOS_REQUISICION"] = $arreglo_requerimientos;
            $arreglo[$indice]["ARTICULOS_VENTAS"] = $arreglo_ventas;

        }  
        return $arreglo;    

         
        
    }

    function extraeDatosInterno($conexion, $arreglo, $campos, $join, $consulta)
    {
        $conection = $conexion;
        $consulta .=" AND  REQUISICIONES.TIPO_DOCUMENTO=4 ";
        
        $tipoDocumento = "";
        
        $ciclos = count($join) / 5;
        $unions = "";
        $whereUnions = "";
        $joins = "";
        
        for($i = 0; $i < $ciclos; $i++)
        {
            if($join[($i * 5) + 4] == "LEFT")
                $joins .= " LEFT JOIN ".$join[($i * 5)]." ON ".$join[(($i * 5) + 2)]." ".$join[(($i * 5) + 1)]." ".$join[(($i * 5) + 3)];
            else if($join[($i * 5) + 4] == "UNION")
            {
                $unions .= ", ".$join[($i * 5)]." ";
                $whereUnions .= " AND ".$join[(($i * 5) + 2)]." ".$join[(($i * 5) + 1)]." ".$join[(($i * 5) + 3)]." ";
            }
        }

        $query = "select ".implode(",",$campos)." from REQUISICIONES $unions $joins WHERE REQUISICIONES.BORRADO IS NULL $whereUnions $consulta ";
            
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $count = count($campos);
        $contador = 0;
        $arreglo = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){

            $arreglo_auxiliar = array();
            $indice = count($arreglo);

            foreach($campos as $index => $valor)
            {
                $nombre = explode(".", $valor);

                if($nombre[1])
                    $arreglo_auxiliar[$valor] = utf8_encode($row->$nombre[1]);
                else
                    $arreglo_auxiliar[$valor] = utf8_encode($row->$valor);
                $contador++;
            }
                
            $campos2 = array("PROVEEDOR",
                            "ARTICULO",
                            "CANTIDAD",
                            "UNIDAD",
                            "IMPORTE",
                            "FACTURA");

            $query2 = "select ".implode(",", $campos2)." from REQUISICIONES_ARTICULOS where REQUISICIONESID=".$row->ID;
            $result2 = ibase_query($conection->getConexion(), $query2) or die(ibase_errmsg());
            $arreglo_requerimientos = array();    
            while($row2 = ibase_fetch_object ($result2, IBASE_TEXT))
            {
                $indice1 = count($arreglo_requerimientos);
                $arreglo_requerimientos[$indice1] = $row2;
            
            }

            $arreglo[$indice] = $arreglo_auxiliar;
            $arreglo[$indice]["ARTICULOS_REQUISICION"] = $arreglo_requerimientos;
            $arreglo[$indice]["ARTICULOS_VENTAS"] = array();;

        }
        return $arreglo;    
        
    }
?>
