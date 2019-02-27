<?php
include("../../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');

$conection = new conexion_nexos();
$conexion = $conection->conexion_nexos();

if($_POST["accion"] == "index")
{
    $conexion = new conexion_nexos(1);

    $query = "select departamento.descripciondepartamento, produccion.iddepartamento, produccion.idtableroproduccion, count(*) as contador from produccion, departamento where produccion.iddepartamento=departamento.id group by departamento.descripciondepartamento,iddepartamento, produccion.idtableroproduccion having count(*) > 1";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();

    $index = 0;
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo1);
        
        $arreglo1[$index]['DEPARTAMENTO'] = $row->DESCRIPCIONDEPARTAMENTO;
        $arreglo1[$index]['IDDEPARTAMENTO'] = $row->IDDEPARTAMENTO;
        $arreglo1[$index]['ID'] = $row->IDTABLEROPRODUCCION;
        $arreglo1[$index]['CONTEO'] = $row->CONTADOR;
    }

    $conexion = new conexion_nexos(2);

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo2 = array();

    $index = 0;
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $index = count($arreglo2);
        $arreglo2[$index]['DEPARTAMENTO'] = $row->DESCRIPCIONDEPARTAMENTO;
        $arreglo2[$index]['IDDEPARTAMENTO'] = $row->IDDEPARTAMENTO;
        $arreglo2[$index]['ID'] = $row->IDTABLEROPRODUCCION;
        $arreglo2[$index]['CONTEO'] = $row->CONTADOR;
    }

    $arreglo3 = array_merge($arreglo1, $arreglo2);
    $obj = (object) $arreglo3;
    echo json_encode($obj);
}

if($_POST["accion"] == "reestablecer")
{
    $conexion = new conexion_nexos(1);

    $query = "select departamento.descripciondepartamento, produccion.iddepartamento, produccion.idtableroproduccion, count(*) as contador from produccion, departamento where produccion.iddepartamento=departamento.id group by departamento.descripciondepartamento,iddepartamento, produccion.idtableroproduccion having count(*) > 1";

    $result = ibase_query($conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo1 = array();
    $contador = 0;
    $index = 0;
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $query_select_delete = "select max(id) as id from produccion where iddepartamento='".$row->IDDEPARTAMENTO."' and idtableroproduccion='".$row->IDTABLEROPRODUCCION."'";        
        $result2 = ibase_query($conexion->getConexion(), $query_select_delete) or die(ibase_errmsg());
        while ($row2 = ibase_fetch_object($result2, IBASE_TEXT)){
            $contador++;
            $query_delete = "delete from produccion where id='".$row2->ID."'";        
            ibase_query($conexion->getConexion(), $query_delete) or die(ibase_errmsg());
        }
    }

    $new_conexion = new conexion_nexos(2);

    $result = ibase_query($new_conexion->getConexion(), $query) or die(ibase_errmsg());

    $arreglo2 = array();

    $index = 0;
    while ($row_new = ibase_fetch_object ($result, IBASE_TEXT)){
        $query_select_delete = "select max(id) as id from produccion where iddepartamento='".$row_new->IDDEPARTAMENTO."' and idtableroproduccion='".$row_new->IDTABLEROPRODUCCION."'";        
        $result_new = ibase_query($new_conexion->getConexion(), $query_select_delete) or die(ibase_errmsg());
        while ($row_new2 = ibase_fetch_object ($result_new, IBASE_TEXT)){
            $contador++;
            $query_delete = "delete from produccion where id=".$row_new2->ID;        
            ibase_query($new_conexion->getConexion(), $query_delete) or die(ibase_errmsg());
        }
    }

    $arreglo[0]['RESULTADO'] = $contador;
    $obj = (object) $arreglo;
    echo json_encode($obj);
}