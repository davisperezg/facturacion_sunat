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
            <h4 class="modal-title">Detalle Comprobante - VENTAS.</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td>
                                <img height="120px" id="detalle_foto_empresa" class="card-img-top">
                            </td>
                            <input type="hidden" name="detalle_venta_id" id="detalle_venta_id">
                        </tr>
                        <tr>
                            <td><span id="empresa_descripcion"></span></td>
                        </tr>
                        <tr>
                            <td><span id="empresa_domicilio_fiscal"></span></td>
                        </tr>
                        <tr>
                            <td><span id="empresa_telefonos"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td><span id="empresa_ruc"></span></td>
                        </tr>
                        <tr>
                            <td><span id="detalle_documento"></span></td>
                        </tr>
                        <tr>
                            <td><span id="detalle_numeracion"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td><span id="detalle_entidad"></span></td>
                        </tr>
                        <tr>
                            <td><span id="numero_documento"></span></td>
                        </tr>
                        <tr>
                            <td><span id="direccion_entidad"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td><span id="detalle_fecha_emision"></span></td>
                        </tr>
                        <tr>
                            <td><span id="detalle_fecha_vencimiento"></span></td>
                        </tr>
                        <tr>
                            <td><span id="detalle_moneda"></span></td>
                        </tr>
                    </table>
                </div>                
            </div>
            <br>
            <table id="tabla_detalle" class="table table-bordered table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Cant.</th>
                        <th>U.M.</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th class="derecha_text">Valor U.</th>
                        <th class="derecha_text">Precio U.</th>
                        <th class="derecha_text">Importe</th>
                        <th id="th_bolsa" class="arranca_oculto">Bolsa</th>
                    </tr>
                </thead>
            </table>
            
            <div class="row" style="padding-top:20px;">
                <div class="col-md-5 col-md-offset-7">
                    <div class="panel panel-body" style="border:1px solid #7FB3D5;border-radius:6px;">
                        
                        <div id="div_total_gravada" class="input-group arranca_oculto">        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Gravada: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_gravada" name="detalle_total_gravada" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>
                        
                        <div id="div_total_igv" class="input-group arranca_oculto">        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total IGV (18%): <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_igv" name="detalle_total_igv" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>

                        <div id="div_total_inafecta" class="input-group arranca_oculto">        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Inafecta: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_inafecta" name="detalle_total_inafecta" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>

                        <div id="div_total_exonerada" class="input-group arranca_oculto" >        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Op. Exonerada: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_exonerada" name="detalle_total_exonerada" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>

                        <div id="div_total_gratuita" class="input-group arranca_oculto">        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Gratuita: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_gratuita" name="detalle_total_gratuita" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>

                        <div id="div_total_exportacion" class="input-group arranca_oculto">        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Exportación: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_exportacion" name="detalle_total_exportacion" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>

                        <div id="div_total_bolsa" class="input-group arranca_oculto">        
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">ICBPER: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_bolsa" name="detalle_total_bolsa" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                        </div>

                        <div id="div_total_a_pagar" class="input-group">                
                            <span class="input-group-addon" style="border:1px solid #ABB2B9;border-right: 0;">Importe Total: <span class="selec_moneda">S/.</span></span>                
                            <input type="text" id="detalle_total_a_pagar" name="detalle_total_a_pagar" class="form-control derecha_text" readonly="" style="border:1px solid #ABB2B9;">
                        </div>        
                    </div>
                </div>
            </div>
            <div>                
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?PHP echo base_url(); ?>assets/js/monstruo/cuotas_and_cobros.js"></script>
<script type="text/javascript">

    let empresas = JSON.parse(localStorage.getItem("empresas"));
    $("#empresa_descripcion").html('<b>'+empresas.empresa+'</b>');
    $("#empresa_domicilio_fiscal").text(empresas.domicilio_fiscal);
    $("#detalle_foto_empresa").attr('src', base_url +'images/'+empresas.foto);    

    $("#empresa_ruc").html('<b>Teléfonos: </b>'+empresas.telefono_fijo+'//'+empresas.telefono_movil);    
    $("#empresa_telefonos").html('<b>Teléfonos: </b>'+empresas.telefono_fijo+'//'+empresas.telefono_movil);    
    $("#empresa_ruc").html('<b>RUC: </b>'+empresas.ruc);
        
    carga_inicial_cuotas();
    carga_inicial_cobros();    
    
</script>