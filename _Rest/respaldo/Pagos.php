<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{
		if($_POST['proveedor']!=0)
		{
			$consulta_filtro.=" and MP.MS_PROVEEDOR_ID=".$_POST['proveedor'];
		}
		$arreglo_pagos = ver_pagos_proveedor($consulta_filtro);
		$obj = (object) $arreglo_pagos;
        echo json_encode($obj);
	}

	function ver_pagos_proveedor($consulta_filtro = "")
	{
	    $conexion = new conexion_nexos($_SESSION['empresa']);

	    $query =  "select mp.factura, mpr.nombre, mp.fecha_factura, (sum(mp.monto) - sum(mp.descuento)) as monto, sum(mp.descuento) as descuento, sum(mp.monto) as monto_total, mpr.condicion_pago, mp.ms_proveedor_id, mp.descripcion  
	    from ms_pagos mp,
	    ms_proveedor mpr
	    where 
	    mp.ms_proveedor_id=mpr.id
	    and mp.pagado=0
	    ".$consulta_filtro."
	    group by mp.factura, mpr.nombre, mp.fecha_factura, mpr.condicion_pago, mp.ms_proveedor_id, mp.descripcion  ";

	    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

	    $arreglo1 = array();

	    $total = 0;
	    $total_vencido = 0;
	    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
	        $index = count($arreglo1);
	        $arreglo1[$index]['FACTURA'] 			=  utf8_decode($row->FACTURA);
	        $arreglo1[$index]['FECHA'] 				=  $row->FECHA_FACTURA;

	        $arreglo1[$index]['PROVEEDOR'] 			= utf8_decode($row->NOMBRE);
	        $arreglo1[$index]['DESCRIPCION'] 			= utf8_decode($row->DESCRIPCION);
	        $arreglo1[$index]['PRECIO'] 			= number_format($row->MONTO,2);
	        $arreglo1[$index]['PRECIO_TOTAL'] 			= number_format($row->MONTO_TOTAL,2);
	        $arreglo1[$index]['DESCUENTO'] 			= number_format($row->DESCUENTO,2);
	        $arreglo1[$index]['CONDICION'] 			= $row->CONDICION_PAGO;
	        $arreglo1[$index]['MS_PROVEEDOR_ID'] 	= $row->MS_PROVEEDOR_ID;
	        $arreglo1[$index]['VENCIDO'] 			= 0;

	        $total += $row->MONTO;

	        $date1=date_create($arreglo1[$index]['FECHA'] );
			$date1->modify("+".$row->CONDICION_PAGO." day");

			$arreglo1[$index]['FECHA_PAGO'] = date_format($date1, 'Y-m-d');


			if($arreglo1[$index]['FECHA_PAGO'] < date('Y-m-d')){
				$arreglo1[$index]['VENCIDO'] = 1;

				$total_vencido += $row->MONTO;
				
			}
			
	    }
	    $arreglo_aux = array(0);
	    
	    for($i=0; $i < count($arreglo1); $i++)
	    	for($j=$i+1; $j < count($arreglo1); $j++)
	    	{
	    		if($arreglo1[$i]['FECHA_PAGO'] > $arreglo1[$j]['FECHA_PAGO'])
	    		{
	    			$arreglo_aux[0] = $arreglo1[$i];
	    			$arreglo1[$i] = $arreglo1[$j];
	    			$arreglo1[$j] = $arreglo_aux[0];
	    		}
	    	}


	    $conexion = null;
	    return array("PAGOS" => $arreglo1, "TOTAL"=> number_format($total,2), "TOTAL_VENCIDO"=> number_format($total_vencido,2));
	        
	}

	if($_POST["accion"] == "pagar")
	{
		$conexion = new conexion_nexos($_SESSION['empresa']);
		$campos = array("PAGADO");
		$valores = array(1);
		$id = " FACTURA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor'];
		
		$json = $conexion->update_table($campos, "MS_PAGOS", $valores, $id);

	    $obj = (object) array("PAGADO");
		echo json_encode($obj);
	    $conexion = null;
	}

	if($_POST["accion"] == "guardar_factura")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);

		
		$cantidad = 0;
		
		
		$query1 = "INSERT INTO MS_PAGOS(FACTURA, MS_PROVEEDOR_ID, FECHA_FACTURA, MS_ARTICULO_ID, MONTO, PAGADO, CANTIDAD, ANCHO, LARGO, DESCRIPCION) VALUES('".$_POST['factura']."',37,'".$_POST['fecha_factura']."',0,".$_POST['monto'].",0,1, 0,0, '".$_POST['descripcion']."')";
        
        $json = array();
        if(ibase_query($conection2->getConexion(), $query1))
		{
			$json = array("correcto");
		}

		
		
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}
	if($_POST["accion"] == "carga_monto")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);

		
		$cantidad = 0;
		
		
		$query1 = "select first 1 descuento from ms_pagos where ms_proveedor_id=".$_POST['id_proveedor']." and factura='".$_POST['factura']."' order by id_pago asc";
        
        $json = array();
        if($result = ibase_query($conection2->getConexion(), $query1))
		{

	    	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$json = array($row->DESCUENTO);
			}
		}
		
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}

	if($_POST["accion"] == "guardar_descuento")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$query1 = "update ms_pagos set descuento=".$_POST['monto']." where ms_proveedor_id=".$_POST['id_proveedor']." and factura='".$_POST['factura']."' order by id_pago asc rows 1";
        
        $json = array();
        if($result = ibase_query($conection2->getConexion(), $query1))
		{
			$json = array("correcto");
		}
		
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}

	if($_POST["accion"] == "eliminar")
	{
		$conexion = new conexion_nexos($_SESSION['empresa']);
		
		$condicionales = " and FACTURA_COMPRA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor'];
		//$count = $conexion->counter("MS_INVENTARIO", array(), $condicionales, 0);
		//if()
		//$json = $conexion->delete_of_table("MS_PAGOS", "", array(), " FACTURA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor']);

		$query1 = "select count(*) as CONTADOR from MS_INVENTARIO WHERE FACTURA_COMPRA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor']." group by FACTURA_COMPRA, MS_PROVEEDOR_ID, FECHA_FACTURA";
        
        $json = array();
        $contador = 0;
        if($result = ibase_query($conexion->getConexion(), $query1))
		{
			while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$contador++	;
				
			}
		}

		$query1 = "select CANTIDAD, CANTIDAD_RESTANTE from MS_INVENTARIO WHERE FACTURA_COMPRA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor'];
        $diferencia = 0;
        if($result = ibase_query($conexion->getConexion(), $query1))
		{
			while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$diferencia += $row->CANTIDAD - $row->CANTIDAD_RESTANTE;
			}
		}		


		if($contador > 1)
		{
			header('HTTP/1.0 500 SE HA DETECTADO DOS FACTURAS, CON EL MISMO PROVEERDOR CON DIFERENTE FECHA');
			return 0;
		}else
		{
			if($diferencia != 0)
			{
				header('HTTP/1.0 500 SE ENCONTRARON MOVIMIENTOS EN EL INVENTARIO DE LOS INSUMOS A BORRAR, VERIFIQUE LOS DATOS');
				return 0;
			}else
			{
				$json = $conexion->delete_of_table("MS_PAGOS", "", array(), " FACTURA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor']);

				$json = $conexion->delete_of_table("MS_INVENTARIO", "", array(), " FACTURA_COMPRA='".$_POST['factura']."' and ms_proveedor_id=".$_POST['proveedor']);
			}
		}
		$obj = (object) $contador;
		echo json_encode($obj);
	    $conexion = null;
	}

	if($_POST["accion"] == "informacion")
	{
		$conection2 = new conexion_nexos($_SESSION['empresa']);
		
		$cantidad = 0;
		
		$query1 = "select mp.FACTURA, mp.FECHA_FACTURA, mp.MONTO, mp.CANTIDAD, mp.ANCHO, mp.LARGO, mp.DESCUENTO, mp.DESCRIPCION, MS_PROVEEDOR_ID, mpr.NOMBRE, ma.NOMBRE_ARTICULO, mf.descripcion as familia, mp.DESCRIPCION 
		from ms_pagos mp
		left join ms_articulos ma on mp.ms_articulo_id = ma.id
		left join ms_familia mf on ma.ms_familia_id = mf.id,
		ms_proveedor mpr
		where 
		mp.ms_proveedor_id = mpr.id
		and mp.ms_proveedor_id=".$_POST['proveedor']." and mp.factura='".$_POST['factura']."' order by id_pago asc";
        
        $json = array();
        if($result = ibase_query($conection2->getConexion(), $query1))
		{

	    	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$index = count($json);
				$json[$index]['FACTURA'] 	= $row->FACTURA;
				$json[$index]['FECHA'] 		= $row->FECHA_FACTURA;
				$json[$index]['MONTO'] 		= $row->MONTO;
				$json[$index]['CANTIDAD'] 	= $row->CANTIDAD;
				$json[$index]['ANCHO'] 		= $row->ANCHO;
				$json[$index]['LARGO'] 		= $row->LARGO;
				$json[$index]['DESCUENTO'] 	= $row->DESCUENTO;
				$json[$index]['PROVEEDOR'] 	= $row->NOMBRE;
				$json[$index]['ARTICULO'] 	= $row->NOMBRE_ARTICULO;
				$json[$index]['FAMILIA'] 	= $row->FAMILIA;
				$json[$index]['DESCRIPCION'] 	= $row->DESCRIPCION;
				$json[$index]['MS_PROVEEDOR_ID'] 	= $row->MS_PROVEEDOR_ID;
			}
		}
		
		$obj = (object) $json;
        echo json_encode($obj);
        $conection2 = null;
		
	}