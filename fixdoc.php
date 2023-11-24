<?php
include_once '_conexion.php';


$sql = _query("SELECT detalle_compra.*,presentacion_producto.unidad,producto.descripcion FROM `detalle_compra` JOIN presentacion_producto ON presentacion_producto.id_pp=detalle_compra.id_presentacion JOIN producto ON producto.id_producto=detalle_compra.id_producto ORDER BY detalle_compra.id_producto");

while ($row = _fetch_array($sql)) {

  if (($row['cantidad']/$row['unidad'])*$row['ultcosto'] != $row['subtotal'])
  {
    if( abs((($row['cantidad']/$row['unidad'])*$row['ultcosto']) - $row['subtotal'])>=0.12)
    {
      echo $row['descripcion']." id_producto: ".$row['id_producto']." CALCULO: ".(($row['cantidad']/$row['unidad'])*$row['ultcosto'])." COMPRA: ".$row['subtotal'];
      echo " ERROR <BR>";

      $sql_presen_sel = _query("SELECT * FROM presentacion_producto where id_producto = $row[id_producto] ");

      while ($row2 = _fetch_array($sql_presen_sel)) {
        // code...
        if( abs((($row['cantidad']/$row2['unidad'])*$row['ultcosto']) - $row['subtotal'])<=0.12)
        {
          echo "SOLUCION ENCONTRADA $row2[id_pp] $row2[unidad]<br>";
          echo "SETEANDO NUEVO costo";
          _update_s("detalle_compra",array('id_presentacion' => $row2['id_pp']),"id_det_compra = $row[id_det_compra]" );
          $costo_u = $row['ultcosto'] / $row['cantidad'];

          $sql_presen_sel2 = _query("SELECT * FROM presentacion_producto where id_producto = $row[id_producto] ");

          while ($row3 = _fetch_array($sql_presen_sel2)) {
            _update_s("presentacion_producto",array('costo' => ($costo_u * $row3['unidad'])),"id_pp = $row3[id_pp]" );
          }
        }
      }
    }
  }
}
 ?>
