<?php
//Controladores
require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuario.controlador.php";
require_once "controladores/venta.controlador.php";
require_once "controladores/pago.controlador.php";
require_once "controladores/producto.controlador.php";
require_once "controladores/cliente.controlador.php";


//Modelos
require_once "modelos/usuario.modelo.php";
require_once "modelos/venta.modelo.php";
require_once "modelos/pago.modelo.php";
require_once "modelos/producto.modelo.php";
require_once "modelos/cliente.modelo.php";


session_start();

$plantilla = new ControladorPlantilla();

if(isset($_SESSION["sSesionUsuario"]) && $_SESSION["sSesionUsuario"] == "ok" && isset($_GET["ruta"])){

    $plantilla -> ctrPlantilla();

}else{

    $plantilla -> ctrFrmLogin();

}