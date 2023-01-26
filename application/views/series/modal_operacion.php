<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Nueva Serie</h4>
        </div>
        <div class="modal-body">
            <div class="row">                        
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="modal_tipo_documentos">Tipo Documento</label>
                        <select id="modal_tipo_documentos" class="form form-control">                                
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="modal_numero_documento">Serie</label>
                        <input type="text" id="modal_serie" name="modal_serie" class="form-control input-sm">
                        <input type="hidden" id="modal_serie_id"/>
                    </div>
                </div>
            </div>                   
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_serie">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/series/operaciones';
                
    if(serie_id != ''){
        console.log('abc');
        ruta_url_item = base_url + 'index.php/WS_series/ws_select_item/' + serie_id;        
        $.getJSON(ruta_url_item)
        .done(function (data){
            $('#modal_serie').val(data.serie);
            $('#modal_serie_id').val(serie_id);
            tipo_documento_id_select = data.tipo_documento_id;
        });
    }    
    
    $.getJSON(base_url + 'index.php/WS_tipo_documentos/documentos_menos_guia')
    .done(function (data) {
        sortJSON(data.tipo_documentos, 'id', 'desc');
        (data.tipo_documentos).forEach(function (repo) {
            var selectedado = (repo.id == 1) ? 'selected' : '';
            $('#modal_tipo_documentos').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.tipo_documento + "</option>");
        });

        if($('#modal_serie_id').val() != ''){
            $("#modal_tipo_documentos option[value='"+tipo_documento_id_select+"']").prop("selected", true);
        }
        if($('#modal_serie_id').val() != ''){
            $('.modal-title').text('Modificar Producto');
            $('#btn_guardar_serie').text('Modificar');
        }
    });
            
    $(document).ready(function(){
        $("#btn_guardar_serie").on('click', function(){
                                    
            //Las facturas deben comenzar con F
            var texto = $("#modal_serie").val().substring(0,1).toUpperCase();
            if(($("#modal_tipo_documentos").val() == 1) && ( texto != 'F')){
                alert('Las facturas deben comenzar con F.');
                return false;
            }
            
            //Las Boletas deben comenzar con B
            if(($("#modal_tipo_documentos").val() == 3) && ( texto != 'B')){
                alert('Las Boletas deben comenzar con B.');
                return false;
            }
            
            if(($("#modal_tipo_documentos").val() == 3) && ( texto != 'B')){
                alert('Las Boletas deben comenzar con B.');
                return false;
            }
            
            if(( ($("#modal_tipo_documentos").val() == 7) || $("#modal_tipo_documentos").val() == 8 ) && ( (texto != 'B') && (texto != 'F') )){
                alert('Las Notas de crédito y débibo deben empezar con F ó B según seha para Factura o Boleta respectivamente.');
                return false;
            }
            
            if($("#modal_serie").val().length != 4){
                alert('La serie debe tener 4 caracteres');
                return false;
            }
            
            if($('#modal_serie').val() == ''){                                                
                alert('Falta ingresar número de serie.');
                return false;
            }
            
            var data = {
                serie_id:$('#modal_serie_id').val(),
                tipo_documento_id:$('#modal_tipo_documentos').val(),
                serie:$('#modal_serie').val().toUpperCase()
            };

            $.getJSON(url_save, data)
            .done(function(datos, textStatus, jqXHR){
                toast('success', 1500, 'Entidad ingresada correctamente');
                $("#myModal").modal('hide');

                $("#tabla_serie_id > tbody").remove();
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
