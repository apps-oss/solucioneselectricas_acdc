<?php



$printer="/dev/usb/lp0";
$st="^XA
^CI28
^CF0,80
^FO70,40^FDavión^FS
^FO90,40^FÑandú^FS
^XZ";
$string="^XA".$st."^XZ";


$fp=fopen($printer, 'wb');
fwrite($fp, $string);
fclose($fp);

?>
