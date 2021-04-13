<?php
	include("../clases/conexion.php");
	include("../clases/utilerias.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	session_start();
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$candado = "";
		
		if($_POST['foliofiltro'])
		{
			$consulta1 .= " AND DOCTOS_VE.FOLIO LIKE '%".$_POST['foliofiltro']."%' ";
		}
		if($_POST['realizados'] && $_POST['realizados']==2)
		{
			$consulta1 .= " AND TABLEROPRODUCCION.MAQUILAS_GF=2 AND TABLEROPRODUCCION.F_MAQUILAS_GF LIKE '".$_POST['fecha']."%' ";
		}else
		{
			$consulta1 .= " AND TABLEROPRODUCCION.MAQUILAS_GF!=2 ";
		}
		if($_POST['clientefiltro'])
		{
			$consulta1 .= " AND (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_VE.CLIENTE_ID) LIKE '%".$_POST['clientefiltro']."%' ";
		}

		if($_POST['activas']==2)
		{
			$consulta1 .= " AND TABLEROPRODUCCION.ACTIVACION = 1";	
		}
		
		
		$json = array();

		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$query = "select
		TABLEROPRODUCCION.ID,
		TABLEROPRODUCCION.DOCTO_VE_DET_ID,
		DOCTOS_VE.DOCTO_VE_ID,
		DOCTOS_VE.FOLIO,
		DOCTOS_VE.TIPO_DOCTO,
		DOCTOS_VE.FECHA,
		DOCTOS_VE.DESCRIPCION,
		TABLEROPRODUCCION.FECHA_ENTREGA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_VE.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_VE.IMPORTE_NETO,
		DOCTOS_VE.TOTAL_IMPUESTOS,
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = TABLEROPRODUCCION.OPERADOR_MAQUILAS_GF) AS NOMBRE_OPERADOR,
		TABLEROPRODUCCION.NOTA,
		TABLEROPRODUCCION.ACTIVACION,
		(select count(*) from TABLEROOBSERVACION where TABLEROOBSERVACION.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND TABLEROOBSERVACION.IDDEPARTAMENTO=8) as CONTADOR_MESSAGE
		
		from DOCTOS_VE,
       	TABLEROPRODUCCION
        
        WHERE  DOCTOS_VE.TIPO_DOCTO IN ('R', 'F') AND DOCTOS_VE.ESTATUS!='C' 
        AND TABLEROPRODUCCION.DOCTO_VE_ID = DOCTOS_VE.DOCTO_VE_ID 
        AND TABLEROPRODUCCION.GF_MAQUILAS=1 AND MAQUILAS_GF!=2
        AND (TABLEROPRODUCCION.GF_DISENO=0 OR TABLEROPRODUCCION.DISENO_GF=2) ".$consulta1;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 				= $row->ID;
			$json[$indice]['IDTABLERO'] 		= $row->DOCTO_VE_DET_ID;
			$json[$indice]['IDPRODUCCION'] 		= $row->DOCTO_VE_ID;
			$json[$indice]['FOLIO'] 			= "NP".$row->TIPO_DOCTO."-".(int)$row->FOLIO;
			$json[$indice]['FECHA'] 			= $row->FECHA;
			$json[$indice]['F_ENTREGA'] 		= $row->FECHA_ENTREGA;
			$json[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json[$indice]['NOMBRE_OPERADOR'] 	= ($row->NOMBRE_OPERADOR != null) ? $row->NOMBRE_OPERADOR : '';
			$json[$indice]['NOTAS'] 			= (utf8_encode($row->NOTA) != null) ? utf8_encode($row->NOTA):'';
			if(count($json[$indice]['NOTAS'])>0)
				$json[$indice]['NOTAS'] 			= str_replace("\n", "<br>", $json[$indice]['NOTAS']);

			
			$json[$indice]['DESCRIPCION'] 		= utf8_encode($row->DESCRIPCION);
			$json[$indice]['CONTADOR_MESSAGE']	= $row->CONTADOR_MESSAGE;
			$json[$indice]['ACTIVACION'] 		= $row->ACTIVACION;
			$json[$indice]['EMPRESA'] 			= 2;

			$campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID",
            				"CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_VE_DET.DOCTO_VE_ID=".$json[$indice]['IDPRODUCCION'];
            
			$json2 = $conection2->select_table($campos2, "DOCTOS_VE_DET", $join2, $condicionales2, $order2, 0);
            $json[$indice]['MATERIALES'] = $json2;	
            
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
        
		$obj = (object) $json;
		echo json_encode($obj);
		$conection2 = NULL;
	}

	if($_POST["accion"] == "activarActividad")
	{
			
		$condicionales = " AND PRODUCCION.ID=".$_POST['id']." AND IDDEPARTAMENTO=8";

		$conection = new conexion_nexos($_POST['EMPRESA']);
		$json = $conection->select_table_advanced_with_counter(array("ACTIVACION"), array("ACTIVACION"), "PRODUCCION", array(), $condicionales, array(), 0, NULL, 1);	
	    
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

	if($_POST["accion"] == "observaciones")
	{
		$campos = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
		$join = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
		
		$condicionales = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=8 AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id'];
		
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
			$valores = array($_POST['idtablero'], 8, "'".utf8_decode($_POST['observacion'])."'");
			
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

	if($_POST["accion"] == "operadores")
	{
		$campos = array("OPERADOR.ID", "OPERADOR.ALIAS");
		
		$join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=8 ";
		
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
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=8 ";
		
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
			$valores = array($_POST['idtablerofinalizar'], 8, $_POST['EmpleadoFinalizar'], 1,  "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "save")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=8 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array("TABLEROPRODUCCION.INSTALACION",
							"TABLEROPRODUCCION.ENTREGA");
			$condicionales = " AND TABLEROPRODUCCION.ID=".$_POST['idtablerofinalizar'];
			$json1 = $conection->select_table($campos, "TABLEROPRODUCCION", array(), $condicionales, array(), 0);

			$contador = 0;		
			$depto = 0;
			
			
			if($json1[0]["TABLEROPRODUCCION.INSTALACION"] == "1" && $depto == 0){
				$contador++;
				$depto = 4;
			}
			
			if($json1[0]["TABLEROPRODUCCION.ENTREGA"] == "1" && $depto == 0){
				$contador++;
				$depto = 6;
			}

			if($contador > 0)
			{
				if(strlen(trim($_POST['observacion'])) > 0)
				{
					$campos3 = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "OBSERVACION");
					$valores2 = array($_POST['idtablerofinalizar'], $depto, "'".$_POST['observacionFinalizado']."'");
					$json4 = $conection->insert_table($campos3, "TABLEROOBSERVACION", $valores2);
				}
			}

			$campos = array( "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID']." AND PRODUCCION.IDDEPARTAMENTO=8";
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);

            $condicionales = " AND IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
            $counter = $conection->counter("produccion", array(), $condicionales, 0);

            if($counter->PAGINADOR == 0)
            {
                $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
            }

			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 8, $_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);

            $condicionales = " IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
            $counter = $conection->counter("produccion", array(), $condicionales, 0);

            if($counter->PAGINADOR == 0)
            {
                $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
            }

			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "cancelar")
	{
		
		$campos = array("PRODUCCION.IDESTATUS", "PRODUCCION.DESCRIPCIONCANCELACION");
		
		$valores = array(3, "'".$_POST['notacancelacion']."'");
		
		$order = array();
		
		$id = "IDTABLEROPRODUCCION = ".$_POST['idtablerocancelar']." AND IDDEPARTAMENTO = 2";
		$arreglo = $conection->update_table($campos, "PRODUCCION", $valores, $id);
		
		$obj = (object) $json;
		echo json_encode($obj);
		
		
	}
	
	if($_POST["accion"] == "countMessaje")
	{
		$join = array();
		
		$condicionales = " AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id']." AND TABLEROOBSERVACION.IDDEPARTAMENTO=8";
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROOBSERVACION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
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
		
		
		$campos2 = array("IDPRODUCCION", "FECHAORDEN");
		$valores2 = array($json[0]['PRODUCCION.ID'], "'".date("Y-m-d H:i:s")."'");

		$json2 = $conection2->insert_table($campos2, "ORDENDIA", $valores2);
		
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
?>
