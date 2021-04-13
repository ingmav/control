<?php
	include("../../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	session_start();
	
	$_SESSION['INVENTARIO'];

	if($_POST["accion"] == "index")
	{
        if($_POST['filtro'] == 'null')
            $_POST['filtro'] = 2069;

        $json = inventario($_POST['filtro'],0, $_POST['activa']);
		$_SESSION['INVENTARIO'] = $json;

		$obj = (object) $json;
		echo json_encode($obj);

	}

    if($_POST['accion'] == "inicializafiltro")
    {

        $conection2 = new conexion_nexos(1);
        $query = "select distinct(nombrelinea) as nombrelinea, linea_articulo_id from articulosweb  where nombrelinea<>''  order by nombrelinea";

        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

        $juegos = array();
        while($row = ibase_fetch_object ($result, IBASE_TEXT))
        {
            $index = count($juegos);
            $juegos[$index]['NOMBRELINEA'] = $row->NOMBRELINEA;
            $juegos[$index]['IDLINEA']    = $row->LINEA_ARTICULO_ID;
        }
        $obj = (object) $juegos;
        echo json_encode($obj);
    }

    if($_POST['accion'] ==  "inicializacomboarticulos")
    {
        $conection = new conexion_nexos(1);

        $campos = array("ID","NOMBRE");

        $join = array();

        $condicionales = " and linea_articulo_id=".$_POST['linea'];

        $order = array("NOMBRE");

        $json = $conection->select_table($campos, "ARTICULOSWEB", $join, $condicionales, $order, 0);


        $obj = (object) $json;
        echo json_encode($obj);
    }

    if($_POST['accion'] ==  "inicializasubcomboarticulos")///////////////////////////////////////////////////////////////////////////////////////////77
    {
        $conection = new conexion_nexos(1);


        $campos = array("ID","NOMBRE");

        $join = array();

        $condicionales = " and idarticuloweb=".$_POST['articulo'];

        $order = array("NOMBRE");

        $json = $conection->select_table($campos, "SUBARTICULOSWEB", $join, $condicionales, $order, 1);


        $obj = (object) $json;
        echo json_encode($obj);
    }

    if($_POST['accion'] == "inicializafechainventario")
    {
        $conection3 = new conexion_nexos(1);

        $row = Array();
        $registro = $conection3->select_last_row_table("INVENTARIOCORTE", array(), " order by INVENTARIOCORTE.ID DESC");

        if($registro->FECHA_FIN == "")
            $registro->FECHA_FIN = date("d.m.Y H:i:s");
        $row[] = $registro;


        $res= $conection3->counter("INVENTARIOCORTE", array(), "",0);
        $row[] = ($res->PAGINADOR - 1);

        $obj = (object) $row;
        echo json_encode($obj);
    }

	if($_POST["accion"] == "inicializainventario")
	{

		$conection2 = new conexion_nexos(1);

        $contador = $conection2->counter("INVENTARIOCORTE", array(), "", 0);

		$json1 = $conection2->insert_table(array("ESTATUS","IDOPERADOR", "INVENTARIO_NO"), "INVENTARIOCORTE", array(2, $_SESSION['IDUSUARIO'], $contador->PAGINADOR));
		$json2 = $conection2->select_max_table("ID", "INVENTARIOCORTE", array(), "");
		
		$campos2 = array("ID");

		$condicionales = " ";
		$json3 = $conection2->select_table($campos2, "ARTICULOSWEB", array(), $condicionales, array(), 1, NULL);
		
		foreach ($json3 as $key => $value) {
			$campos = array("IDINVENTARIOCORTE", "IDARTICULOWEB", "INVENTARIO_INICIAL", "INVENTARIO_FINAL", "REAJUSTE");

			$valores = array($json2, $value['ID'], 0,0, 0);
			
			$json4 = $conection2->insert_table($campos, "INVENTARIO", $valores);
			
		}
		
		$obj = (object) $json4;
		echo json_encode($obj);
	
	}

	if($_POST["accion"] == "inicializaarticulos")
	{
		$conection = new conexion_nexos(1);

		$campos = array("ID","NOMBRE");
		
		$join = array();
		
		$condicionales = " ";
		
		$order = array("NOMBRE");
		
		$json = $conection->select_table($campos, "ARTICULOSWEB", $join, $condicionales, $order, 1);
		
		
		$obj = (object) $json;
		echo json_encode($obj);
	
	}

	if($_POST["accion"] == "saveArticulo")
	{
		$conection = new conexion_nexos(1);

		$campos = array("IDARTICULOWEB", "IDSUBARTICULOWEB","CANTIDAD", "PROVEEDOR", "IDOPERADOR", "IMPORTE");
		
		$valores = array($_POST['agregarArticulo'], $_POST['agregarsubArticulo'], $_POST['cantidadArticulo'], "'".$_POST['proveedorArticulo']."'", $_SESSION['IDUSUARIO'], "'".$_POST['importeArticulo']."'");
		
		$json = $conection->insert_table($campos, "INGRESOINVENTARIO", $valores);
        
        if($_POST['agregarsubArticulo'] > 0)
        {

            $campos = array("CANTIDAD");

            $join = array();

            $condicionales = " AND ID=".$_POST['agregarsubArticulo'];

            $order = array();

            $json1 = $conection->select_table($campos, "SUBARTICULOSWEB", $join, $condicionales, $order, 1);


            $valores = array($json1[0]['CANTIDAD']+$_POST['cantidadArticulo']);

            $json = $conection->update_table($campos, "SUBARTICULOSWEB", $valores, " ID=".$_POST['agregarsubArticulo']);
        }
		$obj = (object) $json;
		echo json_encode($obj);
	
	}

    if($_POST['accion'] == "inicializasubarticulos")
    {
        /*Inicializa inventario operacion*/
        $conection = new conexion_nexos(1);


        //$json_fecha = $conection->select_max_table("FECHA_INICIO", "INVENTARIOCORTE", array(), "");
        $json_fecha = $conection->select_last_row_table("INVENTARIOCORTE", array(), " order by INVENTARIOCORTE.ID DESC");;
        if($json_fecha->FECHA_FIN == '')
            $json_fecha->FECHA_FIN = date("d.m.Y H:i:s");

        $query1 = "SELECT idsubarticuloweb, sum(cantidad  + merma) as unidades from inventarioimpresion where fecha>='".$json_fecha->FECHA_INICIO."' AND fecha <='".$json_fecha->FECHA_FIN."'  AND idarticuloweb=".$_POST['id']." GROUP BY idsubarticuloweb";

        $resultInventariooperaciones = ibase_query($conection->getConexion(), $query1) or die(ibase_errmsg());

        $inventariooperaciones = array();
        while($row = ibase_fetch_object ($resultInventariooperaciones, IBASE_TEXT))
        {
            $index = count($inventariooperaciones);
            $inventariooperaciones[$index]['IDSUBARTICULOWEB'] = $row->IDSUBARTICULOWEB;
            $inventariooperaciones[$index]['UNIDADES'] 	       = $row->UNIDADES;
        }

        $conection2 = new conexion_nexos($_SESSION['empresa']);
        $query1 = "SELECT idsubarticuloweb, sum(cantidad  + merma) as unidades from inventarioimpresion where fecha>='".$json_fecha->FECHA_INICIO."' AND fecha <='".$json_fecha->FECHA_FIN."' AND idarticuloweb=".$_POST['id']." GROUP BY idsubarticuloweb";

        $inventariooperaciones2 = array();
        $resultInventariooperaciones2 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());


       while($row2 = ibase_fetch_object ($resultInventariooperaciones2, IBASE_TEXT))
        {
            $index = count($inventariooperaciones2);
            $inventariooperaciones2[$index]['IDSUBARTICULOWEB'] = $row2->IDSUBARTICULOWEB;
            $inventariooperaciones2[$index]['UNIDADES'] 	    = $row2->UNIDADES;
        }


        /*Fin invenntario operacion*/
        /*Inicializa inventario de suubarticulos*/

        /*
         * UNION INVENTARIOS
         */
        foreach($inventariooperaciones as $key1 => $value1)
         {
             foreach($inventariooperaciones2 as $key2 => $value2)
             {
                if($inventariooperaciones[$key1]['IDSUBARTICULOWEB'] == $inventariooperaciones2[$key2]['IDSUBARTICULOWEB'])
                {
                    $inventariooperaciones[$key1]['UNIDADES'] += $inventariooperaciones2[$key2]['UNIDADES'];
                    unset($inventariooperaciones2[$key2]);
                }

             }
         }

        /*
         * fIN UNION INVENTARIO
         */
        $inventariooperacionesfinal = Array();
        $inventariooperacionesfinal = array_merge($inventariooperaciones, $inventariooperaciones2);
        $conection = new conexion_nexos(1);
        $campos = array("ID","IDARTICULOWEB","NOMBRE", "CANTIDAD");

        $join = array();

        $condicionales = " AND IDARTICULOWEB=".$_POST['id'];

        $order = array("IDARTICULOWEB");

        $json = $conection->select_table($campos, "SUBARTICULOSWEB", $join, $condicionales, $order, 1);

        foreach($json  as $key => $index)
        {
            $json[$key]["INVENTARIO"] = 0;
            $json[$key]["FINAL"] = $json[$key]['CANTIDAD'];
            foreach($inventariooperacionesfinal as $key2 => $index2)
            {
                if($index2['IDSUBARTICULOWEB'] == $json[$key]['ID'])
                {
                    $json[$key]["INVENTARIO"] = $index2['UNIDADES'];
                    $json[$key]["FINAL"] = $json[$key]['CANTIDAD'] - $index2['UNIDADES'];
                }

            }

        }

        $obj = (object) $json;
        echo json_encode($obj);
    }

    if($_POST["accion"] == "cargasubarticulos")
    {
        $conection = new conexion_nexos(1);

        $campos = array("ID", "IDARTICULOWEB", "NOMBRE","CANTIDAD");

        $join = array();

        $condicionales = " ";

        $order = array("NOMBRE");

        $json = $conection->select_table($campos, "SUBARTICULOSWEB", $join, $condicionales, $order, 1);


        foreach($json as $key => $value)
        {

            $campos = array("NOMBRE");

            $join = array();

            $condicionales = " AND ID=".$value['IDARTICULOWEB'];

            $order = array();

            $json2 = $conection->select_table($campos, "ARTICULOSWEB", $join, $condicionales, $order, 1);

            $json[$key]['NOMBREARTICULO'] = $json2[0]['NOMBRE'];
        }
        $obj = (object) $json;
        echo json_encode($obj);
    }

    if($_POST['accion'] == "guardasubarticulos")
    {
        //Finaliza todo////
        $conection = new conexion_nexos(1);
        foreach ($_POST['ARTICULOSWEB'] as $key => $value) {
            $campos = array("CANTIDAD");
            $valores = array($_POST['subarticulo_'.$value]);
            $join = array();

            $condicionales = " ";

            $order = array("ARTICULOSWEB.NOMBRE");

            $json = $conection->update_table($campos, "SUBARTICULOSWEB", $valores , " ID=".$value);
        }

        $jsonidfecha = $conection->select_max_table("ID", "INVENTARIOCORTE", array(), "");

        $query = "SELECT IDARTICULOWEB, SUM(CANTIDAD) as cantidad FROM SUBARTICULOSWEB WHERE BORRADO IS NULL GROUP BY IDARTICULOWEB";

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $updateinventario = array();
        while($row = ibase_fetch_object ($result, IBASE_TEXT))
        {
            $index = count($updateinventario);
            $updateinventario[$index]['IDARTICULOWEB'] = $row->IDARTICULOWEB;
            $updateinventario[$index]['CANTIDAD'] 	 = $row->CANTIDAD;
        }

        foreach ($updateinventario as $key => $value) {
            $campos = array("REAJUSTE");
            $valores = array($value['CANTIDAD']);
            $join = array();

            $condicionales = " ";

            $json2 = $conection->update_table($campos, "INVENTARIO", $valores , " IDINVENTARIOCORTE=".$jsonidfecha." AND IDARTICULOWEB=".$value['IDARTICULOWEB']);

        }

        $inventarioFinal = inventario(0,1);

        $campos 	= array("ESTATUS", "FECHA_FIN");
        $valores 	= array(1, "'".date("Y.m.d H:i:s")."'");

        $json3 = $conection->update_table($campos, "INVENTARIOCORTE", $valores , " ID=".$jsonidfecha);
        $contador = $conection->counter("INVENTARIOCORTE", array(), "", 0);
        $json4 = $conection->insert_table(array("ESTATUS","IDOPERADOR", "INVENTARIO_NO"), "INVENTARIOCORTE", array(1, $_SESSION['IDUSUARIO'], $contador->PAGINADOR));

        $jsonidfecha = $conection->select_max_table("ID", "INVENTARIOCORTE", array(), "");

        $campos = array("ARTICULOSWEB.ID");

        $join = array();

        $condicionales .= " ";

        $order = array();

        $json5 = $conection->select_table($campos, "ARTICULOSWEB", $join, $condicionales, $order, 1);


        foreach ($json5 as $key => $value) {
            foreach ($inventarioFinal as $key2 => $value2) {

                if($value['ARTICULOSWEB.ID'] == $value2['ARTICULOSWEB.ID']){
                    $json5[$key]["INVENTARIO"] = $value2['REAJUSTE'];
                }else
                    $json5[$key]["INVENTARIO"] += 0;
            }
        }

        foreach ($json5 as $key => $value) {
           $conection->insert_table(array("IDINVENTARIOCORTE","IDARTICULOWEB", "INVENTARIO_INICIAL"), "INVENTARIO", array($jsonidfecha, $value['ARTICULOSWEB.ID'], $value['INVENTARIO']));
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        $obj = (object) $json5;
        echo json_encode($obj);
        /*fIN GUARDA SUB ARTICULOS*/
    }


	if($_POST["accion"] == "disminurArticulo")
	{
		$conection = new conexion_nexos(1);

		$campos = array("IDARTICULOWEB","CANTIDAD");
		
		$valores = array($_POST['sustraerArticulo'], $_POST['cantidadArticulo']);
		
		$json = $conection->insert_table($campos, "SUSTRAERINVENTARIO", $valores);

        $obj = (object) $json;
        echo json_encode($obj);
	
	}
	
	if($_POST["accion"] == "reajuste")
	{
		$conection3 = new conexion_nexos(1);

		$jsonidfecha = $conection3->select_max_table("ID", "INVENTARIOCORTE", array(), "");

		$campos 	= array("ESTATUS");
		$valores 	= array(2);

		$json = $conection3->update_table($campos, "INVENTARIOCORTE", $valores , " ID=".$jsonidfecha);

		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "corte")
	{
		$json = inventario();
	}

	if($_POST["accion"] == "fillarticulos")
	{
        $conection = new conexion_nexos(1);
        $data = $conection->select_distinct_table("SUBARTICULOSWEB.IDARTICULOWEB", "SUBARTICULOSWEB", array(), "", 0);

        $json = inventario(0,1);
        //print_r($json);
        foreach ($json as $key => $value) {
            foreach ($data as $key2 => $value2) {
                if($value['ARTICULOSWEB.ID'] == $data[$key2]){
                    unset($json[$key]);
                }
            }
        }

		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "addReajuste")
	{
		$conection1 = new conexion_nexos(1);

		$jsonid = $conection1->select_max_table("ID", "INVENTARIOCORTE", array(), "");
        $fecha_inicio = $conection1->select_max_table("FECHA_INICIO", "INVENTARIOCORTE", array(), "");

        //Nuevo
        $query = "select ic.id, ii.idarticuloweb, (sum(ii.cantidad) + sum(i.inventario_inicial)) as INICIAL_INGRESO from ingresoinventario ii, inventariocorte ic, inventario i
where ii.fecha>=ic.fecha_inicio and ii.idarticuloweb=i.idarticuloweb and i.idinventariocorte = ic.id group by ic.id, ii.idarticuloweb, i.inventario_inicial having maxvalue(ic.id) = ic.id";

        $result = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());
        $ingreso_total = array();
        $index1 = 0;
        $ingreso_buscador = array();
        while($row = ibase_fetch_object ($result, IBASE_TEXT))
        {
            $index1 = count($ingreso_total);
            $ingreso_buscador[] = $row->IDARTICULOWEB;
            $ingreso_total[$index1]['INGRESO'] = $row->INICIAL_INGRESO;
            $ingreso_total[$index1]['ID'] = $row->IDARTICULOWEB;
        }



        $query2 = "select  ii.idarticuloweb, sum(ii.cantidad + ii.merma) as BAJA from inventarioimpresion ii where ii.fecha>='".$fecha_inicio."' group by  ii.idarticuloweb";

        $result2 = ibase_query($conection1->getConexion(), $query2) or die(ibase_errmsg());
        $baja_total1 = array();
        $index2 = 0;
        $baja_buscador1 = array();

        while($row2 = ibase_fetch_object ($result2, IBASE_TEXT))
        {
            $index2 = count($baja_total1);
            $baja_buscador1[] = $row2->IDARTICULOWEB;
            $baja_total1[$index2]['BAJA'] = $row2->BAJA;
            $baja_total1[$index2]['ID'] = $row2->IDARTICULOWEB;
        }

        $conection2 = new conexion_nexos($_SESSION['empresa']);

        $result3 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());
        $baja_total2 = array();
        $index3 = 0;
        $baja_buscador2 = array();

        while($row3 = ibase_fetch_object ($result3, IBASE_TEXT))
        {
            $index3 = count($baja_total2);
            $baja_buscador2[] = $row3->IDARTICULOWEB;
            $baja_total2[$index3]['BAJA'] = $row3->BAJA;
            $baja_total2[$index3]['ID'] = $row3->IDARTICULOWEB;
        }

        $conection1 = new conexion_nexos(1);

        $array_completo = array();
        foreach ($_POST['ARTICULOSWEB'] as $key => $value) {
			$campos = array("INGRESO_TOTAL", "BAJA_TOTAL", "INVENTARIO_FINAL", "REAJUSTE");

            $array_completo[$key]['REAJUSTE'] = $_POST['cantidad_'.$value];
            //$valores = array();
            //Fin nuevo

            $valor_inventario_final = 0;
            $baja_total = 0;

            if(in_array($value, $ingreso_buscador))
            {
                $valor_inventario_final +=  $ingreso_total[array_search($value, $ingreso_buscador)]['INGRESO'];
                $array_completo[$key]['ingreso'] = $ingreso_total[array_search($value, $ingreso_buscador)]['INGRESO'];
            }else
                $array_completo[$key]['ingreso'] = 0;

            if(in_array($value, $baja_buscador1))
            {
                $valor_inventario_final -=  $baja_total1[array_search($value, $baja_buscador1)]['BAJA'];
                $baja_total += $baja_total1[array_search($value, $baja_buscador1)]['BAJA'];
            }

            if(in_array($value, $baja_buscador2))
            {
                $valor_inventario_final -=  $baja_total2[array_search($value, $baja_buscador2)]['BAJA'];
                $baja_total += $baja_total2[array_search($value, $baja_buscador2)]['BAJA'];
            }

            $array_completo[$key]['baja'] = $baja_total;
            $array_completo[$key]['ID'] = $value;
            //$array_completo[$key]['reajuste'] = $_POST['cantidad_'.$value];
            $array_completo[$key]['FINAL'] = $valor_inventario_final;

            $valores = array($array_completo[$key]['ingreso'], $array_completo[$key]['baja'], $array_completo[$key]['FINAL'], $array_completo[$key]['REAJUSTE']);

            $json = $conection1->update_table($campos, "INVENTARIO", $valores , " IDINVENTARIOCORTE='".$jsonid."' AND IDARTICULOWEB='".$value."'");
		}
       /* Fin */

        $arreglo = array($valor_inventario_final1, $valor_inventario_final2, $valor_inventario_final3);
        $row = array($array_completo);
        $obj = (object) $row;
		//$obj = (object) $json;
		echo json_encode($obj);	
	}
	
	if($_POST["accion"] == "validaInicializacion")
	{
		$conection1 = new conexion_nexos(1);

		$jsonestatus = $conection1->select_max_table("ESTATUS", "INVENTARIOCORTE", array(), "");

        if($jsonestatus == null)
            $jsonestatus = 0;


		$data = array("resultado"=>$jsonestatus);
		$obj = (object) $data;
		echo json_encode($obj);	
	}

    function inventario($filter = 0, $default = 0, $activa = 0)
    {
        $conection3 = new conexion_nexos(1);


        //$json_last_row = $conection3->select_last_row_table("INVENTARIOCORTE", array(), "");
        $jsonid = $conection3->select_max_table("ID", "INVENTARIOCORTE", array(), "");
        $fecha_inicio = $conection3->select_max_table("FECHA_INICIO", "INVENTARIOCORTE", array(), "");
        $ultimo_registro = $conection3->select_last_row_table("INVENTARIOCORTE", array(), " order by INVENTARIOCORTE.ID DESC");
	

        if($ultimo_registro->FECHA_FIN == "")
            $ultimo_registro->FECHA_FIN = date("d.m.Y H:i:s");
        //
        if($filter == 0)
            $filtro = "";
        else
            $filtro = "AND AW.LINEA_ARTICULO_ID='".$filter."'";



        $query2 = "SELECT AW.ID, AW.LINEA_ARTICULO_ID, I.IDARTICULOWEB, I.INVENTARIO_INICIAL, IC.FECHA_INICIO, AW.NOMBRE AS ARTICULO, AW.UNIDAD, AW.NOMBRELINEA FROM INVENTARIO I, INVENTARIOCORTE IC, ARTICULOSWEB AW WHERE I.IDARTICULOWEB = AW.ID AND I.IDINVENTARIOCORTE=IC.ID ".$filtro." AND IC.ID='".$jsonid."'  ORDER BY AW.LINEA_ARTICULO_ID, AW.NOMBRE";

        //$query3 = "SELECT IDARTICULOWEB, SUM(CANTIDAD) as CANTIDAD FROM INGRESOINVENTARIO RAW WHERE fecha>'".$fecha_inicio."' GROUP BY IDARTICULOWEB";
        $query3 = "SELECT IDARTICULOWEB, SUM(CANTIDAD) as CANTIDAD FROM INGRESOINVENTARIO RAW WHERE fecha>='".$ultimo_registro->FECHA_INICIO."' AND fecha<='".$ultimo_registro->FECHA_FIN."' GROUP BY IDARTICULOWEB";

       //$query5 = "SELECT idarticuloweb, sum(cantidad + merma) as unidades, sum(merma + 0) as merma from inventarioimpresion where fecha>='".$fecha_inicio."'  GROUP BY idarticuloweb";
       $query5 = "SELECT idarticuloweb, sum(cantidad + merma) as unidades, sum(merma + 0) as merma from inventarioimpresion where fecha>='".$ultimo_registro->FECHA_INICIO."' AND fecha<='".$ultimo_registro->FECHA_FIN."' GROUP BY idarticuloweb";

        $resultIngresos = ibase_query($conection3->getConexion(), $query3) or die(ibase_errmsg());

        $resultInventariooperaciones1 = ibase_query($conection3->getConexion(), $query5) or die(ibase_errmsg());

        $ingresoInventario = array();
        $buscador_ingresos = array();
        while($row = ibase_fetch_object ($resultIngresos, IBASE_TEXT))
        {
            $index = count($ingresoInventario);
            $ingresoInventario[$index]['IDARTICULOWEB']         = $row->IDARTICULOWEB;
            $ingresoInventario[$index]['CANTIDAD'] 	            = $row->CANTIDAD;
            $buscador_ingresos[$index]                                = $row->IDARTICULOWEB;
        }


        $inventariooperacionesNX = array();
        $buscador_baja1 = array();
        while($row = ibase_fetch_object ($resultInventariooperaciones1, IBASE_TEXT))
        {
            $index = count($inventariooperacionesNX);
            $inventariooperacionesNX[$index]['IDARTICULOWEB']           = $row->IDARTICULOWEB;
            $inventariooperacionesNX[$index]['INVENTARIO_INICIAL'] 	    = $row->UNIDADES;
            $inventariooperacionesNX[$index]['MERMA'] 	                = $row->MERMA;
            $buscador_baja1[$index]                                           = $row->IDARTICULOWEB;
        }
	

        $conection2 = new conexion_nexos($_SESSION['empresa']);
        $buscador_baja2 = array();
        $resultInventariooperaciones2 = ibase_query($conection2->getConexion(), $query5) or die(ibase_errmsg());
        $inventariooperacionesNP = array();

        while($row = ibase_fetch_object ($resultInventariooperaciones2, IBASE_TEXT))
        {
            $index = count($inventariooperacionesNP);
            $inventariooperacionesNP[$index]['IDARTICULOWEB']         = $row->IDARTICULOWEB;
            $inventariooperacionesNP[$index]['INVENTARIO_INICIAL'] 	= $row->UNIDADES;
            $inventariooperacionesNP[$index]['MERMA'] 	            = $row->MERMA;
            $buscador_baja2[$index]                                       = $row->IDARTICULOWEB;;
        }
	
        $conection3 = new conexion_nexos(1);
        $resultInventario = ibase_query($conection3->getConexion(), $query2) or die(ibase_errmsg());
        $inventario_inicio = array();
        while($row = ibase_fetch_object ($resultInventario, IBASE_TEXT))
        {
            $index = count($inventario_inicio);
            $inventario_inicio[$index]['ID']                    = $row->ID;
            $inventario_inicio[$index]['IDARTICULOWEB']         = $row->IDARTICULOWEB;
            $inventario_inicio[$index]['LINEA_ID']              = $row->LINEA_ARTICULO_ID;
            $inventario_inicio[$index]['INVENTARIO_INICIAL'] 	= $row->INVENTARIO_INICIAL;
            $inventario_inicio[$index]['FECHA_INICIO'] 	        = $row->FECHA_INICIO;
            $inventario_inicio[$index]['NOMBRE'] 	            = utf8_encode($row->ARTICULO);
            $inventario_inicio[$index]['UNIDAD'] 	            = $row->UNIDAD;
            $inventario_inicio[$index]['LINEA'] 	            = $row->NOMBRELINEA;

            if(in_array($row->IDARTICULOWEB, $buscador_ingresos))
            {
                $inventario_inicio[$index]['INGRESO'] = $ingresoInventario[array_search($row->IDARTICULOWEB, $buscador_ingresos)]['CANTIDAD'];
            }else
                $inventario_inicio[$index]['INGRESO'] = 0.0;

            $baja_total = 0;
            if(in_array($row->IDARTICULOWEB, $buscador_baja1))
            {
                $index1 = array_search($row->IDARTICULOWEB, $buscador_baja1);

                $baja_total += floatval($inventariooperacionesNX[$index1]['INVENTARIO_INICIAL']);
            }

            if(in_array($row->IDARTICULOWEB, $buscador_baja2))
            {
                $index2 = array_search($row->IDARTICULOWEB, $buscador_baja2);
                $baja_total += $inventariooperacionesNP[$index2]['INVENTARIO_INICIAL'];
            }

            $inventario_inicio[$index]['BAJA'] = $baja_total;

            $inventario_inicio[$index]['TOTAL'] = (($inventario_inicio[$index]['INVENTARIO_INICIAL'] + $inventario_inicio[$index]['INGRESO']) - $inventario_inicio[$index]['BAJA']);
        }
        return $inventario_inicio;
    }

if($_POST['accion'] == "cargaCortes")
{
    $conection = new conexion_nexos(1);
    $campos = array("ID", "INVENTARIO_NO", "FECHA_INICIO", "FECHA_FIN");

    $data = $conection->select_table($campos, "INVENTARIOCORTE", array(), "", array(), 0);

    $obj = (object) $data;
    echo json_encode($obj);
}
?>	