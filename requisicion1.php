<?php
session_start();
include "funciones/phpfunctions.php";
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
    <link rel="shortcut icon" href="images/globo.png">
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
<?php if(isset($_SESSION['LEVANTAMIENTOS']) and $_SESSION['LEVANTAMIENTOS'] == 1 ){    ?>
    
    <body>

    <div id="wrapper">
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
                </li>
            </ul>
            <?php

                ventas();
            ?>
            
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <?php
                            creaMenu(17);
                        ?>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="cuerpo">
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> Requisicion de Materiales</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Requisicion de Materiales
                                <div class="btn-group pull-right" style="margin-right:5%">

                                    <div class="dropdown"  style="display:inline-block">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">REPORTES
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                            <li onclick="reporte()"><a href="#">REPORTE REQUERIMIENTOS</a></li>
                                            <li  onclick="reporte2()"><a href="#">REPORTE REQUERIMIENTOS-VENTAS</a></li>
                                        </ul>
                                    </div>
                                    <div class="dropdown" style="display:inline-block">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="menu2" data-toggle="dropdown">ACCIONES
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                          <li id="agregar"><a href="#">AGREGAR</a></li>
                                        <li id="modificar"><a href="#">MODIFICAR</a></li>
                                        <li id="surtido"><a href="#">SURTIDO</a></li>
                                        <li id="validado"><a href="#">VALIDAR</a></li>
                                        <li id="borrar"><a href="#">ELIMINAR</a></li>
                                          
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <input type="text" class="form-control" id="foliofiltro" placeHolder='FOLIO DE REFERENCIA'>
                                                    <input type="text" class="form-control" id="clientefiltro" placeHolder='CLIENTE O PROVEEDOR' ONBLUR="$(this).val($(this).val().toUpperCase())">

                                                    <select class="form-control" id="estatusfiltro">
                                                        <option value="1">PENDIENTE</option>
                                                        <option value="2">SURTIDO</option>
                                                        <option value="3">VALIDADO</option>
                                                    </select>
                                                    <select class="form-control" id="mesfiltro">
                                                        <option value="0">TODOS</option>
                                                        <option value="01">ENERO</option>
                                                        <option value="02">FEBRERO</option>
                                                        <option value="03">MARZO</option>
                                                        <option value="04">ABRIL</option>
                                                        <option value="05">MAYO</option>
                                                        <option value="06">JUNIO</option>
                                                        <option value="07">JULIO</option>
                                                        <option value="08">AGOSTO</option>
                                                        <option value="09">SEPTIEMBRE</option>
                                                        <option value="10">OCTUBRE</option>
                                                        <option value="11">NOVIEMBRE</option>
                                                        <option value="12">DICIEMBRE</option>
                                                    </select>
                                                    <buttom type="button" class="btn btn-info" id="buscar" onclick="actualizaDatagrid()">BUSCAR</buttom>
                                                </ul>

                                            </div>
                                        </div>
                                        <form  id="FormDatagrid">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                                <thead>
                                                <tr role="row">
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 70px;">FOLIO</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">FECHA</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 450px;">DATOS</th>
                                                   
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">IMPORTE</th>
                                                    <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">ESTATUS</th>
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
    
                            </div>
                        </div>
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
                    <h4 class="modal-title" id="myModalLabel">Requisición</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form  id="FormRequisicion">
                                    <input type="hidden" name="id" id="id" >
                                    <ul class="nav nav-tabs">
                                      <li class="active" id="tab_datos_generales_form"><a data-toggle="tab" href="#datos_generales">DATOS GENERALES</a></li>
                                      <li style="visibility:hidden" id="tab_datos_requisicion"><a data-toggle="tab" href="#datos_articulos">DATOS DE ARTICULOS</a></li>
                                      <li style="visibility:hidden" id="tab_datos_venta_requisicion"><a data-toggle="tab" href="#datos_articulos_ventas">DATOS DE ARTICULOS VENTAS</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="datos_generales" class="tab-pane fade in active">
                                            <BR>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="EMPRESA" class="control-label">EMPRESA:</label>
                                                        <select class="form-control" name="empresa" id="empresa">
                                                            <option value="1">NEXOS EMPRESARIALES</option>
                                                            <option value="2">NEXPRINT</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="EMPRESA" class="control-label">TIPO DOCUMENTO:</label>
                                                        <select class="form-control" name="tipo_documento" id="tipo_documento" onchange="verifica_tipo(this)">
                                                            <option value="1">FACTURA</option>
                                                            <option value="2">REMISION</option>
                                                            <option value="3">PUNTO DE VENTA</option>
                                                            <option value="4">INTERNO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="foliorequisicion" class="control-label">FOLIO DE REFERENCIA:</label>
                                                        <input type='text' class="form-control" name="foliorequisicion" id="foliorequisicion" placeHolder='P. ej. 1000 0 A100' onblur="Verifica_Folio(this)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="descripcion" class="control-label">CLIENTE:</label>
                                                        <input class="form-control" name="cliente" id="cliente"  placeholder='P. ej. HONDA DIANA'>

                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="fechaCotizacion" class="control-label">FECHA DE ALTA:</label>
                                                        <input type='date' class="form-control" name="fechaSolicitud" id="fechaSolicitud" />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="descripcion" class="control-label">FORMA DE PAGO:</label>
                                                        <select name="forma_pago" id="forma_pago" class="form-control">
                                                            <option value="1">EFECTIVO</option>
                                                            <option value="2">CHEQUE</option>
                                                            <option value="3">TARJETA</option>
                                                            <option value="4">CREDITO</option>
                                                            <option value="5">TRANSFERENCIA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="fechaCotizacion" class="control-label">OBSERVACION:</label>
                                                        <input class="form-control" type="text" name="observacion" id="observacion" placeholder='OBSERVACIONES (NO ARTICULOS)'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                        <div id="datos_articulos" class="tab-pane fade">
                                          <BR>
                                            <div class="row" id="primera_linea">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        
                                                        <label for="fechaCotizacion" class="control-label">FACTURA:</label>
                                                        <input type='text' class="form-control" name="factura_proveedor" id="factura_proveedor" autocomplete='off' placeholder='P. e.j. 10000' />
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="caja"></div>
                                                        <label for="fechaCotizacion" class="control-label">PROVEEDOR:</label>
                                                        <input type='text' class="form-control" name="proveedor" id="proveedor" autocomplete='off' placeholder='P. e.j. VINILONAS' />
                                                        <input type='hidden' class="form-control" name="idregistroarticulo" id="idregistroarticulo" value="0" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="caja">
                                                            
                                                        </div>
                                                        <label for="foliorequisicion" class="control-label">ARTICULO</label>
                                                        <input type='text' class="form-control" name="articulo" id="articulo" autocomplete='off' placeholder='P. ej. LONA MESH 2.0'/>
                                                        <input type="hidden" name="articulo_id" id="articulo_id" value="0">
                                                        <input type="hidden" name="articulo_id" id="subarticulo_id" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="segunda_linea">    
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fechaCotizacion" class="control-label">CANTIDAD:</label>
                                                        <input type='text' class="form-control" name="cantidad" id="cantidad" placeholder='P. ej. 100'/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="fechaCotizacion" class="control-label">UNIDAD</label>
                                                        <input type='text' class="form-control" name="unidad" id="unidad" placeholder='P. ej. METROS LINEANES O ML'/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fechaCotizacion" class="control-label">IMPORTE:</label>
                                                        <input type='text' class="form-control" name="importe" id="importe" placeholder='P. ej. 1000'/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-primary" id="agregar_articulo">AGREGAR / MODIFICAR</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-striped" width="100%">
                                                        <thead style="backgrund: #DFDFDF">
                                                            <th>PROVEEDOR</th>
                                                            <th>FACTURA</th>
                                                            <th>ARTÍCULO</th>
                                                            <th>CANTIDAD</th>
                                                            <th>UNIDAD DE MEDIDA</th>
                                                            <th>MONTO</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody id="registros_articuos_requisicion">
                                                            <tr data-id='NODATA'>
                                                                <td colspan="6">NO SE ENCUENTRAN RESULTADOS</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot id="resultado_articulos_requisiciones">
                                                            <tr>
                                                                <td colspan="5">TOTAL</td>
                                                                <td id="monto_resultado">$ 0.00</td>
                                                                <td></td>
                                                            </tr>            
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="datos_articulos_ventas" class="tab-pane fade">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-striped" width="100%">
                                                        <thead style="backgrund: #DFDFDF">
                                                            <th>ARTICULO</th>
                                                            <th>UNIDADES</th>
                                                            <th>PRECIO DE VENTA UNITARIO SIN IVA</th>
                                                        </thead>
                                                        <tbody id="registros_articuos_requisicion_ventas">
                                                            <tr data-id='NODATASALE'>
                                                                <td colspan="3">NO SE ENCUENTRAN RESULTADOS</td>
                                                            </tr>
                                                        </tbody>
                                                        
                                                    </table>
                                                </div>
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
                    <button type="button" class="btn btn-primary" id="guardaRequisicion">GUARDAR</button>
                </div>
            </div>
            
        </div>
        
    </div>

    
    <script src="js/jquery-1.11.0.js"></script>

    
    <script src="js/bootstrap.min.js"></script>

    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <script src="js/RestFull.js"></script>

    <script src="js/sb-admin-2.js"></script>
    <script src="js/complemento.js"></script>
    <script src="js/Modulos/General.js"></script>
    <script language="javascript" src="js/Modulos/Requisiciones/scripts.js">


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
