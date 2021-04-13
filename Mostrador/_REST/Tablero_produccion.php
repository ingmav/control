<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../../clases/conexion.php");
	include("../../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	if($_POST["accion"] == "index")
	{

		$conection = new conexion_nexos($_SESSION['empresa']);

		if($_POST['estatus'] == 1)
		{
			$filtro_gf = " AND ((PRODUCCIONPV.DISENO_GF + PRODUCCIONPV.IMPRESION_GF + PRODUCCIONPV.PREPARACION_GF + PRODUCCIONPV.ENTREGA_GF + PRODUCCIONPV.INSTALACION_GF) / 2) = 0";
		}else if($_POST['estatus'] == 2)
		{
			$filtro_gf = " AND ((PRODUCCIONPV.GF_DISENO + PRODUCCIONPV.GF_IMPRESION + PRODUCCIONPV.GF_PREPARACION + PRODUCCIONPV.GF_ENTREGA + PRODUCCIONPV.GF_INSTALACION) - ((PRODUCCIONPV.DISENO_GF + PRODUCCIONPV.IMPRESION_GF + PRODUCCIONPV.PREPARACION_GF + PRODUCCIONPV.ENTREGA_GF + PRODUCCIONPV.INSTALACION_GF) / 2)) = 0";
		}

		$query = "select
		PRODUCCIONPV.DOCTO_PV_ID,
		PRODUCCIONPV.DOCTO_PV_DET_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		PRODUCCIONPV.DESCRIPCION,
		PRODUCCIONPV.GF_DISENO,
		PRODUCCIONPV.DISENO_GF,
		PRODUCCIONPV.GF_IMPRESION,
		PRODUCCIONPV.IMPRESION_GF,
		PRODUCCIONPV.GF_PREPARACION,
		PRODUCCIONPV.PREPARACION_GF,
		PRODUCCIONPV.GF_ENTREGA,
		PRODUCCIONPV.ENTREGA_GF,
		PRODUCCIONPV.GF_INSTALACION,
		PRODUCCIONPV.INSTALACION_GF,
		PRODUCCIONPV.NOTAS_PROCESO,
		((PRODUCCIONPV.GF_DISENO + PRODUCCIONPV.GF_IMPRESION + PRODUCCIONPV.GF_PREPARACION + PRODUCCIONPV.GF_ENTREGA + PRODUCCIONPV.GF_INSTALACION) - ((PRODUCCIONPV.DISENO_GF + PRODUCCIONPV.IMPRESION_GF + PRODUCCIONPV.PREPARACION_GF + PRODUCCIONPV.ENTREGA_GF + PRODUCCIONPV.INSTALACION_GF) / 2)) AS SUMA_PROCESOS
		from DOCTOS_PV
       	INNER JOIN PRODUCCIONPV ON PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND (PRODUCCIONPV.FINALIZAR_PROCESO=0) ".$filtro_gf." order by DOCTOS_PV.FOLIO, DOCTOS_PV.FECHA";
        
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json_mostrador);
			$json_mostrador[$indice]['ID'] 				= $row->DOCTO_PV_ID;
			$json_mostrador[$indice]['ID_DET'] 			= "1_".$row->DOCTO_PV_DET_ID;
			$json_mostrador[$indice]['FOLIO'] 			= "A".(int)substr($row->FOLIO,1);
			$json_mostrador[$indice]['FECHA'] 			= $row->FECHA;
			$json_mostrador[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json_mostrador[$indice]['DESCRIPCION'] 	= utf8_encode($row->DESCRIPCION);
			$json_mostrador[$indice]['EMPRESA'] 		= 3;
			$json_mostrador[$indice]['GF_DISENO'] 		= $row->GF_DISENO;
			$json_mostrador[$indice]['DISENO_GF'] 		= $row->DISENO_GF;
			$json_mostrador[$indice]['GF_IMPRESION']	= $row->GF_IMPRESION;
			$json_mostrador[$indice]['IMPRESION_GF']	= $row->IMPRESION_GF;
			$json_mostrador[$indice]['GF_PREPARACION']	= $row->GF_PREPARACION;
			$json_mostrador[$indice]['PREPARACION_GF']	= $row->PREPARACION_GF;
			$json_mostrador[$indice]['GF_ENTREGA'] 		= $row->GF_ENTREGA;
			$json_mostrador[$indice]['ENTREGA_GF'] 		= $row->ENTREGA_GF;
			$json_mostrador[$indice]['GF_INSTALACION']	= $row->GF_INSTALACION;
			$json_mostrador[$indice]['INSTALACION_GF']	= $row->INSTALACION_GF;
			$json_mostrador[$indice]['PROCESOS']		= $row->SUMA_PROCESOS;
			$json_mostrador[$indice]['NOTAS_PROCESO']	= $row->NOTAS_PROCESO;


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
		
		if($_POST['estatus'] == 1)
		{
			$filtro_dg = " AND ((PRODUCCION_DG.DISENO_GF + PRODUCCION_DG.IMPRESION_GF + PRODUCCION_DG.PREPARACION_GF + PRODUCCION_DG.ENTREGA_GF + PRODUCCION_DG.INSTALACION_GF) / 2) = 0";
		}else if($_POST['estatus'] == 2)
		{
			$filtro_dg = " AND ((PRODUCCION_DG.GF_DISENO + PRODUCCION_DG.GF_IMPRESION + PRODUCCION_DG.GF_PREPARACION + PRODUCCION_DG.GF_ENTREGA + PRODUCCION_DG.GF_INSTALACION) - ((PRODUCCION_DG.DISENO_GF + PRODUCCION_DG.IMPRESION_GF + PRODUCCION_DG.PREPARACION_GF + PRODUCCION_DG.ENTREGA_GF + PRODUCCION_DG.INSTALACION_GF) / 2)) = 0";
		}

		$query = "select
		PRODUCCION_DG.DOCTO_PV_ID,
		PRODUCCION_DG.DOCTO_PV_DET_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_PV.DESCRIPCION,
		PRODUCCION_DG.GF_DISENO,
		PRODUCCION_DG.DISENO_GF,
		PRODUCCION_DG.GF_IMPRESION,
		PRODUCCION_DG.IMPRESION_GF,
		PRODUCCION_DG.GF_PREPARACION,
		PRODUCCION_DG.PREPARACION_GF,
		PRODUCCION_DG.GF_ENTREGA,
		PRODUCCION_DG.ENTREGA_GF,
		PRODUCCION_DG.GF_INSTALACION,
		PRODUCCION_DG.INSTALACION_GF,
		((PRODUCCION_DG.GF_DISENO + PRODUCCION_DG.GF_IMPRESION + PRODUCCION_DG.GF_PREPARACION + PRODUCCION_DG.GF_ENTREGA + PRODUCCION_DG.GF_INSTALACION) - ((PRODUCCION_DG.DISENO_GF + PRODUCCION_DG.IMPRESION_GF + PRODUCCION_DG.PREPARACION_GF + PRODUCCION_DG.ENTREGA_GF + PRODUCCION_DG.INSTALACION_GF) / 2)) AS SUMA_PROCESOS
		from DOCTOS_PV
       	INNER JOIN PRODUCCION_DG ON PRODUCCION_DG.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND (PRODUCCION_DG.FINALIZAR_PROCESO=0) ".$filtro_dg." order by DOCTOS_PV.FOLIO,DOCTOS_PV.FECHA";
        
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json_mostrador);
			$json_mostrador[$indice]['ID'] 				= $row->DOCTO_PV_ID;
			$json_mostrador[$indice]['ID_DET'] 			= "2_".$row->DOCTO_PV_DET_ID;
			$json_mostrador[$indice]['FOLIO'] 			= "A".(int)substr($row->FOLIO,1);
			$json_mostrador[$indice]['FECHA'] 			= $row->FECHA;
			$json_mostrador[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json_mostrador[$indice]['DESCRIPCION'] 	= utf8_encode($row->DESCRIPCION);
			$json_mostrador[$indice]['EMPRESA'] 		= 3;
			$json_mostrador[$indice]['GF_DISENO'] 		= $row->GF_DISENO;
			$json_mostrador[$indice]['DISENO_GF'] 		= $row->DISENO_GF;
			$json_mostrador[$indice]['GF_IMPRESION']	= $row->GF_IMPRESION;
			$json_mostrador[$indice]['IMPRESION_GF']	= $row->IMPRESION_GF;
			$json_mostrador[$indice]['GF_PREPARACION']	= $row->GF_PREPARACION;
			$json_mostrador[$indice]['PREPARACION_GF']	= $row->PREPARACION_GF;
			$json_mostrador[$indice]['GF_ENTREGA'] 		= $row->GF_ENTREGA;
			$json_mostrador[$indice]['ENTREGA_GF'] 		= $row->ENTREGA_GF;
			$json_mostrador[$indice]['GF_INSTALACION']	= $row->GF_INSTALACION;
			$json_mostrador[$indice]['INSTALACION_GF']	= $row->INSTALACION_GF;
			$json_mostrador[$indice]['PROCESOS']		= $row->SUMA_PROCESOS;


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
            $condicionales2 .= " AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149, 2143) 
            					 OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12'))";
			
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
		$conection = null;
		$obj = (object) $json;
		echo json_encode($obj);
	}

if($_POST["accion"] == "finalizar")
{
	$conection = new conexion_nexos($_SESSION['empresa']);
	$procesos = $_POST['procesos'];
	$arreglo_general_gf = array();
	$arreglo_general_dg = array();
	foreach ($procesos as $key => $value) {
		$arreglo = explode("_",$value);
		
		if($arreglo[0] == 1)
			$arreglo_general_gf[] = $arreglo[1];
		else if($arreglo[0] == 2)
			$arreglo_general_dg[] = $arreglo[1];
	}

	if($_SESSION['IDUSUARIO']== 21 || $_SESSION['IDUSUARIO']== 15 || $_SESSION['IDUSUARIO']== 22  || $_SESSION['IDUSUARIO']== 18 || $_SESSION['IDUSUARIO']== 48)
	{
        
		if(count($arreglo_general_gf) > 0)
		{
			$query = "update PRODUCCIONPV set FINALIZAR_PROCESO=1 where DOCTO_PV_DET_ID in (".implode(",", $arreglo_general_gf).")";
	        
	        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
	        
		}

		if(count($arreglo_general_dg) > 0)
		{
			$query = "update PRODUCCION_DG set FINALIZAR_PROCESO=1 where DOCTO_PV_DET_ID in (".implode(",", $arreglo_general_dg).")";
	        
	        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
		}
	}
	

	$json = array(1);
	$conection = null;
	$obj = (object) $json;
	echo json_encode($obj);
}