<style>    
    /* Agregando Inputs */
    .input-group {width: 100%;}
    .input-group-addon { min-width: 180px;text-align: right;}    
    
    .panel-title{
        font-size: 13px;
        font-weight: bold;
    }
    
    .arranca_oculto{
        display: none;
    }
</style>
<div class="modal-dialog bd-example-modal-lg" role="document">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="col-md-8" style="text-align: center"><h3 id="label_guia"></h3></div>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <img id="modal_foto" height="120px">
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-body" style="border:1px solid #7FB3D5;border-radius:6px;">
                        <div id="empresa_ruc"></div>
                        GUIA DE REMISIÓN REMITENTE
                        <div id="numero_guia"></div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <table>
                        <tr>                            
                            <td>Fecha de Emisión:</td>                            
                            <td><div id="fecha_emision"></div></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>                            
                            <td>Fecha Inicio de Traslado:</td>
                            <td><div id="fecha_traslado"></div></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>                            
                            <td>Destinatario:</td>
                            <td><div id="destinatario"></div></td>
                            <td>Punto de partida:</td>
                            <td><div id="partida"></div></td>
                        </tr>
                        <tr>                            
                            <td>RUC:</td>
                            <td><div id="destinatario_ruc"></div></td>
                            <td>Punto de llegada:</td>
                            <td><div id="llegada"></div></td>
                        </tr>
                        <tr>                            
                            <td>Motivo:</td>
                            <td><div id="motivo"></div></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>                            
                            <td>Modalidad:</td>
                            <td><div id="modalidad"></div></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><div id="label_1"></div></td>
                            <td><div id="texto_1"></div></td>
                            <td><div id="label_2"></div></td>
                            <td><div id="texto_2"></div></td>
                        </tr>
                    </table>
                    
                    <br>
                    <table>
                        <tr>
                            <td>Documentos adjuntos: <div id="documentos_adjuntos"></div></td>
                        </tr>
                    </table>

                    <br>
                    <table id="tabla" class="table tabla_items" style="display:none" border="0">
                        <thead>
                            <tr>
                                <th>Cant.</th>
                                <th>Unid. Medida</th>
                                <th>Descripcion</th>                                                                
                            </tr>
                        </thead>                    
                        <tbody>                                                      
                        </tbody>                    
                    </table>   
                    
                    
                    <div id="div_numero_bultos">
                        <table>
                            <tr>
                                <td>Numero bultos:</td>
                                <td><div id="numero_bultos"></div></td>
                            </tr>
                        </table>
                    </div>                    
                    
                    <table>
                        <tr>
                            <td>Peso bruto:</td>
                            <td><div id="peso_total"></div></td>
                        </tr>
                        <tr>
                            <td>Notas:</td>
                            <td><div id="notas"></div></td>
                        </tr>
                    </table>
                                        
                </div>               
            </div>
            <br>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    
    var base_url = '<?PHP echo base_url();?>';    
    var empresa = JSON.parse(localStorage.getItem("empresas"));
    $("#div_numero_bultos").hide();    

    let url_cabecera = base_url + 'index.php/WS_guias/ws_cabecera/' + select_guia_id;
    $.getJSON(url_cabecera)
    .done(function (data) {
        $("#label_guia").text("Detalle Guia: " + data.serie + "-" + data.numero);
        $('#modal_foto').attr('src', base_url+'images/empresas/'+empresa.foto);
        $("#empresa_ruc").text('RUC: '+empresa.ruc);
        $("#numero_guia").text('Nº: ' + data.serie + '-' + data.numero);
        $("#fecha_emision").text(data.fecha_emision);
        $("#fecha_traslado").text(data.fecha_traslado);
        $("#numero_bultos").text(data.numero_bultos);
        $("#peso_total").text(data.peso_total);
        $("#notas").text(data.notas);
        
        if(data.guia_motivo_traslado_id == 7){
           $("#div_numero_bultos").show();    
        }
        
        let url_entidad = base_url + 'index.php/WS_entidades/select/' + data.destinatario_id;
        $.getJSON(url_entidad)
        .done(function (data_entidad) {
            $("#destinatario").text(data_entidad.entidad);
            $("#destinatario_ruc").text(data_entidad.numero_documento);
        });
        
        let url_ubigeo_salida = base_url + 'index.php/WS_ubigeos/datos_ubigeo/' + data.partida_ubigeo;
        $.getJSON(url_ubigeo_salida)
        .done(function (data_ubigeo) {            
            $("#partida").text(data.partida_direccion + '-' + data_ubigeo.datos_ubigeo.distrito + '-' + data_ubigeo.datos_ubigeo.provincia + '-' + data_ubigeo.datos_ubigeo.departamento);
        });
        
        let url_ubigeo_llegada = base_url + 'index.php/WS_ubigeos/datos_ubigeo/' + data.llegada_ubigeo;
        $.getJSON(url_ubigeo_llegada)
        .done(function (data_ubigeo) {
            $("#llegada").text(data.llegada_direccion + '-' + data_ubigeo.datos_ubigeo.distrito + '-' + data_ubigeo.datos_ubigeo.provincia + '-' + data_ubigeo.datos_ubigeo.departamento);
        });
        
        let url_motivo = base_url + 'index.php/WS_guia_motivo_traslados/select_unDato/' + data.guia_motivo_traslado_id + '/guia_motivo_traslado';
        $.getJSON(url_motivo)
        .done(function (data_motivo) {
            $("#motivo").text(data_motivo.guia_motivo_traslado);
        });
        
        let url_modalidad = base_url + 'index.php/WS_guia_modalidad_traslados/select_unDato/' + data.guia_modalidad_traslado_id + '/guia_modalidad_traslado';
        $.getJSON(url_modalidad)
        .done(function (data_modalidad) {
            $("#modalidad").text(data_modalidad.guia_modalidad_traslado);
        });
        
        if(data.guia_modalidad_traslado_id == 1){
            $("#label_1").text('Ruc');
            $("#label_2").text('Razón Social');
            
            let url_entidad_transporte = base_url + 'index.php/WS_entidades/select/' + data.entidad_id_transporte;
            $.getJSON(url_entidad_transporte)
            .done(function (data_entidad) {
                $("#texto_1").text(data_entidad.entidad);
                $("#texto_2").text(data_entidad.numero_documento);
            });
        }else if(data.guia_modalidad_traslado_id == 2){
            $("#label_1").text('DNI');
            $("#texto_1").text(data.dni_conductor);
            $("#label_2").text('Placa');
            $("#texto_2").text(data.vehiculo_placa);
        }                        

        $("#motivo_traslado option[value='"+data.guia_motivo_traslado_id+"']").prop('selected', true);
        $("#modalidad_traslado option[value='"+data.guia_modalidad_traslado_id+"']").prop('selected', true);
        $("#ruc").val(data.transporte_ruc);
        $("#razon_social").val(data.transporte_razon);
        $("#dni").val(data.dni_conductor);
        $("#placa").val(data.vehiculo_placa);

        $("#peso_total").val(data.peso_total);
        $("#notas").val(data.notas);
        $("#numero_bultos").val(data.numero_bultos);
        $("#entidad_id").val(data.destinatario_id);                                                  

    });
            
    let url_detalle = base_url + 'index.php/WS_guia_detalles/ws_select/' + select_guia_id;
    $.getJSON(url_detalle)
    .done(function (data) {
        (data).forEach(function (repo) {
            agregarFila(repo.producto, repo.producto_id, repo.unidad, repo.cantidad)
        });
    });

    var adjunto_numero = '';
    let url_venta_guia = base_url + 'index.php/WS_venta_guias/ws_select_ventas/' + select_guia_id;
    console.log(url_venta_guia);
    $.getJSON(url_venta_guia)
    .done(function (data) {
        (data).forEach(function (repo) {
            adjunto_numero += repo.serie + "-" + repo.numero + " // ";
        }); 
        $("#documentos_adjuntos").text(adjunto_numero);
    });
    
    function agregarFila(producto, producto_id, unidad, cantidad){
        var fila = '<tr class="cont-item fila_generada" >';
        fila += '<td style="border:0;">'+cantidad+'</td>';
        fila += '<td style="border:0;">'+unidad+'</td>';
        fila += '<td style="border:0;" class="col-sm-4">'+producto+'</td>';                
        fila += '</tr>';

        $("#tabla").css("display","block");
        $("#tabla tbody").append(fila);    
    }
    
</script>