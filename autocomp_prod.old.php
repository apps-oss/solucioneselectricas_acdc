<?php

include_once "_core.php";
$query = $_REQUEST['term2'];
$pedido = $_REQUEST['pedido'];
$id_sucursal=$_SESSION['id_sucursal'];
$return_arr = array();
$result=  buscarDescrip($query, $id_sucursal, $pedido);
 if ($result==false) {
     $result=  buscarBarcode($query, $id_sucursal, $pedido);
 }

 if ($result) {
     $barcod=" ";
     $numrows= _num_rows($result);
     if ($numrows>0) {
         $array_prod[] = array();
         $i=0;
         while ($row = _fetch_array($result)) {
             if ($row['barcode']=="") {
                 $barcod=" ";
             } else {
                 $barcod=" [".$row['barcode']."] ";
             }
             $array['id'] = $row['id'];
             $array['descripcion'] = $barcod. $row['descripcion'];
             array_push($return_arr, $array);
             $i++;
         }
     }
     echo json_encode($return_arr);
 } else {
     echo json_encode("");
 }


function buscarBarcode($query, $id_sucursal, $pedido=0)
{
    $sql="SELECT p.id_producto as id, p.descripcion, p.barcode
					FROM producto AS p
					JOIN stock  AS s on s.id_producto=p.id_producto
					JOIN categoria AS cat on cat.id_categoria=p.id_categoria
					WHERE p.barcode LIKE '%$query%'
					AND s.id_sucursal='$id_sucursal'
					AND s.stock>0 ";
    if ($pedido==1) {
        $sql.="AND p.exclusivo_pedido='$pedido'";
    }

    $sql.=" LIMIT 60";
    //WHERE p.barcode='$query'
    $result = _query($sql);
    $numrows= _num_rows($result);
    if ($numrows>0) {
        return $result;
    } else {
        return false;
    }
}
function buscarDescrip($query, $id_sucursal, $pedido=0)
{
    $sql = "SELECT p.id_producto as id, p.descripcion, p.barcode
					FROM producto AS p
					JOIN stock  AS s ON  s.id_producto=p.id_producto
					JOIN categoria AS cat on cat.id_categoria=p.id_categoria
					WHERE p.descripcion LIKE '%$query%'
					AND s.id_sucursal='$id_sucursal'
					AND s.stock>0 ";
    if ($pedido==1) {
        $sql.="AND p.exclusivo_pedido='$pedido'";
    }

    $sql.=" LIMIT 60";
    $result = _query($sql);
    $numrows= _num_rows($result);
    if ($numrows>0) {
        return $result;
    } else {
        return false;
    }
}
