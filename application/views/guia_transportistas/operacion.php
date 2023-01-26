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
        <div class="row">
            <div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>
            <div class="col-md-8" style="text-align: center"><h3 id="label_guia">NUEVA GUIA TRANSPORTISTA</h3></div>
            <div class="col-md-2"></div>
        </div>
        
        <div class="panel panel-info" >
            <div class="panel-heading" >
                <div class="panel-title">Datos Guia</div>                        
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">
                            <label class="control-label">F. emisión:</label>
                            <input type="text" class="form-control" id="fecE" placeholder="Emision">
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">
                            <label class="control-label">F. Traslado:</label>
                            <input type="text" class="form-control" id="fecT" placeholder="Traslado">
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">
                            <label class="control-label">N. MTC:</label>
                            <input type="text" class="form-control" id="numero_mtc" placeholder="N. MTC">
                        </div>
                    </div>                                        
                </div>
                <hr>
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
                        <input type="text" placeholder="Dirección Punto Partida" name="partida_direccion" id="partida_direccion" class="form-control" required="">
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
                        <input type="text" placeholder="Dirección Punto Llegada" name="llegada_direccion" id="llegada_direccion" class="form-control" required="">
                    </div> 
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Remitente:</label>
                            <button type="button" id="crear_nuevo_remitente" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo</button>
                            <input type="text" required="" class="form-control" name="remitente" id="remitente" placeholder="Razón Social">
                            <input type="hidden" required="" class="form-control" name="remitente_id" id="remitente_id">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Destinatario:</label>
                            <button type="button" id="crear_nuevo_destinatario" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Nuevo</button>
                            <input type="text" required="" class="form-control" name="destinatario" id="destinatario" placeholder="Razón Social">
                            <input type="hidden" required="" class="form-control" name="destinatario_id" id="destinatario_id">
                        </div>
                    </div>
                </div>
                
                <hr>
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Datos Vehiculo:</label>
                            <input type="text" required="" class="form-control" name="vehiculo_placa" id="vehiculo_placa" placeholder="Placa">
                            <input type="text" required="" class="form-control" name="vehiculo_mtc" id="vehiculo_mtc" placeholder="N. MTC">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="input-group input-group-sm">                
                            <label class="control-label">Datos del conductor:</label>
                            <input type="text" required="" class="form-control" name="conductor_dni" id="conductor_dni" placeholder="DNI">
                            <input type="text" required="" class="form-control" name="conductor_nombres" id="conductor_nombres" placeholder="Nombres">
                            <input type="text" required="" class="form-control" name="conductor_apellidos" id="conductor_apellidos" placeholder="Apellidos">
                            <input type="text" required="" class="form-control" name="conductor_licencia" id="conductor_licencia" placeholder="Licencia">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div style="padding-left: 15px">
                        <label class="control-label">Adjuntar documento:</label><br>
                    </div>
                    <div id="div_tipo_documento" class="col-md-2 col-lg-2">
                        <select  class="form-control" name="tipo_documento" id="tipo_documento">
                        </select>    
                    </div>    
                    <div class="col-md-1 col-lg-1">                        
                        <div class="input-group input-group-sm">                
                            <input type="text" name="adjunto_serie" id="adjunto_serie" class="form-control" placeholder="SERIE">
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="input-group input-group-sm">                
                            <input type="text" name="adjunto_numero" id="adjunto_numero" class="form-control" placeholder="NUMERO">
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


<script type="text/javascript">    
    var today = new Date();                
    var dd = today.getDate(); 
    var mm = today.getMonth() + 1;   
    var yyyy = today.getFullYear();    

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
    
    var cadena_departamento;
    $.getJSON(base_url + 'index.php/WS_guia_transportistas/cargaDepartamentos')
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
        var url_provincias = base_url + 'index.php/WS_guia_transportistas/cargaProvincias/'+departamento_id;
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
        var url_distrito = base_url + 'index.php/WS_guia_transportistas/cargaDistritos/'+provincia_id;
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
        var url_llegada_provincias = base_url + 'index.php/WS_guia_transportistas/cargaProvincias/'+departamento__llegada_id;
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
        var url_llegada_distrito = base_url + 'index.php/WS_guia_transportistas/cargaDistritos/'+provincia_llegada_id;
        $.getJSON(url_llegada_distrito)
        .done(function (data) {
            cadena_ubigeo = "<option value=''>Seleccionar</option>";
            (data.distritos).forEach(function (repo) {
                cadena_ubigeo += "<option value='" + repo.id + "'>" + repo.distrito + "</option>";
            });
            $('#llegada_distrito_id').html(cadena_ubigeo);
        });
    });
    
    $('#remitente').autocomplete({
        //para guias solo se busca empresas
        source: base_url + 'index.php/WS_entidades/buscador_entidad/2',
        minLength: 2,
        select: function (event, ui) {
            $('#remitente_id').val(ui.item.id);
        }
    });
    
    $('#destinatario').autocomplete({
        //para guias solo se busca empresas
        source: base_url + 'index.php/WS_entidades/buscador_entidad/2',
        minLength: 2,
        select: function (event, ui) {
            $('#destinatario_id').val(ui.item.id);
        }
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
    
    var url_editar = window.location.hash;
    
    $(document).ready(function () {
        $("#guardar_guia").on('click', function(){            
            var data = {};
            
            if(url_editar !== ''){
                let guia_id = url_editar.substring(1);
                data['guia_id'] = guia_id;
            }            
            
            if($("#remitente_id").val() == ''){
                alert('Debe ingresar un remitente.');
                return false;
            }
            
            if($("#destinatario_id").val() == ''){
                alert('Debe ingresar un destinatario.');
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

            data['fecha_emision']       = $("#fecE").val();
            data['fecha_traslado']      = $("#fecT").val();
            data['numero_mtc']          = $("#numero_mtc").val();
            
            data['partida_ubigeo']      = $("#partida_distrito_id").val();
            data['partida_direccion']   = $("#partida_direccion").val();
            data['llegada_ubigeo']      = $("#llegada_distrito_id").val();
            data['llegada_direccion']   = $("#llegada_direccion").val();
                        
            data['remitente_id']        = $("#remitente_id").val();
            data['destinatario_id']     = $("#destinatario_id").val();

            data['vehiculo_placa']      = $("#vehiculo_placa").val();
            data['vehiculo_mtc']        = $("#vehiculo_mtc").val();
            
            data['conductor_dni']       = $("#conductor_dni").val();
            data['conductor_nombres']   = $("#conductor_nombres").val();
            data['conductor_apellidos'] = $("#conductor_apellidos").val();
            data['conductor_licencia']  = $("#conductor_licencia").val();
            
            data['peso_total']          = $("#peso_total").val();
            data['notas']               = $("#notas").val();
            
            data['tipo_documento_id']   = $("#tipo_documento").val();
            data['adjunto_serie']       = $("#adjunto_serie").val();
            data['adjunto_numero']      = $("#adjunto_numero").val();
            
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
                                    
            var url_save = base_url + 'index.php/guias_transportistas/operaciones';            
            $.getJSON(url_save, data)
            .done(function(datos, textStatus, jqXHR){
                toast('success', 2500, 'Guia ingresada correctamente');
                window.location.href = base_url + "index.php/guias_transportistas/index";
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
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
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_documentos/tipo_documentos_all')
        .done(function (data) {
            (data.tipo_documentos).forEach(function (repo) {
                $('#tipo_documento').append("<option value='" + repo.id + "'>" + repo.tipo_documento + "</option>");
        });
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
    
</script>    