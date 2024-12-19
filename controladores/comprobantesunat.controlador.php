<?php

class ControladorComprobanteSunat{

    //Entregar datos sunat cliente
    static public function ctrDatosSunat(){

        $respuesta = ModeloComprobanteSunat::mdlDatosSunat();

        return $respuesta;

    }


    //Devolver el numero para boleta electronica sunat
    static public function ctrNumeroBoletaSunat(){

        $respuesta = ModeloComprobanteSunat::mdlNumeroBoletaSunat();

        return $respuesta;

    }


    //Devolver el numero para factura electronica sunat
    static public function ctrNumeroFacturaSunat(){

        $respuesta = ModeloComprobanteSunat::mdlNumeroFacturaSunat();

        return $respuesta;

    }


    //Generar comprobante sunat
    static public function ctrGenerarComprobanteSunat($datosVenta){

        $respuesta = ModeloComprobanteSunat::mdlGenerarComprobanteSunat($datosVenta);

        return $respuesta;

    }


    //Actualizar la serie y numero sunta de la venta
    static public function ctrActualizarVentaSunat($datosVentaSunat){

        ModeloComprobanteSunat::mdlActualizarVentaSunat($datosVentaSunat);

    }

}