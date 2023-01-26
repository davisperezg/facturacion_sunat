<style>
    ul.ui-autocomplete {
        z-index: 1100;
    }
</style>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title">Registro Movimiento Productos</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-8">
                    <input type="text" id="txt_producto" placeholder="Producto" class="form-control input-lg">
                    <input type="hidden" id="txt_producto_id">
                </div>                
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8" >
                    <select class="form-control form-control-lg" id="sel_movimiento">
                    </select>
                </div>                
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8">
                    <input type="text" id="cantidad" placeholder="Cantidad" class="form-control input-lg">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8">
                    <textarea class="form-control" placeholder="Motivo" id="motivo" rows="3"></textarea>
                </div>                
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary btn-lg" id="btn_guardar">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url                = '<?php echo base_url();?>';
    var url_save = base_url + 'index.php/producto_movimientos/operaciones';
    
    $(document).ready(function(){        
        $("#btn_guardar").on('click', function(){
            if(($('#txt_producto').val() == '') || ($('#txt_producto_id').val() == '') || ($('#sel_movimiento').val() == '') || ($('#cantidad').val() == '') || ($('#motivo').val() == '')){
                toast('Error', 1500, 'Falta ingresar datos');
                return false;
            }

            var data = {
                producto_id:$('#txt_producto_id').val(),
                movimiento:$('#sel_movimiento').val(),
                cantidad:$('#cantidad').val(),
                motivo:$('#motivo').val()
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Producto ingresado correctamente');
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
        
        $('#txt_producto').autocomplete({
            source: base_url + 'index.php/WS_productos/buscador_producto',
            minLength: 2,
            select: function (event, ui) {
                console.log('--');
                $('#txt_producto_id').val(ui.item.id);
            }
        });
    });        
    
    $('#sel_movimiento').append("<option value=''>Movimiento</option>");
    $.each(ls_movimientos, function(i, item) {        
        $('#sel_movimiento').append($('<option>', {
            value: item.id,
            text: item.movimiento
        }));        
    });
        
</script>
