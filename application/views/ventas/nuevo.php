<style type="text/css">
    /* Agregando Inputs */
    .input-group {width: 100%;}
    .input-group-addon { min-width: 180px;text-align: right;}    
    
    .panel-title{
        font-size: 13px;
        font-weight: bold;
    }
    
    .derecha_text { 
        text-align: right; 
    }
</style>
<form id="formComprobante" class="form-horizontal" autocomplete="off">
    <div class="row">
        <div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>
        <div class="col-md-8" style="text-align: center"><h3 id="operacion_texto"></h3></div>
        <div class="col-md-2"></div>
    </div>
    
    <div class="row">        
        <div class="col-md-12">
            <div id="panel_fomulario">
                <div class="panel-heading" >
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:5px;">
                        <div class="row">
                            <div id="div_tipo_de_operacion" class="col-xs-12 col-lg-1">
                                <label>Operación</label>                            
                                <select class="form-control" name="tipo_operacion" id="tipo_operacion">
                                </select>
                            </div>
                            
                            <div class="col-xs-12 col-lg-4">
                                <label class="control-label" style="width: 100%;text-align: left;">Cliente:</label>
                                <input type="text" class="form-control input-sm" id="entidad" value="CLIENTE VARIOS" name="entidad" placeholder="Cliente" style="width: 90%;">
                                <input type="hidden"  name="entidad_id" id="entidad_id" value="1" >
                                <input type="hidden"  name="tipo_entidad_id" id="tipo_entidad_id" value="1" >
                            </div>

                            <div class="col-xs-12 col-lg-1">
                                <div style="padding-top: 20px">
                                    <button type="button" id="datos_entidad_ws_externa" class="btn btn-primary btn-sm">SUNAT</button>
                                    <button type="button" id="crear_nueva_entidad" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo</button>
                                </div>                            
                            </div>

                            <div class="col-xs-12 col-lg-5">
                                <label class="control-label">Dirección:</label>
                                <input type="text" class="form-control" name="direccion" id="direccion" value="---">                                
                            </div>                            
                        </div>                                                                       

                        <div id="div_tipo_documento" class="col-md-2 col-lg-2">
                            <label class="control-label">Tipo Documento:</label>        
                            <select  class="form-control" name="tipo_documento" id="tipo_documento">
                            </select>    
                        </div>    

                        <div id="div_serie" class="col-md-1 col-lg-1">            
                            <label class="control-label">Serie:</label>
                            <!--<input style="text-transform:uppercase" type="text" class="form-control" name="serie" id="serie" placeholder="F001" maxlength="4" pattern='^[fF]{1}[fF|\d]{1}(\d){2}' title="Serie FF.. ó F...">-->
                            <div id="div_serie_actual">
                                <select class="form-control" name="series" id="series"> 
                                </select>
                            </div>
                        </div>

                        <div id="div_numero" class="col-md-1 col-lg-1">
                            <label class="control-label">Numero:</label>
                            <input type="text" readonly="" class="form-control" name="numero" id="numero" maxlength="9" required=""  >
                        </div>                                                

                        <div class="col-md-1 col-lg-1">
                            <label class=" control-label">F. emisión:</label>
                            <input type="text" class="form-control" name="fecE" id="fecE" placeholder="Emision">
                        </div>

                        <div class="col-md-1 col-lg-1">
                            <label class="control-label">F. de Venc:</label>
                            <input type="text" class="form-control" name="fecV" id="fecV" placeholder="Vencimiento" />
                        </div>

                        <div class="col-md-1 col-lg-1">
                            <label class="control-label">Moneda:</label>        
                            <select class="form-control" name="moneda" id="moneda">  
                            </select>
                        </div>       

                        <div class="col-md-1 col-lg-1">
                            <label class="control-label">T. Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" disabled="">
                        </div>

                        <!-- orden de compra -->                         
                        <div id="div_orden_compra" class="col-md-1 col-lg-1">
                            <label class="control-label">Orden Com</label>
                            <input type="text" class="form-control" name="orden_compra" id="orden_compra">
                        </div>
                       
<!--                         Guia 
                        <div id="div_guia" class="col-md-1 col-lg-1">
                            <label class="control-label">Guias</label>
                            <select class="form-control" name="sel_guias" id="sel_guias"> 
                            </select>
                        </div>-->
                    </div>  
                    <div class="row" id="campos_accesorios">
                        <div class="col-xs-3" id="div_numero_guia">
                            <label class="control-label">Número de Guia</label>
                            <input type="text" class="form-control" name="numero_guia" id="numero_guia">
                        </div>
                        <div class="col-xs-3" id="div_condicion_venta">
                            <label class="control-label">Condición de venta</label>
                            <input type="text" class="form-control" name="condicion_venta" id="condicion_venta">
                        </div>
                        <div class="col-xs-3" id="div_nota_de_venta">
                            <label class="control-label">Nota de venta</label>
                            <input type="text" class="form-control" name="nota_venta" id="nota_venta">
                        </div>
                        <div class="col-xs-3" id="div_numero_pedido">
                            <label class="control-label">Número de Pedido</label>
                            <input type="text" class="form-control" name="numero_pedido" id="numero_pedido">
                        </div>
                    </div>
                                        
                    <div class="row" id="div_documento">
                        <div  class="col-md-2 col-lg-2">
                            <label class="control-label">Adjuntar Documento:</label>
                            <select class="form-control" name="adjuntar_documento" id="adjuntar_documento"> 
                            </select>
                        </div>
                        <div class="col-md-2 col-lg-2" id="div_motivo_nota_credito">
                            <label class="control-label">Motivo Nota Crédito:</label>
                            <select class="form-control" name="tipo_ncredito" id="tipo_ncredito"> 
                            </select>
                        </div>                        
                        <div class="col-md-2 col-lg-2" id="div_motivo_nota_debito">
                            <label class="control-label">Motivo Nota Débito:</label>
                            <select class="form-control" name="tipo_ndebito" id="tipo_ndebito"> 
                            </select>
                        </div>
                    </div>
                    
                    <div class="row" id="div_anticipo">
                        <div  class="col-xs-2">
                            <label class="control-label">Anticipo:</label>
                            <select class="form-control" name="cbo_adjuntar_anticipo" id="cbo_adjuntar_anticipo"> 
                            </select>
                        </div>
                        <div class="col-xs-2" id="div_motivo_nota_credito">
                            <input style="margin-top: 22px" type="button" id="btn_adjuntar_anticipo" name="btn_adjuntar_anticipo" class="btn btn-info" value="Adjuntar Anticipo"/>
                        </div>                        
                        <div class="col-xs-4" id="div_motivo_nota_debito">
                            <input style="margin-top: 22px" type="text" class="form-control" readonly="" id="txt_documentos_anticipos" name="txt_documentos_anticipos" />
                        </div>
                    </div>
                </div>        
            </div>
                        
            <div class="row" style="padding-top:20px;">                
                <div class="col-lg-12">
                    <div id="panel_fomulario2" >  
                        <div class="panel-heading">
                            <div class="panel-title">CONCEPTOS DEL COMPROBANTE</div>
                        </div>
                        <div class="panel-body">                        
                            <div class="row" id="valida">
                                <div id="contendor_table" class="col-lg-12">
                                    <table id="tabla" class="table tabla_items" style="display:none" border="0">
                                        <thead>
                                            <tr>                                                
                                                <th>Descripcion</th>
                                                <th>Unid. Medida</th>                                                
                                                <th>Cant.</th>
                                                <th>Tipo Igv</th>
                                                <th>Precio Unitario</th>
                                                <th>Total</th>
                                                <th>Bolsa</th>  
                                                <!--<th>Descuento</th>-->  
                                            </tr>
                                        </thead>                    
                                        <tbody>                                                      
                                        </tbody>                    
                                    </table>
                                    <div class="row">
                                        <div class="col-xs-3 col-lg-1" id="div_agregar_item">
                                            <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                                        </div>
                                        <div class="col-xs-3 col-lg-1" id="div_nuevo_producto">                                        
                                            <button type="button" id="modal_nuevo_producto" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Producto</button>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>                            
                    </div>
                </div>                    
            </div>
        </div>
    </div>
        
    <div class="row" style="padding-top:20px;">
        <div class="col-md-12 col-lg-8">                                                                         
            <div id="panel_otros">
                <div class="panel-heading">
                    <div class="panel-title">Notas de la venta:<input type="checkbox" name="chkNotas" id="chkNotas"></div>
                </div>
                <div class="panel-body" id="div_notas_ventas">
                    <textarea name="notas" id="notas" rows="3" cols="100" disabled style="width: 100%;"></textarea>
                </div>
            </div>
            
            <div id="div_forma_pago" class="row" style="padding-left: 20px">
                <div class="col-xs-4 col-lg-2">
                    <label class="control-label">Forma de pago:</label>
                    <select class="form-control" id="forma_pago">
                    </select>
                </div>
                <div class="col-xs-4 col-lg-2">
                    <label class="control-label">Modo de pago:</label>
                    <select class="form-control" id="modo_pago">
                    </select>
                </div>
                <div class="col-xs-4 col-lg-2">
                    <label class="control-label">Nota de pago:</label>
                    <textarea class="form-control" id="nota" name="nota"></textarea>
                </div>
            </div>            
            
            <div class="row" style="padding-left: 40px">
                <div id="div_credito">
                    <hr>
                    <div class="row">
                        <div class="col-xs-2">
                            <label class="control-label">N. Cuotas:</label>
                            <input class="form-control" type="number" id="numero_cuotas" name="numero_cuotas"/>
                        </div>
                        <div class="col-xs-2">
                            <br>
                            <button type="button" class="btn btn-info btn-sm" id="btn_configuracion_pago">Programar Cuotas.</button>
                        </div>
                    </div>

                    <div class="row" style="padding-left: 20px">
                        <table id="tabla_credito" class="table tabla_items" style="display:none" border="0">
                            <thead>
                                <tr>                                                
                                    <th>N</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>                                        
                                </tr>
                            </thead>                    
                            <tbody>                                                      
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4 col-lg-4">           
            <div class="panel panel-body" style="border:1px solid #7FB3D5;border-radius:6px;">
                
                <div id="div_descuento_global" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Descuento global: <span class="descuento_global">S/.</span></span>
                    <input type="text" id="descuento_global" name="descuento_global" class="form-control derecha_text">
                </div>
                
                <div id="div_total_gravada" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Gravada: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_gravada" name="total_gravada" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>

                <div id="div_total_inafecta" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Inafecta: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_inafecta" name="total_inafecta" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>

                <div id="div_total_exonerada" class="input-group" >        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Op. Exonerada: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_exonerada" name="total_exonerada" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>

                <div id="div_total_igv" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total IGV (<span id="valor_igv"></span>%): <span class="selec_moneda">S/.</span></span>
                    <input type="text" id="total_igv" name="total_igv" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>

                <div id="div_total_gratuita" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Gratuita: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_gratuita" name="total_gratuita" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>
                
                <div id="div_total_exportacion" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Exportación: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_exportacion" name="total_exportacion" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>

                <div id="div_total_bolsa" class="input-group">        
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">ICBPER: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_bolsa" name="total_bolsa" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                </div>

                <div id="div_PrepaidAmount" class="input-group">                
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-right: 0;">Anticipo: <span class="selec_moneda">S/.</span></span>
                    <input type="text" id="PrepaidAmount" name="PrepaidAmount" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;">
                </div>
                
                <div id="div_total_a_pagar" class="input-group">                
                    <span class="input-group-addon" style="border:1px solid #ABB2B9;border-right: 0;">Pago Total: <span class="selec_moneda">S/.</span></span>                
                    <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;">
                </div>
            </div>           
        </div>

        <div id="div_detraccion" class="container">            
            <table>
                <tr>
                    <td>Código Detracción:</td>
                    <td><input type="text" class="form-control" value="" name="detraccion_codigo" id="detraccion_codigo" /></td>
                </tr>
                <tr>
                    <td>Porcentaje Detracción:</td>
                    <td><input type="text" class="form-control" name="detraccion_porcentaje" id="detraccion_porcentaje" /></td>
                </tr>
            </table>
        </div>
        
        <div id="div_retencion" class="container">
            <table>
                <tr>
                    <td>% Retención:</td>
                    <td><input type="text" class="form-control" value="" name="retencion_porcentaje" id="retencion_porcentaje" /></td>
                </tr>        
            </table>
        </div>
        <br>
        <br>
        <div class="container">
            <div class="row" style="padding-bottom: 2rem;">
                <div class="col-lg-12"> 
                    <input id="guardar" class="btn btn-primary btn-lg btn-block" value="Generar Comprobante de Pago" style="background: #1ABC9C;border:0;"/>                                        
                </div>
            </div>
        </div>
    </div>
