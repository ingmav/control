<?php
include("../clases/conexion.php");
session_start();

date_default_timezone_set('America/Mexico_City');

$year  = date("Y");
$month = date("n");
$day   = date("j");

# Obtenemos el numero de la semana
$semana=date("W",mktime(0,0,0,$month,$day,$year));

# Obtenemos el día de la semana de la fecha dada
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));

# el 0 equivale al domingo...
if($diaSemana==0)
    $diaSemana=7;

# A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
$primerDia=date("Y.m.d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));

# A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
$ultimoDia=date("Y.m.d",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));

//echo "<br>Semana: ".$semana." - año: ".$year;
//echo "<br>Primer día ".$primerDia;
//echo "<br>Ultimo día ".$ultimoDia;

if($_POST['accion'] == "datosLevantamiento")
{
	$conection = new conexion_nexos(1);

	$campos = array("LEVANTAMIENTO.ID", "LEVANTAMIENTO.NOMBRECLIENTE", "LEVANTAMIENTO.DESCRIPCION", "LEVANTAMIENTO.FECHALEVANTAMIENTO", "LEVANTAMIENTO.EMPLEADO", "LEVANTAMIENTOESTATUS.LEVANTAMIENTODESCRIPCION");

	$join = array("LEVANTAMIENTOESTATUS", "=", "LEVANTAMIENTOESTATUS.ID", "LEVANTAMIENTO.ESTATUS");

	$order = array("LEVANTAMIENTO.FECHALEVANTAMIENTO DESC");

	$condicionales = " AND LEVANTAMIENTO.ESTATUS=1";

	$json = $conection->select_table($campos, "LEVANTAMIENTO", $join, $condicionales, $order, 1, 0);

	$arreglo_datos['date'] = date("Y-m-d");
	$respuesta[] = $json;
	$respuesta[] = $arreglo_datos;
	$obj = (object) $respuesta;
	echo json_encode($obj);
}

if($_POST['accion'] == "datosCotizacion")
{
	$conection = new conexion_nexos(1);

	$campos = array("COTIZACIONES.ID", "COTIZACIONES.NOMBRECLIENTE", "COTIZACIONES.SOLICITANTE", "COTIZACIONES.DESCRIPCION", "COTIZACIONES.FECHA", "COTIZACIONES.EMPLEADO", "COTIZACIONESESTATUS.COTIZACIONDESCRIPCION", "OPERADOR.ALIAS");

	$join = array("COTIZACIONESESTATUS", "=", "COTIZACIONESESTATUS.ID", "COTIZACIONES.ESTATUS", "LEFT",
				  "OPERADOR", "=", "OPERADOR.ID", "COTIZACIONES.IDOPERADOR", "LEFT");

	$order = array("COTIZACIONES.FECHA DESC");

	$condicionales = " AND COTIZACIONES.ESTATUS=1";

	$json = $conection->select_table_advanced($campos, "COTIZACIONES", $join, $condicionales, $order, 1, 0);

	$arreglo_datos['date'] = date("Y-m-d");
	$respuesta[] = $json;
	$respuesta[] = $arreglo_datos;
	$obj = (object) $respuesta;
	echo json_encode($obj);
}

if($_POST['accion'] == "datosRequisicion")
{
	$conection = new conexion_nexos(1);

	$campos = array("REQUISICION.ID", "REQUISICION.FOLIO", "REQUISICION.FECHA", "OPERADOR.ALIAS", "REQUISICION.PROVEEDOR", "REQUISICION.CLIENTE", "REQUISICION.MATERIAL", "REQUISICION.CANTIDAD", "REQUISICION.UNIDADMEDIDA", "REQUISICION.IMPORTE", "REQUISICION.ESTATUS", "REQUISICION.FECHAREQUISICION", "REQUISICION.OBSERVACION");

	$join = array("OPERADOR", "=", "OPERADOR.ID", "REQUISICION.IDOPERADOR", "LEFT");

	$order = array("REQUISICION.FECHA DESC");

	$condicionales = " AND REQUISICION.ESTATUS=1";

	$json = $conection->select_table_advanced($campos, "REQUISICION", $join, $condicionales, $order, 1, 0);

	$arreglo_datos['date'] = date("Y-m-d");
	$respuesta[] = $json;
	$respuesta[] = $arreglo_datos;
	$obj = (object) $respuesta;
	echo json_encode($obj);
}

