<?php

/**
 * Demostrar lectura de hoja de cálculo o archivo
 * de Excel con PHPSpreadSheet: leer todo el contenido
 * de un archivo de Excel
 *
 * @author parzibyte
 */
# Cargar librerias y cosas necesarias
require_once "vendor/autoload.php";
require('_conexion.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

error_reporting(E_ALL);
# Indicar que usaremos el IOFactory
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "db/InventarioGeneral.xlsx";
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');

verificarCat();
$documento        = $reader->load($rutaArchivo);

# obtener conteo de hojas e iterar por hoja
$totalDeHojas = $documento->getSheetCount();
$fecha_movimiento=date('Y-m-d');
# Iterar hoja por hoja
for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
    # Obtener hoja en el índice que vaya del ciclo
    $sheet = $documento->getSheet($indiceHoja);
    echo "<h3>Datos de productos en la hoja con índice $indiceHoja</h3>";
    # Iterar filas
    $n=1;
    foreach ($sheet->getRowIterator() as $row) {
        //BARCODE	NOMBRE	DESCRIPCION	MARCA	PRESENTACION	PERECEDERO	CATEGORIA	COSTO	PRECIO1	STOCK	DECIMAL(SI LLEVA VENTA POR FRACCION)
        $barcode      = trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()+1));
        $descripcion  = trim($sheet->getCellByColumnAndRow(2, $row->getRowIndex()+1));
        $descpre      = trim($sheet->getCellByColumnAndRow(3, $row->getRowIndex()+1));
        $marca        = strtoupper(trim($sheet->getCellByColumnAndRow(4, $row->getRowIndex()+1)));
        $presentacion = strtoupper(trim($sheet->getCellByColumnAndRow(5, $row->getRowIndex()+1)));
        $perecedero   = trim($sheet->getCellByColumnAndRow(6, $row->getRowIndex()+1));
        $categoria    = trim($sheet->getCellByColumnAndRow(7, $row->getRowIndex()+1));
        $costo        = trim($sheet->getCellByColumnAndRow(8, $row->getRowIndex()+1));
        $precio       = trim($sheet->getCellByColumnAndRow(9, $row->getRowIndex()+1));
        $stock        = trim($sheet->getCellByColumnAndRow(10, $row->getRowIndex()+1));
        $decimal      = trim($sheet->getCellByColumnAndRow(11, $row->getRowIndex()+1));
        $fragil      = trim($sheet->getCellByColumnAndRow(12, $row->getRowIndex()+1));
        $id_presentacion=0;
        $id_categoria=getCategoriaByDesc($categoria);
        $id_marca=0;
        if ($marca=='GENERICO'||$marca=='GENERICA') {
            $id_marca=1;
        }
        if ($presentacion=='UNIDAD') {
            $id_presentacion=1;
        }
        $table = 'producto';
        $form_data = array(
            'descripcion'   => $descripcion,
            'codart'        => $barcode,
            'barcode'       => $barcode,
            'marca'         => $id_marca,
            'minimo'        => 1,
            'exento'        => 0,
            'estado'        => 1,
            'id_proveedor'  => 1,
            'id_categoria'  => $id_categoria,
            'perecedero'    => $perecedero,
            'decimals'      => $decimal,
            'precio' => $precio,
            'costo' => $costo,
            'composicion'   => "_",
            'exclusivo_pedido'=>0,
            'fragil'      => $fragil,
        );
        if ($barcode!="") {
            echo "PRODUCTO:".$n."-". $barcode."-". $descripcion."-".$descpre."<br>";
            $insertar =_insert($table, $form_data);
            if ($insertar) {
                $id_producto = _insert_id();
                $tabla_p = "presentacion_producto";
                $form_pre = array(
                    'id_producto' => $id_producto,
                    'id_presentacion' => $id_presentacion,
                    'descripcion' => $descpre,
                    'unidad' =>1,
                    'precio' => $precio,
                    'costo' => $costo,
                    'activo' => 1,
                    'barcode' => $barcode,
                );
                $insert_pre = _insert($tabla_p, $form_pre);
                if ($insert_pre) {
                    echo "INSERTADO: ".$id_producto." ".$barcode."<br>";
                }
            }
            $table2= 'stock';
            if ($stock>=0) {
                $form_data2 = array(
                'id_producto' => $id_producto,
                'stock' => $stock,
                'stock_local' => $stock,
                'costo_unitario'=>$costo,
                'precio_unitario'=>$precio,
                'create_date'=>$fecha_movimiento,
                'update_date'=>$fecha_movimiento,
                'id_sucursal' => 1
                );
                $insert_stock = _insert($table2, $form_data2);
            }
            $form_data_su = array(
                'id_producto' => $id_producto,
                'id_sucursal' => 1,
                'cantidad' => $stock,
                'id_ubicacion' => 1,
              );
            $table_su = "stock_ubicacion";
            $insert_su = _insert($table_su, $form_data_su);
            $id_su=_insert_id();
        }
        $n++;
    }
}

function getCategoriaByDesc($descrip)
{
    $q="SELECT id_categoria,nombre_cat FROM categoria WHERE nombre_cat ='$descrip'";
    $res=_query($q);
    $id_categoria="-1";
    while ($row=_fetch_array($res)) {
        $id_categoria=$row['id_categoria'];
    }
    return $id_categoria;
}
function verificarCat()
{
    $rutaArchivo = "db/categoria.xlsx";
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $documento        = $reader->load($rutaArchivo);
    # Recuerda que un documento puede tener múltiples hojas
    # obtener conteo e iterar
    $totalDeHojas = $documento->getSheetCount();
    # Iterar hoja por hoja
    for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
        # Obtener hoja en el índice que vaya del ciclo
        $sheet = $documento->getSheet($indiceHoja);
        # Iterar filas
        foreach ($sheet->getRowIterator() as $row) {
            $cat         = trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()));
            $id_cat=getCategoriaByDesc($cat);
            $t='categoria';
            if ($id_cat=='-1') {
                $f = array(
                'nombre_cat' => $cat,
                'descripcion' => $cat,
                'tienda' => 1,
                'pesable' => 0,
                );
                $insert = _insert($t, $f);
                if ($insert) {
                    echo "CAT INSERTADA ".$cat."<BR>";
                }
            }
        }
    }
}
