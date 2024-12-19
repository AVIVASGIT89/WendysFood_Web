///******************** Codigo para activar item del menu AdminLTE dinamicamente ********************
var url = window.location;
// for sidebar menu entirely but not cover treeview
$('ul.nav-sidebar a').filter(function() {
    if (this.href) {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }
}).addClass('active');

// for the treeview
$('ul.nav-treeview a').filter(function() {
    if (this.href) {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');


//******************** Funcion para efecto Cargando... ********************
function configureLoadingScreen(screen){
    $(document)
        .ajaxStart(function () {
            screen.fadeIn();
        })
        .ajaxStop(function () {
            screen.fadeOut();
        });
}



//Setear DataTable a tablas con la clase "DataTable"
$(".dataTable").DataTable({
    "responsive": true, 
    "lengthChange": true, 
    "autoWidth": false,
    "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }

    }

  }
);



function crearDataTable(tabla){

    $(tabla).DataTable({
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": false,
        "language": {
    
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
    
        }
    
      }
    );

}


//Devolver hora actual
function devolverHoraActual(){
	
    // Obtener la hora actual
    const ahora = new Date();

    // Extraer horas, minutos y segundos
    let horas = ahora.getHours();
    let minutos = ahora.getMinutes();
    let segundos = ahora.getSeconds();

    // Asegurarse de que tengan dos dígitos (agregar un 0 si es necesario)
    horas = horas < 10 ? "0" + horas : horas;
    minutos = minutos < 10 ? "0" + minutos : minutos;
    segundos = segundos < 10 ? "0" + segundos : segundos;

    // Formatear la hora como hh:mm:ss
    const horaActual = `${horas}:${minutos}:${segundos}`;
    
    return horaActual;

}



//**************************** Validaciones de caracteres correctos en campos de Input ***********************************
//-------------------------------- Validacion para Ingresar solo Numeros y con Dos Decimales --------------------------------
function filterFloat(evt,input){
    // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
    var key = window.Event ? evt.which : evt.keyCode;    
    var chark = String.fromCharCode(key);
    var tempValue = input.value+chark;
    if(key >= 48 && key <= 57){
        if(filter(tempValue)=== false){
            return false;
        }else{       
            return true;
        }
    }else{
          if(key == 8 || key == 13 || key == 0) {     
              return true;              
          }else if(key == 46){
                if(filter(tempValue)=== false){
                    return false;
                }else{       
                    return true;
                }
          }else{
              return false;
          }
    }
}
function filter(__val__){
    var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
    if(preg.test(__val__) === true){
        return true;
    }else{
       return false;
    }
}


//Funcion que retorna fecha y hora actual
function fechaActual(){
    var now = new Date();
    var anio = now.getFullYear();
    var mes = (now.getMonth()+1);
    var dia = now.getDate();
    var hora = now.getHours();
    var minuto = now.getMinutes();
    var segundo = now.getSeconds();
    
    if(mes < 10){
        mes = '0' + mes;
    }
    if(dia < 10){
        dia = '0' + dia;
    }
    if(hora < 10){
        hora = '0' + hora;
    }
    if(minuto < 10){
        minuto = '0' + minuto;
    }
    if(segundo < 10){
        segundo = '0' + segundo;
    }
    
    return anio + '-' + mes + '-' + dia + ' ' + hora + ':' + minuto + ':' + segundo
    
}


//Permitir el ingresar solo de Numeros Enteros" --------------------------------
function soloNumeros(e) { // 1
	tecla = (document.all) ? e.keyCode : e.which; // 2
	if (tecla==8) return true; // 3
	patron =/^([0-9])+$/; // 4 
	te = String.fromCharCode(tecla); // 5
	return patron.test(te); // 6
}


//Permitir el ingreso solo de numero y letras sin Ñ-ñ
function numletras(e) { // 1
	tecla = (document.all) ? e.keyCode : e.which; // 2
	if (tecla==8) return true; // 3
	patron =/^([a-z]|[A-Z]|[0-9])+$/; // 4  - patron =/^([a-z]|[A-Z]|[0-9]|-|\s|ñ)+$/;
	te = String.fromCharCode(tecla); // 5
	return patron.test(te); // 6
}