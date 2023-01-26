<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Actualizar correo</h4>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="user">Correo</label>                            
                            <input type="text" class="form form-control" id="modal_correo_user">
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="pass">Contraseña</label>
                            <input type="text" class="form form-control" id="modal_correo_pass">
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="repetir_pass">Repetir Contraseña</label>
                            <input type="text" class="form form-control" id="modal_correo_repetir_pass">
                        </div>
                    </div>
                </div>                                
                
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="host">Host</label>                            
                            <input type="text" class="form form-control" id="modal_host">
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="port">Port</label>                            
                            <input type="text" class="form form-control" id="modal_port">
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="correo_cifrado">Correo cifrado (tls / ssl)</label>                            
                            <input type="text" class="form form-control" id="modal_correo_cifrado">
                        </div>
                    </div>
                </div>
                                
                
                <div class="row">
                    <div class="col-xs-12">
       			<div class="form-group">
                            <label for="notas">Notas</label>
                            <textarea class="form form-control" id="modal_correo_notas"></textarea>
       			</div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_entidad">Guardar</button>
            <input id="modal_correo_id" type="hidden">
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/entidades/operaciones';        
            
    $(document).ready(function(){                
        
        $("#btn_guardar_entidad").on('click', function(){
            if(  ($('#modal_correo_user').val() == '') ||  ($('#modal_correo_pass').val() == '') || ($('#modal_correo_repetir_pass').val() == '') || ($('#modal_correo_notas').val() == '') ){
                toast('Error', 1500, 'Falta ingresar datos');
                return false;
            }
            
            if( $('#modal_correo_pass').val() != $('#modal_correo_repetir_pass').val()){
                toast('Error', 2500, 'La contraseñas no coinciden.');
                return false;
            }

            var data = {
                id:$('#modal_correo_id').val(),
                user:$('#modal_correo_user').val(),
                pass:$('#modal_correo_pass').val(),
                
                host:$('#modal_host').val(),
                port:$('#modal_port').val(),
                correo_cifrado:$('#modal_correo_cifrado').val(),
                
                notas:$('#modal_correo_notas').val(),
            };

            var url_save = base_url + 'index.php/correos/operaciones/' + $('#modal_correo_id').val();
            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Entidad ingresada correctamente');
                    $("#myModal").modal('hide');
                        
                    $("#tabla_correo > tbody").remove();
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
