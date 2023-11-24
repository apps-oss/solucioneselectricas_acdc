<?php
include_once "_core.php";
$query = $_REQUEST['query'];
$id_sucursal=$_SESSION['id_sucursal'];
$sql0="SELECT id_cliente, nombre FROM `cliente` WHERE nombre LIKE '%$query%'";
$result = _query($sql0);

if (_num_rows($result)==0) {
	# code...
	echo json_encode ("");
}
else {
$array_prod[] = array();
$i=0;
while ($row1 = _fetch_array($result))
{

	$array_prod[$i] = array('cliente'=>$row1['id_cliente']."|".$row1['nombre']);
	$i++;
}
	echo json_encode ($array_prod);
}

?>