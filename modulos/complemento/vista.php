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

    <title>Produccion</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../css/sb-admin-2.css" rel="stylesheet">

    <link href="../../css/general.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    
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
            
            
            <!-- /.navbar-header -->

            
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
                        <h1 class="page-header"><i class="fa fa-file-code-o"></i> Complemento Liverpool</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Complemento Liverpool
                                 <div class="btn-group pull-right">
                                 
                                 <button type="button" class="btn btn-default" id="iniciarcaja" onclick="$('#agregar').modal('show');" title="INICIA CAJA"><span class='fa fa-plus'></span></button>
                                 <button type="button" class="btn btn-default" id="reportecaja" onclick="$('#configuracion').modal('show')" title="REPORTE DE CAJA"><span class='fa fa-gear'></span></button>
                                   
                                 </div>  
                            </div>
                            <!-- /.panel-heading -->
                            <form id='datagridCaja'>  
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                      	
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th style="width: 130px;">ID</th>
                                                    <th style="width: 130px;">FACTURA</th>
                                                    <th style="width: 120px;">PEDIDO</th>
                                                    <th style="width: 300px;">GLN LIVERPOOL</th>
                                                    <th style="width: 300px;">GLN NEXPRINT</th>
                                                    <th style="width: 450px">MONTO</th>
                                                    <th style="width: 110px;"></th>
                                                    <th style="width: 110px;"></th>
                                                </tr>
                                            </thead>
                                         <form id="datagrid">   
                                         <tbody id="data">
                                        
                                         </tbody>
                                         </form>
                                        </table>
                                
                                      
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
    
    <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog modal-dialog">
            <div class="modal-content modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">XML</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            XML
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formXml"  enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-12">
                                                <label for="tipoDocumento" class="control-label">NO PEDIDO:</label>
                                                  <input type="text" class="form-control" name='folio' id="add_pedido" placeholder="PEDIDO" onblur="$('#add_recibo').val($('#add_pedido').val())" >
                                                  
                                        </div>
                                    </div>
                                    <div class="row">                                                    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">NO CONTRA RECIBO:</label>
                                                <input type='text' class="form-control" name='add_recibo' id='add_recibo' placeholder="CONTRA RECIBO">
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="row">                                                    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">NO DE DEPARTAMENTO:</label>
                                                <input type='text' class="form-control" name='add_departamento' id='add_departamento'>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="tipoDocumento" class="control-label">ARCHIVO</label>
                                            <input type='file' class="form-control" name="archivo" id="archivo">   
                                        
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
                    <button type="button" class="btn btn-primary" onclick="guardarXML()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="configuracion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog ">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">COONFIGURACIÓN</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            CONFIGURACIÓN
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formConfiguracion">
                                         <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">NO PROVEEDOR:</label>
                                                <input type='text' class="form-control" name='no_proveedor' id='config_no_proveedor'>
                                                                                              
                                            </div>   
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">GLN LIVERPOOL:</label>
                                                <input type='text' class="form-control" name='gln_liverpool' id='config_gln_liverpool'>
                                                                                              
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">GLN NEXPRINT:</label>
                                                <input type='text' class="form-control" name='gln_nexprint' id='config_gln_nexprint'>   
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">NO DEPARTAMENTO:</label>
                                                <input type='text' class="form-control" name='no_departamento' id='config_no_departamento'>   
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
                    <button type="button" class="btn btn-primary" onclick="actualiza_configuracion()">ACTUALIZAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    
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
    <<script src="modelo.js"></script>

</body>

</html>
