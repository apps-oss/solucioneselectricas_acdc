<?php
error_reporting(E_ERROR | E_PARSE);
require('_core.php');
require('fpdf/fpdf.php');
require('fpdf/writeTable.php');


$id_proveedor = $_GET['id_proveedor'];
$firstDate    = $_GET['mes_inicial'];
$lastDate     = $_GET['mes_final'];

$monthStrings = [1  => 'Enero',   2 => 'Febre.',  3  => 'Marzo',
                 4  => 'Abril',   5 => 'Mayo',    6  =>  'Junio',
                 7  => 'Julio',   8 => 'Agosto',  9  => 'Septi.',
                 10 => 'Octub.', 11 => 'Novie.', 12 => 'Dicie,'];

$firstYear = substr($firstDate, 0, 4);
$firstMonth = substr($firstDate, 5, 2);

$lastYear = substr($lastDate, 0, 4);
$lastMonth = substr($lastDate, 5, 2);

$inicialDate = new DateTime($firstDate);
$finalDate = new DateTime($lastDate);

$diff = $inicialDate->diff($finalDate);

$monthsCount = ($diff->y * 12) + $diff->m;

$j = 0;
$month = (int)$firstMonth;
$year = (int)$firstYear;

for ($i = 0; $i <= $monthsCount; $i++) {
    if ($month > 12) {
        $year++;
        $j = 0;
        $month = 1;
    }
    $months[] = [
        'year' => $year,
        'month' => $month++,
    ];

    $j++;
}

$data = [];

$data[0][] = 'Total sin IVA';
$data[1][] = 'Total con IVA';
$data[2][] = 'Totales';

$totalIva = 0;
$totalSinIva = 0;

foreach ($months as $i => $item) {
    $month = $item['month'] < 10 ? '0' . $item['month'] : $item['month'];
    $year  = $item['year'];

    $query  = "SELECT SUM(subtotal) as subtotal FROM factura_detalle ";
    $query .= "INNER JOIN producto ON producto.id_producto = factura_detalle.id_prod_serv";
    $query .= " WHERE producto.id_proveedor >=0" ;

    $RESULT = _query($query);

    $row_detail = _fetch_assoc($RESULT);

    $data[0][] = '$ ' . number_format($row_detail['subtotal'], 2);
    $data[1][] = '$ ' . number_format((float)$row_detail['subtotal'] * 1.13, 2);

    if (empty($cantidadTotal[$i])) {
        $total[$i] = (float)$row_detail['subtotal']
            + (float)$row_detail['subtotal'] * 1.13;
    } else {
        $total[$i] += (float)$row_detail['subtotal']
            + (float)$row_detail['subtotal'] * 1.13;
    }

    $data[2][] = '$ ' . number_format($total[$i], 2);

    $totalIva += (float)$row_detail['subtotal'] * 1.13;
    $totalSinIva += (float)$row_detail['subtotal'];
}

$data[0][] = '$ ' . number_format($totalSinIva, 2);
$data[1][] = '$ ' . number_format($totalIva, 2);
$data[2][] = '$ ' . number_format($totalIva + $totalSinIva);

$pdf = new PDFwT('P', 'mm', 'letter');

$pdf->setReportName('Reporte de ventas por proveedor');
$pdf->AddPage();
$pdf->SetFontSize(8);

$header = [];
$header[] = $pdf->th('Descripcion', 'C', 20);

foreach ($months as $item) {
    $header[] = $pdf->th($monthStrings[(int)$item['month']], 'C', 12);
}

$header[] = $pdf->th('Totales', 'C', 25);

$pdf->drawTable($pdf->createTableHeader($header), $data);

$pdf->Output('reporte_producto_proveedor.pdf', 'I');
