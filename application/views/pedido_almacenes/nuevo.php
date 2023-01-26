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
<form id="formComprobante" class="form-horizontal" autocomplete="off">
    <div class="row">
        <div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>
        <div class="col-md-8" style="text-align: center"><h3 id="texto_titulo">NUEVO PEDIDO DE ALMACÉN</h3></div>
        <div class="col-md-2"></div>
    </div>
    
    <div class="row">        
        <div class="col-md-12">            
                        
            <div class="row" style="padding-top:20px;">                
                <div class="col-lg-12">
                    <div id="panel_fomulario2" class="panel panel-info">  
                        <div class="panel-heading">
                            <div class="panel-title">Ingresar Productos</div>
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
                                    <div class="row">
                                        <div class="col-xs-1" id="div_agregar_item">
                                            <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                                        </div>                                        
                                    </div>                                    
                                </div> 
                            </div>            
                            <div id="mostrar"></div>
                            <div id="uu"></div>
                        </div>                            
                    </div>
                </div>                    
            </div>                        
        </div>
    </div>
        
    <div class="row" style="padding-top:20px;">               
        <div class="col-md-12 col-lg-8">                                                                         
            <div id="panel_otros">
                <div class="panel-heading">
                    <div class="panel-title">Notas de la venta:<input type="checkbox" name="chkNotas" id="chkNotas"></div>
                </div>
                <div class="panel-body" id="div_notas_ventas">
                    <textarea name="notas" id="notas" rows="3" cols="100" disabled style="width: 100%;"></textarea>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="row" style="padding-bottom: 2rem;">
                <div class="col-lg-12"> 
                    <input id="guardar" class="btn btn-primary btn-lg btn-block" value="Generar Comprobante de Pago" style="background: #1ABC9C;border:0;"/>                                        
                </div>
            </div>
        </div>    
    </div>        
</form>

