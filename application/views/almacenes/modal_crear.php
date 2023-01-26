<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registro Almacén</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="alm_id" value="<?php echo $almacen->alm_id?>">
       	<div class="row">
       		<div class="col-md-6">
       			<div class="form-group">
       				<label for="nombre">Nombre</label>
       				<input type="text" id="nombre" class="form-control input-sm" value="<?php echo $almacen->alm_nombre?>">
       			</div>
       		</div>
       		<div class="col-md-6">
       			<div class="form-group">
       				<label for="direccion">Dirección</label>
       				<input type="text" id="direccion" class="form-control input-sm" value="<?php echo $almacen->alm_direccion?>">
       			</div>
       		</div>
       	</div>
         
       	<div class="row">
       		<div class="col-md-8">
       			<div class="form-group">
       				<label for="encargado">Encargado</label>
       				<input type="text" id="encargado" class="form-control input-sm" value="<?php echo $almacen->alm_encargado?>">
       			</div>
       		</div>
       		<div class="col-md-4">
       			<div class="form-group">
       				<label for="telefono">Teléfono</label>
       				<input type="number" id="telefono" class="form-control input-sm" value="<?php echo $almacen->alm_telefono?>" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9">
       			</div>
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_almacen">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){

  		//guardar
  		$("#btn_guardar_almacen").click(function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');
  			var datos = {
  							id:$("#alm_id").val(),
  							direccion:$("#direccion").val(),
  							nombre:$("#nombre").val(),
  							encargado:$("#encargado").val(),
  							telefono:$("#telefono").val(),
  							ver_direccion_comprobante:$("#ver_direccion_comprobante").prop("checked")
  						};
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/almacenes/guardarAlmacen',
  				dataType:'json',
  				data:datos,
  				method:'post',
  				success:function(response){
  					if(response.status == STATUS_FAIL)
  					{
  						if(response.tipo == '1')
  						{
  							var errores = response.errores;
  							toast('error', 1500, 'Faltan ingresar datos.');
  							$.each(errores, function(index, value){
  								$("#"+index).parent().addClass('has-error');
  							});
  						}
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'Almacén ingresado');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});
  					
  		});
  	});
  </script>