if($_POST['accion'] == "datosFacturacion")
{
	if($_SESSION['VENTAS'] == 1)
	{
		$conection = new conexion_nexos(1);

		$campos = array("DOCTOS_VE.DOCTO_VE_ID",
						"DOCTOS_VE.FOLIO",
						"DOCTOS_VE.DESCRIPCION",
						"DOCTOS_VE.IMPORTE_NETO",
						"DOCTOS_VE.TOTAL_IMPUESTOS",
						"DOCTOS_VE.USUARIO_ULT_MODIF",
						"CLIENTES.NOMBRE");

		$join = array("CLIENTES", "=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID", "LEFT");

		$order = array("DOCTOS_VE.FOLIO DESC");

		$condicionales = " AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C' AND DOCTOS_VE.FECHA='".date("Y.m.d")."'";

		$json = $conection->select_table_advanced_with_counter($campos, $campos, "DOCTOS_VE", $join, $condicionales, $order, 0, NULL, 1);

		foreach ($json as $key => $value) {
			$campos1_1 = array("ARTICULOS.NOMBRE",
							"DOCTOS_VE_DET.UNIDADES");

			$join1_1 = array("ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID", "LEFT");

			$order1_1 = array("DOCTOS_VE_DET.DOCTO_VE_DET_ID DESC");

			$condicionales1_1 = " AND DOCTOS_VE_DET.DOCTO_VE_ID=".$value['DOCTOS_VE.DOCTO_VE_ID'];

			$json1_1 = $conection->select_table_advanced($campos1_1, "DOCTOS_VE_DET", $join1_1, $condicionales1_1, $order1_1, 0);
			$json[$key]['SUBARTICULOS'] = $json1_1;

		}

		$conection2 = new conexion_nexos(2);

		$json2 = $conection2->select_table_advanced_with_counter($campos, $campos, "DOCTOS_VE", $join, $condicionales, $order, 0 , NULL, 2);

		foreach ($json2 as $key => $value) {
			$campos = array("ARTICULOS.NOMBRE",
							"DOCTOS_VE_DET.UNIDADES");

			$join = array("ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID", "LEFT");

			$order = array("DOCTOS_VE_DET.DOCTO_VE_DET_ID DESC");

			$condicionales = " AND DOCTOS_VE_DET.DOCTO_VE_ID=".$value['DOCTOS_VE.DOCTO_VE_ID'];

			$json2_1 = $conection2->select_table_advanced($campos, "DOCTOS_VE_DET", $join, $condicionales, $order, 0);
			$json2[$key]['SUBARTICULOS'] = $json2_1;

		}

		$json3 = array_merge($json, $json2);
	}else
	{
		$json3 = array("facturacion"=>0);
	}


	$obj = (object) $json3;
	echo json_encode($obj);
}

if($_POST['accion'] == "datosCxC")
{
	if($_SESSION['VENTAS'] == 1)
	{
		$arreglo1 = ver_pagos(1, $_POST, $primerDia, $ultimoDia);
	    $arreglo2 = ver_pagos(2, $_POST, $primerDia, $ultimoDia);
	    $arreglo3 = array_merge($arreglo1, $arreglo2);
	}else
	{
		$arreglo3 = array("cxc"=>0);
	}

    $obj = (object) $arreglo3;
    echo json_encode($obj);
}

