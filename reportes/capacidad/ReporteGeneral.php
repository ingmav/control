<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=ReporteGeneral.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

include("../../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');
session_start();

$totalNumero = 0;
$totalUnidad = 0;
$totalPrecio = 0;
?>
<html lang="en">

<head>
<meta charset="UTF-8">
</head>

<BODY>
<?php
	
	$fecha_inicio = "";
		$fecha_finalizado = "";
		if($_POST['fechadesde']!="")
			$fecha_inicio  = $_POST['fechadesde'];
		else
			$fecha_inicio = date("Y-m-d");
		if($_POST['fechafin']!="")
			$fecha_finalizado = $_POST['fechafin'];
		else
			$fecha_finalizado = date("Y-m-d");

		echo "Reporte de Capacidad de Producción del periodo: $fecha_inicio a $fecha_finalizado, Generado: ".date("Y-m-d H:i:s")."";


		//echo $fecha_inicio." ".$fecha_finalizado;
		 $join = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "DOCTOS_VE_DET.DOCTO_VE_ID",
        "ARTICULOS","=", "DOCTOS_VE_DET.ARTICULO_ID", "ARTICULOS.ARTICULO_ID",
        "LINEAS_ARTICULOS","=", "LINEAS_ARTICULOS.LINEA_ARTICULO_ID", "ARTICULOS.LINEA_ARTICULO_ID");


		$fecha = " AND DOCTOS_VE.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";
        /*$restriccion1 = " AND DOCTOS_VE.TIPO_DOCTO in ('F','R')
        and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
         AND DOCTOS_VE.ESTATUS!='C' ";*/

        $join2 = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "DOCTOS_PV_DET.DOCTO_PV_ID",
            "ARTICULOS","=", "DOCTOS_PV_DET.ARTICULO_ID", "ARTICULOS.ARTICULO_ID",
            "LINEAS_ARTICULOS","=", "LINEAS_ARTICULOS.LINEA_ARTICULO_ID", "ARTICULOS.LINEA_ARTICULO_ID");


        $fecha2 = " AND DOCTOS_PV.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";
        //$restriccion2 = " AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N' ";

		$fecha3 = " AND IMPORTES_DOCTOS_CC.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";

		$TotalVenta = 0;

		$totalGeneral = totalVentasGeneral($fecha, $fecha2, $fecha3);
		
		
		$condicionales = array(0);
    	$arreglo2 = array(14660, 14588, 13983, 14644, 14874, 19395);
		$Diseno = capacidad_completa($join, $join2, $condicionales, $arreglo2, $fecha, $fecha2);

		$porcentaje_acumulado = 0;
		echo "<table><thead><th colspan='6' style='background:#CDCDCD'>Resumén</td></thead>";
		echo "<tbody><tr style='background:#CDCDCD'><td></td><td></td><td>LINEA</td><td>UNIDADES</td><td>MONTO</td><td>%</td></tr>";
		
		$porcentaje_acumulado += (($Diseno[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($Diseno[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>DISEÑO</td><td>".number_format($Diseno[1]['unidades'],2,".",",")."</td><td>".number_format($Diseno[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";
		
		$condicionales = array(0);
    	$arreglo4 = array(14243, 14332, 14010, 14336, 14223, 14340, 14782, 14376);
		
		
		$instalacion = capacidad_completa($join, $join2, $condicionales, $arreglo4, $fecha, $fecha2);
		
		
		$porcentaje_linea = (($instalacion[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = round($porcentaje_linea,2, PHP_ROUND_HALF_DOWN);
		$porcentaje_acumulado += $porcentaje_linea;
		echo "<tr><td></td><td></td><td>INSTALACIÓN</td><td>".number_format($instalacion[1]['unidades'],2,".",",")."</td><td>".number_format($instalacion[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";
		
		$condicionales = array(0);
    	$arreglo6 = array(14384, 14461, 17364, 20441, 15095, 15099, 15111, 15115, 15119, 15107, 15135, 15139, 15143, 15103, 15123, 15127, 15131, 20491, 20494, 20497,20500);
		
		$lonas = capacidad_completa($join, $join2, $condicionales, $arreglo6, $fecha, $fecha2);
		$porcentaje_acumulado += (($lonas[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($lonas[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>GRAN FORMATO (LONAS)</td><td>".number_format($lonas[1]['unidades'],2,".",",")."</td><td>".number_format($lonas[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		$condicionales = array(0);
    	$arreglo8 = array(17368, 15147, 15151, 15155, 15159, 15163, 15167, 15171, 15175, 15191, 15195, 15199, 15203, 15207, 15211, 15215, 15219, 15223,15323, 15327, 15359,  16552, 15311, 15315, 15319, 21744, 21748, 21752, 21756, 21760,21764, 14620,14615,14600,16540,14605,21054,14629,14486,14689,16544,16548, 19464, 22856);

		$vinil = capacidad_completa($join, $join2, $condicionales, $arreglo8, $fecha, $fecha2);
		$porcentaje_acumulado += (($vinil[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($vinil[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>GRAN FORMATO (VINIL)</td><td>".number_format($vinil[1]['unidades'],2,".",",")."</td><td>".number_format($vinil[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";


		$condicionales = array(0);
    	$arreglo10 = array(14205,14231,14235,14239,13973,14400,13968,21654,21660,21664,15079,15227,15075,15231,15235,15239,15243,15247,15251,15083,15255,15087,15259,15263,15267,15271,15275,15279,15283,15287,15331,15347,15351,15355,19401,19405,19409,19413,19419,19423,19428,19432,19436,19440,19444,19448,19452,19456);

		$lamina = capacidad_completa($join, $join2, $condicionales, $arreglo10, $fecha, $fecha2);
		$porcentaje_acumulado += (($lamina[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($lamina[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>GRAN FORMATO (RIGIDOS)</td><td>".number_format($lamina[1]['unidades'],2,".",",")."</td><td>".number_format($lamina[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";


		$condicionales = array(0);
    	$arreglo12 = array(15291,15295,15299,15303);

		
		$sol= capacidad_completa($join, $join2, $condicionales, $arreglo12, $fecha, $fecha2);
		$porcentaje_acumulado += (($sol[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($sol[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>GRAN FORMATO (TRI SOLVENTE)</td><td>".number_format($sol[1]['unidades'],2,".",",")."</td><td>".number_format($sol[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";


		$condicionales = array(0);
		
    	$arreglo14 = array(15059,15335,15063,14752,14494,14441,17776,17384,17388,20445,20449,14629,20409,14648);
		$corte = capacidad_completa($join, $join2, $condicionales, $arreglo14, $fecha, $fecha2);
		$porcentaje_acumulado += (($corte[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($corte[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>CORTE DE VINIL</td><td>".number_format($corte[1]['unidades'],2,".",",")."</td><td>".number_format($corte[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";
		$condicionales = array(0);
		
    	$arreglo30 = array(21532, 21536, 14320);
		$router = capacidad_completa($join, $join2, $condicionales, $arreglo30, $fecha, $fecha2);
		$porcentaje_acumulado += (($router[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($router[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>CORTE EN ROUTER</td><td>".number_format($router[1]['unidades'],2,".",",")."</td><td>".number_format($router[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		$condicionales = array(0);
    	$arreglo16 = array(15387,15397,15405,15414,15050,15423,15432,15441,15450,15459,15468,14944,15477,15486,15495,15504,15513,15522,14952,15531,14969,14960,14978,15540,14987,14993,14999,14247,14014,14018,14022,14026,14030,14172,14139,14144,14149,14154,15005,14522,14181,17327,17398,17359,17372,17376,17392,21802,21993,21997,15019,15023,15027,15031,15371,15375, 14185, 14251, 14255);

		
		$digitales = capacidad_completa($join, $join2, $condicionales, $arreglo16, $fecha, $fecha2);
		$porcentaje_acumulado += (($digitales[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($digitales[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>IMPRESIÓN DIGITAL</td><td>".number_format($digitales[1]['unidades'],2,".",",")."</td><td>".number_format($digitales[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		$condicionales = array(0);
    	$arreglo18 = array(15067,14924,14637,15700,15714,19858,15728,15721,15707,22228,20391,21516,21520,21524,19823,19827,19831,19835,14465,15745,15735,15740,15750,15754,16200,15758,15769,16187,20457,20463,20467,22307,23141,23232, 16616, 19808); 
		
		$banner = capacidad_completa($join, $join2, $condicionales, $arreglo18, $fecha, $fecha2);
		$porcentaje_acumulado += (($banner[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($banner[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>BANNER, PV, MURALES</td><td>".number_format($banner[1]['unidades'],2,".",",")."</td><td>".number_format($banner[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";
		
		$condicionales = array(0);
    	$arreglo20 = array(20413,20417,20421,20425,20428,20432,20435,20438);

		$maquilas = capacidad_completa($join, $join2, $condicionales, $arreglo20, $fecha, $fecha2);
		$porcentaje_acumulado += (($maquilas[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($maquilas[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>MAQUILAS</td><td>".number_format($maquilas[1]['unidades'],2,".",",")."</td><td>".number_format($maquilas[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		$condicionales = array(0);
    	$arreglo22 = array(14668,14863,17285,14901,17600);

		$Web = capacidad_completa($join, $join2, $condicionales, $arreglo22, $fecha, $fecha2);
		$porcentaje_acumulado += (($Web[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($Web[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>MARKETING</td><td>".number_format($Web[1]['unidades'],2,".",",")."</td><td>".number_format($Web[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";


		$condicionales = array(0);
    	$arreglo24 = array(14584);

		$gestion = capacidad_completa($join, $join2, $condicionales, $arreglo24, $fecha, $fecha2);
		$porcentaje_acumulado += (($gestion[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($gestion[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>GESTIÓN DE NEGOCIOS</td><td>".number_format($gestion[1]['unidades'],2,".",",")."</td><td>".number_format($gestion[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		$condicionales = array(0);
    	$arreglo26 = array(22335,21309,21316,21323,21330,21486,21337,21344,21351,21451,21244,21254,21275,21261,21268,21282,21289,21302,21398,21616,21789,22007,22032,14928,22446);

		$sublimacion = capacidad_completa($join, $join2, $condicionales, $arreglo26, $fecha, $fecha2);
		$porcentaje_acumulado += (($sublimacion[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($sublimacion[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>SUBLIMACIÓN</td><td>".number_format($sublimacion[1]['unidades'],2,".",",")."</td><td>".number_format($sublimacion[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		
		$condicionales = array(0);
    	$arreglo28 = array(14197,14702,14312,14193,14490,14641,14568,19760,14478,14697,16307,14457,19756,14560,13978,20453,14528,13903,14300,14633);

		$insumos = capacidad_completa($join, $join2, $condicionales, $arreglo28, $fecha, $fecha2);
		$porcentaje_acumulado += (($insumos[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($insumos[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>INSUMOS Y COMPLEMENTOS</td><td>".number_format($insumos[1]['unidades'],2,".",",")."</td><td>".number_format($insumos[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		$condicionales = array(0);
    	$condicionales2 = array_merge($arreglo2,$arreglo4,$arreglo6,$arreglo8,$arreglo10,$arreglo12,$arreglo14,$arreglo16,$arreglo18,$arreglo20,$arreglo22,$arreglo24,$arreglo26,$arreglo28,$arreglo30); 


		$otros = otros($join, $join2, $condicionales, $condicionales2, $fecha, $fecha2);
		$porcentaje_acumulado += (($otros[1]['precio']/$totalGeneral[0])*100);
		$porcentaje_linea = (($otros[1]['precio']/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>OTROS</td><td>".number_format($otros[1]['unidades'],2,".",",")."</td><td>".number_format($otros[1]['precio'],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";
		
		$porcentaje_acumulado += (($totalGeneral[1]/$totalGeneral[0])*100);
		$porcentaje_linea = (($totalGeneral[1]/$totalGeneral[0])*100);
		echo "<tr><td></td><td></td><td>NOTAS DE CARGO</td><td>1</td><td>".number_format($totalGeneral[1],2,".",",")."</td><td>".number_format($porcentaje_linea,2,".",",")."</td></tr>";

		echo "<tr><td></td><td></td><td></td><td>TOTAL</td><td>".number_format($totalGeneral[1],2,".",",")."</td><td>".number_format($porcentaje_acumulado,2,".",",")."</td></tr></table>";
		
		$arreglo_nota = array(0=>array("ARTICULO"=>"NTC", "NUMERO"=>"1", "NOMBREARTICULO"=>"NOTA DE CARGO", "UNIDADES"=>"1", "PRECIO"=>$totalGeneral[1]));
		echo "<br>";
		imprimirResultado("DISEÑO", $Diseno[0]);
		imprimirResultado("INSTALACIÓN", $instalacion[0]);
		imprimirResultado("IMPRESION GRAN FORMATO (LONAS)", $lonas[0]);
		imprimirResultado("IMPRESION GRAN FORMATO (VINIL)", $vinil[0]);
		imprimirResultado("IMPRESION GRAN FORMATO (RIGIDOS)", $lamina[0]);
		imprimirResultado("IMPRESION GRAN FORMATO (TRI SOL)", $sol[0]);
		imprimirResultado("CORTE DE VINIL", $corte[0]);
		imprimirResultado("CORTE EN ROUTER", $router[0]);
		imprimirResultado("IMPRESIÓN DIGITAL", $digitales[0]);
		imprimirResultado("BANNER, PV, MURALES", $banner[0]);
		imprimirResultado("MAQUILAS", $maquilas[0]);
		imprimirResultado("WEB MARKETING", $Web[0]);
		imprimirResultado("GESTIÓN DE NEGOCIOS", $gestion[0]);
		imprimirResultado("SUBLIMACIÓN", $sublimacion[0]);
		imprimirResultado("INSUMOS Y COMPLEMENTOS", $insumos[0]);
		imprimirResultado("OTROS", $otros[0]);
		imprimirResultado("NOTA DE CARGO", $arreglo_nota);
		
		echo "<br><tr><td>TOTAL</td><td>".$totalNumero."</td><td>--</td><td>".number_format($totalUnidad,2,".",",")."</td><td>".number_format($totalPrecio,2,".",",")."</td></tr></table>";
	?>
	</BODY>
	<?php
	function imprimirResultado($titulo, $arreglo)
	{
		GLOBAL $totalGeneral;
		if(count($arreglo)>0)
		{
			echo "<table border='0' style='width:1400px;'>";
			echo "<thead style='background:#CDCDCD'>";

			echo "<th >CLAVE ARTÍCULO</th>";
			echo "<th >CANTIDAD</th>";
			echo "<th>NOMBRE ARTICULO</th>";
			echo "<th >UNIDADES</th>";
			echo "<th >INGRESO</th>";
			echo "<th >%</th>";
			echo "</thead>";
			echo "<tr style='background:#DEDEDE'><td colspan='6' align='center'>$titulo</td></tr>";
			Global $totalNumero, $totalUnidad, $totalPrecio;

			$odd = 0;
			$arrayAuxiliar = array();
			foreach ($arreglo as $key => $value) {
				for($i=($key+1); $i<count($arreglo); $i++)
				if($arreglo[$i])
				{
					if($arreglo[$key]['ARTICULO']>$arreglo[($i)]['ARTICULO'])
					{
						$arrayAuxiliar = $arreglo[$key]['ARTICULO'];
						$arreglo[$key]['ARTICULO'] = $arreglo[($i)]['ARTICULO'];
						$arreglo[($i)]['ARTICULO'] = $arrayAuxiliar;
					}

				}
			}
			foreach ($arreglo as $key => $value) {
				if(($odd%2)==0)
					echo "<tr style='background:#EFEFEF'>";
				else
					echo "<tr>";
				echo "<td>".$value['ARTICULO']."</td>";
				echo "<td>".$value['NUMERO']."</td>";
				echo "<td>".utf8_encode($value['NOMBREARTICULO'])."</td>";
				echo "<td>".number_format($value['UNIDADES'],2,".",",")."</td>";
				echo "<td>".number_format($value['PRECIO'],2,".",",")."</td>";
				echo "<td></td>";
				echo "</tr>";

				$subtotalNumero += $value['NUMERO'];
				$subtotalUnidad += $value['UNIDADES'];
				$subtotalPrecio += $value['PRECIO'];
				$odd++;
				$percent = (($subtotalPrecio/$totalGeneral[0])*100);
			}
			echo "<tr><td>SUBTOTAL</td><td>$subtotalNumero</td><td>--</td><td>".number_format($subtotalUnidad,2,".",",")."</td><td>".number_format($subtotalPrecio,2,".",",")."</td><td>".number_format($percent,2,".",",")."</td></tr>";
			echo "<tr><td style='height:10px;'></td></tr>";

			$totalNumero += $subtotalNumero;
			$totalUnidad += $subtotalUnidad;
			$totalPrecio += $subtotalPrecio;
		}
	}

	function capacidad_completa($join, $join2, $condicionales1, $condicionales2, $fecha1, $fecha2)
	{
		try
		{
			$ciclos = count($join) / 4;
	          $whereUnions = "";
	          $joins = "";
	          for($i = 0; $i < $ciclos; $i++)
	          {
	              $joins .= ", ".$join[($i * 4)]." ";
	              $joins2 .= ", ".$join2[($i * 4)]." ";
	              $whereUnions .= " AND ".$join[(($i * 4) + 2)]." ".$join[(($i * 4) + 1)]." ".$join[(($i * 4) + 3)];
	              $whereUnions2 .= " AND ".$join2[(($i * 4) + 2)]." ".$join2[(($i * 4) + 1)]." ".$join2[(($i * 4) + 3)];
	          }

			

		$query = "select sum(numero) as numero, sum(unidades) as unidades, sum(precio) as precio, NOMBREARTICULO, CLAVE from 
          (select 
          COUNT(DOCTOS_VE_DET.CLAVE_ARTICULO) AS NUMERO,
			SUM(DOCTOS_VE_DET.unidades) AS UNIDADES,
			SUM(DOCTOS_VE_DET.precio_total_neto) AS PRECIO,
			ARTICULOS.NOMBRE AS NOMBREARTICULO,
			DOCTOS_VE_DET.clave_articulo AS CLAVE

           from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
         DOCTOS_VE_DET.ARTICULO_ID in (".implode(",", $condicionales1).")  AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
          GROUP BY DOCTOS_VE_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
          union all 
          select 
          	COUNT(DOCTOS_PV_DET.CLAVE_ARTICULO) AS NUMERO,
			SUM(DOCTOS_PV_DET.unidades) AS UNIDADES,
			SUM(DOCTOS_PV_DET.precio_total_neto) AS PRECIO,
			ARTICULOS.NOMBRE AS NOMBREARTICULO,
			DOCTOS_PV_DET.clave_articulo AS CLAVE

           from DOCTOS_PV_DET $joins2 WHERE  
          DOCTOS_PV_DET.ARTICULO_ID in (".implode(",", $condicionales1).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
          $whereUnions2
          $fecha2
          GROUP BY DOCTOS_PV_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
          ) GROUP BY NOMBREARTICULO, CLAVE";

          $query2 = "select sum(numero) as numero, sum(unidades) as unidades, sum(precio) as precio, NOMBREARTICULO, CLAVE from 
          (select 
          COUNT(DOCTOS_VE_DET.CLAVE_ARTICULO) AS NUMERO,
			SUM(DOCTOS_VE_DET.unidades) AS UNIDADES,
			SUM(DOCTOS_VE_DET.precio_total_neto) AS PRECIO,
			ARTICULOS.NOMBRE AS NOMBREARTICULO,
			DOCTOS_VE_DET.clave_articulo AS CLAVE

           from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
         DOCTOS_VE_DET.ARTICULO_ID in (".implode(",", $condicionales2).")  AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
          GROUP BY DOCTOS_VE_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
          union all 
          select 
          	COUNT(DOCTOS_PV_DET.CLAVE_ARTICULO) AS NUMERO,
			SUM(DOCTOS_PV_DET.unidades) AS UNIDADES,
			SUM(DOCTOS_PV_DET.precio_total_neto) AS PRECIO,
			ARTICULOS.NOMBRE AS NOMBREARTICULO,
			DOCTOS_PV_DET.clave_articulo AS CLAVE

           from DOCTOS_PV_DET $joins2 WHERE  
          DOCTOS_PV_DET.ARTICULO_ID in (".implode(",", $condicionales2).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
          $whereUnions2
          $fecha2
          GROUP BY DOCTOS_PV_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
          ) GROUP BY NOMBREARTICULO, CLAVE";
			//DOCTOS_VE_DET.precio_total_neto>0
            $conection1 = new conexion_nexos(1);
			$result1 = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());

			$data = array();
			$data_resumen = array();
			$total_unidades = 0;
			$total_precio = 0;
			while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT)){
				$index = count($data);
				$data[$index]['NUMERO'] = $row1->NUMERO;
				$data[$index]['ARTICULO'] = $row1->CLAVE;
				$data[$index]['NOMBREARTICULO'] = $row1->NOMBREARTICULO;
				$data[$index]['UNIDADES'] = $row1->UNIDADES;
				$data[$index]['PRECIO'] = $row1->PRECIO;

				$total_unidades += $row1->UNIDADES;
				$total_precio 	+= $row1->PRECIO;
			}

			$conection2 = new conexion_nexos($_SESSION['empresa']);
			$result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());

			//$data2 = array();

			while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
				$index = count($data);
				$data[$index]['NUMERO'] = $row2->NUMERO;
				$data[$index]['ARTICULO'] = $row2->CLAVE;
				$data[$index]['NOMBREARTICULO'] = $row2->NOMBREARTICULO;
				$data[$index]['UNIDADES'] = $row2->UNIDADES;
				$data[$index]['PRECIO'] = $row2->PRECIO;

				$total_unidades += $row2->UNIDADES;
				$total_precio 	+= $row2->PRECIO;
			}

			$data_resumen['unidades'] 	= $total_unidades;
			$data_resumen['precio'] 	= $total_precio;
			/*$data_final = array();
			foreach ($data as $key => $value) {
				foreach ($data2 as $key2 => $value2) {
					if($data[$key]['ARTICULO'] == $data2[$key2]['ARTICULO'])
					{
						$data[$key]['UNIDADES'] += $data2[$key2]['UNIDADES'];
						$data[$key]['PRECIO'] += $data2[$key2]['PRECIO'];
					}
				}	
			}*/

			return array($data, $data_resumen);
		}catch(Exception $e) {
			echo 'Excepción capturada: ',  $e, "\n";
		}
	}

function otros($join, $join2, $condicionales1, $condicionales2, $fecha1, $fecha2)
{

	try
	{
		$ciclos = count($join) / 4;
          $whereUnions = "";
          $joins = "";
          for($i = 0; $i < $ciclos; $i++)
          {
              $joins .= ", ".$join[($i * 4)]." ";
              $joins2 .= ", ".$join2[($i * 4)]." ";
              $whereUnions .= " AND ".$join[(($i * 4) + 2)]." ".$join[(($i * 4) + 1)]." ".$join[(($i * 4) + 3)];
              $whereUnions2 .= " AND ".$join2[(($i * 4) + 2)]." ".$join2[(($i * 4) + 1)]." ".$join2[(($i * 4) + 3)];
          }

		

	$query = "select sum(numero) as numero, sum(unidades) as unidades, sum(precio) as precio, NOMBREARTICULO, CLAVE from 
      (select 
      COUNT(DOCTOS_VE_DET.CLAVE_ARTICULO) AS NUMERO,
		SUM(DOCTOS_VE_DET.unidades) AS UNIDADES,
		SUM(DOCTOS_VE_DET.precio_total_neto) AS PRECIO,
		ARTICULOS.NOMBRE AS NOMBREARTICULO,
		DOCTOS_VE_DET.clave_articulo AS CLAVE

       from DOCTOS_VE_DET
      $joins
      WHERE 
      DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
      and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
      and 
     DOCTOS_VE_DET.ARTICULO_ID not in (".implode(",", $condicionales1).")  AND DOCTOS_VE.ESTATUS!='C' 
      $whereUnions
      $fecha1
      GROUP BY DOCTOS_VE_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
      union all 
      select 
      	COUNT(DOCTOS_PV_DET.CLAVE_ARTICULO) AS NUMERO,
		SUM(DOCTOS_PV_DET.unidades) AS UNIDADES,
		SUM(DOCTOS_PV_DET.precio_total_neto) AS PRECIO,
		ARTICULOS.NOMBRE AS NOMBREARTICULO,
		DOCTOS_PV_DET.clave_articulo AS CLAVE

       from DOCTOS_PV_DET $joins2 WHERE  
      DOCTOS_PV_DET.ARTICULO_ID not in (".implode(",", $condicionales1).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
      $whereUnions2
      $fecha2
      GROUP BY DOCTOS_PV_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
      ) GROUP BY NOMBREARTICULO, CLAVE";

      $query2 = "select sum(numero) as numero, sum(unidades) as unidades, sum(precio) as precio, NOMBREARTICULO, CLAVE from 
      (select 
      COUNT(DOCTOS_VE_DET.CLAVE_ARTICULO) AS NUMERO,
		SUM(DOCTOS_VE_DET.unidades) AS UNIDADES,
		SUM(DOCTOS_VE_DET.precio_total_neto) AS PRECIO,
		ARTICULOS.NOMBRE AS NOMBREARTICULO,
		DOCTOS_VE_DET.clave_articulo AS CLAVE

       from DOCTOS_VE_DET
      $joins
      WHERE 
      DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
      and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
      and 
     DOCTOS_VE_DET.ARTICULO_ID  not in (".implode(",", $condicionales2).")  AND DOCTOS_VE.ESTATUS!='C' 
      $whereUnions
      $fecha1
      GROUP BY DOCTOS_VE_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
      union all 
      select 
      	COUNT(DOCTOS_PV_DET.CLAVE_ARTICULO) AS NUMERO,
		SUM(DOCTOS_PV_DET.unidades) AS UNIDADES,
		SUM(DOCTOS_PV_DET.precio_total_neto) AS PRECIO,
		ARTICULOS.NOMBRE AS NOMBREARTICULO,
		DOCTOS_PV_DET.clave_articulo AS CLAVE

       from DOCTOS_PV_DET $joins2 WHERE  
      DOCTOS_PV_DET.ARTICULO_ID not in (".implode(",", $condicionales2).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
      $whereUnions2
      $fecha2
      GROUP BY DOCTOS_PV_DET.CLAVE_ARTICULO, ARTICULOS.NOMBRE
      ) GROUP BY NOMBREARTICULO, CLAVE";
		//DOCTOS_VE_DET.precio_total_neto>0
        $conection1 = new conexion_nexos(1);
		$result1 = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());

		$data = array();
		$data_resumen = array();
		$total_unidades = 0;
		$total_precio = 0;
		
		while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT)){
			$index = count($data);
			$data[$index]['NUMERO'] = $row1->NUMERO;
			$data[$index]['ARTICULO'] = $row1->CLAVE;
			$data[$index]['NOMBREARTICULO'] = $row1->NOMBREARTICULO;
			$data[$index]['UNIDADES'] = $row1->UNIDADES;
			$data[$index]['PRECIO'] = $row1->PRECIO;

			$total_unidades += $row1->UNIDADES;
			$total_precio 	+= $row1->PRECIO;
		}

		$conection2 = new conexion_nexos($_SESSION['empresa']);
		$result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());

		//$data2 = array();

		while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
			$index = count($data);
			$data[$index]['NUMERO'] = $row2->NUMERO;
			$data[$index]['ARTICULO'] = $row2->CLAVE;
			$data[$index]['NOMBREARTICULO'] = $row2->NOMBREARTICULO;
			$data[$index]['UNIDADES'] = $row2->UNIDADES;
			$data[$index]['PRECIO'] = $row2->PRECIO;

			$total_unidades += $row2->UNIDADES;
			$total_precio 	+= $row2->PRECIO;
		}

	
		$data_resumen['unidades'] 	= $total_unidades;
		$data_resumen['precio'] 	= $total_precio;
		return array($data, $data_resumen);
		//return $data;
	}catch(Exception $e) {
		echo 'Excepción capturada: ',  $e, "\n";
	}
}
    
/* Fin punto de venta*/
function totalVentasGeneral($condicionales, $condicionales2, $condicionales3)
{

    try
    {

			$total_notas = 0;
        $query = "select
			SUM(DOCTOS_VE.importe_neto) AS PRECIO
			from DOCTOS_VE WHERE 1=1 $condicionales AND DOCTOS_VE.importe_neto>0 AND DOCTOS_VE.TIPO_DOCTO in ('F', 'R')
			and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
			 AND DOCTOS_VE.ESTATUS!='C'";

        $query2 = "select
			SUM(DOCTOS_PV.importe_neto) AS PRECIO
			from DOCTOS_PV WHERE 1=1 $condicionales2 AND DOCTOS_PV.importe_neto>0 AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'";

			$query3 = "select
			sum(IMPORTES_DOCTOS_CC.IMPORTE) as PRECIO
			from DOCTOS_CC LEFT JOIN IMPORTES_DOCTOS_CC ON DOCTOS_CC.DOCTO_CC_ID = IMPORTES_DOCTOS_CC.DOCTO_CC_ID
			WHERE  1=1     and DOCTOS_CC.CONCEPTO_CC_ID='8' and DOCTOS_CC.CANCELADO='N'".$condicionales3;

        $conection1 = new conexion_nexos(1);
        $result1 = ibase_query($conection1->getConexion(), $query) or die(ibase_errmsg());
        $result2 = ibase_query($conection1->getConexion(), $query2) or die(ibase_errmsg());
				$result3 = ibase_query($conection1->getConexion(), $query3) or die(ibase_errmsg());


        $data = array();

        $total = 0;

        while ($row1 = ibase_fetch_object ($result1, IBASE_TEXT)){
            $total+=$row1->PRECIO;
        }

        while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
            $total+=$row2->PRECIO;
        }

				while ($row3 = ibase_fetch_object ($result3, IBASE_TEXT)){
            $total+=$row3->PRECIO;
						$total_notas += $row3->PRECIO;
        }

        $conection2 = new conexion_nexos($_SESSION['empresa']);
        $result4 = ibase_query($conection2->getConexion(), $query) or die(ibase_errmsg());
        $result5 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());
        $result6 = ibase_query($conection2->getConexion(), $query3) or die(ibase_errmsg());

        $data = array();

        while ($row4 = ibase_fetch_object ($result4, IBASE_TEXT)){
            $total+= $row4->PRECIO;
        }

        while ($row5 = ibase_fetch_object ($result5, IBASE_TEXT)){
            $total+= $row5->PRECIO;
        }

				while ($row6 = ibase_fetch_object ($result6, IBASE_TEXT)){
            $total+= $row6->PRECIO;
						$total_notas += $row6->PRECIO;
        }

        return array($total, $total_notas);
    }catch(Exception $e) {
        echo 'Excepción capturada: ',  $e, "\n";
    }

}
?>
