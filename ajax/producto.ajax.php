<?php
require_once "../controladores/producto.controlador.php";

require_once "../modelos/producto.modelo.php";

$accion = $_POST["accion"];

if($accion == "buscarProducto"){

    $codigoProducto = $_POST["codigoProducto"];

    $item = "CODIGO_PRODUCTO";
    $valor = $codigoProducto;

    $respuesta = ModeloProducto::mdlMostrarProducto($item, $valor);

    echo json_encode($respuesta);

}
else
if($accion == "buscarProductoId"){

    $idProducto = $_POST["idProducto"];

    $item = "ID_PRODUCTO";
    $valor = $idProducto;

    $respuesta = ModeloProducto::mdlMostrarProducto($item, $valor);

    echo json_encode($respuesta);

}
else
if($accion == "actualizarProducto"){

    $datosProducto = array(
        "idProducto" => $_POST["idProducto"],
        "codigoProducto" => $_POST["codigoProducto"],
        "nombreProducto" => $_POST["nombreProducto"],
        "precioUnitario" => $_POST["precioUnitario"],
        "tipoCocina" => $_POST["tipoCocina"],
        "tipoPrecio" => $_POST["tipoPrecio"]
    );

    $respuesta = ControladorProducto::ctrActualizarProducto($datosProducto);

    echo json_encode($respuesta);

}