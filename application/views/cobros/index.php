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

<div class="container-fluid" style="margin: 0 25px;">
    <div id="panel_fomulario" class="panel panel-primary">
        <div class="panel-heading" >
            <div class="panel-title"><div align="center"><b>Cobros</b></div></div>
        </div>
        <div class="panel-body">   
            <div class="row" >
                <div class="col-lg-5">
                    <label>Cliente:</label><br>
                    <input autocomplete="off" type="text" class="form-control input-sm" id="entidad" name="entidad" placeholder="Cliente">
                    <input type="hidden"  name="entidad_id" id="entidad_id" >                        
                </div>
                <div id="div_tipo_doc" class="col-lg-2" >
                    <label>Tip.Doc</label><br>
                    <select class="form-control input-sm" name="tipo_documento" id="tipo_documento">
                    </select>
                </div>                    
                <div class="col-lg-1" >
                    <label>Número</label><br>
                    <input type="text" class="form-control input-sm" id="numero" name="numero" placeholder="numero">
                </div>                    
            </div>
            <div class="row">
                <div class="col-lg-3 form-inline">
                    <label>Fec.Emision</label><br>
                    <input class="form-control input-sm" type="text" name="fecha_emision_inicio" id="fecha_emision_inicio" value="" placeholder="Desde">
                    <input class="form-control input-sm" type="text" name="fecha_emision_final" id="fecha_emision_final" value="" placeholder="Hasta">
                </div>

                <div class="col-lg-2 form-inline">
                    <label>Modo Cobro:</label><br>
                    <select class="form-control input-sm" name="modo_pago" id="modo_pago">
                    </select>
                </div>
                <div class="col-lg-2 form-inline">
                    <label>Moneda:</label><br>
                    <select class="form-control input-sm" name="moneda" id="moneda">
                    </select>
                </div>
                <div class="col-lg-1" style="text-align: left;"  >
                    <label></label><br>
                    <a name="buscar_comprobante" id="buscar_comprobante" class="btn btn-primary">Buscar</a>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label></label><br>                                                
                    <button id="btn_nuevo_cobro" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Cobro</button>
                    <a id="exportarExcel" class="btn btn-primary btn_nuevo_comprobante"><i class="glyphicon glyphicon-save"></i> Reporte</a>
                </div>
            </div>
            <div class="row" style="padding-top: 10px">                
                <div class="col-xs-6 form-inline">
                </div>
            </div>
        </div>
    </div>
</div>

<div align="center" class="container-fluid">
    <div class="row-fluid">
        <table id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Modo</th>
                    <th>Fecha</th>
                    <th>N. Pago</th>
                    <th>Monto</th>
                    <th class="centro_text">Archivo</th>
                    <th>Cliente</th>
                    <th class="centro_text">Documento</th>                    
                    <th class="centro_text">A4</th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                    <th class="centro_text"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
            </thead>
        </table>    
    </div>    
    <div id='div_contenedor'>
        <ul id="lista_id_pagination" class="pagination lista_paginacion">
        </ul>
    </div>
