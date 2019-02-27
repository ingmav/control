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
                        "ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO"
                        );
		
		$join = array("TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE", "UNION",
                      "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION", "UNION",
                      "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","UNION",
                      "CONTACTOCLIENTESCALL", "=", "CONTACTOCLIENTESCALL.IDCLIENTESCALL", "CLIENTESCALL.ID", "UNION",
                      "ESTATUSSEGUIMIENTO", "=", "CLIENTESCALL.IDESTATUSSEGUIMIENTO", "ESTATUSSEGUIMIENTO.ID","UNION"
        );

        $condicionales = " AND CONTACTOCLIENTESCALL.PRINCIPAL=1";

        $order = array();

        if($_POST['filtrocliente'] != "")
        {
            $condicionales .= " AND (CLIENTESCALL.NOMBRE like '%".$_POST['filtrocliente']."%' OR CONTACTOCLIENTESCALL.CONTACTO1 like '%".$_POST['filtrocliente']."%')";
        }

        if($_POST['filtrotipocliente'] != "")
        {
            $condicionales .= " AND CLIENTESCALL.IDTIPOCLIENTE like '%".$_POST['filtrotipocliente']."%'";
        }

        if($_POST['filtrosegmento'] != "0")
        {
            $condicionales .= " AND SEGMENTOCALL.ID = '".$_POST['filtrosegmento']."'";
        }

        $json = $conection->select_table_advanced($campos, "CLIENTESCALL", $join, $condicionales, $order, 1, $_POST['page']);


		$obj = (object) $json;
		echo json_encode($obj);
	}

    if($_POST["accion"] == "seleccion_mail")
    {
        if($_POST['tipo'] == 1)
        {
            $campos = array("CONTACTOCLIENTESCALL.CORREO");

            $campos = array("CONTACTOCLIENTESCALL.CORREO");
        
            $join = array("TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE", "UNION",
                          "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION", "UNION",
                          "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","UNION",
                          "CONTACTOCLIENTESCALL", "=", "CONTACTOCLIENTESCALL.IDCLIENTESCALL", "CLIENTESCALL.ID", "UNION",
                          "ESTATUSSEGUIMIENTO", "=", "CLIENTESCALL.IDESTATUSSEGUIMIENTO", "ESTATUSSEGUIMIENTO.ID","UNION"
            );

            $condicionales = " AND CONTACTOCLIENTESCALL.CORREO!='' AND CLIENTESCALL.EMAILING=1";

            $order = array();

            if($_POST['filtrocliente'] != "")
            {
                $condicionales .= " AND (CLIENTESCALL.NOMBRE like '%".$_POST['filtrocliente']."%' OR CONTACTOCLIENTESCALL.CONTACTO1 like '%".$_POST['filtrocliente']."%')";
            }

            if($_POST['filtrotipocliente'] != "")
            {
                $condicionales .= " AND CLIENTESCALL.IDTIPOCLIENTE like '%".$_POST['filtrotipocliente']."%'";
            }

            if($_POST['filtrosegmento'] != "0")
            {
                $condicionales .= " AND SEGMENTOCALL.ID = '".$_POST['filtrosegmento']."'";
            }

            $json = $conection->select_table_advanced($campos, "CLIENTESCALL", $join, $condicionales, $order, 1);



            $obj = (object) $json;
            echo json_encode($obj);
        }else if($_POST['tipo'] == 2)
        {
            $campos = array("CONTACTOCLIENTESCALL.CORREO");

            $join = array();

            $condicionales = " AND IDCLIENTESCALL IN (".$_POST['ids'].") AND CONTACTOCLIENTESCALL.CORREO!=''";

            $order = array();
            

            $json = $conection->select_table($campos, "CONTACTOCLIENTESCALL", $join, $condicionales, $order, 0);

            $obj = (object) $json;
            echo json_encode($obj);
        }
    }

