<script src="<?PHP echo base_url();?>assets/js/monstruo/help.js"></script>
<script src="<?PHP echo base_url();?>assets/js/calendar/moment.min.js"></script>
<link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/calendar/fullcalendar.min.css">
<script src="<?PHP echo base_url();?>assets/js/calendar/fullcalendar.min.js"></script>
<script src="<?PHP echo base_url();?>assets/js/calendar/es.js"></script>

<div class="container">
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10"><div id="CalendarioWeb"></div></div>
        <div class="col-xs-1"></div>
    </div>            
</div>
        
<script src="<?PHP echo base_url(); ?>assets/js/monstruo/cuotas_and_cobros.js"></script>
<script>
    var base_url = '<?php echo base_url();?>';
    var cobro_id;
    var venta_id_select = 1;
    var super_total_a_pagar = 0;
    var super_total_pagado = 0;
    var mes_actual = 
    
    //console.log(base_url + "index.php/WS_cuotas/select_cuotas_clientes");    
    $(document).ready(function(){
        $('body').on('click', 'button.fc-next-button', function() {
  
            console.log('abc');
        });
        
        
       $('#CalendarioWeb').fullCalendar({            
            
           
           header:{
               left: 'today, prev, next',
               center: 'title',
               //right: 'month, basicWeek, basicDay'
           },
           
//           next:{
//               click:function(){
//                   console.log('abc');
//                   alert('recon');
//               }
//           },
           
            
           
//           dayClick:function(date, jsEvent, view){               
//               $("#exampleModal").modal();
//           },
           
           events: base_url + "index.php/WS_ventas/calendario",
//           eventClick:function(calEvent, jsEvent, view){
//                cuota_id = calEvent.cuota_id;
//                venta_id_select = calEvent.venta_id;              
//               
//                ruta_url_item = base_url + 'index.php/WS_cuotas/reporte_cuota/' + cuota_id;                
//                $.getJSON(ruta_url_item)
//                .done(function (data){
//                    $('#numero_pago').text(data.cuota_id);
//                    $('#fecha_cuota').text(data.fecha_cuota);
//                    $('#monto').text(data.total_a_pagar);
//                    $('#estado_cuota').text(data.estado_cuota);
//
//                    $('#detalle_entidad').text(data.entidad);
//                    $('#span_cliente').text(data.entidad);
//                    $('#detalle_documento').text(data.numero);                    
//                    
//                    $("#tabla_id > tbody").remove();                                        
//                    ruta_venta = base_url + 'index.php/WS_ventas/select_all/' + data.entidad_id;
//                    $.getJSON(ruta_venta)
//                    .done(function (datos){
//                        super_total_a_pagar = 0;
//                        super_total_pagado = 0;
//                        (datos).forEach(function (repo) {
//                            agregarFila(repo.numero, repo.total_a_pagar, repo.total_pagado);
//                        });
//                        agrega_total();
//                    });
//                });                
//                
//                $("#tabla_cuota > tbody").remove();
//                $("#tabla_pago > tbody").remove();
//                
//                carga_inicial_cuotas();
//                carga_inicial_cobros();
//               $("#exampleModal").modal();
//           }
       });
    });
    
    var contador_fila = 0;
    function agregarFila(numero, total_a_pagar, total_pagado){
        color = ((contador_fila % 2) == 0) ? "style='background-color: #EAF2F8'" : '';
        contador_fila ++;
        
        super_total_a_pagar += parseFloat(total_a_pagar);
        super_total_pagado += parseFloat(total_pagado);
        
        var fila = '<tr ' + color + ' >';
        fila += '<td>'+numero+'</td>';        
        fila += '<td class="derecha_text">'+total_a_pagar+'</td>';
        fila += '<td class="derecha_text">'+total_pagado+'</td>';
        fila += '<td class="derecha_text">'+(total_a_pagar - total_pagado).toFixed(2)+'</td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);
    }
    
    function agrega_total(){
        var fila = '<tr ' + color + ' class="seleccionado tabla_fila">';
        fila += '<td align="right"><b>Totales:</b></td>';
        fila += '<td class="derecha_text">'+super_total_a_pagar.toFixed(2)+'</td>';
        fila += '<td class="derecha_text">'+super_total_pagado.toFixed(2)+'</td>';
        fila += '<td class="derecha_text">'+(super_total_a_pagar - super_total_pagado).toFixed(2)+'</td>';
        fila += '</tr>';
        $("#tabla_id").append(fila);
    }
</script>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de Cuota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td style="width: 120px"><b>Fecha de Cuota:</b></td>
                                <td><span id="fecha_cuota"></span></td>
                            </tr>
                            <tr>
                                <td style="width: 120px"><b>Monto</b></td>
                                <td><span id="monto"></span></td>
                            </tr>
                            <tr>
                                <td style="width: 120px"><b>Estado</b></td>
                                <td><span id="estado_cuota"></span></td>
                            </tr>                            
                        </table>
                    </div>
                    <div class="col-lg-6" style="padding-top: 10px">
                        <table>
                            <tr>
                                <td style="width: 120px"><b>Cliente</b></td>
                                <td><span id="detalle_entidad"></span></td>
                            </tr>
                            <tr>
                                <td style="width: 120px"><b>Documento</b></td>
                                <td><span id="detalle_documento"></span></td>
                            </tr>                        
                        </table>
                    </div>                
                </div>                
                <br>
                <div class="row">                
                    <br>
                    <div class="col-xs-6">
                        <label>Cuotas</label>
                        <div class="row-fluid">
                            <table id="tabla_cuota" class="table table-bordered table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th>N.</th>                                    
                                        <th>Fecha</th>
                                        <th class="derecha_text">Monto</th>
                                        <!--<th class="centro_text"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>-->
                                    </tr>
                                </thead>
                            </table>    
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label>Pagos</label>
                        <div class="row-fluid">
                            <table id="tabla_pago" class="table table-bordered table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th>N.</th>                                    
                                        <th>Fecha</th>
                                        <th class="derecha_text">Monto</th>
                                        <th class="centro_text">Modo</th>
                                        <th class="centro_text">Nota</th>
                                    </tr>
                                </thead>
                            </table>    
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12">
                        <label>Balance Histórico: <span id="span_cliente"></span></label>
                        <table id="tabla_id" class="table table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>Documento</th>                                
                                    <th class="derecha_text">Total</th>
                                    <th class="derecha_text">Pagado</th>
                                    <th class="derecha_text">Saldo</th>                                
                                </tr>
                            </thead>
                        </table>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
   </div>
</div>