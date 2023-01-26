<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Subir Imagen Cobro  <a id="imagen_descargar">Descargar</a></h4>
        </div>
        <div class="modal-body">

            <img height="280px" id="img_cobro" class="card-img-top">
            <form id="frmSubirImagen" method="post" action="<?php echo base_url()?>index.php/cobros/guardar_imagen" enctype="multipart/form-data" >
                <legend>Cargar Imagen</legend>
                <div class="form-group">
                    <label for="imagen">Selecciona imagen</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" required="">
                </div>
                <button type="submit" class="btn btn-primary">Subir Imagen</button>
                <input type="hidden" name="imagen_cobro_id" id="imagen_cobro_id" />
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
        e.preventDefault();                
        
        var frmData = new FormData;
        frmData.append("imagen", $("input[name=imagen]")[0].files[0]);
        
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
                    $("#imagen_descargar").attr('href', json.mostrar_imagen);
                    $("#imagen_descargar").attr('target', '_blank');
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });
        var cobro_id = $('#imagen_cobro_id').val();
        var imagen = $('input[type=file]').val().split('\\').pop();

        ruta_url_item = base_url + 'index.php/WS_cobros/ws_update_campo_item/' + cobro_id + '/archivo_adjunto/' +imagen;
        console.log('ruta_url_item'+ruta_url_item);
        $.getJSON(ruta_url_item)
                .done(function (data){
                    toast('success', 1500, 'Operación creada correctamente');
                });
    });
});    
</script>    