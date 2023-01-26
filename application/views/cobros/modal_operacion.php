<style>
    ul.ui-autocomplete {
        z-index: 1100;
    }
</style>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Nuevo Pago</h4>
        </div>
        <div class="modal-body">
                
            <div class="form-group">
                <label>Cliente</label>
                <input type="text" class="form-control input-sm" id="entidad_modal" name="entidad_modal" placeholder="Cliente">
                <input type="hidden"  name="entidad_id_modal" id="entidad_id_modal" >                           
            </div>

            <div class="row">
                <div class="form-group col-xs-6">
                    <label>Dócumentos al crédito impagos</label>
                    <select class="form-control" id="comprobantes" name="comprobantes">
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-xs-4">
                    <label>Fecha</label>
                    <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" />
                </div>
            </div>            
            
            <div class="row">
                <div class="form-group col-xs-4">
                    <label>Pago</label>
                    <input name="monto" id="monto" type="text" class="form-control" />
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-xs-4">
                    <label>Modo Cobro:</label>
                    <select class="form-control input-sm" name="modo_pago_modal" id="modo_pago_modal">
                    </select>
                </div>
            </div>            
            
            <div class="form-group">
                <label>Nota</label>
                <textarea id="nota" name="nota" class="form-control"></textarea>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_cobro">Guardar</button>
            <input type="hidden" id="modal_cobro_id"/>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">    
    var base_url = '<?php echo base_url();?>';
    
    var ls_modo_pago = JSON.parse(localStorage.getItem("modo_pagos"));
    $('#modo_pago_modal').append("<option value=''>Seleccionar</option>");
    $.each(ls_modo_pago, function(i, item) {        
        if(item.id != 9){
            $('#modo_pago_modal').append($('<option>', {
                value: item.id,
                text: item.modo_pago
            }));        
        }    
    });
    
    $(document).ready(function(){        
        $('#entidad_modal').autocomplete({
            source: base_url + 'index.php/WS_ventas/buscador_entidad',
            minLength: 2,
            select: function (event, ui) {
                $('#entidad_id_modal').val(ui.item.id);
                
                var rr = base_url + 'index.php/WS_cobros/documentos_impagos/'+ui.item.id;
                console.log('rr:'+rr);
                $.getJSON(rr)
                    .done(function (data) {
                        (data).forEach(function (repo) {
                            $('#comprobantes').append($('<option>', {
                                value: repo.id,
                                text: repo.serie + '-' + repo.numero
                            }));
                        });
                });
            }
        });
        
        $("#btn_guardar_cobro").on('click', function(){
            if(  ($('#entidad_id_modal').val() == '') ||  ($('#comprobantes').val() == '') || ($('#modo_pago_modal').val() == '') || ($('#monto').val() == '') || ($('#fecha_pago').val() == '')){
                toast('Error', 1500, 'Falta ingresar datos');
                return false;
            }            

            var data = {
                venta_id:$('#comprobantes').val(),
                modo_pago_id:$('#modo_pago_modal').val(),
                monto:$('#monto').val(),
                fecha_pago:$('#fecha_pago').val(),
                nota:$('#nota').val(),
                cobro_id:$('#modal_cobro_id').val()
            };

            var url_save = base_url + 'index.php/cobros/operaciones';
            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    console.log(datos+'resultado');
                    toast('success', 1500, 'Pago ingresado correctamente');
                    $("#myModal").modal('hide');
                        
                    $("#tabla_id > tbody").remove();
                    $("#lista_id_pagination > li").remove();
                    carga_inicial();
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " +  textStatus );
                    }
                });                   
        });
        
    });    
</script>
