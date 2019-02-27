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
                        creaMenu(28);
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
                        <h1 class="page-header"><i class="fa fa-empire"></i> Garantía</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Garantía
                                <!-- Single button -->
                                <div class="btn-group pull-right">

                                    <!--<button type="button" class="btn btn-success" onclick="requisicionesrealizadas()"><span class='fa fa-check'></span></button>-->
                                    <button type="button" class="btn btn-default" onclick="reporte()"><span class='fa fa-print'></span></button>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        ACCIONES <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="agregar"><a href="#">AGREGAR</a></li>
                                        <li id="modificar"><a href="#">MODIFICAR</a></li>
                                        <li id="borrar"><a href="#">ELIMINAR</a></li>
                                    </ul>
                                </div>

                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <form id="buscador">
                                                        <input type="text" class="form-control" name="clientefiltro" id="clientefiltro" placeHolder='CLIENTE O FOLIO' ONBLUR="$(this).val($(this).val().toUpperCase())">
                                                        DESDE
                                                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-")."01"; ?>">
                                                        HASTA
                                                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"  value="<?php echo date("Y-m-d"); ?>">
                                                        <buttom type="button" class="btn btn-info" id="buscar" onclick="actualizaDatagrid()">BUSCAR</buttom>
                                                    </form>   
                                                </ul>

                                            </div>
                                        </div>
                                        <form  id="FormDatagrid">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                                <thead>
                                                <tr role="row">
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 70px;">FOLIO</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">FECHA</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 186px;">CLIENTE</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 186px;">MATERIAL</th>

                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">OPERADOR</th>
                                                    
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

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Garantía</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Agregar
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form  id="FormGarantia">
                                    <input type="hidden" name="id" id="id" >
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="foliorequisicion" class="control-label">FOLIO DE FACTURA O REMISIÓN:</label>
                                                <input type='text' class="form-control" name="foliogarantia" id="foliogarantia" />
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="descripcion" class="control-label">CLIENTE:</label>
                                                <input class="form-control" name="cliente" id="cliente" >

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="cliente" class="control-label">MATERIAL:</label>
                                                <input type='text' class="form-control" name="material" id="material" />
                                            </div>
                                        </div>
                                         <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="cliente" class="control-label">CANTIDAD:</label>
                                                <input type="text" class="form-control" name="cantidad" id="cantidad" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="cliente" class="control-label">UNIDAD DE MEDIDA:</label>
                                                <input type="text" class="form-control" name="medida" id="medida" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="monto" class="control-label">MONTO:</label>
                                                <input type="text" class="form-control" name="monto" id="monto" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="fechaCotizacion" class="control-label">MOTIVO:</label>
                                                <input class="form-control" type="text" name="motivo" id="motivo" >
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
                    <button type="button" class="btn btn-primary" id="guardagarantia">GUARDAR</button>
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
    <script language="javascript" src="js/Modulos/Garantia/scripts.js">


    </script>
    </body>

    </html>

