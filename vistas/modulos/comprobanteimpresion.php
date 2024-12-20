<?php
require "../../lib/fpdf/fpdf.php";
require "../../lib/numerosaletras/numeroaletras.php";

require_once "../../modelos/comprobantesunat.modelo.php";

$modelonumero = new modelonumero();

$idVenta = $_GET['idVenta'];

$respuesta = ModeloComprobanteSunat::mdlDetalleVentaSunat($idVenta);

//Seteamos los valores de la venta
$nroComprobante = $respuesta[0]["SERIE_VENTA_SUNAT"]." - ".$respuesta[0]["NRO_VENTA_SUNAT"];
$nombreCliente = $respuesta[0]["NOMBRE_CLIENTE"];
$identificacionCliente = $respuesta[0]["IDENTIFICACION_CLIENTE"];
$fechaVenta = $respuesta[0]["FECHA_VENTA"];
$ventaBase = $respuesta[0]["BASE_VENTA"];
$ventaIGV = $respuesta[0]["IGV_VENTA"];
$ventaTotal = $respuesta[0]["TOTAL_VENTA"];
$tipoComprobante = $respuesta[0]["TIPO_COMPROBANTE_SUNAT"];

$textTipoComprobante;

if($tipoComprobante == "03"){

    $textTipoComprobante = "Boleta Electronica";

}else if($tipoComprobante == "01"){

    $textTipoComprobante = "Factura Electronica";

}else{

    $textTipoComprobante = "Otro";

}


class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image('../../vistas/dist/img/logo.png',10,7,35);
        // Arial bold 15
        $this->SetFont('Arial','',18);
        // Movernos a la derecha
        $this->Cell(81);
        // Título
        $this->Cell(30,10,'Wendys Food',0,0,'C');
        // Salto de línea
        $this->Ln(7);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

/**********  Inicio Contenido PDF  ************/
$pdf->SetFont('Arial','',12);
$pdf->Cell(54);// Movernos 54 a la derecha
$pdf->Cell(40,10,utf8_decode('Av. Bernard Balaguer Mz B Lt 5 - Lurigancho'));
$pdf->Ln(7);
$pdf->Cell(75);// Movernos 75 a la derecha
$pdf->Cell(40,10,utf8_decode('RUC: 10458273711'));

$pdf->Ln(16);   //Salto de Linea de 16

$pdf->SetFont('Arial','',17);
$pdf->Cell(0,10, $textTipoComprobante, 0,0,'C');     // 'C' de centrado
$pdf->Ln(7);
$pdf->SetFont('Arial','B',17);
$pdf->Cell(0,10,$nroComprobante, 0, 0, 'C');   // 'C' de centrado

$pdf->Ln(14);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Fecha de emision: ', 0, 0, '');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $fechaVenta);
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'DNI / RUC: ', 0, 0, '');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $identificacionCliente);
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 10, 'Cliente: ', 0, 0, '');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $nombreCliente);

$pdf->Ln(15);

$pdf->Cell(0, 10, 'Detalle:');

$pdf->Ln(10);


////
$pdf->SetFont('Arial', 'B', 10);

//Colores:
/*
$pdf->SetDrawColor(0,80,180);
$pdf->SetFillColor(220,50,50);
$pdf->SetTextColor(220,50,50);
*/

$pdf->Cell(10, 8, 'Item', 1, 0, 'C');
$pdf->Cell(108, 8, 'Descripcion', 1, 0, 'C');
$pdf->Cell(21, 8, 'Cantidad', 1, 0, 'C');
$pdf->Cell(22, 8, 'Precio U.', 1, 0, 'C');
$pdf->Cell(28, 8, 'Sub Total', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);

//Imprimos los productos de la venta
foreach($respuesta as $key => $value){

    $pdf->Cell(10, 8, ($key + 1), 1, 0, '');
    $pdf->Cell(108, 8, $value["NOMBRE_PRODUCTO"], 1, 0, '');
    $pdf->Cell(21, 8, $value["CANTIDAD"], 1, 0, 'C');
    $pdf->Cell(22, 8, number_format($value["PRECIO_UNITARIO"],2), 1, 0, 'R');
    $pdf->Cell(28, 8, number_format($value["SUBTOTAL"],2), 1, 1, 'R');
    
}


$pdf->Ln(6);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(140, 6, 'Base:', 0, 0, 'R');
$pdf->Cell(49, 6, 'S/. '.$ventaBase, 0, 1, 'R');

$pdf->Cell(140, 6, 'IGV:', 0, 0, 'R');
$pdf->Cell(49, 6, 'S/. '.$ventaIGV, 0, 1, 'R');

$pdf->Cell(140, 6, 'Total:', 0, 0, 'R');
$pdf->Cell(49, 6, 'S/. '.$ventaTotal, 0, 1, 'R');

$pdf->Ln(15);

$letras = $modelonumero->numtoletras(abs($ventaTotal));

$pdf->Cell(0, 10, 'SON: '.$letras, 0, 0, '');
/**********  Fin Contenido PDF  ************/

//Imprimimos el archivo con el nombre de nro. comprobante
$pdf->Output('I',$nroComprobante.".pdf");
?>