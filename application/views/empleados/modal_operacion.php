<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Nuevo Empleado</h4>
        </div>
        <div class="modal-body">
            <form autocomplete="off">
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="modal_o_tipo_empleados">Tipo Empleado</label>
                            <select id="modal_o_tipo_empleados" class="form form-control">                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_apellido_paterno">Apellido Paterno</label>
                            <input type="text" id="modal_o_apellido_paterno" name="modal_o_apellido_paterno" class="form-control input-sm">
                            <input type="hidden" id="modal_o_empleado_id"/>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_apellido_materno">Apellido Materno</label>
                            <input type="text" id="modal_o_apellido_materno" name="modal_o_apellido_materno" class="form-control input-sm">
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_o_nombres">Nombres</label>
                            <input type="text" id="modal_o_nombres" name="modal_o_nombres" class="form-control input-sm">
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_o_contrasena">Contraseña</label>
                            <input type="text" id="modal_o_contrasena" name="modal_o_contrasena" class="form-control input-sm">
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="text" id="modal_o_fecha_nacimiento" name="modal_o_fecha_nacimiento" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_dni">DNI</label>
                            <input type="text" id="modal_o_dni" name="modal_o_dni" class="form-control input-sm">
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="modal_o_domicilio">domicilio</label>
                            <input type="text" id="modal_o_domicilio" name="modal_o_domicilio" class="form-control input-sm">
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_telefono_fijo">Teléfono Fijo</label>
                            <input type="text" id="modal_o_telefono_fijo" name="modal_o_telefono_fijo" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_telefono_movil">Teléfono Movil</label>
                            <input type="text" id="modal_o_telefono_movil" name="modal_o_telefono_movil" class="form-control input-sm">
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_email_1">Correo 1</label>
                            <input type="text" id="modal_o_email_1" name="modal_o_email_1" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_o_email_2">Correo 2</label>
                            <input type="text" id="modal_o_email_2" name="modal_o_email_2" class="form-control input-sm">
                        </div>
                    </div>
                </div>   
            </form>                
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_empleado">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    $('#modal_o_fecha_nacimiento').datepicker();
    var url_save = base_url + 'index.php/empleados/operaciones';
    
    $.getJSON(base_url + 'index.php/WS_tipo_empleados/ws_select')
        .done(function (data) {
            //sortJSON(data.ws_tipo_empleados, 'id', 'desc');
            (data.ws_tipo_empleados).forEach(function (repo) {
                var selectedado = (repo.id == 1) ? 'selected' : '';
                $('#modal_o_tipo_empleados').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.tipo_empleado + "</option>");
        });

        if($('#modal_o_empleado_id').val() != ''){
            $("#modal_o_tipo_empleados option[value='"+tipo_empleado_id_select+"']").prop("selected", true);
        }
        if($('#modal_o_empleado_id').val() != ''){
            $('.modal-title').text('Modificar Empleado');
            $('#btn_guardar_empleado').text('Modificar');
        }
    });
            
    $(document).ready(function(){
        $("#btn_guardar_empleado").on('click', function(){
            if( ($('#modal_o_apellido_paterno').val() == '')  ||  ($('#modal_o_nombres').val() == '')  ){
                toast('Error', 1500, 'Debe ingresar apellido paterno y/o nombres');
                return false;
            }

            var data = {
                empleado_id:$('#modal_o_empleado_id').val(),
                tipo_empleado_id:$('#modal_o_tipo_empleados').val(),
                apellido_paterno:$('#modal_o_apellido_paterno').val(),
                apellido_materno:$('#modal_o_apellido_materno').val(),
                nombres:$('#modal_o_nombres').val(),
                contrasena:$('#modal_o_contrasena').val(),
                fecha_nacimiento:$('#modal_o_fecha_nacimiento').val(),
                dni:$('#modal_o_dni').val(),
                domicilio:$('#modal_o_domicilio').val(),
                telefono_fijo:$('#modal_o_telefono_fijo').val(),
                telefono_movil:$('#modal_o_telefono_movil').val(),
                email_1:$('#modal_o_email_1').val(),
                email_2:$('#modal_o_email_2').val()
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Entidad ingresada correctamente');
                    $("#myModal").modal('hide');
                        
                    $("#tabla_empleado_id > tbody").remove();
                    //$("#lista_id_pagination > li").remove();
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
