<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Entorno:<b><span id="texto_entorno">Beta</span></b></h4>
        </div>
        <div class="modal-body activar">
            <table class="table table-bordered table-condensed table-responsive">               
                <tr>
                    <td align="center"><button id="activar_produccion" type="button" class="btn btn-primary btn-lg">Activar Producción</button></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/empresas/activar_produccion';    
    if(entorno == 1){
        $("#texto_entorno").text('PRODUCCIÓN');
        $(".activar").hide();
    }
    
    $(document).ready(function(){                
        $("#activar_produccion").on('click', function(){                                        
            var data = {
                empresa_id:1
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Entorno producción');
                    $("#texto_entorno").text('PRODUCCIÓN');
                    $(".activar").hide();
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " +  textStatus );
                    }
                });                   
        });
        
        
        
    });        
    
</script>