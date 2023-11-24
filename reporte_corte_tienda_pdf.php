<?php
error_reporting(E_ERROR | E_PARSE);
include('_core.php');
include 'easytable/fpdf.php';
include 'easytable/exfpdf.php';
include 'easytable/easyTable.php';
class PDF extends exFPDF
{
  public function Footer()
  {
      // Go to 1.5 cm from bottom
      $this->SetY(-15);
      // Select Arial italic 8
      $this->SetFont('Arial','I',6);
      // Print centered page number
      $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
      parent::Footer();
  }
}
$pdf=new exFPDF();
$pdf->AliasNbPages();
$pdf->SetFont('arial','',6);
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(12);
$pdf->SetLineWidth(0.07);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();
$fecha=date('Y-m-d');
$id_sucursal = $_SESSION["id_sucursal"];
$id_lectura  = $_REQUEST['id_lectura'];
$id_user = $_SESSION["id_usuario"];
$admin   = $_SESSION["admin"];
//por corte
if (isset( $_REQUEST['id_corte'])){
  $id_apertura  = $_REQUEST['id_apertura'];
  $rowApp       = getByCorteAp($id_corte);
  $id_lectura   = $rowApp['id_lectura'];
}
if (isset( $_REQUEST['id_apertura'])){
  $id_apertura  = $_REQUEST['id_apertura'];
  $rowApp       = getByApertura($id_apertura);
  $id_lectura   = $rowApp['id_lectura'];
}

//Datos de empresa
$datos_suc = datos_sucursal($id_sucursal);
$nombre_suc = $datos_suc['descripcion'];
$logo = getLogo();
$inicio=$fecha;
//datos apertura
$row_ap = getDatosApNoVigente($id_apertura);
$caja   = $row_ap['caja'];
$turno  = $row_ap['turno'];
$id_cajero = $row_ap['id_empleado'];
$cajero    ="CAJERO: ". getCajero($id_cajero);
$row_caja = getDatosCaja($caja);
$nombre_caja = "CAJA: ". $row_caja['nombre'];
$datos_apertura = $nombre_caja ." TURNO: ".$turno;
$title_report=" REPORTE DE CORTE, ARQUEO Y LIQUIDACION ";
$pdf =setLogos($pdf,$logo);
//rango dias_fechas
$rango =getRangoFechaTexto($inicio,$inicio);
$headers='{10,20,40,20,20,20,20,20,20}';
$headtitles = '{190,10}';
$r_user =  getUser($id_user,$admin=0);
$row_user=_fetch_array($r_user);
$nombre_user =  $row_user['nombre'];

$array_comb = getCombustible();
$n_comb = count($array_comb) ;
$table = new easyTable($pdf, $headtitles);
$table = getTitle($table,$nombre_suc,$title_report,$rango,$cajero,$datos_apertura);
$table->endTable();

$pdf->ln(1);
// FIN LECTURA DE TANQUE INICIAL

$headers='{10,40,20,20,5,40,30}';
$table = new easyTable($pdf, $headers,'border:1;');
$table = getHeaders2($table);
$result= getArqueoCorte($id_apertura);
$count=_num_rows($result);
$mid=round($count/2,0)+1;
//DATOS CORTE
$row = getCorteAp($id_apertura);
if($row!=NULL){
  //$row = _fetch_array($res3);
  $turno = $row["turno"];
  $total_cobro = $row["total_cobro"];
  $total_mora = $row["total_mora"];
  $vales = $row["vales"];
  $ingresos = $row["ingresos"];
  $id_apertura = $row["id_apertura"];
  $monto_apertura = $row["cashinicial"];
  $fecha_corte = $row["fecha_corte"];
  $hora_corte = $row["hora_corte"];
  $saldo_caja = $row["saldo_caja"];
  $faltante = $row["faltante"];
  $sobrante = $row["sobrante"];
  $nombre_empleado = $row["nombre_empleado"];
  $nombre_caja = $row["nombre_caja"];
  $cajas = $row["caja"];
  $totalt = $row["totalt"];
  $totalf = $row["totalf"];
  $totalcf = $row["totalcf"];
  $id_cajeros = $row["id_empleado"];
  $total_doc = number_format($totalt+$totalf+$totalcf, 2,'.','');
  $total_cm = $total_cobro + $total_mora;
  $total_mcaja = $ingresos - $vales;
  $total_vf2 = getTotalFactPago($id_apertura);
  $total_fin = $total_vf2  + $monto_apertura ;
}

