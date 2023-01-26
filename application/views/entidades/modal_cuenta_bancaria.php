<div class="modal-dialog bd-example-modal-lg" role="document">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Cuentas Bancarias</b> - <span id="modal_cuenta_entidad"></span></h4>
        </div>
        <div class="modal-body">
            <form id="formulario_cuentas_bancarias">
                <div class="row">                        
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label for="modal_bancos">Banco</label>
                            <select id="modal_bancos" name="modal_bancos" class="form-control input-sm">                            
                            </select>                          
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="modal_tipo_cuentas">T.Cuenta</label>
                            <select id="modal_tipo_cuentas" name="modal_tipo_cuentas" class="form-control input-sm">                            
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="modal_monedas">Moneda</label>
                            <select id="modal_monedas" name="modal_monedas" class="form-control input-sm">                            
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-5">
                        <div class="form-group">
                            <label for="modal_numero_cuenta">Número de cuenta</label>
                            <input type="text" id="modal_numero_cuenta" name="modal_numero_cuenta" class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="modal_codigo_interbancario">Código Interbancario CCI</label>
                            <input type="text" id="modal_codigo_interbancario" name="modal_codigo_interbancario" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="modal_titular">Titular</label>
                            <input type="text" id="modal_titular" name="modal_titular" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <br>
                        <button type="button" class="btn btn-primary" id="btn_guardar_cuentas" name="btn_guardar_cuentas">Guardar</button>
                    </div>
                </div>
            </form>
            <hr>
            <table id="tabla_id_banco" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Banco</th>
                        <th>T.</th>
                        <th>Número</th>
                        <th>Titular</th>
                        <th><i class="glyphicon glyphicon-remove"></i></th>
                    </tr>
                </thead>
            </table>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <input type="hidden" name="modal_cuenta_entidad_id" id="modal_cuenta_entidad_id" />
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    let bancos = JSON.parse(localStorage.getItem("bancos"));
    sortJSON(bancos, 'id', 'desc');
    (bancos).forEach(function (repo) {
        var selectedado = (repo.id == 1) ? 'selected' : '';
        $('#modal_bancos').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.banco + "</option>");
    });
    
    let tipo_cuentas = JSON.parse(localStorage.getItem("tipo_cuentas"));
    sortJSON(tipo_cuentas, 'id', 'desc');
    (tipo_cuentas).forEach(function (repo) {
        var selectedado = (repo.id == 1) ? 'selected' : '';
        $('#modal_tipo_cuentas').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.tipo_cuenta + "</option>");
    });
    
    let monedas = JSON.parse(localStorage.getItem("monedas"));
    sortJSON(monedas, 'id', 'desc');
    (monedas).forEach(function (repo) {
        var selectedado = (repo.id == 1) ? 'selected' : '';
        $('#modal_monedas').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.moneda + "</option>");
    });
    
    var url_save = base_url + 'index.php/cuenta_entidades/save';
            
    $(document).ready(function(){
        
        $("#tabla_id_banco").on('click', '.btn_eliminar_cuenta_entidad', function(e){
            
            var cuenta_entidad_id = $(this).attr('id');            
            var x = confirm("Desea eliminar producto:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/WS_cuenta_entidades/delete_item/' + cuenta_entidad_id;
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
                
        $("#btn_guardar_cuentas").on('click', function(){
            if(  ($('#modal_numero_cuenta').val() == '') ||  ($('#modal_titular').val() == '') ){
                toast('Error', 1500, 'Falta número de cuenta y/o Titular');
                return false;
            }

            var data = {
                entidad_id:$('#modal_cuenta_entidad_id').val(),
                banco_id:$('#modal_bancos').val(),
                tipo_cuenta_id:$('#modal_tipo_cuentas').val(),
                moneda_id:$('#modal_monedas').val(),
                numero_cuenta:$('#modal_numero_cuenta').val(),
                codigo_interbancario:$('#modal_codigo_interbancario').val(),
                titular:$('#modal_titular').val()
            };
            
            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Cuenta Ingresada correctamente');
                    agregarFila_cuenta($( "#modal_bancos option:selected" ).text(), $( "#modal_tipo_cuentas option:selected" ).text(), $( "#modal_monedas option:selected" ).text(), $("#modal_numero_cuenta").val(), $("#modal_titular").val(), datos.cuenta_entidad_id);
                    $('#formulario_cuentas_bancarias')[0].reset();
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " + jqXHR + textStatus + errorThrown);
                    }
                }); 
        });
        
    });
    
    
    function agregarFila_cuenta(banco, tipo_cuenta, moneda, numero_cuenta, titular, cuenta_entidad_id){
        var moneda_text = moneda;
        var fila = '<tr class="seleccionado tabla_fila">';
        fila += '<td>'+banco+'</td>';
        fila += '<td>' + tipo_cuenta + '-<span class="text_capital">' + moneda.substr(0,1) + '</span></td>';
        fila += '<td>'+numero_cuenta+'</td>';
        fila += '<td>'+titular+'</td>';
        fila += '<td align="center"><a id="'+cuenta_entidad_id+'" class="btn btn-danger btn-xs btn_eliminar_cuenta_entidad"><i class="glyphicon glyphicon-remove"></i></a></td>';
        fila += '</tr>';
        $("#tabla_id_banco").append(fila);
    }  
        
</script>
