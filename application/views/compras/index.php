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
                        <input class="form-control input-sm" type="text" name="fecha_emision_inicio" id="fecha_emision_inicio" value="" placeholder="Desde">
                        <input class="form-control input-sm" type="text" name="fecha_emision_final" id="fecha_emision_final" value="" placeholder="Hasta">
                    </div>
                    
                    <div class="col-lg-2 form-inline"  >
                        <label>Moneda:</label><br>
                        <select class="form-control input-sm" name="moneda" id="moneda">
                        </select>
                    </div>
                    <div class="col-lg-2 form-inline"  >
                        <label>Precio con IGV:</label><br>
                        <input type="checkbox" id="precio_con_igv" name="precio_con_igv">
                    </div>
                    <div class="col-lg-1" style="text-align: left;"  >
                        <label></label><br>
                        <a name="buscar_comprobante" id="buscar_comprobante" class="btn btn-primary">Buscar</a>
                    </div>
                    <div class="col-lg-2" style="text-align: right;">
                        <label></label><br>                                                
                        <a name="nuevo_comprobante" id="nuevo_comprobante" class="btn btn-success">Nuevo</a>
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
                    <th>Cliente</th>
                    <th>T.Doc</th>
                    <th>Número</th>
                    <th>F.Emisión</th>
                    <th>M</th>
                    <th class="derecha_text">Total Grabado</th>
                    <th class="derecha_text">IGV</th>
                    <th class="derecha_text">Total</th>
                    <th class="centro_text">A4</th>
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

    var base_url = '<?php echo base_url();?>';
    var total_filas = 0;
    var filas_por_pagina = 20;
    var pagina_inicial = 1;
    var ruta_envio_baja;
    var compra_id_select;
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var catidad_decimales = datos_configuracion.catidad_decimales;
    
    let datos_empresa = JSON.parse(localStorage.getItem("empresas")); 
    var ruc_empresa = datos_empresa.ruc;        
    
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
    
    var url_vd_select = base_url + 'index.php/WS_variables_diversas/select_all';
    $.getJSON(url_vd_select)
    .done(function (data) {
        if(data.precio_con_igv == 1){
            $("#precio_con_igv").prop('checked', true);
        }
    });
    
    //operacion
    //1 facturas o boletas
    //2 orden de compra
    var operacion = window.location.href;
    operacion = operacion.substr(-1);

    var url_operacion = base_url + 'index.php/WS_variables_diversas/tipo_operaciones_compras/'+operacion;
    $.getJSON(url_operacion)
        .done(function (data) {
            $('#operacion').text(data.toUpperCase());
            var texto_operacion = '';

            switch(operacion){
                case '1':
                    texto_operacion = ' - Comprobantes electrónicos.';
                    $("#text_operacion").text('Sunat');
                    $("#panel_fomulario").addClass( "panel panel-danger");
                break;

                case '2':
                    $("#div_tipo_doc").hide();
                    $("#div_serie").hide();
                    $("#text_operacion").text('Facturar');
                    $("#panel_fomulario").addClass( "panel panel-warning");
                break;                
            }
            $('#operacion_texto').text(data.toUpperCase() + texto_operacion);
    });

    $(document).ready(function(){
        $('#fecha_emision_inicio').datepicker();
        $("#fecha_emision_final").datepicker();
        
        let compra_id = '';
        enviar_a_facturar = 0;
        $("#nuevo_comprobante").attr('href', base_url +'index.php/compras/nuevo/#' + compra_id + '/' + operacion + '/' + enviar_a_facturar);
        
        $('#precio_con_igv').change(function() {
            if(this.checked) {
                console.log('hola');
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
                
        $('#exportarExcel').click(function(){
            let entidad_id = ($("#entidad_id").val() =='' ) ? param_stand_url :  $("#entidad_id").val();
            let tipo_documento_id = ( $("#tipo_documento").val() == "") ?  param_stand_url : $("#tipo_documento").val();
            let serie = ( $("#serie").val() == "") ?  param_stand_url : $("#serie").val();
            let numero = ( $("#numero").val() == "") ? param_stand_url : $("#numero").val();
            let fecha_emision_inicio = ( $("#fecha_emision_inicio").val() == "") ?  param_stand_url : $("#fecha_emision_inicio").val();
            let fecha_emision_final = ( $("#fecha_emision_final").val() == "") ?  param_stand_url : $("#fecha_emision_final").val();
            let moneda = ( $("#moneda").val() == "") ?  param_stand_url : $("#moneda").val();
            let operacion_enviar = operacion;

            let url = '<?PHP echo base_url() ?>index.php/compras/exportarExcel/' + entidad_id + '/' + tipo_documento_id + '/' + serie + '/' + numero + '/' +  fecha_emision_inicio + '/' + fecha_emision_final + '/' + moneda + '/' + operacion_enviar;
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
            
            var rr = base_url + 'index.php/WS_compras/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_serie + '/' + param_numero + '/' + param_fecha_emision_inicio + '/' + param_fecha_emision_final + '/' + param_moneda_id + '/' + operacion;
            $.getJSON(rr)
                .done(function (data) {
                    sortJSON(data.ws_select_compras, 'compra_id', 'desc');
                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                     $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_compras).forEach(function (repo) {
                        agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.compra_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda);
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

            var url_l = base_url + 'index.php/WS_compras/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_serie + '/' + param_numero + '/' + param_fecha_emision_inicio + '/' + param_fecha_emision_final + '/' + param_moneda_id + '/' + operacion;
            $.getJSON(url_l)
                .done(function (data) {
                    sortJSON(data.ws_select_compras, 'compra_id', 'desc');

                    total_filas = data.total_filas; 
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_compras).forEach(function (repo) {
                        agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.compra_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda);
                        numero_orden ++;
                    });
            });            
        });
        
        //Perfil - Detalle
        $("#tabla_id").on('click', '.btn_perfil_compra', function(){
            ruta_url = base_url + 'index.php/compras/modal_detalle/';
            $("#myModal").load(ruta_url);
            
            var compra_id = $(this).attr('id');
            compra_id_select = compra_id;
            var monto_bolsa = $(this).data("bolsa");
            var ruta_url_cabecera = base_url + 'index.php/WS_compras/ws_cabecera/' + compra_id;
            $.getJSON(ruta_url_cabecera)
                .done(function (data){                        
                    $('#detalle_compra_id').val(compra_id);

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
                    var ruta_url_detalle = base_url + 'index.php/WS_compra_detalles/ws_detalle/' + compra_id;
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
        
        //enviar sunat        
        $('#tabla_id').on('click', '.enviar_sunat', function(){
            var compra_id = $(this).attr('id');
            var numero_documento = $(this).attr("data-numero");

            var url_l = base_url + 'index.php/compras/enviar_sunat/' + numero_documento;
            console.log(url_l);
            //var url_l = base_url + 'index.php/compras/crearXML/' + compra_id;
            $.getJSON(url_l)
            .done(function (data) {
                toast('success', 2500, 'XML creado correctamente');
                $("#entidad").val("nepelito.......");                
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Error verificar: " + err);
            });
        });        
        
        //generar XML//HOST
        //////////////////////////////////////////////////////////
        $('#tabla_id').on('click', '.generar_xml---', function(){
            var compra_id = $(this).attr('id');
            $.getJSON(base_url + "index.php/compras/getDatosXML/" + compra_id)            
            .done(function(json){
                var datosJSON = JSON.stringify(json);
                var url = ruta_api + 'ws_sunat/index.php';                
                $.post(url,{ datosJSON })
                .done(function(json){
                    var param = JSON.parse(json);
                    var url_envio = ruta_api + param.param_url;
                    console.log('url_envio:'+url_envio);
                    $.getJSON(url_envio)
                    .done(function(data){
                        if(data.error_existe == 1){
                            toast('alert', 4500, data.error_mensaje);
                        }else{
                            var url_estado = base_url + "index.php/WS_compras/updateEstadoOperacion/" + compra_id;
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
                    console.log("Nivel 2: Error en ws al crear y/o firmar: " + err);
                });
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Nivel 1: Error en local, envio de datos POST: " + err);
            });
                        
        });
        ////////////////////////////////////////////////////////
        
        //generar XML//HOST LOCAL
        //////////////////////////////////////////////////////////
        $('#tabla_id').on('click', '.generar_xml', function(){
            var compra_id = $(this).attr('id');
            let ruta = base_url + "index.php/compras/crearXML/" + compra_id;
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
        ////////////////////////////////////////////////////////
                                                        
        //Modificar
        $('#tabla_id').on('click', '.btn-editar', function(){
            let compra_id = $(this).attr('id');
            let enviar_a_facturar = 0; //para enviar a facturar o boletear Notas de Pedido o Cotizaciones
            let url_l = base_url + 'index.php/compras/nuevo/#' + compra_id + '/' + operacion + '/' + enviar_a_facturar;
            window.location.href = url_l;
        });
        
        //Enviar a facturar o boletear Orden de Compra
        $('#tabla_id').on('click', '.btn-envio-facturar', function(){
            let compra_id = $(this).attr('id');
            let enviar_a_facturar = 1; //para enviar a facturar o boletear Notas de Pedido o Cotizaciones
            let url_l = base_url + 'index.php/compras/nuevo/#' + compra_id + '/' + operacion + '/' + enviar_a_facturar;
            window.location.href = url_l;
        });
                
        //modal para modificar datos de cliente - CORREO....
        $("#tabla_id").on('click', '.btn_ubicacion', function(){
            var entidad_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/entidades/modal_ubicacion/';
            $("#myModal").load(ruta_url);                                    

            ruta_url_item = base_url + 'index.php/WS_entidades/select_item/' + entidad_id;
            $.getJSON(ruta_url_item)
                    .done(function (data){
                        $('#modal_ubicacion_entidad').text(data[0].entidad + ' - ' + data[0].numero_documento);
                        $('#modal_ubicacion_entidad_id').val(data[0].entidad_id);
                        $('#modal_email_1').val(data[0].email_1);
                        $('#modal_email_2').val(data[0].email_2);
                        $('#modal_telefono_fijo_1').val(data[0].telefono_fijo_1);
                        $('#modal_telefono_fijo_2').val(data[0].telefono_fijo_2);
                        $('#modal_telefono_movil_1').val(data[0].telefono_movil_1);
                        $('#modal_telefono_movil_2').val(data[0].telefono_movil_2);
                        $('#modal_pagina_web').val(data[0].pagina_web);
                        $('#modal_facebook').val(data[0].facebook);
                        $('#modal_twitter').val(data[0].twitter);
                    })                        
        });        
    });
    
    $('#entidad').autocomplete({
        source: base_url + 'index.php/WS_compras/buscador_entidad',
        minLength: 2,
        select: function (event, ui) {
            $('#entidad_id').val(ui.item.id);
        }
    });
    
    carga_inicial();
    //al cargar página    
    function carga_inicial(){
        //console.log('carga_inicial:'+operacion);
        var url_l = base_url + 'index.php/WS_compras/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' +operacion;
        console.log(url_l);
        $.getJSON(url_l)
            .done(function (data) {                
                sortJSON(data.ws_select_compras, 'compra_id', 'desc');

                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga);

                var numero_orden = 1;
                (data.ws_select_compras).forEach(function (repo) {
                    agregarFila(numero_orden, repo.entidad, repo.abreviado, repo.serie, repo.numero, repo.fecha_emision, repo.total_gravada, repo.total_igv, repo.total_a_pagar, repo.compra_id, repo.total_bolsa, repo.estado_operacion, repo.estado_anulacion, repo.tipo_documento_codigo, repo.entidad_id, operacion, repo.simbolo_moneda);
                    numero_orden ++;
                });
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
        
    function agregarFila(numero_orden, entidad, tipo_documento, serie, numero, fecha_emision, total_gravada, total_igv, total_a_pagar, compra_id, total_bolsa, estado_operacion, estado_anulacion, tipo_documento_codigo, entidad_id, operacion, simbolo_moneda){
        var numeracion;        
        
        tipo_documento = (operacion == 1) ? tipo_documento : '';
        numeracion = (operacion == 1) ? serie+'-'+numero : numero;
        
        switch (operacion) {                    
            case '1'://Facturas o boletas
                enviar_facturar = '';
                break;

            case '2'://Orden de compra
                enviar_facturar = '<button class="btn btn-default btn-xs btn-envio-facturar" id='+compra_id+'><i class="glyphicon glyphicon-repeat"></i></button>';
                break;
        }
        
        var fila = '<tr class="seleccionado tabla_fila">';        
        fila += '<td align="center"><a data-bolsa="'+total_bolsa+'" id="'+compra_id+'" class="btn btn-default btn-xs btn_perfil_compra" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_ubicacion" data-toggle="modal" data-target="#myModal">'+entidad+'</a></td>';
        fila += '<td>'+tipo_documento+'</td>';
        fila += '<td>'+numeracion+'</td>';
        fila += '<td>'+fecha_emision+'</td>';
        fila += '<td>'+simbolo_moneda+'</td>';
        fila += '<td class="derecha_text">'+total_gravada+'</td>';
        fila += '<td class="derecha_text">'+total_igv+'</td>';
        fila += '<td class="derecha_text">'+total_a_pagar+'</td>';
        fila += '<td align="center"><a target="_blank" href="'+base_url+'index.php/compras/pdf_a4/'+compra_id+'"><img title="Ver Pdf" src="'+base_url+'images/pdf.png"></a></td>';
        fila += '<td align=center><button class="btn btn-default btn-xs btn-editar" id='+compra_id+'><i class="glyphicon glyphicon-pencil"></i></button></td>';
        fila += '<td align=center>'+enviar_facturar+'</td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);
    }
    
    function doesFileExist(urlToFile) {
        var xhr = new XMLHttpRequest();
        xhr.open('HEAD', urlToFile, false);
        xhr.send();

        if (xhr.status == "404") {
            return false;
        } else {
            return true;
        }
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