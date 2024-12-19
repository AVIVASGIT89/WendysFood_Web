//Mostrar detalle venta para generar comprobante electronico
function mostrarModalComprobante(idVenta){

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
            var totalMontoVenta = 0;
            var totalBase = 0;
            var totalIGV = 0;
            
            var tbody = $("#tbodyProductosVentaComprobante");
			tbody.empty();

            respuesta.forEach(function(producto){

                i++;
                totalMontoVenta += parseFloat(producto.SUBTOTAL);
                totalBase += parseFloat(producto.SUBTOTAL_BASE);
                totalIGV += parseFloat(producto.SUBTOTAL_IGV);

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
					  '<td align="center">'+producto.CANTIDAD+'</td>'+
					  '<td>'+producto.NOMBRE_PRODUCTO+'</td>'+
                      '<td align="right">'+producto.PRECIO_UNITARIO+'</td>'+
					  '<td align="right">'+producto.SUBTOTAL+
                        '<input type="hidden" name="subtotalBase" value="'+producto.SUBTOTAL_BASE+'"/>'+
					    '<input type="hidden" name="subtotalIGV" value="'+producto.SUBTOTAL_IGV+'"/>'+
                      '</td>'+
					'</tr>'
				);
			});

            $("#fIdVenta").val(idVenta);
            $("#fVentaBase").val(totalBase.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $("#fVentaIGV").val(totalIGV.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $("#spTotalVentaComprobante").text(totalMontoVenta.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

            $("#modalGenerarComprobante").modal('show');

        }

    });

}


//Buscar identificacion cliente
$("#btnBuscarClienteIdentificacion").click(function(){

    var identificacionCliente = $("#identificacionCliente").val();

    if(identificacionCliente == ""){

        alert("Ingrese la identificacion del cliente");
        $("#identificacionCliente").focus();
        return;

    }

    var tamanioIdentificacion = identificacionCliente.length;

    if(tamanioIdentificacion == 8){
        $("#fTipoIdentificacionCliente").val("1");
    }else if(tamanioIdentificacion == 11){
        $("#fTipoIdentificacionCliente").val("6");
    }else{
        $("#fTipoIdentificacionCliente").val("");
    }

    var datos = new FormData();
    datos.append("accion", "buscarClienteIdentificacion");
    datos.append("identificacionCliente", identificacionCliente);

    $.ajax({
        url: "ajax/cliente.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            //Si encontramos la identificacion en la BD
            if(respuesta){

                $("#spFuenteIdentificacion").text("Encontrado en la BD");
                $("#spFuenteIdentificacion").css("color", "green");

                $("#fIdCliente").val(respuesta.ID_CLIENTE);
                $("#clienteNombre").val(respuesta.NOMBRE_CLIENTE);
                $("#fIdentificacionCliente").val(respuesta.IDENTIFICACION_CLIENTE);

            }else{

                $("#fIdentificacionCliente").val("");
                $("#spFuenteIdentificacion").text("");
                $("#clienteNombre").val("");

                //Buscamos la identificacion en SUNAT
                var datosSunat = new FormData();
                datosSunat.append("accion", "buscarIdentificacionSunat");
                datosSunat.append("identificacionCliente", identificacionCliente);

                $.ajax({
                    url: "ajax/cliente.ajax.php",
                    method: "POST",
                    data: datosSunat,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(respuestaSunat){
            
                        //console.log("respuesta sunat:", respuestaSunat);

                        if(respuestaSunat == null){
                            alert("El nro. de identificacion no se encontró en la BD ni en SUNAT");
                            return;
                        }

                        if ("nombre" in respuestaSunat) {

                            registrarNuevoCliente(respuestaSunat.nombre, respuestaSunat.numeroDocumento);
                            
                            $("#spFuenteIdentificacion").text("Importado desde SUNAT");
                            $("#spFuenteIdentificacion").css("color", "blue");

                            $("#clienteNombre").val(respuestaSunat.nombre);
                            $("#fIdentificacionCliente").val(respuestaSunat.numeroDocumento);

                          } else {

                            alert("El nro. de identificacion no se encontró en la BD ni en SUNAT");

                          }
            
                    }
            
                });

            }

        }

    });
    
});


