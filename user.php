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
if(isset($_SESSION['EXTRA']) and $_SESSION['EXTRA'] == 1 )
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
                        <h1 class="page-header"><i class="fa fa-user"></i> Administración de Usuarios</h1>
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
                                                        <input type="text" name='usuario' id='usuario' class='form-control'>
                                                        <button type="button" class="btn btn-primary" id="buscar">BUSCAR</button>
                                                    </ul>
                                                </div>


                                            </div>
                                        </form>
                                        <form  id="FormDatagrid">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                                <thead>
                                                <tr role="row">
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">NOMBRE</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 354px;">ALIAS</th>
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

    <div class="modal fade" id="AgregarUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Usuario
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form  id="FormUser">
                                    <input type="hidden" name="id" id="id" >
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">NOMBRE:</label>
                                                <input type='text' class="form-control" name="nombre" id="nombre" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">ALIAS:</label>
                                                <input type='text' class="form-control" name="alias" id="alias" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">CONTRASEÑA:</label>
                                                <input type='password' class="form-control" name="contrasena" id="contrsena" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="caja" id="caja"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">CAJA</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="cotizacion" id="cotizacion"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">SEGUIMIENTO DE COTIZACIONES</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="extra" id="extra"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">ACTIVIDADES EXTRA</label>
                                            </div>
                                        </div>


                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="seleccion" id="seleccion"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">SELECCIÓN DE DOCUMENTOS</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="diseno" id="diseno"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">DISEÑO</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="impresion" id="impresion"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">IMPRESIÓN</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="instalacion" id="instalacion"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">INSTALACIÓN</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="entrega" id="entrega"></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">ENTREGA</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="maquilas" id="maquilas"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">MAQUILAS</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="tablero" id="tablero"></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">TABLERO</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="inventario" id="inventario"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">INVENTARIO</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="cobros" id="cobros"></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">COBROS</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label"><input type="checkbox" name="capacidad" id="capacidad"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">CAPACIDAD DE PRODUCCIÓN</label>
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
                    <button type="button" class="btn btn-primary" id="guardarUsuario">GUARDAR</button>
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
    <script src="js/Modulos/Admin/user/scripts.js"></script>
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