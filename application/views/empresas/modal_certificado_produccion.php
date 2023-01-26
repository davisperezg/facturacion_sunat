<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Subir Certificado de <b>Producción</b></h4>
        </div>
        <div class="modal-body">

            <img height="240px" id="img_empresa" class="card-img-top">
            <form id="frmSubirImagen" method="post" action="<?php echo base_url()?>index.php/empresas/guardar_certificado_produccion" enctype="multipart/form-data" >
                <legend>Certificado de Producción:<span id="nombre_certicado_produccion"></span></legend>
                <div class="form-group">
                    <label for="imagen">Selecciona Certificado</label>
                    <input type="file" class="form-control" name="foto" id="foto" required="">
                    <br>
                    <input placeholder="Clave" type="password" class="form-control" name="certi_produccion_password" id="certi_produccion_password" required="">
                </div>
                <button type="submit" class="btn btn-primary">Subir Certificado de Producción</button>
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
        var imagen = $('input[type=file]').val().split('\\').pop();
        e.preventDefault();
        
        var frmData = new FormData;
        frmData.append("imagen", $("input[name=foto]")[0].files[0]);
        frmData.append("certi_produccion_nombre", imagen);
        frmData.append("certi_produccion_password", $("#certi_produccion_password").val());
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
                    toast('success', 1500, 'Certificado ingresado correctamente');
                    $(".card-img-top").attr("src", json.mostrar_imagen);
                    $("#nombre_certicado_produccion").text(json.nombre_imagen);
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });
        
    });
});    
</script>   