<?php
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos($_POST['empresa']);
	
	if($_POST["accion"] == "index")
	{

		$arreglo = DataGrid(1, $_POST['desde'], $_POST['hasta'], $_POST['folio'], $_POST['cliente']);

		$arreglo2 = DataGrid(2, $_POST['desde'], $_POST['hasta'], $_POST['folio'], $_POST['cliente']);
        $arreglo3 = DataGrid2($_POST['desde'], $_POST['hasta'], $_POST['folio'], $_POST['cliente']);

		$json3 = array_merge($arreglo, $arreglo2);

        $json3 = array_merge($json3, $arreglo3);

		$contador = count($json3);		

		for($i = 0; $i < $contador; $i++)
		{
			$j = ($i + 1);
			for(; $j < $contador; $j++)
			{
				if($json3[$i]['MAX'] > $json3[$j]['MAX'])
				{
					$arrayAuxiliar[0] = $json3[$i];
					$json3[$i] = $json3[$j];	
					$json3[$j] = $arrayAuxiliar[0];
				}
			}
		}

        $arreglo_salida = array();

        $subtotal = 0;
        $total = 0;
        for($i = 0; $i < $contador; $i++)
        {
            $total +=  $json3[$i]['DOCTOS_VE.IMPORTE_NETO'];
            if($i == 0)
            {

                $subtotal = $json3[$i]['DOCTOS_VE.IMPORTE_NETO'];
                $json3[$i]['DOCTOS_VE.IMPORTE_NETO']  = number_format($json3[$i]['DOCTOS_VE.IMPORTE_NETO'],2,".", ",");
                $arreglo_aux[count($arreglo_aux)]  = $json3[$i];
            }
            else
            {
                if($json3[$i]['MAX'] == $json3[$i-1]['MAX'])
                {
                    $subtotal += $json3[$i]['DOCTOS_VE.IMPORTE_NETO'];
                    $json3[$i]['DOCTOS_VE.IMPORTE_NETO']  = number_format($json3[$i]['DOCTOS_VE.IMPORTE_NETO'],2,".", ",");
                    $arreglo_aux[count($arreglo_aux)]  = $json3[$i];
                }else
                {
                    $index = count($arreglo_aux);
                    $arreglo_aux[$index]['DOCTOS_VE.DOCTO_VE_ID'] = "-";
                    $arreglo_aux[$index]['DOCTOS_VE.TIPO_DOCTO'] = "-";
                    $arreglo_aux[$index]['DOCTOS_VE.FOLIO'] = "0";
                    $arreglo_aux[$index]['DOCTOS_VE.FECHA'] = "-";
                    $arreglo_aux[$index]['CLIENTES.NOMBRE'] = "-";
                    $arreglo_aux[$index]['NOMBREEMPRESA'] = "-";
                    $arreglo_aux[$index]['MAX'] = "-";
                    $arreglo_aux[$index]['DOCTOS_VE.DESCRIPCION'] = "SUBTOTAL";
                    $arreglo_aux[$index]['DOCTOS_VE.IMPORTE_NETO'] = $subtotal;
                    $subtotal = $json3[$i]['DOCTOS_VE.IMPORTE_NETO'];
                    $json3[$i]['DOCTOS_VE.IMPORTE_NETO']  = number_format($json3[$i]['DOCTOS_VE.IMPORTE_NETO'],2,".", ",");
                    $arreglo_aux[]  = $json3[$i];


                }
            }

        }
        $index = count($arreglo_aux);
        $arreglo_aux[$index]['DOCTOS_VE.DOCTO_VE_ID'] = "-";
        $arreglo_aux[$index]['DOCTOS_VE.TIPO_DOCTO'] = "-";
        $arreglo_aux[$index]['DOCTOS_VE.FOLIO'] = "0";
        $arreglo_aux[$index]['DOCTOS_VE.FECHA'] = "-";
        $arreglo_aux[$index]['CLIENTES.NOMBRE'] = "-";
        $arreglo_aux[$index]['NOMBREEMPRESA'] = "-";
        $arreglo_aux[$index]['MAX'] = "-";
        $arreglo_aux[$index]['DOCTOS_VE.DESCRIPCION'] = "SUBTOTAL";
        $arreglo_aux[$index]['DOCTOS_VE.IMPORTE_NETO'] = number_format($subtotal,2,".", ",");

        $index = count($arreglo_aux);
        $arreglo_aux[$index]['DOCTOS_VE.DOCTO_VE_ID'] = "-";
        $arreglo_aux[$index]['DOCTOS_VE.TIPO_DOCTO'] = "-";
        $arreglo_aux[$index]['DOCTOS_VE.FOLIO'] = "0";
        $arreglo_aux[$index]['DOCTOS_VE.FECHA'] = "-";
        $arreglo_aux[$index]['CLIENTES.NOMBRE'] = "-";
        $arreglo_aux[$index]['NOMBREEMPRESA'] = "-";
        $arreglo_aux[$index]['MAX'] = "-";
        $arreglo_aux[$index]['DOCTOS_VE.DESCRIPCION'] = "TOTAL";
        $arreglo_aux[$index]['DOCTOS_VE.IMPORTE_NETO'] = number_format($total,2,".", ",");

		/*$arrayEnviar = Array();
		$page = ($_POST['page'] -1);
		for($i = (0 + ($page * 20)); $i < (($page * 20) + 20); $i++)
		{
			if(!empty($json3[$i]))
			$arrayEnviar[] = $json3[$i];
		}*/
		

		$obj = (object) $arreglo_aux;
		echo json_encode($obj);
	}
	
	function DataGrid($Empresa, $desde, $hasta, $folio, $cliente)
	{
        $join = array("PRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID", "UNION");


        $condicionales = " AND  PRODUCCION.IDESTATUS!=2 ";
        $condicionales2 = " ";

        $conection2 = new conexion_nexos($Empresa);

        $json_distinct = $conection2->select_distinct_table_advanced("TABLEROPRODUCCION.DOCTO_VE_ID", "TABLEROPRODUCCION", $join, $condicionales, 0);

        $campos = array("TABLEROPRODUCCION.DOCTO_VE_ID");
        //$condicionales2 = " and TABLEROPRODUCCION.DOCTO_VE_ID NOT IN (".implode(",", $json_distinct).")

        $condicionales2 = " and TABLEROPRODUCCION.DOCTO_VE_ID NOT IN (".implode(",", $json_distinct).") and DOCTOS_VE.FOLIO like '%".$folio."%' and CLIENTES.NOMBRE like '%".strtoupper($cliente)."%'
        group by TABLEROPRODUCCION.docto_ve_id  having  max(TABLEROPRODUCCION.fecha_termino) between '$desde 00:00:00' and '$hasta 23:59:59'";

        $join = array("DOCTOS_VE", "=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID", "UNION",
                      "CLIENTES", "=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID", "UNION");

        $json = $conection2->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales2, array(), 0, null);


        $keys = "";
        $cont = 0;

        foreach ($json as $arreglo) {
            $keys .= $arreglo['TABLEROPRODUCCION.DOCTO_VE_ID'];
            $cont++;

            if($cont < count($json))
                $keys .= ",";
        }


        $campos2 = array("DOCTOS_VE.DOCTO_VE_ID",
                         "DOCTOS_VE.TIPO_DOCTO",
                         "DOCTOS_VE.FOLIO",
                         "DOCTOS_VE.IMPORTE_NETO",
                         "DOCTOS_VE.FECHA",
                         "CLIENTES.NOMBRE",
                         "DOCTOS_VE.DESCRIPCION");

        $join2 = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID","UNION");
        $order2 =array();

        $condicionales2 = " and DOCTOS_VE.DOCTO_VE_ID IN (".$keys.")";

        if($cont > 0)
        {
            $json2 = $conection2->select_table_advanced($campos2, "DOCTOS_VE", $join2, $condicionales2, $order2, 0, null);

            $index = 0;
            while($index < count($json2))
            {
                if($Empresa == 1)
                    $json2[$index]['NOMBREEMPRESA'] = "NX";
                else
                    $json2[$index]['NOMBREEMPRESA'] = "NP";

                //$joinext = array("TABLEROPRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");
                $condicionalesext = " AND TABLEROPRODUCCION.DOCTO_VE_ID=".$json2[$index]['DOCTOS_VE.DOCTO_VE_ID'];
                $jsonext = $conection2->select_max_table("TABLEROPRODUCCION.FECHA_TERMINO", "TABLEROPRODUCCION", array(), $condicionalesext);
                $json2[$index]['MAX'] = substr($jsonext, 0, 10);
                $index++;
            }
        }else
            $json2 = array();
		/*
		$campos = array("DOCTOS_VE.DOCTO_VE_ID",
						"DOCTOS_VE.FOLIO", 
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.FECHA",
						"CLIENTES.CLIENTE_ID", 
						"CLIENTES.NOMBRE", 
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.CLAVE_CLIENTE",
						"DOCTOS_VE.DESCRIPCION",
						"DOCTOS_VE.IMPORTE_NETO",
						"DOCTOS_VE.TOTAL_IMPUESTOS",
						"DOCTOS_VE.DSCTO_IMPORTE",
						"DOCTOS_VE_LIGAS.DOCTO_VE_DEST_ID");

		$campo = "TABLEROPRODUCCION.DOCTO_VE_ID";

		$join = array("DOCUMENTOSFINALIZADOS","=", "DOCUMENTOSFINALIZADOS.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");


		$condicionales = " AND DOCUMENTOSFINALIZADOS.idtipofinalizacion=2 ";
        $condicionales2 = " AND DOCUMENTOSFINALIZADOS.FECHA like '".date("Y-m-d")."%' ";

		$conection2 = new conexion_nexos($Empresa);

		$json_distinct = $conection2->select_distinct_table($campo, "TABLEROPRODUCCION", $join, $condicionales.$condicionales2, 0);
		
		$join2 = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID",
					  "DOCTOS_VE_LIGAS","=", "DOCTOS_VE_LIGAS.DOCTO_VE_FTE_ID", "DOCTO_VE_ID");
		
		/*if($_POST['pagados'] == 1)
			$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS)";
		else
			$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS)";	

		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}

		$order = array("DOCTOS_VE.FECHA DESC, DOCTOS_VE.FOLIO DESC");
		
	
		$json = $conection2->select_table($campos, "DOCTOS_VE", $join2, $condicionales, $order, 0, null);

		$index = 0;
		while($index < count($json))
		{
			$importe = $json[$index]['DOCTOS_VE.IMPORTE_NETO'] + $json[$index]['DOCTOS_VE.TOTAL_IMPUESTOS'] + $json[$index]['DOCTOS_VE.DSCTO_IMPORTE'];
			$json[$index]['IMPORTE'] = number_format($importe, 2);
			
			$join = array();
			$condicionales3	= "AND TABLEROPRODUCCION.DOCTO_VE_ID=". $json[$index]['DOCTOS_VE.DOCTO_VE_ID'];
			$json4 = $conection2->counter("TABLEROPRODUCCION", $join, $condicionales3, 0);
			$json[$index]['procesos'] = $json4->PAGINADOR;

			$join2 = array("DOCUMENTOSFINALIZADOS","=", "DOCUMENTOSFINALIZADOS.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");
			$condicionales4	= "AND TABLEROPRODUCCION.DOCTO_VE_ID=". $json[$index]['DOCTOS_VE.DOCTO_VE_ID']." AND DOCUMENTOSFINALIZADOS.idtipofinalizacion=2 ";
			$json5 = $conection2->counter("TABLEROPRODUCCION", $join2, $condicionales4, 0);

			$json[$index]['procesosRealizados'] = $json5->PAGINADOR;

			$index++;
		}

		$index = 0;
		while($index < count($json))
		{
			if($Empresa == 1)
				$json[$index]['NOMBREEMPRESA'] = "NX";
			else
				$json[$index]['NOMBREEMPRESA'] = "NP";
			$json[$index]['EMPRESA'] = $Empresa;
			$joinext = array("TABLEROPRODUCCION","=", "PRODUCCION.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");
			$condicionalesext = " AND TABLEROPRODUCCION.DOCTO_VE_ID=".$json[$index]['DOCTOS_VE.DOCTO_VE_ID'];
			$jsonext = $conection2->select_max_table("PRODUCCION.FECHA", "PRODUCCION", $joinext, $condicionalesext);
			$json[$index]['MAX'] = substr($jsonext, 0, 10);
			$index++;
		}
        */
		return $json2;
	}


