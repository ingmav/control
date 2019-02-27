<?php
	include("../clases/conexion.php");
	session_start();
	date_default_timezone_set('America/Mexico_City');

	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos();

	if($_POST["accion"] == "index")
	{
		$campos = array("GARANTIA.ID", "GARANTIA.FOLIO", "GARANTIA.FECHA", "GARANTIA.MATERIAL", "GARANTIA.CANTIDAD","GARANTIA.UNIDADMEDIDA", "GARANTIA.CLIENTE", "OPERADOR.ALIAS");

		$join = array("OPERADOR", "=", "OPERADOR.ID", "GARANTIA.IDOPERADOR");

        $consulta = "";
        if($_POST['clientefiltro']!="" || $_POST['fecha_inicio']!="" || $_POST['fecha_fin']!='')
            $consulta .=" and GARANTIA.FECHA BETWEEN '".$_POST['fecha_inicio']." 00:00' and '".$_POST['fecha_fin']." 23:59' and (GARANTIA.CLIENTE LIKE '%".$_POST['clientefiltro']."%' or  GARANTIA.FOLIO LIKE '%".$_POST['clientefiltro']."%')";

        $condicionales = "  ".$consulta;

		$order = array("GARANTIA.ID DESC");

		$json = $conection->select_table($campos, "GARANTIA", $join, $condicionales, $order, 1, $_POST['page']);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "save")
	{

		$campos = array("FOLIO", "MATERIAL", "CANTIDAD","UNIDADMEDIDA", "CLIENTE", "IDOPERADOR", "MOTIVO", "MONTO");
		$valores = array("'".$_POST['foliogarantia']."'", "'".utf8_decode($_POST['material'])."'", "'".$_POST['cantidad']."'", "'".$_POST['medida']."'", "'".$_POST['cliente']."'", $_SESSION['IDUSUARIO'], "'".utf8_decode($_POST['motivo'])."'", "'".$_POST['monto']."'");

		$json = $conection->insert_table($campos, "GARANTIA", $valores);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "update")
	{

        $campos = array("FOLIO", "MATERIAL", "CANTIDAD","UNIDADMEDIDA", "CLIENTE", "MOTIVO", "MONTO");
        $valores = array("'".$_POST['foliogarantia']."'", "'".utf8_decode($_POST['material'])."'", "'".$_POST['cantidad']."'", "'".$_POST['medida']."'", "'".$_POST['cliente']."'", "'".utf8_decode($_POST['motivo'])."'", "'".$_POST['monto']."'");
		$id = "ID = ".$_POST['id'];

		$json = $conection->update_table($campos, "GARANTIA", $valores, $id);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "eliminar")
	{
		if($_SESSION['IDUSUARIO'] == 21)
		{
			if(count($_POST['id']) > 0)
			{
				$json = $conection->delete_table("GARANTIA", "ID IN", $_POST['id']);
				//print_r($json);
				$obj = (object) $json;
				echo json_encode($obj);
			}
		}else
		{
			$obj = (object) array("error"=> "NO TIENE PERMISO DE REALIZAR ESTA ACCION");
			echo json_encode($obj);
		}
	}

	if($_POST["accion"] == "modificar")
	{
        $campos = array("ID","FOLIO", "MATERIAL", "CANTIDAD","UNIDADMEDIDA", "CLIENTE", "MOTIVO", "MONTO");

		$join = array();
		$condicionales = " AND ID=".$_POST['id'][0];
		$order = array("FECHA");

		$json = $conection->select_table($campos, "GARANTIA", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "counter")
	{

		$join = array();

        $consulta = "";
        if($_POST['clientefiltro']!="" || $_POST['fecha_inicio']!="" || $_POST['fecha_fin']!='')
            $consulta .=" and GARANTIA.FECHA BETWEEN '".$_POST['fecha_inicio']." 00:00' and '".$_POST['fecha_fin']." 23:59' and (GARANTIA.CLIENTE LIKE '%".$_POST['clientefiltro']."%' or  GARANTIA.FOLIO LIKE '%".$_POST['clientefiltro']."%')";


        $condicionales = "  ".$consulta;

		$json = $conection->counter("GARANTIA", $join, $condicionales, 1);

		$obj = (object) $json;
		echo json_encode($obj);
	}

?>
