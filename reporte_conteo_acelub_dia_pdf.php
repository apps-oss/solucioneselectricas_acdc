<?php
error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
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
      $this->SetFont('Arial','I',8);
      // Print centered page number
      $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
      parent::Footer();
  }
}
$pdf=new exFPDF();
$pdf->AliasNbPages();
$pdf->SetFont('arial','',8);
$pdf->SetTopMargin(10);
$pdf->SetLeftMargin(12);
$pdf->SetLineWidth(0.1);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();
$id_sucursal = $_SESSION["id_sucursal"];
$id_user     = $_SESSION["id_usuario"];
$admin       = $_SESSION["admin"];
if (isset( $_REQUEST['fini'])){
  $fecha= $_REQUEST["fini"];
}
if (isset( $_REQUEST['id_apertura'])){
  $id_apertura  = $_REQUEST['id_apertura'];
  $rowApp       = getByApertura($id_apertura);
  $fecha= $rowApp['fecha'];
}


//Datos de empresa
$datos_suc = datos_sucursal($id_sucursal);
$nombre_suc = $datos_suc['descripcion'];
$logo = getLogo();
//datos apertura

$row_ap = getDatosApNoVigente($id_apertura);
$caja   = $row_ap['caja'];
$turno  = $row_ap['turno'];
$id_cajero = $row_ap['id_empleado'];
$monto_apertura = $row_ap['monto_apertura'];

$cajero    ="CAJERO: ". getCajero($id_cajero);
$row_caja = getDatosCaja($caja);
$tipo_caja = $row_caja['tipo_caja'];
$nombre_caja = "CAJA: ". $row_caja['nombre'];
$datos_apertura = $nombre_caja ." TURNO: ".$turno;
$title_report=" INVENTARIO DIARIO DE ACEITES / LUBRICANTES";

$pdf =setLogos($pdf,$logo);
$pdf->Ln(5);
//rango dias_fechas
$rango =getRangoFechaTexto($fecha,$fecha);

$headtitles = '{190,10}';
$r_user =  getUser($id_user,$admin=0);
$row_user=_fetch_array($r_user);
$nombre_user =  $row_user['nombre'];
$table = new easyTable($pdf, $headtitles);
$table = getTitle($table,$nombre_suc,$title_report,$rango,$cajero,$datos_apertura);
$table->endTable();
$headers='{10,20,150,40,40,40}';
$table = new easyTable($pdf, $headers,'border:1;');
$table = getHeaders($table);

$result= getProdAceiteLub($id_apertura);
$count=_num_rows($result);
if ($count>0 ){
    $total_dinero =0;
    $lcut=27;  $total=0; $total_qty = 0; $qty = 0;
    for($i=1;$i<=$count;$i++){
          $row1=_fetch_array($result);
          $id_producto=$row1['id_producto'];


          $table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
          $table->easyCell($i,'align:C;');
          $table->easyCell($row1['id_producto'],'align:L;');
          $table->easyCell(utf8_decode($row1['descripcion']),'align:L;');
          $table->easyCell( $row1['inv_sistema'],'align:R;');
          $table->easyCell( $row1['conteo'],'align:R;');
          $table->easyCell( $row1['diferencia'],'align:R;');

          $total1+= $row1['inv_sistema'];
          $total2+= $row1['conteo'];
          $total3+= $row1['diferencia'];
          $table->printRow();
          if(!($i%$lcut) && $i>0){
               $table->endTable();
               $pdf->AddPage();
               $pdf =setLogos($pdf,$logo);
               $table=new easyTable($pdf,$headtitles);
               $table = getTitle($table,$nombre_suc,$title_report,$rango,$cajero,$datos_apertura);
               $table->endTable();
               $pdf->Ln(5);
               $headers='{10,20,150,40,40,40}';
               $table = new easyTable($pdf, $headers,'border:1;');
               $table = getHeaders($table);
           }
      }
      $table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
      $table->easyCell("TOTAL",'align:C;colspan:3;');
      $table->easyCell( "<b> ".sprintf("%.2f",$total1)."</b>",'align:R;');
      $table->easyCell( "<b> ".sprintf("%.2f",$total2)."</b>",'align:R;');
      $table->easyCell( "<b> ".sprintf("%.2f",$total3)."</b>",'align:R;');
      $table->printRow();
}

