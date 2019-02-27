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

		$campos = array("FECHASEGUIMIENTOCALL.ID",
                        "FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO",
                        "FECHASEGUIMIENTOCALL.DESCRIPCION",
                        "FECHASEGUIMIENTOCALL.OPERADOR",
                        "FECHASEGUIMIENTOCALL.CLIENTESCALL"
                        );
		
		$join = array();

        $condicionales = " AND FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO>='".date("Y.m.d")."'";

        $order = array("FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO");
		//$condicionales = " ".$candado;


        $json = $conection->select_table_advanced($campos, "FECHASEGUIMIENTOCALL", $join, $condicionales, $order, 1, $_POST['page']);

		$obj = (object) $json;
		echo json_encode($obj);
	}

if($_POST["accion"] == "counter")
{

    $candado = "";

   $join = array();

    $condicionales = " AND FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO>='".date("Y.m.d")."'";

    $order = array();
    //$condicionales = " ".$candado;


    $json = $conection->counter_advanced("FECHASEGUIMIENTOCALL", $join, $condicionales, 1);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "cargaCatalogos")
{

    $campos = array("CLIENTESCALL.ID", "CLIENTESCALL.NOMBRE");

    $json = $conection->select_table($campos, "CLIENTESCALL", array(), "", array(), 1);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "save")
{

    $campos = array("CLIENTESCALL", "FECHASEGUIMIENTO", "DESCRIPCION", "OPERADOR", "CREADOPOR");
    $valores = array("'".utf8_decode($_POST['clientecall'])."'", "'".$_POST['fecha']."'", "'".utf8_decode($_POST['descripcion'])."'", "'".utf8_decode($_POST['operador'])."'", $_SESSION['IDUSUARIO']);


    $json = $conection->insert_table($campos, "FECHASEGUIMIENTOCALL", $valores);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "update")
{
    $campos = array("CLIENTESCALL", "FECHASEGUIMIENTO", "DESCRIPCION", "OPERADOR", "CREADOPOR");
    $valores = array("'".utf8_decode($_POST['clientecall'])."'", "'".$_POST['fecha']."'", "'".utf8_decode($_POST['descripcion'])."'", "'".utf8_decode($_POST['operador'])."'", $_SESSION['IDUSUARIO']);

    $json = $conection->update_table($campos, "FECHASEGUIMIENTOCALL", $valores, " FECHASEGUIMIENTOCALL.ID=".$_POST['id']);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST['accion'] == "cargaCliente")
{
    $campos = array("FECHASEGUIMIENTOCALL.ID",
        "FECHASEGUIMIENTOCALL.FECHASEGUIMIENTO",
        "FECHASEGUIMIENTOCALL.CLIENTESCALL",
        "FECHASEGUIMIENTOCALL.DESCRIPCION",
        "FECHASEGUIMIENTOCALL.OPERADOR"
    );

    $join = array();

    $condicionales = " AND FECHASEGUIMIENTOCALL.ID=".$_POST['id'];

    $order = array();

    $json = $conection->select_table_advanced($campos, "FECHASEGUIMIENTOCALL", $join, $condicionales, $order, 0);


    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST['accion'] == "bajaCliente")
{

    $condicionales = " FECHASEGUIMIENTOCALL.ID=".$_POST['id'];

    $order = array();

    $json = $conection->delete_table("FECHASEGUIMIENTOCALL", "FECHASEGUIMIENTOCALL.ID in", array($_POST['id']));

    $obj = (object) $json;
    echo json_encode($obj);
}



?>