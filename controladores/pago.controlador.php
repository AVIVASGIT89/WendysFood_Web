<?php

class ControladorPago{

    //Registrar pago
    static public function ctrRegistrarPago(){

        if(isset($_POST["fechaPago"])){

            $datosPago = array(
                                "fecha" => $_POST["fechaPago"],
                                "tipoPago" => $_POST["tipoPago"],
                                "concepto" => $_POST["conceptoPago"],
                                "monto" => $_POST["montoPago"]
                              );

            $respuesta = ModeloPago::mdlRegistrarPago($datosPago);

            if($respuesta == "ok"){

                echo '<script>

                            Swal.fire({
                                title: "Pago registrado correctamente",
                                icon: "success",
                                confirmButtonText: "Ok"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "pagos";
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


    //Reporte pagos
    static public function ctrReportePagos(){

        if(isset($_POST["fechaDesde"])){

            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta= $_POST["fechaHasta"];

            $respuesta = ModeloPago::mdlReportePagos($fechaDesde, $fechaHasta);

            return $respuesta;

        }

    }


    //Listar tipo pago
    static public function ctrListarTipoPago(){

        $respuesta = ModeloPago::mdlListarTipoPago();

        return $respuesta;

    }

    
    //Anular pago
    static public function ctrAnularPago($idPago){

        $respuesta = ModeloPago::mdlAnularPago($idPago);

        return $respuesta;

    }

}