//Inicio para registrar orden
$(".nuevaOrden").click(function(){

    var nroMesa = $(this).attr("nroMesa");
    var idMesa = $(this).attr("idMesa");
    var tipoVenta = $(this).attr("tipoVenta");

    $("#divClienteDelivery").hide();

    $("#tipoVenta").val(tipoVenta);
    $("#idMesaVenta").val(idMesa);
    $("#spNroMesa").text(nroMesa);

    //Mostramos modal "modalRegistrarOrden"
    $("#modalRegistrarOrden").modal('show');
    //Ponemos el foco en el input "codigoProducto" dentro del modal mostrado
    $('#modalRegistrarOrden').on('shown.bs.modal', function () {
        $('#codigoProducto').focus();
    });
    
});


//Inicio para registrar delivery
$(".nuevoDelivery").click(function(){

    var tipoVenta = $(this).attr("tipoVenta");

    $("#divClienteDelivery").show();

    $("#tipoVenta").val(tipoVenta);
    $("#idMesaVenta").val("1");
    $("#spNroMesa").text("Delivery");

    //Mostramos modal "modalRegistrarOrden"
    $("#modalRegistrarOrden").modal('show');
    //Ponemos el foco en el input "codigoProducto" dentro del modal mostrado
    $('#modalRegistrarOrden').on('shown.bs.modal', function () {
        $('#codigoProducto').focus();
    });
    
});


///****************** Agregar producto a la Orden ******************///
//Buscar producto
function mostrarProducto(){

    var codigoProducto = $("#codigoProducto").val();

    var datos = new FormData();
    datos.append("accion", "buscarProducto");
    datos.append("codigoProducto", codigoProducto);

    var tbody = $("#tbodyListaProductos");

    $.ajax({
        url: "ajax/producto.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            //Si no hay respuesta
            if(!respuesta){

                alert('Producto no encontrado');
                $("#codigoProducto").focus();
                return;

            }

            //Si el producto ya se encuentra en la lista
            var salir = false;

            $("#tbodyListaProductos tr").each(function(){

                var id_producto = $(this).find("input[name='f_id_producto']").val();

                if(id_producto == respuesta.ID_PRODUCTO){

                    alert("El producto ya se encuentra en la lista");
                    salir = true;
                    return;

                }

            });

            if(salir){

                $("#codigoProducto").val("");
                $("#codigoProducto").focus();
                
                return;

            }

            var bloqueoCantidad = "";
            var bloqueoSubtotal = "disabled";
            var eventoSubtotal = "";
            var precioUnitario = respuesta.PRECIO_UNITARIO;
            var baseSubtotal = parseFloat(precioUnitario / 1.18).toFixed(2);
			var igvSubtotal = parseFloat(precioUnitario - baseSubtotal).toFixed(2);

            if(respuesta.TIPO_PRECIO == "2"){

                bloqueoCantidad = "disabled";
                bloqueoSubtotal = "";
                eventoSubtotal = 'onkeypress="return filterFloat(event,this);" onkeyup="calcularSubtotalPrecioManual(event);"';
                precioUnitario = '0';

            }

            tbody.append(
                '<tr>'+
					'<td></td>'+
					'<td>'+respuesta.NOMBRE_PRODUCTO+'</td>'+
                    '<td><input type="text" size="5" name="f_cantidad" value="1" onClick="this.select();" onkeypress="return soloNumeros(event);" onkeyup="carlcularSubtotal(event)" '+bloqueoCantidad+'/></td>'+
                    '<td>'+precioUnitario+'</td>'+
                    '<td>'+
                        '<input type="text" size="5" name="f_subtotal" value="'+precioUnitario+'" onClick="this.select();" '+bloqueoSubtotal+' '+eventoSubtotal+'/>'+
                        '<input type="hidden" name="f_baseSubtotal" value="'+baseSubtotal+'"/>'+
					    '<input type="hidden" name="f_igvSubtotal" value="'+igvSubtotal+'"/>'+
                        '<input type="hidden" name="f_tipo_cocina" value="'+respuesta.TIPO_COCINA+'"/>'+
                    '</td>'+
                    '<td align="center">'+
                        '<a href="javascript:" onclick="quitarProducto(event)"><img src="vistas/images/Menos.png" alt="Quitar Producto" height="21"/></a>'+
                        '<input type="hidden" name="f_id_producto" value="'+respuesta.ID_PRODUCTO+'"/>'+
                    '</td>'+
				'</tr>'
            );

            $("#codigoProducto").val("");
            $("#codigoProducto").focus();

            enumerarItem();
            calcularTotal();

        }

    });

}

