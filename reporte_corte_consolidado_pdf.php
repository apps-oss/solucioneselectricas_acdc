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
//arqueo de caja
$total_arqueo=totalArqueo($id_apertura);
$total_arqueo_efectivo=totalArqueoEfect($id_apertura);
$title_report=" REPORTE DE LECTURAS DE BOMBAS, CORTE Y LIQUIDACION ";
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
              $table->rowStyle('valign:M;border:LRB;font-size:6' );
              $table->easyCell($i,'align:C;');
              $table->easyCell(utf8_decode($row1['descripcion']),'align:L;');
              $table->easyCell(utf8_decode($row1['combustible']),'align:L;');
              $table->easyCell($row1['inicio_combustible'],'align:R;');
              $table->easyCell($row1['fin_combustible'],'align:R;');
              $table->easyCell($row1['galones'],'align:R;');
              $table->easyCell("$ ".$row1['inicio_dinero'],'align:R;');
              $table->easyCell("$ ".$row1['fin_dinero'],'align:R;');
              $table->easyCell("$ ".$row1['total_dinero'],'align:R;');
              $table->printRow();
              $total_dinero += $row1['total_dinero'];
              $total_galones+= $row1['galones'];
        }
        $table->rowStyle('valign:M;border:LRB;font-size:6' );
        $table->easyCell("SUBTOTAL",'align:C;colspan:5;');
        $table->easyCell( "<b>".sprintf("%.2f",$total_galones)."</b>",'align:R;');
        $table->easyCell("",'align:L;colspan:2;');
        $table->easyCell( "<b>$ ".sprintf("%.2f",$total_dinero)."</b>",'align:R;');
        $table->printRow();
   }
}
$total_final = $lect_head_row['total_dinero']  ;
$table->rowStyle('valign:M;border:LRB;font-size:6' );
$table->easyCell("TOTAL",'align:C;colspan:5;');
$table->easyCell("<b>". sprintf("%.2f",$lect_head_row['total_gal'])."</b>",'align:R;');
$table->easyCell("",'align:L;colspan:2;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_dinero'])."</b>",'align:R;');
$table->printRow();
$table->rowStyle('valign:M;border:LRB;font-size:6' );
$table->easyCell("IMPUESTOS",'align:C;colspan:5;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_impuestos'])."</b>",'align:R;');
//$table->printRow();
$table->rowStyle('valign:M;border:LRB;font-size:6' );
$table->easyCell("TOTAL FINAL ",'align:C;colspan:2;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_final)."</b>",'align:R;');
$table->printRow();
$table->endTable();
$pdf->ln(1);
// tabla de diferencia en galonaje segun lecturas
/*
$array_comb = getCombustible();
 $arr_gal=getEstimadoConsComb($id_lectura);
 $headers3='{10,35,20}';
 $table = new easyTable($pdf, $headers3,'border:1;');
 $table = headTable3($table);
 foreach ($array_comb as $key=>$val){
   $table->easyCell( $key,'align:R;');
   $table->easyCell(  $val,'align:C;');
   $table->easyCell(sprintf("%.2f",$arr_gal[$key]),'align:R;');
    $table->printRow();
 }
 $table->endTable();
 $pdf->ln(1);
 */
//tabla consolidada
$res2 = getFactDet($id_apertura);
$count2= _num_rows($res2);

$headers2='{38,25,25,25,25,25,25}';
$table = new easyTable($pdf, $headers2,'border:1;');
$table = headTable2($table);
$total_vf = 0; $total_gal_vf = 0;
for($j=0;$j<$count2;$j++){
  $row2 = _fetch_array($res2);
  $subtotal = $row2['precio'] * $row2['cant_galones'];
  $table->easyCell(utf8_decode($row2['descripcion']),'align:L;');
  $table->easyCell( "$ ".sprintf("%.2f",$row2['costo']),'align:R;');
  $table->easyCell("$ ". sprintf("%.2f",$row2['precio']),'align:R;');
  $table->easyCell( sprintf("%.2f",$row2['cant_galones']),'align:R;');
  $table->easyCell("$ ". sprintf("%.2f",$subtotal),'align:R;');

  if ($row2['id_prod']==2){
    $table->easyCell( sprintf("%.2f",$lect_head_row['gal_super']),'align:R;');
    $table->easyCell("$ ". sprintf("%.2f",$lect_head_row['dinero_super']),'align:R;');
  }
  if ($row2['id_prod']==1){
    $table->easyCell( sprintf("%.2f",$lect_head_row['gal_regular']),'align:R;');
    $table->easyCell("$ ". sprintf("%.2f",$lect_head_row['dinero_regular']),'align:R;');
  }
  if ($row2['id_prod']==3){
    $table->easyCell( sprintf("%.2f",$lect_head_row['gal_diesel']),'align:R;');
    $table->easyCell("$ ". sprintf("%.2f",$lect_head_row['dinero_diesel']),'align:R;');
  }
  $total_vf += $subtotal;
  $total_gal_vf += $row2['cant_galones'];
  $table->printRow();
}
//TOTAL DE IMPUESTOS A COMBUSTIBLES SEGUN FACTURACION
$total_imp_fact=getImpuestosComb($id_apertura);
$total_vf_imp= $total_vf+$total_imp_fact;
$table->easyCell("TOTAL",'align:C;colspan:3;');
$table->easyCell("<b>". sprintf("%.2f",$total_gal_vf)."</b>",'align:R;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_vf)."</b>",'align:R;');
$table->easyCell( "<b>".sprintf("%.2f",$lect_head_row['total_gal'])."</b>",'align:R;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$lect_head_row['total_dinero'])."</b>",'align:R;');
$table->printRow();
/*
$table->rowStyle('valign:M;border:LRB;font-size:6' );
$table->easyCell("IMPUESTOS",'align:C;colspan:4;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_imp_fact)."</b>",'align:R;');
$table->rowStyle('valign:M;border:LRB;font-size:6' );
$table->easyCell("TOTAL FINAL ",'align:C;colspan:1;');
$table->easyCell( "<b>$ ".sprintf("%.2f",$total_vf_imp)."</b>",'align:R;');
$table->printRow();
*/
$table->endTable();
$pdf->ln(1);
// TAbla para creditos y abonos
$r_cred= getCreditos($id_apertura);
$count4 = _num_rows($r_cred);
$headers4='{10,15,20,50,20}';
$table = new easyTable($pdf, $headers4,'border:1;');
//nombre	tipo_documento		num_fact_impresa	subtotal
if($count4>0){
  $table->easyCell("#",'align:C;colspan:1;');
  $table->easyCell("TIPO DOC ",'align:C;colspan:1;');
  $table->easyCell("# DOC ",'align:C;colspan:1;');
  $table->easyCell("CREDITO CLIENTE",'align:C;colspan:1;');
  $table->easyCell("MONTO $",'align:C;colspan:1;');
  $table->printRow();
  for($m=1;$m<=$count4;$m++){
    $row4=_fetch_array($r_cred);
    $table->easyCell( "<b> ".$m."</b>",'align:R;');
    $table->easyCell( "<b> ".$row4['tipo_documento']."</b>",'align:R;');
    $table->easyCell( "<b> ".$row4['num_fact_impresa']."</b>",'align:R;');
    $table->easyCell( "<b> ".$row4['nombre']."</b>",'align:C;');
    $table->easyCell( "<b> ".sprintf("%.2f",$row4['subtotal'])."</b>",'align:R;');
    $table->printRow();
  }
}
$table->endTable();
$pdf->ln(1);
// LECTURA DE TANQUE INICIAL
$r_tanque= getTanqueDiario($fecha,$id_sucursal);
$count3 = _num_rows($r_tanque);
$headers3='{15,50,25}';
$table = new easyTable($pdf, $headers3,'border:1;');
if($count3>0){
  $table->easyCell("#",'align:C;colspan:1;');
  $table->easyCell("DESCRIPCION.",'align:C;colspan:1;');
  $table->easyCell("GALONES ",'align:C;colspan:1;');
  $table->printRow();
  for($l=0;$l<$count3;$l++){
    $row3=_fetch_array($r_tanque);
    $table->easyCell( "<b> ".sprintf("%.2f",$row3['id_tanque'])."</b>",'align:R;');
    $table->easyCell( "<b> ".$row3['descripcion']."</b>",'align:C;');
    $table->easyCell( "<b> ".sprintf("%.2f",$row3['galones_dia'])."</b>",'align:R;');
    $table->printRow();
  }
}
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
//aceite y lubricantes
$total_aceite_lub = getAceiteLubTotal($id_apertura);
/* total combustibles*/
$total_combustibles = getCombustibleTotal($id_apertura)+$total_imp_fact;
$total_combustibles = $lect_head_row['total_dinero'];
/* TOTAL DINERO CONSUMO INTERNO*/
$total_consumo_interno_costo =round(getConsumoIntTotal($id_apertura),2);
$total_consumo_interno =getConsumoInternoPB($id_apertura);
$descuento_consumo_interno=round($total_consumo_interno-$total_consumo_interno_costo,2);
$total_combustibles_ace = $total_combustibles  + $total_aceite_lub;
/* abonos*/
$total_abonos =getAbonosTotal($id_apertura);
//total ingresos
$total_ingresos = $total_combustibles + $total_aceite_lub + $total_abonos + $monto_apertura;
$array_liquida= array();
$saldo_inicial = 0.00;
//CREDITOS
$total_creditos=getbyTipoPago($id_apertura, 'CRE');
//CUPONES (O VALES)
$total_cupones=getbyTipoPago($id_apertura, 'VAL');
//TARJETAS
$total_tarjeta=getbyTipoPago($id_apertura, 'TAR');

//abono a creditos de prov.
$pagos =abonosCreditoTotal($fecha);
$linea0= "";
//vales internos
$total_vales= getMovCajaTotal($id_apertura,1);
$total_vales=  round($total_vales,2);
//EFECTIVO
$efectivo=getbyTipoPago($id_apertura, 'CON');
//dif
$impuesto_fovial=round(getDifImpuesto($id_apertura),2);
//total egresos
$total_egresos = $total_creditos + $total_cupones+ $total_consumo_interno + $pagos + $total_vales + $total_tarjeta + $impuesto_fovial ;
//$total_egresos = $total_creditos + $total_cupones + $total_arqueo + $pagos  ;
//a liquidar
$a_liquidar= $total_ingresos -$total_egresos ;
//total caja
$total_caja =  round(($total_ingresos - $total_egresos),2);
//diferencia
$diferencia = $total_caja - $efectivo;
//DIFERENCIA  EFECTIVO EN BOMBA Y EFECTIVO ENTREGADO
$diferencia2 = $total_arqueo_efectivo - $a_liquidar;

array_push($array_liquida, $linea0);
$linea1=["SALDO INICIAL:"," $ ".$monto_apertura];
array_push($array_liquida, $linea1);
$linea1=["VENTA COMBUSTIBLE:"," $ ".$total_combustibles];
array_push($array_liquida, $linea1);
$linea1=["VENTA ACEITE/LUB:"," $ ".$total_aceite_lub];
array_push($array_liquida, $linea1);
$linea1=["TOTAL VENTA:"," $ ".$total_combustibles_ace];
array_push($array_liquida, $linea1);
$linea1=["ABONOS:"," $ ".$total_abonos];
array_push($array_liquida, $linea1);
$linea1=["TOTAL INGRESO:"," $ ".$total_ingresos];
array_push($array_liquida, $linea1);
$linea1=["CREDITOS:"," $ ".$total_creditos];
array_push($array_liquida, $linea1);
$linea1=["CUPONES:"," $ ".$total_cupones];
array_push($array_liquida, $linea1);
$linea1=["PAGO TARJETA:"," $ ".$total_tarjeta];
array_push($array_liquida, $linea1);
$linea1=["AUTOCONSUMO:"," $ ".$total_consumo_interno];
array_push($array_liquida, $linea1);
$linea1=["DESCUENTO AUTOCONSUMO:"," $ ".$descuento_consumo_interno];
array_push($array_liquida, $linea1);

$linea1=["DESCUENTO FOVIAL:"," $ ".$impuesto_fovial];
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
$linea1=["EFECTIVO:"," $ ".$total_combustibles_sin_coi];
array_push($array_liquida, $linea1);
$linea1=["A LIQUIDAR:"," $ ".$a_liquidar];
array_push($array_liquida, $linea1);
$linea1=["DIFERENCIA:"," $ ".$diferencia2];
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
    $total_dinero= $row1['total'];
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell("TOTAL",'align:C;colspan:3;');
    $table->easyCell( "<b>$ ".sprintf("%.2f",$total_dinero)."</b>",'align:R;');

    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:R;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[12][0],'align:C;');
    $table->easyCell($array_liquida[12][1],'align:R;');
    $table->printRow();
    for($ln=13;$ln<21;$ln++){
      $table->rowStyle('valign:M;border:0;font-size:6' );
      $table->easyCell("  ",'align:C;colspan:5;');
      $table->rowStyle('valign:M;border:LRB;font-size:6' );
      $table->easyCell($array_liquida[$ln][0],'align:C;');
      $table->easyCell($array_liquida[$ln][1],'align:R;');
      $table->printRow();
    }

/*
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[13][0],'align:C;');
    $table->easyCell($array_liquida[13][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[14][0],'align:C;');
    $table->easyCell($array_liquida[14][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[15][0],'align:C;');
    $table->easyCell($array_liquida[15][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[16][0],'align:C;');
    $table->easyCell($array_liquida[16][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[17][0],'align:C;');
    $table->easyCell($array_liquida[17][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[18][0],'align:C;');
    $table->easyCell($array_liquida[18][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[19][0],'align:C;');
    $table->easyCell($array_liquida[19][1],'align:R;');
    $table->printRow();
    $table->rowStyle('valign:M;border:0;font-size:6' );
    $table->easyCell("  ",'align:C;colspan:5;');
    $table->rowStyle('valign:M;border:LRB;font-size:6' );
    $table->easyCell($array_liquida[20][0],'align:C;');
    $table->easyCell($array_liquida[20][1],'align:R;');
    $table->printRow();
    */

}
$table->endTable();
$pdf->ln(1);


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
function getFactDet($id_apertura,$tipo_caja=0){
  $sql = "SELECT fd.id_prod_serv AS id_prod, p.descripcion,pp.costo, pp.precio,
  COALESCE(SUM(fd.cantidad),0) AS cant_galones,
  COALESCE(SUM(fd.total),0) as total_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE c.combustible=1
  AND f.id_apertura='$id_apertura'
  AND f.anulada=0
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

function getTanqueDiario($fecha,$id_sucursal){
  $qt="SELECT td.id_tanque, t.descripcion, td.galones_dia, td.fecha
  FROM tanque AS t
  JOIN tanque_diario  AS td
  ON t.numero=td.id_tanque
  WHERE fecha='$fecha'
  AND td.id_sucursal='$id_sucursal'
  GROUP BY td.id_tanque";
  $rt = _query($qt);

  return $rt;
}
//aceites y lubricantes
function getAceiteLubTotal($id_apertura){
  $sql = "SELECT
  COALESCE(SUM(fd.total),0) as total_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE c.combustible=0
  AND c.pista=1
  AND f.id_apertura='$id_apertura'
  AND f.anulada=0
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
function getCombustibleTotal($id_apertura){
  $sql = "SELECT
  COALESCE(SUM(fd.total),0) as total_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE c.combustible=1
  AND f.id_apertura='$id_apertura'
  AND f.anulada=0
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
/* consumo interno */
function getConsumoIntTotal($id_apertura){
  $sql = "SELECT COALESCE(SUM(fp.subtotal),0) AS total
  FROM factura_pago AS fp
  JOIN factura AS f ON f.id_factura=fp.id_factura
  WHERE fp.alias_tipopago='COI'
  AND f.id_apertura='$id_apertura'
  AND f.anulada=0
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
function getConsumoInternoPB($id_apertura){
  $sql = "SELECT fd.id_prod_serv,COALESCE(SUM(fd.cantidad),0) AS qty,p.precio,
   ROUND(COALESCE(SUM(fd.cantidad) * p.precio,0),2) AS subtotal
  FROM factura_pago AS fp
  JOIN factura AS f ON f.id_factura=fp.id_factura
  JOIN factura_detalle AS fd ON fd.id_factura=fp.id_factura
  JOIN presentacion_producto AS p ON p.id_producto=fd.id_prod_serv
  WHERE fp.alias_tipopago='COI'
  AND  fd.id_presentacion=p.id_pp
  AND f.id_apertura='$id_apertura'
  AND f.anulada=0
  GROUP BY fd.id_prod_serv
  ";
  $res=_query($sql);
  $n =_num_rows($res);
  $total =0;
  if($n>0){
    for ($i=0; $i < $n; $i++) {
      $row = _fetch_array($res);
      $total+= $row['subtotal'];
    }
  }

  return $total;
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
  AND fp.alias_tipopago='$alias'
  AND f.anulada=0";
  $res=_query($sql);
  $row = _fetch_row($res);
  return $row[0];
}
function totalTipoPago($id_apertura){
  $sql = "SELECT fp.alias_tipopago, COALESCE(SUM(fp.subtotal),0) AS total
  FROM factura_pago AS fp
  JOIN factura AS f ON f.id_factura=fp.id_factura
    WHERE f.id_apertura='$id_apertura'
    AND f.anulada=0
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
function totalArqueoEfect($id_apertura){
  $sql = "SELECT COALESCE(sum(subtotal),0) AS totalArq
  FROM arqueo_corte
  JOIN arqueo_conceptos ON arqueo_corte.id_concepto= arqueo_conceptos.id
  WHERE id_apertura='$id_apertura'
  AND arqueo_conceptos.alias_tipopago='CON'
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
//CREDITOS OTORGADOS POR APERTURA
function getCreditos($id_apertura){
$q = "SELECT f.id_factura, c.nombre,f.tipo_documento, f.tipo,
f.num_fact_impresa,fp.subtotal
FROM factura AS f
JOIN factura_pago AS fp ON f.id_factura= fp.id_factura
JOIN cliente AS c ON c.id_cliente=f.id_cliente
WHERE f.id_apertura='$id_apertura'
AND f.anulada=0
AND fp.alias_tipopago='CRE'
  ";
$r = _query($q);
  return $r;
}
function getEstimadoConsComb($id_lectura){
  include("_core.php");
  $lect_head_row = getQueryHeader($id_lectura);
  $gal_regular = $lect_head_row['gal_regular'];
  $gal_super   = $lect_head_row['gal_super'];
  $gal_diesel  = $lect_head_row['gal_diesel'];
  $dinero_regular = $lect_head_row['dinero_regular'];
  $dinero_super   = $lect_head_row['dinero_super'];
  $dinero_diesel  = $lect_head_row['dinero_diesel'];

  //var_dump($gal_super."- ".$gal_regular." -".$gal_diesel);
  $q = getPrecioBaseCombustibles();
  $n =_num_rows($q);

  if($n>0){
      $subt1 =0;  $subt2 =0;  $subt3 =0;
      $dif1 =0;  $dif2 =0;  $dif3 =0;
    for($i=0;$i<$n;$i++){
      $row    = _fetch_array($q);
      $id     = $row['id'];
      $precio = $row['precio'];

      if($id==1){
        $subt1 = $precio * $gal_regular ;
        $dif_dinero1 = $subt1 - $dinero_regular;
        $dif1=round(($dif_dinero1 /$precio),2);
      }
      if($id==2){
        $subt2 = $precio * $gal_super ;
        $dif_dinero2 = $subt2 - $dinero_super;
        $dif2=round(($dif_dinero2 /$precio),2);
      }
      if($id==3){
        $subt3 = $precio * $gal_diesel ;
        $dif_dinero2 = $subt3 - $dinero_diesel;
        $dif3=round(($dif_dinero3 / $precio),2);
      }

    }

    $arr_gal=array(  $dif1,  $dif2,  $dif3);
  }
  //var_dump($arr_gal);
  return $arr_gal;
}
function headTable3($table){

  $table->rowStyle('align:{CCCC};font-style:B; ');
  $table->easyCell(' GALONES FALTANTES ', 'border:0;colspan:4; bgcolor:255,255,255;');
  $table->printRow();
  $table->rowStyle('align:{CCCR};valign:M;bgcolor:#ffffff; font-color:#000000; font-family:arial;font-size:6');
  $style = ' border:1';
  $table->rowStyle($style);
    $table->easyCell('<b>#</b>','align:C;');
  $table->easyCell('<b>COMBUSTIBLE</b>','align:C;');
  $table->easyCell('<b>GALONES </b>','align:R;');

  $table->printRow();
  return $table;
}
//ABONOS POR CLIENTE

//impuestos al combustyible
function getImpuestosComb($id_apertura,$id_producto=0){
  $q="SELECT COALESCE(SUM(fi.total_imp),0) AS total
  FROM fact_imp_combust AS fi
  JOIN factura AS f ON f.id_factura=fi.id_factura
    WHERE f.id_apertura='$id_apertura'
    AND f.anulada=0";
    /*
    if($id_producto>=1 && $id_producto<=3 ){

    }*/
    $res=_query($q);
    $row = _fetch_row($res);
    return $row[0];
}
//impuesto DIF
function getDifImpuesto($id_apertura){
  $q0="SELECT COALESCE(sum(valor),0) AS imp_dif FROM impuestos_gasolina
        WHERE dif=1";
  $r0=_query($q0);
  $row0 = _fetch_row($r0);
  $n =_num_rows($r0);
  $valor_dif = 0;
  $total_galones_dif =0;
  if($n>0){
    $valor_dif = $row0[0];
  }
  $q="SELECT COALESCE(sum(fi.galones_dif),0) AS total_galones_dif
  FROM fact_imp_combust AS fi
  JOIN factura AS f ON f.id_factura=fi.id_factura
  WHERE fi.id_dif!=-1 and  fi.id_impuesto=2
      and fi.galones_dif>0
      and fi.total_imp<=0
      and fi.anulada=0
      AND f.id_apertura='$id_apertura'
      AND f.anulada=0
    ";
    $res=_query($q);
    $row = _fetch_row($res);
    $total_galones_dif = $row[0];
    $total_impuesto=$valor_dif * $total_galones_dif;
    return $total_impuesto;
}
?>
