<?php
session_start();
include("../../../clases/conexion.php");
date_default_timezone_set('America/Mexico_City');
header('Content-type: application/json; charset=utf-8');
    

$action = $_GET['accion'];

if($action == "get_lista")
{
	if(isset($_GET['empresa']))
		$empresa = $_GET['empresa'];

	$conection = new conexion_nexos(2);
	$query = "
	select mc.ms_clientes_id, mc.nombre,  iif( sum(total_importe) is Null, 0, sum(total_importe)) as total, iif(msc.utilizado is Null, 0, msc.utilizado) as puntos_utilizados, iif(msc.mostrador is Null, 0, msc.mostrador) as puntos_mostrador from ms_clientes mc
left join ms_rel_clientes mrc on mc.ms_clientes_id=mrc.ms_cliente_id
left join ms_saldo_cliente msc on mc.ms_clientes_id=msc.ms_cliente_id
left join
(select dv.cliente_id, sum((dv.importe_neto - dv.DSCTO_IMPORTE)) as total_importe from doctos_ve dv
where dv.fecha between '".date("Y")."-01-01' and '".date("Y")."-12-31'
and dv.tipo_docto in ('F', 'R')
and dv.estatus!='C'
AND dv.DOCTO_VE_ID  NOT IN (SELECT DOCTOS_VE_LIGAS.DOCTO_VE_DEST_ID FROM DOCTOS_VE_LIGAS, DOCTOS_VE WHERE DOCTOS_VE_LIGAS.DOCTO_VE_FTE_ID=DOCTOS_VE.DOCTO_VE_ID AND DOCTOS_VE.TIPO_DOCTO='R')
group by dv.cliente_id
union all
select dp.cliente_id, sum(dp.importe_neto - dp.DSCTO_IMPORTE) as total_importe  from doctos_pv dp
where dp.fecha between '".date("Y")."-01-01' and '".date("Y")."-12-31'
and dp.tipo_docto = 'V'
and dp.estatus!='C'
group by dp.cliente_id) as calculo on calculo.cliente_id=mrc.cliente_id
where 1=1
and mrc.cliente_id!=1714
and mc.nombre like '%".strtoupper($empresa)."%'
group by msc.utilizado,msc.mostrador,mc.ms_clientes_id,mc.nombre,mrc.ms_cliente_id";
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['ID'] = $row->MS_CLIENTES_ID;
		$arreglo[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
		$arreglo[$count]['TOTAL'] = $row->TOTAL;
		$arreglo[$count]['UTILIZADOS'] = $row->PUNTOS_UTILIZADOS;
		$arreglo[$count]['MOSTRADOR'] = $row->PUNTOS_MOSTRADOR;

	}
	
	echo json_encode($arreglo);
    $conection = null;
    exit();
}

if($action == "get_ventas")
{
	$conection = new conexion_nexos(2);
	$query = "select dp.docto_pv_id, folio, importe_neto from doctos_pv dp where dp.tipo_docto='V' and dp.fecha='".date("Y-m-d")."' and dp.estatus!='C' and dp.cliente_id='1714' and dp.folio not in (select msdm.folio from ms_detalle_saldo_m msdm where  msdm.APLICADO_PUNTOS=1)";
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['ID'] = $row->DOCTO_PV_ID;
		$arreglo[$count]['FOLIO'] = $row->FOLIO;
		$arreglo[$count]['TOTAL'] = $row->IMPORTE_NETO;
		
		$porcentaje = ($row->IMPORTE_NETO * 0.01);
		$arreglo[$count]['PORCENTAJE'] = $porcentaje;

	}
	
	echo json_encode($arreglo);
    $conection = null;
    exit();
}

if($action == "get_ventas_descuento")
{
	$conection = new conexion_nexos(2);
	$query = "select dp.docto_pv_id, folio, importe_neto from doctos_pv dp where dp.tipo_docto='O' and dp.fecha='".date("Y-m-d")."' and dp.estatus='P' and dp.cliente_id='1714' and (dp.folio not in (select msdm.folio from ms_detalle_saldo_m msdm where (msdm.APLICADO_DESCUENTOS=1 or msdm.APLICADO_PUNTOS=1 and MS_CLIENTE_ID=".$_GET['id'].")))";

	/*$query = "select dp.docto_pv_id, folio, importe_neto from doctos_pv dp where dp.tipo_docto='V' and dp.fecha='2017-10-23'
and dp.estatus!='C' and dp.cliente_id='1714' and
(dp.folio not in (select msdm.folio from ms_detalle_saldo_m msdm where (msdm.APLICADO_DESCUENTOS=1 or msdm.APLICADO_PUNTOS=1 and msdm.ms_cliente_id=14)))";*/
	 
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['ID'] = $row->DOCTO_PV_ID;
		$arreglo[$count]['FOLIO'] = $row->FOLIO;
		$arreglo[$count]['TOTAL'] = $row->IMPORTE_NETO;
		
		$porcentaje = ($row->IMPORTE_NETO * 0.05);
		$arreglo[$count]['PORCENTAJE'] = $porcentaje;

		if($_GET['total_puntos'] > 0)
			$arreglo[$count]['MAX_PUNTOS'] = ($porcentaje > $_GET['total_puntos'] ) ? $_GET['total_puntos']: $porcentaje;
		else
			$arreglo[$count]['MAX_PUNTOS'] = 0;

	}
	
	echo json_encode($arreglo);
    $conection = null;
    exit();
}

