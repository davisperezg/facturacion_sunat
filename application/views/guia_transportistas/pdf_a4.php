<html>
    <head>
        <title>Comprobante PDF-A4</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
            body {
                font-family: Arial, Helvetica, Verdana;
            }
            
            #contenedor{
                width: 100%;
                height: 250px;
            }
            
            #uno{
                width: 48%;
                height: 15%;
                float: left;
                padding-top: 25px;                
            }
            
            #dos{
                font-size: 20px;
                margin-top: 20px;
                padding-top: 25px;
                padding-left: 25px;
                width: 46%;
                height: 15%;
                float: left;
                
                border: 1px solid gray;
                border-radius: 25px;
            }

            .fecha{
                float: left;
                width: 23%;
                border: 1px solid gray;
                border-radius: 10px;
                margin-right: 15px;
                padding-left: 7px;
            }
            
            #documentos{
                float: left;
                width: 46%;
                height: 39px;
                border: 1px solid gray;
                border-radius: 10px;
                padding-left: 7px;
            }
            
            .left_izquierda{
                float: left;
                border: 1px solid gray;
                border-radius: 10px;
                width: 48%;
                margin-right: 15px;
                padding-left: 7px;
            }
            
            .left_izquierda_tabla{
                float: left;
                padding-left: 7px;
            }
            
            .limpiando{
                clear: both;
                height: 1px;
            }
            
            .limpiando_fila{
                clear: both;
                height: 2px;
            }
            
            .border_div{
                border: 1px solid gray;
                border-radius: 10px;
                padding-left: 7px;
            }
            
            .responsive {
                width: 100%;
                max-width: 400px;
                max-height: 200px;
            }
        </style>
    </head>   
                
    <body>
        <div id="contenedor">
            <div id="uno">                
                <img src="<?PHP FCPATH;?>images/empresas/<?php echo $empresa['foto'];?>" height="160" style="text-align:center;" class="responsive" ><br>
                <span><strong>Dirección: </strong><?php echo $empresa['domicilio_fiscal']?></span><br>
                <span><strong>Telf: </strong><?php echo $empresa['telefono_fijo']." // ".$empresa['telefono_movil']?></span>
            </div>
            <div align="center" id="dos">
                <div><b>RUC: <?php echo $empresa['ruc'];?></b></div>
                <br>
                GUIA DE REMISIÓN REMITENTE ELECTRÓNICA
                <br>
                <br>
                <div id="numero_guia">Nº: <?php echo $cabecera['serie']."-".$cabecera['numero']?></div>
            </div>
        </div>
        <div class="limpiando"></div>
        <?php
        $ventas = '';
        foreach ($venta_guias as $value_ventas){
            $ventas .= $value_ventas['serie']."-".$value_ventas['numero']."//";
        }
        $ventas = ($ventas != '') ? substr($ventas, 0, -2) : '';
        ?>
        <div>
            <div class="fecha">Fecha de <br>Emisión: <?php echo $cabecera['fecha_emision'];?></div>
            <div class="fecha">Fecha de <br>Traslado: <?php echo $cabecera['fecha_traslado'];?></div>
            <div id="documentos">Doc.: <?php echo $ventas;?></div>
        </div>
        <div class="limpiando"></div>
        <div>
            <div class="left_izquierda">Punto de partida:<br><?php  echo $cabecera['partida_direccion'].'-'. $cabecera['distrito'].'-'.$cabecera['provincia'].'-'.$cabecera['departamento'];?></div>
            <div class="left_izquierda">Punto de llegada<br><?php  echo $cabecera['llegada_direccion'].'-'. $cabecera['distrito_llegada'].'-'.$cabecera['provincia_llegada'].'-'.$cabecera['departamento_llegada'];?></div>
        </div>
        <br>
        <div class="limpiando"></div>
        <div>
            <div class="left_izquierda">
                Destinatario:<br><?php  echo $cabecera['entidad'];?><br>
                Número Documento:<br><?php  echo " ".$cabecera['numero_documento'];?>
            </div>
            <?php
            $label_3 = '';
            $texto_3 = '';
            if($cabecera['guia_modalidad_traslado_id'] == 1){
                $label_1 = 'Ruc';
                $texto_1 = $cabecera['entidad_transporte_numero_documento'];
                $label_2 = 'Razón Social';
                $texto_2 = $cabecera['entidad_transporte'];
                $label_3 = 'N. MTC';
                $texto_3 = $cabecera['numero_mtc_transporte'];
            }elseif($cabecera['guia_modalidad_traslado_id'] == 2){
                $label_1 = 'Nombres';
                $texto_1 = $cabecera['conductor_nombres'].' '.$cabecera['conductor_apellidos'].'-'.$cabecera['conductor_dni'];
                $label_2 = 'Placa';
                $texto_2 = $cabecera['vehiculo_placa'];
                $label_3 = 'Licencia';
                $texto_3 = $cabecera['conductor_licencia'];
            }
            ?>
            <div class="left_izquierda">
                Motivo: <?php  echo $cabecera['guia_motivo_traslado'];?><br>
                Modalidad: <?php  echo $cabecera['guia_modalidad_traslado'];?><br>
                <?php echo $label_1.": ".$texto_1?><br>
                <?php echo $label_2.": ".$texto_2?><br>
                <?php echo $label_3.": ".$texto_3?><br>
            </div>
        </div>
        <div class="limpiando"></div>
        <div class="border_div">
            <div class="left_izquierda_tabla" style="width: 10%">Cantidad</div>
            <div class="left_izquierda_tabla" style="width: 10%">Medida</div>
            <div class="left_izquierda_tabla" style="width: 80%">Descripción</div>
            <div class="limpiando"></div>
            <hr>
            <?php
            $i = 1;
            foreach ($detalle as $value_detalle){
                $pintado = ($i % 2) == 0 ? "background-color: #E0E0E0" : ""; 
                $i++;
            ?>
            <div class="left_izquierda_tabla" style="width: 10%; <?php echo $pintado?>"><?php echo $value_detalle['cantidad']?></div>
            <div class="left_izquierda_tabla" style="width: 10%; <?php echo $pintado?>"><?php echo $value_detalle['codigo_unidad']?></div>
            <div class="left_izquierda_tabla" style="width: 77%; <?php echo $pintado?>"><?php echo $value_detalle['producto']?></div>
            <div class="limpiando_fila"></div>
            <?php
            }?>            
        </div>
        <?php
        $numero_bultos = ($cabecera['guia_motivo_traslado_id'] == 7) ? "Número de bultos: ".$cabecera['numero_bultos'] : '';
        ?>
        <br>

        <br>
        <div class="border_div">        
        <table style="width: 100%">
            <tr>
                <td style="width: 90%">
                    <div class="tabla_borde_sin_espacio">
                        <table>
                            <tr>
                                <td style="height: 2cm">
                                    <div><?php echo $numero_bultos;?></div>
                                    <div>Peso Bruto: <?php  echo $cabecera['peso_total']?></div>            
                                    <div>Notas: <?php  echo $cabecera['notas'];?></div>   
                                    <br>
                                    <?php
//                                    if($cabecera['firma_sunat'] != null){                                    
//                                        echo "Firma Electrónica: ".$cabecera['firma_sunat'];
//                                    }
                                    ?>
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
        </div>
    </body>
</html>