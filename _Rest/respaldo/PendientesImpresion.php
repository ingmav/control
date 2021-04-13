<?php
	include("../clases/conexion.php");
	include("../clases/utilerias.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	session_start();

	//$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$candado = "";

		$json = array();
		
		$consulta1 = "";
		if($_POST['foliofiltro'])
		{
			$consulta1 .= " AND DOCTOS_VE.FOLIO LIKE '%".$_POST['foliofiltro']."%' ";
		}
		if($_POST['realizados'] && $_POST['realizados']==2)
		{
			$consulta1 .= " AND TABLEROPRODUCCION.IMPRESION_GF=2 AND TABLEROPRODUCCION.F_IMPRESION_GF LIKE '".$_POST['fecha']."%' ";
		}else
		{
			$consulta1 .= " AND TABLEROPRODUCCION.IMPRESION_GF!=2 ";
		}
		if($_POST['clientefiltro'])
		{
			$consulta1 .= " AND (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_VE.CLIENTE_ID) LIKE '%".$_POST['clientefiltro']."%' ";
		}

		if($_POST['activas']==2)
		{
			$consulta1 .= " AND TABLEROPRODUCCION.ACTIVACION = 1";	
		}	
		
		//Nueva consulta
		

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
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = TABLEROPRODUCCION.OPERADOR_IMPRESION_GF) AS NOMBRE_OPERADOR,
		TABLEROPRODUCCION.NOTA,
		TABLEROPRODUCCION.ACTIVACION,
		(select count(*) from TABLEROOBSERVACION where TABLEROOBSERVACION.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND TABLEROOBSERVACION.IDDEPARTAMENTO=3) as CONTADOR_MESSAGE
		
		from DOCTOS_VE,
       	TABLEROPRODUCCION
        
        WHERE  DOCTOS_VE.TIPO_DOCTO IN ('R', 'F') AND DOCTOS_VE.ESTATUS!='C' 
        AND TABLEROPRODUCCION.DOCTO_VE_ID = DOCTOS_VE.DOCTO_VE_ID 
        AND TABLEROPRODUCCION.GF_IMPRESION=1 AND IMPRESION_GF!=2
        AND (TABLEROPRODUCCION.GF_DISENO=0 OR TABLEROPRODUCCION.DISENO_GF=2) ".$consulta1;
        
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
			$consulta2 .= " AND PRODUCCIONPV.IMPRESION_GF=2 AND PRODUCCIONPV.F_IMPRESION_GF LIKE '".$_POST['fecha']."%' ";
		}else
		{
			$consulta2 .= " AND PRODUCCIONPV.IMPRESION_GF!=2 ";
		}
		if($_POST['clientefiltro'])
		{
			$consulta2 .= " AND (SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) LIKE '%".$_POST['clientefiltro']."%' ";
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
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = PRODUCCIONPV.OPERADOR_IMPRESION_GF) AS NOMBRE_OPERADOR,
		PRODUCCIONPV.IDESTATUSIMPRESION,
		PRODUCCIONPV.DESCRIPCION,
		PRODUCCIONPV.ACTIVACION,
		(select count(*) from PVOBSERVACION where DOCTOS_PV.DOCTO_PV_ID = PVOBSERVACION.DOCTO_PV_ID AND PVOBSERVACION.IDDEPARTAMENTO=3) as CONTADOR_MESSAGE
		from DOCTOS_PV,
       	PRODUCCIONPV
        WHERE  DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' 
        AND PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        AND PRODUCCIONPV.GF_IMPRESION=1
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
			$json_mostrador[$indice]['REALIZADO'] 		= $realizado_pv;
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
		$conection = null;
		$conection2 = null;

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
		//$conection1 = null;
		$conection2 = null;
	}

	if($_POST["accion"] == "activarActividad")
	{
			
		$condicionales = " AND PRODUCCION.ID=".$_POST['id']." AND IDDEPARTAMENTO=3";

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
        $conection = new conexion_nexos($_POST['empresa']);
		$campos = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
		$join = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
		
		$condicionales = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=3 AND TABLEROPRODUCCION.ID=".$_POST['id'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "TABLEROOBSERVACION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "saveObservacion")
	{
        $conection = new conexion_nexos($_POST['empresa']);
		if(strlen(trim($_POST['observacion'])) > 0)
		{

			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "OBSERVACION");
			$valores = array($_POST['idtablero'], 3, "'".utf8_decode($_POST['observacion'])."'");
			
			$json = $conection->insert_table($campos, "TABLEROOBSERVACION", $valores);
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}
		else
		{
			$json = array("observacion"=>"no agregado");
			$obj = (object) $json;
			echo json_encode($obj);	
		}
		$conection = null;
	}

	if($_POST["accion"] == "operadores")
	{
        $conection = new conexion_nexos($_POST['empresa']);
		$campos = array("OPERADOR.ID", "OPERADOR.ALIAS");
		
		$join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=3 ";
		
		$order = array();
		
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "saveTurnar")
	{
        $conection = new conexion_nexos($_POST['empresa']);
		$campos = array("PRODUCCION.ID");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=3 ";
		
		$order = array();
		
		$arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);

		if(count($arreglo) > 0)
		{
			$campos = array( "IDOPERADORDEPARTAMENTO", "FECHA", "IDOPERADOR");
			$valores = array($_POST['EmpleadoFinalizar'], "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);
			$id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID']." AND PRODUCCION.IDDEPARTAMENTO=3";
			$json = $conection->update_table($campos, "PRODUCCION", $valores, $id);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}else
		{
			$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
			$valores = array($_POST['idtablerofinalizar'], 3, $_POST['EmpleadoFinalizar'], 1, "'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar']);

			$json = $conection->insert_table($campos, "PRODUCCION", $valores);
			
			$obj = (object) $json;
			echo json_encode($obj);
		}
		
	}

	if($_POST["accion"] == "save")
	{


        $conection = new conexion_nexos($_POST['empresa']);


        $arreglo1 = CuentaProcesosActivos($_POST['empresa'], $_POST['idtablerofinalizar']);
        $arreglo2 = calculaArticuloUtilizado($_POST['empresa'], $_POST['idtablerofinalizar']);
        //$validador = calculaInventarioFaltante($arreglo2);

        $bandera = 0;
        
        $join = array();

        $condicionales = " AND INVENTARIOIMPRESION.IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']."  and INVENTARIOIMPRESION.IDTIPO=1";

        $order = array();

        $arreglo = $conection->counter("INVENTARIOIMPRESION", $join, $condicionales, 0);


        if($arreglo->PAGINADOR>0 )
        {
            $campos = array("PRODUCCION.ID");

            $join = array();

            $condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION='".$_POST['idtablerofinalizar']."' AND PRODUCCION.IDDEPARTAMENTO=3 ";

            $order = array();

            $arreglo = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);


            if(count($arreglo) > 0)
            {

                //Aqui
                $arreglo_join = array("ARTICULOSWEB","=", "ARTICULOSWEB.ID", "INVENTARIOIMPRESION.IDARTICULOWEB", "UNION");
                $condicion_finalizar = " AND ARTICULOSWEB.LINEA_ARTICULO_ID = 6284 and IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar'];
                $json_insumos = $conection->select_table_advanced(array("ARTICULOSWEB.ID"), "INVENTARIOIMPRESION", $arreglo_join, $condicion_finalizar, array(), 0);

                if(count($json_insumos) ==0)
                {
                    ReducirInventarioInsumos($_POST['empresa'], $_POST['idtablerofinalizar']);
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
                    $contador;
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
                    $id = "PRODUCCION.ID = ".$arreglo[0]['PRODUCCION.ID'];
                    $json = $conection->update_table($campos, "PRODUCCION", $valores, $id);

                    $json4 = array(array("Respuesta"=>2));

                    $condicionales = " AND  IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
                    $counter = $conection->counter("produccion", array(), $condicionales, 0);

                    if($counter->PAGINADOR == 0)
                    {
                        $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
                    }

                    $obj = (object) $json4;
                    echo json_encode($obj);
                }else
                {
                    $json = array(array("Respuesta"=>1));
                    $obj = (object) $json;
                    echo json_encode($obj);

                }
            }else
            {
                /*$campos = array("IDTABLEROPRODUCCION", "IDDEPARTAMENTO", "IDOPERADORDEPARTAMENTO", "IDESTATUS", "FECHA", "IDOPERADOR");
                    $valores = array($_POST['idtablerofinalizar'], 3, $_POST['EmpleadoFinalizar'], 2, "'".date("Y-m-d H:i:s")."'", $_SESSION['IDUSUARIO']);

                $json = $conection->insert_table($campos, "PRODUCCION", $valores);

                $condicionales = " AND IDTABLEROPRODUCCION=".$_POST['idtablerofinalizar']." and IDESTATUS = 1";
                $counter = $conection->counter("produccion", array(), $condicionales, 0);

                if($counter->PAGINADOR == 0)
                {
                    $conection->update_table(array("FECHA_TERMINO"), "TABLEROPRODUCCION", array("'".date("Y-m-d H:i:s")."'"), " ID=".$_POST['idtablerofinalizar']);
                }
                    $obj = (object) $json;
                    echo json_encode($obj);*/
            }
        }else
        {
            $json = array(array("Respuesta"=>0));
            $obj = (object) $json;
            echo json_encode($obj);
        }
		
	}

   	if($_POST["accion"] == "cancelar")
	{
        $conection = new conexion_nexos($_POST['empresa']);
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
        $conection = new conexion_nexos($_POST['empresa']);
		$join = array();
		
		$condicionales = " AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['id']." AND TABLEROOBSERVACION.IDDEPARTAMENTO=3";
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROOBSERVACION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}
		 
	if($_POST["accion"] == "vercancelacion")
	{
        $conection = new conexion_nexos($_POST['empresa']);
		$campos = array("PRODUCCION.DESCRIPCIONCANCELACION");
		
		$join = array();
		
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION =".$_POST['id']." AND PRODUCCION.IDDEPARTAMENTO=3";
		
		$order = array();
		
		$json = $conection->select_table($campos, "PRODUCCION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}	 

	if($_POST['accion'] == "saveActividadProceso")
	{
        $conection = new conexion_nexos($_POST['empresa']);
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
        $conection = new conexion_nexos($_POST['empresa']);
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


    if($_POST['accion'] == "cargainventario")
    {
        $conection = new conexion_nexos($_SESSION['empresa']);

        $query = "select imp.id as ID, a.nombrelinea as nombrelinea, a.nombre as nombrearticulo,  sa.nombre as nombresubarticulo, imp.cantidad, imp.merma, imp.motivo from  articulosweb as a
left join lineas_articulos as l on a.linea_articulo_id=l.linea_articulo_id
left join inventarioimpresion as imp on a.id=imp.idarticuloweb
left join subarticulosweb as  sa on imp.idarticuloweb=sa.idarticuloweb and imp.idsubarticuloweb=sa.id and a.LINEA_ARTICULO_ID!=6284
where a.borrado is null and imp.idtableroproduccion=".$_POST['idproduccion']." AND imp.IDTIPO=1
order by  l.nombre";

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $arreglo = array();
        while($row = ibase_fetch_object ($result, IBASE_TEXT))
		{
			$index = count($arreglo);
            $arreglo[$index]['ID'] = utf8_decode($row->ID);
			$arreglo[$index]['NOMBRELINEA'] = utf8_decode($row->NOMBRELINEA);
            $arreglo[$index]['NOMBREARTICULO'] = utf8_decode($row->NOMBREARTICULO);
            $arreglo[$index]['NOMBRESUBARTICULO'] = utf8_decode($row->NOMBRESUBARTICULO);
            $arreglo[$index]['CANTIDAD'] = $row->CANTIDAD;
            $arreglo[$index]['MERMA'] = $row->MERMA;
            $arreglo[$index]['MOTIVO'] = $row->MOTIVO;
		}

		/**/
		$campos_observaciones = array("TABLEROOBSERVACION.OBSERVACION", "TABLEROOBSERVACION.FECHAOBSERVACION");
		
		$join_observacion = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.ID", "TABLEROOBSERVACION.IDTABLEROPRODUCCION");
		
		$condicionales_observacion = " AND TABLEROOBSERVACION.IDDEPARTAMENTO=3 AND TABLEROOBSERVACION.IDTABLEROPRODUCCION=".$_POST['idproduccion'];
		
		$json_observacion = $conection->select_table($campos_observaciones, "TABLEROOBSERVACION", $join_observacion, $condicionales_observacion, array(), 0);	
		/**/
		$respuesta[0] = $arreglo;
		$respuesta[1] = $json_observacion;
        $obj = (object) $respuesta;
        echo json_encode($obj);
    }

    if($_POST['accion'] == "cargainventarioutilizado")
    {
        $arreglo = array();

        $arreglo = calculaArticuloUtilizado($_POST['empresa'], $_POST['idproduccion'], $_POST['idtablero']);

        $obj = (object) $arreglo;
        echo json_encode($obj);
    }

    if($_POST['accion'] == 'saveInventario')
    {
        $conection = new conexion_nexos($_POST['empresa']);
        $campos2 = array("IDARTICULOWEB", "IDSUBARTICULOWEB", "CANTIDAD", "MERMA", "MOTIVO", "IDTABLEROPRODUCCION", "IDOPERADOR", "IDTIPO");
        $valores2 = array($_POST['articulo'], $_POST['subarticulo'], $_POST['cantidad'], $_POST['merma'], "'".$_POST['motivo']."'",  $_POST['idproduccion'], $_SESSION['IDUSUARIO'],1);

        $json2 = $conection->insert_table($campos2, "INVENTARIOIMPRESION", $valores2);

        $obj = (object) $json2;
        echo json_encode($obj);
    }

    if($_POST['accion'] == 'deleteinventario')
    {
        $conection = new conexion_nexos($_POST['empresa']);
        $id = " INVENTARIOIMPRESION.ID = ".$_POST['idinventario']." AND INVENTARIOIMPRESION.IDTIPO=1";
        $json = $conection->delete_of_table("INVENTARIOIMPRESION", $id, Array());

        $obj = (object) $json;
        echo json_encode($obj);
    }

    if($_POST['accion'] == 'cargaInventario_MS')
    {
    	$json = array();
		$conection1 = new conexion_nexos($_SESSION['empresa']);
		
		
		$query = "select
		DOCTOS_VE.DOCTO_VE_ID,
		DOCTOS_VE.FOLIO,
		DOCTOS_VE.TIPO_DOCTO,
		DOCTOS_VE.DESCRIPCION,
		DOCTOS_VE.IMPORTE_NETO
		from DOCTOS_VE
		WHERE  
        DOCTOS_VE.DOCTO_VE_ID=".$_POST['venta'];
        
        $result = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());
        
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			
			$json[$indice]['ID'] 				= $row->DOCTO_VE_ID;
			$json[$indice]['TITULO'] 			= utf8_encode($row->DESCRIPCION);
			$json[$indice]['IMPORTE'] 			= $row->IMPORTE_NETO;
			$json[$indice]['FOLIO'] 			= "NP".$row->TIPO_DOCTO."-".(int)$row->FOLIO;
			$json[$indice]['EMPRESA'] 			= 2	;
			
			$query2 = "select
			ARTICULOS.NOMBRE,
			DOCTOS_VE_DET.UNIDADES,
			DOCTOS_VE_DET.PRECIO_TOTAL_NETO,
			DOCTOS_VE_DET.DOCTO_VE_DET_ID,
			DOCTOS_VE_DET.DOCTO_VE_ID,
			MS_RELACION.ID,
			MS_RELACION.CANTIDAD, 
			MS_RELACION.MS_COMBO_ID,
			MS_RELACION.AUTOMATICO
			from DOCTOS_VE_DET
			LEFT JOIN MS_RELACION ON DOCTOS_VE_DET.ARTICULO_ID = MS_RELACION.ARTICULO_ID,
			ARTICULOS
	        WHERE  
	        DOCTOS_VE_DET.DOCTO_VE_ID = ".$json[$indice]['ID']."
	        AND DOCTOS_VE_DET.ARTICULO_ID = ARTICULOS.ARTICULO_ID";
	        
	        $result2 = ibase_query($conection1->getConexion(), $query2) or die(ibase_errmsg());
	        
			while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
				$indice2 = count($json[$indice]['INSUMOS']);
				$json[$indice]['INSUMOS'][$indice2]['ID'] 			= utf8_encode($row2->ID);
				$json[$indice]['INSUMOS'][$indice2]['ARTICULO'] 	= utf8_encode($row2->NOMBRE);
				$json[$indice]['INSUMOS'][$indice2]['UNIDADES'] 	= $row2->UNIDADES;
				$json[$indice]['INSUMOS'][$indice2]['PRECIO'] 		= $row2->PRECIO_TOTAL_NETO;
				$json[$indice]['INSUMOS'][$indice2]['CANTIDAD'] 	= $row2->CANTIDAD;
				$json[$indice]['INSUMOS'][$indice2]['AUTOMATICO'] 	= $row2->AUTOMATICO;
				$json[$indice]['INSUMOS'][$indice2]['ID_VENTA_DET'] = $row2->DOCTO_VE_DET_ID;
				$json[$indice]['INSUMOS'][$indice2]['ID_VENTA'] 	= $row2->DOCTO_VE_ID;

				if($row2->CANTIDAD)
				{
					$query3 = "select
					ID,
					DESCRIPCION,
					(select first 1 precio_compra from ms_inventario where ms_combo_id=ms_combos.id order by fecha_actualizacion desc) as PRECIO_COMPRA,
					(select  first 1 FOLIO from MS_INVENTARIO where MS_COMBO_ID=ID and cantidad_restante >0 and estatus_inventario=0 order by FECHA_ACTUALIZACION)
					from MS_COMBOS

			        WHERE  
			        TIPO_COMBO =".$row2->MS_COMBO_ID;
			        
			        $result3 = ibase_query($conection1->getConexion(), $query3) or die(ibase_errmsg());
			        
					while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
						$indice3 = count($json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB']);
						$json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB'][$indice3]['NOMBRE'] = utf8_encode($row3->DESCRIPCION);
						$json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB'][$indice3]['ID'] = utf8_encode($row3->ID);
						$json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB'][$indice3]['PRECIO'] = utf8_encode($row3->PRECIO_COMPRA );
					}
				}
				
			}
		}
		
		
		$conection1 = null;
    	$obj = (object) $json;
        echo json_encode($obj);
    }


    function calculaArticuloUtilizado($empresa, $idtablero)
    {
        $conection = new conexion_nexos($empresa);

        $query = "select a.nombre as nombre, sum(dvd.unidades * raw.unidades) as unidades, raw.idarticulosweb as id from doctos_ve dv, doctos_ve_det dvd, relarticulosweb raw, articulosweb a, tableroproduccion tp
                    where
                    dv.docto_ve_id=dvd.docto_ve_id and dvd.clave_articulo=raw.clave_articulo and raw.idarticulosweb=a.id and tp.docto_ve_id=dv.docto_ve_id and
                    tp.id=".$idtablero." and a.linea_articulo_id!='6284'
                    group by raw.idarticulosweb, a.nombre";

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $arreglo = array();
        while($row = ibase_fetch_object ($result, IBASE_TEXT))
        {
            $index = count($arreglo);
            $arreglo[$index]['ID'] = utf8_decode($row->ID);
            $arreglo[$index]['NOMBRE'] = utf8_decode($row->NOMBRE);
            $arreglo[$index]['UNIDADES'] = utf8_decode($row->UNIDADES);
        }

        $query2 = "select idarticuloweb AS id, sum(cantidad) as cantidad from inventarioimpresion where idtipo=1 and idtableroproduccion in (select id from tableroproduccion where docto_ve_id =(select docto_ve_id from tableroproduccion where
        id=".$idtablero.") and impresion=1)  group by idarticuloweb";

        $result2 = ibase_query($conection->getConexion(), $query2) or die(ibase_errmsg());

        $arreglo2 = array();
        while($row2 = ibase_fetch_object ($result2, IBASE_TEXT))
        {
            $index = count($arreglo2);
            $arreglo2[$index]['ID'] = utf8_decode($row2->ID);
            $arreglo2[$index]['CANTIDAD'] = utf8_decode($row2->CANTIDAD);
        }


        foreach ($arreglo as $key => $valor) {
            foreach($arreglo2 as $key2 => $valor2)
            {

                if($valor['ID'] == $valor2["ID"])
                {
                    $arreglo[$key]['UTILIZADO'] = $valor2['CANTIDAD'];
                }else
                    $arreglo[$key]['UTILIZADO'] += 0;
            }

        }

        /*foreach ($arreglo as $key => $valor) {
            $arreglo[$key]['RESTO'] = $valor['UNIDADES'];
            foreach($arreglo2 as $key2 => $valor2)
            {
                if($valor['ID'] == $valor2["ID"])
                {
                    $arreglo[$key]['RESTO'] -= $valor2['CANTIDAD'];
                }
            }
        }*/
        return $arreglo;
    }

    function calculaInventarioFaltante($arreglo)
    {
        $contador = 0;
        foreach($arreglo as $key => $value)
        {
            if($value['RESTO'] > 0)
            {
                $contador++;
            }
        }
        return $contador;
    }


    function CuentaProcesosActivos($empresa, $idtablero)
    {

        $conection = new conexion_nexos($empresa);

        $query1  = "select count(*) as activos from produccion where idtableroproduccion in (select id from tableroproduccion where docto_ve_id in (select docto_ve_id from tableroproduccion where id=".$idtablero.") and impresion=1) and iddepartamento=3 and idestatus=1";

        $result1 = ibase_query($conection->getConexion(), $query1) or die(ibase_errmsg());

        $arreglo = array();
        while($row = ibase_fetch_object ($result1, IBASE_TEXT))
        {
            $index = count($arreglo);
            $arreglo[$index] = $row->ACTIVOS;
        }

        $query2  = "select count(*) as activos from produccion where idtableroproduccion in (select id from tableroproduccion where docto_ve_id in (select docto_ve_id from tableroproduccion where id=".$idtablero.") and impresion=1) and iddepartamento=3 and idestatus=3";

        $result2 = ibase_query($conection->getConexion(), $query2) or die(ibase_errmsg());

        while($row2 = ibase_fetch_object ($result2, IBASE_TEXT))
        {
            $index = count($arreglo);
            $arreglo[$index] = $row2->ACTIVOS;
        }
        return $arreglo;
    }

    function ReducirInventarioInsumos($empresa, $idtablero)
    {
        $conection = new conexion_nexos($empresa);
        $query = "select a.nombre as nombre, sum(dvd.unidades * raw.unidades) as unidades, raw.idarticulosweb as id from doctos_ve dv, doctos_ve_det dvd, relarticulosweb raw, articulosweb a, tableroproduccion tp
                    where
                    dv.docto_ve_id=dvd.docto_ve_id and dvd.clave_articulo=raw.clave_articulo and raw.idarticulosweb=a.id and tp.docto_ve_id=dv.docto_ve_id and
                    tp.id=".$idtablero." and a.linea_articulo_id='6284'
                    group by raw.idarticulosweb, a.nombre";

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $arreglo = array();
        while($row = ibase_fetch_object ($result, IBASE_TEXT))
        {

            $campos = array("IDARTICULOWEB", "IDSUBARTICULOWEB", "CANTIDAD", "MERMA", "MOTIVO", "IDTABLEROPRODUCCION", "IDOPERADOR", "IDTIPO");
            $valores = array($row->ID, 0, $row->UNIDADES,0,"''",$idtablero, $_SESSION['IDUSUARIO'],1);

            $json = $conection->insert_table($campos, "INVENTARIOIMPRESION", $valores);

        }
    }
?>