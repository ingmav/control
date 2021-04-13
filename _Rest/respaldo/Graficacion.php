<?php
	include("../clases/conexion.php");

	date_default_timezone_set('America/Mexico_City');



	if($_POST['accion'] == "index")
	{
        $conection = new conexion_nexos($_SESSION['empresa']);

        $query = "select extract(month from fecha) as mes , sum(doctos_ve.importe_neto) AS IMPORTE from doctos_ve where DOCTOS_VE.tipo_docto='F' and DOCTOS_VE.estatus!='C' and DOCTOS_vE.fecha>'".date("Y").".01.01' group by mes";

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        $contador = 0;
        $indice = 0;
        $arreglo = array();

        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
            $indice = count($arreglo);
            $arreglo[$indice]['MES'] = $row->MES;
            $arreglo[$indice]['IMPORTE'] = $row->IMPORTE;
            $contador++;
        }

				$query1_notas = "select extract(month from idc.fecha) as MES, sum(idc.importe) as IMPORTE from doctos_cc dc, IMPORTES_DOCTOS_CC IDC where dc.concepto_cc_id='8' and dc.cancelado='N' and IDC.docto_cc_id = DC.docto_cc_id and idc.fecha>'".date("Y").".01.01' group by mes";

        $result1_notas = ibase_query($conection->getConexion(), $query1_notas) or die(ibase_errmsg());

        $contador = 0;
        $indice = 0;
        $arreglo1_notas = array();

        while ($row1_notas = ibase_fetch_object ($result1_notas, IBASE_TEXT)){
            $indice = count($arreglo1_notas);

            $arreglo1_notas[$indice]['MES'] = $row1_notas->MES;
            $arreglo1_notas[$indice]['IMPORTE'] = $row1_notas->IMPORTE;
            $contador++;
        }


        $query2 = "select extract(month from fecha) as mes , sum(doctos_pv.importe_neto) AS IMPORTE from doctos_pv where DOCTOS_PV.tipo_docto='V' and DOCTOS_PV.estatus!='C' and DOCTOS_PV.fecha>'".date("Y").".01.01' group by mes";

        $result3 = ibase_query($conection->getConexion(), $query2) or die(ibase_errmsg());

        $contador = 0;
        $indice = 0;
        $arreglo3 = array();

        while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
            $indice = count($arreglo3);
            $arreglo3[$indice]['MES'] = $row3->MES;
            $arreglo3[$indice]['IMPORTE'] = $row3->IMPORTE;
            $contador++;
        }


        $conection2 = new conexion_nexos(1);

        $result2 = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());

        $contador = 0;
        $indice = 0;
        $arreglo2 = array();

        while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
            $indice = count($arreglo2);
            $arreglo2[$indice]['MES'] = $row2->MES;
            $arreglo2[$indice]['IMPORTE'] = $row2->IMPORTE;
            $contador++;
        }

				$result2_notas = ibase_query($conection->getConexion(), $query1_notas) or die(ibase_errmsg());

        $contador = 0;
        $indice = 0;
        $arreglo2_notas = array();

        while ($row2_notas = ibase_fetch_object ($result2_notas, IBASE_TEXT)){
            $indice = count($arreglo2_notas);
            $arreglo2_notas[$indice]['MES'] = $row2_notas->MES;
            $arreglo2_notas[$indice]['IMPORTE'] = $row2_notas->IMPORTE;
            $contador++;
        }

        $suma = 0;
        for($i = 0; $i<count($arreglo); $i++)
        {
            for($j = 0; $j<count($arreglo2); $j++)
            {
                if($arreglo[$i]["MES"] == $arreglo2[$j]["MES"])
                {
                    $arreglo[$i]["IMPORTE"] += $arreglo2[$j]["IMPORTE"];
                }
            }
            for($j = 0; $j<count($arreglo3); $j++)
            {
                if($arreglo3[$j]["MES"] == $arreglo[$i]["MES"])
                {
                    $arreglo[$i]["IMPORTE"] += $arreglo3[$j]["IMPORTE"];
                }
            }

						for($j = 0; $j<count($arreglo1_notas); $j++)
            {
                if($arreglo[$i]["MES"] == $arreglo1_notas[$j]["MES"])
                {
                    $arreglo[$i]["IMPORTE"] += $arreglo1_notas[$j]["IMPORTE"];
                }
            }
						for($j = 0; $j<count($arreglo2_notas); $j++)
            {
                if($arreglo[$i]["MES"] == $arreglo2_notas[$j]["MES"])
                {
                    $arreglo[$i]["IMPORTE"] += $arreglo2_notas[$j]["IMPORTE"];
                }
            }
            $suma += $arreglo[$i]["IMPORTE"];

        }


        $index_total = count($arreglo);
        $arreglo[$index_total]['MES'] += 0;
        $arreglo[$index_total]['IMPORTE'] = $suma / (count($arreglo)-1);
        //$arreglo[$index_total]['IMPORTE'] = count($arreglo);

        $obj = (object) $arreglo;
        echo json_encode($obj);

	}

	if($_POST['accion'] == "diseno")
	{
		$Json1 = instalacion(array("PRO03", "CN12", "CN13"));
		$Json1["Y"] = "ENERO";
		$obj = (object) $Json1;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "impresion")
	{
		$Json1 = instalacion(array("TL07", "TL09", "TL08","TL06"));
		$Json1["Y"] = "ENERO";
		$obj = (object) $Json1;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "impresion2")
	{
		$Json1 = instalacion(array("TL03", "TL00", "TL05","TL04","TL01","PRO04"));
		$Json1["Y"] = "ENERO";
		$obj = (object) $Json1;
		echo json_encode($obj);
	}

	if($_POST['accion'] == "impresion3")
	{
		$Json1 = instalacion(array("TL11", "TL13", "TL12","TL10"));
		$Json1["Y"] = "ENERO";
		$obj = (object) $Json1;
		echo json_encode($obj);
	}

	function instalacion($arreglo)
	{

		/*$indices = array("A", "A", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L");

		$resultados = array();

		$total;
		foreach ($arreglo as $key => $value) {
			$conection = new conexion_nexos(1);
			$join = array("CLAVES_ARTICULOS","=", "CLAVES_ARTICULOS.ARTICULO_ID", "DOCTOS_VE_DET.ARTICULO_ID", "UNION",
						  "DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "DOCTOS_VE_DET.DOCTO_VE_ID", "UNION");

			$condicionales = "AND CLAVES_ARTICULOS.CLAVE_ARTICULO='".$value."' AND DOCTOS_VE.TIPO_DOCTO='F'";

			$json = $conection->counter_advanced("DOCTOS_VE_DET", $join, $condicionales, 0);
			$conection = new conexion_nexos($_SESSION['empresa']);

			$json2 = $conection->counter_advanced("DOCTOS_VE_DET", $join, $condicionales, 0);

			$resultados[] = array("A"=>($json->PAGINADOR + $json2->PAGINADOR), "Y"=>$value);
			//$total += ($json->PAGINADOR + $json2->PAGINADOR);
			//$resultados[$indices[$key+1]] = $total

		}

		$resultados = [0=>[(Object)array( "Y"=>"FD", "A"=>"23")],[]];

		return $resultados;*/
	}
?>
