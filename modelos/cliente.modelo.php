<?php

require_once "conexion.php";

class ModeloCliente{

    //Metodo para listar clientes
    static public function mdlListarClientes($item, $valor){

        if($item == null){

            $stmt = Conexion::conectar()->prepare("SELECT *
                                                    FROM cliente C
                                                    WHERE C.ESTADO_REGISTRO = 1
                                                    ORDER BY C.FECHA_REGISTRO DESC");

            $stmt -> execute();

            return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        }else{

            $stmt = Conexion::conectar()->prepare("SELECT *
                                                    FROM cliente C
                                                    WHERE C.ESTADO_REGISTRO = 1
                                                    AND $item = :$item
                                                    ORDER BY C.FECHA_REGISTRO DESC");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();    //Devolvemos el registro encontrado

        }

        $stmt = null;

    }


    //Buscar cliente en WS a SUNAT
    static public function mldBuscarIdentificacionSunat($identificacion){
        
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        $respuesta = "0";

        if(strlen($identificacion) == 8){

            // Iniciar llamada a API
            $curl = curl_init();
            //https://apis.net.pe/
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $identificacion,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Referer: https://apis.net.pe/consulta-dni-api',
                    'Authorization: Bearer ' . $token
                ),
            ));
            
            $response = curl_exec($curl);            
            $respuesta = json_decode($response);

        }else{//Para RUC
            // Iniciar llamada a API
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $identificacion,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
              ),
            ));

            $response = curl_exec($curl);
            $respuesta = json_decode($response);                        

        }

        return $respuesta;

    }


    //Metodo para registrar nuevo cliente
    static public function mdlRegistrarCiente($datosCliente){

        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("INSERT INTO cliente (IDENTIFICACION_CLIENTE,
                                                    NOMBRE_CLIENTE,
                                                    FECHA_REGISTRO)
                                            VALUES(:IDENTIFICACION_CLIENTE,
                                                    UPPER(:NOMBRE_CLIENTE),
                                                    NOW())");

        $stmt -> bindParam(":IDENTIFICACION_CLIENTE", $datosCliente["identificacionCliente"], PDO::PARAM_STR);
        $stmt -> bindParam(":NOMBRE_CLIENTE", $datosCliente["nombreCliente"], PDO::PARAM_STR);

        if($stmt->execute()){

            $ultimoId = $pdo->lastInsertId();

            $resultado = array(
                "resultado" => "ok",
                "idCliente" => $ultimoId
               );
            
            return $resultado;

        }else{

            return "error";

        }

        $stmt = null;

    }


}