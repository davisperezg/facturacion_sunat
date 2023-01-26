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
    <div class="row">
        <div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>
        <div class="col-md-8" style="text-align: center">
            <h2 align="center">Libros Electrónicos - Compras 8.2</h2>
            <h2 align="center" id="titulo_fecha"></h2>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
<br><br>
    <div class="row">
        <div class="col-xs-1">
        </div> 
        <div class="col-xs-6" style="padding-bottom: 10px">
            <button id="btn_agregar_operacion" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Ingresar Operación</button>
        </div>
        <div class="col-xs-4" style="padding-bottom: 10px">
            <button id="exportarExcel" class="btn btn-primary btn-sm">Descargar Excel</button>
            <button id="btn_importar_excel" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Importar Excel</button>
            <button id="descargarTxt" class="btn btn-warning btn-sm">Descargar txt</button>
        </div>
    </div>
<div class="row-fluid">
    <table role="grid" style="height: auto;" id="tabla_id" class="table table-bordered table-responsive table-hover table-striped">
        <thead>                
            <tr>
                <th>N.</th>
                <th>Periodo</th>
                <th>Código único</th>
                <th>Número correlativo</th>
                
                <th>Fecha de Emisión</th>
                <th>Tipo<br>documento</th>
                <th>Serie</th>       
                <th>Número</th>
                
                <th>Adquisición</th>
                <th>Otros<br>conceptos</th>
                <th>Importe<br>total</th>
                
                <th>Tipo comprobante<br>pago</th>
                <th>serie<br>pago</th>
                <th>Año DUA</th>
                <th>Número<br>pago</th>
                
                <th>Retención IGV</th>
                
                <th>Código<br>Moneda</th>
                <th>Tipo de<br>cambio</th>

                <th>Pais</th>
                <th>Razón Social</th>
                <th>Domicilio Extranjero</th>
                <th>Número<br>documento</th>
                
                <th>Número<br>documento<br>beneficiaio</th>
                <th>Razón social<br>beneficiario</th>
                <th>País beneficiario</th>
                
                <th>Vinculo</th>
                
                <th>Renta<br>bruta</th>
                <th>Deducción</th>
                <th>Renta<br>neta</th>
                <th>Taza<br>retención</th>
                <th>Impuesto<br>retenido</th>
                
                <th>Doble<br>imposición</th>
                <th>Exoneración<br>aplicada</th>
                <th>Tipo de<br>renta</th>
                <th>Modalidad</th>
                <th>Aplicación del<br>impuesto a la<br>renta</th>
                <th>Estado</th>
                <th class="centro_text"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
                <th class="centro_text"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody role="rowgroup">                
        </tbody>
    </table>
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    
    $("#img_atras").attr("src", base_url + "images/atras.png");
    $("#enlace_atras").attr("href", base_url + "index.php/le_compras8_2/index");
    
    var url_hash = $(location).attr('hash');
    var res =  url_hash.split("-");
    var mes = res[1];
    var anio = res[0].substring(1,5);
    $("#titulo_fecha").text(mes_texto(mes) + ' ' + anio);
    
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));
    var param_stand_url = datos_configuracion.param_stand_url;
    var catidad_decimales = datos_configuracion.catidad_decimales;
    
    let datos_empresa = JSON.parse(localStorage.getItem("empresas"));
    var ruc_empresa = datos_empresa.ruc;
    
    $(document).ready(function(){                
        $("#btn_agregar_operacion").click(function(){
            $("#myModal").load(base_url + 'index.php/le_compras8_2_detalles/modal_operacion');
        });
        
        $("#btn_importar_excel").click(function(){
            $("#myModal").load(base_url + 'index.php/le_compras8_2_detalles/modal_importar_excel');                                            
        });
        
        $(".btn_modificar").click(function(){
            $("#myModal").load(base_url + 'index.php/le_compras8_2_detalles/modal_operacion');
        });
        
        $('#exportarExcel').click(function(){            
            let url = base_url + 'index.php/le_compras8_2_detalles/exportarExcel/' + mes + '/' + anio;
            window.open(url, '_blank');
        });
        
        $('#descargarTxt').click(function(){            
            let url = base_url + 'index.php/le_compras8_2_detalles/descargarTxt/' + mes + '/' + anio + '/' + ruc_empresa;
            window.open(url, '_blank');
        });
        
        $("#tabla_id").on('click', '.btn_eliminar', function(e){            
            var id = $(this).attr('id');            
            var x = confirm("Desea eliminar este registro:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/le_compras8_2_detalles/delete_item/' + id;
                $.getJSON(ruta_url_item)
                .done(function (data){                    
                    toast('success', 1500, 'Operación creada correctamente');
                    $("#tabla_id > tbody").remove();
                    carga_inicial();
                });
            }
        });
        
        //modal modificar
        $("#tabla_id").on('click', '.btn_modificar', function(){
            var id = $(this).attr('id');
            
            $("#myModal").load(base_url + 'index.php/le_compras8_2_detalles/modal_operacion');                                

            ruta_url_item = base_url + 'index.php/WS_le_compras8_2_detalles/ws_select_item/' + id;
            $.getJSON(ruta_url_item)
            .done(function (data){
                $("#periodo").val(data.periodo);
                $("#codigo_unico").val(data.codigo_unico);
                $("#numero_correlativo").val(data.numero_correlativo);
                                                         
                $("#fecha_emision").val(data.fecha_emision_cf);
                $("#tipo_documento").val(data.tipo_documento);                
                $("#serie").val(data.serie);
                $("#numero").val(data.numero);
                                      
                $("#adquisicion").val(data.adquisicion);
                $("#otros_conceptos").val(data.otros_conceptos);
                $("#importe_total").val(data.importe_total);
                                                        
                $("#tipo_comprobante_pago").val(data.tipo_comprobante_pago);
                $("#serie_pago").val(data.serie_pago);
                $("#anio_dua").val(data.anio_dua);                
                $("#numero_pago").val(data.numero_pago);
                                                      
                $("#retencion_igv").val(data.retencion_igv);
                $("#codigo_moneda").val(data.codigo_moneda);
                $("#tipo_cambio").val(data.tipo_cambio);
                                                        
                $("#pais_sujeto").val(data.pais_sujeto);
                $("#razon_sujeto").val(data.razon_sujeto);
                $("#domicilio_sujeto").val(data.domicilio_sujeto);
                $("#numero_documento_sujeto").val(data.numero_documento_sujeto);
                                                      
                $("#numero_documento_beneficiario").val(data.numero_documento_beneficiario);
                $("#razon_beneficiario").val(data.razon_beneficiario);
                $("#pais_beneficiario").val(data.pais_beneficiario);
                                                            
                $("#vinculo").val(data.vinculo);
                $("#renta_bruta").val(data.renta_bruta);
                $("#deduccion").val(data.deduccion);
                $("#renta_neta").val(data.renta_neta);
                $("#taza_retencion").val(data.taza_retencion);
                $("#impuesto_retenido").val(data.impuesto_retenido);

                $("#doble_disposicion").val(data.doble_disposicion);
                $("#exoneracion_aplicada").val(data.exoneracion_aplicada);
                $("#tipo_renta").val(data.tipo_renta);
                $("#modalidad").val(data.modalidad);
                $("#aplica_ley").val(data.aplica_ley);                                
                $("#estado").val(data.estado);
                $("#id").val(data.id);
                
                $("#texto_titulo").text('Modificar Compra (No domiciliado) 8.2');
                $("#btn_guardar_operacion").text('Modificar');
            });                        
        });
        
        //modal detalle
        $("#tabla_id").on('click', '.btn_detalle', function(){
            var id = $(this).attr('id');
            
            $("#myModal").load(base_url + 'index.php/le_compras8_2_detalles/modal_detalle');                                

            ruta_url_item = base_url + 'index.php/WS_le_compras8_2_detalles/ws_select_item/' + id;
            $.getJSON(ruta_url_item)
            .done(function (data){
                $("#periodo").text(data.periodo);
                $("#codigo_unico").text(data.codigo_unico);
                $("#numero_correlativo").text(data.numero_correlativo);
                
                $("#fecha_emision").text(data.fecha_emision_cf);
                $("#tipo_documento").text(data.tipo_documento);
                $("#serie").text(data.serie);
                $("#numero").text(data.numero);
                
                $("#adquisicion").text(data.adquisicion);
                $("#otros_conceptos").text(data.otros_conceptos);
                $("#importe_total").text(data.importe_total);
                
                $("#tipo_comprobante_pago").text(data.tipo_comprobante_pago);
                $("#serie_pago").text(data.serie_pago);
                $("#anio_dua").text(data.anio_dua);
                $("#numero_pago").text(data.numero_pago);
                
                $("#retencion_igv").text(data.retencion_igv);
                $("#codigo_moneda").text(data.codigo_moneda);
                $("#tipo_cambio").text(data.tipo_cambio);
                
                $("#pais_sujeto").text(data.pais_sujeto);
                $("#razon_sujeto").text(data.razon_sujeto);
                $("#domicilio_sujeto").text(data.domicilio_sujeto);
                $("#numero_documento_sujeto").text(data.numero_documento_sujeto);
                
                $("#numero_documento_beneficiario").text(data.numero_documento_beneficiario);
                $("#razon_beneficiario").text(data.razon_beneficiario);
                $("#pais_beneficiario").text(data.pais_beneficiario);
                
                $("#vinculo").text(data.vinculo);
                $("#renta_bruta").text(data.renta_bruta);
                $("#deduccion").text(data.deduccion);
                $("#renta_neta").text(data.renta_neta);
                $("#taza_retencion").text(data.taza_retencion);
                $("#impuesto_retenido").text(data.impuesto_retenido);
                                              
                $("#doble_disposicion").text(data.doble_disposicion);
                $("#exoneracion_aplicada").text(data.exoneracion_aplicada);
                $("#tipo_renta").text(data.tipo_renta);
                $("#modalidad").text(data.modalidad);
                $("#aplica_ley").text(data.aplica_ley);
                $("#estado").text(data.estado);
            });
        });
    });

    carga_inicial();
    //al cargar página    
    function carga_inicial(){    
        //seleccionar en tabla de libros
        var url_l = base_url + 'index.php/WS_le_compras8_2_detalles/ws_select/'+ mes + '/' + anio;
        $.getJSON(url_l)
        .done(function (data) {            
            var numero_orden = 1;
            (data).forEach(function (repo) {
                agregarFila(numero_orden, repo.id, repo.periodo, repo.codigo_unico, repo.numero_correlativo, repo.fecha_emision_cf, repo.tipo_documento,
                repo.serie, repo.numero, repo.adquisicion, repo.otros_conceptos, repo.importe_total,
                repo.tipo_comprobante_pago, repo.serie_pago, repo.anio_dua, repo.numero_pago, repo.retencion_igv,
                repo.codigo_moneda, repo.tipo_cambio,
                repo.pais_sujeto, repo.razon_sujeto, repo.domicilio_sujeto, repo.numero_documento_sujeto,
                repo.numero_documento_beneficiario, repo.razon_beneficiario, repo.pais_beneficiario, repo.vinculo,
                repo.renta_bruta, repo.deduccion, repo.renta_neta, repo.taza_retencion, repo.impuesto_retenido,
                repo.doble_disposicion, repo.exoneracion_aplicada, repo.tipo_renta, repo.modalidad, repo.aplica_ley, repo.estado);
                numero_orden ++;
            });
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            console.log( "Algo ha fallado: " +  textStatus );
        });
    }    
  
    var contador_fila = 1;
    var color = '';
    function agregarFila(numero_orden, id, periodo, codigo_unico, numero_correlativo, fecha_emision_cf, tipo_documento,
                serie, numero, adquisicion, otros_conceptos, importe_total,
                tipo_comprobante_pago, serie_pago, anio_dua, numero_pago, retencion_igv,
                codigo_moneda, tipo_cambio,
                pais_sujeto, razon_sujeto, domicilio_sujeto, numero_documento_sujeto,
                numero_documento_beneficiario, razon_beneficiario, pais_beneficiario, vinculo,
                renta_bruta, deduccion, renta_neta, taza_retencion, impuesto_retenido,
                doble_disposicion, exoneracion_aplicada, tipo_renta, modalidad, aplica_ley, estado){                
                
        color = ((contador_fila % 2) == 0) ? "style='background-color: #D3FAE1'" : '';
        contador_fila ++;    
    
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a  id="'+id+'" class="btn btn-default btn-xs btn_detalle" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>' + periodo + '</td>';
        fila += '<td>' + codigo_unico + '</td>';
        fila += '<td>' + numero_correlativo + '</td>';
        
        fila += '<td>'+fecha_emision_cf+'</td>';
        fila += '<td>'+tipo_documento+'</td>';
        fila += '<td>'+serie+'</td>';
        fila += '<td>'+numero+'</td>';
        fila += '<td>'+adquisicion+'</td>';
        fila += '<td>'+otros_conceptos+'</td>';
        fila += '<td>'+importe_total+'</td>';
        
        fila += '<td>'+tipo_comprobante_pago+'</td>';
        fila += '<td>'+serie_pago+'</td>';
        fila += '<td>'+anio_dua+'</td>';        
        fila += '<td>'+numero_pago+'</td>';
        fila += '<td>'+retencion_igv+'</td>';
        
        fila += '<td>'+codigo_moneda+'</td>';
        fila += '<td>'+tipo_cambio+'</td>';
                
        fila += '<td>'+pais_sujeto+'</td>';
        fila += '<td>'+razon_sujeto+'</td>';
        fila += '<td>'+domicilio_sujeto+'</td>';
        fila += '<td>'+numero_documento_sujeto+'</td>';
        
        fila += '<td>'+numero_documento_beneficiario+'</td>';
        fila += '<td>'+razon_beneficiario+'</td>';
        fila += '<td>'+pais_beneficiario+'</td>';
        fila += '<td>'+vinculo+'</td>';
        
        fila += '<td>'+renta_bruta+'</td>';
        fila += '<td>'+deduccion+'</td>';
        fila += '<td>'+renta_neta+'</td>';
        fila += '<td>'+taza_retencion+'</td>';
        fila += '<td>'+impuesto_retenido+'</td>';
        
        fila += '<td>'+doble_disposicion+'</td>';
        fila += '<td>'+exoneracion_aplicada+'</td>';
        fila += '<td>'+tipo_renta+'</td>';
        fila += '<td>'+modalidad+'</td>';
        fila += '<td>'+aplica_ley+'</td>';
        fila += '<td>'+estado+'</td>';
        fila += '<td align="center"><a id="'+id+'" class="btn btn-default btn-xs btn_modificar" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>';
        fila += '<td align="center"><a id="'+id+'" class="btn btn-danger btn-xs btn_eliminar"><i class="glyphicon glyphicon-remove"></i></a></td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }

</script>