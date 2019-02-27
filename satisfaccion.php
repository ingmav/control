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
                         creaMenu(24);
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
                        <h1 class="page-header"><span style='color:#E21800'><i class="fa fa-heartbeat"></i></span> Satisfacción de Clientes

                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Documentos
                                <div class="btn-group pull-right">
                                   <button type="button" class="btn" title="Ver Encuestas" onclick="window.open('http://nexprint.mx/Encuesta/Reporte.php', '_blank')"><i class="fa fa-chrome"></i></button>
                                </div>  
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                       <div class="row">
                                            
                                           
                                            <div class="col-sm-4">
                                                <div id="dataTables-example_filter" class="dataTables_filter">
                                                    <label>Folio:<input type="text" id="search" class="form-control input-sm " aria-controls="dataTables-example"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="dataTables-example_filter" class="dataTables_filter">
                                                    <label>Cliente:<input type="text" id="client" class="form-control input-sm " aria-controls="dataTables-example"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 130px;">FOLIO</th>
                                            <th style="width: 120px;">FECHA / FINALIZADO</th>
                                            <!--<th style="width: 120px;">FINALIZADO</th>-->
                                            <th style="width: 300px;">CLIENTE / DESCRIPCIÓN</th>
                                            <!--<th style="width: 500px;">DESCRIPCIÓN</th>--> 
                                            <th style="width: 100px;">IMPORTE</th>
                                            <!--<th style="width: 100px;">PROCESOS</th>-->
                                            <th style="width: 100px;">ACCIÓN</th>
   
                                          
                                        </tr>
                                    </thead>
                                  <tbody id="data">
                               
                                  </tbody>
                                </table>
                                    <!--<div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                            <ul class="pagination">
                                   
                                            </ul>
                                       </div>
                                   </div>-->
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
    
    <div class="modal fade" id="myModal"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Enviar Correo</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Cliente
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            <div class="table-responsive">

                                <form  id="FormProcesos" method="post">
                                    <input type="hidden" name="DOCTO_VE_ID" id="DOCTO_VE_ID">
                                    <input type="hidden" name="CLIENTE" id="CLIENTE">
                                    <input type="hidden" name="DESC" id="DESC">
                                    <input type="hidden" name="CLIENTE_ID" id="CLIENTE_ID">
                                    <input type="hidden" name="CLAVE_CLIENTE" id="CLAVE_CLIENTE"> 
                                     <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" id="doctofactura">
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" id="foliofactura">
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group" id="fechafactura">
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group"id="clientefactura" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group" id="descripcionfactura">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fecha" id="descripcionfactura" class="control-label ">CORREO:</label>
                                                <input type="text" class="form-control" name="correo" id="correo">
                                            </div>
                                        </div>
                                    </div>
                                    
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
                    <button type="button" class="btn btn-primary" id='noEnviar'>No Enviar</button>
                    <button type="button" class="btn btn-primary" id='Enviar'>Enviar</button>
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

    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    
    <script src="js/Modulos/Satisfaccion/scripts.js" language="javascript"></script>
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