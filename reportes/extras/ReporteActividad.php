<?php
	//include("clases/conexion.php");

header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=actividadesExtras.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
/*date_default_timezone_set('America/Mexico_City');

require('dompdf/dompdf_config.inc.php');
*/
include("../../clases/conexion.php");
$html = '<html style="margin-top: 0em;}">';

$fecha = "";
		
	$conection = new conexion_nexos(1);
	if($_POST['fechaBusqueda']!="")
		$fecha  = $_POST['fechaBusqueda'];
	else
		$fecha = date("Y-m-d");
	

	$campos = array("ACTIVIDADEXTRA.ID","ACTIVIDADEXTRA.RESPONSABLE", "ACTIVIDADEXTRA.COLABORADORES", "ACTIVIDADEXTRA.ACTIVIDAD", "ACTIVIDADEXTRA.FECHA", "ACTIVIDADEXTRA.DE", "ACTIVIDADEXTRA.A", "OPERADOR.NOMBRE");
	
	$join = array("OPERADOR", "=", "OPERADOR.ID", "ACTIVIDADEXTRA.REVISO");
	
	$order = array("ACTIVIDADEXTRA.FECHA", "ACTIVIDADEXTRA.DE");

	$condicionales = " AND ACTIVIDADEXTRA.ID IN  (".implode(",",$_POST['id']).") ";
	//$condicionales = " ";
	
	$json = $conection->select_table($campos, "ACTIVIDADEXTRA", $join, $condicionales, $order, 1, 0);
		

	$html .= utf8_decode("Reporte de Actividades, Fecha de Reporte: $fecha, Fecha de Creación: ".date("Y-m-d")."<br>");
	$html .= "<table width='100%'>";
	$html .= "<thead><tr style='background:#ABABAB'>";
	$html .= "<th>FECHA</th>";
	$html .= "<th>DE - A</th>";
	$html .= "<th>RESPONSABLE / COLABORADORES</th>";
	$html .= "<th>ACTIVIDADES</th>";
	$html .= "<th>REVISOR</th>";
	$html .= "</tr></thead>";
	$html .= "<tbody>";
	$color = "";
	$contador = 0;
	foreach ($json as $key => $value) {
		if(($contador%2)==0)
			$color = " style='background:#EFEFEF'";
		$html.="<tr $color>";
		$html.="<td>".$value['ACTIVIDADEXTRA.FECHA']."</td>";
		$html.="<td>".$value['ACTIVIDADEXTRA.DE']." - ".$value['ACTIVIDADEXTRA.A']."</td>";
		$html.="<td>Responsable: ".$value['ACTIVIDADEXTRA.RESPONSABLE']." <BR> Colaborador(es):".$value['ACTIVIDADEXTRA.COLABORADORES']."</td>";
		$html.="<td>".$value['ACTIVIDADEXTRA.ACTIVIDAD']."</td>";
		$html.="<td>".$value['OPERADOR.NOMBRE']."</td>";
		$html.="</tr>";	
		$contador++;
	}	
	$html .= "</tbody>";
	$html .= "</table>";

$html .= "<body></html>";

echo $html;
/*$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper("letter","landscape");
$dompdf->render();


$dompdf->stream('my.pdf',array('Attachment'=>0));*/
?>