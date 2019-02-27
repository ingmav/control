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
    <link rel="shortcut icon" href="images/globo.png">
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
<?php

 if(isset($_SESSION['COBRO']) and $_SESSION['COBRO'] == 1 )
 {
?>
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
                </li>
            </ul>
            <?php
                ventas();
            ?>  
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                         <?php
                         creaMenu(26);
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
                        <h1 class="page-header"><span style='color:#777'><i class="fa fa-bank"></i></span> Cuentas por Cobrar</h1>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cuentas por Cobrar
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-primary" title="Reporte Cuentas por Cobrar" onclick="reporteCuentas();"><span class='fa fa-print'></span></button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                       <div class="row">

                                           <div class="col-sm-3">
                                               <div id="dataTables-example_filter" class="dataTables_filter">
                                                   <label>Folio<input type="text" name="folio" id="folio" onblur="this.value=this.value.toUpperCase()" class="form-control input-sm " ></label>
                                               </div>
                                           </div>
                                           <div class="col-sm-3">
                                               <div id="dataTables-example_filter" class="dataTables_filter">
                                                   <label>Cliente<input type="text" name="cliente" id="cliente"  onblur="this.value=this.value.toUpperCase()" class="form-control input-sm "></label>
                                               </div>
                                           </div>
                                           <div class="col-sm-1">
                                               <div id="dataTables-example_filter" class="dataTables_filter">
                                                   <button type="button" class="btn btn-success" onclick="actualizaDatagrid()">FILTRAR</button>
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="12%">FOLIO</th>
                                            <th width="35%">DESCRIPCION</th>
                                            <!--<th style="width: 130px;">FECHA CREACIÓN</th>-->
                                            <th width="12%">FECHA</th>
                                            <!--<th style="width: 100px;">FINALIZADO</th>-->    
                                            <th width="10%">MONTO TOTAL</th>
                                            <th width="10%">ANTICIPO</th>
                                            <th width="10%">SALDO</th>
                                            <th width="10%">MONTO POR COBRAR</th>
                                            <!--<th width="15%">FECHA COBRO</th>-->
                                       </tr>
                                    </thead>
                                    <tbody id="data"></tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                             <ul class="pagination"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    
    <script src="js/Modulos/Cuentas_x_cobrar/scripts.js" language="javascript"></script>
    <script src="js/Modulos/General.js"></script>
</body>

</html>
<?php 
}else
{
?>
<div class="panel panel-red">
    <div class="panel-heading">
       <span class='fa fa-cogs'></span> Acceso Denegado
    </div>
    <div class="panel-body">
        <p>Esta intentando acceder a un recursos que no tiene permisos, por favor contactese con su administrador.</p>
    </div>
    <div class="panel-footer">
        Microsip Web 2.0
    </div>
</div>
<?php
}