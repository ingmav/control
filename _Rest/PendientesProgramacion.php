<?php
	include("../clases/conexion.php");
	include("../clases/utilerias.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	session_start();
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$candado = "";
		/*if($_SESSION['IMPRESION'] == 1)
			$candado = " AND OPERADORDEPARTAMENTO.ID IN (SELECT ID FROM OPERADORDEPARTAMENTO WHERE IDOPERADOR = ".$_SESSION['IDUSUARIO'].")";
		else if($_SESSION['IMPRESION'] == 2)
			$candado = " AND (OPERADORDEPARTAMENTO.ID IN (SELECT ID FROM OPERADORDEPARTAMENTO WHERE IDOPERADOR = ".$_SESSION['IDUSUARIO'].") OR OPERADORDEPARTAMENTO.ID IS NULL)";
		*/
		$campos = array("TABLEROPRODUCCION.ID", 
						"DOCTOS_VE.FOLIO", 
						"TABLEROPRODUCCION.FECHA", 
						"CLIENTES.NOMBRE", 
						"DOCTOS_VE.DESCRIPCION", 
						"TABLEROPRODUCCION.NOTA", 
						"DOCTOS_VE_DET.UNIDADES", 
						"ARTICULOS.UNIDAD_VENTA", 
						"OPERADOR.ALIAS", 
						"TABLEROPRODUCCION.PRIORIDAD",
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.ESTATUS",
						 "ORDENDIA.FECHAORDEN");
		
		$join = array("DOCTOS_VE_DET","=", "DOCTOS_VE_DET.DOCTO_VE_DET_ID", "TABLEROPRODUCCION.DOCTO_VE_DET_ID",
					  "DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID",	
					  "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID",
					  "ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID",
					  "PRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID",
					  "OPERADORDEPARTAMENTO","=", "OPERADORDEPARTAMENTO.ID", "PRODUCCION.IDOPERADORDEPARTAMENTO",
					  "OPERADOR","=", "OPERADORDEPARTAMENTO.IDOPERADOR", "OPERADOR.ID",
					  "ORDENDIA","=", "ORDENDIA.IDPRODUCCION", "PRODUCCION.ID");
		
		$condicionales = " AND TABLEROPRODUCCION.PROGRAMACION=1 
						   AND PRODUCCION.IDDEPARTAMENTO=7 AND (TABLEROPRODUCCION.DISENO=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=2) = 2) AND DOCTOS_VE.ESTATUS!='C' ";
		
		if($_POST['realizados'] == 2)
		{
			$condicionales .= " AND PRODUCCION.FECHA like '".$_POST['fecha']."%' AND PRODUCCION.IDESTATUS=2 ";
		}
		if(!isset($_POST['realizados']) || $_POST['realizados'] == 1)
		{
			$condicionales .= " AND PRODUCCION.IDESTATUS !=2 ";
		}

		if($_POST['activas'] == 2)
		{
			$condicionales .= " AND ORDENDIA.FECHAORDEN IS NOT NULL";
		}
		
		$order = array();
		
		$conection = new conexion_nexos(1);
		$json = $conection->select_table($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);
		$index = 0;
		while($index < count($json))
		{
			$json[$index]['EMPRESA'] = 1;
			$json[$index]['NOMBREEMPRESA'] = "NX";
			$index++;
		}

		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$json2 = $conection2->select_table($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);
		
		$index = 0;
		while($index < count($json2))
		{
			$json2[$index]['EMPRESA'] = 2;
			$json2[$index]['NOMBREEMPRESA'] = "NP";
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
				if($json3[$i]['TABLEROPRODUCCION.FECHA'] > $json3[$j]['TABLEROPRODUCCION.FECHA'])
				{
					
					$arrayAuxiliar[0] = $json3[$i];
					$json3[$i] = $json3[$j];	
					$json3[$j] = $arrayAuxiliar[0];
				}else if($json3[$i]['TABLEROPRODUCCION.FECHA'] == $json3[$j]['TABLEROPRODUCCION.FECHA'])
				{
					if($json3[$i]['TABLEROPRODUCCION.PRIORIDAD'] < $json3[$j]['TABLEROPRODUCCION.PRIORIDAD'])
					{
						$arrayAuxiliar[0] = $json3[$i];
						$json3[$i] = $json3[$j];	
						$json3[$j] = $arrayAuxiliar[0];
					}
				}	
			}
		}
		
		//print_r($json3);
		$obj = (object) $json3;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "observaciones")
	{
		$campos = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
		$join = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
		
		$condicionales = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=7 AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "TABLEROOBSERVACION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "saveObservacion")
	{
		if(strlen(trim($_POST['observacion'])) > 0)
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "OBSERVACION");
			$valores = array($_POST['idtablero'], 7, "'".utf8_decode($_POST['observacion'])."'");
			
			$json = $conection->insert_table($campos, "TABLEROOBSERVACION", $valores);
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

	if($_POST["accion"] == "operadores")
	{
		$campos = array("OPERADORDEPARTAMENTO.ID", "OPERADOR.ALIAS");
		
		$join = array("OPERADOR","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=7 ";
		
		$order = array();
		
		$json = $conection->select_table($campos, "OPERADORDEPARTAMENTO", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "saveTurnar")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=7 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array( "IDOPERADORDEPARTAMENTO", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID']." AND PRODUCCION.IDDEPARTAMENTO=7 ";
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 7, $_POST['EmpleadoFinalizar'], 1, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "save")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=7 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array( "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID'];
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 7, $_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "cancelar")
	{
		
		$campos = array("PRODUCCION.IDESTATUS", "PRODUCCION.DESCRIPCIONCANCELACION");
		
		$valores = array(3, "'".$_POST['notacancelacion']."'");
		
		$order = array();
		
		$id = "IDTABLEROPRODUCCION = ".$_POST['idtablerocancelar']." AND IDDEPARTAMENTO = 2";
		$arreglo = $conection->update_table($campos, "PRODUCCION", $valores, $id);
		
		$obj = (object) $json;
		echo json_encode($obj);
		
		
	}
	
	if($_POST["accion"] == "countMessaje")
	{
		$join = array();
		
		$condicionales = " AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id']." AND TABLEROOBSERVACION.IDDEPARTAMENTO=2";
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROOBSERVACION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}
		 
	if($_POST["accion"] == "vercancelacion")
	{
		$campos = array("PRODUCCION.DESCRIPCIONCANCELACION");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['id']." AND PRODUCCION.IDDEPARTAMENTO=7";
		
		$order = array();
		
		$json = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}	 

	if($_POST['accion'] == "saveActividadProceso")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		
		
		$campos2 = array("IDPRODUCCION", "FECHAORDEN");
		$valores2 = array($json[0]['PRODUCCION.ID'], "'".date("Y-m-d H:i:s")."'");

		$json2 = $conection2->insert_table($campos2, "ORDENDIA", $valores2);
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "deleteActividadProceso")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		
		
		$campos2 = array("IDPRODUCCION", "FECHAORDEN");

		$id = " ORDENDIA.IDPRODUCCION = ".$json[0]['PRODUCCION.ID'];
		$json2 = $conection2->delete_of_table("ORDENDIA", $id, Array());
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}
?>