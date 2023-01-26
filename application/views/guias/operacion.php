<style type="text/css">
    /* Agregando Inputs */
    .input-group {width: 100%;}
    .input-group-addon { min-width: 180px;text-align: right;}    
    
    .panel-title{
        font-size: 13px;
        font-weight: bold;
    }
    
    .derecha_text { 
        text-align: right; 
    }
</style>
<div class="container col-xs-12">    
    <form id="formComprobante" class="form-horizontal" autocomplete="off">
        <input type="hidden" name="anticipo" id="anticipo" value="0">
        <div class="row">
            <div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>
            <div class="col-md-8" style="text-align: center"><h3 id="label_guia">NUEVA GUIA</h3></div>
            <div class="col-md-2"></div>
        </div>

        <div class="panel panel-info" >
            <div class="panel-heading" >
                <div class="panel-title">Datos Guia</div>                        
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Destinatario:</label>
                            <button type="button" id="crear_nueva_entidad" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo</button>
                            <input type="text" required="" class="form-control" name="entidad" id="entidad" placeholder="Razón Social">
                            <input type="hidden" required="" class="form-control" name="entidad_id" id="entidad_id">
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">F. emisión:</label>
                            <input type="text" class="form-control" name="fecE" id="fecE" placeholder="Emision">
                        </div>
                    </div>                 
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">F. Traslado:</label>
                            <input type="text" class="form-control" name="fecT" id="fecT" placeholder="Traslado">
                        </div>
                    </div>                 
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Motivo:</label>
                            <select id="motivo_traslado" class="form-control" name="motivo_traslado">
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-lg-2">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Modalidad:</label>
                            <select id="modalidad_traslado" class="form-control" name="modalidad_traslado">
                            </select>
                        </div>
                        <div class="input-group input-group-sm div_conductor_nombres">
                            <input type="text" class="form-control" name="conductor_nombres" id="conductor_nombres" placeholder="Nombres">
                        </div>
                    </div>
                    
                    <div id="div_transporte_privado">
                        <div class="col-md-1 col-lg-1">
                            <div class="input-group input-group-sm">
                                <label class="control-label">Datos carro-</label>
                                <input type="text" class="form-control" name="placa" id="placa" placeholder="Placa">
                            </div>
                            <div class="input-group input-group-sm">                
                                <input type="text" class="form-control" name="conductor_apellidos" id="conductor_apellidos" placeholder="Apellidos">
                            </div>
                        </div> 
                        <div class="col-md-1 col-lg-1">
                            <div class="input-group input-group-sm">
                                <label class="control-label">chofer</label>
                                <input type="text" class="form-control" name="conductor_licencia" id="conductor_licencia" placeholder="Licencia">
                            </div>
                            <div class="input-group input-group-sm">                
                                <input type="text" class="form-control" name="conductor_dni" id="conductor_dni" placeholder="DNI">
                            </div>
                        </div>                         
                    </div>
                    
                    <div id="div_transporte_publico">
                        <div class="col-md-2 col-lg-2">
                            <div class="input-group input-group-sm">                
                                <label class="control-label">Transporte Razón Social:</label>
                                <input type="text" class="form-control" name="transporte_razon_social" id="transporte_razon_social" placeholder="Transporte Razón Social">
                                <input type="text" class="form-control" name="numero_mtc_transporte" id="numero_mtc_transporte" placeholder="Numero Registro MTC">
                                <input type="hidden" id="entidad_id_transporte" name="entidad_id_transporte" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-info" >
            <div class="panel-heading" >
                <div class="panel-title">Datos Envío</div>                        
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Punto Partida</label>
                    </div>                                
                </div>
                <div class="row">                                
                    <div class="col-sm-1">
                        <select class="form-control form-control-sm" id="partida_departamento_id" name="partida_departamento_id" required="">
                        </select>                                     
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control form-control-sm" id="partida_provincia_id" name="partida_provincia_id" required="">                                        
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control form-control-sm" id="partida_distrito_id" name="partida_distrito_id" required="">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Dirección Punto Partida" name="partida_direccion" id="partida_direccion" class="form-control">
                    </div> 
                </div>               
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <label>Punto Llegada</label>
                    </div>                                
                </div>
                <div class="row">                                
                    <div class="col-sm-1">
                        <select class="form-control form-control-sm" id="llegada_departamento_id" name="llegada_departamento_id" required="">
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control form-control-sm" id="llegada_provincia_id" name="llegada_provincia_id" required="">
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control form-control-sm" id="llegada_distrito_id" name="llegada_distrito_id" required="">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Dirección Punto Llegada" name="llegada_direccion" id="llegada_direccion" class="form-control">
                    </div> 
                </div>              
            </div>
        </div>                
        
        <div class="panel panel-info" >
            <div class="panel-heading" >
                <div class="panel-title">Adjuntar Documento</div>                        
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">                
                            <label>Serie</label>
                            <input type="text" name="serie" id="serie" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">                
                            <label>Numero</label>
                            <input type="text" name="numero" id="numero" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">
                            <a style="margin-top: 22px" class="btn btn-primary btn-sm" id="adjuntar_documento">Adjuntar Documento</a>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="input-group">                
                            <label>Documentos Adjuntos</label>
                            <textarea class="form-control" readonly="" name="documentos_adjuntos" id="documentos_adjuntos" rows="2"></textarea>
                            <input type="hidden" name="documentos_adjuntos_id" id="documentos_adjuntos_id" />                            
                        </div>
                    </div>
                    <div id="div_borrar_adjuntos" class="col-md-2 col-lg-2">
                        <div class="input-group input-group-sm">
                            <button type="button" id="borrar_adjuntos" class="btn btn-success btn-sm">Borrar Adjuntos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-info" >  
            <div class="panel-heading">
                <div class="panel-title">CONCEPTOS DEL COMPROBANTE</div>
            </div>
            <div class="panel-body">                        
                <div class="row" id="valida">
                    <div id="contendor_table" class="col-lg-12">
                        <table id="tabla" class="table tabla_items" style="display:none" border="0">
                            <thead>
                                <tr>                                                
                                    <th>Descripcion</th>
                                    <th>Unid. Medida</th>                                                
                                    <th>Cant.</th>
                                </tr>
                            </thead>                    
                            <tbody>                                                      
                            </tbody>                    
                        </table>   
                        <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                        <button type="button" id="modal_nuevo_producto" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo Producto</button>
                    </div> 
                </div>            
                <div id="mostrar"></div>
                <div id="uu"></div>
            </div>
        </div>
        
        <div class="panel panel-info" >
            <div class="panel-heading" >
                <div class="panel-title">Datos Generales</div>                        
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2 col-lg-2">
                        <div class="input-group input-group-sm">                
                            <label>Peso Bruto Total</label>
                            <input type="text" name="peso_total" id="peso_total" class="form-control">
                        </div>
                    </div>
                    <div id="div_numero_bultos" class="col-md-2 col-lg-2">
                        <div class="input-group input-group-sm">                
                            <label>Número de Bultos</label>
                            <input type="text" name="numero_bultos" id="numero_bultos" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="input-group">                
                            <label>Notas</label>
                            <textarea class="form-control" name="notas" id="notas" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" align="center">
            <button type="button" id="guardar_guia" name="guardar_guia" class="btn btn-primary btn-lg">Guardar Guia</button>
            <input id="txt_guia_id" name="txt_guia_id" type="hidden" />
        </div>
        <br><br>
    </form>    