/* datos LIQUIDACION */

/* abonos*/
$total_abonos =getAbonosTotal($id_apertura);
//total ingresos
$total_ingresos =$total_vf2  + $monto_apertura;
$array_liquida= array();
$saldo_inicial = 0.00;
//CREDITOS
$total_creditos=getbyTipoPago($id_apertura, 'CRE');
//CUPONES (O VALES)
$total_cupones=getbyTipoPago($id_apertura, 'VAL');
//arqueo de caja
$total_arqueo=totalArqueo($id_apertura);
//abono a creditos de prov.
$pagos =abonosCreditoTotal($fecha);
$linea0= "";
//EFECTIVO
$efectivo=getbyTipoPago($id_apertura, 'CON');
//vales
$total_vales= getMovCajaTotal($id_apertura,1);
//total egresos
$total_egresos = $total_creditos + $total_cupones + $total_arqueo + $pagos  +$total_vales;
//total caja
$total_caja =  $total_ingresos - $total_egresos;
//diferencia
$diferencia = $total_caja - $efectivo;
//


array_push($array_liquida, $linea0);
$linea1=["SALDO INICIAL:"," $ ".$monto_apertura];
array_push($array_liquida, $linea1);

$linea1=["TOTAL VENTA:"," $ ".$total_vf2];
array_push($array_liquida, $linea1);
$linea1=["ABONOS:"," $ ".$total_abonos];
array_push($array_liquida, $linea1);
$linea1=["TOTAL INGRESO:"," $ ".$total_ingresos];
array_push($array_liquida, $linea1);
$linea1=["CREDITOS:"," $ ".$total_creditos];
array_push($array_liquida, $linea1);
$linea1=["CUPONES:"," $ ".$total_cupones];
array_push($array_liquida, $linea1);
$linea1=["REMESAS:"," $ ".$total_arqueo];
array_push($array_liquida, $linea1);
$linea1=["PAGOS:"," $ ".$pagos];
array_push($array_liquida, $linea1);
$linea1=["VALES:"," $ ".$total_vales]; //vales son egreso solo de tienda !!!
array_push($array_liquida, $linea1);
$linea1=["TOTAL EGRESOS:"," $ ".$total_egresos];
array_push($array_liquida, $linea1);
$linea1=["TOTAL EN CAJA:"," $ ".$total_caja];
array_push($array_liquida, $linea1);
$linea1=["EFECTIVO:"," $ ".$efectivo];
array_push($array_liquida, $linea1);
$linea1=["DIFERENCIA:"," $ ".$diferencia];
array_push($array_liquida, $linea1);

