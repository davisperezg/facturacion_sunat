<div class="container-fluid" style="margin: 0 25px;">   
       
    <div class="panel panel-info" >
        <div class="panel-heading" >
            <div class="panel-title">Documentos</div>                        
        </div>
        <div class="panel-body">   
            <div class="row" >
                <div class="col-xs-3">
                    <label>Empleado:</label><br>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <?php echo $empleados['apellido_paterno'] . ' ' . $empleados['apellido_materno'] . ', ' . $empleados['nombre']?><br>
                </div>
            </div>                                        
            <div class="row" >
                <div class="col-xs-3">
                    <label>Producto:</label><br>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <?php echo $producto;?><br>
                </div>
            </div>                                        
            <div class="row" >
                <div class="col-xs-3">
                    <label>Fecha:</label><br>
                </div>
                <div class="col-xs-3">
                    <?php echo $datos['fecha_inicio'];?><br>
                </div>
                <div class="col-xs-3">
                    <?php echo $datos['fecha_fin'];?><br>
                </div>
            </div>
            <div class="row" >
                <div class="col-xs-3">
                    <label>Cantidad total:</label><br>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <?php echo $datos['cantidad'];?><br>
                </div>
            </div> 
        </div>
        <div class="row" style="padding-top: 10px">                
            <div class="col-xs-6 form-inline">

            </div>
        </div>
    </div>
</div>

<div class="container">   
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">N</th>
                <th scope="col">Documento</th>
                <th scope="col">Fecha</th>
                <th scope="col">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($documentos as $value){?>
            <tr>
                <td><?php echo $i; $i++;?></td>
                <td><a href="<?php echo base_url()?>index.php/comprobantes/pdfGeneraComprobanteOffLine/<?PHP echo $value['comprobante_id']?>" target="_blank"><?php echo $value['serie'].'-'.$value['numero']?></a></td>
                <td><?php echo $value['fecha_de_emision']?></td>
                <td><?php echo $value['cantidad']?></td>
            </tr>
            <?php }?>
        </tbody>
    </table> 
</div>