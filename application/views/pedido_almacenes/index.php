<style>
    .seleccionado{
        cursor: pointer;
    }

    #refresh img{
        margin-left: 50px;
    }
    
    .pagina_seleccionada{
        color: blue;
        background-color: 'green';
    }
    
    .derecha_text { 
        text-align: right; 
    }
    
    .centro_text { 
        text-align: center; 
    }
</style>

<div class="container-fluid" style="margin: 0 25px;">
    <form method="post" action="" autocomplete="off" name="form1" id="form1">
        <div align="center"><span style="font-size: 23px" align="center"></span></div>        
        <div id="panel_fomulario" class="panel panel-info">
            <div class="panel-heading" >
                <div class="panel-title" align="center"><span style="font-size: 23px" align="center">PEDIDOS ALMACÉN</span></div>                        
            </div>
            <div class="panel-body">   
                <div class="row" >
                    <div class="col-lg-2 form-inline"  >
                        <label>Personal:</label><br>
                        <select class="form-control input-sm" name="empleado" id="empleado">
                        </select>
                    </div>
                    
                    <div class="col-lg-1" >
                        <label>Número Pedido</label><br>
                        <input type="text" class="form-control input-sm" id="numero" name="numero" placeholder="numero">
                    </div>                    
                    
                    <div class="col-lg-4 form-inline"  >
                        <label>Fecha Pedido.</label><br>
                        <input class="form-control input-sm" type="text" name="fecha_emision_inicio" id="fecha_emision_inicio" value="" placeholder="Desde">
                        <input class="form-control input-sm" type="text" name="fecha_emision_final" id="fecha_emision_final" value="" placeholder="Hasta">
                    </div>
                </div>
                <div class="row" >                         
                    <div class="col-lg-1" style="text-align: left;"  >
                        <label></label><br>
                        <a name="buscar_comprobante" id="buscar_comprobante" class="btn btn-primary">Buscar</a>
                    </div>
                    <div class="col-lg-4" style="text-align: right;">
                        <label></label><br>                                                
                        <a id="nuevo_comprobante" name="nuevo_comprobante" class="btn btn-success">Nuevo</a>
                        <a id="exportarExcel" class="btn btn-primary btn_nuevo_comprobante"><i class="glyphicon glyphicon-save"></i> Reporte</a>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px">                
                    <div class="col-xs-6 form-inline">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div align="center" class="container-fluid">
    <div class="row-fluid">
        <table id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Personal</th>
                    <th>Número Pedido</th>
                    <th>F.Emisión</th>
                    <th>Estado</th>                    
                    <th class="centro_text">A4</th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                </tr>
            </thead>
        </table>
    </div>
    <div id='div_contenedor'>
        <ul id="lista_id_pagination" class="pagination lista_paginacion">
        </ul>
    </div>