if($_POST['accion'] == "datosFinalizados")
{
	$conexion = new conexion_nexos(1);

    $query = "select dv.folio, c.nombre, dv.descripcion, dv.importe_neto, dv.total_impuestos
    		from tableroproduccion tp, doctos_ve dv, clientes c
    		where
    		dv.cliente_id=c.cliente_id
			and tp.docto_ve_id=dv.docto_ve_id
			and dv.estatus!='C'
			and tp.id not in (select idtableroproduccion from documentosfinalizados df)
			and tp.id not in (select p.idtableroproduccion from produccion p where p.idestatus!=2)
			";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){

    	$indice = count($arreglo1);
    	$arreglo1[$indice]['EMPRESA'] = "NX";
        $arreglo1[$indice]['FOLIO'] = $row->FOLIO;
        $arreglo1[$indice]['NOMBRE'] = utf8_encode($row->NOMBRE);
        $arreglo1[$indice]['DESCRIPCION'] = utf8_encode($row->DESCRIPCION);
        $arreglo1[$indice]['IMPORTE_NETO'] = $row->IMPORTE_NETO;
        $arreglo1[$indice]['TOTAL_IMPUESTOS'] = $row->TOTAL_IMPUESTOS;

    }


    $conexion2 = new conexion_nexos(2);

    $result2 = ibase_query($conexion2->getConexion(), $query) or die(ibase_errmsg());

    $arreglo2 = array();

    while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
    	$indice = count($arreglo2);
        $arreglo2[$indice]['EMPRESA'] = "NP";
        $arreglo2[$indice]['FOLIO'] = $row2->FOLIO;
        $arreglo2[$indice]['NOMBRE'] = utf8_encode($row2->NOMBRE);
        $arreglo2[$indice]['DESCRIPCION'] = utf8_encode($row2->DESCRIPCION);
        $arreglo2[$indice]['IMPORTE_NETO'] = $row2->IMPORTE_NETO;
        $arreglo2[$indice]['TOTAL_IMPUESTOS'] = $row2->TOTAL_IMPUESTOS;
    }

    $json = array_merge($arreglo1, $arreglo2);

    $obj = (object) $arreglo1;
    echo json_encode($obj);
}

if($_POST['accion'] == "datosGenerales")
{
	//Levantamiento
	$conection = new conexion_nexos(1);

	$join = array("LEVANTAMIENTOESTATUS", "=", "LEVANTAMIENTOESTATUS.ID", "LEVANTAMIENTO.ESTATUS");

	$condicionales = " AND LEVANTAMIENTO.ESTATUS=1";

	$json = $conection->counter("LEVANTAMIENTO", $join, $condicionales, 1);
	//Fin Levantamiento

	//Cotizacion

	$join = array("COTIZACIONESESTATUS", "=", "COTIZACIONESESTATUS.ID", "COTIZACIONES.ESTATUS",
				  "OPERADOR", "=", "OPERADOR.ID", "COTIZACIONES.IDOPERADOR");

	$condicionales = " AND COTIZACIONES.ESTATUS=1";

	$json2 = $conection->counter("COTIZACIONES", $join, $condicionales,  1);
	//Fin Cotizacion

	//Requerimientos
	$join = array("OPERADOR", "=", "OPERADOR.ID", "REQUISICION.IDOPERADOR");

	$condicionales = " AND REQUISICION.ESTATUS=1";

	$json3 = $conection->counter("REQUISICION", $join, $condicionales, 1);

	//Fin Requerimientos

	//DOCUMENTOS FINALIZADOS
	/*$join = array("DOCTOS_VE", "=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID");

	$condicionales = " and DOCTOS_VE.ESTATUS!='C'
			and TABLEROPRODUCCION.id not in (select IDTABLEROPRODUCCION from DOCUMENTOSFINALIZADOS)
			and TABLEROPRODUCCION.id not in (select IDTABLEROPRODUCCION from PRODUCCION P where P.IDESTATUS!=2)";

	$json5 = $conection->counter("TABLEROPRODUCCION", $join, $condicionales, 0);*/
	//

	//Facturacion
	$join = array();

	$condicionales = " AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C' AND DOCTOS_VE.FECHA='".date("Y.m.d")."'";

	$json4 = $conection->counter("DOCTOS_VE", $join, $condicionales, 0);

	//Fin Facturacion

	//VER NEXPRINT
	$conection2 = new conexion_nexos(2);

	//DOCUMENTOS FINALIZADOS
	/*$join = array("DOCTOS_VE", "=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID");

	$condicionales = " and DOCTOS_VE.ESTATUS!='C'
			and TABLEROPRODUCCION.id not in (select IDTABLEROPRODUCCION from DOCUMENTOSFINALIZADOS)
			and TABLEROPRODUCCION.id not in (select IDTABLEROPRODUCCION from PRODUCCION P where P.IDESTATUS!=2)";

	$json5_1 = $conection2->counter("TABLEROPRODUCCION", $join, $condicionales, 0);*/
	//

	//Facturacion
	$join = array();

	$condicionales = " AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C' AND DOCTOS_VE.FECHA='".date("Y.m.d")."'";

	$json4_1 = $conection2->counter("DOCTOS_VE", $join, $condicionales, 0);

	//Fin Facturacion

	//FIN VER NEXPRINT
	//CXC
	$contador_cxc = 0;
	$contador_cxc += ver_pagos_contador(1,$primerDia, $ultimoDia);
	$contador_cxc += ver_pagos_contador(2,$primerDia, $ultimoDia);

	//FIN CXC



	$arreglo_contador['levantamiento'] = $json->PAGINADOR;
	$arreglo_contador['cotizacion'] = $json2->PAGINADOR;
	$arreglo_contador['requerimientos'] = $json3->PAGINADOR;
	$arreglo_contador['facturacion'] = $json4->PAGINADOR + $json4_1->PAGINADOR;
	$arreglo_contador['cxc'] = $contador_cxc;
	$arreglo_contador['finalizados'] = $json5->PAGINADOR + $json5_1->PAGINADOR;

	$obj = (object) $arreglo_contador;
    echo json_encode($obj);
	//
}

