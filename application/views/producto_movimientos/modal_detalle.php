<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title">Detalle Movimiento Producto</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-8">
                    <label>Fecha:</label><br>
                    <span id="fecha_insert"></span>
                </div>                
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8" >
                    <label>Producto:</label><br>
                    <span id="modal_producto"></span>
                </div>                
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8">
                    <label>Movimiento:</label><br>
                    <span id="modal_movimiento"></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8">
                    <label>Cantidad:</label><br>
                    <span id="cantidad"></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-8">
                    <label>Motivo:</label><br>
                    <textarea readonly="" class="form-control" placeholder="Motivo" id="motivo" rows="3"></textarea>
                </div>                
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->