//funcion para registrar nuevo cliente
function registrarNuevoCliente(pNombre, pIdentificacion){

    var datosCliente = new FormData();
    datosCliente.append("accion", "registrarCliente");
    datosCliente.append("identificacionCliente", pIdentificacion);
    datosCliente.append("nombreCliente", pNombre);

    $.ajax({
        url: "ajax/cliente.ajax.php",
        method: "POST",
        data: datosCliente,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta: ", respuesta);

            if(respuesta.resultado == "ok"){
                $("#fIdCliente").val(respuesta.idCliente);
            }
            
        }

    });

}


//Generar comprobante electronico
$("#btnRegistrarComprobante").click(function(){

    var tipoDocumento = $("#tipoDocumento").val();

    if(tipoDocumento == ""){
        alert("Seleccione el tipo de comprobante");
        return;
    }

    var identificacionCliente = $("#fIdentificacionCliente").val();
    var tamanioIdentificacion = identificacionCliente.length;

    if(tipoDocumento == "03" && tamanioIdentificacion != 8){
        alert("Debe ingresar un DNI de 8 digitos para Boletas");
        return;
    }

    if(tipoDocumento == "01" && tamanioIdentificacion != 11){
        alert("Debe ingresar un RUC de 11 digitos para Facturas");
        return;
    }

    //Capturamos datos del cliente
    var nombreCliente = $("#clienteNombre").val();
    var fIdentificacionCliente = $("#fIdentificacionCliente").val();
    var tipoIdentificacionCliente = $("#fTipoIdentificacionCliente").val();
    var direccionCliente = "-";

    //Capturamos datos de la venta
    var idCliente = $("#fIdCliente").val();
    var idVenta = $("#fIdVenta").val();
    var fechaVenta = $("#fechaVenta").val();
    var horaActual = devolverHoraActual(); //obtenemos la hora actual desde la funcion "devolverHoraActual()" de plantilla.js
    var ventaBase = $("#fVentaBase").val();
    var ventaIGV = $("#fVentaIGV").val();

    //Obtenemos la lista de productos (detalle)
    var listaProductos = [];
    
    $("#tbodyProductosVentaComprobante tr").each(function(){

        var nombreProducto = $(this).find("td").eq(2).text();
        var baseSubtotal = $(this).find("input[name='subtotalBase']").val();

        listaProductos.push(
            {
				'nombreProducto': nombreProducto,
                'baseSubtotal': baseSubtotal
            }
        );

    });
    

    //Definimos toddos los datos del comprobante
    var datosComprobante = {
        "accion": "generarComprobante",

        "nombreCliente": nombreCliente,
        "identificacionCliente": fIdentificacionCliente,
        "tipoIdentificacionCliente": tipoIdentificacionCliente,
        "direccionCliente": direccionCliente,

        "idVenta": idVenta,
        "idCliente": idCliente,
        "tipoDocumento": tipoDocumento,
        "fechaVenta": fechaVenta,
        "horaVenta": horaActual,
        "ventaBase": ventaBase,
        "ventaIGV": ventaIGV,

        "listaProductos": listaProductos
    };    

    //Generacion de comprobante
    $.ajax({
        url: "ajax/comprobantesunat.ajax.php",
        method: "POST",
        data: datosComprobante,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            if(respuesta.data.respuesta_sunat_codigo == "0"){

                Swal.fire({
                    title: "Comprobante generado correctamente",
                    icon: "success",
                    allowOutsideClick: false,
                    confirmButtonText: "Ok"
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "inicio";
                    }
                    
                });

            }else{

                alert("Error al generar comprobante");

            }

        }

    });
    
});