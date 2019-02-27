<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteActividadesExtras.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
include("../../clases/conexion.php");

$conection = new conexion_nexos(1);

if(isset($_POST['fecha_inicio']) and isset($_POST['fecha_final']))
{
    $departamento = "";

    $html .= utf8_decode("REPORTE DE AGENDA  DEL PERIODO: ".$_POST['fecha_inicio']." A ".$_POST['fecha_final']);
    $html .= "<table>";
    $campos = array("HR", "MINUTO", "OPERADOR.NOMBRE", "AGENDA.FECHA", "AGENDA.DESCRIPCION", "AGENDA.ENTREGA", "AGENDA.FOLIO", "AGENDA.CLIENTE", "AGENDA.HR1", "AGENDA.MINUTO1", "AGENDA.OBSERVACION", "AGENDA.ESTATUS");

    $join = array("OPERADORDEPARTAMENTO","=", "OPERADORDEPARTAMENTO.ID", "AGENDA.OPERADOR", "UNION",
        "OPERADOR","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR", "UNION");

    $condicionales = " AND AGENDA.IDDEPARTAMENTO=".$_POST['departamento']."  AND AGENDA.FECHA BETWEEN '".$_POST['fecha_inicio']."' AND '".$_POST['fecha_final']."'";
    $order = array("AGENDA.OPERADOR", "AGENDA.FECHA ASC");

    $json = $conection->select_table_advanced($campos, "AGENDA", $join, $condicionales, $order, 0);

    $html .="<tr style='background-color: #999; color: #FFF'><td align='center'>FOLIO</td><td align='center'>FECHA</td><td align='center'>ENTREGA</td><td align='center'>CLIENTE</td><td align='center'>DESCRIPCIÓN</td><td align='center'>HRS</td><td align='center'>MIN</td><td align='center'>OPERADOR</td><td align='center'>ESTATUS</td><td align='center'>OBSERVACIÓN</td></tr>";
    $contador = 0;
    foreach($json as $key => $value)
    {
        if($contador%2 == 0)
            $html .= "<tr>";
        else
            $html .= "<tr style='background-color: #DDD'>";
       $html .= "<td>".$value['AGENDA.FOLIO']."</td>";
       $html .= "<td>".$value['AGENDA.FECHA']."</td>";
       $html .= "<td>".$value['AGENDA.ENTREGA']."</td>";
       $html .= "<td>".$value['AGENDA.CLIENTE']."</td>";
       $html .= "<td>".$value['AGENDA.DESCRIPCION']."</td>";
       $html .= "<td>".$value['AGENDA.HR1']."</td>";
       $html .= "<td>".$value['AGENDA.MINUTO1']."</td>";
       $html .= "<td>".$value['OPERADOR.NOMBRE']."</td>";
       
       if($value['AGENDA.ESTATUS'] == 1)
              $html .= "<td>PENDIENTE</td>";
       else if($value['AGENDA.ESTATUS'] == 2)
              $html .= "<td>INICIADO-NO FINALIZADO</td>";
       else if($value['AGENDA.ESTATUS'] == 3)
              $html .= "<td>INICIADO-FINALIZADO PARCIALMENTE</td>";
       else if($value['AGENDA.ESTATUS'] == 4)
              $html .= "<td>EN VALIDACIÓN</td>";
       else if($value['AGENDA.ESTATUS'] == 5)
              $html .= "<td>FINALIZADO</td>";                   
        
       $html .= "<td>".$value['AGENDA.OBSERVACION']."</td>";    
       
       
       
       $html .= "</tr>";
        $contador++;
    }
    $html .= "</table>";
    echo $html;
}else{
    ECHO "DEBE DE SELECCIONAR UN RANGO DE FECHAS PARA PODER GENERAR ESTE REPORTE";
}
?>