if($_POST["accion"] == "counter")
{

    $candado = "";

    $join = array("TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE", "UNION",
        "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION", "UNION",
        "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","UNION",
        "CONTACTOCLIENTESCALL", "=", "CONTACTOCLIENTESCALL.IDCLIENTESCALL", "CLIENTESCALL.ID", "UNION"
    );

    $condicionales = " AND CONTACTOCLIENTESCALL.PRINCIPAL=1";

    $order = array();

    if($_POST['filtrocliente'] != "")
    {
        $condicionales .= " AND CLIENTESCALL.NOMBRE like '%".$_POST['filtrocliente']."%'";
    }

    if($_POST['filtrotipocliente'] != "")
    {
        $condicionales .= " AND CLIENTESCALL.IDTIPOCLIENTE like '%".$_POST['filtrotipocliente']."%'";
    }

    if($_POST['filtrosegmento'] != "0")
    {
        $condicionales .= " AND SEGMENTOCALL.ID = '".$_POST['filtrosegmento']."'";
    }

    $json = $conection->counter_advanced("CLIENTESCALL", $join, $condicionales, 1);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "lista_email")
{
    $campos = array("CONTACTOCLIENTESCALL.CORREO");

    $join = array("CONTACTOCLIENTESCALL", "=", "CLIENTESCALL.ID", "CONTACTOCLIENTESCALL.IDCLIENTESCALL");

    $condicionales = " AND CLIENTESCALL.EMAILING=1 AND BORRADO IS NULL";

    $order = array();
    $condicionales = " ".$candado;


    $json = $conection->select_table($campos, "CLIENTESCALL", $join, $condicionales, $order, 1);

    $obj = (object) $json;
    echo json_encode($obj);
}


