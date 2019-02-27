<?php
	session_start();
	include("../clases/conexion.php");
	session_start();
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos();
	
	if($_POST["accion"] == "index")
	{
		$fecha = "";
		

		if($_POST['fechaBusqueda']!="")
			$fecha  = $_POST['fechaBusqueda'];
		//else
			//$fecha = date("Y-m-d");
		

		$campos = array("ACTIVIDADEXTRA.ID","ACTIVIDADEXTRA.RESPONSABLE", "ACTIVIDADEXTRA.COLABORADORES", "ACTIVIDADEXTRA.ACTIVIDAD", "ACTIVIDADEXTRA.FECHA", "ACTIVIDADEXTRA.DE", "ACTIVIDADEXTRA.A", "OPERADOR.NOMBRE");
		
		$join = array("OPERADOR", "=", "OPERADOR.ID", "ACTIVIDADEXTRA.REVISO");
		
		$order = array("ACTIVIDADEXTRA.FECHA DESC", "ACTIVIDADEXTRA.DE");

		$condicionales = " AND ACTIVIDADEXTRA.FECHA like '$fecha%' ";
		//$condicionales = " ";
		
		$json = $conection->select_table($campos, "ACTIVIDADEXTRA", $join, $condicionales, $order, 1, 0);
		//print_r($json);

		foreach ($json as $key => $value) {
			//echo $value['ACTIVIDADEXTRA.ID'];
			$counter = $conection->counter("ACTIVIDADEXTRANOTAS", array(), " and ACTIVIDADEXTRANOTAS.IDACTIVIDADEXTRA=".$value['ACTIVIDADEXTRA.ID'], 0);
			$json[$key]['OBSERVACIONES'] = $counter->PAGINADOR;
		}
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "save")
	{
		
		$campos = array("RESPONSABLE", "COLABORADORES", "ACTIVIDAD", "FECHA", "DE", "A", "REVISO");
		$valores = array("'".utf8_decode($_POST['responsable'])."'",
						 "'".utf8_decode($_POST['colaboradores'])."'",
						 "'".utf8_decode($_POST['actividad'])."'",
						 "'".$_POST['fechaActividad']."'",
						 "'".$_POST['desdeActividad']."'",
						 "'".$_POST['hastaActividad']."'",
						 $_POST['reviso']);
		
		$json = $conection->insert_table($campos, "ACTIVIDADEXTRA", $valores);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "update")
	{
		
		$campos = array("RESPONSABLE", "COLABORADORES", "ACTIVIDAD", "FECHA", "DE", "A", "REVISO");
		$valores = array("'".utf8_decode($_POST['responsable'])."'",
						 "'".utf8_decode($_POST['colaboradores'])."'",
						 "'".utf8_decode($_POST['actividad'])."'",
						 "'".$_POST['fechaActividad']."'",
						 "'".$_POST['desdeActividad']."'",
						 "'".$_POST['hastaActividad']."'",
						 $_POST['reviso']);

		$id = "ID = ".$_POST['id'];
		
		$json = $conection->update_table($campos, "ACTIVIDADEXTRA", $valores, $id);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "eliminar")
	{
		if(count($_POST['id']) > 0)
		{
			$campos = array("BORRADO");
			$valores = array("'".date("d.m.Y H.i.s")."'");
			$json = $conection->update_table($campos, "ACTIVIDADEXTRA", $valores, " ID =".$_POST['id'][0]);
		
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}
	}
	
	if($_POST["accion"] == "modificar")
	{
		$campos = array("ID", "RESPONSABLE", "COLABORADORES", "ACTIVIDAD", "FECHA", "DE", "A", "REVISO");
		
		$join = array();
		$condicionales = " AND ID=".$_POST['id'][0];
		$order = array("FECHA");
		
		$json = $conection->select_table($campos, "ACTIVIDADEXTRA", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	
	if($_POST['accion'] == "cargaOperador")
	{
		$campos = array("OPERADOR.ID", "OPERADOR.NOMBRE");
		
		$join = array();

		$order = array("OPERADOR.NOMBRE");
				
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "counter")
	{
	
		$join = array();
		
		/*if($_POST['realizados'] == 0)
			$condicionales = " AND COTIZACIONES.ESTATUS = 1 ";
		if($_POST['realizados'] == 1)
			$condicionales = " AND COTIZACIONES.ESTATUS = 2 ";
		*/
		$json = $conection->counter("ACTIVIDADEXTRA", $join, $condicionales, 1);
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "observaciones")
	{
		$campos = array("ACTIVIDADEXTRANOTAS.NOTA", "ACTIVIDADEXTRANOTAS.FECHA", "OPERADOR.ALIAS");
		
		$join = array("ACTIVIDADEXTRA","=", "ACTIVIDADEXTRA.ID", "ACTIVIDADEXTRANOTAS.IDACTIVIDADEXTRA",
					  "OPERADOR", "=", "OPERADOR.ID", "ACTIVIDADEXTRANOTAS.IDOPERADOR");
		
		$condicionales = " AND ACTIVIDADEXTRANOTAS.IDACTIVIDADEXTRA=".$_POST['id'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "ACTIVIDADEXTRANOTAS", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "saveObservacion")
	{

		if(strlen(trim($_POST['observaciones'])) > 0)
		{
			$campos = array("IDACTIVIDADEXTRA", "NOTA", "IDOPERADOR");
			$valores = array($_POST['idactividad'], "'".utf8_decode($_POST['observaciones'])."'", $_SESSION['IDUSUARIO']);
			
			$json = $conection->insert_table($campos, "ACTIVIDADEXTRANOTAS", $valores);
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$json = array("observaciones"=>"no agregado");
			$obj = (object) $json;
			echo json_encode($obj);	
		}
	}
	
?>