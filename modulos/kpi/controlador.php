<?php
session_start();
//$mysqli = new mysqli("localhost", "root", "", "mi_bd");
date_default_timezone_set('America/Mexico_City');
header('Content-type: application/json; charset=utf-8');
    

$action = $_GET['accion'];
$usuario = "root";
$contraseña = "";
try {
	$conexion = new PDO('mysql:host=localhost;dbname=produccion', $usuario, $contraseña);
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
if($action == "get_catalogos")
{
	$arreglo = array();
	$arreglo_final = array();

	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare('SELECT * FROM tipo_trabajador');
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    foreach ($resultado as $row) {
	    	$count = count($arreglo);
			$arreglo[$count]['INDEX'] = $row['id'];
			$arreglo[$count]['VALUE'] = utf8_encode($row['tipo_trabajador']);
	        
	    }

	    $arreglo_final['TIPO_TRABAJADOR'] = $arreglo;

	    $arreglo = array();
	
	    $sql = $conexion->prepare('SELECT * FROM quincenas');
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    foreach ($resultado as $row) {
	    	$count = count($arreglo);
			$arreglo[$count]['INDEX'] = $row['id'];
			$arreglo[$count]['VALUE'] = utf8_encode($row['quincena']);
	        
	    }

	    $arreglo_final['QUINCENA'] = $arreglo;
	    echo json_encode($arreglo_final);

    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($action == "get_trabajadores")
{
	$arreglo = array();
	$arreglo_final = array();

	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare('SELECT * FROM trabajador, tipo_trabajador where trabajador.id_tipo_trabajador=tipo_trabajador.id order by trabajador.nombre');
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    foreach ($resultado as $row) {
	    	$count = count($arreglo);
			$arreglo[$count]['INDEX'] = $row['id'];
			$arreglo[$count]['NOMBRE'] = utf8_encode($row['nombre']);
			$arreglo[$count]['TIPO_TRABAJADOR'] = utf8_encode($row['tipo_trabajador']);
			$arreglo[$count]['SALARIO'] = utf8_encode($row['salario_base']);
			$arreglo[$count]['HORARIO'] = substr($row['hora_inicio'], 0,5)." - ".substr($row['hora_fin'], 0,5);
	    }


	    echo json_encode($arreglo);

    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($action == "get_trabajador")
{
	$arreglo = array();
	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare('SELECT * FROM trabajador where id='.$_GET['id']);

	    
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    foreach ($resultado as $row) {
	    	$arreglo['INDEX'] = $row['id'];
			$arreglo['NOMBRE'] = utf8_encode($row['nombre']);
			$arreglo['TIPO_TRABAJADOR'] = utf8_encode($row['id_tipo_trabajador']);
			$arreglo['SALARIO'] = utf8_encode($row['salario_base']);
			$arreglo['HORARIO'] = substr($row['hora_inicio'], 0,5)." - ".substr($row['hora_fin'], 0,5);
	    }

	    echo json_encode($arreglo);
    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($action == "guardar_trabajador")
{
	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare("INSERT INTO TRABAJADOR(nombre, id_tipo_trabajador, salario_base, hora_inicio, hora_fin) VALUES(:nombre, :tipo, :salario, :hora_inicio, :hora_fin)");
	    //$datos

	    $sql->bindParam(':nombre', 		strtoupper($_GET['nombre_trabajador']));
    	$sql->bindParam(':tipo', 		$_GET['tipo_trabajador']);
    	$sql->bindParam(':salario', 	$_GET['salario_trabajador']);	
    	$sql->bindParam(':hora_inicio', $_GET['horario_inicio']);	
    	$sql->bindParam(':hora_fin', 	$_GET['horario_fin']);	

	    $sql->execute();
	    
	    echo json_encode(array("true"));

    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

$consulta = null;
$conexion = null;
?>