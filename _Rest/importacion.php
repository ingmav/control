<?php
session_start();
include("../clases/conexion.php");


date_default_timezone_set('America/Mexico_City');
$conection = new conexion_nexos(2);

$query = "SELECT MAX(FOLIO) as folio FROM DOCTOS_VE WHERE TIPO_DOCTO='C' AND ESTATUS!='C'";
$result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
$folio = 0;
while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
    $folio = str_pad(($row->FOLIO + 1), 9, "0", STR_PAD_LEFT);
}
$data = $_POST;
$cliente =  $data['cliente'];

//$folio_respuesta = 0;

$query_direccion = "select FIRST 1 DIR_CLI_ID FROM DIRS_CLIENTES WHERE CLIENTE_ID=".$cliente['cliente_id']." AND ES_DIR_PPAL='S'";
$result_direccion = ibase_query($conection->getConexion(), $query_direccion) or die(ibase_errmsg());

while ($row_direccion = ibase_fetch_object ($result_direccion, IBASE_TEXT)){
    $direccion = $row_direccion;
}

$query_articulos = "select ARTICULO_ID from articulos where unidad_venta in ('M2', 'Lamina', 'ML', 'Metro')";
$result_articulos = ibase_query($conection->getConexion(), $query_articulos) or die(ibase_errmsg());

$articulos = array();
while ($row_articulos = ibase_fetch_object ($result_articulos, IBASE_TEXT)){
    $articulos[$row_articulos->ARTICULO_ID] = 1;
}

//print_r($articulos);
$letra_descuento = '';
$monto_descuento = '';
$pctje_descuento = 0;
if($cliente['tipo_descuento'] == 0)
{
    $letra_descuento = 'P';
    $monto_descuento = 0;
}else if($cliente['tipo_descuento'] == 1){
    $letra_descuento = 'P';
    $monto_descuento = $cliente['tipo_descuento_gral_importe'];
    $pctje_descuento = $cliente['descuento_general'];
    //$monto_descuento = $cliente['descuento_general'];
    //$pctje_descuento = ($cliente['descuento_general_monto'] / $monto_descuento);
}else if($cliente['tipo_descuento'] == 2){
    $letra_descuento = 'i';
    $monto_descuento = $cliente['tipo_descuento_gral_importe'];
    $pctje_descuento = $cliente['descuento_general'];
    //$monto_descuento = $cliente['descuento_general'];
    //$pctje_descuento = ($cliente['descuento_general_monto'] / $monto_descuento);
}


$transaction = ibase_trans( IBASE_DEFAULT, $conexion ); 

