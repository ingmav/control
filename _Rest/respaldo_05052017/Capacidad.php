<?php
include("../clases/conexion.php");

date_default_timezone_set('America/Mexico_City');
session_start();

if($_POST["accion"] == "index")
{
    $fecha_inicio = "";
    $fecha_finalizado = "";
    if($_POST['fechaInicio']!="")
        $fecha_inicio  = $_POST['fechaInicio'];
    else
        $fecha_inicio = date("Y-m-d");
    if($_POST['fechaHasta']!="")
        $fecha_finalizado = $_POST['fechaHasta'];
    else
        $fecha_finalizado = date("Y-m-d");

    $join = array("DOCTOS_VE","=", "DOCTOS_VE.DOCTO_VE_ID", "DOCTOS_VE_DET.DOCTO_VE_ID",
        "ARTICULOS","=", "DOCTOS_VE_DET.ARTICULO_ID", "ARTICULOS.ARTICULO_ID",
        "LINEAS_ARTICULOS","=", "LINEAS_ARTICULOS.LINEA_ARTICULO_ID", "ARTICULOS.LINEA_ARTICULO_ID");

    $join2 = array("DOCTOS_PV","=", "DOCTOS_PV.DOCTO_PV_ID", "DOCTOS_PV_DET.DOCTO_PV_ID",
        "ARTICULOS","=", "DOCTOS_PV_DET.ARTICULO_ID", "ARTICULOS.ARTICULO_ID",
        "LINEAS_ARTICULOS","=", "LINEAS_ARTICULOS.LINEA_ARTICULO_ID", "ARTICULOS.LINEA_ARTICULO_ID");


    $fecha = " AND DOCTOS_VE.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";
    $fecha2 = " AND DOCTOS_PV.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";
    $fecha3 = " AND IMPORTES_DOCTOS_CC.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";

    $TotalVenta = 0;



    /* -----------------------------*/
    $condicionales = " DOCTOS_VE_DET.CLAVE_ARTICULO IN ('PRO03', 'CN11', 'CN12', 'CN13','CN17') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = " DOCTOS_PV_DET.CLAVE_ARTICULO IN ('PRO03', 'CN11', 'CN12', 'CN13','CN17') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C'".$fecha2;

    $nexosDiseno = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintDiseno = capacidad($join, $condicionales, 2, $join2, $condicionales2);

    $DisenoTotal = ($nexosDiseno->UNIDADES +$nexprintDiseno->UNIDADES);
    $DisenoCTotal = ($nexosDiseno->CANTIDAD + $nexprintDiseno->CANTIDAD);
    $TotalVenta += $DisenoCTotal;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = " (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='1317' OR DOCTOS_VE_DET.CLAVE_ARTICULO='C05') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C' ".$fecha;
    $condicionales2 = " (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='1317' OR DOCTOS_PV_DET.CLAVE_ARTICULO='C05') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N' ".$fecha2;
    $condicionales3 = " (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2143' OR DOCTOS_VE_DET.CLAVE_ARTICULO='C05') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C' ".$fecha;
    $condicionales4 = " (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2143' OR DOCTOS_PV_DET.CLAVE_ARTICULO='C05') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N' ".$fecha2;

    $nexosInstalacion = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintInstalacion = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $InstalacionTotal = ($nexosInstalacion->UNIDADES + $nexprintInstalacion->UNIDADES);
    $InstalacionCTotal = ($nexosInstalacion->CANTIDAD + $nexprintInstalacion->CANTIDAD);
    $TotalVenta += $InstalacionCTotal;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  ((LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TL%') OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO04')  AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  ((LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TL%') OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO04')  AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = " ((LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TL%') OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO04') AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TL%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = " ((LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TL%') OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO04') AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TL%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosImpresionGFL = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintImpresionGFL = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFL = ($nexosImpresionGFL->UNIDADES + $nexprintImpresionGFL->UNIDADES);
    $ImpresionCTotalGFL = ($nexosImpresionGFL->CANTIDAD + $nexprintImpresionGFL->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFL;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosImpresionGFV = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintImpresionGFV = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFV = ($nexosImpresionGFV->UNIDADES + $nexprintImpresionGFV->UNIDADES);
    $ImpresionCTotalGFV = ($nexosImpresionGFV->CANTIDAD + $nexprintImpresionGFV->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFV;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosImpresionGFLA = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintImpresionGFLA = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFLA = ($nexosImpresionGFLA->UNIDADES + $nexprintImpresionGFLA->UNIDADES);
    $ImpresionCTotalGFLA = ($nexosImpresionGFLA->CANTIDAD + $nexprintImpresionGFLA->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFLA;



    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosImpresionGFP = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintImpresionGFP = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFP = ($nexosImpresionGFP->UNIDADES + $nexprintImpresionGFP->UNIDADES);
    $ImpresionCTotalGFP = ($nexosImpresionGFP->CANTIDAD + $nexprintImpresionGFP->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFP;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  DOCTOS_VE_DET.CLAVE_ARTICULO IN ('CN18', 'CN14', 'CN19', 'CN15') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  DOCTOS_PV_DET.CLAVE_ARTICULO IN ('CN18', 'CN14', 'CN19', 'CN15') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosWeb = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintWeb = capacidad($join, $condicionales, 2, $join2, $condicionales2);

    $WebTotal = ($nexosWeb->UNIDADES + $nexprintWeb->UNIDADES);
    $WebCTotal = ($nexosWeb->CANTIDAD + $nexprintWeb->CANTIDAD);
    $TotalVenta += $WebCTotal;

    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='7159' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='7159' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2140' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2140' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosGestion = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintGestion = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $GestionTotal = ($nexosGestion->UNIDADES + $nexprintGestion->UNIDADES);
    $GestionCTotal = ($nexosGestion->CANTIDAD + $nexprintGestion->CANTIDAD);
    $TotalVenta += $GestionCTotal;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2616' AND DOCTOS_VE_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2616' AND DOCTOS_PV_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2160' AND DOCTOS_VE_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2160' AND DOCTOS_PV_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosCorte = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintCorte = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $CorteTotal = ($nexosCorte->UNIDADES + $nexprintCorte->UNIDADES);
    $CorteCTotal = ($nexosCorte->CANTIDAD + $nexprintCorte->CANTIDAD);
    $TotalVenta += $CorteCTotal;


    /* -----------------------------*/

    /* -----------------------------*/
    /*$condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2048' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2149' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;

    $nexosTarjetas = capacidad($join, $condicionales, 1);
    $nexprintTarjetas = capacidad($join, $condicionales2, 2);

    $TarjetasTotal = ($nexosTarjetas->UNIDADES + $nexprintTarjetas->UNIDADES);
    $TarjetasCTotal = ($nexosTarjetas->CANTIDAD + $nexprintTarjetas->CANTIDAD);
    $TotalVenta += $TarjetasCTotal;*/


    /* -----------------------------*/


    /* -----------------------------*/
    $condicionales = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('1849','1954','2048')  OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('1849','1954','2048')  OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('2146','2147','2149') OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('2146','2147','2149') OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosImpresionD = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintImpresionD = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalD = ($nexosImpresionD->UNIDADES + $nexprintImpresionD->UNIDADES);
    $ImpresionCTotalD = ($nexosImpresionD->CANTIDAD + $nexprintImpresionD->CANTIDAD);
    $TotalVenta += $ImpresionCTotalD;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2069' OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2069' OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2150' OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2150' OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosBanner = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintBanner = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $BannerTotal = ($nexosBanner->UNIDADES + $nexprintBanner->UNIDADES);
    $BannerCTotal = ($nexosBanner->CANTIDAD + $nexprintBanner->CANTIDAD);
    $TotalVenta += $BannerCTotal;


    /* -----------------------------*/

    /* -----------------------------*/
    $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2184' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2184' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2152' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2152'AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosMaquilas = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintMaquilas = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $MaquilasTotal = ($nexosMaquilas->UNIDADES + $nexprintMaquilas->UNIDADES);
    $MaquilasCTotal = ($nexosMaquilas->CANTIDAD + $nexprintMaquilas->CANTIDAD);
    $TotalVenta += $MaquilasCTotal;


    /* -----------------------------*/



    /* -----------------------------*/
    $condicionales = " LINEAS_ARTICULOS.LINEA_ARTICULO_ID NOT IN ('3516',1265,'1849','2184','1317','2616','6347', '2048','2069','7159','1954')  AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = " LINEAS_ARTICULOS.LINEA_ARTICULO_ID NOT IN ('3516',1265,'1849','2184','1317','2616','6347', '2048','2069','7159','1954')  AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = " LINEAS_ARTICULOS.LINEA_ARTICULO_ID NOT IN ('2156','2139','2146','2152','2143','2160','2152','2140','2145','2150','2147', '2149') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = " LINEAS_ARTICULOS.LINEA_ARTICULO_ID NOT IN ('2156','2139','2146','2152','2143','2160','2152','2140','2145','2150','2147', '2149') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;

    $nexosOtros = capacidad($join, $condicionales, 1, $join2, $condicionales2);
    $nexprintOtros = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $OtrosTotalU = ($nexosOtros->UNIDADES + $nexprintOtros->UNIDADES);
    $OtrosTotalC = ($nexosOtros->CANTIDAD + $nexprintOtros->CANTIDAD);
    $TotalVenta += $OtrosTotalC;

    $nexosNotas = Notas(1, $fecha3);
    $nexprintNotas = Notas(2, $fecha3);

    $NotasTotalU = ($nexosNotas->UNIDADES + $nexprintNotas->UNIDADES);
    $NotasTotalC = ($nexosNotas->CANTIDAD + $nexprintNotas->CANTIDAD);
    $TotalVenta += $NotasTotalC;


    /* -----------------------------*/

    /* -----------------------------*/
    /*$condicionales = " ";
    $condicionales2 =  "DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS!='C'".$fecha2;

    //$nexosOtros = capacidad($join, $condicionales, 1);
    $nexprintPv = capacidadPv($join2, $condicionales2, 2);

    $PvTotalU = ($nexprintPv->UNIDADES);
    $PvTotalC = ($nexprintPv->CANTIDAD);
    $TotalVenta += $PvTotalC;*/



    /* -----------------------------*/

    //$TotalVenta+=($TotalVenta*0.16);

    if($TotalVenta == 0)    $TotalVenta=1;

    $percertInstalacion = number_format((($InstalacionCTotal/$TotalVenta)*100),2);
    $percertDiseno = number_format((($DisenoCTotal/$TotalVenta)*100),2);
    $percertImpresionGFL = number_format((($ImpresionCTotalGFL/$TotalVenta)*100),2);
    $percertImpresionGFV = number_format((($ImpresionCTotalGFV/$TotalVenta)*100),2);
    $percertImpresionGFA = number_format((($ImpresionCTotalGFLA/$TotalVenta)*100),2);
    $percertImpresionGFP = number_format((($ImpresionCTotalGFP/$TotalVenta)*100),2);
    $percertImpresionD = number_format((($ImpresionCTotalD/$TotalVenta)*100),2);
    $percertBanner = number_format((($BannerCTotal/$TotalVenta)*100),2);
    $percertMaquilas = number_format((($MaquilasCTotal/$TotalVenta)*100),2);
    //$percertTarjetas = number_format((($TarjetasCTotal/$TotalVenta)*100),2);
    $percertCorte = number_format((($CorteCTotal/$TotalVenta)*100),2);
    $percertGestion = number_format((($GestionCTotal/$TotalVenta)*100),2);
    $percertWeb = number_format((($WebCTotal/$TotalVenta)*100),2);
    $percertOtros = number_format((($OtrosTotalC/$TotalVenta)*100),2);
    $percertNotas = number_format((($NotasTotalC/$TotalVenta)*100),2);
    //$percertPv = number_format((($PvTotalC/$TotalVenta)*100),2);

    if($TotalVenta == 1)    $TotalVenta=0;


    $DisenoTotal = number_format($DisenoTotal,2,".","");
    $DisenoCTotal = number_format($DisenoCTotal,2,".",",");

    $InstalacionTotal = number_format($InstalacionTotal,2,".","");
    $InstalacionCTotal = number_format($InstalacionCTotal,2,".",",");

    $BannerTotal = number_format($BannerTotal,2,".","");
    $BannerCTotal = number_format($BannerCTotal	,2,".",",");

    $MaquilasTotal = number_format($MaquilasTotal,2,".","");
    $MaquilasCTotal = number_format($MaquilasCTotal	,2,".",",");

    $ImpresionTotalD = number_format($ImpresionTotalD,2,".","");
    $ImpresionCTotalD = number_format($ImpresionCTotalD	,2,".",",");

    $GestionTotal = number_format($GestionTotal,2,".","");
    $GestionCTotal = number_format($GestionCTotal,2,".","");

    $CorteTotal = number_format($CorteTotal,2,".","");
    $CorteCTotal = number_format($CorteCTotal,2,".",",");

    //$TarjetasTotal = number_format($TarjetasTotal,2,".","");
    //$TarjetasCTotal = number_format($TarjetasCTotal	,2,".",",");

    $OtrosTotalU = number_format($OtrosTotalU,2,".","");
    $OtrosTotalC = number_format($OtrosTotalC,2,".",",");

    $NotasTotalU = number_format($NotasTotalU,2,".","");
    $NotasTotalC = number_format($NotasTotalC,2,".",",");

    $PvTotalU = number_format($PvTotalU,2,".","");
    $PvTotalC = number_format($PvTotalC,2,".",",");

    $ImpresionTotalGFV = number_format($ImpresionTotalGFV,2,".","");
    $ImpresionCTotalGFV = number_format($ImpresionCTotalGFV	,2,".",",");

    $ImpresionTotalGFLA = number_format($ImpresionTotalGFLA,2,".","");
    $ImpresionCTotalGFLA = number_format($ImpresionCTotalGFLA,2,".",",");

    $ImpresionTotalGFP = number_format($ImpresionTotalGFP,2,".","");
    $ImpresionCTotalGFP = number_format($ImpresionCTotalGFP	,2,".",",");

    $WebTotal = number_format($WebTotal,2,".","");
    $WebCTotal = number_format($WebCTotal,2,".",",");

    $ImpresionTotalGFL = number_format($ImpresionTotalGFL,2,".","");
    $ImpresionCTotalGFL = number_format($ImpresionCTotalGFL,2,".",",");




    $TotalVenta = number_format($TotalVenta,2,".",",");
    $json = array("diseno"=>array("TITULO"=>"DISEÑO", "UNIDADES"=>"HR", "VALOR"=>$DisenoTotal, "CANTIDAD"=>$DisenoCTotal, "PERCENT"=>$percertDiseno),
        "instalacion"=>array("TITULO"=>"INSTALACION", "UNIDADES"=>"M2", "VALOR"=>$InstalacionTotal, "CANTIDAD"=>$InstalacionCTotal, "PERCENT"=>$percertInstalacion),
        "impresionGFL"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (LONA)", "UNIDADES"=>"M2", "VALOR"=>$ImpresionTotalGFL, "CANTIDAD"=>$ImpresionCTotalGFL, "PERCENT"=>$percertImpresionGFL),
        "impresionGFV"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (VINIL)", "UNIDADES"=>"M2", "VALOR"=>$ImpresionTotalGFV, "CANTIDAD"=>$ImpresionCTotalGFV, "PERCENT"=>$percertImpresionGFV),
        "impresionGFLA"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (RIGIDOS)", "UNIDADES"=>"PIEZA", "VALOR"=>$ImpresionTotalGFLA, "CANTIDAD"=>$ImpresionCTotalGFLA, "PERCENT"=>$percertImpresionGFA),
        "impresionGFP"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (TRI SOLVENTE)", "UNIDADES"=>"M2", "VALOR"=>$ImpresionTotalGFP, "CANTIDAD"=>$ImpresionCTotalGFP, "PERCENT"=>$percertImpresionGFP),
        "corte"=>array("TITULO"=>"CORTE DE VINIL", "UNIDADES"=>"M2", "VALOR"=>$CorteTotal, "CANTIDAD"=>$CorteCTotal, "PERCENT"=>$percertCorte),
        "impresionD"=>array("TITULO"=>"IMPRESIÓN DIGITAL", "UNIDADES"=>"PIEZA", "VALOR"=>$ImpresionTotalD, "CANTIDAD"=>$ImpresionCTotalD, "PERCENT"=>$percertImpresionD),
        //"tarjetas"=>array("TITULO"=>"TARJETAS", "UNIDADES"=>"PIEZA", "VALOR"=>$TarjetasTotal, "CANTIDAD"=>$TarjetasCTotal, "PERCENT"=>$percertTarjetas),
        "banner"=>array("TITULO"=>"BANNER, PV, MURALES", "UNIDADES"=>"PIEZA", "VALOR"=>$BannerTotal, "CANTIDAD"=>$BannerCTotal, "PERCENT"=> $percertBanner),
        "maquilas"=>array("TITULO"=>"MAQUILAS", "UNIDADES"=>"PAQUETE", "VALOR"=>$MaquilasTotal, "CANTIDAD"=>$MaquilasCTotal, "PERCENT"=>$percertMaquilas),
        "web"=>array("TITULO"=>"MARKETING", "UNIDADES"=>"HR", "VALOR"=>$WebTotal, "CANTIDAD"=>$WebCTotal, "PERCENT"=>$percertWeb),
        "gestion"=>array("TITULO"=>"GESTION DE NEGOCIOS", "UNIDADES"=>"HR", "VALOR"=>$GestionTotal, "CANTIDAD"=>$GestionCTotal, "PERCENT"=>$percertGestion),
        //"pv"=>array("TITULO"=>"PUNTO DE VENTA", "UNIDADES"=>"--", "VALOR"=>$PvTotalU, "CANTIDAD"=>$PvTotalC, "PERCENT"=>$percertPv),
        "otros"=>array("TITULO"=>"OTROS", "UNIDADES"=>"--", "VALOR"=>$OtrosTotalU, "CANTIDAD"=>$OtrosTotalC, "PERCENT"=>$percertOtros),
        "notas"=>array("TITULO"=>"NOTAS DE CARGO", "UNIDADES"=>"--", "VALOR"=>$NotasTotalU, "CANTIDAD"=>$NotasTotalC, "PERCENT"=>$percertNotas),
        "total"=>array("TITULO"=>"TOTAL", "UNIDADES"=>"", "VALOR"=>"", "CANTIDAD"=>$TotalVenta));



    $obj = (object) $json;
    echo json_encode($obj);
}

function capacidad($join, $condicionales, $empresa, $join2, $condicionales2)
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

          $query = "select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET $joins WHERE $condicionales $whereUnions";
          $query2 = "select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE $condicionales2 $whereUnions2";

          //echo $query;
          $conection = new conexion_nexos($empresa);
          $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

          while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
              $paginador = $row;
          }

          $result2 = ibase_query($conection->getConexion(), $query2) or die(ibase_errmsg());

          while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
              $paginador2 = $row2;
          }

          $paginador->UNIDADES += $paginador2->UNIDADES;
          $paginador->CANTIDAD += $paginador2->CANTIDAD;

          return $paginador;
      }catch(Exception $e) {
          echo 'Excepción capturada: ',  $e, "\n";
      }
}

function capacidadPv($join, $condicionales, $empresa)
{

    try
    {
        $ciclos = count($join) / 4;
        $whereUnions = "";
        $joins = "";
        for($i = 0; $i < $ciclos; $i++)
        {
            $joins .= ", ".$join[($i * 4)]." ";
            $whereUnions .= " AND ".$join[(($i * 4) + 2)]." ".$join[(($i * 4) + 1)]." ".$join[(($i * 4) + 3)];
        }

        $query = "select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins WHERE $condicionales $whereUnions";
        //echo $query;
        $conection = new conexion_nexos($empresa);
        $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

        while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
            $paginador = $row;
        }

        return $paginador;
    }catch(Exception $e) {
        echo 'Excepción capturada: ',  $e, "\n";
    }

}

function Notas($empresa, $condicionales)
{
  try
  {
      $query = "select COUNT(*) as UNIDADES, SUM(IMPORTES_DOCTOS_CC.IMPORTE) as CANTIDAD from DOCTOS_CC, IMPORTES_DOCTOS_CC
      WHERE  DOCTOS_CC.DOCTO_CC_ID=IMPORTES_DOCTOS_CC.DOCTO_CC_ID AND DOCTOS_CC.CONCEPTO_CC_ID='8' AND DOCTOS_CC.CANCELADO='N' ".$condicionales;
      //echo $query;
      $conection = new conexion_nexos($empresa);
      $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

      while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
          $paginador = $row;
      }

      return $paginador;
  }catch(Exception $e) {
      echo 'Excepción capturada: ',  $e, "\n";
  }
}

if($_POST["accion"] == "ventas")
{
    $tabla = "DOCTOS_VE";
    $condicionales = " and tipo_docto='F' and ESTATUS!='C' AND fecha between '".$_POST['fechaInicio']."' and '".$_POST['fechaHasta']."' group by fecha order by fecha";
    $condicionales2 = "  and DOCTOS_CC.CONCEPTO_CC_ID='8' and DOCTOS_CC.CANCELADO='N' AND IMPORTES_DOCTOS_CC.FECHA between '".$_POST['fechaInicio']."' and '".$_POST['fechaHasta']."' group by IMPORTES_DOCTOS_CC.FECHA order by IMPORTES_DOCTOS_CC.FECHA";

    $arreglo1 = calcula_suubtotales(1, $tabla, $condicionales, "NEXOS EMPRESARIALES" , 1, $condicionales2);

    $arreglo2 = calcula_suubtotales(2, $tabla, $condicionales, "NEXPRINT" , 1, $condicionales2);

    $condicionales2 = " and tipo_docto='V' and ESTATUS!='C' AND fecha between '".$_POST['fechaInicio']."' and '".$_POST['fechaHasta']."' group by fecha order by fecha";

    $tabla2 = "DOCTOS_PV";
    $arreglo3 = calcula_suubtotales(2, $tabla2, $condicionales2, "NEXPRINT MOSTRADOR", 0);

    $json2 = array_merge($arreglo1, $arreglo2);
    $json2 = array_merge($json2, $arreglo3);

    $contador = count($json2);
    for($i = 0; $i < $contador; $i++)
    {
        $j = ($i + 1);
        for(; $j < $contador; $j++)
        {
            if($json2[$i]['dia'] > $json2[$j]['dia'])
            {
                $arrayAuxiliar[0] = $json2[$i];
                $json2[$i] = $json2[$j];
                $json2[$j] = $arrayAuxiliar[0];
            }
        }
    }

    $arreglo_salida = array();

    $subtotal = 0;
    $total = 0;
    for($i = 0; $i < $contador; $i++)
    {
        $total +=  $json2[$i]['suma'];
        if($i == 0)
        {

            $subtotal = $json2[$i]['suma'];
            $json2[$i]['suma'] = number_format($json2[$i]['suma'],2, ".", ",");
            $arreglo_aux[count($arreglo_aux)]  = $json2[$i];
        }
        else
        {
            if($json2[$i]['dia'] == $json2[$i-1]['dia'])
            {
                $subtotal += $json2[$i]['suma'];
                $json2[$i]['suma'] = number_format($json2[$i]['suma'],2, ".", ",");
                $arreglo_aux[count($arreglo_aux)]  = $json2[$i];
            }else
            {
                $index = count($arreglo_aux);
                $arreglo_aux[$index]['dia'] = "-";
                $arreglo_aux[$index]['suma'] = number_format($subtotal,2, ".", ",");
                $arreglo_aux[$index]['EMPRESA'] = "SUBTOTAL";

                $subtotal = $json2[$i]['suma'];
                $json2[$i]['suma'] = number_format($json2[$i]['suma'],2, ".", ",");
                $arreglo_aux[]  = $json2[$i];


            }
        }


    }


    $index = count($arreglo_aux);
    $arreglo_aux[$index]['dia'] = "-";
    $arreglo_aux[$index]['suma'] = number_format($subtotal,2, ".", ",");
    $arreglo_aux[$index]['EMPRESA'] = "SUBTOTAL";

    $index = count($arreglo_aux);
    $arreglo_aux[$index]['dia'] = "-";
    $arreglo_aux[$index]['suma'] = number_format($total,2, ".", ",");
    $arreglo_aux[$index]['EMPRESA'] = "TOTAL";

    $obj = (object) $arreglo_aux;
    echo json_encode($obj);
}

function calcula_suubtotales($empresa, $tabla, $condicionales, $namempresa, $notas, $condicionales2 = "")
{
    $conection = new conexion_nexos($empresa);

    $campo_suma = "IMPORTE_NETO";
    $campos = "FECHA";

    $json = $conection->sum_advanced($campo_suma, $campos, $tabla, array(), $condicionales, 0, $namempresa);

    $json2 = array();
    if($notas == 1)
    {
      $campo_suma = "IMPORTES_DOCTOS_CC.IMPORTE";
      $campos = "IMPORTES_DOCTOS_CC.FECHA";
      $tabla = "DOCTOS_CC";
      $join = array("IMPORTES_DOCTOS_CC", "=", "DOCTOS_CC.DOCTO_CC_ID", "IMPORTES_DOCTOS_CC.DOCTO_CC_ID", "LEFT");
      $json2 = $conection->sum_advanced($campo_suma, $campos, $tabla, $join, $condicionales2, 0, "NOTAS DE CARGO");

    }
    $json3 = array_merge($json, $json2);

    /*for($i = 0; $i < count($json); $i++)
    {
        $json[$i]["EMPRESA"] = $namempresa;
    }*/
    return $json3;
}
?>
