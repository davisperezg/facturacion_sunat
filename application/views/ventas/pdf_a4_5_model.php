<!DOCTYPE html>
<html>
    <head>
        <title>Comprobante PDF-A4</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #abajo_izquierda{
                float: left;
                width: 530px;
                font-family: Arial, Helvetica, sans-serif;
            }
            #abajo_dereha{
                font-family: Arial, Helvetica, sans-serif;
                float: left;
                width: 180px;
                border: 1px solid fff;
                border-radius: 12px;
            }
            
            #header_izquierda{
                float: left;
                width: 25%;
                align: right;
            }
            #header_centro{
                font-family: Arial, Helvetica, sans-serif;
                float: left;
                width: 50%;
                padding-left: 10px;
                text-align: center;
            }
            #header_derecha{
                font-family: Arial, Helvetica, sans-serif;
                float: left;
                width: 25%;
                padding-left: 10px;
                border: 1px solid fff;
                border-radius: 12px;
                text-align: center;
            }
            #datos{
                clear: left;
            }
            
            #datos_cliente{
                border: 1px solid fff;
                border-radius: 12px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                padding-left: 5px;
            }
            
            .col_1{
                text-align: left;
                float: left;
                width: 15%;
                font-weight: bold;
                padding-bottom: 5px;
            }
            .col_2{
                text-align: left;
                float: left;
                width: 50%;
            }
            .col_3{
                text-align: left;
                float: left;
                width: 15%;
                text-align: right;
                font-weight: bold;
            }
            .col_4{
                text-align: left;
                float: left;
                width: 20%;
            }
            
            #tipo_documento{                
                font-size: 15px;
            }
            #ruc{
                font-size: 18px;
            }
            .tamanio_mediano{
                font-size: 12px;
            }
            #cliente{
                float: left;
                width: 55%;                
            }
            #numero{
                float: left;
                width: 45%;
            }
            .derecha_text {
                font-family: Arial, Helvetica, sans-serif;
                text-align: right; 
                width: 20px;
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
                font-family: Georgia, Cambria, Times;
            }
            .detalle tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            .colores_model{
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
            }
            .footerPdf {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 135px; 

                
                text-align: right;
                /*line-height: 35px;*/
            }
            
            table.roundedCorners { 
                border: 1px solid fff;
                border-radius: 13px; 
                border-spacing: 0;
                }
            table.roundedCorners td, 
            table.roundedCorners th {
                font-family: Arial, Helvetica, sans-serif;
                border-bottom: 1px solid fff;
                padding: 10px; 
            }
            table.roundedCorners tr:last-child > td {
                border-bottom: none;
            }
            .responsive {
                width: 100%;
                max-width: 400px;
                height: auto;
            }
        </style>
    </head>

    <body>
        <?php
        $tipo_documento = '';
        switch ($cabecera['operacion']) {
            case 1:
                $tipo_documento = $cabecera['tipo_documento']." ELECTRÓNICA";  
                break;
            case 2:
                $tipo_documento = 'NOTA DE VENTA';
                break;
            case 3:
                $tipo_documento = 'COTIZACIÓN';
                break;
        }
        $forma_pago     = ($cabecera['forma_pago_id'] == 2) ? 'Crédito' : 'Contado';
        $text_cambio    = ($cabecera['moneda_id'] != 1) ? 'Tipo de cambio:' : '';
        $tipo_monto     = ($cabecera['moneda_id'] != 1) ? $cabecera['tipo_de_cambio']."<br>" : '';        
        
        $fecha_vencimiento  = ($cabecera['fecha_vencimiento'] == null) ? '' : $cabecera['fecha_vencimiento'];        
        $orden_compra       = ($cabecera['orden_compra'] == null) ? '' : $cabecera['orden_compra'];
        $numero_guia        = ($cabecera['numero_guia'] == null) ? '' : $cabecera['numero_guia'];                        
        ?>
        <div id="div_header">
            <div id="header_izquierda">
                <div align="left">
                    <img aling="left" src="<?PHP FCPATH;?>images/empresas/<?php echo $empresa['foto'];?>" class="responsive" style="text-align:center;" ><br>
                </div>
            </div>            
            <div id="header_centro">
                <div class="tamanio_mediano" style="padding-top: 10px">
                    <b><?php echo $empresa['empresa']."<br>";?></b>
                    <?php echo $empresa['domicilio_fiscal']."<br>";?>
                    <?php echo "Teléfonos: ".$empresa['telefono_fijo']." // ".$empresa['telefono_movil']." - "?>
                    Forma de pago: <?php echo strtoupper($forma_pago);
                    if($cabecera['forma_pago_id'] == 1){
                        echo "<br>Modo pago: ".$cabecera['modo_pago'];
                    }
                    ?>
                </div>
            </div>
            <div id="header_derecha">
                <div id="ruc">
                <?php echo "R.U.C.<BR>".$empresa['ruc']."<br>";?>
                </div>
                <div id="tipo_documento">
                <?php echo strtoupper($tipo_documento)."<br>";
                echo $cabecera['serie']."-".$cabecera['numero']."<br>"?>
                </div>
            </div>
        </div>
        <div style="clear: left;"></div>
        <br>
        <div id="datos_cliente">
            <div class="col_1">Emisión</div>
            <div class="col_2">: <?php echo $cabecera['fecha_emision'];?></div>
            <div class="col_3">Guía Nro. :</div>
            <div class="col_4"></div>            
            <div style="clear: left; "></div>
            
            <div class="col_1">Cliente</div>
            <div class="col_2">: <?php echo $cabecera['entidad'];?></div>
            <div class="col_3">O/C :</div>
            <div class="col_4"><?php echo $orden_compra;?></div>            
            <div style="clear: left;"></div>            
            
            <div class="col_1"><?php echo $cabecera['tipo_entidad'].": "?></div>
            <div class="col_2">: <?php echo $cabecera['numero_documento'];?></div>
            <div class="col_3">Vencimiento :</div>
            <div class="col_4"><?php echo $fecha_vencimiento;?></div>            
            <div style="clear: left;"></div>
            
            <div class="col_1">Dirección</div>
            <div class="col_2">: <?php echo $cabecera['direccion_entidad'];?></div>
            <div class="col_3">Guía Nro. :</div>
            <div class="col_4"><?php echo $numero_guia;?></div>            
            <div style="clear: left;"></div>
            
            <?php
            if($cabecera['moneda_id'] != 1){?>
                <div class="col_1"><?php echo $text_cambio;?></div>
                <div class="col_2"><?php echo $tipo_monto;?></div>                
                <div class="col_3"></div>
                <div class="col_4"></div>
                <div style="clear: left;"></div>
            <?php
            }
            
            $anticipo_total_pagar = 0;
            $anticipo_total_igv = 0;
            if(count($venta_anticipos) > 0){?>                
                <div class="col_1"></div>
                <div class="col_2"></div>
                <div class="col_3">Anticipos:</div>
                <div class="col_4">
                <?php
                    foreach ($venta_anticipos as $value_anticipos){
                        echo $value_anticipos['serie'].'-'.$value_anticipos['numero'] . ": " . $value_anticipos['total_a_pagar']."<br>";
                        $anticipo_total_pagar += floatval($value_anticipos['total_a_pagar']);
                        $anticipo_total_igv += floatval($value_anticipos['total_igv']);
                    }
                ?>
                </div>                                
                <div style="clear: left;"></div>
            <?php
            }?>

            <?php
            if(isset($nota_credito) && ($nota_credito['serie'] != null)){?>            
            <div class="col_1">Documento adjunto:</div>
            <div class="col_2">: <?php echo $nota_credito['serie'].'-'.$nota_credito['numero'];?></div>
            <div class="col_3">Motivo :</div>
            <div class="col_4"><?php echo $nota_credito['tipo_ncredito'];?></div>
            <div style="clear: left;"></div>                        
            <?php
            }
            ?>                                
        </div>                        
                        
        <br><br>
        <table class="roundedCorners">
            <thead>
                <tr>
                    <th style="width: 15px">CANT.</th>
                    <th style="width: 20px">U.M.</th>
                    <th style="width: 400px">DESCRIPCIÓN</th>
                    <!--<th class="derecha_text">V/U</th>-->
                    <th style="width: 20px">Precio Unitario</th>
                    <?php
                    if($cabecera['total_bolsa'] != null){ ?>
                    <th style="width: 15px">Bolsa</th>
                    <?php                        
                    }?>
                    <th style="width: 20px" class="derecha_text">IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sumatoria_descuentos_item = 0;
                    foreach ($detalle as $value) {                        
                        $sumatoria_descuentos_item += $value['descuento'];
                        $impuesto_bolsa_item = ($cabecera['total_bolsa'] != null) ? number_format($value['impuesto_bolsa']*$value['cantidad'],$catidad_decimales - 2) : 0;
                        $base = number_format($value['precio_base'],$catidad_decimales - 2);
                        $base_mas_igv = number_format($value['precio_base']*(1+$cabecera['porcentaje_igv']),$catidad_decimales - 2);
                        $impuesto = ($value['tipo_igv_id'] == 1) ? (1+$cabecera['porcentaje_igv']) : 1;
                    ?>
                <tr>
                    <td><?php echo $value['cantidad']?></td>
                    <td><?php echo $value['codigo_unidad']?></td>
                    <td align="justify" ><?php echo $value['producto']?></td>
                    <td class="derecha_text"><?php echo number_format(($value['precio_base'] - $value['descuento'])*$impuesto,$catidad_decimales - 2); ?></td>
                    <?php
                    if($cabecera['total_bolsa'] != null){ ?>
                    <td class="derecha_text"><?php echo $impuesto_bolsa_item; ?></td>
                    <?php                        
                    }?>                       
                    <td class="derecha_text"><?php echo number_format(($value['cantidad']*(($value['precio_base'] - $value['descuento'])*$impuesto) + $impuesto_bolsa_item), 2); ?></td>
                </tr>
                <?php }?>
            </tbody>            
        </table>
        <br>
        <div id="abajo_izquierda">
            <?php echo $totalLetras?>
        </div>
        <table id="abajo_dereha">            
            <?php 
            if(($cabecera['total_descuentos'] != null) || ($sumatoria_descuentos_item > 0)){
                $total_desc = ($cabecera['total_descuentos'] != null) ? ($cabecera['total_descuentos'] + $sumatoria_descuentos_item) : $sumatoria_descuentos_item;
            ?>
            <tr>                
                <td class="colores_model">Descuento</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($total_desc, 2);?></td>
            </tr>
            <?php             
            }            
            if($cabecera['total_gravada'] != null){?>
            <tr>                
                <td class="colores_model">Gravada</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_gravada'], 2)?></td>
            </tr>            
            <?php }
            if($cabecera['total_gratuita'] != null){?>
            <tr>                
                <td class="colores_model">Gratuita</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_gratuita'],2)?></td>
            </tr>
            <?php }
            if($cabecera['total_exportacion'] != null){?>
            <tr>                
                <td class="colores_model">Exportación</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_exportacion'],2)?></td>
            </tr>
            <?php }
            if($cabecera['total_exonerada'] != null){?>
            <tr>                
                <td class="colores_model">Exonerado</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_exonerada'],2)?></td>
            </tr>
            <?php }
            if($cabecera['total_inafecta'] != null){?>
            <tr>                
                <td class="colores_model">Inafecta</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_inafecta'],2)?></td>
            </tr>
            <?php }
                                    
            if($anticipo_total_pagar > 0){?>
            <tr>                
                <td class="colores_model">Anticipo</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($anticipo_total_pagar - $anticipo_total_igv, 2)?></td>
            </tr>
            <?php }?>            
            <tr>                
                <td class="colores_model">IGV <?php echo $cabecera['porcentaje_igv']*100?>%</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_igv'] - $anticipo_total_igv, 2);?></td>
            </tr>
                     
            <?php
            if($cabecera['total_bolsa'] != null){?>
            <tr>                
                <td class="colores_model">ICBPER</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_bolsa'],2)?></td>
            </tr>
            <?php }            
            if($cabecera['total_a_pagar'] != null){?>
            <tr>
                <td class="colores_model" align="left">Total</td>
                <td class=""><?php echo $cabecera['simbolo_moneda']?></td>
                <td class=" derecha_text"><?php echo number_format($cabecera['total_a_pagar'] - $anticipo_total_pagar, 2)?></td>
            </tr>
            <?php }?>
        </table>        
        <?php
        if(count($cuotas) > 0){
        ?>
        <br><br>
        <table align="center">
            <thead>
                <tr style="background-color: #F7E9AD;">
                    <th>N. Cuota</th>
                    <th>Importe</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>            
            <?php
            $i = 1;
            foreach($cuotas as $value_cuotas){?>
            <tr>
                <td><?php echo $i; $i++?></td>
                <td><?php echo $value_cuotas['monto']?></td>
                <td><?php echo $value_cuotas['fecha_cuota']?></td>                
            </tr>
            <?php
            }            
            ?>
        </table>
        <?php
        }?>
        
        <div class="footerPdf">
            <table style="width: 100%">
                <tr>
                    <td style="width:auto">
                        <div class="tabla_borde_sin_espacio">
                            <table>
                                <tr>
                                    <td>
                                        <img src="<?php echo $rutaqr?>" style="width:2cm;height: 2cm;">
                                    </td>
                                </tr>
                            </table>                        
                        </div>
                    </td>
                    <td style="width: 90%">
                        <div class="tabla_borde_sin_espacio">
                            <table >
                                <tr>
                                    <td style="height: 2cm; font-size: 12px">
                                        <?php
                                        if($cabecera['notas'] != null){
                                            echo $cabecera['notas'];
                                        }else{
                                            echo "Representación impresa";
                                            if(($cabecera['operacion'] == 1)){
                                                echo " de la FACTURA ELECTRÓNICA.<br>";
                                                echo "EMITIDO MEDIANTE PROVEEDOR AUTORIZADO POR LA SUNAT RESOLUCION N.° 097- 2012/SUNAT ";
                                            }
                                        }                                    
                                        ?><br>
                                        <?php
                                        if($cabecera['firma_sunat'] != null){
                                        ?>
                                        Firma Electrónica: <?php echo $cabecera['firma_sunat'];
                                        }?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>                
                </tr>
            </table>                    
        </div>
    </body>
</html>