</div>
<script src="<?PHP echo base_url();?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">    
    
    var base_url = '<?php echo base_url();?>';
    var total_filas = 0;
    var filas_por_pagina = 20;
    var pagina_inicial = 1;
    var venta_id_select;
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var catidad_decimales = datos_configuracion.catidad_decimales;

    var ls_monedas = JSON.parse(localStorage.getItem("monedas"));
    $('#moneda').append("<option value=''>Seleccionar</option>");
    $.each(ls_monedas, function(i, item) {        
        $('#moneda').append($('<option>', {
            value: item.id,
            text: item.moneda
        }));        
    });

    var ls_tipo_documentos = JSON.parse(localStorage.getItem("tipo_documentos"));
    $('#tipo_documento').append("<option value=''>Seleccionar</option>");
    $.each(ls_tipo_documentos, function(i, item) {        
        if(item.id != 9){
            $('#tipo_documento').append($('<option>', {
                value: item.id,
                text: item.tipo_documento
            }));        
        }    
    });

    var ls_modo_pago = JSON.parse(localStorage.getItem("modo_pagos"));
    $('#modo_pago').append("<option value=''>Seleccionar</option>");
    $.each(ls_modo_pago, function(i, item) {        
        if(item.id != 9){
            $('#modo_pago').append($('<option>', {
                value: item.id,
                text: item.modo_pago
            }));        
        }    
    });
    
    $(document).ready(function(){
        $('#fecha_emision_inicio').datepicker();
        $("#fecha_emision_final").datepicker();
        
        //BUSCAR filtros
        $('#buscar_comprobante').on('click', function(){
            pagina = 1; //
            $("#tabla_id > tbody").remove();

            param_entidad_id            = ( $('#entidad_id').val() == '' )              ? param_stand_url :  $('#entidad_id').val();
            param_tipo_documento        = ( $('#tipo_documento').val() == '' )          ? param_stand_url :  $('#tipo_documento').val();
            param_numero                = ( $('#numero').val() == '' )                  ? param_stand_url :  $('#numero').val();
            param_fecha_emision_inicio  = ( $('#fecha_emision_inicio').val() == '' )    ? param_stand_url :  $('#fecha_emision_inicio').val();
            param_fecha_emision_final   = ( $('#fecha_emision_final').val() == '' )     ? param_stand_url :  $('#fecha_emision_final').val();
            param_modo_pago_id          = ( $('#modo_pago').val() == '' )               ? param_stand_url :  $('#modo_pago').val();
            param_moneda_id             = ( $('#moneda').val() == '' )                  ? param_stand_url :  $('#moneda').val();
            
            var rr = base_url + 'index.php/WS_cobros/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_numero + '/' + param_fecha_emision_inicio + '/' + param_fecha_emision_final + '/' + param_modo_pago_id + '/' + param_moneda_id;
            console.log('filtro:'+rr);
            $.getJSON(rr)
                .done(function (data) {                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                     $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.registros).forEach(function (repo) {
                        agregarFila(numero_orden, repo.cobro_id, repo.modo_pago, repo.fecha_pago, repo.cobro_monto, repo.cobro_archivo_adjunto, repo.entidad, repo.venta_serie, repo.venta_numero, repo.venta_id);
                        numero_orden ++;
                    });
            });
        });
        
        //PAGINACION
        $('#div_contenedor').on('click', '.pajaro', function(){
            $('li').removeClass('active');
            $(this).parent().addClass('active');
            
            param_entidad_id            = ( $('#entidad_id').val() == '' )              ? param_stand_url :  $('#entidad_id').val();
            param_tipo_documento        = ( $('#tipo_documento').val() == '' )          ? param_stand_url :  $('#tipo_documento').val();
            param_numero                = ( $('#numero').val() == '' )                  ? param_stand_url :  $('#numero').val();
            param_fecha_emision_inicio  = ( $('#fecha_emision_inicio').val() == '' )    ? param_stand_url :  $('#fecha_emision_inicio').val();
            param_fecha_emision_final   = ( $('#fecha_emision_final').val() == '' )     ? param_stand_url :  $('#fecha_emision_final').val();
            param_modo_pago_id          = ( $('#modo_pago').val() == '' )               ? param_stand_url :  $('#modo_pago').val();
            param_moneda_id             = ( $('#moneda').val() == '' )                  ? param_stand_url :  $('#moneda').val();
            
            pagina = $(this).text();
            $("#tabla_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_cobros/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_documento + '/' + param_numero + '/' + param_fecha_emision_inicio + '/' + param_fecha_emision_final + '/' + param_modo_pago_id + '/' + param_moneda_id;
            console.log('paginacion:'+url_l);
            $.getJSON(url_l)
                .done(function (data) {

                    total_filas = data.total_filas; 
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.registros).forEach(function (repo) {
                        agregarFila(numero_orden, repo.cobro_id, repo.modo_pago, repo.fecha_pago, repo.cobro_monto, repo.cobro_archivo_adjunto, repo.entidad, repo.venta_serie, repo.venta_numero, repo.venta_id);
                        numero_orden ++;
                    });
            });            
        });
        
        $("#btn_nuevo_cobro").click(function(){
            $("#myModal").load(base_url + 'index.php/cobros/modal_operacion');
        });
        
        $('#entidad').autocomplete({
            source: base_url + 'index.php/WS_ventas/buscador_entidad',
            minLength: 2,
            select: function (event, ui) {
                $('#entidad_id').val(ui.item.id);
            }
        });
        
        //modal imagen
        $("#tabla_id").on('click','.btn_imagen', function(){
            var cobro_id = $(this).attr('id');
                        
            ruta_url = base_url + 'index.php/cobros/modal_imagen/';
            $("#myModal").load(ruta_url);
                    
            ruta_url_item = base_url + 'index.php/WS_cobros/ws_select_cobro_id/' + cobro_id;
            $.getJSON(ruta_url_item)
            .done(function (data){

                $('#imagen_cobro_id').val(cobro_id);
                foto_imagen = (data.archivo_adjunto == null) ? 'sin_foto.jpg' : 'cobros/'+data.archivo_adjunto
                $("#img_cobro").attr('src', base_url + 'images/'+foto_imagen);
                $("#imagen_descargar").attr('href', base_url + 'images/'+foto_imagen);
                $("#imagen_descargar").attr('target', '_blank'); 
            });
                    
        });
        
        //detalle cobro
        $("#tabla_id").on('click','.btn_perfil_cobro', function(){
            var cobro_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/cobros/modal_detalle/';
            $("#myModal").load(ruta_url);
            
            venta_id_select = $(this).data("venta_id");            
            ruta_url_item = base_url + 'index.php/WS_cobros/reporte_cobro/' + cobro_id;
            $.getJSON(ruta_url_item)
            .done(function (data){
                $('#numero_pago').text(data.cobro_id);
                $('#fecha_pago').text(data.fecha_pago);
                $('#monto').text(data.monto);
                $('#detalle_modo_pago').text(data.modo_pago);
                $('#nota').text(data.nota);
                $('#detalle_entidad').text(data.entidad);
                $('#detalle_documento').text(data.serie+'-'+data.numero);

                foto_imagen = (data.archivo_adjunto == null) ? 'sin_foto.jpg' : 'cobros/'+data.archivo_adjunto;
                $("#img_cobro").attr('src', base_url + 'images/' + foto_imagen);
            });
        });
        
        //modal modificar
        $("#tabla_id").on('click','.btn_modificar_cobro', function(){
            var cobro_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/cobros/modal_operacion/';
            $("#myModal").load(ruta_url);                                    

            ruta_url_item = base_url + 'index.php/WS_cobros/reporte_cobro/' + cobro_id;
            $.getJSON(ruta_url_item)
            .done(function (data){
                $('#entidad_modal').val(data.entidad);
                $('#entidad_id_modal').val(data.entidad_id);
                $('#fecha_pago').val(data.fecha_pago);
                $('#monto').val(data.monto);
                $('#nota').val(data.nota);
                $('#modal_cobro_id').val(cobro_id);
                $('#btn_guardar_cobro').text('Modificar');
                
                $("#modo_pago_modal option[value='"+data.modo_pago_id+"']").prop('selected', true);
                $("#entidad_modal").attr('readonly', true);
                $('#comprobantes').prepend("<option value='" + data.venta_id + "'>" + data.serie+'-'+data.numero + "</option>");
            })                        
        });
        
        //eliminar
        $("#tabla_id").on('click', '.btn_eliminar_cobro', function(e){            
            var cobro_id = $(this).attr('id');            
            var x = confirm("Desea eliminar cobro:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/WS_cobros/delete_cobro/' + cobro_id;
                $.getJSON(ruta_url_item)
                        .done(function (data){
                            console.log('elimiación correcta' + data);
                        });
                                    
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            }
        });
    });

    carga_inicial();
    //al cargar página    
    function carga_inicial(){
        var url_l = base_url + 'index.php/WS_cobros/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
        $.getJSON(url_l)
            .done(function (data) {                
                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga);

                var numero_orden = 1;
                (data.registros).forEach(function (repo) {
                    agregarFila(numero_orden, repo.cobro_id, repo.modo_pago, repo.fecha_pago, repo.cobro_monto, repo.cobro_archivo_adjunto, repo.entidad, repo.venta_serie, repo.venta_numero, repo.venta_id);
                    numero_orden ++;
                });
        });
    }
    
    var datos = [];
    var numero_documento_venta;
    function agregarFila(numero_orden, cobro_id, modo_pago, fecha_pago, cobro_monto, cobro_archivo_adjunto, entidad, venta_serie, venta_numero, venta_id){
        var fila = '<tr class="seleccionado tabla_fila">';                
        fila += '<td align="center"><a data-venta_id="'+venta_id+'" id="'+cobro_id+'" class="btn btn-default btn-xs btn_perfil_cobro" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+modo_pago+'</td>';
        fila += '<td>'+fecha_pago+'</td>';
        fila += '<td>'+cobro_id+'</td>';
        fila += '<td>'+cobro_monto+'</td>';
        fila += '<td align="center"><a id="'+cobro_id+'" class="btn btn-default btn-xs btn_imagen" data-imagen="'+cobro_archivo_adjunto+'" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-camera"></i></a></td>';        
        fila += '<td>'+entidad+'</td>';
        fila += '<td align="center">'+venta_serie+'-'+venta_numero+'</td>';
        fila += '<td align="center"><a target="_blank" href="'+base_url+'index.php/cobros/pdf_a4/'+cobro_id+'"><img title="Ver Pdf" src="'+base_url+'images/pdf.png"></a></td>';        
        fila += '<td align="center"><a id="'+cobro_id+'" class="btn btn-default btn-xs btn_modificar_cobro" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';
        fila += '<td align="center"><a id="'+cobro_id+'" class="btn btn-danger btn-xs btn_eliminar_cobro"><i class="glyphicon glyphicon-remove"></i></a></td>';
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