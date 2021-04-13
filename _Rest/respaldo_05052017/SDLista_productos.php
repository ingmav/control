<?php
	include("../clases/conexion.php");
	session_start();
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_SESSION['empresa']);
	//$conexion = $conection->conexion_nexos($_SESSION['empresa']);
	
	if($_POST["accion"] == "index")
	{
		$campos = array("DOCTOS_VE_DET.DOCTO_VE_DET_ID", "ARTICULOS.NOMBRE", "DOCTOS_VE_DET.UNIDADES", "ARTICULOS.UNIDAD_VENTA", "DOCTOS_VE_DET.NOTAS", "TABLEROPRODUCCION.FECHA_ENTREGA",
						"TABLEROPRODUCCION.GF_DISENO",
						"TABLEROPRODUCCION.GF_IMPRESION",
						"TABLEROPRODUCCION.GF_MAQUILAS",
						"TABLEROPRODUCCION.GF_PREPARACION",
						"TABLEROPRODUCCION.GF_INSTALACION",
						"TABLEROPRODUCCION.GF_ENTREGA");
		
		$join = array("ARTICULOS","=", "DOCTOS_VE_DET.ARTICULO_ID", "ARTICULOS.ARTICULO_ID", "UNION",
                      "TABLEROPRODUCCION","=", "DOCTOS_VE_DET.DOCTO_VE_DET_ID", "TABLEROPRODUCCION.DOCTO_VE_DET_ID", "LEFT");
		
		$condicionales = " AND DOCTOS_VE_DET.DOCTO_VE_ID=".$_POST['id']." AND DOCTOS_VE_DET.ROL != 'C'";

		$order = array();
		

		$json = $conection->select_table_advanced2($campos, "DOCTOS_VE_DET", $join, $condicionales, $order, 0);
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "index_Pv")
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

	if($_POST["accion"] == "cargar")
	{
		$campos = array("VERIFICADOR", "GF_DISENO", "GF_IMPRESION", "GF_PREPARACION", "GF_ENTREGA", "GF_INSTALACION", "GF_MAQUILAS", "PRIORIDAD", "NOTA");
		
		$join = array();
		
		$condicionales = " AND DOCTO_VE_DET_ID=".$_POST['id'];

		$order = array();
		
		$counter = $conection->counter("TABLEROPRODUCCION", $join, $condicionales, 0);

		$json = $conection->select_table($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

		$respuesta = array("data"=>$json, "contador"=>$counter, "identificador"=>$_POST['id']);
		$obj = (object) $respuesta;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "counter")
	{
	
		$join = array();
		
		$condicionales = " AND FECHA > '2014-11-01'";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}
		
		$json = $conection->counter("DOCTOS_VE", $join, $condicionales, 0);
		
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
				$valores = array($_POST['DOCTO_PV_ID'], $llave, "'".$_POST['fecha_entrega_'.$llave]." ".$_POST['hora_entrega_'.$llave]."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], $_POST['instalacion_'.$llave], 1);
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
				$valores = array($_POST['DOCTO_PV_ID'], $llave, "'".$_POST['fecha_entrega_'.$llave]." ".$_POST['hora_entrega_'.$llave]."'", "'".$_POST['notas_'.$llave]."'", "'".$_POST['notas_'.$llave]."'",$_SESSION['IDUSUARIO'], $_POST['diseno_'.$llave], $_POST['impresion_'.$llave], $_POST['preparacion_'.$llave], $_POST['entrega_'.$llave], $_POST['instalacion_'.$llave], 1);
					
				$json = $conection->insert_table($campos, "PRODUCCIONPV", $valores);

			}	
			$count++;
		}

		
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "save")
	{
		$conection = new conexion_nexos($_SESSION['empresa']);
		
		$contador = count($_POST['procesos']);
		$count = 0;
		$join = array();
		
		while($contador > $count)
		{
			$existe = 0;
			$join = array();

			$condicionales = " AND TABLEROPRODUCCION.DOCTO_VE_DET_ID='".$_POST['procesos'][$count]."'";
			$existe = $conection->counter("TABLEROPRODUCCION", $join, $condicionales, 0);
			
			if($existe->PAGINADOR > 0)
			{
				$diseno = $_POST["diseno_".$_POST['procesos'][$count]]=="1" ? 1: 0 ;
				$impresion = $_POST["impresion_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$instalacion = $_POST["instalacion_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$entrega = $_POST["entrega_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$maquilas = $_POST["maquilas_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$preparacion = $_POST["preparacion_".$_POST['procesos'][$count]]=="1" ? 1: 0;

				$nota = $_POST["notas_".$_POST['procesos'][$count]];


				$campos = array("VERIFICADOR", "GF_DISENO", "GF_IMPRESION","GF_PREPARACION", "GF_INSTALACION", "GF_ENTREGA", "GF_MAQUILAS", "PRIORIDAD", "NOTA");
				$valores = array(1, $diseno, $impresion, $preparacion, $instalacion, $entrega, $maquilas, 1, "'".utf8_decode($nota)."'");

				if(isset($_POST['cerrar']))
				{
					array_push($campos, "CERRAR_SELECCION");
					array_push($valores, 1);
					
				}
				
				$json = $conection->select_table(array("ID"), "TABLEROPRODUCCION", array(), " AND DOCTO_VE_DET_ID=".$_POST['procesos'][$count], array(), 0);
				$id = "TABLEROPRODUCCION.ID =".$json[0]['ID'];


				$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, $id);

			}else
			{
				$diseno = $_POST["diseno_".$_POST['procesos'][$count]]=="1" ? 1: 0 ;
				
				$impresion = $_POST["impresion_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$instalacion = $_POST["instalacion_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$entrega = $_POST["entrega_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$maquilas = $_POST["maquilas_".$_POST['procesos'][$count]]=="1" ? 1: 0;
				$preparacion = 1;
				$nota = $_POST["notas_".$_POST['procesos'][$count]];

                $fecha_entrega = $_POST["fecha_entrega_".$_POST['procesos'][$count]];
                $hora_entrega = $_POST["hora_entrega_".$_POST['procesos'][$count]];

				$campos = array("DOCTO_VE_ID","DOCTO_VE_DET_ID","VERIFICADOR", "GF_DISENO", "GF_IMPRESION", "GF_ENTREGA", "GF_MAQUILAS", "GF_INSTALACION", "PRIORIDAD", "NOTA", "FECHA", "GF_PREPARACION", "FECHA_ENTREGA");

				$valores = array($_POST['DOCTO_VE_ID'],$_POST['procesos'][$count],1, $diseno, $impresion, $entrega, $maquilas, $instalacion, 1, "'".utf8_decode($nota)."'", "'".date("Y-m-d H:i:s")."'", $preparacion, "'".$fecha_entrega." ".$hora_entrega."'");

				if(isset($_POST['cerrar']))
				{
					array_push($campos, "CERRAR_SELECCION");
					array_push($valores, 1);
					
				}
				
				$json = $conection->insert_table($campos, "TABLEROPRODUCCION", $valores);
				$arreglo = array();
			}
			$count++;
		}
		
		/*if(isset($_POST['cerrar']))
		{

			$campos = array("CERRAR_SELECCION");
			$valores = array(1);
			
			$json = $conection->select_table($campos, "TABLEROPRODUCCION", array(), " AND DOCTO_VE_ID=".$_POST['DOCTO_VE_ID'], array(), 0);
			print_r($json);
			//$json = $conection->update_table($campos, "TABLEROPRODUCCION", $valores, " DOCTO_VE_ID=".$_POST['DOCTO_VE_ID']);
		}*/

		$conection->delete_of_table("TABLEROPRODUCCION", " DOCTO_VE_DET_ID NOT IN ", $_POST['procesos'], " and DOCTO_VE_ID=".$_POST['DOCTO_VE_ID']);
		
		
		
		$obj = (object) $json;
		echo json_encode($obj);
		$conection = null;
		
	}
	$conexion = null;
?>