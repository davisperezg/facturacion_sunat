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
<h2 align="center">Entidades<div class="tamanio_pequenio">Clientes/Proveedores</div></h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nueva_entidad" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nueva Entidad</button>           
        </div>

        <div class="col-md-1" >
        </div> 
        <div class="col-md-2" >
            <select id="buscar_tipo_entidades" class="form-control form-control-sm">                
            </select>
        </div> 
        <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" id="entidad" placeholder="Buscar por entidad">
            <input type="hidden" id="entidad_id" />
        </div>   

        <div class="col-md-1" >
            <button class="btn btn-default" type="button" id="btn_buscar_entidad" name="btn_buscar_entidad"><span class="glyphicon glyphicon-search"></span></button>   
        </div>                   
    </div>
    <br>
    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_entidad_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Tipo D.</th>
                    <th>N. Documento</th>
                    <th>Razón Social</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Correo</th>
                    <th>Ubicación</th>
                    <th>Cuentas<br>Bancarias</th>
                    <th>Contactos</th>
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
    var base_url = '<?php echo base_url();?>';
    var total_filas = 0;
    var filas_por_pagina = 20;
    var pagina_inicial = 1;
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var tipo_entidad_id;
    var entidad_id_pro = '';
    var razon_social_pro = '';
    var numero_documento_pro = '';
    
    let tipo_entidades = JSON.parse(localStorage.getItem("tipo_entidades"));
    sortJSON(tipo_entidades, 'id', 'desc');
    
    (tipo_entidades).forEach(function (repo) {
        $('#buscar_tipo_entidades').prepend("<option value='" + repo.id + "'>" + repo.tipo_entidad + "</option>");
    });
    $('#buscar_tipo_entidades').prepend("<option value='' selected >Buscar Tipo</option>");
    
    $(document).ready(function(){
        $("#btn_importar_excel").click(function(){
            $("#myModal").load(base_url + 'index.php/entidades/modal_importar_excel');                                            
        });
        
        //BUSCAR filtros
        $('#btn_buscar_entidad').on('click', function(){
            pagina = 1; //
            param_entidad_id = ($('#entidad_id').val() == '') ? param_stand_url :  $('#entidad_id').val();
            param_tipo_entidad_id = ($('#buscar_tipo_entidades').val() == '') ? param_stand_url :  $('#buscar_tipo_entidades').val();
            $("#tabla_entidad_id > tbody").remove();

            var ruta_url = base_url + 'index.php/WS_entidades/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_entidad_id;               
            console.log(ruta_url);
            $.getJSON(ruta_url)
                .done(function (data) {
                    sortJSON(data.ws_select_entidades, 'entidad_id', 'desc');
                    
                    carga = 1;//se usa para activar la pagina N. 1
                    total_filas = data.total_filas;
                    $("#lista_id_pagination > li").remove();
                    construir_paginacion(total_filas, filas_por_pagina, carga)
                    
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_entidades).forEach(function (repo) {
                        agregarFila(numero_orden, repo.tipo_entidad, repo.numero_documento, repo.entidad, repo.telefono_fijo_1, repo.telefono_movil_1, repo.email_1, repo.entidad_id);
                        numero_orden ++;
                    });
            });
        });
        
        //PAGINACION
        $('#div_contenedor').on('click', '.pajaro', function(){
            param_entidad_id = ($('#entidad_id').val() == '') ? param_stand_url :  $('#entidad_id').val();
            param_tipo_entidad_id = ($('#buscar_tipo_entidades').val() == '') ? param_stand_url :  $('#buscar_tipo_entidades').val();
            
            $('li').removeClass('active');
            $(this).parent().addClass('active');
                        
            pagina = $(this).text();
            $("#tabla_entidad_id > tbody").remove();

            var url_l = base_url + 'index.php/WS_entidades/ws_select/' + pagina + '/' + filas_por_pagina + '/' + param_entidad_id + '/' + param_tipo_entidad_id;
            console.log(url_l);            
            
            $.getJSON(url_l)
                .done(function (data) {
                    sortJSON(data.ws_select_entidades, 'entidad_id', 'desc');

                    total_filas = data.total_filas; 
                    var numero_orden = filas_por_pagina*(pagina-1)+1;
                    (data.ws_select_entidades).forEach(function (repo) {                                                
                        agregarFila(numero_orden, repo.tipo_entidad, repo.numero_documento, repo.entidad, repo.telefono_fijo_1, repo.telefono_movil_1, repo.email_1, repo.entidad_id);
                        numero_orden ++;
                    });
            });            
        });                        
                
        $("#tabla_entidad_id").on('click', '.btn_modificar_entidad', function(){
            entidad_id_pro = $(this).attr('id');
            ruta_url = base_url + 'index.php/entidades/modal_operacion/';
            $("#myModal").load(ruta_url);
        });
        
        $("#tabla_entidad_id").on('click', '.btn_ubicacion', function(){
            entidad_id_pro = $(this).attr('id');
            ruta_url = base_url + 'index.php/entidades/modal_ubicacion/';
            $("#myModal").load(ruta_url);                                    
            
        });
        
        $("#tabla_entidad_id").on('click', '.btn_cuenta_bancaria', function(){
            var entidad_id = $(this).attr('id');
            var razon_social = $(this).parent().parent().find('td').eq(3)[0].innerHTML;
            var numero_documento = $(this).parent().parent().find("td")[2].innerHTML;

            ruta_url = base_url + 'index.php/entidades/modal_cuenta_bancaria/';
            $("#myModal").load(ruta_url);                                    

            var url_l = base_url + 'index.php/WS_cuenta_entidades/ws_select/' + entidad_id;
            $.getJSON(url_l)
                .done(function (data) {
                    //console.log(data);
                    $('#modal_cuenta_entidad').text(razon_social + ' - ' + numero_documento);
                    $('#modal_cuenta_entidad_id').val(entidad_id);
                    sortJSON(data.ws_cuentas, 'entidad_id', 'desc');

                    (data.ws_cuentas).forEach(function (repo) {
                        agregarFila_cuenta(repo.banco, repo.tipo_cuenta, repo.moneda, repo.numero_cuenta, repo.titular, repo.cuenta_entidad_id);
                    });
            });                       
        });
        
        $("#tabla_entidad_id").on('click', '.btn_contactos', function(){
            var entidad_id = $(this).attr('id');
            var razon_social = $(this).parent().parent().find('td').eq(3)[0].innerHTML;
            var numero_documento = $(this).parent().parent().find("td")[2].innerHTML;
            ruta_url = base_url + 'index.php/contactos/modal_contactos/';
            $("#myModal").load(ruta_url);
            
            var url_l = base_url + 'index.php/WS_contactos/ws_select/' + entidad_id;
            //console.log(url_l);
            $.getJSON(url_l)
                .done(function (data) {
                    //console.log(data);
                    $('#modal_cuenta_entidad').text(razon_social + ' - ' + numero_documento);
                    $('#modal_contacto_entidad_id').val(entidad_id);
                    sortJSON(data.ws_contactos, 'entidad_id', 'desc');

                    (data.ws_contactos).forEach(function (repo) {
                        agregarFila_contacto(repo.contacto_id, repo.apellido_paterno, repo.apellido_materno, repo.nombres, repo.celular, repo.correo, repo.comentario);
                    });
            }); 
        });        
        //Perfil - Detalle
        $("#tabla_entidad_id").on('click', '.btn_perfil_entidad', function(){
            entidad_id_pro          = $(this).attr('id');
            razon_social_pro        = $(this).parent().parent().find('td').eq(3)[0].innerHTML;
            numero_documento_pro    = $(this).parent().parent().find("td")[2].innerHTML;
            ruta_url = base_url + 'index.php/entidades/modal_detalle/';
            $("#myModal").load(ruta_url);
        });
        
        $("#tabla_entidad_id").on('click', '.btn_eliminar_entidad', function(){            
            var entidad_id = $(this).attr('id');            
            var x = confirm("Desea eliminar esta entidad:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/WS_entidades/delete_item/' + entidad_id;
                $.getJSON(ruta_url_item)
                        .done(function (data){
                            console.log('elimiación correcta' + data);
                        });
                        
//                var parent = $(this).parent("td").parent("tr");
//                parent.fadeOut('slow'); //Borra la fila afectada                
                $("#tabla_entidad_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                carga_inicial();
            }
        });        
    });
    
    $("#btn_nueva_entidad").click(function(){
        $("#myModal").load('<?php echo base_url()?>index.php/entidades/modal_operacion');
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
        var url_l = base_url + 'index.php/WS_entidades/ws_select/' + pagina_inicial + '/' + filas_por_pagina + '/' + param_stand_url + '/' + param_stand_url + '/' + param_stand_url;
        $.getJSON(url_l)
            .done(function (data) {
                //sortJSON(data.ws_select_entidades, 'entidad_id', 'desc');

                carga = 1;//solo se usa al cargar la página, para activar la pagina N. 1
                total_filas = data.total_filas;
                construir_paginacion(total_filas, filas_por_pagina, carga);

                var numero_orden = 1;
                (data.ws_select_entidades).forEach(function (repo) {
                    agregarFila(numero_orden, repo.tipo_entidad, repo.numero_documento, repo.entidad, repo.telefono_fijo_1, repo.telefono_movil_1, repo.email_1, repo.entidad_id);
                    numero_orden ++;
                });
        });
    }    
   
    function agregarFila(numero_orden, tipo_entidad, numero_documento, entidad, telefono_fijo_1, telefono_movil_1, email_1, entidad_id){    
        
        telefono_fijo_1     = (telefono_fijo_1 == null) ? '' : telefono_fijo_1;
        telefono_movil_1    = (telefono_movil_1 == null) ? '' : telefono_movil_1;
        email_1             = (email_1 == null) ? '' : email_1;
        
        var fila = '<tr class="seleccionado tabla_fila">';        
        fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_perfil_entidad" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+tipo_entidad+'</td>';
        fila += '<td>'+numero_documento+'</td>';
        fila += '<td>'+entidad+'</td>';
        fila += '<td>'+telefono_fijo_1+'</td>';
        fila += '<td>'+telefono_movil_1+'</td>';
        fila += '<td>'+email_1+'</td>';
        fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_ubicacion" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-globe"></i></a></td>';
        fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_cuenta_bancaria" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-piggy-bank"></i></a></td>';
        fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_contactos" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-user"></i></a></td>';
        
        if(entidad_id != 1){
            fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-default btn-xs btn_modificar_entidad" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';
            fila += '<td align="center"><a id="'+entidad_id+'" class="btn btn-danger btn-xs btn_eliminar_entidad"><i class="glyphicon glyphicon-remove"></i></a></td>';
        }
                
        fila += '</tr>';
        $("#tabla_entidad_id").append(fila);    
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