</form>

<script src="../../../assets/js/monstruo/help.js"></script>
<script src="../../../assets/js/monstruo/config.js"></script>

<script type="text/javascript">
    let datos_configuracion         = JSON.parse(localStorage.getItem("datos_configuracion"));
    let datos_monedas               = JSON.parse(localStorage.getItem("monedas"));
    var productos_automaticos       = JSON.parse(localStorage.getItem("productos_automaticos"));
    var datos_accesorios            = JSON.parse(localStorage.getItem("datos_accesorios"));
    var ls_empresa                  = JSON.parse(localStorage.getItem("empresas"));
    
    var porcentaje_valor_igv        = datos_configuracion.porcentaje_valor_igv;
    var valor_impuesto_bolsa        = datos_configuracion.impuesto_bolsa;
    var catidad_decimales           = datos_configuracion.catidad_decimales;
    var tipo_documento_defecto_id   = datos_configuracion.tipo_documento_defecto_id;
    var codigo_ventas_con_anticipos = datos_configuracion.codigo_ventas_con_anticipos;
    
    let tipo_igv            = JSON.parse(localStorage.getItem("tipo_igv"));
    let variables_diversas  = JSON.parse(localStorage.getItem("variables_diversas"));
    let unidades_activas    = JSON.parse(localStorage.getItem("unidades_activas"));
    var tipo_igv_defecto    = variables_diversas.tipo_igv_defecto;
    var precio_con_igv      = variables_diversas.precio_con_igv;
    var detracciones        = variables_diversas.detracciones;
    var retenciones         = variables_diversas.retenciones;
    var valor_igv           = 0.10;
    
    var direccion_cliente_incial = '';
    var select_evento = 0;//1 autocompleter  -  2 boton buscador sunat---- para actualizar direcion y saber de donde llega el dato direccion
    
    var param_precios       = (precio_con_igv == 1) ? (1 + porcentaje_valor_igv) : 1 ;
    //let series_defecto      = JSON.parse(localStorage.getItem("series_defecto"));    
    
    var url_serie = base_url + 'index.php/WS_series/series_defecto';
    $.getJSON(url_serie)
    .done(function (data) {
        (data.ws_select_series).forEach(function (repo) {
            $('#series').append("<option value='" + repo.id + "'>" + repo.serie + "</option>");
        });
    });
    
    var respuesta_inconsistencia = 0;
    var respuesta_inconsistencia_cuotas = 0;
    var total_igv;
    
    var txt_documentos_anticipos = '';
    var documento_id_anticipos = [];
    var total_anticipo = 0;
    
    var descuento_global_anterior = 0;    
    //URL
    //#' + venta_id + '/' + operacion + '/' + enviar_a_facturar
    //venta_id: id para la actualizacion
    //operacion: 1 para crear facturas, boletas o Notas, 2 - Pedido de Ventas, 3 - Cotizacines
    //enviar_a_facturar (Envio a facturar a boletear si son: Notas de Venta o Cotizaciones): 0 no envia, 1 envia;
        
    var params_url = window.location.hash;
    //console.log('params_url:'+params_url);
    
    var enviar_a_facturar   = params_url.substr(-1);
    var operacion_action    = params_url.substr(-3,1);
    var venta_id            = params_url.substring(1, (params_url.length - 4));
    
