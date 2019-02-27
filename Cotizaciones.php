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
<?php
 if(isset($_SESSION['LEVANTAMIENTOS']) and $_SESSION['LEVANTAMIENTOS'] == 1 )
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
                         creaMenu(10);
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
                        <h1 class="page-header"><i class="fa fa-clipboard"></i> Cotizaciones</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	<div class="row">
                	<div class="col-lg-12">
                        <!--Tabla primaria-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cotizaciones
                                <!-- Single button -->
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-default" onclick="reporte()"><span class='fa fa-print'></span></button>
                                   <!--<button type="button" class="btn btn-primary" onclick="actualizaDatagrid()"><span class='fa fa-refresh'></span></button>-->
                                   <!--<button type="button" class="btn btn-success" onclick="cotizacionesrealizadas()"><span class='fa fa-check'></span></button>-->
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
                            <form  id="FormDatagrid">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <ul class="nav nav-pills">
                                                        <input type="text" class="form-control" id="clientefiltro" placeHolder='CLIENTE' ONBLUR="$(this).val($(this).val().toUpperCase())">
                                                        <select class="form-control" id="estatusfiltro">
                                                            <option value="1">PENDIENTE</option>
                                                            <option value="2">REALIZADO</option>
                                                        </select>
                                                        <select class="form-control" id="operadorfiltro" name="operadorfiltro">
                                                            <option value="0">TODOS</option>
                                                        </select>
                                                        <buttom type="button" class="btn btn-info" id="buscar" onclick="actualizaDatagrid()">BUSCAR</buttom>
                                                    </ul>

                                                </div>
                                            </div>

                                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" aria-describedby="dataTables-example_info">
                                        <thead>
                                            <tr role="row">
                                                <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 70px;">FOLIO</th>
                                                <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">FECHA</th>
                                                <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 550px;">CLIENTE / DESCRIPCIÓN</th>
                                                <!--<th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 384px;">DESCRIPCIÓN</th>-->
                                                <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">OPERADOR</th>
                                                <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">ESTATUS</th>
                                                <th tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 20px;" align="center"></th>
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
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-75-screen">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Cotización</h4>
                </div>
                <div class="modal-body">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            Agregar
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                            	<form  id="FormCotizacion">    
                            	<input type="hidden" name="id" id="id" >
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="cliente" class="control-label">CLIENTE:</label>
                                            <input type='text' class="form-control" name="cliente" id="cliente" />
                                        </div>
                                    </div>
                               
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="solicitante" class="control-label">SOLICITANTE</label>
                                            <input type='text' name="solicitante" id="solicitante" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="descripcion" class="control-label">DESCRIPCIÓN:</label>
                                            <textarea class="form-control" rows="5" style="resize:none" name="descripcion" id="descripcion" ></textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">FECHA COTIZACIÓN:</label>
                                            <input type='date' class="form-control" name="fechaCotizacion" id="fechaCotizacion" />
                                        </div>
                                    </div>
                               
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        	<label for="fechaCotizacion" class="control-label">EMPLEADO</label>
                                            <select name="operador" id="operador" class="form-control"></select>
                                        </div>
                                    </div>
                               
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">CORREO DEL CLIENTE</label>
                                            <input type='text' name="email" id="email"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="fechaCotizacion" class="control-label">ESTATUS:</label>
                                            <select class="form-control" name="estatus" id="estatus"></select>
                                            
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
                    <button type="button" class="btn btn-primary" id="guardarCotizacion">GUARDAR</button>
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
	<script language="javascript">
		
		var paginacion = 1;
        var realizados = 0;
		$("#guardarCotizacion").on("click", function()
		{
            //$(this).attr("Disabled", "Disabled");
            //$(this).text("Se esta enviando el correo de notificación, por favor espere un momento");
			if($("#id").val() > 0)
			{
				var variable = "accion=update&"+$("#FormCotizacion").serialize();
				RestFullRequest("_Rest/Cotizacion.php", variable, "agregaRegistro",1);
			}else
			{
				var variable = "accion=save&"+$("#FormCotizacion").serialize();
				RestFullRequest("_Rest/Cotizacion.php", variable, "agregaRegistro",1);
			}
		});
		
        function cotizacionesrealizadas()
        {
            if(realizados == 0)
            {
                realizados = 1;
                actualizaDatagrid();
            }else
            {
                realizados = 0;
               actualizaDatagrid();
            }
        }
		
		function agregaRegistro(response)
		{
			$("#myModal").modal("hide");
			actualizaDatagrid();
		}
		
		$("#agregar").on("click", function()
		{
			$("#myModal").modal("show");
			limpiaForm($("#FormCotizacion"));
		});
		
		$("#borrar").on("click", function()
		{
			var contador = 0;
			$("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
                contador++;
            });
			
			if(contador > 0)
			{
				if(confirm("¿REALMENTE DESEA ELIMINAR EL/LOS REGISTROS?"))
				{
					var variable = "accion=eliminar&"+$("#FormDatagrid").serialize();
					RestFullRequest("_Rest/Cotizacion.php", variable, "actualizaDatagrid", 2);
				}
			}else
				alert("DEBE DE SELECCIONAR UN REGISTRO");
		});
		
		$("#modificar").on("click", function()
		{
			
			var contador = 0;
			$("#FormDatagrid").find("input[type=checkbox]:checked").each(function(index, element) {
                contador++;
            });
			
			if(contador > 0)
			{
				var variable = "accion=modificar&"+$("#FormDatagrid").serialize();
				RestFullRequest("_Rest/Cotizacion.php", variable, "CargaCotizacion");
				$("#myModal").modal("show");
			}else
				alert("DEBE DE SELECCIONAR UN REGISTRO");
		});
		
		function CargaCotizacion(response)
		{
			$("#id").val(response[0].ID);
			$("#cliente").val(response[0].NOMBRECLIENTE);
			$("#descripcion").val(response[0].DESCRIPCION);
			$("#fechaCotizacion").val(response[0].FECHA);
			$("#operador").val(response[0].IDOPERADOR);
            $("#solicitante").val(response[0].SOLICITANTE);
			$("#estatus option[value="+response[0].ESTATUS+"]").prop("selected", true);
			
		}
		
		function actualizaDatagrid()
		{
			var variable = "accion=index&"+$("#FormCotizacion").serialize()+"&realizados="+realizados+"&page="+paginacion+"&clientefiltro="+$("#clientefiltro").val()+"&estatusfiltro="+$("#estatusfiltro").val()+"&operadorfiltro="+$("#operadorfiltro").val();
            RestFullRequest("_Rest/Cotizacion.php", variable, "datagridCotizacion");
		}
		function pages(Obj, value)
        {
            $(".pagination li").removeClass("active");
            $(Obj).addClass("active");
            paginacion = value;
            actualizaDatagrid();
        }
		
		function CargaEstatus(response)
		{
			
			$.each(response, function(index, value)
			{
				
				var contador = 0;
				var arreglo = Array();
				$.each(value, function(index2, value2)
				{
					arreglo[contador] = value2;	
					contador++;	
				});
				$("#estatus").append("<option value='"+arreglo[0]+"'>"+arreglo[1]+"</option>");
				
			});
			
		}
		
		function limpiaForm(Formulario)
		{
			$(Formulario).find('select').each(function(index, element) {
               $(this).find("option").eq(0).prop('selected', true); 
            });
			
			$(Formulario).find("input[type=text]").val("");
			$(Formulario).find("textArea").val("");
			$(Formulario).find("input[type=text]").val("");
			$(Formulario).find("input[type=date]").val("");
            $(Formulario).find("input").val("");
		}
		
		function datagridCotizacion(response)
		{
			actualizaProcesos();
            paginador();
			var datagrid = $("#data");
			datagrid.find("tr").remove();
			var contador = 0;
			$.each(response, function(index, value)
			{
				linea = $("<tr></tr>");
				var campos = "";
				campos += "<td>"+value['COTIZACIONES.ID']+"</td>";
                campos += "<td>"+value['COTIZACIONES.FECHA']+"</td>";
                campos += "<td><b style='color:red'>"+value['COTIZACIONES.NOMBRECLIENTE']+" - "+value['COTIZACIONES.SOLICITANTE']+"</b><br><br><b>"+value['COTIZACIONES.DESCRIPCION']+"</b></td>";
                //campos += "<td>"+value['COTIZACIONES.DESCRIPCION']+"</td>";
                campos += "<td>"+value['OPERADOR.ALIAS']+"</td>";
                campos += "<td>"+value['COTIZACIONESESTATUS.COTIZACIONDESCRIPCION']+"</td>";
               
					
				
				//console.log(value['Cotizacion.ID']);
				campos += "<td align='center'><input type='checkbox' name='id[]' value='"+value['COTIZACIONES.ID']+"'></td>";
				linea.append(campos);
				
				datagrid.append(linea);
				contador++;
			});
			if(contador == 0)
				datagrid.append("<tr><td colspan='9'>NO SE ENCUENTRAN REGISTROS</td></tr>");
		}
		
        function creaPaginador(Response)
        {
            $(".pagination").find("li").remove();
            var paginas = Math.ceil((Response.PAGINADOR / 20));
            var contador = 1;
            while(contador <= paginas)
            {
                if(contador == paginacion)      
                    $(".pagination").append("<li class='paginate_button active' onclick=\"pages(this, "+contador+");\"><a>"+contador+"</a></li>");  
                else    
                    $(".pagination").append("<li class='paginate_button' onclick=\"pages(this, "+contador+");\"><a>"+contador+"</a></li>");
                contador++;
            }
        }

        function paginador()
        {
            var variable = "accion=counter&empresa=1&realizados="+realizados+"&clientefiltro="+$("#clientefiltro").val()+"&estatusfiltro="+$("#estatusfiltro").val();
            RestFullRequest("_Rest/Cotizacion.php", variable, "creaPaginador");
            
        }

		$(document).ready(function(e) {
			actualizaDatagrid();
           // setInterval(actualizaDatagrid,  900000);
            $("#operacion").find("a").click();
			
			var variable = "accion=cargaEstatus&page="+paginacion;
			RestFullRequest("_Rest/Cotizacion.php", variable, "CargaEstatus");

            var variable = "accion=cargaOperador";
            RestFullRequest("_Rest/Cotizacion.php", variable, "CargaOperador");
        });

        function CargaOperador(response)
        {
            var operador = $("#operador");
            var operadorfiltro = $("#operadorfiltro");

            $.each(response, function(index, value)
            {
                operador.append("<option value='"+value['OPERADOR.ID']+"'>"+value['OPERADOR.ALIAS']+"</option>");
                operadorfiltro.append("<option value='"+value['OPERADOR.ID']+"'>"+value['OPERADOR.ALIAS']+"</option>");
            });
        }

        function reporte()
        {

            $("#FormDatagrid").attr("action","reportes/cotizacion/ReporteCotizaciones.php");
            $("#FormDatagrid").attr("method","POST");
            $("#FormDatagrid").attr("target","_blank");
            $("#FormDatagrid").submit();
            $("#FormDatagrid").attr("action","");
            $("#FormDatagrid").attr("method","");
            $("#FormDatagrid").attr("target","");
            //$("#FormDatagrid").attr("src","").attr("target","");
        }
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