if ($count>0){
    $total_dinero =0;
    for($i=1;$i<=$count;$i++){
          $row1=_fetch_array($result);
          $table->rowStyle('valign:M;border:LRB;font-size:6' );
          $table->easyCell($i,'align:C;');
          $table->easyCell(utf8_decode($row1['descripcion']),'align:L;');
          $table->easyCell(sprintf("%.2f",$row1['cantidad']),'align:R;');
          $table->easyCell("$ ".$row1['subtotal'],'align:R;');
          $table->rowStyle('valign:M;border:0;font-size:6' );
          $table->easyCell("  ",'align:R;');
          $table->rowStyle('valign:M;border:LRB;font-size:6' );
          $table->easyCell($array_liquida[$i][0],'align:C;');
          $table->easyCell($array_liquida[$i][1],'align:R;');
          $table->printRow();
    }
    /*
    $total_dinero= $row1['total'];
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell("TOTAL",'align:C;colspan:3;');
    $table->easyCell( "<b>$ ".sprintf("%.2f",$total_dinero)."</b>",'align:R;');
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:R;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[10][0],'align:C;');
    $table->easyCell($array_liquida[10][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[11][0],'align:C;');
    $table->easyCell($array_liquida[11][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[12][0],'align:C;');
    $table->easyCell($array_liquida[12][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[15][0],'align:C;');
    $table->easyCell($array_liquida[15][1],'align:R;');
    $table->printRow();
    */
}
$table->endTable();
$pdf->ln(1);

//tabla consolidada
/*
$headers2='{25,25,25,25,25,25,25,25}';
$headers2='{25,25,25,25}';
$table = new easyTable($pdf, $headers2,'border:1;');
$total_vf = 0;
for($j=0;$j<$count2;$j++){
  $row2=_fetch_array($res2);
  $total_vf += $row2['total_venta'];
}
  $table->easyCell("SALDO INICIAL",'align:C;colspan:1;');
  $table->easyCell("TOTAL FACT.",'align:C;colspan:1;');
  $table->easyCell("TOTAL FINAL",'align:C;colspan:1;');
  $table->easyCell("TOTAL ARQUEO",'align:C;colspan:1;');
  $table->printRow();
  $table->easyCell( "<b>$ ".sprintf("%.2f",$monto_apertura)."</b>",'align:R;');
  $table->easyCell( "<b>$ ".sprintf("%.2f",$total_vf2)."</b>",'align:R;');
  $table->easyCell( "<b>$ ".sprintf("%.2f",$total_fin)."</b>",'align:R;');
  $table->easyCell( "<b>$ ".sprintf("%.2f",$total_dinero)."</b>",'align:R;');
  $table->printRow();
  $table->endTable();
*/
$pdf->Output();

function getHeaders($table){
  $style ='align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000;';
  $style .=' font-family:arial;font-size:6;font-style:B; ';
  $table->rowStyle($style);
  $style = ' border:1';
  $table->rowStyle($style);
  $table->easyCell('<b>No.</b>','align:C;');
  $table->easyCell('<b>BOMBA</b>','align:C;');
  $table->easyCell('<b>COMBUSTIBLE</b>','align:C;');
  $table->easyCell('<b>LECT. INI.</b>','align:C;');
  $table->easyCell('<b>LECT. FIN.</b> ','align:C;');
  $table->easyCell('<b>GALONES</b>','align:C;');
  $table->easyCell('<b>EFEC. INI</b>','align:C;');
  $table->easyCell('<b>EFEC. FIN</b>','align:C;');
  $table->easyCell('<b>TOTAL $</b>','align:C;');
  $table->printRow();
  return $table;
}

