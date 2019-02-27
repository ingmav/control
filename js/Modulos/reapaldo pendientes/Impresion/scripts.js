var empresa = 1;
var paginacion = 1;
var buscar = "";
var activas = 1;
var id_aux = 0;
var btn_seleccionador;
	

function datagridPendientes(response)
{
	var datagrid = $("#PendientesImpresion");
	datagrid.find("tr").remove();
	var contador = 0;
	$.each(response, function(index, value)
    {
        
        var campos = "";
        var index = 0;
        var id;
        var orden_dia = "";
        id                  = value['IDPRODUCCION'];
        idtablero           = value['IDTABLERO'];
        idtableroproduccion = value['ID']; 

        var labelPrioridad = "";
        var labelCancelacion = "";
        
        var btn = "";
        var icono = "";
        if(value['ACTIVACION'] == "" || value['ACTIVACION']==0)
        {
            btn = "default";
            icono = "pause";    
        }else
        {
            btn = "warning";
            icono = "check-square-o";    
        }

        if(value['EMPRESA'] !=3 )
            campos += "<td><button class='btn  btn-"+btn+"' type='button' onclick='cambia_estatus(this, "+value['EMPRESA']+")'><i class='fa fa-"+icono+"'></i> "+value['FOLIO']+"</button></td>";
        else
            campos += "<td><button class='btn  btn-"+btn+"' type='button' onclick='cambia_estatus_mostrador(this)'><i class='fa fa-"+icono+"'></i> "+value['FOLIO']+"</button></td>";
        
        texto_descripcion = "";

        texto_descripcion = value['NOTAS'];

        if(texto_descripcion != "undefined")
            texto_descripcion += "<br>";
        else
            texto_descripcion = "";

        $.each(value['MATERIALES'], function(index2, value2)
        {
            texto_descripcion += "- "+value2['NOMBRE']+" ("+parseFloat(value2['UNIDADES'],2)+") <br>";
        });

        
        campos += "<td><font style='background-color:green;color:white'>"+value['FECHA']+"</font><br><font style='background-color:red;color:white'>"+value['F_ENTREGA']+"</font></td>";
        
        campos += "<td style='text-align:justify'><b style='color:blue'>"+value['NOMBRE_CLIENTE']+"</b><br>"+value['DESCRIPCION']+"</td>";

        
        campos += "<td>"+value['NOMBRE_OPERADOR']+"</td>";
        if(value['EMPRESA']!=3)
          linea = $("<tr data-fila='"+id+"' data-tablero='"+idtablero+"'  data-empresa='"+value['EMPRESA']+"' data-id='"+idtableroproduccion+"' style='color:blue;font-weight:bold;background: #e1e1e1;'></tr>");
            else
          linea = $("<tr data-fila='"+id+"' data-tablero='"+idtablero+"'  data-empresa='"+value['EMPRESA']+"' data-id='"+id+"' style='color:blue;font-weight:bold;background: bisque;'></tr>");
        
        
        var observacion = "<button type='button' class='btn btn-strech  btn-info' title='OBSERVACIONES' onClick='observaciones(this, "+value['EMPRESA']+")'>"+value['CONTADOR_MESSAGE']+"<i class='fa fa-comment'></i></button>";
        var eliminar = "<button type='button' class='btn btn-strech  btn-danger' title='ELIMINAR' onClick='cancelar(this, "+value['EMPRESA']+")'><i class='fa fa-close'></i></button>";
        var finalizar = "<button type='button' class='btn btn-strech btn-success' title='FINALIZAR ACTIVIDAD' onClick='finalizar(this, "+value['EMPRESA']+")'><i class='fa fa-check'></i></button>";
        
        //prioridad   turnar   rechazo
        if($("#realizados").val() == 1) 
        {
            if(value['EMPRESA'] !=3 )
                campos += "<td>"+observacion+" "+eliminar+" "+finalizar+"</td>";
            else
                campos += "<td>"+observacion+" "+eliminar+" "+finalizar+"</td>";
        }
        else
            campos += "<td><label class='label label-success'>REALIZADO</label></td>";

        linea.append(campos);
        datagrid.append(linea);

        var linea2 = $("<tr></tr>");
        campo_unico = $("<td colspan='5'>"+texto_descripcion+"</td>");
        var linea3 = $("<tr></tr>");
        campo_blanco = $("<td colspan='5'></td>");
        
        linea2.append(campo_unico);
        datagrid.append(linea2);

        linea3.append(campo_blanco);
        datagrid.append(linea3);
        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}


    function cambia_estatus(obj, empresa)
    {
        btn_seleccionador = obj;
        $(obj).find("i").removeClass("fa-pause");
        $(obj).find("i").removeClass("fa-check-square-o");
        $(obj).find("i").addClass("fa-circle-o-notch fa-spin");

        var variable = "accion=activarActividad&id="+$(obj).parents("tr").data("tablero")+"&EMPRESA="+empresa;
        RestFullRequest("_Rest/General.php", variable, "ActivaActividad");
        
    }

    function cambia_estatus_mostrador(obj)
    {
        btn_seleccionador = obj;
        $(obj).find("i").removeClass("fa-pause");
        $(obj).find("i").removeClass("fa-check-square-o");
        $(obj).find("i").addClass("fa-circle-o-notch fa-spin");

        var variable = "accion=activarActividad&id="+$(obj).parents("tr").data("fila");
        RestFullRequest("_Rest/PendientesPv.php", variable, "ActivaActividadMostrador");
        
    }

function ActivaActividadMostrador(response)
{
    console.log(response);
    $(btn_seleccionador).find("i").removeClass("fa-circle-o-notch fa-spin");
    if(response.data == 1)
    {
        $(btn_seleccionador).removeClass("btn-default");
        $(btn_seleccionador).addClass("btn-warning");
        $(btn_seleccionador).find("i").removeClass("fa-pause");
        $(btn_seleccionador).find("i").addClass("fa-check-square-o");     
    }else if(response.data == 0)
    {
        $(btn_seleccionador).addClass("btn-default");
        $(btn_seleccionador).removeClass("btn-warning");
        $(btn_seleccionador).find("i").addClass("fa-pause");
        $(btn_seleccionador).find("i").removeClass("fa-check-square-o");

    }
}

function ActivaActividad(response)
{
    console.log(response);
    $(btn_seleccionador).find("i").removeClass("fa-circle-o-notch fa-spin");
    if(response.data == 1)
    {
        $(btn_seleccionador).removeClass("btn-default");
        $(btn_seleccionador).addClass("btn-warning");
        $(btn_seleccionador).find("i").removeClass("fa-pause");
        $(btn_seleccionador).find("i").addClass("fa-check-square-o");     
    }else if(response.data == 0)
    {
        $(btn_seleccionador).addClass("btn-default");
        $(btn_seleccionador).removeClass("btn-warning");
        $(btn_seleccionador).find("i").addClass("fa-pause");
        $(btn_seleccionador).find("i").removeClass("fa-check-square-o");

    }
}

function actualizadatagridinventario(id)
{
    if(empresa!=3)
    {
        id_aux = id;
        $("#registrosarticulos").html("");
        var variable = "accion=cargainventario&idproduccion="+id+"&empresa="+empresa;
        RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargadatagridinventario");
    }else
    {
        id_aux = id;
        $("#registrosarticulos").html("");
        var variable = "accion=cargainventario&idpv="+id;

        RestFullRequest("_Rest/PendientesPv.php", variable, "cargadatagridinventario");
    }
}

function cargadatagridinventario(Response)
{
    if(empresa != 3)
    {
        var variable = "accion=cargainventarioutilizado&idproduccion="+id_aux+"&empresa="+empresa;
        RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargadocumentoutilizado");

        $.each(Response[0], function(index, value)
        {

            texto = "<div class='row'>";
            texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBRELINEA']+"</label></div>";
            texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBREARTICULO']+"</label></div>";
            texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBRESUBARTICULO']+"</label></div>";
            texto+= "<div class='col-lg-1'><label class='control-label'>"+value['CANTIDAD']+"</label></div>";
            texto+= "<div class='col-lg-1'><label class='control-label'>"+value['MERMA']+"</label></div>";
            texto+= "<div class='col-lg-3'><label class='control-label'>"+value['MOTIVO']+"</label></div>";
            texto+= "<div class='col-lg-1'><label class='control-label'><button class='btn btn-strech btn-danger' type='button' onclick='eliminaInventario("+value['ID']+")'><i class='fa fa-close'></i></i></button></label></div>";
            texto+= "</div>";

            $("#registrosarticulos").append(texto);
        });
    	
    	$("#notas_impresion").html("");	
    	$.each(Response[1], function(index, value)
        {
        	console.log(value);
        	var texto = "";	
            texto = " - <b>"+value['TABLEROOBSERVACION.FECHAOBSERVACION']+"</b> -- "+value['TABLEROOBSERVACION.OBSERVACION']+"<br>";
            
            $("#notas_impresion").append(texto);
        });

        $("#articulosUtilizados").html("");
    }else
    {
        var variable = "accion=cargainventarioutilizado&idpv="+id_aux;
        RestFullRequest("_Rest/PendientesPv.php", variable, "cargadocumentoutilizado");

        $.each(Response, function(index, value)
        {

            texto = "<div class='row'>";
            texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBRELINEA']+"</label></div>";
            texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBREARTICULO']+"</label></div>";
            texto+= "<div class='col-lg-2'><label class='control-label'>"+value['NOMBRESUBARTICULO']+"</label></div>";
            texto+= "<div class='col-lg-1'><label class='control-label'>"+value['CANTIDAD']+"</label></div>";
            texto+= "<div class='col-lg-1'><label class='control-label'>"+value['MERMA']+"</label></div>";
            texto+= "<div class='col-lg-3'><label class='control-label'>"+value['MOTIVO']+"</label></div>";
            texto+= "<div class='col-lg-1'><label class='control-label'><button class='btn btn-strech btn-danger' type='button' onclick='eliminaInventario("+value['ID']+")'><i class='fa fa-close'></i></i></button></label></div>";
            texto+= "</div>";

            $("#registrosarticulos").append(texto);
        });

        $("#articulosUtilizados").html("");
    }


}

function cargadocumentoutilizado(Response)
{
    texto = "<div class='row' style='background: #CFCFCF'>";
    texto+= "<div class='col-sm-8'><label class='control-label'>ARTICULO</label></div>";
    texto+= "<div class='col-sm-2'><label class='control-label'>UNIDADES</label></div>";
    //texto+= "<div class='col-lg-2'><label class='control-label'>UTILIZADOS</label></div>";
    texto+= "</div>";
    $.each(Response, function(index, value)
    {
        var estilo = "";
        if((index%2) == 1)
            estilo = " style='background:#EFEFEF'";

        texto += "<div class='row' "+estilo+">";
        texto+= "<div class='col-sm-8'><label class='control-label'>"+value['NOMBRE']+"</label></div>";
        texto+= "<div class='col-sm-2'><label class='control-label'>"+parseFloat(value['UNIDADES'])+"</label></div>";
        //texto+= "<div class='col-lg-2'><label class='control-label'>"+parseFloat(value['UTILIZADO'])+"</label></div>";
        texto+= "</div>";

        $("#articulosUtilizados").append(texto);
        texto = "";
    });
}

function eliminaInventario(id)
{
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
        if(empresa!=3)
        {
            var variable = "accion=deleteinventario&idinventario="+id+"&empresa="+empresa;
            RestFullRequest("_Rest/PendientesImpresion.php", variable, "refresDatagridInventario");
        }else
        {
            var variable = "accion=deleteinventario&idinventario="+id;
            RestFullRequest("_Rest/PendientesPv.php", variable, "refresDatagridInventario");
        }
    }
}

function refresDatagridInventario()
{
    actualizadatagridinventario($("#idproduccion").val());
}

function buscarRealizados()
{
	var variable = "accion=index&empresa="+empresa+"&realizados="+$("#realizados").val()+"&fecha="+$("#fecha").val()+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "datagridPendientes");
    $("#PendientesImpresion").html("<tr><td colspan='4'>Cargando</td></tr>");
}	

