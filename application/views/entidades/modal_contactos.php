<div class="modal-dialog bd-example-modal-lg" role="document">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Contactos</b> - <span id="modal_cuenta_entidad"></span></h4>
        </div>
        <div class="modal-body">
            <form id="formulario_contactos">                            
                <div class="row">                        
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_apellido_paterno">Apellido Paterno</label>
                            <input type="text" name="modal_apellido_paterno" id="modal_apellido_paterno" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_apellido_materno">Apellido Materno</label>
                            <input type="text" name="modal_apellido_materno" id="modal_apellido_materno" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_nombres">Nombres</label>
                            <input type="text" name="modal_nombres" id="modal_nombres" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="modal_celular">Celular</label>
                            <input type="text" id="modal_celular" name="modal_celular" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="modal_correo">Correo</label>
                            <input type="text" id="modal_correo" name="modal_correo" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_comentario">Comentario</label>
                            <input type="text" id="modal_comentario" name="modal_comentario" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button type="button" class="btn btn-primary" id="btn_guardar_contactos" name="btn_guardar_contactos">Guardar</button>
                    </div>
                </div>
            </form>
            <hr>
            <table id="tabla_id_contactos" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Nombres y apellidos</th>
                        <th>Movil</th>
                        <th>Correo</th>
                        <th>Comentario</th>
                        <th><i class="glyphicon glyphicon-remove"></i></th>
                    </tr>
                </thead>
            </table>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <input type="hidden" name="modal_contacto_entidad_id" id="modal_contacto_entidad_id" />
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/contactos/save';            
    $(document).ready(function(){        
        $("#tabla_id_contactos").on('click', '.btn_eliminar_contacto', function(){            
            var contacto_id = $(this).attr('id');            
            var x = confirm("Desea eliminar el contacto:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/WS_contactos/delete_item/' + contacto_id;
                $.getJSON(ruta_url_item)
                        .done(function (data){
                            toast('success', 1500, 'Eliminación correcta');
                            console.log('elimiación correcta' + data);
                        });
                        
                var parent = $(this).parent("td").parent("tr");
                parent.fadeOut('slow'); //Borra la fila afectada                
//                $("#tabla_id > tbody").remove();
//                $("#lista_id_pagination > li").remove();
//                carga_inicial();
            }
        });
                
        $("#btn_guardar_contactos").on('click', function(){
            if(  ($('#modal_apellido_paterno').val() == '')  ){
                toast('Error', 1500, 'Debe ingresar al menos: Apellido Paterno');
                return false;
            }

            var data = {
                entidad_id:$('#modal_contacto_entidad_id').val(),
                apellido_paterno:$('#modal_apellido_paterno').val(),
                apellido_materno:$('#modal_apellido_materno').val(),
                nombres:$('#modal_nombres').val(),
                celular:$('#modal_celular').val(),
                correo:$('#modal_correo').val(),
                comentario:$('#modal_comentario').val()
            };
            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'contacto ingresado correctamente');
                    agregarFila_contacto(datos.contacto_id, $('#modal_apellido_paterno').val(), $('#modal_apellido_materno').val(), $('#modal_nombres').val(), $('#modal_celular').val(), $('#modal_correo').val(), $('#modal_comentario').val());
                    $('#formulario_contactos')[0].reset();
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " + jqXHR + textStatus + errorThrown);
                    }
                }); 
        });        
    });
    
    function agregarFila_contacto(contacto_id, apellido_paterno, apellido_materno, nombres, celular, correo, comentario){
        var fila = '<tr class="seleccionado tabla_fila">';
        fila += '<td>'+apellido_paterno+' '+apellido_materno+' '+nombres+'</td>';
        fila += '<td>' + celular + '</span></td>';
        fila += '<td>'+correo+'</td>';
        fila += '<td>'+comentario+'</td>';
        fila += '<td align="center"><a id="'+contacto_id+'" class="btn btn-danger btn-xs btn_eliminar_contacto"><i class="glyphicon glyphicon-remove"></i></a></td>';
        fila += '</tr>';
        $("#tabla_id_contactos").append(fila);
    }  
        
</script>
