<?php
	header("Content-type: application/rtf; charset=utf-8");
    header("Content-Type: application/json", true);
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos(2);


	if($_POST["accion"] == "index")
	{

		$candado = "";

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
		
		$join = array("SEGUIMIENTOACTIVO", "=", "CLIENTESCALL.ID", "SEGUIMIENTOACTIVO.IDCLIENTESCALL","LEFT",
                      "TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE","UNION",
                      "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION","UNION",
                      "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","UNION",
                      "CONTACTOCLIENTESCALL", "=", "CLIENTESCALL.ID", "CONTACTOCLIENTESCALL.IDCLIENTESCALL", "UNION",
                      "ESTATUSSEGUIMIENTO", "=", "CLIENTESCALL.IDESTATUSSEGUIMIENTO", "ESTATUSSEGUIMIENTO.ID","UNION",

        );

        //$condicionales = " AND CONTACTOCLIENTESCALL.PRINCIPAL=1";

        if($_POST['filtrocliente'] != "")
        {
            $condicionales .= " AND CLIENTESCALL.NOMBRE like '%".$_POST['filtrocliente']."%'";
        }

        if($_POST['filtrotipocliente'] != "")
        {
            $condicionales .= " AND CLIENTESCALL.IDTIPOCLIENTE = '".$_POST['filtrotipocliente']."'";
        }

        $order = array();
		//$condicionales = " ".$candado;


        $json = $conection->select_table_advanced2($campos, "CLIENTESCALL", $join, $condicionales, $order, 1);

        //print_r($json);
        for($i = 0; $i< count($json); $i++)
        {
            $condicionales = " AND SEGUIMIENTOCLIENTESCALL.IDCLIENTESCALL=".$json[$i]["CLIENTESCALL.ID"];
            $json2 = $conection->select_max_table("SEGUIMIENTOCLIENTESCALL.FECHACONTACTO", "SEGUIMIENTOCLIENTESCALL", array(), $condicionales);
            $json[$i]['FECHAMAXIMA'] = $json2;

            if($json[$i]['FECHAMAXIMA'] == NULL)
            {
                $json[$i]['estatus'] = "danger";
                $json[$i]['prioridad'] = 2;
            }else
            {
                $datetime1 = new DateTime(date("Y-m-d"));
                $datetime2 = new DateTime($json[$i]['FECHAMAXIMA']);
                $interval = $datetime1->diff($datetime2);

                if($interval->d > 7)
                {
                    $json[$i]['estatus'] = "danger";
                    $json[$i]['prioridad'] = 1;
                }else if($interval->d > 3){
                    $json[$i]['estatus'] = "warning";
                    $json[$i]['prioridad'] = 3;
                }else{
                    $json[$i]['estatus'] = "success";
                    $json[$i]['prioridad'] = 4;
                }
            }

        }

        $aux = array();
        for($i=0; $i<count($json); $i++)
            for($j=$i+1; $j<count($json); $j++)
            {
                if($json[$j]['prioridad'] < $json[$i]['prioridad'])
                {
                    $aux = $json[$i];
                    $json[$i] = $json[$j];
                    $json[$j] = $aux;
                }
            }

        $page = ($_POST['page'] -1);
        for($i = (0 + ($page * 20)); $i < (($page * 20) + 20); $i++)
        {
            if(!empty($json[$i]))
                $arrayEnviar[] = $json[$i];
        }

		$obj = (object) $arrayEnviar;
		echo json_encode($obj);
	}

if($_POST['accion'] ==  "counter")
{

    $join = array("TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE","UNION",
        "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION","UNION",
        "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","LEFT",
        "CONTACTOCLIENTESCALL", "=", "CLIENTESCALL.ID", "CONTACTOCLIENTESCALL.IDCLIENTESCALL", "UNION",
        "ESTATUSSEGUIMIENTO", "=", "CLIENTESCALL.IDESTATUSSEGUIMIENTO", "ESTATUSSEGUIMIENTO.ID","UNION"
    );

    //$condicionales = " AND SEGMENTOCALL.ID = NULL";

    if($_POST['filtrocliente'] != "")
    {
        $condicionales .= " AND (CLIENTESCALL.NOMBRE like '%".$_POST['filtrocliente']."%' OR CONTACTOCLIENTESCALL.CONTACTO1 like '%".$_POST['filtrocliente']."%')";
    }

    if($_POST['filtrotipocliente'] != "")
    {
        $condicionales .= " AND CLIENTESCALL.IDTIPOCLIENTE = '".$_POST['filtrotipocliente']."'";
    }

    $order = array();
    //$condicionales = " ".$candado;


    $json = $conection->counter_advanced("CLIENTESCALL", $join, $condicionales, 1);
    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "buscarSeguimietno")
{
    $candado = "";

    $campos = array("SEGUIMIENTOCLIENTESCALL.FECHACONTACTO",
                    "TIPOSEGUIMIENTO.DESCRIPCION",
                    "SEGUIMIENTOCLIENTESCALL.RESULTADO",
    );

    $join = array("TIPOSEGUIMIENTO", "=", "TIPOSEGUIMIENTO.ID", "SEGUIMIENTOCLIENTESCALL.IDTIPOSEGUIMIENTO","UNION");

    $condicionales = " AND SEGUIMIENTOCLIENTESCALL.IDCLIENTESCALL=".$_POST['id'];

    $order = array();

    $json = $conection->select_table_advanced($campos, "SEGUIMIENTOCLIENTESCALL", $join, $condicionales, $order, 0);


    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "cargaCatalogos")
{

    $campos = array("TIPOSEGUIMIENTO.ID", "TIPOSEGUIMIENTO.DESCRIPCION");

    $json = $conection->select_table($campos, "TIPOSEGUIMIENTO", array(), "", array(), 0);


    $resultado = Array();


    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "save")
{
    $campos = array("IDCLIENTESCALL", "IDOPERADOR", "FECHACONTACTO", "IDTIPOSEGUIMIENTO", "RESULTADO");
    $valores = array($_POST['id'], $_SESSION['IDUSUARIO'], "'".$_POST['fecha']."'", $_POST['tipoSeguimiento'], "'".utf8_decode($_POST['resultado'])."'");


    $json = $conection->insert_table($campos, "SEGUIMIENTOCLIENTESCALL", $valores);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "activaSeguimiento")
{
    $campos = array("IDCLIENTESCALL");
    $valores = array($_POST['id']);

    $json = $conection->insert_table($campos, "SEGUIMIENTOACTIVO", $valores);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "desactivaSeguimiento")
{
    $json = $conection->delete_of_table("SEGUIMIENTOACTIVO", " IDCLIENTESCALL=".$_POST['id'], array());

    $obj = (object) $json;
    echo json_encode($obj);
}
?>