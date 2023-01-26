<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Nuevo Libro Electrónico - Compras 8.1</h4>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">                        
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="anio">Año</label>
                            <select class="form form-control" id="anio">
                            </select>
                        </div>
                    </div>                   
                </div>  
                <div class="row">                        
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mes">Mes</label>
                            <select class="form form-control" id="mes">
                            </select>
                        </div>
                    </div>                   
                </div>                  
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_le_ventas">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">    
    var anio_actual = <?php echo date("Y");?>;
    var base_url = '<?PHP echo base_url();?>';
    
    var i ;
    var j = 0;
    for(i = (anio_actual - 30); i < anio_actual; i++){
        $('#anio').append("<option value='" + (anio_actual - j) + "'>" + (anio_actual - j) + "</option>");
        j++;
    }
    
    var url_l = base_url + 'index.php/WS_variables_diversas/meses';
    $.getJSON(url_l)
    .done(function (data){
        var m;
        for(m = 0; m < 12 ; m++ ){
            $('#mes').append("<option value='" + (m + 1) + "'>" + data[m+1] + "</option>");
        }
    });
    
    $(document).ready(function(){        
        $("#btn_le_ventas").on('click', function(){
            var data = {
                anio:$('#anio').val(),
                mes:$('#mes').val()                
            };
            
            var url_save = base_url + 'index.php/le_compras8_1/operaciones';
            $.getJSON(url_save, data)
            .done(function(datos, textStatus, jqXHR){
                toast('success', 1500, 'Operación ingresado correctamente');
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
</script>
