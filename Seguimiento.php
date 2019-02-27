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

    <title>PRODUCCION</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="css/sb-admin-2.css" rel="stylesheet">

    <link href="css/general.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

  </head>
<?php
 if(true)
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
                            creaMenu(21);
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
                        <h1 class="page-header"><i class="fa fa-calendar"></i> Follow Up</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Seguimiento
                                <!-- Single button -->
                                <div class="btn-group pull-right">
                                   <button type="button" class="btn btn-default" title="Agregar Cliente" id="btn_agregar"><span class='fa fa-plus'></span></button>
                                   <button type="button" class="btn btn-info" title="Reporte" id="btn_reporte"><span class='fa fa-print'></span></button>

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
                                                    <buttom type="button" class="btn btn-info" id="buscar" onclick="actualizaDatagrid()">BUSCAR</buttom>
                                                </ul>

                                            </div>
                                        </div>
                                <form  id="FormDatagrid">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <td>CLIENTE</td>
                                            <td>DATOS</td>
                                            <td>ESTATUS</td>
                                            <td align="center"><i class="fa fa-calendar" style="color: #555555"></i></td>
                                            <td align="center"><i class="fa fa-check-square-o"></i></td>
                                        </tr>
                                    </thead>
                                  <tbody id="dataseguimiento">
                                  	<tr>
                                    	<td colspan="9">NO SE ENCUENTRAN REGISTROS</td>
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
        <div class="modal-dialog modal-dialog-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Seguimiento</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <span id="Titulo_Modulo"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                            	<form  id="FormSeguimiento">
                            	<input type="hidden" name="id" id="id" >
                                    <div class="row">
                                        <div class="col-sm-12" style=" font-weight: bold">
                                            <div class="form-group">
                                                <label for="fechaLevantamiento" class="control-label">DATOS DE SEGUIMIENTO</label>

                                                <div id="datos"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="fechaLevantamiento" class="control-label">FECHA DE SEGUIMIENTO:</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="date" class="form-control" name="fecha" id="fecha" readonly="readonly" value="<?php echo date("Y-m-d"); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group  ">
                                                <label for="fechaLevantamiento" class="control-label">TIPO DE SEGUIMIENTO:</label>
                                                <select name="tipoSeguimiento" class="form-control" id="tipoSeguimiento"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group ">
                                                <label for="fechaLevantamiento" class="control-label">RESULTADO:</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                                    <textarea name="resultado" id="resultado" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-2 right">
                                            <button type="button" class="btn btn-info" id="agregarSeguimiento">AGREGAR</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div  class="row">
                                        <div class="col-sm-12">
                                            <div style="width: 100%; min-height: 100px; max-height: 300px; overflow-y: scroll;">
                                                <table class="table table-striped">
                                                    <thead style="background-color: #e1edf7">
                                                        <td>Datos</td>
                                                        <td>Resultado</td>
                                                    </thead>
                                                    <tbody  id="listaSeguimiento">
                                                    <tr>
                                                        <td colspan="2">NO SE ENCUENTRAN REGISTRO</td>
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
                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="guardarContacto">GUARDAR</button>
                </div>-->
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
    <script src="js/Modulos/Seguimiento/scripts.js"></script>
    
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