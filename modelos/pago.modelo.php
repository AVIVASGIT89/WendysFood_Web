<?php

require_once "conexion.php";

class ModeloPago{

    //Registrar pago
    static public function mdlRegistrarPago($datosPago){

        $stmt = Conexion::conectar()->prepare("INSERT INTO pago (ID_TIPO_PAGO,
                                                                 FECHA_PAGO,
                                                                 CONCEPTO_PAGO,
                                                                 MONTO_PAGO,
                                                                 USUARIO_REGISTRO,
                                                                 FECHA_REGISTRO)
                                                          VALUES(:ID_TIPO_PAGO,
                                                                 :FECHA_PAGO,
                                                                 :CONCEPTO_PAGO,
                                                                 :MONTO_PAGO,
                                                                 :USUARIO_REGISTRO, 
                                                                  NOW())");

        $stmt -> bindParam(":ID_TIPO_PAGO", $datosPago["tipoPago"], PDO::PARAM_STR);
        $stmt -> bindParam(":FECHA_PAGO", $datosPago["fecha"], PDO::PARAM_STR);
        $stmt -> bindParam(":CONCEPTO_PAGO", $datosPago["concepto"], PDO::PARAM_STR);
        $stmt -> bindParam(":MONTO_PAGO", $datosPago["monto"], PDO::PARAM_STR);
        $stmt -> bindParam(":USUARIO_REGISTRO", $_SESSION["sUsuario"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }


    //Reporte pagos
    static public function mdlReportePagos($desde, $hasta){

        $stmt = Conexion::conectar()->prepare("SELECT P.ID_PAGO,
                                                        P.FECHA_PAGO,
                                                        P.FECHA_REGISTRO,
                                                        P.CONCEPTO_PAGO,
                                                        P.MONTO_PAGO,
                                                        P.USUARIO_REGISTRO
                                                FROM pago P
                                                WHERE P.FECHA_PAGO BETWEEN '$desde' AND '$hasta'
                                                AND P.ESTADO_REGISTRO = 1
                                                ORDER BY P.FECHA_PAGO DESC");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt -> close();

        $stmt = null;

    }

    
    //Listar tipo pagos
    static public function mdlListarTipoPago(){

        $stmt = Conexion::conectar()->prepare("SELECT P.ID_TIPO_PAGO, P.NOMBRE_TIPO_PAGO
                                                FROM pago_tipo P
                                                WHERE P.ESTADO_REGISTRO = 1");
    
        $stmt -> execute();
    
        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados
    
        $stmt -> close();
    
        $stmt = null;
    
    }


    //Anular pago
    static public function mdlAnularPago($idPago){

        $stmt = Conexion::conectar()->prepare("UPDATE pago
                                               SET ESTADO_REGISTRO = 0
                                               WHERE ID_PAGO = :ID_PAGO");

        $stmt -> bindParam(":ID_PAGO", $idPago, PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }

}