<?php
error_reporting(E_ERROR | E_PARSE);
require('_core.php');
include('fpdf/fpdf.php');
include('fpdf/writeTable.php');


    $nombre_cliente = "Todos los clientes. Reporte General";

//modificada con factura_detalle.precio_venta
$query = 'SELECT p.id_producto, p.descripcion, p.marca, pp.descripcion AS presentacion, SUM(fd.cantidad) as cantidad,
            fd.precio_venta as precio, SUM((fd.cantidad/pp.unidad) * fd.precio_venta ) as total
          FROM producto as p INNER JOIN factura_detalle as fd ON fd.id_prod_serv = p.id_producto
          INNER JOIN presentacion_producto AS pp ON fd.id_presentacion = pp.id_pp
          INNER JOIN factura AS f ON fd.id_factura = f.id_factura
          INNER JOIN cliente as c ON f.id_cliente = c.id_cliente
         ';
//Si se especifico un cliente tomamos el listado de las ventas de un cliente en especifico


//Lo agrupa por producto y presentacion (puede repetirse el mismo producto, pero con presentacion diferente)
//$query .= ' GROUP BY fd.id_prod_serv, fd.id_presentacion ORDER BY p.marca ASC';

$productos = _query($query);

//Cuerpo de la tabla
$data = [];
//Cada fila de la tabla es indicado por key
$key = 0;

//Por cada presentacion de un producto vendida al cliente:
while ($row = _fetch_array($productos)) {
    //Agregamos los valores de la fila
    $data[$key][] = $row['id_producto'];
    $data[$key][] = $row['descripcion'];
    $data[$key][] = $row['marca'];
    $data[$key][] = $row['presentacion'];
    $data[$key][] = number_format($row['cantidad'],0);
    $data[$key][] = '$ ' . number_format($row['precio'],2);
    $data[$key][] = '$ ' . number_format($row['total'], 2);
    $key++;//Incrementamos la fila
}

//$pdf=new exFPDF();
$pdf = new  PDFwT();
$pdf->AliasNbPages();
$pdf->SetFont('arial','',8);
$pdf->SetTopMargin(10);
$pdf->SetLeftMargin(12);
$pdf->SetLineWidth(0.1);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();
$firstDate = date("Y-m-d");
$lastDate = $firstDate ;
//rango de fechas
$rangoFechas =getRangoFechaTexto($firstDate, $lastDate );
//Imformacion general del reporte
$pdf->setReportName('Reporte de ventas por cliente y marca');
$pdf->setSubtitle('Cliente: '."\n".$rangoFechas);

//Cabezeras de la tabla
$header = [];
$header[] = $pdf->th('IdProducto', 'C', 20);
$header[] = $pdf->th('Descripcion', 'C', 65);
$header[] = $pdf->th('Marca', 'C', 25);
$header[] = $pdf->th('Presentacion', 'C', 25);
$header[] = $pdf->th('Cantidad', 'C', 15);
$header[] = $pdf->th('Precio', 'C', 15);
$header[] = $pdf->th('Total', 'C', 25);

$pdf->drawTable($pdf->createTableHeader($header), $data);

$pdf->Output('reporte_producto_cliente.pdf', 'I');
