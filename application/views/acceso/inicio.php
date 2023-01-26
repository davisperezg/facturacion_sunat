<div class="container-fluid" style="margin: 0 25px;">
    <div class="row">
        <div style="font-family: tahoma; font-size: 20px" class="col-md-12">
            <span>Bienvenido:</span><?PHP echo " " . ucfirst($this->session->userdata('tipo_empleado')) . "&nbsp;&nbsp;&nbsp;" . $this->session->userdata('usuario') . ", " . $this->session->userdata('apellido_paterno'); ?> 
        </div>
    </div>
    <hr style="border:1px solid #F2F3F4;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <h3 class="panel-title">Venta mensuales:</h3>
                </div>
                <div class="col-md-3">
                    <select name="year" id="year" class="form-control">                        
                    </select>
                </div>
            </div>            
        </div>
        <div class="panel-body">
            <div id="chart_area" style="width: 1000px; height: 620px"></div>
        </div>        
    </div>    
    
</div>
<div class="container">
    <div class="sms"></div>
</div>

<div class="container-fluid" style="margin: 0 25px;">

</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/monstruo/help.js"></script>
<script type="text/javascript">
    var base_url = '<?PHP echo base_url();?>';
        
    $.getJSON(base_url + 'index.php/WS_variables_diversas/productos_automaticos')
    .done(function (data) {
        localStorage.setItem("productos_automaticos", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_variables_diversas/datos_accesorios')
    .done(function (data) {
        localStorage.setItem("datos_accesorios", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_unidades/select_activos')
    .done(function (data) {
        localStorage.setItem("unidades_activas", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_variables_diversas/ruta_guias')
    .done(function (data) {
        localStorage.setItem("ruta_guias", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_producto_movimientos/ws_select_movimiento')
    .done(function (data) {
        localStorage.setItem("movimientos", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_forma_pagos/select_all')
    .done(function (data) {
        localStorage.setItem("forma_pagos", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_modo_pagos/select_all')
    .done(function (data) {
        localStorage.setItem("modo_pagos", JSON.stringify(data));
    });
    
    $.getJSON(base_url + 'index.php/WS_variables_diversas/select_all')
    .done(function (data) {
        localStorage.setItem("variables_diversas", JSON.stringify(data));
    });
        
    var url_serie = base_url + 'index.php/WS_series/series_defecto';
    $.getJSON(url_serie)
        .done(function (data) {
            localStorage.setItem("series_defecto", JSON.stringify(data.ws_select_series));        
    });
    
    $.getJSON(base_url + 'index.php/WS_monedas/monedas')
            .done(function (data) {
                localStorage.setItem("monedas", JSON.stringify(data.monedas));
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_documentos/tipo_documentos_all')
            .done(function (data) {                
                localStorage.setItem("tipo_documentos", JSON.stringify(data.tipo_documentos));
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_igvs/select_js')
            .done(function (data) {
                sortJSON(data.tipo_igv, 'id', 'asc');
                localStorage.setItem("tipo_igv", JSON.stringify(data.tipo_igv));
    });
    
    $.getJSON(base_url + 'index.php/WS_variables_diversas/datos_configuracion')
            .done(function (data) {
                localStorage.setItem("datos_configuracion", JSON.stringify(data.datos_configuracion));
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_entidades/select')
            .done(function (data) {
                localStorage.setItem("tipo_entidades", JSON.stringify(data.tipo_entidades));
    });
    
    $.getJSON(base_url + 'index.php/WS_bancos/select_js')
            .done(function (data) {
                localStorage.setItem("bancos", JSON.stringify(data.bancos));
    });
    
    $.getJSON(base_url + 'index.php/WS_tipo_cuentas/select_js')
            .done(function (data) {
                localStorage.setItem("tipo_cuentas", JSON.stringify(data.tipo_cuentas));
    });
    
    $.getJSON(base_url + 'index.php/WS_empresas/ws_select')
            .done(function (data) {
                localStorage.setItem("empresas", JSON.stringify(data));
    });

    ruta_select = base_url + 'index.php/WS_ventas/suma_mensual';
                
    for(var i = 0; i < 10; i++){
        $("#year").append($('<option>', {
            value: y - i,
            text: y- i
        }))
    }            
            
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback();

    function load_monthwise_data(year, title){
        var temp_title = title + ' ' + year + '';
        
        $.getJSON(ruta_select + '/' + year)
        .done(function (data) {
            drawMonthwiseChart(data, temp_title );
        });
    }
    
    function drawMonthwiseChart(chart_data, chart_main_title){
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Mes');
        data.addColumn('number', 'Ventas');
        data.addColumn('number', 'Compras');
        $.each(jsonData, function(i, jsonData){
            var month = mes_texto(jsonData.mes);
            var ventas = parseFloat($.trim(jsonData.suma_ventas));
            var compras = parseFloat($.trim(jsonData.suma_compras));
            data.addRows([[month, ventas, compras]]);
        });
                                
        var options = {
            title:chart_main_title,
            hAxis:{
                title: "Meses"
            },
            vAxis:{
                title: "Monto"
            }
        };        
        var chart = new google.visualization.ColumnChart(document.getElementById("chart_area"));        
        chart.draw(data, options);
    }
    
    $(document).ready(function(){
        load_monthwise_data($("#year").val(), 'Ventas mensuales');
        
       $('#year').change(function(){
          var year = $(this).val();
          if(year != ''){
              console.log('anio: ' + year);
              load_monthwise_data(year, 'Ventas mensuales');
          }
       });
    });
</script>