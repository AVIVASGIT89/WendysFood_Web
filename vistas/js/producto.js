//Verificar que el usuario ingresado no exista en BD
$('#nuevoCodigo').blur(function(){

    var codigoProducto = $("#nuevoCodigo").val();

    //alert(usuario); return;
    
    if(codigoProducto == ""){

        $("#nuevoCodigo").removeClass("is-valid");
        $("#nuevoCodigo").removeClass("is-invalid");
        $("#spExisteCodigo").text("");
        $('#btnRegistrarProducto').attr('disabled', false);
        return;
        
    }

    var datos = new FormData();
    datos.append("accion", "buscarProducto");
    datos.append("codigoProducto", codigoProducto);

    $.ajax({
        url: "ajax/producto.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            console.log("respuesta:", respuesta);

            if(respuesta){

                $("#spExisteCodigo").text("El codigo ya ha sido registrado");
                $('#btnRegistrarProducto').attr('disabled', true);
                $("#nuevoCodigo").removeClass("is-valid");
                $("#nuevoCodigo").addClass("is-invalid");
                $("#nuevoCodigo").focus()

            }else{

                $("#spExisteCodigo").text("");
                $("#nuevoCodigo").removeClass("is-invalid");
                $("#nuevoCodigo").addClass("is-valid");
                $('#btnRegistrarProducto').attr('disabled', false);

            }

        }

    });

});


//Inicio para editar producto
$(document).on("click", ".editarProducto", function(){

    var idProducto = $(this).attr("idProducto");

    var datos = new FormData();
    datos.append("accion", "buscarProductoId");
    datos.append("idProducto", idProducto);


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

            $("#editarCodigo").val(respuesta.CODIGO_PRODUCTO);
            $("#editarProducto").val(respuesta.NOMBRE_PRODUCTO);
            $("#editarPrecioUnitario").val(respuesta.PRECIO_UNITARIO);
            $("#editarTipoCocina").val(respuesta.TIPO_COCINA);
            $("#editarTipoPrecio").val(respuesta.TIPO_PRECIO);
            $("#idProducto").val(respuesta.ID_PRODUCTO);

            //Mostramos modal "modalRegistrarOrden"
            $("#modal-editar-producto").modal('show');

        }

    });
    
});


//Actualizar producto
$("#btnEditarProducto").click(function(){

    //alert("llega"); return;

    var idProducto = $("#idProducto").val();
    var editarCodigo = $("#editarCodigo").val();
    var editarProducto = $("#editarProducto").val();
    var editarPrecioUnitario = $("#editarPrecioUnitario").val();
    var editarTipoCocina = $("#editarTipoCocina").val();
    var editarTipoPrecio = $("#editarTipoPrecio").val();

    var datosProducto = new FormData();
    datosProducto.append("accion", "actualizarProducto");
    datosProducto.append("idProducto", idProducto);
    datosProducto.append("codigoProducto", editarCodigo);
    datosProducto.append("nombreProducto", editarProducto);
    datosProducto.append("precioUnitario", editarPrecioUnitario);
    datosProducto.append("tipoCocina", editarTipoCocina);
    datosProducto.append("tipoPrecio", editarTipoPrecio);

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Actualizar el producto?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/producto.ajax.php",
                method: "POST",
                data: datosProducto,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){
        
                    //console.log("respuesta:", respuesta);
        
                    if(respuesta == "ok"){
        
                        Swal.fire({
                            title: "Producto actualizado correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "productos";
                            }
                            
                        });
        
                    }
        
                }
        
            });

        }
    });

});