if($_POST["accion"] == "update_crm")
{
    try{
        $campos = array(
                        "CLIENTES.NOMBRE",
                        "CLIENTES.CONTACTO1",
                        "CLIENTES.CONTACTO2",
                        "CLIENTES.CLIENTE_ID"
                        );

        $join = array();

        $condicionales = " ";

        $order = array();

        $json = $conection->select_table($campos, "CLIENTES", $join, $condicionales, $order, 0);

        foreach ($json as $lista1 => $key) {
            $campos = array("DIRS_CLIENTES.CALLE",
                "DIRS_CLIENTES.TELEFONO1",
                "DIRS_CLIENTES.TELEFONO2",
                "DIRS_CLIENTES.EMAIL",
                "DIRS_CLIENTES.CONTACTO"
            );

            $join = array();

            $condicionales = " AND DIRS_CLIENTES.CLIENTE_ID=".$key['CLIENTES.CLIENTE_ID'];

            $order = array();

            $json3 = $conection->select_table($campos, "DIRS_CLIENTES", $join, $condicionales, $order, 0);

            $json[$lista1]['CONTACTO'] = $json3;
        }

        $conection2 = new conexion_nexos(1);
        $campos = array(
            "CLIENTES.NOMBRE",
            "CLIENTES.CONTACTO1",
            "CLIENTES.CONTACTO2",
            "CLIENTES.CLIENTE_ID"
        );

        $join = array();

        $condicionales = " ";

        $order = array();

        $json4 = $conection2->select_table($campos, "CLIENTES", $join, $condicionales, $order, 0);

        foreach ($json4 as $lista1 => $key) {
            $campos = array("DIRS_CLIENTES.CALLE",
                "DIRS_CLIENTES.TELEFONO1",
                "DIRS_CLIENTES.TELEFONO2",
                "DIRS_CLIENTES.EMAIL",
                "DIRS_CLIENTES.CONTACTO"
            );

            $join = array();

            $condicionales = " AND DIRS_CLIENTES.CLIENTE_ID=".$key['CLIENTES.CLIENTE_ID'];

            $order = array();

            $json5 = $conection2->select_table($campos, "DIRS_CLIENTES", $join, $condicionales, $order, 0);

            $json4[$lista1]['CONTACTO'] = $json5;
        }

        foreach ($json as $valor1 => $key1) {
            $indice = 0;
            foreach ($json4 as $valor2 => $key2) {
                if($key1["CLIENTES.NOMBRE"] == $key2["CLIENTES.NOMBRE"])
                {
                    unset($json4[$valor2]);
                    break;
                }
                $indice++;
            }
        }
        $json6 = array_merge($json, $json4);

        $conection = new conexion_nexos(2);

        foreach ($json6 as $valor3) {
            $condicionales = " AND CLIENTESCALL.NOMBRE = '".$valor3['CLIENTES.NOMBRE']."'";
            $registro = $conection->counter("CLIENTESCALL", array(), $condicionales, 1);
            if($registro->PAGINADOR == 0)
            {

                $campos = array("IDTIPOCLIENTE", "NOMBRE", "PAGINAWEB", "IDCLASIFICACION", "IDSEGMENTO", "OBSERVACION", "IDESTATUSCLIENTECALL", "IDESTATUSSEGUIMIENTO");
                $valores = array(2, "'".utf8_decode($valor3['CLIENTES.NOMBRE'])."'", "''", 3 , 31, "''", 1, 1 );


                $json7 = $conection->insert_table($campos, "CLIENTESCALL", $valores);

                $max = $conection->select_max_table("ID", "CLIENTESCALL", array(), "");


                $indice = 0;
                foreach ($valor3['CONTACTO'] as $valor4) {
                    if($indice == 0)
                    {
                        $campos = array("IDCLIENTESCALL", "CONTACTO1", "CONTACTO2", "TELEFONO1", "TELEFONO2", "DIRECCION", "CORREO", "HORARIO", "EMAILING", "PRINCIPAL");
                        $valores = array($max, "'".utf8_decode($valor3['CLIENTES.CONTACTO1'])."'", "'".utf8_decode($valor3['CLIENTES.CONTACTO2'])."'", "'".$valor4['DIRS_CLIENTES.TELEFONO1']."'", "'".$valor4['DIRS_CLIENTES.TELEFONO2']."'", "'".$valor4['DIRS_CLIENTES.CALLE']."'" ,"'".$valor4['DIRS_CLIENTES.EMAIL']."'" , "''", 1, 1 );

                        $json2 = $conection->insert_table($campos, "CONTACTOCLIENTESCALL", $valores);
                    }else
                    {
                        $campos = array("IDCLIENTESCALL", "CONTACTO1", "CONTACTO2", "TELEFONO1", "TELEFONO2", "DIRECCION", "CORREO", "HORARIO", "EMAILING", "PRINCIPAL");
                        $valores = array($max, "'".utf8_decode($valor4['DIRS_CLIENTES.CONTACTO'])."'", "''", "'".$valor4['DIRS_CLIENTES.TELEFONO1']."'", "'".$valor4['DIRS_CLIENTES.TELEFONO2']."'", "'".$valor4['DIRS_CLIENTES.CALLE']."'" ,"'".$valor4['DIRS_CLIENTES.EMAIL']."'" , "''", 1, 0 );

                        $json2 = $conection->insert_table($campos, "CONTACTOCLIENTESCALL", $valores);
                    }
                    $indice++;
                }


            }
        }
    }catch(Exception $e) {
        echo 'Excepción capturada: ',  $e, "\n";
    }


    //$json7 = array_map("unserialize", array_unique(array_map("serialize", $json6)));

    $obj = (object) $json6;
    echo json_encode($obj);
}


