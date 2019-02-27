<?php
session_start();
include("../funciones/phpfunctions.php");
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">
    
    <link href="../css/general.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>
<?php
 if(isset($_SESSION['ENTREGA']) and ($_SESSION['ENTREGA'] == 1  OR $_SESSION['ENTREGA'] == 2) )
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
                            creaMenu(34);
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
                        <h1 class="page-header"><span class="glyphicon glyphicon-lock"></span> Procesos de Entrega
                             
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                             <div class="panel-heading">Procesos
                                 
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        
                                <table class="table table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th  style="width: 120px;">FOLIO</th>
                                            <th  style="width: 140px;">FECHA</th>
                                            <th  style="width: 300px;">CLIENTE / DESCRIPCIÓN</th>
                                            <!--<th  style="width: 80px;">UNIDADES</th>
                                            <th  style="width: 450px;">DESCRIPCIÓN</th>--> 
                                            <!--<th  style="width: 350px;">DESCRIPCIÓN DETALLADA</th>-->
                                            <th  style="width: 100px;">OPERADOR</th>
                                            <th style="width:120px;"></th>
                                        </tr>
                                    </thead>
                                  <tbody id="PendientesEntrega">
                                    
                                  </tbody>
                                </table>
                                    
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
                                    <input type='hidden' name="idtablero" id="idtablero">
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

    <div class="modal fade" id="turnar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">TURNAR</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            TURNAR ACTIVIDAD
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formOperadores">
                                    <input type='hidden' name="idtablerooperador" id="idtablerooperador">
                                    <table class="table">
                                        
                                        <tbody id="descripcionoperadores">
                                            <tr>
                                                <td><SELECT name='selectEmpleado' id='selectEmpleado' class='form-control'></SELECT></td>
                                            </tr>                                       
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
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="finalizaTarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
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
                            <div class="table-responsive">
                                <form id="formFinalizar">
                                    <input type='hidden' name="idtablerofinalizar" id="idtablerofinalizar">
                                    <table class="table">
                                        
                                        <tbody>
                                            <tr>
                                                <td><SELECT name='EmpleadoFinalizar' id='EmpleadoFinalizar' class='form-control'></SELECT></td>
                                            </tr>                                       
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
                    <button type="button" class="btn btn-primary" onclick="guardarTurnar()">TURNAR</button>
                    <button type="button" class="btn btn-primary" onclick="guardar()">FINALIZAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="CancelarTarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">RECHAZAR ACTVIDAD A IMPRESION</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                           RECHAZAR ACTIVIDAD A IMPRESION
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="formCancelar">
                                    <input type='hidden' name="idtablerocancelar" id="idtablerocancelar">
                                    <table class="table">
                                        
                                        <tbody>
                                            <tr>
                                                <td><textarea class="form-control" name="notacancelacion" id="notacancelacion"></textarea></td>
                                            </tr>                                       
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
                    <button type="button" class="btn btn-primary" onclick="cancelaActividad()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../js/RestFull.js"></script>
    <script src="../js/sb-admin-2.js"></script>
    <script src="../js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
    <script src="js/Modulos/Entrega/scripts.js"></script>
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