$query_insert_cotizacion = "INSERT INTO DOCTOS_VE(DOCTO_VE_ID, TIPO_DOCTO, SUBTIPO_DOCTO, FOLIO,FECHA,HORA,CLAVE_CLIENTE,CLIENTE_ID,DIR_CLI_ID,DIR_CONSIG_ID,MONEDA_ID,TIPO_CAMBIO,TIPO_DSCTO,DSCTO_PCTJE,DSCTO_IMPORTE,ESTATUS,APLICADO,DESCRIPCION, IMPORTE_NETO,FLETES,OTROS_CARGOS,TOTAL_IMPUESTOS,TOTAL_RETENCIONES,TOTAL_ANTICIPOS,PESO_EMBARQUE,FORMA_EMITIDA, CONTABILIZADO, ACREDITAR_CXC, SISTEMA_ORIGEN,COND_PAGO_ID, PCTJE_DSCTO_PPAG,VENDEDOR_ID, PCTJE_COMIS, VIA_EMBARQUE_ID,IMPORTE_COBRO, USUARIO_CREADOR, ES_CFD,ENVIADO, FECHA_HORA_ENVIO, CFD_ENVIO_ESPECIAL, CFDI_CERTIFICADO,FECHA_HORA_CREACION,USUARIO_ULT_MODIF,FECHA_HORA_ULT_MODIF,CARGAR_SUN) 
values(GEN_ID(ID_DOCTOS,1), 'C', 'N', '".$folio."', '".date('d.m.Y')."' , '".date('H:i')."', '".$cliente['clave_cliente']."', ".$cliente['cliente_id'].", 
".$direccion->DIR_CLI_ID.", ".$direccion->DIR_CLI_ID.", 1, 0, '".$letra_descuento."', ".$pctje_descuento.",".$monto_descuento.", 'P', 'S', '".$cliente['descripcion']."', ".$cliente['subtotal'].", 0, 0, ". $cliente['iva'].", 0, 0, 0, 'N', 'N', 'N', 'VE', 302, 0, 1017, 0, 1014, 0, 'SYSDBA', 'N', 'N', NULL, 'N', 'N', '".date('d.m.Y H:i')."', 'SYSDBA', '23.11.2020 12:34' ,'S')";
//echo  json_encode(array("estatus"=> 1, "texto"=> "Error al importar la cotización", "folio"=> $folio));
//echo "\n";
$result_INSERT = ibase_query($conection->getConexion(), $query_insert_cotizacion) or die(ibase_errmsg());
if($result_INSERT)
{
    $query = "SELECT MAX(DOCTO_VE_ID) as DOCTO_VE_ID FROM DOCTOS_VE WHERE TIPO_DOCTO='C' AND ESTATUS!='C'";
    $result = ibase_query($conection->getConexion(), $query) or die(ibase_errmsg());
    $id = 0;
    while ($row = ibase_fetch_object ($result, IBASE_TEXT)){
        $id = $row->DOCTO_VE_ID;
    }

    $datos =  $data['datos'];
    //print_r($datos);
    $numero = count($datos);
    $contador = 0;
    foreach ($datos as $key => $value) {
        //echo $value['clave_articulo'];
        $nota = "";
        $cabecara_nota = "";
        if(array_key_exists($value['articulo_id'], $articulos))
        {
            $cabecera_nota = ", NOTAS";
            $nota = ", '".IntVal($value['cantidad']) ." Unidades (Base: ".$value['base']." X Altura: ".$value['altura'].")'";
        }
        $importe_neto = $value['total_monto_descuento'] - $value['iva'];
        $query_insert_cotizacion_detalles = "INSERT INTO DOCTOS_VE_DET(
            DOCTO_VE_DET_ID, 
            DOCTO_VE_ID, 
            CLAVE_ARTICULO, 
            ARTICULO_ID, 
            UNIDADES, 
            UNIDADES_COMPROM, 
            UNIDADES_SURT_DEV, 
            UNIDADES_A_SURTIR, 
            PRECIO_UNITARIO, 
            PCTJE_DSCTO, 
            DSCTO_ART, 
            PCTJE_DSCTO_CLI, 
            DSCTO_EXTRA, 
            PCTJE_DSCTO_VOL,
    PCTJE_DSCTO_PROMO, PRECIO_TOTAL_NETO, PCTJE_COMIS, ROL".$cabecera_nota.", POSICION) 
    values(GEN_ID(ID_DOCTOS,1), ".$id.", '".$value['clave_articulo']."', ".$value['articulo_id'].", ".$value['total'].", 0, 0, 0, ".$value['precio_unitario'].", ".$value['descuento_porcentaje'].", 
    ".$value['descuento_monto'].", ".$value['descuento_porcentaje']." , ".$value['descuento_general'].", 0, 0, ".$importe_neto.", 0, 'N'".$nota.", ".($key+1).")";
    $insert_articulos = ibase_query($conection->getConexion(), $query_insert_cotizacion_detalles) or die(ibase_errmsg());
        if($insert_articulos)
        {
            $contador++;
        }else{
            ibase_rollback($transaction);	
        }
    }
    if($contador == $numero)
    {
        echo  json_encode(array("estatus"=> 1, "texto"=> "Se Ha cargado Correctamente la Cotizacion", "folio"=> $folio));
        ibase_commit($transaction);
    }
}else{
    echo  json_encode(array("estatus"=> 0, "texto"=> "Error al importar la cotización"));
    ibase_rollback($transaction);	
}


?>