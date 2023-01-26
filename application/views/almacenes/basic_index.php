<h2 align="center">Almacénes</h2>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="search" placeholder="Buscar por nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_almacen"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="grid"></div>
</div>

<script>
    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/almacenes/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            search:function(){
                                return $("#search").val();
                            }
                        }
                    }
                }
            },
            schema:{
                data:'data',
                total:'rows'
            },
            pageSize: 20,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true
                             
    });
    $("#grid").kendoGrid({
        dataSource: dataSource,
        height: 550,
        sortable: true,
        pageable: true,
        columns: [
                    {field:'alm_nombre',title:'NOMBRE',width:'150px'},
                    {field:'alm_direccion',title:'DIRECCIÓN',width:"150px"},
                    {field:'alm_encargado',title:'ENCARGADO',width:'100px'},
                    {field:'alm_telefono',title:'TELEFONO',width:'80px'},
                    {field:'alm_editar', title:'&nbsp;',width:'60px',template:"#= alm_editar #"},
                    {field:'alm_eliminar', title:'&nbsp;',width:'60px',template:"#= alm_eliminar #"},
        ],
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_almacen").click(function(e){
               var idAlmacen = $(this).data('id');
                $("#myModal").load('<?php echo base_url()?>index.php/almacenes/editar/'+idAlmacen,{});
            });
            //editar producto
            $(".btn_eliminar_almacen").click(function(e){
                e.preventDefault();
                var idAlmacen = $(this).data('id');
                var msg = $(this).data('msg');
                var url = '<?php echo base_url()?>index.php/almacenes/eliminar/'+idAlmacen
                $.confirm({
                    title: 'Confirmar',
                    content: msg,
                    buttons: {
                        confirm:{
                            text:'aceptar',
                            btnClass: 'btn-blue',
                            action:function(){
                                $.ajax({
                                    url:url,
                                    dataType:'json',
                                    method:'get',
                                    success:function(response){
                                        if(response.status == STATUS_OK)
                                        {
                                            toast('success', 1500, 'Almacén eliminado');
                                            dataSource.read();
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 2000 ,'No se puedo eliminar almacén porque tiene productos agregados.');
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            
                        }
                    }
                });
            });                            
        }
    });    
    //nuevo producto
    $("#btn_nuevo_almacen").click(function(e){
        e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/almacenes/crear',{});
    }); 
    //buscar producto
    $("#btn_buscar_almacen").click(function(e){
        e.preventDefault();
        dataSource.read();
    });

    //buscar producto por campo texto
    $("#search").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
    })


</script>