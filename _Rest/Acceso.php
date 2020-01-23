<?php
	session_start();
	include("../clases/conexion.php");

	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos($_POST['empresa']);

	
	if($_POST["accion"] == "index")
	{

		$campos = array("OPERADOR.LEVANTAMIENTOS", "OPERADOR.DOCUMENTOS" ,"OPERADOR.DISENO","OPERADOR.PROGRAMACION", "OPERADOR.IMPRESION", "OPERADOR.ENTREGA", "OPERADOR.MAQUILAS", "OPERADOR.INSTALACION", "OPERADOR.FINALIZADOS", "OPERADOR.COBRO", "OPERADOR.CAPACIDAD", "OPERADOR.ID", "OPERADOR.COTIZACION", "OPERADOR.INVENTARIO", "OPERADOR.EXTRA", "OPERADOR.SHOPPING", "OPERADOR.ADMINISTRADOR", "OPERADOR.VENTAS", "OPERADOR.DASHBOARD", "OPERADOR.INVENTARIO_ADMIN");
		
		$join = array("OPERADORDEPARTAMENTO", "=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");
		
		$condicionales = " AND OPERADOR.ALIAS='".$_POST['usernex']."' AND OPERADOR.PASSNEX='".$_POST['contrasenianex']."'";
		
		$order = array();

		$conection = new conexion_nexos(1);
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 0);
		
		$count = 0;
		$contador = count($json);
			
		
		for($i = 0; $i < $contador; $i++){
			$_SESSION['IDUSUARIO'] = $json[$i]['OPERADOR.ID'];
			$_SESSION['LEVANTAMIENTOS'] = $json[$i]['OPERADOR.LEVANTAMIENTOS'];
			$_SESSION['DOCUMENTOS'] = $json[$i]['OPERADOR.DOCUMENTOS'];
			$_SESSION['DISENO'] = $json[$i]['OPERADOR.DISENO'];
			$_SESSION['PROGRAMACION'] = $json[$i]['OPERADOR.PROGRAMACION'];
			$_SESSION['IMPRESION'] = $json[$i]['OPERADOR.IMPRESION'];
			$_SESSION['ENTREGA'] = $json[$i]['OPERADOR.ENTREGA'];
			$_SESSION['INSTALACION'] = $json[$i]['OPERADOR.INSTALACION'];
			$_SESSION['FINALIZADOS'] = $json[$i]['OPERADOR.FINALIZADOS'];
			$_SESSION['MAQUILAS'] = $json[$i]['OPERADOR.MAQUILAS'];
			$_SESSION['COBRO'] = $json[$i]['OPERADOR.COBRO'];
			$_SESSION['CAPACIDAD'] = $json[$i]['OPERADOR.CAPACIDAD'];
			$_SESSION['COTIZACION'] = $json[$i]['OPERADOR.COTIZACION'];
			$_SESSION['INVENTARIOACCESO'] = $json[$i]['OPERADOR.INVENTARIO'];
			$_SESSION['EXTRA'] = $json[$i]['OPERADOR.EXTRA'];
            $_SESSION['SHOPPING'] = $json[$i]['OPERADOR.SHOPPING'];
            $_SESSION['ADMIN'] = $json[$i]['OPERADOR.ADMINISTRADOR'];
            $_SESSION['VENTAS'] = $json[$i]['OPERADOR.VENTAS'];
            $_SESSION['DASHBOARD'] = $json[$i]['OPERADOR.DASHBOARD'];
            $_SESSION['INVENTARIO_ADMIN'] = $json[$i]['OPERADOR.INVENTARIO_ADMIN'];
			$_SESSION['ACTIVAMODULOS'] = 1;

		}
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}

	if($_POST["accion"] == "cerrar")
	{	
		UNSET($_SESSION['IDUSUARIO']);
		UNSET($_SESSION['DOCUMENTOS']);
		unset($_SESSION['ACTIVAMODULOS']);
		unset($_SESSION['DISENO']);
		unset($_SESSION['PROGRAMACION']);
		unset($_SESSION['IMPRESION']);
		unset($_SESSION['ENTREGA']);
		unset($_SESSION['INSTALACION']);
		unset($_SESSION['LEVANTAMIENTOS']);
		unset($_SESSION['FINALIZADOS']);
		unset($_SESSION['MAQUILAS']);
		unset($_SESSION['COBRO']);
		unset($_SESSION['CAPACIDAD']);
		unset($_SESSION['COTIZACION']);
		unset($_SESSION['INVENTARIO']);
		unset($_SESSION['EXTRA']);
        unset($_SESSION['SHOPPING']);
        unset($_SESSION['VENTAS']);
        unset($_SESSION['DASHBOARD']);
		
		$respuesta = Array("respuesta"=>"Cerrar  Sesion");
		$obj = (object) $respuesta;
		echo json_encode($obj);
	}

?>