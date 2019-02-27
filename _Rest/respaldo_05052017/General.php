<?php
	session_start();
	include("../clases/conexion.php");

	
	date_default_timezone_set('America/Mexico_City');
	
	
	if($_POST["accion"] == "index")
	{
	    $arreglo_actividades1 = array();
        $arreglo_indices = array();
		$query1 = "select p.iddepartamento, d.descripciondepartamento, count(*) as conteo from tableroproduccion tp, produccion p, departamento d, doctos_ve dv where tp.id not in (select idtableroproduccion from documentosfinalizados) and dv.docto_ve_id=tp.docto_ve_id and tp.id=p.idtableroproduccion and p.iddepartamento=d.id and dv.estatus!='C' and p.idestatus!=2 group by p.iddepartamento, d.descripciondepartamento";

        $conection = new conexion_nexos(1);
        $result = ibase_query($conection->getConexion(), $query1) or die(ibase_errmsg());

        $query_impresion = "select count(*) as count_impresion from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and tp.diseno=1 and tp.impresion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento=2";
        $result_imp = ibase_query($conection->getConexion(), $query_impresion) or die(ibase_errmsg());

		while($row_imp = ibase_fetch_object($result_imp, IBASE_TEXT)){
			$duplicado_impresion = $row_imp->COUNT_IMPRESION;
		}

		$query_maquilas = "select count(*) as count_maquilas from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and tp.diseno=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento=8";
        $result_maq = ibase_query($conection->getConexion(), $query_maquilas) or die(ibase_errmsg());

		while($row_maq = ibase_fetch_object($result_maq, IBASE_TEXT)){
			$duplicado_maquilas = $row_maq->COUNT_MAQUILAS;
		}

		$query_preparacion = "select count(distinct dv.folio) as COUNT_PREPARACION from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and (tp.diseno=1 or tp.impresion=1 or tp.maquilas=1) and tp.preparacion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento in (2,3,8)";        
        $result_pre = ibase_query($conection->getConexion(), $query_preparacion) or die(ibase_errmsg());

		while($row_pre = ibase_fetch_object($result_pre, IBASE_TEXT)){
			$duplicado_preparacion = $row_pre->COUNT_PREPARACION;
		}

		$query_instalacion = "select count(distinct dv.folio) as COUNT_INSTALACION from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and tp.preparacion=1 and tp.instalacion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento in (9)";
		$result_inst = ibase_query($conection->getConexion(), $query_instalacion) or die(ibase_errmsg());

		while($row_inst = ibase_fetch_object($result_inst, IBASE_TEXT)){
			$duplicado_instalacion = $row_inst->COUNT_INSTALACION;
		}

		$query_entrega = "select count(distinct dv.folio) as COUNT_ENTREGA from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and (tp.preparacion=1 or tp.instalacion=1) and tp.entrega=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento in (4,9)";
		$result_ent = ibase_query($conection->getConexion(), $query_entrega) or die(ibase_errmsg());

		while($row_ent = ibase_fetch_object($result_ent, IBASE_TEXT)){
			$duplicado_entrega = $row_ent->COUNT_ENTREGA;
		}

        
        while($row1 = ibase_fetch_object($result, IBASE_TEXT)){
            $indice = count($arreglo_actividades1);
            $arreglo_actividades1[$indice]['id'] = $row1->IDDEPARTAMENTO;
            $arreglo_actividades1[$indice]['nombre'] = utf8_encode($row1->DESCRIPCIONDEPARTAMENTO);

            $contador = $row1->CONTEO;
            if($row1->IDDEPARTAMENTO == "3")
                $contador -= $duplicado_impresion;

            if($row1->IDDEPARTAMENTO == "9")
                $contador -= $duplicado_preparacion;

            if($row1->IDDEPARTAMENTO == "4")
                $contador -= $duplicado_instalacion;

            if($row1->IDDEPARTAMENTO == "6")
                $contador -= $duplicado_entrega;

            if($row1->IDDEPARTAMENTO == "8")
                $contador -= $duplicado_maquilas;

            $arreglo_actividades1[$indice]['conteo'] = $contador;
            $arreglo_indices[] = $row1->IDDEPARTAMENTO;
        }
        
        $conection2 = new conexion_nexos(2);
        $query2 = "select p.iddepartamento, d.descripciondepartamento, count(*) as conteo from tableroproduccion tp, produccion p, departamento d, doctos_ve dv where tp.id not in (select idtableroproduccion from documentosfinalizados) and dv.docto_ve_id=tp.docto_ve_id and tp.id=p.idtableroproduccion and p.iddepartamento=d.id and dv.estatus!='C' and p.idestatus!=2 group by p.iddepartamento, d.descripciondepartamento";

        $result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());

        $query_impresion = "select count(*) as count_impresion from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and tp.diseno=1 and tp.impresion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento=2";
        $result_imp = ibase_query($conection2->getConexion(), $query_impresion) or die(ibase_errmsg());

        while($row_imp = ibase_fetch_object($result_imp, IBASE_TEXT)){
            $duplicado_impresion = $row_imp->COUNT_IMPRESION;
        }

        $query_maquilas = "select count(*) as count_maquilas from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and tp.diseno=1 and tp.impresion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento=8";
        $result_maq = ibase_query($conection2->getConexion(), $query_maquilas) or die(ibase_errmsg());

        while($row_maq = ibase_fetch_object($result_maq, IBASE_TEXT)){
            $duplicado_maquilas = $row_maq->COUNT_MAQUILAS;
        }

        $query_preparacion = "select count(distinct dv.folio) as COUNT_PREPARACION from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and (tp.diseno=1 or tp.impresion=1 or tp.maquilas=1) and tp.preparacion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento in (2,3,8)";        
        $result_pre = ibase_query($conection2->getConexion(), $query_preparacion) or die(ibase_errmsg());

        while($row_pre = ibase_fetch_object($result_pre, IBASE_TEXT)){
            $duplicado_preparacion = $row_pre->COUNT_PREPARACION;
        }

        $query_instalacion = "select count(distinct dv.folio) as COUNT_INSTALACION from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and tp.preparacion=1 and tp.instalacion=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento in (9)";
        $result_inst = ibase_query($conection2->getConexion(), $query_instalacion) or die(ibase_errmsg());

        while($row_inst = ibase_fetch_object($result_inst, IBASE_TEXT)){
            $duplicado_instalacion = $row_inst->COUNT_INSTALACION;
        }

        $query_entrega = "select count(distinct dv.folio) as COUNT_ENTREGA from tableroproduccion tp, produccion p, doctos_ve dv where tp.id=p.idtableroproduccion and (tp.preparacion=1 or tp.instalacion=1) and tp.entrega=1 and dv.docto_ve_id=tp.docto_ve_id and dv.estatus!='C' and p.idestatus!=2 and p.iddepartamento in (4,9)";
        $result_ent = ibase_query($conection2->getConexion(), $query_entrega) or die(ibase_errmsg());

        while($row_ent = ibase_fetch_object($result_ent, IBASE_TEXT)){
            $duplicado_entrega = $row_ent->COUNT_ENTREGA;
        }        

       while($row2 = ibase_fetch_object($result2, IBASE_TEXT)){
            $contador = 0;
            $indice =  array_search($row2->IDDEPARTAMENTO, $arreglo_indices);
       
            if((in_array($row2->IDDEPARTAMENTO, $arreglo_indices)))
            {
               
                $indice = array_search($row2->IDDEPARTAMENTO, $arreglo_indices);
                $row2->IDDEPARTAMENTO." ->".$indice;
                $contador = $row2->CONTEO;
               
                
                if($row2->IDDEPARTAMENTO == "3")
                    $contador -= $duplicado_impresion;

                if($row2->IDDEPARTAMENTO == "9")
                    $contador -= $duplicado_preparacion;

                if($row2->IDDEPARTAMENTO == "4")
                    $contador -= $duplicado_instalacion;

                if($row2->IDDEPARTAMENTO == "6")
                    $contador -= $duplicado_entrega;

                if($row2->IDDEPARTAMENTO == "8")
                    $contador -= $duplicado_maquilas;


                $arreglo_actividades1[$indice]['conteo'] += $contador;
            }else
            {
                $indice = count($arreglo_actividades1);
                $arreglo_actividades1[$indice]['id'] = $row2->IDDEPARTAMENTO;
                $arreglo_actividades1[$indice]['nombre'] = utf8_encode($row2->DESCRIPCIONDEPARTAMENTO);

                $contador = $row2->CONTEO;
                if($row2->IDDEPARTAMENTO == "3")
                    $contador -= $duplicado_impresion;

                if($row2->IDDEPARTAMENTO == "9")
                    $contador -= $duplicado_preparacion;

                if($row2->IDDEPARTAMENTO == "4")
                    $contador -= $duplicado_instalacion;

                if($row2->IDDEPARTAMENTO == "6")
                    $contador -= $duplicado_entrega;

                if($row2->IDDEPARTAMENTO == "8")
                    $contador -= $duplicado_maquilas;

                $arreglo_actividades1[$indice]['conteo'] = $contador;
                $arreglo_indices[] = $row2->IDDEPARTAMENTO;
            }
        }

        
        

        $obj = (object) $arreglo_actividades1;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "ventas")
	{
		$conection = new conexion_nexos(1);

		$array_metas_ventas_mensuales = array(
			0  => 375683.09,
			1  => 440434.66,
			2  => 466906.62,
			3  => 515760.37,
			4  => 639259.34,
			5  => 522072.62,
			6  => 517270.37,
			7  => 546707.44,
			8  => 468999.27,
			9  => 531263.49,
			10 => 575971.94,
			11 => 399670.80
		);

		$array_metas_mostrador_mensuales = array(
			0  => 30000.00,
			1  => 30000.00,
			2  => 30000.00,
			3  => 30000.00,
			4  => 30000.00,
			5  => 30000.00,
			6  => 30000.00,
			7  => 30000.00,
			8  => 30000.00,
			9  => 30000.00,
			10  => 30000.00,
			11  => 30000.00
			
		);

	
		$ventas_diarias_servicio = ($array_metas_ventas_mensuales[date("n")-1]/20);
		$ventas_mensual_servicio = $array_metas_ventas_mensuales[date("n")-1];

		$ventas_diarias_mostrador = ($array_metas_mostrador_mensuales[date("n")-1] / 20);
		$ventas_mensual_mostrador = $array_metas_mostrador_mensuales[date("n")-1];

		$fecha1 = "between '".date("Y-m-")."01' and '".date("Y-m-d")."' ";
		$fecha2 = " '".date("Y-m-d")."' ";

	    $condicionales = " and tipo_docto='F' and ESTATUS!='C' AND fecha $fecha1";
	    $arreglo1 = $conection->sum_regular("DOCTOS_VE.IMPORTE_NETO", "", "DOCTOS_VE", array(), $condicionales, $softdelete);

	    $condicionales_1 = " and tipo_docto='F' and ESTATUS!='C' AND fecha = $fecha2 ";
	    $arreglo1_1 = $conection->sum_regular("DOCTOS_VE.IMPORTE_NETO", "", "DOCTOS_VE", array(), $condicionales_1, $softdelete);

			//Aqui Notas
			$condicionales_nota_1 = " and DOCTOS_CC.CONCEPTO_CC_ID='8' and DOCTOS_CC.CANCELADO='N' AND IMPORTES_DOCTOS_CC.FECHA $fecha1";
			$join = array("IMPORTES_DOCTOS_CC", "=", "DOCTOS_CC.DOCTO_CC_ID", "IMPORTES_DOCTOS_CC.DOCTO_CC_ID", "LEFT");
	    $arreglo1_nota = $conection->sum_regular("IMPORTES_DOCTOS_CC.IMPORTE", "", "DOCTOS_CC", $join, $condicionales_nota_1, $softdelete);

	    $condicionales_nota_2 = "  and DOCTOS_CC.CONCEPTO_CC_ID='8' and DOCTOS_CC.CANCELADO='N' AND IMPORTES_DOCTOS_CC.FECHA = $fecha2 ";
	    $arreglo1_1_nota = $conection->sum_regular("IMPORTES_DOCTOS_CC.IMPORTE", "", "DOCTOS_CC", $join, $condicionales_nota_2, $softdelete);
			//Fin Aqui

  	  $conection2 = new conexion_nexos(2);
	    $arreglo2 = $conection2->sum_regular("DOCTOS_VE.IMPORTE_NETO", "", "DOCTOS_VE", array(), $condicionales, $softdelete);

	    $arreglo2_1 = $conection2->sum_regular("DOCTOS_VE.IMPORTE_NETO", "", "DOCTOS_VE", array(), $condicionales_1, $softdelete);

	    $condicionales2 = " and tipo_docto='V' and ESTATUS!='C' AND fecha $fecha1 ";

	    $condicionales2_1 = " and tipo_docto='V' and ESTATUS!='C' AND fecha = $fecha2 ";

	    $arreglo3 = $conection2->sum_regular("DOCTOS_PV.IMPORTE_NETO", "", "DOCTOS_PV", array(), $condicionales2, $softdelete);

	    $arreglo3_1 = $conection2->sum_regular("DOCTOS_PV.IMPORTE_NETO", "", "DOCTOS_PV", array(), $condicionales2_1, $softdelete);

			//Aqui Notas
			$arreglo2_nota = $conection2->sum_regular("IMPORTES_DOCTOS_CC.IMPORTE", "", "DOCTOS_CC", $join, $condicionales_nota_1, $softdelete);

			$arreglo2_1_nota = $conection2->sum_regular("IMPORTES_DOCTOS_CC.IMPORTE", "", "DOCTOS_CC", $join, $condicionales_nota_2, $softdelete);
			//Fin Aqui


	    $total_servicio_mensual = $arreglo1[0]['suma'] + $arreglo2[0]['suma'] + $arreglo1_nota[0]['suma'] + $arreglo2_nota[0]['suma'];
	    $total_mostrador_mensual = $arreglo3[0]['suma'];

	    $total_servicio_diario = $arreglo1_1[0]['suma'] + $arreglo2_1[0]['suma'] + $arreglo1_1_nota[0]['suma'] + $arreglo2_1_nota[0]['suma'];
	    $total_mostrador_diario = $arreglo3_1[0]['suma'];

	    $array_final = array();

	    $array_final[0]['meta_ventas_servicio_diario'] = number_format($ventas_diarias_servicio,2,".", ",");
	    $array_final[0]['meta_ventas_mostrador_diario'] = number_format($ventas_diarias_mostrador,2,".", ",");

	    $array_final[0]['meta_ventas_servicio_mensual'] = number_format($ventas_mensual_servicio,2,".", ",");
	    $array_final[0]['meta_ventas_mostrador_mensual'] = number_format($ventas_mensual_mostrador,2,".", ",");


	    $array_final[0]['ventas_servicio_diario'] = number_format($total_servicio_diario,2,".", ",");
	    $array_final[0]['ventas_mostrador_diario'] = number_format($total_mostrador_diario,2,".", ",");

	    $array_final[0]['ventas_servicio_mensual'] = number_format($total_servicio_mensual,2,".", ",");
	    $array_final[0]['ventas_mostrador_mensual'] = number_format($total_mostrador_mensual,2,".", ",");

	    $percent_servicio_mensual = (($total_servicio_mensual / $ventas_mensual_servicio) * 100);
	    $array_final[0]['ventas_servicio_diario_percent'] = number_format((($total_servicio_diario / $ventas_diarias_servicio) * 100),2,".", ",");
	    $array_final[0]['ventas_servicio_mensual_percent'] = number_format($percent_servicio_mensual,2,".", ",");

	    $percent_servicio_mensual;
	    if($percent_servicio_mensual <= 15)
	    	 $array_final[0]['img_service'] = "<b><i style='color:#E21800' class='fa fa-battery-0'></i></b>";
	    else if($percent_servicio_mensual > 15 && $percent_servicio_mensual <= 30)
	    	$array_final[0]['img_service'] = "<i style='color:#E21800' class='fa fa-battery-1'></i>";
	    else if($percent_servicio_mensual > 30 && $percent_servicio_mensual <= 70)
	    	$array_final[0]['img_service'] = "<i style='color:rgb(244,171,14)' class='fa fa-battery-2'></i>";
	    else if($percent_servicio_mensual > 70 && $percent_servicio_mensual < 95)
	    	$array_final[0]['img_service'] = "<i style='color:rgb(244,171,14)' class='fa fa-battery-3'></i>";
	    else if($percent_servicio_mensual > 95 && $percent_servicio_mensual <= 100)
	    	$array_final[0]['img_service'] = "<i style='color:rgb(200,208,64)' class='fa fa-battery-4'></i>";
        else if($percent_servicio_mensual > 100)
            $array_final[0]['img_service'] = "<i style='color:rgb(200,0,0)' class='fa fa-trophy'></i>";

	    $percent_mostrador_mensual = (($total_mostrador_mensual / $ventas_mensual_mostrador) * 100);

	    if($percent_mostrador_mensual <= 15)
	    	 $array_final[0]['img_mostrador'] = "<i style='color:#E21800' class='fa fa-battery-0'></i>";
	    else if($percent_mostrador_mensual > 15 && $percent_mostrador_mensual <= 30)
	    	$array_final[0]['img_mostrador'] = "<i style='color:#E21800' class='fa fa-battery-1'></i>";
	    else if($percent_mostrador_mensual > 30 && $percent_mostrador_mensual <= 70)
	    	$array_final[0]['img_mostrador'] = "<i style='color:rgb(244,171,14)' class='fa fa-battery-2'></i>";
	    else if($percent_mostrador_mensual > 70 && $percent_mostrador_mensual < 95)
	    	$array_final[0]['img_mostrador'] = "<i style='color:rgb(244,171,14)' class='fa fa-battery-3'></i>";
	    else if($percent_mostrador_mensual <= 100)
            $array_final[0]['img_mostrador'] = "<i style='color:rgb(200,208,64)' class='fa fa-battery-4'></i>";
        else if($percent_mostrador_mensual > 100)
            $array_final[0]['img_mostrador'] = "<i style='color:rgb(200,0,0)' class='fa fa-trophy'></i>";


      $array_final[0]['ventas_mostrador_diario_percent'] = number_format((($total_mostrador_diario / $ventas_diarias_mostrador) * 100),2,".", ",");
	    $array_final[0]['ventas_mostrador_mensual_percent'] = number_format($percent_mostrador_mensual,2,".", ",");

	    $obj = (object) $array_final;
	    echo json_encode($obj);
	}

    if($_POST['accion'] == "calendarioServicio")
    {

    	$array_metas_mensuales = array(
    			0  => 375683.09,
    			1  => 440434.66,
    			2  => 466906.62,
    			3  => 515760.37,
    			4  => 639259.34,
    			5  => 522072.62,
    			6  => 517270.37,
    			7  => 546707.44,
    			8  => 468999.27,
    			9  => 531263.49,
    			10 => 575971.94,
    			11 => 399670.80
    		);

        /**/
        $tabla = "DOCTOS_VE";
        $condicionales = " and tipo_docto='F' and ESTATUS!='C' AND fecha between '".date("Y-m-")."01' and '".date("Y-m-d")."' group by fecha order by fecha";
				$condicionales2 = "  and DOCTOS_CC.CONCEPTO_CC_ID='8' and DOCTOS_CC.CANCELADO='N' AND IMPORTES_DOCTOS_CC.FECHA between '".date("Y-m-")."01' and '".date("Y-m-d")."' group by fecha order by fecha";

        $arreglo1 = calcula_subtotales(1, $tabla, $condicionales, "NEXOS EMPRESARIALES", 1, $condicionales2);

        $arreglo2 = calcula_subtotales(2, $tabla, $condicionales, "NEXPRINT", 1, $condicionales2);



        $json2 = array_merge($arreglo1, $arreglo2);

        $contador = count($json2);
        for($i = 0; $i < $contador; $i++)
        {
            $j = ($i + 1);
            for(; $j < $contador; $j++)
            {
                if($json2[$i]['dia'] > $json2[$j]['dia'])
                {
                    $arrayAuxiliar[0] = $json2[$i];
                    $json2[$i] = $json2[$j];
                    $json2[$j] = $arrayAuxiliar[0];
                }
            }
        }

        $arreglo_salida = array();

        $subtotal = 0;
        $total = 0;
        for($i = 0; $i <= $contador; $i++)
        {
            if($i == 0)
            {
                $subtotal = $json2[$i]['suma'];
            }
            else
            {
                if($json2[$i]['dia'] == $json2[$i-1]['dia'])
                {
                    $subtotal += $json2[$i]['suma'];
                }else
                {
                    $index = count($arreglo_salida);
                    $arreglo_salida[$index]["dia"] = (int)substr($json2[$i-1]['dia'],8,2);
                    $arreglo_salida[$index]["saldo"] = $subtotal;
                    //$percent = number_format((($subtotal / 25000)*100));
                    $percent = number_format((($subtotal / (($array_metas_mensuales[(date("n") - 1)]) / 20))*100));

                    $arreglo_salida[$index]["percent"] = $percent;
                    if($percent < 40)
                        $arreglo_salida[$index]["clase"] = "style='background:#d9534f'";
                    else if($percent < 90)
                        $arreglo_salida[$index]["clase"] = "style='background:#f0ad4e'";
                    else if($percent >= 90)
                        $arreglo_salida[$index]["clase"] = "style='background:#5cb85c'";

                    $subtotal = $json2[$i]['suma'];
                }
            }


        }


        $month=date("n");
        $year=date("Y");
        $diaActual=date("j");

        # Obtenemos el dia de la semana del primer dia
        # Devuelve 0 para domingo, 6 para sabado
        $diaSemana=date("w",mktime(0,0,0,$month,1,$year))+7;
        # Obtenemos el ultimo dia del mes
        $ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1));


        $last_cell=$diaSemana+$ultimoDiaMes;
        // hacemos un bucle hasta 42, que es el máximo de valores que puede
        // haber... 6 columnas de 7 dias
        $arreglo = array();

        /*if($diaSemana > 7)
            $indexSemana = 7;
        else*/
            $indexSemana = 7;

        $day = 1;
        for($i=$indexSemana;$i<=44;$i++)
        {
            if(($i%7) != 0)
            {
                if($i==$diaSemana)
                {
                    // determinamos en que dia empieza
                    $day=1;
                }

                if($i<$diaSemana || $i>=$last_cell )
                {
                    // celca vacia

                    $arreglo[count($arreglo)]['dia'] = 0;
                }else{
                    // mostramos el dia
                    $index_arreglo = count($arreglo);
                        $arreglo[$index_arreglo]['dia'] = $day;
                        $arreglo[$index_arreglo]['saldo'] = 0;
                        $arreglo[$index_arreglo]['percent'] = 0;
                        $arreglo[$index_arreglo]['clase'] = "";
                            foreach($arreglo_salida as $key=> $values)
                        {
                            if($values['dia'] == $day)
                            {
                                $arreglo[$index_arreglo]['saldo'] = $values['saldo'];
                                $arreglo[$index_arreglo]['percent'] = $values['percent'];
                                $arreglo[$index_arreglo]['clase'] = $values['clase'];
                            }
                        }
                }

            }
            $day++;
            // cuando llega al final de la semana, iniciamos una columna nueva

        }
        //print_r($arreglo);
        /*$arreglo_principal = array();
        $arreglo_principal[0] = $arreglo;
        $arreglo_principal[1] = $array_metas_mensuales[(date("n")-1)];
        $arreglo_principal[2] = date("j");*/

        $obj = (object) $arreglo;
        echo json_encode($obj);

    }

