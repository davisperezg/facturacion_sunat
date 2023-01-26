<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Entidad</b> - <span id="modal_detalle_entidad"></span></h4>
        </div>
        <div class="modal-body">
            
            <table class="table table-bordered table-condensed table-responsive">
                <tr>
                    <td><label for="modal_tipo_entidades">Tipo Entidad:</label></td>
                    <td><span id="modal_tipo_entidades"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_numero_documento">N. Documento:</label></td>
                    <td><span id="modal_numero_documento"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_entidad">Razón Social/Nombres Completos:</label></td>
                    <td><span id="modal_entidad"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_direccion">Dirección:</label></td>
                    <td><span id="modal_direccion"></span></td>
                </tr>                
            </table> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
    var url_save = base_url + 'index.php/contactos/save';            
    $(document).ready(function(){        

        ruta_url_item = base_url + 'index.php/WS_entidades/ws_select_item/' + entidad_id_pro;
        $.getJSON(ruta_url_item)
        .done(function (data){
            $('#modal_detalle_entidad').text(razon_social_pro + ' - ' + numero_documento_pro);
            $('#modal_tipo_entidades').text(data[0].tipo_entidad);
            $('#modal_numero_documento').text(data[0].numero_documento);
            $('#modal_entidad').text(data[0].entidad);
            $('#modal_nombre_comercial').text(data[0].nombre_comercial);
            $('#modal_direccion').text(data[0].direccion);
        });
    });
</script>