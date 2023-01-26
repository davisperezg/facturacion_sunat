<html>
    <head>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }
            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }
            .datos_titulo1{
                font-size: 8px;
            }
            .tabla_cabecera{
                font-size: 10px;
            }
            .tabla_datos{
                font-size: 4px;
                text-align: right;
            }
            .datos_totales{
                font-size: 15px;
                font-weight: bold;
            }
            .derecha_text { 
                text-align: right;
            }
            .centro_text { 
                text-align: center; 
            }
            
            .div_izquierda{
                float: left;
                font-size: 10px;
                width: 110px;
            }
            
            .div_derecha{
                float: left;
                font-size: 10px;
                width: 30px;
            }
        </style>
        <title>Sistema de Ventas</title>
    </head>
    <body>
    <?php         
    switch ($cabecera['operacion']) {
        case 1:
            switch ($cabecera['tipo_documento_id']) {
                case 1:
                    $tipo_documento = "FACTURA";
                    break;
                case 3:
                    $tipo_documento = "BOLETA";
                    break;
                case 7:
                    $tipo_documento = "NOTA DE CREDITO";
                    $data['tipo_nota'] = '-';
                    $data['comp_adjunto'] = '-';
                    break;
                case 8:
                    $tipo_documento = "NOTA DE DEBITO";
                    $data['tipo_nota'] = '-';
                    $data['comp_adjunto'] = '-';
                    break;
            }                        
            break;
        case 2:
          $tipo_documento = 'NOTA DE VENTA';
          break;
        case 3:
          $tipo_documento = 'COTIZACION';
          break;           
    }
    
    $tipopago ="";
    $ruta_foto = base_url()."images/".$empresa['foto'];
    ?>        
        <img src="<?php echo "images/empresas/".$empresa['foto'];?>" height="80" width="100%" style="text-align:center;" border="0">
        
            <p align="center" class ="datos_titulo1">
                <?php echo $empresa['empresa']?><br><br>
                RUC: <?php echo $empresa['ruc']?><br><br>
                <?php echo $empresa['domicilio_fiscal']?><br>
                -------------------------------------------------------<br><br>
                <?php echo $tipo_documento." DE VENTA ELECTRÓNICA "; ?><br>&nbsp;&nbsp;<?php echo $cabecera['serie']?>-<?php echo str_pad($cabecera['numero'], 8, "0", STR_PAD_LEFT)?><br>
                Fecha/hora emision: <?php echo $cabecera['fecha_emision']; ?><br>
                Vendedor : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>                                
                <?php
                if($cabecera['numero_guia'] != ''){?>
                Guia: : <?php echo $cabecera['numero_guia']?><br>
                <?php
                }                
                if(isset($nota_credito) && ($nota_credito['serie'] != null)){
                    echo "Documento adjunto: ".$nota_credito['serie'].'-'.$nota_credito['numero']."<br>";
                    echo "Motivo: ".$nota_credito['tipo_ncredito']."<br>";
                }
                ?>
                -------------------------------------------------------<br><br>
                Cliente: <?php echo $cabecera['entidad']?><br>
                <?php echo $cabecera['tipo_entidad'] . ": ". $cabecera['numero_documento']?><br>
                DIRECCION:<?php echo "  ". $cabecera['direccion_entidad']?><br>
                -------------------------------------------------------<br>
            </p>            
            <table width="100%">
                <tr>                        
                    <td style="font-size: 10px">Producto</td>
                    <th width="13" align="center" class="tabla_cabecera">Total</th>
                </tr>    
                <tr>    
                    <td width="13" style="font-size: 10px">Importe x Cant.</td>
                </tr>
            </table>
            <?php foreach($detalle as $value){
                $impuesto_bolsa_item = ($cabecera['total_bolsa'] != null) ? number_format($value['impuesto_bolsa']*$value['cantidad'],2) : 0;
                $impuesto = ($value['tipo_igv_id'] == 1) ? (1+$cabecera['porcentaje_igv']) : 1;?>

                <div class="div_izquierda" ><b><?php echo $value['producto']?></b></div>
                <div class="div_derecha" style="text-align: right"><?php echo number_format(($value['cantidad']*($value['precio_base']*$impuesto) + $impuesto_bolsa_item), 2); ?></div>
                <div style="clear: left;"></div>                
                <div class="div_izquierda" style="padding-bottom: 6px"><?php echo number_format($value['precio_base']*$impuesto,2)."&nbsp;&nbsp;x&nbsp;&nbsp;".$value['cantidad']?></div>
                <div style="clear: left"></div>                
            <?php                     
            }
            ?>            
            
            <p align="center" class ="datos_titulo1">
            -------------------------------------------------------<br>
            </p>
            <table class="totales" style="width: 100%">
            <?php
            if($cabecera['total_gravada'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">Gravada</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_gravada']?></td>
            </tr>
            <tr>                
                <td style="width:20%" class="datos_totales">IGV <?php echo $cabecera['porcentaje_igv']*100?>%</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_igv']?></td>
            </tr>
            <?php }
            if($cabecera['total_gratuita'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">Gratuita</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_gratuita']?></td>
            </tr>
            <?php }
            if($cabecera['total_exportacion'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">Exportación</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_exportacion']?></td>
            </tr>
            <?php }
            if($cabecera['total_exonerada'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">Exonerado</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_exonerada']?></td>
            </tr>
            <?php }
            if($cabecera['total_inafecta'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">Inafecta</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_inafecta']?></td>
            </tr>
            <?php }
            if($cabecera['total_bolsa'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">ICBPER</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_bolsa']?></td>
            </tr>
            <?php }
            if($cabecera['PrepaidAmount'] != null){?>
            <tr>
                <td style="width:20%" class="datos_totales">Anticipo</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['PrepaidAmount']?></td>
            </tr>
            <?php }
            if($cabecera['total_a_pagar'] != null){?>
            <tr>                
                <td style="width:20%" class="datos_totales">Total</td>
                <td style="width:10%" class="datos_totales"><?php echo $cabecera['simbolo_moneda']?></td>
                <td style="width:70%" class="datos_totales derecha_text"><?php echo $cabecera['total_a_pagar']?></td>
            </tr>
            <?php }?>
        </table>
            <span class="datos_titulo1"><?php echo $totalLetras?></span>
            <p align="center"><img width="40" height="40" src="<?PHP echo $rutaqr?>"></p>
            <p align="center" class ="datos_titulo1"><?php echo $cabecera['firma_sunat']?></p>

        <?php
        if($cabecera['notas'] != null){?>
            <div align="center" style="font-size: 4px">
            <?php
            echo $cabecera['notas'];
            ?>
            </div>
        <?php
        }else{?>
        <div  align="center" style="font-size: 6px">
                    EMITIDO MEDIANTE PROVEEDOR
                    AUTORIZADO POR LA SUNAT
                    RESOLUCION N. 097- 2012/SUNAT
        </div>
        <?php
        }
        ?>                
    </body>
</html>