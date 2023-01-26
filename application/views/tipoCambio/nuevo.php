<!DOCTYPE html>
<html>    
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript">            
            $(document).on('ready',function(){                
                $("#fecha").datepicker();            
            });
        </script>        
    </head>    
    <body>
        <div class="text-center"><h1>Agregar Tipo Cambio</h1></div>
        <div class="container">            
            <form method="post" action="<?= base_url()?>index.php/tipoCambio/guardar" role="form" class="form-signin">
                
                <div class="row" style="padding-top: 20px">
                <div class="col-lg-4 text-right">
                    <label class="control-label ">Fecha</label>
                </div>                                        
                <div class="col-lg-4">                    
                    <input type="text" class="form-control" name="fecha" id="fecha">
                </div>
                </div>                        
                    
                <div class="row" style="padding-top: 20px">
                <div class="col-lg-4 text-right">
                    <label class="control-label ">Moneda</label>
                </div>
                <div class="col-lg-4">
                    <select class="form-control" name="moneda_id" id="moneda_id">
                        <option>Seleccione Moneda</option>
                        <?PHP foreach($monedas as $value){?>
                        <option value="<?= $value['id']?>" ><?= $value['moneda'];?></option>
                        <?PHP }?>
                </select>                        
                </div>
                </div>
                
                <div class="row" style="padding-top: 20px">
                <div class="col-lg-4 text-right">
                    <label class="control-label">T.Cambio</label>                        
                </div>
                    <div class="col-lg-4">                        
                    <input type="text" class="form-control" name="tipo_cambio" id="tipo_cambio">
                </div>
                </div>
                    <div class="row" style="padding-top: 20px">                        
                        <input type="submit" class="btn btn-primary"/>
                    </div>                    
                </form>
            </div>            
        </div>                        
    </body>    
</html>




