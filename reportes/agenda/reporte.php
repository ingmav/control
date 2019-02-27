<?php
date_default_timezone_set('America/Mexico_City');
include("../../clases/conexion.php");
require('../../dompdf/dompdf_config.inc.php');
$html = '<html style="margin-top: 0em;}">';

$conection = new conexion_nexos(1);
//$conection2 = new conexion_nexos(2);

if(isset($_GET['departamento']) and isset($_GET['fecha']))
{
    $departamento = "";
    switch($_GET['departamento'])
    {
        case 2:
            $departamento = "DISEÑO";
        break;
        case 4:
            $departamento = "INSTALACIÓN";
        break;
    }
    $html .= utf8_decode("Reporte de Agenda ($departamento) del día: ".$_GET['fecha']);
    $html .= "<table>";
    $campos = array("OPERADORDEPARTAMENTO.ID",
        "OPERADOR.NOMBRE");

    $join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR", "UNION");

    $condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=".$_GET['departamento']." AND OPERADORDEPARTAMENTO.ID IN (SELECT OPERADOR FROM AGENDA WHERE FECHA='".$_GET['fecha']."')";

    $json = $conection->select_table_advanced($campos, "OPERADOR", $join, $condicionales, $order, 0);

    $horario = "";

    for($i = 9; $i<20; $i++)
    {
        $horario .= "<td width='40px'>$i</td><td width='40px'>1/2</td>";
    }

    $html .="<tr style='font-size: 9pt; background-color: #4285f4; color:#FFF' ><td width='50px'>OPERADOR / HORARIO</td>$horario</tr>";
    foreach($json as $key => $value)
    {
        $hora_actual = 9;
        $minutos_actual = 0;


        $html .= "<tr><td style='background-color:#4285f4; color:#FFF'>".$value['OPERADOR.NOMBRE']."</td>";

        $campos2 = array("AGENDA.DOCTO_VE_ID",
            "AGENDA.DOCTO_VE_DET_ID",
            "AGENDA.FOLIO",
            "AGENDA.ENTREGA",
            "AGENDA.CLIENTE",
            "AGENDA.DESCRIPCION",
            "AGENDA.OPERADOR",
            "AGENDA.HR",
            "AGENDA.MINUTO",
            "AGENDA.EMPRESA",
            "AGENDA.ARREGLO",
            "AGENDA.COLOR");

        $join2 = array();

        $condicionales2 = " AND AGENDA.FECHA='".$_GET['fecha']."' and operador=".$value['OPERADORDEPARTAMENTO.ID']." and IDDEPARTAMENTO=".$_GET['departamento'];

        $json2 = $conection->select_table_advanced($campos2, "AGENDA", $join2, $condicionales2, $order, 0);



        foreach($json2 as $key2 => $value2)
        {
            $texto = "";
            $hora_trabajo = $hora_actual;
            $minutos_trabajo = $minutos_actual + (int) $value2['AGENDA.MINUTO'];

            if($minutos_trabajo < 59)
                $hora_trabajo += (int) $value2['AGENDA.HR'];
            else{
                $horas = intval($minutos_trabajo / 60);
                $minutos_trabajo -= ($horas * 60);
                $hora_actual += ((int) $value2['AGENDA.HR'] + $horas);
            }

            $columnas = ($value2['AGENDA.HR']*2);

            if($value2['AGENDA.MINUTO'] > 0)
                $columnas++;

            $folio = "";
            if($value2['AGENDA.EMPRESA'] > 0)
                if($value2['AGENDA.EMPRESA'] == 1)
                    $folio .= "NX-".$value2['AGENDA.FOLIO'];
                else if($value2['AGENDA.EMPRESA'] == 2)
                    $folio .= "NP-".$value2['AGENDA.FOLIO'];

            if($value2['AGENDA.EMPRESA'] > 0)
                $texto .= "FOLIO:".$folio."<BR>";
            $texto .= "HORARIO: DE ".str_pad($hora_actual, 2, "0", STR_PAD_LEFT).":".str_pad($minutos_actual, 2, "0", STR_PAD_LEFT)." A ".str_pad($hora_trabajo, 2, "0", STR_PAD_LEFT).":".str_pad($minutos_trabajo, 2, "0", STR_PAD_LEFT)." <BR>";
            $texto .= utf8_decode("CLIENTE: ".$value2['AGENDA.CLIENTE'])."<BR>";
            $texto .= utf8_decode("DESCRIPCIÓN: ".$value2['AGENDA.DESCRIPCION'])."<BR>";
            $texto .= "ENTREGA: ".$value2['AGENDA.ENTREGA'];
            $cadena = str_replace("rgba", "rgb", $value2['AGENDA.COLOR']);
            $arreglo = explode(",", $cadena);
            $colores = $arreglo[0].",".$arreglo[1].",".$arreglo[2].");";
            $html .= "<td colspan='".$columnas."' style='font-size:9pt;".$colores."'>".$texto."</td>";

            $hora_actual = $hora_trabajo;
            $minutos_actual = $minutos_trabajo;

        }


        $html .= "</tr>";
    }
    $html .= "</table>";

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("legal","landscape");
    $dompdf->render();


    $dompdf->stream('Resporte_Agenda.pdf',array('Attachment'=>0));
}
?>