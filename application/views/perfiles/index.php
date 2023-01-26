<style>    
    .derecha_text { 
        text-align: right; 
    }
    .centro_text { 
        text-align: center; 
    }
</style>
<h2 align="center">Perfiles</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button id="btn_nuevo_perfil" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo perfil</button>            
        </div>        
    </div>
    <br>

    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Perfil</th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
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
    let datos_configuracion     = JSON.parse(localStorage.getItem("datos_configuracion"));
    var param_stand_url         = datos_configuracion.param_stand_url;
    var texto_tipo_empleado;
    var modal_tipo_empleado_id;
    $(document).ready(function(){                                    
        //modal modificar
        $("#tabla_id").on('click', '.btn_modificar_perfil', function(){
            $("#myModal").load(base_url + 'index.php/tipo_empleados/modal_operacion');            
            texto_tipo_empleado = $(this).parent().parent().find('td').eq(1).text();
            modal_tipo_empleado_id = $(this).attr('id');
        });
    });
    
    $("#btn_nuevo_perfil").click(function(){
        texto_tipo_empleado = '';
        modal_tipo_empleado_id = '';
        $("#myModal").load(base_url + 'index.php/tipo_empleados/modal_operacion');
    });
    
    carga_inicial();    

    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_tipo_empleados/ws_select/';
        $.getJSON(url_l)
            .done(function (data) {                
                var numero_orden = 1;
                var contador_fila = 1;
                var color = '';
                (data.ws_tipo_empleados).forEach(function (repo) {
                    
                    color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
                    contador_fila ++;
                    var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
                    fila += '<td align="center">'+numero_orden+'</td>';
                    fila += '<td>'+repo.tipo_empleado+'</td>';        
                    fila += '<td align="center"><a id="'+repo.id+'" class="btn btn-default btn-xs btn_modificar_perfil" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';       
                    fila += '</tr>';
                    $("#tabla_id").append(fila);

                    numero_orden ++;
                });
        });
    }
</script>