//Calcular total
function calcularTotal(){

    var total = 0;

    $("#tbodyListaProductos tr").each(function() {

        var subtotal = $(this).find("input[name='f_subtotal']").val();

		if(subtotal != ""){
			total = total + parseFloat(subtotal);
		}

    });

    var montoTotalVenta = total.toFixed(2);
    var montoBaseVenta = parseFloat(montoTotalVenta / 1.18).toFixed(2);
	var montoIgvVenta = parseFloat(montoTotalVenta - montoBaseVenta).toFixed(2);

    $("#spTotalVenta").text(montoTotalVenta);
    $("#ventaBase").val(montoBaseVenta);
    $("#ventaIgv").val(montoIgvVenta);

}

//Calcular Subtotal
function carlcularSubtotal(e){

    var tr = $(e.target).closest("tr");
    var cantidad = tr.find("input[name='f_cantidad']").val();
    var precioUnitario = tr.find("td").eq(3).text();
    var subtotal = parseFloat(cantidad) * parseFloat(precioUnitario);
    tr.find("input[name='f_subtotal']").val(subtotal.toFixed(2));
    var baseSubtotal = parseFloat(subtotal / 1.18).toFixed(2);
    tr.find("input[name='f_baseSubtotal']").val(baseSubtotal);
	var igvSubtotal = parseFloat(subtotal - baseSubtotal).toFixed(2);
    tr.find("input[name='f_igvSubtotal']").val(igvSubtotal);

    calcularTotal();

}

//Calcular subtotal de precio manual
function calcularSubtotalPrecioManual(e){

    var tr = $(e.target).closest("tr");
    var subtotal = tr.find("input[name='f_subtotal']").val();
	var baseSubtotal = parseFloat(subtotal / 1.18).toFixed(2);
    tr.find("input[name='f_baseSubtotal']").val(baseSubtotal);
	var igvSubtotal = parseFloat(subtotal - baseSubtotal).toFixed(2);
    tr.find("input[name='f_igvSubtotal']").val(igvSubtotal);
	
	calcularTotal();
    
}

//Quitar producto
function quitarProducto(e){

    $(e.target).closest("tr").remove();
    
	$("#codigoProducto").focus();

    enumerarItem();
    calcularTotal();

}

//Enumerar items de tabla productos
function enumerarItem(){
    var i=0;

    $("#tbodyListaProductos tr").each(function() {

        i++;

        $(this).find("td").eq(0).text(i);

    });
}
//-------------------------------------------------------------------------------------


