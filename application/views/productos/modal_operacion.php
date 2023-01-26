<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Registro Producto</h4>
        </div>
        <div class="modal-body">
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
                </div>
                <div class="row">
                    <div class="col-md-12">
       			<div class="form-group">
                            <label for="descripcion_producto">Nombre</label>
                            <input type="text" id="descripcion_producto" class="form-control input-sm">
                            <input type="hidden" id="modal_producto_id"/>
       			</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
       			<div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" class="form-control input-sm"></textarea>
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
                </div>
                    
                <div class="row">                        
                    <div id="idv_stock_inicial">
                        <div class="col-xs-6" >
                            <div class="form-group">
                            <label for="stock_inicial">Stock Inicial</label>
                            <input type="number" id="stock_inicial" class="form-control input-sm is-invalid has-danger">
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="precio_base_compra">Compras</label><br>
                            Precio Base.<br>
                            <input type="number" id="precio_base_compra" name="precio_base_compra" class="form-control input-sm">
                        </div>
                    </div>  
                    <div class="col-xs-4">
                        <div class="form-group">
                            <br>IGV<br>
                            <input readonly="" type="number" id="igv_valor_compra" class="form-control input-sm">
                        </div>
                    </div>                    
                    <div class="col-xs-4">
                        <div class="form-group">
                            <br>Precio con IGV<br>
                            <input type="number" id="precio_con_igv_compra" class="form-control input-sm">
                        </div>
                    </div>    
                </div>                        
            
                <div class="row">
                    <div class="col-xs-12"><label for="precio_base_venta">Venta:</label></div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            Precio Base.<br>
                            <input type="number" id="precio_base_venta" name="precio_base_venta" class="form-control input-sm">
                        </div>
                    </div>  
                    <div class="col-xs-4">
                        <div class="form-group">
                            IGV<br>
                            <input readonly="" type="number" id="igv_valor" class="form-control input-sm">
                        </div>
                    </div>                       
                    <div class="col-xs-4">
                        <div class="form-group">
                            Precio con IGV<br>
                            <input type="number" id="precio_con_igv" class="form-control input-sm">
                        </div>
                    </div>                       
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn_guardar_producto">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
    var url_save = base_url + 'index.php/productos/operaciones';
    
    if(modal_producto_id == ''){
        $.getJSON(base_url + 'index.php/WS_productos/max_producto_id')
        .done(function (data) {
            $('#codigo').val('C00-' + (parseFloat(data) + 1));
        });    
    }
    if((modal_producto_id != '') && (modal_producto_id > 0)){
        ruta_url_item = base_url + 'index.php/WS_productos/select_item/' + modal_producto_id;
        $.getJSON(ruta_url_item)
        .done(function (data){
            //onsole.log(data);
            precio_base_venta = data[0].precio_base_venta;
            $("#igv_valor").val(parseFloat(precio_base_venta*porcentaje_valor_igv).toFixed(2));
            $("#precio_con_igv").val(parseFloat(precio_base_venta*(1+porcentaje_valor_igv)).toFixed(2));
            
            precio_base_costo = data[0].precio_costo;
            $("#igv_valor_compra").val(parseFloat(precio_base_costo*porcentaje_valor_igv).toFixed(2));
            $("#precio_con_igv_compra").val(parseFloat(precio_base_costo*(1+porcentaje_valor_igv)).toFixed(2));

            $('#codigo_sunat').val(data[0].codigo_sunat);
            $('#codigo').val(data[0].codigo);
            $('#descripcion_producto').val(data[0].producto);
            $('#descripcion').val(data[0].descripcion);
            $('#precio_base_venta').val(data[0].precio_base_venta);
            $('#precio_base_compra').val(data[0].precio_costo);
            $('#comision_venta').val(data[0].comision_venta);

            $('#stock_inicial').val(data[0].stock_inicial);                        
            $('#precio_costo').val(data[0].precio_costo);
            $('#modal_producto_id').val(modal_producto_id);
            $('#idv_stock_inicial').hide();

            modal_categoria_id = data[0].categoria_id;
            modal_unidad_id = data[0].unidad_id;
        });
    }
    
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
                descripcion:$('#descripcion').val(),
                precio_base_venta:$('#precio_base_venta').val(),
                precio_costo:$('#precio_base_compra').val(),
                stock_inicial:$('#stock_inicial').val(),
                categoria_id:$('#categorias').val(),
                unidad_id:$('#unidades').val()
            };

            $.getJSON(url_save, data)
            .done(function(datos, textStatus, jqXHR){
                toast('success', 1500, 'Producto ingresado correctamente');
                $("#myModal").modal('hide');

                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                var url_l = base_url + 'index.php/WS_productos/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
                $.getJSON(url_l)
                .done(function (data) {
                    sortJSON(data.ws_select_productos, 'producto_id', 'desc');

                    carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                    total_filas = data.total_filas;
                    construir_paginacion(total_filas, filas_por_pagina, carga)

                    var numero_orden = 1;
                    (data.ws_select_productos).forEach(function (repo) {
                        agregarFila(numero_orden, repo.codigo_sunat, repo.codigo, repo.producto, repo.categoria, repo.unidad, repo.precio_costo, repo.precio_base_venta, repo.stock_inicial, repo.stock_actual, repo.producto_id, repo.imagen);
                        numero_orden ++;
                    });
                });                    
                console.log( "La solicitud se ha completado correctamente." + datos + "---" + textStatus +"---"+ jqXHR);
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
                    console.log( "Algo ha fallado: " +  textStatus );
                }
            });   
                
        });        

        $("#precio_base_venta").on("keyup", function() {
            precio_base_venta = $("#precio_base_venta").val();                    
            $("#igv_valor").val(parseFloat(precio_base_venta*porcentaje_valor_igv).toFixed(6));
            $("#precio_con_igv").val(parseFloat(precio_base_venta*(1+porcentaje_valor_igv)).toFixed(6));
        });        
        
        $("#precio_con_igv").on("keyup", function() {
            precio_con_igv = $("#precio_con_igv").val();                    
            $("#igv_valor").val(parseFloat(porcentaje_valor_igv*precio_con_igv/(1+porcentaje_valor_igv)).toFixed(6));
            $("#precio_base_venta").val(parseFloat(precio_con_igv/(1+porcentaje_valor_igv)).toFixed(6));
        });
        
        $("#precio_base_compra").on("keyup", function() {
            precio_base_compra = $("#precio_base_compra").val();                    
            $("#igv_valor_compra").val(parseFloat(precio_base_compra*porcentaje_valor_igv).toFixed(2));
            $("#precio_con_igv_compra").val(parseFloat(precio_base_compra*(1+porcentaje_valor_igv)).toFixed(2));
        });
        
        $("#precio_con_igv_compra").on("keyup", function() {
            txt_precio_con_igv_compra = $("#precio_con_igv_compra").val();                    
            $("#igv_valor_compra").val(parseFloat(porcentaje_valor_igv*txt_precio_con_igv_compra/(1+porcentaje_valor_igv)).toFixed(2));
            $("#precio_base_compra").val(parseFloat(txt_precio_con_igv_compra/(1+porcentaje_valor_igv)).toFixed(3));
        });        
    });        
    
    $.getJSON(base_url + 'index.php/WS_categorias/ws_select_all')
        .done(function (data) {
            sortJSON(data.categorias, 'id', 'desc');
            $('#categorias').prepend("<option value=''>Seleccionar</option>");
            (data.categorias).forEach(function (repo) {
                var selectedado = (repo.id == 1) ? 'selected' : '';
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
                var selectedado = (repo.id == 58) ? 'selected' : '';
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
