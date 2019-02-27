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
                        <h1 class="page-header"><i class="fa fa-archive"></i> Caja</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Caja
                                 <div class="btn-group pull-right">
                                 
                                 <button type="button" class="btn btn-default" id="iniciarcaja" onclick="inicializaCaja();" title="INICIA CAJA"><span class='fa fa-check-square'></span></button>
                                 <button type="button" class="btn btn-default" id="reportecaja" onclick="reporte();" title="REPORTE DE CAJA"><span class='fa fa-print'></span></button>
                                 <button type="button" class="btn btn-default" id="agregarcaja" onclick="$('#agregar').modal('show');limpia('formAgregaCaja');$('#importeTotal').html('$ 0.00');$('#importeAnticipo').html('$ 0.00');$('#importeSaldo').html('$ 0.00');$('#formAgregaCaja #docto_ve').val('Null');$('#formAgregaCaja #tipoDocumento').val('I');$('#formAgregaCaja #empresaCaja').val('1');" title="AGREGAR A CAJA"><span class='fa fa-plus-square' style='color:#E21800'></span></button>
                                 <button type="button" class="btn btn-default" id="disminuircaja" onclick="$('#sustraer').modal('show');limpia('formSustraeCaja');" title="DISMINUIR A CAJA"><span class='fa fa-minus-square'  style='color:#008C00'></span></button> 
                                 <button type="button" class="btn btn-danger" id="cancelarcaja" onclick="cancelaCaja();" title="CANCELAR DE CAJA"><span class='fa fa-close'></span></button>  
                                 </div>  
                            </div>
                            <!-- /.panel-heading -->
                            <form id='datagridCaja'>  
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <ul class="nav nav-pills">
                                                        DE:
                                                         <input type="date" name="fecha_inicio" value='<?php echo date("Y-m-d"); ?>' class='form-control'>
                                                         A:
                                                        <input type="date" name="fecha_finalizado" value='<?php echo date("Y-m-d"); ?>' class='form-control'>  
                                                        <button type="button" class="btn btn-primary" onclick="actualizaDatagrid()">BUSCAR</button>     
                                                    </ul>
                                                
                                                </div>
                                               
                                                
                                            </div>
                                        </div>
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                      	
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th style="width: 130px;">FOLIO</th>
                                                    <th style="width: 120px;">FECHA</th>
                                                    <th style="width: 300px;">CLIENTE/PROVEEDOR</th>
                                                    <th style="width: 450px">DESCRIPCIÓN</th>
                                                    <th style="width: 100px;">TIPO</th> 
                                                    <th style="width: 110px;">IMPORTE</th>
                                                    <th style="width:130px;"></th>
                                                </tr>
                                            </thead>
                                         <form id="datagridCaja">   
                                         <tbody id="data">
                                        
                                         </tbody>
                                         </form>
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
        <div class="modal-dialog modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">INGRESOS</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR A CAJA ( <?PHP echo date("d-m-Y"); ?> )
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formAgregaCaja">
                                    <input type="hidden" name="docto_ve" id="docto_ve">
                                    <input type="hidden" name="empresa" id="empresaCaja">
                                    <input type="hidden" name="tipoDocumento" id="tipoDocumento">
                                    <div class="row">
                                        <div class="col-sm-8">                                            
                                            <div class="row">
                                           
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="tipoDocumento" class="control-label">FOLIO:</label>
                                                        <div class="input-group">
                                                          <input type="text" class="form-control" name='folio' id="folio" placeholder="FOLIO" >
                                                          <span class="input-group-addon" style='cursor:pointer' onclick="verBuscador();"><i class='fa fa-filter'></i></span>
                                                        </div>
                                                           
                                                    </div>   
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label for="tipoDocumento" class="control-label">CLIENTE:</label>
                                                        <input type='text' class="form-control" name='cliente' id='cliente'>
                                                    </div>   
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                 <div class="col-sm-4">
                                                    <label for="tipoDocumento" class="control-label">INGRESO</label>
                                                    <input type='text' class="form-control" name="importe" id="importe">   
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label for="tipoDocumento" class="control-label">DESCRIPCION:</label>
                                                        <TEXTAREA class='form-control' style='resize:none' rows='5' name='descripcion' id='descripcion'></TEXTAREA>
                                                    </div>   
                                                </div>
                                            </div>
                                          
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">

                                                <table class="table table-bordered">
                                                    
                                                    <tbody>
                                                        <tr  style="font-size:11pt">
                                                            <td>
                                                                <label for="importeTotal" class="control-label">IMPORTE TOTAL</label>    
                                                            </td>
                                                            <td align="right" id="importeTotal">
                                                                $ 0.00
                                                            </td>
                                                        </tr>
                                                        <tr  style="font-size:11pt">
                                                            <td>
                                                                <label for="importeTotal" class="control-label">ANTICIPO</label>    
                                                            </td>
                                                            <td align="right" id="importeAnticipo">
                                                                $ 0.00
                                                            </td>
                                                        </tr>
                                                        <tr  style="font-size:12pt">
                                                            <td>
                                                                <label for="importeTotal" class="control-label">SALDO</label>  
                                                            </td>
                                                            <td align="right" id="importeSaldo">
                                                                $ 0.00
                                                            </td>
                                                        </tr>    
                                                    </tbody>
                                                    
                                                </table>
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
                    <button type="button" class="btn btn-primary" onclick="guardarCaja()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="sustraer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog ">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">EGRESOS</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            SUSTRAER DE CAJA
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formSustraeCaja">
                                    
                                     <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">FECHA:</label>
                                                <label for="fecha" class="control-label" style="color:#E21800"><?PHP echo date("d-m-Y"); ?></label>
                                                                                              
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">EMPRESA:</label>
                                                <label for="tipoDocumento" class="control-label">NEXOS EMPRESARIALES</label>
                                                    
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">TIPO:</label>
                                                <label for="tipoDocumento" class="control-label">EGRESO</label>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">FOLIO:</label>
                                                <input type='text' class="form-control" name='folio' id="folio" >
                                            </div>   
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">PROVEEDOR:</label>
                                                <input type='text' class="form-control" name='cliente' id='cliente'>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">IMPORTE:</label>
                                                <input type='text' class="form-control" name="importe" id="importe">
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="tipoDocumento" class="control-label">DESCRIPCION:</label>
                                                <TEXTAREA class='form-control' style='resize:none' rows='5' name='descripcion' id='descripcion'></TEXTAREA>
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
                    <button type="button" class="btn btn-primary" onclick="sustraerCaja()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="helper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen ">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">BUSCAR DOCUMENTO</h4>
                </div>
                <form id="formhelper">
                    <div class="modal-body">
                       <div class="panel panel-default">
                            <div class="panel-heading">
                                BUSCAR DOCUMENTO
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    
                                         <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipoDocumento" class="control-label">EMPRESA:</label>
                                                    
                                                        <select class="form-control" name='empresa' id="empresa">
                                                            <option value="1">NEXOS EMPRESARIALES</option>
                                                            <option value="2">NEXPRINT</option>
                                                        </select>
                                                   
                                                </div>   
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipoDocumento" class="control-label">TIPO DE RECIBO:</label>
                                                    <select class="form-control" name='tipo' id="tipo">
                                                        <option value="F">FACTURA</option>
                                                        <option value="R">REMISIÓN</option>
                                                    </select>
                                                </div>   
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipoDocumento" class="control-label">FOLIO:</label>
                                                    <input type="text" class="form-control" id="foliocaja">
                                                </div>   
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipoDocumento" class="control-label">DESCRIPCIÓN:</label>
                                                    <input type="text" class="form-control"  id="descripcioncaja">
                                                </div>   
                                            </div>
                                        </div>    
                                         
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                RESULTADOS
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-sm-1" align="center">
                                        </div>
                                        <div class="col-sm-1" align="center">
                                            FOLIO
                                        </div>
                                        <div class="col-sm-4"  align="center">
                                            CLIENTE
                                        </div>
                                        <div class="col-sm-4" align="center">
                                            DESCRIPCION
                                        </div>
                                        <div class="col-sm-1" >
                                            IMPORTE
                                        </div>
                                        <div class="col-sm-1" >
                                            ANTICIPO
                                        </div>
                                    </div>        
                                     <div style="max-height:300px;min-height:300px; overflow-y: scroll; overflow-x:hidden;" id="registros">
                                     </div>
                                         
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                        <button type="button" class="btn btn-primary" onclick="seleccionarDocumento();">SELECCIONAR</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

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
    <script src="js/Modulos/Caja/scripts.js"></script>
    <script src="js/Modulos/General.js"></script>

</body>

</html>
