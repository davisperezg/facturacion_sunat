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
<h2 align="center">Empresa</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
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
        <table role="grid" style="height: auto;" id="tabla_empresa_id" class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th>Perfil</th>
                    <th>RUC</th>
                    <th>Razón Social</th>
                    <th>Logo</th>
                    <th>Certificado Prueba</th>
                    <th>Certificado Producción</th>
                    <th>Modificar</th>
                    <th>Entorno</th>
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
    var ubigeo;
    var regimen;
    var entorno;
    
    $(document).ready(function(){                     
        $("#tabla_empresa_id").on('click','.btn_modificar_empresa', function(){
            empresa_id = $(this).attr('id');
            ubigeo = $(this).data("ubigeo");
            
            ruta_url = base_url + 'index.php/empresas/modal_operacion/';
            $("#myModal").load(ruta_url);
        });
        //Perfil - Detalle
        $("#tabla_empresa_id").on('click','.btn_perfil', function(){
            var empresa_id = $(this).attr('id');
            ubigeo = $(this).data("ubigeo");
            ruta_url = base_url + 'index.php/empresas/modal_detalle/';
            $("#myModal").load(ruta_url);                                    

            ruta_url_item = base_url + 'index.php/WS_empresas/ws_select_item/' + empresa_id;
            $.getJSON(ruta_url_item)
                .done(function (data){                                                        
                    $('#modal_foto').attr('src', base_url+'images/empresas/'+data.foto);

                    $('#modal_empresa').text(data.empresa);
                    $('#modal_nombre_comercial').text(data.nombre_comercial);
                    $('#modal_ruc').text(data.ruc);
                    $('#modal_domicilio_fiscal').text(data.domicilio_fiscal);
                    $('#modal_usuario_secundario_user').text(data.usu_secundario_produccion_user);
                    $('#modal_ubigeo').text(data.ubigeo);
                    $('#modal_urb').text(data.urbanizacion);
                    $('#modal_pass_certificate').text(data.pass_certificate);
                    $('#modal_telefono_fijo').text(data.telefono_fijo);
                    $('#modal_telefono_fijo2').text(data.telefono_fijo2);
                    $('#modal_telefono_movil').text(data.telefono_movil);
                    $('#modal_telefono_movil2').text(data.telefono_movil2);                        
                    $('#modal_correo').text(data.correo);
                    let modo = (data.modo == 1) ? 'Produccion' : 'Beta';
                    $('#modal_modo').text(modo);
                    $('#regimen').text(data.regimen);
                    $('#codigo_sucursal_sunat').text(data.codigo_sucursal_sunat);
                });
        });
        
        $("#tabla_empresa_id").on('click','.btn_modificar_foto', function(){
            var empresa_id = $(this).attr('id');
                        
            ruta_url = base_url + 'index.php/empresas/modal_foto/';
            $("#myModal").load(ruta_url);
                    
            ruta_url_item = base_url + 'index.php/WS_empresas/ws_select_item/' + empresa_id;
            $.getJSON(ruta_url_item)
                .done(function (data){                       
                    foto_imagen = (data.foto == null) ? 'caja.jpg' : data.foto
                    $("#img_empresa").attr('src', base_url + 'images/empresas/'+foto_imagen);
                });                    
        });
        
        $("#tabla_empresa_id").on('click','.btn_certificado_prueba', function(){
            ruta_url = base_url + 'index.php/empresas/modal_certificado_prueba/';
            $("#myModal").load(ruta_url);
                    
            ruta_url_item = base_url + 'index.php/WS_empresas/ws_select_item';
            $.getJSON(ruta_url_item)
                .done(function (data){                        
                    foto_imagen = (data.certi_prueba_nombre == null) ? '' : data.certi_prueba_nombre;
                    $("#img_empresa").attr('src', base_url + 'application/libraries/certificado_digital/prueba/'+foto_imagen);
                    $("#certi_prueba_nombre").text(foto_imagen);
                    $("#certi_prueba_password").val(data.certi_prueba_password);
                });                    
        });
        
        $("#tabla_empresa_id").on('click','.btn_entorno', function(){
            entorno = $(this).data("entorno");
            ruta_url = base_url + 'index.php/empresas/modal_entorno/';
            $("#myModal").load(ruta_url);
            console.log('entorno'+entorno);

        });
                                
        $("#tabla_empresa_id").on('click','.btn_certificado_produccion', function(){
            ruta_url = base_url + 'index.php/empresas/modal_certificado_produccion/';
            $("#myModal").load(ruta_url);
                    
            ruta_url_item = base_url + 'index.php/WS_empresas/ws_select_item';
            $.getJSON(ruta_url_item)
                .done(function (data){
                    foto_imagen = (data.nombre_certificado_produccion == null) ? '' : data.nombre_certificado_produccion;
                    $("#img_empresa").attr('src', base_url + 'application/libraries/certificado_digital/produccion/'+foto_imagen);
                    $("#certi_produccion_nombre").text(foto_imagen);
                    $("#certi_produccion_password").val(data.certi_produccion_password);
                }); 
        });
    });
    carga_inicial();    
    function carga_inicial(){
        //CARGA INICIAL
        var url_l = base_url + 'index.php/WS_empresas/ws_select_item';
        $.getJSON(url_l)
            .done(function (data) {
                agregarFila(data.empresa, data.ruc, data.domicilio_fiscal, data.ubigeo, data.modo);
            });
    }
   
    function agregarFila(empresa, ruc, domicilio_fiscal, ubigeo, modo){        
        var fila = '<tr class="seleccionado tabla_fila">';        
        fila += '<td align="center"><a id="'+1+'" data-ubigeo="'+ubigeo+'" class="btn btn-default btn-xs btn_perfil" data-toggle="modal" data-target="#myModal">1</a></td>';
        fila += '<td>'+ruc+'</td>';
        fila += '<td>'+domicilio_fiscal+'</td>';
        fila += '<td align="center"><a id="'+1+'" class="btn btn-default btn-xs btn_modificar_foto" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-camera"></i></a></td>';
        fila += '<td align="center"><a id="'+1+'" class="btn btn-default btn-xs btn_certificado_prueba" data-toggle="modal" data-target="#myModal">Certificado Prueba</a></td>';
        fila += '<td align="center"><a id="'+1+'" class="btn btn-default btn-xs btn_certificado_produccion" data-toggle="modal" data-target="#myModal">Certificado Producción</a></td>';
        fila += '<td align="center"><a id="'+1+'" data-ubigeo="'+ubigeo+'" class="btn btn-default btn-xs btn_modificar_empresa" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-pencil"></i></a></td>';        
        fila += '<td align="center"><a id="'+1+'" data-entorno="'+modo+'" class="btn btn-default btn-xs btn_entorno" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-indent-right"></i></a></td>';                
        fila += '</tr>';
        $("#tabla_empresa_id").append(fila);    
    }                
    
</script>    