if($_POST['accion'] == "activarActividad")
{
	$conection = new conexion_nexos($_POST['EMPRESA']);

	$condicionales = " AND TABLEROPRODUCCION.DOCTO_VE_DET_ID=".$_POST['id'];

	$json = $conection->select_table_advanced_with_counter(array("ACTIVACION"), array("ACTIVACION"), "TABLEROPRODUCCION", array(), $condicionales, array(), 0, NULL, 2);	

    $campos = array("ACTIVACION");
    $activacion = 0;
    $respuesta = 0;


    if($json[0]['ACTIVACION'] == 0)
    	$activacion = 1;
    else
    	$activacion = 0;

    
    $valores = array($activacion);
    $id = "TABLEROPRODUCCION.DOCTO_VE_DET_ID = ".$_POST['id'];
    $json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

    $obj = (object) array("data" => $activacion);
    echo json_encode($obj);
    $conectio = NULL;
}

if($_POST['accion'] ==  'observaciones')
{
	$conection = new conexion_nexos($_POST['EMPRESA']);
	$campos = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
	$join 	= array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
	
	$condicionales = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=".$_POST['departamento']." AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id'];
	
	$order = array();
	
	$json = $conection->select_table($campos, "TABLEROOBSERVACION", $join, $condicionales, $order, 0);
	$obj = (object) $json;
	echo json_encode($obj);
}

