<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{
		$consulta1 = "";
		/*if($_POST['filtro_almacen'])
		{
			$consulta1 .= " AND MI.MS_ALMACEN_ID = '".$_POST['filtro_almacen']."' ";
		}*/
		
		
		$conection2 = new conexion_nexos($_SESSION['empresa']);
		
		/*$query = "select
MA.ID,
MA.NOMBRE_ARTICULO,
(SELECT IIF(SUM(MI.CANTIDAD_RESTANTE)>0,SUM(MI.CANTIDAD_RESTANTE),0)  FROM MS_INVENTARIO MI WHERE MI.MS_ARTICULO_ID=MA.ID AND MI.ESTATUS_INVENTARIO=0) AS INVENTARIO
 from MS_ARTICULOS MA";*/
 		$query = "select 
 			MA.ID,
 			MA.NOMBRE_ARTICULO,
 			SUM(MI.CANTIDAD_RESTANTE) AS CANTIDAD_RESTANTE
 			from MS_INVENTARIO MI,
 			MS_ARTICULOS MA WHERE 
 			MI.MS_ARTICULO_ID = MA.ID
 			AND ESTATUS_INVENTARIO=0 AND CANTIDAD_RESTANTE>0
 			GROUP BY MI.MS_ARTICULO_ID, MA.NOMBRE_ARTICULO, MA.ID";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$row->ID]['ARTICULO'] 				= utf8_encode($row->NOMBRE_ARTICULO);
			$json[$row->ID]['MS_INVENTARIO']			= $row->CANTIDAD_RESTANTE;

		$query_inventario_calculado = "select sum(unidades) as unidades from
(SELECT sum(dpd.unidades) as unidades FROM doctos_pv dp, doctos_pv_det dpd, ms_relacion mr, ms_articulos ma
where dp.docto_pv_id=dpd.docto_pv_id
and dp.fecha_hora_creacion>=ma.actualizacion
and dpd.articulo_id=mr.articulo_id
and mr.ms_articulo_id=ma.id
and  MR.ms_articulo_id=".$row->ID."
and MR.ms_tipo_baja_id=1
and ma.estatus=0
and dp.tipo_docto in('V')
and dp.estatus!='C'
union all
SELECT sum(dvd.unidades) as unidades FROM doctos_ve dv, doctos_ve_det dvd, ms_relacion mr, ms_articulos ma
where dv.docto_ve_id=dvd.docto_ve_id
and dv.fecha_hora_creacion>=ma.actualizacion
and dvd.articulo_id=mr.articulo_id
and mr.ms_articulo_id=ma.id
and  MR.ms_articulo_id=".$row->ID."
and MR.ms_tipo_baja_id=1
and ma.estatus=0
and dv.tipo_docto in('F', 'R')
and dv.estatus!='C' ) x";
        
        $result_calculado = ibase_query($conection2->getConexion(), $query_inventario_calculado) or die(ibase_errmsg());
        
			while ($row_calculado = ibase_fetch_object ($result_calculado, IBASE_TEXT)){
				$json[$row->ID]['MS_INVENTARIO'] -= $row_calculado->UNIDADES;
			}
			
		}

		
		$count = 0;
		
		$j = 1;
		
		$conection1 = null;
		$conection2 = null;

		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "formularios")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);
		
		$query = "select
		ID,
		DESCRIPCION
		FROM
		MS_COMBOS
		where ESTATUS=1
		".$consulta1;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 					= $row->ID;
			$json[$indice]['DESCRIPCION'] 			= utf8_encode($row->DESCRIPCION);
		}


		$query = "select
		ID,
		NOMBRE
		FROM
		MS_ALMACEN
		where ACTIVO=0
		".$consulta1;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json2 = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json2);
			$json2[$indice]['ID'] 				= $row->ID;
			$json2[$indice]['NOMBRE'] 			= utf8_encode($row->NOMBRE);
		}
		
		$conection1 = null;
		$conection2 = null;

		$obj = (object) array("ARTICULOS" => $json, "ALMACENES"=>$json2);
		echo json_encode($obj);
	}

	if($_POST["accion"] == "baja")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$campos = array("ESTATUS_INVENTARIO");
		$valores = array(1);
		$id = " ID_INVENTARIO in (".implode(",",$_POST['bajas']).")";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}
	

	if($_POST["accion"] == "guardar")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);

		$cantidad = $_POST['cantidad'];
		$dimension = $_POST['dimension'];


		if($dimension > 0)
		{
			for($i = 0; $i < $_POST['cantidad']; $i++)
			{
				$campo1 = "FOLIO";
				$condicionales = " AND MS_COMBO_ID=".$_POST['articulo'];

				$json_max = $conection2->select_max_table($campo1, "MS_INVENTARIO", array(), $condicionales);

				$campos = array("MS_COMBO_ID", "PRECIO_COMPRA", "MS_ALMACEN_ID", "CANTIDAD", "CANTIDAD_RESTANTE", "FOLIO", "ESTATUS_INVENTARIO", "FACTURA_COMPRA");
				$valores = array($_POST['articulo'], $_POST['precio'],$_POST['campo_almacen'], $_POST['dimension'], $_POST['dimension'], ($json_max)+1, 0, $_POST['factura']);
				$json = $conection2->insert_table($campos, "MS_INVENTARIO", $valores);
			}
		}else
		{
				$campo1 = "FOLIO";
				$condicionales = " AND MS_COMBO_ID=".$_POST['articulo'];

				$json_max = $conection2->select_max_table($campo1, "MS_INVENTARIO", array(), $condicionales);
				
				$campos = array("MS_COMBO_ID", "PRECIO_COMPRA", "MS_ALMACEN_ID", "CANTIDAD", "CANTIDAD_RESTANTE", "FOLIO", "ESTATUS_INVENTARIO", "FACTURA_COMPRA");
				$valores = array($_POST['articulo'], $_POST['precio'],$_POST['campo_almacen'], $_POST['cantidad'], $_POST['cantidad'], ($json_max)+1, 0, $_POST['factura']);
				$json = $conection2->insert_table($campos, "MS_INVENTARIO", $valores);
			
		}
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}

	if($_POST["accion"] == "guardar_almacen")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);

		
		$campos = array("NOMBRE", "activo");
		$valores = array("'".$_POST['almacen']."'", 0);
		$json = $conection2->insert_table($campos, "MS_ALMACEN", $valores);
			
		
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}

	if($_POST["accion"] == "guardar_transferencia")
	{
		
		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$campos = array("MS_ALMACEN_ID");
		$valores = array($_POST['almacen_transferencia']);
		$id = " ID_INVENTARIO in (".implode(",",$_POST['bajas']).")";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}