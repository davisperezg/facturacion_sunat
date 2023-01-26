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

<div class="container">
    <div class="row">
        <div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>
        <div class="col-md-8" style="text-align: center">FORMA DE PAGOS</div>
        <div class="col-md-2"></div>
    </div>

    <div class="row"> 
        <div id="panel_fomulario" class="panel panel-primary">
            <div class="panel-heading" >                    
                <div class="row">
                    <div class="col-md-4"><span id="documento"></span></div>
                    <div class="col-md-6"><span id="entidad"></span></div>
                    <div class="col-md-2"><span id="total_a_pagar"></span></div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-2">
                        <label class="control-label">Forma de pago:</label>
                        <select class="form-control" id="forma_pago">
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <label class="control-label" id="lbl_pago">Pago:</label>
                        <input class="form-control" type="text" name="pago" id="pago" />
                    </div>
                    <div class="col-xs-2">
                        <label class="control-label">Modo de pago:</label>
                        <select class="form-control" id="modo_pago">
                        </select>
                    </div>                    
                    <div class="col-xs-2">
                        <label class="control-label">Fecha pago:</label>
                        <input class="form-control" id="fecha_pago" name="fecha_pago" type="text" name="pago" id="pago" />
                    </div>                        
                </div>

                <div class="row">
                    <div class="col-xs-3">
                        <label class="control-label">Nota:</label>
                        <textarea class="form-control" id="nota" name="nota"></textarea>
                    </div>                    
                </div>

                <div id="div_credito">
                    <hr>
                    <div class="row">
                        <div class="col-xs-2">
                            <label class="control-label">N. Cuotas:</label>
                            <input class="form-control" type="number" id="numero_cuotas" name="numero_cuotas"/>
                        </div>
                        <div class="col-xs-2">
                            <br>
                            <button class="btn btn-info" id="btn_configuracion_pago">Programar Cuotas.</button>
                        </div>
                    </div>

                    <div class="row">
                        <table id="tabla" class="table tabla_items" style="display:none" border="0">
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

            <div class="panel-footer">
                <div align="center">
                    <input type="button" id="btn_pago" value="Guardar Pago" class="btn btn-lg btn-success" />
                </div>                    
            </div>
        </div>
    </div>
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript"> 
    
    var base_url = '<?PHP echo base_url();?>';                
    var params_url = window.location.hash;    
    var venta_id    =   params_url.substring(1, params_url.length);
    var operacion_action = 1; //solo trabajará para factura y boletas en esta vista.            
    var total_a_pagar = 0;
    var valor_cuota = 0;
    var respuesta_inconsistencia = 0;
    var total_pago_cuotas = 0;

    $("#div_credito").hide();
    $("#img_atras").attr("src", base_url + "images/atras.png");
    $("#enlace_atras").attr("href", base_url + "index.php/ventas/nuevo/#" + venta_id  + '/' + operacion_action + '/' + 0);    
    
    var url_save = base_url + 'index.php/WS_ventas/ws_cabecera/' + venta_id;
    $.getJSON(url_save)
    .done(function(datos, textStatus, jqXHR){        
        $("#documento").text(datos.tipo_documento + ': ' + datos.serie + '-' + datos.numero);
        $("#entidad").text(datos.entidad);
        $("#total_a_pagar").text('Total a pagar: '+datos.simbolo_moneda + ' ' +datos.total_a_pagar);
        $("#pago").val(datos.total_a_pagar);
        $("#pago").attr('readonly', true);
        
        total_a_pagar = datos.total_a_pagar;
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

    $(document).ready(function () {
        var today = new Date();                
        var dd = today.getDate(); 
        var mm = today.getMonth() + 1;   
        var yyyy = today.getFullYear();

        if (dd < 10) { 
            dd = '0' + dd; 
        } 
        if (mm < 10) { 
            mm = '0' + mm; 
        } 
        var today = dd + '-' + mm + '-' + yyyy;
        $('#fecha_pago').val(today);
        $('#fecha_pago').datepicker();

        $('#forma_pago').on('click', function(){
            if($('#forma_pago').val() == '2'){
                $("#pago").attr('readonly', false);
                $("#pago").val('');
                $("#lbl_pago").text('Pago Inicial:');
                $("#div_credito").show();
            }else{
                $("#pago").attr('readonly', true);
                $("#pago").val(total_a_pagar);
                $("#lbl_pago").text('Pago:');
                $("#div_credito").hide();
            }
        });
        
        $('#btn_pago').on('click', function(){
            
            var data = {};
            data['operacion']               = operacion_action;
            data['venta_id']                = venta_id;
            data['monto']                   = $("#pago").val();
            data['nota']                    = $("#nota").val();
            data['modo_pago_id']            = $("#modo_pago").val();
            data['fecha_pago']              = $("#fecha_pago").val();                                    
            
            if($('#forma_pago').val() == '1'){
                var url_pago = base_url + 'index.php/cobros/operaciones';
                $.getJSON(url_pago, data)
                .done(function(datos, textStatus, jqXHR){
                    window.location.href = base_url + 'index.php/ventas/index/' + operacion_action;
                });
            }else if($('#forma_pago').val() == '2'){
                if(($("#numero_cuotas").val() == '') || ($("#numero_cuotas").val() == 0)){
                    alert("Para venta al crédito las cuotas deben ser mayor a 0");
                }else{
                    if(($("#pago").val() != '') && ($("#pago").val() > 0)){
                        var url_pago = base_url + 'index.php/cobros/operaciones';
                        $.getJSON(url_pago, data)
                        .done(function(datos, textStatus, jqXHR){
                            console.log('guardo pago inicial');
                        });
                    }
                    
                    /////--actualizo tabla ventas
                    var forma_pago_id = 2; // actualizo a forma: credito
                    $.getJSON(base_url + 'index.php/WS_ventas/actualizacion_forma_pago/' + venta_id + '/' + forma_pago_id)
                    .done(function (data) {
                    });
                    ////////////////////////

                    var data_cuota = {};
                    data_cuota['venta_id']  = venta_id;
                    var array_valor_cuota   = [];
                    var array_fecha_cuota   = [];
                    $('#tabla tbody tr').each(function(){
                        let valor_cuota = $(this).find('td').eq(1).children().val();
                        let fecha_cuota = $(this).find('td').eq(2).children().val();

                        array_valor_cuota.push(valor_cuota);
                        array_fecha_cuota.push(fecha_cuota);

                        total_pago_cuotas += valor_cuota;
                    });

                    respuesta_inconsistencia = 0;
                    respuesta_inconsistencia = detectorInconsistencias(array_valor_cuota, array_fecha_cuota, $("#pago").val(), total_pago_cuotas);
                    if(respuesta_inconsistencia == 1)return false;

                    data_cuota['monto']         = array_valor_cuota;
                    data_cuota['fecha_cuota']   = array_fecha_cuota;                    

                    var url_pago = base_url + 'index.php/cuotas/operaciones';
                    $.getJSON(url_pago, data_cuota)
                    .done(function(datos, textStatus, jqXHR){
                        toast('success', 2500, 'Operación ingresada correctamente');
                        window.location.href = base_url + 'index.php/ventas/index/' + operacion_action;
                    });                    
                }
            }
        });
        
        $("#btn_configuracion_pago").on('click', function(){
            $("#tabla tbody").empty();
            $("#tabla").css("display","block");

            var n = $("#numero_cuotas").val();            
            valor_cuota = (total_a_pagar - $("#pago").val())/n ;            
            
            for (var i = 0; i < n; i++) {
                var fila = '<tr class="cont-item fila_generada" >';
                fila += '<td style="border:0;">'+ (i+1) +'</td>';
                fila += '<td style="border:0;"><input type="text" class="form-control" name="unidad[]" value="'+valor_cuota.toFixed(2)+'"></td>';
                fila += '<td style="border:0;"><input type="date" class="form-control cantidad" id="cantidad" name="cantidad[]"></td>';
                fila += '</tr>';
                $("#tabla tbody").append(fila);                
                console.log(i);
            }
        });
        
        function detectorInconsistencias(array_valor_cuota, array_fecha_cuota, pago, total_pago_cuotas){
            if(array_valor_cuota == ''){
                alert('Debe programar cuotas de pago.');
                respuesta_inconsistencia = 1;
            }
        
            array_valor_cuota.forEach(function(valor_cuota){
                if((Number(valor_cuota) == 0) || (valor_cuota == '')){
                    alert('Valores de cuota/s incorrectos.');
                    respuesta_inconsistencia = 1;
                }
            });

            array_fecha_cuota.forEach(function(fecha_cuota){
                if((fecha_cuota === undefined) || (fecha_cuota == '')){
                    alert('Datos de Fecha incorrectos');
                    respuesta_inconsistencia = 1;
                }
            });
            
            if(pago == ''){
                alert('El pago inicial debe ser mayor o igual a cero (0), luego debe:\n Re-programar el monto de las cuotas.');
                respuesta_inconsistencia = 1;
            }
            
            if((total_pago_cuotas + $("#pago").val()) > total_a_pagar){
                var x = confirm('total_a_pagar:'+total_a_pagar+'\npago_inicial'+$("#pago").val()+'\ntotal_pago_cuotas'+total_pago_cuotas+'\nCuidado: \nEl monto de las cuotas mas el pago inicial, superan el pago total del comprobante. \n -Desea continuar?');
                if (!x){
                    respuesta_inconsistencia = 1;
                }
            }
            return respuesta_inconsistencia;
        }
    });    

</script>