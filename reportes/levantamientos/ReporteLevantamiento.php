<?php
	//include("clases/conexion.php");
date_default_timezone_set('America/Mexico_City');
include("../../clases/conexion.php");
require('../../pdf/dompdf/dompdf_config.inc.php');


if(count($_POST["id"]))
{
	$html = '<html style="margin-top: 0em;}">';

	$campos_operador = array("OPERADORDEPARTAMENTO.ID",
					"OPERADORDEPARTAMENTO.IDOPERADOR");



	$campos = array("LEVANTAMIENTO.NOMBRECLIENTE",
					"LEVANTAMIENTO.DESCRIPCION",
					"LEVANTAMIENTO.FECHALEVANTAMIENTO",
					"LEVANTAMIENTO.EMPLEADO",
					"LEVANTAMIENTOESTATUS.LEVANTAMIENTODESCRIPCION");

	$join = array("LEVANTAMIENTOESTATUS","=", "LEVANTAMIENTO.ESTATUS", "LEVANTAMIENTOESTATUS.ID");
	$conection = new conexion_nexos(1);
	$jsonlevantamiento = $conection->select_table($campos, "LEVANTAMIENTO", $join,  " AND LEVANTAMIENTO.ID IN (".implode(",", $_POST["id"]).")", array(), 0);

	//print_r($jsonlevantamiento);
	$table = "<table width='100%' style='border:1px solid #DEDEDE; font-size:12px' cellspacing='0'>";
			$table.= "<tr style='border:1px solid #DEDEDE; background:#CCC'><td  width='60px'>FECHA</td><td  width='150px'>CLIENTE</td><td  width='230px'>DESCRIPCION</td><td  width='30px'>ENCARGADO</td></tr>";

	foreach($jsonlevantamiento as $rows)
	{
		
		if(count($rows) > 0)
		{
			$cabecera = "";
				
			$contador = 0;
			
				if(($contador%2) != 0)
					$color = "#EEE";
				else
					$color = "#FFF";
				
				$table.="<tr style='background:$color'>";
				$table.="<td style='border:1px solid #999;'>".utf8_decode($rows['LEVANTAMIENTO.FECHALEVANTAMIENTO'])."<br>".utf8_decode($rows['LEVANTAMIENTOESTATUS.LEVANTAMIENTODESCRIPCION'])."</td>";
				$table.="<td  style='border:1px solid #999;'>".utf8_decode($rows['LEVANTAMIENTO.NOMBRECLIENTE'])."</td>";
				$table.="<td  style='border:1px solid #999;'>".utf8_decode($rows['LEVANTAMIENTO.DESCRIPCION'])."</td>";
				
				$table.="<td  style='border:1px solid #999;'>".utf8_decode($rows['LEVANTAMIENTO.EMPLEADO'])."</td>";
				
				$table.="</tr>";
				$table.="<tr><td colspan='4'><table width='100%'' ><tr><td width='33%' height='100px' style='vertical-align:top;border:1px solid #EFEFEF'>MATERIAL:</td><td width='33%' style='vertical-align:top;border:1px solid #EFEFEF'>MEDIDAS:</td><td width='33%' style='vertical-align:top;border:1px solid #EFEFEF'>OBSERVACIONES:</td></tr></table></td></tr>";
				
				$contador++;
			
			
		}
		
		
	}
	$cabecera = utf8_decode("REPORTE DE LEVANTAMIENTOS        FECHA:".date("d-m-Y"));
	$table.= "</table>";
	$table = $cabecera."<br>".$table."<br>";	
	$html .= $table;

	$html .= "<body></html>";

	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper("letter","landscape");
	$dompdf->render();


	$dompdf->stream('my.pdf',array('Attachment'=>0));
}
?>