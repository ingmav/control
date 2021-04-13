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


    $fecha1 = " AND DOCTOS_VE.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";
    $fecha2 = " AND DOCTOS_PV.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";
    $fecha3 = " AND IMPORTES_DOCTOS_CC.FECHA BETWEEN '$fecha_inicio' and '$fecha_finalizado'";

    $TotalVenta = 0;


    $arreglo1 = array(0);
    $arreglo2 = array(14660, 14588, 13983, 14644, 14874, 19395);
    
    $nexosDiseno = capacidad($join, $arreglo1, 1, $join2, $arreglo2, $fecha1, $fecha2);

    $DisenoTotal = ($nexosDiseno['UNIDADES']);
    $DisenoCTotal = ($nexosDiseno['CANTIDAD']);
    
    $TotalVenta += $DisenoCTotal;

    //-------------------------------------
    $arreglo3 = array(0);
    $arreglo4 = array( 14243, 14332, 14010, 14336, 14223, 14340, 14782, 14376);     
    
    $nexosInstalacion = capacidad($join, $arreglo3, 1, $join2, $arreglo4, $fecha1, $fecha2);

    $InstalacionTotal = ($nexosInstalacion['UNIDADES']);
    $InstalacionCTotal = ($nexosInstalacion['CANTIDAD']);
    $TotalVenta += $InstalacionCTotal;


    //$condicionales = "  ((LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TL%') OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO04')  AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    //$condicionales2 = " ((LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TL%') OR DOCTOS_PV_DET.CLAVE_ARTICULO in ('PRO04', 'MSG01')) AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TL%' AND +DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;


    $arreglo5 = array(0);
    $arreglo6 = array(14384, 14461, 17364, 20441, 15095, 15099, 15111, 15115, 15119, 15107, 15135, 15139, 15143, 15103, 15123, 15127, 15131, 20491, 20494, 20497,20500);     
    
    $nexosImpresionGFL = capacidad($join, $arreglo5, 1, $join2, $arreglo6, $fecha1, $fecha2);
    //$nexprintImpresionGFL = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFL = ($nexosImpresionGFL['UNIDADES']);
    $ImpresionCTotalGFL = ($nexosImpresionGFL['CANTIDAD']);
    $TotalVenta += $ImpresionCTotalGFL;


    /*$condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TV%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/

    $arreglo7 = array(0);
    $arreglo8 = array(17368, 15147, 15151, 15155, 15159, 15163, 15167, 15171, 15175, 15191, 15195, 15199, 15203, 15207, 15211, 15215, 15219, 15223,15323, 15327, 15359,  16552, 15311, 15315, 15319, 21744, 21748, 21752, 21756, 21760,21764, 14620,14615,14600,16540,14605,21054,14629,14486,14689,16544,16548, 19464, 22856);
    

    $nexosImpresionGFV = capacidad($join, $arreglo7, 1, $join2, $arreglo8, $fecha1, $fecha2);
    //$nexprintImpresionGFV = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFV = $nexosImpresionGFV['UNIDADES'];// + $nexprintImpresionGFV->UNIDADES);
    $ImpresionCTotalGFV = $nexosImpresionGFV['CANTIDAD'];// + $nexprintImpresionGFV->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFV;


    /*$condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TR%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/

    $arreglo9 = array(0);
    $arreglo10 = array(14205,14231,14235,14239,13973,14400,13968,21654,21660,21664,15079,15227,15075,15231,15235,15239,15243,15247,15251,15083,15255,15087,15259,15263,15267,15271,15275,15279,15283,15287,15331,15347,15351,15355,19401,19405,19409,19413,19419,19423,19428,19432,19436,19440,19444,19448,19452,19456);     
    
    $nexosImpresionGFLA = capacidad($join, $arreglo9, 1, $join2, $arreglo10, $fecha1, $fecha2);
    //$nexprintImpresionGFLA = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFLA = $nexosImpresionGFLA['UNIDADES'];// + $nexprintImpresionGFLA->UNIDADES);
    $ImpresionCTotalGFLA = $nexosImpresionGFLA['CANTIDAD'];// + $nexprintImpresionGFLA->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFLA;


    /*$condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='3516' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_VE_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2156' AND DOCTOS_PV_DET.CLAVE_ARTICULO LIKE 'TP%' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/
    $arreglo11 = array(0);
    $arreglo12 = array(15291,15295,15299,15303); 

    $nexosImpresionGFP = capacidad($join, $arreglo11, 1, $join2, $arreglo12, $fecha1, $fecha2);
    //$nexprintImpresionGFP = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalGFP = $nexosImpresionGFP['UNIDADES'];// + $nexprintImpresionGFP->UNIDADES);
    $ImpresionCTotalGFP = $nexosImpresionGFP['CANTIDAD'];// + $nexprintImpresionGFP->CANTIDAD);
    $TotalVenta += $ImpresionCTotalGFP;

    /*$condicionales = "  DOCTOS_VE_DET.CLAVE_ARTICULO IN ('CN18', 'CN14', 'CN19', 'CN15') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  DOCTOS_PV_DET.CLAVE_ARTICULO IN ('CN18', 'CN14', 'CN19', 'CN15') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/

    $arreglo13 = array(0);
    $arreglo14 = array(14668,14863,17285,14901,17600); 

    $nexosWeb = capacidad($join, $arreglo13, 1, $join2, $arreglo14, $fecha1, $fecha2);
    //$nexprintWeb = capacidad($join, $condicionales, 2, $join2, $condicionales2);

    $WebTotal = $nexosWeb['UNIDADES'];// + $nexprintWeb->UNIDADES);
    $WebCTotal = $nexosWeb['CANTIDAD'];// + $nexprintWeb->CANTIDAD);
    $TotalVenta += $WebCTotal;

    /*$condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='7159' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='7159' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2140' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2140' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/
    $arreglo15 = array(0);
    $arreglo16 = array(14584); 

    $nexosGestion = capacidad($join, $arreglo15, 1, $join2, $arreglo16, $fecha1, $fecha2);
    //$nexprintGestion = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $GestionTotal = $nexosGestion['UNIDADES'];// + $nexprintGestion->UNIDADES);
    $GestionCTotal = $nexosGestion['CANTIDAD'];// + $nexprintGestion->CANTIDAD);
    $TotalVenta += $GestionCTotal;

    /*$condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2616' AND DOCTOS_VE_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2616' AND DOCTOS_PV_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2160' AND DOCTOS_VE_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2160' AND DOCTOS_PV_DET.CLAVE_ARTICULO!='C05' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/
    $arreglo17 = array(0);
    $arreglo18 = array(15059,15335,15063,14752,14494,14441,17776,17384,17388,20445,20449,14629,20409,14648); 

    $nexosCorte = capacidad($join, $arreglo17, 1, $join2, $arreglo18, $fecha1, $fecha2);
    //$nexprintCorte = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $CorteTotal = $nexosCorte['UNIDADES'];// + $nexprintCorte->UNIDADES);
    $CorteCTotal = $nexosCorte['CANTIDAD'];// + $nexprintCorte->CANTIDAD);
    $TotalVenta += $CorteCTotal;

    /////////////////// Corte en Router

    $arreglo31 = array(0);
    $arreglo32 = array(21532, 21536, 14320); 

    $nexosRouter = capacidad($join, $arreglo31, 1, $join2, $arreglo32, $fecha1, $fecha2);
    //$nexprintCorte = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $RouterTotal = $nexosRouter['UNIDADES'];// + $nexprintCorte->UNIDADES);
    $RouterCTotal = $nexosRouter['CANTIDAD'];// + $nexprintCorte->CANTIDAD);
    $TotalVenta += $RouterCTotal;

    ///////////////////

    /*$condicionales = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('1849','1954','2048')  OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('1849','1954','2048')  OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('2146','2147','2149') OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID IN ('2146','2147','2149') OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO01') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/

    $arreglo19 = array(0);
    $arreglo20 = array(15387,15397,15405,15414,15050,15423,15432,15441,15450,15459,15468,14944,15477,15486,15495,15504,15513,15522,14952,15531,14969,14960,14978,15540,14987,14993,14999,14247,14014,14018,14022,14026,14030,14172,14139,14144,14149,14154,15005,14522,14181,17327,17398,17359,17372,17376,17392,21802,21993,21997,15019,15023,15027,15031,15371,14251, 14255,15375, 14185); 

    $nexosImpresionD = capacidad($join, $arreglo19, 1, $join2, $arreglo20, $fecha1, $fecha2);
    //$nexprintImpresionD = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $ImpresionTotalD = $nexosImpresionD['UNIDADES'];// + $nexprintImpresionD->UNIDADES);
    $ImpresionCTotalD = $nexosImpresionD['CANTIDAD'];// + $nexprintImpresionD->CANTIDAD);
    $TotalVenta += $ImpresionCTotalD;

    /*$condicionales = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2069' OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2069' OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2150' OR DOCTOS_VE_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  (LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2150' OR DOCTOS_PV_DET.CLAVE_ARTICULO ='PRO02') AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/

    $arreglo21 = array(0);
    $arreglo22 = array(15067,14924,14637,15700,15714,19858,15728,15721,15707,22228,20391,21516,21520,21524,19823,19827,19831,19835,14465,15745,15735,15740,15750,15754,16200,15758,15769,16187,20457,20463,20467,22307,23141,23232, 16616, 19808); 

    $nexosBanner = capacidad($join, $arreglo21, 1, $join2, $arreglo22, $fecha1, $fecha2);
    //$nexprintBanner = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $BannerTotal = $nexosBanner['UNIDADES'];// + $nexprintBanner->UNIDADES);
    $BannerCTotal = $nexosBanner['CANTIDAD'];// + $nexprintBanner->CANTIDAD);
    $TotalVenta += $BannerCTotal;


   /* $condicionales = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2184' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales2 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2184' AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;
    $condicionales3 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2152' AND DOCTOS_VE.TIPO_DOCTO='F' AND DOCTOS_VE.ESTATUS!='C'".$fecha;
    $condicionales4 = "  LINEAS_ARTICULOS.LINEA_ARTICULO_ID='2152'AND DOCTOS_PV.TIPO_DOCTO='V' AND DOCTOS_PV.ESTATUS='N'".$fecha2;*/

    $arreglo23 = array(0);
    $arreglo24 = array(20413,20417,20421,20425,20428,20432,20435,20438); 

    $nexosMaquilas = capacidad($join, $arreglo23, 1, $join2, $arreglo24, $fecha1, $fecha2);
    //$nexprintMaquilas = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $MaquilasTotal = $nexosMaquilas['UNIDADES'];// + $nexprintMaquilas->UNIDADES);
    $MaquilasCTotal = $nexosMaquilas['CANTIDAD'];// + $nexprintMaquilas->CANTIDAD);
    $TotalVenta += $MaquilasCTotal;

    $arreglo25 = array(0);
    $arreglo26 = array(22335,21309,21316,21323,21330,21486,21337,21344,21351,21451,21244,21254,21275,21261,21268,21282,21289,21302,21398,21616,21789,22007,22032,14928,22446); 

    $nexosSublimacion = capacidad($join, $arreglo25, 1, $join2, $arreglo26, $fecha1, $fecha2);
    //$nexprintMaquilas = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $SublimacionTotal = $nexosSublimacion['UNIDADES'];// + $nexprintMaquilas->UNIDADES);
    $SublimacionCTotal = $nexosSublimacion['CANTIDAD'];// + $nexprintMaquilas->CANTIDAD);
    $TotalVenta += $SublimacionCTotal;

    $arreglo27 = array(0);
    $arreglo28 = array(14197,14702,14312,14193,14490,14641,14568,19760,14478,14697,16307,14457,19756,14560,13978,20453,14528,13903,14300,14633); 

    $nexosInsumos = capacidad($join, $arreglo27, 1, $join2, $arreglo28, $fecha1, $fecha2);
    //$nexprintMaquilas = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $InsumosTotal = $nexosInsumos['UNIDADES'];// + $nexprintMaquilas->UNIDADES);
    $InsumosCTotal = $nexosInsumos['CANTIDAD'];// + $nexprintMaquilas->CANTIDAD);
    $TotalVenta += $InsumosCTotal;


    //$arreglo29 = array_merge($arreglo1,$arreglo3,$arreglo5,$arreglo7,$arreglo9,$arreglo11,$arreglo13,$arreglo15,$arreglo17,$arreglo19,$arreglo21,$arreglo23,$arreglo25,$arreglo27);
    $arreglo29 = array(0);
    $arreglo30 = array_merge($arreglo2,$arreglo4,$arreglo6,$arreglo8,$arreglo10,$arreglo12,$arreglo14,$arreglo16,$arreglo18,$arreglo20,$arreglo22,$arreglo24,$arreglo26,$arreglo28,$arreglo31,$arreglo32 ); 

    $nexosOtros = capacidad_otros($join, $arreglo29, 1, $join2, $arreglo30, $fecha1, $fecha2);
    //$nexprintMaquilas = capacidad($join, $condicionales3, 2, $join2, $condicionales4);

    $OtrosTotal = $nexosOtros['UNIDADES'];// + $nexprintMaquilas->UNIDADES);
    $OtrosCTotal = $nexosOtros['CANTIDAD'];// + $nexprintMaquilas->CANTIDAD);
    $TotalVenta += $OtrosCTotal;

    $nexosNotas = Notas(1, $fecha3);
    $nexprintNotas = Notas(2, $fecha3);

    $NotasTotalU = ($nexosNotas->UNIDADES + $nexprintNotas->UNIDADES);
    $NotasTotalC = ($nexosNotas->CANTIDAD + $nexprintNotas->CANTIDAD);
    $TotalVenta += $NotasTotalC;


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
    $percertCorte = number_format((($CorteCTotal/$TotalVenta)*100),2);
    $percertRouter = number_format((($RouterCTotal/$TotalVenta)*100),2);
    $percertGestion = number_format((($GestionCTotal/$TotalVenta)*100),2);
    $percertWeb = number_format((($WebCTotal/$TotalVenta)*100),2);
    $percertSublimacion = number_format((($SublimacionCTotal/$TotalVenta)*100),2);
    $percertInsumos = number_format((($InsumosCTotal/$TotalVenta)*100),2);
    $percertOtros = number_format((($OtrosCTotal/$TotalVenta)*100),2);
    $percertNotas = number_format((($NotasTotalC/$TotalVenta)*100),2);

    if($TotalVenta == 1)    $TotalVenta=0;


    $DisenoTotal = number_format($DisenoTotal,2,".","");
    $DisenoCTotal = number_format($DisenoCTotal,2,".",",");

    $InstalacionTotal = number_format($InstalacionTotal,2,".","");
    $InstalacionCTotal = number_format($InstalacionCTotal,2,".",",");

    $ImpresionTotalGFL = number_format($ImpresionTotalGFL,2,".","");
    $ImpresionCTotalGFL = number_format($ImpresionCTotalGFL,2,".",",");

     $ImpresionTotalGFV = number_format($ImpresionTotalGFV,2,".","");
    $ImpresionCTotalGFV = number_format($ImpresionCTotalGFV ,2,".",",");

    $ImpresionTotalGFLA = number_format($ImpresionTotalGFLA,2,".","");
    $ImpresionCTotalGFLA = number_format($ImpresionCTotalGFLA,2,".",",");

    $NotasTotalU = number_format($NotasTotalU,2,".","");
    $NotasTotalC = number_format($NotasTotalC,2,".",",");

    $ImpresionTotalGFP = number_format($ImpresionTotalGFP,2,".","");
    $ImpresionCTotalGFP = number_format($ImpresionCTotalGFP ,2,".",",");

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

    $RouterTotal = number_format($RouterTotal,2,".","");
    $RouterCTotal = number_format($RouterCTotal,2,".",",");

    $SublimacionTotal = number_format($SublimacionTotal,2,".","");
    $SublimacionCTotal = number_format($SublimacionCTotal,2,".",",");

    $InsumosTotal = number_format($InsumosTotal,2,".","");
    $InsumosCTotal = number_format($InsumosCTotal,2,".",",");

    $OtrosTotal = number_format($OtrosTotal,2,".","");
    $OtrosCTotal = number_format($OtrosCTotal,2,".",",");

    $PvTotalU = number_format($PvTotalU,2,".","");
    $PvTotalC = number_format($PvTotalC,2,".",",");
    $WebTotal = number_format($WebTotal,2,".","");
    $WebCTotal = number_format($WebCTotal,2,".",",");

    




    $TotalVenta = number_format($TotalVenta,2,".",",");
    $json = array("diseno"=>array("TITULO"=>"DISEÑO", "UNIDADES"=>"HR", "VALOR"=>$DisenoTotal, "CANTIDAD"=>$DisenoCTotal, "PERCENT"=>$percertDiseno),
        "instalacion"=>array("TITULO"=>"INSTALACION", "UNIDADES"=>"HR", "VALOR"=>$InstalacionTotal, "CANTIDAD"=>$InstalacionCTotal, "PERCENT"=>$percertInstalacion),
        "impresionGFL"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (LONA)", "UNIDADES"=>"M2", "VALOR"=>$ImpresionTotalGFL, "CANTIDAD"=>$ImpresionCTotalGFL, "PERCENT"=>$percertImpresionGFL),
        "impresionGFV"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (VINIL)", "UNIDADES"=>"M2", "VALOR"=>$ImpresionTotalGFV, "CANTIDAD"=>$ImpresionCTotalGFV, "PERCENT"=>$percertImpresionGFV),
        "impresionGFLA"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (RIGIDOS)", "UNIDADES"=>"PIEZA", "VALOR"=>$ImpresionTotalGFLA, "CANTIDAD"=>$ImpresionCTotalGFLA, "PERCENT"=>$percertImpresionGFA),
        "impresionGFP"=>array("TITULO"=>"IMPRESIÓN GRAN FORMATO (TRI SOLVENTE)", "UNIDADES"=>"M2", "VALOR"=>$ImpresionTotalGFP, "CANTIDAD"=>$ImpresionCTotalGFP, "PERCENT"=>$percertImpresionGFP),
        "corte"=>array("TITULO"=>"CORTE DE VINIL", "UNIDADES"=>"M2", "VALOR"=>$CorteTotal, "CANTIDAD"=>$CorteCTotal, "PERCENT"=>$percertCorte),
        "router"=>array("TITULO"=>"CORTE EN ROUTER", "UNIDADES"=>"M2", "VALOR"=>$RouterTotal, "CANTIDAD"=>$RouterCTotal, "PERCENT"=>$percertRouter),
        "impresionD"=>array("TITULO"=>"IMPRESIÓN DIGITAL", "UNIDADES"=>"PIEZA", "VALOR"=>$ImpresionTotalD, "CANTIDAD"=>$ImpresionCTotalD, "PERCENT"=>$percertImpresionD),
        "banner"=>array("TITULO"=>"BANNER, PV, MURALES", "UNIDADES"=>"PIEZA", "VALOR"=>$BannerTotal, "CANTIDAD"=>$BannerCTotal, "PERCENT"=> $percertBanner),
        "maquilas"=>array("TITULO"=>"MAQUILAS", "UNIDADES"=>"PAQUETE", "VALOR"=>$MaquilasTotal, "CANTIDAD"=>$MaquilasCTotal, "PERCENT"=>$percertMaquilas),
        "web"=>array("TITULO"=>"MARKETING", "UNIDADES"=>"HR", "VALOR"=>$WebTotal, "CANTIDAD"=>$WebCTotal, "PERCENT"=>$percertWeb),
        "gestion"=>array("TITULO"=>"GESTION DE NEGOCIOS", "UNIDADES"=>"HR", "VALOR"=>$GestionTotal, "CANTIDAD"=>$GestionCTotal, "PERCENT"=>$percertGestion),
        "sublimacion"=>array("TITULO"=>"SUBLIMACIÓN", "UNIDADES"=>"PIEZAS", "VALOR"=>$SublimacionTotal, "CANTIDAD"=>$SublimacionCTotal, "PERCENT"=>$percertSublimacion),
        "insumos"=>array("TITULO"=>"INSUMOS Y COMPLEMENTOS", "UNIDADES"=>"PIEZAS", "VALOR"=>$InsumosTotal, "CANTIDAD"=>$InsumosCTotal, "PERCENT"=>$percertInsumos),
        "otros"=>array("TITULO"=>"OTROS", "UNIDADES"=>"--", "VALOR"=>$OtrosTotal, "CANTIDAD"=>$OtrosCTotal, "PERCENT"=>$percertOtros),
        "notas"=>array("TITULO"=>"NOTAS DE CARGO", "UNIDADES"=>"--", "VALOR"=>$NotasTotalU, "CANTIDAD"=>$NotasTotalC, "PERCENT"=>$percertNotas),
        "total"=>array("TITULO"=>"TOTAL", "UNIDADES"=>"", "VALOR"=>"", "CANTIDAD"=>$TotalVenta));



    $obj = (object) $json;
    echo json_encode($obj);
}

