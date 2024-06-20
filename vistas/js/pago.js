//Anular pago
$(document).on("click", ".anularPago", function(){

    var idPago = $(this).attr("idPago");

    var datos = new FormData();
    datos.append("accion", "anularPago");
    datos.append("idPago", idPago);

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Anular el pago seleccionado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/pago.ajax.php",
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
                            title: "El pago ha sido anulada correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "reporte-pagos";
                            }
                            
                        });
        
                    }
        
                }
        
            });

        }
    });

});