if($_POST["accion"] == "cargaCatalogos")
{

    $campos = array("TIPOCLIENTESCALL.ID", "TIPOCLIENTESCALL.DESCRIPCION");

    $json = $conection->select_table($campos, "TIPOCLIENTESCALL", array(), "", array(), 0);

    $campos2 = array("CLASIFICACIONCALL.ID", "CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION");

    $json2 = $conection->select_table($campos2, "CLASIFICACIONCALL", array(), "", array(), 0);

    $campos3 = array("SEGMENTOCALL.ID", "SEGMENTOCALL.DESCRIPCIONSEGMENTO");

    $json3 = $conection->select_table($campos3, "SEGMENTOCALL", array(), "", array(), 0);

    $campos4 = array("ESTATUSSEGUIMIENTO.ID", "ESTATUSSEGUIMIENTO.DESCRIPCIONSEGUIMIENTO");

    $json4 = $conection->select_table($campos4, "ESTATUSSEGUIMIENTO", array(), "", array(), 0);

    $campos5 = array("TIPOS_CLIENTES.TIPO_CLIENTE_ID", "TIPOS_CLIENTES.NOMBRE");

    $json5 = $conection->select_table($campos5, "TIPOS_CLIENTES", array(), "", array(), 0);

    $resultado = Array();

    $resultado[0] = $json;
    $resultado[1] = $json2;
    $resultado[2] = $json3;
    $resultado[3] = $json4;
    $resultado[4] = $json5;

    $obj = (object) $resultado;
    echo json_encode($obj);
}

if($_POST["accion"] == "save")
{
    
    $campos = array("IDTIPOCLIENTE", "NOMBRE", "PAGINAWEB", "IDCLASIFICACION", "IDSEGMENTO", "OBSERVACION", "IDESTATUSCLIENTECALL", "IDESTATUSSEGUIMIENTO", "IDTIPOCLIENTEMICRO");
    $valores = array($_POST['tipoCliente'], "'".utf8_decode(strtoupper($_POST['nombreCliente']))."'", utf8_decode("'".$_POST['pagina']."'"), $_POST['clase'] ,$_POST['clasificacion'] , "'".$_POST['observacion']."'", 1, $_POST['estatusSeguimiento'], $_POST['t_cliente'] );


    $json = $conection->insert_table($campos, "CLIENTESCALL", $valores);

    $max = $conection->select_max_table("ID", "CLIENTESCALL", array(), "");

    $campos = array("IDCLIENTESCALL", "CONTACTO1", "CONTACTO2", "TELEFONO1", "TELEFONO2", "DIRECCION", "CORREO", "HORARIO", "EMAILING", "PRINCIPAL");
    $valores = array($max, "'".utf8_decode(strtoupper($_POST['contacto1']))."'", "'".utf8_decode(strtoupper($_POST['contacto2']))."'", "'".$_POST['telefono1']."'", "'".$_POST['telefono2']."'", "'".$_POST['direccion']."'" ,"'".$_POST['correo']."'" , "'".$_POST['horario']."'", $_POST['emailing'], 1 );


    $json2 = $conection->insert_table($campos, "CONTACTOCLIENTESCALL", $valores);

    $obj = (object) $json2;
    echo json_encode($obj);
}

