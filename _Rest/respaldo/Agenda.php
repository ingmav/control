<?php
/**
 * Created by PhpStorm.
 * User: SALUD
 * Date: 14/12/15
 * Time: 16:46
 */
header("Content-type: application/rtf; charset=utf-8");
include("../clases/conexion.php");
include("../clases/utilerias.php");

session_start();

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos($_POST['empresa']);


if($_POST['accion'] == "index")
{
    $conection = new conexion_nexos(1);

   $camposx = array("DOCTO_VE_DET_ID");

    $joinx = array();

    $condicionalesx = " AND AGENDA.IDDEPARTAMENTO=".$_POST['departamento']." AND FECHA='".$_POST['dia']."' and EMPRESA=3 ";
    $condicionalesx2 = " AND AGENDA.IDDEPARTAMENTO=".$_POST['departamento']." AND FECHA='".$_POST['dia']."' and EMPRESA=2 ";

    $jsonx1 = $conection->select_table_advanced($camposx, "AGENDA", $joinx, $condicionalesx, array(), 0);
    $jsonx2 = $conection->select_table_advanced($camposx, "AGENDA", $joinx, $condicionalesx2, array(), 0);


    $campos = array("OPERADORDEPARTAMENTO.ID",
        "OPERADOR.NOMBRE");

    $join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR", "UNION");

    $condicionales = " AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=".$_POST['departamento']." and estado=0";

    $json = $conection->select_table_advanced($campos, "OPERADOR", $join, $condicionales, $order, 0);

    
    $campos2 = array("DOCTOS_VE.DOCTO_VE_ID",
                     "DOCTOS_VE_DET.DOCTO_VE_DET_ID",
                     "DOCTOS_VE.FOLIO",
                     "DOCTOS_VE.TIPO_DOCTO",
                     "TABLEROPRODUCCION.FECHA_ENTREGA",
                     "CLIENTES.NOMBRE",
                     "DOCTOS_VE.DESCRIPCION");

    $join2 = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID", "UNION",
                   "DOCTOS_VE_DET","=", "DOCTOS_VE_DET.DOCTO_VE_DET_ID", "TABLEROPRODUCCION.DOCTO_VE_DET_ID", "UNION",
                   "CLIENTES", "=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION");

    
    //Empieza la segunda ronda
    $conection2 = new conexion_nexos(2);

        /*Mostrador*/
        $condicionales_mostrador = "";

        if(count($jsonx1) > 0)
        {
            $indices="";
            foreach($jsonx1 as $key=> $value )
            {
                $indicies .= $value['DOCTO_VE_DET_ID'].",";
            }
            $indicies .= 0;

            $condicionales_mostrador .= " AND DOCTOS_PV_DET.DOCTO_PV_DET_ID NOT IN (".$indicies.")";
        }

        if($_POST['departamento']==2)
        {
            $condicionales_mostrador .= " AND PRODUCCIONPV.DISENO_GF!=2 AND DOCTOS_PV.ESTATUS!='C' and PRODUCCIONPV.GF_DISENO=1";
        }
        if($_POST['departamento']==3)
        {
            $condicionales_mostrador .= " AND PRODUCCIONPV.IMPRESION_GF!=2 AND DOCTOS_PV.ESTATUS!='C' and PRODUCCIONPV.GF_IMPRESION=1";
        }
        if($_POST['departamento']==4)
        {
            $condicionales_mostrador .= " AND PRODUCCIONPV.INSTALACION_GF!=2 AND DOCTOS_PV.ESTATUS!='C' and PRODUCCIONPV.GF_INSTALACION=1";
        }

        $campos_mostrador = array("DOCTOS_PV.DOCTO_PV_ID",
                     "DOCTOS_PV_DET.DOCTO_PV_DET_ID",
                     "DOCTOS_PV.FOLIO",
                     "DOCTOS_PV.TIPO_DOCTO",
                     "PRODUCCIONPV.F_ENTREGA",
                     "CLIENTES.NOMBRE",
                     "DOCTOS_PV.DESCRIPCION");

        $join_mostrador = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "PRODUCCIONPV.DOCTO_PV_ID", "UNION",
                   "DOCTOS_PV_DET","=", "DOCTOS_PV_DET.DOCTO_PV_DET_ID", "PRODUCCIONPV.DOCTO_PV_DET_ID", "UNION",
                   "CLIENTES", "=", "DOCTOS_PV.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION");

        $json2 = $conection2->select_table_advanced($campos_mostrador, "PRODUCCIONPV", $join_mostrador, $condicionales_mostrador, array(), 0);

        $contador1 = 0;

        while(count($json2) > $contador1)
        {
            //$json2[$contador1]['DOCTOS_PV.FOLIO'] = (intval($json2[$contador1]['DOCTOS_PV.FOLIO']));
            $json2[$contador1]['DOCTOS_PV.FOLIO'] = intval(substr($json2[$contador1]['DOCTOS_PV.FOLIO'],1));
            $json2[$contador1]['EMPRESA'] = 3;
            $contador1++;
        }
        /*Fin mostrador*/

    if(count($jsonx2) > 0)
    {
        $indices="";
        foreach($jsonx2 as $key=> $value )
        {
            $indicies .= $value['DOCTO_VE_DET_ID'].",";
    
        }
        $indicies .= 0;

        $condicionales3 .= " AND DOCTOS_VE_DET.DOCTO_VE_DET_ID NOT IN (".$indicies.")";
    }


    if($_POST['departamento']==2)
    {
        $condicionales3 .= " AND TABLEROPRODUCCION.DISENO_GF!=2 AND DOCTOS_VE.ESTATUS!='C' and TABLEROPRODUCCION.GF_DISENO=1";
    }
    if($_POST['departamento']==3)
    {
        $condicionales3 .= " AND TABLEROPRODUCCION.IMPRESION_GF!=2 AND DOCTOS_VE.ESTATUS!='C' and TABLEROPRODUCCION.GF_IMPRESION=1";
    }
    if($_POST['departamento']==4)
    {
        $condicionales3 .= " AND TABLEROPRODUCCION.INSTALACION_GF!=2 AND DOCTOS_VE.ESTATUS!='C' and TABLEROPRODUCCION.GF_INSTALACION=1";
    }

    $json3 = $conection2->select_table_advanced($campos2, "TABLEROPRODUCCION", $join2, $condicionales3, $order, 0);


    $contador1 = 0;

    while(count($json3) > $contador1)
    {
        $json3[$contador1]['DOCTOS_VE.FOLIO'] = (intval($json3[$contador1]['DOCTOS_VE.FOLIO']));
        $json3[$contador1]['EMPRESA'] = 2;
        $contador1++;
    }


    $json4 = array_merge($json2, $json3);

   for($i = 0; $i < count($json4); $i++)
   {
       for($j = $i+1; $j < count($json4); $j++)
       {
           if($json4[$i]['EMPRESA'] == 3)
                $index1 = $json4[$i]['PRODUCCIONPV.F_ENTREGA'];
           else  
                $index1 = $json4[$i]['TABLEROPRODUCCION.FECHA_ENTREGA'];

           if($json4[$j]['EMPRESA'] == 3) 
                $index2 = $json4[$j]['PRODUCCIONPV.F_ENTREGA'];
            else
                $index2 = $json4[$j]['TABLEROPRODUCCION.FECHA_ENTREGA'];

            $auxiliar;
           if($index1 > $index2)
           {
               $auxiliar = $json4[$i];
               $json4[$i] = $json4[$j];
               $json4[$j] = $auxiliar;
           }

       }

   }

    $obj = (object) array("OPERADORES"=>$json, "TAREAS"=>$json4);
    echo json_encode($obj);
    
}

