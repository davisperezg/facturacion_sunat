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
<h2 align="center">Correo<div class="tamanio_pequenio">(Para enviar comprobantes a clientes)</div></h2>
<div align="center" class="container">
    <div class="row-fluid">
        <table id="tabla_correo" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Host</th>
                    <th>port</th>
                    <th>user</th>
                    <th>correo_cifrado</th>
                    <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                </tr>
            </thead>
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

    carga_inicial();
    
    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_correos/select_all/';
        $.getJSON(url_l)
        .done(function (data) {
            console.log(data);
            agregarFila(data.id, data.host, data.port, data.user, data.pass, data.correo_cifrado);
        });
    }    
   
    function agregarFila(correo_id, host, port, user, pass, correo_cifrado){    
        var fila = '<tr class="seleccionado tabla_fila">';        
        fila += '<td align="center">'+1+'</td>';
        fila += '<td>'+host+'</td>';
        fila += '<td>'+port+'</td>';
        fila += '<td>'+user+'</td>';
        fila += '<td>'+correo_cifrado+'</td>';
        fila += '<td align="center"><a id="'+correo_id+'" class="btn btn-default btn-xs btn-editar" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';
        fila += '</tr>';
        $("#tabla_correo").append(fila);    
    }
    
    $(document).ready(function(){
        //Modificar
        $('#tabla_correo').on('click', '.btn-editar', function(){
            let correo_id = $(this).attr('id');
            let url_l = base_url + 'index.php/correos/modal_operacion/';
            $("#myModal").load(url_l);
            
            ruta_url_item = base_url + 'index.php/WS_correos/ws_select/' + correo_id;
            $.getJSON(ruta_url_item)
            .done(function (data){
                console.log(data);
                $('#modal_correo_id').val(correo_id);
                $('#modal_correo_user').val(data.user);
                $('#modal_correo_pass').val(data.pass);
                $('#modal_correo_repetir_pass').val(data.pass);
                
                $('#modal_host').val(data.host);
                $('#modal_port').val(data.port);
                $('#modal_correo_cifrado').val(data.correo_cifrado);
                
                $('#modal_correo_notas').val(data.notas);
            })
        });
        
    });        

</script>