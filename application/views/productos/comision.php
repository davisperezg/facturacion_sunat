<script src="<?PHP echo base_url(); ?>assets/js/funciones.js"></script>
<script type="text/javascript">
    $(document).on('ready',function() {
        $("#fecha_inicio").datepicker();
        $("#fecha_fin").datepicker();
    });
</script>
<?php if($this->session->flashdata('mensaje')!=''){ ?>
<p class="bg-info" style="padding:5px 10px;margin:0 35px;border-radius:5px;text-align: center;background: #1ABC9C;color:#fff;font-weight: 600;font-size: 15px;">
    <?PHP echo $this->session->flashdata('mensaje'); ?>
</p>
<?php } ?>
<div class="container-fluid" style="margin: 0 25px;">
    <form method="post" action="<?PHP echo base_url()?>index.php/productos/comision" name="form1" id="form1">    
        <h3>Comisiones: </h3>
        
        <div class="panel panel-info" >
            <div class="panel-heading" >
                <div class="panel-title">FILTRO DE BUSQUEDA</div>                        
            </div>
            <div class="panel-body">   
                <div class="row" >
                    <div class="col-md-6 col-lg-6">
                        <label>Empleado:</label><br>
                        <select required="" name="empleados" id="empleados" class="form-control">
                            <?php foreach($empleados as $value_empleado){
                                $selected = ($value_empleado['id'] == $_POST['empleados']) ? 'selected': ''; ?>
                            <option <?php echo $selected;?> value="<?php echo $value_empleado['id']?>"><?php echo $value_empleado['apellido_paterno'] . ' ' . $value_empleado['apellido_materno'] . ', ' . $value_empleado['nombre']?></option>
                            <?php
                            } ?>                            
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2" >
                    </div>

                    <div class="col-md-6 col-lg-4 form-inline"  >
                        <label>Fecha</label><br>
                        <?php 
                        $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
                        $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
                        ?>
                        <input required="" class="form-control input-sm" type="text" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio?>" placeholder="Desde">
                        <input required="" class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" value="<?php echo $fecha_fin?>" placeholder="Hasta">

                        <input type="submit" id="buscar_comprobante" class="btn btn-primary" value="Buscar">
                    </div>                                        
                </div>
                <div class="row" style="padding-top: 10px">                
                    <div class="col-xs-6 form-inline">
                        
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col"><div align="center">N</div></th>
                <th scope="col">Producto</th>
                <th scope="col"><div align="center">Cantidad</div></th>
                <th scope="col"><div align="center">Precio Venta</div></th>
                <th scope="col"><div align="center">Venta Total</div></th>
                <th scope="col"><div align="center">% Comisión</div></th>
                <th scope="col"><div align="center">Comisión</div></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $total_comision = 0;
            $total_venta = 0;
            foreach ($comisiones as $value){?>
            <tr>
                <td align="center"><?php echo $i; $i++;?></td>
                <td><?php echo $value['producto']?></td>
                <td align="center"><a title="Ver Documentos" onclick="javascript:window.open('<?php echo base_url()?>index.php/productos/comision_comprobantes/<?PHP echo $value['prod_id'].'/'.$_POST['empleados'].'/'.$fecha_inicio.'/'.$fecha_fin.'/'.$value['cantidad']?>','Documentos','width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><?php echo $value['cantidad']?></a></td>
                <td align="center"><?php echo $value['precio_venta']?></td>
                <td align="center"><?php echo $value['venta_total']; $total_venta += $value['venta_total']?></td>
                <td align="center"><?php echo $value['porcentaje_comision']?></td>
                <td align="center"><?php echo $value['venta_total']*$value['porcentaje_comision']/100; $total_comision += $value['venta_total']*$value['porcentaje_comision']/100;?></td>
            </tr>
            <?php }?>
            <tr>
                <td align="center" colspan="2"><b>Total</b></td>
                <td></td>
                <td></td>
                <td align="center"><?php echo number_format($total_venta, 2);?></td>
                <td></td>
                <td align="center"><?php echo $total_comision;?></td>
            </tr>
        </tbody>
    </table> 
</div>