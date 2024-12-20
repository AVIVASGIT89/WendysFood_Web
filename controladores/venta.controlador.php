<?php

class ControladorVenta{

    //Listar mesas
    static public function ctrListarMesas(){

        $respuesta = ModeloVenta::mdlListarMesas();

        return $respuesta;

    }


    //Listar deliverys de fecha actual (hoy)
    static public function ctrListarDeliveryHoy(){

        $respuesta = ModeloVenta::mdlListarDeliveryHoy();

        return $respuesta;

    }


    //Registrar nueva venta
    static public function ctrRegistrarVenta($datosVenta){

        $respuesta = ModeloVenta::mdlRegistrarVenta($datosVenta);

        return $respuesta;

    }


    //Registrar detalle venta
    static public function ctrRegistrarDetalleVenta($datosDetalleVenta){

        $respuesta = ModeloVenta::mdlRegistrarDetalleVenta($datosDetalleVenta);

        return $respuesta;

    }


    //Mostrar detalle venta
    static public function ctrMostrarDetalleVenta($idVenta){

        $respuesta = ModeloVenta::mdlMostrarDetalleVenta($idVenta);

        return $respuesta;

    }


    //Finalizar venta
    static public function ctrFinalizarVenta($idVenta, $formaPago){

        $respuesta = ModeloVenta::mdlFinalizarVenta($idVenta, $formaPago);

        return $respuesta;

    }

    
    //Cancelar venta
    static public function ctrCancelarVenta($idVenta){

        $respuesta = ModeloVenta::mdlCancelarVenta($idVenta);

        return $respuesta;

    }

    //Actualizar venta
    static public function ctrActualizarVenta($datosVenta){

        $respuestaVenta = ModeloVenta::mdlActualizarVenta($datosVenta);

        return $respuestaVenta;

    }

    //Anular detalle venta
    static public function ctrAnularDetalleVenta($idVenta){

        $respuesta = ModeloVenta::mdlAnularDetalleVenta($idVenta);

        return $respuesta;

    }


    //Anular detalle venta
    static public function ctrReporteResumenVenta(){

        if(isset($_POST["fechaDesde"])){

            $fechaDesde = $_POST["fechaDesde"];
            $fechaHasta= $_POST["fechaHasta"];

            $respuesta = ModeloVenta::mdlReporteResumenVenta($fechaDesde, $fechaHasta);

            return $respuesta;

        }

    }


    //Reporte registros venta
    static public function ctrReporteRegistroVenta($filtros){

        $respuesta = ModeloVenta::mdlReporteRegistroVenta($filtros);

        return $respuesta;

    }


    //Reporte registros venta
    static public function ctrProductosVenta($idVenta){

        $respuesta = ModeloVenta::mdlProductosVenta($idVenta);

        return $respuesta;

    }


    //Enviar mail de alerta por anulacion de orden
    static public function ctrEnviarMailAnulacionOrden($datosMail){

        $respuesta = ModeloVenta::mdlEnviarMailAnulacionOrden($datosMail);

        return $respuesta;

    }


    //Reporte ventas sunat
    static public function ctrReporteVentasSunat($filtros){

        $respuesta = ModeloVenta::mdlReporteVentasSunat($filtros);

        return $respuesta;

    }

}