function activaProceso(idProceso, emp, tipo)
{
	var variable = "accion=saveActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
}

function desactivaProceso(idProceso, emp, tipo)
{
	var variable = "accion=deleteActividadProceso&empresa="+emp+"&proceso="+idProceso+"&tipo="+tipo;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid");
}

function Activas()
{
	if(activas == 1)
		activas = 2;
	else
		activas = 1;

	actualizaDatagrid();	
}

function verObservacionesRechazo(id, emp)
{
	empresa = emp;
	var variable = "accion=vercancelacion&empresa="+empresa+"&id="+$(id).parents("tr").data("fila");
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaCancelacion");
}


function contadorMensajes(id, emp)
{
	empresa = emp;
	var variable = "accion=countMessaje&empresa="+empresa+"&id="+id;
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaCountMessaje");
}

function cargaCountMessaje(Response)
{
	if(Response[0].count > 0)
		$("#"+Response[0].ID).find("button:eq(1)").removeClass("btn-info").addClass("btn-primary");	
	$("#"+Response[0].ID).find("button:eq(1)").append(Response[0].count);
}

function observaciones(obj, emp)
{
	empresa = emp;
    
    if($(obj).parents("tr").data("empresa") != 3)
    {
        $("#idtablero").val($(obj).parents("tr").data("id"));
        var variable = "accion=observaciones&EMPRESA="+empresa+"&id="+$(obj).parents("tr").data("id")+"&departamento=3";
        
        RestFullRequest("_Rest/General.php", variable, "cargaObservaciones");    
    }else
    {
        empresa = 3;
        $("#id").val($(obj).parents("tr").data("fila"));// Es aqui
        var variable = "accion=observaciones&id="+$(obj).parents("tr").data("fila")+"&departamento=3";
        RestFullRequest("_Rest/PendientesPv.php", variable, "cargaObservaciones");
    }
	
}