</div>
<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">    

    var base_url = '<?php echo base_url();?>';
    var total_filas = 0;
    var filas_por_pagina = 20;
    var pagina_inicial = 1;
    var ruta_envio_baja;
    var venta_id_select;
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));
    var param_stand_url = datos_configuracion.param_stand_url;
            
    var data_empleados = {};
    var array_campos = [];
    array_campos.push('id');
    array_campos.push('nombres');
    array_campos.push('apellido_paterno');
    array_campos.push('apellido_materno');
    data_empleados['campos']  =   array_campos;    
        
    var url_empleados = base_url + 'index.php/WS_empleados/select';
    $('#empleado').prepend("<option value=''>Seleccionar</option>");
    $.getJSON(url_empleados, data_empleados)
    .done(function (ls_empleados) {
        $.each(ls_empleados, function(i, item) {
            $('#empleado').append($('<option>', {
                value: item.id,
                text: item.nombres
            }));        
        });
    });
        
    $(document).ready(function(){
        $('#fecha_emision_inicio').datepicker();
        $("#fecha_emision_final").datepicker();
        
        let venta_id = '';
        enviar_a_facturar = 0;
        $("#nuevo_comprobante").attr('href', base_url +'index.php/pedido_almacenes/nuevo');                
                
        $('#exportarExcel').click(function(){
            let entidad_id = ($("#entidad_id").val() =='' ) ? param_stand_url :  $("#entidad_id").val();
            let tipo_documento_id = ( $("#tipo_documento").val() == "") ?  param_stand_url : $("#tipo_documento").val();
            let serie = ( $("#serie").val() == "") ?  param_stand_url : $("#serie").val();
            let numero = ( $("#numero").val() == "") ? param_stand_url : $("#numero").val();
            let fecha_emision_inicio = ( $("#fecha_emision_inicio").val() == "") ?  param_stand_url : $("#fecha_emision_inicio").val();
            let fecha_emision_final = ( $("#fecha_emision_final").val() == "") ?  param_stand_url : $("#fecha_emision_final").val();
            let moneda = ( $("#moneda").val() == "") ?  param_stand_url : $("#moneda").val();
            let operacion_enviar = operacion;

            let url = '<?PHP echo base_url() ?>index.php/ventas/exportarExcel/' + entidad_id + '/' + tipo_documento_id + '/' + serie + '/' + numero + '/' +  fecha_emision_inicio + '/' + fecha_emision_final + '/' + moneda + '/' + operacion_enviar;
            window.open(url, '_blank');
        });
                
        //BUSCAR filtros
        $('#buscar_comprobante').on('click', function(){
            pagina = 1; //
            $("#tabla_id > tbody").remove();

            param_entidad_id = ( $('#entidad_id').val() == '' ) ? param_stand_url :  $('#entidad_id').val();
            param_tipo_documento = ( $('#tipo_documento').val() == '' ) ? param_stand_url :  $('#tipo_documento').val();
            param_serie = ( $('#serie').val() == '' ) ? param_stand_url :  $('#serie').val();
            param_numero = ( $('#numero').val() == '' ) ? param_stand_url :  $('#numero').val();
            param_fecha_emision_inicio = ( $('#fecha_emision_inicio').val() == '' ) ? param_stand_url :  $('#fecha_emision_inicio').val();
            param_fecha_emision_final = ( $('#fecha_emision_final').val() == '' ) ? param_stand_url :  $('#fecha_emision_final').val();
            param_moneda_id = ( $('#moneda').val() == '' ) ? param_stand_url :  $('#moneda').val();
            
            var rr = base_url + 'index.php/WS_ventas/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_serie + '/' + param_numero + '/' + param_fecha_emision_inicio + '/' + param_fecha_emision_final + '/' + param_moneda_id + '/' + operacion;
            $.getJSON(rr)
                .done(function (data) {
                    sortJSON(data.ws_select_ventas, 'venta_id', 'desc');
                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                     $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_ventas).forEach(function (repo) {
                        agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.venta_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda);
                        numero_orden ++;
                    });
            });
        });
        
        //PAGINACION
        $('#div_contenedor').on('click', '.pajaro', function(){
            $('li').removeClass('active');
            $(this).parent().addClass('active');
            
            param_entidad_id = ( $('#entidad_id').val() == '' ) ? param_stand_url :  $('#entidad_id').val();
            param_tipo_documento = ( $('#tipo_documento').val() == '' ) ? param_stand_url :  $('#tipo_documento').val();
            param_serie = ( $('#serie').val() == '' ) ? param_stand_url :  $('#serie').val();
            param_numero = ( $('#numero').val() == '' ) ? param_stand_url :  $('#numero').val();
            param_fecha_emision_inicio = ( $('#fecha_emision_inicio').val() == '' ) ? param_stand_url :  $('#fecha_emision_inicio').val();
            param_fecha_emision_final = ( $('#fecha_emision_final').val() == '' ) ? param_stand_url :  $('#fecha_emision_final').val();
            param_moneda_id = ( $('#moneda').val() == '' ) ? param_stand_url :  $('#moneda').val();
            
            pagina = $(this).text();
            $("#tabla_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_ventas/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_serie + '/' + param_numero + '/' + param_fecha_emision_inicio + '/' + param_fecha_emision_final + '/' + param_moneda_id + '/' + operacion;
            $.getJSON(url_l)
                .done(function (data) {
                    sortJSON(data.ws_select_ventas, 'venta_id', 'desc');

                    total_filas = data.total_filas; 
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_ventas).forEach(function (repo) {
                        agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.venta_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda);
                        numero_orden ++;
                    });
            });            
        });
        
        //Perfil - Detalle
        $("#tabla_id").on('click', '.btn_perfil_venta', function(){
            ruta_url = base_url + 'index.php/ventas/modal_detalle/';
            $("#myModal").load(ruta_url);
            
            var venta_id = $(this).attr('id');
            venta_id_select = venta_id;
            var monto_bolsa = $(this).data("bolsa");
            var ruta_url_cabecera = base_url + 'index.php/WS_ventas/ws_cabecera/' + venta_id;
            $.getJSON(ruta_url_cabecera)
                .done(function (data){                        
                    $('#detalle_venta_id').val(venta_id);

                    $('#detalle_entidad').html('<b>Cliente: </b>'+data.entidad);
                    $('#numero_documento').html('<b>'+data.abreviatura_tipo_entidad + '</b>: ' + data.numero_documento);
                    $('#direccion_entidad').html('<b>Dirección: </b>' + data.direccion_entidad);

                    $('#detalle_fecha_emision').html('<b>Fecha de emisión: </b>' + data.fecha_emision);
                    if(data.fecha_vencimiento != null)
                    $('#detalle_fecha_vencimiento').html('<b>Fecha de vencimiento: </b>' + data.fecha_vencimiento);
                    $('#detalle_moneda').html('<b>Moneda: </b>' + data.moneda);

                    $('#detalle_documento').html('<b>'+data.tipo_documento+' Electrónica');
                    $('#detalle_numeracion').html(data.serie+'-'+data.numero);
                    

                    var porcentaje_igv = Number(data.porcentaje_igv);
                    var ruta_url_detalle = base_url + 'index.php/WS_venta_detalles/ws_detalle/' + venta_id;
                    $.getJSON(ruta_url_detalle)
                        .done(function (data) {
                            (data).forEach(function (repo) {
                                let imagen_impuesto_bolsa = repo.impuesto_bolsa != null ? '<i class="glyphicon glyphicon-ok"></i>': '';

                                var fila = '<tr>';                                
                                fila += '<td>'+repo.cantidad+'</td>';
                                fila += '<td>'+repo.codigo_unidad+'</td>';
                                fila += '<td>'+repo.codigo_producto+'</td>';
                                fila += '<td>'+repo.producto+'</td>';
                                fila += '<td class="derecha_text">'+ (parseFloat(repo.precio_base)).toFixed(catidad_decimales - 2) +'</td>';
                                fila += '<td class="derecha_text">'+(repo.precio_base * (1 + porcentaje_igv)).toFixed(catidad_decimales - 2)+'</td>';
                                fila += '<td class="derecha_text">'+(repo.cantidad*(repo.precio_base * (1 + porcentaje_igv))).toFixed(catidad_decimales - 2)+'</td>';

                                if(monto_bolsa != null){
                                    fila += '<td class="derecha_text">'+imagen_impuesto_bolsa+'</td>';
                                }                                    

                                fila += '</tr>';
                                $("#tabla_detalle").append(fila); 
                        });

                    });
                });
        });
        
        //Modificar
        $('#tabla_id').on('click', '.btn_editar', function(){
            let pedido_almacen_id = $(this).attr('id');
            let url_l = base_url + 'index.php/pedido_almacenes/nuevo/#' + pedido_almacen_id;
            window.location.href = url_l;
        });               
    });        
    
    carga_inicial();
    //al cargar página    
    function carga_inicial(){
        //console.log('carga_inicial:'+operacion);
        var url_l = base_url + 'index.php/WS_pedido_almacenes/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
        //console.log('url_l:'+url_l);
        $.getJSON(url_l)
            .done(function (data) {                

                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga);

                var numero_orden = 1;
                (data.registros).forEach(function (repo) {
                    agregarFila(numero_orden, repo.pedido_almacen_id, repo.empleado, repo.numero_pedido, repo.fecha_insert, repo.fecha_aceptado)
                    numero_orden ++;
                });
        });
    }            
        
    var datos = [];
    var numero_documento_venta;
    var contador_fila = 1;
    function agregarFila(numero_orden, pedido_almacen_id, empleado, numero_pedido, fecha_insert, fecha_aceptado){
        var editar;
        var color = '';
        
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;
                        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a id="'+pedido_almacen_id+'" class="btn btn-default btn-xs btn_perfil_pedido_almacen" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+empleado+'</td>';
        fila += '<td>'+numero_pedido+'</td>';
        fila += '<td>'+fecha_insert+'</td>';
        fila += '<td>'+fecha_aceptado+'</td>';
        fila += '<td align="center"><a target="_blank" href="'+base_url+'index.php/ventas/pdf_a4/'+pedido_almacen_id+'"><img title="Ver Pdf A4" src="'+base_url+'images/pdf.png"></a></td>';
        fila += '<td align="center"><a id="'+pedido_almacen_id+'" class="btn btn-default btn-xs btn_editar" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';
        fila += '<td align="center"><a target="_blank" href="'+base_url+'index.php/ventas/pdf_a5/'+pedido_almacen_id+'"><img title="Ver Pdf A5" src="'+base_url+'images/pdf.png"></a></td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }        
    
    function construir_paginacion(total_filas, filas_por_pagina, carga){
        paginas = Math.trunc(total_filas / filas_por_pagina);
        paginas = (total_filas % filas_por_pagina > 0) ? (paginas + 1): paginas;
        var j = 1;
        for(i = 0; i < paginas; i++){
            var activer = ((i == 0) && (carga == 1)) ? 'active' : '';
            $('.lista_paginacion').append('<li class="page-item ' + activer + '"><a class="pajaro">'+(i+1)+'</a></li>');
        }
    }
 
</script>