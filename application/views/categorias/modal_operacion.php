<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="titulo_modal">Registrar Categoria</h4>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">
                    <div class="col-xs-6">
       			<div class="form-group">
                            <label for="modal_categoria">Categoria</label>
                            <input type="text" id="modal_categoria" name="modal_categoria" class="form-control input-sm">
       			</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
       			<div class="form-group">
                            <label for="modal_categoria">Código</label>
                            <input type="text" id="modal_codigo" name="modal_codigo" class="form-control input-sm">
                            <input type="hidden" id="modal_categoria_id"/>
       			</div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_categoria">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/categorias/operaciones';    
            
    $(document).ready(function(){
                        
        $("#btn_guardar_categoria").on('click', function(){            
            if($('#modal_categoria').val() == ''){
                toast('Error', 1500, 'Debe ingresar Categoria');
                return false;
            }

            var data = {
                categoria_id:$('#modal_categoria_id').val(),
                categoria:$('#modal_categoria').val(),
                codigo:$('#modal_codigo').val(),
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Categoria ingresada correctamente');
                    $("#myModal").modal('hide');
                        
                    $("#tabla_categoria_id > tbody").remove();
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
