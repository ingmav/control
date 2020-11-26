<?php
session_start();
?>
<meta http-equiv="content-type" content="text/html; charset=utf8" />
<?php


    function ventas()
    {
    ?>
        <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Metas de Ventas en Mostrador  <span id='ventas_mostrador_percent'></span> <i class="fa fa-caret-down"></i>
                    </a>
                    <?php 
                    
                    if($_SESSION['VENTAS'] == 1){ ?>
                        <ul class="dropdown-menu dropdown-user" >
                            <li>
                                <a href="#"><i class="fa fa-sign-out fa-fw"></i> <span id='diario_ventas_mostrador'></span></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-sign-out fa-fw"></i> <span id='mensual_ventas_mostrador'></span></a>
                            </li>
                            <li>
                                <table class="table table-bordered" >
                                    <thead>
                                        <tr>
                                            <th class="info" colspan="6">Calendario</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calendario_mostrador">
                                        <tr>
                                            <td>Lu</td>
                                            <td>Ma</td>
                                            <td>Mi</td>
                                            <td>Ju</td>
                                            <td>Vi</td>
                                            <td>Sa</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </li>
                        </ul>
                    <?php } ?>
                </li>
            </ul>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Metas de Ventas en Servicio <span id='ventas_servicio_percent'></span> <i class="fa fa-caret-down"></i>
                    </a>
                    <?php 

                    if($_SESSION['VENTAS'] == 1){ ?>
                    <ul class="dropdown-menu dropdown-user" >
                        <li>
                            <a href="#"><i class="fa fa-sign-out fa-fw"></i> <span id='diario_ventas_servicio'></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sign-out fa-fw"></i> <span id='mensual_ventas_servicio'></span></a>
                        </li>
                        <li>
                            <table class="table table-bordered" >
                                <thead>
                                <tr>
                                    <th class="info" colspan="6">Calendario</th>
                                </tr>
                                </thead>
                                <tbody id="calendario_servicio">
                                <tr>
                                    <td>Lu</td>
                                    <td>Ma</td>
                                    <td>Mi</td>
                                    <td>Ju</td>
                                    <td>Vi</td>
                                    <td>Sa</td>
                                </tr>

                                </tbody>
                            </table>
                        </li>
                    </ul>
                    <?php } ?>
                </li>
            </ul>
          <?php

    }

    function creaMenu($id)
    {
        $cotizacion = "";
        /*$caja = "";*/
        $levantamiento = "";
        $documentos = "";
        $diseno = "";
        /*$programacion = "";*/
        $impresion = "";
        $instalacion = "";
        $entrega = "";
        $finalizado = "";
        $cobro = "";
        $maquilas = "";
        $capacidad = "";
        $seguimientoCotizaciones = "";
        /*$inventarios  = "";
        $extras  = "";*/
        $shopping = "";
        $preparacion = "";
        $pv = "";
        $clientes = "";
        $seguimiento = "";
        /*$telemarketing = "";
        $seguimientoCliente = "";*/
        $satisfaccion = "";
        $agenda = "";
        $cuentas_x_cobrar = "";
        $bd_error = "";
        $garantia = "";
        $reporte = "";

        $dg_seleccion = "";
        $dg_diseno = "";
        $dg_impresion = "";
        $dg_preparacion = "";
        $dg_entrega = "";
        $dg_instalacion = "";
        $tablero_mostrador = "";
        $almacenes = "";

        $inventario = "";

        $inventario = ""; 
        $validacion_venta = ""; 
        $almacenes = "";
        $inventario_general = ""; 
        $conversion = ""; 
        $cuentas_x_pagar = "";
        $proveedores = "";
        $insumos = "";
        $importacion = "";


        switch($id)
        {
            case 1:
                $documentos = "class='active'";break;
            case 2:
                $diseno = "class='active'";break;
            /*case 3:
                $programacion = "class='active'";break;*/
            case 4:
                $impresion = "class='active'";break;
            case 5:
                $instalacion = "class='active'";break;
            case 6:
                $entrega = "class='active'";break;
            case 7:
                $finalizado = "class='active'";break;
            case 8:
                $cobro = "class='active'";break;
            case 9:
                $maquilas = "class='active'";break;
            case 10:
                $cotizacion = "class='active'";break;
            case 11:
                $levantamiento = "class='active'";break;
            /*case 12:
                $caja = "class='active'";break;*/
            case 13:
                $capacidad = "class='active'";break;
            case 14:
                $seguimientoCotizaciones = "class='active'";break;
            /*case 15:
                $inventarios = "class='active'";break;*/
            case 16:
                $extras = "class='active'";break;
            case 17:
                $shopping = "class='active'";break;
            case 18:
                $preparacion = "class='active'";break;

            case 19:
                $pv = "class='active'";break;
            case 20:
                $clientes = "class='active'";break;
            case 21:
                $seguimiento = "class='active'";break;
            /*case 22:
                $telemarketing = "class='active'";break;
            case 23:*/
                $seguimientoCliente = "class='active'";break;
            case 24:
                $satisfaccion = "class='active'";break;
            case 25:
                $agenda = "class='active'";break;
            case 26:
                $cuentas_x_cobrar = "class='active'";break;
            case 27:
                $bd_error = "class='active'";break;
            case 28:
                $garantia = "class='active'";break;
            case 29:
                $reporte = "class='active'";break;
            case 30:
                $dg_seleccion = "class='active'";break;        
            case 31:
                $dg_diseno = "class='active'";break;        
            case 32:
                $dg_impresion = "class='active'";break;        
            case 33:
                $dg_preparacion = "class='active'";break;        
            case 34:
                $dg_entrega = "class='active'";break;   
            case 35:
                $tablero_mostrador = "class='active'";break;  

            case 36:
                $inventario = " class='active'"; break;  

            case 37:
                $validacion_venta = " class='active'"; break; 
            case 38:
                $almacenes = " class='active'"; break;            
            case 39:
                $inventario_general = " class='active'"; break;    
            case 40:
                $conversion = " class='active'"; break;          
            case 41:
                $cuentas_x_pagar = " class='active'"; break;  
            case 42:
                $proveedores = " class='active'"; break;  
            case 43:
                $insumos = " class='active'"; break;                
            case 44:
                $importacion = " class='active'"; break;                
                
        }

        //if($_POST['EXTRA'] == 1)
        //{
        ?>
        <!-- -->
        <?php
        if(isset($_SESSION['SHOPPING']) and $_SESSION['SHOPPING'] == 1 ){ ?>
        <li style="background-color: rgba(0,200,0,0.2)" id="call">
            <a href="#"><i class="fa fa-hand-paper-o"></i> CALL CENTER</span><span class="fa arrow"></span><span class='badge badge-danger pull-right'  id='menuCallcenter'></a>
            <ul class="nav nav-second-level collapse" style="height: 0px;">
                <li><a href="/produccion/seguimientoCotizaciones.php" <?php echo $seguimientoCotizaciones ?>><i class="fa fa-phone"></i> Close Sales</a></li>
                <li><a href="/produccion/Seguimiento.php"  <?php echo $seguimiento ?>><i class="fa fa-calendar"></i> Follow Up<span class='badge badge-danger pull-right' id='menuSeguimiento'></span></a></li>
                <li><a href="/produccion/satisfaccion.php" <?php echo $satisfaccion; ?>><i class="fa fa-heartbeat"></i> Satisfacción de Clientes<span class='badge badge-danger pull-right' id=''></span></a></li>
                <li><a href="/produccion/Prospectos.php"  <?php echo $clientes ?>><i class="fa fa-users"></i> CRM Clientes</a></li>
                <!--<li><a href="Telemarketing.php"  <?php echo $telemarketing ?>><i class="fa fa-fax"></i> Telemarketing<span class='badge badge-danger pull-right' id='menuTelemarketing'></span></a></li>-->
                
            </ul>
        </li>
        <?php } ?>
        <li style="background-color: rgba(0,28,200,0.2)" id="operacion">
            <a href="#"><i class="fa fa-industry"></i> OPERACIÓN<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse" style="height: 0px;">
                
            <?php if(isset($_SESSION['LEVANTAMIENTOS']) and $_SESSION['LEVANTAMIENTOS'] == 1 ){ ?>
                <li ><a href="/produccion/levantamientos.php" <?php echo $levantamiento ?>><i class="fa fa-clipboard"></i> Levantamientos<span class='badge badge-danger pull-right ' id='menuLevantamiento'></span></a></li>
            <?php }
            if(isset($_SESSION['COTIZACION']) and $_SESSION['COTIZACION'] == 1 ){ ?>
                <li><a href="/produccion/cotizaciones.php" <?php echo $cotizacion ?>><i class="fa fa-clipboard"></i> Cotizaciones<span class='badge badge-danger pull-right' id='menuCotizacion'></span></a></li>
            <?php }if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 ){ ?>
            <li><a href="/produccion/garantia.php" <?php echo $garantia ?>><i class="fa fa-empire"></i> Garantía<span class='badge badge-danger pull-right' id='menuGarantia'></span></a></li>
            <?php }if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 ){ ?>
                <li ><a href="/produccion/Documentos.php" <?php echo $documentos ?>><i class="fa fa-file-text-o"></i> Sel. de Documentos<span class='badge badge-danger pull-right' id='menuDocumentos'></span></a></li>
            <?php }
            if(isset($_SESSION['DISENO']) and ($_SESSION['DISENO'] == 1 OR $_SESSION['DISENO']==2)){
                ?>
                <li><a href="/produccion/diseno.php" <?php echo $diseno ?>><i class="fa fa-apple"></i> Diseño<span class='badge badge-danger pull-right' id='menuDiseno'></span></a></li>
            <?php }

            if(isset($_SESSION['IMPRESION']) and ($_SESSION['IMPRESION'] == 1 OR $_SESSION['IMPRESION']==2)){ ?>
                <li><a href="/produccion/impresion.php" <?php echo $impresion ?>><i class="fa fa-print"></i> Impresión<span class='badge badge-danger pull-right' id='menuImpresion'></span></a></li>
            <?php }if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 ){ ?>
            <li><a href="/produccion/preparacion.php" <?php echo $preparacion ?>><i class="fa fa-wrench"></i> Preparación<span class='badge badge-danger pull-right' id='menuPreparacion'></span></a></li>
            <?php
            }
            if(isset($_SESSION['INSTALACION']) and ($_SESSION['INSTALACION'] == 1 OR $_SESSION['INSTALACION']==2)){ ?>
                <li><a href="/produccion/instalacion.php" <?php echo $instalacion ?>><i class="fa fa-truck"></i> Instalación<span class='badge badge-danger pull-right' id='menuInstalacion'></span></a></li>
            <?php }
            if(isset($_SESSION['MAQUILAS']) and $_SESSION['MAQUILAS'] == 1 ){ ?>
                <li ><a href="/produccion/maquilas.php" <?php echo $maquilas ?>><i class="fa fa-file-image-o"></i> Maquilas<span class='badge badge-danger pull-right' id='menuMaquilas'></span></a></li>
            <?php }
            if(isset($_SESSION['ENTREGA']) and ($_SESSION['ENTREGA'] == 1 OR $_SESSION['ENTREGA']==2)){ ?>
                <li><a href="/produccion/entrega.php" <?php echo $entrega ?>><span class="glyphicon glyphicon-lock"></span> Entrega<span class='badge badge-danger pull-right' id='menuEntrega'></span></a></li>
            <?php }
            if(isset($_SESSION['DOCUMENTOS']) and $_SESSION['DOCUMENTOS'] == 1 ){ ?>
                <li><a href="/produccion/agenda.php" <?php echo $agenda ?>><span class="fa fa-calendar"></span> Agenda</a></li>
            <?php } ?>
            </ul>
        </li>

        <?php
        if(isset($_SESSION['SHOPPING']) and $_SESSION['SHOPPING'] == 1 ){ ?>
        <li style="background-color: rgba(200,208,64,0.4)" id="mostrador">
            <a href="#"><i class="fa fa-credit-card"></i> MOSTRADOR<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse" style="height: 0px;">
                <li><a href="/produccion/Mostrador/Dg_seleccion.php" <?php echo $dg_seleccion ?>><i class="fa fa-file-text-o"></i> Selección de documentos<span class='badge badge-danger pull-right' id='Shopping'></span></a></li>
                <li><a href="/produccion/Mostrador/Dg_diseno.php" <?php echo $dg_diseno ?>><i class="fa fa-apple"></i> Diseño </a></li>
                <li><a href="/produccion/Mostrador/Dg_impresion.php" <?php echo $dg_impresion ?>><i class="fa fa-print"></i> Impresión </a></li>
                <li><a href="/produccion/Mostrador/Dg_preparacion.php" <?php echo $dg_preparacion ?>><i class="fa fa-wrench"></i> Preparación</a></li>
                <li><a href="/produccion/Mostrador/Dg_entrega.php" <?php echo $dg_entrega ?>><i class="glyphicon glyphicon-lock"></i> Entrega</a></li>
                <li><a href="/produccion/Mostrador/Dg_tablero.php" <?php echo $tablero_mostrador ?>><i class="fa fa-check-square-o fa-fw"></i> Tablero de Procesos</a></li>
                
            </ul>
        </li>
        <?php } ?>
        <?php
        if(isset($_SESSION['FINALIZADOS']) and $_SESSION['FINALIZADOS'] == 1){ ?>
        <li style="background-color: rgba(200,0,0,0.2)"><a href="/produccion/finalizados.php" <?php echo $finalizado ?>><i class="fa fa-check-square-o fa-fw"></i> TABLERO DE PROCESOS</a></li>
        <?php } ?>
        <?php
        if(isset($_SESSION['SHOPPING']) and $_SESSION['SHOPPING'] == 1 ){ ?>
        <li style="background-color: rgba(244,171,14,0.2)" id="control">
            <a href="#"><i class="fa fa-calculator"></i> CONTROL INTERNO<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse" style="height: 0px;">
                <li><a href="/produccion/Requisicion.php" <?php echo $shopping ?>><i class="fa fa-shopping-cart"></i> Requisición de Materiales<span class='badge badge-danger pull-right' id='Shopping'></span></a></li>
                <?php
                if(isset($_SESSION['COBRO']) and $_SESSION['COBRO'] == 1){ ?>
                <li><a href="/produccion/cuentas_x_cobrar.php" <?php echo $cuentas_x_cobrar ?>><i class="fa fa-bank"></i> Cuentas por Cobrar </a></li>
                <?php }
                if(isset($_SESSION['INVENTARIOACCESO']) and $_SESSION['INVENTARIOACCESO'] == 1){ ?>
                <?php } ?>
                <li><a href="/produccion/modulos/complemento/vista.php" ><i class="fa fa-file-code-o"></i> Complento Liverpool </a></li>
                <li><a href="/produccion/modulos/legal/vista.php" ><i class="fa fa-legal"></i> Modulo Fiscal </a></li>
            </ul>
        </li>
        <?php } ?>

        <?php
        
        if(isset($_SESSION['INVENTARIOACCESO']) and $_SESSION['INVENTARIOACCESO'] == 1 ){ ?>
        <li style="background-color: rgba(75,54,33,0.4)" id="menu_inventario">
            <a href="#"><i class="fa fa-eercast"></i> INVENTARIO<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse" style="height: 0px;">
                <?php if($_SESSION['INVENTARIO_ADMIN'] == 1){ ?>
                    <!--<li><a href="/produccion/Validacion_ventas.php"><i class="fa fa-check-square-o"></i> Validación Ventas-Insumos<span class='badge badge-danger pull-right' id='validacion_venta'></span></a></li>-->
                    <li><a href="/produccion/almacenes.php" <?php echo $almacenes; ?> ><i class="fa fa-superpowers"></i> Inventario Admin<span class='badge badge-danger pull-right' id=''></span></a></li>
                    <li><a href="/produccion/conversion_articulos.php"  <?php echo $conversion ?> ><i class="fa fa-share-alt"></i> Conversión insumos<span class='badge badge-danger pull-right' id=''></span></a></li>
                    <li><a href="/produccion/cuentas_por_pagar.php"  <?php echo $cuentas_x_pagar ?> ><i class="fa fa-handshake-o"></i> Cuentas por Pagar<span class='badge badge-danger pull-right' id=''></span></a></li>
                    <li><a href="/produccion/proveedor.php"  <?php echo $proveedores ?> ><i class="fa fa-file"></i> Proveedores<span class='badge badge-danger pull-right' id=''></span></a></li>
                    <li><a href="/produccion/insumos.php"  <?php echo $insumos ?> ><i class="fa fa-file"></i> Insumos<span class='badge badge-danger pull-right' id=''></span></a></li>
                    
                <?php }else{ ?>    
                    <li><a href="/produccion/inventario_visual.php"  <?php echo $inventario_general ?> ><i class="fa fa-tasks"></i> Inventario<span class='badge badge-danger pull-right' id=''></span></a></li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>

        <?php
        if(isset($_SESSION['CAPACIDAD']) and $_SESSION['CAPACIDAD'] == 1 ){ ?>
        <li style="background-color: rgba(14,244,182,0.2)" id="indicadores">
            <a href="#"><i class="fa fa-dot-circle-o"></i> INDICADORES<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse" style="height: 0px;">

                    <li><a href="/produccion/capacidad.php" <?php echo $capacidad ?>><i class="fa fa-line-chart"></i> Capacidad de Producción</a></li>

            </ul>
        </li>
        <?php } ?>
        <!-- -->


    <?php

         if(isset($_SESSION['ADMIN']) and $_SESSION['ADMIN'] == 1 ){
        ?>
           <li style="background-color: rgba(138,0,255,0.2)" id="admin">
                <a href="#"><i class="fa fa-black-tie"></i> ADMINISTRADOR<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse" style="height: 0px;">
                    <li>
                        <a href="/produccion/user.php"><i class="fa fa-user"></i> Usuario</a>
                    </li>
                    <li>
                        <a href="/produccion/db_error.php" <?php echo $bd_error; ?>><i class="fa fa-database"></i> Restablecer db</a>
                    </li>
                    <li>
                        <a href="/produccion/reporteador.php" <?php echo $reporte; ?>><i class="fa fa-file-text"></i> Reporteador</a>
                    </li>
                </ul>
           </li>

    <?php
        }
        ?>
        <li style="background-color: rgba(3,22,91,0.4); color:white" id="importar">
                <a href="#"><i class="fa fa-black-tie"></i> COTIZACIÓN<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse" style="height: 0px;">
                    <li>
                        <a href="/produccion/importacion.php"  <?php echo $almacenes; ?> <?php echo $importacion; ?>><i class="fa fa-download"></i> Importar</a>
                    </li>
                    
                </ul>
           </li>
        <?php
    }

    ?>
    

<!--  Modal para Mensajes-->
<div class="modal fade" id="MensajePlataforma" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;z-index: 10000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">MENSAJE</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        MENSAJE
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <div id="mensajePlataformaTexto">

                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
            </div>
        </div>

    </div>

</div>


<!-- Final Modal para Mensajes -->
