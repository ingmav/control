<?php
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos();
	
	if($_POST["accion"] == "index")
	{

        $consulta = "";
        if($_POST['clientefiltro']!="")
            $consulta .=" and NOMBRECLIENTE LIKE '%".$_POST['clientefiltro']."%'";

        if($_POST['estatusfiltro']!="")
            $consulta .=" and ESTATUS='".$_POST['estatusfiltro']."'";


		$campos = array("LEVANTAMIENTO.ID", "LEVANTAMIENTO.FECHALEVANTAMIENTO", "LEVANTAMIENTO.NOMBRECLIENTE", "LEVANTAMIENTO.DESCRIPCION", "LEVANTAMIENTO.EMPLEADO","LEVANTAMIENTOESTATUS.LEVANTAMIENTODESCRIPCION");
		
		$join = array("LEVANTAMIENTOESTATUS","=", "LEVANTAMIENTOESTATUS.ID", "LEVANTAMIENTO.ESTATUS");
		
		$condicionales = " ".$consulta;
		
		$order = array("LEVANTAMIENTO.FECHALEVANTAMIENTO");
		
		$json = $conection->select_table($campos, "LEVANTAMIENTO", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "save")
	{
		
		$campos = array("NOMBRECLIENTE", "DESCRIPCION", "FECHALEVANTAMIENTO", "EMPLEADO", "ESTATUS");
		$valores = array("'".strtoupper(utf8_decode($_POST['cliente']))."'", "'".strtoupper(utf8_decode($_POST['descripcion']))."'", "'".$_POST['fechalevantamiento']."'", "'".strtoupper(utf8_decode($_POST['empleado']))."'", $_POST['estatus']);
		
		$json = $conection->insert_table($campos, "LEVANTAMIENTO", $valores);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "update")
	{
		
		$campos = array("NOMBRECLIENTE", "DESCRIPCION", "FECHALEVANTAMIENTO", "EMPLEADO", "ESTATUS");
		$valores = array("'".strtoupper(utf8_decode($_POST['cliente']))."'", "'".strtoupper(utf8_decode($_POST['descripcion']))."'", "'".$_POST['fechalevantamiento']."'", "'".strtoupper(utf8_decode($_POST['empleado']))."'", $_POST['estatus']);
		$id = "ID = ".$_POST['id'];
		
		$json = $conection->update_table($campos, "LEVANTAMIENTO", $valores, $id);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "eliminar")
	{
		if(count($_POST['id']) > 0)
		{
			$json = $conection->delete_table("LEVANTAMIENTO", "ID IN", $_POST['id']);
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}
	}
	
	if($_POST["accion"] == "modificar")
	{
		$campos = array("ID", "NOMBRECLIENTE", "DESCRIPCION", "FECHALEVANTAMIENTO", "EMPLEADO", "ESTATUS");
		
		$join = array();
		$condicionales = " AND ID=".$_POST['id'][0];
		$order = array("FECHALEVANTAMIENTO");
		
		$json = $conection->select_table($campos, "LEVANTAMIENTO", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST['accion'] == "cargaEmpleado")
	{
		$campos = array("OPERADORDEPARTAMENTO.ID", "OPERADOR.ALIAS");
		
		$join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");

		$order = array("OPERADOR.ALIAS");
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=1 ";
		
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST['accion'] == "cargaEstatus")
	{
		$campos = array("LEVANTAMIENTOESTATUS.ID", "LEVANTAMIENTOESTATUS.LEVANTAMIENTODESCRIPCION");
		
		$join = array();

		$order = array("LEVANTAMIENTOESTATUS.ID");
				
		$json = $conection->select_table($campos, "LEVANTAMIENTOESTATUS", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	
?>