//Mostrar detalle orden para Editar
$(".modificarOrden").click(function(){

    var idVenta = $(this).attr("idVenta");
    var idMesa = $(this).attr("idMesa");
    var nroMesa = $(this).attr("nroMesa");
    var fechaOrden = '';

    var datos = new FormData();
    datos.append("accion", "mostrarDetalleVenta");
    datos.append("idVenta", idVenta);

    var tbody = $("#tbodyListaProductosModificar");

    $.ajax({
        url: "ajax/venta.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            tbody.empty();
            var i = 0;

            respuesta.forEach(function(row) {

                fechaOrden = row.FECHA_VENTA;
				
                var bloqueoCantidad = "";
                var bloqueoSubtotal = "disabled";
                var eventoSubtotal = "";
                var precioUnitario = row.PRECIO_UNITARIO;

                if(row.TIPO_PRECIO == "2"){

                    bloqueoCantidad = "disabled";
                    bloqueoSubtotal = "";
                    eventoSubtotal = 'onkeypress="return filterFloat(event,this);" onkeyup="calcularSubtotalPrecioManualModificar(event);"';
                    precioUnitario = '0';

                }
                
                i++;

				tbody.append( 
                    '<tr>'+
                        '<td></td>'+
                        '<td>'+row.NOMBRE_PRODUCTO+'</td>'+
                        '<td><input type="text" size="5" name="f_cantidad_modificar" value="'+row.CANTIDAD+'" onClick="this.select();" onkeypress="return soloNumeros(event);" onkeyup="carlcularSubtotalModificar(event)" '+bloqueoCantidad+'/></td>'+
                        '<td>'+precioUnitario+'</td>'+
                        '<td>'+
                            '<input type="text" size="5" name="f_subtotal_modificar" value="'+row.SUBTOTAL+'" onClick="this.select();" '+bloqueoSubtotal+' '+eventoSubtotal+'/>'+
                            '<input type="hidden" name="f_baseSubtotal_modificar" value="'+row.SUBTOTAL_BASE+'"/>'+
                            '<input type="hidden" name="f_igvSubtotal_modificar" value="'+row.SUBTOTAL_IGV+'"/>'+
                        '</td>'+
                        '<td align="center">'+
                            '<a href="javascript:" onclick="quitarProductoModificar(event)"><img src="vistas/images/Menos.png" alt="Quitar Producto" height="21"/></a>'+
                            '<input type="hidden" name="f_id_producto" value="'+row.ID_PRODUCTO+'"/>'+
                        '</td>'+
                    '</tr>'
				);

                $("#spTotalVentaModificar").text(row.TOTAL_VENTA);
                $("#idVentaModificar").val(row.ID_VENTA);
			});

            $("#spNroMesaModificar").text(nroMesa);
            $("#spFechaOrdenModificar").text(fechaOrden);
            $("#idMesaModificar").val(idMesa);

            //Mostramos modal "modalModificarOrden"
            $("#modalModificarOrden").modal('show');
            //Ponemos el foco en el input "codigoProductoModificar" dentro del modal mostrado
            $('#modalModificarOrden').on('shown.bs.modal', function () {
                $('#codigoProductoModificar').focus();
            });

            enumerarItemModificar();

        }

    });

});


//Mostrar productos de orden para realizar pago
$(".registrarPago").click(function(){

    var idVenta = $(this).attr("idVenta");
    var idMesa = $(this).attr("idMesa");
    var nroMesa = $(this).attr("nroMesa");
    var fechaOrden = '';

    var datos = new FormData();
    datos.append("accion", "mostrarDetalleVenta");
    datos.append("idVenta", idVenta);

    var tbody = $("#tbodyListaProductosPago");

    $.ajax({
        url: "ajax/venta.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            tbody.empty();
            var i = 0;

            respuesta.forEach(function(row) {

                fechaOrden = row.FECHA_VENTA;
				i++;

				tbody.append( 
                        "<tr>"+
                          "<td>"+i+"</td>"+
                          "<td>"+row.NOMBRE_PRODUCTO+"</td>"+
                          "<td align='center'>"+row.CANTIDAD+"</td>"+
                          "<td align='right'>"+row.PRECIO_UNITARIO+"</td>"+
                          "<td align='right'>"+row.SUBTOTAL+"</td>"+
                          "<td align='center'><button class='btn btn-primary btn-sm' onClick='imprimirProducto("+nroMesa+", &#34;"+row.NOMBRE_PRODUCTO+"&#34;, "+row.CANTIDAD+", "+row.TIPO_COCINA+");'><i class='fas fa-print'></i></button></td>"+
                        "</tr>"
				);

                $("#spTotalVentaPago").text(row.TOTAL_VENTA);
                $("#idVentaPago").val(row.ID_VENTA);
			});

            $("#spNroMesaPago").text(nroMesa);
            $("#spFechaOrdenPago").text(fechaOrden);
            $("#idMesaPago").val(idMesa);
            $("#modalRegistrarPago").modal('show');

        }

    });

});