if($_POST['accion'] == "show")
{
    $conection = new conexion_nexos(2);

    if($_POST['empresa'] != 3)
    {
        $campos2 = array("DOCTOS_VE.DOCTO_VE_ID",
            "DOCTOS_VE_DET.DOCTO_VE_DET_ID",
            "DOCTOS_VE.FOLIO",
            "DOCTOS_VE.TIPO_DOCTO",
            "TABLEROPRODUCCION.FECHA_ENTREGA",
            "CLIENTES.NOMBRE",
            "DOCTOS_VE.DESCRIPCION",
            "TABLEROPRODUCCION.NOTA");

        $join2 = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID", "UNION",
            "DOCTOS_VE_DET","=", "DOCTOS_VE_DET.DOCTO_VE_DET_ID", "TABLEROPRODUCCION.DOCTO_VE_DET_ID", "UNION",
            //"PRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID", "UNION",
            "CLIENTES", "=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION");

        if($_POST['departamento'] == 2)
        {
            $condicionales2 = " AND TABLEROPRODUCCION.GF_DISENO=1 AND TABLEROPRODUCCION.DISENO_GF!=2 AND DOCTOS_VE.ESTATUS!='C' and TABLEROPRODUCCION.DOCTO_VE_ID=".$_POST['id']." AND TABLEROPRODUCCION.DOCTO_VE_DET_ID=".$_POST['id_det'];
        }
        if($_POST['departamento'] == 3)
        {
            $condicionales2 = " AND TABLEROPRODUCCION.GF_IMPRESION=1 AND TABLEROPRODUCCION.IMPRESION_GF!=2 AND DOCTOS_VE.ESTATUS!='C' and TABLEROPRODUCCION.DOCTO_VE_ID=".$_POST['id']." AND TABLEROPRODUCCION.DOCTO_VE_DET_ID=".$_POST['id_det'];
        }
        if($_POST['departamento'] == 4)
        {
            $condicionales2 = " AND TABLEROPRODUCCION.GF_INSTALACION=1 AND TABLEROPRODUCCION.INSTALACION_GF!=2 AND DOCTOS_VE.ESTATUS!='C' and TABLEROPRODUCCION.DOCTO_VE_ID=".$_POST['id']." AND TABLEROPRODUCCION.DOCTO_VE_DET_ID=".$_POST['id_det'];
        }


        $json2 = $conection->select_table_advanced($campos2, "TABLEROPRODUCCION", $join2, $condicionales2, $order, 0);
    }else{
        $campos2 = array("DOCTOS_PV.DOCTO_PV_ID",
            "DOCTOS_PV_DET.DOCTO_PV_DET_ID",
            "DOCTOS_PV.FOLIO",
            "DOCTOS_PV.TIPO_DOCTO",
            "PRODUCCIONPV.F_ENTREGA",
            "CLIENTES.NOMBRE",
            "DOCTOS_PV.DESCRIPCION",
            "PRODUCCIONPV.NOTAS_PROCESO");

        $join2 = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "PRODUCCIONPV.DOCTO_PV_ID", "UNION",
            "DOCTOS_PV_DET","=", "DOCTOS_PV_DET.DOCTO_PV_DET_ID", "PRODUCCIONPV.DOCTO_PV_DET_ID", "UNION",
            "CLIENTES", "=", "DOCTOS_PV.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION");

        if($_POST['departamento'] == 2)
        {
            $condicionales2 = " AND PRODUCCIONPV.GF_DISENO=1 AND PRODUCCIONPV.DISENO_GF!=2 AND DOCTOS_PV.ESTATUS!='C' and PRODUCCIONPV.DOCTO_PV_ID=".$_POST['id']." AND PRODUCCIONPV.DOCTO_PV_DET_ID=".$_POST['id_det'];
        }
        if($_POST['departamento'] == 3)
        {
            $condicionales2 = " AND PRODUCCIONPV.GF_IMPRESION=1 AND PRODUCCIONPV.IMPRESION_GF!=2 AND DOCTOS_PV.ESTATUS!='C' and PRODUCCIONPV.DOCTO_PV_ID=".$_POST['id']." AND PRODUCCIONPV.DOCTO_PV_DET_ID=".$_POST['id_det'];
        }
        if($_POST['departamento'] == 4)
        {
            $condicionales2 = " AND PRODUCCIONPV.GF_INSTALACION=1 AND PRODUCCIONPV.INSTALACION_GF!=2 AND DOCTOS_PV.ESTATUS!='C' and PRODUCCIONPV.DOCTO_PV_ID=".$_POST['id']." AND PRODUCCIONPV.DOCTO_PV_DET_ID=".$_POST['id_det'];
        }


        $json2 = $conection->select_table_advanced($campos2, "PRODUCCIONPV", $join2, $condicionales2, $order, 0);
    }    
    $obj = (object) array("IDEMPRESA"=>$_POST['empresa'],"DOCTO_VE_ID"=>$_POST['id'],"DOCTO_VE_DET_ID"=>$_POST['id_det'], "TAREAS"=>$json2);
    echo json_encode($obj);
    $conection = null;
}

