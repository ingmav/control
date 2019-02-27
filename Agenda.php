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

        <title>MICROSIP WEB 2.0 - AGENDA</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link href="css/general.css" rel="stylesheet">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <style>
        #contenedor_principal
        {
            width: 100%;
            /*border: 1px solid #000000;*/
        }

        #panel_cabecera div div
        {
            float: left;
            width: 3.6%;
            border: 1px solid #000000;
            text-alig: left;
            position: relative;
        }

        .titulo
        {
            width: 14%;
            /* border: 1px solid #000; */
            height: 100px;
            font-size: 15pt;
            background-color: #4285f4;
            border-bottom: 1px solid #FFF;
            color: #FFF;
            font-family: tahoma;
        }

        .contenedor1
        {
            background-color: rgba(127,181,181,0.1);
            border-bottom: 1px solid rgba(0,0,100,0.2);
        }

        .titulo div
        {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 100%;
        }

        .tiempo
        {
            width: 86%;
            height: 100px;
            /*border-bottom: 1px dotted #000;
            border-top: 1px dotted #000;
            border-right: 1px dotted #000;*/
        }

        .tiempo > div
        {
            width: 4.54%;
            /*border: 1px solid #000;*/
            float: left;
            height: 100px;
            /*border-bottom: 1px dotted #000;
            border-top: 1px dotted #000;
            border-right: 1px dotted #000;*/
            font-size: 10px;
        }

        .tiempo, .titulo
        {
            float: left;
        }

        .tarea{
            display: -webkit-inline-box;
            width: 23%;
            height: 150px;
            text-align: justify;
            padding: 15px;
            text-overflow: ellipsis;
            overflow: hidden;
            font-size: 8pt;
            position: relative;
            margin-right: 10px;
            margin-bottom: 10px;

        }

        .tarea_corporativo
        {
            background-color: rgba(0,176,240,0.2);
        }

        .tarea_mostrador
        {
            background-color: rgba(193,224,103,0.5);
        }
        
        .agendar
        {
            position: absolute;
            /*margin-left: -40px;*/
            top: 0;
            right: 0;
        }

        .funciones
        {
            position: absolute;
            right: 5;
            bottom: 10;
        }

        .hrs div
        {
            font-size: 15pt;
            border-right: 1px solid rgba(0,0,200,0.2);
            border-bottom: 1px solid #FFF;
            vertical-align: bottom;
            background-color: #4285f4;
            color: #FFF;
            height: 40px;
        }

        .agendado
        {
            padding: 5px;
        }

        .agenda_usuario
        {
            /*background-color: rgba(170,0,170,0.2);*/
            border-right: 1px solid rgba(170,0,170,0.4);
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
                        creaMenu(25);
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
                        <h1 class="page-header"><i class="fa fa-calendar"></i> Agenda</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Agenda

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button type="button" class="btn btn-default" onclick="$('#Reporteproductividad').modal('show')"><i class="fa fa-print"></i> % productividad</button>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group pull-right">

                                                <select name="departamento" id="departamento" class="form-control" onchange="actualizaDatagrid();recarga_eficiencia()">
                                                    <option value="2" selected="selected">DISEÑO</option>
                                                    <option value="3">IMPRESION</option>
                                                    <option value="4">INSTALACIÓN</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group pull-right">
                                                <span class="input-group-addon" id="basic-addon1" style="cursor: pointer" onclick="actualizaDatagrid()"><i class="fa fa-arrow-circle-left"></i></span>
                                                <input class="form-control" type="date" name="dia" id="dia" value="<?php echo date("Y-m-d") ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="dropdown">
                                              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Reportes
                                                <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="#" onclick="ver_Actividades_Extras();">Actividades Extra</a></li>
                                                <li><a href="#" onclick="reporteAgenda();" >Reporte Agenda</a></li>
                                                
                                              </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" class="btn btn-success" onclick="GuardaBD();" title="Guardar Agenda"><span class='fa fa-save'></span></button>
                                            <button type="button" class="btn btn-info" onclick="AgregarTarea();" title="Agregar Tarea"><span class='fa fa-plus'></span></button>
                                        </div>
                                        <!--<div class="col-sm-1">
                                            <button type="button" class="btn btn-warning" onclick="();" title="Guardado Borrador"><span class='fa fa-eraser'></span></button>
                                        </div>-->
                                    </div>

                            </div>
                            <!-- /.panel-heading -->
                            <form id="formcapacidad">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <ul class="nav nav-pills">

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="contenidoAgenda" class="dataTables_wrapper form-inline" role="grid">
                                            <div id="contenedor_general">
                                                <div class="encabezado_agenda">
                                                    <div class="titulo" style="height: 40px">
                                                        <div style="width: 100%">Operador / Tiempo</div>
                                                    </div>
                                                    <div class="tiempo hrs" style="height: 40px">
                                                        <div>9</div>
                                                        <div></div>
                                                        <div>10</div>
                                                        <div></div>
                                                        <div>11</div>
                                                        <div></div>
                                                        <div>12</div>
                                                        <div></div>
                                                        <div>1</div>
                                                        <div></div>
                                                        <div>2</div>
                                                        <div></div>
                                                        <div>3</div>
                                                        <div></div>
                                                        <div>4</div>
                                                        <div></div>
                                                        <div>5</div>
                                                        <div></div>
                                                        <div>6</div>
                                                        <div></div>
                                                        <div>7</div>
                                                        <div></div>
                                                        
                                                    </div>

                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="row" style="background-color:#428bca; color:#FFF; margin-left: 0px;width: 100%; text-align: center; font-weight: bold;">
                                        <div class="col-sm-12" >
                                            TAREAS GUARDADAS
                                        </div>
                                    </div>
                                </div>    
                                <div class="lista_borrador">

                                </div>
                                <br>
                                <div class="lista_tareas">

                                </div>
                            </form>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /fin tabla primaria -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">AGREGAR TAREA</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR TAREA
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formTarea">
                                    <input type='hidden' name="id_general" id="id_general">
                                    <input type='hidden' name="identifiicador" id="identificador">
                                    <input type='hidden' name="idOperador" id="idOperador">
                                    <input type='hidden' name="color" id="color">
                                    <input type='hidden' name="registro" id="registro">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Folio</label>
                                                <input type="text" class="form-control" name="folio" id="folio">
                                            </div>
                                        </div>
                                    
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label class="control-label">Descripción</label>
                                                <input type="text" class="form-control" name="descripcion" id="descripcion">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Tiempo Programado</label>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Hrs" name="hr" id="hr" value="1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Min" name="min" id="min" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Tiempo Real</label>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Hrs" name="hr2" id="hr2" value="1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Min" name="min2" id="min2" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Integrantes</label>
                                                <select multiple="multiple" class="form-control" style="height: 120px" name="addoperador" id="addoperador">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Observación</label>
                                                <textarea class="form-control"  style="height: 120px" name="observacion" id="observacion"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">Estatus</label>
                                                <select class="form-control" name="estatus" id="estatus">
                                                   <option value="1">PENDIENTE</option>     
                                                   <option value="2">INICIADO-NO FINALIZADO</option>     
                                                   <option value="3">INICIADO-FINALIZADO PARCIALMENTE</option>     
                                                   <option value="4">EN VALIDACIÓN</option>     
                                                   <option value="5">FINALIZADO</option>     
                                                </select>
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
                    <button type="button" class="btn btn-primary btn_guardar_principal" onclick="guardarTarea()">GUARDAR</button>
                    <button type="button" class="btn btn-primary btn_guardar_borrador" onclick="guardarTareaBorradorExtra()">GUARDAR EN BORRADOR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="AddFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">AGREGAR TAREA</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            AGREGAR TAREA
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formTareaFactura">

                                    <input type='hidden' name="docto_ve_id" id="docto_ve_id">
                                    <input type='hidden' name="docto_ve_det_id" id="docto_ve_det_id">
                                    <input type='hidden' name="empresa" id="empresa">
                                    <input type='hidden' name="id_general" id="id_general">
                                    <input type='hidden' name="identificador" id="identificador">
                                    <input type='hidden' name="idOperador" id="idOperador">
                                    <input type='hidden' name="color" id="color">
                                    <input type='hidden' name="registro" id="registro">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">Folio</label>
                                                <input type="text" class="form-control" name="folio" id="folio" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha Entrega</label>
                                                <input type="text" class="form-control" name="entrega" id="entrega" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label class="control-label">Cliente</label>
                                                <input type="text" class="form-control" name="cliente" id="cliente" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Tiempo Programado</label>
                                                <div class="row">

                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Hrs" name="hr" id="hr">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Min" name="min" id="min">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Tiempo Real</label>
                                                <div class="row">

                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Hrs" name="hr2" id="hr2">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" placeholder="Min" name="min2" id="min2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Estatus</label>
                                                <select class="form-control" name="estatus" id="estatus">
                                                   <option value="1">PENDIENTE</option>     
                                                   <option value="2">INICIADO-NO FINALIZADO</option>     
                                                   <option value="3">INICIADO-FINALIZADO PARCIALMENTE</option>     
                                                   <option value="4">EN VALIDACIÓN</option>     
                                                   <option value="5">FINALIZADO</option>     
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">   
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">Descripción</label>
                                                <input type="text" class="form-control" name="descripcion" id="descripcion" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">   
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">Observacion</label>
                                                <input type="text" class="form-control" name="observacion" id="observacion">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                             
                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Nota</label>
                                                <textarea class="form-control" style="height: 80px" name="nota" id="nota" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Integrantes</label>
                                                <select multiple="multiple" class="form-control" style="height: 80px" name="addoperador" id="addoperador">

                                                </select>
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
                    <button type="button" class="btn btn-primary btn_guardar_principal" onclick="guardarTareaFactura()">GUARDAR</button>
                    <button type="button" class="btn btn-primary btn_guardar_borrador" onclick="guardarTareaBorrador()">GUARDAR EN BORRADOR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="ReporteExtras" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">AGREGAR TAREA</h4>
                </div>
                <form id="formReporteExtras" method="post" target="_blank" action="reportes/agenda/reporte_extra.php">
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                AGREGAR TAREA
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">FECHA DE INICIO</label>
                                                <input type="date" class="form-control" name="fecha_inicio">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">FECHA DE FINAL</label>
                                                <input type="date" class="form-control" name="fecha_final">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <select name="departamento" id="departamento" class="form-control">
                                                <option value="2">DISEÑO</option>
                                                <option value="4" selected="selected">INSTALACIÓN</option>
                                            </select>
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
                        <button type="submit" class="btn btn-primary">GENERAR REPORTE</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="Reporteproductividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">REPORTE</h4>
                </div>
                <form id="formReporteProductividad" method="post" target="_blank" action="reportes/productividad/reporte_diseno.php">
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                PRODUCTIVIDAD
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">FECHA DE INICIO</label>
                                                <input type="date" class="form-control" name="fecha_inicio">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">FECHA DE FINAL</label>
                                                <input type="date" class="form-control" name="fecha_final">
                                            </div>
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
                        <button type="submit" class="btn btn-primary">GENERAR REPORTE</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!--<script src="js/jquery-1.11.0.js"></script>-->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/Agenda/scripts.js"></script>
    <script src="js/Modulos/General.js"></script>
    <script>


    </body>
</html>