function Datagrid2($desde, $hasta, $folio, $cliente)
{

    $join = array();


    $condicionales = " AND  INVENTARIOIMPRESION.IDTIPO=2 AND FECHA between '".$desde." 00:00:00' and '".$hasta." 23:59:59'";

    $conection2 = new conexion_nexos($_SESSION['empresa']);

    $json_distinct = $conection2->select_distinct_table_advanced("INVENTARIOIMPRESION.IDTABLEROPRODUCCION", "INVENTARIOIMPRESION", array(), $condicionales, 0);



    $campos = array("DOCTOS_PV.DOCTO_PV_ID",
        "DOCTOS_PV.TIPO_DOCTO",
        "DOCTOS_PV.FOLIO",
        "DOCTOS_PV.IMPORTE_NETO",
        "DOCTOS_PV.FECHA",
        "CLIENTES.NOMBRE",
        "DOCTOS_PV.DESCRIPCION"
        );



    $join = array("CLIENTES","=", "DOCTOS_PV.CLIENTE_ID", "CLIENTES.CLIENTE_ID","UNION");

    if(count($json_distinct) > 0)
    {
        $condicionales2 = " AND DOCTOS_PV.DOCTO_PV_ID IN (".implode(",", $json_distinct).")  and DOCTOS_PV.FOLIO like '%".$folio."%' and CLIENTES.NOMBRE like '%".strtoupper($cliente)."%'";

        $json = $conection2->select_table_advanced($campos, "DOCTOS_PV", $join, $condicionales2, array(), 0, null);

        $contador = count($json);
        $arrayAuxiliar = array();
        $json2 = array();

        for($i = 0; $i < $contador; $i++)
        {
            $json2[$i]['DOCTOS_VE.DOCTO_VE_ID'] = $json[$i]['DOCTOS_PV.DOCTO_PV_ID'];
            $json2[$i]['DOCTOS_VE.TIPO_DOCTO'] = $json[$i]['DOCTOS_PV.TIPO_DOCTO'];
            $json2[$i]['DOCTOS_VE.FOLIO'] = substr($json[$i]['DOCTOS_PV.FOLIO'],1);
            $json2[$i]['DOCTOS_VE.FECHA'] = $json[$i]['DOCTOS_PV.FECHA'];
            $json2[$i]['DOCTOS_VE.IMPORTE_NETO'] = $json[$i]['DOCTOS_PV.IMPORTE_NETO'];
            $json2[$i]['CLIENTES.NOMBRE'] = $json[$i]['CLIENTES.NOMBRE'];
            $json2[$i]['DOCTOS_VE.DESCRIPCION'] = $json[$i]['DOCTOS_PV.DESCRIPCION'];

            $condicionalesext = " AND INVENTARIOIMPRESION.IDTABLEROPRODUCCION=".$json2[$i]['DOCTOS_VE.DOCTO_VE_ID'];
            $jsonext = $conection2->select_max_table("INVENTARIOIMPRESION.FECHA", "INVENTARIOIMPRESION", array(), $condicionalesext);
            $json2[$i]['MAX'] = substr($jsonext, 0, 10);

            //$json2[$i]['MAX'] = $json[$i]['INVENTARIOIMPRESION.FECHA'];
            $json2[$i]['NOMBREEMPRESA'] = "NF";
        }
    }else
        $json2 = array();


    //print_r($json2);
    return $json2;
}

	if($_POST["accion"] == "counter")
	{
	
		$campo = "TABLEROPRODUCCION.DOCTO_VE_ID";
		
		$join = array("DOCUMENTOSFINALIZADOS","=", "DOCUMENTOSFINALIZADOS.IDTABLEROPRODUCCION", "TABLEROPRODUCCION.ID");
		
		$condicionales = " AND DOCUMENTOSFINALIZADOS.idtipofinalizacion=2 ";


		$json_distinct = $conection->select_distinct_table($campo, "TABLEROPRODUCCION", $join, $condicionales, 0);
		
		$join2 = array("CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID");
		
		if($_POST['pagados'] == 1)
			$condicionales2 = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID NOT IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS) ";
		else
			$condicionales2 = " AND DOCTOS_VE.DOCTO_VE_ID IN (".implode(",", $json_distinct).") AND DOCTOS_VE.DOCTO_VE_ID IN (SELECT DOCTO_VE_ID FROM DOCUMENTOSPAGADOS)";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales2.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales2.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}
		
		$conection2 = new conexion_nexos(1);
		$json2 = $conection2->counter("DOCTOS_VE", $join2, $condicionales2, 0);

		$conection3 = new conexion_nexos($_SESSION['empresa']);
		$json3 = $conection3->counter("DOCTOS_VE", $join2, $condicionales2, 0);
		
		$counter_final['PAGINADOR'] = $json2->PAGINADOR + $json3->PAGINADOR;
		$obj = (object) $counter_final;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "countProcess")
	{
		$join = array();
		
		$condicionales = " AND TABLEROPRODUCCION.DOCTO_VE_ID=".$_POST['id'];
		
		$order = array();
		
		$arreglo = $conection->counter("TABLEROPRODUCCION", $join, $condicionales, $softdelete);

		
		$arrayAuxiliar = Array();
		
		$arrayAuxiliar[0]['count'] = $arreglo->PAGINADOR;
		$arrayAuxiliar[0]['ID'] = $_POST['id'];
		
		$obj = (object) $arrayAuxiliar;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "email")
	{
		
		$campos = array("DOCTOS_VE.CLAVE_CLIENTE");
		$join = array();

		$condicionales = " AND DOCTOS_VE.DOCTO_VE_ID=".$_POST['docto_ve_id'];
		$order = array();
		
		$json = $conection->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0, NULL);
		
		$conection2 = new conexion_nexos(1);
		$campos2 = array("CORREO");
		$join2 = array();

		$condicionales2 = " AND CLAVE_CLIENTE='".$json[0]['DOCTOS_VE.CLAVE_CLIENTE']."'";
		$order2 = array();
		
		$json2 = $conection2->select_table($campos2, "CORREOENCUESTA", array(), $condicionales2, $order2, $softdelete, NULL);		

		
		$obj = (object) $json2;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "savePay")
	{
		$conection2 = new conexion_nexos(1);
		$count = $conection2->counter("CORREOENCUESTA", array(), "AND CLAVE_CLIENTE='".$_POST['CLAVE_CLIENTE']."'", 0);
		$count->PAGINADOR; 

		if($count->PAGINADOR==0)
		{
			$conection2->insert_table(array("CORREO","CLAVE_CLIENTE"), "CORREOENCUESTA",array("'".$_POST['correo']."'","'".$_POST['CLAVE_CLIENTE']."'" ));
		}else
		{
			$conection2->update_table(array("CORREO"), "CORREOENCUESTA",array("'".$_POST['correo']."'" ), " CLAVE_CLIENTE='".$_POST['CLAVE_CLIENTE']."'");
		}
		$campos = array("DOCTO_VE_ID", "FECHA");
		$valores = array($_POST['id'], "'".date("Y-m-d H:i:s")."'");
		
		$conection = new conexion_nexos($_POST['empresa']);
		$count2 = $conection->counter("DOCUMENTOSPAGADOS", array(), "AND DOCTO_VE_ID='".$_POST['id']."'", 0);

		if($count2->PAGINADOR == 0)
			$json = $conection->insert_table($campos, "DOCUMENTOSPAGADOS", $valores);
		else
			$json = array("Respuesta"=> 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
?>