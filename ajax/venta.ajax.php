<?php

require_once "../controladores/venta.controlador.php";
require_once "../controladores/mesa.controlador.php";

require_once "../modelos/venta.modelo.php";
require_once "../modelos/mesa.modelo.php";

$accion = $_POST["accion"];

if($accion == "registrarVenta"){

    $tipoVenta = $_POST["tipoVenta"];
    $listaProductos = $_POST["listaProductos"];

    $idVenta = null;
    $resultado = null;

    $nroProductos = count($listaProductos);

    $datosVenta = array(
                        "tipoVenta" => $_POST["tipoVenta"],
                        "idMesaVenta" => $_POST["idMesaVenta"],
                        "nroProductos" => $nroProductos,
                        "totalVenta" => $_POST["totalVenta"],
                        "baseVenta" => $_POST["baseVenta"],
                        "igvVenta" => $_POST["igvVenta"],
                        "clienteDelivery" => $_POST["clienteDelivery"]
                    );

    $registroVenta = ControladorVenta::ctrRegistrarVenta($datosVenta);

    if($registroVenta["resultado"] == "ok"){
        
        $idVenta = $registroVenta["idVenta"];

        foreach($listaProductos as $key => $value){

            $datosDetalleVenta = array(
                                    "idVenta" => $idVenta,
                                    "idProducto" => $value["idProducto"],
                                    "precioUnitario" => $value["precioUnitario"],
                                    "cantidad" => $value["cantidad"],
                                    "subTotalBase" => $value["baseSubtotal"],
                                    "subTotalIgv" => $value["igvSubtotal"],
                                    "subTotal" => $value["subtotal"]
                                );

            $registroDetalleVenta = ControladorVenta::ctrRegistrarDetalleVenta($datosDetalleVenta);
            
        }

        $datosMesa = array(
            "idVenta" => $idVenta,
            "totalConsumo" => $_POST["totalVenta"],
            "idMesa" => $_POST["idMesaVenta"]
        );

        //Si la orden es de tipo 1 (mesa), reservamos mesa con datos de venta
        if($tipoVenta == "1"){
            $ocuparMesa = ControladorMesa::ctrOcuparMesa($datosMesa);
        }

        $resultado = array(
                        "resultado" => "ok",
                        "idVenta" => $idVenta
                    );
        
    }else{

        $resultado = array(
                        "resultado" => "error",
                        "idVenta" => ""
                    );

    }

    echo json_encode($resultado);

}
else
if($accion == "mostrarDetalleVenta"){

    $idVenta = $_POST["idVenta"];

    $respuesta = ControladorVenta::ctrMostrarDetalleVenta($idVenta);

    echo json_encode($respuesta);

}
else
if($accion == "cancelarVenta"){

    $idVenta = $_POST["idVenta"];
    $idMesa = $_POST["idMesa"];

    $respuestaVenta = ControladorVenta::ctrCancelarVenta($idVenta);
    
    $respuestaMesa = ControladorMesa::ctrDesocuparMesa($idMesa);

    echo json_encode($respuestaMesa);

}
else
if($accion == "cancelarVentaFromDetalle"){

    $idVenta = $_POST["idVenta"];

    $respuesta = ControladorVenta::ctrCancelarVenta($idVenta);

    echo json_encode($respuesta);

}
else
if($accion == "finalizarVenta"){

    $idVenta = $_POST["idVenta"];
    $idMesa = $_POST["idMesa"];
    $formaPago = $_POST["formaPago"];

    $respuestaVenta = ControladorVenta::ctrFinalizarVenta($idVenta, $formaPago);
    
    $respuestaMesa = ControladorMesa::ctrDesocuparMesa($idMesa);

    echo json_encode($respuestaMesa);

}
else
if($accion == "actualizarVenta"){

    $idVenta = $_POST["idVenta"];
    $listaProductos = $_POST["listaProductos"];

    $nroProductos = count($listaProductos);

    $datosVentaModificar = array(
        "idVenta" => $_POST["idVenta"],
        "idMesa" => $_POST["idMesa"],
        "nroProductos" => $nroProductos,
        "totalVenta" => $_POST["totalVenta"],
        "baseVenta" => $_POST["baseVenta"],
        "igvVenta" => $_POST["igvVenta"]
    );

    $respuestaAnulacion = ControladorVenta::ctrAnularDetalleVenta($idVenta);

    $respuestaVenta = ControladorVenta::ctrActualizarVenta($datosVentaModificar);

    $respuestaMesa = ControladorMesa::ctrActualuzarTotalConsumoMesa($datosVentaModificar);

    foreach($listaProductos as $key => $value){

        $datosDetalleVenta = array(
                                "idVenta" => $idVenta,
                                "idProducto" => $value["idProducto"],
                                "precioUnitario" => $value["precioUnitario"],
                                "cantidad" => $value["cantidad"],
                                "subTotalBase" => $value["baseSubtotal"],
                                "subTotalIgv" => $value["igvSubtotal"],
                                "subTotal" => $value["subtotal"]
                            );

        $registroDetalleVenta = ControladorVenta::ctrRegistrarDetalleVenta($datosDetalleVenta);
        
    }

    echo json_encode($respuestaMesa);

}
else
if($accion == "enviarMailAnulacionOrden"){

    $listaProductos = $_POST["listaProductos"];
    $fechaOrden = $_POST["fechaOrden"];
    $fechaCancelacion = $_POST["fechaCancelacion"];
    $tipoOrden = $_POST["tipoOrden"];

    if($tipoOrden == "Delivery"){

        $tipoOrden = "<b>".$tipoOrden."</b>";

    }else{

        $tipoOrden = "en <b>Mesa ".$tipoOrden."</b>";

    }

    $datosMail = array(
        "listaProductos"=> $listaProductos,
        "fechaOrden"=> $fechaOrden,
        "fechaCancelacion"=> $fechaCancelacion,
        "tipoOrden"=> $tipoOrden
    );

    $respuesta = ControladorVenta::ctrEnviarMailAnulacionOrden($datosMail);

    echo json_encode($respuesta);
    
}