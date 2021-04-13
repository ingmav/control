<?php
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	//$conection = new conexion_nexos();
	//$conexion = $conection->conexion_nexos($_POST['empresa']);
	$conection = new conexion_nexos($_SESSION['empresa']);
	
	if($_POST["accion"] == "index")
	{
		$campos = array("DOCTOS_VE.DOCTO_VE_ID", "DOCTOS_VE.FOLIO", "DOCTOS_VE.FECHA", "CLIENTES.NOMBRE", "DOCTOS_VE.DESCRIPCION", "DOCTOS_VE.TIPO_DOCTO", "DOCTOS_VE.ESTATUS");
		
		$join = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID");
		
		$condicionales = " AND DOCTOS_VE.FECHA > '2017-04-17' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C' AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM TABLEROPRODUCCION WHERE CERRAR_SELECCION=1) AND DOCTOS_VE.DOCTO_VE_ID  NOT IN (SELECT DOCTOS_VE_LIGAS.DOCTO_VE_DEST_ID FROM DOCTOS_VE_LIGAS, DOCTOS_VE WHERE DOCTOS_VE_LIGAS.DOCTO_VE_FTE_ID=DOCTOS_VE.DOCTO_VE_ID AND DOCTOS_VE.TIPO_DOCTO='R')
				 ";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper(utf8_encode($_POST['client']))."%'";
		}

		$order = array("DOCTOS_VE.FECHA, DOCTOS_VE.FOLIO ASC");
		

		$json = $conection->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0);

		$conection 	= null;
		$conexion 	= null;
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "index_mostrador")
	{
		$conexion = $conection->conexion_nexos($_SESSION['empresa']);

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
            //$condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (1849,1954,6346,2048)";
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
		$conexion = null;
		//print_r($json);	
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "counter")
	{
	
		/*$join = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID");
		
		$condicionales = " AND FECHA > '2015-01-11'  AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C'  AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM CERRADOPRODUCCION)";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}
		
		$json = $conection->counter("DOCTOS_VE", $join, $condicionales, 0);
		
		$obj = (object) $json;
		echo json_encode($obj);*/
	}

	if($_POST["accion"] == "countProcess")
	{
		$join = array();
		
		$condicionales = " AND TABLEROPRODUCCION.DOCTO_VE_ID=".$_POST['id'];
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROPRODUCCION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}
?>