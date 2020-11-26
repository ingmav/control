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

    <title>Producción</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--<link href="css/general.css" rel="stylesheet">
    -->


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
                <a class="navbar-brand" href="index.php">Producción</a>
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
            <!-- /.navbar-header -->

            <?php
                //ventas();
            ?>  
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">                
                    <?php
                        creaMenu(44);
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
                        <h1 class="page-header"><i class="fa fa-download"></i> Importación Cotización</h1>
                    </div>
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    </div>
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    <label>Buscar Folio:</label>
                                    <input type="text" class="form-control" id='folio'>  
                                    <button type="button" class="btn btn-primary" onclick="verficar()">Importar</button>
                                    <span style="font-size:16pt; font-weight:bold;" id="folio_importado">Folio Generado: <span style='color:red' id='num_folio'>00001</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style='font-weight: bold;'><span style='color:red' id='cve_cliente'></span> <span id='nombre_cliente'></span></h5>
                            <p class="card-text" id='descripcion'  style='font-weight: bold;'></p>
                            
                        </div>
                    </div>
                </div>
                <div class="col-ls-12">
                <table class="table table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                    <thead>
                        <tr role="row">
                            <th style="width: 100px;">CLAVE</th>
                            <th style="width: 150px;">DESCRIPCIÓN ARTÍCULO</th>
                            <th style="width: 300px; text-align:center">CANTIDAD</th>
                            <th style="width: 300px; text-align:center">PRECIO U.</th>
                            <th style="width: 100px; text-align:center">DESC</th>
                            <th style="width: 100px; text-align:right">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="DatosCotizacion">
                    
                    </tbody>
                    <tfoot id="ResumenCotizacion">
                    
                    </tfoot>
                </table>    
                </div>
                
            </div>
        </div>         
    </div>
    
    
    <script src="js/jquery-1.11.0.js"></script>
   <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/modulos/importar/importar.js"></script>

    <!--<script src="js/Modulos/General.js"></script>-->

</body>

</html>
