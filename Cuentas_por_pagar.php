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

    <!-- Custom Fonts -->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="css/general.css" rel="stylesheet">

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
                            creaMenu(41);
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
                            <h1 class="page-header"><i class="fa fa-handshake-o"></i> Cuentas por Pagar

                            </h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    
            	<div class="row">
                        
                    
                	<div class="col-sm-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Procesos
                                
                                <div class="btn-group pull-right">

                                   <!--<button type="button" class="btn btn-primary" onclick='agregar_inventario()' title="Agregar Inventario"><span class='fa fa-plus'></span> Factura</button>-->
                                   <!--href="reportes/inventario_admin/pago_proveedor.php"-->
                                   <button type="button" class="btn btn-primary" onclick='agregar_inventario()' title="Agregar Factura"><span class='fa fa-plus'></span> AGREGAR FACTURA SERVICIOS</button>
                                   
                                </div>
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-default" onclick="window.open('reportes/inventario_admin/pago_proveedor.php', '_blank')"><i class="fa fa-print"></i> Reporte</button>    
                                    <button type="button" class="btn btn-warning" onclick="window.open('reportes/inventario/backup_cuentasxpagar.php', '_blank')"><i class="fa fa-download"></i></button>    
                                </div>
                                
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">

                                    <div class="row">
                                        
                                        <div class="col-sm-5">
                                            <ul class="nav nav-pills">
                                                <select name="grupo" id="filtro_grupo" class='form-control' onchange="actualizaDatagrid()">
                                                </select>
                                            </ul>                                            
                                        </div>   
                                        <div class="col-sm-1">
                                            <ul class="nav nav-pills">
                                                <button type="button" class="btn btn-warning" onclick="historial()"><i class="fa fa-history fa-2x"></i><br>Historial</button>
                                            </ul>                                            
                                        </div>   
                                        <div class="col-sm-3">
                                            <ul class="nav nav-pills">
                                                MONTO A PAGAR: <h3>$ <span id="monto_inventario">0.00</span></h3>
                                            </ul>                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <ul class="nav nav-pills">
                                                MONTO VENCIDO: <h3>$ <span id="monto_inventario_vencido">0.00</span></h3>
                                            </ul>                                            
                                        </div>                                       
                                    </div>
                                <form id='form_almacen'>    	
                                    <table class="table table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                        <thead>
                                            <tr role="row">
                                                <th  style="width: 5%;" >FACTURA</th>
                                                <th  style="width: 45%;">PROVEEDOR</th>
                                                <th  style="width: 10%;">FECHA FACTURA</th>
                                                <th  style="width: 10%;">FECHA PAGO</th>                                             
                                                <th  style="width: 10%;">MONTO</th>                                             
                                                <th  style="width: 20%;"></th>                                             
                                            </tr>
                                            
                                        </thead>
                                      <tbody id="cuenta">
                                      	
                                      </tbody>
                                    </table>
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
    


    <div class="modal fade" id="modal_factura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-fullscreen">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Inventario</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           AGREGAR INVENTARIO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_ALMACEN">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">FACTURA</label>
                                                <input type="text" name="factura" id='factura' class="form-control" placeholder="P. ej. 578" onblur="verificar_factura()">
                                              </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">PROVEEDOR</label>
                                                <div class="input-group">
                                                    <select name="proveedor" id="proveedor"  class="form-control"  onblur="verificar_factura()">
                                                    </select>
                                                    <div class="input-group-btn">    
                                                        <button type="button" class="btn btn-primary" onclick="$('#modal_proveedor').modal('show'); load_lista_proveedores()"><i class="fa fa-plus"></i></button>
                                                    </div>    
                                                </div>
                                              </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">FECHA FACTURA</label>
                                                
                                                <input type="date" name="fecha_factura" id='fecha_factura' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-3" id="doble_factura">
                                            <div class="form-group">
                                                <label for="email">FACTURA ENCONTRADA</label>
                                                
                                                <BUTTON type='button' class='btn btn-warning' onclick="$('#modal_datos').modal('show')"><i class="fa fa-warning" ></i></BUTTON>
                                              </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">CATAGORÍA</label>
                                                
                                                <select name="categoria" id="categoria"  class="form-control">
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">PRODUCTO</label>
                                                <select name="articulo" id="articulo" class="form-control">
                                                </select>
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">CANTIDAD (UNIDADES)</label>
                                                
                                                <input type="text" name="unidades" id='unidades' class="form-control" placeholder="P ej. 2">
                                            </div>
                                        </div>  
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">ANCHO</label>
                                                
                                                <input type="text" name="ancho" id='ancho' class="form-control" placeholder="P ej. 3.02 (ancho de rollos)">
                                            </div>
                                        </div>  
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">LARGO</label>
                                                <input type="text" name="largo" id="largo" class="form-control" placeholder="P. Ej. 100 (largo de rollos o unidad)">
                                            </div>
                                        </div>    
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">UNIDAD MEDIDA</label>
                                                <input type="text" name="unidad" id='unidad' class="form-control" placeholder="P ej. M2, PIEZA, ML">
                                            </div>
                                        </div>    
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">COSTO (SIN IVA)</label>
                                                <input type="text" name="costo" id="costo" class="form-control" placeholder=" P. Ej. 2578.32 (cifra exacta)">
                                            </div>
                                        </div>    
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <br>
                                              <button type="button" class="btn btn-primary" onclick="btn_guardar_inventario();"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                   <hr>
                                   <table class="table table-bordered" class="col-sm-12">
                                       <thead>
                                           <th>Factura</th>
                                           <th>Artículo</th>
                                           <th>Cantidad</th>
                                           <th>Unidades</th>
                                           <th>Costo unitario</th>
                                           <th>Costo Total</th>
                                           
                                       </thead>
                                       <tbody id="lista_factura">
                                           <tr>
                                                <td colspan="6">No hay Artículos ingresados</td>
                                           </tr>
                                       </tbody>
                                       <tfoot>
                                           <th colspan="4"></th>
                                           <th>Total</th>
                                           <th id="total_factura">0.00</th>
                                           
                                       </tfoot>
                                   </table>
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                                    
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick='cerrar_factura()'>CERRAR FACTURA</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="modal_proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Proveedores</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           AGREGAR PROVEEDOR
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_PROVEEDOR">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">NOMBRE</label>
                                                <input type="text" name="nombre_proveedor" id='nombre_proveedor' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">DIRECCIÓN</label>
                                                
                                                <input type="text" name="direccion" id='direccion' class="form-control">
                                                
                                              </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">TELÉFONO</label>
                                                
                                                <input type="text" name="telefono" id='telefono' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">CONDICIÓN DE PAGO (DÍAS)</label>
                                                
                                                <input type="text" name="condicion" id='condicion' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">CONTÁCTO</label>
                                                
                                                <input type="text" name="contacto" id='contacto' class="form-control">
                                            </div>
                                        </div>         
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">DESCRIPCIÓN</label>
                                                <input type="text" name="descripcion" id='descripcion' class="form-control">
                                            </div>
                                        </div>    
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">NO CUENTA</label>
                                                <input type="text" name="cuenta" id='cuenta' class="form-control">
                                            </div>
                                        </div>    
                                    </div>
                                    
                                   <hr>
                                   
                                </form>
                            </div>
                            <!-- /.table-responsive -->
                                    
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="guarda_proveedor()">GUARDAR</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="modal_informacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Factura</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           INFORMACIÓN FACTURA
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_INFO">
                                    <input type="hidden" name="proveedor_id" id="proveedor_id">
                                    <input type="hidden" name="factura_id" id="factura_id">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">FACTURA</label>
                                                <input type="text" name="factura" id='factura' class="form-control">
                                            </div>
                                        </div> 
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">FECHA</label>
                                                <input type="text" name="fecha" id='fecha' class="form-control" readonly="readonly">
                                            </div>
                                        </div> 
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="email">PROVEEDOR</label>
                                                <input type="text" name="proveedor" id='proveedor' class="form-control"  readonly="readonly">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ARTICULO</th>
                                                    <th>CANTIDAD</th>
                                                    <th>MONTO</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista_articulos" width="100%">
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td>SUBTOTAL</td>
                                                    <td id='subtotal'>0.00</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>DESCUENTO</td>
                                                    <td id='descuento'>0.00</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>TOTAL</td>
                                                    <td id='total'>0.00</td>
                                                </tr>
                                            </tfoot>
                                            
                                        </table>
                                    </div>
                                    
                                 </form>       
                            </div>
                            <!-- /.table-responsive -->
                                    
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="guarda_folio();">GUARDAR</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="modal_descuento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Descuento</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           DESCUENTO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_DESCUENTO">
                                    <input type="hidden" name="id_proveedor" id="id_proveedor">
                                    <input type="hidden" name="factura" id="factura">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="email">DESCUENTO</label>
                                                <input type="text" name="monto" id='monto' class="form-control" >
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
                    <button type="button" class="btn btn-primary" onclick="guardar_descuento()">GUARDAR</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

     <div class="modal fade" id="modal_historial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">HISTORIAL DE PAGOS</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           HISTORIAL DE PAGOS
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_HISTORIAL">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">PROVEEDOR</label>
                                                <select name="historial_proveedor" id="historial_proveedor"  class="form-control">
                                                    
                                                </select>
                                            </div>
                                        </div> 
                                    
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">FACTURA</label>
                                                <input type="text" name="historial_factura" id='historial_factura' class="form-control" >
                                            </div>
                                        </div> 
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">INICIO</label>
                                                <input type="date" name="historial_inicio" id='historial_inicio' class="form-control" >
                                            </div>
                                        </div> 
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">FINAL</label>
                                                <input type="date" name="historial_fin" id='historial_fin' class="form-control" >
                                            </div>
                                        </div> 
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-primary form-control" onclick="buscar_historial()">BUSCAR</button>
                                        </div> 
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>FACTURA</th>
                                            <th>FECHA</th>
                                            <th>PROVEEDOR</th>
                                            <th>MONTO</th>
                                            <th>PAGADO</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="tabla_historial">
                                            <tr>
                                                <td colspan="6">SIN RESULTADOS</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered" id="tabla_info_general" style="display: none;">
                                        <thead>
                                            <th colspan="3" style="background-color: #23D; text-align: center; color: #FFF">INFORMACIÓN DETALLADA</th>
                                            
                                        </thead>
                                        <thead>
                                            <th>INSUMO</th>
                                            <th>CANTIDAD</th>
                                            <th>MONTO (CON IVA)</th>
                                            
                                        </thead>
                                        <tbody id="tabla_info_historiaL">
                                            <tr>
                                                <td colspan="3">SIN RESULTADOS</td>
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
                    <button type="button" class="btn btn-primary" onclick="guardar_descuento()">GUARDAR</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->


    <div class="modal fade" id="modal_datos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-fullscreen">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Factura</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           FACTURA ENCONTRADA
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_DATOS">
                                    <table class="table">                                        
                                        <thead>
                                            <tr>
                                                <th>FACTURA</th>
                                                <th>FECHA</th>
                                                <th>ARTICULO</th>
                                                <th>CANTIDAD</th>
                                                <th>MONTO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpo_datos">
                                                                              
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

    


    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->


    <!-- Custom Theme JavaScript -->
    <script src="js/RestFull.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
    <script src="js/Modulos/pagos/scripts.js"></script>
</body>

</html>