//Registrar pago orden
$("#btnRegistrarPago").click(function(){

    if (!$('input[name="formaPago"]').is(':checked')) {
        
        alert('Debe seleccionar una forma de pago');
        return;

    }

    var idVenta = $("#idVentaPago").val();
    var idMesa = $("#idMesaPago").val();
    var formaPago = $("input:radio[name=formaPago]:checked").val();

    var datos = new FormData();
    datos.append("accion", "finalizarVenta");
    datos.append("idVenta", idVenta);
    datos.append("idMesa", idMesa);
    datos.append("formaPago", formaPago);

    Swal.fire({
        title: 'Alerta!!',
        text: "Confirme registro de pago",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/venta.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){
        
                    //console.log("respuesta:", respuesta);
        
                    if(respuesta == "ok"){
        
                        Swal.fire({
                            title: "El pago ha sido registrado correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "inicio";
                            }
                            
                        });
        
                    }
        
                }
        
            });

        }
    });

});


//Anular venta desde reporte detalle
function anularOrdenFromDetalle(idVenta){

    //alert("llega: " + idVenta); return;

    var datos = new FormData();
    datos.append("accion", "cancelarVentaFromDetalle");
    datos.append("idVenta", idVenta);

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Anular la orden: " + idVenta,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/venta.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){
        
                    //console.log("respuesta:", respuesta);
        
                    if(respuesta == "ok"){
        
                        Swal.fire({
                            title: "La orden ha sido anulada correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "registro-ventas";
                            }
                            
                        });
        
                    }
        
                }
        
            });

        }
    });

}


//Cancelar orden
$("#btnCancelarOrden").click(function(){

    var idVenta = $("#idVentaModificar").val();
    var idMesa = $("#idMesaModificar").val();

    var datos = new FormData();
    datos.append("accion", "cancelarVenta");
    datos.append("idVenta", idVenta);
    datos.append("idMesa", idMesa);

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Cancelar la orden?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/venta.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){
        
                    //console.log("respuesta:", respuesta);
        
                    if(respuesta == "ok"){

                        enviarMailAnularOrden();
        
                        Swal.fire({
                            title: "La orden ha sido cancelada correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "inicio";
                            }
                            
                        })
        
                    }
        
                }
        
            });

        }
    });
});

//Enviar mail anular orden
function enviarMailAnularOrden(){

    var listaProductos = [];
    var tipoOrden = $("#spNroMesaModificar").text();
    var fechaOrden = $("#spFechaOrdenModificar").text();
    var fechaCancelacion = fechaActual();

    //Obtenemos la lista de productos (detalle)
    $("#tbodyListaProductosModificar tr").each(function(){

        var cantidad = $(this).find("input[name='f_cantidad_modificar']").val();
        var precioUnitario = $(this).find("td").eq(3).text();
		var nombreProducto = $(this).find("td").eq(1).text();
        var subtotal = $(this).find("input[name='f_subtotal_modificar']").val();

        listaProductos.push(
            {
				'nombreProducto': nombreProducto,
                'cantidad': cantidad,
                'precioUnitario': precioUnitario,
                'subtotal': subtotal
            }
        );

    });

    var datosMail = {
        "accion": "enviarMailAnulacionOrden",
        "listaProductos": listaProductos,
        "fechaOrden": fechaOrden,
        "fechaCancelacion": fechaCancelacion,
        "tipoOrden": tipoOrden
    };

    $.ajax({
    url: "ajax/venta.ajax.php",
    method: "POST",
    data: datosMail,
    dataType: "json",
    success: function(respuesta){

        console.log("respuesta:", respuesta);

        }

    });

}



