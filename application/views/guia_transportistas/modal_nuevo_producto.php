<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Registro Producto</h4>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">                        
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo">Código Producto Sunat</label>
                            <input type="text" id="codigo_sunat" class="form-control input-sm" value="-">                            
                        </div>
                    </div>
                    <div class="col-md-6">
       			<div class="form-group">
                            <label for="codigo">Código</label>
                            <input type="text" id="codigo"  class="form-control input-sm">
       			</div>
                    </div>
                    <div class="col-md-12">
       			<div class="form-group">
                            <label for="descripcion_producto">Nombre/Descripción</label>
                            <input type="text" id="descripcion_producto" class="form-control input-sm">
                            <input type="hidden" id="modal_producto_id"/>
       			</div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoria"> Categoria </label>
                            <select required="" class="form-control" id="categorias" name="categorias">                             
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="medida"> Unidad/Medida </label>
                            <select required="" class="form-control" id="unidades" name="unidades">                          
                            </select>
                        </div>
                    </div> 

                    <div>
                        <div class="col-xs-6" >
                            <div class="form-group">
                            <label for="stock_inicial">Stock Inicial</label>
                            <input type="number" id="stock_inicial" class="form-control input-sm is-invalid has-danger">
                            </div>
                        </div>
                        <div class="col-xs-6"  >
                            <div class="form-group">
                                <label for="comision_venta">Comisión de venta</label>
                                <input type="number" id="comision_venta" class="form-control input-sm is-invalid has-danger">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="precio_base_venta">Precio Base.</label>
                            <input type="number" id="precio_base_venta" name="precio_base_venta" class="form-control input-sm">
                        </div>
                    </div>  
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="igv_valor">IGV</label>
                            <input readonly="" type="number" id="igv_valor" class="form-control input-sm">
                        </div>
                    </div>                       
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="precio_con_igv">Precio con IGV</label>
                            <input type="number" id="precio_con_igv" class="form-control input-sm">
                        </div>
                    </div>                       
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_producto">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/productos/operaciones';
    
    $(document).ready(function(){
        
        $("#btn_guardar_producto").on('click', function(){
            if(($('#codigo_sunat').val() == '') || ($('#codigo').val() == '') || ($('#descripcion_producto').val() == '') || ($('#categorias').val() == '') || ($('#unidades').val() == '')){
                toast('Error', 1500, 'Falta ingresar datos');
                return false;
            }

            var data = {
                producto_id:$('#modal_producto_id').val(),
                codigo_sunat:$('#codigo_sunat').val(),
                codigo:$('#codigo').val(),
                producto:$('#descripcion_producto').val(),
                precio_base_venta:$('#precio_base_venta').val(),
                comision_venta:$('#comision_venta').val(),
                stock_inicial:$('#stock_inicial').val(),
                categoria_id:$('#categorias').val(),
                unidad_id:$('#unidades').val()
            };

            $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 1500, 'Producto ingresado correctamente');
                    $("#myModal").modal('hide');                                           
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " +  textStatus );
                    }
                });   
                
        });        

        $("#precio_base_venta").on("keyup", function() {
            precio_base_venta = $("#precio_base_venta").val();                    
            $("#igv_valor").val(parseFloat(precio_base_venta*porcentaje_valor_igv).toFixed(2));
            $("#precio_con_igv").val(parseFloat(precio_base_venta*(1+porcentaje_valor_igv)).toFixed(2));
        });
        
        $("#precio_con_igv").on("keyup", function() {
            precio_con_igv = $("#precio_con_igv").val();                    
            $("#igv_valor").val(parseFloat(porcentaje_valor_igv*precio_con_igv/(1+porcentaje_valor_igv)).toFixed(2));
            $("#precio_base_venta").val(parseFloat(precio_con_igv/(1+porcentaje_valor_igv)).toFixed(3));
        });
        
    });
        
    $.getJSON(base_url + 'index.php/WS_categorias/ws_select')
        .done(function (data) {
            sortJSON(data.categorias, 'id', 'desc');
            $('#categorias').prepend("<option value=''>Seleccionar</option>");
            (data.categorias).forEach(function (repo) {
                var selectedado = (repo.id == 0) ? 'selected' : '';
                $('#categorias').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.categoria + "</option>");
            });
            if($('#modal_producto_id').val() != ''){
                $("#categorias option[value='"+modal_categoria_id+"']").prop("selected", true);                
            }                        
    });
                
    $.getJSON(base_url + 'index.php/WS_unidades/ws_select')
        .done(function (data) {
            sortJSON(data.unidades, 'id', 'desc');
            $('#unidades').prepend("<option value=''>Seleccionar</option>");
            (data.unidades).forEach(function (repo) {
                var selectedado = (repo.id == 0) ? 'selected' : '';
                $('#unidades').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.unidad + "</option>");
        });
        if($('#modal_producto_id').val() != ''){
            $("#unidades option[value='"+modal_unidad_id+"']").prop("selected", true);    
        }
        if($('#modal_producto_id').val() != ''){
            $('.modal-title').text('Modificar Producto');
            $('#btn_guardar_producto').text('Modificar');
        }
    });
</script>
