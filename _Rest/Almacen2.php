<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	//$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{
		$consulta1 = "";
		$conection2 = new conexion_nexos(2);
		$consulta_filtro = "";	

		if($_POST['familia']!=0)
		{
			$consulta_filtro.=" and MA.MS_FAMILIA_ID=".$_POST['familia'];
		}
		if($_POST['texto']!="" && isset($_POST['texto']))	 
		{
			$consulta_filtro.=" and MA.NOMBRE_ARTICULO like '%".strtoupper($_POST['texto'])."%'";
		}
		
		$query = "select
MA.ID,
MAX(MI.ID_INVENTARIO) AS ID_INVENTARIO,
MF.DESCRIPCION AS FAMILIA,
MA.NOMBRE_ARTICULO,
MA.CANTIDAD_MINIMA,
MA.UNITARIO,
MA.MS_FAMILIA_ID,
AVG(MI.ANCHO) AS ANCHO,
AVG(MI.LARGO) AS LARGO,
MA.unidad_venta,
MA.unidad_compra,
MA.PAQUETE,
SUM(MI.CANTIDAD_RESTANTE) AS CANTIDAD_RESTANTE,
AVG(MI.PRECIO_UNITARIO) AS MONTO,
MA.ACTUALIZACION,
(select count(*) from ms_inventario where ms_articulo_id=ma.id and activo=0 AND ESTATUS_INVENTARIO=0) AS REGISTROS,
(select sum(cantidad_restante) from ms_inventario where ms_articulo_id=ma.id and activo=1 AND ESTATUS_INVENTARIO=0) AS CANTIDAD_USO,
(SELECT first 1 precio_compra from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as MONTO_METRAJE,
(SELECT first 1 precio_unitario cantidad from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as MONTO_UNITARIO,
(SELECT first 1 '( ' || ancho || ' X ' || largo || ') ' from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as DIMENSION,
(SELECT first 1 cantidad from ms_inventario where ma.id=ms_articulo_id order by id_inventario desc) as DIMENSION_UNITARIO
from
MS_ARTICULOS MA
LEFT JOIN MS_INVENTARIO MI ON  MA.ID = MI.MS_ARTICULO_ID AND MI.ESTATUS_INVENTARIO=0,
MS_FAMILIA MF

WHERE MA.ESTATUS=0
AND MI.CANTIDAD_RESTANTE >0
AND MA.MS_FAMILIA_ID=MF.ID
AND MI.TIPO!='B'
".$consulta_filtro."

GROUP BY MF.DESCRIPCION, MA.NOMBRE_ARTICULO, MA.CANTIDAD_MINIMA, MA.UNITARIO, MA.MS_FAMILIA_ID, MA.ANCHO, MA.LARGO, MA.unidad_venta,  MA.unidad_compra,  MA.PAQUETE, MA.ID, MA.ACTUALIZACION
ORDER BY MF.DESCRIPCION, MA.NOMBRE_ARTICULO";
		

        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        $total_precio_unitario = 0;
        $arreglo = array();
		
        
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			

			$indice = count($json);

			
			$json[$indice]['INVENTARIO']				= floatval($row->CANTIDAD_RESTANTE);
			$json[$indice]['ARTICULO_ID'] 				= $row->ID;
			$json[$indice]['PAQUETE'] 					= $row->PAQUETE;
			$json[$indice]['UNITARIO'] 					= $row->UNITARIO;
			$json[$indice]['UNIDAD_VENTA'] 				= $row->UNIDAD_VENTA;
			$json[$indice]['UNIDAD_COMPRA'] 			= $row->UNIDAD_COMPRA;
			$json[$indice]['ID_FAMILIA']	 			= $row->MS_FAMILIA_ID;
			$json[$indice]['CANTIDAD_USO'] 				= floatval($row->CANTIDAD_USO);
			$json[$indice]['ANCHO'] 					= floatval($row->ANCHO);
			$json[$indice]['LARGO'] 					= floatval($row->LARGO);
			$json[$indice]['MONTO_METRAJE']				= floatval($row->MONTO_METRAJE);
			$json[$indice]['MONTO_UNITARIO']			= floatval($row->MONTO_UNITARIO);
			$json[$indice]['REGISTROS']					= $row->REGISTROS;
			$json[$indice]['CANTIDAD_USO']				= $row->CANTIDAD_USO;
			$json[$indice]['ARTICULO'] 					= $row->NOMBRE_ARTICULO." (".$row->FAMILIA.") ";
			$json[$indice]['FAMILIA'] 					= $row->FAMILIA;
			$json[$indice]['ACTUALIZACION']				= $row->ACTUALIZACION;
			$json[$indice]['CANTIDAD_MINIMA']			= $row->CANTIDAD_MINIMA;
			$json[$indice]['DIMENSION']					= $row->DIMENSION;
			$json[$indice]['DIMENSION_UNITARIO']		= $row->DIMENSION_UNITARIO;
			//$json[$indice]['PRECIO_UNITARIO']			= $row->PRECIO_UNITARIO;	
			$json[$indice]['PRECIO_UNITARIO']			= floatval($row->MONTO);	
			$json[$indice]['ID_INVENTARIO']				= $row->ID_INVENTARIO;	
			

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
				$json[$indice]['INVENTARIO'] -= $row_calculado->UNIDADES;
				
			}
		}
		$count = 0;
		
		$j = 1;

		
		
		$conection1 = null;
		$conection2 = null;

		$arreglo_respuesta = array("ARTICULOS"=>$json, "TOTAL"=>number_format($total_precio_unitario,2));
		$obj = (object) $arreglo_respuesta;
		echo json_encode($obj);
 					
 		/*$query = "select  
 					MA.ID, 
 					MA.NOMBRE_ARTICULO,
 					MF.DESCRIPCION AS FAMILIA,
 					MA.ACTUALIZACION, 
 					MA.CANTIDAD_MINIMA,
 					IIF ((SELECT FIRST 1 MI.CANTIDAD FROM MS_INVENTARIO MI WHERE MI.ms_articulo_id=MA.id AND MI.ESTATUS_INVENTARIO=0 ORDER by MI.id_inventario) IS NULL, 1, 0) AS BANDERA,
 					IIF ((SELECT FIRST 1 MI.CANTIDAD FROM MS_INVENTARIO MI WHERE MI.ms_articulo_id=MA.id ORDER by MI.id_inventario) IS NULL, 1, 0) AS BANDERA2,
					(SELECT AVG(CANTIDAD) FROM MS_INVENTARIO MI WHERE MI.ms_articulo_id=MA.id AND MI.ESTATUS_INVENTARIO=0) AS CANTIDAD,
					(SELECT AVG(PRECIO_COMPRA) FROM MS_INVENTARIO MI WHERE MI.ms_articulo_id=MA.id AND MI.ESTATUS_INVENTARIO=0) AS PRECIO_COMPRA,
					(SELECT FIRST 1 PRECIO_COMPRA FROM MS_INVENTARIO MI WHERE MI.ms_articulo_id=MA.id ORDER BY MI.ID_INVENTARIO) AS PRECIO_COMPRA_2,
					(SELECT SUM(MI.CANTIDAD_RESTANTE) FROM MS_INVENTARIO MI WHERE MI.ms_articulo_id=MA.id AND MI.ESTATUS_INVENTARIO=0 AND MI.CANTIDAD_RESTANTE>0) AS CANTIDAD_RESTANTE,
					(select count(*) from ms_inventario where ms_articulo_id=ma.id and activo=0 AND ESTATUS_INVENTARIO=0) AS REGISTROS,
					(select sum(cantidad_restante) from ms_inventario where ms_articulo_id=ma.id and activo=1 AND ESTATUS_INVENTARIO=0) AS CANTIDAD_USO,
					MA.paquete,
					MA.UNIDAD_VENTA,
					MA.UNIDAD_COMPRA,
					MA.UNITARIO,
					MA.ANCHO,
					MA.LARGO
					from  MS_ARTICULOS MA, MS_FAMILIA MF
					WHERE MA.ESTATUS=0 ".$consulta_filtro."
					AND MA.MS_FAMILIA_ID=MF.ID
					ORDER BY MF.DESCRIPCION, MA.NOMBRE_ARTICULO";
		

        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        $total_precio_unitario = 0;
        $arreglo = array();
		
        
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			

			$indice = count($json);

			if($row->BANDERA == 1)
			{ 
				
				$json[$row->ID]['INVENTARIO_INICIAL']		= 0;
				$json[$row->ID]['INVENTARIO']				= 0;
				$json[$row->ID]['CANTIDAD']					= 0;

				if($row->BANDERA2 == 1)
				{
					$json[$row->ID]['PRECIO_COMPRA']			= 0;
				}else
				{
					$json[$row->ID]['PRECIO_COMPRA']			= $row->PRECIO_COMPRA_2;
				}
				
			}else
			{
				$json[$row->ID]['INVENTARIO_INICIAL']		= $row->CANTIDAD_RESTANTE;
				$json[$row->ID]['INVENTARIO']				= $row->CANTIDAD_RESTANTE;
				$json[$row->ID]['CANTIDAD']					= $row->CANTIDAD;
				$json[$row->ID]['PRECIO_COMPRA']			= $row->PRECIO_COMPRA;
			}

			$json[$row->ID]['ARTICULO_ID'] 				= $row->ID;
			$json[$row->ID]['REGISTROS'] 				= $row->REGISTROS;
			$json[$row->ID]['PAQUETE'] 					= $row->PAQUETE;
			$json[$row->ID]['UNITARIO'] 				= $row->UNITARIO;
			$json[$row->ID]['UNIDAD_VENTA'] 			= $row->UNIDAD_VENTA;
			$json[$row->ID]['UNIDAD_COMPRA'] 			= $row->UNIDAD_COMPRA;
			$json[$row->ID]['CANTIDAD_USO'] 			= $row->CANTIDAD_USO;
			$json[$row->ID]['ANCHO'] 					= $row->ANCHO;
			$json[$row->ID]['LARGO'] 					= $row->LARGO;
			$json[$row->ID]['ARTICULO'] 				= utf8_encode($row->NOMBRE_ARTICULO." (".$row->FAMILIA.") ");
			$json[$row->ID]['ACTUALIZACION']			= $row->ACTUALIZACION;
			$json[$row->ID]['MS_INVENTARIO']			= 0;
			$json[$row->ID]['SUGERIDA']					= 0;
			$json[$row->ID]['CANTIDAD_MINIMA']			= $row->CANTIDAD_MINIMA;
			
			
			$json[$row->ID]['INDICE']					= $indice;
			$json[$row->ID]['BANDERA']					= $row->BANDERA;

			

			if($row->CANTIDAD > 0)
				$json[$row->ID]['PRECIO_UNITARIO']		= ($row->PRECIO_COMPRA / $row->CANTIDAD);
			else
				$json[$row->ID]['PRECIO_UNITARIO']		= 0;

			

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
				$json[$row->ID]['MS_INVENTARIO'] = (is_numeric($row_calculado->UNIDADES)? $row_calculado->UNIDADES: 0);
				$cantidad_restante 				 = $row->CANTIDAD_RESTANTE - $json[$row->ID]['MS_INVENTARIO'];
				$json[$row->ID]['INVENTARIO'] -= $json[$row->ID]['MS_INVENTARIO'];
				//$json[$row->ID]['INVENTARIO']  = number_format($json[$row->ID]['INVENTARIO'],2);
				$json[$row->ID]['SUGERIDA'] 	 = ($cantidad_restante < $row->CANTIDAD_MINIMA ) ? ($row->CANTIDAD_MINIMA - $cantidad_restante ): 0;

				$precio_articulo = (($cantidad_restante>0)? $cantidad_restante:0) * $json[$row->ID]['PRECIO_UNITARIO'];
				$json[$row->ID]['PRECIO_TOTAL'] = number_format($precio_articulo,2); 

				$total_precio_unitario += $precio_articulo;
			}	
		}
		$count = 0;
		
		$j = 1;

		
		
		$conection1 = null;
		$conection2 = null;

		$arreglo_respuesta = array("ARTICULOS"=>$json, "TOTAL"=>number_format($total_precio_unitario,2));
		$obj = (object) $arreglo_respuesta;
		echo json_encode($obj);*/
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
		".$consulta1."
		where deleted is null
		order by NOMBRE
		";
        
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

	if($_POST["accion"] == "historial")
	{
		$conection2 = new conexion_nexos(2);
		$consulta = "";

		if($_POST['historial_proveedor'] != 0)
			$consulta .= " and mp.ms_proveedor_id=".$_POST['historial_proveedor'];
		
		if($_POST['historial_factura'] != "")
			$consulta .= " and mp.factura='".$_POST['historial_factura']."'";
		
		if($_POST['historial_inicio'] != "")
		{
			if($_POST['historial_fin'] != "")
				$consulta .= " and mp.fecha_factura between '".$_POST['historial_inicio']."'  and '".$_POST['historial_fin']."'";
			else
				$consulta .= " and mp.fecha_factura = '".$_POST['historial_inicio']."'";
		}
		
		$query = "select
					mp.factura,
					mpr.nombre,
					mp.ms_proveedor_id,
					mp.fecha_factura,
					sum(mp.monto) as subtotal,
					sum(mp.descuento) as descuento,
					sum(mp.monto-descuento) as total,
					max(mp.fecha_pagado) as fecha_pagado
					from ms_pagos mp, ms_proveedor mpr
					where mp.pagado=1
					and mp.ms_proveedor_id=mpr.id

					".$consulta."
					group by
					mp.factura,
					mp.ms_proveedor_id,
					mpr.nombre,
					mp.fecha_factura
					order by mp.fecha_factura";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['MS_PROVEEDOR_ID']			= $row->MS_PROVEEDOR_ID;
			$json[$indice]['FACTURA'] 					= $row->FACTURA;
			$json[$indice]['NOMBRE'] 					= $row->NOMBRE;
			$json[$indice]['FECHA_FACTURA'] 			= $row->FECHA_FACTURA;
			$json[$indice]['SUBTOTAL'] 					= round($row->SUBTOTAL,2);
			$json[$indice]['DESCUENTO'] 				= $row->DESCUENTO;
			$json[$indice]['TOTAL'] 					= round($row->TOTAL,2);
			$json[$indice]['FECHA_PAGADO']				= $row->FECHA_PAGADO;
			
		}

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "info_historial")
	{
		$conection2 = new conexion_nexos(2);
		
		$query = "select
					ma.nombre_articulo || ' ( ' || mf.descripcion || ' ) ' as articulo,
					mp.cantidad ,
					mp.ancho,
					mp.largo,
					mp.monto as subtotal,
					mp.descuento as descuento
					
					from ms_pagos mp, 
					ms_articulos ma,
					ms_familia mf
					
					where mp.pagado=1
					and mp.ms_articulo_id = ma.id
					and ma.ms_familia_id = mf.id

					and mp.ms_proveedor_id=".$_POST['proveedor']."
					and mp.factura='".$_POST['factura']."'
					
					";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ARTICULO']					= $row->ARTICULO;
			$json[$indice]['CANTIDAD'] 					= $row->CANTIDAD;
			$json[$indice]['ANCHO'] 					= $row->ANCHO;
			$json[$indice]['LARGO'] 					= $row->LARGO;
			$json[$indice]['SUBTOTAL'] 					= round($row->SUBTOTAL,2);
			$json[$indice]['DESCUENTO'] 				= $row->DESCUENTO;
			
			
		}

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "cerrar_factura")
	{
		$conection2 = new conexion_nexos(2);
		
		$query = "select FACTURA_COMPRA, MS_PROVEEDOR_ID, FECHA_FACTURA, MS_ARTICULO_ID, sum(PRECIO_COMPRA) as PRECIO_COMPRA, ANCHO, LARGO, COUNT(*) AS CANTIDAD FROM MS_INVENTARIO WHERE TIPO='B' GROUP BY FACTURA_COMPRA, MS_PROVEEDOR_ID, FECHA_FACTURA, MS_ARTICULO_ID, ANCHO, LARGO";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			
			$campos = array("FACTURA", "MS_PROVEEDOR_ID", "FECHA_FACTURA", "MS_ARTICULO_ID", "MONTO", "PAGADO", "CANTIDAD", "ANCHO", "LARGO");
			
			$valores = array("'".$row->FACTURA_COMPRA."'", $row->MS_PROVEEDOR_ID, "'".$row->FECHA_FACTURA."'", $row->MS_ARTICULO_ID, $row->PRECIO_COMPRA, 0 , $row->CANTIDAD, $row->ANCHO, $row->LARGO);
			//print_r($valores);
			$conection2->insert_table($campos, "MS_PAGOS", $valores);
			
			$query1 = "INSERT INTO MS_PAGOS(FACTURA, MS_PROVEEDOR_ID, FECHA_FACTURA, MS_ARTICULO_ID, MONTO, PAGADO, CANTIDAD, ANCHO, LARGO, TIPO) VALUES('".$_POST['factura']."',".$_POST['proveedor'].",'".$_POST['fecha_factura']."',".$_POST['articulo'].",".$_POST['costo'].",0,".$_POST['unidades'].", ".$_POST['ancho'].", ".$_POST['largo'].")";
			
			if(ibase_query($conection2->getConexion(), $query1))
			{
				
				$query2 = "select
				max(ID_PAGO) AS ID_PAGO
				FROM MS_PAGOS";
				$result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());
	       	
				$row2 = ibase_fetch_object ($result2, IBASE_TEXT);
				$query2 = "UPDATE MS_INVENTARIO SET MS_PAGOS_ID=".$row2->ID_PAGO." where TIPO='B'";
			
				ibase_query($conection2->getConexion(), $query2);
			}
		}
		$campos = array("CERRADA", "TIPO");
		$valores = array(1, "'C'");
		$id = " CERRADA=0";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);
		
        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST['accion'] == "ver_inventario")
	{
		$conection2 = new conexion_nexos(2);
		$query = "select mi.ms_articulo_id, max(mi.ID_INVENTARIO) as ID_INVENTARIO,  mi.activo, mi.ancho, mi.largo, sum(mi.cantidad_restante) as CANTIDAD_RESTANTE, count(*) as unidades, MA.NOMBRE_ARTICULO, MA.unitario, MA.UNIDAD_COMPRA, MA.PAQUETE
FROM MS_INVENTARIO MI, MS_ARTICULOS MA where MI.MS_ARTICULO_ID=".$_POST['id']." AND MI.MS_ARTICULO_ID = MA.ID and MI.ESTATUS_INVENTARIO=0 and MI.TIPO!='B'
and mi.activo=0
group by mi.ms_articulo_id, mi.activo, mi.ancho, mi.largo, MA.NOMBRE_ARTICULO, MA.unitario, MA.UNIDAD_COMPRA, MA.PAQUETE
";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	$json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 					= $row->MS_ARTICULO_ID;
			$json[$indice]['ID_INVENTARIO'] 		= $row->ID_INVENTARIO;
			$json[$indice]['UNIDADES'] 				= $row->UNIDADES;
			$json[$indice]['CANTIDAD_RESTANTE'] 	= $row->CANTIDAD_RESTANTE;
			$json[$indice]['ANCHO'] 				= $row->ANCHO;
			$json[$indice]['LARGO'] 				= $row->LARGO;
			$json[$indice]['UNITARIO'] 				= $row->UNITARIO;
			$json[$indice]['NOMBRE_ARTICULO'] 		= utf8_decode($row->NOMBRE_ARTICULO);
			$json[$indice]['UNIDAD_COMPRA'] 		= utf8_decode($row->UNIDAD_COMPRA);
			$json[$indice]['PAQUETE'] 				= utf8_decode($row->PAQUETE);
		}
		
		$query2 = "select MI.ID_INVENTARIO, mi.ms_articulo_id, mi.activo, mi.ancho, mi.largo, count(*) as unidades, MA.NOMBRE_ARTICULO, MA.unitario, sum(MI.CANTIDAD_RESTANTE) as CANTIDAD_RESTANTE
