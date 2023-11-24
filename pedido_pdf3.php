<?php

error_reporting(E_ERROR | E_PARSE);
require('_core.php');
require('fpdf/fpdf.php');
class PDFact extends FPDF
{
    public $libase = array();
    // Cabecera de página\
    public function Header()
    {
        $set_y=$this->GetY();
        $set_x=$this->GetX();
        $this->SetXY($set_x, $set_y);
        $this->SetFont('Arial', '', 5);
        $ncol=$this->ncol;
        // divisiones tres copias lines
        if ($this->libase['mostrar']==1) {
            //division de la pagina

            for ($i=1; $i < $ncol; $i++) {
                $this->Line($this->libase["l$i"], 0, $this->libase["l$i"], 215);
            }

            //lineas encabezado
            for ($i=1; $i < 10; $i++) {
                $this->Line(6, $this->libase["e$i"], 275, $this->libase["e$i"]);
            }
            for ($i=0; $i < 8; $i++) {
                $this->Line(($this->libase["linvert"]["l$i"]), 55, $this->libase["linvert"]["l$i"], 195);
            }
        }
    }

    public function Footer()
    {
    }
    public function setear($a, $b, $c, $d, $e, $f, $g, $w)
    {
        # code...
        $this->a=$a;
        $this->b=$b;
        $this->c=$c;
        $this->d=$d;
        $this->e=$e;
        $this->f=$f;
        $this->g=$g;
        $this->w=$w;
    }

    public function LineWriteB($array)
    {
        $ygg=0;
        $maxlines=1;
        $array_a_retornar=array();
        $array_max= array();
        foreach ($array as $key => $value) {
            // /Descripcion/
            $nombr=$value[0];
            // /fpdf width/
            $size=$value[1];
            // /fpdf alignt/
            $aling=$value[2];
            $jk=0;
            $w = $size;
            $h  = 0;
            $txt=$nombr;
            $border=0;
            if (!isset($this->CurrentFont)) {
                $this->Error('No font has been set');
            }
            $cw = &$this->CurrentFont['cw'];
            if ($w==0) {
                $w = $this->w-$this->rMargin-$this->x;
            }
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r", '', $txt);
            $nb = strlen($s);
            if ($nb>0 && $s[$nb-1]=="\n") {
                $nb--;
            }
            $b = 1;

            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $nl = 1;
            while ($i<$nb) {
                // Get next character
                $c = $s[$i];
                if ($c=="\n") {
                    $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $i-$j);
                    $array_a_retornar[$ygg]["size"][]=$size;
                    $array_a_retornar[$ygg]["aling"][]=$aling;
                    $jk++;

                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    $nl++;
                    if ($border && $nl==2) {
                        $b = $b2;
                    }
                    continue;
                }
                if ($c==' ') {
                    $sep = $i;
                    $ls = $l;
                    $ns++;
                }
                $l += $cw[$c];
                if ($l>$wmax) {
                    // Automatic line break
                    if ($sep==-1) {
                        if ($i==$j) {
                            $i++;
                        }
                        $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $i-$j);
                        $array_a_retornar[$ygg]["size"][]=$size;
                        $array_a_retornar[$ygg]["aling"][]=$aling;
                        $jk++;
                    } else {
                        $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $sep-$j);
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
                    if ($border && $nl==2) {
                        $b = $b2;
                    }
                } else {
                    $i++;
                }
            }
            // Last chunk
            if ($this->ws>0) {
                $this->ws = 0;
            }
            if ($border && strpos($border, 'B')!==false) {
                $b .= 'B';
            }
            $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $i-$j);
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
        foreach ($array_a_retornar as $keys) {
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
                $str = $data[$j]["valor"][$i];
                $this->Cell($data[$j]["size"][$i], 4, $str, $abajo, $salto, $data[$j]["aling"][$i]);
            }
        }
    }
    public function lineguide($secciones_base, $ncol)
    {
        $this->libase =$secciones_base;
        $this->ncol =$ncol;
    }
    public function LW($array)
    {
        $ygg=0;
        $maxlines=1;
        $array_a_retornar=array();
        $array_max= array();
        foreach ($array as $key => $value) {
            // /Descripcion/
            $nombr=$value[0];
            // /fpdf width/
            $size=$value[1];
            // /fpdf alignt/
            $aling=$value[2];
            $jk=0;
            $w = $size;
            $h  = 0;
            $txt=$nombr;
            $border=0;
            if (!isset($this->CurrentFont)) {
                $this->Error('No font has been set');
            }
            $cw = &$this->CurrentFont['cw'];
            if ($w==0) {
                $w = $this->w-$this->rMargin-$this->x;
            }
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r", '', $txt);
            $nb = strlen($s);
            if ($nb>0 && $s[$nb-1]=="\n") {
                $nb--;
            }
            $b = 1;

            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $nl = 1;
            while ($i<$nb) {
                // Get next character
                $c = $s[$i];
                if ($c=="\n") {
                    $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $i-$j);
                    $array_a_retornar[$ygg]["size"][]=$size;
                    $array_a_retornar[$ygg]["aling"][]=$aling;
                    $jk++;

                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    $nl++;
                    if ($border && $nl==2) {
                        $b = $b2;
                    }
                    continue;
                }
                if ($c==' ') {
                    $sep = $i;
                    $ls = $l;
                    $ns++;
                }
                $l += $cw[$c];
                if ($l>$wmax) {
                    // Automatic line break
                    if ($sep==-1) {
                        if ($i==$j) {
                            $i++;
                        }
                        $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $i-$j);
                        $array_a_retornar[$ygg]["size"][]=$size;
                        $array_a_retornar[$ygg]["aling"][]=$aling;
                        $jk++;
                    } else {
                        $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $sep-$j);
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
                    if ($border && $nl==2) {
                        $b = $b2;
                    }
                } else {
                    $i++;
                }
            }
            // Last chunk
            if ($this->ws>0) {
                $this->ws = 0;
            }
            if ($border && strpos($border, 'B')!==false) {
                $b .= 'B';
            }
            $array_a_retornar[$ygg]["valor"][]=substr($s, $j, $i-$j);
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
        foreach ($array_a_retornar as $keys) {
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
        $salto = 0;
        for ($i=0; $i < $total_lineas; $i++) {
            // code...
            for ($j=0; $j < $total_columnas; $j++) {
                if ($i==0) {
                    $str = $data[$j]["valor"][$i];
                    // $this->Cell($data[$j]["size"][$i], 4, utf8_decode($str), $this->libase['mostrar'], $salto, $data[$j]["aling"][$i]);
                    $this->Cell($data[$j]["size"][$i], 4, utf8_decode($str), 0, $salto, $data[$j]["aling"][$i]);
                }
            }
        }
    }
}
//$pdf = new FPDF('P', 'mm', 'A4');
$pdf = new PDFact('L', 'mm', 'letter');

