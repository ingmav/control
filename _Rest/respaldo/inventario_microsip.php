<?php
header("Content-Type: text/html;charset=utf-8");
include("../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');
session_start();

if($_POST["accion"] == "index")
{

	$conection = new conexion_nexos(2);

	$consulta = "";
	
	if(isset($_POST['filtroarticulo']))
	{
		$consulta .= " AND (ca.clave_articulo like '%".$_POST['filtroarticulo']."%'  or upper(a.nombre) like '%".$_POST['filtroarticulo']."%') ";
	}

	if(isset($_POST['filtro_grupo']) && is_numeric($_POST['filtro_grupo']))
	{
		$consulta .= " AND A.LINEA_ARTICULO_ID=".$_POST['filtro_grupo']." ";
	}

	$query = "select a.articulo_id, 
	ca.clave_articulo, 
	a.nombre ,
	(select count(*) from MS_RELACION where articulo_id=a.articulo_id and ESTATUS=0) as cantidad_insumos
	from articulos a, claves_articulos ca
where a.articulo_id = ca.articulo_id
and a.estatus='A' ".$consulta." 
and a.articulo_id not in (select articulo_id from MS_NO_INVENTARIO)
order by ca.clave_articulo, a.nombre";
		
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$indice = count($arreglo);
		$arreglo[$indice]['ID'] = $row->ARTICULO_ID;
		$arreglo[$indice]['CLAVE'] = $row->CLAVE_ARTICULO;
		$arreglo[$indice]['NOMBRE'] = strtoupper(utf8_encode($row->NOMBRE));
		$arreglo[$indice]['CANTIDAD_INSUMOS'] = $row->CANTIDAD_INSUMOS;
	}

	$obj = (object) $arreglo;
	echo json_encode($obj);
	$conection = null;
}

if($_POST["accion"] == "carga_insumos")
{
	$conection = new conexion_nexos(2);


	$query = " select MR.ID, MA.NOMBRE_ARTICULO, MR.CANTIDAD, MT.DESCRIPCION from MS_RELACION MR, MS_ARTICULOS MA, MS_TIPO_BAJA MT
 WHERE MR.MS_ARTICULO_ID=MA.ID
 AND MT.ID_BAJA=MR.MS_TIPO_BAJA_ID
 AND MR.ARTICULO_ID =".$_POST['ID']." AND MR.ESTATUS=0";
		
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$indice = count($arreglo);
		$arreglo[$indice]['ID'] = $row->ID;
		$arreglo[$indice]['NOMBRE'] = utf8_encode($row->NOMBRE_ARTICULO);
		$arreglo[$indice]['CANTIDAD'] = $row->CANTIDAD;
		$arreglo[$indice]['BAJA'] = $row->DESCRIPCION;
	}

	$obj = (object) $arreglo;
	echo json_encode($obj);
	$conection = null;
}

if($_POST["accion"] == "catalogo_grupos")
{
	$conection2 = new conexion_nexos(2);
		
	$query = "select
	ID,
	NOMBRE_ARTICULO
	FROM
	MS_ARTICULOS
	where ESTATUS=0 ORDER BY NOMBRE_ARTICULO
	".$consulta1;
    
    $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
   	$json = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$indice = count($json);
		$json[$indice]['ID'] 					= $row->ID;
		$json[$indice]['DESCRIPCION'] 			= utf8_encode($row->NOMBRE_ARTICULO);
	}

	$query = "select
	LA.LINEA_ARTICULO_ID,
	LA.NOMBRE
	FROM
	LINEAS_ARTICULOS LA
	ORDER BY NOMBRE
	".$consulta1;
    
    $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
   	$json2 = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$indice = count($json2);
		$json2[$indice]['ID'] 					= $row->LINEA_ARTICULO_ID;
		$json2[$indice]['DESCRIPCION'] 			= utf8_encode($row->NOMBRE);
	}

	$query = "select
	ID_BAJA,
	DESCRIPCION
	FROM
	MS_TIPO_BAJA
	ORDER BY ID_BAJA
	".$consulta1;
    
    $result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
   	$json3 = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$indice = count($json3);
		$json3[$indice]['ID'] 					= $row->ID_BAJA;
		$json3[$indice]['DESCRIPCION'] 			= utf8_encode($row->DESCRIPCION);
	}


	$conection2 = null;

	$obj = (object) array("GRUPOS_INSUMOS" => $json, "LINEAS" => $json2, "TIPO_BAJA" => $json3);
	echo json_encode($obj);
}

if($_POST["accion"] == "relacion_articulo")
{
	$conection2 = new conexion_nexos(2);

	/*$query = "select TIPO_COMBO FROM MS_COMBOS WHERE ID=".$_POST['grupo_insumo'];
		
	$result = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	$tipo = 0;
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$tipo = $row->TIPO_COMBO;

	}*/
	$campos = array("ARTICULO_ID", "MS_ARTICULO_ID", "ESTATUS", "CANTIDAD", "MS_TIPO_BAJA_ID");
	$valores = array($_POST['id_articulo'],$_POST['grupo_insumo'], 0 ,$_POST['cantidad_insumo'], $_POST['baja_insumo']);

	$json = $conection2->insert_table($campos, "MS_RELACION", $valores);

	
	$conection2 = null;

	$obj = (object) array("ID"=>$_POST['id_articulo']);
	echo json_encode($obj);
}

if($_POST["accion"] == "eliminar_articulo")
{
	$conection2 = new conexion_nexos(2);

	$campos = array("ARTICULO_ID");
	$valores = array($_POST['ID']);

	$json = $conection2->insert_table($campos, "MS_NO_INVENTARIO", $valores);
	
	$conection2 = null;

	$obj = (object) $json;
	echo json_encode($obj);
}

if($_POST["accion"] == "quitar_articulo")
{
	$conection2 = new conexion_nexos(2);
	$campos = array("ESTATUS", "FECHA_BAJA");
	$valores = array(1, "'".DATE("Y-m-d H:i:s")."'");
	$id = " ID in (".implode(",",$_POST['bajas']).")";
	
	$json = $conection2->update_table($campos, "MS_RELACION", $valores, $id);

    $obj = (object) array("ID"=>$_POST['id_articulo']);
	echo json_encode($obj);
    $conection2 = null;
	
}

if($_POST["accion"] == "agregar_insumo_catalogo")
{
	$conection2 = new conexion_nexos(2);
	$campos = array("NOMBRE_ARTICULO", "MS_FAMILIA_ID", "CANTIDAD_MINIMA");
	$valores = array("'".$_POST['insumo']."'",$_POST['familia'], $_POST['minimo']);

	$json = $conection2->insert_table($campos, "MS_ARTICULOS", $valores);

	
	$conection2 = null;

	$obj = (object) $json;
	echo json_encode($obj);
	
}

