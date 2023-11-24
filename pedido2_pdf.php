<?php

error_reporting(E_ERROR | E_PARSE);
require('_core.php');
require('fpdf185/fpdf.php');
require('fpdf185/facTable.php');
include('num2letras.php');
include('AlignMarginText.php');

//$pdf = new FPDF('P', 'mm', 'A4');
$pdf = new PDFact('P', 'mm', 'letter');

$secciones_base= array(
  //lineas vericales que dividen la pagina en tres
  'l0' => 0,
  'l1' => 139,
  'l2' => 215,
  'l3' => 255,
  //si se muestran las lineas guia o no
  'mostrar' => 1,
  //altura de impresion de los encabezados y area de impresion
  //encabezados de arriba
  'e1' => 34,
  'e2' => 40,
  'e3' => 46,
  'e4' => 52,
  'e5' => 58,
  //area de impresion detalles
  'e6' => 58,
  //'e6' => 55,
  'e7' => 240,
  'e8' => 245,
  'e9' => 250,
  //footer
  'f1' => 245,
  'f2' => 250,
  'f3' => 255,
  'f4' => 255,
  'f5' => 260,
  'f6' => 265,
  'f7' => 170,
  'f8' => 175,
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

  'linvert'=>array(
    'l1'=>20,
    'l2'=>145,
    'l3'=>175,
    'l4'=>260,
    //'l5'=>320,
  ),
);
$ncol=1;
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
$row     = _fetch_assoc($result1);
$fecha_fact   = ed($row['fecha']);
$txt_prima   = " POR : $ ". sprintf("%.2f", $row['total']);
$txt_pedido   = "PEDIDO # ". $id_factura;
$tipo_pago   = $row['tipo_pago'];
$condicion = 'Contado';
if ($tipo_pago=='CRE') {
    $condicion = 'Crédito';
}
$result2 = cliente_pedido($id_factura);
$r_items=datos_pedido_det($id_factura);
$n_items=_num_rows($r_items);
$row_client =_fetch_assoc($result2);
$duinit=$row_client['dui'];
$cliente="CLIENTE: ". $row_client['nombre'];
$dir="DIRECCION: ". $row_client['direccion'];

$imagen = getLogo();
//empresa
$empresa = getEmpresa();
$pdf->Image($imagen, 10, 10, 25, 0);
//$pdf->Image($imagen, 160, 10, 25, 0);
$pdf->SetFont('Arial', '', 10);
$total_txt= getTotalTexto(number_format($row['total'], 2, ".", ""));
$total = number_format($row['total'], 2, '.', '');
$saldo = $total - $row['abono'];
$saldo = number_format($saldo, 2, '.', '');
for ($i=0; $i < $ncol; $i++) {
    $pdf->SetFont('Arial', '', 10);
    // primera linea
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e1']-4);
    $array_data = array(
        array("",160,"L"),
        array("FECHA: ".$fecha_fact,40,"L"),
    );
    $pdf->LW($array_data);
    // 2da. linea

    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e2']-4);
    $array_data = array(
        array(" ",10,"L"),
        array($txt_pedido,90,"L"),
        array(" ",10,"L"),
        array($txt_prima,90,"L"),
    );
    $pdf->LW($array_data);

    //3a linea
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e3']-4);
    $array_data = array(
        array("",10,"L"),
        array(strtoupper($cliente),100,"L"),
    );
    $pdf->LW($array_data);
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e4']-4);
    $array_data = array(
        array("",10,"L"),
        array(strtoupper($dir),100,"L"),
    );
    $pdf->LW($array_data);

    //5 linea
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), $sb['e5']-4);
    $array_data = array(
        array("CANT",20,"L"),
        array("DESCRIPCIÓN",120,"L"),
        array("PRECIO",30,"C"),
        array("SUBTOTAL",30,"C"),
    );
    $pdf->LW($array_data);
    $pdf->SetFont('Arial', '', 7);
    $array_data = array(
        array("",200,"L"),

    );
    $pdf->LW($array_data);
    for ($j=0;$j<$n_items;$j++) {
        $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['e6']+($j*3)));
        $row_items = _fetch_array($r_items);
        $id        = $row_items['id_producto'];
        $desc      = $row_items['descripcion'];
        $qty       = $row_items['cantidad'];
        $bcode     = $row_items['barcode'];
        $pv        = $row_items['precio_venta'];
        $subt      = $row_items['subtotal'];

        $array_data = array(
          array($qty,20,"L"),
          array($desc,115,"L"),
          array("$ ".sprintf("%.2f", $pv),30,"R"),
          array("$ ".sprintf("%.2f", $subt),28,"R")
        );
        $pdf->LW($array_data);
        // $pdf->SetXY(($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]))+140, ($sb['e5']+($j*3)));
       // $pdf->LW($array_data);
    }
    //$pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]),($sb['e6']+($j*3)));

    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f1']-4));
    $array_data = array(

      array(" ",140,"L"),
      array(" TOTAL ",35,"C"),
      array("$ ".sprintf("%.2f", $total),25,"R"),
    );
    $pdf->LW($array_data);
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f2']-4));
    $array_data = array(

      array("  ".$total_txt,200,"L"),

    );
    $pdf->LW($array_data);
    /*
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f2']-4));
    $array_data = array(
      array("",140,"L"),
      array("PRIMA ",30,"L"),
      array("$ ".sprintf("%.2f", $row['abono']),30,"L"),
    );
    $pdf->LW($array_data);
    $pdf->SetXY($sb[$sb["imll$i"]["b"]]+($sb["imll$i"]["p"]), ($sb['f3']-4));
    $array_data = array(
      array("",140,"L"),
      array("SALDO ",30,"L"),
      array("$ ".sprintf("%.2f", $saldo),30,"L"),
    );
    $pdf->LW($array_data);*/
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
      array(" ".$dir,100,"L"),
    );
    $pdf->LW($array_data);
    //agregar pie
    //$empresa
}

$nom_file = "fact_" . $fecha_fact .'_' .$id_factura. ".pdf";

$pdf->Output("I", $nom_file);