if($_POST["accion"] == "saveContacto")
{
    if($_POST['principal'] == 1)
    {
        $id = " CONTACTOCLIENTESCALL.IDCLIENTESCALL=".$_POST['id'];
        $json = $conection->update_table(array("PRINCIPAL"), "CONTACTOCLIENTESCALL", array(0), $id);
    }

    if($_POST['num_contacto'] > 0 )
    {

        $campos = array("CONTACTO1", "CONTACTO2", "TELEFONO1", "TELEFONO2", "DIRECCION", "CORREO", "HORARIO", "EMAILING", "PRINCIPAL");
        $valores = array("'".utf8_decode(strtoupper($_POST['contacto1']))."'", "'".utf8_decode(strtoupper($_POST['contacto2']))."'", "'".$_POST['telefono1']."'", "'".$_POST['telefono2']."'", "'".$_POST['direccion']."'" ,"'".$_POST['correo']."'" , "'".$_POST['horario']."'", $_POST['emailing'], $_POST['principal'] );

        $id = " CONTACTOCLIENTESCALL.ID=".$_POST['num_contacto']." and CONTACTOCLIENTESCALL.IDCLIENTESCALL=".$_POST['id'];
        $json = $conection->update_table($campos, "CONTACTOCLIENTESCALL", $valores, $id);
    }else
    {
        $campos = array("IDCLIENTESCALL", "CONTACTO1", "CONTACTO2", "TELEFONO1", "TELEFONO2", "DIRECCION", "CORREO", "HORARIO", "EMAILING", "PRINCIPAL");
        $valores = array($_POST['id'], "'".utf8_decode($_POST['contacto1'])."'", "'".utf8_decode($_POST['contacto2'])."'", "'".$_POST['telefono1']."'", "'".$_POST['telefono2']."'", "'".$_POST['direccion']."'" ,"'".$_POST['correo']."'" , "'".$_POST['horario']."'", $_POST['emailing'], $_POST['principal'] );


        $json = $conection->insert_table($campos, "CONTACTOCLIENTESCALL", $valores);
    }

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST["accion"] == "deleteContacto")
{
    $id = " CONTACTOCLIENTESCALL.ID=".$_POST['num_contacto']." and CONTACTOCLIENTESCALL.IDCLIENTESCALL=".$_POST['id'];
    $json = $conection->delete_of_table("CONTACTOCLIENTESCALL", $id, NULL);

    $obj = (object) $json;
    echo json_encode($obj);
}



if($_POST["accion"] == "update")
{

    $campos = array("IDTIPOCLIENTE", "NOMBRE", "PAGINAWEB", "IDCLASIFICACION", "IDSEGMENTO", "OBSERVACION", "IDESTATUSCLIENTECALL", "IDESTATUSSEGUIMIENTO", "IDTIPOCLIENTEMICRO");
    $valores = array($_POST['tipoCliente'], "'".utf8_decode(strtoupper($_POST['nombreCliente']))."'", utf8_decode("'".$_POST['pagina']."'"), $_POST['clase'] ,$_POST['clasificacion'] , "'".$_POST['observacion']."'", 1, $_POST['estatusSeguimiento'], $_POST['t_cliente']);

    $json2 = $conection->update_table($campos, "CLIENTESCALL", $valores, " CLIENTESCALL.ID=".$_POST['id']);

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST['accion'] == "cargaCliente")
{
    $candado = "";

    $campos = array("CLIENTESCALL.ID",
        "TIPOCLIENTESCALL.DESCRIPCION",
        "CLIENTESCALL.NOMBRE",
        "CLIENTESCALL.PAGINAWEB",
        "CLIENTESCALL.IDCLASIFICACION",
        "CLIENTESCALL.IDSEGMENTO",
        "CLIENTESCALL.IDESTATUSCLIENTECALL",
        "CLIENTESCALL.IDTIPOCLIENTE",
        "CLIENTESCALL.IDESTATUSSEGUIMIENTO",
        "CLIENTESCALL.OBSERVACION",
        "CLIENTESCALL.IDTIPOCLIENTEMICRO",
        "CLASIFICACIONCALL.DESCRIPCIONCLASIFICACION",
        "SEGMENTOCALL.DESCRIPCIONSEGMENTO",

    );

    $join = array("TIPOCLIENTESCALL", "=", "TIPOCLIENTESCALL.ID", "CLIENTESCALL.IDTIPOCLIENTE", "UNION",
        "CLASIFICACIONCALL", "=", "CLASIFICACIONCALL.ID", "CLIENTESCALL.IDCLASIFICACION", "UNION",
        "SEGMENTOCALL", "=", "SEGMENTOCALL.ID", "CLIENTESCALL.IDSEGMENTO","UNION"
    );

    $condicionales = " and CLIENTESCALL.ID=".$_POST['id'];

    $order = array();


    $json = $conection->select_table_advanced($campos, "CLIENTESCALL", $join, $condicionales, $order, 1);

    $contador = 0;
    foreach($json as $val => $key)
    {
        //echo $key['CLIENTESCALL.ID'];
        $campos2 = array("CONTACTOCLIENTESCALL.ID",
            "CONTACTOCLIENTESCALL.CONTACTO1",
            "CONTACTOCLIENTESCALL.CONTACTO2",
            "CONTACTOCLIENTESCALL.DIRECCION",
            "CONTACTOCLIENTESCALL.TELEFONO1",
            "CONTACTOCLIENTESCALL.TELEFONO2",
            "CONTACTOCLIENTESCALL.CORREO",
            "CONTACTOCLIENTESCALL.HORARIO",
            "CONTACTOCLIENTESCALL.EMAILING",
            "CONTACTOCLIENTESCALL.PRINCIPAL"
        );

        $join2 = array();

        $condicionales2 = " AND CONTACTOCLIENTESCALL.IDCLIENTESCALL=".$key['CLIENTESCALL.ID'];

        $order = array();
        $condicionales = " ".$candado;

        $json2 = $conection->select_table($campos2, "CONTACTOCLIENTESCALL", $join2, $condicionales2, $order, 0);

        $json[$contador]['CONTACTO'] = $json2;

        $contador++;
    }


    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST['accion'] == "cargaContacto")
{
    $campos2 = array(
        "CONTACTOCLIENTESCALL.CONTACTO1",
        "CONTACTOCLIENTESCALL.CONTACTO2",
        "CONTACTOCLIENTESCALL.DIRECCION",
        "CONTACTOCLIENTESCALL.TELEFONO1",
        "CONTACTOCLIENTESCALL.TELEFONO2",
        "CONTACTOCLIENTESCALL.CORREO",
        "CONTACTOCLIENTESCALL.HORARIO",
        "CONTACTOCLIENTESCALL.EMAILING",
        "CONTACTOCLIENTESCALL.PRINCIPAL"
    );

    $join2 = array();

    $condicionales2 = " AND CONTACTOCLIENTESCALL.IDCLIENTESCALL=".$_POST['id']." AND CONTACTOCLIENTESCALL.ID=".$_POST['contacto'];

    $order = array();
    $condicionales = " ".$candado;

    $json2 = $conection->select_table($campos2, "CONTACTOCLIENTESCALL", $join2, $condicionales2, $order, 0);

    $obj = (object) $json2;
    echo json_encode($obj);
}

