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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<?php
 if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 )
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
                            creaMenu(35);
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
                        <h1 class="page-header"><i class="fa fa-file-text-o"></i> Tablero de Procesos Mostrador</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Tablero de Procesos Mostrador
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-primary" onclick="reporte_tablero()"><span class="fa fa-print"></span></button>
                                    <button type="button" class="btn btn-default" onclick="finalizar()"><span class="fa fa-check"></span></button>
                                  
                                </div>
                            </div>
                            <!-- /.panel-heading -->
                            <form name="form" id="form_tablero"> 
                                
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                    	<div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <select class="form-control" id="estatus_general" onchange="cambia_estatus_general(this.value)">
                                                       <option value="0">TODOS</option>
                                                       <option value="1">NO INICIALIZADOS</option>
                                                       <option value="2">FINALIZADOS</option>
                                                   </select>
                                                </ul>
                                            </div>
                                        </div>
                        			</div>
                                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 120px;">FOLIO</th>
                                            <th style="width: 120px;">FECHA</th>
                                            <th style="width: 384px;">CLIENTE / DESCRIPCION</th>
                                            
                                            <th><span class="fa fa-apple"></span></th>
                                            <th><span class="fa fa-print"></span></th>
                                            <th><span class="fa fa-wrench"></span></th>
                                            <th><span class="fa fa-truck"></span></th>
                                            <th><span class="glyphicon glyphicon-lock"></span></th>
                                            <th><input type="checkbox" name="check" onclick="verificar(this)"></th>
                                        </tr>
                                    </thead>
                                  
                                  <tbody id="data">
                                        <tr><td colspan="9">No se encuentran resultados...</td></tr>
                                  </tbody>

                                </table>
                                    
                              </div>
                            </div>
                            </form>
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
	<script src="js/Modulos/Tablero/scripts.js" language="javascript">
		
    </script>
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