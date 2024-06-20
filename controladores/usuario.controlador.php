<?php

class ControladorUsuario{

    //Validad acceso usaurio
    public function ctrValidarUsuario(){

        if(isset($_POST["usuario"])){

            $datosUsuario = array(
                                    "USUARIO" => $_POST["usuario"],
                                    "CLAVE" => $_POST["clave"]
                                 );
            
            $respuesta = ModeloUsuario::mdlValidarUsuario($datosUsuario);

            if($respuesta){

                if($respuesta["ESTADO_REGISTRO"] == "1"){
                
                    $_SESSION["sSesionUsuario"] = "ok";
                    $_SESSION["sIdUsuario"] = $respuesta["ID_USUARIO"];
                    $_SESSION["sUsuario"] = $respuesta["USUARIO"];
                    $_SESSION["sNombreUsuario"] = $respuesta["NOMBRE_USUARIO"];
                    $_SESSION["sRolUsuario"] = $respuesta["ROL_USUARIO"];

                    //Cargamos en session la lista de tipo pago
                    cargarParametrosSession();

                    echo '<script>
                            window.location = "inicio";
                          </script>';
                    
                }

            }else{

                echo '<br> <div class="alert alert-danger alert-dismissible">Error en validacion</div>';

            }

        }

    }


    //Listar usuarios
    static public function ctrListarUsuarios($item, $valor){

        $respuesta = ModeloUsuario::mdlListarUsuarios($item, $valor);

        return $respuesta;

    }


    //Listar roles usuario
    static public function ctrListarRolesUsuario($item, $valor){

        $respuesta = ModeloUsuario::mdlListarRolesUsuario($item, $valor);

        return $respuesta;

    }


    //Registrar nuevo usuario
    static public function ctrRegistrarUsuario(){

        if(isset($_POST["nuevoUsuario"])){

            $datos = array(
                "nombreUsuario" => $_POST["nombreUsuario"],
                "apellidoUsuario" => $_POST["apellidoUsuario"],
                "nuevoUsuario" => $_POST["nuevoUsuario"],
                "claveUsuario" => $_POST["claveUsuario"],
                "rolUsuario" => $_POST["rolUsuario"]
               );

            $respuesta = ModeloUsuario::mdlRegistrarUsuario($datos);

            if($respuesta == "ok"){

                echo '<script>
                        Swal.fire({
                            title: "Usuario registrado correctamente",
                            icon: "success",
                            confirmButtonText: "Ok"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                        })
                      </script>';

            }else{

                echo '<script>
                        alert("Error");
                      </script>';

            }

        }

    }


    //Editar usuario
    static public function ctrEditarUsuario(){

        if(isset($_POST["nombreUsuarioEditar"])){

            $datos = array(
                "nombreUsuarioEditar" => $_POST["nombreUsuarioEditar"],
                "apellidoUsuarioEditar" => $_POST["apellidoUsuarioEditar"],
                "claveUsuarioEditar" => $_POST["claveUsuarioEditar"],
                "rolUsuarioEditar" => $_POST["rolUsuarioEditar"],
                "idUsuarioEditar" => $_POST["idUsuarioEditar"]
               );

            $respuesta = ModeloUsuario::mdlEditarUsuario($datos);

            if($respuesta == "ok"){

                echo '<script>
                        Swal.fire({
                            title: "Usuario actualizado correctamente",
                            icon: "success",
                            confirmButtonText: "Ok"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                        })
                      </script>';

            }else{

                echo '<script>
                        alert("Error");
                      </script>';

            }

        }

    }


    //Desactivar usuario
    static public function ctrDesactivarUsuario($idUsuario){

        $respuesta = ModeloUsuario::mdlDesactivarUsuario($idUsuario);

        return $respuesta;
        
    }


    //Activar usuario
    static public function ctrActivarUsuario($idUsuario){

        $respuesta = ModeloUsuario::mdlActivarUsuario($idUsuario);

        return $respuesta;
        
    }

}


//Cargar parametros en session
function cargarParametrosSession(){

    $listaTipoPago = ControladorPago::ctrListarTipoPago();  //Cargar lista de tipo pago

    $_SESSION["sListaTipoPago"] = $listaTipoPago;

}