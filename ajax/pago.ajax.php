<?php

require_once "../controladores/pago.controlador.php";

require_once "../modelos/pago.modelo.php";

$accion = $_POST["accion"];

if($accion == "anularPago"){

    $idPago = $_POST["idPago"];

    $respuesta = ControladorPago::ctrAnularPago($idPago);

    echo json_encode($respuesta);

}