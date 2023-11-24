<?php

//$tmpdir = sys_get_temp_dir();   # directorio temporal
//$array = $_REQUEST['datosproductos'];
include ("_conexion.php");
$sql="SELECT p.id_producto, p.barcode, p.descripcion,  pp.precio,pp.id_presentacion,
pp.descripcion as descpre, pr.nombre
FROM producto AS p, presentacion_producto AS pp, presentacion AS pr
WHERE  p.id_producto=pp.id_producto
AND pp.id_presentacion=pr.id_presentacion LIMIT 2";
$result=_query($sql);
$n=_num_rows($result);

$puerto=system('ls /dev/usb/lp*');
if ($puerto=='/dev/usb/lp0')
	$printer="/dev/usb/lp0";
else
	$printer="/dev/usb/lp1";



for ($i=0;$i<$n ;$i++){
	$row=_fetch_array($result);
	//d_producto 	barcode 	 	marca 	precio 	presentacion 	descpre
	$id_prod=$row['id_producto'];
	$descripcion=$row['descripcion'];
	$barcode=trim($row['barcode']);
	$nombre=$row['nombre'];
	$precio=sprintf("%.2f",$row['precio']);
	$id_presentacion=$row['id_presentacion'];
	$descpre=$row['descpre'];
	$posx=260; //x,y posicion
	$string.="^XA";
 	$posy=5;
	$string.="^CF0,25";
	$string.="^FO".$posx.",".$posy."^FD"."DISTRIBUIDORA X"."^FS";
	$string.="^CF0,30";
	$string.="^BY2,1";
	$posx=265; $posy+=25;
	$string.="^FO".$posx.",".$posy."^BY2,2";
	$string.="^BCN,80,Y,N,N";
	$string.="^FD".$barcode."-".$id_presentacion."^FS";
	$posy+=110;
	$string.="^CF0,20";
	$string.="^FO".$posx.",".$posy."^FD".$descripcion."^FS";
	$posy+=50;
	$string.="^FO".$posx.",".$posy."^FD".$descpre." ".$nombre."^FS";
	$posx=510;
	$posy+=20;
	$string.="^CF0,30";
	$string.="^FO".$posx.",".$posy."^FD"."$".$precio."^FS";
	$string.="^XZ";
}

$fp=fopen($printer, 'wb');
fwrite($fp, $string);
fclose($fp);

?>
