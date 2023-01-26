<style>
    .derecha_text { 
        text-align: right; 
    }
    .centro_text { 
        text-align: center; 
    }
</style>
<h2 align="center">Unidades</h2>
<br>
<div class="container">
    <br>

    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Código</th>
                    <th>Unidad</th>
                    <th class="centro_text">Activar</th>
                </tr>
            </thead>
            <tbody role="rowgroup">
                
            </tbody>
        </table>
    </div>
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    
    $(document).ready(function(){
        //modal modificar
        $("#tabla_id").on('click', '.btn_activar', function(){
            var id = $(this).attr('id');
            var data_activo = $(this).data("activo");
            var ruta_url = base_url + 'index.php/WS_unidades/activar/' + id + '/' + data_activo;
            $.getJSON(ruta_url)
            .done(function (data) {
                $("#tabla_id > tbody").remove();
                $("#lista_id_pagination > li").remove();
                numero_orden = 1;
                carga_inicial();
            });
        });
    });
    carga_inicial();
    
    var numero_orden = 1;
    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_unidades/select';
        $.getJSON(url_l)
            .done(function (data) {
                (data).forEach(function (repo) {
                    agregarFila(numero_orden, repo.id, repo.codigo, repo.unidad, repo.activo);
                    numero_orden ++;
                });
        });
    }
    
    var color = '';
    var color_fila = '';
    var contador_fila = 1;
    function agregarFila(numero_orden, id, codigo, unidad, activo){
        if(activo == 1){
            color = 'btn-success';
            texto_unidad = 'Activo';
        }else{
            color = 'btn-default';
            texto_unidad = 'desactivo';
        }
        
        color_fila = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;        
        var fila = '<tr ' + color_fila + ' class="seleccionado tabla_fila">';
        fila += '<td align="center">'+numero_orden+'</td>';
        fila += '<td>'+codigo+'</td>';
        fila += '<td>'+unidad+'</td>';
        fila += '<td align="center"><a data-activo="' + activo + '" id="' + id + '" class="btn ' + color + ' btn-sm btn_activar">' + texto_unidad + '</a></td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);
    }
</script>