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
 if(isset($_SESSION['FINALIZADOS']) and $_SESSION['FINALIZADOS'] == 1 )
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
                            creaMenu(7);
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
                        <h1 class="page-header"><i class="fa fa-check-square-o fa-fw"></i> Tablero de Procesos

                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Monto Total en Producción: <b style="color: #E21800; font-size: 14pt"><span id="Desglose_Monto"></span></b>
                                <div class="btn-group pull-right">
                                    <!--<button type="button" class="btn btn-primary" title="Reporte General de Pendientes" onclick="window.open('reportes\tablero\Reporte_General_Tablero.php','_blank');"><span class='fa fa-print'></span></button>-->
                                    <button type="button" class="btn btn-default" title="Reporte General de Tablero" onclick="reporte_tablero()"><span class='fa fa-print'></span></button>
                                    <!--<button type="button" class="btn btn-success" onclick="porFinalizar()"><span class='fa fa-check'></span></button>
                                    <button type="button" class="btn btn-info" onclick="noIniciadas()"><span class='fa fa-pause'></span></button>
                                   <button type="button" class="btn btn-primary" onclick="actualizaDatagrid()"><span class='fa fa-refresh'></span></button>-->
                                   <button type="button" class="btn btn-DANGER" onclick="finalizarDocumento()"><span class='fa fa-check'></span></button>

                                </div>  
                            </div>
                            <!-- /.panel-heading -->
                            <form method="post" id="filter_tablero" action="reportes/tablero/reporteTablero.php" target="_blank">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div id="dataTables-example_filter" class="dataTables_filter">
                                                        <label>Folio:<input type="text" name="folio" id="search" class="form-control input-sm " aria-controls="dataTables-example"></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div id="dataTables-example_filter" class="dataTables_filter">
                                                        <label>Cliente:<input type="text" name="cliente" id="client" class="form-control input-sm " aria-controls="dataTables-example"></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div id="dataTables-example_filter" class="dataTables_filter">
                                                        <label>Activos:
                                                        <select class="form-control" name="activos" onchange="tableroPendientes(this.value)" id='pendientesTablero'>
                                                            <option value="0">TODOS</option>
                                                            <option value="1">DISEÑO</option>
                                                            <option value="2">IMPRESION</option>
                                                            <option value="3">MAQUILAS</option>
                                                            <option value="4">INSTALACION</option>
                                                            <option value="5">ENTREGA</option>
                                                            <option value="6">PREPARACIÓN</option>
                                                        </select>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div id="dataTables-example_filter" class="dataTables_filter">
                                                        <label>Estatus:
                                                        <select class="form-control" name="estatus" id='estatusTablero' onchange="estatusPendientes(this.value)">
                                                            <option value="0">TODOS</option>
                                                            <option value="1">PENDIENTES</option>
                                                            <option value="2">FINALIZADOS</option>
                                                        </select>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                        <thead>
                                            <tr role="row">
                                                <td style="width: 130px;">FOLIO</td>
                                                <td style="width: 140px;">FECHA</td>
                                                <!--<th style="width: 300px;">CLIENTE</th>-->
                                                <td>DESCRIPCIÓN</td>
                                                <td style="width: 10px; text-align:center"><span class="fa fa-apple" title='DISEÑO'></span></td>
                                                <td style="text-align:center" width="10"><span class="fa fa-print" title='IMPRESIÓN'></span></td>
                                                <td style="width: 30px;text-align:center"><span class="fa fa-file-image-o" title='MAQUILAS'></span></td>
                                                <td style="width: 30px;text-align:center"><span class="fa fa-wrench" title='PREPARACION'></span></td>
                                                <td style="width: 30px;text-align:center"><span class="fa fa-truck" title='INSTALACIÓN'></span></td>
                                                <td style="width: 30px;text-align:center"><span class="glyphicon glyphicon-lock" title='ENTREGA'></span></td>


                                                <!--<th style="width: 30px;text-align:center"><span class="fa fa-close" title='CANCELAR'></span></th>-->
                                                <!--<th style="width: 30px;text-align:center"><span class="fa fa-check" title='TERMINAR'></span></th>-->
                                                <td style="width: 30px;text-align:center"><input type="checkbox" name="check" onclick="verificar(this)"></td>

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

    <div class="modal fade" id="myModal"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
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

                                <form  id="FormProcesos">
                                    <input type="hidden" name="DOCTO_VE_ID" id="DOCTO_VE_ID">    
                                    <div class="row" id="factura">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="fecha" id="foliofactura" class="control-label " style="color:#00F">FOLIO:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="fecha" id="fechafactura" class="control-label " style="color:#00F">FECHA:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="fecha" id="clientefactura" class="control-label " style="color:#00F">CLIENTE:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="fecha" id="descripcionfactura" class="control-label " style="color:#00F">DESCRIPCION:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>    
                                                <th style="width:30%">Nombre</th>
                                                <th>Unidades</th>
                                                <th style="width:50%">Notas</th>
                                                <th><span class="fa fa-check"></span></th>
                                                <th><span class="fa fa-apple"></span></th>
                                                <th><span class="fa fa-print"></span></th>
                                                <th><span class="fa fa-truck"></span></th>
                                                <th><span class="fa fa-exclamation-triangle"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_productos">
                                            
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
                    <button type="button" class="btn btn-primary" id='guardarProduccion'>Guardar</button>
                    <button type="button" class="btn btn-primary" id='guardarCerrarProduccion'>Guardar y Cerrar Documento</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->


     <div class="modal fade" id="informacion"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
         <div class="modal-dialog modal-dialog-75-screen">
             <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Información</h4>
                </div>

                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Información
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive" id="panel_informacion">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cambio_fecha"  tabindex="-1" role="dialog" aria-labelledby="modalCatalogoLabel" aria-hidden="true" data-backdrop="static">
         <div class="modal-dialog modal-dialog">
             <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Cambiar Fecha de Entrega</h4>
                </div>

                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Fecha de Entrega
                        </div>

                        <div class="panel-body">
                            <form id="cambio_fecha">
                                <input id="id_fecha" type="hidden">
                                <div class="row">
                                    <div class="col-sm-12">
                                        Folio:  <span id="folio_fecha"></span>
                                    </div>
                                    <div class="col-sm-12">
                                        Cliente:  <span id="cliente_fecha"></span>
                                    </div>
                                    <div class="col-sm-12">
                                        Datos:  <span id="datos_fecha"></span>
                                    </div>
                                    <div class="col-sm-12">
                                        Fecha Entrega:  <input type="date" class="form-control input" id="fecha_hora" value="2020-01-01">
                                        
                                    </div>
                                    <div class="col-sm-12">
                                        Hora Entrega:  
                                        <input type="time" class="form-control input" id="hora">
                                    </div>
                                </div>
                           </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="guardar_cambio()">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
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
    
    <script src="js/Modulos/Finalizados/scripts.js" language="javascript"></script>
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