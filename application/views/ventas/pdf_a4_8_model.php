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
                width: 35%;
                align: right;
            }
            #header_centro{
                font-family: Arial, Helvetica, sans-serif;
                float: left;
                width: 30%;
                padding-left: 10px;
                text-align: center;
            }
            #header_derecha{
                font-family: Arial, Helvetica, sans-serif;
                float: left;
                width: 35%;
                padding-left: 10px;
                border: 1px solid fff;
                text-align: center;
            }
            #datos{
                clear: left;
            }
            
            #datos_cliente{
                border: 1px solid fff;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                padding-left: 5px;                
            }

            #datos_varios{
                border: 1px solid fff;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                padding-left: 5px;
                margin-top: 20px;
                padding-top: 5px;
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
                font-size: 17px;
            }
            #ruc{
                font-size: 15px;
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
            .izquierda_text {
                font-family: Arial, Helvetica, sans-serif;
                text-align: left; 
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
                height: 200px; 

                
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

            #border_recon {
                border: 1px solid black;
                border-collapse: collapse;
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
                </div>
            </div>
            <div id="header_derecha">
                <div id="ruc">
                <?php echo "R.U.C. N. ".$empresa['ruc']."<br><br>";?>
                </div>
                <div id="tipo_documento">
                <?php echo strtoupper($tipo_documento)."<br><br>";
                echo "N° " . $cabecera['serie']."-".$cabecera['numero']."<br>"?>
                </div>
            </div>
        </div>
        <div style="clear: left;"></div>
        <br>
        <div id="datos_cliente">                        
            
            <div class="col_1">Cliente</div>
            <div class="col_2">: <?php echo $cabecera['entidad'];?></div>
            <div class="col_3">Emisión</div>
            <div class="col_4">: <?php echo $cabecera['fecha_emision'];?></div>            
            <div style="clear: left;"></div>            
            
            <div class="col_1"><?php echo $cabecera['tipo_entidad'];?></div>
            <div class="col_2">: <?php echo $cabecera['numero_documento'];?></div>
            <?php
            if($fecha_vencimiento != ''){
            ?>
            <div class="col_3">Vencimiento</div>
            <div class="col_4">: <?php echo $fecha_vencimiento;?></div>
            <?php
            }
            ?>
            <div style="clear: left;"></div>
            
            <div class="col_1">Dirección</div>
            <div class="col_2">: <?php echo $cabecera['direccion_entidad'];?></div>            
            <div style="clear: left;"></div>
        </div>

        <div id="datos_varios">
            <div class="col_1">Forma de pago</div>
            <div class="col_2">: <?php echo strtoupper($forma_pago);?></div>
            <?php
            if($cabecera['forma_pago_id'] == 1){?>
                <div class="col_3">Modo pago </div>
                <div class="col_4"><?php if($cabecera['modo_pago']!= '') { echo ": ".$cabecera['modo_pago'];} ?></div>
            <?php
            }?>
            <div style="clear: left;"></div>

            <div class="col_1"><?php if($numero_guia!= '') { echo "O/C"; } ?></div>
            <div class="col_2"><?php if($orden_compra!= '') { echo ": ".$orden_compra;} ?></div>
            <div class="col_3"><?php if($numero_guia!= '') { echo "Guía Nro."; } ?></div>
            <div class="col_4"><?php if($numero_guia!= '') { echo ": ".$numero_guia;} ?></div>
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
            }

            if(isset($nota_credito) && ($nota_credito['serie'] != null)){?>            
            <div class="col_1">Documento adjunto:</div>
            <div class="col_2">: <?php echo $nota_credito['serie'].'-'.$nota_credito['numero'];?></div>
            <div class="col_3">Motivo</div>
            <div class="col_4">: <?php echo $nota_credito['tipo_ncredito'];?></div>
            <div style="clear: left;"></div>
            <?php
            }
            ?>
        </div>
                        
        <br><br>
        <table style="width: 100%" border="0" cellspacing="0" cellpadding="0" class="detalle">
            <thead>
                <tr style="background-color: #1B2B7B; color: white">
                    <th class="izquierda_text">CANT.</th>
                    <th class="izquierda_text">U.M.</th>
                    <th class="izquierda_text">COD.</th>
                    <th class="izquierda_text">DESCRIPCIÓN</th>
                    <!--<th class="derecha_text">Desc.</th>-->
                    <th class="derecha_text">P/U</th>
                    <?php
                    if($cabecera['total_bolsa'] != null){ ?>
                    <th class="derecha_text">Bolsa</th>
                    <?php                        
                    }?>
                    <th class="derecha_text">IMPORTE</th>
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
                    <td><?php echo $value['codigo_producto']?></td>
                    <td align="justify" ><?php echo $value['producto']?></td>
                    <!--<td class="derecha_text"><?php //echo $value['descuento']; ?></td>-->
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
            <?php //echo $totalLetras?>
        </div>
        <table class="totales" style="width: 100%">            
            <?php            
            if(($cabecera['total_descuentos'] != null) || ($sumatoria_descuentos_item > 0)){
                $total_desc = ($cabecera['total_descuentos'] != null) ? ($cabecera['total_descuentos'] + $sumatoria_descuentos_item) : $sumatoria_descuentos_item;
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Descuento</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($total_desc, 2);?></td>
            </tr>
            <?php             
            }            
            if($cabecera['total_gravada'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Gravada</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_gravada'],2)?></td>
            </tr>            
            <?php }
            if($cabecera['total_gratuita'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Gratuita</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_gratuita'],2)?></td>
            </tr>
            <?php }
            if($cabecera['total_exportacion'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Exportación</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_exportacion'],2)?></td>
            </tr>
            <?php }
            if($cabecera['total_exonerada'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Exonerado</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_exonerada'],2)?></td>
            </tr>
            <?php }
            if($cabecera['total_inafecta'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Inafecta</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_inafecta'],2)?></td>
            </tr>
            <?php }
            
            if($anticipo_total_pagar > 0){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Anticipo</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($anticipo_total_pagar - $anticipo_total_igv, 2)?></td>
            </tr>
            <?php }?>
                        
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>IGV <?php echo $cabecera['porcentaje_igv']*100?>%</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_igv'] - $anticipo_total_igv, 2);?></td>
            </tr>                        
            
            <?php 
            if($cabecera['total_bolsa'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>ICBPER</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo number_format($cabecera['total_bolsa'],2)?></td>
            </tr>
            <?php }            
            if($cabecera['total_a_pagar'] != null){?>
            <tr>
                <td colspan="4"><?php echo $totalLetras?></td>
                <td style="background-color: #1B2B7B; color: white"><b>Total</b></td>
                <td style="background-color: #1B2B7B; color: white"><b><?php echo $cabecera['simbolo_moneda']?></b></td>
                <td style="background-color: #1B2B7B; color: white" class="derecha_text"><b><?php echo number_format($cabecera['total_a_pagar'] - $anticipo_total_pagar, 2)?></b></td>
            </tr>
            <?php }?>
        </table>
        
        
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
                <tr>
                    <td colspan="2">
                        <br>
                        Sirvanse realizar el depósito a nuestra cuenta BCP:
                        <table id="border_recon">
                            <tr>
                                <td id="border_recon">BANCO DE CREDITO DEL PERÚ&nbsp;</td>
                                <td id="border_recon">&nbsp;1918853815072 </td>
                            </tr>
                            <tr>
                                <td id="border_recon">CCI</td>
                                <td id="border_recon">&nbsp;00219100885381507258</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>                    
        </div>
    </body>
</html>