</div>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url = '<?PHP echo base_url();?>';
    let tipo_igv = JSON.parse(localStorage.getItem("tipo_igv"));
    let datos_configuracion = JSON.parse(localStorage.getItem("datos_configuracion"));
    var porcentaje_valor_igv = datos_configuracion.porcentaje_valor_igv;
    var respuesta_inconsistencia = 0;
        
    $("#img_atras").attr("src", base_url + "images/atras.png");
    $("#enlace_atras").attr("href", base_url + "index.php/guias");
    $("#div_borrar_adjuntos").hide();
    
    var today = new Date();                
    var dd = today.getDate(); 
    var mm = today.getMonth() + 1;   
    var yyyy = today.getFullYear();
    var cadena;
    var cadena_modalidad;

    if (dd < 10) { 
        dd = '0' + dd; 
    } 
    if (mm < 10) { 
        mm = '0' + mm; 
    } 
    var today = dd + '-' + mm + '-' + yyyy; 

    $('#fecE').val(today);
    $('#fecE').datepicker();
    $('#fecT').val(today);
    $("#fecT").datepicker();
    
    $("#div_numero_bultos").hide();
    $("#div_transporte_publico").show();
    $("#div_transporte_privado").hide();
    $(".div_conductor_nombres").hide();
    $("#modalidad_traslado").on("click", function(){
        if($("#modalidad_traslado").val() == '1'){
            $("#div_transporte_publico").show();
            $("#div_transporte_privado").hide();            
            $(".div_conductor_nombres").hide();
        }else{
            $("#div_transporte_publico").hide();
            $("#div_transporte_privado").show();  
            $(".div_conductor_nombres").show();  
        }
    });
    
    $("#motivo_traslado").on("click", function(){
        if($("#motivo_traslado").val() == '7'){
            $("#div_numero_bultos").show();          
        }else{
            $("#div_numero_bultos").hide();  
        }
    });
    
    $.getJSON(base_url + 'index.php/WS_guia_motivo_traslados/ws_select')
        .done(function (data) {        
            (data).forEach(function (repo) {
                cadena += "<option value='" + repo.id + "'>" + repo.guia_motivo_traslado + "</option>";
            });
            $('#motivo_traslado').html(cadena);
        });
        
    $.getJSON(base_url + 'index.php/WS_guia_modalidad_traslados/ws_select')
        .done(function (data) {        
            (data).forEach(function (repo) {
                cadena_modalidad += "<option value='" + repo.id + "'>" + repo.guia_modalidad_traslado + "</option>";
            });
            $('#modalidad_traslado').html(cadena_modalidad);
        });
    
    var cadena_departamento;
    $.getJSON(base_url + 'index.php/WS_guias/cargaDepartamentos')
        .done(function (data) {
            cadena_departamento = "<option value=''>Seleccionar</option>";
            (data.departamentos).forEach(function (repo) {
                cadena_departamento += "<option value='" + repo.id + "'>" + repo.departamento + "</option>";
            });
            $('#partida_departamento_id').html(cadena_departamento);
            $('#llegada_departamento_id').html(cadena_departamento);
    });
    
    var cadena_ubigeo;
    $("#partida_departamento_id").on("click", function(){
        $('#partida_provincia_id option').remove();
        var departamento_id = $("#partida_departamento_id").val();
        var url_provincias = base_url + 'index.php/WS_guias/cargaProvincias/'+departamento_id;
        $.getJSON(url_provincias)
        .done(function (data) {
            cadena_ubigeo = "<option value=''>Seleccionar</option>";
            (data.provincias).forEach(function (repo) {
                cadena_ubigeo += "<option value='" + repo.id + "'>" + repo.provincia + "</option>";
            });
            $('#partida_provincia_id').html(cadena_ubigeo);
        });
    });
    
    $("#partida_provincia_id").on("click", function(){
        $('#partida_distrito_id option').remove();
        var provincia_id = $("#partida_provincia_id").val();
        var url_distrito = base_url + 'index.php/WS_guias/cargaDistritos/'+provincia_id;
        $.getJSON(url_distrito)
        .done(function (data) {
            cadena_ubigeo = "<option value=''>Seleccionar</option>";
            (data.distritos).forEach(function (repo) {
                cadena_ubigeo += "<option value='" + repo.id + "'>" + repo.distrito + "</option>";
            });
            $('#partida_distrito_id').html(cadena_ubigeo);
        });        
    });
    /////////////////////////////////////////////
    $("#llegada_departamento_id").on("click", function(){
        $('#llegada_provincia_id option').remove();
        var departamento__llegada_id = $("#llegada_departamento_id").val();
        var url_llegada_provincias = base_url + 'index.php/WS_guias/cargaProvincias/'+departamento__llegada_id;
        $.getJSON(url_llegada_provincias)
        .done(function (data) {
            cadena_ubigeo = "<option value=''>Seleccionar</option>";
            (data.provincias).forEach(function (repo) {
                cadena_ubigeo += "<option value='" + repo.id + "'>" + repo.provincia + "</option>";
            });
            $('#llegada_provincia_id').html(cadena_ubigeo);
        });        
    });
    
    $("#llegada_provincia_id").on("click", function(){
        $('#llegada_distrito_id option').remove();
        var provincia_llegada_id = $("#llegada_provincia_id").val();
        var url_llegada_distrito = base_url + 'index.php/WS_guias/cargaDistritos/'+provincia_llegada_id;
        $.getJSON(url_llegada_distrito)
        .done(function (data) {
            cadena_ubigeo = "<option value=''>Seleccionar</option>";
            (data.distritos).forEach(function (repo) {
                cadena_ubigeo += "<option value='" + repo.id + "'>" + repo.distrito + "</option>";
            });
            $('#llegada_distrito_id').html(cadena_ubigeo);
        });
    });
    
    $("#modal_nuevo_producto").on('click', function(){
        ruta_url = base_url + 'index.php/guias/modal_nuevo_producto/';
        $("#myModal").load(ruta_url);
    });
    
    $("#agrega").on('click', function(){
        agregarFila();
    });
            
    function agregarFila(producto, producto_id, unidad, cantidad){        
        producto = (producto == undefined) ? '' : 'readonly="readonly" value = ' + producto;
        producto_id = (producto_id == undefined) ? 'data-valor = 0' : 'data-valor = ' + producto_id;
        unidad = (unidad == undefined) ? '' : 'value = ' + unidad;
        cantidad = (cantidad == undefined) ? 'value = ' + 1 : 'value = ' + cantidad;
        
        var fila = '<tr class="cont-item fila_generada" >';
        fila += '<td style="border:0;" class="col-sm-4"><input ' + producto + ' ' + producto_id + ' class="form-control descripcion-item" id="descripcion" name="descripcion[]" required=""></td>';
        fila += '<td style="border:0;"><input ' + unidad + ' type="text" class="form-control" readonly id="unidad" name="unidad[]"></td>';
        fila += '<td style="border:0;"><input data-nepele=1 ' + cantidad + ' type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" ></td>';               
        fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
        fila += '</tr>';

        $("#tabla").css("display","block");
        $("#tabla tbody").append(fila);    
    }
        
    var cadena_venta_detalle_id = '';
    var cadena_documentos = '';
    var guia_venta_id = '';
    var cadena_nueva = '';
    var url_editar = window.location.hash;
    
    //estas variables para editar
    var adjunto_numero = '';
    var adjunto_venta_id = '';    
    $(document).ready(function () {
        $("#guardar_guia").on('click', function(){            
            var data = {};
            
            if(url_editar != ''){
                let guia_id = url_editar.substring(1);
                data['guia_id'] = guia_id;
            }            
            
            if($("#entidad_id").val() == ''){
                alert('Debe ingresar una empresa con RUC como destinatario.');
                return false;
            }
            
            if($("#partida_direccion").val() == ''){
                alert('Debe ingresar punto de partida.');
                return false;
            }
            
            if($("#llegada_direccion").val() == ''){
                alert('Debe ingresar punto de llegada.');
                return false;
            }
            
                                    
            if(($("#partida_distrito_id").val() == '') || $("#partida_distrito_id").val() == null){
                alert('Debe ingresar distrito de partida.');
                return false;
            }
            
            if(($("#llegada_distrito_id").val() == '') || $("#llegada_distrito_id").val() == null){
                alert('Debe ingresar distrito de llegada.');
                return false;
            }            
            
            if(($("#modalidad_traslado").val() == 1) && (($("#entidad_id_transporte").val() == '') || $("#entidad_id_transporte").val() == null)){
                alert('Debe ingresar empresa de transporte.');
                return false;
            }
            
            if($("#modalidad_traslado").val() == 2){
                let cantidad_placa = $('#placa').val();
                
                if(cantidad_placa.length > 6){
                    alert('La placa debe tener máximo 6 caracterres.');
                    return false;
                }                    
            }
                        
            data['fecha_emision']               = $("#fecE").val();
            data['fecha_traslado']              = $("#fecT").val();
            data['guia_motivo_traslado_id']     = $("#motivo_traslado").val();
            data['guia_modalidad_traslado_id']  = $("#modalidad_traslado").val();
                        
            data['entidad_id_transporte']       = $("#entidad_id_transporte").val();
            data['numero_mtc_transporte']       = $("#numero_mtc_transporte").val();
                        
            data['vehiculo_placa']              = $("#placa").val();
            data['conductor_dni']               = $("#conductor_dni").val();
            data['conductor_nombres']           = $("#conductor_nombres").val();
            data['conductor_apellidos']         = $("#conductor_apellidos").val();
            data['conductor_licencia']          = $("#conductor_licencia").val();
            
            data['destinatario_id']             = $("#entidad_id").val();
            data['partida_ubigeo']              = $("#partida_distrito_id").val();
            data['partida_direccion']           = $("#partida_direccion").val();
            data['llegada_ubigeo']              = $("#llegada_distrito_id").val();
            data['llegada_direccion']           = $("#llegada_direccion").val();
            
            data['peso_total']                  = $("#peso_total").val();
            data['numero_bultos']               = $("#numero_bultos").val();
            data['notas']                       = $("#notas").val();
            
            data['documentos_adjuntos_id']      = $("#documentos_adjuntos_id").val();
            
            var array_producto_id = [];
            var array_cantidad = [];
            
            $('#tabla tbody tr').each(function(){
                let producto_id = $(this).find('td').eq(0).children().attr('data-valor');
                let cantidad = $(this).find('td').eq(2).children().val();

                array_producto_id.push(producto_id);
                array_cantidad.push(cantidad);
            });
            
            respuesta_inconsistencia = 0;
            respuesta_inconsistencia = detectorInconsistencias(array_producto_id, array_cantidad);
            if(respuesta_inconsistencia == 1)return false;            
                        
            if(array_cantidad.length == 0){
               alert('Debe ingresar al menos 1 producto.');
                respuesta_inconsistencia = 1;
            }
            
            data['producto_id']     = array_producto_id;
            data['cantidad']        = array_cantidad;
                                    
            var url_save = base_url + 'index.php/guias/operaciones';            
            $.getJSON(url_save, data)
            .done(function(datos, textStatus, jqXHR){
                toast('success', 2500, 'Guia ingresada correctamente');
                window.location.href = base_url + "index.php/guias/index";
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
                    console.log( "Algo ha fallado: " +  textStatus );
                }
            });
        });
        
        $("#adjuntar_documento").on('click', function(){
            let serie = $("#serie").val();
            let numero = $("#numero").val();
            
            url_guia_detalle = base_url + 'index.php/WS_venta_detalles/ws_detalle_guia/' + serie + '/' + numero;
            $.getJSON(url_guia_detalle)
            .done(function(data){
                (data).forEach(function (repo) {
                    console.log('cadena_venta_detalle_id:'+cadena_venta_detalle_id);
                    console.log('venta_detalle_id:'+repo.venta_detalle_id);
                    var index = cadena_venta_detalle_id.search(repo.venta_detalle_id);
                    if(index >= 0){
                        toast('error', 2500, 'documento ya ingresado');
                    }else{
                        cadena_venta_detalle_id += repo.venta_detalle_id + ' // ';

                        if((serie + '-' + numero) != cadena_nueva){
                            cadena_documentos += serie + '-' + numero + ' // ';
                            guia_venta_id += repo.venta_id + ',';
                            if(adjunto_numero != '') {
                                $("#documentos_adjuntos").val(cadena_documentos + adjunto_numero);
                                $("#documentos_adjuntos_id").val(guia_venta_id + adjunto_venta_id);
                            }else{
                                console.log("z");
                                $("#documentos_adjuntos").val(cadena_documentos);
                                $("#documentos_adjuntos_id").val(guia_venta_id);
                            }
                            cadena_nueva = serie + '-' + numero;
                        }                                                            
                        agregarFila(repo.producto, repo.producto_id, repo.unidad, repo.cantidad);                        
                    }
                });
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
                    toast('error', 2500, 'documento no existe.');
                    console.log( "Algo ha fallado: " +  textStatus );
                }
            });
        });
        
        $('#contendor_table').on('keyup change', '.tabla_items',function(){
            $('.descripcion-item').autocomplete({
                source : '<?PHP echo base_url();?>index.php/WS_ventas/buscador_item',
                minLength : 2,
                select : function (event,ui){
                    var _item = $(this).closest('.cont-item');
                    //var data_item = '<input class="val-descrip" type="hidden" value="'+ ui.item.producto_id + '" name = "item_id[]" id = "item_id">';
                    //_item.find('#data_item').html(data_item);
                    _item.find('#descripcion').attr("readonly",true);
                    _item.find('#descripcion').attr('data-valor',ui.item.producto_id);
                    _item.find('#unidad').val(ui.item.unidad);                  
                }
            });
        });
        
        /////////-----EDITAR------//////////        
        if(url_editar != ''){
            let guia_id = url_editar.substring(1);
            $('#txt_guia_id').val(guia_id);
            
            $("#label_guia").text("Modificar Guia");
            $("#guardar_guia").text("Modificar Guia");

            let url_cabecera = base_url + 'index.php/WS_guias/ws_cabecera/' + guia_id;
            $.getJSON(url_cabecera)
            .done(function (data) {
                if(data.guia_modalidad_traslado_id == '1'){
                    $("#div_transporte_publico").show();
                    $("#div_transporte_privado").hide();
                    $(".div_conductor_nombres").hide();
                }else{
                    $("#div_transporte_publico").hide();
                    $("#div_transporte_privado").show();
                    $(".div_conductor_nombres").show();
                }
                
                $("#label_guia").text("Modificar Guia: " + data.serie + "-" + data.numero);
                $("#fecE").val(data.fecha_emision);
                $("#fecT").val(data.fecha_traslado);
                $("#motivo_traslado option[value='"+data.guia_motivo_traslado_id+"']").prop('selected', true);
                $("#modalidad_traslado option[value='"+data.guia_modalidad_traslado_id+"']").prop('selected', true);
                $("#ruc").val(data.transporte_ruc);
                $("#razon_social").val(data.transporte_razon);
                $("#conductor_dni").val(data.conductor_dni);
                $("#conductor_nombres").val(data.conductor_nombres);
                $("#conductor_apellidos").val(data.conductor_apellidos);
                $("#conductor_licencia").val(data.conductor_licencia);
                
                $("#placa").val(data.vehiculo_placa);                                                                
                $("#peso_total").val(data.peso_total);
                $("#notas").val(data.notas);
                $("#numero_bultos").val(data.numero_bultos);
                $("#entidad_id").val(data.destinatario_id);
                $("#entidad_id_transporte").val(data.entidad_id_transporte);
                $("#numero_mtc_transporte").val(data.numero_mtc_transporte);
                
                let url_entidad = base_url + 'index.php/WS_entidades/select_unDato/' + data.destinatario_id + '/entidad';
                $.getJSON(url_entidad)
                .done(function (data_entidad) {
                    $("#entidad").val(data_entidad.entidad);
                });
                
                let url_entidad_transporte = base_url + 'index.php/WS_entidades/select_unDato/' + data.entidad_id_transporte + '/entidad';
                $.getJSON(url_entidad_transporte)
                .done(function (data_entidad) {
                    $("#transporte_razon_social").val(data_entidad.entidad);
                });
                
                $("#partida_direccion").val(data.partida_direccion);
                let url_ubigeo_salida = base_url + 'index.php/WS_ubigeos/datos_ubigeo/' + data.partida_ubigeo;
                $.getJSON(url_ubigeo_salida)
                .done(function (data_ubigeo) {
                    $('#partida_distrito_id').prepend("<option value='"+data.partida_ubigeo+"' >"+data_ubigeo.datos_ubigeo.distrito+"</option>");
                    $('#partida_provincia_id').prepend("<option value='"+data.partida_ubigeo.substring(0, 4)+"' >"+data_ubigeo.datos_ubigeo.provincia+"</option>");
                    $("#partida_departamento_id option[value='"+data.partida_ubigeo.substring(0, 2)+"']").prop('selected', true);
                });
                
                $("#llegada_direccion").val(data.llegada_direccion);
                let url_ubigeo_llegada = base_url + 'index.php/WS_ubigeos/datos_ubigeo/' + data.llegada_ubigeo;
                $.getJSON(url_ubigeo_llegada)
                .done(function (data_ubigeo) {
                    $('#llegada_distrito_id').prepend("<option value='"+data.llegada_ubigeo+"' >"+data_ubigeo.datos_ubigeo.distrito+"</option>");
                    $('#llegada_provincia_id').prepend("<option value='"+data.llegada_ubigeo.substring(0, 4)+"' >"+data_ubigeo.datos_ubigeo.provincia+"</option>");
                    $("#llegada_departamento_id option[value='"+data.llegada_ubigeo.substring(0, 2)+"']").prop('selected', true);
                });
            });
            
            let url_detalle = base_url + 'index.php/WS_guia_detalles/ws_select/' + guia_id;
            $.getJSON(url_detalle)
            .done(function (data) {
                (data).forEach(function (repo) {
                    agregarFila(repo.producto, repo.producto_id, repo.unidad, repo.cantidad)
                });
            });

            let url_venta_guia = base_url + 'index.php/WS_venta_guias/ws_select_ventas/' + guia_id;
            console.log(url_venta_guia);
            $.getJSON(url_venta_guia)
            .done(function (data) {
                (data).forEach(function (repo) {
                    adjunto_numero += repo.serie + "-" + repo.numero + " // ";
                    adjunto_venta_id += repo.venta_id + ",";
                }); 
                $("#documentos_adjuntos").val(adjunto_numero);
                $("#documentos_adjuntos_id").val(adjunto_venta_id);
            });
            
            $("#div_borrar_adjuntos").show();
            $("#borrar_adjuntos").on('click', function(){
                $("#documentos_adjuntos").val('');
                $("#documentos_adjuntos_id").val('');
                
                adjunto_numero = '';
                adjunto_venta_id = '';
                cadena_nueva = '';
                cadena_venta_detalle_id = '';
                cadena_documentos = '';
                guia_venta_id = '';
                
                console.log('cadena_venta_detalle_id:'+cadena_venta_detalle_id);
            });
        }
        /////////-----FIN EDITAR------//////////        
    });
    
    function detectorInconsistencias(array_producto_id, array_cantidad, entidad_id){    
        var tipo_entidad_id = Number(tipo_entidad_id);
        
        array_cantidad.forEach(function(cantidad){
            if(Number(cantidad) == 0){
                alert('Las cantidades deben ser mayor a cero (0)');
                respuesta_inconsistencia = 1;
            }
        });
    
        array_producto_id.forEach(function(producto_id){
            if( (producto_id === undefined) || (producto_id == 0) ){
                alert('Debe ingresar todos los productos correctamente.---');
                respuesta_inconsistencia = 1;
            }
        });
        
        return respuesta_inconsistencia;
    }
    
    $('#tabla').on('click', '.eliminar', function(){
        $(this).closest('tr').remove();
    });
    
    $('#entidad').autocomplete({
        //para guias solo se busca empresas
        source: base_url + 'index.php/WS_entidades/buscador_entidad_2/2',
        minLength: 2,
        select: function (event, ui) {
            $('#entidad_id').val(ui.item.id);            
            $('#llegada_direccion').val(ui.item.direccion);            
        }
    });
    
    $('#transporte_razon_social').autocomplete({
        //para guias solo se busca empresas
        source: base_url + 'index.php/WS_entidades/buscador_entidad/2',
        minLength: 2,
        select: function (event, ui) {
            $('#entidad_id_transporte').val(ui.item.id);            
        }
    });
    
    $("#crear_nueva_entidad").on('click', function(){
        ruta_url = base_url + 'index.php/ventas/modal_nueva_entidad/';
        $("#myModal").load(ruta_url);
    });
</script>    