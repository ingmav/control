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
                            creaMenu(38);
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
                            <h1 class="page-header"><i class="fa fa-print"></i> Almacenes

                            </h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">Procesos
                                
                                <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-primary" onclick='agregar_inventario()' title="Agregar Inventario"><span class='fa fa-plus'></span> Factura</button>
                                   <button type="button" class="btn btn-primary" onclick='$("#modal_insumo").modal("show")' title="Agregar Insumo"><span class='fa fa-plus'></span> Articulo</button>
                                   
                                </div>
                                
                                

                                <div class="btn-group btn-success pull-right" role="group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      REPORTES
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="reportes/inventario_admin/proveedor.php" target="_blank">COMPRAS</a></li>
                                      <li><a href="reportes/inventario_admin/sugeridos.php" target="_blank">INVENTARIO</a></li>
                                      <!--<li><a href="#">UTILIDAD</a></li>--> 
                                    </ul>
                                  </div>

                                
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">

                                    <div class="row">
                                        
                                        <div class="col-sm-6">
                                            <label>FAMILIA</label>
                                            <ul class="nav nav-pills">
                                                <select name="grupo" id="filtro_grupo" class='form-control' onchange="actualizaDatagrid()">
                                                </select>
                                            </ul>                                            
                                        </div>   
                                        <div class="col-sm-6">
                                            <ul class="nav nav-pills">
                                                MONTO INVENTARIO: $ <span id="monto_inventario">0.00</span><br>
                                                MONTO INSUMOS: $ <span id="monto_insumos">0.00</span><br>
                                                MONTO HERRAMIENTAS: $ <span id="monto_herramientas">0.00</span><br>
                                            </ul>                                            
                                        </div>                                       
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-sm-10">
                                            <ul class="nav nav-pills">
                                                <label>TEXTO </label>
                                                <input type="text" class="form-control" name="filtro_texto" id="filtro_texto">
                                            </ul>                                              
                                        </div>   
                                        <div class="col-sm-2">
                                           <BUTTON type='button' class='btn'  onclick="actualizaDatagrid()">BUSCAR</BUTTON>                                         
                                        </div>   
                                    </div>
                                <form id='form_almacen'>    	
                                    <table class="table table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                        <thead>
                                            <tr role="row">
                                                <th  style="width: 20%;">ARTICULO</th>
                                                <th  style="width: 10%;">DATOS INVENTARIO</th>
                                                <th  style="width: 10%;">ULTIMA COMPRA</th>
                                                <th  style="width: 10%;">FECHA DE ACTUALIZACIÓN</th>                                             
                                                <th  style="width: 1%;"></th>                                             
                                            </tr>
                                            
                                        </thead>
                                      <tbody id="almacen">
                                      	
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
    
    
    <div class="modal fade" id="modal_proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content-75-screen">
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
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">NOMBRE</label>
                                                <input type="text" name="nombre_proveedor" id='nombre_proveedor' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">DIRECCIÓN</label>
                                                
                                                <input type="text" name="direccion" id='direccion' class="form-control">
                                                
                                              </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">TELÉFONO</label>
                                                
                                                <input type="text" name="telefono" id='telefono' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">CONDICIÓN DE PAGO (DÍAS)</label>
                                                
                                                <input type="text" name="condicion" id='condicion' class="form-control">
                                              </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">CONTÁCTO</label>
                                                
                                                <input type="text" name="contacto" id='contacto' class="form-control">
                                            </div>
                                        </div>         
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">DESCRIPCIÓN</label>
                                                <input type="text" name="descripcion" id='descripcion' class="form-control">
                                            </div>
                                        </div>    
                                    </div>
                                    
                                   <hr>
                                   <table class="table table-bordered" class="col-sm-12">
                                       <thead>
                                           <th>NOMBRE</th>
                                           <th>CONTACTO</th>
                                           <th>TELEFONO</th>
                                           <th>COND. PAGO</th>
                                           <th></th>
                                       </thead>
                                       <tbody id="lista_proveedores">
                                           <tr>
                                                <td colspan="6">No hay Proveedores ingresados</td>
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
                    <button type="button" class="btn btn-primary" onclick="guarda_proveedor()">GUARDAR</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="modal_insumo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content-75-screen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">INSUMOS</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           AGREGAR INSUMOS
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_INSUMO">
                                    <input type="hidden" name="id" id="id">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">FAMILIA</label>
                                                <select name="familia" id="familia" class="form-control">
                                                    
                                                </select>
                                              </div>
                                        </div>
                                    
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">INSUMO</label>
                                                <input type="text" class="form-control" name="insumo" id="insumo">
                                              </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">MINIMO</label>
                                               <input type="text" class="form-control" name="minimo" id="minimo">
                                              </div>
                                        </div>
                                    
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                               <label for="email">UNITARIO</label>
                                               <select name="unitario" id="unitario" class="form-control" onchange="estado_unitario(this.value)">
                                                   <option value="1">Si</option>
                                                   <option value="0">No</option>
                                               </select>
                                                "SI": PARA INSUMOS NO DIVISIBLES<BR>
                                                "NO": PARA INSUMOS MEDIBLES EN M2 
                                              </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                               <label for="email">ANCHO</label>
                                               <input type="text" class="form-control" name="ancho" id="ancho" disabled="disabled">
                                              </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                               <label for="email">LARGO</label>
                                               <input type="text" class="form-control" name="largo" id="largo" disabled="disabled">
                                              </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                               <label for="email">UNIDADES POR PAQUETE</label>
                                               <input type="text" class="form-control" name="u_paquete" id="u_paquete" placeholder="50, 100">
                                              </div>
                                        </div>
                                   
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                               <label for="email">UNIDAD DE VENTA (TEXTO)</label>
                                               <input type="text" class="form-control" name="u_venta" id="u_venta" placeholder="PIEZA, M2, ML">
                                              </div>
                                        </div>
                                    
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                               <label for="email">UNIDAD DE COMPRA (TEXTO)</label>
                                               <input type="text" class="form-control" name="u_compra" id="u_compra" placeholder="PAQUETE, ROLLO, PIEZA">
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
                    <button type="button" class="btn btn-primary" onclick="guarda_insumo()">GUARDAR</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal_inventario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
                                                
                                                <BUTTON type='button' class='btn btn-warning' onclick="$('#modal_datos').modal('show')"><i class="fa fa-warning"></i></BUTTON>
                                              </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">CATAGORÍA</label>
                                                
                                                <select name="categoria" id="categoria"  class="form-control">
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">PRODUCTO</label>
                                                <select name="articulo" id="articulo" class="form-control">
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">ANCHO (METROS)</label>
                                                
                                                <input type="text" name="ancho" id='ancho' class="form-control" placeholder="P ej. 3.02 (ancho de rollos)">
                                            </div>
                                        </div>  
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="email">LARGO (METROS)</label>
                                                <input type="text" name="largo" id="largo" class="form-control" placeholder="P. Ej. 100 (largo de rollos o unidad)">
                                            </div>
                                        </div> 
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label for="email">CANTIDAD</label>
                                                
                                                <input type="text" name="unidades" id='unidades' class="form-control" placeholder="P ej. 2">
                                            </div>
                                        </div>  
                                        <div class="col-sm-2">
                                                <label for="email">COSTO (SIN IVA)</label>
                                                <input type="text" name="costo" id="costo" class="form-control"  aria-describedby="button-addon2" placeholder=" P. Ej. 2578.32 (cifra exacta)">
                                            
                                        </div>    
                                       
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-sm-12">
                                                <button type="button" class="btn btn-primary form-control" onclick="btn_guardar_inventario();"><i class="fa fa-plus"></i> AGREGAR INSUMO</button>
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
                                           <th></th>
                                           
                                       </thead>
                                       <tbody id="lista_factura">
                                           
                                       </tbody>
                                       <tfoot>
                                           <th colspan="4"></th>
                                           <th>Total</th>
                                           <th id="total_factura">0.00</th>
                                           
                                       </tfoot>
                                   </table>
                                </form>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick='cerrar_factura()'>CERRAR FACTURA</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            
        </div>
        
    </div>
    
    <!--<div class="modal fade" id="modal_transferencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Inventario</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           TRANSFERENCIA INVENTARIO
                        </div>
                        
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_TRANSFERENCIA">
                                    <table class="table">                                        
                                        <tbody>
                                            <tr><td>ALMACÉN</td></tr>
                                            <tr>
                                                <td><select name="almacen_transferencia" id="almacen_transferencia" class="form-control">
                                                    
                                                </select></td>
                                            </tr>
                                            
                                                                               
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                                    
                            
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-primary" onclick="guardar_transferencia()">GUARDAR</button>
                </div>
            </div>
            
        </div>
        
    </div>
    -->
    <div class="modal fade" id="modal_almacen_alta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Inventario</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           AGREGAR ALMACÉN
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_ALMACEN_ALTA">
                                    <table class="table">                                        
                                        <tbody>
                                            <tr><td>ALMACEN</td></tr>
                                            <tr><td><input type="text" name="almacen" id='almacen' class="form-control"></td></tr>
                                                                              
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
                    <button type="button" class="btn btn-primary" onclick="guardar_almacen()">GUARDAR</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /#wrapper -->

    <div class="modal fade" id="modal_baja_manual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
       <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">BAJA</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           BAJA INVENTARIO
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form id="FORM_ARTICULO_BAJA">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td colspan="6">ARTÍCULO: <b><span id="NOMBRE_ARTICULO_MODAL"></span></b></td>
                                            </tr>
                                            <tr>
                                                <th colspan="6" style="background-color: #E21800;color: #FFF;"><center>UNIDADES COMPLETAS</center></th>
                                            </tr>
                                            <tr>
                                                <th>ID INSUMO</th>
                                                <th>UNIDADES</th>
                                                <th width="50%">ARTICULO</th>
                                                <th width="10%">CANTIDAD BAJA</th>
                                                <th width="10%">BAJA COMPLETA</th>
                                                <th width="10%" id="titulo_baja">BAJA PARCIAL</th>
                                                
                                            </tr>
                                        </thead>                                        
                                        <tbody id="lista_inventario_baja">
                                            
                                                                              
                                        </tbody>
                                    </table>
                                    <br>
                                    <table class="table  table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="7" style="background-color: #E21800;color: #FFF;"><center>UNIDADES ACTIVOS</center></th>
                                            </tr>
                                            <tr>
                                                <th>ID INVENTARIO</th>
                                                <th>UNIDADES</th>
                                                <th>ARTICULO</th>
                                                <th>RESTANTE </th>
                                                <th>CANTIDAD</th>
                                                <th>TOTAL</th>
                                                <th style="width: 100px;"></th>
                                            </tr>
                                        </thead>                                        
                                        <tbody id="lista_inventario_baja_activo">
                                            
                                                                              
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
                    <!--<button type="button" class="btn btn-primary" onclick="baja_almacen()">GUARDAR</button>-->
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
	<script src="js/Modulos/almacen/scripts.js"></script>
</body>

</html>
