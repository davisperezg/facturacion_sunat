<!DOCTYPE html>
<html>    
    <head>
        <title>Comprobante PDF-A4</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">    
        <style>
            #datos_empresa{
                font-family: Arial, Helvetica, sans-serif;                
                width: 100%;
            }
            #logo{
                width: 100%;
            }
            table{
                width:100%;        
             }            
        </style>
    </head>
    
    <body>        
        <div id="div_header" align="center">
            <div id="logo" align="center">
                <img align="center" src="<?PHP FCPATH;?>images/empresas/<?php echo $empresa['foto'];?>" height="120" style="text-align:center;" ><br>
            </div>    
            <br>
            <div id="datos_empresa" align="center">                                
                <div class="tamanio_mediano" style="padding-top: 10px">
                    <b><?php echo $empresa['empresa'];?></b>
                </div>                
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <table>
                <tr>
                    <td><div align="center">Pedido Virtual</div></td>
                </tr>
                <tr>
                    <td><img align="center" src="<?php echo $rutaqr?>" style="padding-left: 200px; width:8cm;height: 8cm;"></td>
                </tr>
                <tr>
                    <td><div align="center">FacturacionIntegral.com</div></td>
                </tr>
            </table>
        </div>
    </body>   
</html>