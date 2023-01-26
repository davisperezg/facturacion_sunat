<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Perfil:</b>Empresa</h4>
            <img id="modal_foto" height="120px">
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-condensed table-responsive">
                <tr>
                    <td><label for="modal_empresa">Razón Social:</label></td>
                    <td><span id="modal_empresa"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_nombre_comercial">Nombre Comercial:</label></td>
                    <td><span id="modal_nombre_comercial"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_ruc">Ruc:</label></td>
                    <td><span id="modal_ruc"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_domicilio_fiscal">Domicilio Fiscal:</label></td>
                    <td><span id="modal_domicilio_fiscal"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_departamento">Departamento:</label></td>
                    <td><span id="modal_departamento"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_provincia">Provincia:</label></td>
                    <td><span id="modal_provincia"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_distrito">Distrito:</label></td>
                    <td><span id="modal_distrito"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_ubigeo">Ubigeo:</label></td>
                    <td><span id="modal_ubigeo"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_urb">Urbanización:</label></td>
                    <td><span id="modal_urb"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_fijo">Teléfono Fijo:</label></td>
                    <td><span id="modal_telefono_fijo"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_fijo2">Teléfono Fijo2:</label></td>
                    <td><span id="modal_telefono_fijo2"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_movil">Celular 1:</label></td>
                    <td><span id="modal_telefono_movil"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_telefono_movil2">Celular 2:</label></td>
                    <td><span id="modal_telefono_movil2"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_correo">Correo:</label></td>
                    <td><span id="modal_correo"></span></td>
                </tr>                
                <tr>
                    <td><label for="modal_usuario_secundario_user">Usuario Secundario (User):</label></td>
                    <td><span id="modal_usuario_secundario_user"></span></td>
                </tr>
                <tr>
                    <td><label for="regimen">Régimen:</label></td>
                    <td><span id="regimen"></span></td>
                </tr>
                <tr>
                    <td><label for="codigo_sucursal_sunat">Código sucursal SUNAT:</label></td>
                    <td><span id="codigo_sucursal_sunat"></span></td>
                </tr>
                <tr>
                    <td><label for="modal_modo">Modo:</label></td>
                    <td><span id="modal_modo"></span></td>
                </tr>                
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url = base_url + 'index.php/WS_ubigeos/datos_ubigeo/' + ubigeo;
    $.getJSON(url)
    .done(function (data) {
        $('#modal_departamento').text(data.datos_ubigeo.departamento);
        $('#modal_provincia').text(data.datos_ubigeo.provincia);
        $('#modal_distrito').text(data.datos_ubigeo.distrito);
    });
</script>    