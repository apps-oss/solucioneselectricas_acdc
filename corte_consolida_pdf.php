<?php
error_reporting(E_ERROR | E_PARSE);
include("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');


class PDF extends FPDF
{
    var $a;
    var $b;
    var $c;
    var $d;
    var $e;
    var $f;
    var $w;
    // Cabecera de página\
    public function Header()
    {
      $correlativo = $this->a;
      $usaVal = $_GET['valor'];
      $encabezado ="";
      if ($usaVal>1||!is_numeric($usaVal)) {
        // code...
        $encabezado = " ES";
      }
      else if ($usaVal==0) {
        // code...
        $encabezado = " USA";
      }
      else if ($usaVal==1) {
        // code...
        $encabezado = " ES";
      }
      else{
      }
      //Encabezado General

      $this->SetFont('arial','',6);
      $set_x = 0;
      $set_y = 15;
      $this->SetXY($set_x, $set_y);
        $this->SetFont('arial','',6);
      $this->Cell(120,6,utf8_decode("BOLETA DE PAGO"),0,1,'C');
      $this->Ln(10);
    }

    public function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetXY(20, 197);
        $this->Cell(30,5,"F._______________________",0,0,'C');
        $this->SetXY(80, 197);
        $this->Cell(30,5,"F._______________________",0,0,'C');

        $this->SetXY(162, 197);
        $this->Cell(30,5,"F._______________________",0,0,'C');
        $this->SetXY(225, 197);
        $this->Cell(30,5,"F._______________________",0,1,'C');

        $this->SetXY(20, 202);
        $this->Cell(30,5,"PATRONO",0,1,'C');
        $this->SetXY(80, 202);
        $this->Cell(30,5,"EMPLEADO",0,1,'C');

        $this->SetXY(165, 202);
        $this->Cell(30,5,"PATRONO",0,1,'C');
        $this->SetXY(225, 202);
        $this->Cell(30,5,"EMPLEADO",0,1,'C');

        $this->SetY(-12);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página requiere $pdf->AliasNbPages();
        //utf8_decode() de php que convierte nuestros caracteres a ISO-8859-1
        $this-> Cell(40, 10, utf8_decode('Fecha de impresión: '.date('Y-m-d')), 0, 0, 'L');
        $this->Cell(156, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'R');
    }
    public function setear($a,$b,$c,$d,$e,$f,$g,$w)
    {
      $this->a=$a;
      $this->b=$b;
      $this->c=$c;
      $this->d=$d;
      $this->e=$e;
      $this->f=$f;
      $this->g=$g;
      $this->w=$w;
    }

    function LineWrite($array)
    {
    $ygg=0;
    $maxlines=1;
    $array_a_retornar=array();
    $array_max= array();
    foreach ($array as $key => $value) {
      /*Descripcion*/
      $nombr=$value[0];
      /*fpdf width*/
      $size=$value[1];
      /*fpdf alignt*/
      $aling=$value[2];
      $jk=0;
      $w = $size;
      $h  = 0;
      $txt=$nombr;
      $border=0;
      if(!isset($this->CurrentFont))
        $this->Error('No font has been set');
      $cw = &$this->CurrentFont['cw'];
      if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
      $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
      $s = str_replace("\r",'',$txt);
      $nb = strlen($s);
      if($nb>0 && $s[$nb-1]=="\n")
        $nb--;
      $b = 1;
      $sep = -1;
      $i = 0;
      $j = 0;
      $l = 0;
      $ns = 0;
      $nl = 1;
      while($i<$nb)
      {
        // Get next character
        $c = $s[$i];
        if($c=="\n")
        {
          $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
          $array_a_retornar[$ygg]["size"][]=$size;
          $array_a_retornar[$ygg]["aling"][]=$aling;
          $jk++;

          $i++;
          $sep = -1;
          $j = $i;
          $l = 0;
          $ns = 0;
          $nl++;
          if($border && $nl==2)
            $b = $b2;
          continue;
        }
        if($c==' ')
        {
          $sep = $i;
          $ls = $l;
          $ns++;
        }
        $l += $cw[$c];
        if($l>$wmax)
        {
          // Automatic line break
          if($sep==-1)
          {
            if($i==$j)
              $i++;
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;
          }
          else
          {
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;
            $i = $sep+1;
          }
          $sep = -1;
          $j = $i;
          $l = 0;
          $ns = 0;
          $nl++;
          if($border && $nl==2)
            $b = $b2;
        }
        else
          $i++;
      }
      // Last chunk
      if($this->ws>0)
      {
        $this->ws = 0;
      }
      if($border && strpos($border,'B')!==false)
        $b .= 'B';
      $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
      $array_a_retornar[$ygg]["size"][]=$size;
      $array_a_retornar[$ygg]["aling"][]=$aling;
      $jk++;
      $ygg++;
      if ($jk>$maxlines) {
        $maxlines=$jk;
      }
    }

    $ygg=0;
    foreach($array_a_retornar as $keys)
    {
      for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
        $array_a_retornar[$ygg]["valor"][]="";
        $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
        $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
      }
      $ygg++;
    }
    $data=$array_a_retornar;
    $total_lineas=count($data[0]["valor"]);
    $total_columnas=count($data);

    for ($i=0; $i < $total_lineas; $i++) {
      for ($j=0; $j < $total_columnas; $j++) {
        // code...
        $salto=0;
        $abajo="LR";
        if ($i==0) {
          // code...
          $abajo="TLR";
        }
        if ($j==$total_columnas-1) {
          // code...
          $salto=1;
        }
        if ($i==$total_lineas-1) {
          // code...
          $abajo="BLR";
        }
        if ($i==$total_lineas-1&&$i==0) {
          // code...
          $abajo="1";
        }
        $abajo=0;
        $str = $data[$j]["valor"][$i];
        //$this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i]);
        $this->Cell($data[$j]["size"][$i],4,$str,1,$salto,$data[$j]["aling"][$i]);
      }
     }
    }
    function LineWriteB($array)
    {
    $ygg=0;
    $maxlines=1;
    $array_a_retornar=array();
    $array_max= array();
    foreach ($array as $key => $value) {
      /*Descripcion*/
      $nombr=$value[0];
      /*fpdf width*/
      $size=$value[1];
      /*fpdf alignt*/
      $aling=$value[2];
      $jk=0;
      $w = $size;
      $h  = 0;
      $txt=$nombr;
      $border=0;
      if(!isset($this->CurrentFont))
        $this->Error('No font has been set');
      $cw = &$this->CurrentFont['cw'];
      if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
      $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
      $s = str_replace("\r",'',$txt);
      $nb = strlen($s);
      if($nb>0 && $s[$nb-1]=="\n")
        $nb--;
      $b = 1;

      $sep = -1;
      $i = 0;
      $j = 0;
      $l = 0;
      $ns = 0;
      $nl = 1;
      while($i<$nb)
      {
        // Get next character
        $c = $s[$i];
        if($c=="\n")
        {
          $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
          $array_a_retornar[$ygg]["size"][]=$size;
          $array_a_retornar[$ygg]["aling"][]=$aling;
          $jk++;

          $i++;
          $sep = -1;
          $j = $i;
          $l = 0;
          $ns = 0;
          $nl++;
          if($border && $nl==2)
            $b = $b2;
          continue;
        }
        if($c==' ')
        {
          $sep = $i;
          $ls = $l;
          $ns++;
        }
        $l += $cw[$c];
        if($l>$wmax)
        {
          // Automatic line break
          if($sep==-1)
          {
            if($i==$j)
              $i++;
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;
          }
          else
          {
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;

            $i = $sep+1;
          }
          $sep = -1;
          $j = $i;
          $l = 0;
          $ns = 0;
          $nl++;
          if($border && $nl==2)
            $b = $b2;
        }
        else
          $i++;
      }
      // Last chunk
      if($this->ws>0)
      {
        $this->ws = 0;
      }
      if($border && strpos($border,'B')!==false)
        $b .= 'B';
      $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
      $array_a_retornar[$ygg]["size"][]=$size;
      $array_a_retornar[$ygg]["aling"][]=$aling;
      $jk++;
      $ygg++;
      if ($jk>$maxlines) {
        // code...
        $maxlines=$jk;
      }
    }

    $ygg=0;
    foreach($array_a_retornar as $keys)
    {
      for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
        // code...
        $array_a_retornar[$ygg]["valor"][]="";
        $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
        $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
      }
      $ygg++;
    }

    $data=$array_a_retornar;
    $total_lineas=count($data[0]["valor"]);
    $total_columnas=count($data);

    for ($i=0; $i < $total_lineas; $i++) {
      // code...
      for ($j=0; $j < $total_columnas; $j++) {
        // code...
        $salto=0;
        $abajo="LR";
        if ($i==0) {
          // code...
          $abajo="TLR";
        }
        if ($j==$total_columnas-1) {
          // code...
          $salto=1;
        }
        if ($i==$total_lineas-1) {
          // code...
          $abajo="BLR";
        }
        if ($i==$total_lineas-1&&$i==0) {
          // code...
          $abajo="1";
        }
        if ($j==0) {
          // code...
          $abajo="0";
        }
        $str = $data[$j]["valor"][$i];
        $this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i]);
      }
    }
  }
}

$pdf = new PDF('L', 'mm', 'Letter');
$pdf->setear("","","","","","","","");
$pdf->SetMargins(10, 10);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 25);
$pdf->AliasNbPages();
$pdf->setear('', '', '', '', '', '', '', '');
$pdf->AddPage();

$set_x = 10;
$set_y = 8;

$l = array(
  's' => 0.1,
  'col1' => 40,
  'col2' => 60,
  'col3' => 40,
);
  $pdf->SetFont('arial','',6);
$nombre="X";
$array_data = array(
  array('',$l['s'],"L"),
  array('EMPLEADO:',$l['col1'],"L"),
  array($nombre,$l['col2'],"L"),
  array('',$l['col3'],"L"),
  array('desEMPLEADO:',$l['col1'],"L"),
  array($nombre,$l['col2'],"L"),
);
$pdf->LineWrite($array_data);
$pdf->LN(1);

$pdf->Output();
?>
