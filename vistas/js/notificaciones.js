
//Registro de ultimas notificaciones enviadas
function registroNotificaciones(){

    $.ajax({
        url: ambienteEjecucion + "/apiwendysfood/notificaciones/consulta-notificaciones",
        method: "GET",
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta); return;

            $("#tablaNotificaciones").DataTable().destroy();

            var i = 0;
            var tipoColor;
            
            var tbody = $("#tbodyRegistroNotificaciones");
			tbody.empty();

            respuesta.notificaciones.forEach(function(notificacion){

                i++;

                if(notificacion.TIPO == "1"){
                    tipoColor = "badge badge-warning";
                }else{
                    tipoColor = "badge badge-info";
                }

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
					  '<td align="center">'+notificacion.FECHA_NOTIFICACION+'</td>'+
                      '<td align="center"><span class="'+tipoColor+'">'+notificacion.TIPO_NOTIFICACION+'</span></td>'+
					  '<td align="center">'+notificacion.NRO_NOTIFICACIONES+'</td>'+
                      '<td align="center">'+notificacion.USUARIO_ENVIO+'</td>'+
					'</tr>'
				);

			});

            crearDataTable("#tablaNotificaciones");

        }

    });

}


//Registro de clientes
function registroClientes(){

    //Ejecuta el efecto cargando...
	var screen = $('#loading-screen');
	configureLoadingScreen(screen);

    $.ajax({
        url: ambienteEjecucion + "/apiwendysfood/cliente/registro-clientes",
        method: "GET",
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta); return;

            $("#tablaClientes").DataTable().destroy();

            var i = 0;
            var puntosColor;
            var msjEnviado;
            var envioColor;
            
            var tbody = $("#tbodyRegistroClientes");
			tbody.empty();

            respuesta.clientes.forEach(function(cliente){

                intPuntosCliente = cliente.PUNTOS_VIGENTES;
                i++;

                if(intPuntosCliente < 100){
                    puntosColor = "badge badge-warning";
                }else{
                    puntosColor = "badge badge-info";
                }

                if(cliente.MSJ_ENVIADO == "1"){
                    msjEnviado = "Enviado";
                    envioColor = "badge badge-success";
                }else{
                    msjEnviado = "No enviado";
                    envioColor = "badge badge-secondary";
                }

				tbody.append( 
					'<tr>'+
					  '<td>'+i+'</td>'+
                      '<td align="center">'+cliente.CODIGO_CLIENTE+'</td>'+
					  '<td align="center">'+cliente.IDENTIFICACION_CLIENTE+'</td>'+
					  '<td>'+cliente.NOMBRE_CLIENTE+'</td>'+
                      '<td align="center"><span class="'+puntosColor+'">'+cliente.PUNTOS_VIGENTES+'</span></td>'+
                      '<td align="center">'+cliente.FECHA_REGISTRO+'</td>'+
                      '<td align="center"><span class="'+envioColor+'">'+msjEnviado+'</span></td>'+
					'</tr>'
				);

			});

            crearDataTable("#tablaClientes");

        }

    });

}


//Procesar y mostrar nro de clientes
$(".procesarClientes").click(function(){

    var tipoNotificacion = $("#tipoNotificacion").val();
    var fechaDesde = $("#fechaDesde").val();
    var fechaHasta = $("#fechaHasta").val();

    if(tipoNotificacion == ""){
        alert("Ingrese tipo de notificacion");
        return;
    }

    var URL;
    var textoTipo;

    if(tipoNotificacion == "1"){

        URL = ambienteEjecucion + "/apiwendysfood/notificaciones/conteoVentasMenor100Puntos/?fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta;
        textoTipo = "Menor a 100 Puntos";
        $("#spTipo").removeClass("badge badge-info");
        $("#spTipo").addClass("badge badge-warning");

    }
    else
    if(tipoNotificacion == "2"){

        URL = ambienteEjecucion + "/apiwendysfood/notificaciones/conteoVentasMayor100Puntos/?fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta;
        textoTipo = "Mayor a 100 Puntos";
        $("#spTipo").removeClass("badge badge-warning");
        $("#spTipo").addClass("badge badge-info");

    }

    //Efecto "Procesando..."
    $("#btnProcesar").prop("disabled", true);
    $("#btnProcesar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');

    $.ajax({
        url: URL,
        method: "GET",
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            $("#spNroClientes").text(respuesta.nroVentas);
            $("#spTipo").text(textoTipo);

            $('#dvDatosNotificacion').show();

            $("#btnProcesar").prop("disabled", false);
            $("#btnProcesar").html('Procesar');

        }

    });
    
});


//Enviar notificacion
$(".enviarNotificacion").click(function(){

    var nroClientes = $("#spNroClientes").text();
    var intNroClientes = parseInt(nroClientes);

    if(intNroClientes <= 0){

        alert("El nro de clientes debe ser mayor a 0");
        return;

    }

    var URL;
    var tipoNotificacion = $("#tipoNotificacion").val();
    var fechaDesde = $("#fechaDesde").val();
    var fechaHasta = $("#fechaHasta").val();

    if(tipoNotificacion == "1"){

        URL = ambienteEjecucion + "/apiwendysfood/notificaciones/enviarNotificacionMenor100Puntos";

    }
    else
    if(tipoNotificacion == "2"){

        URL = ambienteEjecucion + "/apiwendysfood/notificaciones/enviarNotificacionMayor100Puntos";

    }

    //Definimos las fechas
    var datosNotificacion = {
        "fechaDesde": fechaDesde,
        "fechaHasta": fechaHasta
    };

    //Ejecuta el efecto cargando...
	var screen = $('#loading-screen');
	configureLoadingScreen(screen);

    $.ajax({
        url: URL,
        method: "POST",
        data: datosNotificacion,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            var nroNotificaciones;

            if(respuesta.resultado == "ok"){

                nroNotificaciones = respuesta.mensaje;
                alert("Envio correcto, " + nroNotificaciones);

            }else{

                nroNotificaciones = respuesta.mensaje;
                alert("Error en envío de notificaciones");

            }

            window.location = "notificaciones";

        }

    });
    
});


//Resetear campo Msj enviado cliente
$(".resetCliente").click(function(){

    if(confirm("Esta seguro de realizar el reset?")){

        //Ejecuta el efecto cargando...
        var screen = $('#loading-screen');
        configureLoadingScreen(screen);

        $.ajax({
            url: ambienteEjecucion + "/apiwendysfood/notificaciones/resetClienteMsjEnvio",
            method: "POST",
            dataType: "json",
            success: function(respuesta){

                //console.log("respuesta:", respuesta);

                if(respuesta.resultado == "ok"){

                    alert("Reset realizado correctamente");

                }else{

                    alert("Error en reset de clientes");

                }

                window.location = "clientes";

            }

        });

    }
    
});