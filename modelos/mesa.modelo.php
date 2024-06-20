<?php

require_once "conexion.php";

class ModeloMesa{

    //Ocupar mesa
    static public function mdlOcuparMesa($datosMesa){

        $stmt = Conexion::conectar()->prepare("UPDATE mesa SET ESTADO_MESA = 2,
                                                               ID_VENTA_OCUPACION = :ID_VENTA_OCUPACION,
                                                               TOTAL_CONSUMO = :TOTAL_CONSUMO,
                                                               ULTIMA_OCUPACION = NOW()
                                                        WHERE ID_MESA = :ID_MESA");

        $stmt -> bindParam(":ID_VENTA_OCUPACION", $datosMesa["idVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":TOTAL_CONSUMO", $datosMesa["totalConsumo"], PDO::PARAM_STR);
        $stmt -> bindParam(":ID_MESA", $datosMesa["idMesa"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }


    //Desocupar mesa
    static public function mdlDesocuparMesa($idMesa){

        $stmt = Conexion::conectar()->prepare("UPDATE mesa SET ESTADO_MESA = 1,
                                                               ID_VENTA_OCUPACION = NULL,
                                                               TOTAL_CONSUMO = 0
                                                        WHERE ID_MESA = :ID_MESA");

        $stmt -> bindParam(":ID_MESA", $idMesa, PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }


    //Actualizar total consumo mesa
    static public function mdlActualuzarTotalConsumoMesa($datosVenta){

        $stmt = Conexion::conectar()->prepare("UPDATE mesa SET TOTAL_CONSUMO = :TOTAL_CONSUMO
                                                           WHERE ID_MESA = :ID_MESA");

        $stmt -> bindParam(":TOTAL_CONSUMO", $datosVenta["totalVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":ID_MESA", $datosVenta["idMesa"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }

}