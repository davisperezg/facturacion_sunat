<!DOCTYPE html>
<html>
    <head>
        <title>Comprobante PDF-A4</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #datos_empresa{
                font-family: Georgia, Cambria, Times, serif;
                float: left;
                width: 55%;
                padding-left: 10px;
            }
            #logo{
                float: left;
                width: 45%;
                align: right;
            }
            #datos{
                clear: left;
            }            
            #tipo_documento{                
                font-size: 30px;
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
                font-family: Georgia, Cambria, Times;
            }
            .detalle tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            .colores_model{
                background-color: #F7E69C;
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
        ?>
        <div id="div_header">
            <div id="logo">
                <div align="left">
                    <img aling="left" src="<?PHP FCPATH;?>images/empresas/<?php echo $empresa['foto'];?>" class="responsive" style="text-align:center;" ><br>
                </div>
            </div>
            <div id="datos_empresa">
                <div id="tipo_documento">
                <?php echo strtoupper($tipo_documento)."<br>";?>
                </div>
                
                <div id="ruc">
                <?php echo $empresa['ruc']."<br>";?>
                </div>
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
        </div>
        <div style="clear: left;"></div>
        <div style="padding-top: 10px;">                        
            <div id="numero">
                <div align="left">
                    <table align="left">
                    <tr>
                        <td class="colores_model">Número:</td>
                        <td class="colores_model" style="font-size: 20px"><?php echo $cabecera['serie']."-".$cabecera['numero']."<br>"?></td>
                    </tr>
                    <tr>
                        <td>Fecha de Emisión:</td>
                        <td><?php echo $cabecera['fecha_emision'];?></td>
                    </tr>
                    <tr>
                        <td>Moneda:</td>
                        <td><?php echo ucfirst($cabecera['moneda']);?></td>
                    </tr>
                    
                    <?php
                    if($cabecera['fecha_vencimiento'] != null){?>                    
                    <tr>
                        <td>Fecha de Vencimiento:</td>
                        <td><?php echo $cabecera['fecha_vencimiento'];?></td>
                    </tr>
                    <?php
                    }?>
                    
                    <?php                            
                    if($cotizacion != array()){?>
                    <tr>
                        <td class="letra_mediana">Cotización:</td>
                        <td class="letra_mediana"><?php echo $cotizacion['numero']?></td>
                    </tr>
                    <?php
                    }                          
                    if($nota_venta != array()){?>
                    <tr>
                        <td class="letra_mediana">Nota de venta:</td>
                        <td class="letra_mediana"><?php echo $nota_venta['numero']?></td>
                    </tr>
                    <?php
                    }
                    
                    if(isset($venta_guia['serie']) && ($venta_guia['serie'] != '')){?>
                    <tr>
                        <td class="letra_mediana">Guia:</td>
                        <td class="letra_mediana"><?php echo $venta_guia['serie'].'-'.$venta_guia['numero']?></td>
                    </tr>
                    <?php
                    }
                    ?>                            
                    <?php
                    if($cabecera['orden_compra'] != ''){?>
                    <tr>
                        <td class="letra_mediana">Orden compra:</td>
                        <td class="letra_mediana"><?php echo $cabecera['orden_compra']?></td>
                    </tr>
                    <?php
                    }
                    ?> 
                    <?php
                    if($cabecera['numero_guia'] != ''){?>
                    <tr>
                        <td class="letra_mediana">Número de Guia:</td>
                        <td class="letra_mediana"><?php echo $cabecera['numero_guia']?></td>
                    </tr>
                    <?php
                    }
                    ?> 
                    <?php
                    if($cabecera['condicion_venta'] != ''){?>
                    <tr>
                        <td class="letra_mediana">Condición de Venta:</td>
                        <td class="letra_mediana"><?php echo $cabecera['condicion_venta']?></td>
                    </tr>
                    <?php
                    }
                    ?> 
                    <?php
                    if($cabecera['nota_venta'] != ''){?>
                    <tr>
                        <td class="letra_mediana">Nota de venta:</td>
                        <td class="letra_mediana"><?php echo $cabecera['nota_venta']?></td>
                    </tr>
                    <?php
                    }
                    ?> 
                    <?php
                    if($cabecera['numero_pedido'] != ''){?>
                    <tr>
                        <td class="letra_mediana">Número de pedido:</td>
                        <td class="letra_mediana"><?php echo $cabecera['numero_pedido']?></td>
                    </tr>
                    <?php
                    }
                    ?> 
                    <tr>
                        <td><?php echo $text_cambio;?></td>
                        <td><?php echo $tipo_monto;?></td>
                    </tr>
                    <?php
                    if(isset($nota_credito) && ($nota_credito['serie'] != null)){?>
                    <tr>
                        <td>Documento adjunto:</td>
                        <td><?php echo $nota_credito['serie'].'-'.$nota_credito['numero'];?></td>
                    </tr>
                    <tr>
                        <td>Motivo:</td>
                        <td><?php echo $nota_credito['tipo_ncredito'];?></td>
                    </tr>
                    <?php
                    }
                    $anticipo_total_pagar = 0;
                    $anticipo_total_igv = 0;
                    if(count($venta_anticipos) > 0){?>
                    <tr>
                        <td>Anticipos:</td>
                        <td></td>
                    </tr>
                    <?php
                        foreach ($venta_anticipos as $value_anticipos){
                    ?>
                    <tr>
                        <td><?php echo $value_anticipos['serie'].'-'.$value_anticipos['numero']?></td>
                        <td><?php echo $value_anticipos['total_a_pagar']?></td>
                    </tr>
                    <?php
                        $anticipo_total_pagar += floatval($value_anticipos['total_a_pagar']);
                        $anticipo_total_igv += floatval($value_anticipos['total_igv']);
                        }
                    }?>
                </table>
                </div>
            </div>
            <div id="cliente">
                <table>
                    <tr>
                        <td>Cliente:</td>
                        <td><?php echo $cabecera['entidad']?></td>
                    </tr>
                    <tr>
                        <td><?php echo $cabecera['tipo_entidad'].": "?></td>
                        <td><?php echo $cabecera['numero_documento']?></td>
                    </tr>
                    <tr>
                        <td>Dirección:</td>
                        <td><?php echo $cabecera['direccion_cliente_de_venta'];?></td>
                    </tr>
                </table>
            </div>
        </div> 
        <br><br><br><br><br><br><br><br><br>
        <table style="width: 100%" border="1" cellspacing="0" cellpadding="0" class="detalle">
            <thead>
                <tr class="colores_model">
                    <th>CANT.</th>
                    <th>U.M.</th>
                    <th>COD.</th>
                    <th>DESCRIPCIÓN</th>
                    <th class="derecha_text">V/U</th>
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
                    <td class="derecha_text"><?php echo $base; ?></td>
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
            if($cabecera['total_a_pagar'] != null){?>
            <tr>
                <td colspan="4"><?php echo $totalLetras?></td>
                <td class="colores_model">Total</td>
                <td class="colores_model"><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="colores_model derecha_text"><?php echo number_format($cabecera['total_a_pagar'] - $anticipo_total_pagar, 2)?></td>
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
            </table>
        </div>
        
        <?php
        if(count($cuotas) > 0){
        ?>
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
    </body>
</html>