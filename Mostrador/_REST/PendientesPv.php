<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../../clases/conexion.php");
	include("../../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_SESSION['empresa']);

	if($_POST["accion"] == "activarActividad")
	{
			
		$condicionales = " AND PRODUCCION_DG.DOCTO_PV_ID=".$_POST['id'];

		$json = $conection->select_table_advanced_with_counter(array("ACTIVACION"), array("ACTIVACION"), "PRODUCCION_DG", array(), $condicionales, array(), 0, NULL, 2);	
	    
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
            $id = "PRODUCCION_DG.DOCTO_PV_ID = ".$_POST['id'];
            $json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);

            $obj = (object) array("data" => $activacion);
            echo json_encode($obj);
        }else
        {
            $campos = array("DOCTO_PV_ID", "FECHAHORA", "IDESTATUS", "IDUSUARIO", "ACTIVACION");
            $valores = array($_POST['id'], "'".date("Y-m-d H:i:s")."'", 1, $_SESSION['IDUSUARIO'], 1);

            $json = $conection->insert_table($campos, "PRODUCCION_DG", $valores, $_SESSION['IDUSUARIO']);

            $obj = (object) array("data"=>1);
            echo json_encode($obj);
        }
		/**/	
		
	}

	if($_POST["accion"] == "observaciones")
	{
		$campos = array("DGOBSERVACION.OBSERVACION", "DGOBSERVACION.FECHAOBSERVACION");
		
		$join = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "DGOBSERVACION.DOCTO_PV_ID");
		
		$condicionales = " AND DGOBSERVACION.DOCTO_PV_ID=".$_POST['id']." AND DGOBSERVACION.IDDEPARTAMENTO=".$_POST['departamento'];
		
		$order = array();
		
		$json = $conection->select_table($campos, "DGOBSERVACION", $join, $condicionales, $order, 0);
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
			
			$json = $conection->insert_table($campos, "DGOBSERVACION", $valores);
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
		$id = "PRODUCCION_DG.DOCTO_PV_DET_ID = ".$_POST['idtablerofinalizar'];
		$json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);
		
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
        $id = "PRODUCCION_DG.DOCTO_PV_DET_ID = ".$_POST['idtablerofinalizar'];
        $json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);

        $obj = (object) $json;
        echo json_encode($obj);

        
    }

	if($_POST["accion"] == "cancelar")
	{
        $campos = array("PRODUCCION_DG.ID"
        	, "PRODUCCION_DG.GF_DISENO"
        	, "PRODUCCION_DG.DISENO_GF"
        	, "PRODUCCION_DG.GF_IMPRESION"
        	, "PRODUCCION_DG.IMPRESION_GF"
        	, "PRODUCCION_DG.GF_PREPARACION"
        	, "PRODUCCION_DG.PREPARACION_GF"
        	, "PRODUCCION_DG.GF_ENTREGA"
        	, "PRODUCCION_DG.ENTREGA_GF"
        	, "PRODUCCION_DG.GF_INSTALACION"
        	, "PRODUCCION_DG.INSTALACION_GF");

        $join = array();

        $condicionales = " AND PRODUCCION_DG.DOCTO_PV_DET_ID='".$_POST['idtablerocancelar']."' ";

        $order = array();

        $arreglo = $conection->select_table($campos, "PRODUCCION_DG", $join, $condicionales, $order, 0);

        if($arreglo[0]['PRODUCCION_DG.GF_ENTREGA'] == 1 && $arreglo[0]['PRODUCCION_DG.ENTREGA_GF'] == 2)
    	{
    		$campos = array("ENTREGA_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCION_DG.ID = ".$arreglo[0]['PRODUCCION_DG.ID'];
        	$json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCION_DG.GF_INSTALACION'] == 1 && $arreglo[0]['PRODUCCION_DG.INSTALACION_GF'] == 2)
    	{
    		$campos = array("INSTALACION_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCION_DG.ID = ".$arreglo[0]['PRODUCCION_DG.ID'];
        	$json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCION_DG.GF_PREPARACION'] == 1 && $arreglo[0]['PRODUCCION_DG.PREPARACION_GF'] == 2)
    	{
    		$campos = array("PREPARACION_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCION_DG.ID = ".$arreglo[0]['PRODUCCION_DG.ID'];
        	$json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCION_DG.GF_IMPRESION'] == 1 && $arreglo[0]['PRODUCCION_DG.IMPRESION_GF'] == 2)
    	{
    		$campos = array("IMPRESION_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCION_DG.ID = ".$arreglo[0]['PRODUCCION_DG.ID'];
        	$json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);
	
    	}else if($arreglo[0]['PRODUCCION_DG.GF_DISENO'] == 1 && $arreglo[0]['PRODUCCION_DG.DISENO_GF'] == 2)
    	{
    		$campos = array("DISENO_GF", "ACTIVACION");
	        $valores = array(1, 0);
    	    $id = "PRODUCCION_DG.ID = ".$arreglo[0]['PRODUCCION_DG.ID'];
        	$json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);
	
    	}
        

        $obj = (object) $json;
        echo json_encode($obj);
        /*if(count($arreglo) > 0)
        {
            $campos = array("IDESTATUS", "FECHAHORACANCELACION", "CANCELACION", "IDUSUARIO");
            $valores = array( 3, "'".date("Y-m-d H:i:s")."'", "'".$_POST['texto']."'", $_SESSION['IDUSUARIO']);
            $id = "PRODUCCION_DG.ID = ".$arreglo[0]['PRODUCCION_DG.ID'];
            $json = $conection->update_table($campos, "PRODUCCION_DG", $valores, $id);

            $obj = (object) $json;
            echo json_encode($obj);
        }else
        {
            $campos = array("DOCTO_PV_ID", "IDOPERADOR", "IDESTATUS", "FECHAHORACANCELACION", "CANCELACION", "IDUSUARIO");
            $valores = array($_POST['id'], $_POST['EmpleadoFinalizar'], 3, "'".date("Y-m-d H:i:s")."'", "'".$_POST['texto']."'", $_SESSION['IDUSUARIO']);

            $json = $conection->insert_table($campos, "PRODUCCION_DG", $valores);

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