///****************** Registrar venta (Orden) ******************///
$("#btnRegistrarVenta").click(function(){

    $("#btnRegistrarVenta").prop('disabled', true);

    var tipoVenta = $("#tipoVenta").val();
    var idMesa = $("#idMesaVenta").val();
    var nroMesa = $("#spNroMesa").text();
    var totalVenta = parseFloat($("#spTotalVenta").text());
    var clienteDelivery = $("#clienteDelivery").val();
    var baseVenta = $("#ventaBase").val();
    var igvVenta = $("#ventaIgv").val();

    if(!totalVenta > 0){
        alert("La venta no puede ser 0");
        $("#btnRegistrarVenta").prop('disabled', false);
        return;
    }

    if(tipoVenta == "2"){
        if(clienteDelivery == ""){
            alert("Debe ingresar el nombre de cliente");
            $('#clienteDelivery').focus();
            $("#btnRegistrarVenta").prop('disabled', false);
            return;
        }
    }

    //Variable para almacenar la lista de productos
    var listaProductos = [];
    
    //Obtenemos la lista de productos (detalle)
    $("#tbodyListaProductos tr").each(function(){

        var idProducto = $(this).find("input[name='f_id_producto']").val();
        var cantidad = $(this).find("input[name='f_cantidad']").val();
        var precioUnitario = $(this).find("td").eq(3).text();
		var nombreProducto = $(this).find("td").eq(1).text();
        var subtotal = $(this).find("input[name='f_subtotal']").val();
        var baseSubtotal = $(this).find("input[name='f_baseSubtotal']").val();
		var igvSubtotal = $(this).find("input[name='f_igvSubtotal']").val();
        var tipoCocina = $(this).find("input[name='f_tipo_cocina']").val();

        if(subtotal == "0" || subtotal == ""){
			alert("No puede haber un Subtotal con valor 0");
            $("#btnRegistrarVenta").prop('disabled', false);
			exit(); //cortamos la ejecucion
		}

        listaProductos.push(
            {
                'idProducto': idProducto,
				'nombreProducto': nombreProducto,
                'cantidad': cantidad,
                'precioUnitario': precioUnitario,
                'subtotal': subtotal,
                'baseSubtotal': baseSubtotal,
                'igvSubtotal': igvSubtotal,
                'tipoCocina': tipoCocina
            }
        );

    });

    //Definimos toddos los datos de la venta
    var datosVenta = {
                        "accion": "registrarVenta",
                        "tipoVenta": tipoVenta,
                        "idMesaVenta": idMesa,
                        "totalVenta": totalVenta,
                        "baseVenta": baseVenta,
                        "igvVenta": igvVenta,
                        "clienteDelivery": clienteDelivery,
                        "listaProductos": listaProductos
                    };
    
    //Efecto "Registrando..."
    $(this).prop("disabled", true);
    $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registrando...');      
    
    //Registro de venta por AJAX
    $.ajax({
        url: "ajax/venta.ajax.php",
        method: "POST",
        data: datosVenta,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            if(respuesta["resultado"] == "ok"){

                imprimirOrden(listaProductos, nroMesa, clienteDelivery);

                Swal.fire({
                    title: "Orden registrada correctamente",
                    icon: "success",
                    allowOutsideClick: false,
                    confirmButtonText: "Ok"
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "inicio";
                    }
                    
                });

            }

        }

    });
    
});


//Imprimir orden
function imprimirOrden(pListaProductos, pNroMesa, pClienteDelivery){

    var datosEnvio = {
            'nroMesa': pNroMesa,
            'clienteDelivery': pClienteDelivery,
            'listaProductos': pListaProductos
        };

    $.ajax({
        data: datosEnvio,
        url: "http://localhost/impwendysfood/imprimirOrden.php",
        type: "POST",
        dataType: "json",
        success: function(data){
            //alert("Impresion: " + data.hecho);
        }/*,
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			 alert("Status: " + textStatus);
			 alert("Error: " + errorThrown); 
			//alert('Error de system.');
		} */
    });
}


//Imprimir ticket venta pago
function imprimirTicket(pListaProductos, pNroMesa){

    var datosEnvio = {
            'nroMesa': pNroMesa,
            'listaProductos': pListaProductos
        };

    $.ajax({
        data: datosEnvio,
        url: "http://localhost/impwendysfood/imprimirTicket.php",
        type: "POST",
        dataType: "json",
        success: function(data){
            //alert("Impresion: " + data.hecho);
        }/*,
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			 alert("Status: " + textStatus);
			 alert("Error: " + errorThrown); 
			//alert('Error de system.');
		} */
    });
}


