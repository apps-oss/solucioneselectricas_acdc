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
$id_credito= $_REQUEST["id_credito"];

//Datos de empresa
$datos_suc = datos_sucursal($id_sucursal);
$nombre_suc = $datos_suc['descripcion'];
$logo = getLogo();
//datos apertura

$title_report=" HISTORIAL CUOTAS POR CREDITO";
$pdf =setLogos($pdf,$logo);
$pdf->Ln(5);
//rango dias_fechas
$rango =getRangoFechaTexto($fecha,$fecha);
$headtitles = '{190,10}';
$r_user =  getUser($id_user,$admin=0);
$row_user=_fetch_array($r_user);
$nombre_user =  $row_user['nombre'];
$result= getAbonosCreditos($id_credito);

$rescred=getCredito($id_credito);
$id_cliente=$rescred['id_cliente'];
$id_factura=$rescred['id_factura'];
$rowCliente=getCteCredito($id_cliente);
$nombreCliente=" CLIENTE: ".$rowCliente['nombre'];
$resdet = detProdCredito($id_factura);
$count0=_num_rows($resdet);
$table = new easyTable($pdf, $headtitles);
$table = getTitle($table,$nombre_suc,$title_report,$nombreCliente);
$table->endTable();

$table=new easyTable($pdf, 1, 'width:150; align:C;   font-size:8;font-family:helvetica; ');
$table->easyCell(" DETALLE DE CREDITO " , '  font-style:B; ');
$table->printRow();
$table->easyCell("FECHA OTORGADO: ". ED($rescred['fecha']));
$table->printRow();
$table->easyCell("TOTAL CREDITO: $ ". sprintf("%.2f",$rescred['total']));
$table->printRow();
$table->easyCell("ABONADO: $".sprintf("%.2f",$rescred['abono']));
$table->printRow();
$table->easyCell("SALDO: $ ".sprintf("%.2f",$rescred['saldo']));
$table->printRow();
$table->endTable(5);

if ($count0>0 ){
  $table=new easyTable($pdf, 1, 'width:100;align:C;   font-size:8;font-family:helvetica; ');
  $table->easyCell("  LISTA DE PRODUCTOS CREDITO ",  '  font-style:B; ');
  $table->printRow();
  $table->endTable();
  $table=new easyTable($pdf, '{20,100}', 'align:C;   font-size:8;font-family:helvetica; ');
  $table->easyCell("  CANTIDAD ",  '  font-style:B; ','align:C;');
  $table->easyCell( " DESCRICION " , '  font-style:B; ','align:C;');
  $table->printRow();
  for($j=0;$j<$count0;$j++){
        $row0=_fetch_array($resdet);
        $table->easyCell(sprintf("%.2f",$row0['cantidad'])." - " ,'align:R;');
        $table->easyCell(" ".utf8_decode($row0['descripcion']));
        $table->printRow();
  }
  $table->endTable(5);
}
$headers='{20,50,50}';
$table = new easyTable($pdf, $headers,'border:1;');
$table = getHeaders($table);

$count1=_num_rows($result);
if ($count1>0 ){
    $total_dinero =0;
    $lcut=25;  $total=0; $total_qty = 0; $qty = 0;
    for($i=1;$i<=$count1;$i++){
          $row=_fetch_array($result);
          $abono = sprintf("%.2f",$row['abono']);
          $table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:10' );
          $table->easyCell($i,'align:C;');
          $table->easyCell(ed($row['fecha']),'align:L;');
          $table->easyCell( $abono,'align:R;');
          $table->printRow();
          if(!($i%$lcut) && $i>0){
               $table->endTable();
               $pdf->AddPage();
               $pdf =setLogos($pdf,$logo);
               $table=new easyTable($pdf,$headtitles);
               $table = getTitle($table,$nombre_suc,$title_report,$nombreCliente);
               $table->endTable();
               $pdf->Ln(5);
               $headers='{20,50,50 }';
               $table = new easyTable($pdf, $headers,'border:1;');
               $table = getHeaders($table);
           }
      }

      $table->rowStyle('valign:M;border:LRB;paddingY:2;font-size:10' );
      $table->easyCell("TOTAL ABONADO",'align:C;colspan:2;');
      $table->easyCell( "<b> $ ".sprintf("%.2f",$rescred['abono'])."</b>",'align:R;');

      $table->printRow();
}
$table->endTable();
$pdf->Ln(5);
$pdf->Output();

function getHeaders($table){
  $table->rowStyle('align:{CCCC};font-style:B; ');
  $table->easyCell('   ', 'border:0;colspan:4; bgcolor:255,255,255;');
  $table->printRow();
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:10;font-style:B; ');
  $style = ' border:1';
  $table->rowStyle($style);
  $table->easyCell('<b>No.</b>','align:C;');
  $table->easyCell('<b>FECHA</b>','align:C;');
  $table->easyCell('<b>ABONO $</b>','align:C;');
  $table->printRow();
  return $table;
}
function getTitle($table,$nombre,$title_report,$rango){
  $table->rowStyle('font-size:11; font-style:B;');
  $table->easyCell("<b>".mb_strtoupper($nombre)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".mb_strtoupper($title_report)."</b>",'align:C;');
  $table->printRow();
  $table->easyCell("<b>".$rango."</b>",'align:C;');
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

function headTable2($table){
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:10');
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

function getCteCredito($id_cliente){
  $sql = " SELECT * FROM cliente WHERE id_cliente='$id_cliente'";
  $res=_query($sql);
  $row=_fetch_assoc($res);
  return $row;
}

function getAbonosCreditos($id_credito){
  $sql = " SELECT * FROM abono_credito WHERE id_credito='$id_credito'
  ORDER BY fecha";
  $res=_query($sql);
  return $res;
}

function detProdCredito($id_factura){
    $q="SELECT fd.id_prod_serv,p.id_producto,p.descripcion,fd.cantidad
  FROM factura_detalle AS fd
  JOIN producto AS p ON p.id_producto=fd.id_prod_serv

  WHERE fd.id_factura='$id_factura'";
  $res=_query($q);
  return $res;
}
function getCredito($id_credito){
	//Obtener informacion de tabla
  $q="SELECT * FROM credito AS cr
  WHERE cr.id_credito='$id_credito'";
  $res=_query($q);
  $row=_fetch_assoc($res);
  return $row;
}

?>
