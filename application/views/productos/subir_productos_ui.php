<div class="modal-dialog" role="document">
	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Subir Productos</h4>
      </div>
      <div class="modal-body">
      	<div class="demo-section k-content"></div>
      	<input type="file" name="files" id="files" placeholder="asdasd">
        <div id="msj-error" class="alert alert-danger"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_producto">Guardar</button>
      </div>      		
	</div>	
</div>
<script>
 	$(document).ready(function(){
      $('#msj-error').hide();
	    $("#files").kendoUpload({
	        async: {
	            saveUrl: "<?php echo base_url()?>index.php/productos/guardarSubidaProductos",
	            autoUpload: true,
	        },
          multiple:false,
          validation: {
              allowedExtensions: [".xlsx",".xls"],
          },
          progress:function(){
            $('#msj-error').hide();
          }	,        
	        success:function(){
	           dataSource.read();
             toast("success", 1500, "Productos subidos!");
	        },
	        error:function(e){
            toast("error", 1500, "No se pudo subir productos");
            $('#msj-error').show();
            var texto = "<p>"+e.XMLHttpRequest.response+"</p>"
            $('#msj-error').html(texto);
	        	console.log(e);
            
	        }
	    }); 		
 	});	
</script>
