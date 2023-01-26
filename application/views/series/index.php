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
<h2 align="center">Series</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nueva_serie" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nueva Entidad</button>
            <a class="btn btn-primary btn-sm" id="exportar_entidad"> Reporte Entidades</a>
            <button id="btn_subir_serie"  class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Importar Entidades</button>            
        </div>

        <div class="col-md-1" >
        </div> 
        <div class="col-md-2" >
        </div> 
        <div class="col-md-4">
        </div>   

        <div class="col-md-1" >            
        </div>                   
    </div>
    <br>
    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_serie_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Tipo Documento</th>
                    <th>Numero</th>
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
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));    
    var param_stand_url = datos_configuracion.param_stand_url;
    var tipo_documento_id_select;
    var serie_id = '';
     
    var url_serie = base_url + 'index.php/WS_series/series_defecto';
    $.getJSON(url_serie)
        .done(function (data) {
            localStorage.setItem("series_defecto", JSON.stringify(data.ws_select_series));        
    });
    
    $(document).ready(function(){                     
                
        $("#tabla_serie_id").on('click','.btn_modificar_serie', function(){
            serie_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/series/modal_operacion/';
            $("#myModal").load(ruta_url);
        });      
        //Perfil - Detalle
        $("#tabla_serie_id").on('click','.btn_perfil_serie', function(){
            serie_id = $(this).attr('id');
            ruta_url = base_url + 'index.php/series/modal_detalle/';
            $("#myModal").load(ruta_url);
        });
        
        $("#tabla_serie_id").on('click', '.btn_eliminar_serie', function(){            
            var serie_id = $(this).attr('id');            
            var x = confirm("Desea eliminar esta serie:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/WS_series/delete_item/' + serie_id;
                $.getJSON(ruta_url_item)
                        .done(function (data){
                            console.log('elimiación correcta' + data);
                        });
                        
                var parent = $(this).parent("td").parent("tr");
                parent.fadeOut('slow'); //Borra la fila afectada                
//                $("#tabla_serie_id > tbody").remove();
//                $("#lista_id_pagination > li").remove();
//                carga_inicial();
            }
        });        
    });
    
    $("#btn_nueva_serie").click(function(){
        serie_id = '';
        $("#myModal").load('<?php echo base_url()?>index.php/series/modal_operacion');
    }); 
    
    carga_inicial();
    
    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_series/ws_select';
        //console.log(url_l);
        $.getJSON(url_l)
            .done(function (data) {
                //sortJSON(data.ws_select_series, 'serie', 'desc');
                var numero_orden = 1;
                (data.ws_select_series).forEach(function (repo) {                    
                    agregarFila(numero_orden, repo.serie_id, repo.tipo_documento, repo.serie);
                    numero_orden ++;
                });
            });
    }
   
    function agregarFila(numero_orden, serie_id, tipo_documento, serie){
        
        var fila = '<tr class="seleccionado tabla_fila">';        
        fila += '<td align="center"><a id="'+serie_id+'" class="btn btn-default btn-xs btn_perfil_serie" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>'+tipo_documento+'</td>';
        fila += '<td>'+serie+'</td>';
        fila += '<td align="center"><a id="'+serie_id+'" class="btn btn-default btn-xs btn_modificar_serie" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';
        fila += '<td align="center"><a id="'+serie_id+'" class="btn btn-danger btn-xs btn_eliminar_serie"><i class="glyphicon glyphicon-remove"></i></a></td>';
        
        fila += '</tr>';
        $("#tabla_serie_id").append(fila);    
    }                
    
</script>    