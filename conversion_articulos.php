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

    <title>Microsip Producción</title>

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
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Microsip Producción</a>
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
                        creaMenu(40);
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
                        <h1 class="page-header"><i class="fa fa-share-alt"></i> Conversion Insumos</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <!--<div class="panel-heading">Procesos
                                 <div class="btn-group pull-right">
                                 <button type="button" class="btn btn-warning" onclick="Activas()"><span class='fa fa-dashboard'></span></button>
                                 <button type="button" class="btn btn-default" onclick="window.open('ReporteImpresion.php?tipo=2','_blank')"><span class='fa fa-print'></span></button>
                                 <button type="button" class="btn btn-primary" onclick="actualizaDatagrid()"><span class='fa fa-refresh'></span></button>  
                                 </div>  
                            </div>-->
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <ul class="nav nav-pills">
                                                    <input type="text" id='articulofiltro' name="articulofiltro" class='form-control' style="width: 100%" placeholder="CLAVE O ARTICULO" onblur="$(this).val($(this).val().toUpperCase())">
                                                </ul>
                                            
                                            </div>
                                            <div class="col-sm-2">
                                                <ul class="nav nav-pills">
                                                    <button type="button" class="btn btn-primary" onclick="actualizaDatagrid()">BUSCAR</button>
                                                </ul>
                                            
                                            </div>
                                            <div class="col-sm-6">
                                                <ul class="nav nav-pills">
                                                    <label style="font-size: 15px">Líneas</label>
                                                    <select name="grupo" id="filtro_grupo" class='form-control' onchange="actualizaDatagrid()">
                                                        
                                                    </select>
                                                </ul>                                            
                                            </div>
                                           
                                            
                                        </div>
                                    </div>
                                    <!--<div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">-->
                                    	
                                <table class="table table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 10%">CLAVE</th>
                                            <th style="width: 80%">ARTÍCULO</th>
                                            <th style="width: 10%;">INSUMOS</th>
                                            <th style="width: 10%;">RELACIONAR</th>
                                            <th style="width: 10%;">ELIMINAR</th>
                                        </tr>
                                    </thead>
                                  <tbody id="datagrid">
                                  	
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
    
    <div class="modal fade" id="acciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen ">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">INSUMOS</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            RELACIONAR INSUMOS
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="form">
                                    <input type="hidden" name="id_articulo" id="id_articulo">
                                    <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">ARTÍCULO</th>
                                                 </tr>   
                                            </thead>
                                        <tbody >
                                            <tr>
                                                <td id="dato_articulo"></td>
                                            </tr>                                         
                                        </tbody>
                                    </table>
                                    <table class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">AGREGAR INSUMO AL ARTICULO
                                                    <div class="pull-right">
                                                        <button type="button" class="btn btn-warning" onclick="agregar_insumo()"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                    </th>
                                                 </tr>
                                                 <tr>
                                                    <th>GRUPO DE INSUMO</th>
                                                    <th>CANTIDAD</th>
                                                    <th>TIPO BAJA</th>
                                                 </tr>   
                                            </thead>
                                        <tbody >
                                            <tr>
                                                <td>
                                                    <select name="grupo_insumo" id="grupo_insumo" class="form-control"></select>
                                                </td>
                                                <td><input type="text" name="cantidad_insumo" id="cantidad_insumo" class="form-control"></td>
                                                <td>
                                                    <select name="baja_insumo" id="baja_insumo" class="form-control"></select>
                                                </td>
                                                <td style="width: 100px"><button type="button" class="btn btn-primary" onclick="relacionar_articulo_insumo()"><i class="fa fa-share-alt"></i></button></td>

                                            </tr>                                         
                                        </tbody>
                                    </table>
                                    <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>ARTICULO</th>
                                                    <th>CANTIDAD</th>
                                                    <th>TIPO DE BAJA</th>
                                                    <th style="width: 100px"><button type="button" class="btn btn-danger" onclick="quitar_articulo_insumo()"><i class="fa fa-minus"></i></button></th>
                                                 </tr>   
                                            </thead>
                                        <tbody id="tabla_datos">
                                                                                 
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="add_insumos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen ">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">INSUMOS</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR INSUMO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="form_insumo">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    FAMILIA            
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select name="familia" class="form-control">
                                                    	<option value="0">SIN FAMILIA</option>
                                                    </select>            
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td>
                                                    NOMBRE INSUMO            
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="insumo" id="insumo" class="form-control">       
                                                </td>
                                            </tr>  
                                            <tr>
                                                <td>
                                                    CANTIDAD MINIMA           
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="minimo" id="minimo" class="form-control">       
                                                </td>
                                            </tr>                                         
                                        </tbody>
                                    </table>
                                    
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="guardaInsumo()">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
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

    <!-- Morris Charts JavaScript -->
    <!--<script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/conversion/scripts.js"></script>
    <script src="js/Modulos/General.js"></script>

</body>

</html>
<?php 
//}else
//{
?>
<!--<div class="panel panel-red">
    <div class="panel-heading">
       <span class='fa fa-cogs'></span> Acceso Denegado
    </div>
    <div class="panel-body">
        <p>Esta intentando acceder a un recursos que no tiene permisos, por favor contactese con su administrador.</p>
    </div>
    <div class="panel-footer">
        Microsip Web 2.0
    </div>
</div>-->
<?php
//}
?>