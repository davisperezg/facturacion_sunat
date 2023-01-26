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
            <h2 align="center">Libros Electrónicos - Ventas 14.1</h2>
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
                <th>Fecha de Vencimiento</th>
                <th>Tipo<br>documento</th>
                <th>Serie</th>
                <th>Número</th>
                <th>Número<br>Final</th>

                <th>Tipo documento<br>cliente</th>
                <th>Número documento</th>
                <th>Razon Social/Nombres</th>

                <th>Exportación</th>
                <th>Base<br>Imponible</th>
                <th>Base<br>Imponible<br>Descuento</th>
                <th>IGV</th>
                <th>IGV<br>Descuento</th>
                <th>Exonerado</th>
                <th>Inafecto</th>
                <th>I.S.C.</th>
                <th>Arroz Pillado<br>Base Imponible</th>
                <th>Arroz Pillado<br>IGV</th>
                <th>ICBPER</th>
                <th>otros<br>conceptos</th>
                <th>Importe<br>Total</th>

                <th>Código<br>Moneda</th>
                <th>Tipo de<br>cambio</th>

                <th>Fecha<br>Emisión</th>
                <th>Tipo<br>Documento</th>
                <th>Serie</th>
                <th>Numero</th>

                <th>Identificación<br>contrato</th>
                <th>Error<br>tipo I</th>
                <th>Medio pago<br>Documentos<br>cancelados</th>
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
    $("#enlace_atras").attr("href", base_url + "index.php/le_ventas14_1/index");
    
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
            $("#myModal").load(base_url + 'index.php/le_ventas14_1_detalles/modal_operacion');
        });
        
        $("#btn_importar_excel").click(function(){
            $("#myModal").load(base_url + 'index.php/le_ventas14_1_detalles/modal_importar_excel');                                            
        });
        
        $(".btn_modificar").click(function(){
            $("#myModal").load(base_url + 'index.php/le_ventas14_1_detalles/modal_operacion');
        });
        
        $('#exportarExcel').click(function(){            
            let url = base_url + 'index.php/le_ventas14_1_detalles/exportarExcel/' + mes + '/' + anio;
            window.open(url, '_blank');
        });
        
        $('#descargarTxt').click(function(){            
            let url = base_url + 'index.php/le_ventas14_1_detalles/descargarTxt/' + mes + '/' + anio + '/' + ruc_empresa;
            window.open(url, '_blank');
        });
        
        $("#tabla_id").on('click', '.btn_eliminar', function(e){            
            var id = $(this).attr('id');            
            var x = confirm("Desea eliminar este registro:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/le_ventas14_1_detalles/delete_item/' + id;
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
            
            $("#myModal").load(base_url + 'index.php/le_ventas14_1_detalles/modal_operacion');                                

            ruta_url_item = base_url + 'index.php/WS_le_ventas14_1_detalles/ws_select_item/' + id;
            $.getJSON(ruta_url_item)
            .done(function (data){                
                $("#periodo").val(data.periodo);
                $("#codigo_unico").val(data.codigo_unico);
                $("#numero_correlativo").val(data.numero_correlativo);
                $("#fecha_emision").val(data.fecha_emision_cf);
                $("#fecha_vencimiento").val(data.fecha_vencimiento_cf);
                $("#tipo_documento").val(data.tipo_documento);
                $("#serie").val(data.serie);
                $("#numero").val(data.numero);
                $("#numero_final").val(data.numero_final);
                $("#tipo_cliente").val(data.tipo_cliente);
                $("#numero_documento").val(data.numero_documento);
                $("#cliente").val(data.cliente);
                $("#exportacion").val(data.exportacion);
                $("#base_imponible").val(data.base_imponible);
                $("#base_imponible_descuento").val(data.base_imponible_descuento);
                $("#igv").val(data.igv);
                $("#igv_descuento").val(data.igv_descuento);
                $("#exonerado").val(data.exonerado);
                $("#inafecto").val(data.inafecto);
                $("#isc").val(data.isc);
                $("#arroz_pillado_base_disponible").val(data.arroz_pillado_base_disponible);
                $("#arroz_pillado_igv").val(data.arroz_pillado_igv);
                $("#ICBPER").val(data.ICBPER);
                $("#otros_conceptos").val(data.otros_conceptos);
                $("#importe_total").val(data.importe_total);
                $("#codigo_moneda").val(data.codigo_moneda);
                $("#tipo_cambio").val(data.tipo_cambio);
                $("#da_fecha_emision").val(data.da_fecha_emision_cf);
                $("#da_tipo_documento").val(data.da_tipo_documento);
                $("#da_serie").val(data.da_serie);
                $("#da_numero").val(data.da_numero);
                $("#identificacion_contrato").val(data.identificacion_contrato);
                $("#error_tipo_1").val(data.error_tipo_1);
                $("#medio_pago_cancelacion").val(data.medio_pago_cancelacion);
                $("#estado").val(data.estado);
                $("#id").val(data.id);
                
                $("#texto_titulo").text('Modificar venta');
                $("#btn_guardar_operacion").text('Modificar');
            });                        
        });
        
        //modal detalle
        $("#tabla_id").on('click', '.btn_detalle', function(){
            var id = $(this).attr('id');
            
            $("#myModal").load(base_url + 'index.php/le_ventas14_1_detalles/modal_detalle');                                

            ruta_url_item = base_url + 'index.php/WS_le_ventas14_1_detalles/ws_select_item/' + id;
            $.getJSON(ruta_url_item)
            .done(function (data){                
                $("#periodo").text(data.periodo);
                $("#codigo_unico").text(data.codigo_unico);
                $("#numero_correlativo").text(data.numero_correlativo);
                $("#fecha_emision").text(data.fecha_emision_cf);
                $("#fecha_vencimiento").text(data.fecha_vencimiento_cf);
                $("#tipo_documento").text(data.tipo_documento);
                $("#serie").text(data.serie);
                $("#numero").text(data.numero);
                $("#numero_final").text(data.numero_final);
                $("#tipo_cliente").text(data.tipo_cliente);
                $("#numero_documento").text(data.numero_documento);
                $("#cliente").text(data.cliente);
                $("#exportacion").text(data.exportacion);
                $("#base_imponible").text(data.base_imponible);
                $("#base_imponible_descuento").text(data.base_imponible_descuento);
                $("#igv").text(data.igv);
                $("#igv_descuento").text(data.igv_descuento);
                $("#exonerado").text(data.exonerado);
                $("#inafecto").text(data.inafecto);
                $("#isc").text(data.isc);
                $("#arroz_pillado_base_disponible").text(data.arroz_pillado_base_disponible);
                $("#arroz_pillado_igv").text(data.arroz_pillado_igv);
                $("#ICBPER").text(data.ICBPER);
                $("#otros_conceptos").text(data.otros_conceptos);
                $("#importe_total").text(data.importe_total);
                $("#codigo_moneda").text(data.codigo_moneda);
                $("#tipo_cambio").text(data.tipo_cambio);
                $("#da_fecha_emision").text(data.da_fecha_emision_cf);
                $("#da_tipo_documento").text(data.da_tipo_documento);
                $("#da_serie").text(data.da_serie);
                $("#da_numero").text(data.da_numero);
                $("#identificacion_contrato").text(data.identificacion_contrato);
                $("#error_tipo_1").text(data.error_tipo_1);
                $("#medio_pago_cancelacion").text(data.medio_pago_cancelacion);
                $("#estado").text(data.estado);
                $("#id").text(data.id);                         
            });                        
        });
    });

    carga_inicial();
    //al cargar página    
    function carga_inicial(){    
        //seleccionar en tabla de libros
        var url_l = base_url + 'index.php/WS_le_ventas14_1_detalles/ws_select/'+ mes + '/' + anio;
        $.getJSON(url_l)
        .done(function (data) {            
            var numero_orden = 1;
            (data).forEach(function (repo) {
                agregarFila(numero_orden, repo.id, repo.periodo, repo.codigo_unico, repo.numero_correlativo, repo.fecha_emision_cf, repo.fecha_vencimiento_cf, repo.tipo_documento, repo.serie, repo.numero, repo.numero_final, repo.tipo_cliente, repo.numero_documento, repo.cliente, repo.exportacion, repo.base_imponible, repo.base_imponible_descuento, repo.igv, repo.igv_descuento, repo.exonerado, repo.inafecto, repo.isc, repo.arroz_pillado_base_disponible, repo.arroz_pillado_igv, repo.ICBPER, repo.otros_conceptos, repo.importe_total, repo.codigo_moneda, repo.tipo_cambio, repo.da_fecha_emision_cf, repo.da_tipo_documento, repo.da_serie, repo.da_numero, repo.identificacion_contrato, repo.error_tipo_1, repo.medio_pago_cancelacion, repo.estado);
                numero_orden ++;
            });
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            console.log( "Algo ha fallado: " +  textStatus );
        });
    }    
  
    var contador_fila = 1;
    var color = '';
    function agregarFila(numero_orden, id, periodo, codigo_unico, numero_correlativo, fecha_emision, fecha_vencimiento, tipo_documento, serie, numero, numero_final, tipo_cliente, numero_documento, cliente, exportacion, base_imponible, base_imponible_descuento, igv, igv_descuento, exonerado, inafecto, isc, arroz_pillado_base_disponible, arroz_pillado_igv, ICBPER, otros_conceptos, importe_total, codigo_moneda, tipo_cambio, da_fecha_emision, da_tipo_documento, da_serie, da_numero, identificacion_contrato, error_tipo_1, medio_pago_cancelacion, estado){
        if(fecha_vencimiento == null)fecha_vencimiento = '';
        if(numero_final == null)numero_final = '';
        if(exportacion == null)exportacion = '';
        if(exonerado == null)exonerado = '';
        if(inafecto == null)inafecto = '';
        if(isc == null)isc = '';
        if(arroz_pillado_base_disponible == null)arroz_pillado_base_disponible = '';
        if(arroz_pillado_igv == null)arroz_pillado_igv = '';
        if(ICBPER == null)ICBPER = '';
        if(importe_total == null)importe_total = '';
        if(otros_conceptos == null)otros_conceptos = '';
        if(tipo_cambio == null)tipo_cambio = '';
        if(da_fecha_emision == null)da_fecha_emision = '';
        if(da_tipo_documento == null)da_tipo_documento = '';
        if(da_serie == null)da_serie = '';
        if(da_numero == null)da_numero = '';
        if(identificacion_contrato == null)identificacion_contrato = '';
        if(error_tipo_1 == null)error_tipo_1 = '';
        if(medio_pago_cancelacion == null)medio_pago_cancelacion = '';
        if(estado == null)estado = '';
                
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;
        
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="center"><a  id="'+id+'" class="btn btn-default btn-xs btn_detalle" data-toggle="modal" data-target="#myModal">'+numero_orden+'</a></td>';
        fila += '<td>' + periodo + '</td>';
        fila += '<td>' + codigo_unico + '</td>';
        fila += '<td>' + numero_correlativo + '</td>';
        fila += '<td>'+fecha_emision+'</td>';
        fila += '<td>'+fecha_vencimiento+'</td>';
        fila += '<td>'+tipo_documento+'</td>';
        fila += '<td>'+serie+'</td>';
        fila += '<td>'+numero+'</td>';
        fila += '<td>'+numero_final+'</td>';
        fila += '<td>'+tipo_cliente+'</td>';
        fila += '<td>'+numero_documento+'</td>';
        fila += '<td>'+cliente+'</td>';
        fila += '<td>'+exportacion+'</td>';
        fila += '<td>'+base_imponible+'</td>';
        fila += '<td>'+base_imponible_descuento+'</td>';
        fila += '<td>'+igv+'</td>';
        fila += '<td>'+igv_descuento+'</td>';
        fila += '<td>'+exonerado+'</td>';
        fila += '<td>'+inafecto+'</td>';
        fila += '<td>'+isc+'</td>';
        fila += '<td>'+arroz_pillado_base_disponible+'</td>';
        fila += '<td>'+arroz_pillado_igv+'</td>';
        fila += '<td>'+ICBPER+'</td>';
        fila += '<td>'+otros_conceptos+'</td>';
        fila += '<td>'+importe_total+'</td>';
        fila += '<td>'+codigo_moneda+'</td>';
        fila += '<td>'+tipo_cambio+'</td>';
        fila += '<td>'+da_fecha_emision+'</td>';
        fila += '<td>'+da_tipo_documento+'</td>';
        fila += '<td>'+da_serie+'</td>';
        fila += '<td>'+da_numero+'</td>';
        fila += '<td>'+identificacion_contrato+'</td>';
        fila += '<td>'+error_tipo_1+'</td>';
        fila += '<td>'+medio_pago_cancelacion+'</td>';
        fila += '<td>'+estado+'</td>';
        fila += '<td align="center"><a id="'+id+'" class="btn btn-default btn-xs btn_modificar" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>';
        fila += '<td align="center"><a id="'+id+'" class="btn btn-danger btn-xs btn_eliminar"><i class="glyphicon glyphicon-remove"></i></a></td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }

</script>