///****************** Imprimir Ticket Venta ******************///
$("#btnImprimirOrden").click(function(){

    //Variable para almacenar la lista de productos
    var nroMesa = $("#spNroMesaPago").text();
    var listaProductosPago = [];
    
    //Obtenemos la lista de productos (detalle)
    $("#tbodyListaProductosPago tr").each(function(){

        var cantidad = $(this).find("td").eq(2).text();
        var precioUnitario = $(this).find("td").eq(3).text();
		var nombreProducto = $(this).find("td").eq(1).text();
        var subtotal = $(this).find("td").eq(4).text();
        
        listaProductosPago.push(
            {
				'nombreProducto': nombreProducto,
                'cantidad': cantidad,
                'precioUnitario': precioUnitario,
                'subtotal': subtotal
            }
        );

    });
    
    imprimirTicket(listaProductosPago, nroMesa);
    
});


//Imprimir producto de orden
$("#btnImpProducto").click(function(){

    alert("llega"); return;

    var nroMesa = $(this).attr("nroMesa");
    var nomProducto = $(this).attr("nomProducto");
    var cantidad = $(this).attr("cantidad");
    var tipoCocina = $(this).attr("tipoCocina");

    var listaProductos = [];

    listaProductos.push(
        {   
            'nombreProducto': nomProducto,
            'cantidad': cantidad,
            'tipoCocina': tipoCocina
        }
    );

    var datosEnvio = {
        'nroMesa': nroMesa,
        'clienteDelivery': '',
        'listaProductos': listaProductos
    };

    $.ajax({
        data: datosEnvio,
        url: "http://localhost/impwendysfood/imprimirOrden.php",
        type: "POST",
        dataType: "json",
        success: function(data){
            //alert("Impresion: " + data.hecho);
        }/*,
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus);
            alert("Error: " + errorThrown); 
            //alert('Error de system.');
        } */
    });
    
});

function imprimirProducto(pNroMesa, pNomProducto, pCantidad, pTipoCocina){

    //alert(pNroMesa + "-" +pNomProducto+"-"+pCantidad+"-"+pTipoCocina); return;
    
    var listaProductos = [];

    listaProductos.push(
        {   
            'nombreProducto': pNomProducto,
            'cantidad': pCantidad,
            'tipoCocina': pTipoCocina
        }
    );

    var datosEnvio = {
        'nroMesa': pNroMesa,
        'clienteDelivery': '',
        'listaProductos': listaProductos
    };

    $.ajax({
        data: datosEnvio,
        url: "http://localhost/impwendysfood/imprimirOrden.php",
        type: "POST",
        dataType: "json",
        success: function(data){
            //alert("Impresion: " + data.hecho);
        }/*,
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus);
            alert("Error: " + errorThrown); 
            //alert('Error de system.');
        } */
    });

}


//Limpiar tabla productos
$("#btnLimpiar").click(function(){

    var tbody = $("#tbodyListaProductos");

    tbody.empty();

    $("#spTotalVenta").text('0.00');

    $("#codigoProducto").focus();
    
});


