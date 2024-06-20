<?php
require_once "../controladores/usuario.controlador.php";
require_once "../modelos/usuario.modelo.php";

$accion = $_POST["accion"];

if($accion == "verificarUsuario"){

    $usuario = $_POST["usuario"];

    $item = "USUARIO";
    $valor = $usuario;

    $respuesta = ControladorUsuario::ctrListarUsuarios($item, $valor);

    echo json_encode($respuesta);

}
else 
if($accion == "editarUsuario"){

    $idUsuario = $_POST["idUsuario"];

    $item = "ID_USUARIO";
    $valor = $idUsuario;

    $respuesta = ControladorUsuario::ctrListarUsuarios($item, $valor);

    echo json_encode($respuesta);

}
else 
if($accion == "desactivarUsuario"){

    $idUsuario = $_POST["idUsuario"];

    $respuesta = ControladorUsuario::ctrDesactivarUsuario($idUsuario);

    echo json_encode($respuesta);

}
else 
if($accion == "activarUsuario"){

    $idUsuario = $_POST["idUsuario"];

    $respuesta = ControladorUsuario::ctrActivarUsuario($idUsuario);

    echo json_encode($respuesta);

}