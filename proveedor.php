<?php
session_start();
include("funciones/phpfunctions.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>

     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
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
                         creaMenu(42);
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
                        <h1 class="page-header"><i class="fa fa-file"></i> Catálogo de Proveedores</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Proveedores
                                <!-- Single button -->
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-primary" onclick="window.open('reportes/inventario_admin/catalogo_proveedor.php', '_blank')">
                                        <i class="fa fa-print"></i> REPORTE PROVEEDOR
                                    </button>
                                    
                                  <button type="button" id="agregar" class="btn btn-default dropdown-toggle">
                                    AGREGAR
                                  </button>
                                        
                                </div>
                               
                            </div>
                            <!-- /.panel-heading -->
                            <form  id="FormDatagrid">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                            
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                        <thead>
                                            <tr>
                                                <th>NOMBRE</th>
                                                <th>CONTACTO</th>
                                                <th>TELÉFONO</th>
                                                <th>CONDICIÓN DE PAGO</th>
                                                <th>NO. CUENTA</th>
                                                <th width="100">ACCION</th>
                                            </tr>
                                        </thead>
                                      <tbody id="lista_proveedores">
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

                                  </div>
                                </div>
                            </form>

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
    
    <div class="modal fade" id="modal_proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Proveedores</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           AGREGAR PROVEEDOR
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_PROVEEDOR">
                                    <input type="hidden" name="id" id="id">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">NOMBRE</label>
                                                <input type="text" name="nombre_proveedor" id='nombre_proveedor' class="form-control" placeholder="NOMBRE">
                                              </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">DIRECCIÓN</label>
                                                
                                                <input type="text" name="direccion" id='direccion' class="form-control" placeholder="DIRECCION">
                                                
                                              </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">TELÉFONO</label>
                                                
                                                <input type="text" name="telefono" id='telefono' class="form-control" placeholder="TELEFONO">
                                              </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">CONDICIÓN DE PAGO (DÍAS)</label>
                                                
                                                <input type="text" name="condicion" id='condicion' class="form-control" placeholder="10">
                                              </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">NOMBRE CONTÁCTO</label>
                                                
                                                <input type="text" name="contacto" id='contacto' class="form-control" placeholder="NOMBRE DEL CONTACTO">
                                            </div>
                                        </div>         
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">DATOS ADICIONALES</label>
                                                <input type="text" name="descripcion" id='descripcion' class="form-control" placeholder="DATOS ADICIONALES">
                                            </div>
                                        </div>    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">BANCO Y No CUENTA</label>
                                                <TEXTAREA  name="cuenta" id='cuenta' class="form-control"></TEXTAREA> 
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
                    <button type="button" class="btn btn-primary" onclick="guarda_proveedor()">GUARDAR</button>
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


    <script src="js/RestFull.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
    <script src="js/Modulos/proveedor/scripts.js"></script>
	
</body>

</html>
