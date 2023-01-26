function sortJSON(data, key, orden) {
    return data.sort(function (a, b) {
        var x = a[key],
        y = b[key];

        if (orden === 'asc') {
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        }

        if (orden === 'desc') {
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        }
    });
}

n =  new Date();   
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDate();

//1 dd/mm/yyyy
//2 yyyy/mm/dd
function fecha_actual(formato){
    var fecha;
    n =  new Date();
    switch(formato) {
        case 1:
          fecha = n.getDate() + "-" + (n.getMonth() + 1) + "-" + n.getFullYear();
          break;
        case 2:
          fecha = n.getFullYear() + "-" + (n.getMonth() + 1) + "-" + n.getDate();
          break;
      }
    return fecha;
}

function fecha_actual_completando_ceros(formato){
    var fecha;
    n =  new Date();
    y = n.getFullYear();
    m = n.getMonth() + 1;
    if(m < 10){
        m = '0' + m;
    }    
    d = n.getDate();    
    
    switch(formato) {
        case 1:
          fecha = n.getDate() + "-" + (m) + "-" + n.getFullYear();
          break;
        case 2:
          fecha = n.getFullYear() + "-" + (m) + "-" + n.getDate();
          break;
      }
    return fecha;
}

function mes_texto(numero){
    var mes;
    switch(numero){
        case '1':
            mes = 'Enero';
        break;

        case '2':
            mes = 'Febrero';
        break;

        case '3':
            mes = 'Marzo';
        break;

        case '4':
            mes = 'Abril';
        break;

        case '5':
            mes = 'Mayo';
        break;

        case '6':
            mes = 'Junio';
        break;

        case '7':
            mes = 'Julio';
        break;

        case '8':
            mes = 'Agosto';
        break;

        case '9':
            mes = 'Septiembre';
        break;

        case '10':
            mes = 'Octubre';
        break;

        case '11':
            mes = 'Noviembre';
        break;

        case '12':
            mes = 'Diciembre';
        break;            
    }
    return mes;
}

var movimiento_productos = ["", "ingreso", "salida"];