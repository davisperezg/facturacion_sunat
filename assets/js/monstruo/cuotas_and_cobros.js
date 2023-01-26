function carga_inicial_cuotas(){
    var url_cuotas = base_url + 'index.php/WS_cuotas/ws_select/' + venta_id_select;
    $.getJSON(url_cuotas)
        .done(function (data) {
            var numero_orden = 1;
            (data).forEach(function (repo) {
                agregarFila_cuota(numero_orden, repo.fecha_cuota, repo.monto, repo.estado);
                numero_orden ++;
            });
    });
}

function agregarFila_cuota(numero_orden, fecha_cuota, monto, estado){        
    var estado_description = '';
    if(estado == '1'){
        estado_description = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
    }

    var fila = '<tr class="seleccionado tabla_fila">';        
    fila += '<td align="center">'+numero_orden+'</td>';        
    fila += '<td>'+fecha_cuota+'</td>';
    fila += '<td class="derecha_text">'+monto+'</td>';        
    fila += '<td align=center>'+estado_description+'</td>';
    fila += '</tr>';
    $("#tabla_cuota").append(fila);    
}

function carga_inicial_cobros(){
    var url_cobros = base_url + 'index.php/WS_cobros/ws_select_cobros/' + venta_id_select;
    $.getJSON(url_cobros)
        .done(function (data) {
            var numero_orden = 1;
            (data).forEach(function (repo) {
                agregarFila_cobro(numero_orden, repo.fecha_pago, repo.monto, repo.modo_pago);
                numero_orden ++;
            });
    });
}

function agregarFila_cobro(numero_orden, fecha_pago, monto, modo_pago){
    var fila = '<tr class="seleccionado tabla_fila">';        
    fila += '<td align="center">'+numero_orden+'</td>';        
    fila += '<td>'+fecha_pago+'</td>';
    fila += '<td class="derecha_text">'+monto+'</td>';        
    fila += '<td align=center>'+modo_pago+'</td>';
    fila += '</tr>';
    $("#tabla_pago").append(fila);    
}