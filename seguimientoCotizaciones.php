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

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">


    <link href="css/general.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">


</head>
<?php
 if(isset($_SESSION['COTIZACION']) and $_SESSION['COTIZACION'] == 1 )
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
                         creaMenu(14);
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
                        <h1 class="page-header"><i class="fa fa-phone"></i> CLOSE SALES</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cotizaciones
                                <!-- Single button -->
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-success" onclick="ventana_ventas()"><span class='fa fa-bar-chart'></span></button>
                                    <button type="button" class="btn btn-primary" onclick="reporte()"><span class='fa fa-print'></span></button>
                                 </div>    
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <select class="form-control" name="estatus" id="estatus" onchange="cambiaBuscador(this.value)">
                                                        <option value='0'>NO INICIADOS</option>
                                                        <option value='1'>CANCELADOS</option>
                                                        <option value='2'>EN GESTIÓN</option>
                                                        <option value='3'>AUTORIZADOS</option>
                                                    </select>
                                                     <input type="text" class="form-control" id="numeroCotizacion" placeHolder='NUMERO COTIZACION'>

                                                    <select class="form-control" name="empresa" id="empresa" onchange="actualizaDatagrid()">
                                                        <option value='1'>NEXOS</option>
                                                        <option value='2'>NEXPRINT</option>
                                                    </select>
                                                </ul>
                                            </div>
                                           
                                            
                                        </div>
                                <form  id="FormDatagrid">    	
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th  style="width: 70px;">ESTATUS</th>
                                            <th  style="width: 90px;">FOLIO</th>
                                            <th  style="width: 90px;">FECHA DE INGRESO / ACTUALIZACION</th>
                                            <!--<th  style="width: 90Px;">FECHA DE ACTUALIZACIÓN</th>-->
                                            <th  style="width: 251px;">CLIENTE / DESCRIPCIÓN</th>
                                            <!--<th  style="width: 384px;">DESCRIPCIÓN</th>-->
                                            <th  style="width: 90px;">OPERADOR</th>
                                            <th  style="width: 90px;">MONTO</th>
                                            <th  style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                  <tbody id="data">
                                  	<tr>
                                    	<td colspan="6">NO SE ENCUENTRAN REGISTROS</td>
                                    </tr>
                                  </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                            <ul class="pagination">
                                   
                                            </ul>
                                       </div>
                                   </div>
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
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Cotización</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Agregar
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                            	<form  id="FormCotizacion">    
                            	<input type="hidden" name="id" id="id" >
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="cliente" class="control-label">CLIENTE:</label>
                                            <input type='text' class="form-control" name="cliente" id="cliente" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="solicitante" class="control-label">SOLICITANTE</label>
                                            <input type='text' name="solicitante" id="solicitante" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="descripcion" class="control-label">DESCRIPCIÓN:</label>
                                            <textarea class="form-control" rows="5" style="resize:none" name="descripcion" id="descripcion" ></textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">FECHA COTIZACIÓN:</label>
                                            <input type='date' class="form-control" name="fechaCotizacion" id="fechaCotizacion" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                        	<label for="fechaCotizacion" class="control-label">EMPLEADO</label>
                                            <input type='text' name="empleado" id="empleado"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">ESTATUS:</label>
                                            <select class="form-control" name="estatus" id="estatus"></select>
                                            
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="guardarCotizacion">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="observaciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen ">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">OBSERVACIONES</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR OBSERVACIÓN
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formObservaciones">
                                    <input type='hidden' name="iddocto_ve_id" id="iddocto_ve_id">
                                    <table class="table">
                                        
                                        <tbody id="descripcionobservaciones">
                                            <!--<tr>
                                                <td><textarea style="resize:none" class="form-control"></textarea></td>
                                            </tr>-->                                       
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
                    <button type="button" class="btn btn-primary" onclick="guardarObservacion()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->


    <div class="modal fade" id="ventas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <form id="formVentas" method="post" action="ReporteMaximasVentas.php" target="_blank">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">REPORTE DE VENTAS TOTALES</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                REPORTE DE VENTAS
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="form-label">FECHA DE INICIO:</label>
                                                <input type="date" class="form-control" name="fechaInicio" id="fechaInicio">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="form-label">FECHA DE FINAL:</label>
                                                <input type="date" class="form-control" name="fechaFin" id="fechaFin">
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
                        <button type="submit" class="btn btn-primary" >REPORTE</button>
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

    <!-- Morris Charts JavaScript -->
    <!--<script src="js/plugins/morris/raphael.min.js"></script>-->
    <!--<script src="js/plugins/morris/morris.min.js"></script>-->
    <!--<script src="js/plugins/morris/morris-data.js"></script>-->
    <script src="js/RestFull.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
	<script src="js/Modulos/SeguimientoCotizacion/scripts.js"></script>


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