///****************** Modificar Orden ******************///
//Agregar producto
function mostrarProductoModificar(){

    var codigoProducto = $("#codigoProductoModificar").val();

    var datos = new FormData();
    datos.append("accion", "buscarProducto");
    datos.append("codigoProducto", codigoProducto);

    var tbody = $("#tbodyListaProductosModificar");

    $.ajax({
        url: "ajax/producto.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            //Si no hay respuesta
            if(!respuesta){

                alert('Producto no encontrado');
                $("#codigoProductoModificar").focus();
                return;

            }

            //Si el producto ya se encuentra en la lista
            var salir = false;

            $("#tbodyListaProductosModificar tr").each(function(){

                var id_producto = $(this).find("input[name='f_id_producto']").val();

                if(id_producto == respuesta.ID_PRODUCTO){

                    alert("El producto ya se encuentra en la lista");
                    salir = true;
                    return;

                }

            });

            if(salir){

                $("#codigoProductoModificar").val("");
                $("#codigoProductoModificar").focus();
                
                return;

            }

            var bloqueoCantidad = "";
            var bloqueoSubtotal = "disabled";
            var eventoSubtotal = "";
            var precioUnitario = respuesta.PRECIO_UNITARIO;
            var baseSubtotal = parseFloat(precioUnitario / 1.18).toFixed(2);
			var igvSubtotal = parseFloat(precioUnitario - baseSubtotal).toFixed(2);

            if(respuesta.TIPO_PRECIO == "2"){

                bloqueoCantidad = "disabled";
                bloqueoSubtotal = "";
                eventoSubtotal = 'onkeypress="return filterFloat(event,this);" onkeyup="calcularSubtotalPrecioManualModificar(event);"';
                precioUnitario = '0';

            }

            tbody.append(
                '<tr>'+
					'<td></td>'+
					'<td>'+respuesta.NOMBRE_PRODUCTO+'</td>'+
                    '<td><input type="text" size="5" name="f_cantidad_modificar" value="1" onClick="this.select();" onkeypress="return soloNumeros(event);" onkeyup="carlcularSubtotalModificar(event)" '+bloqueoCantidad+'/></td>'+
                    '<td>'+precioUnitario+'</td>'+
                    '<td>'+
                        '<input type="text" size="5" name="f_subtotal_modificar" value="'+precioUnitario+'" onClick="this.select();" '+bloqueoSubtotal+' '+eventoSubtotal+'/>'+
                        '<input type="hidden" name="f_baseSubtotal_modificar" value="'+baseSubtotal+'"/>'+
					    '<input type="hidden" name="f_igvSubtotal_modificar" value="'+igvSubtotal+'"/>'+
                    '</td>'+
                    '<td align="center">'+
                        '<a href="javascript:" onclick="quitarProductoModificar(event)"><img src="vistas/images/Menos.png" alt="Quitar Producto" height="21"/></a>'+
                        '<input type="hidden" name="f_id_producto" value="'+respuesta.ID_PRODUCTO+'"/>'+
                    '</td>'+
				'</tr>'
            );

            $("#codigoProductoModificar").val("");
            $("#codigoProductoModificar").focus();

            enumerarItemModificar();
            calcularTotalModificar();

        }

    });

}

//Calcular Subtotal Modificar
function carlcularSubtotalModificar(e){

    var tr = $(e.target).closest("tr");
    var cantidad = tr.find("input[name='f_cantidad_modificar']").val();
    var precioUnitario = tr.find("td").eq(3).text();
    var subtotal = parseFloat(cantidad) * parseFloat(precioUnitario);
    tr.find("input[name='f_subtotal_modificar']").val(subtotal.toFixed(2));
    var baseSubtotal = parseFloat(subtotal / 1.18).toFixed(2);
    tr.find("input[name='f_baseSubtotal_modificar']").val(baseSubtotal);
	var igvSubtotal = parseFloat(subtotal - baseSubtotal).toFixed(2);
    tr.find("input[name='f_igvSubtotal_modificar']").val(igvSubtotal);

    calcularTotalModificar();

}

//Calcular subtotal de precio manual modificar
function calcularSubtotalPrecioManualModificar(e){

    var tr = $(e.target).closest("tr");
    var subtotal = tr.find("input[name='f_subtotal_modificar']").val();
	var baseSubtotal = parseFloat(subtotal / 1.18).toFixed(2);
    tr.find("input[name='f_baseSubtotal_modificar']").val(baseSubtotal);
	var igvSubtotal = parseFloat(subtotal - baseSubtotal).toFixed(2);
    tr.find("input[name='f_igvSubtotal_modificar']").val(igvSubtotal);
	
	calcularTotalModificar();
    
}

