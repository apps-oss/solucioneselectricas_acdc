<?php
ini_set('display_errors', true);
error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/oss_pdf.php');

class PDF extends OSS_PDF
{
    private $proveedor;

    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    }

    public function cabeceraTabla()
    {
        $this->SetFont('Arial', '', 8);
        $cabecera_tabla = [
            utf8_decode('CODIGO'),
            utf8_decode('PRODUCTO'),
            utf8_decode('PRESENTACIÓN'),
            utf8_decode('DESCRIPCIÓN'),
            utf8_decode('UBICACIÓN'),
            utf8_decode('COSTO'),
            utf8_decode('PRECIO'),
            utf8_decode('EXISTENCIA'),
            utf8_decode('TOTAL($)')
        ];
        $this->SetAligns(['C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C']);
        $this->SetWidths([25, 75, 35, 30, 25, 15, 15, 20, 20]);
        $this->Row($cabecera_tabla);
        $x = $this->GetX();
        $y = $this->GetY();
        $this->Line($x, $y, $x + 260, $y);
    }

    function Footer()
    {
        // Go to 1.5 cm from the bottom
        $this->SetY(-10);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print centered page number
        $fech = date("d/m/Y");

        $impress = "";
        $id_proveedor = $this->proveedor;
        if ($id_proveedor != 0) {
            $sql_proveedor = "SELECT nombre FROM proveedor WHERE id_proveedor=$id_proveedor";
            $row = _fetch_array(_query($sql_proveedor));
            $nombre_prove = $row['nombre'];
            $impress = "REPORTE DE INVENTARIO  DEl PROVEEDOR " . $nombre_prove . " del dia " . $fech;
        } else {
            $impress = "REPORTE DE INVENTARIO " . $fech;
        }

        $mitad = ($this->GetPageWidth() / 2) - 10;
        $this->cell($mitad, 5, utf8_decode($impress), 0, 0, 'L');
        $this->cell($mitad, 5, utf8_decode('Pagina ' . $this->PageNo() . ' de ' . "{nb}"), 0, 0, 'R');
    }
}

$id_sucursal = $_SESSION["id_sucursal"];
$id_proveedor = $_GET['id_proveedor'];
$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
$resultado_emp = _query($sql_empresa);
$row_emp = _fetch_array($resultado_emp);

$pdf = new PDF('L', 'mm', 'Letter');
$pdf->SetMargins(10, 10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 10);
$pdf->setSucursal($row_emp);
$pdf->setProveedor($id_proveedor);
$pdf->setSizeFont(8);
$pdf->setTituloReporte("REPORTE DE INVENTARIO");
$pdf->AddFont("latin", "", "latin.php");
$pdf->AddPage();

$total_general = 0;

$sql_stock = "";
//if($id_proveedor==0){
$sql_stock = _query("SELECT pr.id_proveedor, pr.id_producto,pr.descripcion, pr.barcode, c.nombre_cat as cat, SUM(su.cantidad) as cantidad
    FROM producto AS pr
    LEFT JOIN categoria AS c ON pr.id_categoria=c.id_categoria
    JOIN stock_ubicacion AS su ON pr.id_producto=su.id_producto
    WHERE su.id_sucursal='$id_sucursal' GROUP BY su.id_producto ORDER BY pr.descripcion");
//}
/*
else if($id_proveedor!=0){
    $sql_stock = _query("SELECT pr.id_proveedor, pr.id_producto,pr.descripcion, pr.barcode, c.nombre_cat as cat, SUM(su.stock) as cantidad
                     FROM producto AS pr
                     LEFT JOIN categoria AS c ON pr.id_categoria=c.id_categoria
                     JOIN stock AS su ON pr.id_producto=su.id_producto
                     WHERE su.id_sucursal='$id_sucursal' AND pr.id_proveedor=$id_proveedor GROUP BY su.id_producto ORDER BY pr.descripcion");
}*/


$contar = _num_rows($sql_stock);
if ($contar > 0) {
    while ($row = _fetch_array($sql_stock)) {
        $id_producto = $row['id_producto'];
        $descripcion = $row["descripcion"];
        $cat = $row['cat'];
        $barcode = $row['barcode'];
        $existencias = $row['cantidad'];
        $estante = 'NO ASIGNADO';
        $posicion = '';

        $sql_pres = _query("SELECT pp.*, p.nombre as descripcion_pr
            FROM presentacion_producto as pp, presentacion as p
            WHERE pp.id_presentacion=p.id_presentacion
            AND pp.id_producto='$id_producto'
            ORDER BY pp.unidad DESC");
        $npres = _num_rows($sql_pres);

        $exis = 0;
        $n = 0;
        $p = 0;
        $s = 0;


        $barcode = utf8_decode($barcode);
        $descripcion = utf8_decode($descripcion);

        while ($rowb = _fetch_array($sql_pres)) {
            $unidad = $rowb["unidad"];
            $costo = $rowb["costo"];
            $precio = $rowb["precio"];
            $descripcion_pr = $rowb["descripcion"];
            $presentacion = $rowb["descripcion_pr"];
            if ($existencias >= $unidad) {
                $exis = intdiv($existencias, $unidad);
                $existencias = $existencias % $unidad;
            } else {
                $exis =  0;
            }
            $total_costo = round(($costo) * $exis, 4);
            $total_general += $total_costo;
            $p += 5;
            $s += 1;
            $data = [
                $barcode,
                $descripcion,
                utf8_decode($presentacion),
                utf8_decode($descripcion_pr),
                utf8_decode("$estante" . " " . "$posicion"),
                utf8_decode(number_format($costo, 2)),
                utf8_decode(number_format($precio, 2)),
                utf8_decode($exis),
                utf8_decode(number_format($total_costo, 4))
            ];

            $pdf->SetAligns(['L', 'L', 'C', 'C', 'C', 'R', 'R', 'C', 'R']);
            $pdf->SetWidths([25, 75, 35, 30, 25, 15, 15, 20, 20]);
            $pdf->Row($data);
        }
    }

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->Line($x, $y, $x + 260, $y);

    $pdf->SetWidths([210, 50]);
    $pdf->SetAligns(['L', 'R']);
    $pdf->Row(['TOTAL', utf8_decode("$" . number_format($total_general, 4))]);
}

ob_clean();
$pdf->Output("reporte_inventario.pdf", "I");
