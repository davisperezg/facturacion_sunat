<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Subir Archivo Excel - Compras 8.1</h4>
        </div>
        <div class="modal-body">

            <img height="280px" id="img_producto" class="card-img-top">
            <form id="frmSubirImagen" method="post" action="<?php echo base_url()?>index.php/le_compras8_1_detalles/guardar_file_excel" enctype="multipart/form-data" >
                <legend>Cargar Excel</legend>
                <div class="form-group">
                    <label for="imagen">Selecciona excel</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" required="">
                </div>
                <button type="submit" class="btn btn-primary">Subir Archivo</button>
                <input type="hidden" name="mes" id="mes" />
                <input type="hidden" name="anio" id="anio" />
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
        
        ruta_url_item = base_url + 'index.php/le_compras8_1_detalles/importarExcel/' + $("#mes").val() + '/' + $("#anio").val();
        console.log(ruta_url_item);
        
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
                    var imagen = $('input[type=file]').val().split('\\').pop();
                    var data = {};
                    data['imagen'] = imagen;
                                        
                    $.getJSON(ruta_url_item, data)
                    .done(function (datos){
                        toast('success', 1500, 'Operación creada correctamente');
                        $("#myModal").modal('hide');                        
                        $("#tabla_id > tbody").remove();
                        
                        carga_inicial();
                    })
                    .fail(function( jqXHR, textStatus, errorThrown ) {
                        console.log( "Algo ha fallado-: " +  textStatus );
                    });
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });

        var imagen = $('input[type=file]').val().split('\\').pop();
        console.log('imagen:'+imagen);
    });
});
</script>