//Calcular total modificar
function calcularTotalModificar(){

    var total = 0;

    $("#tbodyListaProductosModificar tr").each(function() {

        var subtotal = $(this).find("input[name='f_subtotal_modificar']").val();

		if(subtotal != ""){
			total = total + parseFloat(subtotal);
		}

    });

    var montoTotalVenta = total.toFixed(2);
    var montoBaseVenta = parseFloat(montoTotalVenta / 1.18).toFixed(2);
	var montoIgvVenta = parseFloat(montoTotalVenta - montoBaseVenta).toFixed(2);

    $("#spTotalVentaModificar").text(montoTotalVenta);
    $("#ventaBaseModificar").val(montoBaseVenta);
    $("#ventaIgvModificar").val(montoIgvVenta);

}

//Quitar producto modificar
function quitarProductoModificar(e){

    $(e.target).closest("tr").remove();
    
	$("#codigoProductoModificar").focus();

    enumerarItemModificar();
    calcularTotalModificar();

}

//Enumerar items de tabla productos
function enumerarItemModificar(){
    
    var i=0;

    $("#tbodyListaProductosModificar tr").each(function() {

        i++;

        $(this).find("td").eq(0).text(i);

    });
}


///****************** Modificar Orden ******************///
$("#btnModificarOrden").click(function(){

    $("#btnModificarOrden").prop('disabled', true);

    var totalVenta = parseFloat($("#spTotalVentaModificar").text());
    var idVenta = $("#idVentaModificar").val();
    var idMesa = $("#idMesaModificar").val();
    var baseVenta = $("#ventaBaseModificar").val();
    var igvVenta = $("#ventaIgvModificar").val();

    if(!totalVenta > 0){
        alert("La venta no puede ser 0");
        $("#btnModificarOrden").prop('disabled', false);
        return;
    }

    //Variable para almacenar la lista de productos
    var listaProductosModificar = [];

    //Obtenemos la lista de productos (detalle)
    $("#tbodyListaProductosModificar tr").each(function(){

        var idProducto = $(this).find("input[name='f_id_producto']").val();
        var cantidad = $(this).find("input[name='f_cantidad_modificar']").val();
        var precioUnitario = $(this).find("td").eq(3).text();
		var nombreProducto = $(this).find("td").eq(1).text();
        var subtotal = $(this).find("input[name='f_subtotal_modificar']").val();
        var baseSubtotal = $(this).find("input[name='f_baseSubtotal_modificar']").val();
		var igvSubtotal = $(this).find("input[name='f_igvSubtotal_modificar']").val();

        if(subtotal == "0" || subtotal == ""){
			alert("No puede haber un Subtotal con valor 0");
            $("#btnModificarOrden").prop('disabled', false);
			exit(); //cortamos la ejecucion
		}

        listaProductosModificar.push(
            {
                'idProducto': idProducto,
				'nombreProducto': nombreProducto,
                'cantidad': cantidad,
                'precioUnitario': precioUnitario,
                'subtotal': subtotal,
                'baseSubtotal': baseSubtotal,
                'igvSubtotal': igvSubtotal
            }
        );

    });

    //Definimos toddos los datos de la venta
    var datosVentaModificar = {
        "accion": "actualizarVenta",
        "idVenta": idVenta,
        "idMesa": idMesa,
        "totalVenta": totalVenta,
        "baseVenta": baseVenta,
        "listaProductos": listaProductosModificar,
        "igvVenta": igvVenta
        
    };

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Actualizar la orden?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/venta.ajax.php",
                method: "POST",
                data: datosVentaModificar,
                dataType: "json",
                success: function(respuesta){
        
                    //console.log("respuesta:", respuesta);
        
                    if(respuesta == "ok"){
        
                        Swal.fire({
                            title: "Orden actualizada correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "inicio";
                            }
                            
                        })
        
                    }
        
                }
        
            });

        }
    });

});


///***** Para no refrescar pagina por busqueda de producto por codigo *******
$(function(){
    $("#frmBuscarProducto").submit(function(e) {
        mostrarProducto();
        return false;
    });
});


///***** Para no refrescar pagina por busqueda de producto por codigo *******
$(function(){
    $("#frmBuscarProductoModificar").submit(function(e) {
        mostrarProductoModificar();
        return false;
    });
});