$table->endTable();
$pdf->Ln(5);
$pdf->Output();

function getHeaders($table){
  $table->rowStyle('align:{CCCC};font-style:B; ');
  $table->easyCell('   ', 'border:0;colspan:4; bgcolor:255,255,255;');
  $table->printRow();
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:8;font-style:B; ');
  $style = ' border:1';
  $table->rowStyle($style);
  $table->easyCell('<b>No.</b>','align:C;');
  $table->easyCell('<b>COD. PROD</b>','align:C;');
  $table->easyCell('<b>DESCRIPCION</b>','align:C;');
  $table->easyCell('<b>STOCK</b>','align:C;');
  $table->easyCell('<b>CONTEO</b>','align:C;');
  $table->easyCell('<b>DIFERENCIA</b>','align:C;');

  $table->printRow();
  return $table;
}
function getTitle($table,$nombre,$title_report,$rango,$nombre_user,$datos_apertura){
  $table->rowStyle('font-size:11; font-style:B;');
  $table->easyCell("<b>".mb_strtoupper($nombre)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".mb_strtoupper($title_report)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".$rango."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".utf8_decode($nombre_user)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".utf8_decode($datos_apertura)."</b>",'align:C;');
  $table->printRow();
  return $table;
}
function getFoot($table,$id_lectura){//getFoot($table,$total_tickets,$row_fiesta){
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
  $pdf->Cell( 44, 44, $pdf->Image($logo1, $pdf->GetX()+5, $pdf->GetY(), 20), 0, 0, 'L', false );
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
  ORDER BY id_tipo_combustible
  ";
  $result=_query($sql);
  return $result;
}

function getFactDet($id_apertura,$tipo_caja=1){
  //pendiente agregar impuestos
  $sql = "SELECT fd.id_prod_serv AS id_prod, p.descripcion,pp.costo, pp.precio,
  COALESCE(SUM(fd.cantidad),0) AS cant,
  COALESCE(SUM(fd.total),0) as total_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE f.id_apertura='$id_apertura'";
  if ($tipo_caja==2){
    $sql .=   " AND c.combustible=1 ";
  }
  $sql .= " GROUP BY fd.id_prod_serv";
  $res=_query($sql);
  return $res;
}

function headTable2($table){
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:8');
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
function getProdAceiteLub($id_apertura){
  $sql = " SELECT p.id_producto, p.descripcion,l.inv_sistema,l.conteo,l.diferencia
FROM producto as p JOIN lectura_lub_dia AS l
ON p.id_producto=l.id_producto
WHERE l.id_apertura='$id_apertura' ";
  $res=_query($sql);

  return $res;
}
function getStockByProd($id_producto,$id_sucursal){
  $sql="  SELECT  COALESCE(SUM(su.cantidad),0) as cantidad
  FROM stock_ubicacion AS su
  WHERE su.id_producto='$id_producto'
  AND su.id_sucursal='$id_sucursal'";
  $res=_query($sql);
  if(_num_rows($res)>0){
    $row = _fetch_row($res);
  }
  return $row;
}
function getAceiteLubByProd($id_producto,$id_sucursal,$fecha){
  $sql = "SELECT
  COALESCE(SUM(fd.total),0) as total_venta,COALESCE(SUM(fd.cantidad),0) as cantidad,
  fd.precio_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE c.combustible=0
  AND c.pista=1
  AND fd.id_prod_serv='$id_producto'
  AND f.id_sucursal='$id_sucursal'
  AND f.fecha= '$fecha'
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row;
}
?>