function turnarEmpleado(obj, emp)
{
	empresa = emp;
	$("#idtablerofinalizar").val($(obj).parents("tr").data("fila"));
	$("#finalizaTarea").modal("show");


}

function finalizar(obj, emp)
{
	empresa = emp;
    if(empresa != 3)
    {	
        $("#idtablerofinalizar").val($(obj).parents("tr").data("id"));
        $("#idfinalizar").val(0);
        $("#idproduccion").val($(obj).parents("tr").data("id"));
    	$("#finalizaTarea").modal("show");
        //actualizadatagridinventario($(obj).parents("tr").data("tablero"));
        lista_inventario(emp, $(obj).parents("tr").data("tablero"), $(obj).parents("tr").data("fila"));
    }else
    {
        
        $("#finalizaTarea").modal("show");
        $("#finalizaTarea textArea").val("");
        $("#idtablerofinalizar").val($(obj).parents("tr").data("tablero"));
        $("#idfinalizar").val($(obj).parents("tr").data("tablero"));
        $("#idproduccion").val($(obj).parents("tr").data("tablero"));
        //actualizadatagridinventario($(obj).parents("tr").data("fila"));
    }

}

function lista_inventario(tipo, id, venta)
{
    if(tipo == 2)
    {
        var variable = "accion=cargaInventario_MS&empresa="+empresa+"&id="+id+"&venta="+venta;
        RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaInventario");
    }
}

