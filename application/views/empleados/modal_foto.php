<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Subir Foto - <span id="datos_emplado"></span></h4>
        </div>
        <div class="modal-body">

            <img height="280px" id="img_empleado" class="card-img-top">
            <form id="frmSubirImagen" method="post" action="<?php echo base_url()?>index.php/empleados/guardar_foto" enctype="multipart/form-data" >
                <legend>Cargar Foto</legend>
                <div class="form-group">
                    <label for="imagen">Selecciona foto</label>
                    <input type="file" class="form-control" name="foto" id="foto" required="">
                </div>
                <button type="submit" class="btn btn-primary">Subir Imagen</button>
                <input type="hidden" name="foto_empleado_id" id="foto_empleado_id" />
            </form>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script type="text/javascript">
$(document).ready(function(){
    
    var frm = $("#frmSubirImagen");
    
    frm.bind('submit', function(e){
        var empleado_id = $('#foto_empleado_id').val();
        var imagen = $('input[type=file]').val().split('\\').pop();
        e.preventDefault();                
        
        var frmData = new FormData;
        frmData.append("imagen", $("input[name=foto]")[0].files[0]);
        frmData.append("empleado_id", empleado_id);
        frmData.append("foto", imagen);
        $.ajax({
            url: frm.attr("action"),
            type: frm.attr("method"),
            data: frmData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                var json = $.parseJSON(response);                
                if (response != 0) {
                    $(".card-img-top").attr("src", json.mostrar_imagen);
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });
        
    });
});    
</script>    