//Reporte registro ventas
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

            //console.log("respuesta:", respuesta);

            $("#tablaRegistroVentas").DataTable().destroy();

            var i = 0;
            var totalMontoVenta = 0;
            var estadoVenta;

            var botonAnular = '';
            var botonSunat = '';
            
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

                    //Estado SUNAT
                    if(venta.ENVIO_SUNAT == "0"){
                        botonSunat = '<button class="btn btn-sm btn-primary" title="Enviar a SUNAT" onclick="mostrarModalComprobante('+venta.ID_VENTA+');"><i class="fas fa-file-upload"></i></button>';
                    }
                    else
                    if(venta.ENVIO_SUNAT == "1"){
                        botonSunat = '<button class="btn btn-sm btn-success" title="Descargar boleta"><i class="fas fa-file-download"></i></button>';
                    }
                    
                }
                else
                if(venta.ESTADO_VENTA == "3"){
                    estadoVenta = '<span class="badge badge-danger">Anulado</span>';
                    botonAnular = '';
                }
                else{
                    estadoVenta = "Otro";
                }

                i++;
                totalMontoVenta += parseFloat(venta.TOTAL_VENTA);

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
					  '<td align="center">'+venta.ID_VENTA+'</td>'+
                      '<td align="center">'+venta.TIPO_VENTA+'</td>'+
					  '<td align="center">'+venta.FECHA_VENTA+'</td>'+
					  '<td align="center"><a href="#" onclick="mostrarDetalleVenta('+venta.ID_VENTA+');">'+venta.NRO_PRODUCTOS+'</a></td>'+
                      '<td align="center">'+venta.TOTAL_VENTA+'</td>'+
					  '<td align="center">'+estadoVenta+'</td>'+
					  '<td align="center">'+venta.USUARIO_VENTA+'</td>'+
                      '<td align="center">'+
                        '<div class="btn-group">'+
                          botonAnular+
                        '</div>'+
                      '</td>'+
                      '<td align="center">'+
                        '<div class="btn-group">'+
                          botonSunat+
                        '</div>'+
                      '</td>'+
					'</tr>'
				);
			});

            $("#spRegistroVentas").text(i);
            $("#spTotalVentas").text(totalMontoVenta.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})); //formato miles y 2 decimales

            crearDataTable("tablaRegistroVentas");

        }

    });

});


//Mostrar productos de orden cancelada
$(".productosOrden").click(function(){

    var idVenta = $(this).attr("idVenta");

    mostrarDetalleVenta(idVenta);

});


//Modal detalle venta
function mostrarDetalleVenta(idVenta){

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

            //console.log("respuesta:", respuesta);

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
