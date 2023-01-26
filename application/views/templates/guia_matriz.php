<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>


<style>

body{
    font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
    font-weight: bold;
}

</style>


<body class="white-bg">

<table width="100%">

            <br><br><br><br><br><br><br><br>

            <div>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 16px;font-weight: 600;"> 
                    <tbody>
                    <tr>
                        <td width="20px" align="left" style="padding-top: 20px;font-size: 16px;"><?php echo $guia->fecha_inicio_traslado?></td>
                        <td width="20px" align="left" style="padding-top: 18px;font-size: 16px;"><?php echo $guia->fecha_inicio_traslado?></td>
                        <td ></td>
                    </tr>
                   
                    <tr>
                        <td width="360px" align="left" colspan="2" style="padding-top: 13px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $guia->destinatario_razon_social?></td>
                        <td width="100px" align="left" style="padding-top: 8px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $guia->partida_direccion?></td>
                    </tr>

                    <tr>
                        <td width="20px" align="left" style="padding-top: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $guia->destinatario_ruc?></td>
                        
                        <td ></td>
                        <td width="20px" align="left" style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $guia->llegada_direccion?></td>
                    </tr>

                    </tbody>
                </table>


            </div>

            <div>
               <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 15px;font-weight: 600;">
                <tr>
                    <td align="right" width="220px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==1)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==1)?"X":"X";?></td>
                    <td align="right" width="300px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==2)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==2)?"X":"X";?></td>
                    <td align="right" width="130px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==8)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==8)?"X":"X";?></td>
                    <td align="right" style="height: 1px;padding:0;<?php echo ($guia->motivo_traslado==10)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==10)?"X":"x";?></td>
                </tr>
                <tr>
                    <td align="right" width="220px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==3)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==3)?"X":"X";?></td>
                    <td align="right" width="300px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==6)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==6)?"X":"X";?></td>
                    <td align="right" width="130px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==11)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==11)?"X":"X";?></td>
                    <td align="right" style="height: 1px;padding:0;<?php echo ($guia->motivo_traslado==9)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==9)?"X":"X";?></td>
                </tr>
                <tr>
                    <td align="right" width="220px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==4)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==4)?"X":"X";?></td>
                    <td align="right" width="300px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==5)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==5)?"X":"X";?></td>
                    <td align="right" width="130px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==12)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==12)?"X":"X";?></td>
                    <td align="right" style="height: 1px;padding:0;<?php echo ($guia->motivo_traslado==7)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==7)?"X":"X";?></td>
                </tr>
                <tr>
                    <td align="right" width="220px" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==14)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==14)?"X":"X";?></td>
                    <td colspan="3" align="right" style="height: 1px;padding:2px 0;<?php echo ($guia->motivo_traslado==13)?"":"color:#fff;";?>"><?php echo ($guia->motivo_traslado==13)?"X":"X";?></td>
                    
                </tr>
                    
               </table> 
            </div>    

             <br><br><br>

            <div>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 16px;font-weight: 600;">
                    <tbody>
                   
                        <?php foreach($guia->detalles as $index => $item):?>
                        <tr class="border_top">
                            <td align="center" width="70px"><?php echo $item->cantidad?></td>
                            <td align="center" width="90px"><?php echo $item->codigo?></td>
                            <td align="left" ><?php echo $item->descripcion?></td>
                            
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>

            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>



            <!--<div>
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td colspan="2"><strong>ENVIO</strong></td>
                    </tr>
                    <tr class="border_top">
                        <td width="60%" align="left">
                            <strong>Fecha Emisión:</strong>  <?php echo $guia->fecha_inicio_traslado?>
                        </td>
                        <td width="40%" align="left"><strong>Fecha Inicio de Traslado:</strong>  <?php echo $guia->fecha_inicio_traslado?> </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Motivo Traslado:</strong>  <?php echo $guia->descripcion?> </td>
                        <td width="40%" align="left"><strong>Modalidad de Transporte:</strong>   </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>Peso Bruto Total (KG):</strong> <?php echo $guia->peso_total?> </td>
                        <td width="40%"><strong>Número de Bultos:</strong> <?php echo $guia->numero_bultos?> </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left"><strong>P. Partida:</strong>   <?php echo $guia->partida_direccion?></td>
                        <td width="40%" align="left"><strong>P. Llegada: </strong>  <?php echo $guia->llegada_direccion?></td>
                    </tr>
                    </tbody>
                </table>
            </div>-->

     

           <div >
                <table width="100%" border="0" cellpadding="5" cellspacing="0" style="font-size: 16px;font-weight: 600;">
                    <tbody>
                    
                    <tr class="border_top">
                        <td width="110px" align="left"><?php echo $guia->transporte_razon_social?></td>
                        <td width="25px" align="left"><?php echo $guia->transporte_ruc?></td>
                        <td width="25px" align="left"><?php echo $guia->vehiculo_marca?></td>
                        <td width="25px" align="left"><?php echo $guia->vehiculo_placa?></td>
                        <td width="25px" align="left"><?php echo $guia->vehiculo_licencia?></td>
                    </tr>
                  
                   
                    </tbody>
                </table>
            </div>

            <br>

            

            <div>
            </div>
        </td>
    </tr>
    </tbody></table>
</body></html>