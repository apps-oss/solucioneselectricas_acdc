<?php
include_once '_conexion.php';
#UPDATE  `factura` SET id_usuario=2,id_empleado=2,turno=1,id_apertura=1293 WHERE fecha='2021-02-11' AND numero_doc LIKE "%tik%"
$datos = _query("SELECT * FROM `factura` WHERE factura.tipo_documento IN ('TIK') AND factura.finalizada=1 AND fecha between '2021-06-30' AND '2021-07-12' ORDER BY factura.id_factura ASC");
  $corelativo = 632;
while($row = _fetch_array($datos))
{
  echo "$row[numero_doc] -> ".str_pad($corelativo,10,0,STR_PAD_LEFT)."_".$row['tipo_documento']."<br>";
  _update("factura",array("numero_doc"=>str_pad($corelativo,10,0,STR_PAD_LEFT)."_TIK","num_fact_impresa"=>$corelativo),"id_factura=$row[id_factura]");
  $corelativo++;
}
?>
