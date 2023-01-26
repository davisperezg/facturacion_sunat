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
    <h2 align="center">Libros Electrónicos - Compras 8.1</h2>        
    <div class="row">
        <div class="col-xs-1">
        </div> 
        <div class="col-xs-5" style="padding-bottom: 10px">
            <button id="btn_nuevo_libro_compra" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo</button>
        </div>
    </div>
</div>
<br><br>
<div class="container">
    <div class="row-fluid">
        <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover">
            <thead>                
                <tr>
                    <th>N.</th>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Pdf</th>
                    <th>Excel</th>
                    <th>Txt</th>
                    <th>Generar</th>                    
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
    
    $(document).ready(function(){
        $("#btn_nuevo_libro_compra").click(function(){
            $("#myModal").load('<?php echo base_url()?>index.php/le_compras8_1/modal_nuevo');
        });
        
        //Generar
        $('#tabla_id').on('click', '.btn-generar', function(){
            let datos_id = $(this).attr('id');            
            var res     = datos_id.split("-");
            var mes     = res[1];
            var anio    = res[0];
            
            //insertar documentos en libros        
            var url_insert = base_url + 'index.php/le_compras8_1_detalles/ingresarDatosLibros/' + mes + '/' + anio;
            console.log(url_insert);
            //return false;
            $.getJSON(url_insert)
            .done(function (data) {            
            });
    
            let url_l = base_url + 'index.php/le_compras8_1/generar/#' + datos_id;
            window.location.href = url_l;
        });
    });    
        
    carga_inicial();
    //al cargar página    
    function carga_inicial(){
        //console.log('carga_inicial:'+operacion);
        var url_l = base_url + 'index.php/WS_le_compras8_1/ws_select/';
        $.getJSON(url_l)
        .done(function (data) {
            var numero_orden = 1;
            (data).forEach(function (repo) {
                agregarFila(numero_orden, repo.anio, repo.mes, repo.id);
                numero_orden ++;
            });
        });
    }
    
    var contador_fila = 1;
    var color = '';
    function agregarFila(numero_orden, anio, mes, id){        
        color = ((contador_fila % 2) == 0) ? "style='background-color: #F9E6E4'" : '';
        contador_fila ++;
        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a  id="'+id+'" class="btn btn-default btn-xs btn_perfil_venta" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>' + anio + '</td>';
        fila += '<td>' + mes_texto(mes) + '</td>';
        fila += '<td>Pdf</td>';
        fila += '<td>Excel</td>';
        fila += '<td>txt</td>';
        fila += '<td><button class="btn btn-default btn-xs btn-generar" id='+anio+'-'+mes+'>Generar</button></td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }        
        
</script>