function ver_pagos($empresa, $filtro, $primerDiax, $ultimoDiax)
{
    $conexion = new conexion_nexos($empresa);

    $query = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $arreglo1[] = $row->DOCTO_VE_ID;
    }

    $query2 = "select DOCTOS_VE.DOCTO_VE_ID  from TABLEROPRODUCCION, DOCTOS_VE WHERE TABLEROPRODUCCION.docto_ve_id=DOCTOS_VE.docto_ve_id AND DOCTOS_VE.TIPO_DOCTO='R' AND DOCTOS_VE.estatus!='C' AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)  ";

    $result2 = ibase_query($conexion->getConexion(), $query2) or die(ibase_errmsg());

    $arreglo2 = array();

    while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
        $arreglo2[] = $row2->DOCTO_VE_ID;
    }

    $arreglo3 = array();
    if(count($arreglo2) > 0)
    {

        $query3 = "select DOCTO_VE_DEST_ID from DOCTOS_VE_LIGAS WHERE DOCTO_VE_FTE_ID IN (".implode(",", $arreglo2).")";

        $result3 = ibase_query($conexion->getConexion(), $query3) or die(ibase_errmsg());

        $arreglo3 = array();

        while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
            $arreglo3[] = $row3->DOCTO_VE_DEST_ID;
        }

        $diferencia_remision_factura = count($arreglo2) - count($arreglo3);

        $arreglo1 = array_merge($arreglo1, $arreglo3);
    }

    $query4 = "select DOCTO_DEST_ID from DOCTOS_ENTRE_SIS WHERE CLAVE_SIS_DEST='CC' AND CLAVE_SIS_FTE='VE' AND DOCTO_FTE_ID IN (".implode(",", $arreglo1).")";

    $result4 = ibase_query($conexion->getConexion(), $query4) or die(ibase_errmsg());

    $arreglo4 = array();

    while ($row4 = ibase_fetch_object ($result4, IBASE_TEXT)){
        $arreglo4[] = $row4->DOCTO_DEST_ID;
    }

    $filtro_interior = "";
    if(count($filtro) > 0)
    {
        $filtro_interior .= " and d1.folio like '%".$filtro['folio']."%' and c.nombre like '%".$filtro['cliente']."%'";
    }

    $query6 = "select importes_doctos_cc.docto_cc_acr_id,
    doctos_cc.fecha_aplicacion,
    (importes_doctos_cc.importe + importes_doctos_cc.impuesto) as IMPORTE
from doctos_cc, importes_doctos_cc
where doctos_cc.docto_cc_id=importes_doctos_cc.docto_cc_id and doctos_cc.naturaleza_concepto='R'
and doctos_cc.cancelado='N' and doctos_cc.estatus='P'
and doctos_cc.fecha_aplicacion between '".$primerDiax."' and '".$ultimoDiax."'";

    $result6 = ibase_query($conexion->getConexion(), $query6) or die(ibase_errmsg());

    $arreglo6 = array();
    $arreglo7 = array();
    $texto_arreglo = "";
    $cont = 0;
    while ($row6 = ibase_fetch_object ($result6, IBASE_TEXT)){
        if($cont > 0)
        	$texto_arreglo .= ",";
        $arreglo6[] = array("ID"=>$row6->DOCTO_CC_ACR_ID, "IMPORTE"=>$row6->IMPORTE, "FECHA"=>$row6->FECHA_APLICACION);
        $arreglo7[] = $row6->DOCTO_CC_ACR_ID;
        $texto_arreglo .= $row6->DOCTO_CC_ACR_ID;
        $cont++;
    }


    $query5 = "select
