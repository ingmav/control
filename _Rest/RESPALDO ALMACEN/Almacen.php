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
		$conection2 = new conexion_nexos(2);
		$consulta_filtro = "";	

		if($_POST['familia']!=0)
		{
			$consulta_filtro.=" and MA.MS_FAMILIA_ID=".$_POST['familia'];
		}
		$query = "select 
 			MA.ID,
 			MA.NOMBRE_ARTICULO,
 			SUM(MI.CANTIDAD_RESTANTE) AS CANTIDAD_RESTANTE,
 			MA.ACTUALIZACION,
 			ma.CANTIDAD_MINIMA,
 			AVG(MI.PRECIO_UNITARIO) AS PRECIO_UNITARIO,
 			sum(MI.PRECIO_COMPRA) AS PRECIO_COMPRA
 			from MS_INVENTARIO MI,
 			MS_ARTICULOS MA WHERE 
 			MI.MS_ARTICULO_ID = MA.ID
 			AND ESTATUS_INVENTARIO=0 AND CANTIDAD_RESTANTE>0
 			".$consulta_filtro."
 			GROUP BY MI.MS_ARTICULO_ID, MA.NOMBRE_ARTICULO, MA.ID, MA.ACTUALIZACION, MA.CANTIDAD_MINIMA, MI.PRECIO_UNITARIO
 			ORDER BY MA.NOMBRE_ARTICULO";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        $total_precio_unitario = 0;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$row->ID]['ARTICULO_ID'] 				= utf8_encode($row->ID);
			$json[$row->ID]['ARTICULO'] 				= utf8_encode($row->NOMBRE_ARTICULO);
			$json[$row->ID]['INVENTARIO_INICIAL']		= $row->CANTIDAD_RESTANTE;
			$json[$row->ID]['ACTUALIZACION']			= $row->ACTUALIZACION;
			$json[$row->ID]['MS_INVENTARIO']			+= 0;
			$json[$row->ID]['SUGERIDA']					+= 0;
			$json[$row->ID]['CANTIDAD_MINIMA']			= $row->CANTIDAD_MINIMA;
			$json[$row->ID]['PRECIO_COMPRA']			= $row->PRECIO_COMPRA;

		$query_inventario_calculado = "select sum(unidades) as unidades from
