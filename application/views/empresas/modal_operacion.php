<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Perfil:</b>Empresa</h4>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-condensed table-responsive">
                <tr>
                    <td><label for="modal_empresa">Razón Social:</label></td>
                    <td>
                        <input type="text" name="txt_modal_empresa" id="txt_modal_empresa" class="form-control input-sm"/>
                        <input type="hidden" name="txt_modal_empresa_id" id="txt_modal_empresa_id" />
                    </td>
                </tr>
                <tr>
                    <td><label for="modal_nombre_comercial">Nombre Comercial:</label></td>
                    <td><input type="text" name="txt_modal_nombre_comercial" id="txt_modal_nombre_comercial" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_ruc">Ruc:</label></td>
                    <td><input type="text" name="txt_modal_ruc" id="txt_modal_ruc" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_domicilio_fiscal">Domicilio Fiscal:</label></td>
                    <td><input type="text" name="txt_modal_domicilio_fiscal" id="txt_modal_domicilio_fiscal" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_departamento">Departamento:</label></td>
                    <td>
                        <select class="form-control form-control-sm" id="departamento_id" name="departamento_id" required="">
                        </select>
                    </td>
                </tr>                
                <tr>
                    <td><label for="modal_provincia">Provincia:</label></td>
                    <td>
                        <select class="form-control form-control-sm" id="provincia_id" name="provincia_id" required="">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="modal_distrito">Distrito:</label></td>
                    <td>
                        <select class="form-control form-control-sm" id="distrito_id" name="distrito_id" required="">
                        </select>
                    </td>
                </tr>                
                <tr>
                    <td><label for="modal_ubigeo">Ubigeo:</label></td>
                    <td><input type="text" name="txt_modal_ubigeo" id="txt_modal_ubigeo" class="form-control input-sm" readonly=""/></td>
                </tr>                
                <tr>
                    <td><label for="modal_urb">Urbanización:</label></td>
                    <td><input type="text" name="txt_modal_urb" id="txt_modal_urb" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_fijo">Teléfono Fijo:</label></td>
                    <td><input type="text" name="txt_modal_telefono_fijo" id="txt_modal_telefono_fijo" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_fijo2">Teléfono Fijo2:</label></td>
                    <td><input type="text" name="txt_modal_telefono_fijo2" id="txt_modal_telefono_fijo2" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_movil">Celular 1:</label></td>
                    <td><input type="text" name="txt_modal_telefono_movil" id="txt_modal_telefono_movil" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_movil2">Celular 2:</label></td>
                    <td><input type="text" name="txt_modal_telefono_movil2" id="txt_modal_telefono_movil2" class="form-control input-sm"/></td>
                </tr>
                <tr>
                    <td><label for="modal_correo">Correo:</label></td>
                    <td><input type="text" name="txt_modal_correo" id="txt_modal_correo" class="form-control input-sm"/></td>
                </tr>                
                <tr>
                    <td><label for="modal_usuario_secundario_user">Usuario Secundario (User):</label></td>
                    <td>
                    <input type="text" name="txt_modal_usuario_secundario_user" id="txt_modal_usuario_secundario_user" class="form-control input-sm"/>
                    </td>
                </tr>
                <tr>
                    <td><label for="modal_usuario_secundario_passoword">Usuario Secundario (Password):</label></td>
                    <td>
                    <input type="text" name="txt_modal_usuario_secundario_passoword" id="txt_modal_usuario_secundario_passoword" class="form-control input-sm"/>
                    </td>
                </tr>
                <tr>
                    <td><label for="regimen_id">Régimen:</label></td>
                    <td>
                        <select class="form-control form-control-sm" id="regimen_id" name="regimen_id" required="">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="txt_codigo_sucursal_sunat">Código Sucursal SUNAT:</label></td>
                    <td>
                    <input type="text" name="txt_codigo_sucursal_sunat" id="txt_codigo_sucursal_sunat" class="form-control input-sm"/>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_modificar_empresa">Modificar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/empresas/operaciones';
            
    ruta_url_item = base_url + 'index.php/WS_empresas/ws_select_item/' + empresa_id;
    $.getJSON(ruta_url_item)
    .done(function (data){
        $('#txt_modal_empresa_id').val(empresa_id);
        $('#txt_modal_empresa').val(data.empresa);
        $('#txt_modal_nombre_comercial').val(data.nombre_comercial);
        $('#txt_modal_ruc').val(data.ruc);
        $('#txt_modal_domicilio_fiscal').val(data.domicilio_fiscal);
        $('#txt_modal_departamento').val(data.departamento);
        $('#txt_modal_provincia').val(data.provincia);
        $('#txt_modal_distrito').val(data.distrito);
        $('#txt_modal_ubigeo').val(data.ubigeo);
        $('#txt_modal_urb').val(data.urbanizacion);
        $('#txt_modal_pass_certificate').val(data.pass_certificate);
        $('#txt_modal_telefono_fijo').val(data.telefono_fijo);
        $('#txt_modal_telefono_fijo2').val(data.telefono_fijo2);
        $('#txt_modal_telefono_movil').val(data.telefono_movil);
        $('#txt_modal_telefono_movil2').val(data.telefono_movil2);                        
        $('#txt_modal_correo').val(data.correo);
        $('#txt_modal_usuario_secundario_user').val(data.usu_secundario_produccion_user);
        $('#txt_modal_usuario_secundario_passoword').val(data.usu_secundario_produccion_password);
        $('#txt_modal_modo').val(data.modo);
        $('#txt_codigo_sucursal_sunat').val(data.codigo_sucursal_sunat);
        ubigeo = data.ubigeo;
        regimen = data.regimen_id;
        
        var url = base_url + 'index.php/WS_regimenes/select_js';
        $.getJSON(url)
        .done(function (data) {
            (data).forEach(function (repo) {
                selected = (repo.id == regimen) ? 'selected' : '';
                $('#regimen_id').append("<option " + selected + " value='" + repo.id + "'>"+ repo.abreviatura + ' - ' + repo.regimen + "</option>");
            });
        });

        var url = base_url + 'index.php/WS_ubigeos/ws_departamentos';
        $.getJSON(url)
        .done(function (data) {
            (data.departamentos).forEach(function (repo) {
                selected = (repo.id == ubigeo.substring(0,2)) ? 'selected' : '';
                $('#departamento_id').append("<option " + selected + " value='" + repo.id + "'>" + repo.departamento + "</option>");
            });
        });

        var url = base_url + 'index.php/WS_ubigeos/cargaProvincias/' + ubigeo.substring(0,2);
        $.getJSON(url)
        .done(function (data) {
            (data.provincias).forEach(function (repo) {
                selected = (repo.id == ubigeo.substring(0,4)) ? 'selected' : '';
                $('#provincia_id').append("<option " + selected + " value='" + repo.id + "'>" + repo.provincia + "</option>");
            });
        });

        var url = base_url + 'index.php/WS_ubigeos/cargaDistritos/' + ubigeo.substring(0,4);
        $.getJSON(url)
        .done(function (data) {
            (data.distritos).forEach(function (repo) {
                selected = (repo.id == ubigeo) ? 'selected' : '';
                $('#distrito_id').append("<option " + selected + " value='" + repo.id + "'>" + repo.distrito + "</option>");
            });
        });

        
    });
        
    $(document).ready(function(){
        $("#btn_modificar_empresa").on('click', function(){
            if( ($('#txt_modal_empresa').val() == '' ) || ($('#txt_modal_ruc').val() == '' ) || ($('#txt_modal_domicilio_fiscal').val() == '' )){
                toast('Error', 1500, 'Falta ingresar razón social, RUC y domicilio fiscal como datos mínimos.');
                return false;
            }
            var data = {
                empresa_id:$('#txt_modal_empresa_id').val(),
                empresa:$('#txt_modal_empresa').val(),
                nombre_comercial:$('#txt_modal_nombre_comercial').val(),
                ruc:$('#txt_modal_ruc').val(),
                domicilio_fiscal:$('#txt_modal_domicilio_fiscal').val(),
                telefono_fijo:$('#txt_modal_telefono_fijo').val(),
                telefono_fijo2:$('#txt_modal_telefono_fijo2').val(),
                telefono_movil:$('#txt_modal_telefono_movil').val(),
                telefono_movil2:$('#txt_modal_telefono_movil2').val(),
                correo:$('#txt_modal_correo').val(),
                ubigeo:$('#txt_modal_ubigeo').val(),
                urbanizacion:$('#txt_modal_urb').val(),
                usu_secundario_produccion_user:$('#txt_modal_usuario_secundario_user').val(),
                usu_secundario_produccion_password:$('#txt_modal_usuario_secundario_passoword').val(),
                regimen_id:$('#regimen_id').val(),
                codigo_sucursal_sunat:$('#txt_codigo_sucursal_sunat').val()
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Empresa ingresada correctamente');
                    $("#myModal").modal('hide');
                    $("#tabla_empresa_id > tbody").remove();
                    carga_inicial();                                        
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " +  textStatus );
                    }
                });
        });
        
        $("#departamento_id").on("click", function(){
            $('#provincia_id option').remove();
            var departamento_id = $("#departamento_id").val();
            var url_provincias = base_url + 'index.php/WS_ubigeos/cargaProvincias/'+departamento_id;
            $.getJSON(url_provincias)
                    .done(function (data) {
                        $('#provincia_id').append("<option value=''>Seleccionar</option>");
                        (data.provincias).forEach(function (repo) {
                            $('#provincia_id').append("<option value='" + repo.id + "'>" + repo.provincia + "</option>");
                    });
            });        
        });

        $("#provincia_id").on("click", function(){
            $('#distrito_id option').remove();
            var provincia_id = $("#provincia_id").val();
            var url_distrito = base_url + 'index.php/WS_ubigeos/cargaDistritos/'+provincia_id;
            console.log(url_distrito);
            $.getJSON(url_distrito)
                    .done(function (data) {
                        $('#distrito_id').prepend("<option value=''>Seleccionar</option>");
                        (data.distritos).forEach(function (repo) {
                            $('#distrito_id').append("<option value='" + repo.id + "'>" + repo.distrito + "</option>");
                    });
            });        
        });
        
        $("#distrito_id").on("click", function(){
            var ubigeo = $("#distrito_id").val();
            $("#txt_modal_ubigeo").val(ubigeo);
        });

    });        
    
</script>