<?php

error_reporting(E_ERROR | E_PARSE);
require('_core.php');
require('fpdf/fpdf.php');
include('num2letras.php');
$pdf = new FPDF('P', 'mm', 'letter');
$id_factura = $_REQUEST['id_factura'];
$pdf->SetAutoPagebreak(false);
$pdf->SetMargins(0, 0, 0);
$pdf->AddPage();
$sql = 'SELECT count(*) FROM factura_detalle WHERE id_factura=' .$id_factura;
$result =  _query($sql) ;
$row_cli =  _fetch_row($result);
//mysqli_free_result($result);
$nb_ln = $row_cli[0];
$result1 = datos_factura($id_factura);
$row     = _fetch_assoc($result1);
$fecha   = $row['fecha'];
$fechaprint=ED($fecha);
list($dd,$mm,$yy)   =explode("-",$fechaprint);

$tipo_pago   = $row['tipo_pago'];
$condicion = 'Contado';
if ($tipo_pago=='CRE') {
    $condicion = 'Crédito';
}
$result2 = cliente_factura($id_factura);
$row_client =_fetch_assoc($result2);
$duinit=$row_client['dui'];
if ($row_client['dui']=="") {
    $duinit=$row_client['nit'];
}
//mysqli_free_result($result2);
$nom_file = "fact_" . $fecha .'_' .$id_factura. ".pdf";
$pdf->SetFont('Arial', '', 9);
$x=60; $y=6;
$pdf->SetXY($x, $y);
$pdf->Cell(60, 8, "  " . $row['num_fact_impresa'], 0, 0, '');
$x=25;
$y+=43;
$pdf->SetXY($x+132, $y);
$pdf->Cell(60, 8, "  " . $dd."         ".$mm."           ".$yy, 0, 0, '');

$pdf->SetXY($x, $y+6);
$pdf->Cell(80, 8, "  " . $row_client['nombre'], 0, 0, '');
$pdf->SetXY($x+122, $y+6);
$pdf->Cell(60, 8, "  " . $duinit, 0, 1, '');
$pdf->SetXY($x+2, $y+12);
$pdf->Cell(80, 8, "  " . substr($row_client['direccion'],0,50), 0, 0, '');
$pdf->SetXY($x+58, $y+18);
$pdf->Cell(86, 8, "  " . utf8_decode($row_client['nombre_departamento']), 0, 0, '');
//$pdf->SetXY($x+160, $y+18);
//$pdf->Cell(86, 8, $condicion, 0, 0, '');
//  articulos
$pdf->SetFont('Arial', '', 8);
$y = 72;
$lin_max=23;
$ln=1;
$res =  datos_fact_det($id_factura);
$x=3;
$pdf->SetLeftMargin(20);
while ($data =  _fetch_assoc($res)) {
    $bcode=$data['codigo'];
    if($data['codigo']==""){
        $bcode=$data['barcode'];
    }
    $pdf->SetXY(10, $y+9);
    $pdf->Cell(8, 5, round($data['cantidad'], 0), 0, 0, 'R');
    $pdf->SetXY($x+18, $y+9);
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(40, 5, $bcode, 0, 0, 'L');
    $pdf->SetXY($x+42, $y+9);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(132, 5, $data['descripcion']." ".$data['marca'], 0, 0, 'L');
    $pv = number_format($data['precio_venta'], 2, '.', '');
    $pdf->SetXY($x+148, $y+9);
    $pdf->Cell(12, 5, $pv, 0, 0, 'R');
    $subt = number_format($data['subtotal'], 2, '.', ' ');
    $pdf->SetXY($x+186, $y+9);
    $pdf->Cell(12, 5, $subt, 0, 0, 'R');
    $y += 4;
    $ln++;
}
$lineas_faltantes=$lin_max - $ln;
if ($lineas_faltantes>0) {
    for ($j=0;$j<$lineas_faltantes;$j++) {
        $y +=4;
        $pdf->SetXY(2, $y+9);
        $pdf->Cell(10, 5, "", 0, 1, '');
    }
}
mysqli_free_result($res);
$sumas = number_format($row['sumas'], 2, '.', ' ');
$iva = number_format($row['iva'], 2, '.', '');
$retencion  = number_format($row['retencion'], 2, '.', '');
$percepcion = number_format($row['percepcion'], 2, '.', '');
$total = number_format($row['total'], 2, '.', '');
$total_final =number_format($total + $percencion - $retencion, 2, ".", "");
$total_txt= getTotalTexto(number_format($total_final, 2, ".", ""));
$y1 = 165;
$x1 =188;
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY($x1, $y1+1);
$pdf->Cell(10, 5, $sumas, 0, 0, 'R');
$pdf->SetXY($x1 -170, $y1 + 6);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(100, 5, $total_txt, 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY($x1, $y1 + 8);
$pdf->Cell(10, 5, $iva, 0, 0, 'R');
$pdf->SetXY($x1, $y1 + 13);
$pdf->Cell(10, 5, $total, 0, 0, 'R');
$pdf->SetXY($x1, $y1 + 18);
$pdf->Cell(10, 5, $percepcion, 0, 0, 'R');
$pdf->SetXY($x1, $y1 + 24);
$pdf->Cell(10, 5, $retencion, 0, 0, 'R');
$pdf->SetXY($x1, $y1 + 40);
$pdf->Cell(10, 5, $total_final, 0, 0, 'R');
$pdf->Output("I", $nom_file);