function capacidad($join, $claves1, $empresa, $join2, $claves2, $fecha1, $fecha2)
{
    try
      {
        $resultado = array("UNIDADES"=>0, "CANTIDAD"=>0);
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

          $joins;
          
          /*$query = "select sum(unidades) as unidades, sum(cantidad) as cantidad from 
          (select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
          (LINEAS_ARTICULOS.LINEA_ARTICULO_ID in (".implode(",", $lineas1).") OR DOCTOS_VE_DET.CLAVE_ARTICULO in (".implode(",", $claves1).")) AND DOCTOS_VE.TIPO_DOCTO in ('F', 'R') AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
          union all 
          select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE  
          (LINEAS_ARTICULOS.LINEA_ARTICULO_ID in (".implode(",", $lineas2).") OR DOCTOS_VE_DET.CLAVE_ARTICULO in (".implode(",", $claves2).")) AND DOCTOS_VE.TIPO_DOCTO  = 'V' AND DOCTOS_VE.ESTATUS='N'
          $whereUnions2
          $fecha2
          )";

          
          //$query2 = "select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE $condicionales2 $whereUnions2";

          $conection = new conexion_nexos(1);
          $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

          while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
              $paginador = $row;
          }*/

          $conection2 = new conexion_nexos($_SESSION['empresa']);

          
          
          $query2 = "select sum(unidades) as unidades, sum(cantidad) as cantidad from 
          (select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
         DOCTOS_VE_DET.ARTICULO_ID in (".implode(",", $claves2).")  AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
          union all 
          select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE  
          DOCTOS_PV_DET.ARTICULO_ID in (".implode(",", $claves2).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
          $whereUnions2
          $fecha2)";
          /*$query2 = "select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
         DOCTOS_VE_DET.ARTICULO_ID in (".implode(",", $claves2).")  AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
         ";*/
         
          /*$query2 = "select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE  
          DOCTOS_PV_DET.ARTICULO_ID in (".implode(",", $claves2).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS!='C'
          $whereUnions2
          $fecha2";*/
          
          $result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());

          while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
              $paginador2 = $row2;
          }
         
          $conection = null;
          $conection2 = null;
          
          $resultado['UNIDADES'] = floatval($paginador->UNIDADES) + floatval($paginador2->UNIDADES);
          $resultado['CANTIDAD'] = floatval($paginador->CANTIDAD) + floatval($paginador2->CANTIDAD);
          

          return $resultado;
      }catch(Exception $e) {
          echo 'Excepción capturada: ',  $e, "\n";
      }
}

