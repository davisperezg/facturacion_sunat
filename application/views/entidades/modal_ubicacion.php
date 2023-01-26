<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>UBICACIONES</b> - <span id="modal_ubicacion_entidad"></span></h4>
        </div>
        <div class="modal-body">
            <div class="row">                        
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_telefono_fijo_1">Teléfono Fijo 1</label>
                        <input type="text" id="modal_telefono_fijo_1" class="form-control input-sm">                            
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_telefono_fijo_2">Teléfono Fijo 2</label>
                        <input type="text" id="modal_telefono_fijo_2" class="form-control input-sm">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_telefono_movil_1">Teléfono Movil 1</label>
                        <input type="text" id="modal_telefono_movil_1" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_telefono_movil_2">Teléfono Movil 2</label>
                        <input type="text" id="modal_telefono_movil_2" class="form-control input-sm">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_email_1">Correo 1</label>
                        <input type="text" id="modal_email_1" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_email_2">Correo 2</label>
                        <input type="text" id="modal_email_2" class="form-control input-sm">
                    </div>
                </div>
            </div>                
                
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_pagina_web">Página Web</label>
                        <input type="text" id="modal_pagina_web" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_facebook">Facebook</label>
                        <input type="text" id="modal_facebook" class="form-control input-sm">
                    </div>
                </div>
            </div>
                                                
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="modal_twitter">Twitter</label>
                        <input type="text" id="modal_twitter" class="form-control input-sm">
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_ubicacion">Guardar</button>
            <input type="hidden" name="modal_ubicacion_entidad_id" id="modal_ubicacion_entidad_id" />
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/entidades/ubicacion';
            
    $(document).ready(function(){
        
        ruta_url_item = base_url + 'index.php/WS_entidades/select_item/' + entidad_id_pro;
        $.getJSON(ruta_url_item)
        .done(function (data){
            console.log('data[0].email_1:' + data[0].email_1);
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
        });
        
        
        $("#btn_guardar_ubicacion").on('click', function(){
            if(  ($('#modal_telefono_fijo_1').val() == '') 
                    && ($('#modal_telefono_fijo_2').val() == '') 
                    && ($('#modal_telefono_movil_1').val() == '') 
                    && ($('#modal_telefono_movil_2').val() == '') 
                    && ($('#modal_email_1').val() == '') 
                    && ($('#modal_email_2').val() == '') 
                    && ($('#modal_pagina_web').val() == '') 
                    && ($('#modal_facebook').val() == '') 
                    && ($('#modal_twitter').val() == '') 
                ){
                toast('Error', 1500, 'Debe ingresar al menos un dato');
                return false;
            }

            var data = {
                entidad_id:$('#modal_ubicacion_entidad_id').val(),
                email_1:$('#modal_email_1').val(),
                email_2:$('#modal_email_2').val(),
                telefono_fijo_1:$('#modal_telefono_fijo_1').val(),
                telefono_fijo_2:$('#modal_telefono_fijo_2').val(),
                telefono_movil_1:$('#modal_telefono_movil_1').val(),
                telefono_movil_2:$('#modal_telefono_movil_2').val(),
                pagina_web:$('#modal_pagina_web').val(),
                facebook:$('#modal_facebook').val(),
                twitter:$('#modal_twitter').val()
            };
            console.log(url_save);
            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'ubicaciones ingresadas correctamente');
                    $("#myModal").modal('hide');
                    
                    $("#tabla_entidad_id > tbody").remove();
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