//    console.log('enviar_a_facturar:'+enviar_a_facturar);
//    console.log('operacion_action:'+operacion_action);
//    console.log('venta_id:'+venta_id);    
    
    switch(operacion_action){
        case '1':     
            $("#panel_fomulario").addClass( "panel panel-primary");
            $("#panel_fomulario2").addClass( "panel panel-primary");
            $("#panel_otros").addClass( "panel panel-primary");
        break;

        case '2':            
            $("#panel_fomulario").addClass( "panel panel-info");
            $("#panel_fomulario2").addClass( "panel panel-info");
            $("#panel_otros").addClass( "panel panel-info");
        break;

        case '3':            
            $("#panel_fomulario").addClass( "panel panel-success");
            $("#panel_fomulario2").addClass( "panel panel-success");
            $("#panel_otros").addClass( "panel panel-success");
        break;
    }
    
    $("#valor_igv").text(valor_igv);
    
    if(detracciones == '1'){
        $("#div_detraccion").show();
    }
    if(detracciones == '0'){
        $("#div_detraccion").hide();
    }
    
    if(retenciones == '1'){
        $("#div_retencion").show();
    }
    if(retenciones == '0'){
        $("#div_retencion").hide();
    }
    
    if((enviar_a_facturar == 1) || (venta_id == '')){//CREAR
        $("#div_numero").hide();
    }else{//ACTUALIZAR
        $("#guardar").val('Modificar');
    }        
        
    //let venta_id = url_editar.substring(1, (url_editar.length - 2 ));
    if(datos_accesorios == 1){
        $("#campos_accesorios").show();
        $("#div_orden_compra").show();
    }else{
        $("#campos_accesorios").hide();
        $("#div_orden_compra").hide();
    }
    
    if(operacion_action != 1 && enviar_a_facturar == 0){
        $("#div_tipo_documento").hide();
        $("#div_serie").hide();
        $("#div_orden_compra").hide();
        $("#div_guia").hide();
        $("#div_tipo_de_operacion").hide();
        $("#campos_accesorios").hide();
    }
    //descuento global por mientras desactivado.
    $("#div_descuento_global").hide();
    
    $("#div_notas_ventas").hide();
    $("#div_credito").hide();
    var valor_cuota = 0;
    var total_pago_cuotas = 0;
    if(enviar_a_facturar == 0 && operacion_action != 1){
        $("#div_forma_pago").hide();
    }

    var url_operacion = base_url + 'index.php/WS_variables_diversas/tipo_operaciones/' + operacion_action;
    $.getJSON(url_operacion)
    .done(function (data) {
        let texto_inicial = (enviar_a_facturar == 1) ? 'Mandar a Facturar: ' : 'NUEVAS ';
        $('#operacion_texto').text(texto_inicial + data.toUpperCase());
    });
    
    $.getJSON(base_url + 'index.php/WS_forma_pagos/select_all')
    .done(function (data) {
        (data).forEach(function (repo) {
            $('#forma_pago').append("<option value='" + repo.id + "'>" + repo.forma_pago + "</option>");
        });
    });
    
    $.getJSON(base_url + 'index.php/WS_modo_pagos/select_all')
    .done(function (data) {
        (data).forEach(function (repo) {
            $('#modo_pago').append("<option value='" + repo.id + "'>" + repo.modo_pago + "</option>");
        });
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_operaciones/tipo_de_operaciones')
    .done(function (data) {
        (data.tipo_de_operacion).forEach(function (repo) {
            $('#tipo_operacion').append("<option value='" + repo.codigo + "'>" + repo.operacion + "</option>");
        });
    });
    
    $("#img_atras").attr("src", base_url + "images/atras.png");
    $("#enlace_atras").attr("href", base_url + "index.php/ventas/index/" + operacion_action);
    
    $("#div_total_gravada").hide();
    $("#div_total_igv").hide();
    $("#div_total_gratuita").hide();
    $("#div_total_exportacion").hide();
    $("#div_total_inafecta").hide();
    $("#div_total_bolsa").hide();
    $("#div_total_exonerada").hide();
    $("#div_anticipo").hide();
    
    //documentos de Notas de credito
    $("#div_documento").hide();
    $("#div_motivo_nota_credito").hide();
    $("#div_motivo_nota_debito").hide();
    
    $(document).ready(function () {
        var today   = new Date();                
        var dd      = today.getDate(); 
        var mm      = today.getMonth() + 1;   
        var yyyy    = today.getFullYear();
        
        if (dd < 10) { 
            dd = '0' + dd; 
        } 
        if (mm < 10) { 
            mm = '0' + mm; 
        } 
        var today = dd + '-' + mm + '-' + yyyy; 

        $('#fecE').val(today);
        $('#fecE').datepicker();        
        $("#fecV").datepicker();
        
        $("#guardar").on("click", function(){            
            var array_producto_id   = [];
            var array_producto      = [];
            var array_unidad        = [];
            var array_cantidad      = [];
            var array_tipo_igv      = [];
            var array_precio_base   = [];
            var array_chekBolsa     = [];
            var array_descuento_lineal = [];
            var data = {};
                        
            $('#tabla tbody tr').each(function(){
                let producto_id = $(this).find('td').eq(0).find('.val-descrip').val();
                let producto    = $(this).find('td').eq(0).find('.descripcion-item').val();
                let unidad      = $(this).find('td').eq(1).children().val();
                let cantidad    = $(this).find('td').eq(2).children().val();
                let tipo_igv    = $(this).find('td').eq(3).children().val();
                let precio_base = $(this).find('td').eq(4).children().val()/param_precios;
                
                let chekBolsa = ($(this).find('td').eq(6).find('#chekBolsa').is(":checked") == true) ? valor_impuesto_bolsa : null;
                let descuento = $(this).find('td').eq(7).children().val();
                
                array_producto_id.push(producto_id);
                array_producto.push(producto);
                array_unidad.push(unidad);
                array_cantidad.push(cantidad);
                array_tipo_igv.push(tipo_igv);
                array_precio_base.push(precio_base);
                array_chekBolsa.push(chekBolsa);
                array_descuento_lineal.push(descuento);
            });

            respuesta_inconsistencia = 0;
            respuesta_inconsistencia = detectorInconsistencias(array_tipo_igv, $("#tipo_operacion").val(), $("#tipo_entidad_id").val(), array_producto_id, array_producto, array_cantidad, $("#entidad_id").val(), $("#tipo_documento").val(), $("#adjuntar_documento").val());
            if(respuesta_inconsistencia == 1)return false;
            
            data['producto_id']     = array_producto_id;
            data['producto']        = array_producto;
            data['unidad']          = array_unidad;
            data['cantidad']        = array_cantidad;
            data['tipo_igv_id']     = array_tipo_igv;
            data['precio_base']     = array_precio_base;
            data['impuesto_bolsa']  = array_chekBolsa;
            data['descuento']       = array_descuento_lineal;

            data['operacion']                   = operacion_action;
            data['venta_id']                    = venta_id;
            data['enviar_a_facturar']           = enviar_a_facturar;
            
            data['entidad_id']                  = $("#entidad_id").val();
            data['direccion_cliente']           = $("#direccion").val();
            data['direccion_cliente_incial']    = direccion_cliente_incial;
            data['select_evento']               = select_evento;
            
            if((operacion_action == 1) || (enviar_a_facturar == 1)){
                data['tipo_documento_id']       = $("#tipo_documento").val();
                data['serie']                   = $("#series option:selected").text();
                data['orden_compra']            = $("#orden_compra").val();
                data['tipo_operacion']          = $("#tipo_operacion").val();
            }                                    
            
            data['ruc']                     = ls_empresa.ruc;
            data['numero']                  = $("#numero").val();
            data['fecha_emision']           = $("#fecE").val();
            data['fecha_vencimiento']       = $("#fecV").val();
            data['moneda_id']               = $("#moneda").val();
            data['tipo_de_cambio']          = $("#tipo_de_cambio").val();
            data['total_descuentos']        = $("#descuento_global").val();
            data['total_gravada']           = $("#total_gravada").val();
            data['porcentaje_igv']          = porcentaje_valor_igv;
            data['total_igv']               = $("#total_igv").val();
            data['total_gratuita']          = $("#total_gratuita").val();
            data['total_exportacion']       = $("#total_exportacion").val();
            data['total_exonerada']         = $("#total_exonerada").val();
            data['total_inafecta']          = $("#total_inafecta").val();
            data['bolsa_monto_unitario']    = valor_impuesto_bolsa;
            data['total_bolsa']             = $("#total_bolsa").val();
            data['total_a_pagar']           = $("#total_a_pagar").val();
            data['PrepaidAmount']           = $("#PrepaidAmount").val();
            data['notas']                   = $("#notas").val();
            data['forma_pago_id']           = $("#forma_pago").val();
            data['modo_pago_id']            = $("#modo_pago").val();
            data['numero_guia']             = $("#numero_guia").val();
            data['condicion_venta']         = $("#condicion_venta").val();
            data['nota_venta']              = $("#nota_venta").val();
            data['numero_pedido']           = $("#numero_pedido").val();
            
            if(detracciones == 1){
                data['detraccion_codigo']               = $("#detraccion_codigo").val();
                data['detraccion_porcentaje']           = $("#detraccion_porcentaje").val();
            }
            
            if(retenciones == 1){
                data['retencion_porcentaje']            = $("#retencion_porcentaje").val();
            }
            
            switch ($("#tipo_documento").val()) {
                case '7':
                data['venta_relacionado_id']    = $("#adjuntar_documento").val();
                data['tipo_ncredito_id']        = $("#tipo_ncredito").val();
                break;

                case '8':
                data['venta_relacionado_id']    = $("#adjuntar_documento").val();
                data['tipo_ndebito_id']         = $("#tipo_ndebito").val();
                break;
            }
            
            //PARA ANTICIPOS
            //documentos de ventas que serán anticipos de una venta
            data['documento_id_anticipos']  = documento_id_anticipos;
            //            
            //detectar error en cuotas -- INICIO
            if($('#forma_pago').val() == '2'){
                var array_valor_cuota   = [];
                var array_fecha_cuota   = [];
                $('#tabla_credito tbody tr').each(function(){
                    let valor_cuota = $(this).find('td').eq(1).children().val();
                    let fecha_cuota = $(this).find('td').eq(2).children().val();

                    array_valor_cuota.push(valor_cuota);
                    array_fecha_cuota.push(fecha_cuota);

                    total_pago_cuotas += valor_cuota;
                });            
                respuesta_inconsistencia_cuotas = 0;
                respuesta_inconsistencia_cuotas = detectorInconsistencias_cuotas(array_valor_cuota, array_fecha_cuota, total_pago_cuotas);
                if(respuesta_inconsistencia_cuotas == 1)return false;                
            }            
            //detectar error en cuotas -- FIN
            
            //para insertar o para enviar a facturar una cotizacion o nota de venta
            if((enviar_a_facturar == 1) || (venta_id == '')){
                //envio el maximo numero del documento desde la vista. (Para una operacion que evita q se graven registros multiples)
                var url_max_numero = base_url + 'index.php/WS_ventas/maximo_numero_documento/1/'+$("#tipo_documento").val()+'/'+$("#series option:selected").text();
                $.getJSON(url_max_numero, data)
                .done(function(datos, textStatus, jqXHR){
                    data['numero']  =  datos.numero + 1;
                    var url_save = base_url + 'index.php/ventas/operaciones';
                    $.getJSON(url_save, data)
                    .done(function(datos, textStatus, jqXHR){
                        toast('success', 2500, 'Venta ingresada correctamente');
                        
                        //guardo pagos y cuotas
                        if((enviar_a_facturar == 1) || (operacion_action == 1)){
                            pagos_and_cuotas(operacion_action, datos.venta_id);
                        }                        
                        
                        let param_enviar = (enviar_a_facturar == 1) ? 1 : operacion_action;
                        window.location.href = base_url + 'index.php/ventas/index/' + param_enviar;
                    })
                    .fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                            console.log( "Algo ha fallado: " +  textStatus );
                        }
                    });
                })
            }else{//para actualizar
                var url_save = base_url + 'index.php/ventas/operaciones';
                $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    
                    var param_enviar = (enviar_a_facturar == 1) ? 1 : operacion_action;
                    if((enviar_a_facturar == 0) && (operacion_action == 1)){
                        var d_cuotas_pagos = base_url + 'index.php/WS_cuotas/delete_cuotas_pagos/' + venta_id;
                        $.getJSON(d_cuotas_pagos)
                        .done(function(datos, textStatus, jqXHR){
                            pagos_and_cuotas(operacion_action, venta_id);
                            toast('success', 2500, 'Venta ingresada correctamente');                            
                            window.location.href = base_url + "index.php/ventas/index/" + param_enviar;
                        });
                    }
                    toast('success', 2500, 'Venta ingresada correctamente');
                    window.location.href = base_url + "index.php/ventas/index/" + param_enviar;                    
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " +  textStatus );
                    }
                });
            }
            //en el caso q se envie a facturar (para notas y cotizaciones) se enviara al listado de comprobantes (facturas y boletas)            
        });

        $("#agrega").on('click', function(){
            agregarFila(undefined, undefined, undefined, undefined, undefined, undefined, undefined, tipo_igv_defecto);
        });

        $('#tipo_documento').on('change', function () {
            $('#series').empty();
            entidad_id = $('#entidad_id').val();
            
            if((this.value == 7) || (this.value == 8)){
                $("#div_agregar_item").hide();
            }else{
                $("#div_agregar_item").show();
            }
            
            var tipo_documento_select = this.value;            
            $.getJSON(base_url + 'index.php/WS_series/ws_select/' + this.value)
            .done(function (data) {
                $('#series').empty();
                (data.ws_select_series).forEach(function (repo) {
                    $('#series').append("<option value='" + repo.serie_id + "'>" + repo.serie + "</option>");
                });                
                cargaDocumentoNotaCredito(entidad_id, tipo_documento_select);
            });
                        
        });
        
        $('#tipo_operacion').on('change', function(){            
            if( (this.value == codigo_ventas_con_anticipos) && ($("#entidad_id").val() != '') && ($("#entidad_id").val() != 0)){
                $("#div_anticipo").show();
                documento_id_anticipos = [];
                llenar_anticipos(this.value, $("#entidad_id").val());
            }else{
                $("#div_anticipo").hide();
                total_anticipo = 0;
                regreso_total = parseFloat($("#total_a_pagar").val()) + parseFloat($("#PrepaidAmount").val());
                $("#total_a_pagar").val(regreso_total);
                $("#PrepaidAmount").val('');
                txt_documentos_anticipos = '';
                $("#txt_documentos_anticipos").val(txt_documentos_anticipos);
            }
        });
        
        $('#series').on('change', function () {
            var tipo_documento_id = $("#tipo_documento option:selected").val();                    
            var serie = $("#series option:selected").text();            
            
            entidad_id = $('#entidad_id').val();
            cargaDocumentoNotaCredito(entidad_id, tipo_documento_id);            
        });
        
        $('#moneda').on('change', function () {
            var moneda_id = $("#moneda option:selected").val();
            var fecha = fecha_actual_completando_ceros(2);
            var ruta = base_url + 'index.php/WS_tipo_cambios/tipo_cambio/' + fecha;
            
            if(moneda_id == 1){
                $('#tipo_de_cambio').prop('disabled', true);
                $('#tipo_de_cambio').val('');
            }
            if(moneda_id == 2){                
                $(".selec_moneda").text("$");
                $('#tipo_de_cambio').prop('disabled',false);                
                $.getJSON(ruta)
                .done(function (data) {                    
                    $('#tipo_de_cambio').val(data);
                });
            }
            if(moneda_id == 3){
                $(".selec_moneda").text("E");
                $('#tipo_de_cambio').prop('disabled',false);
                $('#tipo_de_cambio').val('');
            }
        });
        
        $('#adjuntar_documento').on('change', function(){
            //sacamos el venta_id del documento a adjuntar. Luego sacamos los items de dicho documento.
            venta_id_DA = $("#adjuntar_documento option:selected").val();
            if(venta_id_DA != undefined){
                $('#moneda').empty();
                let url_moneda= base_url + 'index.php/WS_ventas/select_by_campo/' + venta_id_DA + '/moneda_id';
                $.getJSON(url_moneda)
                .done(function (data) {
                    $.each(datos_monedas, function(i, item) {
                        let selected = (data.moneda_id == item.id) ? 'selected' : '';
                        $('#moneda').append("<option "+selected+" value='" + item.id + "'>" + item.moneda + "</option>");
                    });
                    
                    var moneda_id = $("#moneda option:selected").val();
                    if(moneda_id == 1){
                        $('#tipo_de_cambio').prop('disabled', true);
                        $('#tipo_de_cambio').val('');
                    }else{
                        $('#tipo_de_cambio').prop('disabled',false);
                    }
                });
                
                limpiarTotales();
                $("#tabla tbody").empty();
                let url_detalle = base_url + 'index.php/WS_venta_detalles/ws_detalle/' + venta_id_DA;
                $.getJSON(url_detalle)
                .done(function (data) {
                    (data).forEach(function (repo) {
                        agregarFila(repo.producto, repo.producto_id, repo.unidad, repo.cantidad, (repo.precio_base) * param_precios, parseFloat((repo.precio_base) * param_precios * repo.cantidad).toFixed(catidad_decimales) , repo.impuesto_bolsa, repo.tipo_igv_id, repo.descuento);
                        calcularTotales(repo.cantidad, repo.precio_base, repo.tipo_igv_id, repo.impuesto_bolsa, total_anticipo, 0);
                    });
                });
            }
        });
        
        $('#tabla').on('click', '.eliminar', function(){
            $(this).closest('tr').remove();
            
            limpiarTotales();
            $('#tabla tbody tr').each(function(){
                var cantidad = $(this).find('td').eq(2).children().val();
                var tipo_igv = $(this).find('td').eq(3).children().val();
                param_precios = (tipo_igv == 1) ? param_precios : 1
                var precio = $(this).find('td').eq(4).children().val()/param_precios;
                              
                var txt_precio = $(this).find('td').eq(4).children().val();
                $(this).find('td').eq(5).children().val((cantidad*txt_precio).toFixed(catidad_decimales));
                var chequeado = $(this).find('td').eq(6).children().prop('checked');
                
                calcularTotales(cantidad, precio, tipo_igv, chequeado, total_anticipo, 0);
            });
        });        
        
        $('#contendor_table').on('keyup change', '.tabla_items',function(){
            limpiarTotales();
            
            var param_precio = (precio_con_igv == 1) ? (1 + porcentaje_valor_igv) : 1 ;
            $('#tabla tbody tr').each(function(){
                var cantidad = $(this).find('td').eq(2).children().val();
                var tipo_igv = $(this).find('td').eq(3).children().val();
                param_precio = (tipo_igv == 1) ? param_precio : 1;//recibe la variación del IGV (El q se coloca con el chek en vista, index de venta) solo cuando el impuesto es 1 (osea grabado)
                var precio = $(this).find('td').eq(4).children().val()/param_precio;
                
                var txt_precio = $(this).find('td').eq(4).children().val();
                $(this).find('td').eq(5).children().val((cantidad*txt_precio).toFixed(catidad_decimales));
                var chequeado = $(this).find('td').eq(6).children().prop('checked');
                var descuento = $(this).find('td').eq(7).children().val()/param_precio;
                
                calcularTotales(cantidad, precio - descuento/cantidad, tipo_igv, chequeado, total_anticipo, 0);
            });
            
            $('.descripcion-item').autocomplete({
                source : '<?PHP echo base_url();?>index.php/WS_ventas/buscador_item',
                minLength : 2,
                select : function (event,ui){
                    var _item = $(this).closest('.cont-item');
                    var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.producto_id + '" name = "item_id[]" id = "item_id">';

                    _item.find('#data_item').html(data_item);
                    _item.find('#descripcion').attr("readonly",true);
                    _item.find("#unidad option[value='"+ui.item.unidad_id+"']").prop('selected', true);
                    _item.find('#unidad').attr("readonly",true);
                    
                    _item.find('.importe').val((ui.item.precio*param_precio).toFixed(catidad_decimales));
                    _item.find('.totalp').val((ui.item.precio*param_precio).toFixed(catidad_decimales));
                    
                    limpiarTotales();
                    descuento_global_anterior = 0;
                    $('#tabla tbody tr').each(function(){
                        var cantidad = $(this).find('td').eq(2).children().val();
                        var tipo_igv = $(this).find('td').eq(3).children().val();
                        param_precio = (tipo_igv == 1) ? param_precio : 1;//recibe la variación del IGV (El q se coloca con el chek en vista, index de venta) solo cuando el impuesto es 1 (osea grabado)
                        var precio = $(this).find('td').eq(4).children().val()/param_precio;
                        
                        //$(this).find('td').eq(5).children().val(cantidad*precio);
                        var chequeado = $(this).find('td').eq(6).children().prop('checked');
                        var descuento = $(this).find('td').eq(7).children().val()/param_precio;
                
                        calcularTotales(cantidad, precio - descuento/cantidad, tipo_igv, chequeado, total_anticipo, 0);
                    });
                }
            });
        });
        
        $('#descuento_global').on('keyup', function(){
            total_gravada = $("#total_gravada").val();
            var keycode = event.keyCode;
            
            if(keycode == '13'){
                //$("#total_gravada").val(parseFloat(total_gravada) - parseFloat($(this).val()) + parseFloat(descuento_global_anterior) + parseFloat(descuento_global_grabado_anterior));
                $("#total_gravada").val(parseFloat(total_gravada) - parseFloat($(this).val()) + parseFloat(descuento_global_anterior));
                $("#total_igv").val(valor_igv * $("#total_gravada").val());
                $("#total_a_pagar").val(valor_igv * $("#total_gravada").val());                                
                
                descuento_global_anterior = $(this).val();
            }            
        });
        
        $("#chkNotas").change(function(){
            if($(this).is(":checked")){
                $("#notas").removeAttr("disabled");
                $("#div_notas_ventas").show();
            }else{
                $("#notas").attr("disabled","tue");
                $("#div_notas_ventas").hide();
            }
        });
        
        $('#forma_pago').on('change', function(){
            if($('#forma_pago').val() == '2'){
                $("#div_credito").show();
                $("#modo_pago").attr('disabled', true);
                $("#nota").attr('disabled', true);
                $("#numero_cuotas").val(1);
                
                valor_cuota = $("#total_a_pagar").val();
                agregarTablaCredito(1, parseFloat(valor_cuota));
            }else{
                $("#div_credito").hide();
                $("#modo_pago").attr('disabled', false);
                $("#nota").attr('disabled', false);
            }
        });
        
        $("#btn_configuracion_pago").on('click', function(){
            console.log('btn-cfg:');
            if(($("#total_a_pagar").val() == '') || ($("#total_a_pagar").val() == '0')){
                alert('El monto total a pagar debe ser mayor a cero (> 0)');
                return false;
            }
            
            valor_cuota = $("#total_a_pagar").val()/$("#numero_cuotas").val();
            agregarTablaCredito( $("#numero_cuotas").val(), valor_cuota);
        });
        
        //Buscar RUC externo ws
        $("#datos_entidad_ws_externa").on('click', function(){
            var numero_documento_buscar = $("#entidad").val();
            if( (numero_documento_buscar.length != 8) && (numero_documento_buscar.length != 11) ){
                toast('Error', 1500, 'Cantidad de dígitos incorrectos.');
                return false;
            }
            toast('success', 2000, 'Buscando');
            //console.log('numero_documento_buscar:'+numero_documento_buscar);
            var url_l = base_url + 'index.php/WS_entidades/buscador_externo_ruc_ventas/' + numero_documento_buscar + '/1';            
            $.getJSON(url_l)
            .done(function (data) {
                $("#entidad_id").val(data.entidad_id);
                $("#entidad").val(data.entidad + ' - ' + numero_documento_buscar);
                $("#direccion").val(data.direccion);
                
                direccion_cliente_incial = data.direccion;
                select_evento = 2;

                if(numero_documento_buscar.length == 8){
                    $("#tipo_entidad_id").val(1);
                }
                if(numero_documento_buscar.length == 11){
                    $("#tipo_entidad_id").val(2);
                }

                if($("#tipo_operacion").val() == codigo_ventas_con_anticipos){
                    llenar_anticipos($("#tipo_operacion").val(), data.entidad_id)
                }

            })
            .fail(function() {
                toast('Error', 1500, 'Datos no encontrados');
                $("#entidad_id").val('');
                $("#entidad").val('');
                $("#direccion").val('');
                $("#tipo_entidad_id").val('');
            });
        });
        
        $("#crear_nueva_entidad").on('click', function(){
            ruta_url = base_url + 'index.php/ventas/modal_nueva_entidad/';
            $("#myModal").load(ruta_url);
        });
        
        $("#modal_nuevo_producto").on('click', function(){
            //console.log('direccion_cliente_incial:' + direccion_cliente_incial);
            
            ruta_url = base_url + 'index.php/ventas/modal_nuevo_producto/';
            $("#myModal").load(ruta_url);
        });
        
        $("#btn_adjuntar_anticipo").on('click', function(){            
            txt_documentos_anticipos += $('select[id="cbo_adjuntar_anticipo"] option:selected').text() + ', ';            
            $("#txt_documentos_anticipos").val(txt_documentos_anticipos);
                    
            let array_anticipo = $('select[id="cbo_adjuntar_anticipo"] option:selected').val();                    
            var res = array_anticipo.split("-x-");
            console.log(res[0]);
            console.log(res[1]);
            //calcularTotales(cantidad, precio, tipo_igv, chequeado_bolsa)
            documento_id_anticipos.push(res[0]);            
            calcularTotales(0, 0, 1, false, parseFloat(res[1]), 1);
        });
        
        /////////-----EDITAR------//////////
        if(venta_id != ''){
            var parametro_precios = (precio_con_igv == 1) ? (1 + porcentaje_valor_igv) : 1 ;
            let url_cabecera = base_url + 'index.php/WS_ventas/ws_cabecera/' + venta_id;
            $.getJSON(url_cabecera)
            .done(function (data) {
                $("#fecE").val(data.fecha_emision);
                if(data.fecha_vencimiento != null) $("#fecV").val(data.fecha_vencimiento);
                $("#tipo_operacion option[value='"+data.tipo_operacion+"']").prop('selected', true);
                $("#tipo_documento option[value='"+data.tipo_documento_id+"']").prop('selected', true);
                
                if(enviar_a_facturar == 0){
                    $('#tipo_documento').attr('disabled', 'disabled');
                }

                //solo para facturas, boletas y notas de C Y D
                if(operacion_action == 1 || enviar_a_facturar == 1){
                    if((data.tipo_documento_id == 7) || (data.tipo_documento_id == 8)){
                        $("#div_documento").show();
                        serie_text = data.serie;
                        letra = serie_text.substring(0,1);
                        
                        switch (letra) {
                            case 'F':
                            buscar_tipo_documento_id = 1;
                            break;

                            case 'B':
                            buscar_tipo_documento_id = 3;
                            break;
                        }
                        
                        url_nc = base_url + 'index.php/WS_ventas/ws_select_entidad_documento/' + data.entidad_id + '/' + buscar_tipo_documento_id;
                        $.getJSON(url_nc)
                        .done(function (data_documentos) {
                            $('#adjuntar_documento').empty();

                            (data_documentos).forEach(function (repo) {
                                $('#adjuntar_documento').append("<option value='" + repo.id + "'>" + repo.serie + "-" + repo.numero + "</option>");
                            });
                            $("#adjuntar_documento option[value='"+ data.venta_relacionado_id +"']").attr("selected", true);
                            
                            //sacamos el venta_id del documento a adjuntar. Luego sacamos los items de dicho documento.
                            $("#tabla tbody").empty();
                            //venta_id_DA = $("#adjuntar_documento option:selected").val();
                            venta_id_DA = data.venta_relacionado_id;
                            if(venta_id_DA != undefined){

                                $('#moneda').empty();
                                let url_moneda= base_url + 'index.php/WS_ventas/select_by_campo/' + venta_id_DA + '/moneda_id';
                                $.getJSON(url_moneda)
                                .done(function (data) {                        
                                    $.each(datos_monedas, function(i, item) {
                                        let selected = (data.moneda_id == item.id) ? 'selected' : '';
                                        $('#moneda').append("<option "+selected+" value='" + item.id + "'>" + item.moneda + "</option>");
                                    });

                                    var moneda_id = $("#moneda option:selected").val();
                                    if(moneda_id == 1){
                                        $('#tipo_de_cambio').prop('disabled', true);
                                        $('#tipo_de_cambio').val('');
                                    }else{
                                        $('#tipo_de_cambio').prop('disabled',false);
                                    }
                                });

                                limpiarTotales();
                                let url_detalle = base_url + 'index.php/WS_venta_detalles/ws_detalle/' + venta_id_DA;
                                $.getJSON(url_detalle)
                                .done(function (data) {
                                    (data).forEach(function (repo) {
                                        agregarFila(repo.producto, repo.producto_id, repo.unidad_id, repo.cantidad, parseFloat(repo.precio_base * parametro_precios).toFixed(catidad_decimales), parseFloat(repo.precio_base * repo.cantidad * parametro_precios).toFixed(catidad_decimales) , repo.impuesto_bolsa, repo.tipo_igv_id, repo.descuento);
                                        calcularTotales(repo.cantidad, repo.precio_base, repo.tipo_igv_id, repo.impuesto_bolsa, total_anticipo, 0);
                                    });
                                });
                            }
                        });
                        //cargamos Motivo de Nota de credito.
                        $("#tipo_ncredito option[value='"+ data.tipo_ncredito_id +"']").attr("selected", true);
                        cargar_motivo_tipo_documentos(data.tipo_documento_id, data.tipo_ncredito_id);
                    }
                    
                    //al contado
                    if(data.forma_pago_id == "1"){
                        let url_contado = base_url + 'index.php/WS_cobros/ws_select_cobro/' + venta_id;
                        $.getJSON(url_contado)
                        .done(function (data_cobro) {
                            $("#modo_pago option[value='"+data_cobro.id+"']").prop('selected', true);
                            $("#nota").val(data.nota);
                        });
                    }else if(data.forma_pago_id == "2"){//al credito
                        $("#div_credito").show();
                        $("#forma_pago option[value='"+data.forma_pago_id+"']").prop('selected', true);
                        $("#modo_pago").attr('disabled', true);
                        $("#nota").attr('disabled', true);
                        
                        $.getJSON(base_url + 'index.php/WS_cuotas/ws_select/' + venta_id)
                        .done(function (data) {
                            $("#tabla_credito tbody").empty();
                            $("#tabla_credito").css("display","block");
                            $("#numero_cuotas").val(data.length);
                            var order_cuota = 1;
                            (data).forEach(function (repo) {
                                agregarTablaCreditoFila(order_cuota, repo.monto, repo.fecha_cuota);
                                order_cuota ++
                            });
                        });
                    }
                }
                
                $("#numero").val(data.numero);
                $("#entidad").val(data.entidad);
                $("#entidad_id").val(data.entidad_id);
                $("#tipo_entidad_id").val(data.tipo_entidad_id);
                $("#direccion").val(data.direccion_entidad);
                
                $("#moneda option[value='"+data.moneda_id+"']").prop('selected', true);
                $("#tipo_de_cambio").val(data.tipo_de_cambio);
                $("#orden_compra").val(data.orden_compra);
                $("#notas").val(data.notas);
                $("#numero_guia").val(data.numero_guia);
                $("#condicion_venta").val(data.condicion_venta);
                $("#nota_venta").val(data.nota_venta);
                $("#numero_pedido").val(data.numero_pedido);
                
                $("#descuento_global").val(data.total_descuentos);
                descuento_global_anterior = (data.total_descuentos == null) ? 0 : data.total_descuentos;
                
                $("#total_gravada").val(data.total_gravada);
                if(data.total_gravada != null) $("#div_total_gravada").show();
                
                $("#total_igv").val(data.total_igv);
                if(data.total_igv != null) $("#div_total_igv").show();
                
                $("#total_gratuita").val(data.total_gratuita);
                if(data.total_gratuita != null) $("#div_total_gratuita").show();
                
                $("#total_exportacion").val(data.total_exportacion);
                if(data.total_exportacion != null) $("#div_total_exportacion").show();
                
                $("#total_exonerada").val(data.total_exonerada);
                if(data.total_exonerada != null) $("#div_total_exonerada").show();
                
                $("#total_inafecta").val(data.total_inafecta);
                if(data.total_inafecta != null) $("#div_total_inafecta").show();
                
                $("#total_bolsa").val(data.total_bolsa);
                if(data.total_bolsa != null) $("#div_total_bolsa").show();
                
                $("#total_a_pagar").val(data.total_a_pagar);
                                
                if(enviar_a_facturar == 0){
                    let url_serie = base_url + 'index.php/WS_series/select_by_serie/' + data.serie;
                    $.getJSON(url_serie)
                    .done(function (data_serie) {
                        $('#series').empty();
                        $('#series').append("<option value='" + data_serie.id + "'>" + data_serie.serie + "</option>");
                        $('#series').attr('disabled', 'disabled');
                    });
                }                
            });
                        
            let url_detalle = base_url + 'index.php/WS_venta_detalles/ws_detalle/' + venta_id;
            $.getJSON(url_detalle)
            .done(function (data) {
                (data).forEach(function (repo) {                    
                    agregarFila(repo.producto, repo.producto_id, repo.unidad_id, repo.cantidad, parseFloat(repo.precio_base * parametro_precios).toFixed(catidad_decimales), parseFloat(repo.precio_base * repo.cantidad * parametro_precios).toFixed(catidad_decimales) , repo.impuesto_bolsa, repo.tipo_igv_id, repo.descuento);
                });
            });
        }
    });
    
    function llenar_anticipos(tipo_operacion, entidad_id){
        if((tipo_operacion == codigo_ventas_con_anticipos) && ( entidad_id != '' ) ){
            url_nc = base_url + 'index.php/WS_ventas/ws_select_entidad_documento/' + entidad_id;
            $.getJSON(url_nc)
            .done(function (data) {
                $('#cbo_adjuntar_anticipo').empty();
                
                (data).forEach(function (repo) {
                    $('#cbo_adjuntar_anticipo').append("<option value='" + repo.id + '-x-' + repo.total_a_pagar + "'>" + repo.serie + "-" + repo.numero + "</option>");
                });
            });
        }
    }
    
    function cargaDocumentoNotaCredito(entidad_id, tipo_documento){    
        //tipo_documento = $('#tipo_documento').val();
        //entidad_id = $('#entidad_id').val();        
        if((tipo_documento == 7) || (tipo_documento == 8)){
            $("#div_documento").show();
            serie_text = $("#series option:selected").text();
            letra = serie_text.substring(0,1);

            switch (letra) {
                case 'F':
                buscar_tipo_documento_id = 1;
                break;

                case 'B':
                buscar_tipo_documento_id = 3;
                break;
            }

            url_nc = base_url + 'index.php/WS_ventas/ws_select_entidad_documento/' + entidad_id + '/' + buscar_tipo_documento_id;
            $.getJSON(url_nc)
            .done(function (data) {
                $('#adjuntar_documento').empty();
                
                (data).forEach(function (repo) {
                    $('#adjuntar_documento').append("<option value='" + repo.id + "'>" + repo.serie + "-" + repo.numero + "</option>");
                });
                
                //sacamos el venta_id del documento a adjuntar. Luego sacamos los items de dicho documento.
                $("#tabla tbody").empty();
                venta_id_DA = $("#adjuntar_documento option:selected").val();
                if(venta_id_DA != undefined){
                    
                    $('#moneda').empty();
                    let url_moneda= base_url + 'index.php/WS_ventas/select_by_campo/' + venta_id_DA + '/moneda_id';
                    $.getJSON(url_moneda)
                    .done(function (data) {
                        $.each(datos_monedas, function(i, item) {
                            let selected = (data.moneda_id == item.id) ? 'selected' : '';
                            $('#moneda').append("<option "+selected+" value='" + item.id + "'>" + item.moneda + "</option>");
                        });
                        
                        var moneda_id = $("#moneda option:selected").val();
                        if(moneda_id == 1){
                            $('#tipo_de_cambio').prop('disabled', true);
                            $('#tipo_de_cambio').val('');
                        }else{
                            $('#tipo_de_cambio').prop('disabled',false);
                        }
                    });
                    
                    limpiarTotales();
                    let url_detalle = base_url + 'index.php/WS_venta_detalles/ws_detalle/' + venta_id_DA;
                    $.getJSON(url_detalle)
                    .done(function (data) {
                        (data).forEach(function (repo) {
                            agregarFila(repo.producto, repo.producto_id, repo.unidad_id, repo.cantidad, parseFloat(repo.precio_base * param_precios).toFixed(catidad_decimales), parseFloat(repo.precio_base * repo.cantidad * param_precios).toFixed(catidad_decimales) , repo.impuesto_bolsa, repo.tipo_igv_id, repo.descuento);
                            calcularTotales(repo.cantidad, repo.precio_base, repo.tipo_igv_id, repo.impuesto_bolsa, total_anticipo, 0);
                        });
                    });
                }
            });

            //cargamos Motivo de Nota de credito.
            cargar_motivo_tipo_documentos(tipo_documento);
        }else{
            $("#div_documento").hide();
        }
    }

    $.getJSON(base_url + 'index.php/WS_tipo_documentos/tipo_documentos')
            .done(function (data) {
                (data.tipo_documentos).forEach(function (repo) {
                    var selectedado = (repo.id == tipo_documento_defecto_id) ? 'selected' : '';
                    if(repo.id != 9){
                        $('#tipo_documento').append("<option " + selectedado + " value='" + repo.id + "'>" + repo.tipo_documento + "</option>");
                    }
            });
    });          

    $.getJSON(base_url + 'index.php/WS_monedas/monedas')
            .done(function (data) {
                (data.monedas).forEach(function (repo) {
                    $('#moneda').append("<option value='" + repo.id + "'>" + repo.moneda + "</option>");
            });
    });
    
    $('#entidad').autocomplete({
        source: base_url + 'index.php/WS_entidades/buscador_entidad',
        minLength: 2,
        select: function (event, ui) {
            $('#entidad_id').val(ui.item.id);
            $('#tipo_entidad_id').val(ui.item.tipo_entidad_id);
            $("#direccion").val(ui.item.direccion);
                        
            direccion_cliente_incial = ui.item.direccion;
            select_evento = 1;
            //para anticipos
            if($("#tipo_operacion").val() == codigo_ventas_con_anticipos){
                $("#div_anticipo").show();
                documento_id_anticipos = [];
                llenar_anticipos($("#tipo_operacion").val(), ui.item.id);
            }                                    
            //fin -- anticipos
            
            cargaDocumentoNotaCredito(ui.item.id, $('#tipo_documento').val());
            cargar_guias(ui.item.id);
        }
    });
    
    function cargar_motivo_tipo_documentos(tipo_documento, selected_ncredito = '', selected_ndedito = ''){
        if(tipo_documento == 7){
            $("#div_motivo_nota_credito").show();
            $("#div_motivo_nota_debito").hide();
            url_mnc = base_url + 'index.php/WS_tipo_ncreditos/select_all';
            $.getJSON(url_mnc)
            .done(function (data) {
                $('#tipo_ncredito').empty();
                (data).forEach(function (repo) {
                    $('#tipo_ncredito').append("<option value='" + repo.id + "'>" + repo.tipo_ncredito + "</option>");
                });
                if(selected_ncredito != ''){
                    $("#tipo_ncredito option[value='"+ selected_ncredito +"']").attr("selected", true);
                }                
            });
        }
        if(tipo_documento == 8){
            $("#div_motivo_nota_credito").hide();
            $("#div_motivo_nota_debito").show();
            url_mnc = base_url + 'index.php/WS_tipo_ndebitos/select_all';
            $.getJSON(url_mnc)
            .done(function (data) {
                $('#tipo_ndebito').empty();
                (data).forEach(function (repo) {
                    $('#tipo_ndebito').append("<option value='" + repo.id + "'>" + repo.tipo_ndebito + "</option>");
                });
            }); 
        }   
    }
    
    function cargar_guias(select_entidad_id){
        var url_select_entidad_id = base_url + 'index.php/WS_guias/select_entidad/'+select_entidad_id;        
        $.getJSON(url_select_entidad_id)
            .done(function (data) {
                $('#sel_guias').append("<option value=''>Seleccionar</option>");
                data.forEach(function (repo) {
                    $('#sel_guias').append("<option value='" + repo.id + "'>" + repo.serie + '-' + repo.numero + "</option>");
            });
        });        
    }
    
    //se tomará en consideración para exportación
    //tipo operacion...  para exportacion valor: 0200
    //tipo_entidad_id = 0....  para exportació pq sería código: 0 Según Sunat --- Empresas Del Extranjero - No Domiciliado    
    function detectorInconsistencias(tipo_igv_producto, tipo_operacion, tipo_entidad_id, array_producto_id, array_producto, array_cantidad, entidad_id, tipo_documento, adjuntar_documento){
        var tipo_entidad_id = Number(tipo_entidad_id);
        var tipo_documento_id = Number(tipo_documento);
        var tipo_igv_exportacion = 0; //tipo igv para exportación es 19
        var tipo_igv_otro = 0;        
        
        array_cantidad.forEach(function(cantidad){
            if(Number(cantidad) == 0){
                alert('Las cantidades deben ser mayor a cero (0)');
                respuesta_inconsistencia = 1;
            }
        });                
                
        if( (productos_automaticos == 0) || (productos_automaticos == null)){
            array_producto_id.forEach(function(producto_id){
                if((producto_id === undefined) || (producto_id == '')){
                    alert('Debe ingresar los productos correctamente (Debe seleccionar).');
                    respuesta_inconsistencia = 1;
                }
            });
        }else if(productos_automaticos == 1){
            array_producto.forEach(function(descripcion){
                if(descripcion == ''){
                    alert('Debe ingresar descripción al producto');
                    respuesta_inconsistencia = 1;
                }
            });
        }        
        
        tipo_igv_producto.forEach(function(tipo_igv){
            if(tipo_igv == 19){
                tipo_igv_exportacion = 1;
            }else{
                tipo_igv_otro = 1;
            }
        });
        
        if((entidad_id == null) || (entidad_id == '')){
            alert('Debe ingresar un cliente');
            respuesta_inconsistencia = 1;
        }
        
        if(tipo_igv_producto.length == 0){            
            alert('Debe ingresar al menos 1 producto.');
            respuesta_inconsistencia = 1;
        }
        
        if( (tipo_igv_exportacion == 1) && (tipo_igv_otro == 1) ){
            alert('No puede haber tipo de IGV exportación y otros tipos de IGV en los productos.');
            respuesta_inconsistencia = 1;
        }        

        if( ((tipo_igv_exportacion == 1) && (tipo_igv_otro == 0)) && (tipo_operacion != '0200') && (tipo_entidad_id != 3)  ){
            alert('Para exportación: \n -El tipo de Operación debe ser: exportación. \n -El Cliente debe ser exportación (Empresas Del Extranjero - No Domiciliado)');
            respuesta_inconsistencia = 1;
        }
        if( ((tipo_igv_exportacion == 1) && (tipo_igv_otro == 0)) && (tipo_operacion == '0200') && (tipo_entidad_id != 3)  ){
            alert('Para exportación: \n -El Cliente debe ser exportación (Empresas Del Extranjero - No Domiciliado)');
            respuesta_inconsistencia = 1;
        }
        if( ((tipo_igv_exportacion == 1) && (tipo_igv_otro == 0)) && (tipo_operacion != '0200') && (tipo_entidad_id == 3)  ){
            alert('Para exportación: \n -El tipo de Operación debe ser: exportación.');
            respuesta_inconsistencia = 1;
        }                
                
        if( (tipo_igv_exportacion == 0) && (tipo_operacion == '0200') && (tipo_entidad_id == 3)  ){
            alert('Para exportación: \n -Los productos deben tener tipo IGV de exportación.');
            respuesta_inconsistencia = 1;
        }
        if( (tipo_igv_exportacion == 0) && (tipo_operacion != '0200') && (tipo_entidad_id == 3)  ){
            alert('Para exportación: \n -Los productos deben tener tipo IGV de exportación. \n -El tipo de Operación debe ser: exportación.');
            respuesta_inconsistencia = 1;
        }
        if( (tipo_igv_exportacion == 0) && (tipo_operacion == '0200') && (tipo_entidad_id != 3)  ){
            alert('Para exportación: \n -Los productos deben tener tipo IGV de exportación. \n -El Cliente debe ser exportación (Empresas Del Extranjero - No Domiciliado)');
            respuesta_inconsistencia = 1;
        }
        
        //Para entidad dni con boleta, ruc con factura
        //tipo_documento  1 factura --  3 boleta
        //tipo_entidad_id 1 DNI --  2 RUC
        if(operacion_action == '1'){
            if( (tipo_operacion == '0101') && (tipo_documento_id == 1) && (tipo_entidad_id == 1) ){
                alert('La factura no puede ser con DNI');
                respuesta_inconsistencia = 1;
            }

            if( (tipo_operacion == '0101') && (tipo_documento_id == 3) && (tipo_entidad_id == 2) ){
                alert('La boleta no puede ser con RUC');
                respuesta_inconsistencia = 1;
            }
        }

        if( ((tipo_documento_id == 7) || (tipo_documento_id == 8)) && ((adjuntar_documento == '') || adjuntar_documento == null)){
            alert('Para Nota de Crédito o Débito debe adjuntar un documento.');
            respuesta_inconsistencia = 1;
        }
        return respuesta_inconsistencia;
    }
    
    function agregarFila(producto, producto_id, unidad_id, cantidad, importe, total, impuesto_bolsa, tipo_igv_id, descuento_lineal){
        producto = (producto == undefined) ? '' : producto;
        producto_id = (producto_id == undefined) ? '' : 'value = ' + producto_id;
        //unidad = (unidad == undefined) ? '' : 'value = ' + unidad;
        cantidad = (cantidad == undefined) ? 'value = ' + 1 : 'value = ' + cantidad;
        importe = (importe == undefined) ? '' : 'value = ' + parseFloat(importe).toFixed(catidad_decimales);
        total = (total == undefined) ? 'value = ' + 0.00 : 'value = ' + parseFloat(total).toFixed(catidad_decimales);
        impuesto_bolsa = (impuesto_bolsa == null) ? '' : 'checked';
        descuento_lineal = (descuento_lineal == undefined) ? '' : 'value = ' + descuento_lineal;
        var fila = '<tr class="cont-item fila_generada" >';
        fila += '<td class="col-sm-4" style="border:0;"><input value = "' + producto + '" class="form-control descripcion-item" id="descripcion" name="descripcion[]" required=""><div id="data_item"><input class="val-descrip" '+producto_id+' type="hidden" name="item_id[]" id="item_id"></div></td>';
        
        
        fila += '<td style="border:0;">';
        fila += '<select class="form-control" id="unidad" name="unidad[]">';
        $.each(unidades_activas, function(i, item) {
            selected_unidad = (unidad_id == item.id) ? 'selected' : '';
            fila += '<option ' + selected_unidad + ' value="' + item.id + '">' + item.unidad + '</option>';
            //fila += '<option value="' + item.id + '">' + item.unidad + '</option>';
        });
        fila += '</select>'
        fila += '</td>';        
        
        
        fila += '<td style="border:0;"><input ' + cantidad + ' type="number" id="cantidad" name="cantidad[]" class="form-control cantidad" ></td>';        
        
        fila += '<td style="border:0;">';
        fila += '<select class="form-control" id="tipo_igv" name="tipo_igv[]">';
        $.each(tipo_igv, function(i, item) {
            selected_igv = (tipo_igv_id == item.id) ? 'selected' : '';
        fila += '<option ' + selected_igv + ' value="' + item.id + '">' + item.tipo_igv + '</option>';
        });
        fila += '</select>'
        fila += '</td>';
        
        
        fila += '<td style="border:0;"><input ' + importe + ' type="number" id="importe" name="importe[]" required="" class="form-control importe derecha_text"></td>';
        fila += '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" >';
        fila += '<input type="hidden" id="icbper"  name="icbper[]" class="form-control"  readonly="" >';
        fila += '<td style="border:0;"><input ' + total + ' type="text" id="total" name="total[]" class="form-control totalp derecha_text" readonly=""></td>';
        fila += '<td style="border:0;"><input ' + impuesto_bolsa + ' type="checkbox" value="1" id="chekBolsa" name="chekBolsa[]"></td>';
        //fila += '<td style="border:0;"><input ' + descuento_lineal + ' class="form-control input-sm descuento_lineal" type="text" id="descuento_lineal" name="descuento_lineal[]"></td>';
        fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
        fila += '</tr>';

        $("#tabla").css("display","block");
        $("#tabla tbody").append(fila);    
    }

    //con_anticipo 0 no biene del evento del boton btn_adjuntar_anticipo, por tanto no se recalcula
    function calcularTotales(cantidad, precio, tipo_igv, chequeado_bolsa, anticipo = 0, con_anticipo = 0){
        var bolsa_actual = 0;
        var bolsa_anterior = 0;
        if(chequeado_bolsa){
            bolsa_anterior = ($("#total_bolsa").val() == '') ? 0 : parseFloat($("#total_bolsa").val());
            bolsa = (bolsa_anterior + parseFloat(valor_impuesto_bolsa*cantidad)).toFixed(catidad_decimales);
            $("#total_bolsa").val(bolsa);
            bolsa_actual = parseFloat($("#total_bolsa").val());
            show_totales($("#total_bolsa").val(), 'div_total_bolsa');
        }
        
        switch(parseInt(tipo_igv)) {
            //IGV
            case 1:
                total_ = ($("#total_gravada").val() == '') ? 0 : parseFloat($("#total_gravada").val());
                
                total_gravada = ($("#total_gravada").val() == '') ? 0 : parseFloat($("#total_gravada").val());                
                total_a_pagar = ($("#total_a_pagar").val() == '') ? 0 : parseFloat($("#total_a_pagar").val());
                total_igv = ($("#total_igv").val() == '') ? 0 : parseFloat($("#total_igv").val());

                if(con_anticipo == 1){
                    total_anticipo_parcial = ($("#PrepaidAmount").val() == '') ? 0 : parseFloat($("#PrepaidAmount").val());
                    $("#PrepaidAmount").val((total_anticipo_parcial + anticipo).toFixed(catidad_decimales));
                    total_anticipo = total_anticipo + anticipo
                }                
                
                $("#total_gravada").val((total_gravada + precio*cantidad).toFixed(catidad_decimales));
                $("#total_igv").val((total_igv + precio*cantidad*porcentaje_valor_igv).toFixed(catidad_decimales));
                igv_final = parseFloat($("#total_igv").val());
                
                $("#total_a_pagar").val(( igv_final - total_igv + total_a_pagar + precio*cantidad + bolsa_actual - bolsa_anterior - (anticipo)).toFixed(catidad_decimales));
                
                show_totales($("#total_gravada").val(), 'div_total_gravada');
                show_totales($("#total_igv").val(), 'div_total_igv');
                break;  
                
            //Exonerado
            case 9:
                total_a_pagar = ($("#total_a_pagar").val() == '') ? 0 : parseFloat($("#total_a_pagar").val());
                total_exonerada = ($("#total_exonerada").val() == '') ? 0 : parseFloat($("#total_exonerada").val());
                
                $("#total_exonerada").val((total_exonerada + precio*cantidad).toFixed(catidad_decimales));
                $("#total_a_pagar").val((total_a_pagar + precio*cantidad + bolsa_actual - bolsa_anterior).toFixed(catidad_decimales));
                show_totales($("#total_exonerada").val(), 'div_total_exonerada');
                break;

            //Inafecto                        
            case 11:
                total_a_pagar = ($("#total_a_pagar").val() == '') ? 0 : parseFloat($("#total_a_pagar").val());
                total_inafecta = ($("#total_inafecta").val() == '') ? 0 : parseFloat($("#total_inafecta").val());
                
                $("#total_inafecta").val((total_inafecta + precio*cantidad).toFixed(catidad_decimales));
                $("#total_a_pagar").val((total_a_pagar + precio*cantidad + bolsa_actual - bolsa_anterior).toFixed(catidad_decimales));
                show_totales($("#total_inafecta").val(), 'div_total_inafecta');
                break;
                
            //Exportación                        
            case 19:
                total_a_pagar = ($("#total_a_pagar").val() == '') ? 0 : parseFloat($("#total_a_pagar").val());
                total_exportacion = ($("#total_exportacion").val() == '') ? 0 : parseFloat($("#total_exportacion").val());

                $("#total_exportacion").val((total_exportacion + precio*cantidad).toFixed(catidad_decimales));                
                $("#total_a_pagar").val((total_a_pagar + precio*cantidad + bolsa_actual - bolsa_anterior).toFixed(catidad_decimales));
                show_totales($("#total_exportacion").val(), 'div_total_exportacion');
                break;

            //Gratuitas  
            default:
                //total_a_pagar = ($("#total_a_pagar").val() == '') ? 0 : parseFloat($("#total_a_pagar").val());
                total_gratuita = ($("#total_gratuita").val() == '') ? 0 : parseFloat($("#total_gratuita").val());

                //$("#total_a_pagar").val((total_a_pagar + precio*cantidad).toFixed(catidad_decimales));
                $("#total_gratuita").val((total_gratuita + precio*cantidad + bolsa_actual - bolsa_anterior).toFixed(catidad_decimales));
                show_totales($("#total_gratuita").val(), 'div_total_gratuita');
                break;
        }
    }
    
    function show_totales(total, nombre){
        if(total > 0){ 
            $("#"+nombre).show();
        }else{
            $("#"+nombre).hide();
        }
    }
            
    function limpiarTotales(){
        $("#total_gravada").val('');
        $("#total_igv").val('');
        $("#total_gratuita").val('');
        $("#total_exportacion").val('');
        $("#total_inafecta").val('');
        $("#total_exonerada").val('');
        $("#total_bolsa").val('');
        $("#total_a_pagar").val('');
    }        
    
    function pagos_and_cuotas(operacion_action, venta_id){            
        var data = {};
        data['operacion']               = operacion_action;
        data['venta_id']                = venta_id;
        data['monto']                   = $("#total_a_pagar").val();
        data['nota']                    = $("#nota").val();
        data['modo_pago_id']            = $("#modo_pago").val();
        data['fecha_pago']              = $("#fecE").val();

        if($('#forma_pago').val() == '1'){
            var url_pago = base_url + 'index.php/cobros/operaciones';
            $.getJSON(url_pago, data)
            .done(function(datos, textStatus, jqXHR){
            });
        }else if($('#forma_pago').val() == '2'){
            if(($("#numero_cuotas").val() == '') || ($("#numero_cuotas").val() == 0)){
                alert("Para venta al crédito las cuotas deben ser mayor a 0");
                return false;
            }else{
                var data_cuota = {};
                data_cuota['venta_id']  = venta_id;
                var array_valor_cuota   = [];
                var array_fecha_cuota   = [];
                $('#tabla_credito tbody tr').each(function(){
                    let valor_cuota = $(this).find('td').eq(1).children().val();
                    let fecha_cuota = $(this).find('td').eq(2).children().val();

                    array_valor_cuota.push(valor_cuota);
                    array_fecha_cuota.push(fecha_cuota);

                    total_pago_cuotas += valor_cuota;
                });

                data_cuota['monto']         = array_valor_cuota;
                data_cuota['fecha_cuota']   = array_fecha_cuota;                    

                var url_pago = base_url + 'index.php/cuotas/operaciones';
                $.getJSON(url_pago, data_cuota)
                .done(function(datos, textStatus, jqXHR){                        
                });                    
            }
        }
    }
    
    function detectorInconsistencias_cuotas(array_valor_cuota, array_fecha_cuota, total_pago_cuotas){
            if(array_valor_cuota == ''){
                alert('Debe programar cuotas de pago.');
                respuesta_inconsistencia_cuotas = 1;
            }
        
            array_valor_cuota.forEach(function(valor_cuota){
                if((Number(valor_cuota) == 0) || (valor_cuota == '')){
                    alert('Valores de cuota/s incorrectos.');
                    respuesta_inconsistencia_cuotas = 1;
                }
            });

            array_fecha_cuota.forEach(function(fecha_cuota){
                if((fecha_cuota === undefined) || (fecha_cuota == '')){
                    alert('Datos de Fecha incorrectos');
                    respuesta_inconsistencia_cuotas = 1;
                }
            });
            return respuesta_inconsistencia_cuotas;
    }

    function agregarTablaCredito(numero_cuotas, valor_cuota){
        $("#tabla_credito tbody").empty();
        $("#tabla_credito").css("display","block");
        for (var i = 0; i < numero_cuotas; i++) {
            var fila = '<tr class="cont-item fila_generada" >';
            fila += '<td style="border:0;">'+ (i+1) +'</td>';
            fila += '<td style="border:0;"><input type="text" class="form-control" id="pago" name="pago[]" value="'+valor_cuota.toFixed(2)+'"></td>';
            fila += '<td style="border:0;"><input type="date" class="form-control cantidad" id="cantidad" name="cantidad[]"></td>';
            fila += '</tr>';
            $("#tabla_credito tbody").append(fila);
        }
    }
    
    function agregarTablaCreditoFila(orden, valor_cuota, fecha){        
        var credito_fila = '<tr class="cont-item fila_generada" >';
        credito_fila += '<td style="border:0;">' + orden + '</td>';
        credito_fila += '<td style="border:0;"><input type="text" class="form-control" id="pago" name="pago[]" value="' + valor_cuota + '"></td>';
        credito_fila += '<td style="border:0;"><input type="date" class="form-control cantidad" id="cantidad" name="cantidad[]" value="' + fecha + '"></td>';
        credito_fila += '</tr>';
        $("#tabla_credito tbody").append(credito_fila);
    }
    
</script>