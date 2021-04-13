<?php
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	session_start();
	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos();
	
	if($_POST["accion"] == "index")
	{
		
		$fecha_inicio = "";
		$fecha_finalizado = "";
		if($_POST['fecha_inicio']!="")
			$fecha_inicio  = $_POST['fecha_inicio'];
		else
			$fecha_inicio = date("Y-m-d");
		if($_POST['fecha_finalizado']!="")
			$fecha_finalizado = $_POST['fecha_finalizado'];
		else
			$fecha_finalizado = date("Y-m-d");
			
		
		$campos = array("CAJA.ID",
						"CAJA.EMPRESA", 
						"CAJA.TIPO", 
						"CAJA.TIPODOCTO", 
						"CAJA.FECHA",
						"CAJA.FOLIO",
						"CAJA.CLIENTE",
						"CAJA.IMPORTE",
						"CAJA.DESCRIPCION");
		
		$join = array();
		
		$condicionales = " AND CAJA.TIPO=3  AND CAJA.FECHA BETWEEN '$fecha_inicio 00:00:00' and '$fecha_finalizado 23:59:59'";
		
		$order = array("CAJA.ID ASC");
		
		$json2 = $conection->select_table_first($campos, "CAJA", $join, $condicionales, $order, 1);

		
		$campos = array("CAJA.ID",
						"CAJA.EMPRESA", 
						"CAJA.TIPO",
						"CAJA.TIPODOCTO", 
						"CAJA.FECHA",
						"CAJA.FOLIO",
						"CAJA.CLIENTE",
						"CAJA.IMPORTE",
						"CAJA.DESCRIPCION");
		
		$join = array();
		
		$condicionales = " AND CAJA.TIPO!=3  AND CAJA.FECHA BETWEEN '$fecha_inicio 00:00:00' and '$fecha_finalizado 23:59:59'";
		
		$order = array("CAJA.TIPO DESC");
		
		$json = $conection->select_table($campos, "CAJA", $join, $condicionales, $order, 1);
		
		$total = 0;
		$contador = 0;

		
		$ingresos = array();
		$egresos = array();
		$saldo_inicial = $json2;
		$saldo_inicial[0]["CAJA.TIPOLOGIA"] = "SALDO INICIAL";
		$total_ingresos = 0;
		$total_egresos = 0;
		foreach ($json as $key => $value) {
			if($json[$key]['CAJA.TIPO'] == 1)
			{
				$index = count($ingresos);
				$ingresos[] = $json[$key];
				$total_ingresos += $json[$key]['CAJA.IMPORTE'];
				//$json[$key]['CAJA.IMPORTE'] = number_format($json[$key]['CAJA.IMPORTE'],2,".","");
				$total += $json[$key]['CAJA.IMPORTE'];
				$ingresos[$index]['CAJA.TIPOLOGIA'] = "INGRESO";
			}
			else if($json[$key]['CAJA.TIPO'] == 2)
			{
				$index = count($egresos);
				$egresos[] = $json[$key];
				$total_egresos += $json[$key]['CAJA.IMPORTE'];
				$total -= $json[$key]['CAJA.IMPORTE'];
				$egresos[$index]['CAJA.TIPOLOGIA'] = "EGRESO";
			}
			
			$contador++;
		} 
		
		$saldototal = $saldo_inicial[0]['CAJA.IMPORTE'] + $total_ingresos - $total_egresos;

		$saldo_inicial[] = array("CAJA.ID"=>"0", "CAJA.EMPRESA"=>3, "CAJA.TIPO"=>10, "CAJA.TIPODOCTO"=>"S", "CAJA.FOLIO"=>"*********", "CAJA.CLIENTE"=>"*********", "CAJA.FECHA"=>"*********", "CAJA.TIPOLOGIA"=>"<b style='color:#EF1800'>SUBTOTAL SALDO INICIAL</b>", "CAJA.IMPORTE"=>$saldo_inicial[0]['CAJA.IMPORTE'], "CAJA.DESCRIPCION"=>"*********");    
        $ingresos[] = array("CAJA.ID"=>"0", "CAJA.EMPRESA"=>3, "CAJA.TIPO"=>10, "CAJA.TIPODOCTO"=>"S", "CAJA.FOLIO"=>"*********", "CAJA.CLIENTE"=>"*********", "CAJA.FECHA"=>"*********", "CAJA.TIPOLOGIA"=>"<b style='color:#EF1800'>SUBTOTAL INGRESOS</b>", "CAJA.IMPORTE"=>$total_ingresos, "CAJA.DESCRIPCION"=>"*********");    
        $egresos[] = array("CAJA.ID"=>"0", "CAJA.EMPRESA"=>3, "CAJA.TIPO"=>10, "CAJA.TIPODOCTO"=>"S", "CAJA.FOLIO"=>"*********", "CAJA.CLIENTE"=>"*********", "CAJA.FECHA"=>"*********", "CAJA.TIPOLOGIA"=>"<b style='color:#EF1800'>SUBTOTAL EGRESOS</b>", "CAJA.IMPORTE"=>$total_egresos, "CAJA.DESCRIPCION"=>"*********");
        $importetotal[] = array("CAJA.ID"=>"0", "CAJA.EMPRESA"=>3, "CAJA.TIPO"=>10, "CAJA.TIPODOCTO"=>"T", "CAJA.FOLIO"=>"*********", "CAJA.CLIENTE"=>"*********", "CAJA.FECHA"=>"*********", "CAJA.TIPOLOGIA"=>"<b style='color:#EF1800'>TOTAL</b>", "CAJA.IMPORTE"=>$saldototal, "CAJA.DESCRIPCION"=>"*********");    
        
        $inicial = array_merge($saldo_inicial, $egresos);
        $subtotal = array_merge($inicial, $ingresos);
        $tablatotal = array_merge($subtotal, $importetotal);

        foreach ($tablatotal as $key => $value) {
        	$tablatotal[$key]['CAJA.IMPORTE'] = number_format($tablatotal[$key]['CAJA.IMPORTE'],2,".",""); 
        }
		$obj = (object) $tablatotal;
		echo json_encode($obj);
		

		//$obj = (object) $json3;
		//echo json_encode($obj);
	}

	if($_POST["accion"] == "cargacotizacion")
	{

		if(strlen(trim($_POST['folio'])) >0)
		{
			$conection2 = new conexion_nexos($_POST['empresa']);
			$campos = array("DOCTOS_VE.IMPORTE_NETO",
							"DOCTOS_VE.DSCTO_IMPORTE", 
							"CLIENTES.NOMBRE",
							"DOCTOS_VE.DESCRIPCION");
			
			$join = array("CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID");
			
			$condicionales = " AND DOCTOS_VE.FOLIO like '%".$_POST['folio']."' AND DOCTOS_VE.TIPO_DOCTO='".$_POST['tipo']."'";
			
			$order = array();
			
			$json = $conection2->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0);
			
			$json[0]['DOCTOS_VE.IMPORTE_NETO'] += (($json[0]['DOCTOS_VE.IMPORTE_NETO'] * 0.16) - $json[0]['DOCTOS_VE.DSCTO_IMPORTE']); 
			$json[0]['DOCTOS_VE.IMPORTE_NETO'] = number_format($json[0]['DOCTOS_VE.IMPORTE_NETO'],2);
			
		}else
		{
			$json = array(0=>array("DOCTOS_VE.IMPORTE_NETO"=>"","DOCTOS_VE.DSCTO_IMPORTE"=>"", "CLIENTES.NOMBRE"=>"","DOCTOS_VE.DESCRIPCION"=>""));
		}
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "agregaCaja")
	{

		$conection2 = new conexion_nexos(1);
		$campos = array("EMPRESA", "DOCTO_VE_ID", "TIPO", "TIPODOCTO", "FECHA", "FOLIO", "CLIENTE", "IMPORTE", "DESCRIPCION", "IDOPERADOR");

		if($_POST['docto_ve'] == "")
		{
			$_POST['empresa'] = 1;
			$_POST['TIPO'] = "I";
		}

		$valores = array($_POST['empresa'],$_POST['docto_ve'], 1, "'".$_POST['tipoDocumento']."'", "'".date("d.m.Y H.i.s")."'", "'".$_POST['folio']."'", "'".utf8_decode($_POST['cliente'])."'", str_replace(",", "",$_POST['importe']), "'".utf8_decode($_POST['descripcion'])."'", $_SESSION['IDUSUARIO'] );
		
		$json = $conection2->insert_table($campos, "CAJA", $valores);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "sustraerCaja")
	{

		$conection2 = new conexion_nexos(1);
		$campos = array("EMPRESA", "TIPO", "TIPODOCTO", "FECHA", "FOLIO", "CLIENTE", "IMPORTE", "DESCRIPCION", "IDOPERADOR");

		$valores = array(1, 2, "'E'", "'".date("d.m.Y H.i.s")."'", "'".$_POST['folio']."'", "'".utf8_decode($_POST['cliente'])."'", str_replace(",", "",$_POST['importe']), "'".utf8_decode($_POST['descripcion'])."'", $_SESSION['IDUSUARIO'] );
		
		$json = $conection2->insert_table($campos, "CAJA", $valores);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
		

	if($_POST["accion"] == "verificaInicioCaja")
	{
		$conection2 = new conexion_nexos(1);
		$condicionales = " AND CAJA.TIPO=3 AND CAJA.FECHA like '".date("Y-m-d")."%'";
		$json = $conection2->counter("CAJA", array(), $condicionales, 1);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "inicializarCaja")
	{
		$conection2 = new conexion_nexos(1);
		$campos = array("CAJA.TIPO",
						"CAJA.FECHA",
						"CAJA.IMPORTE");
		
		$join = array();
		
		$condicionales = " AND CAJA.TIPO=3";
		
		$order = array("CAJA.FECHA DESC");
		
		$json = $conection2->select_table_first($campos, "CAJA", $join, $condicionales, $order, 1);

		$campos2 = array("CAJA.TIPO", 
						"CAJA.FECHA",
						"CAJA.IMPORTE");

		
		$join2 = array();
		
		$condicionales2 = " AND CAJA.TIPO!=3  AND CAJA.FECHA like '".substr($json[0]['CAJA.FECHA'],0,10)."%'";
			
		$order2 = array("CAJA.ID");
		
		$json2 = $conection2->select_table($campos2, "CAJA", $join2, $condicionales2, $order2, 1);
		
		
		$total = 0;
		$contador = 0;

		$json3 = array_merge($json2, $json);
		

		foreach ($json3 as $key => $value) {
			if($json3[$key]['CAJA.TIPO'] == 1)
				$total += $json3[$key]['CAJA.IMPORTE'];
			else if($json3[$key]['CAJA.TIPO'] == 2)
				$total -= $json3[$key]['CAJA.IMPORTE'];
			else if($json3[$key]['CAJA.TIPO'] == 3)
				$total += $json3[$key]['CAJA.IMPORTE'];
			$contador++;
			
		} 
		
		$campos3 = array("EMPRESA", "TIPO", "TIPODOCTO", "FECHA", "CLIENTE", "IMPORTE", "DESCRIPCION", "IDOPERADOR");

		$valores3 = array(1, 3,"'I'", "'".date("d.m.Y H.i.s")."'", utf8_decode("'OPERACIÓN INICIAL'"), $total, "'SALDO INICIAL'", $_SESSION['IDUSUARIO'] );
		
		$json3 = $conection2->insert_table($campos3, "CAJA", $valores3);
		$obj = (object) $json3;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "cancelaCaja")
	{
		$conection2 = new conexion_nexos(1);
		$id = "CAJA.ID IN ";
		$indices = $_POST["id"];
		$condicinales = " AND CAJA.FECHA LIKE '".date("Y-m-d")."%' AND CAJA.TIPO!=3";
		$json3 = $conection2->delete_table_completo("CAJA", $id, $indices, $condicinales);
		$obj = (object) $json3;
		echo json_encode($obj);
	}


	if($_POST["accion"] == "verificaSaldo")
	{
		$conection2 = new conexion_nexos(1);
		$campos = array("CAJA.TIPO",
						"CAJA.FECHA",
						"CAJA.IMPORTE");
		
		$join = array();
		
		$condicionales = " AND CAJA.TIPO=3";
		
		$order = array("CAJA.FECHA DESC");
		
		$json = $conection2->select_table_first($campos, "CAJA", $join, $condicionales, $order, 1);

		$campos2 = array("CAJA.TIPO", 
						"CAJA.FECHA",
						"CAJA.IMPORTE");

		
		$join2 = array();
		
		$condicionales2 = " AND CAJA.TIPO!=3  AND CAJA.FECHA like '".substr($json[0]['CAJA.FECHA'],0,10)."%'";
			
		$order2 = array("CAJA.ID");
		
		$json2 = $conection2->select_table($campos2, "CAJA", $join2, $condicionales2, $order2, 1);
		
		
		$total = 0;
		$contador = 0;

		$json3 = array_merge($json2, $json);
		

		foreach ($json3 as $key => $value) {
			if($json3[$key]['CAJA.TIPO'] == 1)
				$total += $json3[$key]['CAJA.IMPORTE'];
			else if($json3[$key]['CAJA.TIPO'] == 2)
				$total -= $json3[$key]['CAJA.IMPORTE'];
			else if($json3[$key]['CAJA.TIPO'] == 3)
				$total += $json3[$key]['CAJA.IMPORTE'];
			$contador++;
			
		}
		$json4 = array(0=>array("TOTAL"=>$total));
		$obj = (object) $json4;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "importe")
	{
				
		$campos = array("DOCTOS_VE.DOCTO_VE_ID",
						"DOCTOS_VE.FOLIO",
						"CLIENTES.NOMBRE",
						"DOCTOS_VE.DESCRIPCION",
						"DOCTOS_VE.IMPORTE_NETO");
		
		$join = array("CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID");


		
		$condicionales = " AND DOCTOS_VE.FOLIO LIKE '%".$_POST['folio']."' AND DOCTOS_VE.FECHA>'2015.01.01' AND DOCTOS_VE.DESCRIPCION LIKE '%".utf8_decode($_POST['descripcion'])."%' AND DOCTOS_VE.TIPO_DOCTO='".$_POST['tipo']."' AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT CAJACERRADA.DOCTO_VE_ID FROM CAJACERRADA)";
		
		$order = array("DOCTOS_VE.FOLIO DESC");
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0);

		$conection3 = new conexion_nexos(1);
		$lista = array();
		foreach ($json as $key => $value) {
			$importe = ($json[$key]["DOCTOS_VE.IMPORTE_NETO"] * 1.16);

			$json[$key]["DOCTOS_VE.FOLIO"] =  (int) $json[$key]["DOCTOS_VE.FOLIO"];
			$json[$key]["DOCTOS_VE.IMPORTE_NETO"] = number_format($importe, 2);  

			$query = "select SUM(IMPORTE) as ANTICIPO from CAJA WHERE DOCTO_VE_ID='".$value['DOCTOS_VE.DOCTO_VE_ID']."' AND EMPRESA=".$_POST['empresa']." AND BORRADO IS NULL";
			
			$result = ibase_query($conection3->getConexion(), $query) or die(ibase_errmsg());
			
			$row = ibase_fetch_object ($result, IBASE_TEXT);
			if($row->ANTICIPO == "")
				$json[$key]['ANTICIPO'] = "0.00";
			else
				$json[$key]['ANTICIPO'] = $row->ANTICIPO;
		
			$lista[] = $json[$key];
			/*if((round($importe,2) - $row->ANTICIPO) == 0)
			{
				$campos = array("EMPRESA", "DOCTO_VE_ID");

				$valores = array($_POST['empresa'], $value["DOCTOS_VE.DOCTO_VE_ID"]);

				$json = $conection2->insert_table($campos, "CAJACERRADA", $valores);
				
			}else{
				//echo (round($importe,2) - $row->ANTICIPO)."<br>";
				$lista[] = $json[$key];
			}*/	
		}
		
		$obj = (object) $lista;
		echo json_encode($obj);
		
	}

	if($_POST["accion"] == "consultaCaja")
	{
		$campos = array("DOCTOS_VE.DOCTO_VE_ID",
						"DOCTOS_VE.FOLIO",
						"CLIENTES.NOMBRE",
						"DOCTOS_VE.DESCRIPCION",
						"DOCTOS_VE.IMPORTE_NETO");
		
		$join = array("CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID");


		
		$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID=".$_POST['docto_ve'];
		
		$order = array("DOCTOS_VE.FOLIO DESC");
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0);

		$conection3 = new conexion_nexos(1);
		foreach ($json as $key => $value) {
			  

			$query = "select SUM(IMPORTE) as ANTICIPO from CAJA WHERE DOCTO_VE_ID='".$value['DOCTOS_VE.DOCTO_VE_ID']."' AND EMPRESA=".$_POST['empresa']." AND BORRADO IS NULL";
			
			$result = ibase_query($conection3->getConexion(), $query) or die(ibase_errmsg());
			
			$row = ibase_fetch_object ($result, IBASE_TEXT);
			if($row->ANTICIPO == "")
				$json[$key]['ANTICIPO'] = 0.00;
			else
				$json[$key]['ANTICIPO'] = number_format($row->ANTICIPO,2);

			$importe_iva = ($json[$key]["DOCTOS_VE.IMPORTE_NETO"] * 1.16);

			$json[$key]["RESTO"] = $importe_iva - $json[$key]['ANTICIPO'];
			$json[$key]["RESTO"] = number_format($json[$key]["RESTO"], 2);
			$json[$key]["DOCTOS_VE.FOLIO"] =  (int) $json[$key]["DOCTOS_VE.FOLIO"];
			$json[$key]["DOCTOS_VE.IMPORTE_NETO"] = number_format($importe_iva, 2);
		}
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "Cajanocerrada")
	{
		$conection1 = new conexion_nexos(1);
		
		$campos = array("DOCTOS_VE.FOLIO",
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.IMPORTE_NETO",
						"CLIENTES.NOMBRE",
						"DOCTOS_VE.DESCRIPCION");

		$campos2 = array("CAJA.DOCTO_VE_ID");
		
		$join = array("DOCTOS_VE","=", "CAJA.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID",
					  "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID");
		$join2 = array();

		$condicionales = " AND CAJA.EMPRESA=1 and CAJA.DOCTO_VE_ID>0 AND CAJA.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM CAJACERRADA)";
		$condicionales2 = " AND CAJA.EMPRESA=2 and CAJA.DOCTO_VE_ID>0 AND CAJA.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM CAJACERRADA)";

		$order = array();
		
		$json1 = $conection1->select_table($campos, "CAJA", $join, $condicionales, $order, 1);
		$json2 = $conection1->select_table($campos2, "CAJA", $join2, $condicionales2, $order, 1);

		$conection2 = new conexion_nexos($_SESSION['empresa']);

		foreach ($json2 as $key => $value) {
			
		$campos = array("DOCTOS_VE.FOLIO",
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.IMPORTE_NETO",
						"CLIENTES.NOMBRE",
						"DOCTOS_VE.DESCRIPCION");
	
		$join2 = array("DOCTOS_VE","=", "CAJA.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID",
					  "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID");

		$condicionales2 = " AND CAJA.EMPRESA=2 and CAJA.DOCTO_VE_ID>0 AND CAJA.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM CAJACERRADA)";

		$order = array();
		
		$json2 = $conection2->select_table($campos2, "CAJA", $join2, $condicionales2, $order, 1);
		}
		//$json3 = array_merge($json1, $json2);

		$obj = (object) $json2;
		echo json_encode($obj);

	}
?>	