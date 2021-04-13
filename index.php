<?php
session_start();

if($_SESSION['ACTIVAMODULOS'] == 1)
{
    if(isset($_SESSION['DASHBOARD']) and $_SESSION['DASHBOARD'] == 1 )
        header('Location: Dashboard.php');
    else if(isset($_SESSION['LEVANTAMIENTOS']) and $_SESSION['LEVANTAMIENTOS'] == 1 )
         header('Location: Levantamientos.php');
    else if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 )
         header('Location: Documentos.php');
    else if(isset($_SESSION['DISENO']) and ($_SESSION['DISENO'] == 1 OR $_SESSION['DISENO']==2))
        header('Location: diseno.php');    
    else if(isset($_SESSION['IMPRESION']) and ($_SESSION['IMPRESION'] == 1 OR $_SESSION['IMPRESION']==2))
        header('Location: impresion.php');
    else  if(isset($_SESSION['INSTALACION']) and ($_SESSION['INSTALACION'] == 1 OR $_SESSION['INSTALACION']==2))
        header('Location: instalacion.php');
    else  if(isset($_SESSION['FINALIZADOS']) and $_SESSION['FINALIZADOS'] == 1)
        header('Location: finalizados.php');
    else  if(isset($_SESSION['SHOPPING']) and $_SESSION['SHOPPING'] == 1)
        header('Location: cobros.php');
}    
    

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MICROSIP WEB 2.0</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ingresar</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="Formingresar">
                            <fieldset>
                                <div class="form-group" >
                                    <input class="form-control" placeholder="Usuario" name="usernex" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña" name="contrasenianex" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <select name='tipo_sistema'  class="form-control">
                                        <option value="2">Nexprint</option>
                                        <option value="3">Nexprint Mostrador</option>
                                    </select>
                                </div>
                                
                                <a href="#" class="btn btn-lg btn-success btn-block" onclick="ingresar();">Ingresar</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

     <script src="js/RestFull.js"></script>

     <script src="js/Modulos/Acceso/Acceso.js"></script>
</body>

</html>
