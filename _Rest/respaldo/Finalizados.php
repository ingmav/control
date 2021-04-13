<?php
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	session_start();
	//$conection = new conexion_nexos();
	//$conexion = $conection->conexion_nexos($_POST['empresa']);
	
	if($_POST["accion"] == "index")
	{

        $conection = new conexion_nexos($_SESSION['empresa']);

        if(isset($_POST['buscar']))
        {
            $buscar = (int)$_POST['buscar'];
            $condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
        }

        if(isset($_POST['client']))
        {
            $condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
        }
        if(isset($_POST['pTablero']))
        {
            switch($_POST['pTablero'])
            {
                case 1:
                    $condicionales.= " AND TABLEROPRODUCCION.GF_DISENO=1";
                    if($_POST['eTablero']!=0 )
                    {
                        $condicionales.= " AND TABLEROPRODUCCION.DISENO_GF=".$_POST['eTablero'];
                    }
                break;
                case 2:
                    $condicionales.= " AND TABLEROPRODUCCION.GF_IMPRESION=1";
                    if($_POST['eTablero']!=0 )
                    {
                        $condicionales.= " AND TABLEROPRODUCCION.IMPRESION_GF=".$_POST['eTablero'];
                    }
                break;
                case 3:
                    $condicionales.= " AND TABLEROPRODUCCION.GF_MAQUILAS=1";
                    if($_POST['eTablero']!=0 )
                    {
                        $condicionales.= " AND TABLEROPRODUCCION.MAQUILAS_GF=".$_POST['eTablero'];
                    }
                break;
                case 4:
                    $condicionales.= " AND TABLEROPRODUCCION.GF_INSTALACION=1";
                    if($_POST['eTablero']!=0 )
                    {
                        $condicionales.= " AND TABLEROPRODUCCION.INSTALACION_GF=".$_POST['eTablero'];
                    }
                break;
                case 5:
                    $condicionales.= " AND TABLEROPRODUCCION.GF_ENTREGA=1";
                    if($_POST['eTablero']!=0 )
                    {
                        $condicionales.= " AND TABLEROPRODUCCION.ENTREGA_GF=".$_POST['eTablero'];
                    }
                break;
                case 6:
                    $condicionales.= " AND TABLEROPRODUCCION.GF_PREPARACION=1";
                    if($_POST['eTablero']!=0 )
                    {
                        $condicionales.= " AND TABLEROPRODUCCION.PREPARACION_GF=".$_POST['eTablero'];
                    }
                break;

            }

        }

        $query =    "select 
                    TABLEROPRODUCCION.ID,
                    DOCTOS_VE.FOLIO,
                    DOCTOS_VE.FECHA,    
                    CLIENTES.NOMBRE,
                    CLIENTES.CLIENTE_ID,
                    DOCTOS_VE.DESCRIPCION,
                    GF_DISENO,
                    IIF(GF_DISENO=1, IIF(DISENO_GF=2, 1,0),0) AS ESTATUS_DISENO,
                    GF_PREPARACION,
                    IIF(GF_PREPARACION=1, IIF(PREPARACION_GF=2, 1,0),0) AS ESTATUS_PREPARACION,
                    GF_IMPRESION,
                    IIF(GF_IMPRESION=1, IIF(IMPRESION_GF=2, 1,0),0) AS ESTATUS_IMPRESION,
                    GF_INSTALACION,
                    IIF(GF_INSTALACION=1, IIF(INSTALACION_GF=2, 1,0),0) AS ESTATUS_INSTALACION,
                    GF_ENTREGA,
                    IIF(GF_ENTREGA=1, IIF(ENTREGA_GF=2, 1,0),0) AS ESTATUS_ENTREGA,
                    GF_MAQUILAS,
                    IIF(GF_MAQUILAS=1, IIF(MAQUILAS_GF=2, 1,0),0) AS ESTATUS_MAQUILAS,
                    DOCTOS_VE.TIPO_DOCTO,
                    DOCTOS_VE.ESTATUS,
                    DOCTOS_VE.IMPORTE_NETO
                    FROM 
                    TABLEROPRODUCCION,
                    CLIENTES,
                    DOCTOS_VE
                    WHERE
                    TABLEROPRODUCCION.DOCTO_VE_ID = DOCTOS_VE.DOCTO_VE_ID
                    AND DOCTOS_VE.CLIENTE_ID = CLIENTES.CLIENTE_ID
                    AND DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C' and finalizar_proceso=0 ".$condicionales;

        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
        $json = array();
        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){

            $indice = count($json);
            $json[$indice]['ID']                    = $row->ID;
            $json[$indice]['FOLIO']                 = $row->FOLIO;
            $json[$indice]['TIPO_DOCTO']            = $row->TIPO_DOCTO;
            $json[$indice]['FECHA']                 = $row->FECHA;
            $json[$indice]['EMPRESA']               = "NP";
            $json[$indice]['NOMBRE']                = utf8_encode($row->NOMBRE);
            $json[$indice]['CLIENTE_ID']            = $row->CLIENTE_ID;
            $json[$indice]['DESCRIPCION']           = utf8_encode($row->DESCRIPCION);
            $json[$indice]['GF_DISENO']             = $row->GF_DISENO;
            $json[$indice]['ESTATUS_DISENO']        = $row->ESTATUS_DISENO;
            $json[$indice]['GF_PREPARACION']        = $row->GF_PREPARACION;
            $json[$indice]['ESTATUS_PREPARACION']   = $row->ESTATUS_PREPARACION;
            $json[$indice]['GF_IMPRESION']          = $row->GF_IMPRESION;
            $json[$indice]['ESTATUS_IMPRESION']     = $row->ESTATUS_IMPRESION;
            $json[$indice]['GF_INSTALACION']        = $row->GF_INSTALACION;
            $json[$indice]['ESTATUS_INSTALACION']   = $row->ESTATUS_INSTALACION;
            $json[$indice]['GF_ENTREGA']            = $row->GF_ENTREGA;
            $json[$indice]['ESTATUS_ENTREGA']       = $row->ESTATUS_ENTREGA;
            $json[$indice]['GF_MAQUILAS']           = $row->GF_MAQUILAS;
            $json[$indice]['ESTATUS_MAQUILAS']      = $row->ESTATUS_MAQUILAS;
        } 

        $contador = count($json);
        for($i = 0; $i < $contador; $i++)
        {
            $j = ($i + 1);
            for(; $j < $contador; $j++)
            {
                if($json[$i]['FECHA'] > $json[$j]['FECHA'])
                {
                    $arrayAuxiliar[0] = $json[$i];
                    $json[$i] = $json[$j];
                    $json[$j] = $arrayAuxiliar[0];
                }
            }
        }


        $obj = (object) $json;
        echo json_encode($obj);           

    	/*$campos = array("TABLEROPRODUCCION.ID",
						"DOCTOS_VE.FOLIO", 
						"TABLEROPRODUCCION.FECHA", 
						"CLIENTES.NOMBRE",
						"CLIENTES.CLIENTE_ID", 
						"DOCTOS_VE.DESCRIPCION", 
						"TABLEROPRODUCCION.DISENO",
						"TABLEROPRODUCCION.PREPARACION",
						"TABLEROPRODUCCION.IMPRESION", 
						"TABLEROPRODUCCION.INSTALACION",
						"TABLEROPRODUCCION.ENTREGA",
						"TABLEROPRODUCCION.MAQUILAS",
						"TABLEROPRODUCCION.NOTA",
						"TABLEROPRODUCCION.FECHA_ENTREGA",
						"DOCTOS_VE.TIPO_DOCTO",
						"DOCTOS_VE.ESTATUS",
                        "DOCTOS_VE.IMPORTE_NETO");
		
		$join = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID","UNION",
					  "CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID","UNION");
		
		$condicionales = " AND DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C' 
							AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}

        if(isset($_POST['pTablero']))
        {
            switch($_POST['pTablero'])
            {
                case 1:
                    $condicionales.= " AND TABLEROPRODUCCION.DISENO=1";
                break;
                case 2:
                    $condicionales.= " AND TABLEROPRODUCCION.IMPRESION=1";
                break;
                case 3:
                    $condicionales.= " AND TABLEROPRODUCCION.MAQUILAS=1";
                break;
                case 4:
                    $condicionales.= " AND TABLEROPRODUCCION.INSTALACION=1";
                break;
                case 5:
                    $condicionales.= " AND TABLEROPRODUCCION.ENTREGA=1";
                break;
                case 6:
                    $condicionales.= " AND TABLEROPRODUCCION.PREPARACION=1";
                break;

            }

        }

		$order = array("DOCTOS_VE.FECHA DESC, DOCTOS_VE.FOLIO DESC");
		
		$conection2 = new conexion_nexos(1);

        $monto_total = 0;
		$json = $conection2->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

        //print_r($json);    
        $contador = 0;

        foreach($json as $arreglo)
        {
            //Bloqueo de monto para lic moises
            if($_SESSION['IDUSUARIO'] == 18)
            {
                $json[$contador]['DOCTOS_VE.IMPORTE_NETO'] = "";
            }
            //    

            $monto_total += $json[$contador]['DOCTOS_VE.IMPORTE_NETO'];
            $json[$contador]["NOMBREEMPRESA"] = "NX";
            $json[$contador]["IDEMPRESA"] = 1;
            $campos2 = array("PRODUCCION.IDDEPARTAMENTO",
                            "PRODUCCION.IDESTATUS");
            $json2 = $conection2->select_table_advanced($campos2, "PRODUCCION", array(), " AND PRODUCCION.IDTABLEROPRODUCCION = ".$arreglo["TABLEROPRODUCCION.ID"], array("PRODUCCION.IDDEPARTAMENTO"), 0);
            $counter = 0;
            $counter_check = 0;
            $counter_check2 = 0;

            if($json[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'] == "")
               $json[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'] = "0000-00-00";


            $time2 = substr($json[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'],0,10);

            $timestamp1 = mktime(0,0,0,date("m"), date("d"), date("Y"));
            $timestamp2 = mktime(0,0,0,substr($time2,5,2), substr($time2,8,2), substr($time2,0,4));


            $segundos_diferencia = $timestamp2 - $timestamp1;


            $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

            $json[$contador]['RESTANTE_ENTREGA'] =  floor($dias_diferencia);

            foreach($json2 as $production)
            {
                if($production['PRODUCCION.IDESTATUS'] == 2)
                    $counter_check++;
                else  if($production['PRODUCCION.IDESTATUS'] == 1 || $production['PRODUCCION.IDESTATUS'] == 3)
                    $counter_check2++;
                $counter++;
            }
            
            $json[$contador]["produccion"] = $json2;
            if($counter_check == $counter)
                $json[$contador]['TERMINADO'] = 1;
            else
                $json[$contador]['TERMINADO'] = 0;

            if($counter_check2 == $counter)
                $json[$contador]['NO_INICIADO'] = 1;
            else
                $json[$contador]['NO_INICIADO'] = 0;

            $contador++;
        }

		
		$conection3 = new conexion_nexos($_SESSION['empresa']);
		
		$json3 = $conection3->select_table_advanced($campos, "TABLEROPRODUCCION", $join, $condicionales, $order, 0);

        $contador = 0;
		foreach($json3 as $arreglo)
		{
            //Bloqueo de monto para lic moises
            if($_SESSION['IDUSUARIO'] == 18)
            {
                $json3[$contador]['DOCTOS_VE.IMPORTE_NETO'] = "";
            }
            //  
            $monto_total += $json3[$contador]['DOCTOS_VE.IMPORTE_NETO'];
            $json3[$contador]["NOMBREEMPRESA"] = "NP";
            $json3[$contador]["IDEMPRESA"] = 2;
            $campos2 = array("PRODUCCION.IDDEPARTAMENTO",
                "PRODUCCION.IDESTATUS");
            
            $json2 = $conection3->select_table_advanced($campos2, "PRODUCCION", array(), " AND PRODUCCION.IDTABLEROPRODUCCION = ".$arreglo["TABLEROPRODUCCION.ID"], array("PRODUCCION.IDDEPARTAMENTO"), 0);
            $json3[$contador]["produccion"] = $json2;

                
            $counter = 0;
            $counter_check = 0;
            $counter_check2 = 0;

            if($json3[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'] == "")
                $json3[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'] = "0000-00-00";


            $time1 = substr($json3[$contador]['TABLEROPRODUCCION.FECHA'],0,10);
            $time2 = substr($json3[$contador]['TABLEROPRODUCCION.FECHA_ENTREGA'],0,10);

            $timestamp1 = mktime(0,0,0,date("m"), date("d"), date("Y"));
            $timestamp2 = mktime(0,0,0,substr($time2,5,2), substr($time2,8,2), substr($time2,0,4));


            $segundos_diferencia = $timestamp2 - $timestamp1;


            $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);


            $json3[$contador]['RESTANTE_ENTREGA'] =  floor($dias_diferencia);

            foreach($json2 as $production)
            {

                if($production['PRODUCCION.IDESTATUS'] == 2)
                    $counter_check++;
                if($production['PRODUCCION.IDESTATUS'] == 1 || $production['PRODUCCION.IDESTATUS'] == 3)
                    $counter_check2++;
                $counter++;
            }


            if($counter_check >= $counter)
                $json3[$contador]['TERMINADO'] = 1;
            else
                $json3[$contador]['TERMINADO'] = 0;


            if($counter_check2 == $counter)
                $json3[$contador]['NO_INICIADO'] = 1;
            else
                $json3[$contador]['NO_INICIADO'] = 0;
            $contador++;
        }

        
        $json4 = array_merge($json3, $json);
        if($_POST['iniciadas'] == 1)
        {

            for($i = 0; $i < count($json4); $i++)
            {
                if($json4[$i]["NO_INICIADO"] == 1)
                    $auxIniciadas[] = $json4[$i];
            }
            $json4 = $auxIniciadas;
        }

        if($_POST['realizadas'] == 1)
        {
            for($i = 0; $i < count($json4); $i++)
            {
                if($json4[$i]["TERMINADO"] == 1)
                    $auxIniciadas[] = $json4[$i];
            }
            $json4 = $auxIniciadas;
        }


        $contador = count($json4);
        for($i = 0; $i < $contador; $i++)
        {
            $j = ($i + 1);
            for(; $j < $contador; $j++)
            {
                if($json4[$i]['RESTANTE_ENTREGA'] > $json4[$j]['RESTANTE_ENTREGA'])
                {
                    $arrayAuxiliar[0] = $json4[$i];
                    $json4[$i] = $json4[$j];
                    $json4[$j] = $arrayAuxiliar[0];
                }
            }
        }

        $arrayEnviar = Array();
        $page = ($_POST['page'] -1);
        for($i = (0 + ($page * 20)); $i < (($page * 20) + 20); $i++)
        {
            if(!empty($json4[$i]))
                $arrayEnviar[] = $json4[$i];
        }
        $arrayEnviar['monto_total'] = $monto_total;
        $arrayEnviar['pagesnum'] = count($json4);


		$obj = (object) $arrayEnviar;
		echo json_encode($obj);*/
	}
	
	/*if($_POST["accion"] == "counter")
	{
	
		$join = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "TABLEROPRODUCCION.DOCTO_VE_ID", "UNION",
					  "CLIENTES","=", "DOCTOS_VE.CLIENTE_ID", "CLIENTES.CLIENTE_ID","UNION");
		
		$condicionales = " AND DOCTOS_VE.FECHA > '2014-11-01' AND (DOCTOS_VE.TIPO_DOCTO='F' OR DOCTOS_VE.TIPO_DOCTO='R') AND DOCTOS_VE.ESTATUS!='C'
							AND TABLEROPRODUCCION.ID NOT IN (SELECT IDTABLEROPRODUCCION FROM DOCUMENTOSFINALIZADOS)";
		
		if(isset($_POST['buscar']))
		{
			$buscar = (int)$_POST['buscar'];
			$condicionales.= " AND DOCTOS_VE.FOLIO like '%".$buscar."%'";
		}

		if(isset($_POST['client']))
		{
			$condicionales.= " AND CLIENTES.NOMBRE like '%".strtoupper($_POST['client'])."%'";
		}
		
		$conection2 = new conexion_nexos(1);
		$json = $conection2->counter_advanced("TABLEROPRODUCCION", $join, $condicionales, 0);

		$conection3 = new conexion_nexos($_SESSION['empresa']);
		$json2 = $conection3->counter_advanced("TABLEROPRODUCCION", $join, $condicionales, 0);

		$json->PAGINADOR += $json2->PAGINADOR; 
		
		$obj = (object) $json;
		echo json_encode($obj);
	}*/

	if($_POST['accion'] == "informacion")
	{
		$conection2 = new conexion_nexos($_POST['empresa']);
		$campos = array("DISENO", "IMPRESION", "MAQUILAS", "INSTALACION", "ENTREGA", "PREPARACION");
		$condicionales = " AND TABLEROPRODUCCION.ID=".$_POST['id'];
		
		$json = $conection2->select_table($campos, "TABLEROPRODUCCION", array(), $condicionales, array(), 0);


		$campos = array("PRODUCCION.IDDEPARTAMENTO", "PRODUCCION.FECHA", "OPERADOR.ALIAS", "PRODUCCION.IDESTATUS");
		$condicionales = " AND PRODUCCION.IDTABLEROPRODUCCION=".$_POST['id'];
		
		$join = array("OPERADORDEPARTAMENTO","=", "OPERADORDEPARTAMENTO.ID", "PRODUCCION.IDOPERADORDEPARTAMENTO",
					  "OPERADOR","=", "OPERADORDEPARTAMENTO.IDOPERADOR", "OPERADOR.ID");
		
		$json2 = $conection2->select_table($campos, "PRODUCCION", $join, $condicionales, array(), 0);
		
		$count = array("0"=>array("contador"=>count($json2)));
		$json3 = array_merge($json, $count);



		$json4 = array_merge($json3, $json2);
        $obj = (object) $json4;
        echo json_encode($obj);
		
	}

    if($_POST['accion'] == "informacion_extra")
    {

        $conection2 = new conexion_nexos($_POST['empresa']);

        $campos = array("DOCTOS_VE_DET.DOCTO_VE_DET_ID", "ARTICULOS.NOMBRE", "DOCTOS_VE_DET.UNIDADES", "ARTICULOS.UNIDAD_VENTA", "DOCTOS_VE_DET.NOTAS");

        $join = array("TABLEROPRODUCCION","=", "TABLEROPRODUCCION.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID",
                      "DOCTOS_VE_DET","=", "DOCTOS_VE_DET.DOCTO_VE_ID", "DOCTOS_VE.DOCTO_VE_ID",
                      "ARTICULOS","=", "DOCTOS_VE_DET.ARTICULO_ID", "ARTICULOS.ARTICULO_ID");

        $condicionales = " AND TABLEROPRODUCCION.ID=".$_POST['id']." AND DOCTOS_VE_DET.ROL!='C'";

        $order = array();


        $json = $conection2->select_table($campos, "DOCTOS_VE", $join, $condicionales, $order, 0);

        $obj = (object) $json;
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

	if($_POST["accion"] == "finzalizar")
	{
		if($_SESSION['IDUSUARIO']== 21 || $_SESSION['IDUSUARIO']== 15 || $_SESSION['IDUSUARIO']== 22  || $_SESSION['IDUSUARIO']== 18)
		{
            $conection2 = new conexion_nexos($_SESSION['empresa']);

            $campos = array("FINALIZAR_PROCESO");
            $valores = array(1);
            $id = " ID IN (".implode(",", $_POST['proceso_tablero']).")";
            $json = $conection2->update_table($campos, "TABLEROPRODUCCION", $valores, $id);
            
		}else{
			$json = array("error"=>"1");
		}	
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "abrirdocumento")
	{
		if($_SESSION['IDUSUARIO']== 21 || $_SESSION['IDUSUARIO']== 15 || $_SESSION['IDUSUARIO']== 22  || $_SESSION['IDUSUARIO']== 18)
		{
			$conection2 = new conexion_nexos($_POST['empresa']);
			$campos = array("CERRAR_SELECCION");
            $valores = array(0);
			$id = " TABLEROPRODUCCION.ID=".$_POST['id'];

			$json = $conection2->update_table($campos, "TABLEROPRODUCCION", $valores, $id);
			
		}else{
			$json = array("error"=>"1");
		}	
		//print_r($json);
		$obj = (object) $json2;
		echo json_encode($obj);
		
	}
?>