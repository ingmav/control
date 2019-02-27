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
                        creaMenu(19);
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
                        <h1 class="page-header"><i class="fa fa-credit-card"></i> Mostrador</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Procesos
                                 <div class="btn-group pull-right">
                                 <!--<button type="button" class="btn btn-default" onclick="window.open('ReporteImpresion.php?tipo=2','_blank')"><span class='fa fa-print'></span></button>-->
                                <!--<button type="button" class="btn btn-primary" onclick="location.reload()"><span class='fa fa-refresh'></span></button>-->
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
                                                    <!--<select class="form-control" id="realizados">
                                                        <option value="1">NO REALIZADOS</option>
                                                        <option value="2">REALIZADOS</option>
                                                    </select>-->
                                                    <input type="date" id='fecha' value='<?php echo date("Y-m-d"); ?>' class='form-control'>
                                                    <input type="text" id='foliofiltro' class='form-control' placeholder="FOLIO" onblur="$(this).val($(this).val().toUpperCase())">
                                                    <input type="text" id='clientefiltro' class='form-control' placeholder="CLIENTE" onblur="$(this).val($(this).val().toUpperCase())">

                                                    <button type="button" class="btn btn-primary" onclick="buscarRealizados()">BUSCAR</button>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    	
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 70px;">FOLIO</th>
                                            <th style="width: 90px;">FECHA</th>
                                            <th style="width: 350px;">CLIENTE / MATERIALES</th>
                                            <!--<th style="width: 400px;">MATERIALES</th>-->
                                            <th style="width: 100px;">MONTO</th>
                                            <!--<th style="width: 350px;">DESCRIPCIÓN DETALLADA</th>-->
                                            <th style="width: 110px;">OPERADOR</th>
                                            <th style="width:100px;"></th>
                                        </tr>
                                    </thead>
                                  <tbody id="PendientesPV">
                                  	
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
                                    <input type='hidden' name="id" id="id">
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

    
    <!--<div class="modal fade" id="finalizaTarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">FINALIZAR /TURNAR</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                           FINALIZAR ACTIVIDAD
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formFinalizar">
                                    <input type='hidden' name="idfinalizar" id="idfinalizar">
                                    <table class="table">
                                        
                                        <tbody>
                                            <tr>
                                                <td><SELECT name='EmpleadoFinalizar' id='EmpleadoFinalizar' class='form-control'></SELECT></td>
                                            </tr>
                                            
                                            <tr>
                                                <td><textarea type='text' name="observacionFinalizado" class='form-control' rows='5' style="resize:none;"></textarea></td>
                                            </tr>                                       
                                        </tbody>
                                    </table>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-primary" onclick="guardarTurnar()">TURNAR</button>
                    <button type="button" class="btn btn-primary" onclick="guardar()">FINALIZAR</button>
                </div>
            </div>

        </div>

    </div>
    -->
        <div class="modal fade" id="finalizaTarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">FINALIZAR</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                FINALIZAR ACTIVIDAD
                            </div>
                            <!-- /.panel-heading -->

                            <div class="panel-body">
                                <form id="formFinalizar">
                                    <div class="table-responsive">
                                        <input type='hidden' name="idfinalizar" id="idfinalizar">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <SELECT name='EmpleadoFinalizar' id='EmpleadoFinalizar' class='form-control'></SELECT>
                                            </div>
                                            <div class="col-lg-10">
                                                <input type='text' name="observacionFinalizado" class='form-control'>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>

                        <hr>
                        <form id="formInventario">
                            <input type="hidden" name="idproduccion" id="idproduccion">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            REDUCIR ARTICULOSDE INVENTARIO
                                        </div>

                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <div class="row">
                                                    <div>
                                                        <div class="col-lg-3">
                                                            <label class="control-label">LÍNEA</label>
                                                            <select class="form-control" name="linea" id="lineaArticulo">
                                                                <option value="0">SELECCIONE UNA OPCIÓN</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label class="control-label">ARTICULO</label>
                                                            <select class="form-control" name="articulo" id="agregarArticulo"></select>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <label class="control-label">SUB ARTICULO</label>
                                                            <select class="form-control" name="subarticulo" id="agregarsubArticulo"></select>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <label class="control-label">CANTIDAD</label>
                                                            <input type="text" name="cantidad" id="cantidad" class="form-control">
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <label class="control-label">MERMA</label>
                                                            <input type="text" name="merma" id="merma" class="form-control">
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <button type="button" class="btn-lg btn-info" onclick="btnagregaInventario()">AGREGAR</button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div>
                                                        <div class="col-lg-12">
                                                            <label class="control-label">MOTIVO</label>
                                                            <input type="text" name="motivo" id="motivo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row" style="background: #ebebeb">
                                                    <div style="color: #E21800">
                                                        <div class="col-lg-2">
                                                            <label class="control-label">LÍNEA</label>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <label class="control-label">ARTICULO</label>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <label class="control-label">SUB ARTICULO</label>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <label class="control-label">CANTIDAD</label>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <label class="control-label">MERMA</label>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label class="control-label">MOTIVO</label>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div id="registrosarticulos">
                                                    NO SE ENCUENTRAN REGISTROS
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            REDUCIR ARTICULOSDE INVENTARIO
                                        </div>

                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12" id="articulosUtilizados">
                                                        <label class="control-label">ARTICULOS UTILIZADOS</label>
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <label class="control-label">ARTICULO</label>
                                                            </div>
                                                            <!--<div class="col-lg-2">
                                                                <label class="control-label">COTIZADO</label>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <label class="control-label">UTILIZADO</label>
                                                            </div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                            <button type="button" class="btn btn-primary" onclick="guardarTurnar()">TURNAR</button>
                            <button type="button" class="btn btn-primary" onclick="guardar()">FINALIZAR</button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    
    <div class="modal fade" id="Cancelacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">CANCELACION</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                           DETALLE CANCELACION
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formCancelacion">

                                    <table class="table">
                                        
                                        <tbody id="detallesCancelacion">
                                                                                  
                                        </tbody>
                                    </table>
                                    DESCRIPCIÓN DE CANCELACIÓN
                                    <textarea name="texto" id="texto" style="width: 100%"></textarea>
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="cancelar()">CANCELAR</button>
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
    <script src="js/Modulos/pv/scripts.js"></script>
    <script src="js/Modulos/General.js"></script>

</body>

</html>