if($_POST['accion'] ==  'saveTurnar')
{
	$conection = new conexion_nexos($_POST['empresa']);
	
	if($_POST['departamento'] == 2)
	{
		$campos = array( "OPERADOR_DISENO_GF");		
	}
	if($_POST['departamento'] == 3)
	{
		$campos = array( "OPERADOR_IMPRESION_GF");		
	}
	if($_POST['departamento'] == 4)
	{
		$campos = array( "OPERADOR_INSTALACION_GF");		
	}
	if($_POST['departamento'] == 6)
	{
		$campos = array( "OPERADOR_ENTREGA_GF");		
	}
	if($_POST['departamento'] == 8)
	{
		$campos = array( "OPERADOR_MAQUILAS_GF");		
	}
	if($_POST['departamento'] == 9)
	{
		$campos = array( "OPERADOR_PREPARACION_GF");		
	}
	$valores = array($_POST['EmpleadoFinalizar']);
	$id = "TABLEROPRODUCCION.ID = ".$_POST['idtablerofinalizar'];
	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);
	
	$obj = (object) $json;
	echo json_encode($obj);
	$conection = NULL;
}

if($_POST['accion'] ==  'save')
{
	$conection = new conexion_nexos($_POST['empresa']);
	
	if($_POST['departamento'] == 2)
	{
		$campos = array("OPERADOR_DISENO_GF","DISENO_GF", "F_DISENO_GF");		
	}
	if($_POST['departamento'] == 3)
	{
		$campos = array("OPERADOR_IMPRESION_GF", "IMPRESION_GF",  "F_IMPRESION_GF");		
	}
	if($_POST['departamento'] == 4)
	{
		$campos = array("OPERADOR_INSTALACION_GF", "INSTALACION_GF", "F_INSTALACION_GF");		
	}
	if($_POST['departamento'] == 6)
	{
		$campos = array("OPERADOR_ENTREGA_GF", "ENTREGA_GF", "F_ENTREGA_GF");		
	}
	if($_POST['departamento'] == 8)
	{
		$campos = array( "OPERADOR_MAQUILAS_GF", "MAQUILAS_GF", "F_MAQUILAS_GF");		
	}
	if($_POST['departamento'] == 9)
	{
		$campos = array( "OPERADOR_PREPARACION_GF","PREPARACION_GF", "F_PREPARACION_GF");		
	}
	$valores = array($_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'");
	$id = "TABLEROPRODUCCION.ID = ".$_POST['idtablerofinalizar'];
	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);
	
	$obj = (object) $json;
	echo json_encode($obj);
	$conection = NULL;
}

