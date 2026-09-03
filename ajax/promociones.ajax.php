<?php

require_once "../controladores/promociones.controlador.php";
require_once "../modelos/promociones.modelo.php";

$accion = $_POST["accion"];

if($accion == "desactivarPromocion"){

    $idPromocion = $_POST["idPromocion"];

    $respuesta = ControladorPromociones::ctrDesactivarPromocion($idPromocion);

    echo json_encode($respuesta);

}
else
if($accion == "activarPromocion"){

    $idPromocion = $_POST["idPromocion"];

    $respuesta = ControladorPromociones::ctrActivarPromocion($idPromocion);

    echo json_encode($respuesta);

}