if($action == "aplicar_puntos")
{

	$conection = new conexion_nexos(2);
	
	$busqueda = "select first 1 (IMPORTE_NETO) as IMPORTE, FOLIO  from doctos_pv WHERE DOCTO_PV_ID=".$_GET['id'];
	$result_busqueda = ibase_query($conection->getConexion(), $busqueda) or die(ibase_errmsg());	
	$num = 0;
	$resultado = array();
	while ($row = ibase_fetch_object ($result_busqueda, IBASE_TEXT)){
		$resultado['IMPORTE'] = $row->IMPORTE;
		$resultado['FOLIO'] = $row->FOLIO;
	}
	
	$importe_total 	= $resultado['IMPORTE'];
	$importe_total 	= ($importe_total * 0.01);	 

	$get_saldo = "select first 1 MS_SALDO_CLIENTE_ID, MS_CLIENTE_ID, SUBTOTAL, UTILIZADO, TOTAL, MOSTRADOR FROM MS_SALDO_CLIENTE WHERE MS_CLIENTE_ID=".$_GET['cliente_id'];

	$return_saldo = ibase_query($conection->getConexion(), $get_saldo) or die(ibase_errmsg());	
	$saldo = array();
	while ($row = ibase_fetch_object ($return_saldo, IBASE_TEXT)){
		$index = 0;
		$saldo[$index]['ID'] 			= $row->MS_SALDO_CLIENTE_ID;
		$saldo[$index]['CLIENTE_ID'] 	= $row->MS_CLIENTE_ID;
		$saldo[$index]['SUBTOTAL'] 		= $row->SUBTOTAL;
		$saldo[$index]['UTILIZADO'] 	= $row->UTILIZADO;
		$saldo[$index]['TOTAL'] 		= $row->TOTAL;
		$saldo[$index]['MOSTRADOR'] 	= $row->MOSTRADOR;

	}

	if(count($saldo) == 0)
	{
		$insert_saldo = "insert into MS_SALDO_CLIENTE (MS_CLIENTE_ID, SUBTOTAL, UTILIZADO, TOTAL, MOSTRADOR) VALUES(".$_GET['cliente_id'].", 0, 0, 0, ".($importe_total).")";

		ibase_query($conection->getConexion(), $insert_saldo) or die(ibase_errmsg());
	}else
	{
		$update_saldo = "update MS_SALDO_CLIENTE set MOSTRADOR=(MOSTRADOR + ".($importe_total).") where MS_SALDO_CLIENTE_ID=".$saldo[0]['ID'];

		ibase_query($conection->getConexion(), $update_saldo) or die(ibase_errmsg());
	}

	$get_id = "select first 1 MS_SALDO_CLIENTE_ID FROM MS_SALDO_CLIENTE where ms_cliente_id=".$_GET['cliente_id']." order by MS_SALDO_CLIENTE_ID desc";

	$return_id = ibase_query($conection->getConexion(), $get_id) or die(ibase_errmsg());	
	$id_saldo = array();
	while ($row = ibase_fetch_object ($return_id, IBASE_TEXT)){
		$id_saldo['ID'] = $row->MS_SALDO_CLIENTE_ID;

	}

	$insert_saldo = "insert into MS_DETALLE_SALDO_M (MS_CLIENTE_ID, MONTO_TOTAL, TOTAL_PUNTOS, DOCTO_PV_ID, MS_SALDO_CLIENTE_ID, USUARIO_ID, FOLIO, APLICADO_PUNTOS) VALUES(".$_GET['cliente_id'].", ".$resultado['IMPORTE'].", ".$importe_total.", ".$_GET['id'].", ".$id_saldo['ID'].", ".$_SESSION['IDUSUARIO'].", '".$resultado['FOLIO']."', 1)";

	ibase_query($conection->getConexion(), $insert_saldo) or die(ibase_errmsg());

	$arreglo = array();
	echo json_encode($arreglo);
    $conection = null;
    exit();
}

