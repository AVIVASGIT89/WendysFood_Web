//Verificar que el usuario ingresado no exista en BD
$('#nuevoUsuario').blur(function(){

    var usuario = $("#nuevoUsuario").val();
    
    if(usuario == ""){

        $("#nuevoUsuario").removeClass("is-valid");
        $("#nuevoUsuario").removeClass("is-invalid");
        $("#spExisteUsuario").text("");
        $('#btnRegistrar').attr('disabled', false);
        return;
        
    }

    var datos = new FormData();
    datos.append("accion", "verificarUsuario");
    datos.append("usuario", usuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            console.log("respuesta:", respuesta);

            if(respuesta){

                $("#spExisteUsuario").text("El usuario ya ha sido registrado");
                $('#btnRegistrar').attr('disabled', true);
                $("#nuevoUsuario").removeClass("is-valid");
                $("#nuevoUsuario").addClass("is-invalid");
                $("#nuevoUsuario").focus()

            }else{

                $("#spExisteUsuario").text("");
                $("#nuevoUsuario").removeClass("is-invalid");
                $("#nuevoUsuario").addClass("is-valid");
                $('#btnRegistrar').attr('disabled', false);

            }

        }

    });

});


//Mostrar modal y datos de Usuario para editar
$(document).on("click", ".btnEditar", function(){

    var idUsuario = $(this).attr("idUsuario");

    //alert(idUsuario);

    var datos = new FormData();
    datos.append("accion", "editarUsuario");
    datos.append("idUsuario", idUsuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){

            //console.log("respuesta:", respuesta);

            $("#nombreUsuarioEditar").val(respuesta["NOMBRE_USUARIO"]);
            $("#apellidoUsuarioEditar").val(respuesta["APELLIDO_USUARIO"]);
            $("#usuarioEditar").val(respuesta["USUARIO"]);
            $("#claveUsuarioEditar").val(respuesta["CLAVE"]);
            $("#rolUsuarioEditar").val(respuesta["ROL_USUARIO"]).change();
            $("#idUsuarioEditar").val(respuesta["ID_USUARIO"]);
            
            $("#modal-editar-usuario").modal("show");

        }

    });
    
});


//Desactivar usuario
$(document).on("click", ".btnDesactivar", function(){

    var idUsuario = $(this).attr("idUsuario");

    var datos = new FormData();
    datos.append("accion", "desactivarUsuario");
    datos.append("idUsuario", idUsuario);

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Desactivar el usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/usuarios.ajax.php",
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
                            title: "El usuario ha sido desactivado correctamente",
                            icon: "success",
                            allowOutsideClick: false,
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                            
                        })
        
                    }
        
                }
        
            });

        }
    });

});


//Activar usuario
$(document).on("click", ".btnActivar", function(){

    var idUsuario = $(this).attr("idUsuario");

    var datos = new FormData();
    datos.append("accion", "activarUsuario");
    datos.append("idUsuario", idUsuario);

    Swal.fire({
        title: 'Alerta!!',
        text: "Esta seguro que desea Activar el usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Confirmar'
      }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/usuarios.ajax.php",
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
                            title: "El usuario ha sido activado correctamente",
                            allowOutsideClick: false,
                            icon: "success",
                            confirmButtonText: "Ok"
                        }).then((result) => {
        
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                            
                        })
        
                    }
        
                }
        
            });

        }
    });

});


//Funcion para permitir el ingreso solo de numero y letras sin Ñ-ñ
function numletras(e) { // 1
	tecla = (document.all) ? e.keyCode : e.which; // 2
	if (tecla==8) return true; // 3
	patron =/^([a-z]|[A-Z]|[0-9])+$/; // 4  - patron =/^([a-z]|[A-Z]|[0-9]|-|\s|ñ)+$/;
	te = String.fromCharCode(tecla); // 5
	return patron.test(te); // 6
}