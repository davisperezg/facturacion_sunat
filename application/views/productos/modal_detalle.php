<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Detalle Producto</h4>
        </div>
        <div class="modal-body">
            <div class="col-xs-6">
                <div aling="center">
                    <img height="220px" id="img_producto">
                </div>                
            </div>
            <div class="col-xs-6">
                <div aling="center">
                    <img height="22px" id="img_codigo_barras">
                </div>
                <br>
            </div>
            <br>
            <table class="tabla table-bordered table-condensed table-hover">
                <tr>
                    <td>Código Sunat</td>
                    <td><span id="codigo_sunat"></span></td>
                </tr>
                <tr>
                    <td>Código Interno</td>
                    <td><span id="codigo"></span></td>
                </tr>
                <tr>
                    <td>Producto</td>
                    <td><span id="descripcion_producto"></span></td>
                </tr>
                <tr>
                    <td>Descripción</td>
                    <td><span id="descripcion"></span></td>
                </tr>
                <tr>
                    <td>Categoría</td>
                    <td><span id="categoria"></span></td>
                </tr>
                <tr>
                    <td>Unidad</td>
                    <td><span id="unidad"></span></td>
                </tr>
                <tr>
                    <td>Stock Inicial</td>
                    <td><span id="stock_inicial"></span></td>
                </tr>
                <tr>
                    <td>Stock Actual</td>
                    <td><span id="stock_actual"></span></td>
                </tr>
                <tr>
                    <td>Precio Costo (Compras)</td>
                    <td><span id="precio_costo"></span></td>
                </tr>
                <tr>
                    <td>Precio Base (Ventas)</td>
                    <td><span id="precio_base_venta"></span></td>
                </tr>
                <tr>
                    <td>IGV</td>
                    <td><span id="igv_valor"></span></td>
                </tr>
                <tr>
                    <td>Precio Venta</td>
                    <td><span id="precio_con_igv"></span></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/config.js"></script>
<script type="text/javascript">
    
    

    ruta_url_item = base_url + 'index.php/WS_productos/ws_select_item/' + modal_producto_id;
    $.getJSON(ruta_url_item)
    .done(function (data){
        precio_base_venta = data[0].precio_base_venta;
        $("#igv_valor").text(parseFloat(precio_base_venta*porcentaje_valor_igv).toFixed(2));
        $("#precio_con_igv").text(parseFloat(precio_base_venta*(1+porcentaje_valor_igv)).toFixed(2));

        $('#codigo_sunat').text(data[0].codigo_sunat);
        $('#codigo').text(data[0].codigo);
        $('#descripcion_producto').text(data[0].producto);
        $('#descripcion').text(data[0].descripcion);
        $('#precio_base_venta').text(data[0].precio_base_venta);
        $('#precio_costo').text(data[0].precio_costo);
        $('#comision_venta').text(data[0].comision_venta);
        $('#stock_inicial').text(data[0].stock_inicial);
        $('#stock_actual').text(data[0].stock_actual);
        $('#modal_producto_id').text(producto_id);

        $('#categoria').text(data[0].categoria);
        $('#unidad').text(data[0].unidad);

        foto_imagen = (data[0].imagen == null) ? 'sin_foto.jpg' : data[0].imagen;
        $("#img_producto").attr('src', base_url + 'images/productos/' + foto_imagen);
        
        $("#img_codigo_barras").attr('src', base_url + 'index.php/productos/barcode_get?text=' + data[0].codigo + '&size=22');
    });
    
</script>    