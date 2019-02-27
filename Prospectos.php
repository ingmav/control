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
 if(isset($_SESSION['LEVANTAMIENTOS']) and $_SESSION['LEVANTAMIENTOS'] == 1 )
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
                            creaMenu(20);
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
                        <h1 class="page-header"><i class="fa fa-users"></i> CRM Clientes</h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Prospectos / Clientes
                                <!-- Single button -->
                                <div class="btn-group pull-right">
                                <button type="button" class="btn btn-warning" title="Backup" id="btn_descargar"><span class='fa fa-download'></span></button>
                                   <button type="button" class="btn btn-info" title="Enviar Mail" id="btn_mail"><span class='fa fa-at'></span></button>
                                   <button type="button" class="btn btn-default" title="Agregar Cliente" id="btn_agregar"><span class='fa fa-plus'></span></button>
                                   <button type="button" class="btn btn-default" title="Editar Cliente" id="editarCliente"><span class='fa fa-edit'></span></button>
                                   <button type="button" class="btn btn-default" title="Eliminar Cliente" id="bajaCliente"><span class='fa fa-close'></span></button>
                                </div>
                               
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">

                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <input type="text" class="form-control" id="clientefiltro" placeHolder='CLIENTE' ONBLUR="$(this).val($(this).val().toUpperCase())">
                                                    <select class="form-control" id="estatusfiltro">
                                                        <option value="1">PROSPECTO</option>
                                                        <option value="2">CLIENTE</option>
                                                    </select>
                                                    <select class="form-control" id="segmentofiltro">
                                                        <option value="0">TODOS</option>
                                                    </select>
                                                    <buttom type="button" class="btn btn-info" id="buscar" onclick="actualizaDatagrid()">BUSCAR</buttom>
                                                </ul>

                                            </div>
                                        </div>
                                <form  id="FormDatagrid">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <td width="70px">NOMBRE Y CLASE</td>

                                            <td>DATOS</td>
                                            <!--<td>SEGMENTO</td>-->
                                            <!--<td>TELÉFONO(S)</td>
                                            <td>CORREO</td>
                                            <td>DIRECCIÓN</td>-->
                                            <td align="center"><i class="fa fa-envelope" style="color: #555555"></i></td>
                                            <td align="center"><i class="fa fa-check-square-o"></i></td>
                                        </tr>
                                    </thead>
                                  <tbody id="dataprospectos">
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
    
    <div class="modal fade" id="cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Prospecto / Cliente</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <span id="Titulo_Modulo"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                            	<form  id="FormProspecto">
                            	<input type="hidden" name="id" id="id" >

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12" style="color: #E21800; font-weight: bold">
                                                <div class="form-group">
                                                    <label for="fechaLevantamiento" class="control-label">DATOS DE LA EMPRESA</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">NOMBRE DEL CLIENTE:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                        <input type="text" class="form-control" name="nombreCliente" id="nombreCliente" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group  ">
                                                    <label for="fechaLevantamiento" class="control-label">PÁGINA WEB:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                        <input type="text" class="form-control" name="pagina" id="pagina"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--<div class="col-sm-2">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">EMAILING:</label>
                                                    <input type="checkbox" checked="checked" name="emailing" id="emailing">
                                                </div>
                                            </div>-->
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">TIPO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        <select class="form-control" id="tipoCliente" name="tipoCliente"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">CLASE:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-star"></i></span>
                                                        <select class="form-control" id="clase" name="clase"></select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">SEGMENTO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-thumbs-o-up"></i></span>
                                                        <select class="form-control" id="clasificacion" name="clasificacion">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">ESTATUS SEGUIMIENTO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-thumbs-o-up"></i></span>
                                                        <select class="form-control" id="estatusSeguimiento" name="estatusSeguimiento">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group ">
                                                    <label for="typeclient" class="control-label">TIPO DE CLIENTE:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-thumbs-o-up"></i></span>
                                                        <select class="form-control" id="t_cliente" name="t_cliente">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">OBSERVACIÓN:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                        <textarea class="form-control" name="observacion" id="observacion"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">

                                        <div class="row">
                                            <div class="col-sm-12" style="color: #E21800; font-weight: bold">
                                                <div class="form-group">
                                                    <label for="fechaLevantamiento" class="control-label" >DATOS DEL CONTACTO</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">CONTACTO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-plus-circle"></i></span>
                                                        <select class="form-control" name="num_contacto" id="num_contacto">
                                                            <option value="0">CONTACTO NUEVO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">CONTACTO 1:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        <input type="text" class="form-control" name="contacto1" id="contacto1"/>
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">TELEFONO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                        <input type="text" class="form-control" name="telefono1" id="telefono1"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">CONTACTO 2:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        <input type="text" class="form-control" name="contacto2" id="contacto2"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">TELEFONO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                        <input type="text" class="form-control" name="telefono2" id="telefono2"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">CORREO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">@</span>
                                                        <input type="text" class="form-control" name="correo" id="correo"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">HORARIO:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                        <input type="text" class="form-control" name="horario" id="horario"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">DIRECCIÓN (CALLE, NÚMERO, COLONIA, C.P., CIUDAD, ESTADO):</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                        <textarea name="direccion" id="direccion" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">CONTACTO PRINCIPAL:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                        <select class="form-control" name="principal" id="principal">
                                                            <option value="1">SI</option>
                                                            <option value="0">NO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <label for="fechaLevantamiento" class="control-label">EMAILING:</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                        <select class="form-control" name="emailing" id="emailing">
                                                            <option value="1">SI</option>
                                                            <option value="0">NO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <button type="button" class="btn btn-primary form-control" id="guardarContacto">GUARDAR CONTACTO</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group ">
                                                    <div class="form-group ">
                                                        <button type="button" class="btn btn-danger form-control" id="eliminarContacto">ELIMINAR CONTACTO</button>
                                                    </div>
                                                </div>
                                            </div>
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
                    <button type="button" class="btn btn-primary" id="guardarCliente">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <!-- -->
    <div class="modal fade" id="envio_email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Email</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span id="Titulo_Modulo"></span>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form  id="">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group ">
                                                <label for="fechaLevantamiento" class="control-label">Email Activos</label>
                                                <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                <textarea name="lista_email" id="lista_email" class="form-control" style="min-height: 400px"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="actualiza_lista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Actualizar con Microsip</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span id="Titulo_Modulo"></span>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form  id="">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group ">
                                                <label for="fechaLevantamiento" class="control-label">Progreso</label>
                                                <div>
                                                    En espera <i class="fa fa-clock-o"></i>
                                                </div>
                                                <!--<div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                        <span class="sr-only"></span>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="actualiza_crm"><i class="fa fa-refresh"></i>Actualizar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->
    <!-- -->
    <div class="modal fade" id="cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog-fullscreen">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Levantamientos</h4>
    </div>
    <div class="modal-body">
    <div class="panel panel-default">
    <div class="panel-heading">
        <span id="Titulo_Modulo"></span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
    <div class="table-responsive">
    <form  id="FormLevantamiento">
    <input type="hidden" name="id" id="id" >

    <div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12" style="color: #E21800; font-weight: bold">
                <div class="form-group">
                    <label for="fechaLevantamiento" class="control-label">DATOS DE LA EMPRESA</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">NOMBRE EMPRESA:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                        <input type="text" class="form-control" name="nombreEmpresa" id="nombreEmpresa" />
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group  ">
                    <label for="fechaLevantamiento" class="control-label">PÁGINA WEB:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                        <input type="text" class="form-control" name="pagina" id="pagina"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">EMAILING:</label>
                    <input type="checkbox" checked="checked" name="emailing" id="emailing">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">DIRECCIÓN (CALLE, NÚMERO, COLONIA, C.P., CIUDAD, ESTADO):</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                        <textarea name="direccion" id="direccion" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">OBSERVACIÓN:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                        <textarea class="form-control" name="observacion" id="observacion"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12" style="color: #E21800; font-weight: bold">
                <div class="form-group">
                    <label for="fechaLevantamiento" class="control-label" >DATOS DEL CONTACTO</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">NOMBRE COMPLETO:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="nombre" id="nombre"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">CARGO:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="cargo" id="cargo"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">TELÉFONO FIJO:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input type="text" class="form-control" name="telefonofijo" id="telefonofijo"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">EXTENSIÓN.:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input type="text" class="form-control" name="extension" id="extension"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">TELÉFONO MOVIL:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input type="text" class="form-control" name="telefonomovil"  id="telefonomovil"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">CORREO:</label>
                    <div class="input-group">
                        <span class="input-group-addon">@</span>
                        <input type="text" class="form-control" name="correo" id="correo"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">HORARIO:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        <input type="text" class="form-control" name="horario" id="horario"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">TIPO DE CLIENTE:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <select class="form-control" id="tipoCliente" name="tipoCliente"></select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">CLASE:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-star"></i></span>
                        <select class="form-control" id="clase" name="clase"></select>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">CLASIFICACION:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-thumbs-o-up"></i></span>
                        <select class="form-control" id="clasificacion" name="clasificacion">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group ">
                    <label for="fechaLevantamiento" class="control-label">ESTATUS SEGUIMIENTO:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-thumbs-o-up"></i></span>
                        <select class="form-control" id="estatusSeguimiento" name="estatusSeguimiento">

                        </select>
                    </div>
                </div>
            </div>

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
        <button type="button" class="btn btn-primary" id="guardarContacto">GUARDAR</button>
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
    <script src="js/Modulos/Prospectos/scripts.js"></script>
    
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