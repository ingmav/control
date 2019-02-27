<?php
session_start();
	include("../clases/conexion.php");
	
	date_default_timezone_set('America/Mexico_City');
	
	$conection = new conexion_nexos();
	$conexion = $conection->conexion_nexos();
	
	if($_POST["accion"] == "index")
	{
		$campos = array("COTIZACIONES.ID", "COTIZACIONES.FECHA", "COTIZACIONES.NOMBRECLIENTE", "COTIZACIONES.DESCRIPCION","COTIZACIONES.SOLICITANTE", "OPERADOR.ALIAS","COTIZACIONESESTATUS.COTIZACIONDESCRIPCION");
		
		$join = array("COTIZACIONESESTATUS","=", "COTIZACIONESESTATUS.ID", "COTIZACIONES.ESTATUS","LEFT",
                      "OPERADOR","=", "OPERADOR.ID", "COTIZACIONES.IDOPERADOR","LEFT");

        $consulta = "";
        if($_POST['clientefiltro']!="")
            $consulta .=" and NOMBRECLIENTE LIKE '%".$_POST['clientefiltro']."%'";

        if($_POST['estatusfiltro']!="")
            $consulta .=" and ESTATUS='".$_POST['estatusfiltro']."'";

        if($_POST['operadorfiltro']!="0")
            $consulta .=" and IDOPERADOR='".$_POST['operadorfiltro']."'";

		$condicionales = " ".$consulta;

		
		$order = array("COTIZACIONES.FECHA DESC", "COTIZACIONES.ID DESC");
		
		$json = $conection->select_table_advanced($campos, "COTIZACIONES", $join, $condicionales, $order, 1, $_POST['page']);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "save")
	{
		$transaction = ibase_trans( IBASE_DEFAULT, $conection );  
		try{
		$campos = array("NOMBRECLIENTE", "DESCRIPCION", "FECHA", "IDOPERADOR", "SOLICITANTE", "ESTATUS");
		$valores = array("'".strtoupper(utf8_decode($_POST['cliente']))."'", "'".strtoupper(utf8_decode($_POST['descripcion']))."'", "'".$_POST['fechaCotizacion']."'", "'".strtoupper(utf8_decode($_POST['operador']))."'", "'".strtoupper(utf8_decode($_POST['solicitante']))."'", $_POST['estatus']);

		$json = $conection->insert_table($campos, "COTIZACIONES", $valores);
        }catch(Exception $e)
        {
               $json = array("data"=>$e);
        }

        //enviar_correo();
		/*$obj = (object) $json;
		echo json_encode($obj);*/
        if($_POST['email'] != "")
        {
            $condicionales = " AND ID=".$_SESSION['IDUSUARIO'];
            $asesor = $conection->select_table(array('EMAIL'), "OPERADOR", array(), $condicionales, "", 1);

            $texto = "<table>";
            $texto .= "<tr><td>CLIENTE: </td><td>".$_POST['cliente']."</td></tr>";
            $texto .= "<tr><td>SOLICITANTE: </td><td>".$_POST['solicitante']."</td></tr>";
            $texto .= "<tr><td>DESCRIPCION: </td><td>".$_POST['descripcion']."</td></tr>";
            $texto .= "<tr><td>FECHA: </td><td>".$_POST['fechaCotizacion']."</td></tr>";
            $texto .= "</table>";

            $resultado = enviar_correo($texto, $asesor, $_POST['email']);
            if($resultado == 1)
            {
                $respuesta['respuesta'] = "SE HA ENVIADO CORRECTAMENTE EL CORREO";
                $respuesta['estatusCorreo'] = 1;
                ibase_commit($transaction);
            }
            else{
            	ibase_rollback($transaction);  
                $respuesta['respuesta'] = "NO SE HA ENVIADO CORRECTAMENTE EL CORREO";
                $respuesta['estatusCorreo'] = 0;
            }
        }else
        {
        	ibase_commit($transaction);
            $respuesta = array("data"=>"Correcto");
        }
        $obj = (object) $respuesta;
        echo json_encode($obj);

	}
	
	if($_POST["accion"] == "update")
	{
		
		$campos = array("NOMBRECLIENTE", "DESCRIPCION", "FECHA", "IDOPERADOR", "SOLICITANTE", "ESTATUS");
		$valores = array("'".strtoupper(utf8_decode($_POST['cliente']))."'", "'".strtoupper(utf8_decode($_POST['descripcion']))."'", "'".$_POST['fechaCotizacion']."'", "'".strtoupper(utf8_decode($_POST['operador']))."'", "'".strtoupper(utf8_decode($_POST['solicitante']))."'", $_POST['estatus']);
		$id = "ID = ".$_POST['id'];
		
		$json = $conection->update_table($campos, "COTIZACIONES", $valores, $id);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "eliminar")
	{
		if(count($_POST['id']) > 0)
		{
			$json = $conection->delete_table("COTIZACIONES", "ID IN", $_POST['id']);
			//print_r($json);
			$obj = (object) $json;
			echo json_encode($obj);
		}
	}
	
	if($_POST["accion"] == "modificar")
	{
		$campos = array("ID", "NOMBRECLIENTE", "DESCRIPCION", "FECHA", "IDOPERADOR", "SOLICITANTE", "ESTATUS");
		
		$join = array();
		$condicionales = " AND ID=".$_POST['id'][0];
		$order = array("FECHA");
		
		$json = $conection->select_table($campos, "COTIZACIONES", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST['accion'] == "cargaOperador")
	{
		$campos = array("OPERADOR.ID", "OPERADOR.ALIAS");
		
		$join = array("OPERADORDEPARTAMENTO","=", "OPERADOR.ID", "OPERADORDEPARTAMENTO.IDOPERADOR");

		$order = array("OPERADOR.ALIAS");
		
		$condicionales = " AND OPERADOR.ESTADO=0 AND OPERADORDEPARTAMENTO.IDDEPARTAMENTO=11 ";
		
		$json = $conection->select_table($campos, "OPERADOR", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST['accion'] == "cargaEstatus")
	{
		$campos = array("COTIZACIONESESTATUS.ID", "COTIZACIONESESTATUS.COTIZACIONDESCRIPCION");
		
		$join = array();

		$order = array("COTIZACIONESESTATUS.ID");
				
		$json = $conection->select_table($campos, "COTIZACIONESESTATUS", $join, $condicionales, $order, 1);
		//print_r($json);
		$obj = (object) $json;
		echo json_encode($obj);
	}
	
	if($_POST["accion"] == "counter")
	{
	
		$join = array();
        $consulta = "";
        if($_POST['clientefiltro']!="")
            $consulta .=" and NOMBRECLIENTE LIKE '%".$_POST['clientefiltro']."%'";

        if($_POST['estatusfiltro']!="")
            $consulta .=" and ESTATUS='".$_POST['estatusfiltro']."'";

        $condicionales = " ".$consulta;
		
		$json = $conection->counter("COTIZACIONES", $join, $condicionales, 1);
		
		$obj = (object) $json;
		echo json_encode($obj);
	}

function enviar_correo($cuerpo, $asesor, $cliente)
{
    date_default_timezone_set('America/Mexico_City'); //Se define la zona horaria
    require_once('../PHPMailer/class.phpmailer.php'); //Incluimos la clase phpmailer

    $mail = new PHPMailer(true); // Declaramos un nuevo correo, el parametro true significa que mostrara excepciones y errores.

    $mail->IsSMTP(); // Se especifica a la clase que se utilizará SMTP
    //$cuerpo;
    //echo $asesor[0]['EMAIL'];
    //echo $cliente;
    try {
        //------------------------------------------------------
        $correo_emisor="cotizaciones@nexosempresariales.com.mx";     //Correo a utilizar para autenticarse
        //con Gmail o en caso de GoogleApps utilizar con @tudominio.com
        $nombre_emisor= utf8_decode("Cotización");               //Nombre de quien envía el correo
        $contrasena= "+is6)ab]~4dc";          //contraseña de tu cuenta en Gmail

        //--------------------------------------------------------
        $mail->SMTPDebug  = 1;                     // Habilita información SMTP (opcional para pruebas)
        // 1 = errores y mensajes
        // 2 = solo mensajes
        $mail->SMTPAuth   = true;                  // Habilita la autenticación SMTP
        $mail->SMTPSecure = "ssl";                 // Establece el tipo de seguridad SMTP
        $mail->Host       = "mail.nexosempresariales.com.mx";      // Establece Gmail como el servidor SMTP
        $mail->Port       = 465;                   // Establece el puerto del servidor SMTP de Gmail
        $mail->Username   = $correo_emisor;           // Usuario Gmail
        $mail->Password   = $contrasena;           // Contraseña Gmail
        //A que dirección se puede responder el correo
        $mail->AddReplyTo("cotizaciones@nexosempresariales.com.mx", $nombre_emisor);
        //La direccion a donde mandamos el correo

        //$mail->AddAddress($_POST['email'], "CLIENTE");
        $mail->AddAddress($cliente, "CLIENTE");
        $mail->AddAddress($asesor[0]['EMAIL'], "ASESOR");


        //De parte de quien es el correo
        $mail->SetFrom("cotizaciones@nexosempresariales.com.mx", $nombre_emisor);
        //Asunto del correo
        $mail->Subject = utf8_decode('Recepcion de cotizacion');
        //Mensaje alternativo en caso que el destinatario no pueda abrir correos HTML
        //$mail->AltBody = $mensaje;
        //El cuerpo del mensaje, puede ser con etiquetas HTML
        $logo = "<img src='http://nexprint.mx/imagenes/cabecera/logo.png'><br><br>";
        $cabecera = "Este correo se genera de manera automática, dando inicio al proceso de cotización<br><br>";
        $pie = "<br>Gracias por su preferencia, conozca más de nosotros en <a href='http://www.nexprint.mx'>www.nexprint.mx</a>";
        $mensaje = "<html>

						 <div style='font-size:11pt; font-family:TAHOMA'>
						          $logo
						          $cabecera
								  $cuerpo
								  $pie
							  </html>";
        $mail->MsgHTML(utf8_decode($mensaje));
        //Enviamos el correo
        $respuesta = array();

        if($mail->Send())
        {
            return 1;
        }else
        {
            return 2;
        }

    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Errores de PhpMailer
        //return 1;
    } catch (Exception $e) {
        echo $e->getMessage(); //Errores de cualquier otra cosa.
        //return 1;
    }

}
?>