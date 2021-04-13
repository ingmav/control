<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');

if($_POST['accion'] == "insumos")
	{

		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$campos = array("MS_FAMILIA.DESCRIPCION", 
						"MS_ARTICULOS.NOMBRE_ARTICULO", 
						"MS_ARTICULOS.CANTIDAD_MINIMA", 
						"MS_ARTICULOS.UNITARIO", 
						"MS_ARTICULOS.ID"
						);
				
		$condicionales = " AND MS_ARTICULOS.ESTATUS=0";
		if($_POST['familia'] != 0)
			$condicionales .= " and MS_FAMILIA_ID=".$_POST['familia'];

		$join = array("MS_FAMILIA", "=", "MS_FAMILIA.ID" , "MS_ARTICULOS.MS_FAMILIA_ID", "UNION");
		$json = $conection2->select_table_advanced($campos, "MS_ARTICULOS", $join, $condicionales, array("MS_ARTICULOS.MS_FAMILIA_ID"), 0);

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST['accion'] == "eliminar_insumo")
	{

		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$campos = array("ESTATUS");
		$valores = array(1);
		$id = " ID = ".$_POST['id'];
		
		$json = $conection2->update_table($campos, "MS_ARTICULOS", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;

	}

	if($_POST['accion'] == "carga_insumo")
	{

		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$campos = array("NOMBRE_ARTICULO", 
						"MS_FAMILIA_ID", 
						"CANTIDAD_MINIMA", 
						"UNITARIO", 
						"ID");
				
		$condicionales = " and id=".$_POST['id'];
		$json = $conection2->select_table($campos, "MS_ARTICULOS", array(), $condicionales, array(), 0);

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}
?>