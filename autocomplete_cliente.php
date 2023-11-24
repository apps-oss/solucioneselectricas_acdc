<?php
	include_once "_core.php";
	$query = $_REQUEST["query"];
	$entrada=_query("SELECT id_cliente,nombre FROM cliente WHERE  nombre LIKE '%$query%' ORDER BY nombre LIMIT 20 ");
	$datos= array();
	//$array_prod[] = array();
		$i=0;
	while($raw=_fetch_array($entrada))
		{
			$id_cliente=$raw["id_cliente"];
			$nombre = $raw["nombre"];
			$datos[]=$id_cliente."| ".$nombre;
				$i++;
		}
		echo json_encode($datos);
?>