d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion,
(sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven) ) AS IMPORTE,
vcc.fecha_vencimiento, vcc.pctje_ven,
IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven)), 0) AS ANTICIPO,
((sum( DISTINCT idc1.importe + idc1.impuesto) / ( 100 /  vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) / ( 100 /  vcc.pctje_ven) ), 0)) AS TOTAL
from doctos_cc d1, vencimientos_cargos_cc vcc, clientes c, importes_doctos_cc idc1
left join importes_doctos_cc idc2 on idc1.docto_cc_acr_id = idc2.docto_cc_acr_id and idc2.tipo_impte='R' and idc2.estatus!='P'
where
d1.docto_cc_id=idc1.docto_cc_id
and d1.docto_cc_id=vcc.docto_cc_id
and d1.cliente_id = c.cliente_id
and d1.naturaleza_concepto='C' and d1.cancelado='N'
and d1.docto_cc_id in (".$texto_arreglo.")

".$filtro_interior."
group by d1.docto_cc_id, d1.concepto_cc_id, d1.folio, d1.fecha, c.nombre, d1.descripcion, vcc.fecha_vencimiento, vcc.pctje_ven
having (((sum( DISTINCT idc1.importe + idc1.impuesto) / (100 / vcc.pctje_ven)) - IIF(sum(idc2.importe + idc2.impuesto)>=0, (sum(idc2.importe + idc2.impuesto) ), 0))) > 0
order by c.nombre";

    $result5 = ibase_query($conexion->getConexion(), $query5) or die(ibase_errmsg());

    $arreglo5 = array();

    while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
        $arreglo5[] = array("ID"=>$row5->DOCTO_CC_ID, "FOLIO"=>$row5->FOLIO, "CONCEPTO_CC"=>$row5->CONCEPTO_CC_ID,"FECHA"=>$row5->FECHA, "NOMBRE"=>utf8_encode($row5->NOMBRE), "DESCRIPCION"=>utf8_encode($row5->DESCRIPCION), "IMPORTE"=>$row5->IMPORTE, "FECHA_VENCIMIENTO"=>$row5->FECHA_VENCIMIENTO, "NUMERO_COBROS"=>$row5->PCTJE_VEN, "ANTICIPO"=>$row5->ANTICIPO, "TOTAL"=>$row5->TOTAL);
    }



    foreach ($arreglo5 as $key=> $value) {
        if($empresa == 1)
            $arreglo5[$key]['EMPRESA'] = "NX";
        else if($empresa == 2)
            $arreglo5[$key]['EMPRESA'] = "NP";

        if(in_array($arreglo5[$key]['ID'], $arreglo4))
        {
            $arreglo5[$key]['FINALIZADO'] = 0;
        }else{
            $arreglo5[$key]['FINALIZADO'] = 1;
        }

        if(in_array($arreglo5[$key]['ID'], $arreglo7))
        {
            $index = array_search($arreglo5[$key]['ID'], $arreglo7);
            $arreglo5[$key]['DEPOSITO'] = $arreglo6[$index]['IMPORTE'];
            $arreglo5[$key]['FECHA_DEPOSITO'] = $arreglo6[$index]['FECHA'];
        }else{
            $arreglo5[$key]['DEPOSITO'] = 0;
            $arreglo5[$key]['FECHA_DEPOSITO'] = "";
        }
    }
    return $arreglo5;
}

function ver_pagos_contador($empresa, $primerDiax, $ultimoDiax)
{
    $conexion = new conexion_nexos($empresa);

    $join = array("IMPORTES_DOCTOS_CC", "=", "IMPORTES_DOCTOS_CC.DOCTO_CC_ID", "DOCTOS_CC.DOCTO_CC_ID");

	$condicionales = " and doctos_cc.naturaleza_concepto='R' and doctos_cc.cancelado='N' and doctos_cc.estatus='P' and doctos_cc.fecha_aplicacion between '".$primerDiax."' and '".$ultimoDiax."'";

	$json = $conexion->counter("DOCTOS_CC", $join, $condicionales, 0);

    return $json->PAGINADOR;
}
