<?php

require_once "../controladores/venta.controlador.php";

require_once "../modelos/venta.modelo.php";

$accion = $_POST["accion"];

if($accion == "registroVentas"){

    $filtros = array(
            "fechaDesde" => $_POST["fechaDesde"],
            "fechaHasta" => $_POST["fechaHasta"],
            "tipoVenta" => $_POST["tipoVenta"],
            "estadoVenta" => $_POST["estadoVenta"]
    );

    $respuesta = ControladorVenta::ctrReporteRegistroVenta($filtros);

    echo json_encode($respuesta);

}
else
if($accion == "productosVenta"){

    $idVenta = $_POST["idVenta"];

    $respuesta = ControladorVenta::ctrProductosVenta($idVenta);

    echo json_encode($respuesta);

}
else
if($accion == "ventasSunat"){

    $filtros = array(
            "fechaDesde" => $_POST["fechaDesde"],
            "fechaHasta" => $_POST["fechaHasta"],
            "tipoComprobante" => $_POST["tipoComprobante"],
            "estadoEnvio" => $_POST["estadoEnvio"]
    );

    $respuesta = ControladorVenta::ctrReporteVentasSunat($filtros);

    echo json_encode($respuesta);

}