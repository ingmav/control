<?php
session_start();
include("../funciones/phpfunctions.php");
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">
    
    <link href="../css/general.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<?php
 if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 )
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
                            creaMenu(30);
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
                        <h1 class="page-header"><i class="fa fa-file-text-o"></i> Selección de Documentos</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Documentos
                                <div style='float: right;'><button class="btn btn-primary" onclick='eliminacion()'><i class='fa fa-trash'></i> Eliminar</button></div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    	<div class="row">
                                            <div class="col-lg-6">
                                                <ul class="nav nav-pills">
                                                    <li class="active" id="digital"><a href="#home-pills" data-toggle="tab">DIGITAL</a></li>
                                                    <li id="gran_formato"><a href="#home-pills" data-toggle="tab">GRAN FORMATO</a></li> 
                                                    <!--<li id="pv"><a href="#home-pills" data-toggle="tab">Mostrador</a></li>-->
                                                </ul>
                                            
                                            </div>
                                            
                                        </div>    
                                        
                        			</div>
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 120px;">FOLIO</th>
                                            <th style="width: 120px;">FECHA</th>
                                            <th style="width: 384px;">CLIENTE / DESCRIPCION</th>
                                            <th style="width: 50px;">
                                            	ACTIVIDADES
                                            </th>
                                            <th style="width: 50px; text-align:center">
                                            <input type='checkbox' onclick="seleccion_multiple()" />
                                            </th>
                                            
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
    
    <div class="modal fade" id="procesosgf"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-fullscreen">
            <div class="modal-content modal-content-fullscreen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Enviar a Producción</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Procesos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            <div class="table-responsive">

                            	<form  id="FormProcesosgf">
                                    <input type="hidden" name="DOCTO_VE_ID" id="DOCTO_VE_ID">    
                                    <div class="row" id="factura">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="fecha" id="foliofacturaGF" class="control-label " style="color:#00F">FOLIO:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="fecha" id="fechafacturaGF" class="control-label " style="color:#00F">FECHA:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="fecha" id="clientefacturaGF" class="control-label " style="color:#00F">CLIENTE:</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>    
                                                <th style="width:30%">Nombre </th>
                                                <th>Unidades</th>
                                                <th style="width:50%">Notas</th>
                                                <th>Fecha de Entrega</th>
                                                <th>Hora de Entrega</th>
                                                <th><span class="fa fa-check"></span></th>
                                                <th><span class="fa fa-apple"></span></th>
                                                <th><span class="fa fa-print"></span></th>
                                                <th><span class="fa fa-wrench"></span></th>
                                                <th><span class="fa fa-truck"></span></th>
                                                <th><span class="glyphicon glyphicon-lock"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_productos_gf">
                                            
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    
                    <button type="button" class="btn btn-primary" id='guardarCerrarProducciongf'>Guardar y Cerrar Documento</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="ProcesosPv"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-fullscreen">
            <div class="modal-content modal-content-fullscreen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Enviar a Producción</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Procesos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            <div class="table-responsive">

                                <form  id="FormProcesosPv">
                                    <input type="hidden" name="DOCTO_PV_ID" id="DOCTO_PV_ID">    
                                    <div class="row" id="factura">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="fecha" id="foliofacturaPv" class="control-label " style="color:#00F">FOLIO:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="fecha" id="fechafacturaPv" class="control-label " style="color:#00F">FECHA:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="fecha" id="clientefacturaPv" class="control-label " style="color:#00F">CLIENTE:</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>    
                                                <th style="width:30%">Nombre </th>
                                                <th>Unidades</th>
                                                <th style="width:50%">Notas</th>
                                                <th>Fecha de Entrega</th>
                                                <th>Hora de Entrega</th>
                                                <th><span class="fa fa-check"></span></th>
                                                <th><span class="fa fa-apple"></span></th>
                                                <th><span class="fa fa-print"></span></th>
                                                <th><span class="fa fa-wrench"></span></th>
                                                <th><span class="glyphicon glyphicon-lock"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_productos_pv">
                                            
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <!--<button type="button" class="btn btn-primary" id='guardarProduccion'>Guardar</button>-->
                    <button type="button" class="btn btn-primary" id='guardarCerrarProduccionPV'>Guardar y Cerrar Documento</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../js/RestFull.js"></script>
    <script src="../js/sb-admin-2.js"></script>
    <script src="../js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
	<script src="js/Modulos/Seleccion/scripts.js" language="javascript">
		
    </script>
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