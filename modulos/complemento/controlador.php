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

if($action == "index")
{
	$arreglo = array();
	$arreglo_final = array();

	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare('SELECT * FROM ms_complemento_liverpool where borrado_al is null order by id');
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    foreach ($resultado as $row) {
	    	$count = count($arreglo);
			$arreglo[$count]['INDEX'] = $row['id'];
			$arreglo[$count]['PEDIDO'] = utf8_encode($row['no_pedido']);
			$arreglo[$count]['FACTURA'] = utf8_encode($row['no_factura']);
			$arreglo[$count]['RECIBO'] = utf8_encode($row['no_contra_recibo']);
			$arreglo[$count]['MONTO'] = utf8_encode($row['monto']);
			$arreglo[$count]['GLN_LIVERPOOL'] = utf8_encode($row['gln_liverpool']);
			$arreglo[$count]['GLN_NEXPRINT'] = utf8_encode($row['gln_nexprint']);
			$arreglo[$count]['ARCHIVO'] = utf8_encode($row['nombre_archivo']);
	    }


	    echo json_encode($arreglo);

    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($action == "cargar_configuracion")
{
	$arreglo = array();
	$arreglo_final = array();

	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare('SELECT * FROM ms_config_liverpool where borrado_al is null order by id');
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    foreach ($resultado as $row) {
	    	$count = count($arreglo);
			$arreglo[$count]['INDEX'] = $row['id'];
			$arreglo[$count]['NO_PROVEEDOR'] = utf8_encode($row['no_proveedor']);
			$arreglo[$count]['GLN_LIVERPOOL'] = utf8_encode($row['gln_liverpool']);
			$arreglo[$count]['GLN_PROVEEDOR'] = utf8_encode($row['gln_nexprint']);
			$arreglo[$count]['NO_DEPARTAMETNO'] = utf8_encode($row['no_departamento']);

	    }


	    echo json_encode($arreglo);

    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($action == "actualiza_configuracion")
{


	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$upd = $conexion->prepare("UPDATE ms_config_liverpool SET no_proveedor=:proveedor, gln_liverpool=:gln_liverpool, gln_nexprint=:gln_nexprint, no_departamento=:no_departamento where 1=1");
		$upd->bindParam(':proveedor', 		$_GET['no_proveedor']);
		$upd->bindParam(':gln_liverpool', 	$_GET['gln_liverpool']);
		$upd->bindParam(':gln_nexprint', 	$_GET['gln_nexprint']);
		$upd->bindParam(':no_departamento', $_GET['no_departamento']);
		$upd->execute();

	    echo json_encode(true);
    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($action == "eliminar_registro")
{

	try {
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$upd = $conexion->prepare("UPDATE ms_complemento_liverpool SET borrado_al=:date_timer where id=".$_GET['id']);
		$upd->bindParam(':date_timer', 		date("Y-m-d H:i:s"));
		$upd->execute();

	    echo json_encode(true);
    	exit();
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

if($_POST['accion'] == "guardaXml")
{
	try {

		//Valores de configuracion
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare('SELECT * FROM ms_config_liverpool where borrado_al is null order by id');
	    $sql->execute();
	    $resultado = $sql->fetchAll();
	    $arreglo = Array();
	    foreach ($resultado as $row) {
	    	$count = count($arreglo);
			$arreglo[$count]['INDEX'] = $row['id'];
			$arreglo[$count]['GLN_LIVERPOOL'] = utf8_encode($row['gln_liverpool']);
			$arreglo[$count]['GLN_NEXPRINT'] = utf8_encode($row['gln_nexprint']);
			$arreglo[$count]['NO_PROVEEDOR'] = utf8_encode($row['no_proveedor']);
			$arreglo[$count]['NO_DEPARTAMENTO'] = utf8_encode($row['no_departamento']);
	    }
	    //fin valores de configuracion

		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = $conexion->prepare("INSERT INTO ms_complemento_liverpool(no_pedido, no_contra_recibo, no_departamento, no_factura, monto, gln_liverpool, gln_nexprint, nombre_archivo) VALUES(:pedido, :recibo, :departamento, :factura, :monto, :liverpool, :nexprint, :nombre_archivo)");
	    //$datos
	    if($_FILES['archivo']['size'] > 0)
	    {

	    	

		    $factura 		= "0";
		    $monto 			= 0.00;
		    $gln_liverpool 	= $arreglo[0]['GLN_LIVERPOOL'];
		    $gln_nexprint 	= $arreglo[0]['GLN_NEXPRINT'];
		    $nombre_archivo = $_FILES['archivo']['name'];


		    $sql->bindParam(':pedido', 			strtoupper($_POST['pedido']));
	    	$sql->bindParam(':recibo', 			$_POST['recibo']);
	    	$sql->bindParam(':departamento', 	$_POST['departamento']);	
	    	$sql->bindParam(':factura', 		$factura);	
	    	$sql->bindParam(':monto', 			$monto);	
	    	$sql->bindParam(':liverpool', 		$gln_liverpool);	
	    	$sql->bindParam(':nexprint', 		$gln_nexprint);	
	    	$sql->bindParam(':nombre_archivo', 	$nombre_archivo);	

	    	$arreglo[0]['PEDIDO'] = $_POST['pedido'];
	    	$arreglo[0]['RECIBO'] = $_POST['recibo'];
	    	$arreglo[0]['DEPARTAMENTO'] = $_POST['departamento'];

		    $sql->execute();

		    $last_id = $conexion->lastInsertId(); 
		    
		    if($last_id > 0)
		    {
			    //Guardar original
			    move_uploaded_file($_FILES['archivo']['tmp_name'], 'doctos\\original\\'.$last_id.".xml");
			    //
		    	//$last_id = 9;
		    	$resultado = crear_xml($last_id, $arreglo);
			    if($resultado[0] == true)
			    {
			    	$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    			$upd = $conexion->prepare("UPDATE ms_complemento_liverpool SET no_factura=:factura, monto=:monto where id=".$last_id);
	    			$upd->bindParam(':factura', 		$resultado[1]['Folio']);
	    			$upd->bindParam(':monto', 			$resultado[1]['Total']);
	    			$upd->execute();

	    			$resultado[1]['id'] = $last_id;
	    			$resultado[1]['nombre_archivo'] = $nombre_archivo;
	    			
	    			echo  json_encode($resultado[1]);		
			    }else{
			    	$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    			$upd = $conexion->prepare("UPDATE ms_complemento_liverpool SET borrado_al=:borrado where id=".$last_id);
	    			$upd->bindParam(':borrado', 		date("Y-m-d H:i:s"));
	    			$upd->execute();
	    			echo  json_encode($resultado[1]);
			    }	
			}
		    

		    //echo json_encode(array("true"));

	    	//exit();
	    }
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	
}

function crear_xml($ruta, $arreglo)
{
	try{
	$id = $ruta;
	$ruta = 'doctos\\original\\'.$ruta.".xml";
	$xml        = new SimpleXMLElement ($ruta,null,true);

	$namespaces = $xml->getDocNamespaces();
	// aquí le decimos que busque el nodo padre Comprobante y dentro de el busque el nodo Emisor para
	// así encontrar los atributos.
	$nodo_principal;
	$nodo_emisor;
	$nodo_receptor;
	$nodo_conceptos;
	$nodo_traslado;
	$nodo_impuestos;
	$nodo_impuestos_detalles;
	$nodo_timbre;
	foreach ($xml->xpath('//cfdi:Comprobante') as $Nodo){  // SECCION EMISOR
		$nodo_principal = $Nodo;
	} 


	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Nodo){  // SECCION EMISOR
		$nodo_emisor = $Nodo;
	} 

	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Nodo){  // SECCION EMISOR
		$nodo_receptor = $Nodo;
	} 

	$index = 0;
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Nodo){  // SECCION EMISOR
		$nodo_conceptos[$index] = $Nodo;
		$index++;
	}
	$index = 0;
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Nodo){  // SECCION EMISOR
		$nodo_traslado[$index] = $Nodo;
		$index++;
	} 

	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Nodo){  // SECCION EMISOR
		$nodo_impuestos = $Nodo;
	} 

	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Nodo){  // SECCION EMISOR
		$nodo_impuestos_detalles = $Nodo;
	} 

	//Obtencion del timbre fiscal
	 $dom = new DOMDocument('1.0','utf-8'); // Creamos el Objeto DOM
     $dom->load($ruta); // Definimos la ruta de nuestro XML
     // Recorremos el XML Tag por Tag para encontrar los elementos buscados
     // Obtenemos el Machote(Estructura) del XML desde la web de SAT 
     foreach ($dom->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital', '*') as $elemento) {

     	$nodo_timbre['xmlns:tfd']				= $elemento->getAttribute('xmlns:tfd');
     	$nodo_timbre['xsi:schemaLocation']		= $elemento->getAttribute('xsi:schemaLocation');
     	$nodo_timbre['SelloSAT']				= $elemento->getAttribute('SelloSAT');
     	$nodo_timbre['NoCertificadoSAT']		= $elemento->getAttribute('NoCertificadoSAT');
     	$nodo_timbre['SelloCFD']				= $elemento->getAttribute('SelloCFD');
     	$nodo_timbre['RfcProvCertif']			= $elemento->getAttribute('RfcProvCertif');
     	$nodo_timbre['FechaTimbrado']			= $elemento->getAttribute('FechaTimbrado');
     	$nodo_timbre['UUID']					= $elemento->getAttribute('UUID');
     	$nodo_timbre['Version']					= $elemento->getAttribute('Version');
     }
	//Fin obtencion del timbre fiscal
	
	$creacion_nodos = creacion_xml($id, $nodo_principal, $nodo_emisor, $nodo_receptor, $nodo_conceptos, $nodo_traslado, $nodo_impuestos, $nodo_impuestos_detalles, $nodo_timbre, $arreglo);
	return $creacion_nodos;
}catch(Exception $e)
{
	echo $e;
}


}

