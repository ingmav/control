<?php
	header("Content-type: application/rtf; charset=utf-8");
	include("../clases/conexion.php");
	include("../clases/utilerias.php");

	session_start();
	
	date_default_timezone_set('America/Mexico_City');
	
	if($_POST["accion"] == "index")
	{

		$candado = "";
		
		$json = array();
		$conection1 = new conexion_nexos(2);
		
		
		$query = "select
		DOCTOS_VE.DOCTO_VE_ID,
		DOCTOS_VE.FOLIO,
		DOCTOS_VE.TIPO_DOCTO,
		DOCTOS_VE.DESCRIPCION,
		DOCTOS_VE.IMPORTE_NETO
		from DOCTOS_VE
		WHERE  
        DOCTOS_VE.TIPO_DOCTO IN ('R', 'F') AND DOCTOS_VE.ESTATUS!='C' and fecha> '2017-04-10'
        and DOCTO_VE_ID NOT IN (select ID_VENTA FROM MS_MOVIMIENTO WHERE EMPRESA=2)";
        
        $result = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());
        
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$indice = count($json);
			
			$json[$indice]['ID'] 				= $row->DOCTO_VE_ID;
			$json[$indice]['TITULO'] 			= utf8_encode($row->DESCRIPCION);
			$json[$indice]['IMPORTE'] 			= $row->IMPORTE_NETO;
			$json[$indice]['FOLIO'] 			= "NP".$row->TIPO_DOCTO."-".(int)$row->FOLIO;
			$json[$indice]['EMPRESA'] 			= 2	;
			
			$query2 = "select
			ARTICULOS.NOMBRE,
			DOCTOS_VE_DET.UNIDADES,
			DOCTOS_VE_DET.PRECIO_TOTAL_NETO,
			DOCTOS_VE_DET.DOCTO_VE_DET_ID,
			DOCTOS_VE_DET.DOCTO_VE_ID,
			MS_RELACION.ID,
			MS_RELACION.CANTIDAD, 
			MS_RELACION.MS_COMBO_ID,
			MS_RELACION.AUTOMATICO
			from DOCTOS_VE_DET
			LEFT JOIN MS_RELACION ON DOCTOS_VE_DET.ARTICULO_ID = MS_RELACION.ARTICULO_ID,
			ARTICULOS
	        WHERE  
	        DOCTOS_VE_DET.DOCTO_VE_ID = ".$json[$indice]['ID']."
	        AND DOCTOS_VE_DET.ARTICULO_ID = ARTICULOS.ARTICULO_ID";
	        
	        $result2 = ibase_query($conection1->getConexion(), $query2) or die(ibase_errmsg());
	        
			while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
				$indice2 = count($json[$indice]['INSUMOS']);
				$json[$indice]['INSUMOS'][$indice2]['ID'] 			= utf8_encode($row2->ID);
				$json[$indice]['INSUMOS'][$indice2]['ARTICULO'] 	= utf8_encode($row2->NOMBRE);
				$json[$indice]['INSUMOS'][$indice2]['UNIDADES'] 	= $row2->UNIDADES;
				$json[$indice]['INSUMOS'][$indice2]['PRECIO'] 		= $row2->PRECIO_TOTAL_NETO;
				$json[$indice]['INSUMOS'][$indice2]['CANTIDAD'] 	= $row2->CANTIDAD;
				$json[$indice]['INSUMOS'][$indice2]['AUTOMATICO'] 	= $row2->AUTOMATICO;
				$json[$indice]['INSUMOS'][$indice2]['ID_VENTA_DET'] = $row2->DOCTO_VE_DET_ID;
				$json[$indice]['INSUMOS'][$indice2]['ID_VENTA'] 	= $row2->DOCTO_VE_ID;

				if($row2->CANTIDAD)
				{
					$query3 = "select
					ID,
					DESCRIPCION,
					(select first 1 precio_compra from ms_inventario where ms_combo_id=ms_combos.id order by fecha_actualizacion desc) as PRECIO_COMPRA
					from MS_COMBOS

			        WHERE  
			        TIPO_COMBO =".$row2->MS_COMBO_ID;
			        
			        $result3 = ibase_query($conection1->getConexion(), $query3) or die(ibase_errmsg());
			        
					while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
						$indice3 = count($json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB']);
						$json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB'][$indice3]['NOMBRE'] = utf8_encode($row3->DESCRIPCION);
						$json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB'][$indice3]['ID'] = utf8_encode($row3->ID);
						$json[$indice]['INSUMOS'][$indice2]['ARTICULOS_WEB'][$indice3]['PRECIO'] = utf8_encode($row3->PRECIO_COMPRA );
					}
				}
				
			}
		}
		
		
		$conection1 = null;
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "recalcula_precio")
	{
		$conection1 = new conexion_nexos(2);
		$json = array();

		$query = "select first 1 precio_compra from ms_inventario where ms_combo_id=".$_POST['valor']." order by fecha_actualizacion desc";
        
        $result = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());
        
        $json['PRECIO'] = 0.00;
		while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
			$json['PRECIO'] = $row->PRECIO_COMPRA;
		}

		$json['ID'] = $_POST['id'];
		$json['ID_INSUMO'] = $_POST['id_insumo'];

		$obj = (object) $json;
		echo json_encode($obj);

		$conection1 = null;
	}

	if($_POST["accion"] == "validar")
		{
			$conection1 = new conexion_nexos(2);
			$json = array();

			$seleccionado = $_POST['venta'];
			foreach ($seleccionado as $key => $value) {
				$valores = explode("_", $value);
				$contador = 0;
				$pivote = $_POST['detalle'][$value][0];
				foreach ($_POST['detalle'][$value] as $key2 => $value2) {
					if($pivote != $value2)
					{
						$contador = 0;
						$pivote = $value2;
					}

					$articulo 	= $_POST['articulo_web'][$value."_".$value2][$contador];
					$precio 	= $_POST['precio_web'][$value."_".$value2][$contador];
					$unidades 	= $_POST['unidades_web'][$value."_".$value2][$contador];

					$nombre 	= $_POST['descripcion_articulo_v'][$value."_".$value2][$contador];
					$precio_v 	= $_POST['precio_v'][$value."_".$value2][$contador];
					$unidades_v = $_POST['unidad_v'][$value."_".$value2][$contador];


					$contador++;
					$campos = array("EMPRESA",
									"ID_VENTA",
									"ID_VENTA_DET",
									"ARTICULO_ID",
									"CANTIDAD_VENDIDO",
									"CANTIDAD_COMPROMETIDO",
									"CANTIDAD_INVENTARIO",
									"PRECIO_UNITARIO_COMPRA",
									"PRECIO_TOTAL",
									"MS_INVENTARIO_ID",
									"PRECIO_VENTA",
									"DESCRIPCION");

					$valores = array($valores[0],
									 $valores[1],
									 $value2,
									 $articulo,
									 $unidades_v,
									 $unidades,
									 0,
									 $precio,
									 ($precio * $unidades),
									 0,
									 $precio_v,
									 "'".$nombre."'");
					$json = $conection1->insert_table($campos, "MS_MOVIMIENTO", $valores);

					$campos2 		= array("COMPROMETIDO");
					$tabla2 		= "MS_COMBOS";
					$condicionales 	= " AND ID=".$articulo; 
					$json_articulo = $conection1->select_table($campos2, $tabla2, array(), $condicionales, array(), 0);

					
					$valores	= array($json_articulo[0]['COMPROMETIDO'] + $unidades);
					$conection1->update_table($campos2, $tabla2, $valores, " ID=".$articulo);

					
				}
			}

			

			$obj = (object) $_POST;
			echo json_encode($obj);

			$conection1 = null;
		}