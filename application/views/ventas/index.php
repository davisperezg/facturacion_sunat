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
        <div align="center"><span style="font-size: 23px" align="center" id="operacion"></span></div>        
        <div id="panel_fomulario">
            <div class="panel-heading" >
                <div class="panel-title"><span style="font-size: 23px" align="center" id="operacion_texto"></span></div>                        
            </div>
            <div class="panel-body">   
                <div class="row" >
                    <div class="col-xs-4">
                        <label>Cliente:</label><br>
                        <input type="text" class="form-control input-sm" id="entidad" name="entidad" placeholder="Cliente">
                        <input type="hidden"  name="entidad_id" id="entidad_id" >                        
                    </div>
                    <div id="div_tipo_doc" class="col-lg-2" >
                        <label>Tip.Doc</label><br>
                        <select class="form-control input-sm" name="tipo_documento" id="tipo_documento">
                        </select>
                    </div>
                    <div id="div_serie" class="col-xs-1" >
                        <label>Serie</label><br>
                        <input type="text" class="form-control input-sm" id="serie" name="serie" placeholder="serie">
                    </div>
                    <div class="col-lg-1" >
                        <label>Número</label><br>
                        <input type="text" class="form-control input-sm" id="numero" name="numero" placeholder="numero">
                    </div>                    
                    
                    <div class="col-lg-3 form-inline"  >
                        <label>Fec.Emision</label><br>
                        <input class="form-control input-sm" type="text" name="rec_in" id="rec_in" value="" placeholder="Desde">
                        <input class="form-control input-sm" type="text" name="tec_nal" id="tec_nal" value="" placeholder="Hasta">
                    </div>
                </div>                
                <div class="row" >                    
                    <div class="col-xs-12 col-lg-2 form-inline"  >
                        <label>Moneda:</label><br>
                        <select class="form-control input-sm" name="moneda" id="moneda">
                        </select>
                    </div>
                    
                    <div class="col-xs-12 col-lg-2 form-inline"  >
                        <label>Personal:</label><br>
                        <select class="form-control input-sm" name="empleado" id="empleado">
                        </select>
                    </div>
                                        
                    <div class="col-xs-6 col-lg-1" style="text-align: left;"  >
                        <label></label><br>
                        <a name="buscar_comprobante" id="buscar_comprobante" class="btn btn-primary">Buscar</a>
                    </div>
                    <div class="col-xs-6 col-lg-1" style="text-align: left;">
                        <label></label><br>
                        <a id="nuevo_comprobante" name="nuevo_comprobante" class="btn btn-success">Nuevo</a>                        
                    </div>
                    <div class="col-xs-6 col-lg-1" style="text-align: left;">
                        <label></label><br>                        
                        <a id="exportarExcel" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i>Excel</a>                        
                    </div>
                    <div class="col-xs-6 col-lg-1" style="text-align: left;">
                        <label></label><br>                        
                        <a id="vista_calendario" class="btn btn-primary"><i class="glyphicon glyphicon-calendar"></i>Calendario</a>
                    </div>
                    <div class="col-xs-6 col-lg-1" style="text-align: left;">
                        <label></label><br>                        
                        <a id="pedido_virtual" class="btn btn-info"><img src="<?php echo base_url()?>images/icons/qr-code.svg">&nbsp;Pedido Virtual</a>
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
                    <th>Cliente</th>
                    <th>T.Doc</th>
                    <th>Número</th>
                    <th>F.Emisión</th>
                    <th>M</th>
                    <th class="derecha_text">Total Grabado</th>
                    <th class="derecha_text">IGV</th>
                    <th class="derecha_text">Total</th>
                    <th class="centro_text">A4</th>
                    <th class="centro_text">A5</th>
                    <th class="centro_text">Ticket</th>
                    <th class="centro_text">T-58</th>
                    <th class="centro_text">Email</th>
                    <th class="centro_text">XML</th>
                    <th class="centro_text">CDR</th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                    <th class="centro_text"><span id="text_operacion"></span></th>
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

    var base_url            = '<?php echo base_url();?>';
    var total_filas         = 0;
    var filas_por_pagina    = 20;
    var pagina_inicial      = 1;
    var ruta_envio_baja;
    var venta_id_select;
    var super_total_grabado = 0;
    var super_total_igv     = 0;
    var super_total_a_pagar = 0;
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));
    var param_stand_url     = datos_configuracion.param_stand_url;
    var catidad_decimales   = datos_configuracion.catidad_decimales;
    
    let datos_empresa       = JSON.parse(localStorage.getItem("empresas"));
    var ruc_empresa         = datos_empresa.ruc;
    var entidad_id_pro      = '';
    
    //actualiza series por defecto (en el local storage) si se acaba de crear una serie en confguracion
    var url_serie = base_url + 'index.php/WS_series/series_defecto';
    $.getJSON(url_serie)
    .done(function (data) {
        localStorage.setItem("series_defecto", JSON.stringify(data.ws_select_series));
    });
    
    var ls_monedas = JSON.parse(localStorage.getItem("monedas"));
    $('#moneda').prepend("<option value=''>Seleccionar</option>");
    $.each(ls_monedas, function(i, item) {
        $('#moneda').append($('<option>', {
            value: item.id,
            text: item.moneda
        }));
    });

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
    
    var url_vd_select = base_url + 'index.php/WS_variables_diversas/select_all';
    $.getJSON(url_vd_select)
    .done(function (data) {
        if(data.precio_con_igv == 1){
            $("#precio_con_igv").prop('checked', true);
        }
    });
    
    var url_vd_automaticos = base_url + 'index.php/WS_variables_diversas/productos_automaticos';
    //console.log('url_vd_automaticos:' + url_vd_automaticos);
    $.getJSON(url_vd_automaticos)    
    .done(function (data) {
        if(data == 1){
            $("#productos_automaticos").prop('checked', true);
        }
    });
    
    //operacion
    //1 facturas o boletas
    //2 notas de venta
    //3 cotizaciones
    var operacion = window.location.href;
    operacion = operacion.substr(-1);

    var url_operacion = base_url + 'index.php/WS_variables_diversas/tipo_operaciones/' + operacion;
    $.getJSON(url_operacion)
        .done(function (data) {
            $('#operacion').text(data.toUpperCase());
            var texto_operacion = '';

            switch(operacion){
                case '1':
                    texto_operacion = ' - Comprobantes electrónicos.';
                    $("#text_operacion").text('Sunat');
                    $("#panel_fomulario").addClass( "panel panel-primary");
                break;

                case '2':
                    $("#div_tipo_doc").hide();
                    $("#div_serie").hide();
                    $("#text_operacion").text('Facturar');
                    $("#panel_fomulario").addClass( "panel panel-info");
                break;
                
                case '3':
                    $("#div_tipo_doc").hide();
                    $("#div_serie").hide();
                    $("#text_operacion").text('Facturar');
                    $("#panel_fomulario").addClass( "panel panel-success");
                break;
            }
            $('#operacion_texto').text(data.toUpperCase() + texto_operacion);
    });

    $(document).ready(function(){
        $('#rec_in').datepicker();
        $("#tec_nal").datepicker();
        
        let venta_id = '';
        enviar_a_facturar = 0;
        $("#nuevo_comprobante").attr('href', base_url +'index.php/ventas/nuevo/#' + venta_id + '/' + operacion + '/' + enviar_a_facturar);
        $("#vista_calendario").attr('href', base_url +'index.php/ventas/calendario/#' + venta_id + '/' + operacion + '/' + enviar_a_facturar);
        $("#pedido_virtual").attr('target', '_blank');
        $("#pedido_virtual").attr('href', base_url +'index.php/ventas_ss/pedido_virtual/');
        
        $('#precio_con_igv').change(function() {
            if(this.checked) {
                localStorage.setItem("precio_con_igv", 1);
                var url_variables = base_url + 'index.php/variables_diversas/operaciones/1';
                $.getJSON(url_variables)
                    .done(function (data) {
                });
            }else{
                localStorage.setItem("precio_con_igv", 0);
                var url_variables = base_url + 'index.php/variables_diversas/operaciones/0';
                $.getJSON(url_variables)
                    .done(function (data) {
                });
            }
        });
        
        $('#productos_automaticos').change(function() {
            if(this.checked) {
                localStorage.setItem("productos_automaticos", 1);
            }else{
                localStorage.setItem("productos_automaticos", 0);                
            }
        });

        $('#exportarExcel').click(function(){
            let entidad_id = ($("#entidad_id").val() =='' ) ? param_stand_url :  $("#entidad_id").val();
            let tipo_documento_id = ( $("#tipo_documento").val() == "") ?  param_stand_url : $("#tipo_documento").val();
            let serie = ( $("#serie").val() == "") ?  param_stand_url : $("#serie").val();
            let numero = ( $("#numero").val() == "") ? param_stand_url : $("#numero").val();
            let rec_in = ( $("#rec_in").val() == "") ?  param_stand_url : $("#rec_in").val();
            let tec_nal = ( $("#tec_nal").val() == "") ?  param_stand_url : $("#tec_nal").val();
            let moneda = ( $("#moneda").val() == "") ?  param_stand_url : $("#moneda").val();
            let operacion_enviar = operacion;

            let url = '<?PHP echo base_url() ?>index.php/ventas/exportarExcel/' + entidad_id + '/' + tipo_documento_id + '/' + serie + '/' + numero + '/' +  rec_in + '/' + tec_nal + '/' + moneda + '/' + operacion_enviar;
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
            param_rec_in = ( $('#rec_in').val() == '' ) ? param_stand_url :  $('#rec_in').val();
            param_tec_nal = ( $('#tec_nal').val() == '' ) ? param_stand_url :  $('#tec_nal').val();
            param_moneda_id = ( $('#moneda').val() == '' ) ? param_stand_url :  $('#moneda').val();
            
            var rr = base_url + 'index.php/WS_ventas/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_serie + '/' + param_numero + '/' + param_rec_in + '/' + param_tec_nal + '/' + param_moneda_id + '/' + operacion;
            $.getJSON(rr)
                .done(function (data) {
                    super_total_grabado = 0;
                    super_total_igv = 0;
                    super_total_a_pagar = 0;
                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                     $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_ventas).forEach(function (repo) {
                        agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.venta_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda, repo.ruta_xml, repo.ruta_cdr, repo.respuesta_sunat_descripcion);
                        numero_orden ++;
                    });
                    agrega_total();
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
            param_rec_in = ( $('#rec_in').val() == '' ) ? param_stand_url :  $('#rec_in').val();
            param_tec_nal = ( $('#tec_nal').val() == '' ) ? param_stand_url :  $('#tec_nal').val();
            param_moneda_id = ( $('#moneda').val() == '' ) ? param_stand_url :  $('#moneda').val();
            
            pagina = $(this).text();
            $("#tabla_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_ventas/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_serie + '/' + param_numero + '/' + param_rec_in + '/' + param_tec_nal + '/' + param_moneda_id + '/' + operacion;
            $.getJSON(url_l)
                .done(function (data) {
                    super_total_grabado = 0;
                    super_total_igv = 0;
                    super_total_a_pagar = 0;
                    
                    total_filas = data.total_filas; 
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_ventas).forEach(function (repo) {
                        agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.venta_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda, repo.ruta_xml, repo.ruta_cdr, repo.respuesta_sunat_descripcion);
                        numero_orden ++;
                    });
                    agrega_total();
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

                    //para la tabla de detalle, para q no se muestre la columna de bolsa
                    if(monto_bolsa != null){
                        $("#th_bolsa").removeClass('arranca_oculto');
                    }                        

                    if(data.total_gravada != null){
                        $("#div_total_gravada").removeClass('arranca_oculto');
                        $("#detalle_total_gravada").val(data.total_gravada);

                        $("#div_total_igv").removeClass('arranca_oculto');
                        $("#detalle_total_igv").val(data.total_igv);
                    }

                    if(data.total_inafecta != null){
                        $("#div_total_inafecta").removeClass('arranca_oculto');
                        $("#detalle_total_inafecta").val(data.total_inafecta);
                    }

                    if(data.total_exonerada != null){
                        $("#div_total_exonerada").removeClass('arranca_oculto');
                        $("#detalle_total_exonerada").val(data.total_exonerada);
                    }

                    if(data.total_gratuita != null){
                        $("#div_total_gratuita").removeClass('arranca_oculto');
                        $("#detalle_total_gratuita").val(data.total_gratuita);
                    }

                    if(data.total_exportacion != null){
                        $("#div_total_exportacion").removeClass('arranca_oculto');
                        $("#detalle_total_exportacion").val(data.total_exportacion);
                    }
                    
                    if(data.total_bolsa != null){
                        $("#div_total_bolsa").removeClass('arranca_oculto');
                        $("#detalle_total_bolsa").val(data.total_bolsa);
                    } 

                    $("#detalle_total_a_pagar").val(data.total_a_pagar);

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
        
        //Enviar sunat interno
        $('#tabla_id').on('click', '.API_SUNAT', function(){
            var venta_id = $(this).attr('id');
            let ruta = base_url + "index.php/ventas/enviar_sunat/" + venta_id;
            console.log('ruta:'+ruta);
            $.getJSON(ruta)
            .done(function(data){
                toast('success', 2500, data.message);
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Error al enviar: " + err);
            });
        });
        
        //Enviar API_SUNAT
        $('#tabla_id').on('click', '.API_SUNAT----', function(){            
            var venta_id = $(this).attr('id');
            let ruta = base_url + "index.php/WS_ventas/data_ws_monstruo/" + venta_id;
            $.getJSON(ruta)
            .done(function(data){
                var datosJSON = JSON.stringify(data);
                $.post(api_monstruo,{datosJSON})
                .done(function(res){
                    toast('success', 2500, 'Datos enviados correctamente.');
                })
                                
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Error al enviar: " + err);
            });
        });
        ////////////////////////////////////////////////////////

        //Enviar Mail        
        $('#tabla_id').on('click', '.btn-mail', function(){
            var venta_id = $(this).attr('id');
            var url_l = base_url + 'index.php/WS_correos/Send_Mail/' + venta_id;
            $.getJSON(url_l)
            .done(function (data) {
                toast('success', 2500, 'Mail Enviado.');               
            })
            .fail(function( jqxhr, textStatus, error ) {
                toast('alert', 2500, error); 
                var err = textStatus + ", " + error;
                console.log("Error verificar: " + err);
            });
        });
        
        //Baja solicitar ticket        
        $('#tabla_id').on('click', '.btn-baja', function(){
            var venta_id = $(this).attr('id');            
            var x = confirm("Confirmar, dar de BAJA el comprobante?");
            if (x){
                //creo XML y FIRMO
                ruta_xml = base_url + 'index.php/ventas/baja/' + venta_id;
                console.log('ruta_baja:'+ruta_xml);
                $.getJSON(ruta_xml)
                .done(function (data) {
                    //envio XML
                    ruta_envio_baja = base_url + "ws_sunat/index.php?numero_documento=" + data.numero_documento + "&cod_1=" + data.cod_1 + "&cod_2=" + data.cod_2 + "&cod_3=" + data.cod_3 + "&cod_4=" + data.cod_4 + "&cod_5=" + data.cod_5 + "&cod_6=" + data.cod_6 + "&cod_7=" + data.cod_7;
                    console.log("ruta_envio" + ruta_envio_baja);
                    $.getJSON(ruta_envio_baja)
                    .done(function(data_ticket){
                        console.log('aca debe llegar el ticket.');
                        console.log(data_ticket);
                        toast('success', 2500, 'Ticket N.:'+data_ticket.ticket[0]);
                        console.log(data_ticket.ticket[0]);
                        
                        $("#tabla_id > tbody").remove();
                        $("#lista_id_pagination > li").remove();
                        carga_inicial();
                        
                        if(data_ticket.ticket[0] != ''){
                            ruta_xml = base_url + 'index.php/anulaciones/guarda_ticket/' + venta_id + '/' + data_ticket.ticket[0];
                            console.log('ruta_baja:'+ruta_xml);
                            $.getJSON(ruta_xml)
                            .done(function (data) {
                            })
                        }
                    })
                    .fail(function( jqxhr, textStatus, error ) {
                        var err = jqxhr + ", " + textStatus + ", " + error;
                        console.log("Error verificar: " + err);
                    });
                })
                .fail(function( jqxhr, textStatus, error ) {
                    var err = jqxhr + ", " + textStatus + ", " + error;
                    console.log("Error verificar: " + err);
                });
            }
        });
                
        $('#tabla_id').on('click', '.btn-baja-resumen', function(){
            var venta_id = $(this).attr('id');
            let ruta = base_url + "index.php/ventas/baja_resumen/" + venta_id + "/" + ruc_empresa;
            console.log(ruta);
            $.getJSON(ruta)
            .done(function(data){
                toast('success', 2500, data.message);
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Error al enviar: " + err);
            });
        });
                
        //Baja envio ticket
        //enviar a Sunat       
        //cod_1: Select web Service: 1 factura, boletas --- 9 es para guias
        //cod_2: Entorno:  0 Beta, 1 Produccion
        //cod_3: ruc
        //cod_4: usuario secundario USU(segun seha beta o producción)
        //cod_5: usuario secundario PASSWORD(segun seha beta o producción)
        //cod_6: Accion:   1 enviar documento a Sunat --  2 enviar a anular  --  3 enviar ticket
        //cod_7: serie de documento
        //cod_8: numero ticket
        
        $('#tabla_id').on('click', '.btn-baja_enviar_ticket', function(){
            var venta_id = $(this).attr('id');            
            
            ruta_param = base_url + 'index.php/ventas/baja_enviar_ticket/' + venta_id;
            console.log('ruta_param:'+ruta_param);                        
            
            $.getJSON(ruta_param)
            .done(function (data) {                               
                
                console.log('respuesta ticket.');
                console.log(data);
                toast('success', 2500, 'Respuesta del ticket:'+data.resultado);
                console.log(data.resultado);
                
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
        });
                                                
        //Baja        
        $('#tabla_id').on('click', '.btn-baja--', function(){
            var venta_id = $(this).attr('id');            
            var x = confirm("Confirmar, dar de BAJA el comprobante?");
            if (x){ 
                ruta_baja = base_url + 'index.php/ventas/baja/' + venta_id;
                console.log(ruta_baja);
                $.getJSON(ruta_baja)
                .done(function (json) {                    
                    var url_envio =  json.param_url;
                    $.getJSON(url_envio)
                    .done(function(data){
                        if(data.error_existe == 1){
                            toast('alert', 4500, data.error_mensaje);
                        }else{
                            var url_estado = base_url + "index.php/WS_ventas/updateEstadoAnulacion/" + venta_id;
                            console.log(url_estado);
                            $.getJSON(url_estado)
                            .done(function(){
                                toast('success', 2500, data.message);
                                $("#tabla_id > tbody").remove();
                                $("#lista_id_pagination > li").remove();
                                carga_inicial();
                            })
                            .fail(function( jqxhr, textStatus, error ) {
                                var err = textStatus + ", " + error;
                                console.log("Nivel 4: Error al enviar: " + err);
                            });
                        }
                    })
                    .fail(function( jqxhr, textStatus, error ) {
                        var err = textStatus + ", " + error;
                        console.log("Nivel 3: Error al enviar: " + err);
                    });               
                })
                .fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ", " + error;
                    console.log("Error verificar: " + err);
                });
            }
        });
        
        //Modificar
        $('#tabla_id').on('click', '.btn-editar', function(){
            let venta_id = $(this).attr('id');
            let enviar_a_facturar = 0; //para enviar a facturar o boletear Notas de Pedido o Cotizaciones
            let url_l = base_url + 'index.php/ventas/nuevo/#' + venta_id + '/' + operacion + '/' + enviar_a_facturar;
            window.location.href = url_l;
        });
        
        //Enviar a facturar o boletear Notas de Pedido o Cotizaciones
        $('#tabla_id').on('click', '.btn-envio-facturar', function(){
            let venta_id = $(this).attr('id');
            let enviar_a_facturar = 1; //para enviar a facturar o boletear Notas de Pedido o Cotizaciones
            let url_l = base_url + 'index.php/ventas/nuevo/#' + venta_id + '/' + operacion + '/' + enviar_a_facturar;
            window.location.href = url_l;
        });
        
        $('#tabla_id').on('click', '.get_status_cdr', function(){
            let ruta = base_url + "index.php/ventas/get_status_cdr/" + $(this).attr('id');
            console.log(ruta);
            $.getJSON(ruta)
            .done(function(data){
                toast('success', 2500, data.message);
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Error al enviar: " + err);
            });
        });
        
        //modal para modificar datos de cliente - CORREO....
        $("#tabla_id").on('click', '.btn_ubicacion', function(){
            entidad_id_pro = $(this).attr('id');
            ruta_url = base_url + 'index.php/entidades/modal_ubicacion/';
            $("#myModal").load(ruta_url);
        });
        
        $("#tabla_id").on('click', '.btn_descargar_xml', function(){
            let url = base_url + "files/facturacion_electronica/FIRMA/20604051984-03-B001-26.xml";
            window.open(url);
        });
        
        $("#tabla_id").on('click', '.btn_descargar_cdr', function(){
            let url = base_url + "files/facturacion_electronica/FIRMA/R-20604051984-03-B001-26.zip";
            window.open(url);
        });
    });
    
    $('#entidad').autocomplete({
        source: base_url + 'index.php/WS_ventas/buscador_entidad',
        minLength: 2,
        select: function (event, ui) {
            $('#entidad_id').val(ui.item.id);
        }
    });
    
    carga_inicial();
    //al cargar página    
    function carga_inicial(){
        //console.log('carga_inicial:'+operacion);
        var url_l = base_url + 'index.php/WS_ventas/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' +operacion;
        $.getJSON(url_l)
            .done(function (data) {
                super_total_grabado = 0;
                super_total_igv = 0;

                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga);

                var numero_orden = 1;
                (data.ws_select_ventas).forEach(function (repo) {
                    agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.venta_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda, repo.ruta_xml, repo.ruta_cdr, repo.respuesta_sunat_descripcion);
                    numero_orden ++;
                });
                agrega_total();
        });
    }
        
    $.getJSON(base_url + 'index.php/WS_tipo_documentos/tipo_documentos')
            .done(function (data) {
                sortJSON(data.tipo_documentos, 'id', 'desc');
                $('#tipo_documento').prepend("<option value=''>Seleccionar</option>");
                (data.tipo_documentos).forEach(function (repo) {
                    var selectedado = (repo.id == 0) ? 'selected' : '';
                    $('#tipo_documento').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.tipo_documento + "</option>");
            });
    });
        
    var datos = [];
    var numero_documento_venta;
    var contador_fila = 1;
    var param_cdr;
    function agregarFila(numero_orden, entidad, tipo_documento, serie, numero, fecha_emision, total_gravada, total_igv, total_a_pagar, venta_id, total_bolsa, estado_operacion, estado_anulacion, tipo_documento_codigo, entidad_id, operacion, simbolo_moneda, ruta_xml, ruta_cdr, respuesta_sunat_descripcion){
        var mail;
        var editar;
        var enviar_sunat;
        var color = '';
        var numeracion;
        var xml = '';
        var cdr = '';
        
        if(total_gravada == null){ total_gravada = 0}
        if(total_igv == null){ total_igv = 0}
        if(total_a_pagar == null){ total_a_pagar = 0}
        
        super_total_grabado += parseFloat(total_gravada);
        super_total_igv += parseFloat(total_igv);
        super_total_a_pagar += parseFloat(total_a_pagar);
        
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;
        
        numero_documento_venta = ruc_empresa + '-' + tipo_documento_codigo + '-' + serie + '-' + numero;
        param_cdr = ruc_empresa + '/' + tipo_documento_codigo + '/' + serie + '/' + numero + '/' + venta_id;
        switch (estado_operacion) {            
            case 0://no enviado
                xml = (ruta_xml != '') ? '<a class="btn btn-default btn-xs" download="'+ numero_documento_venta + '.xml" href="'+ruta_xml+'">XML</a>' : '';
                cdr = (ruta_xml != '') ? '<a id="'+param_cdr+'" class="btn btn-warning btn-xs get_status_cdr">CDR</a>' : '';
                mail = '-';
                editar = '<button class="btn btn-default btn-xs btn-editar" id='+venta_id+'><i class="glyphicon glyphicon-pencil"></i></button>';
                
                switch (operacion) {                    
                    case '1'://Facturas o boletas
                        enviar_sunat = '<button id="'+venta_id+'/'+numero_documento_venta+'" title="Enviar a Sunat" class="btn btn-default btn-xs API_SUNAT"><img src=' + base_url + 'images/logo_sunat.jpg data-id="40" class="descargar-pdf" width="25px"></button>';
                        break;
                        
                    case '2'://cotizaciones
                        enviar_sunat = '<button class="btn btn-default btn-xs btn-envio-facturar" id='+venta_id+'><i class="glyphicon glyphicon-repeat"></i></button>';
                        break;
                        
                    case '3'://cotizaciones
                        enviar_sunat = '<button class="btn btn-default btn-xs btn-envio-facturar" id='+venta_id+'><i class="glyphicon glyphicon-repeat"></i></button>';
                        break;
                }
                break;
                
            case 1://venta aceptado                
                xml = (ruta_xml != '') ? '<a class="btn btn-default btn-xs" download="' + numero_documento_venta + '.xml" href="'+ruta_xml+'">XML</a>' : '';
                cdr = (ruta_cdr != '') ? '<a class="btn btn-default btn-xs" download="R-' + numero_documento_venta + '.zip" href="'+ruta_cdr+'">CDR</a>' : '';
                
                if(estado_anulacion == null){
                    mail = '<button class="btn btn-default btn-xs btn-mail" id='+venta_id+'><i class="glyphicon glyphicon-envelope"></i></button>';
                    editar = 'Enviado Sunat';
                    if((tipo_documento_codigo == '01') || (tipo_documento_codigo == '07') || (tipo_documento_codigo == '08')){
                        enviar_sunat = '<button title="Anular comprobante" class="btn btn-default btn-xs btn-baja" id='+venta_id+'><i class="glyphicon glyphicon-remove"></i></button>';
                    }else if(tipo_documento_codigo == '03'){
                        enviar_sunat = '<button title="Anular comprobante" class="btn btn-default btn-xs btn-baja-resumen" id='+venta_id+'><i class="glyphicon glyphicon-remove"></i></button>';
                    }                    
                }
                if(estado_anulacion == 0){
                    mail = '<button class="btn btn-default btn-xs btn-mail" id='+venta_id+'><i class="glyphicon glyphicon-envelope"></i></button>';
                    editar = 'Enviado Sunat';
                    enviar_sunat = '<button title="Respuesta de Anulación" class="btn btn-default btn-xs btn-baja_enviar_ticket" id='+venta_id+'><i class="glyphicon glyphicon-registration-mark"></i></button>';
                }
                if(estado_anulacion == 1){
                    color = "style='background-color: #FADBD8'";
                    
                    mail = '<button class="btn btn-default btn-xs btn-mail" id='+venta_id+'><i class="glyphicon glyphicon-envelope"></i></button>';
                    editar = 'Anulación aceptada';
                    enviar_sunat = '-';
                }
                break;
            
            case 2://venta rechazado
                xml = (ruta_xml != '') ? '<a class="btn btn-default btn-xs" download="' + numero_documento_venta + '.xml" href="' + ruta_xml + '">XML</a>' : '';
                cdr = (ruta_cdr != '') ? '<a class="btn btn-default btn-xs" download="R-' + numero_documento_venta + '.zip" href="' + ruta_cdr + '">CDR</a>' : '';
                color = "style='background-color: #FADBD8'";
                
                mail = '---';
                editar = '---';
                enviar_sunat = '<button title="' + respuesta_sunat_descripcion + '" class="btn btn-default btn-xs btn-baja_enviar_ticket" id=' + venta_id + '><i class="glyphicon glyphicon-info-sign"></i></button>';
                break;
        }
        
        tipo_documento = (operacion == 1) ? tipo_documento : '';
        numeracion = (operacion == 1) ? serie+'-'+numero : numero;
        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a data-bolsa="'+total_bolsa+'" id="'+venta_id+'" class="btn btn-default btn-xs btn_perfil_venta" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_ubicacion" data-toggle="modal" data-target="#myModal">'+entidad+'</a></td>';
        fila += '<td>' + tipo_documento + '</td>';
        fila += '<td>' + numeracion + '</td>';
        fila += '<td>' + fecha_emision + '</td>';
        fila += '<td>' + simbolo_moneda + '</td>';
        fila += '<td class="derecha_text">' + total_gravada + '</td>';
        fila += '<td class="derecha_text">' + total_igv + '</td>';
        fila += '<td class="derecha_text">' + total_a_pagar + '</td>';
        fila += '<td align="center"><a target="_blank" href="' + base_url + 'index.php/ventas/pdf_a4/' + venta_id + '"><img title="Ver Pdf A4" src="' + base_url + 'images/pdf.png"></a></td>';
        fila += '<td align="center"><a target="_blank" href="' + base_url + 'index.php/ventas/pdf_a5/' + venta_id + '"><img title="Ver Pdf A5" src="' + base_url + 'images/pdf.png"></a></td>';
        fila += '<td align="center"><a target="_blank" href="' + base_url + 'index.php/ventas/pdf_ticket/' + venta_id + '"><img width="30px" title="Ver Pdf" src="' + base_url + 'images/ticket.png"></a></td>';
        //fila += '<td align="center"><a target="_blank" href="' + base_url + 'index.php/ventas/fpdf_ticket/' + venta_id + '"><img width="30px" title="Ver Pdf" src="' + base_url + 'images/ticket.png"></a></td>';
        fila += '<td align="center"><a target="_blank" href="' + base_url + 'index.php/ventas/pdf_58/' + venta_id + '">T-58</a></td>';
        fila += '<td align=center>' + mail + '</td>';
        fila += '<td align=center>' + xml + '</td>';
        fila += '<td align=center>' + cdr + '</td>';
        fila += '<td align=center>' + editar + '</td>';
        fila += '<td align=center>' + enviar_sunat + '</td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }
    
    function agrega_total(){
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;        
        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';        
        fila += '<td align="right" colspan="6"><b>Totales:</b></td>';        
        fila += '<td class="derecha_text">'+super_total_grabado.toFixed(catidad_decimales - 2)+'</td>';
        fila += '<td class="derecha_text">'+super_total_igv.toFixed(catidad_decimales - 2)+'</td>';
        fila += '<td class="derecha_text">'+(super_total_a_pagar).toFixed(catidad_decimales - 2)+'</td>';
        fila += '<td colspan="3"></td>';        
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