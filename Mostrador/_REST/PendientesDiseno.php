<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../../clases/conexion.php");
	include("../../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$candado = "";
		
		$conection2 = new conexion_nexos(2);
		
		$query = "select
		PRODUCCION_DG.DOCTO_PV_DET_ID,
		DOCTOS_PV.DOCTO_PV_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		PRODUCCION_DG.F_ENTREGA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_PV.IMPORTE_NETO,
		DOCTOS_PV.TOTAL_IMPUESTOS,
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = PRODUCCION_DG.OPERADOR_DISENO_GF) AS NOMBRE_OPERADOR,
		PRODUCCION_DG.IDESTATUSIMPRESION,
		PRODUCCION_DG.DESCRIPCION,
		PRODUCCION_DG.ACTIVACION,
		(select count(*) from DGOBSERVACION where DOCTOS_PV.DOCTO_PV_ID = DGOBSERVACION.DOCTO_PV_ID AND DGOBSERVACION.IDDEPARTAMENTO=2) as CONTADOR_MESSAGE
		from DOCTOS_PV,
       	PRODUCCION_DG
        
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND PRODUCCION_DG.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        AND PRODUCCION_DG.DISENO_GF!=2 AND PRODUCCION_DG.GF_DISENO=1";
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json_mostrador = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json_mostrador);
			$json_mostrador[$indice]['IDTABLERO'] 		= $row->DOCTO_PV_DET_ID;
			$json_mostrador[$indice]['IDPRODUCCION'] 	= $row->DOCTO_PV_ID;
			$json_mostrador[$indice]['FOLIO'] 			= "A".(int)substr($row->FOLIO,1);
			$json_mostrador[$indice]['FECHA'] 			= $row->FECHA;
			$json_mostrador[$indice]['F_ENTREGA'] 		= $row->F_ENTREGA;
			$json_mostrador[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json_mostrador[$indice]['NOMBRE_OPERADOR'] = ($row->NOMBRE_OPERADOR != null) ? $row->NOMBRE_OPERADOR : '';
			$json_mostrador[$indice]['DESCRIPCION'] 	= utf8_encode($row->DESCRIPCION);
			$json_mostrador[$indice]['IDESTATUS'] 		= $row->IDESTATUSIMPRESION;
			$json_mostrador[$indice]['CONTADOR_MESSAGE']= $row->CONTADOR_MESSAGE;
			$json_mostrador[$indice]['ACTIVACION'] 		= $row->ACTIVACION;
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
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json_mostrador[$index]['IDPRODUCCION'];
            $condicionales2 .= " AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149) 
            					 OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05'))";
			
			//echo $condicionales;
            $json2 = $conection2->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

            if(count($json2) > 0)
            {

            	$indice_selected = count($json);
            	$json[$indice_selected] = $json_mostrador[$index];	
	            $json[$indice_selected]['MATERIALES'] = $json2;	
	          
			}
			//Fin observaciones
            $index++;
		}
		/*for($i = 0; $i < $contador; $i++)
		{
			$j = ($i + 1);
			for(; $j < $contador; $j++)
			{
				
				if($json3[$i]['TABLEROPRODUCCION.FECHA'] > $json3[$j]['TABLEROPRODUCCION.FECHA'])
				{
					$arrayAuxiliar[0] = $json3[$i];
					$json3[$i] = $json3[$j];	
					$json3[$j] = $arrayAuxiliar[0];
				}
			}
		}*/
			

		//$conection 	= null;
		$conection2 = null;

		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "observaciones")
	{
		$campos = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
		$join = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
		
		$condicionales = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=2 AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "TABLEROOBSERVACION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "saveObservacion")
	{
		if(strlen(trim($_POST['observacion'])) > 0)
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "OBSERVACION");
			$valores = array($_POST['idtablero'], 2, "'".utf8_decode($_POST['observacion'])."'");
			
			$json = $conection->insert_table($campos, "TABLEROOBSERVACION", $valores);
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$json = array("observacion"=>"no agregado");
			$obj = (object) $json;
			echo json_encode($obj);	
		}
	}

	if($_POST["accion"] == "activarActividad")
	{
			
		$condicionales = " AND PRODUCCION.ID=".$_POST['id']." AND IDDEPARTAMENTO=2";

		$conection = new conexion_nexos($_POST['EMPRESA']);
		$json = $conection->select_table_advanced_with_counter(array("ACTIVACION"), array("ACTIVACION"), "PRODUCCION", array(), $condicionales, array(), 0, NULL, 1);	
	    
		/**/
		if(count($json) > 0)
        {
            $campos = array("ACTIVACION");
            $activacion = 0;
            $respuesta = 0;
            if($json[0]['ACTIVACION'] == 0)
            	$activacion = 1;
            else
            	$activacion = 0;

            
            $valores = array($activacion);
            $id = "PRODUCCION.ID = ".$_POST['id'];
            $json = $conection->update_table($campos, "PRODUCCION", $valores, $id);

            $obj = (object) array("data" => $activacion);
            $conection = null;
            echo json_encode($obj);
            
        }
		/**/	
		
	}

	if($_POST["accion"] == "operadores")
	{
		$campos = array("OPERADOR.ID", "OPERADOR.ALIAS");
		
		$join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=2 AND OPERADOR.BORRADO IS NULL";
		
		$order = array();
		
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "saveTurnar")
	{
		
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=2 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array( "IDOPERADORDEPARTAMENTO", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID'];
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 2, $_POST['EmpleadoFinalizar'], 1,  "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "save")
	{

		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=2 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array("TABLEROPRODUCCION.PROGRAMACION",
							"TABLEROPRODUCCION.IMPRESION",
							"TABLEROPRODUCCION.INSTALACION",
							"TABLEROPRODUCCION.ENTREGA",
							"TABLEROPRODUCCION.MAQUILAS");
			$condicionales = " AND TABLEROPRODUCCION.ID=".$_POST['idtablerofinalizar'];
			$json1 = $conection->select_table($campos, "TABLEROPRODUCCION", array(), $condicionales, array(), 0);

			$contador = 0;		
			$depto = 0;
			
			if($json1[0]["TABLEROPRODUCCION.IMPRESION"] == "1" && $depto == 0){
				$contador++;
				 $depto = 3;
			}
			if($json1[0]["TABLEROPRODUCCION.INSTALACION"] == "1" && $depto == 0){
				$contador++;
				$depto = 4;
			}
			if($json1[0]["TABLEROPRODUCCION.PROGRAMACION"] == "1" && $depto == 0){
				$contador++;
				$depto = 7;
			}
			if($json1[0]["TABLEROPRODUCCION.MAQUILAS"] == "1" && $depto == 0){
				$contador++;
				$depto = 8;
			}
			if($json1[0]["TABLEROPRODUCCION.ENTREGA"] == "1" && $depto == 0){
				$contador++;
				$depto = 6;
			}

			if($contador > 0)
			{
				if(strlen(trim($_POST['observacionFinalizado'])) > 0)
				{
					$campos3 = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "OBSERVACION");
					$valores2 = array($_POST['idtablerofinalizar'], $depto, "'".$_POST['observacionFinalizado']."'");
					$json4 = $conection->insert_table($campos3, "TABLEROOBSERVACION", $valores2);
				}
			}

			$campos = array( "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], 2,  "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID'];
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);

            $condicionales = " AND IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
            $counter = $conection->counter("produccion", array(), $condicionales, 0);

            if($counter->PAGINADOR == 0)
            {
                $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
            }

			
			$obj = (object) $json;
			echo json_encode($obj);
			//
		}else
		{
			
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 2, $_POST['EmpleadoFinalizar'], 2,  "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);

            $condicionales = " IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
            $counter = $conection->counter("produccion", array(), $condicionales, 0);

            if($counter->PAGINADOR == 0)
            {
                $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
            }

			$obj = (object) $jsonx;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "countMessaje")
	{
		$join = array();
		
		$condicionales = " AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id']." AND TABLEROOBSERVACION.IDDEPARTAMENTO=2";
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROOBSERVACION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "cancelacion")
	{
		$campos = array("PRODUCCION.DESCRIPCIONCANCELACION");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['id']." AND PRODUCCION.IDDEPARTAMENTO=2";
		
		$order = array();
		
		$json = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}	

	if($_POST['accion'] == "saveActividadProceso")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);


        $json1_1 = $conection2->counter("ORDENDIA", array(), " AND ORDENDIA.IDPRODUCCION =".$json[0]['PRODUCCION.ID'], 0);

        $json2 = array();
        if($json1_1->PAGINADOR == 0)
        {
            $campos2 = array("IDPRODUCCION", "FECHAORDEN");
            $valores2 = array($json[0]['PRODUCCION.ID'], "'".date("Y-m-d H:i:s")."'");

            $json2 = $conection2->insert_table($campos2, "ORDENDIA", $valores2);
        }

		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "saveActividadPendiente")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

        $json1_1 = $conection2->counter("ORDENPENDIENTE", array(), " AND ORDENPENDIENTE.IDPRODUCCION =".$json[0]['PRODUCCION.ID'], 0);

        $json2 = array();
        if($json1_1->PAGINADOR == 0)
        {
            $campos2 = array("IDPRODUCCION", "FECHAPENDIENTE");
            $valores2 = array($json[0]['PRODUCCION.ID'], "'".date("Y-m-d H:i:s")."'");

            $json2 = $conection2->insert_table($campos2, "ORDENPENDIENTE", $valores2);
        }

		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "deleteActividadProceso")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		
		
		$campos2 = array("IDPRODUCCION", "FECHAORDEN");

		$id = " ORDENDIA.IDPRODUCCION = ".$json[0]['PRODUCCION.ID'];
		$json2 = $conection2->delete_of_table("ORDENDIA", $id, Array());
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "deleteActividadPendiente")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		
		
		$campos2 = array("IDPRODUCCION", "FECHAPENDIENTE");

		$id = " ORDENPENDIENTE.IDPRODUCCION = ".$json[0]['PRODUCCION.ID'];
		$json2 = $conection2->delete_of_table("ORDENPENDIENTE", $id, Array());
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}
	$conection = null;
?>