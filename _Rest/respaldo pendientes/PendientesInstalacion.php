<?php
	include("../clases/conexion.php");
	include("../clases/utilerias.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	session_start();

	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$candado = "";
		
		$json = array();

		if($_POST['foliofiltro'])
		{
			$consulta1 .= " AND DOCTOS_VE.FOLIO LIKE '%".$_POST['foliofiltro']."%' ";
		}
		if($_POST['realizados'] && $_POST['realizados']==2)
		{
			$consulta1 .= " AND TABLEROPRODUCCION.INSTALACION_GF=2 AND TABLEROPRODUCCION.F_INSTALACION_GF LIKE '".$_POST['fecha']."%' ";
		}else
		{
			$consulta1 .= " AND TABLEROPRODUCCION.INSTALACION_GF!=2 ";
		}
		if($_POST['clientefiltro'])
		{
			$consulta1 .= " AND (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_VE.CLIENTE_ID) LIKE '%".utf8_decode($_POST['clientefiltro'])."%' ";
		}

		if($_POST['activas']==2)
		{
			$consulta1 .= " AND TABLEROPRODUCCION.ACTIVACION = 1";	
		}
		
		$conection2 = new conexion_nexos(2);
		
		
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
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = TABLEROPRODUCCION.OPERADOR_INSTALACION_GF) AS NOMBRE_OPERADOR,
		TABLEROPRODUCCION.NOTA,
		TABLEROPRODUCCION.ACTIVACION,
		(select count(*) from TABLEROOBSERVACION where TABLEROOBSERVACION.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND TABLEROOBSERVACION.IDDEPARTAMENTO=4) as CONTADOR_MESSAGE
		
		from DOCTOS_VE,
       	TABLEROPRODUCCION
        
        WHERE  DOCTOS_VE.TIPO_DOCTO IN ('R', 'F') AND DOCTOS_VE.ESTATUS!='C' 
        AND TABLEROPRODUCCION.DOCTO_VE_ID = DOCTOS_VE.DOCTO_VE_ID 
        AND TABLEROPRODUCCION.GF_INSTALACION=1 AND INSTALACION_GF!=2
        AND (TABLEROPRODUCCION.GF_DISENO=0 OR TABLEROPRODUCCION.DISENO_GF=2)
        AND (TABLEROPRODUCCION.GF_IMPRESION=0 OR TABLEROPRODUCCION.IMPRESION_GF=2)
        AND (TABLEROPRODUCCION.GF_PREPARACION=0 OR TABLEROPRODUCCION.PREPARACION_GF=2) ".$consulta1;
        
        $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
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

		$consulta2 = "";
		if($_POST['foliofiltro'])
		{
			$consulta2 .= " AND DOCTOS_PV.FOLIO LIKE '%".$_POST['foliofiltro']."%' ";
		}
		if($_POST['realizados'] && $_POST['realizados']==2)
		{
			$consulta2 .= " AND PRODUCCIONPV.INSTALACION_GF=2 AND PRODUCCIONPV.F_INSTALACION_GF LIKE '".$_POST['fecha']."%' ";
		}else
		{
			$consulta2 .= " AND PRODUCCIONPV.INSTALACION_GF!=2 ";
		}
		
		if($_POST['clientefiltro'])
		{
			$consulta2 .= " AND (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) LIKE '%".utf8_decode($_POST['clientefiltro'])."%' ";
		}
		if($_POST['activas']==2)
		{
			$consulta2 .= " AND PRODUCCIONPV.ACTIVACION = 1";	
		}

		$query = "select
		PRODUCCIONPV.DOCTO_PV_DET_ID,
		DOCTOS_PV.DOCTO_PV_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		PRODUCCIONPV.F_ENTREGA,
		PRODUCCIONPV.NOTAS_PROCESO,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_PV.IMPORTE_NETO,
		DOCTOS_PV.TOTAL_IMPUESTOS,
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = PRODUCCIONPV.OPERADOR_INSTALACION_GF) AS NOMBRE_OPERADOR,
		PRODUCCIONPV.IDESTATUSIMPRESION,
		PRODUCCIONPV.DESCRIPCION,
		PRODUCCIONPV.ACTIVACION,
		(select count(*) from PVOBSERVACION where DOCTOS_PV.DOCTO_PV_ID = PVOBSERVACION.DOCTO_PV_ID AND PVOBSERVACION.IDDEPARTAMENTO=4) as CONTADOR_MESSAGE
		from DOCTOS_PV,
       	PRODUCCIONPV
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        AND PRODUCCIONPV.INSTALACION_GF!=2 AND PRODUCCIONPV.GF_INSTALACION=1
        AND (PRODUCCIONPV.GF_PREPARACION=0 OR PRODUCCIONPV.PREPARACION_GF=2)
        AND (PRODUCCIONPV.GF_IMPRESION=0 OR PRODUCCIONPV.IMPRESION_GF=2)
        AND (PRODUCCIONPV.GF_DISENO=0 OR PRODUCCIONPV.DISENO_GF=2) ".$consulta2;
        
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
			$json_mostrador[$indice]['DESCRIPCION'] 	= ($row->NOTAS_PROCESO != null) ? $row->NOTAS_PROCESO:'';
			$json_mostrador[$indice]['IDESTATUS'] 		= $row->IDESTATUSIMPRESION;
			$json_mostrador[$indice]['CONTADOR_MESSAGE']= $row->CONTADOR_MESSAGE;
			$json_mostrador[$indice]['ACTIVACION'] 		= $row->ACTIVACION;
			$json_mostrador[$indice]['EMPRESA'] 		= 3;
			$json_mostrador[$indice]['NOTAS'] 			= ($row->NOTAS_PROCESO != null) ? $row->NOTAS_PROCESO:'';
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
            //$condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (1849,1954,6346,2048)";
            $condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149, 2143) 
            					 AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12')";
			
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
			
		$condicionales = " AND PRODUCCION.ID=".$_POST['id']." AND IDDEPARTAMENTO=4";

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
	if($_POST["accion"] == "observaciones")
	{
		$campos = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
		$join = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
		
		$condicionales = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=4 AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id'];
		
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
			$valores = array($_POST['idtablero'], 4, "'".utf8_decode($_POST['observacion'])."'");
			
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
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=4 and OPERADOR.BORRADO IS NULL";
		
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
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=4 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array( "IDOPERADORDEPARTAMENTO", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID']." AND PRODUCCION.IDDEPARTAMENTO=4";
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 4, $_POST['EmpleadoFinalizar'], 1, "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "save")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=4 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{

			$campos = array("TABLEROPRODUCCION.ENTREGA");
			$condicionales = " AND TABLEROPRODUCCION.ID=".$_POST['idtablerofinalizar'];
			$json1 = $conection->select_table($campos, "TABLEROPRODUCCION", array(), $condicionales, array(), 0);

			$contador = 0;		
			$depto = 0;
			
			
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
				}else
				{
					$json = array("observacion"=>"no agregado");
					$obj = (object) $json;
					echo json_encode($obj);	
				}
			}

			$campos = array( "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID']." AND PRODUCCION.IDDEPARTAMENTO=4";
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);

            $condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
            $counter = $conection->counter("PRODUCCION", array(), $condicionales, 0);

            if($counter->PAGINADOR == 0)
            {
                $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
            }

			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 4, $_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);

            $condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
            $counter = $conection->counter("PRODUCCION", array(), $condicionales, 0);

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
		
		$id = "IDTABLEROPRODUCCION = ".$_POST['idtablerocancelar']." AND IDDEPARTAMENTO = 9";
		$arreglo = $conection->update_table($campos, "PRODUCCION", $valores, $id);
		
		$obj = (object) $json;
		echo json_encode($obj);
		
		
	}
	
	if($_POST["accion"] == "countMessaje")
	{
		$join = array();
		
		$condicionales = " AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id']." AND TABLEROOBSERVACION.IDDEPARTAMENTO=4";
		
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
		 
	if($_POST['accion'] == "saveActividadPendiente")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		
		
		$campos2 = array("IDPRODUCCION", "FECHAPENDIENTE");
		$valores2 = array($json[0]['PRODUCCION.ID'], "'".date("Y-m-d H:i:s")."'");

		$json2 = $conection2->insert_table($campos2, "ORDENPENDIENTE", $valores2);
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}


	/*if($_POST['accion'] == "savepreparacion")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['preparacionidproduccion']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['preparaciondepartamento'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['preparacionemp']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);


		$campos2 = array("IDPRODUCCION","COLABORADORES","DESCRIPCIONPREPARACION", "FECHAPREPARACION");
		
		$valores2 = array($json[0]['PRODUCCION.ID'], "'".$_POST['colaboradores']."'", "'".$_POST['descripcionpreparacion']."'", "'".date("Y-m-d H:i:s")."'");
		if($_POST['preparacionid']=="")
			$json2 = $conection2->insert_table($campos2, "PREPARACION", $valores2);
		else
			$json2 = $conection2->update_table($campos2, "PREPARACION", $valores2, " PREPARACION.ID=".$_POST['preparacionid']);
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "deletepreparacion")
	{	
		$conection2 = new conexion_nexos($_POST['preparacionemp']);
		
		$json2 = $conection2->delete_of_table("PREPARACION", "PREPARACION.ID=".$_POST['preparacionid'], array());
		;
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}
	
	if($_POST['accion'] == "preparacion")
	{

		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);


		$campos2 = array("PREPARACION.ID", "PREPARACION.IDPRODUCCION","PREPARACION.COLABORADORES","PREPARACION.DESCRIPCIONPREPARACION");
		
		$join = array();
		
		$condicionales2 = " AND PREPARACION.IDPRODUCCION =".$json[0]['PRODUCCION.ID'];
		
		$order = array();
		
		$conection3 = new conexion_nexos($_POST['empresa']);
		
			$json2 = $conection3->select_table($campos2, "PREPARACION", $join, $condicionales2, $order, 0);
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}*/

	if($_POST['accion'] == "instalacion")
	{

		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['proceso']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['tipo'];
		
		$order = array();
		
		$conection2 = new conexion_nexos($_POST['empresa']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);


		$campos2 = array("INSTALACION.ID", "INSTALACION.IDPRODUCCION","INSTALACION.COLABORADORESINSTALACION");
		
		$join = array();
		
		$condicionales2 = " AND INSTALACION.IDPRODUCCION =".$json[0]['PRODUCCION.ID'];
		
		$order = array();
		
		$conection3 = new conexion_nexos($_POST['empresa']);
		
			$json2 = $conection3->select_table($campos2, "INSTALACION", $join, $condicionales2, $order, 0);
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}
		 
	if($_POST['accion'] == "saveinstalacion")
	{
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['instalacionidproduccion']." AND PRODUCCION.IDDEPARTAMENTO=".$_POST['instalaciondepartamento'];
		
		$order = array();
		

		$conection2 = new conexion_nexos($_POST['instalacionemp']);
		$json = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);


		$campos2 = array("IDPRODUCCION","COLABORADORESINSTALACION");
		

		$valores2 = array($json[0]['PRODUCCION.ID'], "'".$_POST['colaboradoresinstalacion']."'");
		if($_POST['instalacionid']=="")
			$json2 = $conection2->insert_table($campos2, "INSTALACION", $valores2);
		else
			$json2 = $conection2->update_table($campos2, "INSTALACION", $valores2, " INSTALACION.ID=".$_POST['instalacionid']);
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "deleteinstalacion")
	{	
		$conection2 = new conexion_nexos($_POST['instalacionemp']);
		
		$json2 = $conection2->delete_of_table("INSTALACION", "INSTALACION.ID=".$_POST['instalacionid'], array());
		;
		
		$obj = (object) $json2;
		echo json_encode($obj);
	}	 
?>