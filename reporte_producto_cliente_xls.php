<?php
require('_conexion.php');
require_once "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setCreator("Luis J. Aguilar")
    ->setLastModifiedBy('x')
    ->setTitle('Archivo de Pructos por Cliente')
    ->setDescription('Un archivo de Excel exportado desde Mariadb');

  $titulos=[];
  $titulos[]  = "OPERACIONES DG";
  $titulos[] = "REPORTE DE VENTAS POR CLIENTE Y MARCA";

  //Cabezeras de la tabla
  $header = [];
  $header[] = 'No.';
  $header[] = 'IdProducto';
  $header[] = 'Descripcion';
  $header[] = 'Marca';
  $header[] = 'Presentacion';
  $header[] = 'Cantidad';
  $header[] = 'Precio';
  $header[] = 'Total';
  $numeroDeFila = 3;
# Como ya hay una hoja por defecto, la obtenemos, no la creamos
$hojaDeProductos = $documento->getActiveSheet();
$hojaDeProductos->setTitle("Productos");

$total=0;
$id_cliente = $_REQUEST['id_cliente'];
$marca      = $_REQUEST['marca'];
$firstDate  = $_REQUEST['mes_inicial'];
$lastDate   = $_REQUEST['mes_final'];


//Si se seleciono un cliente en especifico buscamos el nombre, de otra forma es un reporte general
if($id_cliente != -1){
    $query_cliente = _query('SELECT nombre FROM cliente WHERE id_cliente = ' . $id_cliente);
    $nombre_cliente = _fetch_array($query_cliente)['nombre'];
}else
    $nombre_cliente = "Todos los clientes. Reporte General";
$titulos[] =$nombre_cliente;
$titulos[] =getRangoFechaTexto($firstDate, $lastDate );
if (!empty($titulos)) {
    foreach ($titulos as $itemTitle) {
      $hojaDeProductos->mergeCells('A'.$numeroDeFila.':G'. $numeroDeFila);
      $hojaDeProductos->setCellValueByColumnAndRow(1, $numeroDeFila, $itemTitle);
      $numeroDeFila++;
    }
}
$numeroDeFila++;
$hojaDeProductos->fromArray($header,1, 'A'.$numeroDeFila);
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
    $i = 0;
    $total =  0;
  while ($row = _fetch_array($productos)) {
    //Agregamos los valores de la fila
    $key++;//Incrementamos la fila
    $i++;
    $numeroDeFila++;
    # Escribirlos en el documento
    $hojaDeProductos->setCellValueByColumnAndRow(1, $numeroDeFila, $i);
    $hojaDeProductos->setCellValueByColumnAndRow(2, $numeroDeFila, $row['id_producto']);
    $hojaDeProductos->setCellValueByColumnAndRow(3, $numeroDeFila,$row['descripcion']);
    $hojaDeProductos->setCellValueByColumnAndRow(4, $numeroDeFila, utf8_decode( $row['marca']));
    $hojaDeProductos->setCellValueByColumnAndRow(5, $numeroDeFila, utf8_decode($row['presentacion']));
    $hojaDeProductos->setCellValueByColumnAndRow(6, $numeroDeFila, $row['cantidad']);
    $hojaDeProductos->setCellValueByColumnAndRow(7, $numeroDeFila, $row['precio']);
    $hojaDeProductos->setCellValueByColumnAndRow(8, $numeroDeFila, $row['total']);
    $total = $total+$row['total'];

  }
$numeroDeFila++;

for($j=0;$j<5;$j++){
  $hojaDeProductos->setCellValueByColumnAndRow($j, $numeroDeFila, "");
}
$hojaDeProductos->setCellValueByColumnAndRow(7, $numeroDeFila,"TOTAL:");
$hojaDeProductos->setCellValueByColumnAndRow(8,  $numeroDeFila,round($total,2));

# Crear un "escritor"
$writer = new Xlsx($documento);
# Le pasamos la ruta de guardado
//$writer->save('productos_cliente_fechas.xlsx');
$fileName = 'productos_cliente_fechas.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');

        ob_end_clean();
        $writer->save('php://output');
//echo "fin";
?>
