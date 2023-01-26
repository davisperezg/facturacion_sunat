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
            <h2 align="center">Libros Electrónicos - Compras 8.1</h2>
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
                <th>Año DUA</th>                
                <th>Número</th>
                <th>Número<br>Final</th>

                <th>Tipo documento<br>Proveedor</th>
                <th>Número documento</th>
                <th>Razon Social/Nombres</th>
                
                <th>Base<br>Imponible<br>(Venta con IGV)</th>
                <th>IGV<br>(Venta con IGV)</th>
                <th>Base<br>Imponible<br>(Venta con/sin IGV)</th>
                <th>IGV<br>(Venta con/sin IGV)</th>
                <th>Base<br>Imponible<br>(Venta sin IGV)</th>
                <th>IGV<br>(Venta sin IGV)</th>
                <th>No grabados</th>                
                <th>I.S.C.</th>                
                <th>ICBPER</th>
                <th>otros<br>conceptos</th>
                <th>Importe<br>Total</th>

                <th>Código<br>Moneda</th>
                <th>Tipo de<br>cambio</th>

                <th>Fecha<br>Emisión</th>
                <th>Tipo<br>Documento</th>
                <th>Serie</th>
                <th>DUA</th>
                <th>Numero</th>
                
                <th>Detracción<br>Fecha de emisión</th>
                <th>Detracción<br>N. depósito</th>
                
                <th>Sujeto retención</th>

                <th>Clasificación<br>Bienes</th>
                <th>Identificación<br>contrato</th>
                <th>Error<br>tipo I</th>
                <th>Error<br>tipo 2</th>
                <th>Error<br>tipo 3</th>
                <th>Error<br>tipo 4</th>
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
    $("#enlace_atras").attr("href", base_url + "index.php/le_compras8_1/index");
    
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
            $("#myModal").load(base_url + 'index.php/le_compras8_1_detalles/modal_operacion');
        });
        
        $("#btn_importar_excel").click(function(){
            $("#myModal").load(base_url + 'index.php/le_compras8_1_detalles/modal_importar_excel');                                            
        });
        
        $(".btn_modificar").click(function(){
            $("#myModal").load(base_url + 'index.php/le_compras8_1_detalles/modal_operacion');
        });
        
        $('#exportarExcel').click(function(){            
            let url = base_url + 'index.php/le_compras8_1_detalles/exportarExcel/' + mes + '/' + anio;
            window.open(url, '_blank');
        });
        
        $('#descargarTxt').click(function(){            
            let url = base_url + 'index.php/le_compras8_1_detalles/descargarTxt/' + mes + '/' + anio + '/' + ruc_empresa;
            window.open(url, '_blank');
        });
        
        $("#tabla_id").on('click', '.btn_eliminar', function(e){            
            var id = $(this).attr('id');            
            var x = confirm("Desea eliminar este registro:");
            if (x){ 
                ruta_url_item = base_url + 'index.php/le_compras8_1_detalles/delete_item/' + id;
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
            
            $("#myModal").load(base_url + 'index.php/le_compras8_1_detalles/modal_operacion');                                

            ruta_url_item = base_url + 'index.php/WS_le_compras8_1_detalles/ws_select_item/' + id;
            $.getJSON(ruta_url_item)
            .done(function (data){ 
                
                console.log(data.codigo_moneda);
                console.log(data.tipo_cambio);
                
                $("#periodo").val(data.periodo);
                $("#codigo_unico").val(data.codigo_unico);
                $("#numero_correlativo").val(data.numero_correlativo);
                                
                $("#fecha_emision").val(data.fecha_emision_cf);
                $("#fecha_vencimiento").val(data.fecha_vencimiento_cf);
                $("#tipo_documento").val(data.tipo_documento);
                $("#serie").val(data.serie);
                $("#anio_dua").val(data.anio_dua);
                $("#numero").val(data.numero);
                $("#numero_final").val(data.numero_final);
                
                $("#tipo_documento_proveedor").val(data.tipo_documento_proveedor);
                $("#numero_documento").val(data.numero_documento);
                $("#razon_social").val(data.razon_social);
                
                $("#base_imponible_tipo_1").val(data.base_imponible_tipo_1);
                $("#igv_tipo_1").val(data.igv_tipo_1);
                $("#base_imponible_tipo_2").val(data.base_imponible_tipo_2);
                $("#igv_tipo_2").val(data.igv_tipo_2);
                $("#base_imponible_tipo_3").val(data.base_imponible_tipo_3);
                $("#igv_tipo_3").val(data.igv_tipo_3);
                $("#no_grabadas").val(data.no_grabadas);
                $("#isc").val(data.isc);
                $("#ICBPER").val(data.ICBPER);
                $("#otros_conceptos").val(data.otros_conceptos);
                $("#importe_total").val(data.importe_total);
                
                $("#codigo_moneda").val(data.codigo_moneda);
                $("#tipo_cambio").val(data.tipo_cambio);
                
                $("#da_fecha_emision").val(data.da_fecha_emision_cf);
                $("#da_tipo_documento").val(data.da_tipo_documento);
                $("#da_serie").val(data.da_serie);
                $("#da_dua").val(data.da_dua);
                $("#da_numero").val(data.da_numero);
                
                $("#fecha_emision_detraccion").val(data.fecha_emision_detraccion_cf);
                $("#numero_deposito_detraccion").val(data.numero_deposito_detraccion);
                $("#sujeto_retencion").val(data.sujeto_retencion);
                
                $("#clasificacion_bienes").val(data.clasificacion_bienes);
                $("#identificacion_contrato").val(data.identificacion_contrato);
                $("#error_tipo_1").val(data.error_tipo_1);
                $("#error_tipo_2").val(data.error_tipo_2);
                $("#error_tipo_3").val(data.error_tipo_3);
                $("#error_tipo_4").val(data.error_tipo_4);
                $("#medio_pago_cancelacion").val(data.medio_pago_cancelacion);
                $("#estado").val(data.estado);
                $("#id").val(data.id);
                
                $("#texto_titulo").text('Modificar Compra');
                $("#btn_guardar_operacion").text('Modificar');
            });                        
        });
        
        //modal detalle
        $("#tabla_id").on('click', '.btn_detalle', function(){
            var id = $(this).attr('id');
            
            $("#myModal").load(base_url + 'index.php/le_compras8_1_detalles/modal_detalle');                                

            ruta_url_item = base_url + 'index.php/WS_le_compras8_1_detalles/ws_select_item/' + id;
            $.getJSON(ruta_url_item)
            .done(function (data){                
                $("#periodo").text(data.periodo);
                $("#codigo_unico").text(data.codigo_unico);
                $("#numero_correlativo").text(data.numero_correlativo);                                
                
                $("#fecha_emision").text(data.fecha_emision);
                $("#fecha_vencimiento").text(data.fecha_vencimiento);
                $("#tipo_documento").text(data.tipo_documento);
                $("#serie").text(data.serie);
                $("#anio_dua").text(data.anio_dua);
                $("#numero").text(data.numero);
                $("#numero_final").text(data.numero_final);                
                
                $("#tipo_documento_proveedor").text(data.tipo_documento_proveedor);
                $("#numero_documento").text(data.numero_documento);
                $("#razon_social").text(data.razon_social);
                                
                $("#base_imponible_tipo_1").text(data.base_imponible_tipo_1);
                $("#igv_tipo_1").text(data.igv_tipo_1);
                $("#base_imponible_tipo_2").text(data.base_imponible_tipo_2);
                $("#igv_tipo_2").text(data.igv_tipo_2);
                $("#base_imponible_tipo_3").text(data.base_imponible_tipo_3);
                $("#igv_tipo_3").text(data.igv_tipo_3);
                $("#no_grabadas").text(data.no_grabadas);
                $("#isc").text(data.isc);
                $("#ICBPER").text(data.ICBPER);
                $("#otros_conceptos").text(data.otros_conceptos);
                $("#importe_total").text(data.importe_total);
                
                $("#codigo_moneda").text(data.codigo_moneda);
                $("#tipo_cambio").text(data.tipo_cambio);
                
                $("#da_fecha_emision").text(data.da_fecha_emision);
                $("#da_tipo_documento").text(data.da_tipo_documento);
                $("#da_serie").text(data.da_serie);
                $("#da_dua").text(data.da_dua);
                $("#da_numero").text(data.da_numero);
                
                $("#fecha_emision_detraccion").text(data.fecha_emision_detraccion);
                $("#numero_deposito_detraccion").text(data.numero_deposito_detraccion);
                $("#sujeto_retencion").text(data.sujeto_retencion);
                
                $("#").val(data.clasificacion_bienes);
                $("#").val(data.identificacion_contrato);
                $("#").val(data.error_tipo_1);
                $("#").val(data.error_tipo_2);
                $("#").val(data.error_tipo_3);
                $("#").val(data.error_tipo_4);
                $("#").val(data.medio_pago_cancelacion);                
                $("#estado").val(data.estado);
                $("#id").val(data.id);
                                                
                $("#clasificacion_bienes").text(data.clasificacion_bienes);
                $("#identificacion_contrato").text(data.identificacion_contrato);
                $("#error_tipo_1").text(data.error_tipo_1);
                $("#error_tipo_2").text(data.error_tipo_2);
                $("#error_tipo_3").text(data.error_tipo_3);
                $("#error_tipo_4").text(data.error_tipo_4);
                $("#medio_pago_cancelacion").text(data.medio_pago_cancelacion);                
                $("#estado").text(data.estado);
            });                        
        });
    });

    carga_inicial();
    //al cargar página    
    function carga_inicial(){    
        //seleccionar en tabla de libros
        var url_l = base_url + 'index.php/WS_le_compras8_1_detalles/ws_select/'+ mes + '/' + anio;
        $.getJSON(url_l)
        .done(function (data) {            
            var numero_orden = 1;
            (data).forEach(function (repo) {                
                agregarFila(numero_orden, repo.id, repo.periodo, repo.codigo_unico, repo.numero_correlativo, repo.fecha_emision_cf, repo.fecha_vencimiento_cf, repo.tipo_documento, repo.serie, repo.anio_dua, repo.numero, repo.numero_final, repo.tipo_documento_proveedor, repo.numero_documento, repo.razon_social, repo.base_imponible_tipo_1, repo.igv_tipo_1, repo.base_imponible_tipo_2, repo.igv_tipo_2, repo.base_imponible_tipo_3, repo.igv_tipo_3, repo.no_grabadas, repo.isc, repo.ICBPER, repo.otros_conceptos, repo.importe_total, repo.codigo_moneda, repo.tipo_cambio, repo.da_fecha_emision_cf, repo.da_tipo_documento, repo.da_serie, repo.da_dua, repo.da_numero, repo.fecha_emision_detraccion_cf, repo.numero_deposito_detraccion, repo.sujeto_retencion, repo.clasificacion_bienes, repo.identificacion_contrato, repo.error_tipo_1, repo.error_tipo_2, repo.error_tipo_3, repo.error_tipo_4, repo.medio_pago_cancelacion, repo.estado, repo.compra_id, repo.insercion_automatica);
                numero_orden ++;
            });
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            console.log( "Algo ha fallado: " +  textStatus );
        });
    }    
  
    var contador_fila = 1;
    var color = '';
    function agregarFila(numero_orden, id, periodo, codigo_unico, numero_correlativo, fecha_emision, fecha_vencimiento, tipo_documento, serie, anio_dua, numero, numero_final, tipo_documento_proveedor, numero_documento, razon_social, base_imponible_tipo_1, igv_tipo_1, base_imponible_tipo_2, igv_tipo_2, base_imponible_tipo_3, igv_tipo_3, no_grabadas, isc, ICBPER, otros_conceptos, importe_total, codigo_moneda, tipo_cambio, da_fecha_emision, da_tipo_documento, da_serie, da_dua, da_numero, fecha_emision_detraccion, numero_deposito_detraccion, sujeto_retencion, clasificacion_bienes, identificacion_contrato, error_tipo_1, error_tipo_2, error_tipo_3, error_tipo_4, medio_pago_cancelacion, estado, compra_id, insercion_automatica){
        if(fecha_vencimiento == null)fecha_vencimiento = '';
        if(anio_dua == null)anio_dua = '';
        if(tipo_documento_proveedor == null)tipo_documento_proveedor = '';
        if(numero_final == null)numero_final = '';
        if(base_imponible_tipo_2 == null)base_imponible_tipo_2 = '';
        if(igv_tipo_2 == null)igv_tipo_2 = '';
        if(base_imponible_tipo_3 == null)base_imponible_tipo_3 = '';
        if(igv_tipo_3 == null)igv_tipo_3 = '';
        if(isc == null)isc = '';        
        if(ICBPER == null)ICBPER = '';
        if(importe_total == null)importe_total = '';
        if(otros_conceptos == null)otros_conceptos = '';
        if(tipo_cambio == null)tipo_cambio = '';
        if(da_fecha_emision == null)da_fecha_emision = '';
        if(da_tipo_documento == null)da_tipo_documento = '';
        if(da_serie == null)da_serie = '';
        if(da_dua == null)da_dua = '';
        if(da_numero == null)da_numero = '';
        
        if(fecha_emision_detraccion == null)fecha_emision_detraccion = '';
        if(numero_deposito_detraccion == null)numero_deposito_detraccion = '';
        if(sujeto_retencion == null)sujeto_retencion = '';
        if(clasificacion_bienes == null)clasificacion_bienes = '';
        if(identificacion_contrato == null)identificacion_contrato = '';
        if(error_tipo_1 == null)error_tipo_1 = '';
        if(error_tipo_2 == null)error_tipo_2 = '';
        if(error_tipo_3 == null)error_tipo_3 = '';
        if(error_tipo_4 == null)error_tipo_4 = '';
        if(medio_pago_cancelacion == null)medio_pago_cancelacion = '';
        if(estado == null)estado = '';
                
        color = ((contador_fila % 2) == 0) ? "style='background-color: #F9E6E4'" : '';
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
        fila += '<td>'+anio_dua+'</td>';
        fila += '<td>'+numero+'</td>';
        fila += '<td>'+numero_final+'</td>';
        
        fila += '<td>'+tipo_documento_proveedor+'</td>';
        fila += '<td>'+numero_documento+'</td>';
        fila += '<td>'+razon_social+'</td>';
        
        fila += '<td>'+base_imponible_tipo_1+'</td>';
        fila += '<td>'+igv_tipo_1+'</td>';
        fila += '<td>'+base_imponible_tipo_2+'</td>';
        fila += '<td>'+igv_tipo_2+'</td>';
        fila += '<td>'+base_imponible_tipo_3+'</td>';
        fila += '<td>'+igv_tipo_3+'</td>';
        fila += '<td>'+no_grabadas+'</td>';
        fila += '<td>'+isc+'</td>';
        fila += '<td>'+ICBPER+'</td>';
        fila += '<td>'+otros_conceptos+'</td>';
        fila += '<td>'+importe_total+'</td>';
        
        fila += '<td>'+codigo_moneda+'</td>';
        fila += '<td>'+tipo_cambio+'</td>';
        
        fila += '<td>'+da_fecha_emision+'</td>';
        fila += '<td>'+da_tipo_documento+'</td>';
        fila += '<td>'+da_serie+'</td>';
        fila += '<td>'+da_dua+'</td>';
        fila += '<td>'+da_numero+'</td>';
        
        fila += '<td>'+fecha_emision_detraccion+'</td>';
        fila += '<td>'+numero_deposito_detraccion+'</td>';
        
        fila += '<td>'+sujeto_retencion+'</td>';        
        
        fila += '<td>'+clasificacion_bienes+'</td>';
        fila += '<td>'+identificacion_contrato+'</td>';
        fila += '<td>'+error_tipo_1+'</td>';
        fila += '<td>'+error_tipo_2+'</td>';
        fila += '<td>'+error_tipo_3+'</td>';
        fila += '<td>'+error_tipo_4+'</td>';
        fila += '<td>'+medio_pago_cancelacion+'</td>';
        fila += '<td>'+estado+'</td>';
        fila += '<td align="center"><a id="'+id+'" class="btn btn-default btn-xs btn_modificar" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>';
        fila += '<td align="center"><a id="'+id+'" class="btn btn-danger btn-xs btn_eliminar"><i class="glyphicon glyphicon-remove"></i></a></td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);    
    }

</script>