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

    <title>Producción</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../css/sb-admin-2.css" rel="stylesheet">

    <link href="../../css/general.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style type="text/css">
        .cargando{
            display: none;
        }

        .informacion
        {
            display: block;
        }
    </style>
    
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
            <?php
                ventas();
            ?>    
            
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                     <?php
                        creaMenu(46);
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
                        <h1 class="page-header"><i class="fa fa-check-square"></i> KPI´S</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                 <div class="row">
                                        <div class="col-lg-9">
                                            <label>Nombre</label>
                                            <input type="text" class="form-control" name="nombre" id="nombre">
                                        </div>
                                        <div class="col-lg-3">
                                             <div class="btn-group pull-right">
                                                <button type="button" class="btn btn-primary" onclick="">AGREGAR</button>
                                             </div>
                                            
                                        </div>
                                     </div>     
                            </div>
                            <!-- /.panel-heading -->
                            <form id='datagridCaja'>  
                                <div class="panel-body">
                                    <div class="table-responsive">
                                      <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th style="width: 200px;">INDICADOR CLAVE DE DESEMPEÑO</th>
                                                    <th>OBJETIVO</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                             <tbody id="data">
                                            
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
    
    <div class="modal fade" id="rel_clientes"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Trabajadores Nexprint</h4>
                </div>
                <form id="form_trabajadores">
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                               Trabajadores Nexprint
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>NOMBRE</label>
                                        <input type="text" class="form-control" name="nombre_trabajador" id="nombre_trabajador">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>TIPO</label>
                                        <select name="tipo_trabajador" id="tipo_trabajador" class="form-control">
                                        	
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>HORARIO INICIO</label>
                                        <input type="time" name="horario_inicio" id="horario_inicio" class="form-control">
                     
                                    </div>
                                    <div class="col-lg-6">
                                        <label>HORARIO FIN</label>
                                        <input  type="time" name="horario_fin" id="horario_fin" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>SALARIO BASE</label>
                                        <input type="text" class="form-control" name="salario_trabajador" id="salario_trabajador" placeholder="10000.00">
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </form>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="guardar_trabajador">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="add_clientes"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Clientes Nexprint</h4>
                </div>
                <form id="form_cliente">
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Agregar Cliente
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>CLAVE</label>
                                    <input type="text" class="form-control" name="clave" id="clave">
                                    <input type="hidden" name="id" id="id">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>NOMBRE</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>FECHA NACIMIENTO</label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info" onclick="guardar_cliente()">Guardar</button>

                </div>
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
    <script src="../../js/Modulos/General.js"></script>

    <script src="../../modulos/kpi/modelo.js"></script>

</body>

</html>
