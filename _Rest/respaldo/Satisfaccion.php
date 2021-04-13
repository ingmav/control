<?php
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos($_POST['empresa']);
	
	if($_POST["accion"] == "index")
	{

		//$arreglo = DataGrid(1);
		$arreglo2 = DataGrid(2);
		//$json3 = array_merge($arreglo, $arreglo2);
		
		$json3 = $arreglo2;
		$contador = count($json3);		

		for($i = 0; $i < $contador; $i++)
		{
			$j = ($i + 1);
			for(; $j < $contador; $j++)
			{
				
				if($json3[$i]['MAX'] < $json3[$j]['MAX'])
				{
					$arrayAuxiliar[0] = $json3[$i];
					$json3[$i] = $json3[$j];	
					$json3[$j] = $arrayAuxiliar[0];
				}else if($json3[$i]['MAX'] == $json3[$j]['MAX'])
				{
					if($json3[$i]['MAX'] > $json3[$j]['max(values)'])
					{
						$arrayAuxiliar[0] = $json3[$i];
						$json3[$i] = $json3[$j];	
						$json3[$j] = $arrayAuxiliar[0];
					}
				}	
			}
		}

		/*$arrayEnviar = Array();
		$page = ($_POST['page'] -1);
		for($i = (0 + ($page * 20)); $i < (($page * 20) + 20); $i++)
		{
			if(!empty($json3[$i]))
			$arrayEnviar[] = $json3[$i];
		}
		

		$obj = (object) $arrayEnviar;
		echo json_encode($obj);*/
		$obj = (object) $json3;
		echo json_encode($obj);
	}
	
	function DataGrid($Empresa)
	{
		$campos = array("DOCTOS_VE.DOCTO_VE_ID",
						"DOCTOS_VE.FOLIO", 
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.FECHA",
						"CLIENTES.CLIENTE_ID", 
						"CLIENTES.NOMBRE", 
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.CLAVE_CLIENTE",
						"DOCTOS_VE.DESCRIPCION",
						"DOCTOS_VE.IMPORTE_NETO",
						"DOCTOS_VE.TOTAL_IMPUESTOS",
						"DOCTOS_VE.DSCTO_IMPORTE",
						"DOCTOS_VE_LIGAS.DOCTO_VE_DEST_ID",
						"TABLEROPRODUCCION.FECHA_TERMINO");

		$campo = "TABLEROPRODUCCION.DOCTO_VE_ID";
		$join = array();
		
		$condicionales = " AND TABLEROPRODUCCION.FINALIZAR_PROCESO=1 and TABLEROPRODUCCION.fecha>='2016.11.01'";

		$conection2 = new conexion_nexos($Empresa);

		$json_distinct = $conection2->select_distinct_table($campo, "TABLEROPRODUCCION", $join, $condicionales, 0);
		
		$join2 = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION",
					  "DOCTOS_VE_LIGAS","=", "DOCTOS_VE_LIGAS.DOCTO_VE_FTE_ID", "DOCTOS_VE.DOCTO_VE_ID", "LEFT",
					  "TABLEROPRODUCCION","=", "TABLEROPRODUCCION.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID", "LEFT");
		
		if($_POST['pagados'] == 1)
			$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS)";
		else
			$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS)";	
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}

		$order = array("DOCTOS_VE.FECHA DESC, DOCTOS_VE.FOLIO DESC");
		
		$condicionales .=" AND  DOCTOS_VE.FECHA>='01.06.2016' ";
	
		//$json = $conection2->select_table($campos, "DOCTOS_VE", $join2, $condicionales, $order, 0, null);
		$json = $conection2->select_table_advanced_with_counter($campos, $campos, "DOCTOS_VE", $join2, $condicionales, $order, 0, NULL, $Empresa);
		$index = 0;
		/*while($index < count($json))
		{
			$importe = $json[$index]['DOCTOS_VE.IMPORTE_NETO'] + $json[$index]['DOCTOS_VE.TOTAL_IMPUESTOS'] + $json[$index]['DOCTOS_VE.DSCTO_IMPORTE'];
			$json[$index]['IMPORTE'] = number_format($importe, 2);
			
			
			$index++;
		}*/

		$index = 0;
		/*while($index < count($json))
		{
			if($Empresa == 1)
				$json[$index]['NOMBREEMPRESA'] = "NX";
			else
				$json[$index]['NOMBREEMPRESA'] = "NP";
			$json[$index]['EMPRESA'] = $Empresa;
			$joinext = array("TABLEROPRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");
			$condicionalesext = " AND TABLEROPRODUCCION.DOCTO_VE_ID=".$json[$index]['DOCTOS_VE.DOCTO_VE_ID'];
			$jsonext = $conection2->select_max_table("PRODUCCION.FECHA", "PRODUCCION", $joinext, $condicionalesext);
			$json[$index]['MAX'] = substr($jsonext, 0, 10);
			$index++;
		}*/

		return $json;
	}

	if($_POST["accion"] == "counter")
	{
	
		$campo = "TABLEROPRODUCCION.DOCTO_VE_ID";
		
		$join = array("DOCUMENTOSFINALIZADOS","=", "DOCUMENTOSFINALIZADOS.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");
		
		$condicionales = " AND DOCUMENTOSFINALIZADOS.idtipofinalizacion=2 and TABLEROPRODUCCION.fecha>='2016.11.01'";


		$json_distinct = $conection->select_distinct_table($campo, "TABLEROPRODUCCION", $join, $condicionales, 0);
		
		$join2 = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID");
		
		if($_POST['pagados'] == 1)
			$condicionales2 = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS) ";
		else
			$condicionales2 = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS)";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales2.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales2.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}
		
		$condicionales2 .="  and DOCTOS_VE.FECHA>='01.06.2016' ";
		$conection2 = new conexion_nexos(1);
		$json2 = $conection2->counter("DOCTOS_VE", $join2, $condicionales2, 0);

		$conection3 = new conexion_nexos($_SESSION['empresa']);
		$json3 = $conection3->counter("DOCTOS_VE", $join2, $condicionales2, 0);
		
		$counter_final['PAGINADOR'] = $json2->PAGINADOR + $json3->PAGINADOR;
		$obj = (object) $counter_final;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "countProcess")
	{
		$join = array();
		
		$condicionales = " AND TABLEROPRODUCCION.DOCTO_VE_ID=".$_POST['id'];
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROPRODUCCION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "email")
	{
		
		$campos = array("DOCTOS_VE.CLAVE_CLIENTE");
		$join = array();

		$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID=".$_POST['docto_ve_id'];
		$order = array();
		
		$json = $conection->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0, NULL);
		
		$conection2 = new conexion_nexos(1);
		$campos2 = array("CORREO");
		$join2 = array();

		$condicionales2 = " AND CLAVE_CLIENTE='".$json[0]['DOCTOS_VE.CLAVE_CLIENTE']."'";
		$order2 = array();
		
		$json2 = $conection2->select_table($campos2, "CORREOENCUESTA", array(), $condicionales2, $order2, $softdelete, NULL);		

		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "savePay")
	{
		$conection2 = new conexion_nexos(1);
		$count = $conection2->counter("CORREOENCUESTA", array(), "AND CLAVE_CLIENTE='".$_POST['CLAVE_CLIENTE']."'", 0);
		$count->PAGINADOR; 

		if($count->PAGINADOR==0)
		{
			$conection2->insert_table(array("CORREO","CLAVE_CLIENTE"), "CORREOENCUESTA",array("'".$_POST['correo']."'","'".$_POST['CLAVE_CLIENTE']."'" ));
		}else
		{
			$conection2->update_table(array("CORREO"), "CORREOENCUESTA",array("'".$_POST['correo']."'" ), " CLAVE_CLIENTE='".$_POST['CLAVE_CLIENTE']."'");
		}
		$campos = array("DOCTO_VE_ID", "FECHA");
		$valores = array($_POST['id'], "'".date("Y-m-d H:i:s")."'");
		
		$conection = new conexion_nexos($_POST['empresa']);
		$count2 = $conection->counter("DOCUMENTOSPAGADOS", array(), "AND DOCTO_VE_ID='".$_POST['id']."'", 0);

		if($count2->PAGINADOR == 0)
			$json = $conection->insert_table($campos, "DOCUMENTOSPAGADOS", $valores);
		else
			$json = array("Respuesta"=> 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
?>