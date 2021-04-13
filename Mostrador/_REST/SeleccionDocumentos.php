<?php
	include("../../clases/conexion.php");
	session_start();
	date_default_timezone_set('America/Mexico_City');

	if($_POST["accion"] == "index")
	{
		
		$conexion = new conexion_nexos($_SESSION['empresa']);
		
		$query = "select
		DOCTOS_PV.DOCTO_PV_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_PV.DESCRIPCION
		from DOCTOS_PV
       	LEFT JOIN PRODUCCIONPV ON PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND (PRODUCCIONPV.CERRAR_SELECCION IS NULL OR PRODUCCIONPV.CERRAR_SELECCION=0)";
        
        $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json_mostrador);
			
			$json_mostrador[$indice]['ID'] 				= $row->DOCTO_PV_ID;
			$json_mostrador[$indice]['FOLIO'] 			= "A".(int)substr($row->FOLIO,1);
			$json_mostrador[$indice]['FECHA'] 			= $row->FECHA;
			$json_mostrador[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json_mostrador[$indice]['DESCRIPCION'] 	= utf8_encode($row->DESCRIPCION);
			$json_mostrador[$indice]['EMPRESA'] 		= 3;
		}

		$index = 0;
		$json_selected = array();
		while($index < count($json_mostrador))
		{
            $campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
            				"CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json_mostrador[$index]['ID'];
            $condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149, 2143) 
            					 AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12')";
			
			//echo $condicionales;
            $json2 = $conexion->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

            if(count($json2) > 0)
            {

            	$indice_selected = count($json);
            	$json[$indice_selected] = $json_mostrador[$index];	
	            $json[$indice_selected]['MATERIALES'] = $json2;	
	          
			}
			//Fin observaciones
            $index++;
		}

		$contador = count($json);
		for($i = 0; $i < $contador; $i++)
		{
			$j = ($i + 1);
			for(; $j < $contador; $j++)
			{
				if($json[$i]['FECHA'] > $json[$j]['FECHA'])
				{
					
					$arrayAuxiliar[0] = $json[$i];
					$json[$i] = $json[$j];	
					$json[$j] = $arrayAuxiliar[0];
				}
			}
		}
		$conexion = null;
			
		$obj = (object) $json;
		echo json_encode($obj);
	}
	if($_POST["accion"] == "index_mostrador")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		

	    $query = "select
		DOCTOS_PV.DOCTO_PV_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_PV.DESCRIPCION
		from DOCTOS_PV
       	LEFT JOIN PRODUCCION_DG ON PRODUCCION_DG.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND (PRODUCCION_DG.CERRAR_SELECCION IS NULL OR PRODUCCION_DG.CERRAR_SELECCION=0)";
        
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json_mostrador);
			$json_mostrador[$indice]['ID'] 				= $row->DOCTO_PV_ID;
			$json_mostrador[$indice]['FOLIO'] 			= "A".(int)substr($row->FOLIO,1);
			$json_mostrador[$indice]['FECHA'] 			= $row->FECHA;
			$json_mostrador[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json_mostrador[$indice]['DESCRIPCION'] 	= utf8_encode($row->DESCRIPCION);
			$json_mostrador[$indice]['EMPRESA'] 		= 3;
		}

		$index = 0;
		$json_selected = array();
		while($index < count($json_mostrador))
		{
            $campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
            				"CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json_mostrador[$index]['ID'];
            $condicionales2 .= " AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149) 
            					 OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05'))";
            
			
			//echo $condicionales;
            $json2 = $conection->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

            if(count($json2) > 0)
            {

            	$indice_selected = count($json);
            	$json[$indice_selected] = $json_mostrador[$index];	
	            $json[$indice_selected]['MATERIALES'] = $json2;	
	          
			}
			//Fin observaciones
            $index++;
		}
		$contador = count($json);
		for($i = 0; $i < $contador; $i++)
		{
			$j = ($i + 1);
			for(; $j < $contador; $j++)
			{
				if($json[$i]['FECHA'] > $json[$j]['FECHA'])
				{
					
					$arrayAuxiliar[0] = $json[$i];
					$json[$i] = $json[$j];	
					$json[$j] = $arrayAuxiliar[0];
				}
			}
		}
		$conexion = null;
		//print_r($json);	
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	
?>