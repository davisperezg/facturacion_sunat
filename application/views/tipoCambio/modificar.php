<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div class="container">
            <div class="text-center"><h1>Modificar Tipo Cambio</h1></div>
        <form method="post" action="<?= base_url()?>index.php/tipoCambio/modificar_g/<?= $tCambioSelect['id']?>">
            <div class="row" style="padding-top: 20px;">
                <div class="col-lg-4 text-right">
                    <label class="control-label">Fecha:</label>
                </div>
                <div class="col-lg-4">
                    <input class="form-control" name="fecha" id="fecha" value="<?= $tCambioSelect['fecha']?>"/>
                </div>
            </div>            
            <div class="row" style="padding-top: 20px;">
                <div class="col-lg-4 text-right">
                    <label class="control-label">Moneda:</label>
                </div>
                <div class="col-lg-4">
                    <select class="form-control" name="moneda_id" id="moneda_id">
                    <?PHP                     
                        foreach ($monedas as $value) {                                                   
                        $selected = ($value['id'] == $tCambioSelect['moneda_id'])? 'SELECTED' : ''?>                
                    <option <?= $selected;?> value="<?= $value['id']?>"><?= $value['moneda']?></option>                                
                    <?PHP }?>
                    </select>
                </div>
            </div>            
            <div class="row" style="padding-top: 20px;">
                <div class="col-lg-4 text-right">
                    <label class="control-label">Tipo Cambio:</label>
                </div>
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="tipo_cambio" id="tipo_cambio" value="<?= $tCambioSelect['tipo_cambio']?>">
                </div>
            </div>                        
            <div class="row" style="padding-top: 20px;">
                <input type="submit" class="btn btn-primary" value="Modificar">
            </div>                   
        </form>                
        </div>
    </body>        
</html>