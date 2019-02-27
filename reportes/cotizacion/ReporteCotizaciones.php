<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteCotizaciones.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
include("../../clases/conexion.php");

$html = '<html style="margin-top: 0em;}">';

$campos_operador = array("OPERADORDEPARTAMENTO.ID",
    "OPERADORDEPARTAMENTO.IDOPERADOR");



$campos = array(
    "COTIZACIONES.ID",
    "COTIZACIONES.NOMBRECLIENTE",
    "COTIZACIONES.DESCRIPCION",
    "COTIZACIONES.FECHA",
    "OPERADOR.ALIAS",
    "COTIZACIONES.ESTATUS");

$join = array("COTIZACIONESESTATUS","=", "COTIZACIONES.ESTATUS", "COTIZACIONESESTATUS.ID", "LEFT",
              "OPERADOR","=", "OPERADOR.ID", "COTIZACIONES.IDOPERADOR", "LEFT");
$conection = new conexion_nexos(1);
/*if(count($_POST["id"]) > 0)
    $jsonCOTIZACIONES = $conection->select_table_advanced($campos, "COTIZACIONES", $join,  " AND ESTATUS=1 AND COTIZACIONES.ID IN (".implode(",", $_POST["id"]).")", array("COTIZACIONES.FECHA DESC"), 1);
else if($_POST['operadorfiltro'] > 0)
    $jsonCOTIZACIONES = $conection->select_table_advanced($campos, "COTIZACIONES", $join,  " AND ESTATUS=1 AND COTIZACIONES.IDOPERADOR=".$_POST['operadorfiltro'], array("COTIZACIONES.FECHA DESC"), 1);
else*/
    $jsonCOTIZACIONES = $conection->select_table_advanced($campos, "COTIZACIONES", $join,  " ", array("COTIZACIONES.FECHA DESC"), 1);

$table = "<table width='100%' style='border:1px solid #DEDEDE; font-size:12px' cellspacing='0'>";
$table.= "<tr style='border:1px solid #DEDEDE; background:#CCC'><td  width='60px'>FOLIO</td><td  width='60px'>FECHA</td><td  width='150px'>CLIENTE</td><td  width='230px'>DESCRIPCION</td><td  width='30px'>OPERADOR</td><td  width='30px'>ESTATUS</td></tr>";

if(count($jsonCOTIZACIONES) > 0)
{
    foreach($jsonCOTIZACIONES as $rows)
    {
        $cabecera = "";

        $contador = 0;

        if(($contador%2) != 0)
            $color = "#EEE";
        else
            $color = "#FFF";

        $table.="<tr style='background:$color'>";
        $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['COTIZACIONES.ID'])."</td>";
        $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['COTIZACIONES.FECHA'])."</td>";
        $table.="<td  style='border:1px solid #999;'>".utf8_decode($rows['COTIZACIONES.NOMBRECLIENTE'])."</td>";
        $table.="<td  style='border:1px solid #999;'>".utf8_decode($rows['COTIZACIONES.DESCRIPCION'])."</td>";

        $table.="<td  style='border:1px solid #999;'>".utf8_decode($rows['OPERADOR.ALIAS'])."</td>";

        if($rows['COTIZACIONES.ESTATUS'] == 1)
            $table.="<td  style='border:1px solid #999;'>PENDIENTE</td>";
        else
            $table.="<td  style='border:1px solid #999;'>REALIZADO</td>";

        $table.="</tr>";

        $contador++;
    }
    $cabecera = utf8_decode("REPORTE DE COTIZACIONES       FECHA:".date("d-m-Y"));
    $table.= "</table>";
    $table = $cabecera."<br>".$table."<br>";
    $html .= $table;

    $html .= "<body></html>";
    echo $html;
}
?>