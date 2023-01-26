<!DOCTYPE html>
<html>
    <head>
        <title>Comprobante PDF-A4</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <style>    
            /* Agregando Inputs */
            .input-group {width: 100%;}
            .input-group-addon { min-width: 180px;text-align: right;}    

            .panel-title{
                font-size: 13px;
                font-weight: bold;
            }
            .letra_grande{
                font-size: 20px;
                font-family: Arial, Helvetica, sans-serif;
            }
            .letra_mediana{
                font-size: 12px;
                font-family: Arial, Helvetica, sans-serif;
            }
            .letra_pequenia{
                font-size: 10px;
                font-family: Arial, Helvetica, sans-serif;
            }
            .derecha_text { 
                text-align: right; 
            }
            .centro_text { 
                text-align: center; 
            }
            
            .tabla_borde{
                border:1.5px solid #aaa;border-radius:8px;
                padding: 10px;
            }
            .tabla_borde_sin_espacio{
                border:1.5px solid #aaa;border-radius:8px;
            }
            
            html, body{
                margin-top: 10px !important;
                padding-top: 10px !important;
            }
            .detalle, th, td {
                border-left: none;
                border-right: none;
                font-size: 13px;
                font-family: Arial, Helvetica, sans-serif;
            }
            .detalle tr:nth-child(even) {
                background-color: #f2f2f2;
            }

        </style>
    </head>
    <body>        
        <table style="width: 100%">        
            <tr>
                <td>        
                    <?php
                    //var_dump($cobro);exit;
                    $ruta = ($cobro['archivo_adjunto'] == null || $cobro['archivo_adjunto'] == '') ? 'sin_foto.jpg' : 'cobros/'.$cobro['archivo_adjunto'];
                    ?>
                    <img src="<?php FCPATH;?>images/<?php echo $ruta?>" height="160" style="text-align:center;" ><br>
                </td>                        
            </tr>
        </table>
        <div align="center"><h2>Detalle de Pago</h2></div>
        <table style="width: 100%">
            <tr>
                <td style="width: 60%">
                    <div class="tabla_borde">
                        <table style="width: 100%">        
                            <tr><td class="letra_mediana">N. pago: <b><?php echo $cobro['cobro_id']?></b></td></tr>
                            <tr><td class="letra_mediana">Fecha de pago: <b><?php echo $cobro['fecha_pago']?></b></td></tr>
                            <tr><td class="letra_mediana">Monto: <?php echo $cobro['monto'];?></td></tr>
                            <tr><td class="letra_mediana">Modo de Pago: <?php echo $cobro['modo_pago'];?></td></tr>
                            <tr><td class="letra_mediana">Nota de pago:<?php echo $cobro['nota'];?></td></tr>
                        </table>
                    </div>
                </td>
                <td style="width: 40%">
                    <div class="tabla_borde">
                        <table>
                            <tr><td class="letra_mediana">Cliente: <b><?php echo $cobro['entidad']?></b></td></tr>
                            <tr><td class="letra_mediana">Documento: <b><?php echo $cobro['serie'].'-'.$cobro['numero']?></b></td></tr>                            
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <br><br><br><hr>
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <label>Cuotas</label>
                    <div class="tabla_borde">
                        <table id="tabla_cuota" class="table table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>N.</th>                                    
                                    <th style="width: 80px">Fecha</th>
                                    <th style="width: 50px">Monto</th>
                                    <th class="centro_text">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($cuotas as $value_cuotas){
                                ?>
                                <tr>
                                    <td><?php echo $i; $i++?></td>
                                    <td><?php echo $value_cuotas['fecha_cuota']?></td>
                                    <td><?php echo $value_cuotas['monto']?></td>
                                    <td><?php echo $value_cuotas['estado']?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td style="width: 50%">
                    <label>Cobros</label>
                    <div class="tabla_borde">
                        <table id="tabla_pago" class="table table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>N.</th>                                    
                                    <th style="width: 80px">Fecha</th>
                                    <th style="width: 50px">Monto</th>
                                    <th>Modo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($cobros as $value_cobros){
                                ?>
                                <tr>
                                    <td><?php echo $i; $i++?></td>
                                    <td><?php echo $value_cobros['fecha_pago']?></td>
                                    <td><?php echo $value_cobros['monto']?></td>
                                    <td><?php echo $value_cobros['modo_pago']?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </td>                
            </tr>
        </table>
    </body>
</html>