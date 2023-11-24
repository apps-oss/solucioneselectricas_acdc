<?php
include_once "_core.php";
$return_arr = array();
$query =ltrim($_REQUEST['term']);
$id_sucursal=$_SESSION['id_sucursal'];
$array_data = array();
$result=  buscarDescrip($query,$id_sucursal);

 if($result){
    $numrows= _num_rows($result);
  	if ($numrows>0  ){
			$i=0;
			while ($row = _fetch_array($result))
			{
        $array_prod['id'] =  $row['id_producto'];
        $array_prod['id_producto'] = $row['id_producto'];
    		$array_prod['descripcion'] = $row['descripcion'];
    		  array_push($return_arr,$array_prod);
				$i++;
			}
  }
  echo json_encode ($return_arr);
}
else {
  	echo json_encode ("");
}


function buscarBarcode($query,$id_sucursal){
	$sql="SELECT p.id_producto, p.descripcion, p.barcode
					FROM producto AS p
					JOIN stock  AS s on s.id_producto=p.id_producto
					JOIN categoria AS cat on cat.id_categoria=p.id_categoria
					WHERE p.barcode='$query'
					AND s.id_sucursal='$id_sucursal'
					AND cat.pista=1
					AND s.stock>0 limit 100
					";
  $result = _query($sql);
  $numrows= _num_rows($result);
  if($numrows>0){
    return $result;
  }else {
    return false;
  }
}
function buscarDescrip($query,$id_sucursal){
	$sql = "SELECT p.id_producto , p.descripcion, p.barcode
					FROM producto AS p
					JOIN stock  AS s ON  s.id_producto=p.id_producto
					JOIN categoria AS cat on cat.id_categoria=p.id_categoria
					WHERE p.descripcion LIKE '%$query%'

					AND s.id_sucursal='$id_sucursal'
					AND cat.pista=1
          AND cat.combustible=0
					AND s.stock>0 limit 5
					";
  $result = _query($sql);
  $numrows= _num_rows($result);
  if($numrows>0){
    return $result;
  }else {
    return false;
  }
}

?>
