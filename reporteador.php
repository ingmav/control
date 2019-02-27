<?php
session_start();
include("funciones/phpfunctions.php");
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
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="css/plugins/timeline.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/sb-admin-2.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="css/plugins/morris.css" rel="stylesheet">

        <link href="css/general.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    </head>

    <body>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Microsip Web 2.0</a>
            </div>
            <!-- /.navbar-header -->
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

            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <?php
                        creaMenu(27);
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
                        <h1 class="page-header"><i class="fa fa-text"></i> Reporteador</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Reporteador
                            </div>
                            <!-- /.panel-heading -->

                            <div class="table-responsive">
                                <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    <form  id="FormMostrador" method="post" action="reportes/reporteador/reporte_mostrador.php" target="_blank">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="3" style="width: 100px;">Reporte de Mostrador</th> 
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                            <tr>
                                                <td>DESDE 
                                                <input type="date" name="desde_mostrador" id="desde_mostrador" class="form-control"></td>
                                                <td>HASTA 
                                                <input type="date" name="hasta_mostrador" id="hasta_mostrador" class="form-control"></td>
                                                <td><button type="button" class="btn btn-success form-control"  onclick="reporte_mostrador()">CREAR REPORTE</button></td>
                                            </tr>
                                            
                                            </tbody>
                                        </table>
                                        
                                    </form>
                                </div>
                            </div>
                            

                            
                            <div class="table-responsive">
                                <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    <form  id="FormCotizacion" method="post" action="reportes/reporteador/reporte_cotizaciones.php" target="_blank">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="3" style="width: 100px;">Reporte de Cotizaciones</th> 
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                            <tr>
                                                <td>DESDE 
                                                <input type="date" name="desde_cotizacion" id="desde_cotizacion" class="form-control"></td>
                                                <td>HASTA 
                                                <input type="date" name="hasta_cotizacion" id="hasta_cotizacion" class="form-control"></td>
                                                <td><button type="button" class="btn btn-success form-control" onclick="reporte_cotizacion()">CREAR REPORTE</button></td>
                                            </tr>
                                            
                                            </tbody>
                                        </table>
                                        
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="3" style="width: 100px;">Reporte de Ventas</th> 
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-primary" onclick="window.open('reportes/reporteador/ventas_tablero.php', '_blank')">Reporte Tablero de Producción</button>
                                                    <button type="button" class="btn btn-primary" onclick="window.open('reportes/reporteador/ventas_anual.php', '_blank')">Reporte Ventas Anual</button>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            
                                            </tbody>
                                        </table>
                                        
                                    </form>
                                </div>
                            </div>
                            <!-- /.table-responsive -->
                            
                            <!-- /.panel-body -->
                        </div>
                        <!-- /fin tabla primaria -->
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

    <!-- Morris Charts JavaScript -->
    <script src="js/RestFull.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
    <script src="js/Modulos/Admin/reporteador/scripts.js"></script>
    </body>

    </html>
