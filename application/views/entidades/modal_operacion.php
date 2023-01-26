<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Registro Entidad</h4>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="modal_tipo_entidades">Tipo Entidad</label>
                            <select id="modal_tipo_entidades" class="form form-control">                                
                            </select>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
       			<div class="form-group">
                            <label for="modal_numero_documento">N. Documento</label>
                            <input type="text" id="modal_numero_documento" name="modal_numero_documento" class="form-control input-sm">
                            <input type="hidden" id="modal_entidad_id"/>
       			</div>
                    </div>
                    <div class="col-xs-2">
       			<div class="form-group">
                            <label for="datos_ws_externa">Buscar</label>
                            <button type="button" class="btn btn-primary" id="datos_ws_externa" name="datos_ws_externa">Sunat</button>
       			</div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-xs-12">
       			<div class="form-group">
                            <label for="modal_entidad">Razón Social / Apellido Paterno Apellido Materno, Nombres</label>
                            <input type="text" id="modal_entidad" class="form-control input-sm">
       			</div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-xs-12">
       			<div class="form-group">
                            <label for="modal_direccion">Dirección</label>
                            <input type="text" id="modal_direccion" class="form-control input-sm">
       			</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="telefono_movil_1">Teléfono</label>
                            <input type="text" id="telefono_movil_1" name="telefono_movil_1" class="form-control input-sm">
                        </div>
                    </div>
                </div>                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_entidad">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/entidades/operaciones';
    
    ruta_url_item = base_url + 'index.php/WS_entidades/select_item/' + entidad_id_pro;
    $.getJSON(ruta_url_item)
    .done(function (data){
        $('#modal_numero_documento').val(data[0].numero_documento);
        $('#modal_entidad').val(data[0].entidad);
        $('#modal_nombre_comercial').val(data[0].nombre_comercial);
        $('#modal_direccion').val(data[0].direccion);
        $('#telefono_movil_1').val(data[0].telefono_movil_1);
        $('#modal_entidad_id').val(entidad_id_pro);

        tipo_entidad_id = data[0].tipo_entidad_id;
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_entidades/select')
    .done(function (data){
        sortJSON(data.tipo_entidades, 'id', 'desc');
        (data.tipo_entidades).forEach(function (repo) {
            var selectedado = (repo.id == 1) ? 'selected' : '';
            $('#modal_tipo_entidades').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.tipo_entidad + "</option>");
        });

        if($('#modal_entidad_id').val() != ''){
            $("#modal_tipo_entidades option[value='"+tipo_entidad_id+"']").prop("selected", true);
        }
        if($('#modal_entidad_id').val() != ''){
            $('.modal-title').text('Modificar Producto');
            $('#btn_guardar_entidad').text('Modificar');
        }
    });
            
    $(document).ready(function(){
        
        $("#datos_ws_externa").on('click', function(){       
            toast('success', 2000, 'Buscando');
            var modal_numero_documento = $("#modal_numero_documento").val();
            if( (modal_numero_documento.length != 8) && (modal_numero_documento.length != 11) ){
                toast('Error', 1500, 'Cantidad de dígitos incorrectos.');
                return false;
            }
            
            var url_l = base_url + 'index.php/WS_entidades/buscador_externo_ruc_ventas/' + $("#modal_numero_documento").val() + '/0';
            
            console.log('numero_documento:' + $("#modal_numero_documento").val());
            
            $.getJSON(url_l)
            .done(function (data) {
                if((data.entidad_id != '') && (data.entidad_id > 0)){
                    alert("Entidad: " + data.entidad + " existente.");
                    return false;
                }

                $("#modal_entidad").val(data.entidad);
                $("#modal_direccion").val(data.direccion);
            })
            .fail(function() {
                toast('Error', 1500, 'Datos no encontrados');
                $("#entidad_id").val('');
                $("#entidad").val('');
                $("#direccion").val('');
            });
            
            

//            //buscador para RUC
//            if(modal_numero_documento.length == 11){            
//                var url_l = base_url + 'index.php/WS_entidades/buscador_externo_ruc/' + $("#modal_numero_documento").val();
//                $.getJSON(url_l)
//                    .done(function (data) {
//                        $("#modal_entidad").val(data.entidad);
//                        $("#modal_direccion").val(data.direccion);
//                    })
//                    .fail(function() {
//                        toast('Error', 1500, 'Datos no encontrados');
//                        $("#modal_entidad").val('');
//                        $("#modal_direccion").val('');
//                    })
//            }
//            
//            //buscador para DNI
//            if(modal_numero_documento.length == 8){            
//                var url_l = base_url + 'index.php/WS_entidades/buscador_externo_dni/' + $("#modal_numero_documento").val();
//                $.getJSON(url_l)
//                    .done(function (data) {                    
//                        $("#modal_entidad").val(data.entidad);
//                    })
//                    .fail(function() {
//                        toast('Error', 1500, 'Datos no encontrados');
//                        $("#modal_entidad").val('');
//                        $("#modal_direccion").val('');
//                    })
//            }
        });
        
        $("#btn_guardar_entidad").on('click', function(){
            if(  ($('#modal_numero_documento').val() == '') ||  ($('#modal_entidad').val() == '') || ($('#modal_direccion').val() == '')  ){
                toast('Error', 1500, 'Falta ingresar datos');
                return false;
            }
            
            if(($('#modal_tipo_entidades').val() == 1) && ($('#modal_numero_documento').val().length != 8)){
                toast('Error', 1500, 'DNI debe tener 8 caracteres.');
                return false;
            }
            
            if(($('#modal_tipo_entidades').val() == 2) && ($('#modal_numero_documento').val().length != 11)){
                toast('Error', 1500, 'RUC debe tener 11 caracteres.');
                return false;
            }

            var data = {
                entidad_id          :$('#modal_entidad_id').val(),
                tipo_entidades      :$('#modal_tipo_entidades').val(),
                numero_documento    :$('#modal_numero_documento').val(),
                entidad             :$('#modal_entidad').val(),
                direccion           :$('#modal_direccion').val(),
                telefono_movil_1    :$('#telefono_movil_1').val()
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Entidad ingresada correctamente');
                    $("#myModal").modal('hide');
                        
                    $("#tabla_entidad_id > tbody").remove();
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