if($_POST['accion'] == "iniciadas")
{
    $conection = new conexion_nexos(1);

    if($_POST['dia']!="")
    {
        $condicionales = " AND AGENDA.FECHA='".$_POST['dia']."'";
    }else
    {
        $condicionales = " AND AGENDA.FECHA='".date("Y-m-d")."'";
    }

    $condicionales .= " AND IDDEPARTAMENTO =".$_POST['departamento'];

    /*if($conection->counter("AGENDA", array(), $condicionales, $softdelete)->PAGINADOR == 0)
    {

        $campos2 = array("DOCTOS_VE.DOCTO_VE_ID",
            "DOCTOS_VE_DET.DOCTO_VE_DET_ID",
            "DOCTOS_VE.FOLIO",
            "DOCTOS_VE.TIPO_DOCTO",
            "TABLEROPRODUCCION.FECHA_ENTREGA",
            "CLIENTES.NOMBRE",
            "DOCTOS_VE.DESCRIPCION",
            "TABLEROPRODUCCION.NOTA");

        $join2 = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID", "UNION",
            "DOCTOS_VE_DET","=", "DOCTOS_VE_DET.DOCTO_VE_DET_ID", "TABLEROPRODUCCION.DOCTO_VE_DET_ID", "UNION",
            "PRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID", "UNION",
            "CLIENTES", "=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION");


        if($_POST['departamento']==2)
        {
            $condicionales2 = " AND PRODUCCION.IDDEPARTAMENTO=2 AND PRODUCCION.IDESTATUS=3 AND DOCTOS_VE.ESTATUS!='C' AND TABLEROPRODUCCION.DISENO=1";

        }else if($_POST['departamento']==3){
            $condicionales2 = " AND PRODUCCION.IDDEPARTAMENTO=3 AND PRODUCCION.IDESTATUS!=2 AND DOCTOS_VE.ESTATUS!='C' AND TABLEROPRODUCCION.IMPRESION=1";

    }else if($_POST['departamento']==4)
               $condicionales2 = " AND PRODUCCION.IDDEPARTAMENTO=4 AND PRODUCCION.IDESTATUS!=2 AND DOCTOS_VE.ESTATUS!='C' AND TABLEROPRODUCCION.INSTALACION=1
        and TABLEROPRODUCCION.id not in
    (select tableroproduccion.id  from tableroproduccion, produccion
    where tableroproduccion.id=produccion.idtableroproduccion and
    produccion.iddepartamento='9' and produccion.idestatus!='2')";


       $json2 = $conection->select_table_advanced($campos2, "TABLEROPRODUCCION", $join2, $condicionales2, $order, 0);

    }else{
    */
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
            "AGENDA.COLOR",
            "AGENDA.REALIZADO",
            "AGENDA.ID",
            "AGENDA.HR1",
            "AGENDA.MINUTO1",
            "AGENDA.OBSERVACION",
            "AGENDA.ESTATUS",);

        $join2 = array();

            $json2 = $conection->select_table_advanced($campos2, "AGENDA", $join2, $condicionales, $order, 0);

    //}


    $obj = (object) array("IDEMPRESA"=>1, "TAREAS"=>$json2);
    echo json_encode($obj);
}


