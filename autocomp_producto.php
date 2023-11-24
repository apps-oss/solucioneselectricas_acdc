<?php

include_once "_core.php";
$query = $_REQUEST['query'];
$id_sucursal=$_SESSION['id_sucursal'];
$array_data = array();
$result=  buscarDescrip($query, $id_sucursal);
 if ($result==false) {
     $result=  buscarBarcode($query, $id_sucursal);
 }

 if ($result) {
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
             $array_prod[$i] = array('producto'=>$row['id']."|".$barcod.$row['descripcion']);
             $i++;
         }
     }
     echo json_encode($array_prod); //Return the JSON Array
 } else {
    echo json_encode("");
}


function buscarBarcode($query, $id_sucursal)
{
    $sql="SELECT p.id_producto as id, p.descripcion, p.barcode
					FROM producto AS p
					JOIN stock  AS s on s.id_producto=p.id_producto
					JOIN categoria AS cat on cat.id_categoria=p.id_categoria
					WHERE p.barcode LIKE '%$query%'
					AND s.id_sucursal='$id_sucursal'
					AND s.stock>0 limit 100
					";
    //WHERE p.barcode='$query'
    $result = _query($sql);
    $numrows= _num_rows($result);
    if ($numrows>0) {
        return $result;
    } else {
        return false;
    }
}
function buscarDescrip($query, $id_sucursal)
{
    $sql = "SELECT p.id_producto as id, p.descripcion, p.barcode
					FROM producto AS p
					JOIN stock  AS s ON  s.id_producto=p.id_producto
					JOIN categoria AS cat on cat.id_categoria=p.id_categoria
					WHERE p.descripcion LIKE '%$query%'
					AND s.id_sucursal='$id_sucursal'
					AND s.stock>0 limit 100
					";
    $result = _query($sql);
    $numrows= _num_rows($result);
    if ($numrows>0) {
        return $result;
    } else {
        return false;
    }
}