function creacion_xml($id, $nodo, $emisor, $receptor, $conceptos, $traslado, $impuesto, $impuesto_detalle, $timbre, $detallista)
{
	
	try{
		$objetoXML = new XMLWriter();
		// Estructura básica del XML
		$objetoXML->openURI('C:\wamp\www\produccion\modulos\complemento\doctos\salida\\'.$id.".xml");
		$objetoXML->setIndent(true);
		$objetoXML->setIndentString("\t");
		$objetoXML->startDocument('1.0', 'utf-8');
		// Inicio del nodo raíz
		$objetoXML->startElement("cfdi:Comprobante");
	 
		$objetoXML->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
		$objetoXML->writeAttribute("xmlns:detallista", "http://www.sat.gob.mx/detallista");
		$objetoXML->writeAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd");
		$objetoXML->writeAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/3");

		$objetoXML->writeAttribute("Version", $nodo['Version']);
		$objetoXML->writeAttribute("Serie", $nodo['Serie']);
		$objetoXML->writeAttribute("Folio", $nodo['Folio']);
		$objetoXML->writeAttribute("Fecha", $nodo['Fecha']);

		$objetoXML->writeAttribute("Sello", $nodo['Sello']);
		$objetoXML->writeAttribute("FormaPago", $nodo['FormaPago']);
		$objetoXML->writeAttribute("NoCertificado", $nodo['NoCertificado']);
		$objetoXML->writeAttribute("Certificado", $nodo['Certificado']);

		
		$objetoXML->writeAttribute("CondicionesDePago", $nodo['CondicionesDePago']);
		$objetoXML->writeAttribute("SubTotal", $nodo['SubTotal']);
		$objetoXML->writeAttribute("Moneda", $nodo['Moneda']);
		$objetoXML->writeAttribute("Total", $nodo['Total']);
		$objetoXML->writeAttribute("TipoDeComprobante", $nodo['TipoDeComprobante']);
		$objetoXML->writeAttribute("MetodoPago", $nodo['MetodoPago']);
		$objetoXML->writeAttribute("LugarExpedicion", $nodo['LugarExpedicion']);

		//Nodo Emisor
			$objetoXML->startElement("cfdi:Emisor");
			$objetoXML->writeAttribute("Rfc", $emisor['Rfc']);
			$objetoXML->writeAttribute("Nombre", $emisor['Nombre']);
			$objetoXML->writeAttribute("RegimenFiscal", $emisor['RegimenFiscal']);
			$objetoXML->endElement(); 
		//Fin nodo Emisor

		//Nodo Receptor
			$objetoXML->startElement("cfdi:Receptor");
			$objetoXML->writeAttribute("Rfc", $receptor['Rfc']);
			$objetoXML->writeAttribute("Nombre", $receptor['Nombre']);
			$objetoXML->writeAttribute("UsoCFDI", $receptor['UsoCFDI']);
			$objetoXML->endElement(); 
		//Fin nodo Receptor	

		//Nodo Conceptos
			$objetoXML->startElement("cfdi:Conceptos");
			$index = 0;
			foreach ($conceptos as $key => $value) {
				$objetoXML->startElement("cfdi:Concepto");
					$objetoXML->writeAttribute("ClaveProdServ", $value['ClaveProdServ']);
					$objetoXML->writeAttribute("NoIdentificacion", $value['NoIdentificacion']);
					$objetoXML->writeAttribute("Cantidad", $value['Cantidad']);
					$objetoXML->writeAttribute("ClaveUnidad", $value['ClaveUnidad']);
					$objetoXML->writeAttribute("Unidad", $value['Unidad']);
					$objetoXML->writeAttribute("Descripcion", $value['Descripcion']);
					$objetoXML->writeAttribute("ValorUnitario", $value['ValorUnitario']);
					$objetoXML->writeAttribute("Importe", $value['Importe']);

					$objetoXML->startElement("cfdi:Impuestos");
						$objetoXML->startElement("cfdi:Traslados");
							$objetoXML->startElement("cfdi:Traslado");
								$objetoXML->writeAttribute("Base", $traslado[$index]['Base']);
								$objetoXML->writeAttribute("Impuesto", $traslado[$index]['Impuesto']);
								$objetoXML->writeAttribute("TipoFactor", $traslado[$index]['TipoFactor']);
								$objetoXML->writeAttribute("TasaOCuota", $traslado[$index]['TasaOCuota']);
								$objetoXML->writeAttribute("Importe", $traslado[$index]['Importe']);
							$objetoXML->endElement(); 
						$objetoXML->endElement(); 
					$objetoXML->endElement(); 
				$objetoXML->endElement(); 
				$index++;
			}
			 
			$objetoXML->endElement(); 
		//Fin nodo Conceptos		

		//Nodo Impuestos
		$objetoXML->startElement("cfdi:Impuestos");
		$objetoXML->writeAttribute("TotalImpuestosTrasladados", $impuesto_detalle['Importe']);
			$objetoXML->startElement("cfdi:Traslados");
				$objetoXML->startElement("cfdi:Traslado");
					$objetoXML->writeAttribute("Impuesto", $impuesto_detalle['Impuesto']);
					$objetoXML->writeAttribute("TipoFactor", $impuesto_detalle['TipoFactor']);
					$objetoXML->writeAttribute("TasaOCuota", $impuesto_detalle['TasaOCuota']);
					$objetoXML->writeAttribute("Importe", $impuesto_detalle['Importe']);
			$objetoXML->endElement();
			$objetoXML->endElement(); 
		$objetoXML->endElement(); 
		//Fin nodo Impuestos	

		

		//Nodo Timbre
		$objetoXML->startElement("cfdi:Complemento");

			//Nodo Detallista
			$objetoXML->startElement("detallista:detallista");
			$objetoXML->writeAttribute("contentVersion", "1.3.1");
			$objetoXML->writeAttribute("documentStatus", "ORIGINAL");
			$objetoXML->writeAttribute("documentStructureVersion", "AMC8.1");
			$objetoXML->writeAttribute("type", "SimpleInvoiceType");

				$objetoXML->startElement("detallista:requestForPaymentIdentification");
					$objetoXML->startElement("detallista:entityType");
						$objetoXML->text("INVOICE");
					$objetoXML->endElement(); 
				$objetoXML->endElement();

				// transformar texto
				$numero = $nodo['Total'];
				$numero_decimal = explode(".",$numero);
				
				$letras = new NumberToLetterConverter();
				$texto_letras = $letras->to_word($numero_decimal[0], 'MXN');

				//
				$objetoXML->startElement("detallista:specialInstruction");
					$objetoXML->writeAttribute("code", "ZZZ");
					$objetoXML->startElement("detallista:text");
						$objetoXML->text($texto_letras.$numero_decimal[1]."/100 MN");
					$objetoXML->endElement(); 
				$objetoXML->endElement();

				$objetoXML->startElement("detallista:orderIdentification");
					$objetoXML->startElement("detallista:referenceIdentification");
						$objetoXML->writeAttribute("type", "ZZZ");
						$objetoXML->text($detallista[0]['PEDIDO']);
					$objetoXML->endElement(); 
				$objetoXML->endElement();

				$objetoXML->startElement("detallista:AdditionalInformation");
					$objetoXML->startElement("detallista:referenceIdentification");
						$objetoXML->writeAttribute("type", "IV");
						$objetoXML->text("0");
					$objetoXML->endElement(); 
				$objetoXML->endElement(); 

				$objetoXML->startElement("detallista:DeliveryNote");
					$objetoXML->startElement("detallista:referenceIdentification");
						$objetoXML->text($detallista[0]['RECIBO']);
					$objetoXML->endElement(); 
				$objetoXML->endElement(); 

				$objetoXML->startElement("detallista:buyer");
					$objetoXML->startElement("detallista:gln");
						$objetoXML->text($detallista[0]['GLN_LIVERPOOL']);
					$objetoXML->endElement(); 
					$objetoXML->startElement("detallista:text");
						$objetoXML->text($detallista[0]['NO_DEPARTAMENTO']);
					$objetoXML->endElement(); 
				$objetoXML->endElement(); 

				$objetoXML->startElement("detallista:seller");
					$objetoXML->startElement("detallista:gln");
						$objetoXML->text($detallista[0]['GLN_NEXPRINT']);
					$objetoXML->endElement(); 
					$objetoXML->startElement("detallista:alternatePartyIdentification");
						$objetoXML->writeAttribute("type", "SELLER_ASSIGNED_IDENTIFIER_FOR_A_PARTY");
						$objetoXML->text($detallista[0]['NO_PROVEEDOR']);
					$objetoXML->endElement(); 
				$objetoXML->endElement(); 
			
			$objetoXML->endElement(); 
			//Fin nodo Impuestos	

			$objetoXML->startElement("tfd:TimbreFiscalDigital");
				$objetoXML->writeAttribute("xmlns:tfd", $timbre['xmlns:tfd']);
				$objetoXML->writeAttribute("xsi:schemaLocation", $timbre['xsi:schemaLocation']);
				$objetoXML->writeAttribute("SelloSAT", $timbre['SelloSAT']);
				$objetoXML->writeAttribute("NoCertificadoSAT", $timbre['NoCertificadoSAT']);
				$objetoXML->writeAttribute("SelloCFD", $timbre['SelloCFD']);
				$objetoXML->writeAttribute("RfcProvCertif", $timbre['RfcProvCertif']);
				$objetoXML->writeAttribute("FechaTimbrado", $timbre['FechaTimbrado']);
				$objetoXML->writeAttribute("UUID", $timbre['UUID']);
				$objetoXML->writeAttribute("Version", $timbre['Version']);
			$objetoXML->endElement(); 
		$objetoXML->endElement(); 
		//Fin nodo Timbre	
		
		$objetoXML->endElement(); 
		$objetoXML->endDocument(); // Final del documento

		return array(true, $nodo);
		//rename ("./"$id.".xml","./doctos//".$id.".xml");
	}catch(Exception $e)
	{
		echo $e;
	}
	
}