if($_POST['accion'] == "cancelar")
{

	$conection = new conexion_nexos($_POST['empresa']);
$campos = array("TABLEROPRODUCCION.ID"
    	, "TABLEROPRODUCCION.GF_DISENO"
    	, "TABLEROPRODUCCION.DISENO_GF"
    	, "TABLEROPRODUCCION.GF_IMPRESION"
    	, "TABLEROPRODUCCION.IMPRESION_GF"
    	, "TABLEROPRODUCCION.GF_PREPARACION"
    	, "TABLEROPRODUCCION.PREPARACION_GF"
    	, "TABLEROPRODUCCION.GF_ENTREGA"
    	, "TABLEROPRODUCCION.ENTREGA_GF"
    	, "TABLEROPRODUCCION.GF_INSTALACION"
    	, "TABLEROPRODUCCION.INSTALACION_GF");

    $join = array();

    $condicionales = " AND TABLEROPRODUCCION.ID='".$_POST['idtablerocancelar']."' ";

    $order = array();

    $arreglo = $conection->select_table($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

    if($arreglo[0]['TABLEROPRODUCCION.GF_ENTREGA'] == 1 && $arreglo[0]['TABLEROPRODUCCION.ENTREGA_GF'] == 2)
	{
		$campos = array("ENTREGA_GF", "ACTIVACION");
        $valores = array(1, 0);
	    $id = "TABLEROPRODUCCION.ID = ".$arreglo[0]['TABLEROPRODUCCION.ID'];
    	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

	}else if($arreglo[0]['TABLEROPRODUCCION.GF_INSTALACION'] == 1 && $arreglo[0]['TABLEROPRODUCCION.INSTALACION_GF'] == 2)
	{
		$campos = array("INSTALACION_GF", "ACTIVACION");
        $valores = array(1, 0);
	    $id = "TABLEROPRODUCCION.ID = ".$arreglo[0]['TABLEROPRODUCCION.ID'];
    	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

	}else if($arreglo[0]['TABLEROPRODUCCION.GF_PREPARACION'] == 1 && $arreglo[0]['TABLEROPRODUCCION.PREPARACION_GF'] == 2)
	{
		$campos = array("PREPARACION_GF", "ACTIVACION");
        $valores = array(1, 0);
	    $id = "TABLEROPRODUCCION.ID = ".$arreglo[0]['TABLEROPRODUCCION.ID'];
    	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

	}else if($arreglo[0]['TABLEROPRODUCCION.GF_IMPRESION'] == 1 && $arreglo[0]['TABLEROPRODUCCION.IMPRESION_GF'] == 2)
	{
		$campos = array("IMPRESION_GF", "ACTIVACION");
        $valores = array(1, 0);
	    $id = "TABLEROPRODUCCION.ID = ".$arreglo[0]['TABLEROPRODUCCION.ID'];
    	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

	}else if($arreglo[0]['TABLEROPRODUCCION.GF_DISENO'] == 1 && $arreglo[0]['TABLEROPRODUCCION.DISENO_GF'] == 2)
	{
		$campos = array("DISENO_GF", "ACTIVACION");
        $valores = array(1, 0);
	    $id = "TABLEROPRODUCCION.ID = ".$arreglo[0]['TABLEROPRODUCCION.ID'];
    	$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

	}
    

    $obj = (object) $json;
    echo json_encode($obj);
    $conection = NULL;
 
}


if($_POST['accion'] == "calendarioMostrador")
{

    /**/
    $condicionales2 = " and tipo_docto='V' and ESTATUS!='C' AND fecha between '".date("Y-m-")."01' and '".date("Y-m-d")."' group by fecha order by fecha";

    $tabla2 = "DOCTOS_PV";

    $arreglo2 = calcula_subtotales(2, $tabla2, $condicionales2, "NEXPRINT MOSTRADOR", 0);


    $arreglo_salida = array();

    $subtotal = 0;
    $total = 0;
    $contador = count($arreglo2);
    for($i = 0; $i <= $contador; $i++)
    {
        if($i == 0)
        {
            $subtotal = $arreglo2[$i]['suma'];
        }
        else
        {
            if($arreglo2[$i]['dia'] == $arreglo2[$i-1]['dia'])
            {
                $subtotal += $arreglo2[$i]['suma'];
            }else
            {
                $index = count($arreglo_salida);
                $arreglo_salida[$index]["dia"] = (int)substr($arreglo2[$i-1]['dia'],8,2);
                $arreglo_salida[$index]["saldo"] = number_format($subtotal,2,".", ",");
                $percent = number_format((($subtotal / 1500)*100));
                $arreglo_salida[$index]["percent"] = $percent;
                if($percent < 40)
                    $arreglo_salida[$index]["clase"] = "style='background:#d9534f'";
                else if($percent < 90)
                    $arreglo_salida[$index]["clase"] = "style='background:#f0ad4e'";
                else if($percent >= 90)
                    $arreglo_salida[$index]["clase"] = "style='background:#5cb85c'";

                $subtotal = $arreglo2[$i]['suma'];
            }
        }
    }

    /**/
    $month=date("n");
    $year=date("Y");
    $diaActual=date("j");

    # Obtenemos el dia de la semana del primer dia
    # Devuelve 0 para domingo, 6 para sabado
    $diaSemana=date("w",mktime(0,0,0,$month,1,$year))+7;
    # Obtenemos el ultimo dia del mes
    $ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1));


    $last_cell=$diaSemana+$ultimoDiaMes;
    // hacemos un bucle hasta 42, que es el máximo de valores que puede
    // haber... 6 columnas de 7 dias
    $arreglo = array();

    /*if($diaSemana > 7)
        $indexSemana = 8;
    else*/
        $indexSemana = 7;

    $day = 1;
    for($i=$indexSemana;$i<=44;$i++)
    {
        if(($i%7) != 0)
        {
            if($i==$diaSemana)
            {
                // determinamos en que dia empieza
                $day=1;
            }

            if($i<$diaSemana || $i>=$last_cell)
            {
                // celca vacia

                $arreglo[count($arreglo)]['dia'] = 0;
            }else{
                // mostramos el dia
                $index_arreglo = count($arreglo);
                $arreglo[$index_arreglo]['dia'] = $day;
                $arreglo[$index_arreglo]['saldo'] = 0;
                $arreglo[$index_arreglo]['percent'] = 0;
                $arreglo[$index_arreglo]['clase'] = "";
                foreach($arreglo_salida as $key=> $values)
                {
                    if($values['dia'] == $day)
                    {
                        $arreglo[$index_arreglo]['saldo'] = $values['saldo'];
                        $arreglo[$index_arreglo]['percent'] = $values['percent'];
                        $arreglo[$index_arreglo]['clase'] = $values['clase'];
                    }
                }
            }

        }
        // cuando llega al final de la semana, iniciamos una columna nueva
        $day++;
    }

    $obj = (object) $arreglo;
    echo json_encode($obj);

}

	
if($_POST['accion'] == 'autocomplete_articulos')
{
	$conection = new conexion_nexos(1);

	$arreglo_autocomplete = array();
	$query_autocomplete = "select first 5 id as idarticulo, 0 as idsubarticulo, nombre  from articulosweb where nombre like '%".strtoupper($_POST['query'])."%' union 
						   select first 5 idarticuloweb, id as id, nombre from subarticulosweb where nombre like '%".strtoupper($_POST['query'])."%' ";
	$result_autocomplete = ibase_query($conection->getConexion(), $query_autocomplete) or die(ibase_errmsg());

	while($row_autocomplete = ibase_fetch_object($result_autocomplete, IBASE_TEXT)){
		$index = count($arreglo_autocomplete);

		$arreglo_autocomplete[$index]['IDARTICULO'] = $row_autocomplete->IDARTICULO;
		$arreglo_autocomplete[$index]['IDSUBARTICULO'] = $row_autocomplete->IDSUBARTICULO;
		$arreglo_autocomplete[$index]['NOMBRE'] = utf8_encode($row_autocomplete->NOMBRE);
	}

	$obj = (object) $arreglo_autocomplete;
    echo json_encode($obj);
}