function cargaInventario(response)
{
    var datagrid = $("#datagridInventario");
    datagrid.find("tr").remove();
    var contador = 0;

    $.each(response, function(index, value)
    {

        var campos = "";
        var campos2 = "";
        var campos3 = "";
        var index = 0;
        var id, empresa;
        id = value['ID'];
        empresa = value['EMPRESA'];

        campos += "<td>"+value['FOLIO']+"</td>";
        campos += "<td>"+value['TITULO']+"</td>";
        //campos += "<td>$ "+moneda(value['IMPORTE'], 2, [",", "."])+"</td>";
        linea = $("<tr data-fila='"+id+"' id='"+id+"' style='background-color: rgba(0,28,200,0.2)'></tr>");
        
        //campos +="<td><input type='checkbox' name='venta[]' value='"+empresa+"_"+id+"'></td>";
        linea.append(campos);

        datagrid.append(linea);

        linea2 = $("<tr></tr>");
        campos2 = $("<td colspan='4'></td>");

        linea2.append(campos2);
        datagrid.append(linea2);

        var table = $("<table class='table table-hover dataTable no-footer'></table>");
        linea3 = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

        campos3 +="<th>ARTICULO</th>";
        
        campos3 +="<th>ARTICULO MS</th>";
        campos3 +="<th>UNIDAD MS</th>";
        campos3 +="<th>ARTICULO INVENTARIO MS</th>";
        campos3 +="<th>DAR BAJA/th>";
        
        linea3.append(campos3);
        table.append(linea3);
        var linea3 = "";
        var campos3 = "";
        var venta_det_id , venta_id;

        $.each(value['INSUMOS'], function(indice, valor)
        {
            var id_insumo     = valor['ID'];
            venta_det_id  = valor['ID_VENTA_DET'];
            venta_id      = valor['ID_VENTA'];

            linea3 = $("<tr data-fila='"+id+"' id='"+id+"'></tr>");

            campos3 +="<td>"+valor['ARTICULO']+"</td>";
            //campos3 +="<td>"+valor['PRECIO']+"</td>";
            var precio = 0.00;
            var unidades = 0;
            
            if(valor['CANTIDAD'])
            {
                //campos3 +="<td>"+(valor['CANTIDAD'] * valor['UNIDADES'])+"</td>";
                var select = $("<select class='form-control' style='width:300px' name='articulo_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='verifica_precio("+id+","+id_insumo+", this.value)'></select>");    
                $.each(valor['ARTICULOS_WEB'], function(indice2, valor2)
                {
                    if(valor2['ID'] == valor['AUTOMATICO']){
                        var opcion = $("<option value='"+valor2['ID']+"' selected='selected'>"+valor2['NOMBRE']+"</option>");
                        precio = valor2['PRECIO'];
                    }
                    else
                        var opcion = $("<option value='"+valor2['ID']+"'>"+valor2['NOMBRE']+"</option>");
                    select.append(opcion);
                });
                
                var option = $("<td></td>");
                option.append(select);
            }
            else
            {
                campos3 +="<td>N/A</td>";
                campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
                //campos3 +="<td>N/A</td>";
            }

            unidades = currency(valor['UNIDADES'], 2, ["."]);

            //campos3 +="<td>"+currency(valor['UNIDADES'], 2, ["."])+"</td>";
            linea3.append(campos3);

            if(precio > 0)
                precio = precio;
            else
                precio =  0.00;
            if(valor['CANTIDAD'])
            {
                linea3.append(option);
                //linea3.append($("<td><input type='text' id='precio_unitario_"+id+"_"+id_insumo+"' name='precio_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+precio+"'></td>"));
                linea3.append($("<td><input type='text' id='unidades_"+id+"_"+id_insumo+"' name='unidades_web["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' onchange='calcula_total("+id+","+id_insumo+")' class='form-control' style='width:80px' value='"+unidades+"'><input type='hidden' id='unidades_"+id+"_"+id_insumo+"' name='detalle["+empresa+"_"+venta_id+"][]' value='"+venta_det_id+"'><input type='hidden' name='descripcion_articulo_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['ARTICULO']+"'><input type='hidden' name='precio_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+valor['PRECIO']+"'><input type='hidden' name='unidad_v["+empresa+"_"+venta_id+"_"+venta_det_id+"][]' value='"+(valor['CANTIDAD'] * valor['UNIDADES'])+"'></td>"));
                //linea3.append($("<td id='precio_total_"+id+"_"+id_insumo+"'>"+currency((precio * unidades), 2, [",","."])+"</td>"));

            }

            
            table.append(linea3);
            var linea3 = "";
            var campos3 = "";
        });

        campos2.append(table);

        contador++;
    });
    if(contador == 0)
        datagrid.append("<tr><td colspan='8'>NO SE ENCUENTRAN REGISTROS</td></tr>");
}

function cancelar(obj, emp)
{
    empresa = emp;
    if(empresa != 3)
    {
    	$("#idtablerocancelar").val($(obj).parents("tr").data("id"));
    	$("#CancelarTarea").modal("show");
    }else
    {
        $("#idtablerocancelar").val($(obj).parents("tr").data("tablero"));
        $("#CancelarTarea").modal("show");
    }

}



function operadores()
{
	var variable = "accion=operadores&empresa="+empresa+"&"+$("#FormLevantamiento").serialize();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "cargaOperadores");
}

function guardarObservacion()
{
    if(empresa!=3)
    {
    	var variable = "accion=saveObservacion&empresa="+empresa+"&"+$("#formObservaciones").serialize();
        RestFullRequest("_Rest/PendientesImpresion.php", variable, "actualizaDatagrid", 1);
        $("#observaciones").modal("hide");
    }else
    {
        var variable = "accion=saveObservacion&"+$("#formObservaciones").serialize()+"&departamento=3";
        RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
        $("#observaciones").modal("hide");
    }
	
}

function guardarTurnar()
{
    if(empresa!=3)
    {
        var variable = "accion=saveTurnar&empresa="+empresa+"&departamento=3&"+$("#formFinalizar").serialize();
        RestFullRequest("_Rest/General.php", variable, "actualizaDatagrid");

        $("#finalizaTarea").modal("hide");
    }else
    {
        var variable = "accion=saveTurnar&empresa="+2+"&"+$("#formFinalizar").serialize()+"&departamento=3";
        RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid", 1);
        $("#finalizaTarea").modal("hide");
    }
	
}

function guardar()
{
    if(empresa!=3)
    {
        var variable = "accion=save&empresa="+empresa+"&departamento=3&"+$("#formFinalizar").serialize();
        RestFullRequest("_Rest/General.php", variable, "actualizaDatagrid", 1);
        $("#finalizaTarea").modal("hide");
    	
    }else
    {
        var variable = "accion=save&empresa="+2+"&"+$("#formFinalizar").serialize()+"&departamento=3";
        RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid",1);
        $("#finalizaTarea").modal("hide");
    }
}

function verificarinventario(Response)
{
    //console.log(Response);
    /*if(Response[0]['Respuesta'] == 0)
    {
        alert("Debe de reducir de inventario para poder finalizar la actividad");
    }else if(Response[0]['Respuesta'] == 1)
    {
        alert("Es la ultima actividad de este documento, debe de finalizar todo el inventario para poder continuar");
    }else
    {*/
        actualizaDatagrid();
        $("#finalizaTarea").modal("hide");
    //}

}

function cancelaActividad()
{
    if(empresa!=3)
    {
        var variable = "accion=cancelar&empresa="+empresa+"&departamento=3&"+$("#formCancelar").serialize();
        RestFullRequest("_Rest/General.php", variable, "actualizaDatagrid");
        $("#CancelarTarea").modal("hide");
    }else
    {
        var variable = "accion=cancelar&"+$("#formCancelar").serialize()+"&departamento=3";
        RestFullRequest("_Rest/PendientesPv.php", variable, "actualizaDatagrid");
        $("#CancelarTarea").modal("hide");
    }
}

function cargaObservaciones(Response)
{
	var contador = 0;
	$("#descripcionobservaciones").find("tr").remove();
	$("#observaciones").modal("show");
	$.each(Response, function(index, value)
	{
        if(value['TABLEROOBSERVACION.OBSERVACION'])
            $("#descripcionobservaciones").append("<tr><td> > "+value['TABLEROOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['TABLEROOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
        else
            $("#descripcionobservaciones").append("<tr><td> > "+value['PVOBSERVACION.OBSERVACION']+"</td><td> <i class='fa fa-clock-o'></i><i><span style='font-size:9pt;'> "+value['PVOBSERVACION.FECHAOBSERVACION']+"</i></span></td></tr>");
		contador++;
	});
	if(contador < 1)
		$("#descripcionobservaciones").append("<tr><td>NO EXISTEN OBSERVACIONES</td></tr>");	
	$("#descripcionobservaciones").append("<tr><td colspan='2'><textarea style='resize:none' class='form-control' name='observacion'></textarea></td></tr>");
	
}

function cargaOperadores(Response)
{
    console.log(Response);
	$.each(Response, function(index, value)
	{
		$("#selectEmpleado").append('<option value='+value['OPERADOR.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		$("#EmpleadoFinalizar").append('<option value='+value['OPERADOR.ID']+'>'+value['OPERADOR.ALIAS']+'</option>');
		
	});
}

function actualizaDatagrid()
{

	var variable = "accion=index&empresa="+empresa+"&page="+paginacion+"&buscar="+$("#search").val()+$("#FormLevantamiento").serialize()+"&activas="+activas+"&foliofiltro="+$("#foliofiltro").val()+"&clientefiltro="+$("#clientefiltro").val();
    RestFullRequest("_Rest/PendientesImpresion.php", variable, "datagridPendientes");
	//paginador();
}

$(document).ready(function(e) {

	actualizaDatagrid();

	//setInterval(actualizaDatagrid,  900000);
    $("#operacion").find("a").click();
	operadores();
    inicializafiltro();
});


function btnagregaInventario()
{
    if($("#agregarsubArticulo").val() == 0 && $("#agregarsubArticulo option").size() > 1 )
    {
        alert("Debe de seleccionar el tipo de sub articulo, para continuar");
    }else
    {
        if($("#cantidad").val() != "" && $("#merma").val() != "" && $("#lineaArticulo").val!=0 && $("#agregarArticulo").val!=0)
        {
            if(empresa!=3)
            {
                var variable = "accion=saveInventario&"+$("#formInventario").serialize()+"&empresa="+empresa;
                RestFullRequest("_Rest/PendientesImpresion.php", variable, "refresDatagridInventario");
            }else
            {
                var variable = "accion=saveInventario&"+$("#formInventario").serialize()+"&empresa="+2;
                RestFullRequest("_Rest/PendientesPv.php", variable, "refresDatagridInventario");
            }

            $("#formInventario #cantidad, #merma, #motivo").val("");
            $("#formInventario #cantidad, #merma").val("0");
            $("#lineaArticulo").val(0);
            $("#lineaArticulo").change();
        }else
        {
            alert("Debe de ingresar en el articulo, la cantidad y la merma de producto");
        }
    }
   
}

function ReporteImpresion()

{
	$("#repImpresion").attr("action", "ReporteImpresion.php?tipo=3");
	$("#repImpresion").attr("target", "_blank");
	$("#repImpresion").submit();
	$("#repImpresion").attr("action", "");
	$("#repImpresion").attr("target", "");
}

$("#lineaArticulo").on("change", function(){
    var variable = "accion=inicializacomboarticulos&linea="+$("#lineaArticulo").val();
    RestFullRequest("_Rest/Inventario.php", variable, "cargacomboarticulos");
});

function cargacomboarticulos(Response)
{
    $("#agregarArticulo").html("");
    $("#agregarArticulo").append("<OPTION value='0'>SELECCIONE UNA OPCIÓN</OPTION>");


    $.each(Response, function(index, value)
    {
        $("#agregarArticulo").append("<option value="+value['ID']+">"+value['NOMBRE']+"</option>");
    });

    $("#agregarsubArticulo").html("");
}

$("#agregarArticulo").on("change", function()
{
    var variable = "accion=inicializasubcomboarticulos&articulo="+$("#agregarArticulo").val();
    RestFullRequest("_Rest/Inventario.php", variable, "cargasubcomboarticulos");
});

function cargasubcomboarticulos(Response)
{
    $("#agregarsubArticulo").html("");
    $("#agregarsubArticulo").append("<OPTION value='0'>SELECCIONE UNA OPCIÓN</OPTION>");


    $.each(Response, function(index, value)
    {
        $("#agregarsubArticulo").append("<option value="+value['ID']+">"+value['NOMBRE']+"</option>");
    });
}

function inicializafiltro()
{
    var variable = "accion=inicializafiltro";
    RestFullRequest("_Rest/Inventario.php", variable, "cargaFiltro");
}

function cargaFiltro(Response)
{
    $("#linefilter").append("<option VALUE='0'>TODOS</option>");
    $.each(Response, function(index, value)
    {
        $("#linefilter").append("<option VALUE='"+value['IDLINEA']+"'>"+value['NOMBRELINEA']+"</option>");
        $("#lineaArticulo").append("<option VALUE='"+value['IDLINEA']+"'>"+value['NOMBRELINEA']+"</option>");
    });
}