class NumberToLetterConverter {
  private $UNIDADES = array(
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
  );
  private $DECENAS = array(
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
  );
  private $CENTENAS = array(
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
  );
  private $MONEDAS = array(
    array('country' => 'Colombia', 'currency' => 'COP', 'singular' => 'PESO COLOMBIANO', 'plural' => 'PESOS COLOMBIANOS', 'symbol', '$'),
    array('country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
    array('country' => 'El Salvador', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
    array('country' => 'Europa', 'currency' => 'EUR', 'singular' => 'EURO', 'plural' => 'EUROS', 'symbol', '€'),
    array('country' => 'México', 'currency' => 'MXN', 'singular' => '', 'plural' => '', 'symbol', '$'),
    array('country' => 'Perú', 'currency' => 'PEN', 'singular' => 'NUEVO SOL', 'plural' => 'NUEVOS SOLES', 'symbol', 'S/'),
    array('country' => 'Reino Unido', 'currency' => 'GBP', 'singular' => 'LIBRA', 'plural' => 'LIBRAS', 'symbol', '£'),
    array('country' => 'Argentina', 'currency' => 'ARS', 'singular' => 'PESO', 'plural' => 'PESOS', 'symbol', '$')
  );
    private $separator = ',';
    private $decimal_mark = '.';
    private $glue = ' CON ';
    /**
     * Evalua si el número contiene separadores o decimales
     * formatea y ejecuta la función conversora
     * @param $number número a convertir
     * @param $miMoneda clave de la moneda
     * @return string completo
     */
    public function to_word($number, $miMoneda = null) {
        if (strpos($number, $this->decimal_mark) === FALSE) {
          $convertedNumber = array(
            $this->convertNumber($number, $miMoneda, 'entero')
          );
        } else {
          $number = explode($this->decimal_mark, str_replace($this->separator, '', trim($number)));
          $convertedNumber = array(
            $this->convertNumber($number[0], $miMoneda, 'entero'),
            $this->convertNumber($number[1], $miMoneda, 'decimal'),
          );
        }
        return implode($this->glue, array_filter($convertedNumber));
    }
    /**
     * Convierte número a letras
     * @param $number
     * @param $miMoneda
     * @param $type tipo de dígito (entero/decimal)
     * @return $converted string convertido
     */
    private function convertNumber($number, $miMoneda = null, $type) {   
        
        $converted = '';
        if ($miMoneda !== null) {
            try {
                
                $moneda = array_filter($this->MONEDAS, function($m) use ($miMoneda) {
                    return ($m['currency'] == $miMoneda);
                });
                $moneda = array_values($moneda);
                if (count($moneda) <= 0) {
                    throw new Exception("Tipo de moneda inválido");
                    return;
                }
                ($number < 2 ? $moneda = $moneda[0]['singular'] : $moneda = $moneda[0]['plural']);
            } catch (Exception $e) {
                echo $e->getMessage();
                return;
            }
        }else{
            $moneda = '';
        }
        if (($number < 0) || ($number > 999999999)) {
            return false;
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
            }
        }
        
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', $this->convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', $this->convertGroup($cientos));
            }
        }
        $converted .= $moneda;
        return $converted;
    }
    /**
     * Define el tipo de representación decimal (centenas/millares/millones)
     * @param $n
     * @return $output
     */
    private function convertGroup($n) {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = $this->CENTENAS[$n[0] - 1];   
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= $this->UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}
/*
f($_POST["accion"] == "guardaXml")
	{
		$conection = new conexion_nexos(2);

		$consulta = "";
        
		$campos = array("ID", "NO_PROVEEDOR", "GLN_PROVEEDOR", "GLN_LIVERPOOL");
		
		$join = array();
		
		$condicionales = " ";
		
		$order = array();
		
		$json = $conection->select_table($campos, "MS_CONFIG_LIVERPOOL", $join, $condicionales, $order, 1);

		$campos = array("NO_PEDIDO", "NO_CONTRA_RECIBO", "NO_DEPARTAMENTO", "NO_FACTURA", "MONTO", "GLN_LIVERPOOL", "GLN_NEXPRINT");
		$valores = array("'".$_POST['add_pedido']."'", "'".$_POST['add_recibo']."'", "'".$_POST['add_departamento']."'", '1', 1000.00, "'0001'", "'013'");

		$json = $conection->insert_table($campos, "MS_COMPLEMENTO_LIVERPOOL", $valores);

		$obj = (object) $json;
		echo json_encode($obj);

		$conection = null;
	}
include("../../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	
	if($_POST["accion"] == "index")
	{
		$conection = new conexion_nexos(2);

        $consulta = "";
        
		$campos = array("ID", "NO_PEDIDO", "NO_CONTRA_RECIBO", "NO_DEPARTAMENTO", "NO_FACTURA","MONTO");
		
		$join = array();
		
		$condicionales = " ";
		
		$order = array();
		
		$json = $conection->select_table($campos, "MS_COMPLEMENTO_LIVERPOOL", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
		$conection = null;
	}

	if($_POST["accion"] == "carga_configuracion")
	{
		$conection = new conexion_nexos(2);

        $consulta = "";
        
		$campos = array("ID", "NO_PROVEEDOR", "GLN_PROVEEDOR", "GLN_LIVERPOOL");
		
		$join = array();
		
		$condicionales = " ";
		
		$order = array();
		
		$json = $conection->select_table($campos, "MS_CONFIG_LIVERPOOL", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
		$conection = null;
	}

	if($_POST["accion"] == "actualiza_configuracion")
	{
		$conection = new conexion_nexos(2);

        $campos = array("NO_PROVEEDOR", "GLN_PROVEEDOR", "GLN_LIVERPOOL");
		$valores = array("'".$_POST['no_proveedor']."'", "'".$_POST['gln_nexprint']."'", "'".$_POST['gln_liverpool']."'");
		$id = "1=1";
		
		$json = $conection->update_table($campos, "MS_CONFIG_LIVERPOOL", $valores, $id);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);

		$conection = null;
	}

	i*/
?>