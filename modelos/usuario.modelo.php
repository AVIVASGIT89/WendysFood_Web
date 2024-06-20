<?php

require_once "conexion.php";

class ModeloUsuario{

    //Metodo para validad usuario
    static public function mdlValidarUsuario($datosUsuario){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuario WHERE USUARIO = :USUARIO AND CLAVE = :CLAVE");

        $stmt -> bindParam(":USUARIO", $datosUsuario["USUARIO"], PDO::PARAM_STR);
        $stmt -> bindParam(":CLAVE", $datosUsuario["CLAVE"], PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch();    //Devolvemos el registro encontrado

        $stmt -> close();

        $stmt = null;

    }


    //Metodo para listar usuarios
    static public function mdlListarUsuarios($item, $valor){

        if($item == null){

            $stmt = Conexion::conectar()->prepare("SELECT U.*, 
                                                          R.NOMBRE_ROL
                                                    FROM usuario U
                                                    INNER JOIN usuario_rol R ON R.ID_ROL = U.ROL_USUARIO
                                                    ORDER BY U.FECHA_REGISTRO");

            $stmt -> execute();

            return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT U.*, 
                                                          R.NOMBRE_ROL
                                                    FROM usuario U
                                                    INNER JOIN usuario_rol R ON R.ID_ROL = U.ROL_USUARIO
                                                    WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();    //Devolvemos el registro encontrado

        }

        $stmt -> close();

        $stmt = null;

    }


    //Metodo para listar roles de usuario
    static public function mdlListarRolesUsuario($item, $valor){

        if($item == null){

            $stmt = Conexion::conectar()->prepare("SELECT * FROM usuario_rol WHERE ESTADO_REGISTRO = 1");

            $stmt -> execute();

            return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT * FROM usuario_rol WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();    //Devolvemos el registro encontrado

        }

        $stmt -> close();

        $stmt = null;

    }


    //Metodo para registrar nuevo usuario
    static public function mdlRegistrarUsuario($datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO usuario (USUARIO,
                                                                    CLAVE,
                                                                    NOMBRE_USUARIO,
                                                                    APELLIDO_USUARIO,
                                                                    ROL_USUARIO,
                                                                    FECHA_REGISTRO)
                                                             VALUES(UPPER(:USUARIO),
                                                                    :CLAVE,
                                                                    NOMPROPIO(:NOMBRE_USUARIO),
                                                                    NOMPROPIO(:APELLIDO_USUARIO), 
                                                                    :ROL_USUARIO,
                                                                    NOW())");

        $stmt -> bindParam(":USUARIO", $datos["nuevoUsuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":CLAVE", $datos["claveUsuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":NOMBRE_USUARIO", $datos["nombreUsuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":APELLIDO_USUARIO", $datos["apellidoUsuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":ROL_USUARIO", $datos["rolUsuario"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }


    //Metodo para editar usuario
    static public function mdlEditarUsuario($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE usuario SET NOMBRE_USUARIO = NOMPROPIO(:NOMBRE_USUARIO),
                                                                  APELLIDO_USUARIO = NOMPROPIO(:APELLIDO_USUARIO),
                                                                  CLAVE = :CLAVE,
                                                                  ROL_USUARIO = :ROL_USUARIO
                                                                  WHERE ID_USUARIO = :ID_USUARIO");

        $stmt -> bindParam(":NOMBRE_USUARIO", $datos["nombreUsuarioEditar"], PDO::PARAM_STR);
        $stmt -> bindParam(":APELLIDO_USUARIO", $datos["apellidoUsuarioEditar"], PDO::PARAM_STR);
        $stmt -> bindParam(":CLAVE", $datos["claveUsuarioEditar"], PDO::PARAM_STR);
        $stmt -> bindParam(":ROL_USUARIO", $datos["rolUsuarioEditar"], PDO::PARAM_STR);
        $stmt -> bindParam(":ID_USUARIO", $datos["idUsuarioEditar"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt -> close();

        $stmt = null;

    }


    //Metodo para desactivar usuario
    static public function mdlDesactivarUsuario($idUsuario){

        $stmt = Conexion::conectar()->prepare("UPDATE usuario SET ESTADO_REGISTRO = 0 WHERE ID_USUARIO = :ID_USUARIO");

        $stmt -> bindParam(":ID_USUARIO", $idUsuario, PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt -> close();

        $stmt = null;

    }


    //Metodo para activar usuario
    static public function mdlActivarUsuario($idUsuario){

        $stmt = Conexion::conectar()->prepare("UPDATE usuario SET ESTADO_REGISTRO = 1 WHERE ID_USUARIO = :ID_USUARIO");

        $stmt -> bindParam(":ID_USUARIO", $idUsuario, PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt -> close();

        $stmt = null;

    }

}