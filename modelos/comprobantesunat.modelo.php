<?php

require_once "conexion.php";

class ModeloComprobanteSunat{

    //Metodo para devolver los datos SUNAT
    static public function mdlDatosSunat(){

        $stmt = Conexion::conectar()->prepare("SELECT S.RUC,
                                                    S.RAZON_SOCIAL,
                                                    S.NOMBRE_COMERCIAL,
                                                    S.SERIE_ACTUAL_BOLETA,
                                                    S.SERIE_ACTUAL_FACTURA,
                                                    S.DOMICILIO_FISCAL,
                                                    S.UBIGEO,
                                                    S.USUARIO_SECUNDARIO_SUNAT,
                                                    S.CLAVE_USUARIO_SECUNDARIO,
                                                    S.AMBIENTE
                                            FROM configuracion_sunat S 
                                            WHERE S.ESTADO_REGISTRO = 1");

        $stmt -> execute();

        return $stmt -> fetch();    //Devolvemos el registro encontrado

    }


    //Metodo para devolver el numero para boleta electronica sunat
    static public function mdlNumeroBoletaSunat(){

        $stmt = Conexion::conectar()->prepare("SELECT NEW_NRO_BOLETA_SUNAT() 'NUMERO_BOLETA'");

        $stmt -> execute();

        return $stmt -> fetch();    //Devolvemos el registro encontrado

    }


    //Metodo para devolver el numero para factura electronica sunat
    static public function mdlNumeroFacturaSunat(){

        $stmt = Conexion::conectar()->prepare("SELECT NEW_NRO_FACTURA_SUNAT() 'NUMERO_FACTURA'");

        $stmt -> execute();

        return $stmt -> fetch();    //Devolvemos el registro encontrado

    }


    //Metodo para invocar al API y generar comprobante sunat
    static public function mdlGenerarComprobanteSunat($datosVenta){

        // URL del API
        $url = "https://rvivas.com/API_SUNAT/post.php";

        //Convertimos los datos a JSON
        $jsonData = json_encode($datosVenta);

        // Crear contexto de flujo
        $options = [
            "http" => [
                "header" => "Content-Type: application/json\r\n" .
                            "Content-Length: " . strlen($jsonData) . "\r\n",
                "method" => "POST",
                "content" => $jsonData
            ]
        ];

        $context = stream_context_create($options);

        // Enviamos la solicitud y obtenemos la respuesta
        $response = file_get_contents($url, false, $context);

        /*verificamos la respuesta
        if ($response === false) {
            echo "Error al enviar la solicitud.";
        } else {
            echo $response;
        }*/

        return $response;

    }


    //Metodo para actualizar la serie y numero sunta de la venta
    static public function mdlActualizarVentaSunat($datosVenta){

        $stmt = Conexion::conectar()->prepare("UPDATE venta
                                                SET ENVIO_SUNAT = 1,
                                                    ID_CLIENTE = :ID_CLIENTE,
                                                    SERIE_VENTA_SUNAT = :SERIE_VENTA_SUNAT,
                                                    NRO_VENTA_SUNAT = :NRO_VENTA_SUNAT
                                                WHERE ID_VENTA = :ID_VENTA");

        $stmt -> bindParam(":ID_CLIENTE", $datosVenta["idCliente"], PDO::PARAM_STR);
        $stmt -> bindParam(":SERIE_VENTA_SUNAT", $datosVenta["serieVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":NRO_VENTA_SUNAT", $datosVenta["numeroVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":ID_VENTA", $datosVenta["idVenta"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt = null;

    }

}