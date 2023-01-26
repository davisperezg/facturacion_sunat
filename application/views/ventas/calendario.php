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
    var mes_boton = m;
    
    //console.log(base_url + "index.php/WS_cuotas/select_cuotas_clientes");    
    $(document).ready(function(){
        $('body').on('click', 'button.fc-next-button', function() {  
            mes_boton += 1;
            console.log('mes_boton:' + mes_boton);
            console.log('abc');
        });
        
        $('body').on('click', '#nepele', function() {  
            console.log('b');
            //calendar.next();
        });
        
        
        
       $('#CalendarioWeb').fullCalendar({
           events: base_url + "index.php/WS_ventas/calendario",
           
           header:{
               left: 'today, prev, next, Miboton',
               center: 'title',
           },                      
           
           
           customButtons:{
               Miboton:{
                   id: "nepele",
                   text:"Siguiente",
                   click:function(){
                       console.log('a');
                       $('#CalendarioWeb').next();
                       events: base_url + "index.php/WS_ventas/calendario/"
                   }
               }
           }

           
       });       
    });   
    
</script>