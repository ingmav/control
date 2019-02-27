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
<?php
 if(isset($_SESSION['INVENTARIOACCESO']) and ($_SESSION['INVENTARIOACCESO'] == 1  OR $_SESSION['INVENTARIOACCESO'] == 2) )
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
                ventas();
            ?>  
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                     <?php
                        creaMenu(15);
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
                        <h1 class="page-header"><i class="fa fa-tasks"></i> Inventarios</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <diiv class="row">
                    <div class="col-lg-12" id="tituloInventario">

                    </div>
                </diiv>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Articulos
                                 <div class="btn-group pull-right">
                                 <button type="button" class="btn btn-default" id="Inventarioceros" title="Inventario en Ceros" onclick="sinInventario();"><span class='fa fa-file-text-o'></span></button>
                                 <button type="button" class="btn btn-default" id="InventarioInicializa" title="Inicializar" onclick="InicializaInventario();"><span class='fa fa-check-square-o'></span></button>
                                 <button type="button" class="btn btn-default" id="InventarioAgregar" title="Agregar Inventario" onclick="agregarInventario();"><span class='fa fa-plus-square'></span></button>
                                 <!--<button type="button" class="btn btn-default" id="InventarioSustrae" title="Sustraer Inventario" onclick="sustraerInventario();"><span class='fa fa-minus-square'></span></button>-->
                                 <button type="button" class="btn btn-default" id="InventarioReporte" title='Reporte' onclick="enviarReporte();"><span class='fa fa-print'></span></button>
                                 <button type="button" class="btn btn-default" id="InventarioReajusta" title="Reajuste" onclick="openreajuste();"><span class='fa fa-reorder'></span></button>
                                 <button type="button" class="btn btn-default" id="InventarioCorte" title="Corte Inventario" onclick="corteInventario();"><span class='fa fa-tachometer'></span></button>
                                 </div>  
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="nav nav-pills">

                                                    <select class="form-control" id="linefilter">

                                                    </select>

                                                </ul>

                                            </div>


                                        </div>
                                    </div>
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    	
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                           <th style="width: 200px;">ARTICULO</th>
                                            <th style="width: 20px;">UNIDAD</th>
                                            <th style="width: 160px;">LINEA</th>
                                            <th style="width: 100px;">INVENTARIO INICIAL</th>
                                            <th style="width: 100px;">INGRESO</th>

                                            <th style="width: 100px;">BAJAS</th>
                                            <th style="width: 100px;">INVENTARIO FINAL</th>
                                            <th style="width: 100px;">SUB ARTICULOS</th>

                                            
                                        </tr>
                                    </thead>
                                  <tbody id="DatagridInventario">
                                  	
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
                            
                        </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /fin tabla primaria -->
                    </div>
                </div>
            </div>
        </div>         
    </div>
    
    <div class="modal fade" id="agregarInventario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog ">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">AGREGAR</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR INVENTARIO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formAgregar">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">LINEA:</label>
                                                <SELECT name="lineaArticulo" id='lineaArticulo' class='form-control'>
                                                    <OPTION value="0">SELECCIONE UNA OPCIÓN</OPTION>
                                                </SELECT>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">ARTICULO:</label>
                                                <SELECT name="agregarArticulo" id='agregarArticulo' class='form-control'>

                                                </SELECT>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">SUB ARTICULO:</label>
                                                <SELECT name="agregarsubArticulo" id='agregarsubArticulo' class='form-control'>
                                                    <OPTION></OPTION>
                                                </SELECT>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">PROVEEDOR:</label>
                                                <input type='text' name="proveedorArticulo" class="form-control">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">CANTIDAD:</label>
                                               <input type='text' name="cantidadArticulo" class="form-control">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">IMPORTE:</label>
                                                <input type='text' name="importeArticulo" class="form-control">

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-primary" onclick="guardarArticulo()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="sustraerInventario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog ">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">AGREGAR</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR INVENTARIO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formSustraer">
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">ARTICULO:</label>
                                                <SELECT name="sustraerArticulo" id='sustraerArticulo' class='form-control'>
                                                    <OPTION></OPTION>
                                                </SELECT>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" class="control-label ">CANTIDAD:</label>
                                               <input type='text' name="cantidadArticulo" class="form-control">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-primary" onclick="quitarArticulo()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

        <div class="modal fade" id="reajustar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">SELECCIÓN DE INVENTARIO</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            REAJUSTE INVENTARIO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formReajuste">
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <table width="100%">
                                                <thead>
                                                    <th>ARTICULO</th>
                                                    <th>UNIDAD</th>
                                                    <th>LINEA</th>
                                                    <th>CANTIDAD</th>
                                                </thead>
                                                <TBODY id='reajustearticulos'>

                                                </TBODY>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-primary" onclick="agregarReajuste()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="reajustarsubarticulos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">REAJUSTE</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            REAJUSTE SUBARTICULOS INVENTARIO
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formReajustesub">
                                        <input type="hidden" name="idarticuloweb" id="idarticuloweb">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table width="100%">
                                                <thead>
                                                <th>ARTICULO</th>
                                                <th>SUBARTICULO</th>

                                                <th>CANTIDAD</th>
                                                </thead>
                                                <TBODY id='reajustesubarticulos'>

                                                </TBODY>
                                            </table>
                                        </div>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-primary" onclick="agregarsubarticulos()">GUARDAR</button>
                </div>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="Config_Reporte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog">
            <form id="corte" target="_blank" method="post" action="reportes/inventario/ReporteInventario.php">
                <div class="modal-content modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">SELECCIÓN DE INVENTARIO</h4>
                    </div>

                        <div class="modal-body">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    SELECCIÓN DE INVENTARIO
                                </div>

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <form id="formReajustesub">
                                            <input type="hidden" name="idarticuloweb" id="idarticuloweb">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <select class="form-control" name="seleccion_corte" id="seleccion_corte"></select>

                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                        <button type="submit" class="btn btn-primary">GENERAR REPORTE</button>
                    </div>
                </div>
            </form>
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

    <!-- Morris Charts JavaScript -->
    <!--<script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/Inventario/scripts.js"></script>
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