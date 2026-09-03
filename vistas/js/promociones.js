//Ver imagen de promocion
$(document).on("click", ".verImagenPromocion", function(){

    var urlImagen = $(this).attr("urlImagen");

    $('#imagenModal').attr('src', urlImagen);

    $("#modal-imagen-promocion").modal('show');
    
});


//Desactivar promocion
$(document).on("click", ".desactivarPromocion", function(){

    var idPromocion = $(this).attr("idPromocion");

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea desactivar la promocion?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
    }).then((result) => {
        if (result.isConfirmed) {

            var datos = new FormData();
            datos.append("accion", "desactivarPromocion");
            datos.append("idPromocion", idPromocion);

            $.ajax({
                url: "ajax/promociones.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){

                    if(respuesta == "ok"){

                        Swal.fire({
                            title: "Promocion desactivada correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {

                            if (result.isConfirmed) {
                                window.location = "promociones";
                            }

                        });

                    }

                }

            });

        }
    });

});


//Activar promocion
$(document).on("click", ".activarPromocion", function(){

    var idPromocion = $(this).attr("idPromocion");

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea activar la promocion?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
    }).then((result) => {
        if (result.isConfirmed) {

            var datos = new FormData();
            datos.append("accion", "activarPromocion");
            datos.append("idPromocion", idPromocion);

            $.ajax({
                url: "ajax/promociones.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){

                    if(respuesta == "ok"){

                        Swal.fire({
                            title: "Promocion activada correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {

                            if (result.isConfirmed) {
                                window.location = "promociones";
                            }

                        });

                    }

                }

            });

        }
    });

});