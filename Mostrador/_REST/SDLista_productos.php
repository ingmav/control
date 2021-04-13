<?php
	include("../../clases/conexion.php");
	session_start();
	date_default_timezone_set('America/Mexico_City');
	
	if($_POST["accion"] == "index")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		//$conexion = $conection->conexion_nexos($_SESSION['empresa']);
	
		$query = "select 
produccionpv.id,
doctos_pv.folio,
doctos_pv_det.docto_pv_det_id,
articulos.nombre,
doctos_pv_det.unidades,
articulos.unidad_venta,
produccionpv.notas_proceso,
produccionpv.f_entrega,
produccionpv.gf_diseno,
produccionpv.gf_impresion,
produccionpv.gf_preparacion,
produccionpv.gf_entrega,
produccionpv.gf_instalacion
from doctos_pv
left join produccionpv on produccionpv.docto_pv_id = doctos_pv.docto_pv_id
,doctos_pv_det,  articulos, claves_articulos
where doctos_pv.docto_pv_id=doctos_pv_det.docto_pv_id
and doctos_pv_det.articulo_id=articulos.articulo_id
and articulos.articulo_id = claves_articulos.articulo_id
AND DOCTOS_PV_DET.DOCTO_PV_ID=".$_POST['id']."
AND ARTICULOS.LINEA_ARTICULO_ID NOT IN (2146,2147,2142, 2149, 2143) AND CLAVES_ARTICULOS.CLAVE_ARTICULO NOT IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05', 'CN12')";
		$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 				= $row->ID;
			$json[$indice]['DET_ID'] 			= $row->DOCTO_PV_DET_ID;
			$json[$indice]['FOLIO'] 			= $row->FOLIO;
			$json[$indice]['NOMBRE'] 			= utf8_encode($row->NOMBRE);
			$json[$indice]['UNIDADES'] 			= $row->UNIDADES;
			$json[$indice]['UNIDAD_VENTA'] 		= $row->UNIDAD_VENTA;
			$json[$indice]['NOTAS'] 			= utf8_encode($row->NOTAS_PROCESO);
			$json[$indice]['F_ENTREGA'] 		= $row->F_ENTREGA;
			$json[$indice]['GF_DISENO'] 		= $row->GF_DISENO;
			$json[$indice]['GF_IMPRESION'] 		= $row->GF_IMPRESION;
			$json[$indice]['GF_PREPARACION'] 	= $row->GF_PREPARACION;
			$json[$indice]['GF_ENTREGA'] 		= $row->GF_ENTREGA;
			$json[$indice]['GF_INSTALACION'] 	= $row->GF_INSTALACION;
		}

		$conection = null;
		$obj = (object) $json;
		echo json_encode($obj);
	}
	if($_POST["accion"] == "index_Pv")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		//$conexion = $conection->conexion_nexos($_SESSION['empresa']);
	
		$query = "select 
