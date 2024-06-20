<?php

class ControladorProducto{

    //Mostrar producto
    static public function ctrMostrarProducto($item, $valor){

        $respuesta = ModeloProducto::mdlMostrarProducto($item, $valor);

        return $respuesta;

    }


    //Registrar Producto
    static public function ctrRegistrarProducto(){

        if(isset($_POST["nuevoCodigo"])){

            $datosProducto = array(
                                "nuevoCodigo" => $_POST["nuevoCodigo"],
                                "nombreProducto" => $_POST["nombreProducto"],
                                "precioUnitario" => $_POST["precioUnitario"],
                                "tipoCocina" => $_POST["tipoCocina"],
                                "tipoPrecio" => $_POST["tipoPrecio"]
                              );

            $respuesta = ModeloProducto::mdlRegistrarProducto($datosProducto);

            if($respuesta == "ok"){

                echo '<script>

                            Swal.fire({
                                title: "Producto registrado correctamente",
                                icon: "success",
                                confirmButtonText: "Ok"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "productos";
                                }
                            })

                    </script>';

            }else{

                echo '<script>
                            alert("Error en registro");
                    </script>';

            }

        }

    }


    //Mostrar producto
    static public function ctrActualizarProducto($datosProducto){

        $respuesta = ModeloProducto::mdlActualizarProducto($datosProducto);

        return $respuesta;

    }

}