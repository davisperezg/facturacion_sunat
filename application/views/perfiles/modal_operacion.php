<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 id="title_texto" class="modal-title">Nuevo Perfil---</h4>
        </div>
        <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="codigo">Perfil:</label>
                            <input type="text" id="txt_tipo_empleado" class="form-control input-sm">
                            <input type="hidden" id="modal_tipo_empleado_id" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table role="grid" style="height: auto;" id="tabla_modulo_id" class="table table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>N.</th>
                                    <th>Menú</th>
                                    <th>Módulo</th>
                                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_perfil">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/Tipo_empleado_modulos/operaciones';
    var valor_check = 1;
    
    $(document).ready(function(){
        //$('#nepele').text('yyyy');
        
        $("#btn_guardar_perfil").on('click', function(){
            var data = {};            
            var array_check = [];
            
            $('#tabla_modulo_id tbody tr').each(function(){                                                
                if($(this).find('td').eq(3).find('#chek').is(":checked") == true){
                    array_check.push($(this).find('td').children().val())    
                }                
            });
            data['check_modulo']        = array_check;
            data['tipo_empleado']       = $("#txt_tipo_empleado").val();
            data['tipo_empleado_id']    = modal_tipo_empleado_id;

            $.getJSON(url_save, data)
            .done(function(datos, textStatus, jqXHR){                
                toast('success', 1500, 'Perfil ingresado correctamente');
                $("#myModal").modal('hide');

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

    carga_inicial_modal();
    
    function carga_inicial_modal(){
        $('#txt_tipo_empleado').val(texto_tipo_empleado);
        
        
        console.log('modal_tipo_empleado_id:'+modal_tipo_empleado_id);
        //CARGA INICIAL
        if(texto_tipo_empleado == ''){
            var url_l = base_url + 'index.php/WS_tipo_empleados/ws_modulos_all/';
            $.getJSON(url_l)
            .done(function (data) {
                (data).forEach(function (repo) {
                    agregarFila(repo.papa, repo.hijo, repo.hijo_id);
                });
            });
        }else{
            $('#btn_guardar_perfil').text('Modificar');
            var url_l = base_url + 'index.php/WS_tipo_empleados/ws_modulos_usados/' + modal_tipo_empleado_id;
            $.getJSON(url_l)
            .done(function (data) {
                (data).forEach(function (repo) {
                    agregarFila(repo.papa, repo.hijo, repo.hijo_id, repo.modulo_id_usado);
                });
            });
        }
    }
    
    var contador_papa = 0;
    var contador_hijo = 0;
    var color = '';
    var papa_diferente = '';
    function agregarFila(papa, hijo, hijo_id, check){
        check = (check == null) ? '' : 'checked';
        if(papa != papa_diferente){            
            contador_papa ++;
            contador_hijo = 0;
        }
        contador_hijo ++;        
        color = (contador_papa % 2 == 0) ? "style='background-color: #EAF2F8'" : '';
        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';                
        fila += '<td align="center">'+contador_hijo+'</td>';
        fila += '<td>'+papa+'</td>';
        fila += '<td>'+hijo+'</td>';
        fila += '<td><input ' + check + ' type="checkbox" value="'+hijo_id+'" id="chek" name="chek[]"></td>';
        papa_diferente = papa;        
        
        fila += '</tr>';
        $("#tabla_modulo_id").append(fila);    
    }
    
</script>