function getHeaders2($table){
  $table->rowStyle('align:{CCCC};font-style:B; ');
  $table->easyCell(' ARQUEO DE CAJA ', 'border:0;colspan:4; bgcolor:255,255,255;');
  $table->printRow();
  $style ='align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000;';
  $style .=' font-family:arial;font-size:6;font-style:B; ';
  $table->rowStyle($style);
  $style = ' border:1';
  $table->rowStyle($style);
  $table->easyCell('<b>No.</b>','align:C;');
  $table->easyCell('<b>DESCRIPCION</b>','align:C;');
  $table->easyCell('<b>CANTIDAD</b>','align:C;');
  $table->easyCell('<b>SUBTOTAL $</b>','align:C;');
  $table->rowStyle('valign:M;border:0;font-size:6' );
  $table->easyCell("  ",'align:C;');
  $table->rowStyle('valign:M;border:1;font-size:6' );
  $table->easyCell('<b>LIQUIDACION $</b>','colspan:2;align:C;');
  $table->printRow();
  return $table;
}
function getTitle($table,$nombre,$title_report,$rango,$nombre_user,$datos_apertura){
  //$table->rowStyle('font-size:6; font-style:B;');
  $table->easyCell("<b>".mb_strtoupper($nombre)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".mb_strtoupper($title_report)." ".$rango."</b>",'align:C;');
  $table->printRow();
  //$table->easyCell("<b>".$rango."</b>",'align:C;');
  //$table->printRow();
  $table->easyCell("<b>".utf8_decode($nombre_user)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".utf8_decode($datos_apertura)."</b>",'align:C;');
  $table->printRow();
  return $table;
}
function getFoot($table,$id_lectura){
  $row=getQueryHeader($id_lectura);
  $table->rowStyle('bgcolor:153,255,153;');
  $table->rowStyle('align:{CCCC};');
  $table->easyCell(' ** TOTALES ** ', 'border:1;colspan:5; bgcolor:255,255,255;');
  $table->easyCell("$ ".number_format($row['total_gal'],2,".",","), 'align:R;');
  $table->easyCell('  ', 'border:1;colspan:2; bgcolor:255,255,255;');
  $table->easyCell("$ ".number_format($row['total_dinero'],2,".",","), 'align:R;');
  $table->printRow();
  return $table;
}
function setLogos($pdf,$logo1,$logo2=""){
  //$pdf->Cell( 44, 44, $pdf->Image($logo1, $pdf->GetX()+5, $pdf->GetY(), 20), 0, 0, 'L', false );
  $pdf->Cell( 34, 34, $pdf->Image($logo1, $pdf->GetX()+5, $pdf->GetY(), 18), 0, 0, 'L', false );
  if($logo2!=""){
      $pdf->Cell( 38, 38, $pdf->Image($logo2, 170, $pdf->GetY(), 20), 0, 0, 'R', false );
  }
  return $pdf;
}

function getQueryDetalle($id_lectura){
  $sql = " SELECT b.id, b.numero,b.descripcion, l.id_lectura, l.id_tipo_combustible,
  l.combustible,  l.fecha, l.inicio_combustible, l.fin_combustible, l.galones,
  l.inicio_dinero, l.fin_dinero, l.total_dinero,l.hora_corte
  FROM lectura_detalle_bomba AS l
  JOIN bomba AS b ON b.id=l.id_bomba
  WHERE id_lectura = '$id_lectura'
  ORDER BY id_tipo_combustible
  ";
  $result=_query($sql);
  return $result;
}
function getByIdComb($id_lectura,$id_comb){ //funcion para traer lectura por tipo de combustible
  $sql = " SELECT b.id, b.numero,b.descripcion, l.id_lectura, l.id_tipo_combustible,
  l.combustible,  l.fecha, l.inicio_combustible, l.fin_combustible, l.galones,
  l.inicio_dinero, l.fin_dinero, l.total_dinero,l.hora_corte
  FROM lectura_detalle_bomba AS l
  JOIN bomba AS b ON b.id=l.id_bomba
  WHERE id_lectura = '$id_lectura'
  AND id_tipo_combustible = '$id_comb'
  ORDER BY id_tipo_combustible";
  $result=_query($sql);
  return $result;
}
function getQueryHeader($id_lectura){
  $sql = " SELECT id_lectura, id_apertura, gal_diesel, gal_regular, gal_super,
  total_gal, dinero_diesel, dinero_regular, dinero_super, total_dinero, fecha,
  hora_inicio, hora_corte, id_sucursal, id_usuario, total_impuestos
  FROM lectura_bomba
  WHERE id_lectura = '$id_lectura'";
  $res=_query($sql);
  if(_num_rows($res)>0){
    $row = _fetch_array($res);
  }
  return $row;
}
function getFactDet($id_apertura){
  $sql = "SELECT fd.id_prod_serv AS id_prod, p.descripcion,pp.costo, pp.precio,
  COALESCE(SUM(fd.cantidad),0) AS cant_galones,
  COALESCE(SUM(fd.total),0) as total_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE c.combustible=1
  AND f.id_apertura='$id_apertura'
  GROUP BY fd.id_prod_serv";
  $res=_query($sql);
  return $res;
}
//traer tipos de combustible
function getCombustible(){
  $array = array();
  $q="SELECT p.id_producto AS id,p.descripcion
  FROM producto as p
  JOIN categoria AS c ON p.id_categoria=c.id_categoria
  WHERE c.combustible=1";
  $res=_query($q);
  while ($row=_fetch_array($res)) {
      $id=$row['id'];
      $description=$row['descripcion'];
      $array[$id] = $description;
  }
  return $array;
}
function getByApertura($id_apertura){
  $sql = " SELECT id_lectura, id_apertura, gal_diesel, gal_regular, gal_super,
  total_gal, dinero_diesel, dinero_regular, dinero_super, total_dinero, fecha,
  hora_inicio, hora_corte, id_sucursal, id_usuario, total_impuestos
  FROM lectura_bomba
  WHERE id_apertura = '$id_apertura'";
  $res=_query($sql);
  if(_num_rows($res)>0){
    $row = _fetch_array($res);
  }
  return $row;
}
function headTable2($table){
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:6');
  $style = ' border:1';
  $table->rowStyle($style);
  $table->easyCell('<b>COMBUSTIBLE</b>','align:C;');
  $table->easyCell('<b>ULT. COSTO $</b>','align:R;');
  $table->easyCell('<b>PRECIO $','align:R;');
  $table->easyCell('<b>GAL. FACT</b>','align:R;');
  $table->easyCell('<b>VTA. FACT $</b>','align:R;');
  $table->easyCell('<b>GAL. LECT</b>','align:R;');
  $table->easyCell('<b>VTA. LECT $</b>','align:R;');
  $table->printRow();
  return $table;
}


//abonos de creditos
function getAbonosTotal($id_apertura){
  $sql = "SELECT COALESCE(SUM(abono),0) as total
  FROM abono_credito
  WHERE id_apertura='$id_apertura'
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
function getbyTipoPago($id_apertura, $alias){
  $sql = "SELECT  COALESCE(SUM(fp.subtotal),0) AS total
  FROM factura_pago AS fp
  JOIN factura AS f ON f.id_factura=fp.id_factura
    WHERE f.id_apertura='$id_apertura'
  AND fp.alias_tipopago='$alias'";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
function totalTipoPago($id_apertura){
  $sql = "SELECT fp.alias_tipopago, COALESCE(SUM(fp.subtotal),0) AS total
  FROM factura_pago AS fp
  JOIN factura AS f ON f.id_factura=fp.id_factura
    WHERE f.id_apertura='$id_apertura'
  GROUP by fp.alias_tipopago";
  $res=_query($sql);
  $row = _fetch_array($res);
}

function totalArqueo($id_apertura){
  $sql = "SELECT COALESCE(sum(subtotal),0) AS totalArq
  FROM arqueo_corte
  WHERE id_apertura='$id_apertura'
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
//abonos realizados a credito de proveedores
//abonos al credito
function abonosCreditoTotal($fecha){
  $sql = "SELECT COALESCE(SUM(abono),0) as total
  FROM cuentas_por_pagar_abonos
  WHERE fecha='$fecha'
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
//moviomientos de caja
function getMovCajaTotal($id_apertura,$tipo=0){
  //tipo=0 entrada, tipo =1 salida
  $sql = "SELECT  COALESCE(SUM(valor),0) as total FROM mov_caja
  WHERE   id_apertura = '$id_apertura'
	";
  if ($tipo==0){
   $sql .=  " AND entrada=1";
 }else{
    $sql .=  " AND salida=1";
 }
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
?>
