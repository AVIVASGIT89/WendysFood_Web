<?php

require_once "../controladores/comprobantesunat.controlador.php";
require_once "../modelos/comprobantesunat.modelo.php";

$accion = $_POST["accion"];

if($accion == "generarComprobante"){

    $datosSunat = ControladorComprobanteSunat::ctrDatosSunat();
    //var_dump($datosSunat);

    $idVenta = $_POST["idVenta"];
    $idCliente = $_POST["idCliente"];
    $tipoComprobante = $_POST["tipoDocumento"];
    $serieComprobante;
    $numeroComprobante;
    $items = [];

    if($tipoComprobante == "01"){

        $serieComprobante = $datosSunat["SERIE_ACTUAL_FACTURA"];
        $respuesta = ControladorComprobanteSunat::ctrNumeroFacturaSunat();
        $numeroComprobante = $respuesta["NUMERO_FACTURA"];

    }else if($tipoComprobante == "03"){
        
        $serieComprobante = $datosSunat["SERIE_ACTUAL_BOLETA"];
        $respuesta = ControladorComprobanteSunat::ctrNumeroBoletaSunat();
        $numeroComprobante = $respuesta["NUMERO_BOLETA"];

    }

    $ventaSunatActualizar = [
        "idVenta" => $idVenta,
        "idCliente" => $idCliente,
        "serieVenta" => $serieComprobante,
        "numeroVenta" => $numeroComprobante
    ];

    $empresa = [
        "ruc" => $datosSunat["RUC"],
        "razon_social" => $datosSunat["RAZON_SOCIAL"],
        "nombre_comercial" => $datosSunat["NOMBRE_COMERCIAL"],
        "domicilio_fiscal" => $datosSunat["DOMICILIO_FISCAL"],
        "ubigeo" => $datosSunat["RUC"],
        "urbanizacion" => "-",
        "distrito" => "-",
        "provincia" => "-",
        "departamento" => "-",
        "modo" => $datosSunat["AMBIENTE"],
        "usu_secundario_produccion_user" => $datosSunat["USUARIO_SECUNDARIO_SUNAT"],
        "usu_secundario_produccion_password" => $datosSunat["CLAVE_USUARIO_SECUNDARIO"]
    ];
    
    $cliente = [
        "razon_social_nombres" => $_POST["nombreCliente"],
        "numero_documento" => $_POST["identificacionCliente"],
        "codigo_tipo_entidad" => $_POST["tipoIdentificacionCliente"],
        "cliente_direccion" => "-"
    ];
    
    $venta = [
        "serie" => $serieComprobante,
        "numero" => $numeroComprobante,
        "fecha_emision" => $_POST["fechaVenta"],
        "hora_emision" => $_POST["horaVenta"],
        "fecha_vencimiento" => "",
        "moneda_id" => "1",
        "forma_pago_id" => "1",
        "total_gravada" => $_POST["ventaBase"],
        "total_igv" => $_POST["ventaIGV"],
        "total_exonerada" => "",
        "total_inafecta" => "",
        "tipo_documento_codigo" => $_POST["tipoDocumento"],
        "nota" => ""
    ];

    $listaProductos = $_POST["listaProductos"];

    foreach($listaProductos as $key => $value){

        $items [] = [
            "producto" => $value["nombreProducto"],
            "cantidad" => "1",
            "precio_base" => $value["baseSubtotal"],
            "codigo_sunat" => "",
            "codigo_producto" => "",
            "codigo_unidad" => "NIU",
            "tipo_igv_codigo" => "10"
        ];
        
    }


    $datosVenta = array("empresa" => $empresa,
                        "cliente" => $cliente,
                        "venta" => $venta,
                        "items" => $items);

    //echo json_encode($datosVenta);

    $respuestaSunat = ControladorComprobanteSunat::ctrGenerarComprobanteSunat($datosVenta);

    //Verificamos si la respuesta es 0 para actualar la venta
    $respuestaVerificar = json_decode($respuestaSunat, true);
    $codigoRespuesta = $respuestaVerificar["data"]["respuesta_sunat_codigo"];

    if($codigoRespuesta == "0"){

        ControladorComprobanteSunat::ctrActualizarVentaSunat($ventaSunatActualizar);

    }

    echo $respuestaSunat;

}