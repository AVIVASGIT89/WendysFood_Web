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
                        botonAnular = '';
                        botonSunat = '<a href="vistas/modulos/comprobanteimpresion.php?idVenta='+venta.ID_VENTA+'" target="_blank" class="btn btn-sm btn-success" title="Descargar boleta"><i class="fas fa-file-download"></i></a>';
                    }
                    else
                    if(venta.ENVIO_SUNAT == "2"){
                        botonAnular = '';
                        botonSunat = '<a href="vistas/modulos/comprobanteimpresion.php?idVenta='+venta.ID_VENTA+'" target="_blank" class="btn btn-sm btn-warning" title="Descargar boleta"><i class="fas fa-file-download"></i></a>';
                    }
                    
                }
                else
                if(venta.ESTADO_VENTA == "3"){
                    estadoVenta = '<span class="badge badge-danger">Anulado</span>';
                    botonAnular = '';
                    botonSunat = '';
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

            crearDataTable("#tablaRegistroVentas");

        }

    });

});


//Reporte ventas sunat
$("#btnReporteVentasSunat").click(function(){

    var fechaDesde = $("#fechaDesde").val();
    var fechaHasta = $("#fechaHasta").val();
    var tipoComprobante = $("#tipoComprobante").val();
    var estadoEnvio = $("#estadoEnvio").val();

    var datos = new FormData();
    datos.append("accion", "ventasSunat");
    datos.append("fechaDesde", fechaDesde);
    datos.append("fechaHasta", fechaHasta);
    datos.append("tipoComprobante", tipoComprobante);
    datos.append("estadoEnvio", estadoEnvio);

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

            $("#tablaReposteVentasSunat").DataTable().destroy();

            var i = 0;
            var totalMontoVenta = 0;

            var botonDescargar = '';
            
            var tbody = $("#tbodyReporteVentasSunat");
			tbody.empty();

            respuesta.forEach(function(ventaSunat){

                i++;
                totalMontoVenta += parseFloat(ventaSunat.TOTAL_VENTA);
                estadoEnvio;

                if(ventaSunat.ENVIO_SUNAT == "1"){

                    estadoEnvio = '<span class="badge badge-success">Finalizado</span>';
                    botonDescargar = '<a href="vistas/modulos/comprobanteimpresion.php?idVenta='+ventaSunat.ID_VENTA+'" target="_blank" class="btn btn-sm btn-success" title="Descargar boleta"><i class="fas fa-file-download"></i></a>';

                }else if(ventaSunat.ENVIO_SUNAT == "2"){

                    estadoEnvio = '<span class="badge badge-warning">Observado</span>';
                    botonDescargar = '<button class="btn btn-sm btn-danger btnMensajeSunat" idventa="'+ventaSunat.ID_VENTA+'"><i class="fas fa-comment"></i></button> <button class="btn btn-sm btn-primary btnDatosReenvioSunat" idventa="'+ventaSunat.ID_VENTA+'"><i class="fas fa-share"></i></button> <a href="vistas/modulos/comprobanteimpresion.php?idVenta='+ventaSunat.ID_VENTA+'" target="_blank" class="btn btn-sm btn-warning" title="Descargar boleta"><i class="fas fa-file-download"></i></a>';

                }else{

                    estadoEnvio = '<span class="badge badge-secondary">Otro</span>';

                }

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
                      '<td align="center">'+ventaSunat.FECHA_VENTA+'</td>'+
                      '<td align="center">'+ventaSunat.TIPO_COMPROBANTE+'</td>'+
                      '<td align="center">'+ventaSunat.SERIE_VENTA_SUNAT+'</td>'+
                      '<td align="center">'+ventaSunat.NRO_VENTA_SUNAT+'</td>'+
                      '<td align="center">'+ventaSunat.TOTAL_VENTA+'</td>'+
                      '<td align="center">'+estadoEnvio+'</td>'+
                      '<td align="center">'+
                        '<div class="btn-group">'+
                          botonDescargar+
                        '</div>'+
                      '</td>'+
					'</tr>'
				);
			});

            $("#spRegistroVentas").text(i);
            $("#spTotalVentas").text(totalMontoVenta.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})); //formato miles y 2 decimales

            crearDataTable("#tablaReposteVentasSunat");

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