if($_POST['accion'] == "bajaCliente")
{

    $condicionales = " AND CONTACTOCLIENTESCALL.PRINCIPAL=1 AND CLIENTESCALL=".$_POST['id'];

    $order = array();

    $json = $conection->delete_table("CLIENTESCALL", "CLIENTESCALL.ID in", array($_POST['id']));

    $obj = (object) $json;
    echo json_encode($obj);
}

if($_POST['accion'] == "actualizaTipo")
{
    $candado = "";

    $campos = array("CLIENTESCALL.IDTIPOCLIENTE");

    $condicionales = " AND CLIENTESCALL.ID=".$_POST['idcliente'];

    $order = array();

   $json = $conection->select_table_advanced($campos, "CLIENTESCALL", array(), $condicionales, array(), 1);

    if($json[0]['CLIENTESCALL.IDTIPOCLIENTE'] == 1)
    {
        $campos = array("IDTIPOCLIENTE");
        $valores = array(2);

        $id = " CLIENTESCALL.ID=".$_POST['idcliente'];
        $json = $conection->update_table($campos, "CLIENTESCALL", $valores, $id);

    }else if($json[0]['CLIENTESCALL.IDTIPOCLIENTE'] == 2)
    {
        $campos = array("IDTIPOCLIENTE");
        $valores = array(1);

        $id = " CLIENTESCALL.ID=".$_POST['idcliente'];
        $json = $conection->update_table($campos, "CLIENTESCALL", $valores, $id);
    }
    $obj = (object) $json;
    echo json_encode($obj);
}

?>