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
        <?php
        $tipo_documento = '';
        switch ($cabecera['operacion']) {
            case 1:
                $tipo_documento = $cabecera['tipo_documento']." DE COMPRA";  
                break;
            case 2:
                $tipo_documento = 'ORDEN DE COMPRA';
                break;         
        }
        ?>
        <table style="width: 100%">
            <tr>
                <td style="width: 70%">
                    <table>
                        <tr>
                            <td>        
                                <img src="<?PHP FCPATH;?>images/empresas/<?php echo $empresa['foto'];?>" height="160" style="text-align:center;" ><br>
                            </td>
                        </tr>
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
                            <td>Forma de pago: Contado</td>
                        </tr>
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
                                <td class="letra_grande centro_text">N.:<?php echo $cabecera['serie']."-".$cabecera['numero']?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <?php
        $text_cambio = ($cabecera['moneda_id'] != 1) ? 'Tipo de cambio:' : '';
        $tipo_monto = ($cabecera['moneda_id'] != 1) ? $cabecera['tipo_de_cambio'] : '';        
        ?>
        <table>
            <tr>
                <td>
                    <div class="tabla_borde">
                        <table>
                            <tr>
                                <td class="letra_mediana"><b>Cliente:</b></td>
                                <td class="letra_mediana"><?php echo $cabecera['entidad']?></td>
                            </tr>
                            <tr>
                                <td class="letra_mediana"><?php echo $cabecera['tipo_entidad']?>:</td>
                                <td class="letra_mediana"><?php echo $cabecera['numero_documento']?></td>
                            </tr>
                            <tr>
                                <td class="letra_mediana">Dirección:</td>
                                <td class="letra_mediana"><?php echo $cabecera['direccion_entidad']?></td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td>
                    <div class="tabla_borde">
                        <table>
                            <tr>
                                <td class="letra_mediana">Fecha de Emisión:</td>
                                <td class="letra_mediana"><?php echo $cabecera['fecha_emision']?></td>
                            </tr>
                            <tr>
                                <td class="letra_mediana">Fecha de Vencimiento:</td>
                                <td class="letra_mediana"><?php echo $cabecera['fecha_vencimiento']?></td>
                            </tr>
                            <tr>
                                <td class="letra_mediana">Moneda:</td>
                                <td class="letra_mediana"><?php echo $cabecera['moneda']?></td>
                            </tr>
                            <tr>
                                <td><?php echo $text_cambio;?></td>
                                <td><?php echo $tipo_monto;?></td>
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
                <?php foreach ($detalle as $value) {
                    $impuesto_bolsa_item = ($cabecera['total_bolsa'] != null) ? number_format($value['impuesto_bolsa']*$value['cantidad'],2) : 0;?>
                <tr>
                    <td><?php echo $value['cantidad']?></td>
                    <td><?php echo $value['codigo_unidad']?></td>
                    <td><?php echo $value['codigo_producto']?></td>
                    <td><?php echo $value['producto']?></td>
                    <td class="derecha_text"><?php echo number_format($value['precio_base'],2);?></td>
                    <td class="derecha_text"><?php echo number_format($value['precio_base']*(1+$cabecera['porcentaje_igv']),2); ?></td>
                    <?php
                    if($cabecera['total_bolsa'] != null){ ?>
                    <td class="derecha_text"><?php echo $impuesto_bolsa_item; ?></td>
                    <?php                        
                    }?>                       
                    <td class="derecha_text"><?php echo number_format($value['cantidad']*$value['precio_base']*(1+$cabecera['porcentaje_igv']) + $impuesto_bolsa_item,2)?></td>
                </tr>
                <?php }?>
            </tbody>            
        </table>
        <table class="totales" style="width: 100%">
            <?php
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
                                <td style="height: 2cm">
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