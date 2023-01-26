<style>
    .shadow {
        box-shadow: 0px 0px 1px 0px #b3b2b2e3;
        border: .2px solid #cac8c8e3;
        border-radius: 1px;
    }
    .table-responsive table tbody tr td {
        border: .2px solid #dedede85;
        padding: 1rem;
        text-align: center;
    }
</style>
<!-- <div align="center" style="font-size: 27px">SISTEMA FACTURACIÓN ELECTRÓNICA</div> -->
<div class="container">
    <div class="row">
        <div style="font-family: tahoma; font-size: 20px" class="col-md-6">
            <span>Bienvenido:</span><?PHP echo " " . ucfirst($this->session->userdata('tipo_empleado')) . "&nbsp;&nbsp;&nbsp;" . $this->session->userdata('usuario') . ", " . $this->session->userdata('apellido_paterno'); ?>&nbsp;&nbsp;&nbsp;
        </div>
    </div>
    <hr>
</div>
<div class="container">
    <div class="sms"></div>
</div>
<div class="container-fluid" style="width: 98%;margin: 0 auto;">
    <div class="row">
        <div class="col-md-6">
            <h4>Resumen de ventas del <label id="fecha_inicio"></label> al <label id="fecha_final"></label> </h4>

        </div>
        <div class="col-md-6">
            <form id="formdash" method="POST" class="form-inline" role="form">

                <div class="form-group">
                    <label class="sr-only" for="">Fecha Inicio</label>
                    <input type="date" class="form-control fecha" name="inicio" id="inicio" placeholder="Fecha Inicio" value="<?php echo date('Y-m-d');?>">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="">Fecha Final</label>
                    <input type="date" class="form-control fecha" name="final" id="final" placeholder="Fecha Final" value="<?php echo date('Y-m-d');?>">
                </div>
                <button class="btn btn-primary" id="update"> Actualizar </button>
            </form>
        </div>
    </div>

    <div class="row shadow">
        <div class="col-md-8" id="table-dash">
            
            

        </div>
        <div class="col-md-4">            
           <canvas id="bar-chart-grouped" width="800" height="450"></canvas>
        </div>

    </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    /*new Chart(document.getElementById("bar-chart-grouped"), {
        type: 'bar',
        data: {
          labels: ["1900", "1950", "1999", "2050"],
          datasets: [
            {
              label: "Africa",
              backgroundColor: "#3e95cd",
              data: [133,221,783,2478]
            }, {
              label: "Europe",
              backgroundColor: "#8e5ea2",
              data: [408,547,675,734]
            }
          ]
        },
        options: {
          title: {
            display: true,
            text: 'Population growth (millions)'
          }
        }
    });*/
</script>

<script type="text/javascript" >

    jQuery(document).ready(function($) {
        getDatos();
        $('#update').click(function(e) {
            e.preventDefault();
            var ini = $('#inicio').val();
            var fin = $('#final').val();
            if (ini=='') {
                $('.sms').html('<div class="alert alert-warning">Selecciona fecha de inicio </div>');
                setTimeout(function() {$('.sms').html('');}, 2000);
            } else if (fin=='') {
                $('.sms').html('<div class="alert alert-warning">Selecciona fecha final </div>');
                setTimeout(function() {$('.sms').html('');}, 2000);
            }else {
                getDatos(ini,fin);
            }
        });

    });


    function getDatos(ini='',fin='') {

        $.ajax({
            url: '<?= base_url();?>index.php/comprobantes/dashboard',
            type: 'POST',            
            data: {inicio: ini, final:fin},
        })
        .done(function(data) {
            var datos = JSON.parse(data);
            var html = '';

            html+='<div class="table-responsive"><table class="table" style="overflow:hidden;">';
            html+='<thead><tr><th></th><th>Boleta Soles</th><th>Boleta Dólares</th><th>Factura Soles</th><th>Factura Dólares</th></tr></thead><tbody>';

            html+='<tr>';
            html+='<td>Efectivo</td>';
            html+='<td><p>S/. '+ datos.e_boleta_soles.toFixed(2) +'</p><span>'+ datos.cantebsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.e_boleta_dolar.toFixed(2) +'</p> <span>'+ datos.cantebdolar +' documentos</span></td>';
            html+='<td><p>S/. '+ datos.e_factura_soles.toFixed(2) +'</p><span>'+ datos.cantefsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.e_factura_dolar.toFixed(2) +'</p><span>'+ datos.cantefdolar +' documentos</span></td>';
            html+='</tr>';

            html+='<tr>';
            html+='<td> tarjeta </td>';
            html+='<td><p>S/. '+ datos.t_boleta_soles.toFixed(2) +'</p><span>'+ datos.canttbsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.t_boleta_dolar.toFixed(2) +'</p><span>'+ datos.canttbdolar +' documentos</span></td>';
            html+='<td><p>S/. '+ datos.t_factura_soles.toFixed(2) +'</p><span>'+ datos.canttfsoles +' documentos</span></td>';
            html+='<td><p>$ '+ datos.t_factura_dolar.toFixed(2) +'</p><span>'+ datos.canttfdolar +' documentos</span></td>';
            html+='</tr>';

            html+='<tr>';
            html+='<td> Total </td>';
            html+='<td><p>S/. '+ (datos.e_boleta_soles + datos.t_boleta_soles).toFixed(2) +'</p><span>'+ (datos.cantebsoles + datos.canttbsoles ) +' documentos</span></td>';
            html+='<td><p>$ '+ (datos.e_boleta_dolar + datos.t_boleta_dolar).toFixed(2) +'</p><span>'+ (datos.cantebdolar + datos.canttbdolar) +' documentos</span></td>';
            html+='<td><p>S/. '+ (datos.e_factura_soles + datos.t_factura_soles).toFixed(2) +'</p><span>'+ (datos.cantefsoles + datos.canttfsoles) +' documentos</span></td>';
            html+='<td><p>$ '+ (datos.e_factura_dolar + datos.t_factura_dolar).toFixed(2) +'</p><span>'+ (datos.cantefdolar + datos.canttfdolar) +' documentos</span></td>';
            html+='</tr>';

            html+='</tbody></table></div>';

            $('#table-dash').html(html);
            $("#fecha_inicio").text($('#inicio').val());
            $("#fecha_final").text($('#final').val());

            new Chart(document.getElementById("bar-chart-grouped"), {
                type: 'bar',
                data: {
                  labels: ["Boleta Efectivo", "Boleta Tarjeta", "Factura Efectivo", "Factura Tarjeta"],
                  datasets: [
                    {
                      label: "Soles",
                      backgroundColor: "#3e95cd",
                      data: [datos.e_boleta_soles.toFixed(2),datos.t_boleta_soles.toFixed(2),datos.e_factura_soles.toFixed(2),datos.t_factura_soles.toFixed(2)]
                    }, {
                      label: "Dolar",
                      backgroundColor: "#8e5ea2",
                      data: [datos.e_boleta_dolar.toFixed(2),datos.t_boleta_dolar.toFixed(2),datos.e_factura_dolar.toFixed(2),datos.t_factura_dolar.toFixed(2)]
                    }
                  ]
                },
                options: {
                  title: {
                    display: true,
                    text: 'Gráfico resumen ventas'
                  }
                }
            });

        })
        
    }


</script>