if($_POST['accion'] == 'autocomplete_proveedor')
{
    $conection = new conexion_nexos(1);

    $arreglo_autocomplete = array();
    $query_autocomplete = "select first 5 nombre  from PROVEEDORWEB where ESTATUS=0 and nombre like '%".strtoupper($_POST['query'])."%'";
    $result_autocomplete = ibase_query($conection->getConexion(), $query_autocomplete) or die(ibase_errmsg());

    while($row_autocomplete = ibase_fetch_object($result_autocomplete, IBASE_TEXT)){
        $index = count($arreglo_autocomplete);
        $arreglo_autocomplete[$index]['NOMBRE'] = utf8_encode($row_autocomplete->NOMBRE);
    }

    $obj = (object) $arreglo_autocomplete;
    echo json_encode($obj);
}


function calcula_subtotales($empresa, $tabla, $condicionales, $namempresa, $notas, $condicionales2 = "")
{
    $conection = new conexion_nexos($empresa);

    $campo_suma = "IMPORTE_NETO";
    $campos = "FECHA";

    $json = $conection->sum_advanced($campo_suma, $campos, $tabla, array(), $condicionales, 0, $namempresa);

		$json2 = array();
		if($notas == 1)
		{
			$campo_suma = "IMPORTES_DOCTOS_CC.IMPORTE";
			$campos = "IMPORTES_DOCTOS_CC.FECHA";
			$tabla = "DOCTOS_CC";
			$join = array("IMPORTES_DOCTOS_CC", "=", "DOCTOS_CC.DOCTO_CC_ID", "IMPORTES_DOCTOS_CC.DOCTO_CC_ID", "LEFT");
			$json2 = $conection->sum_advanced($campo_suma, $campos, $tabla, $join, $condicionales2, 0, "NOTAS DE CARGO");

		}
		$json3 = array_merge($json, $json2);

    return $json3;
}


?>
