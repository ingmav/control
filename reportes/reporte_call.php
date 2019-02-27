<?php
//include("clases/conexion.php");
date_default_timezone_set('America/Mexico_City');
include("../clases/conexion.php");
require('../dompdf/dompdf_config.inc.php');



    $html = '<html style="margin-top: 0em;}">';

    $campos_operador = array("OPERADORDEPARTAMENTO.ID",
        "OPERADORDEPARTAMENTO.IDOPERADOR");



    $campos = array("CLIENTESCALL.ID",
        "TIPOCLIENTESCALL.DESCRIPCION",
        "CLIENTESCALL.NOMBRE",
        "CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION",
        "SEGMENTOCALL.DESCRIPCIONSEGMENTO",
        "CONTACTOCLIENTESCALL.CONTACTO1",
        "CONTACTOCLIENTESCALL.DIRECCION",
        "CONTACTOCLIENTESCALL.TELEFONO1",
        "CONTACTOCLIENTESCALL.TELEFONO2",
        "CONTACTOCLIENTESCALL.CORREO",
        "CONTACTOCLIENTESCALL.HORARIO",
        "CONTACTOCLIENTESCALL.EMAILING",
        "ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO",
        "SEGUIMIENTOACTIVO.FECHA"
    );

    $join = array("SEGUIMIENTOACTIVO", "=", "CLIENTESCALL.ID", "SEGUIMIENTOACTIVO.IDCLIENTESCALL","UNION",
        "TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE","UNION",
        "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION","UNION",
        "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","UNION",
        "CONTACTOCLIENTESCALL", "=", "CLIENTESCALL.ID", "CONTACTOCLIENTESCALL.IDCLIENTESCALL", "UNION",
        "ESTATUSSEGUIMIENTO", "=", "CLIENTESCALL.IDESTATUSSEGUIMIENTO", "ESTATUSSEGUIMIENTO.ID","UNION",

    );
    $conection = new conexion_nexos(2);
    $json = $conection->select_table_advanced($campos, "CLIENTESCALL", $join, "", array(), 0);

    //print_r($jsonlevantamiento);
    $table = "<table width='100%' style='border:1px solid #DEDEDE; font-size:12px' cellspacing='0'>";
    $table.= "<tr style='border:1px solid #DEDEDE; background:#CCC'><td  width='60px'>CLIENTE</td><td  width='150px'>CLASE / SEGMENTO</td><td  width='230px'>CONTACTO</td><td  width='30px'>TELÉFONO</td><td>CORREO</td><td>ESTATUS</td></tr>";

    foreach($json as $rows)
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
            $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['CLIENTESCALL.NOMBRE'])."</td>";

            $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION'])." / ".utf8_decode($rows["SEGMENTOCALL.DESCRIPCIONSEGMENTO"])."</td>";
            $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['CONTACTOCLIENTESCALL.CONTACTO1'])."</td>";
            $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['CONTACTOCLIENTESCALL.TELEFONO1'])." / ".utf8_decode($rows["CONTACTOCLIENTESCALL.TELEFONO2"])."</td>";
            $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['CONTACTOCLIENTESCALL.CORREO'])."</td>";
            $table.="<td style='border:1px solid #999;'>".utf8_decode($rows['ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO'])."</td>";

            $contador++;


        }


    }

    $cabecera = utf8_decode("REPORTE DE CALLCENTER (ACTIVOS)       FECHA:".date("d-m-Y"));
    $table.= "</table>";
    $table = $cabecera."<br>".$table."<br>";
    $html .= $table;

    $html .= "<body></html>";

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("letter","landscape");
    $dompdf->render();


    $dompdf->stream('my.pdf',array('Attachment'=>0));

?>