<style>    
    .derecha_text { 
        text-align: right; 
    }
    .centro_text { 
        text-align: center; 
    }
    .tamanio_pequenio{
        font-size: 15px;
    }
    .text_capital { 
        text-transform:capitalize; 
    } 
</style>
<h2 align="center">Guias Transportistas</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <a name="nueva_guia" id="nueva_guia" class="btn btn-success btn-sm">Nueva Guia</a>
            <a class="btn btn-primary btn-sm" id="exportar_entidad">Excel</a>
        </div>

        <div class="col-md-1" >
            <input type="text" class="form-control form-control-sm" id="guia_serie" name="guia_serie" placeholder="Serie">
        </div> 
        <div class="col-md-1" >
            <input type="text" class="form-control form-control-sm" id="guia_numero" name="guia_numero" placeholder="Número">
        </div> 
        <div class="col-md-2" >
            <input type="text" class="form-control form-control-sm" id="fecha_emision_desde" name="fecha_emision_desde" placeholder="Fecha Emisión Desde">
        </div> 
        <div class="col-md-2" >
            <input type="text" class="form-control form-control-sm" id="fecha_emision_hasta" name="fecha_emision_hasta" placeholder="Fecha Emisión Hasta">
        </div> 
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" id="entidad" placeholder="Buscar por entidad">
            <input type="hidden" id="entidad_id" />
        </div>   

        <div class="col-md-1" >
            <button class="btn btn-default" type="button" id="btn_buscar_entidad" name="btn_buscar_entidad"><span class="glyphicon glyphicon-search"></span></button>   
        </div>                   
    </div>
    <br>
    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_guia_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Guia</th>
                    <th>Cliente</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Traslado</th>                    
                    <th>A4</th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                    <th class="centro_text">Sunat</th>                    
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
    var base_url = '<?php echo base_url();?>';
    var total_filas = 0;
    var filas_por_pagina = 10;
    var pagina_inicial = 1;
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var select_guia_id;    
    
    $(document).ready(function(){
        
        $("#fecha_emision_desde").datepicker();
        $("#fecha_emision_hasta").datepicker();
        //BUSCAR filtros
        $('#btn_buscar_entidad').on('click', function(){
            pagina = 1; //
            param_entidad_id = ($('#entidad_id').val() == '') ? param_stand_url :  $('#entidad_id').val();
            param_serie = ($('#guia_serie').val() == '') ? param_stand_url :  $('#guia_serie').val();
            param_numero = ($('#guia_numero').val() == '') ? param_stand_url :  $('#guia_numero').val();
            param_fecha_emision_desde = ($('#fecha_emision_desde').val() == '') ? param_stand_url :  $('#fecha_emision_desde').val();
            param_fecha_emision_hasta = ($('#fecha_emision_hasta').val() == '') ? param_stand_url :  $('#fecha_emision_hasta').val();
            $("#tabla_guia_id > tbody").remove();

            var ruta_url = base_url + 'index.php/WS_guia_transportistas/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_serie + '/' + param_numero + '/' + param_fecha_emision_desde + '/' + param_fecha_emision_hasta;
            console.log(ruta_url);
            $.getJSON(ruta_url)
                .done(function (data) {
                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                    $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.guia_transportistas).forEach(function (repo) {
                        agregarFila(numero_orden, repo.guia_id, repo.entidad, repo.fecha_emision, repo.fecha_traslado, repo.serie, repo.numero, repo.venta_numero, repo.estado_operacion);
                        numero_orden ++;
                    });
            });
        });
        
        //PAGINACION
        $('#div_contenedor').on('click', '.pajaro', function(){
            param_entidad_id = ($('#entidad_id').val() == '') ? param_stand_url :  $('#entidad_id').val();
            param_serie = ($('#guia_serie').val() == '') ? param_stand_url :  $('#guia_serie').val();
            param_numero = ($('#guia_numero').val() == '') ? param_stand_url :  $('#guia_numero').val();
            param_fecha_emision_desde = ($('#fecha_emision_desde').val() == '') ? param_stand_url :  $('#fecha_emision_desde').val();
            param_fecha_emision_hasta = ($('#fecha_emision_hasta').val() == '') ? param_stand_url :  $('#fecha_emision_hasta').val();
            
            $('li').removeClass('active');
            $(this).parent().addClass('active');
                        
            pagina = $(this).text();
            $("#tabla_guia_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_guia_transportistas/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_serie + '/' + param_numero + '/' + param_fecha_emision_desde + '/' + param_fecha_emision_hasta;
            $.getJSON(url_l)
            .done(function (data) {
                //sortJSON(data.ws_select_entidades, 'entidad_id', 'desc');

                total_filas = data.total_filas; 
                var numero_orden = filas_por_pagina*(pagina-1)+1;
                (data.guia_transportistas).forEach(function (repo) {
                    agregarFila(numero_orden, repo.guia_id, repo.entidad, repo.fecha_emision, repo.fecha_traslado, repo.serie, repo.numero, repo.venta_numero, repo.estado_operacion);
                    numero_orden ++;
                });
            });
        });
               
        //Perfil - Detalle
        $("#tabla_guia_id").on('click', '.btn_perfil_guia', function(){
            select_guia_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/guia_transportistas/modal_detalle/';
            $("#myModal").load(ruta_url);
        });        
        
        $("#nueva_guia").attr('href', base_url+'index.php/guias_transportistas/operacion');        
        //Modificar        
        $('#tabla_guia_id').on('click', '.btn_editar', function(){
            let guia_id = $(this).attr('id');
            let url_l = base_url + 'index.php/guia_transportistas/operacion/#' + guia_id;
            window.location.href = url_l;
        });
        
        //Enviar sunat interno
        //////////////////////////////////////////////////////////
        $('#tabla_guia_id').on('click', '.API_SUNAT', function(){
            var guia_id = $(this).attr('id');
            let url_xml = base_url + "index.php/guias_transportistas/enviarSunat/" + guia_id;
            console.log(url_xml);
            $.getJSON(url_xml)            
            .done(function(data){        
                toast('success', 2500, data.message);
                $("#tabla_guia_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                toast('success', 2500, jqxhr + '---' + textStatus + '-' + error);
                console.log("Nivel 1: Error en local, envio de datos POST: " + err);
            });                        
        });
                
        //Enviar API_SUNAT
        $('#tabla_guia_id').on('click', '.API_SUNAT---', function(){   
            var guia_id = $(this).attr('id');
            let ruta = base_url + "index.php/WS_guia_transportistas/api_guia_transportistas/" + guia_id;
            $.getJSON(ruta)
            .done(function(data){
                var datosJSON = JSON.stringify(data);
                $.post(api_monstruo_guias,{datosJSON})
                .done(function(res){
                    toast('success', 2500, 'Datos enviados correctamente.');
                })

                $("#tabla_guia_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            })
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log("Error al enviar: " + err);
            });
        });
        ////////////////////////////////////////////////////////        
    });    
    
    $('#entidad').autocomplete({
        source: base_url + 'index.php/WS_entidades/buscador_entidad',
        minLength: 2,
        select: function (event, ui) {
            $('#entidad_id').val(ui.item.id);
        }
    });
    
    carga_inicial();
    
    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_guia_transportistas/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
        $.getJSON(url_l)
            .done(function (data) {
                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga);

                var numero_orden = 1;
                (data.guias).forEach(function (repo) {
                    agregarFila(numero_orden, repo.guia_id, repo.entidad, repo.fecha_emision, repo.fecha_traslado, repo.serie, repo.numero, repo.venta_numero, repo.estado_operacion);
                    numero_orden ++;
                });                                                
        });
    }    
   
    function agregarFila(numero_orden, guia_id, entidad, fecha_emision, fecha_traslado, serie, numero, venta_numero, estado_operacion){            
        //venta_numero    = (venta_numero == null) ? '' : venta_numero.substring(0, venta_numero.length - 1);  
        var mail;
        var editar;
        var enviar_sunat;
        switch (estado_operacion) {
            case '0'://no enviado
                mail = '-';
                editar = '<button class="btn btn-default btn-xs btn_editar" id='+guia_id+'><i class="glyphicon glyphicon-pencil"></i></button>';
                enviar_sunat = '<button class="btn btn-default btn-xs API_SUNAT" id='+guia_id+'><img src=' + base_url + 'images/logo_sunat.jpg data-id="40" class="descargar-pdf" width="25px"></button>';
                break;
              
            case '1'://aceptado
                mail = '<button class="btn btn-default btn-xs btn-mail" id='+guia_id+'><i class="glyphicon glyphicon-envelope"></i></button>';
                editar = 'Enviado Sunat';
                enviar_sunat = '-';
                break;
            
            case '2'://rechazado
                mail = '-';
                editar = '-';
                enviar_sunat = '-';
                break;
        }

        var fila = '<tr class="seleccionado tabla_fila">';        
        fila += '<td align="center"><a id="'+guia_id+'" class="btn btn-default btn-xs btn_perfil_guia" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+serie + '-' + numero+'</td>';
        fila += '<td>'+entidad+'</td>';
        fila += '<td>'+fecha_emision+'</td>';
        fila += '<td>'+fecha_traslado+'</td>';        
        fila += '<td align="center"><a target="_blank" href="'+base_url+'index.php/guias_transportistas/pdf_a4/'+guia_id+'"><img title="Ver Pdf" src="'+base_url+'images/pdf.png"></a></td>';        
        fila += '<td align=center>'+editar+'</td>';
        fila += '<td align=center>'+enviar_sunat+'</td>';
        
        fila += '</tr>';
        $("#tabla_guia_id").append(fila);    
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