(SELECT sum(dpd.unidades) as unidades FROM doctos_pv dp, doctos_pv_det dpd, ms_relacion mr, ms_articulos ma
where dp.docto_pv_id=dpd.docto_pv_id
and dp.fecha_hora_creacion>ma.actualizacion
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
and dv.fecha_hora_creacion>ma.actualizacion
and dvd.articulo_id=mr.articulo_id
and mr.ms_articulo_id=ma.id
and  MR.ms_articulo_id=".$row->ID."
and MR.ms_tipo_baja_id=1
and ma.estatus=0
and dv.tipo_docto in('F')
and dv.estatus!='C' ) x";
        
        $result_calculado = ibase_query($conection2->getConexion(), $query_inventario_calculado) or die(ibase_errmsg());
        
			while ($row_calculado = ibase_fetch_object ($result_calculado, IBASE_TEXT)){
				$json[$row->ID]['MS_INVENTARIO'] = number_format($row_calculado->UNIDADES,2);
				$cantidad_restante = $row->CANTIDAD_RESTANTE - $json[$row->ID]['MS_INVENTARIO'];
				$json[$row->ID]['SUGERIDA'] 	 = ($cantidad_restante < $row->CANTIDAD_MINIMA ) ? ($row->CANTIDAD_MINIMA - $cantidad_restante ): 0;

				$precio_articulo = (($cantidad_restante>0)? $cantidad_restante:0) * $row->PRECIO_UNITARIO;
				//$precio_articulo = $row->CANTIDAD_RESTANTE* $row->PRECIO_UNITARIO;

				$total_precio_unitario += $precio_articulo;
					
			}
			//$total_precio_unitario += $row->PRECIO_COMPRA;

			
		}

		
		$count = 0;
		
		$j = 1;
		
		$conection1 = null;
		$conection2 = null;

		$arreglo_respuesta = array("ARTICULOS"=>$json, "TOTAL"=>number_format($total_precio_unitario,2));
		$obj = (object) $arreglo_respuesta;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "formularios")
	{
		$conection2 = new conexion_nexos(2);
		
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

		$query = "select
		ID,
		DESCRIPCION
		FROM
		MS_FAMILIA
		".$consulta1;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json3 = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json3);
			$json3[$indice]['ID'] 				= $row->ID;
			$json3[$indice]['NOMBRE'] 			= utf8_encode($row->DESCRIPCION);
		}

		$query = "select
		ID,
		NOMBRE
		FROM
		MS_PROVEEDOR
		".$consulta1;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json4 = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json4);
			$json4[$indice]['ID'] 				= $row->ID;
			$json4[$indice]['NOMBRE'] 			= utf8_encode($row->NOMBRE);
		}
		
		$conection1 = null;
		$conection2 = null;

		$obj = (object) array("ARTICULOS" => $json, "ALMACENES"=>$json2, "CATEGORIA"=>$json3, "PROVEEDOR"=>$json4);
		echo json_encode($obj);
	}

	if($_POST["accion"] == "baja")
	{
		$conection2 = new conexion_nexos(2);
		$campos = array("ESTATUS_INVENTARIO");
		$valores = array(1);
		$id = " ID_INVENTARIO in (".implode(",",$_POST['bajas']).")";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "cerrar_factura")
	{
		$conection2 = new conexion_nexos(2);
		$campos = array("CERRADA");
		$valores = array(1);
		$id = " CERRADA=0";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST['accion'] == "ver_inventario")
	{
		$conection2 = new conexion_nexos(2);
		$query = "select
		MI.ID_INVENTARIO,
		MI.CANTIDAD_RESTANTE,
		MA.NOMBRE_ARTICULO,
		MI.ANCHO,
		MI.LARGO,
		MA.UNITARIO
		FROM
		MS_INVENTARIO MI,
		MS_ARTICULOS MA
		where MI.MS_ARTICULO_ID=".$_POST['id']." AND
		MI.MS_ARTICULO_ID = MA.ID
		and MI.ESTATUS_INVENTARIO=0";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 					= $row->ID_INVENTARIO;
			$json[$indice]['CANTIDAD_RESTANTE'] 	= $row->CANTIDAD_RESTANTE;
			$json[$indice]['ANCHO'] 				= $row->ANCHO;
			$json[$indice]['LARGO'] 				= $row->LARGO;
			$json[$indice]['UNITARIO'] 				= $row->UNITARIO;
			$json[$indice]['NOMBRE_ARTICULO'] 		= utf8_decode($row->NOMBRE_ARTICULO);
		}
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}


	if($_POST['accion'] == "baja_almacen")
	{
		
		$conection2 = new conexion_nexos(2);
		foreach ($_POST['ids'] as $key => $value) {
			

			$query = "select
			FIRST 1
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO
			FROM
			MS_INVENTARIO
			where ID_INVENTARIO=".$value;

	        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
	       	
	       	$cantidad_restante = 0;
	       	$precio_unitario = 0;
			while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$cantidad_restante 	= $row->CANTIDAD_RESTANTE;
				$precio_unitario 	= $row->PRECIO_UNITARIO;
			}

			$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");
			$baja =  ($cantidad_restante - $_POST['dimension_'.$value]);

			if($baja!=0)
			{
				$valores_movimiento = array(2, 0,0, 0,0, $baja,  $precio_unitario, ($precio_unitario*$baja), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $value );
				$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
			}	

			$campos = array("CANTIDAD_RESTANTE");
			$valores = array($_POST['dimension_'.$value]);
			
			if($_POST['dimension_'.$value] == 0)
			{
				$campos[] = "ESTATUS_INVENTARIO";
				$valores[] = 1;
			}
			$id = " ID_INVENTARIO =".$value;
			$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);
			
			
		}


		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;	
	}
	

	if($_POST["accion"] == "articulos")
	{
		$conection2 = new conexion_nexos(2);
		
		$query = "select
		ID,
		NOMBRE_ARTICULO
		FROM
		MS_ARTICULOS
		where MS_FAMILIA_ID=".$_POST['familia']
		." ORDER BY NOMBRE_ARTICULO";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 					= $row->ID;
			$json[$indice]['DESCRIPCION'] 			= utf8_encode($row->NOMBRE_ARTICULO);
		}
		$conection2 = null;

		$obj = (object) array("ARTICULOS" => $json);
		echo json_encode($obj);
	}

	if($_POST["accion"] == "guardar")
	{
		$conection2 = new conexion_nexos(2);

		$query = "select
		FIRST 1
		UNITARIO
		FROM
		MS_ARTICULOS
		where ID=".$_POST['articulo']." ";

		$result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json_articulo = array();
       	$es_calculable = 0;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$es_calculable = $row->UNITARIO;
		}

		$query = "select
		FIRST 1
		CONDICION_PAGO
		FROM
		MS_PROVEEDOR
		where ID=".$_POST['proveedor']." ";

		$result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$condicion_proveedor = 0;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$condicion_proveedor = $row->CONDICION_PAGO;
		}
		
		$cantidad = 0;
		$precio_unitario = 0;
		$precio_total = 0;
		$loops = 1;

		if($es_calculable == 0)
		{
			$cantidad = ($_POST['ancho'] * $_POST['largo']);
			$cantidad = round($cantidad,2, PHP_ROUND_HALF_DOWN);
			
			$precio_unitario = (($_POST['costo'] / $_POST['unidades']) / ($cantidad));
			$precio_unitario = round($precio_unitario,2,PHP_ROUND_HALF_DOWN);
			
			$precio_total = ($_POST['costo'] / $_POST['unidades']);
			$precio_total = round($precio_total,2, PHP_ROUND_HALF_DOWN);
			
			$loops = $_POST['unidades'];
		}else
		{
			$cantidad = $_POST['unidades'];
			$cantidad = round($cantidad,2, PHP_ROUND_HALF_DOWN);
			$precio_unitario = ($_POST['costo'] / $_POST['unidades']);
			$precio_unitario = round($precio_unitario,2,PHP_ROUND_HALF_DOWN);
			
			$precio_total = $_POST['costo'];
			$_POST['ancho'] = 0;
			$_POST['largo'] = 0;
		}

		$campos = array("PRECIO_COMPRA", 
						"MS_ALMACEN_ID", 
						"CANTIDAD", 
						"CANTIDAD_RESTANTE", 
						"ESTATUS_INVENTARIO", 
						"FACTURA_COMPRA", 
						"PRECIO_UNITARIO", 
						"MS_ARTICULO_ID", 
						"DESCRIPCION", 
						"MS_PROVEEDOR_ID", 
						"FECHA_FACTURA", 
						"FECHA_PAGO", 
						"ANCHO", 
						"LARGO");
				
		$valores = array($precio_total, 
						1, 
						$cantidad,
						$cantidad,
						0,
						"'".$_POST['factura']."'",
						$precio_unitario,
						$_POST['articulo'],
						"''",
						$_POST['proveedor'],
						"'".$_POST['fecha_factura']."'",
						"'".date("Y-m-d")."'",
						$_POST['ancho'],
						$_POST['largo']
						);
		for($i = 0; $i<$loops; $i++)
		$json = $conection2->insert_table($campos, "MS_INVENTARIO", $valores);

		/*$cantidad = $_POST['cantidad'];
		$dimension = $_POST['dimension'];

		$query = "select MS_ARTICULO_ID FROM MS_COMBOS WHERE ID=".$_POST['articulo'];
		
		$result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

		$count = count($campos);
		$contador = 0;
		$arreglo = array();
		$tipo = 0;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$tipo = $row->MS_ARTICULO_ID;

		}

		if($dimension > 0)
		{
			for($i = 0; $i < $_POST['cantidad']; $i++)
			{
		
				$campos = array("MS_COMBO_ID", "PRECIO_COMPRA", "MS_ALMACEN_ID", "CANTIDAD", "CANTIDAD_RESTANTE", "FOLIO", "ESTATUS_INVENTARIO", "FACTURA_COMPRA", "CANTIDAD_RESTANTE_COMPROMETIDA", "PRECIO_UNITARIO", "MS_ARTICULO_ID", "DESCRIPCION");
				$valores = array($_POST['articulo'], $_POST['precio'],$_POST['campo_almacen'], $_POST['dimension'], $_POST['dimension'], 1, 0, $_POST['factura'],
					$_POST['dimension'], ($_POST['precio'] / $_POST['dimension']), $tipo, "'".$_POST['descripcion']."'");
				$json = $conection2->insert_table($campos, "MS_INVENTARIO", $valores);
			}
		}else
		{
				$campos = array("MS_COMBO_ID", "PRECIO_COMPRA", "MS_ALMACEN_ID", "CANTIDAD", "CANTIDAD_RESTANTE", "FOLIO", "ESTATUS_INVENTARIO", "FACTURA_COMPRA","CANTIDAD_RESTANTE_COMPROMETIDA", "PRECIO_UNITARIO", "MS_ARTICULO_ID", "DESCRIPCION");
				$valores = array($_POST['articulo'], $_POST['precio'],$_POST['campo_almacen'], $_POST['cantidad'], $_POST['cantidad'], 1, 0, $_POST['factura'],$_POST['cantidad'], ($_POST['precio'] / $_POST['cantidad']), $tipo, "'".$_POST['descripcion']."'");
				$json = $conection2->insert_table($campos, "MS_INVENTARIO", $valores);
			
		}*/
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}

	if($_POST['accion'] == "guardar_proveedor")
	{

		$conection2 = new conexion_nexos(2);
		$campos = array("NOMBRE", 
						"DIRECCION", 
						"TELEFONO", 
						"CONDICION_PAGO", 
						"CONTACTO", 
						"DESCRIPCION");
				
		$valores = array("'".$_POST['nombre_proveedor']."'", 
						"'".$_POST['direccion']."'", 
						$_POST['telefono'], 
						$_POST['condicion'], 
						"'".$_POST['contacto']."'", 
						"'".$_POST['descipcion']."'");
		
		$json = $conection2->insert_table($campos, "MS_PROVEEDOR", $valores);

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST['accion'] == "guardar_insumo")
	{

		$conection2 = new conexion_nexos(2);
		$campos = array("NOMBRE_ARTICULO", 
						"ESTATUS", 
						"MS_FAMILIA_ID", 
						"CANTIDAD_MINIMA", 
						"ACTUALIZACION",
						"UNITARIO"
						);
				
		$valores = array("'".$_POST['insumo']."'", 
						0,
						$_POST['familia'],
						$_POST['minimo'],
						"'".date("Y-m-d H:i:s")."'", 
						$_POST['unitario']);
		
		$json = $conection2->insert_table($campos, "MS_ARTICULOS", $valores);

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST['accion'] == "proveedor")
	{
		$conection2 = new conexion_nexos(2);
		$query = "SELECT
					ID,
					NOMBRE,
					CONTACTO,
					CONDICION_PAGO,
					TELEFONO

FROM MS_PROVEEDOR WHERE DELETED IS NULL";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 			= utf8_encode($row->ID);
			$json[$indice]['NOMBRE'] 			= utf8_encode($row->NOMBRE);
			$json[$indice]['CONTACTO']			= utf8_encode($row->CONTACTO);
			$json[$indice]['TELEFONO']			= utf8_encode($row->TELEFONO);
			$json[$indice]['CONDICION_PAGO']	= $row->CONDICION_PAGO;

		}
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;	
	}

	if($_POST["accion"] == "actualiza_lista_factura")
	{
		$conection2 = new conexion_nexos(2);
		$query = "SELECT
mi.FACTURA_COMPRA,
ma.nombre_articulo,
(count(*)) AS REGISTROS,
SUM(CANTIDAD) AS CANTIDAD,
mi.precio_unitario,
(SUM(PRECIO_COMPRA)) AS PRECIO_COMPRA

FROM ms_inventario mi,
MS_PROVEEDOR mp,
ms_articulos ma
WHERE mi.CERRADA=0
AND mi.ms_proveedor_id=mp.ID
and mi.ms_articulo_id=ma.id
group BY mi.FACTURA_COMPRA, ma.nombre_articulo, mi.precio_unitario";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['FACTURA_COMPRA'] 			= $row->FACTURA_COMPRA;
			$json[$indice]['NOMBRE_ARTICULO']			= utf8_encode($row->NOMBRE_ARTICULO);
			$json[$indice]['REGISTROS']					= $row->REGISTROS;
			$json[$indice]['UNIDADES']					= $row->CANTIDAD;
			$json[$indice]['PRECIO_UNITARIO']			= $row->PRECIO_UNITARIO;
			$json[$indice]['PRECIO_COMPRA']			= $row->PRECIO_COMPRA;
		}
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;	
	}

	/*	{
		$conection2 = new conexion_nexos(2);

		
		$campos = array("NOMBRE", "activo");
		$valores = array("'".$_POST['almacen']."'", 0);
		$json = $conection2->insert_table($campos, "MS_ALMACEN", $valores);
			
		
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}*/

	if($_POST["accion"] == "guardar_transferencia")
	{
		
		$conection2 = new conexion_nexos(2);
		$campos = array("MS_ALMACEN_ID");
		$valores = array($_POST['almacen_transferencia']);
		$id = " ID_INVENTARIO in (".implode(",",$_POST['bajas']).")";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "baja_articulo")
	{


		$conection2 = new conexion_nexos(2);
		
		$query = "select
		FIRST 1
		CANTIDAD_RESTANTE,
		PRECIO_UNITARIO
		FROM
		MS_INVENTARIO
		where ID_INVENTARIO=".$_POST['id'];

        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	
       	$cantidad_restante = 0;
       	$precio_unitario = 0;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$cantidad_restante 	= $row->CANTIDAD_RESTANTE;
			$precio_unitario 	= $row->PRECIO_UNITARIO;
		}

		$baja =  $cantidad_restante;

		$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
		$valores_movimiento = array(2, 0, 0, 0,0, $baja,  $precio_unitario, ($precio_unitario*$baja), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $_POST['id'] );
		$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
		

		$campos = array("ESTATUS_INVENTARIO", "CANTIDAD_RESTANTE");
		$valores = array(1, 0);
		$id = " ID_INVENTARIO =".$_POST['id'];
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);
		
        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "eliminar_proveedor")
	{
		
		$conection2 = new conexion_nexos(2);
		$campos = array("DELETED");
		$valores = array(date("Y-m-d H:i:s"));
		$id = " ID = ".$_POST['ID'];
		
		$json = $conection2->update_table($campos, "MS_PROVEEDOR", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "actualizar_inventario")
	{
		$conection2 = new conexion_nexos(2);
		$query = "select 
 			MA.ID,
 			MA.NOMBRE_ARTICULO,
 			SUM(MI.CANTIDAD_RESTANTE) AS CANTIDAD_RESTANTE,
 			MA.ACTUALIZACION
 			from MS_INVENTARIO MI,
 			MS_ARTICULOS MA WHERE 
 			MI.MS_ARTICULO_ID = MA.ID
 			AND ESTATUS_INVENTARIO=0 AND CANTIDAD_RESTANTE>0
 			GROUP BY MI.MS_ARTICULO_ID, MA.NOMBRE_ARTICULO, MA.ID, MA.ACTUALIZACION";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		
        	$articulo_actualizar = 0;
        	$fecha_actualizar    = "";
		
			$indice = count($json);
			$json[$row->ID]['ARTICULO'] 				= utf8_encode($row->NOMBRE_ARTICULO);
			$json[$row->ID]['MS_INVENTARIO']			= $row->CANTIDAD_RESTANTE;
			$json[$row->ID]['ACTUALIZACION']			= $row->ACTUALIZACION;

		

			$query_inventario_calculado = "select empresa, id, id_det, unidades, articulo_id, fecha_hora_creacion, baja from
(SELECT 3 as empresa, dpd.docto_pv_id as id, dpd.docto_pv_det_id as id_det, dpd.unidades, dpd.articulo_id, dp.fecha_hora_creacion
,(dpd.unidades * mr.cantidad) as baja
FROM doctos_pv dp, doctos_pv_det dpd, ms_relacion mr, ms_articulos ma
where dp.docto_pv_id=dpd.docto_pv_id
and dp.fecha_hora_creacion > ma.actualizacion
and dpd.articulo_id=mr.articulo_id
and mr.ms_articulo_id=ma.id
and  MR.ms_articulo_id=".$row->ID."
and MR.ms_tipo_baja_id=1
and ma.estatus=0
and dp.tipo_docto in('V')
and dp.estatus!='C'
union all
SELECT 2 as empresa, dvd.docto_ve_id as id, dvd.docto_ve_det_id as id_det, dvd.unidades, dvd.articulo_id, dv.fecha_hora_creacion
,(dvd.unidades * mr.cantidad) as baja
FROM doctos_ve dv, doctos_ve_det dvd, ms_relacion mr, ms_articulos ma
where dv.docto_ve_id=dvd.docto_ve_id
and dv.fecha_hora_creacion>ma.actualizacion
and dvd.articulo_id=mr.articulo_id
and mr.ms_articulo_id=ma.id
and  MR.ms_articulo_id=".$row->ID."
and MR.ms_tipo_baja_id=1
and ma.estatus=0
and dv.tipo_docto in('F', 'R')
and dv.estatus!='C' ) x
order by fecha_hora_creacion";
    
	        $result_calculado = ibase_query($conection2->getConexion(), $query_inventario_calculado) or die(ibase_errmsg());

				$query_inventario_global = "select sum(cantidad_restante) restante_global from ms_inventario
				where
				estatus_inventario=0
				and ms_articulo_id=".$row->ID."
				group by ms_articulo_id";
		        $cantidad_global = 0;
		        $result_inventario_global = ibase_query($conection2->getConexion(), $query_inventario_global) or die(ibase_errmsg());
		    	while ($row_inventario_global = ibase_fetch_object ($result_inventario_global, IBASE_TEXT)){
					$cantidad_global 	= $row_inventario_global->RESTANTE_GLOBAL;
				}
				//echo $global." -- ";
			        
	        	
	        	$cantidad = array("id"=>0, "cantidad_restante"=>0);
	        	$cantidad_baja  = 0;
	        	$arreglo_baja = array();
				while ($row_calculado = ibase_fetch_object ($result_calculado, IBASE_TEXT)){
					
					if($cantidad['cantidad_restante'] == 0)
					{
						
						$query_inventario = "select first 1 id_inventario, cantidad_restante, precio_unitario, ms_articulo_id from ms_inventario
		where
		estatus_inventario=0
		and ms_articulo_id=".$row->ID."
		order by fecha_actualizacion, id_inventario";
		        
		        $result_inventario = ibase_query($conection2->getConexion(), $query_inventario) or die(ibase_errmsg());
		        	$json_inventario = array();
						
						do
						{
							while ($row_inventario = ibase_fetch_object ($result_inventario, IBASE_TEXT)){
								
								$cantidad['id'] 				= $row_inventario->ID_INVENTARIO;
								$cantidad['cantidad_restante'] 	= $row_inventario->CANTIDAD_RESTANTE;
								$cantidad['precio_unitario'] 	= $row_inventario->PRECIO_UNITARIO;
								$cantidad['ms_articulo_id'] 	= $row_inventario->MS_ARTICULO_ID;
							}

							if($cantidad_baja > 0)
							{
								if($cantidad_baja < $cantidad['cantidad_restante'])
								{
									$campos = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");
									$valores = array($arreglo_baja->EMPRESA, $arreglo_baja->ID, $arreglo_baja->ID_DET, $arreglo_baja->ARTICULO_ID, $arreglo_baja->UNIDADES, $cantidad_baja,$cantidad['precio_unitario'],($cantidad_baja * $cantidad['precio_unitario']),"'BAJA AUTOMATICA'","'".date("Y-m-d H:i:s")."'", $cantidad['id']);
									$conection2->insert_table($campos, "MS_MOVIMIENTO", $valores);	

									
									$campo1  = array("CANTIDAD_RESTANTE");
									$valores = array(($cantidad['cantidad_restante'] - $cantidad_baja));
									$cantidad_global -= $cantidad_baja;

									$id = " ID_INVENTARIO=".$cantidad['id'];
									$conection2->update_table($campo1, "MS_INVENTARIO", $valores, $id);	

									$cantidad['cantidad_restante']." - ".$cantidad['id']." **";
									$cantidad['cantidad_restante'] -= $cantidad_baja;

									$articulo_actualizar 	= $cantidad['ms_articulo_id'];
									$fecha_actualizar 		= $arreglo_baja->FECHA_HORA_CREACION; 
									$cantidad_baja = 0;
								}else
								{
									$campos = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");
									$valores = array($arreglo_baja->EMPRESA, $arreglo_baja->ID, $arreglo_baja->ID_DET, $arreglo_baja->ARTICULO_ID, $arreglo_baja->UNIDADES, $cantidad['cantidad_restante'],$cantidad['precio_unitario'],($cantidad['cantidad_restante'] * $cantidad['precio_unitario']),"'BAJA AUTOMATICA'","'".date("Y-m-d H:i:s")."'", $cantidad['id']);
									$conection2->insert_table($campos, "MS_MOVIMIENTO", $valores);	

									
									$campo1  = array("CANTIDAD_RESTANTE", "ESTATUS_INVENTARIO");
									$valores = array(0,1);
									
									$cantidad_global -= $cantidad['cantidad_restante'];
									$cantidad_baja -= $cantidad['cantidad_restante'];

									$id = " ID_INVENTARIO=".$cantidad['id'];
									$conection2->update_table($campo1, "MS_INVENTARIO", $valores, $id);	

									$cantidad['cantidad_restante'] = 0;

									$articulo_actualizar 	= $cantidad['ms_articulo_id'];
									$fecha_actualizar 		= $arreglo_baja->FECHA_HORA_CREACION; 
									
								}
							}
						}while($cantidad_baja!=0);
					}
					
					if($cantidad['cantidad_restante'] > 0)
					{
						$campos = array();
						
						
						if($row_calculado->UNIDADES < $cantidad['cantidad_restante'])
						{
							
							$campos = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");
							$valores = array($row_calculado->EMPRESA, $row_calculado->ID, $row_calculado->ID_DET, $row_calculado->ARTICULO_ID, $row_calculado->UNIDADES, $row_calculado->BAJA,$cantidad['precio_unitario'],($row_calculado->BAJA * $cantidad['precio_unitario']),"'BAJA AUTOMATICA'","'".date("Y-m-d H:i:s")."'", $cantidad['id']);
							$conection2->insert_table($campos, "MS_MOVIMIENTO", $valores);	

							
							$campo1  = array("CANTIDAD_RESTANTE");
							$valores = array(($cantidad['cantidad_restante'] - $row_calculado->BAJA));
							$cantidad_global -= $row_calculado->BAJA;

							$id = " ID_INVENTARIO=".$cantidad['id'];
							$conection2->update_table($campo1, "MS_INVENTARIO", $valores, $id);	

							$cantidad['cantidad_restante']." - ".$cantidad['id']." **";
							$cantidad['cantidad_restante'] -= $row_calculado->BAJA;

							$articulo_actualizar 	= $cantidad['ms_articulo_id'];
							$fecha_actualizar 		= $row_calculado->FECHA_HORA_CREACION; 

						}else
						{
							if($cantidad_global > $row_calculado->UNIDADES)
							{
								$campos = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");
							$valores = array($row_calculado->EMPRESA, $row_calculado->ID, $row_calculado->ID_DET, $row_calculado->ARTICULO_ID, $row_calculado->UNIDADES, $cantidad['cantidad_restante'],$cantidad['precio_unitario'],($cantidad['cantidad_restante'] * $cantidad['precio_unitario']),"'BAJA AUTOMATICA'","'".date("Y-m-d H:i:s")."'", $cantidad['id']);
							$conection2->insert_table($campos, "MS_MOVIMIENTO", $valores);	

							$cantidad_baja = $row_calculado->BAJA - $cantidad['cantidad_restante'];

							$campo1  = array("CANTIDAD_RESTANTE", "ESTATUS_INVENTARIO");
							$valores = array(0,1);
							$cantidad_global -= $row_calculado->BAJA;

							$id = " ID_INVENTARIO=".$cantidad['id'];
							$conection2->update_table($campo1, "MS_INVENTARIO", $valores, $id);	

							//$cantidad['cantidad_restante']." - ".$cantidad['id']." **";
							$cantidad['cantidad_restante'] = 0;

							$articulo_actualizar 	= $cantidad['ms_articulo_id'];
							$fecha_actualizar 		= $row_calculado->FECHA_HORA_CREACION; 

							$arreglo_baja = $row_calculado;
							}
							
						}
					}
					
					
				}
				
				$nuevafecha = strtotime ( '+1 second' , strtotime ( $fecha_actualizar ) ) ;
				$nuevafecha = date ( 'Y-m-j H:i:s' , $nuevafecha );

				$campo1  = array("ACTUALIZACION");
				$valores = array("'".$nuevafecha."'");
				$id = " ID=".$articulo_actualizar;
				$conection2->update_table($campo1, "MS_ARTICULOS", $valores, $id);

			}
			

		$count = 0;
		
		$j = 1;
		
		$conection1 = null;
		$conection2 = null;

		$obj = (object) $json;
		echo json_encode($obj);
		
	}