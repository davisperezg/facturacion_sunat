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
<div class="container-fluid">
    <h2 align="center">Kardex - Promedio Ponderado</h2>        
    <div class="row">
        <div class="col-xs-1">
        </div> 
        <div class="col-xs-5" style="padding-bottom: 10px">
            <a id="btn_actulizar_datos" class="btn btn-success">Actualizar Datos</a>
            Datos actualizados al:<span id="txt_datos_actualizados_al"></span>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-1">
        </div> 
        <div class="col-xs-5">
            <input type="text" name="producto" id="producto" class="form form-control" placeholder="Buscar por producto" />
            <input type="hidden" id="producto_id" />
        </div>
        <div class="col-xs-2">
            <input type="date" class="form form-control" name="fec_init" id="fec_init" />
        </div>    
        <div class="col-xs-2">
            <input type="date" class="form form-control" name="fec_fint" id="fec_fint" />
        </div>
        <div class="col-xs-1">
            <button class="btn btn-default" type="button" id="btn_buscar_producto"><span class="glyphicon glyphicon-search"></span></button>   
        </div>
    </div>    
</div>
<br><br>
<div class="container">
    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th rowspan="2">N.</th>
                    <th rowspan="2">Fecha</th>
                    <th rowspan="2">Operación</th>
                    <th rowspan="2">T.D.</th>
                    <th rowspan="2">Documento</th>                                        
                    <th colspan="3"><div align="center">Entradas</div></th>
                    <th colspan="3"><div align="center">Salidas</div></th>
                    <th colspan="3"><div align="center">Saldos</div></th>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <th>Valor<br>Unitario</th>
                    <th>Valor<br>Total</th>
                    <th>Cantidad</th>
                    <th>Valor<br>Unitario</th>
                    <th>Valor<br>Total</th>
                    <th>Cantidad</th>
                    <th>Valor<br>Unitario</th>
                    <th>Valor<br>Total</th>                    
                </tr>
            </thead>
            <tbody role="rowgroup">                
            </tbody>
        </table>
    </div>
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url    =   '<?php echo base_url();?>';
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var catidad_decimales = datos_configuracion.catidad_decimales;
    
    $(document).ready(function(){
        $('#producto').autocomplete({
            source: base_url + 'index.php/WS_productos/buscador_producto',
            minLength: 2,
            select: function (event, ui) {
                $('#producto_id').val(ui.item.id);
            }
        });
        
        $("#btn_actulizar_datos").click(function (){
            var url_kardex = base_url + 'index.php/WS_kardex_promedio/actualizar_datos';
            $.getJSON(url_kardex)
            .done(function (data) {
                toast('success', 2500, 'Datos actualizados correctamente');                
                var url_actualizacion_kardex = base_url + 'index.php/WS_variables_diversas/ultima_actualizacion_kardex';
                $.getJSON(url_actualizacion_kardex)
                .done(function (datos) {                    
                    $("#txt_datos_actualizados_al").text(datos.fecha_hora);
                });                    
            });
        });
        
        $("#btn_buscar_producto").click(function(){
            if(($("#fec_init").val() == '') || ($("#fec_fint").val() == '')){
                alert('Debe ingresar correctamente las fechas');
                return false;
            }
                        
            $("#tabla_id > tbody").remove();
            var url_l = base_url + 'index.php/WS_kardex_promedio/select/' + $("#producto_id").val() + '/' + $("#fec_init").val() + '/' + $("#fec_fint").val();
            $.getJSON(url_l)
                .done(function (data) {
                    var numero_orden = 1;
                    (data).forEach(function (repo) {
                        agregarFila(numero_orden, repo.producto_id, repo.compra_venta, repo.tipo_documento_id, repo.documento_id, repo.numero, repo.serie, repo.fecha, repo.entrada_cantidad, repo.entrada_costo, repo.salida_cantidad, repo.salida_costo, repo.final_cantidad, repo.final_costo, repo.final_total);
                        numero_orden ++;
                    });
            });
        });
        
        //Perfil - Detalle VENTA
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
        
        //Perfil - Detalle COMPRA
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
        
        //Perfil - Detalle Movimiento
        $("#tabla_id").on('click', '.btn_perfil_movimiento', function(){
            var producto_movimiento_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/producto_movimientos/modal_detalle/';
            $("#myModal").load(ruta_url);
            ruta_url_item = base_url + 'index.php/WS_producto_movimientos/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + producto_movimiento_id;
            $.getJSON(ruta_url_item)
            .done(function (data){
                console.log(data);
                console.log('producto:'+data.movimientos[0].producto);
                $('#fecha_insert').text(data.movimientos[0].fecha_insert);
                $('#modal_producto').text(data.movimientos[0].producto);
                $('#modal_movimiento').text(movimiento_productos[data.movimientos[0].movimiento]);
                $('#cantidad').text(data.movimientos[0].cantidad);
                $('#motivo').text(data.movimientos[0].motivo);                        
            });
        });
    });
    
    var entrada_final = '';
    var salida_final = '';
    var numero_documento = '';
    function agregarFila(numero_orden, producto_id, compra_venta, tipo_documento_id, documento_id, numero, serie, fecha, entrada_cantidad, entrada_costo, salida_cantidad, salida_costo, final_cantidad, final_costo, final_total){
                
        entrada_final = (entrada_cantidad != null && entrada_costo != null) ? parseFloat(entrada_cantidad*entrada_costo).toFixed(2) : '';
        entrada_cantidad = (entrada_cantidad != null) ? parseFloat(entrada_cantidad).toFixed(2) : '';
        entrada_costo = (entrada_costo != null) ? parseFloat(entrada_costo).toFixed(2) : '';        
        
        salida_final = (salida_cantidad != null && salida_costo != null) ? parseFloat(salida_cantidad*salida_costo).toFixed(2) : '';
        salida_cantidad = (salida_cantidad != null) ? parseFloat(salida_cantidad).toFixed(2) : '';
        salida_costo = (salida_costo != null) ? parseFloat(salida_costo).toFixed(2) : '';

        numero_documento = ((compra_venta == 0) || (compra_venta == 3)) ? '' : serie + '-' + numero;
                
        switch(compra_venta){
            case '0':
                clase_detalle = '';
            break;

            case '1':
                clase_detalle = 'btn btn-default btn-xs btn_perfil_compra';
            break;

            case '2':
                clase_detalle = 'btn btn-default btn-xs btn_perfil_venta';
            break;
            
            case '3':
                clase_detalle = 'btn btn-default btn-xs btn_perfil_movimiento';
            break;
        }
        
        var fila = '<tr class="seleccionado tabla_fila">';
        fila += '<td align="center"><a id="'+documento_id+'" class="'+clase_detalle+'" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+fecha+'</td>';
        fila += '<td>'+operacion(compra_venta)+'</td>';
        fila += '<td>'+datos_tipo_documento(tipo_documento_id)+'</td>';
        fila += '<td>'+numero_documento+'</td>';
        fila += '<td class="derecha_text">'+entrada_cantidad+'</td>';
        fila += '<td class="derecha_text">'+entrada_costo+'</td>';
        fila += '<td class="derecha_text">'+entrada_final+'</td>';        
        
        fila += '<td class="derecha_text">'+salida_cantidad+'</td>';
        fila += '<td class="derecha_text">'+salida_costo+'</td>';
        fila += '<td class="derecha_text">'+salida_final+'</td>';
        
        fila += '<td class="derecha_text">'+parseFloat(final_cantidad).toFixed(2)+'</td>';
        fila += '<td class="derecha_text">'+parseFloat(final_costo).toFixed(2)+'</td>';
        fila += '<td class="derecha_text">'+parseFloat(final_total).toFixed(2)+'</td>';  
        
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }
    
    function operacion(numero){
        var resultado = ''
        switch (numero) {
            case '0':
              resultado = 'Stock Inicial';
              break;
            case '1':
              resultado = 'Compras';
              break;
            case '2':
              resultado = 'Ventas';
              break;
              
            case '3':
              resultado = 'Movimiento Almacén';
              break;            
        }
        return resultado;
    }
    
    var ls_tipo_documentos = JSON.parse(localStorage.getItem("tipo_documentos"));
    var abreviado_factura = '';
    var abreviado_boleta = '';    
    var abreviado_nota_credito = '';
    var abreviado_nota_debito = '';
    
    $.each(ls_tipo_documentos, function(i, item) {        
        if(item.id == 1){
            abreviado_factura = item.abreviado;
        }                        
        if(item.id == 3){
            abreviado_boleta = item.abreviado;
        }                        
        if(item.id == 7){
            abreviado_nota_credito = item.abreviado;
        }                        
        if(item.id == 8){
            abreviado_nota_debito = item.abreviado;
        }                        
    });
    
    function datos_tipo_documento(tipo_documento_id){
        var resultado = ''
        switch (tipo_documento_id) {
            case '1':
              resultado = abreviado_factura;
              break;
            case '3':
              resultado = abreviado_boleta;
              break;
            case '7':
              resultado = abreviado_nota_credito;
              break;            
            case '8':
              resultado = abreviado_nota_debito;
              break;            
        }
        return resultado;
    }
        
</script>