FROM MS_INVENTARIO MI, MS_ARTICULOS MA where MI.MS_ARTICULO_ID=".$_POST['id']." AND MI.MS_ARTICULO_ID = MA.ID and MI.ESTATUS_INVENTARIO=0 AND MI.tipo!='B'
and mi.activo=1
group by mi.ms_articulo_id, mi.activo, mi.ancho, mi.largo, MA.NOMBRE_ARTICULO, MA.unitario, MI.ID_INVENTARIO
";
        
        $result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());
       	$json2 = array();
		while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
			$indice = count($json2);
			$json2[$indice]['ID'] 					= $row2->ID_INVENTARIO;
			$json2[$indice]['UNIDADES'] 			= $row2->UNIDADES;
			$json2[$indice]['CANTIDAD_RESTANTE'] 	= $row2->CANTIDAD_RESTANTE;
			$json2[$indice]['ANCHO'] 				= $row2->ANCHO;
			$json2[$indice]['LARGO'] 				= $row2->LARGO;
			$json2[$indice]['UNITARIO'] 			= $row2->UNITARIO;
			$json2[$indice]['NOMBRE_ARTICULO'] 		= utf8_decode($row2->NOMBRE_ARTICULO);
			
		}

		$unitario = 0;
		$id = $_POST['id'];
		$bandera = 0;
		$descuento_calculado = 0;
		if(count($json) > 0)
		{
			$unitario = $json[0]['UNITARIO']; 
			$bandera++;
		}else if(count($json2) > 0)
		{
			$unitario = $json2[0]['UNITARIO']; 
			$bandera++;
		}

		if( $bandera > 0)
		{
				$query_inventario_calculado = "select sum(unidades) as unidades from
(SELECT sum(dpd.unidades) as unidades FROM doctos_pv dp, doctos_pv_det dpd, ms_relacion mr, ms_articulos ma
where dp.docto_pv_id=dpd.docto_pv_id
and dp.fecha_hora_creacion>ma.actualizacion
and dpd.articulo_id=mr.articulo_id
and mr.ms_articulo_id=ma.id
and  MR.ms_articulo_id=".$id."
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
and  MR.ms_articulo_id=".$id."
and MR.ms_tipo_baja_id=1
and ma.estatus=0
and dv.tipo_docto in('F')
and dv.estatus!='C' ) x";
        
	        $result_calculado = ibase_query($conection2->getConexion(), $query_inventario_calculado) or die(ibase_errmsg());
	        
			while ($row_calculado = ibase_fetch_object ($result_calculado, IBASE_TEXT)){
				$descuento_calculado = $row_calculado->UNIDADES;
			}
		}

		//$json[(count($json)-1)]['CANTIDAD_RESTANTE'] -= $descuento_calculado; 
		$bandera = 0;
		/*while($bandera == 0 && $descuento_calculado>0)
		{
			if(count($json2) > 0)
			{
				$contador1 = 0;
				$num_index = (count($json2)-1);
				while($json2[$num_index - $contador1] && $descuento_calculado>0)
				{
					if($descuento_calculado>0)
					{
						if($json2[($num_index-$contador1)]['CANTIDAD_RESTANTE'] < $descuento_calculado)
						{
							$descuento_calculado -= $json2[($num_index-$contador1)]['CANTIDAD_RESTANTE'];
							unset($json2[($num_index-$contador1)]);
						}else
						{
							$json2[($num_index-$contador1)]['CANTIDAD_RESTANTE'] -= $descuento_calculado;
							$descuento_calculado = 0;
							
						}
					}
					$contador1++;
				}

			}
			if(count($json) > 0)
			{
				$contador1 = 0;
				$num_index = (count($json)-1);
				while($json[($num_index - $contador1)] && $descuento_calculado>0)
				{
					if($descuento_calculado>0)
					{
						if($json[($num_index - $contador1)]['CANTIDAD_RESTANTE'] < $descuento_calculado)
						{
							$descuento_calculado -= $json[($num_index - $contador1)]['CANTIDAD_RESTANTE'];
							unset($json[($num_index - $contador1)]);
						}else
						{
							$json[($num_index - $contador1)]['CANTIDAD_RESTANTE'] -= $descuento_calculado;
							$descuento_calculado = 0;
							
						}
					}
					$contador1++;
				}
				
			}

			if($descuento_calculado<0)
				$bandera = 1;	
		}*/
		$obj = (object) array("ENTERO"=> $json, "USO"=> $json2, "DESCUENTO"=> $descuento_calculado);
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

		." and estatus=0 ORDER BY NOMBRE_ARTICULO";
        
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

		$_POST['costo'] =  round(($_POST['costo'] * 1.16),2, PHP_ROUND_HALF_DOWN);

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

		//$campos = array("FACTURA", "MS_PROVEEDOR_ID", "FECHA_FACTURA", "MS_ARTICULO_ID", "MONTO", "PAGADO", "CANTIDAD", "ANCHO", "LARGO");
		//$valores = array("'".$_POST['factura']."'",$_POST['proveedor'],"'".$_POST['fecha_factura']."'",$_POST['articulo'],$_POST['costo'],0,$_POST['unidades'], $_POST['ancho'], $_POST['largo']);
		//$conection2->insert_table($campos, "MS_PAGOS", $valores);
		
		//$query1 = "INSERT INTO MS_PAGOS(FACTURA, MS_PROVEEDOR_ID, FECHA_FACTURA, MS_ARTICULO_ID, MONTO, PAGADO, CANTIDAD, ANCHO, LARGO, TIPO) VALUES('".$_POST['factura']."',".$_POST['proveedor'].",'".$_POST['fecha_factura']."',".$_POST['articulo'].",".$_POST['costo'].",0,".$_POST['unidades'].", ".$_POST['ancho'].", ".$_POST['largo'].")";
        
        /*if(ibase_query($conection2->getConexion(), $query1))
		{
			
			$query = "select
			max(ID_PAGO) AS ID_PAGO
			FROM MS_PAGOS";
	    */    
	        /*$result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
	       	
			while ($row = ibase_fetch_object ($result, IBASE_TEXT)){*/
				$id_pago = $row->ID_PAGO;
				
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
							"LARGO",
							"MS_PAGOS_ID");
					
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
							$_POST['largo'],
							0);
				for($i = 0; $i<$loops; $i++)
				$json = $conection2->insert_table($campos, "MS_INVENTARIO", $valores);
			
				
			
			//}
		//}

		
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
						"DESCRIPCION",
						"CUENTA");
				
		$valores = array("'".strtoupper($_POST['nombre_proveedor'])."'", 
						"'".strtoupper($_POST['direccion'])."'", 
						"'".strtoupper($_POST['telefono'])."'", 
						$_POST['condicion'], 
						"'".strtoupper($_POST['contacto'])."'", 
						"'".strtoupper($_POST['descripcion'])."'",
						"'".strtoupper($_POST['cuenta'])."'");
		
		if($_POST['id'] > 0)
		{
			$json = $conection2->update_table($campos, "MS_PROVEEDOR", $valores, " ID=".$_POST['id']);
		}else
		{
			$json = $conection2->insert_table($campos, "MS_PROVEEDOR", $valores);
		}

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST['accion'] == "carga_proveedor")
	{

		$conection2 = new conexion_nexos(2);
		$campos = array("NOMBRE", 
						"DIRECCION", 
						"TELEFONO", 
						"CONDICION_PAGO", 
						"CONTACTO", 
						"DESCRIPCION",
						"CUENTA",
						"ID");
				
		$condicionales = " and id=".$_POST['id'];
		$json = $conection2->select_table($campos, "MS_PROVEEDOR", array(), $condicionales, array(), 0);

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
						"UNITARIO",
						"ANCHO",
						"LARGO",
						"UNIDAD_VENTA",
						"PAQUETE",
						"UNIDAD_COMPRA"
						);

		$_POST['ancho'] = ($_POST['unitario'] == 1)? 0: $_POST['ancho'];		
		$valores = array("'".strtoupper($_POST['insumo'])."'", 
						0,
						$_POST['familia'],
						$_POST['minimo'],
						"'".date("Y-m-d H:i:s")."'", 
						$_POST['unitario'],
						($_POST['unitario']==0)? $_POST['ancho']:0,
						($_POST['unitario']==0)? $_POST['largo']:0,
						"'".$_POST['u_venta']."'",
						($_POST['unitario']==1)?$_POST['u_paquete']:0,
						"'".$_POST['u_compra']."'"
						);
		
		if($_POST['id'] > 0)
		{
			$json = $conection2->update_table($campos, "MS_ARTICULOS", $valores, " ID=".$_POST['id']);
		}else
		{
			$json = $conection2->insert_table($campos, "MS_ARTICULOS", $valores);
		}
		
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
					TELEFONO,
					CUENTA