produccion_dg.id,
doctos_pv.folio,
doctos_pv_det.docto_pv_det_id,
articulos.nombre,
doctos_pv_det.unidades,
articulos.unidad_venta,
produccion_dg.notas_proceso,
produccion_dg.f_entrega,
produccion_dg.gf_diseno,
produccion_dg.gf_impresion,
produccion_dg.gf_preparacion
from doctos_pv
left join produccion_dg on produccion_dg.docto_pv_id = doctos_pv.docto_pv_id
,doctos_pv_det,  articulos, claves_articulos
where doctos_pv.docto_pv_id=doctos_pv_det.docto_pv_id
and doctos_pv_det.articulo_id=articulos.articulo_id
and articulos.articulo_id = claves_articulos.articulo_id
AND DOCTOS_PV_DET.DOCTO_PV_ID=".$_POST['id']."
AND (ARTICULOS.LINEA_ARTICULO_ID IN (2146,2147,2142, 2149) 
            					 OR CLAVES_ARTICULOS.CLAVE_ARTICULO IN ('MSD00','MSD01','MSD02','MSD03','MSD04','MSD05'))";
		$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			$json[$indice]['ID'] 				= $row->ID;
			$json[$indice]['DET_ID'] 			= $row->DOCTO_PV_DET_ID;
			$json[$indice]['FOLIO'] 			= $row->FOLIO;
			$json[$indice]['NOMBRE'] 			= utf8_encode($row->NOMBRE);
			$json[$indice]['UNIDADES'] 			= $row->UNIDADES;
			$json[$indice]['UNIDAD_VENTA'] 		= $row->UNIDAD_VENTA;
			$json[$indice]['NOTAS'] 			= utf8_encode($row->NOTAS_PROCESO);
			$json[$indice]['GF_DISENO'] 		= $row->GF_DISENO;
			$json[$indice]['GF_IMPRESION'] 		= $row->GF_IMPRESION;
			$json[$indice]['GF_PREPARACION'] 	= $row->GF_PREPARACION;
			$json[$indice]['GF_ENTREGA'] 		= $row->GF_ENTREGA;
		}

		$conection = null;
		$obj = (object) $json;
		echo json_encode($obj);
	}

	
	

	if($_POST['accion'] == "savegf")
	{
		
		$conection = new conexion_nexos($_SESSION['empresa']);
		$contador = count($_POST['procesos']);
		$count = 0;
		$join = array();
		
		while($count < $contador)
		{
			$llave = $_POST['procesos'][$count];	
			$join = array();

			$condicionales = " AND PRODUCCIONPV.DOCTO_PV_DET_ID='".$_POST['procesos'][$count]."'";
			$existe = $conection->counter("PRODUCCIONPV", $join, $condicionales, 0);

			if($existe->PAGINADOR > 0)
			{
				$llave = $_POST['procesos'][$count];
				if(!isset($_POST['diseno_'.$llave]))
					$_POST['diseno_'.$llave] = 0;
				if(!isset($_POST['impresion_'.$llave]))
					$_POST['impresion_'.$llave] = 0;
				if(!isset($_POST['preparacion_'.$llave]))
					$_POST['preparacion_'.$llave] = 0;
				if(!isset($_POST['entrega_'.$llave]))
					$_POST['entrega_'.$llave] = 0;
				if(!isset($_POST['instalacion_'.$llave]))
					$_POST['instalacion_'.$llave] = 0;

				$campos = array("DOCTO_PV_ID", "DOCTO_PV_DET_ID", "F_ENTREGA", "NOTAS_PROCESO", "DESCRIPCION", "IDUSUARIO",
								"GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "GF_INSTALACION", "CERRAR_SELECCION");
				$valores = array($_POST['DOCTO_VE_ID'], $llave, "'".$_POST['fecha_entrega_'.$llave]." ".$_POST['hora_entrega_'.$llave]."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], $_POST['instalacion_'.$llave], 1);
				$id = " PRODUCCIONPV.DOCTO_PV_DET_ID='".$_POST['procesos'][$count]."'";
				$json = $conection->update_table($campos, "PRODUCCIONPV", $valores, $id);	
			}else{
				
				
				if(!isset($_POST['diseno_'.$llave]))
					$_POST['diseno_'.$llave] = 0;
				if(!isset($_POST['impresion_'.$llave]))
					$_POST['impresion_'.$llave] = 0;
				if(!isset($_POST['preparacion_'.$llave]))
					$_POST['preparacion_'.$llave] = 0;
				if(!isset($_POST['entrega_'.$llave]))
					$_POST['entrega_'.$llave] = 0;
				if(!isset($_POST['instalacion_'.$llave]))
					$_POST['instalacion_'.$llave] = 0;

				$campos = array("DOCTO_PV_ID", "DOCTO_PV_DET_ID", "F_ENTREGA", "NOTAS_PROCESO", "DESCRIPCION", "IDUSUARIO",
								"GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "GF_INSTALACION", "CERRAR_SELECCION");
				$valores = array($_POST['DOCTO_VE_ID'], $llave, "'".$_POST['fecha_entrega_'.$llave]." ".$_POST['hora_entrega_'.$llave]."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], $_POST['instalacion_'.$llave], 1);
					
				$json = $conection->insert_table($campos, "PRODUCCIONPV", $valores);

			}	
			$count++;
		}
		$conection = null;
		$obj = (object) $json;
		echo json_encode($obj);

	}
	if($_POST['accion'] == "save_pv")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		$contador = count($_POST['procesos']);
		$count = 0;
		$join = array();
		

		while($count < $contador)
		{
			$llave = $_POST['procesos'][$count];	
			$join = array();

			$condicionales = " AND produccion_dg.DOCTO_PV_DET_ID='".$_POST['procesos'][$count]."'";
			$existe = $conection->counter("produccion_dg", $join, $condicionales, 0);

			if($existe->PAGINADOR > 0)
			{
				$llave = $_POST['procesos'][$count];
				if(!isset($_POST['diseno_'.$llave]))
					$_POST['diseno_'.$llave] = 0;
				if(!isset($_POST['impresion_'.$llave]))
					$_POST['impresion_'.$llave] = 0;
				if(!isset($_POST['preparacion_'.$llave]))
					$_POST['preparacion_'.$llave] = 0;
				if(!isset($_POST['entrega_'.$llave]))
					$_POST['entrega_'.$llave] = 0;
				
				$campos = array("DOCTO_PV_ID", "DOCTO_PV_DET_ID", "F_ENTREGA", "NOTAS_PROCESO", "DESCRIPCION", "IDUSUARIO",
								"GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "CERRAR_SELECCION");
				$valores = array($_POST['DOCTO_PV_ID'], $llave, "'".$_POST['fecha_entrega_'.$llave]." ".$_POST['hora_entrega_'.$llave]."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], 1);
				$id = " produccion_dg.DOCTO_PV_DET_ID='".$_POST['procesos'][$count]."'";
				$json = $conection->update_table($campos, "produccion_dg", $valores, $id);	
			}else{
				
				
				if(!isset($_POST['diseno_'.$llave]))
					$_POST['diseno_'.$llave] = 0;
				if(!isset($_POST['impresion_'.$llave]))
					$_POST['impresion_'.$llave] = 0;
				if(!isset($_POST['preparacion_'.$llave]))
					$_POST['preparacion_'.$llave] = 0;
				if(!isset($_POST['entrega_'.$llave]))
					$_POST['entrega_'.$llave] = 0;
				
				$campos = array("DOCTO_PV_ID", "DOCTO_PV_DET_ID", "F_ENTREGA", "NOTAS_PROCESO", "DESCRIPCION", "IDUSUARIO",
								"GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "CERRAR_SELECCION");
				$valores = array($_POST['DOCTO_PV_ID'], $llave, "'".$_POST['fecha_entrega_'.$llave]." ".$_POST['hora_entrega_'.$llave]."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], 1);
					
				$json = $conection->insert_table($campos, "produccion_dg", $valores);

			}	
			$count++;
		}
		$conection = null;
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "delete_multiple_pv")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		$contador = count($_POST['arr']);
		$count = 0;
		$join = array();
		//$_POST['arr'];

		while($count < $contador)
		{
			$llave = $_POST['arr'][$count];	
			$join = array();

				
			if(!isset($_POST['diseno_'.$llave]))
				$_POST['diseno_'.$llave] = 0;
			if(!isset($_POST['impresion_'.$llave]))
				$_POST['impresion_'.$llave] = 0;
			if(!isset($_POST['preparacion_'.$llave]))
				$_POST['preparacion_'.$llave] = 0;
			if(!isset($_POST['entrega_'.$llave]))
				$_POST['entrega_'.$llave] = 0;

			$id_general = 0;

			$query = "select first 1 doctos_pv_det.DOCTO_PV_DET_ID from doctos_pv, doctos_pv_det where doctos_pv.docto_pv_id=doctos_pv_det.docto_pv_id AND DOCTOS_PV_DET.DOCTO_PV_ID=".$llave;
			$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
			$json = array();
			while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$id_general 				= $row->DOCTO_PV_DET_ID;
				$campos = array("DOCTO_PV_ID", "DOCTO_PV_DET_ID", "F_ENTREGA", "NOTAS_PROCESO", "DESCRIPCION", "IDUSUARIO",
							"GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "CERRAR_SELECCION", "FINALIZAR_PROCESO");
				$valores = array($llave, $id_general, "'".date("Y-m-d H:i:s")."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], 1, 1);
					
				$json = $conection->insert_table($campos, "produccion_dg", $valores);
			}		
			$count++;
		}
		$conection = null;
		
		$obj = (object) $json;
		echo json_encode($obj);
	}


	if($_POST['accion'] == "delete_multiple_pv_gf")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		$contador = count($_POST['arr']);
		$count = 0;
		$join = array();
		//$_POST['arr'];

		while($count < $contador)
		{
			$llave = $_POST['arr'][$count];	
			$join = array();

				
			if(!isset($_POST['diseno_'.$llave]))
					$_POST['diseno_'.$llave] = 0;
				if(!isset($_POST['impresion_'.$llave]))
					$_POST['impresion_'.$llave] = 0;
				if(!isset($_POST['preparacion_'.$llave]))
					$_POST['preparacion_'.$llave] = 0;
				if(!isset($_POST['entrega_'.$llave]))
					$_POST['entrega_'.$llave] = 0;
				if(!isset($_POST['instalacion_'.$llave]))
					$_POST['instalacion_'.$llave] = 0;

				

			$id_general = 0;

			$query = "select first 1 doctos_pv_det.DOCTO_PV_DET_ID from doctos_pv, doctos_pv_det where doctos_pv.docto_pv_id=doctos_pv_det.docto_pv_id AND DOCTOS_PV_DET.DOCTO_PV_ID=".$llave;
			$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
			$json = array();
			while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
				$id_general 				= $row->DOCTO_PV_DET_ID;
				$campos = array("DOCTO_PV_ID", "DOCTO_PV_DET_ID", "F_ENTREGA", "NOTAS_PROCESO", "DESCRIPCION", "IDUSUARIO",
								"GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "GF_INSTALACION", "CERRAR_SELECCION", "FINALIZAR_PROCESO");
				$valores = array($llave, $id_general, "'".date("Y-m-d H:i:s")."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], $_POST['instalacion_'.$llave], 1, 1);
					
				$json = $conection->insert_table($campos, "PRODUCCIONPV", $valores);
			}		
			$count++;
		}
		$conection = null;
		
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	$conexion = null;
?>