<!DOCTYPE html>
<html>
    <head>
        <title>Comprobante PDF-A5</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <style>    
            /* Agregando Inputs */
            body { margin: 0px; }
            
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
                padding: 2px;
            }
            .tabla_borde_sin_espacio{
                border:1.5px solid #aaa;border-radius:8px;
            }
            
            html, body{
                margin-top: 5px;
                margin-bottom: 5px;                 
                margin-left: 7px !important;
                margin-right: 7px !important;
                
                padding-top: 5px;
                padding-bottom: 5px;                 
                padding-left: 5px !important;
                padding-right: 5px !important;
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
        $text_cambio = ($cabecera['moneda_id'] != 1) ? 'Tipo de cambio:' : '';
        $tipo_monto = ($cabecera['moneda_id'] != 1) ? $cabecera['tipo_de_cambio'] : '';        
        ?>
        <table style="width: 100%">
            <tr>
                <td style="width: 15%">
                    <table>
                        <tr>
                            <td>        
                                <img src="<?PHP FCPATH;?>images/empresas/<?php echo $empresa['foto'];?>" width="200px" style="text-align:center;" ><br>
                            </td>
                        </tr>                        
                    </table>
                </td>
                <td style="width: 45%">
                    <table>                        
                        <tr>
                            <td class="letra_mediana"><b><?php echo $empresa['empresa']?></b></td>
                        </tr>
                        <tr>
                            <td class="letra_mediana"><?php echo $empresa['domicilio_fiscal']?></td>
                        </tr>
                        <tr>
                            <td class="letra_mediana"><?php echo $empresa['telefono_fijo']." // ".$empresa['telefono_movil']?></td>
                        </tr>
                        <tr>
                            <?php                            
                            $forma_pago = ($cabecera['forma_pago_id'] == 1) ? 'Contado' : 'Crédito';
                            ?>
                            <td>Forma de pago: <?php echo strtoupper($forma_pago);?></td>
                        </tr>
                        <tr>
                            <td class="letra_mediana">Fecha de Emisión: <?php echo $cabecera['fecha_emision']?></td>
                        </tr>
                        <?php
                        if($cabecera['fecha_vencimiento'] != null){?>
                        <tr>
                            <td class="letra_mediana">Fecha de Vencimiento: <?php echo $cabecera['fecha_vencimiento']?></td>
                        </tr>
                        <?php
                        }
                        ?>                            
                        <tr>
                            <td class="letra_mediana">Moneda: <?php echo ucfirst($cabecera['moneda'])?></td>
                        </tr>
                        <?php
                        if(isset($venta_guia['serie']) && ($venta_guia['serie'] != '')){?>
                        <tr>
                            <td class="letra_mediana">Guia: <?php echo $venta_guia['serie'].'-'.$venta_guia['numero']?></td>
                        </tr>
                        <?php
                        }
                        ?>                            
                        <?php
                        if($cabecera['orden_compra'] != ''){?>
                        <tr>
                            <td class="letra_mediana">Orden compra: <?php echo $cabecera['orden_compra']?></td>
                        </tr>
                        <?php
                        }
                        ?> 
                        <?php
                        if($cabecera['numero_guia'] != ''){?>
                        <tr>
                            <td class="letra_mediana">Número de Guia: <?php echo $cabecera['numero_guia']?></td>
                        </tr>
                        <?php
                        }
                        ?> 
                        <?php
                        if($cabecera['condicion_venta'] != ''){?>
                        <tr>
                            <td class="letra_mediana">Condición de Venta: <?php echo $cabecera['condicion_venta']?></td>
                        </tr>
                        <?php
                        }
                        ?> 
                        <?php
                        if($cabecera['nota_venta'] != ''){?>
                        <tr>
                            <td class="letra_mediana">Nota de venta: <?php echo $cabecera['nota_venta']?></td>
                        </tr>
                        <?php
                        }
                        ?> 
                        <?php
                        if($cabecera['numero_pedido'] != ''){?>
                        <tr>
                            <td class="letra_mediana">Número de pedido: <?php echo $cabecera['numero_pedido']?></td>
                        </tr>
                        <?php
                        }
                        ?> 
                        <tr>
                            <td><?php echo $text_cambio." ".$tipo_monto;?></td>
                        </tr>
                        <?php
                        if(isset($nota_credito) && ($nota_credito['serie'] != null)){?>
                        <tr>
                            <td>Documento adjunto: <?php echo $nota_credito['serie'].'-'.$nota_credito['numero'];?></td>
                        </tr>
                        <tr>
                            <td>Motivo: <?php echo $nota_credito['tipo_ncredito'];?></td>
                        </tr>
                        <?php
                        }
                        if(count($venta_anticipos) > 0){?>
                        <tr>
                            <td>Anticipos:
                        </tr>
                        <?php
                            foreach ($venta_anticipos as $value_anticipos){
                        ?>
                        <tr>
                            <td><?php echo $value_anticipos['serie'].'-'.$value_anticipos['numero']." ".$value_anticipos['total_a_pagar']?></td>
                        </tr>
                        <?php
                            }
                        }?>
                        <tr>
                            <td class="letra_mediana"><b>Cliente:</b><?php echo $cabecera['entidad']." ".$cabecera['tipo_entidad']." ".$cabecera['numero_documento']?>
                                Dirección:<?php echo $cabecera['direccion_entidad']?></td>
                        </tr>
                        <?php                            
                        if($cotizacion != array()){?>
                        <tr>
                            <td class="letra_mediana">Cotización: <?php echo $cotizacion['numero']?></td>
                        </tr>
                        <?php
                        }                          
                        if($nota_venta != array()){?>
                        <tr>
                            <td class="letra_mediana">Nota de venta: <?php echo $nota_venta['numero']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </td>
                <td style="width: 30%">
                    <div class="tabla_borde">
                        <table align="center">
                            <tr>
                                <td class="letra_grande centro_text">RUC: <?php echo $empresa['ruc']?></td>
                            </tr>
                            <tr>
                                <td class="letra_grande centro_text"><?php echo strtoupper($tipo_documento)?></td>
                            </tr>
                            <tr>
                                <td class="letra_grande centro_text"><?php echo $cabecera['serie']."-".$cabecera['numero']?></td>
                            </tr>
                        </table>
                    </div>
                </td>
                
            </tr>
        </table>        
        <br>
        <table style="width: 100%" border="1" cellspacing="0" cellpadding="0" class="detalle">
            <thead>
                <tr>
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
            if($cabecera['PrepaidAmount'] != null){?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>Anticipo</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo $cabecera['PrepaidAmount']?></td>
            </tr>            
            <?php }
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
                <td class="derecha_text"><?php echo $cabecera['total_gravada']?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50%"></td>
                <td>IGV <?php echo $cabecera['porcentaje_igv']*100?>%</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo $cabecera['total_igv']?></td>
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
                <td class="derecha_text"><?php echo $cabecera['total_gratuita']?></td>
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
                <td class="derecha_text"><?php echo $cabecera['total_exportacion']?></td>
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
                <td class="derecha_text"><?php echo $cabecera['total_exonerada']?></td>
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
                <td class="derecha_text"><?php echo $cabecera['total_inafecta']?></td>
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
                <td class="derecha_text"><?php echo $cabecera['total_bolsa']?></td>
            </tr>
            <?php }            
            if($cabecera['total_a_pagar'] != null){?>
            <tr>
                <td colspan="4"><?php echo $totalLetras?></td>
                <td>Total</td>
                <td><?php echo $cabecera['simbolo_moneda']?></td>
                <td class="derecha_text"><?php echo $cabecera['total_a_pagar']?></td>
            </tr>
            <?php }?>
        </table>
        <table style="width: 100%">
            <tr>
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
            </tr>
        </table>
        
    </body>
</html>