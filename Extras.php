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
 //if(isset($_SESSION['EXTRA']) and $_SESSION['EXTRA'] == 1 )
 //{
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
                         creaMenu(16);
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
                        <h1 class="page-header"><i class="fa fa-child"></i> Actividades Extra</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Actividades
                                <!-- Single button -->
                                <div class="btn-group pull-right">
                                   <button type="button" class="btn btn-default" id="Agregar"><span class='fa fa-plus-square'></span></button>
                                   <button type="button" class="btn btn-default" id="editar"><span class='fa fa-pencil-square'></span></button>  
                                   <button type="button" class="btn btn-default" id='eliminar'><span class='fa fa-minus-square'></span></button>  
                                   <button type="button" class="btn btn-default" id='reporte'><span class='fa fa-print'></span></button>  
                                  
                                </div>
                               
                            </div>
                            <!-- /.panel-heading -->
                              
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    <form  id="FormBuscar">
                                    <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <input type="date" name='fechaBusqueda' id='fechaBusqueda' class='form-control'>
                                                    <button type="button" class="btn btn-primary" id="buscar">BUSCAR</button>     
                                                </ul>
                                            </div>

                                            
                                        </div>
                                    </form>        
                                <form  id="FormDatagrid">    	
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 150px;">FECHA / DE - A</th>
                                            <!--<th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 354px;">DE - A</th>-->
                                            <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 251px;">RESPONSABLE / COLABORADORES / ACTIVIDAD</th>
                                            <!--<th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 384px;">ACTIVIDAD</th>-->
                                            <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 186px;">REVISO</th>
                                            <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 30px;">OBS</th>
                                            <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 70px;" align="center"></th>
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
    
    <div class="modal fade" id="AgregarExtra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Actividad</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Extra
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                            	<form  id="FormActividad">    
                            	<input type="hidden" name="id" id="id" >
                                 <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">FECHA ACTIVIDAD:</label>
                                            <input type='date' class="form-control" name="fechaActividad" id="fechaActividad" />
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">DESDE:</label>
                                            <input type='time' class="form-control" name="desdeActividad" id="desdeActividad" />
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">HASTA:</label>
                                            <input type='time' class="form-control" name="hastaActividad" id="hastaActividad" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="cliente" class="control-label">RESPONSABLE:</label>
                                            <input type='text' class="form-control" name="responsable" id="responsable" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="solicitante" class="control-label">COLABORADORES:</label>
                                            <input type='text' name="colaboradores" id="colaboradores" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="descripcion" class="control-label">ACTIVIDAD:</label>
                                            <textarea class="form-control" rows="5" style="resize:none" name="actividad" id="actividad" ></textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                        	<label for="fechaCotizacion" class="control-label">RESPOSABLE A REVISAR:</label>
                                            <SELECT class='form-control' name='reviso' id='reviso'></SELECT>
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
                    <button type="button" class="btn btn-primary" id="guardarActividad">GUARDAR</button>
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
                                    <input type='hidden' name="idactividad" id="idactividad">
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
	<script src="js/Modulos/Extra/scripts.js"></script>
</body>

</html>
<?php 
/*}else
{*/
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