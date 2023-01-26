<style>    
    /* Agregando Inputs */
    .input-group {width: 100%;}
    .input-group-addon { min-width: 180px;text-align: right;}    
    
    .panel-title{
        font-size: 13px;
        font-weight: bold;
    }
    
    .arranca_oculto{
        display: none;
    }
</style>
<div class="modal-dialog bd-example-modal-lg" role="document">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Detalle Cobro.</h4>
        </div>
        <div class="modal-body">
            <div class="row justify-content-md-center">
                <div aling="center">
                    <img height="220px" id="img_cobro">
                </div>                
            </div><br>
            <hr>
            <div class="row">
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td style="width: 100px"><b>N. Pago:</b></td>
                            <td><span id="numero_pago"></span></td>
                        </tr>
                        <tr>
                            <td><b>Fecha pago:</b></td>
                            <td><span id="fecha_pago"></span></td>
                        </tr>
                        <tr>
                            <td><b>Monto</b></td>
                            <td><span id="monto"></span></td>
                        </tr>
                        <tr>
                            <td><b>Modo pago</b></td>
                            <td><span id="detalle_modo_pago"></span></td>
                        </tr>
                        <tr>
                            <td><b>Nota</b></td>
                            <td><span id="nota"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td style="width: 100px"><b>Cliente</b></td>
                            <td><span id="detalle_entidad"></span></td>
                        </tr>
                        <tr>
                            <td><b>Documento</b></td>
                            <td><span id="detalle_documento"></span></td>
                        </tr>                        
                    </table>
                </div>                
            </div>
            <br>
            <div>                
                <br>
                <div class="col-xs-6">
                    <label>Cuotas</label>
                    <div class="row-fluid">
                        <table id="tabla_cuota" class="table table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>N.</th>                                    
                                    <th>Fecha</th>
                                    <th class="derecha_text">Monto</th>
                                    <th class="centro_text"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
                                </tr>
                            </thead>
                        </table>    
                    </div>
                </div>
                <div class="col-xs-6">
                    <label>Pagos</label>
                    <div class="row-fluid">
                        <table id="tabla_pago" class="table table-bordered table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>N.</th>                                    
                                    <th>Fecha</th>
                                    <th class="derecha_text">Monto</th>
                                    <th class="centro_text">Modo</th>
                                </tr>
                            </thead>
                        </table>    
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>        
    </div>
</div>
<script src="<?PHP echo base_url(); ?>assets/js/monstruo/cuotas_and_cobros.js"></script>
<script type="text/javascript">
    carga_inicial_cuotas();
    carga_inicial_cobros();
</script>  