FROM MS_PROVEEDOR WHERE DELETED IS NULL ORDER BY NOMBRE";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 			= utf8_encode($row->ID);
			$json[$indice]['NOMBRE'] 			= utf8_encode($row->NOMBRE);
			$json[$indice]['CONTACTO']			= utf8_encode($row->CONTACTO);
			$json[$indice]['TELEFONO']			= utf8_encode($row->TELEFONO);
			$json[$indice]['CUENTA']			= utf8_encode($row->CUENTA);
			$json[$indice]['CONDICION_PAGO']	= $row->CONDICION_PAGO;

		}
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;	
	}

	if($_POST["accion"] == "eliminar_insumo_borrador")
	{
		$conection2 = new conexion_nexos(2);
		if($_POST['unitario'] == 1)
			$query = "DELETE FROM MS_INVENTARIO WHERE ID_INVENTARIO=".$_POST['id'];
		else
			$query = "DELETE FROM MS_INVENTARIO WHERE MS_ARTICULO_ID=".$_POST['articulo_id']." and ancho=".$_POST['ancho']." and largo=".$_POST['largo'];
		
			
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        
		$obj = (object) array();
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
(SUM(PRECIO_COMPRA)) AS PRECIO_COMPRA,
mi.ANCHO,
mi.LARGO,
mi.MS_ARTICULO_ID,
ma.UNITARIO,
max(mi.id_inventario) as ID

FROM ms_inventario mi,
MS_PROVEEDOR mp,
ms_articulos ma
WHERE mi.TIPO='B'
AND mi.ms_proveedor_id=mp.ID
and mi.ms_articulo_id=ma.id
group BY mi.FACTURA_COMPRA, ma.nombre_articulo, mi.precio_unitario, mi.ANCHO, mi.LARGO, mi.MS_ARTICULO_ID, ma.UNITARIO";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['FACTURA_COMPRA'] 			= $row->FACTURA_COMPRA;
			$json[$indice]['NOMBRE_ARTICULO']			= utf8_encode($row->NOMBRE_ARTICULO);
			$json[$indice]['REGISTROS']					= $row->REGISTROS;
			$json[$indice]['UNIDADES']					= $row->CANTIDAD;
			$json[$indice]['PRECIO_UNITARIO']			= $row->PRECIO_UNITARIO;
			$json[$indice]['PRECIO_COMPRA']				= $row->PRECIO_COMPRA;
			$json[$indice]['ANCHO']						= $row->ANCHO;
			$json[$indice]['LARGO']						= $row->LARGO;
			$json[$indice]['MS_ARTICULO_ID']			= $row->MS_ARTICULO_ID;
			$json[$indice]['UNITARIO']					= $row->UNITARIO;
			$json[$indice]['ID']						= $row->ID;
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

	if($_POST["accion"] == "activar_articulo")
	{
		$conection2 = new conexion_nexos(2);
		
		$query1 = "UPDATE
		MS_INVENTARIO
		set activo=1
		where ID_INVENTARIO=".$_POST['id_inventario']."
		and cantidad_restante>0
		and ESTATUS_INVENTARIO = 0 
		and cantidad=cantidad_restante
		rows 1";

        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
       	$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "baja_articulo_parcial")
	{
		$conection2 = new conexion_nexos(2);
		
		$query1 = "select
		ID_INVENTARIO,
		CANTIDAD_RESTANTE,
		PRECIO_UNITARIO,
		PRECIO_COMPRA
		FROM
		MS_INVENTARIO
		where MS_ARTICULO_ID=".$_POST['id']."
		and cantidad_restante>0
		and ESTATUS_INVENTARIO = 0 
		ORDER BY FECHA_FACTURA ASC";

        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
       	
       	$cantidad_restante = 0;
       	$precio_unitario = 0;
       	$restante = ($_POST['cantidad_entera']);

		while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT) ){
			if($restante > 0)
			{
				if($restante > $row1->CANTIDAD_RESTANTE)
				{
					$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
					$valores_movimiento = array(2, 0, 0, 0,0, $row1->CANTIDAD_RESTANTE,  $row1->PRECIO_UNITARIO, ($row1->CANTIDAD_RESTANTE*  $row1->PRECIO_UNITARIO), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
					$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
					
					$campos = array("ESTATUS_INVENTARIO", "CANTIDAD_RESTANTE");
					$valores = array(1, 0);
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
					$restante -= $row1->CANTIDAD_RESTANTE;
						
				}else
				{
					$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
					$valores_movimiento = array(2, 0, 0, 0,0, ($row1->CANTIDAD_RESTANTE - $restante),  $row1->PRECIO_UNITARIO, (($row1->CANTIDAD_RESTANTE - $restante) * $row1->PRECIO_UNITARIO), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
					$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
					$campos = array("CANTIDAD_RESTANTE");
					$valores = array(($row1->CANTIDAD_RESTANTE - $restante));
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
					
					$restante = 0;
				}
				
			}
		}
		
		
        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "baja_articulo")
	{
		$conection2 = new conexion_nexos(2);
		
		$query = "select
		FIRST 1
		UNITARIO,
		PAQUETE
		FROM
		MS_ARTICULOS
		where ID=".$_POST['id']."";

        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
       	
       	$llave = 0;
       	$paquete = 0;
       	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$llave = $row->UNITARIO;
			$paquete = $row->PAQUETE;
		}

		if($llave == 0)
		{
			$query1 = "select
			ID_INVENTARIO,
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO,
			PRECIO_COMPRA
			FROM
			MS_INVENTARIO
			where MS_ARTICULO_ID=".$_POST['id']."
			and activo=0 and cantidad_restante>0
			and ESTATUS_INVENTARIO = 0 
			ORDER BY FECHA_FACTURA ASC";

	        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
	       	
	       	$cantidad_restante = 0;
	       	$precio_unitario = 0;
	       	$restante = $_POST['cantidad_entera'];
			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT)){
				if($restante > 0)
				{
					$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
					$valores_movimiento = array(2, 0, 0, 0,0, $row1->CANTIDAD_RESTANTE,  $row1->PRECIO_UNITARIO, $row1->PRECIO_COMPRA, "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
					$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
					

					$campos = array("ESTATUS_INVENTARIO", "CANTIDAD_RESTANTE");
					$valores = array(1, 0);
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
					$restante--;
				}
			}
		}else if($llave == 1)
		{
			$query1 = "select
			ID_INVENTARIO,
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO,
			PRECIO_COMPRA
			FROM
			MS_INVENTARIO
			where MS_ARTICULO_ID=".$_POST['id']."
			and cantidad_restante>0
			and ESTATUS_INVENTARIO = 0 
			ORDER BY FECHA_FACTURA ASC";

	        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
	       	
	       	$cantidad_restante = 0;
	       	$precio_unitario = 0;
	       	$restante = ($paquete * $_POST['cantidad_entera']);

			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT) ){
				if($restante > 0)
				{
					if($restante > $row1->CANTIDAD_RESTANTE)
					{
						$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
						$valores_movimiento = array(2, 0, 0, 0,0, $row1->CANTIDAD_RESTANTE,  $row1->PRECIO_UNITARIO, ($row1->CANTIDAD_RESTANTE*  $row1->PRECIO_UNITARIO), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
						$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
						
						$campos = array("ESTATUS_INVENTARIO", "CANTIDAD_RESTANTE");
						$valores = array(1, 0);
						$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
						
						$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
						$restante -= $row1->CANTIDAD_RESTANTE;
							
					}else
					{
						$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
						$valores_movimiento = array(2, 0, 0, 0,0, ($row1->CANTIDAD_RESTANTE - $restante),  $row1->PRECIO_UNITARIO, (($row1->CANTIDAD_RESTANTE - $restante) * $row1->PRECIO_UNITARIO), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
						$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
						$campos = array("CANTIDAD_RESTANTE");
						$valores = array(($row1->CANTIDAD_RESTANTE - $restante));
						$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
						$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
						
						$restante = 0;
					}
					
				}
			}
		}
		
        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "baja_parcial")
	{
		$conection2 = new conexion_nexos(2);
		
		$llave = $_POST['unitario'];

		if($llave == 0)
		{
			$query1 = "select
			ID_INVENTARIO,
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO,
			PRECIO_COMPRA,
			ANCHO
			FROM
			MS_INVENTARIO
			where ID_INVENTARIO=".$_POST['id']."
			and ANCHO=".$_POST['ancho']."
			and LARGO=".$_POST['largo']."
			and cantidad_restante>0
			and ESTATUS_INVENTARIO = 0";

	        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
	       	
	       	
			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT) ){

				$baja = ($row1->ANCHO * $_POST['cantidad']);
				
				$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
				$valores_movimiento = array(2, 0, 0, 0,0, $baja,  $row1->PRECIO_UNITARIO, ($baja * $row1->PRECIO_UNITARIO), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
						$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
				$restante = ($row1->CANTIDAD_RESTANTE - $baja);
				if($restante  <= 0)
				{
					$campos = array("CANTIDAD_RESTANTE", "ESTATUS_INVENTARIO");
					$valores = array(0, 1);
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
				}else
				{

					$campos = array("CANTIDAD_RESTANTE");
					$valores = array(($row1->CANTIDAD_RESTANTE - $baja));
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
				}
			}
		}else if($llave == 1)
		{
			$query1 = "select
			ID_INVENTARIO,
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO,
			PRECIO_COMPRA,
			ANCHO
			FROM
			MS_INVENTARIO
			where MS_ARTICULO_ID=".$_POST['id']."
			and cantidad_restante>0
			and ESTATUS_INVENTARIO = 0
			order by fecha_actualizacion asc";

	        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
	       	
	       	$baja = ($_POST['cantidad']);
				
			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT) ){

				
				$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
				$valores_movimiento = array(2, 0, 0, 0,0, $baja,  $row1->PRECIO_UNITARIO, ($baja * $row1->PRECIO_UNITARIO), "'BAJA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
				$restante = ($row1->CANTIDAD_RESTANTE - $baja);
				
				if($baja > 0)
				{
					if($restante  <= 0)
					{
						$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);				
						$campos = array("CANTIDAD_RESTANTE", "ESTATUS_INVENTARIO");
						$valores = array(0, 1);
						$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
						$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
						$baja -= $row1->CANTIDAD_RESTANTE;
					}else
					{
						$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);				
						$campos = array("CANTIDAD_RESTANTE");
						$valores = array(($row1->CANTIDAD_RESTANTE - $baja));
						$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
						$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
						$baja = 0;
					}
				}
			}
		}

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "suma_parcial")
	{
		$conection2 = new conexion_nexos(2);
		
		$llave = $_POST['unitario'];

		if($llave == 0)
		{
			$query1 = "select
			ID_INVENTARIO,
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO,
			PRECIO_COMPRA,
			ANCHO
			FROM
			MS_INVENTARIO
			where ID_INVENTARIO=".$_POST['id']."
			and ANCHO=".$_POST['ancho']."
			and LARGO=".$_POST['largo']."
			and cantidad_restante>0
			and ESTATUS_INVENTARIO = 0
			and ACTIVO=1";

	        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
	       	
	       	$bandera = 0;
			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT) ){
				if($bandera == 0)
				{
					$alta = ($row1->ANCHO * $_POST['cantidad']);
					
					$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
					$valores_movimiento = array(2, 0, 0, 0,0, "-".$alta,  $row1->PRECIO_UNITARIO, ($alta * $row1->PRECIO_UNITARIO), "'ALTA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
							$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);
					$restante = ($row1->CANTIDAD_RESTANTE + $alta);
					
					$campos = array("CANTIDAD_RESTANTE");
					$valores = array($restante);
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
					$bandera++;
				}
				
			}
		}else if($llave == 1)
		{
			$query1 = "select
			ID_INVENTARIO,
			CANTIDAD_RESTANTE,
			PRECIO_UNITARIO,
			PRECIO_COMPRA,
			ANCHO
			FROM
			MS_INVENTARIO
			where MS_ARTICULO_ID=".$_POST['id']."
			and cantidad_restante>0
			and ESTATUS_INVENTARIO = 0
			order by fecha_actualizacion asc";

	        $result1 = ibase_query($conection2->getConexion(), $query1) or die(ibase_errmsg());
	       	
	       	$alta = ($_POST['cantidad']);
			$bandera = 0;	
			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT) ){

				if($bandera == 0)
				{
					$campos_movimiento = array("EMPRESA", "ID_VENTA", "ID_VENTA_DET", "ARTICULO_ID", "CANTIDAD_VENDIDO", "CANTIDAD_BAJA", "PRECIO_UNITARIO_COMPRA", "PRECIO_TOTAL", "DESCRIPCION", "FECHA_REGISTRO", "MS_INVENTARIO_ID");		
					$valores_movimiento = array(2, 0, 0, 0,0, "-".$alta,  $row1->PRECIO_UNITARIO, ($alta * $row1->PRECIO_UNITARIO), "'ALTA MANUAL'", "'".date("Y-m-d H:i:s")."'", $row1->ID_INVENTARIO );
					$restante = ($row1->CANTIDAD_RESTANTE + $alta);
					
					$conection2->insert_table($campos_movimiento, "MS_MOVIMIENTO", $valores_movimiento);				
					$campos = array("CANTIDAD_RESTANTE");
					$valores = array($restante);
					$id = " ID_INVENTARIO =".$row1->ID_INVENTARIO;
					$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);	
					$bandera++;
				}
			}
		}

		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "eliminar_proveedor")
	{
		
		$conection2 = new conexion_nexos(2);
		$campos = array("DELETED");
		$valores = array("'".date("Y-m-d H:i:s")."'");
		$id = " ID = ".$_POST['id'];
		
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
	if($_POST["accion"] == "guarda_folio")
	{
		$conection2 = new conexion_nexos(2);
		$campos = array("FACTURA");
		$valores = array("'".$_POST['factura']."'");
		$id = " FACTURA= '".$_POST['factura_id']."' and MS_PROVEEDOR_ID= '".$_POST['proveedor_id']."'";
		
		$json = $conection2->update_table($campos, "MS_PAGOS", $valores, $id);

		$campos = array("FACTURA_COMPRA");
		$valores = array("'".$_POST['factura']."'");
		$id = " FACTURA_COMPRA= '".$_POST['factura_id']."' and MS_PROVEEDOR_ID= '".$_POST['proveedor_id']."'";
		
		$json = $conection2->update_table($campos, "MS_INVENTARIO", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
	}

	if($_POST["accion"] == "verficar_factura")
	{
		$conection2 = new conexion_nexos(2);
		
		$query = "SELECT 
		mp.ID_PAGO,
		mp.FACTURA,
		mp.MS_PROVEEDOR_ID,
		mp.FECHA_FACTURA,
		mp.MONTO,
		mp.PAGADO,
		mp.MS_ARTICULO_ID,
		mp.CANTIDAD,
		mp.ANCHO,
		mp.LARGO,
		mp.DESCRIPCION,
		ma.NOMBRE_ARTICULO || ' ' || mf.descripcion as NOMBRE_ARTICULO
		 from MS_PAGOS mp,
		 MS_ARTICULOS ma,
		 MS_FAMILIA mf WHERE 
		 mp.ms_articulo_id= ma.id
		 and mf.id=ma.ms_familia_id and
		 FACTURA='".$_POST['factura']."' AND MS_PROVEEDOR_ID=".$_POST['proveedor'];
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 			= $row->ID_PAGO;
			$json[$indice]['FACTURA'] 			= $row->FACTURA;
			$json[$indice]['MS_PROVEEDOR_ID'] 			= $row->MS_PROVEEDOR_ID;
			$json[$indice]['FECHA_FACTURA'] 			= $row->FECHA_FACTURA;
			$json[$indice]['MONTO'] 			= $row->MONTO;
			$json[$indice]['PAGADO'] 			= $row->PAGADO;
			$json[$indice]['MS_ARTICULO_ID'] 	= $row->MS_ARTICULO_ID;
			$json[$indice]['CANTIDAD'] 			= $row->CANTIDAD;
			$json[$indice]['ANCHO'] 			= $row->ANCHO;
			$json[$indice]['LARGO'] 			= $row->LARGO;
			$json[$indice]['DESCRIPCION'] 		= utf8_encode($row->DESCRIPCION);
			$json[$indice]['NOMBRE_ARTICULO'] 	= utf8_encode($row->NOMBRE_ARTICULO);
			
		}

		if(count($json) > 0)
			$obj = (object) array("articulos"=>$json, "numero"=>1);
		else
			$obj = (object) array("articulos"=>$json, "numero"=>0);
		
        echo json_encode($obj);
        $conection2 = null;	
	}