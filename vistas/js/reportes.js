//Reporte resumen ventas
$("#btnRegistroVentas").click(function(){

    var fechaDesde = $("#fechaDesde").val();
    var fechaHasta = $("#fechaHasta").val();
    var estadoVenta = $("#estadoVenta").val();
    var tipoVenta = $("#tipoVenta").val();

    var datos = new FormData();
    datos.append("accion", "registroVentas");
    datos.append("fechaDesde", fechaDesde);
    datos.append("fechaHasta", fechaHasta);
    datos.append("estadoVenta", estadoVenta);
    datos.append("tipoVenta", tipoVenta);

    //Ejecuta el efecto cargando...
	var screen = $('#loading-screen');
	configureLoadingScreen(screen);

    $.ajax({
        url: "ajax/reportes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta); return;

            $("#tablaRegistroVentas").DataTable().destroy();

            var i = 0;
            var totalMontoVenta = 0;
            var estadoVenta;
            var botonAnular;
            
            var tbody = $("#tbodyRegistroVentas");
			tbody.empty();

            respuesta.forEach(function(venta){

                if(venta.ESTADO_VENTA == "1"){
                    estadoVenta = '<span class="badge badge-warning">Pendiente</span>';
                    botonAnular = '';
                }
                else
                if(venta.ESTADO_VENTA == "2"){
                    estadoVenta = '<span class="badge badge-success">Finalizado</span>';
                    botonAnular = '<button class="btn btn-sm btn-danger" title="Anular" onclick="anularOrdenFromDetalle('+venta.ID_VENTA+');"><i class="fas fa-times-circle"></i></button>';
                }
                else
                if(venta.ESTADO_VENTA == "3"){
                    estadoVenta = '<span class="badge badge-danger">Anulado</span>';
                    botonAnular = '';
                }
                else{
                    estadoVenta = "Otro";
                }

                /*
                switch(venta.ESTADO_VENTA){
                    case 1:
                        estadoVenta = '<span class="badge badge-warning">Pendiente</span>';
                        botonAnular = '';
                        break;
                    case 2:
                        estadoVenta = '<span class="badge badge-success">Finalizado</span>';
                        botonAnular = '<button class="btn btn-sm btn-danger" title="Anular" onclick="anularOrdenFromDetalle('+venta.ID_VENTA+');"><i class="fas fa-times-circle"></i></button>';
                        break;
                    case 3:
                        estadoVenta = '<span class="badge badge-danger">Anulado</span>';
                        botonAnular = '';
                        break;
                    default:
                        estadoVenta = "Otro";
                }*/


                i++;
                totalMontoVenta += parseFloat(venta.TOTAL_VENTA);

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
					  '<td align="center">'+venta.ID_VENTA+'</td>'+
                      '<td align="center">'+venta.TIPO_VENTA+'</td>'+
					  '<td align="center">'+venta.FECHA_VENTA+'</td>'+
					  '<td align="center"><a href="#" onclick="mostratDetalleVenta('+venta.ID_VENTA+');">'+venta.NRO_PRODUCTOS+'</a></td>'+
                      '<td align="center">'+venta.TOTAL_VENTA+'</td>'+
					  '<td align="center">'+estadoVenta+'</td>'+
					  '<td align="center">'+venta.USUARIO_VENTA+'</td>'+
                      '<td align="center">'+
                        '<div class="btn-group">'+
                          botonAnular+
                        '</div>'+
                      '</td>'+
					'</tr>'
				);
			});

            $("#spRegistroVentas").text(i);
            $("#spTotalVentas").text(totalMontoVenta.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})); //formato miles y 2 decimales

            $('#tablaRegistroVentas').DataTable({

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
            
            });

        }

    });

});


//Modal detalle venta
function mostratDetalleVenta(idVenta){

    var datos = new FormData();
    datos.append("accion", "productosVenta");
    datos.append("idVenta", idVenta);

    $.ajax({
        url: "ajax/reportes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta); return;

            var i = 0;
            
            var tbody = $("#tbodyProductosVenta");
			tbody.empty();

            respuesta.forEach(function(producto){

                i++;

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
					  '<td align="center">'+producto.CANTIDAD+'</td>'+
					  '<td>'+producto.NOMBRE_PRODUCTO+'</td>'+
                      '<td align="right">'+producto.PRECIO_UNITARIO+'</td>'+
					  '<td align="right">'+producto.SUBTOTAL+'</td>'+
					'</tr>'
				);
			});

            $("#modalDetalleVenta").modal('show');

        }

    });

}
