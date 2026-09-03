<?php

class ControladorPromociones{

    //Listar promociones
    static public function ctrMostrarPromociones($item, $valor){

        $respuesta = ModeloPromociones::mdlMostrarPromociones($item, $valor);

        return $respuesta;

    }

    //Registrar promocion
    static public function ctrRegistrarPromocion(){

        if(isset($_POST["nuevaPromocion"])){

            $datosPromocion = array(
                "NOMBRE_PROMOCION" => $_POST["nuevaPromocion"],
                "DESCRIPCION_PROMOCION" => $_POST["detallePromocion"],
                "URL_IMAGEN" => $_POST["urlImagen"]
            );

            $respuesta = ModeloPromociones::mdlRegistrarPromocion($datosPromocion);

            if($respuesta == "ok"){

                echo '<script>

                    Swal.fire({
                        title: "La promocion ha sido registrada correctamente!",
                        icon: "success",
                        allowOutsideClick: false,
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "promociones";
                        }
                    });

                </script>';

            }else{

                echo '<script>

                    Swal.fire({
                        title: "Error al registrar la promocion!",
                        icon: "error",
                        allowOutsideClick: false,
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "promociones";
                        }
                    });

                </script>';

            }

        }

    }

    //Desactivar promocion
    static public function ctrDesactivarPromocion($idPromocion){

        $respuesta = ModeloPromociones::mdlActualizarEstadoPromocion($idPromocion, "0");

        return $respuesta;

    }

    //Activar promocion
    static public function ctrActivarPromocion($idPromocion){

        $respuesta = ModeloPromociones::mdlActualizarEstadoPromocion($idPromocion, "1");

        return $respuesta;

    }

}