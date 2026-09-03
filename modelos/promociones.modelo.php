<?php

require_once "conexion.php";

class ModeloPromociones{

    //Listar promociones
    static public function mdlMostrarPromociones($item, $valor){

        if($item == null){

            $stmt = Conexion::conectar()->prepare("SELECT * FROM promociones");

            $stmt -> execute();

            return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT * FROM promociones WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();    //Devolvemos el registro encontrado

        }

        $stmt -> close();

        $stmt = null;

    }


    static public function mdlRegistrarPromocion($datosPromocion){

        $stmt = Conexion::conectar()->prepare("INSERT INTO promociones(NOMBRE_PROMOCION, DESCRIPCION_PROMOCION, URL_IMAGEN) 
                                                VALUES (:NOMBRE_PROMOCION, :DESCRIPCION_PROMOCION, :URL_IMAGEN)");

        $stmt->bindParam(":NOMBRE_PROMOCION", $datosPromocion["NOMBRE_PROMOCION"], PDO::PARAM_STR);
        $stmt->bindParam(":DESCRIPCION_PROMOCION", $datosPromocion["DESCRIPCION_PROMOCION"], PDO::PARAM_STR);
        $stmt->bindParam(":URL_IMAGEN", $datosPromocion["URL_IMAGEN"], PDO::PARAM_STR);


        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();
        $stmt = null;

    }


    static public function mdlActualizarEstadoPromocion($idPromocion, $estadoRegistro){

        $stmt = Conexion::conectar()->prepare("UPDATE promociones SET ESTADO_REGISTRO = :ESTADO_REGISTRO WHERE ID_PROMOCION = :ID_PROMOCION");

        $stmt->bindParam(":ESTADO_REGISTRO", $estadoRegistro, PDO::PARAM_STR);
        $stmt->bindParam(":ID_PROMOCION", $idPromocion, PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();
        $stmt = null;

    }

}