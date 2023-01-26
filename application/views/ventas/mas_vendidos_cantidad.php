<div class="container">
    <div align="center" class="row-fluid">
        <h3>Productos mas vendidos(CANTIDAD)</h3>
    </div>
    
    <div class="row">
        <div class="col-xs-5 form-inline"  >
            <label>Fecha:</label><br>
            <input class="form-control input-sm" type="text" name="rec_in" id="rec_in" value="" placeholder="Desde">
            <input class="form-control input-sm" type="text" name="tec_nal" id="tec_nal" value="" placeholder="Hasta">
        </div>
                
        <div class="col-xs-2">            
            <br>
            <a name="buscar" id="buscar" class="btn btn-primary">Buscar</a>
        </div>
    </div>
    <br><br>
    <div align="center" class="container-fluid">
    <div class="row-fluid">
        <table id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Cantidad</th>                    
                </tr>
            </thead>
        </table>    
    </div>    
    <div id='div_contenedor'>
        <ul id="lista_id_pagination" class="pagination lista_paginacion">
        </ul>
    </div>
</div>
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">    
    $(document).ready(function(){
        $('#rec_in').datepicker();
        $("#tec_nal").datepicker();        
        
        $("#buscar").on('click', function(){
            $("#tabla_id > tbody").remove();
            carga_inicial();        
        });
        
    });
        
    //al cargar página    
    function carga_inicial(){
        //console.log('carga_inicial:'+operacion);
        var url_l = base_url + 'index.php/WS_kardex_promedio/mas_vendidos_cantidad/' + $('#rec_in').val() + '/' + $('#tec_nal').val();
        //console.log('url_l:'+url_l);
        $.getJSON(url_l)
            .done(function (data) {                
                var numero_orden = 1;
                (data).forEach(function (repo) {
                    agregarFila(numero_orden, repo.producto_id, repo.codigo, repo.producto, repo.suma_cantidad);
                    numero_orden ++;
                });
        });
    }
    
    var color;
    var contador_fila = 1;
    function agregarFila(numero_orden, producto_id, codigo, producto, suma_cantidad){
        color = ((contador_fila % 2) == 0) ? "style='background-color: #CEF6CE'" : '';
        contador_fila ++;
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center">'+numero_orden+'</td>';
        fila += '<td>'+codigo+'</td>';
        fila += '<td>'+producto+'</td>';        
        fila += '<td class="derecha_text">'+suma_cantidad+'</td>';        
        fila += '</tr>';
        $("#tabla_id").append(fila);
    }
    
</script>