<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteMayores ventas.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

date_default_timezone_set('America/Mexico_City');
include("clases/conexion.php");

$html = '<html style="margin-top: 0em;}">';

echo "REPORTE DE MAYORES VENTAS DEL PERIODO ".$_POST['fechaInicio']." al ".$_POST['fechaFin'];
try{
    $conection = new conexion_nexos(1);

    $query = "select sum(importe_neto) as importe, clientes.nombre
                from doctos_ve,
                clientes where
                doctos_ve.cliente_id=clientes.cliente_id and
                doctos_ve.tipo_docto='F' and doctos_ve.estatus!='C'
                and doctos_Ve.fecha between '".$_POST['fechaInicio']."' and '".$_POST['fechaFin']."'

                group by clientes.nombre
                order by importe";

    //echo "\n\n\n";
    $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

    $arreglo = array();
    while($row = ibase_fetch_object ($result, IBASE_TEXT))
    {
        $index = count($arreglo);
       $arreglo[$index]["CLIENTE"] = $row->NOMBRE;
       $arreglo[$index]["IMPORTE"] = $row->IMPORTE;
    }


    $conection2 = new conexion_nexos($_SESSION['empresa']);

    $result2 = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

    $arreglo2 = array();
    while($row2 = ibase_fetch_object ($result2, IBASE_TEXT))
    {
        $index = count($arreglo2);
        $arreglo2[$index]["CLIENTE"] = $row2->NOMBRE;
        $arreglo2[$index]["IMPORTE"] = $row2->IMPORTE;
    }

    $query2 = "select sum(importe_neto) as importe, clientes.nombre
                from doctos_pv,
                clientes where
                doctos_pv.cliente_id=clientes.cliente_id and
                doctos_pv.tipo_docto='V' and doctos_pv.estatus!='C'
                and doctos_pv.fecha between '".$_POST['fechaInicio']."' and '".$_POST['fechaFin']."'
                group by clientes.nombre
                order by importe";

    $result3 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());

    $arreglo3 = array();
    $index = 0;
    while($row3 = ibase_fetch_object ($result3, IBASE_TEXT))
    {
        $index = count($arreglo3);
        $arreglo3[$index]["CLIENTE"] = $row3->NOMBRE;
        $arreglo3[$index]["IMPORTE"] = $row3->IMPORTE;
    }
    /********************************************************/
    $final = count($arreglo2);    
    for($i = 0; $i<$final; $i++)
    {
        for($j = 0; $j<count($arreglo); $j++)
        {
            if($arreglo2[$i]["CLIENTE"] === $arreglo[$j]["CLIENTE"])
            {
                $arreglo[$j]["IMPORTE"] += $arreglo2[$i]["IMPORTE"];
                unset($arreglo2[$i]);
            }
        }
    }

    $final = count($arreglo3);
    for($i = 0; $i<$final; $i++)
    { 
        for($j = 0; $j<count($arreglo); $j++)
        {
            
            if($arreglo3[$i]["CLIENTE"] === $arreglo[$j]["CLIENTE"])
            {
                $arreglo[$j]["IMPORTE"] += $arreglo3[$i]["IMPORTE"];
                unset($arreglo3[$i]);
            }
        }
    }

    $arreglo2 = array_non_empty_items($arreglo2);
    $arreglo4 = array_merge($arreglo, $arreglo2);

    $arreglo3 = array_non_empty_items($arreglo3);
    $arreglo5 = array_merge($arreglo4, $arreglo3);

}catch(Exception $e) {
    echo 'Excepción capturada: ',  $e, "\n";
}

print_array(sort_array($arreglo5));

function array_non_empty_items($input) {
    // If it is an element, then just return it
    if (!is_array($input)) {
        return $input;
    }

    $non_empty_items = array();

    foreach ($input as $key => $value) {
        if($value['CLIENTE'])
        {
            $index = count($non_empty_items);
            $non_empty_items[$index]["CLIENTE"] = $value['CLIENTE'];
            $non_empty_items[$index]["IMPORTE"] = $value['IMPORTE'];
        }

    }

    // Finally return the array without empty items
    return $non_empty_items;
}

function sort_array($input) {
    $aux = array();

    for($i = 0; $i<count($input); $i++)
        for($j = $i+1; $j<count($input); $j++)
        {
            if($input[$i]["IMPORTE"] < $input[$j]["IMPORTE"])
            {
                $aux = $input[$j];
                $input[$j] = $input[$i];
                $input[$i] = $aux;

            }
        }

    // Finally return the array without empty items
    return $input;
}


function print_array($input) {

    echo "<table>";
    echo "<tr><td>CLIENTE</td><td>IMPORTE</td><td>%</td></tr>";
    $valor_total = 0;
    $contador = 0;
    foreach ($input as $key => $value)
    {
        $valor_total += $value['IMPORTE'];
    }
    foreach ($input as $key => $value)
    {
        $color = "";
        if(($contador%2) == 0){
            $color = "style='background:#CFCFCF'";
        }
        echo "<tr $color><td>".utf8_encode($value['CLIENTE'])."</td><td align='right'> ".number_format($value['IMPORTE'],2)."</td><td align='right'>".round(((($value['IMPORTE']/$valor_total) * 100)),2)."</td></tr>";

        $contador++;
    }
    echo "<tr><td>TOTAL</td><td align='right''> ".number_format($valor_total,2)."</td></tr>";
    echo "</table>";
}
?>