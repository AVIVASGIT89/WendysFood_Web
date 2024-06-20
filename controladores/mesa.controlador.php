<?php

class ControladorMesa{

    //Ocupar mesa
    static public function ctrOcuparMesa($datosMesa){

        $respuesta = ModeloMesa::mdlOcuparMesa($datosMesa);

        return $respuesta;

    }


    //Desocupar mesa
    static public function ctrDesocuparMesa($idMesa){

        $respuesta = ModeloMesa::mdlDesocuparMesa($idMesa);

        return $respuesta;

    }


    //Desocupar mesa
    static public function ctrActualuzarTotalConsumoMesa($datosVenta){

        $respuesta = ModeloMesa::mdlActualuzarTotalConsumoMesa($datosVenta);

        return $respuesta;

    }
}