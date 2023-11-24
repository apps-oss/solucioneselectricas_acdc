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
$id_lectura  = $_REQUEST['id_lectura'];
$id_user = $_SESSION["id_usuario"];
$admin   = $_SESSION["admin"];

if (isset( $_REQUEST['id_apertura'])){
  $id_apertura  = $_REQUEST['id_apertura'];
  $rowApp       = getByApertura($id_apertura);
  $id_lectura   = $rowApp['id_lectura'];
}
//Datos de empresa
$datos_suc = datos_sucursal($id_sucursal);
$nombre_suc = $datos_suc['descripcion'];
$logo = getLogo();
$lect_head_row=getQueryHeader($id_lectura);
$inicio      = $lect_head_row['fecha'];
$id_apertura = $lect_head_row['id_apertura'];
//datos apertura
$row_ap = getDatosApNoVigente($id_apertura);
$caja   = $row_ap['caja'];
$turno  = $row_ap['turno'];
$id_cajero = $row_ap['id_empleado'];
$cajero    ="CAJERO: ". getCajero($id_cajero);
$row_caja = getDatosCaja($caja);
$nombre_caja = "CAJA: ". $row_caja['nombre'];
$datos_apertura = $nombre_caja ." TURNO: ".$turno;
$title_report=" REPORTE DE LECTURAS DE BOMBAS ";
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
$table = new easyTable($pdf, $headers,'border:1;');
$table = getHeaders($table);
foreach ($array_comb as $key=>$val){
    $result = getByIdComb($id_lectura,$key);
    $count=_num_rows($result);
    if ($count>0 ){
        $total_dinero =0; $total_galones= 0;
        for($i=1;$i<=$count;$i++){
              $row1=_fetch_array($result);
              $table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
              $table->easyCell($i,'align:C;');
              $table->easyCell(utf8_decode($row1['descripcion']),'align:L;');
              $table->easyCell(utf8_decode($row1['combustible']),'align:L;');
              $table->easyCell($row1['inicio_combustible'],'align:R;');
              $table->easyCell($row1['fin_combustible'],'align:R;');
              $table->easyCell( sprintf("%.2f",$row1['galones']),'align:R;');
              $table->easyCell("$ ".$row1['inicio_dinero'],'align:R;');
              $table->easyCell("$ ".$row1['fin_dinero'],'align:R;');
              $table->easyCell("$ ".sprintf("%.2f",$row1['total_dinero']),'align:R;');
              $table->printRow();
              $total_dinero += $row1['total_dinero'];
              $total_galones+= $row1['galones'];
        }
        $table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
        $table->easyCell("SUBTOTAL",'align:C;colspan:5;');
        $table->easyCell( "<b>".sprintf("%.2f",$total_galones)."</b>",'align:R;');
        $table->easyCell("",'align:L;colspan:2;');
        $table->easyCell( "<b>$ ".sprintf("%.2f",$total_dinero)."</b>",'align:R;');
        $table->printRow();
   }
}
$total_final = $lect_head_row['total_dinero'] + $lect_head_row['total_impuestos'] ;
$table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
$table->easyCell("TOTAL",'align:C;colspan:5;');
$table->easyCell("<b>". sprintf("%.2f",$lect_head_row['total_gal'])."</b>",'align:R;');
$table->easyCell("",'align:L;colspan:2;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_dinero'])."</b>",'align:R;');
$table->printRow();
$table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
$table->easyCell("IMPUESTOS",'align:C;colspan:8;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_impuestos'])."</b>",'align:R;');
$table->printRow();
$table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
$table->easyCell("TOTAL FINAL ",'align:C;colspan:8;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_final)."</b>",'align:R;');
$table->printRow();
$table->endTable();
$pdf->Ln(5);
$res2 = getFactDet($id_apertura);
$count2= _num_rows($res2);
//tabla consolidada
$headers2='{38,25,25,25,25,25,25}';
$table = new easyTable($pdf, $headers2,'border:1;');
$table = headTable2($table);
$total_vf = 0; $total_gal_vf = 0;
for($j=0;$j<$count2;$j++){
  $row2 = _fetch_array($res2);
  $table->easyCell(utf8_decode($row2['descripcion']),'align:L;');
  $table->easyCell( "$ ".sprintf("%.2f",$row2['costo']),'align:R;');
  $table->easyCell("$ ". sprintf("%.2f",$row2['precio']),'align:R;');
  $table->easyCell( sprintf("%.2f",$row2['cant_galones']),'align:R;');
  $table->easyCell("$ ". sprintf("%.2f",$row2['total_venta']),'align:R;');
  if ($row2['id_prod']==1){
    $table->easyCell( sprintf("%.2f",$lect_head_row['gal_super']),'align:R;');
    $table->easyCell("$ ". sprintf("%.2f",$lect_head_row['dinero_super']),'align:R;');
  }
  if ($row2['id_prod']==2){
    $table->easyCell( sprintf("%.2f",$lect_head_row['gal_regular']),'align:R;');
    $table->easyCell("$ ". sprintf("%.2f",$lect_head_row['dinero_regular']),'align:R;');
  }
  if ($row2['id_prod']==3){
    $table->easyCell( sprintf("%.2f",$lect_head_row['gal_diesel']),'align:R;');
    $table->easyCell("$ ". sprintf("%.2f",$lect_head_row['dinero_diesel']),'align:R;');
  }
  $total_vf += $row2['total_venta'];
  $total_gal_vf += $row2['cant_galones'];
  $table->printRow();
}
$table->easyCell("TOTAL",'align:C;colspan:3;');
$table->easyCell("<b>". sprintf("%.2f",$total_gal_vf)."</b>",'align:R;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_vf)."</b>",'align:R;');
$table->easyCell( "<b>".sprintf("%.2f",$lect_head_row['total_gal'])."</b>",'align:R;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_dinero'])."</b>",'align:R;');
$table->printRow();
$table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
$table->easyCell("IMPUESTOS",'align:C;colspan:6;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_impuestos'])."</b>",'align:R;');
$table->printRow();
$table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:8' );
$table->easyCell("TOTAL FINAL ",'align:C;colspan:6;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_final)."</b>",'align:R;');
$table->printRow();
$table->endTable();
$pdf->Output();

function getHeaders($table){
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:8;font-style:B; ');
  $style = ' border:1';
  $table->rowStyle($style);
  $table->easyCell('<b>No.</b>','align:C;');
  $table->easyCell('<b>BOMBA</b>','align:C;');
  $table->easyCell('<b>COMBUSTIBLE</b>','align:C;');
  $table->easyCell('<b>LECT. FIN</b>','align:C;');
  $table->easyCell('<b>LECT. INI.</b> ','align:C;');
  $table->easyCell('<b>GALONES</b>','align:C;');
  $table->easyCell('<b>EFEC. INI</b>','align:C;');
  $table->easyCell('<b>EFEC. FIN</b>','align:C;');
  $table->easyCell('<b>TOTAL $</b>','align:C;');
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
  //$table->easyCell("$ ".number_format($total_tickets,2,".",","), 'align:R;');
  $table->printRow();
  return $table;
}
function setLogos($pdf,$logo1,$logo2=""){
  $pdf->Cell( 44, 44, $pdf->Image($logo1, $pdf->GetX()+5, $pdf->GetY(), 20), 0, 0, 'L', false );
  if($logo2!=""){
      $pdf->Cell( 38, 38, $pdf->Image($logo2, 170, $pdf->GetY(), 20), 0, 0, 'R', false );
  }

  //$pdf->Line(13.5, 280,198.5,280); // linea
  //$pdf->Rect(13.5, 40, 186, 230.5);
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
function getQueryHeader($id_lectura){
  $sql = " SELECT id_lectura, id_apertura, gal_diesel, gal_regular, gal_super,
   total_gal, dinero_diesel, dinero_regular, dinero_super, total_dinero, fecha,
    hora_inicio, hora_corte, id_sucursal, id_usuario, total_impuestos
    FROM lectura_bomba
  WHERE id_lectura = '$id_lectura'
  ";
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
  WHERE id_apertura = '$id_apertura'
  ";
  $res=_query($sql);
  if(_num_rows($res)>0){
    $row = _fetch_array($res);
  }
  return $row;
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

?>
