<?php

class ControladorCliente{

    //Listar cliente
    static public function ctrListarCliente($item, $valor){

        $respuesta = ModeloCliente::mdlListarClientes($item, $valor);

        return $respuesta;

    }


    //Buscar cliente SUNAT
    static public function ctrBuscarIdentificacionSunat($identificacion){

        $respuesta = ModeloCliente::mldBuscarIdentificacionSunat($identificacion);

        return $respuesta;

    }


    //Registrar cliente
    static public function ctrRegistrarCiente($datosCliente){

        $respuesta = ModeloCliente::mdlRegistrarCiente($datosCliente);

        return $respuesta;

    }

}