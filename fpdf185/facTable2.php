<?php


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
