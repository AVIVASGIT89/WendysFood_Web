<?php

require_once "../controladores/cliente.controlador.php";
require_once "../modelos/cliente.modelo.php";

$accion = $_POST["accion"];

if($accion == "buscarClienteIdentificacion"){

    $identificacionCliente = $_POST["identificacionCliente"];

    $item = "IDENTIFICACION_CLIENTE";
    $valor = $identificacionCliente;

    $respuesta = ControladorCliente::ctrListarCliente($item, $valor);

    echo json_encode($respuesta);

}
else
if($accion == "buscarIdentificacionSunat"){

    $identificacionCliente = $_POST["identificacionCliente"];

    $respuesta = ControladorCliente::ctrBuscarIdentificacionSunat($identificacionCliente);

    echo json_encode($respuesta);

}
else
if($accion == "registrarCliente"){

    $datosCliente = array(
        "identificacionCliente" => $_POST["identificacionCliente"],
        "nombreCliente" => $_POST["nombreCliente"]
    );

    //var_dump($datosCliente); die();

    $registroCliente = ControladorCliente::ctrRegistrarCiente($datosCliente);

    if($registroCliente["resultado"] == "ok"){

        $resultado = array(
            "resultado" => "ok",
            "idCliente" => $registroCliente["idCliente"]
        );

    }else{
        $resultado = array(
            "resultado" => "error",
            "idVenta" => ""
        );
    }

    echo json_encode($resultado);

}