function capacidad_otros($join, $claves1, $empresa, $join2, $claves2, $fecha1, $fecha2)
{
    try
      {
        $resultado = array("UNIDADES"=>0, "CANTIDAD"=>0);
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

          $joins;
          
          /*$query = "select sum(unidades) as unidades, sum(cantidad) as cantidad from 
          (select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
          (LINEAS_ARTICULOS.LINEA_ARTICULO_ID in (".implode(",", $lineas1).") OR DOCTOS_VE_DET.CLAVE_ARTICULO in (".implode(",", $claves1).")) AND DOCTOS_VE.TIPO_DOCTO in ('F', 'R') AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
          union all 
          select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE  
          (LINEAS_ARTICULOS.LINEA_ARTICULO_ID in (".implode(",", $lineas2).") OR DOCTOS_VE_DET.CLAVE_ARTICULO in (".implode(",", $claves2).")) AND DOCTOS_VE.TIPO_DOCTO  = 'V' AND DOCTOS_VE.ESTATUS='N'
          $whereUnions2
          $fecha2
          )";

          
          //$query2 = "select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE $condicionales2 $whereUnions2";

          $conection = new conexion_nexos(1);
          $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());

          while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
              $paginador = $row;
          }*/

          $conection2 = new conexion_nexos($_SESSION['empresa']);

          $query2 = "select sum(unidades) as unidades, sum(cantidad) as cantidad from 
          (select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('F', 'R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
           DOCTOS_VE_DET.ARTICULO_ID not in (".implode(",", $claves2).") AND DOCTOS_VE.TIPO_DOCTO in ('F', 'R') AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1
          union all 
          select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE  
          DOCTOS_PV_DET.ARTICULO_ID not in (".implode(",", $claves2).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
          $whereUnions2
          $fecha2)";

          /*$query2 = "select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
           DOCTOS_VE_DET.ARTICULO_ID not in (".implode(",", $claves2).") AND DOCTOS_VE.TIPO_DOCTO in ('F', 'R') AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1";*/

          /*$query2 = "select SUM(DOCTOS_VE_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_VE_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_VE_DET
          $joins
          WHERE 
          DOCTOS_VE.TIPO_DOCTO IN ('R') 
          and DOCTOS_VE.DOCTO_VE_ID NOT IN (select dvl.docto_ve_fte_id from  doctos_ve_ligas dvl)
          and 
           DOCTOS_VE_DET.ARTICULO_ID not in (".implode(",", $claves2).") AND DOCTOS_VE.TIPO_DOCTO in ('F', 'R') AND DOCTOS_VE.ESTATUS!='C' 
          $whereUnions
          $fecha1";*/
          /*$query2 = "select SUM(DOCTOS_PV_DET.UNIDADES) as UNIDADES, SUM(DOCTOS_PV_DET.PRECIO_TOTAL_NETO) as CANTIDAD from DOCTOS_PV_DET $joins2 WHERE  
          DOCTOS_PV_DET.ARTICULO_ID not in (".implode(",", $claves2).") AND DOCTOS_PV.TIPO_DOCTO  = 'V' AND DOCTOS_PV.ESTATUS='N'
          $whereUnions2
          $fecha2";*/

          $result2 = ibase_query($conection2->getConexion(), $query2) or die(ibase_errmsg());

          while ($row2 = ibase_fetch_object ($result2, IBASE_TEXT)){
              $paginador2 = $row2;
          }
         
          $conection = null;
          $conection2 = null;
          
          $resultado['UNIDADES'] = floatval($paginador->UNIDADES) + floatval($paginador2->UNIDADES);
          $resultado['CANTIDAD'] = floatval($paginador->CANTIDAD) + floatval($paginador2->CANTIDAD);


          return $resultado;
          
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

      //return 0;
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
