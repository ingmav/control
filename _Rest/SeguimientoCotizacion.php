<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$candado = " AND DOCTOS_VE.FECHA>='2019-01-01'";
		
		$campos = array( "DOCTOS_VE.DOCTO_VE_ID",
                         "DOCTOS_VE.ESTATUS",
						 "DOCTOS_VE.IMPORTE_NETO",
						 "DOCTOS_VE.TOTAL_IMPUESTOS",
						 "DOCTOS_VE.FOLIO",
						 "SEGUIMIENTOCOTIZACION.MODIFICADO_AL",
						 "DOCTOS_VE.FECHA", 
						 "CLIENTES.NOMBRE", 
						 "DOCTOS_VE.DESCRIPCION", 
						 "DOCTOS_VE.TIPO_DOCTO",
						 "OPERADOR.ALIAS",
						 "SEGUIMIENTOCOTIZACION.IDESTATUS");
		
		$join = array( "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID", "UNION",
			 		   "SEGUIMIENTOCOTIZACION","=", "SEGUIMIENTOCOTIZACION.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID", "LEFT",
			 		   "OPERADOR","=", "OPERADOR.ID", "SEGUIMIENTOCOTIZACION.IDOPERADOR", "LEFT");
		
	    $condicionales = "";
		if($_POST['buscar'] !="")
		{
			$condicionales.=" AND DOCTOS_VE.FOLIO LIKE '%".$_POST['buscar']."' ";
		}

		if($_POST['filtroEstatus']==0 || $_POST['filtroEstatus']=="")
			$condicionales.=" AND (SEGUIMIENTOCOTIZACION.IDESTATUS IS NULL OR SEGUIMIENTOCOTIZACION.IDESTATUS=0)";
		else
		{
			$condicionales.=" AND SEGUIMIENTOCOTIZACION.IDESTATUS=".$_POST['filtroEstatus'];
		}

		$condicionales .= " AND  DOCTOS_VE.TIPO_DOCTO='C' AND DOCTOS_VE.ESTATUS='P' ".$candado;
		
		$order = array("DOCTOS_VE.FECHA DESC");


		//$conection = new conexion_nexos(1);
		$json = $conection->select_table_advanced2($campos, "DOCTOS_VE", $join, $condicionales, $order, 0, $_POST['page']);
		$index = 0;
		while($index < count($json))
		{
            if($_POST['empresa'] == 1)
            {
			    $json[$index]['NOMBREEMPRESA'] = "NX";
                $json[$index]['EMPRESA'] = 1;
            }else if($_POST['empresa'] == 2)
            {
                $json[$index]['NOMBREEMPRESA'] = "NP";
                $json[$index]['EMPRESA'] = 2;
            }

			$json[$index]['DOCTOS_VE.IMPORTE_NETO'] = number_format(($json[$index]['DOCTOS_VE.IMPORTE_NETO'] + $json[$index]['DOCTOS_VE.TOTAL_IMPUESTOS']),2);
			
			$index++;
		}


		/*$conection2 = new conexion_nexos(2);
		$json2 = $conection2->select_table_advanced2($campos, "DOCTOS_VE", $join, $condicionales, $order, 0);
		
		$index = 0;
		while($index < count($json2))
		{
			$json2[$index]['NOMBREEMPRESA'] = "NP";
			$json2[$index]['EMPRESA'] = 2;
			$json2[$index]['DOCTOS_VE.IMPORTE_NETO'] = number_format(($json2[$index]['DOCTOS_VE.IMPORTE_NETO'] * 1.16),2);
			$index++;
		}		
		
		$json3 = Array();
		$json3 = array_merge($json2, $json);
		
		$count = 0;
		$contador = count($json3);
		$arrayAuxiliar = Array();
		$j = 1;
		for($i = 0; $i < $contador; $i++)
		{
			$j = ($i + 1);
			for(; $j < $contador; $j++)
			{
        		if($json3[$i]['DOCTOS_VE.FECHA'] < $json3[$j]['DOCTOS_VE.FECHA'])
				{
					$arrayAuxiliar[0] = $json3[$i];
					$json3[$i] = $json3[$j];	
					$json3[$j] = $arrayAuxiliar[0];
				}
			}
		}
        */
        //$_SESSION['seguimientoReporte'] = $json;

		/*$arrayEnviar = Array();
		$page = ($_POST['page'] -1);
		for($i = (0 + ($page * 20)); $i < (($page * 20) + 20); $i++)
		{
			if(!empty($json3[$i]))
			$arrayEnviar[] = $json3[$i];
		}
        */
		$obj = (object) $json;
		echo json_encode($obj);
	}


	if($_POST["accion"] == "observaciones")
	{
		$campos = array("DESCRIPCION", "FECHA");
		
		$join = array();
		
		$condicionales = " AND DOCTO_VE_ID=".$_POST['id'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "OBSERVACIONCOTIZACION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "saveObservacion")
	{
		if(strlen(trim($_POST['observacion'])) > 0)
		{
			$campos = array("DOCTO_VE_ID", "DESCRIPCION", "IDOPERADOR");
			$valores = array($_POST['iddocto_ve_id'], "'".$_POST['observacion']."'", $_SESSION['IDUSUARIO']);
			
			$json = $conection->insert_table($campos, "OBSERVACIONCOTIZACION", $valores);
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$json = array("observacion"=>"no agregado");
			$obj = (object) $json;
			echo json_encode($obj);	
		}
	}

	if($_POST["accion"] == "countMessaje")
	{
		$join = array();
		
		$condicionales = " AND DOCTO_VE_ID=".$_POST['id']."";
		
		$order = array();
		
		$arreglo = $conection->counter("OBSERVACIONCOTIZACION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "cambiaEstatus")
	{
		$conection2 = new conexion_nexos($_POST['empresa']);
		$condicionales = " AND SEGUIMIENTOCOTIZACION.DOCTO_VE_ID=".$_POST['id'];
		$json2 = $conection2->select_table_first(array("SEGUIMIENTOCOTIZACION.ID"), "SEGUIMIENTOCOTIZACION", array(), $condicionales, array(), 0);

		if(count($json2)>0)
		{
			$campos = array("IDESTATUS","MODIFICADO_AL", "IDOPERADOR");
			$valores = array($_POST['valor'], "'".date("d.m.Y H.i.s")."'", $_SESSION['IDUSUARIO']);
			$id = "DOCTO_VE_ID=".$_POST['id'];
			$json3 = $conection2->update_table($campos, "SEGUIMIENTOCOTIZACION", $valores, $id);

		}else
		{
			$campos = array("DOCTO_VE_ID", "IDESTATUS","MODIFICADO_AL", "IDOPERADOR");
			$valores = array($_POST['id'], $_POST['valor'], "'".date("d.m.Y H.i.s")."'", $_SESSION['IDUSUARIO']);
			$json3 = $conection2->insert_table($campos, "SEGUIMIENTOCOTIZACION", $valores);
		}
		$obj = (object) $json3;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "counter")
	{
	
		$join2 = array("SEGUIMIENTOCOTIZACION","=", "SEGUIMIENTOCOTIZACION.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID");
		
		if($_POST['buscar'] !="")
		{
			$condicionales2.=" AND DOCTOS_VE.FOLIO LIKE '%".$_POST['buscar']."'";
		}

		if($_POST['filtroEstatus']==0 || $_POST['filtroEstatus']=="")
			$condicionales2.=" AND (SEGUIMIENTOCOTIZACION.IDESTATUS IS NULL OR SEGUIMIENTOCOTIZACION.IDESTATUS=0)";
		else
		{
			$condicionales2.=" AND SEGUIMIENTOCOTIZACION.IDESTATUS=".$_POST['filtroEstatus'];
		}

		$condicionales2 .= " AND  DOCTOS_VE.TIPO_DOCTO='C' AND DOCTOS_VE.ESTATUS='P' AND DOCTOS_VE.FECHA>='2016-01-01'";
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json2 = $conection2->counter("DOCTOS_VE", $join2, $condicionales2, 0);

		/*$conection3 = new conexion_nexos(2);
		$json3 = $conection3->counter("DOCTOS_VE", $join2, $condicionales2, 0);
		*/
		$counter_final['PAGINADOR'] = $json2->PAGINADOR;
		$obj = (object) $counter_final;
		echo json_encode($obj);
	}
?>