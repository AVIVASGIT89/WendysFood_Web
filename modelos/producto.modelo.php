<?php

require_once "conexion.php";

class ModeloProducto{

    //Listar mesas
    static public function mdlMostrarProducto($item, $valor){

        if($item == null){

            $stmt = Conexion::conectar()->prepare("SELECT P.ID_PRODUCTO,
                                                          P.CODIGO_PRODUCTO,
                                                          P.NOMBRE_PRODUCTO,
                                                          P.TIPO_COCINA,
                                                          CASE(P.TIPO_COCINA)
                                                              WHEN 1 THEN 'Cocina'
                                                              WHEN 2 THEN 'Carrito'
                                                              WHEN 3 THEN 'Ambos'
                                                          END NOM_TIPO_COCINA,
                                                          P.PRECIO_UNITARIO,
                                                          P.TIPO_PRECIO,
                                                          CASE(P.TIPO_PRECIO)
                                                              WHEN 1 THEN 'Automatico'
                                                              WHEN 2 THEN 'Manual'
                                                          END NOM_TIPO_PRECIO
                                                    FROM producto P
                                                    WHERE P.ESTADO_REGISTRO = 1");

            $stmt -> execute();

            return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT * FROM producto WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();    //Devolvemos el registro encontrado

        }

        $stmt -> close();

        $stmt = null;

    }


    //Registrar producto
    static public function mdlRegistrarProducto($datosProducto){

        $stmt = Conexion::conectar()->prepare("INSERT INTO producto (CODIGO_PRODUCTO,
                                                                    NOMBRE_PRODUCTO,
                                                                    TIPO_COCINA,
                                                                    PRECIO_UNITARIO,
                                                                    TIPO_PRECIO,
                                                                    USUARIO_REGISTRO,
                                                                    FECHA_REGISTRO)
                                                            VALUES(:CODIGO_PRODUCTO,
                                                                    NOMPROPIO(:NOMBRE_PRODUCTO),
                                                                    :TIPO_COCINA,
                                                                    :PRECIO_UNITARIO,
                                                                    :TIPO_PRECIO,
                                                                    :USUARIO_REGISTRO,
                                                                    NOW())");

        $stmt -> bindParam(":CODIGO_PRODUCTO", $datosProducto["nuevoCodigo"], PDO::PARAM_STR);
        $stmt -> bindParam(":NOMBRE_PRODUCTO", $datosProducto["nombreProducto"], PDO::PARAM_STR);
        $stmt -> bindParam(":TIPO_COCINA", $datosProducto["tipoCocina"], PDO::PARAM_STR);
        $stmt -> bindParam(":PRECIO_UNITARIO", $datosProducto["precioUnitario"], PDO::PARAM_STR);
        $stmt -> bindParam(":TIPO_PRECIO", $datosProducto["tipoPrecio"], PDO::PARAM_STR);
        $stmt -> bindParam(":USUARIO_REGISTRO", $_SESSION["sUsuario"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }


    //Actualizar producto
    static public function mdlActualizarProducto($datosProducto){

        $stmt = Conexion::conectar()->prepare("UPDATE producto SET CODIGO_PRODUCTO = :CODIGO_PRODUCTO,
                                                                    NOMBRE_PRODUCTO = :NOMBRE_PRODUCTO,
                                                                    TIPO_COCINA = :TIPO_COCINA,
                                                                    PRECIO_UNITARIO = :PRECIO_UNITARIO,
                                                                    TIPO_PRECIO = :TIPO_PRECIO
                                                                WHERE ID_PRODUCTO = :ID_PRODUCTO");

        $stmt -> bindParam(":ID_PRODUCTO", $datosProducto["idProducto"], PDO::PARAM_STR);
        $stmt -> bindParam(":CODIGO_PRODUCTO", $datosProducto["codigoProducto"], PDO::PARAM_STR);
        $stmt -> bindParam(":TIPO_COCINA", $datosProducto["tipoCocina"], PDO::PARAM_STR);
        $stmt -> bindParam(":PRECIO_UNITARIO", $datosProducto["precioUnitario"], PDO::PARAM_STR);
        $stmt -> bindParam(":TIPO_PRECIO", $datosProducto["tipoPrecio"], PDO::PARAM_STR);
        $stmt -> bindParam(":NOMBRE_PRODUCTO", $datosProducto["nombreProducto"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt -> close();

        $stmt = null;

    }

}