<style>    
    .derecha_text { 
        text-align: right; 
    }
    .centro_text { 
        text-align: center; 
    }
</style>
<h2 align="center">Productos</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_producto" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Articulo</button>
            <a id="exportarExcel" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-save"></i>Reporte</a>
            <button id="btn_importar_excel" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Importar</button>
        </div>

        <div class="col-md-2" >
            <select id="buscar_categoria" class="form-control form-control-sm">                
            </select>
        </div> 
        <div class="col-md-2" >
            <select id="buscar_unidades" class="form-control form-control-sm">                
            </select>
        </div> 
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" id="producto" placeholder="Buscar por producto">
            <input type="hidden" id="producto_id" />
        </div>   

        <div class="col-md-1" >
            <button class="btn btn-default" type="button" id="btn_buscar_producto"><span class="glyphicon glyphicon-search"></span></button>   
        </div>                   
    </div>
    <br>

    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Código Sunat</th>
                    <th>Código Interno</th>
                    <th>Nombre/Descripción</th>
                    <th>Categoria</th>
                    <th>Unidad</th>
                    <th class="centro_text">P. Costo</th>
                    <th class="centro_text">P.Venta (Base)</th>
                    <th class="centro_text">P.Lista (con IGV)</th>
                    <th class="centro_text">Stock<br>Inicial</th>
                    <th class="centro_text">Stock<br>Actual</th>                    
                    <th class="centro_text"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                    <th class="centro_text"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
            </thead>
            <tbody role="rowgroup">                
            </tbody>
        </table>
    </div>    
    <div id='div_contenedor'>
        <ul id="lista_id_pagination" class="pagination lista_paginacion">
        </ul>
    </div>
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url                = '<?php echo base_url();?>';
    var total_filas             = 0;
    var filas_por_pagina        = 20;
    var pagina_inicial          = 1;
    let datos_configuracion     = JSON.parse(localStorage.getItem("datos_configuracion"));
    var param_stand_url         = datos_configuracion.param_stand_url;
    var porcentaje_valor_igv    = datos_configuracion.porcentaje_valor_igv;
    var catidad_decimales       = datos_configuracion.catidad_decimales;
    var modal_categoria_id;
    var modal_unidad_id;
    var modal_producto_id = '';
    
    $(document).ready(function(){
        
        $("#btn_importar_excel").click(function(){
            $("#myModal").load(base_url + 'index.php/productos/modal_importar_excel');                                            
        });
        
        //BUSCAR filtros
        $('#btn_buscar_producto').on('click', function(){
            pagina = 1; //
            param_producto_id = ($('#producto_id').val() == '') ? param_stand_url :  $('#producto_id').val();
            param_categoria_id = ($('#buscar_categoria').val() == '') ? param_stand_url :  $('#buscar_categoria').val();
            param_unidad_id = ($('#buscar_unidades').val() == '') ? param_stand_url :  $('#buscar_unidades').val();
            $("#tabla_id > tbody").remove();

            var ruta_url = base_url + 'index.php/WS_productos/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_producto_id + '/' + param_categoria_id + '/' + param_unidad_id;
            //console.log(ruta_url);
            $.getJSON(ruta_url)
                .done(function (data) {
                    sortJSON(data.ws_select_productos, 'producto_id', 'desc');
                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                    $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_productos).forEach(function (repo) {
                        agregarFila(numero_orden, repo.codigo_sunat, repo.codigo, repo.producto, repo.categoria, repo.unidad, repo.precio_costo, repo.precio_base_venta, repo.stock_inicial, repo.stock_actual, repo.producto_id, repo.imagen);                        
                        numero_orden ++;
                    });
            });
        });
        
        //PAGINACION
        $('#div_contenedor').on('click', '.pajaro', function(){
            param_producto_id = ($('#producto_id').val() == '') ? param_stand_url :  $('#producto_id').val();
            param_categoria_id = ($('#buscar_categoria').val() == '') ? param_stand_url :  $('#buscar_categoria').val();
            param_unidad_id = ($('#buscar_unidades').val() == '') ? param_stand_url :  $('#buscar_unidades').val();
            
            $('li').removeClass('active');
            $(this).parent().addClass('active');
                        
            pagina = $(this).text();
            $("#tabla_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_productos/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_producto_id + '/' + param_categoria_id + '/' + param_unidad_id;            
            //console.log(url_l);
            $.getJSON(url_l)
                .done(function (data) {
                    sortJSON(data.ws_select_productos, 'producto_id', 'desc');

                    total_filas = data.total_filas; 
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_productos).forEach(function (repo) {                        
                        agregarFila(numero_orden, repo.codigo_sunat, repo.codigo, repo.producto, repo.categoria, repo.unidad, repo.precio_costo, repo.precio_base_venta, repo.stock_inicial, repo.stock_actual, repo.producto_id, repo.imagen);
                        numero_orden ++;
                    });
            });            
        });

        //modal modificar
        $("#tabla_id").on('click', '.btn_modificar_producto', function(){
            var producto_id = $(this).attr('id');
            modal_producto_id = producto_id;
            
            ruta_url = base_url + 'index.php/productos/modal_operacion/';
            $("#myModal").load(ruta_url);
        });
        
        //modal detalle
        $("#tabla_id").on('click', '.btn_perfil_producto', function(){
            modal_producto_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/productos/modal_detalle/';
            $("#myModal").load(ruta_url);
        });
        
        //modal imagen
        $("#tabla_id").on('click', '.btn_imagen', function(){
            modal_producto_id = $(this).attr('id');                       
            ruta_url = base_url + 'index.php/productos/modal_imagen/';
            $("#myModal").load(ruta_url);
                    
            ruta_url_item = base_url + 'index.php/WS_productos/ws_select_item/' + modal_producto_id;
            $.getJSON(ruta_url_item)
            .done(function (data){

                $('#imagen_producto_id').val(modal_producto_id);
                foto_imagen = (data[0].imagen == null) ? 'sin_foto.jpg' : data[0].imagen
                $("#img_producto").attr('src', base_url + 'images/productos/'+foto_imagen)
            });                    
        });
        
        $("#tabla_id").on('click', '.btn_eliminar_producto', function(e){            
            var producto_id = $(this).attr('id');            
            var x = confirm("Desea eliminar producto:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/WS_productos/delete_item/' + producto_id;
                $.getJSON(ruta_url_item)
                .done(function (data){
                    console.log('elimiación correcta' + data);
                });
                        
//                var parent = $(this).parent("td").parent("tr");
//                parent.fadeOut('slow'); //Borra la fila afectada                
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            }
        });
        
        $('#exportarExcel').click(function(){
            param_producto_id = ($('#producto_id').val() == '') ? param_stand_url :  $('#producto_id').val();
            param_categoria_id = ($('#buscar_categoria').val() == '') ? param_stand_url :  $('#buscar_categoria').val();
            param_unidad_id = ($('#buscar_unidades').val() == '') ? param_stand_url :  $('#buscar_unidades').val();

            let ruta_excel = base_url + 'index.php/productos/exportarExcel/' + param_producto_id + '/' + param_categoria_id + '/' + param_unidad_id;
            //console.log(ruta_url);
            window.open(ruta_excel, '_blank');
        });
        
    });
    
    $("#btn_nuevo_producto").click(function(){
        modal_producto_id = '';
        $("#myModal").load('<?php echo base_url()?>index.php/productos/modal_operacion');
    }); 
    
    $('#producto').autocomplete({
        source: base_url + 'index.php/WS_productos/buscador_producto',
        minLength: 2,
        select: function (event, ui) {
            $('#producto_id').val(ui.item.id);
        }
    });
    
    carga_inicial();
    
    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_productos/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
        $.getJSON(url_l)
            .done(function (data) {
                sortJSON(data.ws_select_productos, 'producto_id', 'desc');

                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga)

                var numero_orden = 1;
                (data.ws_select_productos).forEach(function (repo) {
                    //console.log(repo);
                    agregarFila(numero_orden, repo.codigo_sunat, repo.codigo, repo.producto, repo.categoria, repo.unidad, repo.precio_costo, repo.precio_base_venta, repo.stock_inicial, repo.stock_actual, repo.producto_id, repo.imagen);
                    numero_orden ++;
                });
        });
    }
    
    $.getJSON(base_url + 'index.php/WS_categorias/ws_select_all')
        .done(function (data) {
            sortJSON(data.categorias, 'id', 'desc');            
            $('#buscar_categoria').prepend("<option value=''>Buscar Categoria</option>");
            (data.categorias).forEach(function (repo) {
                var selectedado = (repo.id == 0) ? 'selected' : '';
                $('#buscar_categoria').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.categoria + "</option>");
            });
    });
    
    $.getJSON(base_url + 'index.php/WS_unidades/ws_select')
        .done(function (data) {
            sortJSON(data.unidades, 'id', 'desc');
            $('#buscar_unidades').prepend("<option value=''>Buscar Unidad</option>");
            (data.unidades).forEach(function (repo) {
                var selectedado = (repo.id == 0) ? 'selected' : '';
                $('#buscar_unidades').prepend("<option " + selectedado + " value='" + repo.id + "'>" + repo.unidad + "</option>");
        });
    });
    
    var contador_fila = 1;
    var color = '';
    function agregarFila(numero_orden, codigo_sunat, codigo, producto, categoria, unidad, precio_costo, precio_base_venta, stock_inicial, stock_actual, producto_id, imagen){    
        precio_base_venta = (precio_base_venta == null) ? '' : precio_base_venta;
        
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;
        
        if(precio_costo == null) precio_costo = '';
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a id="'+producto_id+'" class="btn btn-default btn-xs btn_perfil_producto" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+codigo_sunat+'</td>';
        fila += '<td>'+codigo+'</td>';
        fila += '<td>'+producto+'</td>';
        fila += '<td>'+categoria+'</td>';
        fila += '<td>'+unidad+'</td>';
        fila += '<td class="derecha_text">'+precio_costo+'</td>';
        fila += '<td class="derecha_text">'+precio_base_venta+'</td>';
        fila += '<td class="derecha_text">'+parseFloat(precio_base_venta*(1+porcentaje_valor_igv)).toFixed(catidad_decimales)+'</td>';
        fila += '<td class="derecha_text">'+stock_inicial+'</td>';
        fila += '<td class="derecha_text">'+stock_actual+'</td>';
        fila += '<td align="center"><a id="'+producto_id+'" class="btn btn-default btn-xs btn_imagen" data-imagen="'+imagen+'" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-camera"></i></a></td>';
        fila += '<td align="center"><a id="'+producto_id+'" class="btn btn-default btn-xs btn_modificar_producto" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';
        fila += '<td align="center"><a id="'+producto_id+'" class="btn btn-danger btn-xs btn_eliminar_producto"><i class="glyphicon glyphicon-remove"></i></a></td>';
        
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }                
    
    function construir_paginacion(total_filas, filas_por_pagina, carga){
        paginas = Math.trunc(total_filas / filas_por_pagina);
        paginas = (total_filas % filas_por_pagina > 0) ? (paginas + 1): paginas;
        var j = 1;
        for(i = 0; i < paginas; i++){
            var activer = ((i == 0) && (carga == 1)) ? 'active' : '';
            $('.lista_paginacion').append('<li class="page-item ' + activer + '"><a class="pajaro">'+(i+1)+'</a></li>');
        }
    }
</script>