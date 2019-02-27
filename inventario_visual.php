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

    

    <!-- Custom Fonts -->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="css/general.css" rel="stylesheet">

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
            <?php
                ventas();
            ?>  
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">                        
                        <?php
                            creaMenu(39);
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
                            <h1 class="page-header"><i class="fa fa-tasks"></i> Inventario

                            </h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    
            	<div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Inventario
                                
                                
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">

                                    <div class="row">
                                        
                                        <div class="col-sm-5">
                                            <ul class="nav nav-pills">
                                                <label>FAMILIA</label>
                                                <select name="grupo" id="filtro_grupo" class='form-control' onchange="actualizaDatagrid()">
                                                </select>
                                            </ul>                                            
                                        </div>
                                        <div class="col-sm-5">
                                            <ul class="nav nav-pills">
                                                <label>TEXTO </label>
                                                <input type="text" class="form-control" name="filtro_texto" id="filtro_texto">
                                            </ul>                                            
                                        </div>   
                                        <div class="col-sm-2">
                                           <BUTTON type='button' class='btn'  onclick="actualizaDatagrid()">BUSCAR</BUTTON>                                         
                                        </div>   
                                                                      
                                    </div>
                                <form id='form_almacen'>        
                                    <table class="table table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                        <thead>
                                            <tr role="row">
                                                <th  style="width: 20%;">ARTICULO</th>
                                                <th  style="width: 10%;">DATOS INVENTARIO</th>
                                                <!--<th  style="width: 10%;">ULTIMA COMPRA</th>-->
                                                <th  style="width: 10%;">FECHA DE ACTUALIZACIÓN</th>                                             
                                            </tr>
                                            
                                        </thead>
                                      <tbody id="almacen">
                                        
                                      </tbody>
                                    </table>
                                </form>       
                              </div>
                            </div>
                            <!-- /.table-responsive -->
                            
                        </div>
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


    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
	<script src="js/Modulos/inventario_visual/scripts.js"></script>
    <!--<script src="js/Modulos/almacen/scripts.js"></script>-->
</body>

</html>
