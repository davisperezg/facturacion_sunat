<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tipo Cambio</title>        
        
        <script type="text/javascript">
            
            $(document).on('ready',function(){                            
                $("#fecha").datepicker();                            
            });
        
        </script>  
<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tipo_cambio').DataTable();
    } );
</script>
    </head>
    <body> 
        <p class="bg-info"><?= $this->session->flashdata('mensaje');?></p>
        <div class="container">                        
            <form method="post" action="<?= base_url()?>index.php/tipoCambio/index" role="form" class="form-inline">
            <div class="form-inline">
                <div class="col-lg-3">
                <label class="control-label">Moneda:</label>
                <select class="form-control" name="moneda_id" id="moneda_id">
                    <option>Seleccione Moneda</option>
                    <?PHP foreach($monedas as $value){                        
                        $selected = ($moneda_selec == $value['id']) ? 'SELECTED': '';?>                        
                    <option <?= $selected;?> value="<?= $value['id']?>" ><?= $value['moneda'];?></option>
                    <?PHP }?>
                </select>
                </div>                           
                <div class="col-lg-3">
                <label class="control-label">Fecha:</label>
                <input type="text" class="form-control" name="fecha" id="fecha" 
                       value="<?PHP if(isset($_POST['fecha']))
                                        echo $_POST['fecha'];                                    
                       ?>" placeholder="Seleccione Fecha">
                </div>               
                <div class="col-lg-2">
                <input type="submit" class="btn btn-primary">
                </div>
            </div>                        
            </form>                            
        </div>                
        <br>        
        <div class="container">
            <div class="text-right" style="margin-bottom:1rem;">
                <a href="<?= base_url().'index.php/tipoCambio/nuevo';?>" class="btn btn-success btn-sm">Agregar TCambio</a>
            </div>

        <table id="tipo_cambio" class="table table-striped">            
            <thead>
                <th>Id</th>
                <th>Moneda</th>
                <th>Tipo Cambio</th>
                <th>Fecha</th>
                <!--<th>Activo</th>-->
                <th>Modificar</th>
                <th>Eliminar</th>
            </thead>            
            <tbody>
                <?PHP foreach ($tipo_cambio as $value) { ?>
                <tr>
                    <td><?= $value['id']?></td>
                    <td><?= $value['moneda_id']?></td>
                    <td><?= $value['tipo_cambio']?></td>
                    <td><?= $value['fecha'] ?></td>
                    <!--<td><a href="<?= base_url()?>index.php/tipoCambio/modificar_g/<?= $value['id']?>/<?= $value['activo']?>"><?= $value['activo'] ?></a></td>-->
                    <td><a href="<?= base_url()?>index.php/tipoCambio/modificar/<?= $value['id']?>"><span class="glyphicon glyphicon-edit"></span></a></td>
                    <td><a href="<?= base_url()?>index.php/tipoCambio/eliminar/<?= $value['id']?>"><span class="glyphicon glyphicon-remove"></span></td>
                </tr>                
                <?PHP }?>
            </tbody>            
        </table>
    </div>
    



    </body>        
</html>




