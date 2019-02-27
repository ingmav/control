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
	$query = "select mc.clave, mc.fecha_nacimiento, mc.nombre, mc.ms_clientes_id, (select count(*) from ms_rel_clientes where mc.ms_clientes_id=ms_cliente_id) as relacion from ms_clientes mc
	WHERE mc.BORRADO_AL IS NULL
	AND mc.nombre like '%".strtoupper($empresa)."%'";
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$count = count($campos);
	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['ID'] = $row->MS_CLIENTES_ID;
		$arreglo[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
		$arreglo[$count]['CLAVE'] = utf8_encode($row->CLAVE);
		$arreglo[$count]['FECHA_NACIMIENTO'] = $row->FECHA_NACIMIENTO;
		$arreglo[$count]['CUENTAS'] = $row->RELACION;
	}
	
	echo json_encode($arreglo);
    $conection = null;
    exit();
}


if($action == "get_rel_cliente")
{
	$conection = new conexion_nexos(2);
	$query = "select mc.clave, mc.fecha_nacimiento, mc.nombre, mc.ms_clientes_id, (select count(*) from ms_rel_clientes where mc.ms_clientes_id=ms_cliente_id) as relacion from ms_clientes mc
	WHERE mc.BORRADO_AL IS NULL
	AND mc.ms_clientes_id=".$_GET['id'];
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['ID'] = $row->MS_CLIENTES_ID;
		$arreglo[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
		$arreglo[$count]['CLAVE'] = utf8_encode($row->CLAVE);
		$arreglo[$count]['FECHA_NACIMIENTO'] = $row->FECHA_NACIMIENTO;
		$arreglo[$count]['CUENTAS'] = $row->RELACION;
	}

	$query = "select c.cliente_id, cc.clave_cliente, c.nombre, sum(total_importe) AS importe from clientes c, claves_clientes cc,
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
group by dp.cliente_id) as calculo
where calculo.cliente_id=c.cliente_id
and c.cliente_id=cc.cliente_id
and c.estatus!='B'
and c.cliente_id not in (select cliente_id from ms_rel_clientes)
group by c.cliente_id, cc.clave_cliente, c.nombre";
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$contador = 0;
	$arreglo2 = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo2);
		$arreglo2[$count]['ID'] = $row->CLIENTE_ID;
		$arreglo2[$count]['CLAVE'] = $row->CLAVE_CLIENTE;
		$arreglo2[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
		$arreglo2[$count]['IMPORTE'] = $row->IMPORTE;
	}

	$query = "select cc.clave_cliente, c.nombre from clientes c, claves_clientes cc, ms_rel_clientes mrc
	where c.cliente_id=cc.cliente_id
	and c.cliente_id=mrc.cliente_id
	and mrc.ms_cliente_id=".$_GET['id'];
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$contador = 0;
	$arreglo3 = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo3);
		$arreglo3[$count]['CLAVE'] = $row->CLAVE_CLIENTE;
		$arreglo3[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
	}
	
	$arreglo_principal = array("CLIENTE"=>$arreglo, "CLIENTES"=>$arreglo2, "REL_CLIENTES"=>$arreglo3);
	echo json_encode($arreglo_principal);
    $conection = null;
    exit();	
}

if($action == "set_rel_cliente")
{
	$conection = new conexion_nexos(2);
	$query = "select count(*) as contador from ms_rel_clientes where ms_cliente_id=".$_GET['id_cliente_principal']." and cliente_id=".$_GET['id_cliente_relacion'];
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$contador = 0;
	$arreglo1 = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$arreglo['CONTADOR'] = $row->CONTADOR;
	}

	if($arreglo['CONTADOR'] == 0)
	{
		$query = "insert into ms_rel_clientes(ms_cliente_id, cliente_id) values(".$_GET['id_cliente_principal'].",".$_GET['id_cliente_relacion'].")";
			
		$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

		$arreglo2 = $_GET['id_cliente_principal'];
	}

	echo json_encode($arreglo2);
    $conection = null;
    exit();		
}

if($action == "save_rel_cliente")
{
	$conection = new conexion_nexos(2);
	
	if ((int)$_GET['id'] == 0) {

		if($_GET['nombre']!="")
		{
			if($_GET['fecha_nacimiento']=="")
				$_GET['fecha_nacimiento'] = '01/01/1900';
			
			$query = "insert into ms_clientes(clave, fecha_nacimiento, nombre, mostrador) values('".strtoupper($_GET['clave'])."', '".strtoupper($_GET['fecha_nacimiento'])."', '".strtoupper(utf8_decode($_GET['nombre']))."', 0)";
				
			$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());		
		}else
		{
			header("HTTP/1.1 500 Internal Server Error");
			echo "DEBE DE ESCRIBIR EL NOMBRE DEL CLIENTE";
			exit();
		}
	}else
	{
		if($_GET['fecha_nacimiento']=="")
			$_GET['fecha_nacimiento'] = '01/01/1900';
		$query = "update ms_clienteS set clave='".strtoupper($_GET['clave'])."', fecha_nacimiento='".$_GET['fecha_nacimiento']."', nombre='".strtoupper(utf8_decode($_GET['nombre']))."' where MS_CLIENTES_ID=".$_GET['id'];
				
		$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());	
	}

	echo json_encode("ok");
    $conection = null;
    exit();		
}

if($action == "search_cliente")
{
	$conection = new conexion_nexos(2);
	
	$query = "select MS_CLIENTES_ID, clave, fecha_nacimiento, nombre from ms_clientes where MS_CLIENTES_ID=".$_GET['id'];
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$contador = 0;
	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['ID'] = $row->MS_CLIENTES_ID;
		$arreglo[$count]['CLAVE'] = utf8_encode($row->CLAVE);
		$arreglo[$count]['FECHA_NACIMIENTO'] = $row->FECHA_NACIMIENTO;
		$arreglo[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
	}

	echo json_encode($arreglo);
    $conection = null;
    exit();		
}

if($action == "actualizar_clientes")
{
	$conection = new conexion_nexos(2);
	
	$query = "select nombre, cliente_id from clientes where cliente_id not in (select cliente_id from ms_rel_clientes) and clientes.estatus!='B'";
			
	$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

	$arreglo = array();
	while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
		$count = count($arreglo);
		$arreglo[$count]['CLIENTE_ID'] = $row->CLIENTE_ID;
		$arreglo[$count]['NOMBRE'] = utf8_encode($row->NOMBRE);
	}

	$contador = 0;
	while(count($arreglo) > $contador)
	{
		$query = "insert into ms_clientes(nombre) values('".strtoupper(utf8_decode($arreglo[$contador]['NOMBRE']))."')";
				
		ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());	

		$query = "select FIRST 1 MS_CLIENTES_ID from ms_clientes ORDER BY MS_CLIENTES_ID DESC";
			
		$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

		$id = 0;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$id = $row->MS_CLIENTES_ID;
		}

		$query = "insert into MS_REL_CLIENTES(MS_CLIENTE_ID, CLIENTE_ID) values(".$id.", ".$arreglo[$contador]['CLIENTE_ID'].")";
				
		ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());	

		$contador ++;		
	}
	echo json_encode($arreglo);
    $conection = null;
    exit();		
}

?>