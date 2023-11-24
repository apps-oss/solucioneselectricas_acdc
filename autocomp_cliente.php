<?php
include_once "_core.php";
$return_arr = array();
$query =ltrim($_REQUEST['term']);
$id_sucursal=$_SESSION['id_sucursal'];
$array_data = array();
$result=  buscarNombre($query);

 if($result){
    $numrows= _num_rows($result);
  	if ($numrows>0  ){
			$i=0;
			while ($row = _fetch_array($result))
			{
        $array['id_cliente'] = $row['id_cliente'];
    		$array['nombre'] = $row['nombre'];
    		  array_push($return_arr,$array);
				$i++;
			}
  }
  echo json_encode ($return_arr);
}
else {
  	echo json_encode ("");
}



function buscarNombre($query ){
	$sql = "SELECT id_cliente, nombre
					FROM cliente
					WHERE nombre LIKE '%$query%'
				 limit 50
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
