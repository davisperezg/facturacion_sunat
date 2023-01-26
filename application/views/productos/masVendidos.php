<div class="container">   
    <h2>Productos Mas Vendidos</h2>
    <table class="table table-condensed table-bordered table-striped">
        <thead>
            <tr>
                <th>N.</th>
                <th>Producto</th>
                <th>Código Sunat</th>
                <th>Cantidad</th>
            </tr>
        </thead>    
        <tbody>
            <?php
            $i = 1;
            foreach ($masVendidos as $value){
            ?>
            <tr>
                <td><?php echo $i; $i++?></td>
                <td><?php echo $value['prod_codigo_sunat'];?></td>
                <td><?php echo $value['prod_nombre'];?></td>
                <td><?php echo $value['cantidades'];?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>