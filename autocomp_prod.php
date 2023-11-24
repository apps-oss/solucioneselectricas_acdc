<?php

include_once "_core.php";
$query = $_REQUEST['term2'];
$pedido = $_REQUEST['pedido'];
$id_sucursal=$_SESSION['id_sucursal'];
$return_arr = array();

 $result=  buscar($query, $id_sucursal, "d");
 if ($result==false) {
     $result=  buscar($query, $id_sucursal, "b");
 }
 if ($result==false) {
     $result=  buscarServicio($query);
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
             $array['tipo'] = $row['tipo'];
             array_push($return_arr, $array);
             $i++;
         }
     }
     echo json_encode($return_arr);
 } else {
     echo json_encode("");
 }

 function buscar($query, $id_sucursal, $tipo)
 {
     $sql="SELECT p.id_producto as id, p.descripcion, p.barcode, 'P' AS tipo
            FROM producto AS p
            JOIN stock  AS s on s.id_producto=p.id_producto
            JOIN categoria AS cat on cat.id_categoria=p.id_categoria
            WHERE  s.id_sucursal='$id_sucursal'
            AND s.stock>0 
            AND ";

     if ($tipo=='b') {
         $sql.=" p.barcode LIKE '%$query%'";
     } else {
         $sql.="  p.descripcion LIKE '%$query%' ";
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
function buscarServicio($query)
{
    $sql="SELECT  id_servicio AS id , descripcion, 'SERVICIO' AS  barcode,'S' AS tipo
     FROM servicios";
    $sql.="  WHERE descripcion LIKE '%$query%' ";
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
