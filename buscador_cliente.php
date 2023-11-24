<?php
include_once "_core.php";
$query = $_REQUEST['query'];
$limit = $_REQUEST['limit'];
$querys=explode(" ",$query);
$num_q = count($querys);
$array = array();
$result=  buscarNombre($query,$limit);
 if($result==false){
    $result=  buscarDui($query,$limit);
 }
 if($result==false){
    $result=  buscarNrc($query,$limit);
 }

 if($result){
    $numrows= _num_rows($result);
  	if ($numrows>0  ){
  	for ($i=0;$i<$numrows;$i++){
  		$row=_fetch_array($result);
				array_push($array,$row);
  	}
  }
}
echo json_encode ($array); //Return the JSON Array
function buscarDui($query,$limit=4){
  $sql0 = "SELECT id_cliente, nombre, direccion dui, nit, nrc  FROM cliente WHERE dui LIKE '%$query%'";
  $sql0 .=" LIMIT $limit";
  $result = _query($sql0);
  $numrows= _num_rows($result);
  if($numrows>0){
    return $result;
  }else {
    return false;
  }
}
function buscarNombre($query,$limit=4){
  $sql0="SELECT id_cliente, nombre, direccion dui, nit, nrc FROM cliente WHERE nombre LIKE '%$query%'";
  $sql0 .=" LIMIT $limit";
  $result = _query($sql0);
  $numrows= _num_rows($result);
  if($numrows>0){
    return $result;
  }else {
    return false;
  }
}
function buscarNrc($query,$limit=4){
  $sql0="SELECT id_cliente, nombre, direccion dui, nit, nrc FROM cliente WHERE nrc LIKE '%$query%'";
  $sql0 .=" LIMIT $limit";
  $result = _query($sql0);
  $numrows= _num_rows($result);
  if($numrows>0){
    return $result;
  }else {
    return false;
  }
}

?>
