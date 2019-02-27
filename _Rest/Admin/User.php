<?php
include("../../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos();
$conexion = $conection->conexion_nexos();

if($_POST["accion"] == "index")
{
    $fecha = "";


    if($_POST['user']!="")
        $fecha  = $_POST['fechaBusqueda'];
    //else
    //$fecha = date("Y-m-d");


    $campos = array("ID", "NOMBRE", "ALIAS");

    $join = array();

    $order = array("NOMBRE DESC");

    $condicionales = "";
    //$condicionales = " ";

    $json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 1, 0);
    //print_r($json);
    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "saveUser")
{
    $cotizacion = 0;
    $seleccion = 0;
    $diseno = 0;
    $impresion = 0;
    $instalacion = 0;
    $maquilas = 0;
    $entrega = 0;
    $tablero = 0;
    $cobro = 0;
    $capacidad = 0;
    $inventario = 0;
    $extras = 0;
    $shopping = 0;

    if(isset($_POST['cotizacion']))
        $cotizacion =1;
    if(isset($_POST['seleccion']))
        $seleccion =1;
    if(isset($_POST['diseno']))
        $diseno =1;
    if(isset($_POST['impresion']))
        $impresion =1;
    if(isset($_POST['instalacion']))
        $instalacion =1;
    if(isset($_POST['maquilas']))
        $maquilas =1;
    if(isset($_POST['entrega']))
        $entrega =1;
    if(isset($_POST['tablero']))
        $tablero =1;
    if(isset($_POST['cobros']))
        $cobro =1;
    if(isset($_POST['capacidad']))
        $capacidad =1;
    if(isset($_POST['inventario']))
        $inventario =1;
    if(isset($_POST['extra']))
        $extras =1;
    if(isset($_POST['caja']))
       $shopping =1;

    $campos = array("NOMBRE", "ALIAS","PASSNEX","COTIZACION", "LEVANTAMIENTOS", "DOCUMENTOS", "DISENO", "PROGRAMACION", "IMPRESION", "ENTREGA", "INSTALACION", "FINALIZADOS", "COBRO", "MAQUILAS", "CAPACIDAD", "INVENTARIO", "EXTRA", "SHOPPING", "ESTADO");
    $valores = array("'".utf8_decode($_POST['nombre'])."'",
        "'".utf8_decode($_POST['alias'])."'",
        "'".utf8_decode($_POST['contrasena'])."'",
        $cotizacion,
        1,
        $seleccion,
        $diseno,
        0,
        $impresion,
        $entrega,
        $instalacion,
        $tablero,
        $cobro,
        $maquilas,
        $capacidad,
        $inventario,
        $extras,
        $shopping,
        0
        );

    $json = $conection->insert_table($campos, "OPERADOR", $valores);
    //print_r($json);
    $obj = (object) $json;
    echo json_encode($obj);
}
if($_POST["accion"] == "deleteUser")
{
    if(count($_POST['id']) > 0)
    {

        $json = $conection->delete_table("OPERADOR", "ID IN", Array($_POST['id']));
        //print_r($json);
        $obj = (object) $json;
        echo json_encode($obj);
    }
}

if($_POST["accion"] == "findUser")
{
    $campos = array("ID", "NOMBRE", "ALIAS","PASSNEX","COTIZACION", "LEVANTAMIENTOS", "DOCUMENTOS", "DISENO", "PROGRAMACION", "IMPRESION", "ENTREGA", "INSTALACION", "FINALIZADOS", "COBRO", "MAQUILAS", "CAPACIDAD", "INVENTARIO", "EXTRA", "SHOPPING", "ESTADO");
    $join = array();
    $condicionales = " AND ID=".$_POST['id'];
    $order = array();

    $json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 1);
    //print_r($json);
    $obj = (object) $json;
    echo json_encode($obj);

}

if($_POST["accion"] == "updateUser")
{
    $cotizacion = 0;
    $seleccion = 0;
    $diseno = 0;
    $impresion = 0;
    $instalacion = 0;
    $maquilas = 0;
    $entrega = 0;
    $tablero = 0;
    $cobro = 0;
    $capacidad = 0;
    $inventario = 0;
    $extras = 0;
    $shopping = 0;

    if(isset($_POST['cotizacion']))
        $cotizacion =1;
    if(isset($_POST['seleccion']))
        $seleccion =1;
    if(isset($_POST['diseno']))
        $diseno =1;
    if(isset($_POST['impresion']))
        $impresion =1;
    if(isset($_POST['instalacion']))
        $instalacion =1;
    if(isset($_POST['maquilas']))
        $maquilas =1;
    if(isset($_POST['entrega']))
        $entrega =1;
    if(isset($_POST['tablero']))
        $tablero =1;
    if(isset($_POST['cobros']))
        $cobro =1;
    if(isset($_POST['capacidad']))
        $capacidad =1;
    if(isset($_POST['inventario']))
        $inventario =1;
    if(isset($_POST['extra']))
        $extras =1;
    if(isset($_POST['caja']))
        $shopping =1;

    $campos = array("NOMBRE", "ALIAS","COTIZACION", "LEVANTAMIENTOS", "DOCUMENTOS", "DISENO", "PROGRAMACION", "IMPRESION", "ENTREGA", "INSTALACION", "FINALIZADOS", "COBRO", "MAQUILAS", "CAPACIDAD", "INVENTARIO", "EXTRA", "SHOPPING");

    $valores = array("'".utf8_decode($_POST['nombre'])."'",
        "'".utf8_decode($_POST['alias'])."'",

        $cotizacion,
        1,
        $seleccion,
        $diseno,
        0,
        $impresion,
        $entrega,
        $instalacion,
        $tablero,
        $cobro,
        $maquilas,
        $capacidad,
        $inventario,
        $extras,
        $shopping,
        0
    );

    if($_POST['contrasena']!="")
    {
        array_push($campos,"PASSNNEX");
        array_push($valores, "'".utf8_decode($_POST['contrasena'])."'");
    }
    $json = $conection->update_table($campos, "OPERADOR", $valores, " ID=".$_POST["id"]);
    //print_r($json);
    $obj = (object) $json;
    echo json_encode($obj);
}
?>