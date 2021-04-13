<?php

include("../../clases/conexion.php");


header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteOperaciones.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

$html = '<html style="margin-top: 0em;}">';

$campos_operador = array("OPERADORDEPARTAMENTO.ID",
				"OPERADORDEPARTAMENTO.IDOPERADOR");



$departamento = $_GET['tipo'];
$conection = new conexion_nexos(1);
$json_operador = $conection->select_table($campos_operador, "OPERADORDEPARTAMENTO", array(),  " AND IDDEPARTAMENTO=".$departamento, array(), 0);


foreach($json_operador as $row_operador)
{
	$campos = array("TABLEROPRODUCCION.ID",
							"PRODUCCION.ID as IDPRODUCCION", 
							"DOCTOS_VE.FOLIO", 
							"TABLEROPRODUCCION.FECHA", 
							"CLIENTES.NOMBRE", 
							"ARTICULOS.ARTICULO_ID", 
							"TABLEROPRODUCCION.NOTA", 
							"DOCTOS_VE_DET.UNIDADES", 
							"ARTICULOS.UNIDAD_VENTA", 
							"OPERADOR.ALIAS", 
							"TABLEROPRODUCCION.PRIORIDAD",
							"PRODUCCION.IDESTATUS", 
							"PRODUCCION.DESCRIPCIONCANCELACION",
							"DOCTOS_VE.TIPO_DOCTO",
							"DOCTOS_VE.ESTATUS",
							"ORDENDIA.FECHAORDEN",
							"INSTALACION.COLABORADORESINSTALACION");
			
	$join = array("DOCTOS_VE_DET","=", "DOCTOS_VE_DET.DOCTO_VE_DET_ID", "TABLEROPRODUCCION.DOCTO_VE_DET_ID", "UNION",
				  "DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID","UNION",
				  "CLIENTES","=", "CLIENTES.CLIENTE_ID", "DOCTOS_VE.CLIENTE_ID", "UNION",
				  "ARTICULOS","=", "ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID", "UNION",
				  "PRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID", "UNION",
				  "OPERADORDEPARTAMENTO","=", "OPERADORDEPARTAMENTO.ID", "PRODUCCION.IDOPERADORDEPARTAMENTO", "LEFT",
				  "OPERADOR","=", "OPERADORDEPARTAMENTO.IDOPERADOR", "OPERADOR.ID", "LEFT",
				  "ORDENDIA","=", "ORDENDIA.IDPRODUCCION", "PRODUCCION.ID", "LEFT",
				  "INSTALACION","=", "INSTALACION.IDPRODUCCION", "PRODUCCION.ID", "LEFT");


	
	switch($departamento)
	{
		case  2:
			$titulo = utf8_encode("DISEÑO");
			$condicionales = " AND (PRODUCCION.IDESTATUS=1 OR PRODUCCION.IDESTATUS=3) AND IDDEPARTAMENTO=2 AND TABLEROPRODUCCION.DISENO=1 AND DOCTOS_VE.ESTATUS!='C'";
	
		break;
		case  3:
			$titulo = utf8_encode("IMPRESIÓN");
			$condicionales = "  AND (PRODUCCION.IDESTATUS=1 OR PRODUCCION.IDESTATUS=3) AND TABLEROPRODUCCION.IMPRESION=1
						   AND PRODUCCION.IDDEPARTAMENTO=3 AND (TABLEROPRODUCCION.DISENO=0 OR
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=2) = 2) AND DOCTOS_VE.ESTATUS!='C' ";
	
		break;
		case 4:
			$titulo = utf8_encode("INSTALACIÓN");
			$condicionales = "  AND PRODUCCION.IDESTATUS!=2 AND TABLEROPRODUCCION.INSTALACION=1
						   AND PRODUCCION.IDDEPARTAMENTO=4 AND (TABLEROPRODUCCION.DISENO=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=2) = 2)
							AND (TABLEROPRODUCCION.IMPRESION=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=3) = 2) AND DOCTOS_VE.ESTATUS!='C'";
		break;
		case  6:
		$titulo = "ENTREGA";
		$condicionales = "  AND PRODUCCION.IDESTATUS!=2 AND TABLEROPRODUCCION.ENTREGA=1 
						   AND PRODUCCION.IDDEPARTAMENTO=6 AND (TABLEROPRODUCCION.DISENO=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=2) = 2)
							AND (TABLEROPRODUCCION.IMPRESION=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=3) = 2)
						   AND (TABLEROPRODUCCION.MAQUILAS=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=8) = 2) AND DOCTOS_VE.ESTATUS!='C'";
		break;
		case  7:
			$titulo = "PROGRAMACIÓN";
			$condicionales = "  AND PRODUCCION.IDESTATUS!=2 AND TABLEROPRODUCCION.PROGRAMACION=1 
						   AND PRODUCCION.IDDEPARTAMENTO=7 AND (TABLEROPRODUCCION.DISENO=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=2) = 2) AND DOCTOS_VE.ESTATUS!='C' ";
	
		break;
		case 8:
		$titulo = utf8_encode("MAQUILAS");
		$condicionales = "  AND PRODUCCION.IDESTATUS!=2 AND TABLEROPRODUCCION.MAQUILAS=1 
						   AND PRODUCCION.IDDEPARTAMENTO=8 AND (TABLEROPRODUCCION.DISENO=0 OR 
						   (SELECT IDESTATUS FROM PRODUCCION P WHERE P.IDTABLEROPRODUCCION=TABLEROPRODUCCION.ID AND P.IDDEPARTAMENTO=2) = 2) AND DOCTOS_VE.ESTATUS!='C'";
		break;
		

	}	

    $condicionales .= " AND ORDENDIA.FECHAORDEN IS NOT NULL and PRODUCCION.IDOPERADORDEPARTAMENTO=".$row_operador['OPERADORDEPARTAMENTO.ID'];

	$order = array();
	$order1 = array("ORDENDIA.ID ASC");

	$conection = new conexion_nexos(1);
	$json = $conection->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales, $order1, 0);

    $index = 0;
	while($index < count($json))
	{

		$campos12 = array("ARTICULOS.NOMBRE");

		$join12 = array();

		$condicionales12 = " AND ARTICULO_ID=".$json[$index]['ARTICULOS.ARTICULO_ID'];

		$conection12 = new conexion_nexos(1);
		$json12 = $conection12->select_table($campos12, "ARTICULOS", $join12, $condicionales12, $order, 0);

		$json[$index]['EMPRESA'] = 1;
		$json[$index]['NOMBREEMPRESA'] = "NX";
		$json[$index]['NOMBREARTICULO'] =utf8_encode($json12[0]['ARTICULOS.NOMBRE']);

		$index++;
	}

	$conection2 = new conexion_nexos($_SESSION['empresa']);
	$json2 = $conection2->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

	$index = 0;

	while($index < count($json2))
	{

		$campos22 = array("ARTICULOS.NOMBRE");

		$join22 = array();

		$condicionales22 = " AND ARTICULO_ID=".$json2[$index]['ARTICULOS.ARTICULO_ID'];

		$conection22 = new conexion_nexos($_SESSION['empresa']);
		

		$json22 = $conection22->select_table($campos22, "ARTICULOS", $join22, $condicionales22, $order, 0);

		$json2[$index]['EMPRESA'] = 2;
		$json2[$index]['NOMBREEMPRESA'] = "NP";
		$json2[$index]['NOMBREARTICULO'] = utf8_encode($json22[0]['ARTICULOS.NOMBRE']);

		$index++;
	}
			

	$json3 = Array();
	$json3 = array_merge($json2, $json);

	$count = 0;
	$contador = count($json3);
	$arrayAuxiliar = Array();
	$j = 1;

	for($i = 0; $i < $contador; $i++)
	{
		$j = ($i + 1);
		for(; $j < $contador; $j++)
		{
			if($json3[$i]['TABLEROPRODUCCION.FECHA'] > $json3[$j]['TABLEROPRODUCCION.FECHA'])
			{
				
				$arrayAuxiliar[0] = $json3[$i];
				$json3[$i] = $json3[$j];	
				$json3[$j] = $arrayAuxiliar[0];
			}else if($json3[$i]['TABLEROPRODUCCION.FECHA'] == $json3[$j]['TABLEROPRODUCCION.FECHA'])
			{
				if($json3[$i]['TABLEROPRODUCCION.PRIORIDAD'] < $json3[$j]['TABLEROPRODUCCION.PRIORIDAD'])
				{
					$arrayAuxiliar[0] = $json3[$i];
					$json3[$i] = $json3[$j];	
					$json3[$j] = $arrayAuxiliar[0];
				}
			}	
		}
        $json3[$i]['CLIENTES.NOMBRE'] = utf8_encode($json3[$i]['CLIENTES.NOMBRE']);
        $json3[$i]['"TABLEROPRODUCCION.NOTA"'] = utf8_encode($json3[$i]['"TABLEROPRODUCCION.NOTA"']);
	}


	
	if(count($json3) > 0)
	{
		$cabecera = "";
		$table = "<table width='100%' style='border:1px solid #DEDEDE; font-size:12px' cellspacing='0'>";
		if($departamento != 4)
			$table.= "<tr style='border:1px solid #DEDEDE; background:#CCC'><td>FOLIO</td><td>FECHA</td><td>CLIENTE</td><td>UNI.</td><td>DESCRIPCION</td></tr>";
		else
			$table.= "<tr style='border:1px solid #DEDEDE; background:#CCC'><td  width='60px'>FOLIO</td><td  width='60px'>FECHA</td><td  width='150px'>CLIENTE</td><td  width='40px'>UNI.</td><td  width='400px'>DESCRIPCION</td><td width='60px'>INICIO</td><td width='60px'>FIN</td><td>FIRMA</td></tr>";	
		$contador = 0;
		foreach($json3 as $row => $value)
		{
			if(($contador%2) != 0)
				$color = "#EEE";
			else
				$color = "#FFF";
			$table.="<tr style='background:$color'>";
			$table.="<td  style='border:1px solid #999;'>".$value['NOMBREEMPRESA']."-".intval($value['DOCTOS_VE.FOLIO'])."-".$value['DOCTOS_VE.TIPO_DOCTO']."</td>";
			$table.="<td  style='border:1px solid #999;'>".$value['TABLEROPRODUCCION.FECHA']."</td>";
			$table.="<td style='border:1px solid #999;'>".utf8_decode($value['CLIENTES.NOMBRE'])."</td>";
			$table.="<td  style='border:1px solid #999;'>".number_format($value['DOCTOS_VE_DET.UNIDADES'], 2)."</td>";
			$colaboradores = "";
			if($value['INSTALACION.COLABORADORESINSTALACION'])
				$colaboradores = "<BR>COLABORADORES:".$value['INSTALACION.COLABORADORESINSTALACION'];
			$table.="<td style='border:1px solid #999;'>".utf8_decode($value['NOMBREARTICULO'])."<br>".$value['TABLEROPRODUCCION.NOTA']."<br>".$colaboradores."</td>";
			//$table.="<td>".$value['CLIENTES.NOMBRE']."</td>";
			if($departamento == 4)
				$table .= "<td style='border:1px solid #999;'></td><td style='border:1px solid #999;'></td><td  style='border:1px solid #999;'></td>";
			
			$table.="</tr>";
			$cabecera = utf8_decode("ORDEN DEL DIA ($titulo)       OPERADOR:".$value['OPERADOR.ALIAS']."     FECHA:".date("d-m-Y"));
			$contador++;
		}
		$table.= "</table>";
		$table = $cabecera."<br>".$table."<br>";	
		$html .= $table;
	}
	
	
	
}

$html .= "<body></html>";
echo $html;
/*$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper("letter","landscape");
$dompdf->render();


$dompdf->stream('my.pdf',array('Attachment'=>0));*/
?>