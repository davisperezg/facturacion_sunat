<style>    
    .derecha_text { 
        text-align: right; 
    }
    .centro_text { 
        text-align: center; 
    }
</style>
<h2 align="center">Movimiento Almacén</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <button id="btn_nuevo_movimiento" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Movimiento</button>
            <a class="btn btn-primary btn-sm" id="exportar_product">Reporte Excel</a>
        </div>
        
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" id="producto" placeholder="Buscar por producto">
            <input type="hidden" id="producto_id" />
        </div>

        <div class="col-md-2" >
            <select id="movimiento" class="form-control form-control-sm">                
            </select>
        </div>
        
        <div class="col-md-3" >
            <div style="width: 120px; float: left">
                <input class="form-control input-sm" type="text" name="fecha_inicio" id="fecha_inicio" value="" placeholder="Desde">            
            </div>
            <div style="width: 120px; padding-left: 5px; float: left">
                <input class="form-control input-sm" type="text" name="fecha_final" id="fecha_final" value="" placeholder="Hasta">
            </div>                        
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
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Movimiento</th>
                    <th>Cant.</th>
                    <th>Motivo</th>                    
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
    $('#fecha_inicio').datepicker();
    $("#fecha_final").datepicker();
    
    var base_url                = '<?php echo base_url();?>';
    var total_filas             = 0;
    var filas_por_pagina        = 20;
    var pagina_inicial          = 1;
    let datos_configuracion     = JSON.parse(localStorage.getItem("datos_configuracion"));
    var param_stand_url         = datos_configuracion.param_stand_url;
    var modal_categoria_id;
    var modal_unidad_id;
    
    var ls_movimientos = JSON.parse(localStorage.getItem("movimientos"));    
    
    $(document).ready(function(){        
        
        //BUSCAR filtros
        $('#btn_buscar_producto').on('click', function(){
            pagina = 1; //
            param_producto_id   = ($('#producto_id').val()  == '')  ? param_stand_url :  $('#producto_id').val();
            param_movimiento    = ($('#movimiento').val()   == '')  ? param_stand_url :  $('#movimiento').val();
            param_fecha_inicio  = ($('#fecha_inicio').val() == '' ) ? param_stand_url :  $('#fecha_inicio').val();
            param_fecha_final   = ($('#fecha_final').val()  == '' ) ? param_stand_url :  $('#fecha_final').val();
            param_producto_movimiento_id = param_stand_url;
            $("#tabla_id > tbody").remove();

            var ruta_url = base_url + 'index.php/WS_producto_movimientos/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_producto_id + '/' + param_movimiento + '/' + param_fecha_inicio + '/' + param_fecha_final  + '/' + param_producto_movimiento_id;
            $.getJSON(ruta_url)
            .done(function (data) {                    
                carga = 1;//se usa para activar la pagina N. 1
                total_filas = data.total_filas;
                $("#lista_id_pagination > li").remove();
                construir_paginacion(total_filas, filas_por_pagina, carga)

                var numero_orden = filas_por_pagina*(pagina-1)+1;
                (data.movimientos).forEach(function (repo) {
                    agregarFila(numero_orden, repo.producto_movimiento_id, repo.fecha_insert, repo.producto, repo.movimiento, repo.cantidad, repo.motivo);
                    numero_orden ++;
                });
            });
        });
        
        //PAGINACION
        $('#div_contenedor').on('click', '.pajaro', function(){
            param_producto_id   = ($('#producto_id').val()  == '')  ?   param_stand_url :   $('#producto_id').val();
            param_movimiento    = ($('#movimiento').val()   == '')  ?   param_stand_url :   $('#movimiento').val();
            param_fecha_inicio  = ($('#fecha_inicio').val() == '')  ?   param_stand_url :   $('#fecha_inicio').val();
            param_fecha_final   = ($('#fecha_final').val()  == '')  ?   param_stand_url :   $('#fecha_final').val();
            param_producto_movimiento_id = param_stand_url;
            
            $('li').removeClass('active');
            $(this).parent().addClass('active');

            pagina = $(this).text();
            $("#tabla_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_producto_movimientos/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_producto_id + '/' + param_movimiento + '/' + param_fecha_inicio + '/' + param_fecha_final  + '/' + param_producto_movimiento_id;
            $.getJSON(url_l)
            .done(function (data) {
                total_filas = data.total_filas; 
                var numero_orden = filas_por_pagina*(pagina-1)+1;
                (data.movimientos).forEach(function (repo) {
                    agregarFila(numero_orden, repo.producto_movimiento_id, repo.fecha_insert, repo.producto, repo.movimiento, repo.cantidad, repo.motivo);
                    numero_orden ++;
                });
            });
        });
        
        //modal detalle
        $("#tabla_id").on('click', '.btn_perfil_producto', function(){
            var producto_movimiento_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/producto_movimientos/modal_detalle/';
            $("#myModal").load(ruta_url);
            ruta_url_item = base_url + 'index.php/WS_producto_movimientos/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + producto_movimiento_id;
            console.log('///'+ruta_url_item);
            $.getJSON(ruta_url_item)
            .done(function (data){
                console.log(data);
                console.log('producto:'+data.movimientos[0].producto);
                $('#fecha_insert').text(data.movimientos[0].fecha_insert);
                $('#modal_producto').text(data.movimientos[0].producto);
                $('#modal_movimiento').text(movimiento_productos[data.movimientos[0].movimiento]);
                $('#cantidad').text(data.movimientos[0].cantidad);
                $('#motivo').text(data.movimientos[0].motivo);                        
            });
        });
    });
    
    $("#btn_nuevo_movimiento").click(function(){
        $("#myModal").load('<?php echo base_url()?>index.php/producto_movimientos/modal_operacion');
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
        $("#tabla_id > tbody").remove();        
        var url_l = base_url + 'index.php/WS_producto_movimientos/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
        $.getJSON(url_l)
            .done(function (data) {

                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga)

                var numero_orden = 1;
                (data.movimientos).forEach(function (repo) {
                    agregarFila(numero_orden, repo.producto_movimiento_id, repo.fecha_insert, repo.producto, repo.movimiento, repo.cantidad, repo.motivo);
                    numero_orden ++;
                });
        });
    }
    
    $.getJSON(base_url + 'index.php/WS_producto_movimientos/ws_select_movimiento')
        .done(function (data) {
        $('#movimiento').append("<option  value=''>Movimiento</option>");
        (data).forEach(function (repo) {
            $('#movimiento').append("<option  value='" + repo.id + "'>" + repo.movimiento + "</option>");
        });
    });
        
    
    var contador_fila = 1;
    var color = '';
    function agregarFila(numero_orden, producto_movimiento_id, fecha_insert, producto, campo_movimiento, cantidad, motivo){        
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;
        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a id="'+producto_movimiento_id+'" class="btn btn-default btn-xs btn_perfil_producto" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+fecha_insert+'</td>';
        fila += '<td>'+producto+'</td>';
        fila += '<td>'+ movimiento_productos[campo_movimiento] +'</td>';
        fila += '<td>'+cantidad+'</td>';
        fila += '<td>'+motivo+'</td>';
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