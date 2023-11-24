<?php
error_reporting(E_ERROR | E_PARSE);
require('_core.php');
require('fpdf/fpdf.php');
/*
$id_cliente = $_REQUEST['id_cliente'];
$marca      = $_REQUEST['marca'];
$firstDate  = $_REQUEST['mes_inicial'];
$lastDate   = $_REQUEST['mes_final'];
*/
$firstDate  = $_REQUEST['mes_inicial'];
$lastDate   = $_REQUEST['mes_final'];
//Si se seleciono un cliente en especifico buscamos el nombre, de otra forma es un reporte general
if($id_cliente != -1){
    $query_cliente = _query('SELECT nombre FROM cliente WHERE id_cliente = ' . $id_cliente);
    $nombre_cliente = _fetch_array($query_cliente)['nombre'];
}else
    $nombre_cliente = "Todos los clientes. Reporte General";

//Query base, selecciona el producto, su descripcion, marca y presentacion
//ademas de la cantidad vendida, precio por su presentacion y el total ganado del producto.
//Filtra por el rango de fechas especificado y la marca seleccionada.
//original
/*
$query = 'SELECT p.id_producto, p.descripcion, p.marca, pp.descripcion AS presentacion, SUM(fd.cantidad) as cantidad,
            pp.precio, SUM(fd.cantidad * pp.precio) as total
          FROM producto as p INNER JOIN factura_detalle as fd ON fd.id_prod_serv = p.id_producto
          INNER JOIN presentacion_producto AS pp ON fd.id_presentacion = pp.id_pp
          INNER JOIN factura AS f ON fd.id_factura = f.id_factura
          INNER JOIN cliente as c ON f.id_cliente = c.id_cliente
          WHERE f.fecha BETWEEN "'.$firstDate.'" AND "'.$lastDate . '"
          AND p.marca LIKE "%'.$marca.'%" ';
*/
//modificada con factura_detalle.precio_venta
$query = 'SELECT p.id_producto, p.descripcion, p.marca, pp.descripcion AS presentacion, SUM(fd.cantidad) as cantidad,
            fd.precio_venta as precio, SUM((fd.cantidad/pp.unidad) * fd.precio_venta ) as total
          FROM producto as p INNER JOIN factura_detalle as fd ON fd.id_prod_serv = p.id_producto
          INNER JOIN presentacion_producto AS pp ON fd.id_presentacion = pp.id_pp
          INNER JOIN factura AS f ON fd.id_factura = f.id_factura
          INNER JOIN cliente as c ON f.id_cliente = c.id_cliente
          WHERE f.fecha BETWEEN "'.$firstDate.'" AND "'.$lastDate . '"
          AND p.marca LIKE "%'.$marca.'%" ';
//Si se especifico un cliente tomamos el listado de las ventas de un cliente en especifico
if($id_cliente != -1)
    $query .= ' AND f.id_cliente =' . $id_cliente;

//Lo agrupa por producto y presentacion (puede repetirse el mismo producto, pero con presentacion diferente)
$query .= ' GROUP BY fd.id_prod_serv, fd.id_presentacion ORDER BY p.marca ASC';

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

$pdf = new PDF('P', 'mm', 'letter');
//rango de fechas
$rangoFechas =getRangoFechaTexto($firstDate, $lastDate );
//Imformacion general del reporte
$pdf->setReportName('Reporte de ventas por cliente y marca');
$pdf->setSubtitle('Cliente: ' . $nombre_cliente."\n".$rangoFechas);
$pdf->AddPage();
$pdf->SetFontSize(8);

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