if($_POST[0] == "guardaDB")
{
    $conection2 = new conexion_nexos(1);

    $id = " AGENDA.FECHA='".$_POST[1]."' and AGENDA.IDDEPARTAMENTO=".$_POST[2];
    $json1 = $conection2->delete_of_table("AGENDA", $id, array());
    foreach($_POST  as $key => $value)
    {
        if($key>2)
        {
            if($_POST[$key]['entrega'] == "")
                $_POST[$key]['entrega'] = $_POST[1];

            if($_POST[$key]['hour1'] == "" && $_POST[$key]['minute1'] == "")
            {
                $_POST[$key]['hour1'] =$_POST[$key]['hour'];
                $_POST[$key]['minute1'] = $_POST[$key]['minute'];
            }

            $campos = array("EMPRESA", "DOCTO_VE_ID", "DOCTO_VE_DET_ID", "HR", "MINUTO", "HR1", "MINUTO1", "OPERADOR", "IDDEPARTAMENTO", "ARREGLO", "FOLIO", "CLIENTE","DESCRIPCION", "ENTREGA", "COLOR", "FECHA", "OBSERVACION", "ESTATUS");

           $valores = array($_POST[$key]['empresa'],$_POST[$key]['docto_ve_id'], $_POST[$key]['docto_ve_det_id'], $_POST[$key]['hour'], $_POST[$key]['minute'], $_POST[$key]['hour1'], $_POST[$key]['minute1'], $_POST[$key]['operador'], $_POST[2], $_POST[$key]['general'], "'".$_POST[$key]['folio']."'", "'".$_POST[$key]['cliente']."'", "'".$_POST[$key]['descripcion']."'", "'".$_POST[$key]['entrega']."'", "'background-color:".$_POST[$key]['color']."'", "'".$_POST[1]."'", "'".$_POST[$key]['observacion']."'", "'".$_POST[$key]['estatus']."'");

           $json = $conection2->insert_table($campos, "AGENDA", $valores);

           //echo $_POST[$key]['empresa'];
        }
    }

    //print_r($_POST['campos']);
    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST['accion'] == "verificaEficiencia")
{
    $conection1 = new conexion_nexos(1);

    $campos2 = array("TABLEROPRODUCCION.DOCTO_VE_DET_ID");
    $join2 = array("TABLEROPRODUCCION", "=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID", "LEFT");
    $condicionales = " AND PRODUCCION.FECHA BETWEEN '".$_POST['fecha']." 00:00:00' AND '".$_POST['fecha']." 23:59:59' and PRODUCCION.IDDEPARTAMENTO=".$_POST['departamento']." and PRODUCCION.IDESTATUS=2";

    $json1 = $conection1->select_table_advanced($campos2, "PRODUCCION", $join2, $condicionales, array(), 0);

    $arreglo1 = array();
    foreach($json1 as $key1 => $value1)
    {
        $arreglo1[] = $value1['TABLEROPRODUCCION.DOCTO_VE_DET_ID'];
    }
    if(count($arreglo1) > 0)
    {
        $id1 = " DOCTO_VE_DET_ID IN (".implode(",", $arreglo1).") and IDDEPARTAMENTO='".$_POST['departamento']."' and FECHA='".$_POST['fecha']."' AND EMPRESA=1";
        $id2 = " DOCTO_VE_DET_ID NOT IN (".implode(",", $arreglo1).") and IDDEPARTAMENTO='".$_POST['departamento']."' and FECHA='".$_POST['fecha']."' AND DOCTO_VE_DET_ID!=0 AND EMPRESA=1";

        $conection1->update_table(array("REALIZADO"), "AGENDA", array("2"), $id1);
        $conection1->update_table(array("REALIZADO"), "AGENDA", array("1"), $id2);
    }

    $conection2 = new conexion_nexos(2);

    $campos2 = array("TABLEROPRODUCCION.DOCTO_VE_DET_ID");
    $join2 = array("TABLEROPRODUCCION", "=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID", "LEFT");
    $condicionales = " AND PRODUCCION.FECHA BETWEEN '".$_POST['fecha']." 00:00:00' AND '".$_POST['fecha']." 23:59:59' and PRODUCCION.IDDEPARTAMENTO=".$_POST['departamento']." and PRODUCCION.IDESTATUS=2";

    $json2 = $conection2->select_table_advanced($campos2, "PRODUCCION", $join2, $condicionales, array(), 0);

    $arreglo2 = array();
    foreach($json2 as $key2 => $value2)
    {
        $arreglo2[] = $value2['TABLEROPRODUCCION.DOCTO_VE_DET_ID'];
    }
    $conection1 = new conexion_nexos(1);
    if(count($arreglo2) > 0)
    {

        $id1 = " DOCTO_VE_DET_ID IN (".implode(",", $arreglo2).") and IDDEPARTAMENTO='".$_POST['departamento']."' and FECHA='".$_POST['fecha']."' and EMPRESA=2";
        $id2 = " DOCTO_VE_DET_ID NOT IN (".implode(",", $arreglo2).") and IDDEPARTAMENTO='".$_POST['departamento']."' and FECHA='".$_POST['fecha']."' AND DOCTO_VE_DET_ID!=0 AND EMPRESA=2";

        $conection1->update_table(array("REALIZADO"), "AGENDA", array("2"), $id1);
        $conection1->update_table(array("REALIZADO"), "AGENDA", array("1"), $id2);
    }

    $ccontador_total = $conection1->counter("AGENDA", array(), " AND FECHA='".$_POST['fecha']."' AND IDDEPARTAMENTO='".$_POST['departamento']."'", 0);
    $contador_realizados = $conection1->counter("AGENDA", array(), " AND FECHA='".$_POST['fecha']."' AND IDDEPARTAMENTO='".$_POST['departamento']."' and REALIZADO=2", 0);

    $resultado = array("realizados"=>$contador_realizados, "total"=>$ccontador_total, );
    $obj = (object) $resultado;
    echo json_encode($obj);
}
if($_POST['accion'] == "validaActividad")
{
    $conection1 = new conexion_nexos(1);

    try{
        if($_POST['fecha'] == date("Y-m-d"))
        {
            $id = " AGENDA.ID=".$_POST['id'];
            $conection1->update_table(array("REALIZADO"), "AGENDA", array("2"), $id);

            $obj = (object) array("resultado" => 1, "id"=>$_POST['id']);
            echo json_encode($obj);
        }
    }catch(Exception $e)
    {
        $obj = (object) array("resultado" => 0);
        echo json_encode($obj);
    }

}
?>