<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var params_url              =   window.location.hash;    
    var pedido_almacen_id       =   params_url.substring(1);
    console.log('pedido_almacen_id:'+pedido_almacen_id);
    
    let variables_diversas      =   JSON.parse(localStorage.getItem("variables_diversas"));            
    let datos_configuracion     =   JSON.parse(localStorage.getItem("datos_configuracion"));
    
    var base_url                =   '<?PHP echo base_url();?>';
    var catidad_decimales       =   datos_configuracion.catidad_decimales;                
    var params_url              =   window.location.hash;
        
    $("#img_atras").attr("src", base_url + "images/atras.png");
    $("#enlace_atras").attr("href", base_url + "index.php/pedido_almacenes/index");
    $("#div_notas_ventas").hide();
    
    $(document).ready(function () {
        var today   = new Date();                
        var dd      = today.getDate(); 
        var mm      = today.getMonth() + 1;   
        var yyyy    = today.getFullYear();
        
        if (dd < 10) { 
            dd = '0' + dd; 
        } 
        if (mm < 10) { 
            mm = '0' + mm; 
        } 
        var today = dd + '-' + mm + '-' + yyyy; 

        $('#fecE').val(today);
        $('#fecE').datepicker();
        $("#fecV").datepicker();
        
        $("#guardar").on("click", function(){
            var array_producto_id = [];
            var array_producto = [];
            var array_cantidad = [];
            var data = {};
            
            $('#tabla tbody tr').each(function(){
                let producto_id = $(this).find('td').eq(0).find('.val-descrip').val();
                let producto = $(this).find('td').eq(0).find('.decription_producto').val();
                let cantidad = $(this).find('td').eq(2).children().val();
                
                array_producto_id.push(producto_id);
                array_producto.push(producto);
                array_cantidad.push(cantidad);
            });

            respuesta_inconsistencia = 0;
            //respuesta_inconsistencia = detectorInconsistencias(array_tipo_igv, $("#tipo_operacion").val(), $("#tipo_entidad_id").val(), array_producto_id, array_cantidad, $("#entidad_id").val(), $("#tipo_documento").val(), $("#adjuntar_documento").val());
            //if(respuesta_inconsistencia == 1)return false;
            
            data['producto_id']         = array_producto_id;
            data['producto']            = array_producto;
            data['cantidad']            = array_cantidad;
            data['notas']               = $("#notas").val();
            data['pedido_almacen_id']   = pedido_almacen_id;
            
            var url_max_numero = base_url + 'index.php/WS_pedido_almacenes/maximo_numero_documento';
            $.getJSON(url_max_numero, data)
            .done(function(datos, textStatus, jqXHR){
                data['numero']  =  parseInt(datos) + 1;
                var url_save = base_url + 'index.php/pedido_almacenes/operaciones';
                $.getJSON(url_save, data)
                .done(function(datos, textStatus, jqXHR){
                    toast('success', 2500, 'Venta ingresada correctamente');
                    window.location.href = base_url + 'index.php/pedido_almacenes/index/';
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "Algo ha fallado: " +  textStatus );
                    }
                });
            })
        });

        $("#agrega").on('click', function(){
            agregarFila(undefined, undefined, undefined, undefined);
        });

        $('#tabla').on('click', '.eliminar', function(){
            $(this).closest('tr').remove();

            $('#tabla tbody tr').each(function(){
                var cantidad = $(this).find('td').eq(2).children().val();
                var precio = $(this).find('td').eq(4).children().val();
                $(this).find('td').eq(5).children().val(cantidad*precio);
            });
        });
        
        $('#contendor_table').on('keyup change', '.tabla_items',function(){
            $('.descripcion-item').autocomplete({
                source : '<?PHP echo base_url();?>index.php/WS_ventas/buscador_item',
                minLength : 2,
                select : function (event,ui){
                    var _item = $(this).closest('.cont-item');
                    var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.producto_id + '" name = "item_id[]" id = "item_id">';

                    _item.find('#data_item').html(data_item);
                    _item.find('#descripcion').attr("readonly",true);
                    _item.find('#unidad').val(ui.item.unidad);
                    _item.find('#producto').val(ui.item.producto);
                }
            });
        });
        
        $("#chkNotas").change(function(){
            if($(this).is(":checked")){
                $("#notas").removeAttr("disabled");
                $("#div_notas_ventas").show();
            }else{
                $("#notas").attr("disabled","tue");
                $("#div_notas_ventas").hide();
            }
        });
        
        //para el update
        if(pedido_almacen_id != ''){
            $("#texto_titulo").text("MODIFICAR PEDIDO ALMACÉN");
            $("#guardar").val("MODIFICAR PEDIDO ALMACÉN");
            
            let url_cabecera = base_url + 'index.php/WS_pedido_almacenes/select/' + pedido_almacen_id;
            $.getJSON(url_cabecera)
            .done(function (data) {
                if(data.notas != ''){
                    $("#div_notas_ventas").show();
                    $("#notas").val(data.notas);
                }
            });
            
            url_detaurl_detalle  = base_url + 'index.php/WS_pedido_almacen_detalles/ws_detalle/' + pedido_almacen_id;
            $.getJSON(url_detaurl_detalle)
            .done(function (data) {
                (data).forEach(function (repo) {
                    agregarFila(repo.producto, repo.producto_id, repo.unidad, repo.cantidad);
                });
            });
        }
    });
    
    //se tomará en consideración para exportación
    //tipo operacion...  para exportacion valor: 0200
    //tipo_entidad_id = 0....  para exportació pq sería código: 0 Según Sunat --- Empresas Del Extranjero - No Domiciliado    
    function detectorInconsistencias(tipo_igv_producto, tipo_operacion, tipo_entidad_id, array_producto_id, array_cantidad, entidad_id, tipo_documento, adjuntar_documento){
        var tipo_entidad_id = Number(tipo_entidad_id);
        var tipo_documento_id = Number(tipo_documento);
        var tipo_igv_exportacion = 0; //tipo igv para exportación es 19
        var tipo_igv_otro = 0;        
        
        array_cantidad.forEach(function(cantidad){
            if(Number(cantidad) == 0){
                alert('Las cantidades deben ser mayor a cero (0)');
                respuesta_inconsistencia = 1;
            }
        });
        
        array_producto_id.forEach(function(producto_id){
            if((producto_id === undefined) || (producto_id == '')){
                alert('Debe ingresar todos los productos correctamente.');
                respuesta_inconsistencia = 1;
            }
        });
        
        tipo_igv_producto.forEach(function(tipo_igv){
            if(tipo_igv == 19){
                tipo_igv_exportacion = 1;
            }else{
                tipo_igv_otro = 1;
            }
        });
        
        if((entidad_id == null) || (entidad_id == '')){
            alert('Debe ingresar un cliente');
            respuesta_inconsistencia = 1;
        }
        
        if(tipo_igv_producto.length == 0){            
            alert('Debe ingresar al menos 1 producto.');
            respuesta_inconsistencia = 1;
        }
        
        if( (tipo_igv_exportacion == 1) && (tipo_igv_otro == 1) ){
            alert('No puede haber tipo de IGV exportación y otros tipos de IGV en los productos.');
            respuesta_inconsistencia = 1;
        }        

        if( ((tipo_igv_exportacion == 1) && (tipo_igv_otro == 0)) && (tipo_operacion != '0200') && (tipo_entidad_id != 3)  ){
            alert('Para exportación: \n -El tipo de Operación debe ser: exportación. \n -El Cliente debe ser exportación (Empresas Del Extranjero - No Domiciliado)');
            respuesta_inconsistencia = 1;
        }
        if( ((tipo_igv_exportacion == 1) && (tipo_igv_otro == 0)) && (tipo_operacion == '0200') && (tipo_entidad_id != 3)  ){
            alert('Para exportación: \n -El Cliente debe ser exportación (Empresas Del Extranjero - No Domiciliado)');
            respuesta_inconsistencia = 1;
        }
        if( ((tipo_igv_exportacion == 1) && (tipo_igv_otro == 0)) && (tipo_operacion != '0200') && (tipo_entidad_id == 3)  ){
            alert('Para exportación: \n -El tipo de Operación debe ser: exportación.');
            respuesta_inconsistencia = 1;
        }                
                
        if( (tipo_igv_exportacion == 0) && (tipo_operacion == '0200') && (tipo_entidad_id == 3)  ){
            alert('Para exportación: \n -Los productos deben tener tipo IGV de exportación.');
            respuesta_inconsistencia = 1;
        }
        if( (tipo_igv_exportacion == 0) && (tipo_operacion != '0200') && (tipo_entidad_id == 3)  ){
            alert('Para exportación: \n -Los productos deben tener tipo IGV de exportación. \n -El tipo de Operación debe ser: exportación.');
            respuesta_inconsistencia = 1;
        }
        if( (tipo_igv_exportacion == 0) && (tipo_operacion == '0200') && (tipo_entidad_id != 3)  ){
            alert('Para exportación: \n -Los productos deben tener tipo IGV de exportación. \n -El Cliente debe ser exportación (Empresas Del Extranjero - No Domiciliado)');
            respuesta_inconsistencia = 1;
        }
        
        //Para entidad dni con boleta, ruc con factura
        //tipo_documento  1 factura --  3 boleta
        //tipo_entidad_id 1 DNI --  2 RUC
        if( (tipo_operacion == '0101') && (tipo_documento_id == 1) && (tipo_entidad_id == 1) ){
            alert('La factura no puede ser con DNI');
            respuesta_inconsistencia = 1;
        }
        
        if( (tipo_operacion == '0101') && (tipo_documento_id == 3) && (tipo_entidad_id == 2) ){
            alert('La boleta no puede ser con RUC');
            respuesta_inconsistencia = 1;
        }

        if( ((tipo_documento_id == 7) || (tipo_documento_id == 8)) && ((adjuntar_documento == '') || adjuntar_documento == null)){
            alert('Para Nota de Crédito o Débito debe adjuntar un documento.');
            respuesta_inconsistencia = 1;
        }        
        return respuesta_inconsistencia;
    }
    
    function agregarFila(producto, producto_id, unidad, cantidad){
        producto = (producto == undefined) ? '' : producto;
        producto_id = (producto_id == undefined) ? '' : 'value = ' + producto_id;
        unidad = (unidad == undefined) ? '' : 'value = ' + unidad;
        cantidad = (cantidad == undefined) ? 'value = ' + 1 : 'value = ' + cantidad;
        
        var fila = '<tr class="cont-item fila_generada" >';
        fila += '<td class="col-sm-4" style="border:0;"><input type="hidden" class="form-control decription_producto" id="producto" name="producto[]"><input value = "' + producto + '" class="form-control descripcion-item" id="descripcion" name="descripcion[]" required=""><div id="data_item"><input class="val-descrip" '+producto_id+' type="hidden" name="item_id[]" id="item_id"></div></td>';
        fila += '<td style="border:0;"><input ' + unidad + ' type="text" class="form-control" readonly id="unidad" name="unidad[]"></td>';
        fila += '<td style="border:0;"><input ' + cantidad + ' type="number" id="cantidad" name="cantidad[]" class="form-control cantidad" ></td>';
        fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
        fila += '</tr>';

        $("#tabla").css("display","block");
        $("#tabla tbody").append(fila);    
    }

</script>