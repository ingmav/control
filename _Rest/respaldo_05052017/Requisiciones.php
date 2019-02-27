<?php
	include("../clases/conexion.php");
	session_start();
	date_default_timezone_set('America/Mexico_City');

	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos();

	if($_POST["accion"] == "index")
	{
		$campos = array("REQUISICIONES.ID", "REQUISICIONES.EMPRESA", "REQUISICIONES.TIPO_DOCUMENTO", "REQUISICIONES.FOLIO", "REQUISICIONES.FECHA", "REQUISICIONES.CLIENTE","REQUISICIONES.ESTATUS", "REQUISICIONES.FORMA_PAGO", "OPERADOR.ALIAS", "REQUISICIONES_ARTICULOS.PROVEEDOR");

		$join = array("OPERADOR", "=", "OPERADOR.ID", "REQUISICIONES.IDOPERADOR", "LEFT",
					  "REQUISICIONES_ARTICULOS", "=", "REQUISICIONES_ARTICULOS.REQUISICIONESID", "REQUISICIONES.ID", "LEFT");

        $consulta = "";
        if($_POST['clientefiltro']!="")
            $consulta .=" and (REQUISICIONES.CLIENTE LIKE '%".$_POST['clientefiltro']."%' OR REQUISICIONES_ARTICULOS.PROVEEDOR LIKE '%".$_POST['clientefiltro']."%')";

        if($_POST['estatusfiltro']!="")
            $consulta .=" and REQUISICIONES.ESTATUS='".$_POST['estatusfiltro']."'";

         if($_POST['foliofiltro']!="")
            $consulta .=" and REQUISICIONES.FOLIO='".$_POST['foliofiltro']."'";

        
        if($_POST['mesfiltro']!="0")
            $consulta .=" and REQUISICIONES.FECHA like '".date("Y-".$_POST['mesfiltro'])."%'";

        $condicionales = "  ".$consulta;
        $group = "GROUP BY REQUISICIONES.ID,
				REQUISICIONES.EMPRESA,
				REQUISICIONES.TIPO_DOCUMENTO,
				REQUISICIONES.FOLIO,
				REQUISICIONES.FECHA,
				REQUISICIONES.CLIENTE,
				REQUISICIONES.ESTATUS,
				REQUISICIONES.FORMA_PAGO,
				OPERADOR.ALIAS,
				REQUISICIONES_ARTICULOS.PROVEEDOR";

		$order = array("REQUISICIONES.FECHA DESC");

		$json = $conection->sum_regular2("REQUISICIONES_ARTICULOS.REQUISICIONESID", "REQUISICIONES_ARTICULOS.IMPORTE",$campos, "REQUISICIONES", $join, $condicionales, $group, $order, 1, $_POST['page']);
		
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "save")
	{

		$variables = $_POST;
		$reglas = array("foliorequisicion" => "texto",
						"fechaSolicitud" => "texto",
						"cliente" => "texto");

		$reglas_arreglo = array("proveedor" 		 => "texto",
								"factura_proveedor"  => "texto",
								"articulo" 			 => "texto",
								"cantidad"			 => "texto",
								"unidad"   			 => "texto",
								"importe" 			 => "moneda");

		$error = $conection->validador_formulario($variables, $reglas);
		if(count($error) > 0)
		{
			header('HTTP/1.1 409 Error interno');	
			$obj = (object) $error;
			echo json_encode($obj);	
			return 0;
		}

		

		if(count($variables['identificador']) == 0)
		{
			header('HTTP/1.1 409 Error interno');	
			$error = array( 0 => array("campo" => "Articulos"));
			$obj = (object) $error;
			echo json_encode($obj);	
			return 0;	
		}

		$arreglo_articulos =array();
		foreach ($variables['identificador'] as $key => $value) {

			$arreglo_valores['proveedor'] = $variables['proveedor'][$key];
			$arreglo_valores['factura'] = $variables['factura_proveedor'][$key];
			$arreglo_valores['articulo'] = $variables['nombre_articulo'][$key];
			$arreglo_valores['articuloid'] = $variables['id_articulo'][$key];
			$arreglo_valores['subarticuloid'] = $variables['id_sub_articulo'][$key];
			$arreglo_valores['cantidad'] = $variables['cantidad'][$key];
			$arreglo_valores['unidad'] = $variables['unidad'][$key];
			$arreglo_valores['importe'] = $variables['importe'][$key]; 	

			$error = $conection->validador_formulario($arreglo_valores, $reglas_arreglo);

			if(count($error) > 0)
			{
				header('HTTP/1.1 409 Error interno');	
				$obj = (object) $error;
				echo json_encode($obj);	
				return 0;
			}
			$arreglo_articulos[] = $arreglo_valores;
		}
		
		$_POST['estatus'] = 1;

		$campos = array("FOLIO", "EMPRESA", "TIPO_DOCUMENTO", "FECHA", "CLIENTE","ESTATUS", "IDOPERADOR", "OBSERVACION", "FORMA_PAGO");
		$valores = array("'".$_POST['foliorequisicion']."'", "'".$_POST['empresa']."'", "'".$_POST['tipo_documento']."'", "'".$_POST['fechaSolicitud']."'", "'".utf8_encode($_POST['cliente'])."'", $_POST['estatus'], $_SESSION['IDUSUARIO'], "'".$_POST['observacion']."'", "'".$_POST['forma_pago']."'");

		$json = $conection->insert_table($campos, "REQUISICIONES", $valores);
		
		$json2 = $conection->select_last_row_table("REQUISICIONES", array(), " order by ID DESC");
		
		
		foreach ($arreglo_articulos as $key => $value) {
			if($value['identificador'] == 0)
			{
				if($value['articuloid']=="")
					$value['articuloid'] = 0;

				if($value['subarticuloid']== "")
					$value['subarticuloid'] = 0;

				
				$campos = array("REQUISICIONESID", "PROVEEDOR", "FACTURA", "ARTICULO","ARTICULO_ID", "SUBARTICULO_ID", "CANTIDAD", "UNIDAD", "IMPORTE");
				$valores = array($json2->ID, "'".$value['proveedor']."'", "'".$value['factura']."'", "'".$value['articulo']."'", "'".$value['articuloid']."'", "'".$value['subarticuloid']."'", "'".$value['cantidad']."'", "'".$value['unidad']."'", "'".$value['importe']."'");
			
				$json = $conection->insert_table($campos, "REQUISICIONES_ARTICULOS", $valores);
			}
			
		}		
			
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "calcula_folio")
	{
		$condiciones = " AND TIPO_DOCUMENTO=4 ";
		$json = $conection->counter("REQUISICIONES", array(), $condiciones, 0);
		$obj = (object) $json;
        echo json_encode($obj);	
	}

	if($_POST["accion"] == "update")
	{
        $variables = $_POST;
        $reglas = array("foliorequisicion" => "texto",
            "fechaSolicitud" => "texto",
            "cliente" => "texto");

        $reglas_arreglo = array("proveedor" 		 => "texto",
								"factura_proveedor"  => "texto",
								"articulo" 			 => "texto",
								"cantidad"			 => "texto",
								"unidad"   			 => "texto",
								"importe" 			 => "moneda");

        $error = $conection->validador_formulario($variables, $reglas);
        if(count($error) > 0)
        {
            header('HTTP/1.1 409 Error interno');
            $obj = (object) $error;
            echo json_encode($obj);
            return 0;
        }



        if(count($variables['identificador']) == 0)
        {
            header('HTTP/1.1 409 Error interno');
            $error = array( 0 => array("campo" => "Articulos"));
            $obj = (object) $error;
            echo json_encode($obj);
            return 0;
        }

        $arreglo_articulos =array();
        foreach ($variables['identificador'] as $key => $value) {

            $arreglo_valores['proveedor'] = $variables['proveedor'][$key];
            $arreglo_valores['factura'] = $variables['factura_proveedor'][$key];
            $arreglo_valores['articulo'] = $variables['nombre_articulo'][$key];
            $arreglo_valores['articuloid'] = $variables['id_articulo'][$key];
            $arreglo_valores['subarticuloid'] = $variables['id_sub_articulo'][$key];
            $arreglo_valores['cantidad'] = $variables['cantidad'][$key];
            $arreglo_valores['unidad'] = $variables['unidad'][$key];
            $arreglo_valores['importe'] = $variables['importe'][$key];
            $arreglo_valores['identificador'] = $variables['identificador'][$key];

            $error = $conection->validador_formulario($arreglo_valores, $reglas_arreglo);

            if(count($error) > 0)
            {
                header('HTTP/1.1 409 Error interno');
                $obj = (object) $error;
                echo json_encode($obj);
                return 0;
            }
            $arreglo_articulos[] = $arreglo_valores;
        }
        $_POST['estatus'] = 1;
        $campos = array("FOLIO", "EMPRESA", "TIPO_DOCUMENTO", "FECHA", "CLIENTE","ESTATUS", "OBSERVACION", "FORMA_PAGO");
        $valores = array("'".$_POST['foliorequisicion']."'", "'".$_POST['empresa']."'", "'".$_POST['tipo_documento']."'", "'".$_POST['fechaSolicitud']."'", "'".utf8_decode($_POST['cliente'])."'", $_POST['estatus'], "'".utf8_decode($_POST['observacion'])."'", "'".$_POST['forma_pago']."'");

        
        $json = $conection->update_table($campos, "REQUISICIONES", $valores, " ID=".$_POST['id']);
        $arreglo_registros = array();
        foreach ($arreglo_articulos as $key => $value) {
        	
            if($value['identificador'] != 0)
            {
                $arreglo_registros[] = $value['identificador'];
            }
        }
        //print_r($arreglo_registros);

        if(count($arreglo_registros) > 0)
        	if(!$conection->delete_of_table("REQUISICIONES_ARTICULOS", " REQUISICIONESID=".$_POST['id']." and id not in ", $arreglo_registros))
        	{
        		header('HTTP/1.1 409 Error interno');
        		$error = array(array("campo"=>"NO SE HA PODIDO ACTUALIZAR LOS PRODUCTOS"));
                $obj = (object) $error;
                echo json_encode($obj);
                return 0;
        	}	
        foreach ($arreglo_articulos as $key => $value) {
        	if($value['articuloid'] == "")
            		$value['articuloid'] = 0;	
            if($value['subarticuloid'] == "")
            	$value['subarticuloid'] = 0;
            	
            if($value['identificador'] == 0)
            {

                $campos = array("REQUISICIONESID", "PROVEEDOR", "FACTURA", "ARTICULO","ARTICULO_ID", "SUBARTICULO_ID", "CANTIDAD", "UNIDAD", "IMPORTE");
                $valores = array($_POST['id'], "'".utf8_decode($value['proveedor'])."'", "'".$value['factura']."'", "'".utf8_decode($value['articulo'])."'", "".$value['articuloid']."", "".$value['subarticuloid']."", "'".$value['cantidad']."'", "'".$value['unidad']."'", "'".$value['importe']."'");

                $conection->insert_table($campos, "REQUISICIONES_ARTICULOS", $valores);
            }else{
                $campos = array("PROVEEDOR", "FACTURA", "ARTICULO","ARTICULO_ID", "SUBARTICULO_ID", "CANTIDAD", "UNIDAD", "IMPORTE");
                $valores = array("'".utf8_decode($value['proveedor'])."'", "'".$value['factura']."'", "'".utf8_decode($value['articulo'])."'", "".$value['articuloid']."", "".$value['subarticuloid']."", "'".$value['cantidad']."'", "'".$value['unidad']."'", "'".$value['importe']."'");

                $conection->update_table($campos, "REQUISICIONES_ARTICULOS", $valores, " ID=".$value['identificador']);
            }

        }

        $obj = (object) $json;
        echo json_encode($obj);

      
	}

	if($_POST["accion"] == "eliminar")
	{
		if($_SESSION['IDUSUARIO'] == 21)
		{
			if(count($_POST['id']) > 0)
			{
				$json = $conection->delete_table("REQUISICIONES", "ID IN", $_POST['id']);
				
				$obj = (object) $json;
				echo json_encode($obj);
			}
		}else
		{
			 	header('HTTP/1.1 409 Error interno');
                $json = array(array("campo" =>"NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓN."));
				$obj = (object) $json;
				echo json_encode($obj);
		}
	}

	if($_POST["accion"] == "surtir")
	{
		if($_SESSION['IDUSUARIO'] == 21 || $_SESSION['IDUSUARIO'] == 46)
		{
			if(count($_POST['id']) > 0)
			{
				$campos = array("ESTATUS");
				$valores = array("2");
				$id = " ID IN (".implode(",", $_POST['id']).")";

				$json = $conection->update_table($campos, "REQUISICIONES", $valores, $id);
				
				$obj = (object) $json;
				echo json_encode($obj);
			}
		}else
		{
			 	header('HTTP/1.1 409 Error interno');
                $json = array(array("campo" =>"NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓNs.".$_SESSION['IDUSUARIO']));
				$obj = (object) $json;
				echo json_encode($obj);
		}
	}

	if($_POST["accion"] == "validar")
	{
		if($_SESSION['IDUSUARIO'] == 21)
		{
			if(count($_POST['id']) > 0)
			{
				$campos = array("ESTATUS");
				$valores = array("3");
				$id = " ID IN (".implode(",", $_POST['id']).")";

				$json = $conection->update_table($campos, "REQUISICIONES", $valores, $id);
				
				$obj = (object) $json;
				echo json_encode($obj);
			}
		}else
		{
			 	header('HTTP/1.1 409 Error interno');
                $json = array(array("campo" =>"NO TIENE PERMISO PARA REALIZAR ESTA ACCIÓN."));
				$obj = (object) $json;
				echo json_encode($obj);
		}
	}

	if($_POST["accion"] == "modificar")
	{
        $campos = array("ID","FOLIO", "EMPRESA", "TIPO_DOCUMENTO","FECHA", "CLIENTE","ESTATUS", "OBSERVACION", "FORMA_PAGO");

		$join = array();
		$condicionales = " AND ID=".$_POST['id'][0];
		$order = array();

		$json = $conection->select_table($campos, "REQUISICIONES", $join, $condicionales, $order, 1);

		$condicionales2 = " AND REQUISICIONESID = ".$json[0]['ID'];
		$campos2 = array("ID", "PROVEEDOR", "FACTURA", "ARTICULO", "ARTICULO_ID", "SUBARTICULO_ID", "CANTIDAD", "UNIDAD", "IMPORTE");
		$json2 = $conection->select_table($campos2, "REQUISICIONES_ARTICULOS", array(), $condicionales2, array(), 0);
		
		$json[0]['ARTICULOS'] = $json2;
	

		$obj = (object) $json;
		echo json_encode($obj);
	}

	
	/*if($_POST["accion"] == "counter")
	{

		$join = array();

        $consulta = "";
        if($_POST['clientefiltro']!="")
            $consulta .=" and REQUISICIONES.CLIENTE LIKE '%".$_POST['clientefiltro']."%'";

        if($_POST['estatusfiltro']!="")
            $consulta .=" and REQUISICIONES.ESTATUS='".$_POST['estatusfiltro']."'";

         if($_POST['mesfiltro']!="0")
            $consulta .=" and REQUISICIONES.FECHA like '".date("Y-".$_POST['mesfiltro'])."%'";

        $condicionales = "  ".$consulta;

		$json = $conection->counter("REQUISICIONES", $join, $condicionales, 1);

		$obj = (object) $json;
		echo json_encode($obj);
	}*/

	if($_POST["accion"] == "consulta_folio")
	{
		if($_POST['empresa'] > 0)
		{
			if($_POST['tipo_documento'] > 0)
			{
				$conection = new conexion_nexos($_POST['empresa']);
				switch ($_POST['tipo_documento']) {
					case '1':
						$table = "DOCTOS_VE";
						$variable_table = $table.".TIPO_DOCTO='F' and ".$table.".folio like '%".str_pad($_POST['folio'], 8, "0", STR_PAD_LEFT)."'";
						break;
					case '2':
						$table = "DOCTOS_VE";
						$variable_table = $table.".TIPO_DOCTO='R' and ".$table.".folio like '%".str_pad($_POST['folio'], 8, "0", STR_PAD_LEFT)."'";
						break;
					case '3':
						$table = "DOCTOS_PV";
						$variable_table = $table.".TIPO_DOCTO='V' and ".$table.".folio like '%".str_pad($_POST['folio'], 8, "0", STR_PAD_LEFT)."'";
						break;	
				}
				 
				 $join = array("CLIENTES", "=", "CLIENTES.CLIENTE_ID", $table.".CLIENTE_ID");
				 $campos = array("CLIENTES.NOMBRE");
				 $folio = $_POST['folio'];

				 $condicionales = "AND ".$variable_table;
				 $json = $conection->select_table_first($campos, $table, $join, $condicionales, array(), 0);

				 if(count($json))
				 {
					switch ($_POST['tipo_documento']) {
						case '1':
							$table = "DOCTOS_VE";
							$table2 = "DOCTOS_VE_DET";
							$pivot = "DOCTO_VE_ID";
							$variable_table = $table.".TIPO_DOCTO='F' and ".$table.".folio = '".str_pad($_POST['folio'], 9, "0", STR_PAD_LEFT)."' and DOCTOS_VE_DET.PRECIO_TOTAL_NETO>0 ";
							$campos = array("ARTICULOS.NOMBRE", $table2.".UNIDADES", $table2.".PRECIO_TOTAL_NETO");
							break;
						case '2':
							$table = "DOCTOS_VE";
							$table2 = "DOCTOS_VE_DET";
							$pivot = "DOCTO_VE_ID";
							$variable_table = $table.".TIPO_DOCTO='R' and ".$table.".folio='".str_pad($_POST['folio'], 9, "0", STR_PAD_LEFT)."' and DOCTOS_VE_DET.PRECIO_TOTAL_NETO>0 ";
							$campos = array("ARTICULOS.NOMBRE", $table2.".UNIDADES", $table2.".PRECIO_TOTAL_NETO");
							break;
						case '3':
							$table = "DOCTOS_PV";
							$table2 = "DOCTOS_PV_DET";
							$pivot = "DOCTO_PV_ID";
							$variable_table = $table.".TIPO_DOCTO='V' and ".$table.".folio= 'A".str_pad($_POST['folio'], 8, "0", STR_PAD_LEFT)."' and DOCTOS_PV_DET.PRECIO_TOTAL_NETO>0 ";
							$campos = array("ARTICULOS.NOMBRE", $table2.".UNIDADES", $table2.".PRECIO_TOTAL_NETO");
							break;	
					}
					 
					 $join = array("CLIENTES", "=", "CLIENTES.CLIENTE_ID", $table.".CLIENTE_ID", "LEFT",
					 			  $table2, "=", $table2.".".$pivot, $table.".".$pivot, "LEFT",
					 			  "ARTICULOS", "=", "ARTICULOS.ARTICULO_ID", $table2.".ARTICULO_ID", "LEFT",);

					 
					 
					 $condicionales = "AND ".$variable_table;
					 $json2 = $conection->select_table_advanced($campos, $table, $join, $condicionales, array(), 0);

					 $arreglo_salida = array();
					 foreach ($json2 as $key => $value) {
					 	$index = count($arreglo_salida);
					 	$arreglo_salida[$index]['NOMBRE'] = $value["ARTICULOS.NOMBRE"];
					 	$arreglo_salida[$index]['UNIDAD'] = $value[$table2.".UNIDADES"];
					 	$arreglo_salida[$index]['PRECIO'] = $value[$table2.".PRECIO_TOTAL_NETO"];
					 }

				 }
				 $arreglo_respuesta = array($json, $arreglo_salida);	
				 $obj = (object) $arreglo_respuesta;
				 echo json_encode($obj);
			}else
			{
				echo 0;
			}
		}else
		{
			echo 0;
		}
	}
?>
