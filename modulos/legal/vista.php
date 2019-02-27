<?php
session_start();
include("../../funciones/phpfunctions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Microsip Web 2.0</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../css/sb-admin-2.css" rel="stylesheet">

    <link href="../../css/general.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    
</head>

<body>


    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Microsip Web 2.0</a>
            </div>
            
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user" onclick="cerrarSesion();">
                        <li>
                            <a href="#"><i class="fa fa-sign-out fa-fw"></i> Cerrar Sesión</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
            </ul>
            <?php
                ventas();
            ?>    
            
            
            <!-- /.navbar-header -->

            
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        
                     <?php
                        creaMenu(12);
                    ?>
                       
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
		
        <div class="cuerpo">
        	<div id="page-wrapper">
            	<div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><i class="fa fa-legal"></i> Módulo Fiscal</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Reporte
                            </div>
                            <form id='datagridCaja'>  
                                <div class="row">
                                    <div class="col-sm-2" align="center">
                                        <button type="button" class="btn btn-info" style="margin: 10px 0px" onclick="$('#global').modal('show')"><i class="fa fa-print fa-4x"></i> <br><i style="font-size: 14pt">Globalización <br>de Facturas</i></button>
                                    </div>
                                    <div class="col-sm-2"   align="center">
                                        <button type="button" class="btn btn-info" style="margin: 10px 0px; min-width: 150px"  onclick="$('#mensual').modal('show')"><i class="fa fa-print fa-4x"></i> <br><i style="font-size: 14pt">Mensual <br>Fiscal</i></button>
                                    </div>
                                </div>        
                            </form>
                        </div>
                    </div>                       
                </div>
            </div>
        </div>         
    </div>
    <div class="modal fade" id="global" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog modal-dialog">
            <div class="modal-content modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Reporte</h4>
                </div>
                <form id="form_global" action="reportes/global.php" target="_blank" method="POST">
                    <div class="modal-body">
                       <div class="panel panel-default">
                            <div class="panel-heading">
                                Reporte Global de Facturas
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                                    <label for="tipoDocumento" class="control-label">DESDE:</label>
                                                      <input type="date" class="form-control" name='desde' >
                                                      
                                            </div>
                                            <div class="col-sm-6">
                                                    <label for="tipoDocumento" class="control-label">HASTA:</label>
                                                      <input type="date" class="form-control" name='hasta' >
                                                      
                                            </div>
                                        </div>
                                      
                                    
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">GENERAR REPORTE</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="mensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog modal-dialog">
            <div class="modal-content modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Reporte</h4>
                </div>
                <form id="form_global" action="reportes/mensual.php" target="_blank" method="POST">
                    <div class="modal-body">
                       <div class="panel panel-default">
                            <div class="panel-heading">
                                Reporte Mensual
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                                    <label for="tipoDocumento" class="control-label">AL:</label>
                                                      <input type="date" class="form-control" name='al' >
                                                      
                                            </div>
                                            <div class="col-sm-6">
                                                    <input type="radio" name='tipo' value='1'  checked='checked'>
                                                    <label for="tipoDocumento" class="control-label">FACTURAS</label>
                                                    <input type="radio" name='tipo' value='2' >
                                                    <label for="tipoDocumento" class="control-label">COMPLETO</label>
                                                    
                                                      
                                            </div>
                                        </div>
                                      
                                    
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">GENERAR REPORTE</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    

    
    <!-- jQuery Version 1.11.0 -->
    <script src="../../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../js/plugins/metisMenu/metisMenu.min.js"></script>


    <!-- Custom Theme JavaScript -->
    <script src="../../js/RestFull.js"></script>
    <script src="../../js/sb-admin-2.js"></script>
    <script src="../../js/complemento.js"></script>
    <<script src="modelo.js"></script>

</body>

</html>
