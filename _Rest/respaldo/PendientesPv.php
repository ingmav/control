<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_SESSION['empresa']);


	if($_POST["accion"] == "index")
	{

		$candado = "";

		$campos = array("DOCTOS_PV.DOCTO_PV_ID",
                        "DOCTOS_PV.FOLIO",
                        "DOCTOS_PV.FECHA",
                        "CLIENTES.NOMBRE",
                        "DOCTOS_PV.IMPORTE_NETO",
                        "DOCTOS_PV.TOTAL_IMPUESTOS",
                        "OPERADOR.ALIAS",
                        "PRODUCCIONPV.IDESTATUS",
                        "PRODUCCIONPV.DESCRIPCION",
                        "PRODUCCIONPV.ACTIVACION"
                        );
		
		$join = array(
                      "PRODUCCIONPV", "=", "PRODUCCIONPV.DOCTO_PV_ID", "DOCTOS_PV.DOCTO_PV_ID", "LEFT",
                      "OPERADORDEPARTAMENTO","=", "PRODUCCIONPV.IDOPERADOR", "OPERADORDEPARTAMENTO.ID", "LEFT",
                      "OPERADOR","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR", "LEFT",
                      "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_PV.CLIENTE_ID", "LEFT",
                      );

        $condicionales = "";
        $condicionales2 = "";
        $order = array();
		$condicionales = " DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C'  ".$candado;


        if($_SESSION['IDUSUARIO'] == 16)
            $condicionales = " DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C' AND OPERADORDEPARTAMENTO.IDOPERADOR=".$_SESSION['IDUSUARIO'].$candado;

        if($_POST['fecha'] != "")
        {
            $condicionales2 .= " AND DOCTOS_PV.FECHA like '%".$_POST['fecha']."%'";
        }
		if($_POST['foliofiltro'] != "")
        {
            $condicionales2 .= " AND DOCTOS_PV.FOLIO like '%".$_POST['foliofiltro']."%'";
        }

        if($_POST['clientefiltro'] != "")
        {
            $condicionales2 .= " AND CLIENTES.NOMBRE like '%".$_POST['clientefiltro']."%'";
        }

        $condicionales2 .= " AND (PRODUCCIONPV.IDESTATUS IS NULL OR PRODUCCIONPV.IDESTATUS=1 )";

        $condicionales.= $condicionales2;

        
        $query = "select
		DOCTOS_PV.DOCTO_PV_ID,
		DOCTOS_PV.FOLIO,
		DOCTOS_PV.FECHA,
		(SELECT NOMBRE FROM CLIENTES WHERE CLIENTES.CLIENTE_ID = DOCTOS_PV.CLIENTE_ID) AS NOMBRE_CLIENTE,
		DOCTOS_PV.IMPORTE_NETO,
		DOCTOS_PV.TOTAL_IMPUESTOS,
		(SELECT ALIAS FROM OPERADOR WHERE OPERADOR.ID = PRODUCCIONPV.IDOPERADOR) AS NOMBRE_OPERADOR,
		PRODUCCIONPV.IDESTATUS,
		PRODUCCIONPV.DESCRIPCION,
		PRODUCCIONPV.ACTIVACION
		from DOCTOS_PV
       	LEFT JOIN PRODUCCIONPV ON PRODUCCIONPV.DOCTO_PV_ID = DOCTOS_PV.DOCTO_PV_ID 
        LEFT JOIN OPERADORDEPARTAMENTO ON PRODUCCIONPV.IDOPERADOR = OPERADORDEPARTAMENTO.ID
        WHERE ".$condicionales;
        
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 				= $row->DOCTO_PV_ID;
			$json[$indice]['FOLIO'] 			= $row->FOLIO;
			$json[$indice]['FECHA'] 			= $row->FECHA;
			$json[$indice]['NOMBRE_CLIENTE'] 	= utf8_encode($row->NOMBRE_CLIENTE);
			$json[$indice]['IMPORTE_NETO'] 		= $row->IMPORTE_NETO;
			$json[$indice]['TOTAL_IMPUESTOS'] 	= $row->TOTAL_IMPUESTOS;
			$json[$indice]['NOMBRE_OPERADOR'] 	= ($row->NOMBRE_OPERADOR != null) ? $row->NOMBRE_OPERADOR : '';
			$json[$indice]['IDESTATUS'] 		= $row->IDESTATUS;
			$json[$indice]['DESCRIPCION'] 		= utf8_encode($row->DESCRIPCION);
			$json[$indice]['ACTIVACION'] 		= $row->ACTIVACION;
		}

		
		//$json = $conection->select_table_advanced($campos, "DOCTOS_PV", $join, $condicionales, $order, 0);
		
		$index = 0;
		$json_selected = array();
		while($index < count($json))
		{
            $campos2 = array("NOMBRE",
                "UNIDADES"
            );

            $join2 = array("ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_PV_DET.ARTICULO_ID",
            				"CLAVES_ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "CLAVES_ARTICULOS.ARTICULO_ID");

            
            $order2 = array();
            $condicionales2 = " AND DOCTOS_PV_DET.DOCTO_PV_ID=".$json[$index]['ID'];
            /*$condicionales2 .= " AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149) 
            					 AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05')";*/
			
			$condicionales2 .= " AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149) 
            					 OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05'))";
            //echo $condicionales;
            $json2 = $conection->select_table($campos2, "DOCTOS_PV_DET", $join2, $condicionales2, $order2, 0);

            if(count($json2) > 0)
            {

            	$indice_selected = count($json_selected);
            	$json_selected[$indice_selected] = $json[$index];	
	            $json_selected[$indice_selected]['MATERIALES'] = $json2;	
	          	
	            //Observaciones
	          	
				$join_observacion = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "PVOBSERVACION.DOCTO_PV_ID");
				
				$condicionales_observacion = " AND PVOBSERVACION.DOCTO_PV_ID=".$json[$index]['ID'];

				$json_observacion = $conection->counter("PVOBSERVACION", $join_observacion, $condicionales_observacion, 0);

				$json_selected[$indice_selected]['OBSERVACIONES'] = $json_observacion->PAGINADOR;	
			}
			//Fin observaciones
            $index++;
		}

		$conection = null;	
		$obj = (object) $json_selected;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "activarActividad")
	{
			
		$condicionales = " AND PRODUCCIONPV.DOCTO_PV_ID=".$_POST['id'];

		$json = $conection->select_table_advanced_with_counter(array("ACTIVACION"), array("ACTIVACION"), "PRODUCCIONPV", array(), $condicionales, array(), 0, NULL, 2);	
	    
		/**/
		if(count($json) > 0)
        {
            $campos = array("FECHAHORA", "ACTIVACION");
            $activacion = 0;
            $respuesta = 0;
            if($json[0]['ACTIVACION'] == 0)
            	$activacion = 1;
            else
            	$activacion = 0;

            
            $valores = array("'".date("Y-m-d H:i:s")."'", $activacion);
            $id = "PRODUCCIONPV.DOCTO_PV_ID = ".$_POST['id'];
            $json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);

            $obj = (object) array("data" => $activacion);
            echo json_encode($obj);
        }else
        {
            $campos = array("DOCTO_PV_ID", "FECHAHORA", "IDESTATUS", "IDUSUARIO", "ACTIVACION");
            $valores = array($_POST['id'], "'".date("Y-m-d H:i:s")."'", 1, $_SESSION['IDUSUARIO'], 1);

            $json = $conection->insert_table($campos, "PRODUCCIONPV", $valores, $_SESSION['IDUSUARIO']);

            $obj = (object) array("data"=>1);
            echo json_encode($obj);
        }
		/**/	
		
	}

	if($_POST["accion"] == "observaciones")
	{
		$campos = array("PVOBSERVACION.OBSERVACION", "PVOBSERVACION.FECHAOBSERVACION");
		
		$join = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "PVOBSERVACION.DOCTO_PV_ID");
		
		$condicionales = " AND PVOBSERVACION.DOCTO_PV_ID=".$_POST['id']." AND PVOBSERVACION.IDDEPARTAMENTO=".$_POST['departamento'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "PVOBSERVACION", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	

	if($_POST["accion"] == "saveObservacion")
	{
		if(strlen(trim($_POST['observacion'])) > 0)
		{
			$campos = array("DOCTO_PV_ID", "OBSERVACION", "IDDEPARTAMENTO");
			$valores = array($_POST['id'], "'".utf8_decode($_POST['observacion'])."'", $_POST['departamento']);
			
			$json = $conection->insert_table($campos, "PVOBSERVACION", $valores);
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
		
		$condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=10 ";
		
		$order = array();
		
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 0);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "saveTurnar")
	{

		$campos = array( "FECHAHORA", "DESCRIPCION", "IDUSUARIO");

		if($_POST['departamento'] == 2)
        	array_push($campos, "OPERADOR_DISENO_GF");

        if($_POST['departamento'] == 3){
        	array_push($campos, "OPERADOR_IMPRESION_GF");
        }

        if($_POST['departamento'] == 9){
        	array_push($campos, "OPERADOR_PREPARACION_GF");
        }

        if($_POST['departamento'] == 6){
        	array_push($campos, "OPERADOR_ENTREGA_GF");
        }

        if($_POST['departamento'] == 4){
        	array_push($campos, "OPERADOR_INSTALACION_GF");
        }

		$valores = array("'".date("Y-m-d H:i:s")."'", "'".$_POST['observacionFinalizado']."'",$_SESSION['IDUSUARIO'], $_POST['EmpleadoFinalizar']);
		$id = "PRODUCCIONPV.DOCTO_PV_DET_ID = ".$_POST['idtablerofinalizar'];
		$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);
		
		$obj = (object) $json;
		echo json_encode($obj);
	
	}


    if($_POST["accion"] == "save")
    {
        $conection = new conexion_nexos($_SESSION['empresa']);

        $campos = array("FECHAHORA", "IDOPERADOR", "DESCRIPCION", "IDUSUARIO", "ACTIVACION");
        
        if($_POST['departamento'] == 2)
        	array_push($campos, "DISENO_GF", "F_DISENO_GF");

        if($_POST['departamento'] == 3){
        	array_push($campos, "IMPRESION_GF", "F_IMPRESION_GF");
        }

        if($_POST['departamento'] == 9){
        	array_push($campos, "PREPARACION_GF", "F_PREPARACION_GF");
        }

        if($_POST['departamento'] == 6){
        	array_push($campos, "ENTREGA_GF", "F_ENTREGA_GF");
        }

        if($_POST['departamento'] == 4){
        	array_push($campos, "INSTALACION_GF", "F_INSTALACION_GF");
        }

        $valores = array("'".date("Y-m-d H:i:s")."'", $_POST['EmpleadoFinalizar'], "'".$_POST['observacionFinalizado']."'", $_SESSION['IDUSUARIO'],0, "2", "'".date("Y-m-d H:i:s")."'");
        $id = "PRODUCCIONPV.DOCTO_PV_DET_ID = ".$_POST['idtablerofinalizar'];
        $json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);

        
    }

	if($_POST["accion"] == "cancelar")
	{
        $campos = array("PRODUCCIONPV.ID"
        	, "PRODUCCIONPV.GF_DISENO"
        	, "PRODUCCIONPV.DISENO_GF"
        	, "PRODUCCIONPV.GF_IMPRESION"
        	, "PRODUCCIONPV.IMPRESION_GF"
        	, "PRODUCCIONPV.GF_PREPARACION"
        	, "PRODUCCIONPV.PREPARACION_GF"
        	, "PRODUCCIONPV.GF_ENTREGA"
        	, "PRODUCCIONPV.ENTREGA_GF"
        	, "PRODUCCIONPV.GF_INSTALACION"
        	, "PRODUCCIONPV.INSTALACION_GF");

        $join = array();

        $condicionales = " AND PRODUCCIONPV.DOCTO_PV_DET_ID='".$_POST['idtablerocancelar']."' ";

        $order = array();

        $arreglo = $conection->select_table($campos, "PRODUCCIONPV", $join, $condicionales, $order, 0);

        if($arreglo[0]['PRODUCCIONPV.GF_ENTREGA'] == 1 && $arreglo[0]['PRODUCCIONPV.ENTREGA_GF'] == 2)
    	{
    		$campos = array("ENTREGA_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCIONPV.ID = ".$arreglo[0]['PRODUCCIONPV.ID'];
        	$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCIONPV.GF_INSTALACION'] == 1 && $arreglo[0]['PRODUCCIONPV.INSTALACION_GF'] == 2)
    	{
    		$campos = array("INSTALACION_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCIONPV.ID = ".$arreglo[0]['PRODUCCIONPV.ID'];
        	$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCIONPV.GF_PREPARACION'] == 1 && $arreglo[0]['PRODUCCIONPV.PREPARACION_GF'] == 2)
    	{
    		$campos = array("PREPARACION_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCIONPV.ID = ".$arreglo[0]['PRODUCCIONPV.ID'];
        	$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCIONPV.GF_IMPRESION'] == 1 && $arreglo[0]['PRODUCCIONPV.IMPRESION_GF'] == 2)
    	{
    		$campos = array("IMPRESION_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCIONPV.ID = ".$arreglo[0]['PRODUCCIONPV.ID'];
        	$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCIONPV.GF_DISENO'] == 1 && $arreglo[0]['PRODUCCIONPV.DISENO_GF'] == 2)
    	{
    		$campos = array("DISENO_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCIONPV.ID = ".$arreglo[0]['PRODUCCIONPV.ID'];
        	$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);
	
    	}
        

        $obj = (object) $json;
        echo json_encode($obj);
        /*if(count($arreglo) > 0)
        {
            $campos = array("IDESTATUS", "FECHAHORACANCELACION", "CANCELACION", "IDUSUARIO");
            $valores = array( 3, "'".date("Y-m-d H:i:s")."'", "'".$_POST['texto']."'", $_SESSION['IDUSUARIO']);
            $id = "PRODUCCIONPV.ID = ".$arreglo[0]['PRODUCCIONPV.ID'];
            $json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);

            $obj = (object) $json;
            echo json_encode($obj);
        }else
        {
            $campos = array("DOCTO_PV_ID", "IDOPERADOR", "IDESTATUS", "FECHAHORACANCELACION", "CANCELACION", "IDUSUARIO");
            $valores = array($_POST['id'], $_POST['EmpleadoFinalizar'], 3, "'".date("Y-m-d H:i:s")."'", "'".$_POST['texto']."'", $_SESSION['IDUSUARIO']);

            $json = $conection->insert_table($campos, "PRODUCCIONPV", $valores);

            $obj = (object) $json;
            echo json_encode($obj);
        }*/


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
		
		
		$campos2 = array("IDPRODUCCION", "FECHAORDEN");
		$valores2 = array($json[0]['PRODUCCION.ID'], "'".date("Y-m-d H:i:s")."'");

		$json2 = $conection2->insert_table($campos2, "ORDENDIA", $valores2);
		
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

    if($_POST['accion'] == "cargainventario")
    {
        $conection = new conexion_nexos($_SESSION['empresa']);

        $query = "select imp.id as ID, a.nombrelinea as nombrelinea, a.nombre as nombrearticulo,  sa.nombre as nombresubarticulo, imp.cantidad, imp.merma, imp.motivo
        from  articulosweb as a
    left join lineas_articulos as l on a.linea_articulo_id=l.linea_articulo_id
    left join inventarioimpresion as imp on a.id=imp.idarticuloweb
    left join subarticulosweb as  sa on imp.idarticuloweb=sa.idarticuloweb and imp.idsubarticuloweb=sa.id and a.LINEA_ARTICULO_ID!=6284
    where a.borrado is null and imp.idtableroproduccion=".$_POST['idpv']." AND imp.IDTIPO=2
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


        $obj = (object) $arreglo;
        echo json_encode($obj);
    }
    if($_POST['accion'] == "cargainventarioutilizado")
    {
        $arreglo = array();

        $arreglo = calculaArticuloUtilizado(2, $_POST['idpv']);

        $obj = (object) $arreglo;
        echo json_encode($obj);
    }

function calculaArticuloUtilizado($empresa, $idtablero)
{
    $conection = new conexion_nexos($empresa);

    $query = "select a.nombre as nombre, sum(pvd.unidades * raw.unidades) as unidades, raw.idarticulosweb as id
                    from doctos_pv pv, doctos_pv_det pvd, relarticulosweb raw, articulosweb a
                    where
                    pv.docto_pv_id=pvd.docto_pv_id and pvd.clave_articulo=raw.clave_articulo and raw.idarticulosweb=a.id and
                    pv.docto_pv_id=".$idtablero." and a.linea_articulo_id!='6284'
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

    $query2 = "select idarticuloweb AS id, sum(cantidad) as cantidad
        from inventarioimpresion where idtipo=2 and idtableroproduccion = $idtablero group by idarticuloweb";

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

    
    return $arreglo;
}

if($_POST['accion'] == 'saveInventario')
{
    $conection = new conexion_nexos($_SESSION['empresa']);
    $campos2 = array("IDARTICULOWEB", "IDSUBARTICULOWEB", "CANTIDAD", "MERMA", "MOTIVO", "IDTABLEROPRODUCCION", "IDOPERADOR", "IDTIPO");
    $valores2 = array($_POST['articulo'], $_POST['subarticulo'], $_POST['cantidad'], $_POST['merma'], "'".$_POST['motivo']."'",  $_POST['idproduccion'], $_SESSION['IDUSUARIO'],2);

    $json2 = $conection->insert_table($campos2, "INVENTARIOIMPRESION", $valores2);

    $obj = (object) $json2;
    echo json_encode($obj);
}

if($_POST['accion'] == 'deleteinventario')
{
    $conection = new conexion_nexos($_SESSION['empresa']);
    $id = " INVENTARIOIMPRESION.ID = ".$_POST['idinventario']." AND INVENTARIOIMPRESION.IDTIPO=2";
    $json = $conection->delete_of_table("INVENTARIOIMPRESION", $id, Array());

    $obj = (object) $json;
    echo json_encode($obj);
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

function ReducirInventarioInsumos($empresa, $idtablero)
{
    $conection = new conexion_nexos($empresa);
    $query = "select a.nombre as nombre, sum(dvd.unidades * raw.unidades) as unidades, raw.idarticulosweb as id
                    from doctos_pv dv, doctos_pv_det dvd, relarticulosweb raw, articulosweb a, tableroproduccion tp
                    where
                    dv.docto_pv_id=dvd.docto_pv_id and dvd.clave_articulo=raw.clave_articulo and raw.idarticulosweb=a.id and
                    dv.docto_pv_id=".$idtablero." and a.linea_articulo_id='6284'
                    group by raw.idarticulosweb, a.nombre";

    $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

    $arreglo = array();
    while($row = ibase_fetch_object ($result, IBASE_TEXT))
    {

        $campos = array("IDARTICULOWEB", "IDSUBARTICULOWEB", "CANTIDAD", "MERMA", "MOTIVO", "IDTABLEROPRODUCCION", "IDOPERADOR", "IDTIPO");
        $valores = array($row->ID, 0, $row->UNIDADES,0,"''",$idtablero, $_SESSION['IDUSUARIO'],2);

        $json = $conection->insert_table($campos, "INVENTARIOIMPRESION", $valores);

    }
}
?>