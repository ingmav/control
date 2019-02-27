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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

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
            
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                     <?php
                        creaMenu(37);
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
                        <h1 class="page-header"><i class="fa fa-check-square-o"></i> Validación Ventas-Insumos</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Validación Ventas-Insumos
                                 <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-default" onclick="reporte()"><span class="fa fa-print"></span></button>
                                    <button type="button" class="btn btn-DANGER" onclick="validar()"><span class="fa fa-check"></span></button>

                                </div>
                            </div>
                            <!-- /.panel-heading -->
                            <form id='datagridValidacion'>  
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                            
                                        </div>
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                      	
                                        <table class="table table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th style="width: 130px;">FACTURA</th>
                                                    <th>DESCRIPCION</th>
                                                    <th style="width: 120px">P/VENTA</th>
                                                    <th style="width: 10px;"><input type="checkbox" name="check" onclick="checador(this)"></th>
                                                </tr>
                                            </thead>
                                          
                                         <tbody id="datagridValidacionVenta">
                                        
                                         </tbody>
                                         
                                        </table>
                                
                                      <div class="row">
                                        <div class="col-sm-6">
                                            <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                                <ul class="pagination">
                                       
                                                </ul>
                                           </div>
                                       </div>  
                                  </div>
                            </div>
                            <!-- /.table-responsive -->
                            </form>
                        </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /fin tabla primaria -->
                    </div>
                </div>
            </div>
        </div>         
    </div>
    
    <div class="modal fade" id="ReporteInsumos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Reporte de Insumos</h4>
                </div>
                <form id="formReporteExtras" method="post" target="_blank" action="reportes/validacion/reporte_insumos.php">
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Insumos
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">FECHA DE INICIO</label>
                                                <input type="date" class="form-control" name="fecha_inicio">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">FECHA DE FINAL</label>
                                                <input type="date" class="form-control" name="fecha_final">
                                            </div>
                                        </div>

                                    </div>
                                   
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                        <button type="submit" class="btn btn-primary">GENERAR REPORTE</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/Validacion-ventas/scripts.js"></script>
    <script src="js/Modulos/General.js"></script>

</body>

</html>
