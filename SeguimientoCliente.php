<?php
/**
 * Created by PhpStorm.
 * User: SALUD
 * Date: 25/10/15
 * Time: 23:21
 */
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

    <title>Microsip 2.0</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">


    <link href="css/general.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script language="javascript" type="text/javascript" src="graficas/excanvas.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="graficas/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="graficas/jquery.jqplot.min.js"></script>
    <script language="javascript" type="text/javascript" src="graficas/plugins/jqplot.barRenderer.js"></script>
    <script language="javascript" type="text/javascript" src="graficas/plugins/jqplot.highlighter.js"></script>
    <script language="javascript" type="text/javascript" src="graficas/plugins/jqplot.cursor.js"></script>
    <script language="javascript" type="text/javascript" src="graficas/plugins/jqplot.pointLabels.js"></script>

    <link rel="stylesheet" type="text/css" href="graficas/jquery.jqplot.css" />

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
        <!-- /.navbar-header -->
        <?php
            ventas();
        ?>  

        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <?php creaMenu(23); ?>

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
                    <h1 class="page-header"><i class="fa fa-thumb-tack"></i> Seguimiento Cliente</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!--Tabla primaria-->
                    <div class="panel panel-default">
                        <div class="panel-heading">Seguimiento Cliente</div>
                        <!-- /.panel-heading -->
                        <form id='Graficas'>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-pills">
                                                    <select name="anio" id="anio" class="form-control">
                                                        <option value="2014">2014</option>
                                                        <option value="2014">2015</option>
                                                    </select>
                                                    <select name="clientecall" id="clientecall" class="form-control">

                                                    </select>


                                                    <buttom type="button" class="btn btn-info" id="buscar" onclick="actualizaDatagrid()">BUSCAR</buttom>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="chartdiv" style="height:auto;width:100%; "></div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.table-responsive -->

                                </div>
                                <!-- /.panel-body -->
                            </div>
                        </form>
                <!-- /fin tabla primaria -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/plugins/metisMenu/metisMenu.min.js"></script>

<!-- Morris Charts JavaScript -->
<!--<script src="js/plugins/morris/raphael.min.js"></script>
<script src="js/plugins/morris/morris.min.js"></script>
<script src="js/plugins/morris/morris-data.js"></script>-->

<!-- Custom Theme JavaScript -->
<script src="js/RestFull.js"></script>
<script src="js/sb-admin-2.js"></script>
<script src="js/complemento.js"></script>
<script src="js/Modulos/General.js"></script>
<script>

    function actualizaDatagrid(Response)
    {
        var arreglo= Array();
        var arreglo2= Array();
        var cont = 0;
        $.each(Response, function(index, value)
        {
            arreglo.push(Array(parseInt(value.MES), parseInt(value.IMPORTE)));
            cont++;
        });


        plot1 = $.jqplot("chartdiv", [arreglo], {
            // Turns on animatino for all series in this plot.
            animate: true,
            // Will animate plot on calls to plot1.replot({resetAxes:true})
            animateReplot: true,
            cursor: {
                show: true,
                zoom: false,
                looseZoom: false,
                showTooltip: false
            },
            series:[
                {
                    pointLabels: {
                        show: true
                    },
                    renderer: $.jqplot.BarRenderer,
                    showHighlight: false,
                    yaxis: 'y2axis',
                    rendererOptions: {
                        // Speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for bar series is 3000.
                        animation: {
                            speed: 500
                        },
                        barWidth: 55,
                        barPadding: -95,
                        barMargin: 0,
                        highlightMouseOver: false
                    }
                },
                {
                    rendererOptions: {
                        // speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for a line series is 2500.
                        animation: {
                            speed: 2000
                        }
                    }
                }
            ],
            axesDefaults: {
                pad: 0
            },
            axes: {
                // These options will set up the x axis like a category axis.
                xaxis: {
                    tickInterval: 1,
                    drawMajorGridlines: false,
                    drawMinorGridlines: true,
                    drawMajorTickMarks: false,
                    rendererOptions: {
                        tickInset: 0.5,
                        minorTicks: 1
                    }
                },
                yaxis: {
                    tickOptions: {
                        formatString: "%'d"
                    },
                    rendererOptions: {
                        forceTickAt0: true
                    }
                },
                y2axis: {
                    tickOptions: {
                        formatString: "%'d"
                    },
                    rendererOptions: {
                        // align the ticks on the y2 axis with the y axis.
                        alignTicks: true,
                        forceTickAt0: true
                    }
                }
            },
            highlighter: {
                show: true,
                showLabel: true,
                tooltipAxes: 'y',
                sizeAdjust: 7.5 , tooltipLocation : 'ne'
            }
        });

    }

    $(document).ready(function () {
        var variable = "accion=cargaCatalogos&tipo=2";
        RestFullRequest("_Rest/Telemarketing.php", variable, "rellenaFormulario");
        $("#call").find("a").click();
        //var variable = "accion=index";
        //RestFullRequest("_Rest/Graficacion.php", variable, "actualizaDatagrid");

    });

    function rellenaFormulario(response)
    {
        $.each(response, function(index, value)
        {
            $("#clientecall").append("<option  value="+value['CLIENTESCALL.ID']+">"+value['CLIENTESCALL.NOMBRE']+"</option>");
        });
    }
</script>

</body>
</html>