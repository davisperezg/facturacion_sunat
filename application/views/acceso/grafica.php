<br><br>
<div class="container">
    <h3 align="center">Create Dynamic Column Chart using PHP Ajax</h3>
    <br>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <h3 class="panel-title">Month Wise Profit Data</h3>
                </div>
                <div class="col-md-3">
                    <select name="year" id="year" class="form-control">
                        <option value="">Select Year</option>
                        <option value="2021">2021</option>
                    </select>
                </div>
            </div>            
        </div>
        <div class="panel-body">
            <div id="chart_area" style="width: 1000px; height: 620px"></div>
        </div>        
    </div>    
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    var ruta_base = '<?php echo base_url();?>';   
    ruta = ruta_base + 'index.php/WS_test_borrar/anios';
    ruta_select = ruta_base + 'index.php/WS_ventas/suma_ventas';
        
    $.getJSON(ruta)
    .done(function(data){
        (data).forEach(function(repo){
            $("#year").append($('<option>', {
                value: repo.YEAR,
                text: repo.YEAR
            }))
        })
    });

            
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback();

    function load_monthwise_data(year, title){
        var temp_title = title + ' ' + year + '';
        $.ajax({
            url: ruta_select + '/' + year,
            method : "GET",
            data: {year: year},
            dataType: "JSON",
            success:function(data)
            {
                console.log('data:' + data);
                drawMonthwiseChart(data, temp_title );
            }
        });
    }
    
    function drawMonthwiseChart(chart_data, chart_main_title){
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Mes');
        data.addColumn('number', 'Monto');
        $.each(jsonData, function(i, jsonData){
            var month = jsonData.mes;
            var profit = parseFloat($.trim(jsonData.suma));
            data.addRows([[month, profit]]);
        });
                                
        var options = {
            title:chart_main_title,
            hAxis:{
                title: "Months"
            },
            vAxis:{
                title: "Profit"
            }
        };        
        var chart = new google.visualization.ColumnChart(document.getElementById("chart_area"));        
        chart.draw(data, options);
    }
    
    $(document).ready(function(){
       $('#year').change(function(){
          var year = $(this).val();
          if(year != ''){
              console.log('anio: ' + year);
              load_monthwise_data(year, 'Total de ventas por Mes.')
          }
       });
    });
</script>