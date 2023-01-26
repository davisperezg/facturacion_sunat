<style>
    .seleccionado{
        cursor: pointer;
    }

    #refresh img{
        margin-left: 50px;
    }
    
    .pagina_seleccionada{
        color: blue;
        background-color: 'green';
    }
    
    .derecha_text { 
        text-align: right; 
    }
    
    .centro_text { 
        text-align: center; 
    }
</style>
<div class="container-fluid">
    <h2 align="center">Kardex - Promedio Ponderado</h2>        
    <div class="row">
        <div class="col-xs-1">
        </div> 
        <div class="col-xs-5" style="padding-bottom: 10px">
            <a id="btn_actulizar_datos" class="btn btn-success">Actualizar Datos</a>
            Datos actualizados al:<span id="txt_datos_actualizados_al"></span>
        </div>
    </div>    
    <div class="row">
        <div class="col-xs-1">
        </div>         
        <div class="col-xs-2">
            <select id="sel_anio" class="form form-control">                
            </select>
        </div>
        <div class="col-xs-2">
            <select id="sel_mes_inicio" class="form form-control">
            </select>
        </div>
        <div class="col-xs-2">
            <select id="sel_mes_fin" class="form form-control">
            </select>
        </div>        
        <div class="col-xs-1">
            <button class="btn btn-default" type="button" id="btn_buscar_producto"><span class="glyphicon glyphicon-search"></span></button>   
        </div>
    </div>    
</div>
<br><br>
<div class="container">
    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th colspan="5"></th>
                    <th colspan="2">INVENTARIO INICIAL</th>
                    <th colspan="2">COMPRAS</th>
                    <th colspan="2">VENTAS</th>
                    <th colspan="2">STOCK FINAL</th>
                </tr>
                <tr>
                    <th>N.</th>
                    <th>COD.</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Unidad de<br>Medida</th>
                    <th>Unidad</th>
                    <th>S/.</th>
                    <th>Unidad</th>
                    <th>S/.</th>
                    <th>Unidad</th>
                    <th>S/.</th>
                    <th>Unidad</th>
                    <th>S/.</th>
                </tr>
            </thead>
            <tbody role="rowgroup">                
            </tbody>
        </table>
    </div>
</div>
<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url    =   '<?php echo base_url();?>';
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var catidad_decimales = datos_configuracion.catidad_decimales;
    
    console.log(mes_texto('1'));
    
    for(var i = y; i > (y - 10); i --){
        $('#sel_anio').append($('<option>', {
            value: i,
            text: i
        }));
    }
    
    for(var j = 1; j <= 12; j++){
        $('#sel_mes_inicio').append($('<option>', {
            value: j,
            text: mes_texto(j.toString())
        }));
    }
    
    for(var k = 1; k <= 12; k++){
        $('#sel_mes_fin').append($('<option>', {
            value: k,
            text: mes_texto(k.toString())
        }));
    }
    
    //$("#sel_anio")
    
    $(document).ready(function(){                
        $("#btn_actulizar_datos").click(function (){
            var url_kardex = base_url + 'index.php/WS_kardex_promedio/actualizar_datos';
            $.getJSON(url_kardex)
            .done(function (data) {
                toast('success', 2500, 'Datos actualizados correctamente');                
                var url_actualizacion_kardex = base_url + 'index.php/WS_variables_diversas/ultima_actualizacion_kardex';
                $.getJSON(url_actualizacion_kardex)
                .done(function (datos) {                    
                    $("#txt_datos_actualizados_al").text(datos.fecha_hora);
                });                    
            });
        });
        
        $("#btn_buscar_producto").click(function(){
            if(($("#fec_init").val() == '') || ($("#fec_fint").val() == '')){
                alert('Debe ingresar correctamente las fechas');
                return false;
            }
                        
            $("#tabla_id > tbody").remove();
            var url_l = base_url + 'index.php/WS_kardex_promedio/reporte_mensual/' + $("#sel_anio").val() + '/' + $("#sel_mes_inicio").val() + '/' + $("#sel_mes_inicio").val();
            $.getJSON(url_l)
                .done(function (data) {
                    var numero_orden = 1;
                    (data).forEach(function (repo) {
                        agregarFila(numero_orden, repo.codigo, repo.producto, repo.unidad, repo.precio_costo, repo.stock_inicial, repo.entrada, repo.salida);
                        numero_orden ++;
                    });
            });
        });        
    });
        
    var entrada = 0;
    var salida = 0;
    function agregarFila(numero_orden, codigo, producto, unidad, precio_costo, stock_inicial, entrada, salida){        
        
        entrada = (entrada == null) ? 0 : entrada;
        salida = (salida == null) ? 0 : salida;
        
        var fila = '<tr class="seleccionado tabla_fila">';
        fila += '<td align="center"><a data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>' + codigo + '</td>';
        fila += '<td>' + producto + '</td>';
        fila += '<td>' + precio_costo + '</td>';
        fila += '<td>' + unidad + '</td>';
        
        fila += '<td class="derecha_text">' + stock_inicial + '</td>';
        fila += '<td class="derecha_text">' + (stock_inicial * precio_costo).toFixed(2) + '</td>';

        fila += '<td class="derecha_text">' + entrada + '</td>';
        fila += '<td class="derecha_text">' + (entrada * precio_costo).toFixed(2) + '</td>';
        
        fila += '<td class="derecha_text">' + salida + '</td>';
        fila += '<td class="derecha_text">' + (salida * precio_costo).toFixed(2) + '</td>';
        
        fila += '<td class="derecha_text">'+ (parseFloat(stock_inicial) + parseFloat(entrada) - parseFloat(salida)).toFixed(2) +'</td>';
        fila += '<td class="derecha_text">'+ ((parseFloat(stock_inicial) + parseFloat(entrada) - parseFloat(salida)) * precio_costo).toFixed(2) +'</td>';
                        
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }
    
    function operacion(numero){
        var resultado = ''
        switch (numero) {
            case '0':
              resultado = 'Stock Inicial';
              break;
            case '1':
              resultado = 'Compras';
              break;
            case '2':
              resultado = 'Ventas';
              break;
              
            case '3':
              resultado = 'Movimiento Almacén';
              break;            
        }
        return resultado;
    }
    
    var ls_tipo_documentos = JSON.parse(localStorage.getItem("tipo_documentos"));
    var abreviado_factura = '';
    var abreviado_boleta = '';    
    var abreviado_nota_credito = '';
    var abreviado_nota_debito = '';
    
    $.each(ls_tipo_documentos, function(i, item) {        
        if(item.id == 1){
            abreviado_factura = item.abreviado;
        }                        
        if(item.id == 3){
            abreviado_boleta = item.abreviado;
        }                        
        if(item.id == 7){
            abreviado_nota_credito = item.abreviado;
        }                        
        if(item.id == 8){
            abreviado_nota_debito = item.abreviado;
        }                        
    });
    
    function datos_tipo_documento(tipo_documento_id){
        var resultado = ''
        switch (tipo_documento_id) {
            case '1':
              resultado = abreviado_factura;
              break;
            case '3':
              resultado = abreviado_boleta;
              break;
            case '7':
              resultado = abreviado_nota_credito;
              break;            
            case '8':
              resultado = abreviado_nota_debito;
              break;            
        }
        return resultado;
    }
        
</script>