$secciones_base= array(
  //lineas vericales que dividen la pagina en tres
  'l0' => 0,
  'l1' => 139,
 // 'l2' => 215,

  //si se muestran las lineas guia o no
  'mostrar' => 1,
  //altura de impresion de los encabezados y area de impresion
  //encabezados de arriba
  /*'e1' => 36,
  'e2' => 41,*/
  'e1' => 20,
  'e2' => 40,
  'e3' => 45,
  'e4' => 50,
  'e5' => 55,
  //area de impresion detalles
  'e6' => 55,
  //'e6' => 190,
  'e7' => 190,
  'e8' => 195,
  'e9' => 200,
  //footer
  'f1' => 195,
  'f2' => 200,
  'f3' => 205,
  'f4' => 210,
  'f5' => 225,
  'f6' => 230,
  'f7' => 235,
  'f8' => 240,
  //limite de impresion izquierdo al lado de cant para ver como saldra impreso limirte b mas p
  'imll0' => array(
    'b' => 'l0',
    'p' => 4,
  ),
  'imll1' => array(
    'b' => 'l1',
    'p' => 5,
  ),
  'imll2' => array(
    'b' => 'l2',
    'p' => 5,
  ),
  //limite de impresion derecho al lado de ventas gravadas para ver como saldra impreso limite b menos b
  'imlr1' => array(
    'b' => 'l1',
    'm' => 5,
  ),
  'imlr2' => array(
    'b' => 'l2',
    'm' => 5,
  ),

  'imlr3' => array(
    'b' => 'l3',
    'm' => 4,
  ),
  //limite de impresion footer
  'imlf1' => array(
    'b' => 'l1',
    'm' => 33,
  ),
  'imlf2' => array(
    'b' => 'l2',
    'm' => 33,
  ),

  'imlf3' => array(
    'b' => 'l3',
    'm' => 32,
  ),

  'linvert'=>array(
    'l0'=>6,
    'l1'=>22,
    'l2'=>90,
    'l3'=>115,
    'l4'=>155,
    'l5'=>235,
    'l6'=>255,
    'l7'=>275,
  ),
);
$ncol=2;
$pdf->lineguide($secciones_base, $ncol);

$sb= $secciones_base;

$id_factura = $_REQUEST['id_factura'];
$pdf->SetAutoPagebreak(false);
$pdf->SetMargins(0, 0, 0);
$pdf->AddPage();
$sql = 'SELECT count(*) FROM pedidos_detalle WHERE id_factura=' .$id_factura;
$result =  _query($sql) ;
$row_cli =  _fetch_row($result);

$nb_ln = $row_cli[0];