if($action == "descontar_puntos")
{

	$conection = new conexion_nexos(2);
	
	$busqueda = "select first 1 (IMPORTE_NETO) as IMPORTE, FOLIO  from doctos_pv WHERE DOCTO_PV_ID=".$_GET['id'];
	$result_busqueda = ibase_query($conection->getConexion(), $busqueda) or die(ibase_errmsg());	
	$num = 0;
	$resultado = array();
	while ($row = ibase_fetch_object ($result_busqueda, IBASE_TEXT)){
		$resultado['IMPORTE'] = $row->IMPORTE;
		$resultado['FOLIO'] = $row->FOLIO;

	}

	$importe_total 	= $resultado['IMPORTE'];

	if(($resultado['IMPORTE'] * 0.05) <  $_GET['descuento_'.$_GET['id']])
	{
		header("HTTP/1.1 500 Internal Server Error");
		echo "HA EXCEDIDO EL NÚMERO MAXIMO DE PUNTOS A APLICAR, POR FAVOR VERIFIQUE SUS DATOS";
		exit();
		
	}

	///
	$descuento_puntos = $_GET['descuento_'.$_GET['id']];
	$importe_total 	= $resultado['IMPORTE'] - $descuento_puntos;
	
	$query = "update DOCTOS_PV SET TIPO_DSCTO='I', DSCTO_PCTJE=((".($descuento_puntos)."/(IMPORTE_NETO + TOTAL_IMPUESTOS)) * 100), DSCTO_IMPORTE=".$descuento_puntos." WHERE DOCTO_PV_ID=".$_GET['id'];
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
	////
	
	$get_saldo = "select first 1 MS_SALDO_CLIENTE_ID, MS_CLIENTE_ID, SUBTOTAL, UTILIZADO, TOTAL, MOSTRADOR FROM MS_SALDO_CLIENTE WHERE MS_CLIENTE_ID=".$_GET['cliente_id'];

	$return_saldo = ibase_query($conection->getConexion(), $get_saldo) or die(ibase_errmsg());	
	$saldo = array();
	while ($row = ibase_fetch_object ($return_saldo, IBASE_TEXT)){
		$index = 0;
		$saldo[$index]['ID'] 			= $row->MS_SALDO_CLIENTE_ID;
		$saldo[$index]['CLIENTE_ID'] 	= $row->MS_CLIENTE_ID;
		$saldo[$index]['SUBTOTAL'] 		= $row->SUBTOTAL;
		$saldo[$index]['UTILIZADO'] 	= $row->UTILIZADO;
		$saldo[$index]['TOTAL'] 		= $row->TOTAL;
		$saldo[$index]['MOSTRADOR'] 	= $row->MOSTRADOR;

	}

	

	if(count($saldo) == 0)
	{
		$insert_saldo = "insert into MS_SALDO_CLIENTE (MS_CLIENTE_ID, SUBTOTAL, UTILIZADO, TOTAL, MOSTRADOR) VALUES(".$_GET['cliente_id'].", ".$resultado['IMPORTE'].", ".$_GET['descuento_'.$_GET['id']].", ".($resultado['IMPORTE'] - $_GET['descuento_'.$_GET['id']]).", 0)";

		ibase_query($conection->getConexion(), $insert_saldo) or die(ibase_errmsg());
	}else
	{
		$update_saldo = "update MS_SALDO_CLIENTE set UTILIZADO=(UTILIZADO + ".($_GET['descuento_'.$_GET['id']]).") where MS_SALDO_CLIENTE_ID=".$saldo[0]['ID'];

		ibase_query($conection->getConexion(), $update_saldo) or die(ibase_errmsg());
	}

	$get_id = "select first 1 MS_SALDO_CLIENTE_ID FROM MS_SALDO_CLIENTE where ms_cliente_id=".$_GET['cliente_id']." order by MS_SALDO_CLIENTE_ID desc";

	$return_id = ibase_query($conection->getConexion(), $get_id) or die(ibase_errmsg());	
	$id_saldo = array();
	while ($row = ibase_fetch_object ($return_id, IBASE_TEXT)){
		$id_saldo['ID'] = $row->MS_SALDO_CLIENTE_ID;

	}

	$insert_saldo = "insert into MS_DETALLE_SALDO_M (MS_CLIENTE_ID, MONTO_TOTAL, TOTAL_PUNTOS, DOCTO_PV_ID, MS_SALDO_CLIENTE_ID, USUARIO_ID, FOLIO, APLICADO_DESCUENTOS) VALUES(".$_GET['cliente_id'].", ".$resultado['IMPORTE'].", ".$_GET['descuento_'.$_GET['id']].", ".$_GET['id'].", ".$id_saldo['ID'].", ".$_SESSION['IDUSUARIO'].", '".$resultado['FOLIO']."', 1)";

	ibase_query($conection->getConexion(), $insert_saldo) or die(ibase_errmsg());

	$arreglo = array();
	echo json_encode($arreglo);
    $conection = null;
    exit();
}
?>