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
                            <h1 class="page-header"><i class="fa fa-dashboard"></i> Dashboard</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            
                            <!--<center><div style="font-family:Navidad_two; font-size:30pt; color:blue; padding:0px">Cuando te digan no puedes:<br> diles, ¡observa como lo hago!</div></center>-->
                            
                        </div>
                    </div>        
                	<!--<div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-clipboard fa-4x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge" id="cont_levantamiento">0</div>
                                            <div>LEVANTAMIENTOS</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer" id="ver_info_levantamiento" data-indice="1">
                                        <span class="pull-left">VER A DETALLE</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-clipboard fa-4x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge" id="cont_cotizaciones">0</div>
                                            <div>COTIZACIONES</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer" id="ver_info_cotizacion" data-indice="2">
                                        <span class="pull-left">VER DETALLES</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-shopping-cart fa-4x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge" id="cont_requerimientos">0</div>
                                            <div>REQ. MATERIAL</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer" id="ver_info_material" data-indice="4">
                                        <span class="pull-left">VER DETALLES</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php if($_SESSION['VENTAS'] == 1){ ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-clipboard fa-4x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge" id="cont_facturas">0</div>
                                            <div>FACTURAS</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer" id="ver_info_factura" data-indice="3">
                                        <span class="pull-left">VER DETALLES</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-calculator fa-4x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge" id="cont_cxc">0</div>
                                            <div>C X C</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer" id="ver_info_cuentas" data-indice="5">
                                        <span class="pull-left">VER A DETALLE</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>
                    <?php  } ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-red">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-check fa-4x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge" id="cont_actividades">0</div>
                                            <div>ACTIVIDADES FIN.</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer" id="ver_info_actividad_fin" data-indice="6">
                                        <span class="pull-left">VER DETALLES</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>

        <div class="modal fade" id="myStructure"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog  modal-dialog-75-screen">
                <div class="modal-content modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="tituloModal">Hola</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Información
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <div class="table-responsive" id="table_modal1">


                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /#wrapper -->
    </body>
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <!--<script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>

    <!--<script src="js/Modulos/General.js"></script>-->
    <!--<script src="js/Modulos/Dashboard/scripts.js"></script>-->

</body>

</html>