$result1 = datos_pedido($id_factura);
$row     = _fetch_array($result1);
$fecha_fact   = ed($row['fecha']);
$txt_prima   = "PEDIDO POR : $ ". sprintf("%.2f", $row['total']);
$txt_pedido   = "ORDEN DE PEDIDO";
$tipo_pago   = $row['tipo_pago'];
$condicion = 'COND: CONTADO';
if ($tipo_pago=='CRE') {
    $condicion = 'COND: CRÉDITO';
}
$result2 = cliente_pedido($id_factura);
$r_items=datos_pedido_det($id_factura);
$n_items=_num_rows($r_items);
$row_client =_fetch_array($result2);
$duinit=$row_client['dui'];
$cliente="CLIENTE: ". $row_client['nombre'];
$dir="DIRECCION: ". $row_client['direccion'];
$imagen = getLogo();
//empresa
$empresa = getEmpresa();
$pdf->Image($imagen, 20, 5, 25, 0);
$pdf->Image($imagen, 160, 5, 25, 0);
$pdf->SetFont('Arial', '', 10);
//$pdf->Cell(60, 8, "  " . ED($fecha), 0, 0, '');
$pdf->SetXY(75, 5);
$pdf->Cell(80, 6, $txt_pedido, 0, 1, 'C');
$pdf->SetFont('Arial', '', 14);
$pdf->SetXY(80, 8);
$pdf->Cell(80, 12, utf8_decode('N° ').$id_factura, 0, 1, 'C');
$pdf->SetXY(210, 5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(80, 6, $txt_pedido, 0, 1, 'C');
$pdf->SetFont('Arial', '', 14);
$pdf->SetXY(210, 8);
$pdf->Cell(80, 12, utf8_decode('N° ').$id_factura, 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(10);
$pdf->MultiCell(110, 6, utf8_decode($empresa['direccion']), 0, 'L');
$pdf->SetX(10);
$pdf->Cell(80, 6, $empresa['telefono1'], 0, 0, 'L');
$pdf->Cell(30, 6, "FECHA: ".$fecha_fact, 0, 1, 'L');
$pdf->SetXY(140, 20);
$pdf->MultiCell(110, 6, utf8_decode($empresa['direccion']), 0, 'L');
$pdf->SetX(140);
$pdf->Cell(80, 6, $empresa['telefono1'], 0, 0, 'L');
$pdf->Cell(30, 6, "FECHA: ".$fecha_fact, 0, 1, 'L');
$total_txt= getTotalTexto(number_format($row['total'], 2, ".", ""));
$total = number_format($row['total'], 2, '.', '');
$saldo = $total - $row['abono'];
$saldo = number_format($saldo, 2, '.', '');
for ($i=0; $i < $ncol; $i++) {
    $pdf->SetFont('Arial', '', 9);

    //3a linea
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e3']-4);
    $array_data = array(
        array("",3,"L"),
        array(strtoupper($cliente),98,"L"),
        array($condicion,40,"L"),

    );
    $pdf->LW($array_data);

    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e4']-4);
    $array_data = array(
        array("",3,"L"),
        array(strtoupper($dir),100,"L"),
    );
    $pdf->LW($array_data);
    //4a linea
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e5']-4);
    $array_data = array(
        array(" CANT",20,"C"),
        array("DESCRIPCIÓN",75,"L"),
       // array("  ",2,"C"),
        array("PRECIO",18,"C"),
        array("TOTAL" ,18,"C"),
    );
    $pdf->LW($array_data);
    $pdf->SetFont('Arial', '', 6);
    for ($j=0;$j<$n_items;$j++) {
        //$pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]),($sb['e6']+($j*3)));
        $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['e6']+($j*3)));

        $row_items =_fetch_array($r_items);
        $id= $row_items['id_producto'];
        $desc= "  ".$row_items['descripcion'];
        $qty= $row_items['cantidad'];
        $pv=sprintf("%.2f", round($row_items['precio_venta'], 2));
        $subt= sprintf("%.2f", round($row_items['subtotal'], 2));
        if ($subt>0) {
            $array_data = array(
          array($qty,18,"R"),
          array($desc,76,"L"),
        //  array("  ",2,"C"),
          array($pv,17,"R"),
          array($subt,18,"R"),
        );

            $pdf->LW($array_data);
            $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"])+133, ($sb['e6']+($j*3)));
            $pdf->LW($array_data);
        }
    }
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['e6']+($j*3)));
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f1']-4));
    $array_data = array(
      array(" ",90,"L"),
      array("TOTAL ",25,"C"),
      array("$ ".sprintf("%.2f", $total),35,"L"),
    );
    $pdf->LW($array_data);
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f2']-4));
    $array_data = array(
      array(" ",10,"L"),
      array(" ".$total_txt,110,"L"),
    );
    $pdf->LW($array_data);

    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f4']+2));
    $array_data = array(
      array("",1,"L"),
      array(" ".$empresa['www'],100,"L"),
    );
    $pdf->LW($array_data);

    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f5']+2));
    $array_data = array(
      array("",1,"L"),
      array("TEL. ".$empresa['telefono1'],100,"L"),
    );
    $pdf->LW($array_data);
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f6']+2));
    $array_data = array(
      array("",1,"L"),
      array(" ".$empresa['direccion'],100,"L"),
    );
    $pdf->LW($array_data);
    //agregar pie
    //$empresa
}
$pdf->Line(275, 20, 275, 60);
$pdf->Line(6, 20, 6, 60);
$pdf->Line(6, 195, 6, 200);
$pdf->Line(275, 195, 275, 200);
$nom_file = "ped_" . $fecha_fact .'_' .$id_factura. ".pdf";

$pdf->Output("I", $nom_file);
