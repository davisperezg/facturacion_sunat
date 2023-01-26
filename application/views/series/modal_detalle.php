<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Serie:</b></h4>
        </div>
        <div class="modal-body">
            
            <table class="table table-bordered table-condensed table-responsive">
                <tr>
                    <td><label for="modal_tipo_documento">Tipo Documento:</label></td>
                    <td><span id="modal_tipo_documento"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_serie">Serie:</label></td>
                    <td><span id="modal_serie"></span></td>
                </tr>                
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">    
    ruta_url_item = base_url + 'index.php/WS_series/ws_select_item/' + serie_id;
    $.getJSON(ruta_url_item)
    .done(function (data){                                       
        $('#modal_tipo_documento').text(data.tipo_documento);
        $('#modal_serie').text(data.serie);
    });    
</script>