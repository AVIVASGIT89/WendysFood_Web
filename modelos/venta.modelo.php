<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "conexion.php";

class ModeloVenta{

    //Listar mesas
    static public function mdlListarMesas(){

        $stmt = Conexion::conectar()->prepare("SELECT M.ID_MESA,
                                                      M.ID_VENTA_OCUPACION,
                                                      M.NRO_MESA,
                                                      M.TOTAL_CONSUMO,
                                                      M.ESTADO_MESA
                                               FROM mesa M
                                               WHERE M.ESTADO_REGISTRO = 1
                                               AND M.VISIBLE = 1
                                               ORDER BY M.NRO_MESA");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }


    //Listar deliverys de fecha actual (hoy)
    static public function mdlListarDeliveryHoy(){

        $stmt = Conexion::conectar()->prepare("(SELECT ID_VENTA,
                                                        TIPO_VENTA,
                                                        ESTADO_VENTA,
                                                        TOTAL_VENTA,
                                                        USUARIO_VENTA,
                                                        CLIENTE_DELIVERY,
                                                        FECHA_VENTA,
                                                        1 AS ORDER_COLUMN
                                                FROM venta
                                                WHERE TIPO_VENTA = 2
                                                AND ESTADO_VENTA = 1
                                                AND ESTADO_REGISTRO = 1
                                                ORDER BY FECHA_VENTA DESC)
                                                
                                                UNION ALL

                                                (SELECT ID_VENTA,
                                                        TIPO_VENTA,
                                                        ESTADO_VENTA,
                                                        TOTAL_VENTA,
                                                        USUARIO_VENTA,
                                                        CLIENTE_DELIVERY,
                                                        FECHA_VENTA,
                                                        2 AS ORDER_COLUMN
                                                FROM venta
                                                WHERE TIPO_VENTA = 2
                                                AND ESTADO_VENTA IN (2, 3)
                                                AND ESTADO_REGISTRO = 1
                                                AND DATE_FORMAT(FECHA_VENTA, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
                                                ORDER BY FECHA_VENTA DESC)
                                                
                                                ORDER BY ORDER_COLUMN, FECHA_VENTA DESC");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }


    //Registrar venta (orden)
    static public function mdlRegistrarVenta($datosVenta){

        session_start();

        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("INSERT INTO venta (TIPO_VENTA,
                                                 ID_USUARIO_VENTA,
                                                 ID_MESA,
                                                 NRO_PRODUCTOS,
                                                 USUARIO_VENTA,
                                                 FECHA_VENTA,
                                                 BASE_VENTA,
                                                 IGV_VENTA,
                                                 TOTAL_VENTA,
                                                 CLIENTE_DELIVERY)
                                         VALUES(:TIPO_VENTA,
                                                :ID_USUARIO_VENTA,
                                                :ID_MESA,
                                                :NRO_PRODUCTOS,
                                                :USUARIO_VENTA,
                                                 NOW(),
                                                :BASE_VENTA,
                                                :IGV_VENTA,
                                                :TOTAL_VENTA,
                                                :CLIENTE_DELIVERY)");

        $stmt -> bindParam(":ID_USUARIO_VENTA", $_SESSION["sIdUsuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":TIPO_VENTA", $datosVenta["tipoVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":ID_MESA", $datosVenta["idMesaVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":NRO_PRODUCTOS", $datosVenta["nroProductos"], PDO::PARAM_STR);
        $stmt -> bindParam(":USUARIO_VENTA", $_SESSION["sUsuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":BASE_VENTA", $datosVenta["baseVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":IGV_VENTA", $datosVenta["igvVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":TOTAL_VENTA", $datosVenta["totalVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":CLIENTE_DELIVERY", $datosVenta["clienteDelivery"], PDO::PARAM_STR);

        if($stmt->execute()){

            $ultimoId = $pdo->lastInsertId();

            $resultado = array(
                "resultado" => "ok",
                "idVenta" => $ultimoId
               );
            
            return $resultado;

        }else{

            return "error";

        }

        $stmt = null;

        $pdo = null;

    }


    //Registrar detalle venta
    static public function mdlRegistrarDetalleVenta($datosDetalleVenta){

        $stmt = Conexion::conectar()->prepare("INSERT INTO venta_detalle (ID_VENTA,
                                                                            ID_PRODUCTO,
                                                                            PRECIO_UNITARIO,
                                                                            CANTIDAD,
                                                                            SUBTOTAL_BASE,
                                                                            SUBTOTAL_IGV,
                                                                            SUBTOTAL)
                                                                    VALUES(:ID_VENTA,
                                                                            :ID_PRODUCTO,
                                                                            :PRECIO_UNITARIO,
                                                                            :CANTIDAD,
                                                                            :SUBTOTAL_BASE,
                                                                            :SUBTOTAL_IGV, 
                                                                            :SUBTOTAL)");

        $stmt -> bindParam(":ID_VENTA", $datosDetalleVenta["idVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":ID_PRODUCTO", $datosDetalleVenta["idProducto"], PDO::PARAM_STR);
        $stmt -> bindParam(":PRECIO_UNITARIO", $datosDetalleVenta["precioUnitario"], PDO::PARAM_STR);
        $stmt -> bindParam(":CANTIDAD", $datosDetalleVenta["cantidad"], PDO::PARAM_STR);
        $stmt -> bindParam(":SUBTOTAL_BASE", $datosDetalleVenta["subTotalBase"], PDO::PARAM_STR);
        $stmt -> bindParam(":SUBTOTAL_IGV", $datosDetalleVenta["subTotalIgv"], PDO::PARAM_STR);
        $stmt -> bindParam(":SUBTOTAL", $datosDetalleVenta["subTotal"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt = null;

    }


    //Mostrar detalle venta
    static public function mdlMostrarDetalleVenta($idVenta){

        $stmt = Conexion::conectar()->prepare("SELECT V.ID_VENTA,
                                                        V.TOTAL_VENTA,
                                                        V.FECHA_VENTA,
                                                        D.ID_DETALLE_VENTA,
                                                        D.ID_PRODUCTO,
                                                        P.NOMBRE_PRODUCTO,
                                                        P.TIPO_PRECIO,
                                                        P.TIPO_COCINA,
                                                        D.CANTIDAD,
                                                        D.PRECIO_UNITARIO,
                                                        D.SUBTOTAL,
                                                        D.SUBTOTAL_BASE,
                                                        D.SUBTOTAL_IGV
                                                FROM venta V
                                                INNER JOIN venta_detalle D ON D.ID_VENTA = V.ID_VENTA
                                                INNER JOIN producto P ON P.ID_PRODUCTO = D.ID_PRODUCTO
                                                WHERE V.ID_VENTA = $idVenta
                                                AND V.ESTADO_VENTA = 1
                                                AND D.ESTADO_REGISTRO = 1
                                                ORDER BY D.ID_DETALLE_VENTA");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }


    //Finalizar venta
    static public function mdlFinalizarVenta($idVenta, $formaPago){

        $stmt = Conexion::conectar()->prepare("UPDATE venta SET ESTADO_VENTA = 2,
                                                                ID_FORMA_PAGO = :ID_FORMA_PAGO
                                                            WHERE ID_VENTA = :ID_VENTA");

        $stmt -> bindParam(":ID_VENTA", $idVenta, PDO::PARAM_STR);
        $stmt -> bindParam(":ID_FORMA_PAGO", $formaPago, PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt = null;

    }


    //Cancelar venta
    static public function mdlCancelarVenta($idVenta){

        session_start();

        $stmt = Conexion::conectar()->prepare("UPDATE venta SET ESTADO_VENTA = 3,
                                                                FECHA_CANCELACION = NOW(),
                                                                USUARIO_CANCELACION = :USUARIO_CANCELACION
                                                            WHERE ID_VENTA = :ID_VENTA");

        $stmt -> bindParam(":ID_VENTA", $idVenta, PDO::PARAM_STR);
        $stmt -> bindParam(":USUARIO_CANCELACION", $_SESSION["sUsuario"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt = null;

    }


    //Actualizar venta
    static public function mdlActualizarVenta($datosVenta){

        $stmt = Conexion::conectar()->prepare("UPDATE venta SET TOTAL_VENTA = :TOTAL_VENTA,
                                                                NRO_PRODUCTOS = :NRO_PRODUCTOS,
                                                                BASE_VENTA = :BASE_VENTA,
                                                                IGV_VENTA = :IGV_VENTA
                                                            WHERE ID_VENTA = :ID_VENTA");

        $stmt -> bindParam(":ID_VENTA", $datosVenta["idVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":NRO_PRODUCTOS", $datosVenta["nroProductos"], PDO::PARAM_STR);
        $stmt -> bindParam(":BASE_VENTA", $datosVenta["baseVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":IGV_VENTA", $datosVenta["igvVenta"], PDO::PARAM_STR);
        $stmt -> bindParam(":TOTAL_VENTA", $datosVenta["totalVenta"], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt = null;

    }


    //Anular detalle venta
    static public function mdlAnularDetalleVenta($idVenta){

        $stmt = Conexion::conectar()->prepare("UPDATE venta_detalle SET ESTADO_REGISTRO = 0
                                                                    WHERE ID_VENTA = :ID_VENTA");

        $stmt -> bindParam(":ID_VENTA", $idVenta, PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";

        }

        $stmt = null;

    }


    //Reporte resumen venta
    static public function mdlReporteResumenVenta($fechaDesde, $fechaHasta){

        $stmt = Conexion::conectar()->prepare("SELECT V.ID_USUARIO_VENTA,
                                                      V.USUARIO_VENTA,
                                                      (SELECT SUM(V2.TOTAL_VENTA)
                                                        FROM venta V2
                                                        WHERE V2.FECHA_VENTA BETWEEN '$fechaDesde 00:00:00' AND '$fechaHasta 23:59:59'
                                                        AND V2.ESTADO_REGISTRO = 1
                                                        AND V2.ESTADO_VENTA = 2
                                                        AND V2.ID_USUARIO_VENTA = V.ID_USUARIO_VENTA) TOTAL_VENTA_FINALIZADO,
                                                      (SELECT SUM(V3.TOTAL_VENTA)
                                                        FROM venta V3
                                                        WHERE V3.FECHA_VENTA BETWEEN '$fechaDesde 00:00:00' AND '$fechaHasta 23:59:59'
                                                        AND V3.ESTADO_REGISTRO = 1
                                                        AND V3.ESTADO_VENTA = 1
                                                        AND V3.ID_USUARIO_VENTA = V.ID_USUARIO_VENTA) TOTAL_VENTA_PENDIENTE,
                                                      (SELECT SUM(V4.TOTAL_VENTA) 
                                                        FROM venta V4
                                                        WHERE V4.FECHA_VENTA BETWEEN '$fechaDesde 00:00:00' AND '$fechaHasta 23:59:59'
                                                        AND V4.ESTADO_REGISTRO = 1
                                                        AND V4.ESTADO_VENTA = 3
                                                        AND V4.ID_USUARIO_VENTA = V.ID_USUARIO_VENTA) TOTAL_VENTA_CANCELADO,
                                                      COUNT(1) NRO_VENTAS
                                                FROM venta V
                                                WHERE V.FECHA_VENTA BETWEEN '$fechaDesde 00:00:00' AND '$fechaHasta 23:59:59'
                                                AND V.ESTADO_REGISTRO = 1
                                                GROUP BY V.ID_USUARIO_VENTA");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }


    //Reporte registro venta
    static public function mdlReporteRegistroVenta($filtros){

        $fechaDesde = $filtros["fechaDesde"];
        $fechaHasta = $filtros["fechaHasta"];
        $condicionEstado = "";
        $condicionTipo = "";

        if($filtros["estadoVenta"] != "0"){

            $condicionEstado = " AND V.ESTADO_VENTA = ".$filtros["estadoVenta"]." ";

        }

        if($filtros["tipoVenta"] != "0"){

            $condicionTipo = " AND V.TIPO_VENTA = ".$filtros["tipoVenta"]." ";

        }

        $stmt = Conexion::conectar()->prepare("SELECT V.ID_VENTA,
                                                      V.TIPO_VENTA,
                                                      CASE(V.TIPO_VENTA)
                                                            WHEN 1 THEN 'Mesa'
                                                            WHEN 2 THEN 'Delivery'
                                                      END TIPO_VENTA,
                                                      V.FECHA_VENTA,
                                                      V.NRO_PRODUCTOS,
                                                      V.TOTAL_VENTA,
                                                      V.ESTADO_VENTA,
                                                      V.ENVIO_SUNAT,
                                                      V.USUARIO_VENTA
                                                FROM venta V
                                                WHERE V.FECHA_VENTA BETWEEN '$fechaDesde 00:00:00' AND '$fechaHasta 23:59:59'
                                                $condicionEstado
                                                $condicionTipo
                                                AND V.ESTADO_REGISTRO = 1
                                                ORDER BY V.FECHA_VENTA DESC");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }


    //Detalle venta
    static public function mdlProductosVenta($idVenta){

        $stmt = Conexion::conectar()->prepare("SELECT V.FECHA_VENTA, 
                                                        P.NOMBRE_PRODUCTO,
                                                        D.CANTIDAD,
                                                        D.PRECIO_UNITARIO,
                                                        D.SUBTOTAL,
                                                        D.SUBTOTAL_BASE,
                                                        D.SUBTOTAL_IGV
                                                FROM venta V
                                                INNER JOIN venta_detalle D ON D.ID_VENTA = V.ID_VENTA
                                                INNER JOIN producto P ON P.ID_PRODUCTO = D.ID_PRODUCTO
                                                WHERE D.ID_VENTA = $idVenta
                                                AND D.ESTADO_REGISTRO = 1");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }

    
    //Enviar mail de alerta por anulacion de orden
    static public function mdlEnviarMailAnulacionOrden($datosMail){

        require '../lib/mailer/Exception.php';
        require '../lib/mailer/PHPMailer.php';
        require '../lib/mailer/SMTP.php';

        session_start();

        $usuario = $_SESSION["sUsuario"];
        $listaProductos = $datosMail["listaProductos"];
        $fechaOrden = $datosMail["fechaOrden"];
        $fechaCancelacion = $datosMail["fechaCancelacion"];
        $tipoOrden = $datosMail["tipoOrden"];
        $productos = '';

        foreach($listaProductos as $key => $value){

            $productos = $productos.'<tr>
                                       <td>'.($key + 1).'</td>
                                       <td align="center">'.$value["cantidad"].'</td>
                                       <td>'.$value["nombreProducto"].'</td>
                                       <td align="right">'.$value["precioUnitario"].'</td>
                                       <td align="right">'.$value["subtotal"].'</td>
                                     </tr>';
                                      
        }

        $mail = new PHPMailer(true);

        $cuerpoMail = '<html>
                        <style>
                            .badge-rojo {
                                display: inline-block;
                                padding: 0.25em 0.4em;
                                font-size: 75%;
                                font-weight: 700;
                                line-height: 1;
                                text-align: center;
                                white-space: nowrap;
                                vertical-align: baseline;
                                border-radius: 0.35rem;
                                background-color: #dc3545!important;
                                color: #fff;
                            }
                        </style>
                        <body style="background-color:#ecefe6;">
                            <div style="width: 100%; font-size: 13px; padding-top: 20px; padding-bottom: 20px; display: flex; justify-content: center; align-items: center;">

                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <div style="width: 500px; padding: 10px;">
                                                <div style="padding: 20px; background: white; text-align: left; border-radius: 8px;">
                                                    <p>
                                                        Alerta!
                                                        <br>
                                                        <br>
                                                        El usuario <b>'.$usuario.'</b> ha <span class="badge-rojo">cancelado</span> la siguiente orden '.$tipoOrden.':
                                                        <br>
                                                        <br>
                                                        <table align="center">
                                                            <thead style="color: white; background: #0b6730;">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Cant.</th>
                                                                    <th>Producto</th>
                                                                    <th>Precio</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>'
                                                                .$productos.'
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                        <table>
                                                            <tr>
                                                                <td>Fecha orden:</td>
                                                                <td>'.$fechaOrden.'</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Fecha cancelacion:</td>
                                                                <td>'.$fechaCancelacion.'</td>
                                                            </tr>
                                                        </table>
                                                    </p>
                                                    <br>
                                                    <p align="center">
                                                        <img src="https://solucionventas.com/wendysfood/vistas/dist/img/logomenu.png" width="50">
                                                        <br>
                                                        Copyright &#169; Wendys Food
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </body>
                        </html>';
    
        try {
            //Para omitir restricciones SSL
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
        
            //***** Para servidor local (localhost)
            
            $mail->SMTPDebug = 0;                      		        //Enable verbose debug output
            $mail->isSMTP();                           		        //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                   //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                               //Enable SMTP authentication
            $mail->Username   = 'vcimportacionesperu@gmail.com';  	//SMTP username
            $mail->Password   = 'zgavqvotzvvptgmb';                 //SMTP password
            $mail->SMTPSecure = 'tls';            			        //Enable implicit TLS encryption
            $mail->Port       = 587;                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
        
            //***** Para servidor en hosting
            /*
            $mail->SMTPDebug = 0;                      		        //Enable verbose debug output
            $mail->isSMTP();                           		        //Send using SMTP
            $mail->Host       = 'mail.limagamers.com';              //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                               //Enable SMTP authentication
            $mail->Username   = 'notificaciones@limagamers.com';  	//SMTP username
            $mail->Password   = '4xv2q!AiARGn';                     //SMTP password
            $mail->SMTPSecure = 'ssl';            			        //Enable implicit TLS encryption
            $mail->Port       = 465;
            */
        
            //Recipients
            $mail->setFrom('vcimportacionesperu@gmail.com','Alertas Wendys Food');
            $mail->addAddress('giezi_111@hotmail.com');                         //Add a recipient
            //$mail->addAddress('ellen@example.com');                           //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('alejo.dias.89@gmail.com');
            //$mail->addBCC('bcc@example.com');
        
            //Attachments (adjuntos)
            //$mail->addAttachment('/var/tmp/file.tar.gz');                     //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');                //Optional name
        
            //Content
            $mail->isHTML(true);                                                //Set email format to HTML
            $mail->Subject = 'Alerta de cancelacion de orden';
            $mail->Body    = $cuerpoMail;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            //echo 'Mensaje enviado correctamente';

            //$response = array("hecho" => true);
            return "ok";
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            //$response = array("hecho" => false);
            return "error";
        }

    }


    //Reporte ventas SUNAT
    static public function mdlReporteVentasSunat($filtros){

        $fechaDesde = $filtros["fechaDesde"];
        $fechaHasta = $filtros["fechaHasta"];

        $condicionTipoComprobante = "";
        $condicionEstadoEnvio = " AND V.ENVIO_SUNAT != 0 ";

        if($filtros["tipoComprobante"] != "0"){

            $condicionTipoComprobante = " AND V.TIPO_COMPROBANTE_SUNAT = ".$filtros["tipoComprobante"]." ";

        }

        if($filtros["estadoEnvio"] != "0"){

            $condicionEstadoEnvio = " AND V.ENVIO_SUNAT = ".$filtros["estadoEnvio"]." ";

        }

        $stmt = Conexion::conectar()->prepare("SELECT V.ID_VENTA,
                                                        V.FECHA_VENTA,
                                                        CASE
                                                            WHEN V.TIPO_COMPROBANTE_SUNAT = '01' THEN 'Factura'
                                                            WHEN V.TIPO_COMPROBANTE_SUNAT = '03' THEN 'Boleta' 
                                                        END 'TIPO_COMPROBANTE',
                                                        V.SERIE_VENTA_SUNAT,
                                                        V.NRO_VENTA_SUNAT,
                                                        V.ENVIO_SUNAT,
                                                        CASE
                                                            WHEN V.ENVIO_SUNAT = 1 THEN 'Enviado'
                                                            WHEN V.ENVIO_SUNAT = 2 THEN 'Observado' 
                                                        END 'ESTADO_ENVIO',
                                                        V.TOTAL_VENTA
                                                FROM venta V
                                                WHERE V.ESTADO_REGISTRO = 1
                                                $condicionEstadoEnvio
                                                $condicionTipoComprobante
                                                AND V.FECHA_VENTA BETWEEN '$fechaDesde' AND '$fechaHasta'
                                                ORDER BY V.FECHA_VENTA DESC");

        $stmt -> execute();

        return $stmt -> fetchAll();    //Devolvemos todos los registros encontrados

        $stmt = null;

    }

}