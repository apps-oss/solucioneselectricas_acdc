<?php

include('AlignMarginText.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');
function getMargins($alias, $id_sucursal=1)
{
    $q="SELECT  id, alias,marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10,
  marg_body,marg_foot, cols_body, col_body_arr, lines_body,
  f1, f2, f3, f4, f5, f6, f7, f8, f9, f10
  FROM margen_cols_form
  WHERE alias='$alias' AND id_sucursal='$id_sucursal'";
    $res=_query($q);
    $datos = _fetch_array($res);
    return $datos;
}
function print_ticket($id_factura)
{
    $align=new AlignMarginText();
    $id_sucursal=$_SESSION['id_sucursal'];
    $line1 = str_repeat("_", 46) . "\n";
    $empresa=empresa();
    $alias= 'TIK';

    //Sucursal
    $row_suc=datos_sucursal($id_sucursal);
    $nitsuc= $row_suc['nit'];
    $nrcsuc= $row_suc['nrc'];
    $direcsuc= $row_suc['direccion'];
    $code_giro= $row_suc['cod_act_eco'];
    $giro_suc=get_giro($code_giro);
    $razonsuc= $row_suc['nombre_comercial'];
    //detalles
    $result_fact=datos_factura($id_factura);
    $nrows_fact=_num_rows($result_fact);
    $det_ticket = "";
    $espacio = " ";
    $margen_izq1 =$align->leftmargin($espacio, 1);
    $margen_izq2 =$align->leftmargin($espacio, 4);
    $esp_init = $margen_izq1;
    $total      = 0;
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);
        $id_cliente = $row_fact['id_cliente'];
        $id_factura = $row_fact['id_factura'];
        $id_usuario = $row_fact['id_usuario'];
        $id_vendedor= $row_fact['id_empleado'];
        $total      = $row_fact['total'];
        $fecha=$row_fact['fecha'];
        $hora=$row_fact['hora'];
        $caja=$row_fact['caja'];
        $turno=$row_fact['turno'];
        $fecha_fact=ed($fecha);
        $numero_doc=trim($row_fact['numero_doc']);
        $total=$row_fact['total'];
        $descuent=$row_fact['descuento'];
        $porcentaje=$row_fact['porcentaje'];

        $total_efectivo = $row_fact['total_efectivo'];
        $total_tarjeta  = $row_fact['total_tarjeta'];
        $resPago  = getPagoXFactura($id_factura, "VAL");
        $nrowPago = _num_rows($resPago);
        $datos_extra = "";
        if ($nrowPago>0) {
            $rowPago   =  _fetch_array($resPago);
            $datos_extra = $rowPago['datos_extra'];
        }
        $dats_caja = getCaja($caja);
        $fehca = ED($dats_caja["fecha"]);
        $resolucion = $dats_caja["resolucion"];
        $serie = $dats_caja["serie"];
        $desde = $dats_caja["desde"];
        $hasta = $dats_caja["hasta"];
        $cajero= getCajero($id_usuario);
        $nombrecaja=$dats_caja["nombre"];
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto="";
        $muni="";
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nitcte=$row1["nit"];
            $nrccte=$row1["nrc"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombreapecte=$row1['nombre'];
            $direccion=$row1['direccion'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $codigocliente=$row1['codcliente'];
            if (isset($id_d) && isset($id_m)) {
                $depto=getNombreDepartamentoId($id_d);
                $muni=getNombreMunicipioCod($id_d, $id_m);
                /*$row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];*/
            }
        }
        //dividir texto giro
        $desgiro = $align->wordwrap1("GIRO: ".$giro_suc, 55);
        $descgiro="";
        foreach ($desgiro as $lin) {
            $descgiro .= trim($lin). "\n";
            //$descgiro .= $align->onelineleft($lin,40, 1, $espacio). "\n";
        }
        //dividir texto de direccion
        $desdireccion = $align->wordwrap1("DIRECCION: ".$direcsuc, 60);
        $desdirec="";
        foreach ($desdireccion as $lin) {
            $desdirec .= trim($lin). "\n";
            //$descgiro .= $align->onelineleft($lin,40, 1, $espacio). "\n";
        }
        $nombreVendedor=vendedor($id_vendedor);
        $len_numero_doc=strlen($numero_doc)-4;
        $tiq=substr($numero_doc, 0, $len_numero_doc);
        $date1 = new DateTime($fecha." ".$hora);
        $hora1= $date1->format("g"). ':' .$date1->format("i"). ' ' .$date1->format("A");
        $fecha1 = $date1->format("d"). '/' .$date1->format("m"). '/' .$date1->format("Y");
        //$tiq = zfill($corr, 10);LEFT_P
        $hstring  = CENTER_P;//chr(27) . chr(97) . chr(1); //Center
        $hstring .= DOUBLEFONT_P;// chr(27) . chr(33) . chr(16); //FONT double size pos
        $hstring .= $empresa."\n";
        $hstring .= FONT_B; //FONT a medium size
        $hstring .= $razonsuc."\n";
        $hstring .= $descgiro;
        $hstring .= $desdirec;
        /*
        $hstring .= "NIT :".$nitsuc." NRC :".$nrcsuc."\n";
        $hstring .= "RESOLUCION:  ".$resolucion."\n";
        $hstring .= " DEL ".$desde." AL ".$hasta."\n";
        $hstring .= " SERIE ".$serie."\n";
        $hstring .= " FECHA RESOLUCION ".$fehca."\n";
        */
        $hstring .= " TICKET #: " . $tiq . "\n";
        $hstring .= " FECHA: " .	$fecha1 . " HORA:" . $hora1 . "\n";
        $hstring .= " VENDEDOR: ".$nombreVendedor."\n";
        $hstring .= " CAJERO: ". $cajero . "\n";
        $hstring .= " CAJA : ".$nombrecaja. "  TURNO: ".$turno."\n";
        $hstring .=head($alias);
        $hstring .=  LEFT_P;
        $th = chr(13) . " DESCRIPCION                      CANT.      P.U.    SUBTOTAL" . "\n";
        $det_ticket = chr(13) . $line1; // Print text Lin
        $det_ticket .= FONT_B; //FONT B small size
        $det_ticket .=  $th;
        $det_ticket .= FONT_A; //FONT a medium size
        $det_ticket .= chr(13) . $line1. "\n";

        $total_final=0;
        $lineas=6;
        $cuantos=0;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;
        $tmpItems= array();
        $wdesc = 60;
        $desc = "";
        $det_ticket .=  FONT_B;//$_font_b; //FONT B small size
        //Obtener informacion de tabla Factura_detalle y producto o servicio
        $result_fact_det=datos_fact_det($id_factura);
        $nrows_fact_det=_num_rows($result_fact_det);
        for ($i=0;$i<$nrows_fact_det;$i++) {
            $row_fact_det=_fetch_array($result_fact_det);
            $id_producto =$row_fact_det['id_producto'];
            $descripcion =$row_fact_det['descripcion'];
            //descripcion presentacion
            $id_presentacion =$row_fact_det['id_presentacion'];
            $descpre =$row_fact_det['descpre'];
            $nombre_pre =$row_fact_det['descp'];
            $descpresenta =$row_fact_det['descripcion_pr'];
            $exento=$row_fact_det['exento'];
            $id_factura_detalle =$row_fact_det['id_factura_detalle'];
            $id_prod_serv =$row_fact_det['id_prod_serv'];
            $cantidad =$row_fact_det['cantidad'];
            $precio_venta =$row_fact_det['precio_venta'];
            $descuento =$row_fact_det['descuento'];
            $subt=$row_fact_det['subtotal'];
            $unidad=$row_fact_det['unidad'];
            //$subt = $subt - $descuento;
            $id_empleado =$row_fact_det['id_empleado'];
            $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];

            $cantidad=$cantidad/$unidad;
            $descripcion1 = substr($descripcion." ".$nombre_pre, 0, 36);
            $descfinal    = $align->addspright($descripcion1, 36);
            $descripts = $align->wordwrap1($descripcion1, $wdesc);
            $tmplinea = array();
            $ln=0;

            foreach ($descripts as $descrip) {
                $descript = $align->addspright($descrip, $wdesc);
                $desc .= $align->onelineleft($descript, $wdesc, 1, $espacio). "\n";
                $ln=$ln+1;
            }
            $precio_unit=sprintf("%.4f", $precio_venta);
            $subtotal=sprintf("%.4f", $subt);
            $total_final=$total_final+$subtotal;
            if ($exento==0) {
                $e_g="G";
                $subt_gravado=sprintf("%.4f", $subt);
                $total_gravado=$subt_gravado+$total_gravado;
            } else {
                $e_g="E";
                $subt_exento=sprintf("%.4f", $subt);
                $total_exento=$subt_exento+$total_exento;
            }
            $subtotal=round($subtotal, 4);
            $pre  = $align->rightaligner(number_format($precio_unit, 2, ".", ","), $espacio, 8);
            $cant = $align->rightaligner($cantidad, $espacio, 4);
            $subt = $align->rightaligner(number_format($subtotal, 2, ".", ","), $espacio, 9);
            $det_ticket .= $descfinal." " . $cant  ." ". $pre . " " . $subt . "\n";
        }
        $impuestoGas=getImpGass($id_factura);
        $n_imp = _num_rows($impuestoGas);
        for ($n=0;$n<$n_imp;$n++) {
            $row_imp=_fetch_array($impuestoGas);
            $imp_n = $row_imp['imp_nombre'];
            $imp_nombre = $row_imp['imp_nombre'];
            /*if( $row_imp['id_impuesto']==2 &&  $row_imp['id_dif']!='-1' ) {
              $tot_imp = 0;
            }
      else{*/
            $tot_imp = $row_imp['total_imp'];
            //}
            $total_imp= $align->rightaligner(number_format($tot_imp, 2, ".", ","), $espacio, 12);

            $c_imp = $align->rightaligner("-", $espacio, 5);
            $imp_nombre = $align->onelineleft($imp_n, '42', 1, $espacio);
            $det_ticket .= " - " . $imp_nombre . "  " . $margen_izq1 .  $total_imp . "\n";
        }
        //DATOS EXTRA !!!
        if ($datos_extra!="" || !is_string($datos_extra)) {
            $data_ext = json_decode($datos_extra, true);
            foreach ($data_ext as $key => $data1) {
                $det_ticket  .= strtoupper($key. " : ". $data1). "\n";
            }
        }
        $det_ticket .= FONT_A;
        $det_ticket .= chr(13) . $line1;

        $totales =  DOUBLEFONT_P; //FONT DOUBLE
        $totales .= RIGHT_P;  //Right align
        $totals = "  TOTAL   $ " .number_format($total, 2, ".", ","). "  " . "\n";
        $lentot = strlen($totals);
        $totales .= $totals;
        $totales .= FONT_A;  //FONT A
        $totales .= str_repeat("_", $lentot) . "\n";
        $logo = getLogoSuc($id_sucursal);
        $uri=getUrl().$logo;
        $totales   .= CENTER_P; //center align
        $resPagoefec  = getPagoXFactura($id_factura, "CON");
        $nrowPagoefec = _num_rows($resPagoefec);
        $pstring ="";
        $pstring .= CENTER_P; //center align
        if ($nrowPagoefec>0) {
            $rowPagoEfec   =  _fetch_array($resPagoefec);
            $cambioEfec = $rowPagoEfec['datos_extra'];
            //mostrar cambio
            if ($cambioEfec!="" || !is_string($cambioEfec)) {
                $data_extEfect = json_decode($cambioEfec, true);
                foreach ($data_extEfect as $key => $data) {
                    //if($key=='cambio' && $data!='0')
                    $pstring  .= strtoupper($key. " : ". $data."  ");
                }
            }
        }
        //pie
        $pstring .="\n";
        $pstring .=foot($alias);
        if ($total_efectivo>0) {
            $totales .="PAGO EFECTIVO: ". $total_efectivo. "\n";
        }
        if ($total_tarjeta>0) {
            $totales .= "PAGO TARJETA: ".$total_tarjeta. "\n";
        }
        for ($n=0;$n<4;$n++) {
            $pstring .= "\n";
        }
        $pstring  .= CENTER_P; //center align
        $pstring .= FONT_A; //FONT A
        $total_letras = CENTER_P;
        $total_letras .= getTotalTexto(number_format($total, 2, ".", ","));
        $xdatos["encabezado"] = $hstring;
        $xdatos["totales"] = $totales;
        $xdatos["cuerpo"] = $det_ticket;
        $xdatos["pie"] = $pstring;
        $xdatos["total_letras"] = $total_letras;
        //$xdatos["img"] = $uri ;
        $xdatos["img"] = $uri ;
        return $xdatos;
    }
}
function print_fact($id_factura, $nitcte='', $nombreapecte='')
{
    $id_sucursal=$_SESSION['id_sucursal'];
    $align=new AlignMarginText();
    //traer  posiciones y columnas almacenados en bd
    $val        = getMargins('COF', $id_sucursal);
    $marg_sup   = $val['marg_sup'];
    $h1         = explode(',', $val['h1']);
    $h2         = explode(',', $val['h2']);
    $h3         = explode(',', $val['h3']);
    $h4         = explode(',', $val['h4']);
    $h5         = explode(',', $val['h5']);
    $h6         = explode(',', $val['h6']);
    $h7         = explode(',', $val['h7']);
    $h8         = explode(',', $val['h8']);
    $f1         = explode(',', $val['f1']);
    $f2         = explode(',', $val['f2']);
    $f3         = explode(',', $val['f3']);
    $f4         = explode(',', $val['f4']);
    $f5         = explode(',', $val['f5']);
    $f6         = explode(',', $val['f6']);
    $f7         = explode(',', $val['f7']);
    $f8         = explode(',', $val['f8']);
    $cb_arr     = explode(',', $val['col_body_arr']);
    $marg_body  = $val['marg_body'];
    $marg_foot  = $val['marg_foot'];
    $lines_body = $val['lines_body'];
    // FIN posiciones y columnas

    //Empresa
    $sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $iva=$row_empresa['iva']/100;
    //inicio datos
    //Obtener informacion de tabla Factura
    $result_fact=datos_factura($id_factura);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);
        $id_cliente=$row_fact['id_cliente'];
        $id_factura = $row_fact['id_factura'];
        $id_usuario=$row_fact['id_usuario'];
        $id_vendedor = $row_fact['id_empleado'];
        $extra_nombre=$row_fact['extra_nombre'];
        $credito= $row_fact['credito'];
        $fecha=$row_fact['fecha'];
        $fecha_fact=ed($fecha);
        $numero_doc=trim($row_fact['numero_doc']);
        $total=$row_fact['total'];
        $retencion= $row_fact['retencion'];
        $len_numero_doc=strlen($numero_doc)-4;
        $num_fact=substr($numero_doc, 0, $len_numero_doc);
        $tipo_fact=substr($numero_doc, $len_numero_doc, 4);
        //Datos del Cliente
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto="";
        $muni="";
        $vtacta = "CONTADO";
        if ($credito==1) {
            $vtacta = "CREDITO";
        }

        $resPago  = getPagoXFactura($id_factura, "VAL");
        $nrowPago = _num_rows($resPago);
        $datos_extra = "";
        if ($nrowPago>0) {
            $rowPago   =  _fetch_array($resPago);
            $datos_extra = $rowPago['datos_extra'];
        }
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nitcte=$row1["nit"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombres=$row1['nombre'];
            $nombreapecte=$row1['nombre'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $direccion=$row1["direccion"];
            $codigocliente=$row1['codcliente'];
            if (isset($id_d) && isset($id_m)) {
                $depto=getNombreDepartamentoId($id_d);
                $muni=getNombreMunicipioCod($id_d, $id_m);
                /*$row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];*/
            }
        }
        if ($nitcte=="") {
            $nitcte=$dui;
        }
        $txt_dui="DUI: ".$dui;
        $nombreVendedor=vendedor($id_vendedor);
        $dir_txt=substr($direccion, 0, 75);
        $total_final=0;
        $imprimir="";
        $info_factura ="";
        $info_factura .= chr(27).chr(54); //spanish latin print chars
    $info_factura .= LINEINCH6; // 12/203  1,6,12,18 etc  LINEINCH6; //6 lineas  por  pulgada
    //$hstring .= chr(27).chr(50); //6 lineas  por  pulgada
    for ($s=0;$s<$marg_sup;$s++) {
        $info_factura.="\n";
    }

        //Datos encabezado factura
        /*
        if($extra_nombre!=""){
          $nombreapecte=$nombreapecte." (".$extra_nombre.")";
        }*/
        //DIF
        $numero_dif=getIdDif($id_factura);
        if ($numero_dif!=" ") {
            $nombreapecte=$nombreapecte." DIF : ".$numero_dif."";
        }
        list($dd, $mm, $aa)=explode("-", $fecha_fact);
        $info_factura.= $align->addspright(" ", $h1[0]).$align->addspright($txt_dui, $h1[1]);//nit o dui $align->addspright($nitcte,$h4[1]).$align->addspleft("",$h4[2]).
        $info_factura.= $align->addspright(" ", $h1[2]).$align->addspright($dd."        ".$mm."        "."$aa", $h1[3])."\n\n";
        $info_factura.= $align->addspright(" ", $h2[0]).$align->addspright($nombreapecte, $h2[1])."\n";
        $info_factura.= $align->addspleft("", $h3[0]).$align->addspright($dir_txt, $h3[1])."\n"; //.$align->addspright($txt_dui,$h3[2])."\n";
        $info_factura.= $align->addspleft("", $h4[0]).$align->addspright($vtacta, $h4[1]);

        /*$info_factura.= $align->addspright("", $h1[0]).$align->addspright($nombreapecte, $h1[1]);

        $info_factura.= $align->addspleft("", $h1[1])." ".$dd." ".$mm." "."$aa"."\n\n";*/

        //$info_factura.= $align->addspleft("",$h5[0]).$align->addspright($nombreVendedor,$h5[1]).$align->addspleft("",$h5[2]).$align->addspright($codigocliente,$h5[3])."\n";// condiciones operacion
        for ($p=0;$p<$marg_body;$p++) {
            $info_factura.="\n";
        }
        //traer datos factura
        $result_fact_det=datos_fact_det($id_factura);
        $nrows_fact_det=_num_rows($result_fact_det);
        $lineas=6;
        $cuantos=1;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;
        $total_bonifica=0;
        //$info_factura.= chr(27).chr(48); //Select 8 lines per inch
    $info_factura.=  LINEINCH8; // 12/203  1,6,12,18 etc
        for ($i=0;$i<$nrows_fact_det;$i++) {
            $row_fact_det=_fetch_array($result_fact_det);
            $id_producto =$row_fact_det['id_producto'];
            $unidad=$row_fact_det['unidad'];
            $descripcion =trim($row_fact_det['descripcion']);
            //descripcion presentacion
            $descpre =trim($row_fact_det['descpre']);
            $descpresenta =trim($row_fact_det['descp']);
            $exento=$row_fact_det['exento'];
            $id_factura_detalle =$row_fact_det['id_factura_detalle'];
            $id_prod_serv =$row_fact_det['id_prod_serv'];
            $cantidad =$row_fact_det['cantidad'];
            $precio_venta =$row_fact_det['precio_venta'];
            $subt =$row_fact_det['subtotal'];
            $id_empleado =$row_fact_det['id_empleado'];
            $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
            $cantidad=$cantidad/$unidad;



            //linea a linea
            $descripcion1=substr($descripcion, 0, $cb_arr[3]).", ".substr($descpresenta, 0, 10);
            $lendesc=$cb_arr[3]+5;
            $subt=$precio_venta*$cantidad;
            $precio_unit=sprintf("%.4f", $precio_venta);
            $subtotal=sprintf("%.4f", $subt);
            $total_final=$total_final+$subtotal;

            if ($exento==0) {
                $e_g="G";
                $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                $precio_sin_iva0 =$row_fact_det['precio_venta'];
                $subt_sin_iva=round($precio_sin_iva0*$cantidad, 4);
                $subt_gravado=round($subt_sin_iva, 4);
                $total_gravado=$subt_sin_iva+$total_gravado;
            } else {
                $e_g="E";
                $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                $precio_sin_iva0 =$row_fact_det['precio_venta'];
                $subt_sin_iva=$precio_sin_iva0*$cantidad;
                $subt_exento=sprintf("%.4f", $subt_sin_iva);
                $total_exento=$subt_sin_iva+$total_exento;
                $total_gravado=$subt_sin_iva+$total_gravado;
            }
            $precio_sin_iva_print = round($precio_sin_iva, 4);
            $subt_sin_iva_print   = round($subt_sin_iva, 2);
            $psiva                = number_format($precio_sin_iva_print, 4);
            $ssiva                = number_format($subt_sin_iva_print, 4);
            $info_factura.=$align->addspright(" ", $cb_arr[0]); //margen inicial
            $info_factura.=$align->addspleft($cantidad, $cb_arr[1])." "; //cantidad
            $info_factura.=$align->addspright(" ", $cb_arr[2]);//espacio
            $info_factura.=$align->addspright($descripcion1, $lendesc); //descripcion
            $info_factura.=$align->addspleft($psiva, $cb_arr[4]);  //precio
            $info_factura.=$align->addspleft("", $cb_arr[5]);  //vta. no sujetas
            $info_factura.=$align->addspleft("", $cb_arr[6]);  //vta. exenta
            $info_factura.=$align->addspleft($ssiva, $cb_arr[7])."\n";
            $cuantos=$cuantos+1;
        }

        $espacio =" ";
        $total_gravado=round($total_gravado, 2);
        $margen_izq1 =$align->leftmargin($espacio, 1);
        $calc_iva               = round($iva * $total_gravado, 4);
        $total_iva_format       = sprintf("%.4f", $calc_iva);
        $total_value_exento     = sprintf("%.4f", $total_exento);
        $total_value_gravado    = sprintf("%.4f", $total_gravado);

        $cadena_salida_txt      = getTotalTexto($total);
        //DATOS EXTRA !!!
        if ($datos_extra!="" || !is_string($datos_extra)) {
            $data_ext = json_decode($datos_extra, true);
            foreach ($data_ext as $key => $data1) {
                $info_factura .= $align->addspleft(" ", 8).strtoupper($key. " : ". $data1). "\n";
                $cuantos++;
            }
        }
        //totales y n lineas
        $lineas_faltantes= $lines_body - $cuantos;
        if ($lineas_faltantes>0) {
            for ($j=0;$j<$lineas_faltantes;$j++) {
                $info_factura.= "\n";
            }
        }
        if ($marg_foot>0) {
            for ($k=0;$k<$marg_foot;$k++) {
                $info_factura.= "\n";
            }
        }
        $info_factura.= chr(27).chr(50); //Select 6 lines per inch

        //generar 2 lineas del texto del total de la factura
        $total_txt0 = $align->wordwrap1($cadena_salida_txt, $f1[1]);
        $tmplinea = array();
        $ln=0;
        foreach ($total_txt0 as $total_txt1) {
            $tmplinea[] = $align->addspright($total_txt1, $f1[1]+1);
            $ln=$ln+1;
        }
        $info_factura.=$align->addspleft(" ", $f1[0]).$align->addspright($tmplinea[0], $f1[1]);
        $info_factura.=$align->addspleft(" ", $f1[2]).$align->addspright(" ", $f1[3]).$align->addspleft($total_value_gravado, $f1[4])."\n";
        if ($ln<=1) {
            $info_factura.=$align->addspleft(" ", $f2[0]).$align->addspright(" ", $f2[1]);
        //$info_factura.=$align->addspleft(" ", $f2[2]).$align->addspright("AD HONOREM: ", $f2[3]).$align->addspleft(" ", $f2[4])."\n";
        } else {
            $info_factura.=$align->addspleft(" ", $f2[0]).$align->addspright($tmplinea[1], $f2[1]);
            //$info_factura.=$align->addspleft(" ", $f2[2]).$align->addspright("AD HONOREM: ", $f2[3]).$align->addspleft(" ", $f2[4])."\n";
        }

        $info_factura.= $align->addspleft(" ", $f2[2]).$align->addspright(" ", $f2[3]).$align->addspleft("0.0000", $f2[4])."\n";
        $info_factura.= $align->addspleft(" ", $f3[0]).$align->addspright(" ", $f3[1]).$align->addspleft($total_exento, $f3[2])."\n";
        $info_factura.= $align->addspleft(" ", $f4[0]).$align->addspright(" ", $f4[1]).$align->addspleft($total_value_gravado, $f4[2])."\n";
        $info_factura.= $align->addspleft(" ", $f5[0]).$align->addspright(" ", $f5[1]).$align->addspleft($retencion, $f4[2]);
        //impuestos a la gasolina
        $impuestoGas=getImpGass($id_factura);
        $n_imp = _num_rows($impuestoGas);
        $total_impuestos = 0;
        if ($n_imp>0) {
            for ($n=0;$n<$n_imp;$n++) {
                $row_imp=_fetch_array($impuestoGas);
                $imp_nombre = $row_imp['imp_nombre'];
                $imp_nombre = $row_imp['imp_nombre'];
                if ($row_imp['id_impuesto']==2 &&  $row_imp['id_dif']!='-1' && $row_imp['aplica_impuesto']==0) {
                    $tot_imp = 0;
                //$tot_imp = $row_imp['total_imp'];
                } else {
                    $tot_imp = $row_imp['total_imp'];
                }
                $total_imp= sprintf("%.4f", $tot_imp);
                //$info_factura.=$align->addspleft(" ", $f3[0]+$f3[1]+$f3[2]).$align->addspright($imp_nombre.": ", $f3[3]).$align->addspleft($total_imp, $f3[4])."\n";
                $total_impuestos += $tot_imp;
            }
        }
        //totales incluye impuestos
        $total_fin        = round($total_exento + $total_gravado  - $retencion + $total_impuestos, 2);
        $total_fin_format = sprintf("%.4f", $total_fin);

        if ($n_imp==0) {
            $num_imp = getCantImpGas();
            for ($j=0;$j<$num_imp;$j++) {
                $info_factura.= "\n";
            }
        }
        $info_factura.= $align->addspleft(" ", $f6[0]).$align->addspright(" ", $f6[1]).$align->addspleft($total_fin_format, $f4[2])."\n";
        $info_factura.="\n";
        // retornar valor generado en funcion
        return ($info_factura);
    }
}
function print_ccf($id_fact, $tipo_id, $nitcte="", $nrccte="", $nombreapecte="")
{
    $id_sucursal=$_SESSION['id_sucursal'];
    $align=new AlignMarginText();
    //traer  posiciones y columnas almacenados en bd
    $val=getMargins('CCF', $id_sucursal);
    // posiciones y columnas
    $marg_sup   = $val['marg_sup'];
    $h1         = explode(',', $val['h1']);
    $h2         = explode(',', $val['h2']);
    $h3         = explode(',', $val['h3']);
    $h4         = explode(',', $val['h4']);
    $h5         = explode(',', $val['h5']);
    $h6         = explode(',', $val['h6']);
    $h7         = explode(',', $val['h7']);
    $h8         = explode(',', $val['h8']);
    $f1         = explode(',', $val['f1']);
    $f2         = explode(',', $val['f2']);
    $f3         = explode(',', $val['f3']);
    $f4         = explode(',', $val['f4']);
    $f5         = explode(',', $val['f5']);
    $f6         = explode(',', $val['f6']);
    $f7         = explode(',', $val['f7']);
    $f8         = explode(',', $val['f8']);
    $cb_arr     = explode(',', $val['col_body_arr']);
    $marg_body  = $val['marg_body'];
    $marg_foot  = $val['marg_foot'];
    $lines_body = $val['lines_body'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $id_factura=$id_fact;
    $tipo_id=$tipo_id;
    //Empresa
    $sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $empresa=$row_empresa['descripcion'];
    $razonsocial=$row_empresa['nombre_comercial'];
    $giro_empresa=$row_empresa['giro'];
    $iva=$row_empresa['iva']/100;
    //inicio datos
    $info_factura=chr(13);
    //Obtener informacion de tabla Factura
    $result_fact=datos_factura($id_factura);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);
        $id_cliente=$row_fact['id_cliente'];
        $id_factura   = $row_fact['id_factura'];
        $id_usuario   = $row_fact['id_usuario'];
        $id_vendedor  = $row_fact['id_empleado'];
        $extra_nombre = $row_fact['extra_nombre'];
        $credito      = $row_fact['credito'];
        $fecha=$row_fact['fecha'];
        $fecha_fact=ed($fecha);
        $numero_doc=trim($row_fact['numero_doc']);
        $total=$row_fact['total'];
        $retencion=$row_fact['retencion'];
        $len_numero_doc=strlen($numero_doc)-4;
        $num_fact=substr($numero_doc, 0, $len_numero_doc);
        $tipo_fact=substr($numero_doc, $len_numero_doc, 4);

        $resPago  = getPagoXFactura($id_factura, "VAL");
        $nrowPago = _num_rows($resPago);
        $datos_extra = "";
        if ($nrowPago>0) {
            $rowPago   =  _fetch_array($resPago);
            $datos_extra = $rowPago['datos_extra'];
        }
        //Datos de empleado
        $sql_user="select * from usuario where id_usuario='$id_usuario'";
        $result_user= _query($sql_user);
        $row_user=_fetch_array($result_user);
        $nrow_user=_num_rows($result_user);
        $usuario=$row_user['usuario'];
        $nombreusuario=$row_user['nombre'];
        //$nombres=$row_user['apellido']." ".$row_user['nombre'];
        //Datos del Cliente
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto = "";
        $muni  = "";
        $vtacta="CONTADO";
        if ($credito==1) {
            $vtacta="CREDITO";
        }
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nit=$row1["nit"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombres=$row1['nombre'];
            $nombreapecte=$row1['nombre'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $codigocliente=$row1['codcliente'];
            $direccion=$row1["direccion"];
            //ver si el cliente retiene el 1% o el 10%
            $retiene=$row1["retiene"];
            $retiene10=$row1["retiene10"];

            if (isset($id_d) && isset($id_m)) {
                $deptoMuni=getDepartamento($id_d, $id_m);
                $row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];
                $depto_muni = $muni.$depto;
            }
            /*if (isset($id_d) && isset($id_m)) {
                $depto=getNombreDepartamentoId($id_d);
                $muni=getNombreMunicipioCod($id_d, $id_m);
            }*/
        }
        //$resultVendedor=vendedor($id_vendedor);
        $nombreVendedor=vendedor($id_vendedor);
        //$dir_txt=$direccion;
        $txt_dui="DUI: ".$dui;
        $total_final=0;
        $imprimir="";
        $info_factura="";
        //Datos encabezado factura
        $info_factura.= SPANISH;//chr(27).chr(54); //spanish latin print chars
    $info_factura.= LINEINCH8;//chr(27).chr(50); //6 lineas  por  pulgada
    for ($s=0;$s<$marg_sup;$s++) {
        $info_factura.="\n";
    }
        /*
        if($extra_nombre!=""){
          $nombreapecte=$nombreapecte." (".$extra_nombre.")";
        }*/
        //DIF
        $numero_dif=getIdDif($id_factura);
        if ($numero_dif!=" ") {
            $nombreapecte=$nombreapecte." DIF : ".$numero_dif."";
        }

        $dir_txt = $align->wordwrap1($direccion, $h2[1]);
        $lindir = array();
        $ldir=0;
        foreach ($dir_txt  as $dir_txt1) {
            $lindir[] = $align->addspright($dir_txt1, $h2[1]+1);
            $ldir=$ldir+1;
        }
        $info_factura.= $align->addspright(" ", $h1[2]).$align->addspright($fecha_fact, $h1[3]);
        $info_factura.= $align->addspright(" ", $h1[0]).$align->addspright($nombreapecte, $h1[1])."\n";

        $info_factura.= $align->addspright(" ", $h2[0]).$align->addspright($lindir[0], $h2[1]);
        $info_factura.= $align->addspright(" ", $h2[2]).$align->addspright($nrccte, $h2[3])."\n";//nrc
        $info_factura.= $align->addspright(" ", $h3[2]).$align->addspright($depto_muni, $h2[3])."\n";//municipio, departamento
        $info_factura.= $align->addspright(" ", $h3[2]).$align->addspright($girocte, $h2[3])."\n";//giro
        /*
        $info_factura.= $align->addspright($depto_muni, $h3[0]+$h3[1]+$h3[2]);
        $info_factura.= $align->addspright($girocte, $h3[3])."\n";//nr

        if ($ldir>1) {
            $info_factura.= $align->addspright(" ", $h3[0]).$align->addspright($lindir[1], $h3[1]);
            $info_factura.= $align->addspright(" ", $h3[2]).$align->addspright($girocte, $h3[3])."\n";//nr
        } else {
            $info_factura.= $align->addspright(" ", $h3[0]+$h3[1]+$h3[2]);
            $info_factura.= $align->addspright($girocte, $h3[3])."\n";//nr
        }

        $info_factura.= $align->addspleft("",$h3[0]).$align->addspright($dir_txt,$h3[1]);
            $info_factura.= $align->addspleft("",$h3[1]).$align->addspright($girocte,$h3[3])."\n";// giro
            */
        $info_factura.= $align->addspright("", $h5[0]+$h5[1]+$h5[2]);
        $info_factura.= $align->addspright($nitcte, $h5[3]);//nit
        $info_factura.= $align->addspright(" ", $h4[0]+$h4[1]+$h4[2]);
        $info_factura.= $align->addspright($vtacta, $h4[3])."\n";//  venta a cuenta: CREDITO o CONTADO

        for ($p=0;$p<$marg_body;$p++) {
            $info_factura.="\n";
        }
        //traer datos factura
        $result_fact_det=datos_fact_det($id_factura);
        $nrows_fact_det=_num_rows($result_fact_det);
        $total_final=0;
        $lineas=8;
        $cuantos=1;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;

        $info_factura.= LINEINCH8;//chr(27).chr(51)."2";
        for ($i=0;$i<$nrows_fact_det;$i++) {
            $row_fact_det=_fetch_array($result_fact_det);
            $id_producto =$row_fact_det['id_producto'];
            $unidad=$row_fact_det['unidad'];
            $descripcion =trim($row_fact_det['descripcion']);
            //descripcion presentacion
            $descpre =trim($row_fact_det['descpre']);
            $descpresenta =trim($row_fact_det['descp']);
            $exento=$row_fact_det['exento'];
            $id_factura_detalle =$row_fact_det['id_factura_detalle'];
            $id_prod_serv =$row_fact_det['id_prod_serv'];
            $cantidad =$row_fact_det['cantidad'];
            $precio_venta =$row_fact_det['precio_venta'];
            $subt =$row_fact_det['subtotal'];
            $id_empleado =$row_fact_det['id_empleado'];
            $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
            $cantidad=$cantidad/$unidad;
            $bonificacion  = 0;
            //linea a linea
            $descripcion1=substr($descripcion, 0, 30).", ".substr($descpresenta, 0, 10);
            $lendesc=$cb_arr[1]+10;
            $subt=$precio_venta*$cantidad;
            $precio_unit=sprintf("%.4f", $precio_venta);
            $subtotal=sprintf("%.4f", $subt);
            $total_final=$total_final+$subtotal;
            if ($exento==0) {
                $e_g="G";
                $precio_sin_iva0 =$row_fact_det['precio_venta']/(1+($iva));
                //$precio_sin_iva =round($row_fact_det['precio_venta']/(1+($iva)),4);
                $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                $subt_sin_iva=round($precio_sin_iva*$cantidad, 4);
                $subt_gravado=round($subt_sin_iva, 2);
                $total_gravado=$subt_sin_iva+$total_gravado;
            } else {
                $e_g="E";
                $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                $precio_sin_iva0 =$row_fact_det['precio_venta'];
                $subt_sin_iva=$precio_sin_iva0*$cantidad;
                $subt_exento=sprintf("%.4f", $subt_sin_iva);
                $total_exento=$subt_sin_iva+$total_exento;
            }
            $precio_sin_iva_print = round($precio_sin_iva, 4);
            $subt_sin_iva_print   = round($subt_sin_iva, 4);
            $psiva                = number_format($precio_sin_iva_print, 4);
            $ssiva                = number_format($subt_sin_iva_print, 4);
            $info_factura.=$align->addspright(" ", $cb_arr[0]); //margen inicial
            $info_factura.=$align->addspright($descripcion1, $lendesc); //descripcion
            $info_factura.=$align->addspright(" ", $cb_arr[2]);
            $info_factura.=$align->addspleft($cantidad, $cb_arr[3])." "; //cantidad
            $info_factura.=$align->addspright(" ", $cb_arr[4]);
            $info_factura.=$align->addspleft($psiva, $cb_arr[5]);  //precio
            $info_factura.=$align->addspleft(" ", $cb_arr[6]+$cb_arr[7]+$cb_arr[8]);  //vta. no sujetas
            $info_factura.=$align->addspleft($ssiva, $cb_arr[9])."\n";
            $cuantos=$cuantos+1;
        }

        $espacio =" ";
        $margen_izq1 =$align->leftmargin($espacio, 1);
        $total_gravado = round($total_gravado, 2);
        $calc_iva            = round($iva * $total_gravado, 4);
        $total_iva_format    = sprintf("%.4f", $calc_iva);
        $subtotal            = $total_gravado + $calc_iva;
        $total_value_exento  = sprintf("%.4f", $total_exento);
        $total_value_gravado = sprintf("%.4f", $total_gravado);
        $subtotal_print      = sprintf("%.4f", $subtotal);
        $cadena_salida_txt   = getTotalTexto($total);
        //DATOS EXTRA !!!
        if ($datos_extra!="" || !is_string($datos_extra)) {
            $data_ext = json_decode($datos_extra, true);
            foreach ($data_ext as $key => $data1) {
                $info_factura .= $align->addspleft(" ", 8).strtoupper($key. " : ". $data1). "\n";
                $cuantos++;
            }
        }
        //totales y n lineas
        $lineas_faltantes= $lines_body - $cuantos;
        if ($lineas_faltantes>0) {
            for ($j=0;$j<$lineas_faltantes;$j++) {
                $info_factura.= "\n";
            }
        }
        if ($marg_foot>0) {
            for ($k=0;$k<$marg_foot;$k++) {
                $info_factura.= "\n";
            }
        }
        $info_factura.=LINEINCH8;//chr(27).chr(48)."2"; chr(27).chr(50); //Select 6 lines per inch

        //generar 2 lineas del texto del total de la factura
        $total_txt0 = $align->wordwrap1($cadena_salida_txt, $f1[1]);
        $tmplinea = array();
        $ln=0;
        foreach ($total_txt0 as $total_txt1) {
            $tmplinea[] = $align->addspright($total_txt1, $f1[1]+1);
            $ln=$ln+1;
        }
        //imprime  total gravado
        $info_factura.=$align->addspright(" ", $f1[0]+$f1[1]+$f1[2]).$align->addspright("SUMAS :", $f1[3]).$align->addspleft($total_value_gravado, $f1[4])."\n";
        $info_factura.=$align->addspright(" ", $f2[0]).$align->addspright($tmplinea[0], $f2[1]).$align->addspleft(" ", $f2[2]).$align->addspright("IVA :", $f2[3]).$align->addspleft($total_iva_format, $f2[4])."\n"; //calculo IVA
        if ($ln<=1) {
            $info_factura.= $align->addspright(" ", $f3[0]+$f3[1]+$f3[2]);
            $info_factura.= $align->addspright("SUBTOTAL ", $f3[3]).$align->addspleft($subtotal_print, $f3[4])."\n";
        } else {
            $info_factura.= $align->addspright(" ", $f3[0]).$align->addspright($tmplinea[1], $f3[1]).$align->addspleft(" ", $f3[2]);
            $info_factura.= $align->addspright("SUBTOTAL ", $f3[3]).$align->addspleft($subtotal_print, $f3[4])."\n";
        }

        //impuestos a la gasolina
        $impuestoGas=getImpGass($id_factura);
        $n_imp = _num_rows($impuestoGas);
        $total_impuestos = 0;
        if ($n_imp>0) {
            for ($n=0;$n<$n_imp;$n++) {
                $row_imp=_fetch_array($impuestoGas);
                $imp_nombre = $row_imp['imp_nombre'];
                if ($row_imp['id_impuesto']==2 &&  $row_imp['id_dif']!='-1' && $row_imp['aplica_impuesto']==0) {
                    $tot_imp = 0;
                //$tot_imp = $row_imp['total_imp'];
                } else {
                    $tot_imp = $row_imp['total_imp'];
                }
                $total_imp= sprintf("%.4f", $tot_imp);
                $info_factura.=$align->addspleft(" ", $f3[0]+$f3[1]+$f3[2]).$align->addspright($imp_nombre.": ", $f3[3]).$align->addspleft($total_imp, $f3[4])."\n";
                $total_impuestos += $tot_imp;
            }
        }
        if ($n_imp==0) {
            $num_imp = getCantImpGas();
            for ($j=0;$j<$num_imp;$j++) {
                $info_factura.= "\n";
            }
        }
        $total_fin              = round($total_exento + $total_gravado+  $calc_iva + $total_impuestos - $retencion, 2);
        //$total_fin              = round($total_exento + $total_gravado+  $calc_iva - $retencion ,2);
        $total_fin_format       = sprintf("%.2f", $total_fin);
        $total_fin_guarda       = sprintf("%.2f", $total);
        $info_factura.=$align->addspleft(" ", $f8[0]).$align->addspright(" ", $f8[1]).$align->addspright(" ", $f8[2]).$align->addspright("TOTAL ", $f3[3]).$align->addspleft($total_fin_guarda, $f8[4])."\n"; //subtotal descuento bonifica + IVA
        $info_factura.="\n";
        // retornar valor generado en funcion
        return ($info_factura);
    }
}
function print_ncr($id_factura)
{
    $id_sucursal=$_SESSION['id_sucursal'];
    $align=new AlignMarginText();
    //traer  posiciones y columnas almacenados en bd
    $val=getMargins('NCR', $id_sucursal);
    // posiciones y columnas
    $marg_sup   = $val['marg_sup'];
    $h1         = explode(',', $val['h1']);
    $h2         = explode(',', $val['h2']);
    $h3         = explode(',', $val['h3']);
    $h4         = explode(',', $val['h4']);
    $h5         = explode(',', $val['h5']);
    $h6         = explode(',', $val['h6']);
    $h7         = explode(',', $val['h7']);
    $h8         = explode(',', $val['h8']);
    $f1         = explode(',', $val['f1']);
    $f2         = explode(',', $val['f2']);
    $f3         = explode(',', $val['f3']);
    $f4         = explode(',', $val['f4']);
    $f5         = explode(',', $val['f5']);
    $f6         = explode(',', $val['f6']);
    $f7         = explode(',', $val['f7']);
    $f8         = explode(',', $val['f8']);
    $cb_arr     = explode(',', $val['col_body_arr']);
    $marg_body = $val['marg_body'];
    $marg_foot  = $val['marg_foot'];
    $lines_body = $val['lines_body'];
    $id_sucursal=$_SESSION['id_sucursal'];
    //Empresa
    $sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $empresa=$row_empresa['descripcion'];
    $razonsocial=$row_empresa['razon_social'];
    $giro_empresa=$row_empresa['giro'];
    $iva=$row_empresa['iva']/100;
    //inicio datos
    $info_factura=chr(13);
    //Obtener informacion de tabla Factura
    $result_fact=datos_factura($id_factura);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);
        $id_cliente=$row_fact['id_cliente'];
        $id_factura = $row_fact['id_factura'];
        $id_dev = $row_fact['id_dev'];
        $id_usuario = $row_fact['id_usuario'];
        $id_vendedor = $row_fact['id_empleado'];
        $extra_nombre=$row_fact['extra_nombre'];
        $fecha=$row_fact['fecha'];
        $fecha_fact=ed($fecha);
        $numero_doc=trim($row_fact['numero_doc']);

        $total=$row_fact['total'];
        $retencion=$row_fact['retencion'];
        $len_numero_doc=strlen($numero_doc)-4;
        $num_fact=substr($numero_doc, 0, $len_numero_doc);
        $tipo_fact=substr($numero_doc, $len_numero_doc, 4);
        //datos devolucion nota credito

        $sql_dev="SELECT id_factura AS id_fact_emitido,monto,concepto,tipo as tipo_dev
    FROM devoluciones WHERE id_dev='$id_dev'";
        $result_dev= _query($sql_dev);
        $rowdev=_fetch_array($result_dev);
        $id_fact_emitido=$rowdev['id_fact_emitido'];
        $tipo_dev=$rowdev['tipo_dev'];
        $monto_dev=$rowdev['monto'];
        $concepto_dev=$rowdev['concepto'];
        $sql_fp="SELECT  fecha AS fecha_emision_prev,tipo_documento,num_fact_impresa
    FROM factura WHERE id_factura='$id_fact_emitido'";
        $result_fp= _query($sql_fp);
        $row_fp=_fetch_array($result_fp);
        $fecha_emision_prev=$row_fp['fecha_emision_prev'];
        $num_fact_impresa=$row_fp['num_fact_impresa'];
        $tipo_doc=trim($row_fp['tipo_documento']);
        //Datos del Cliente
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto="";
        $muni="";
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nitcte=$row1["nit"];
            $nrccte=$row1["nrc"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombreapecte=$row1['nombre'];
            $direccion=$row1['direccion'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $codigocliente=$row1['codcliente'];
            if (isset($id_d) && isset($id_m)) {
                $deptoMuni=getDepartamento($id_d, $id_m);
                $row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];
            }
        }

        $nombreVendedor=vendedor($id_vendedor);
        $dir_txt=$align->texto_espacios($direccion, 30);
        $total_final=0;
        $imprimir="";
        $info_factura="";
        //Datos encabezado factura
        $info_factura.= chr(27).chr(54); //spanish latin print chars
    $info_factura.= chr(27).chr(50); //6 lineas  por  pulgada
    for ($s=0;$s<$marg_sup;$s++) {
        $info_factura.="\n";
    }
        list($dd, $mm, $aa)=explode("-", $fecha_fact);
        $info_factura.= $align->addspleft(" ", $h1[0]).$align->addspcent($dd, $h1[1]).$align->addspcent($mm, $h1[2]).$align->addspcent($aa, $h1[2])."\n\n";
        $info_factura.=$align->addspright(" ", $h2[0]).$align->addspright($nombreapecte, $h2[1])."\n";
        $info_factura.=$align->addspleft(" ", $h3[0]).$align->addspright($dir_txt, $h3[1]);
        $info_factura.=$align->addspleft(" ", $h3[2]).$align->addspright($depto, $h3[3])."\n\n";
        $info_factura.=$align->addspleft(" ", $h4[0]).$align->addspright($nitcte, $h4[1]);//nit
    $info_factura.=$align->addspleft(" ", $h4[2]).$align->addspright($girocte, $h4[3]); //giro
    $info_factura.=$align->addspleft(" ", $h4[4]).$align->addspright($nrccte, $h4[5])."\n\n";//nrc
    $info_factura.=$align->addspleft(" ", $h6[0]).$align->addspright($fecha_emision_prev." CCF: ".$num_fact_impresa, $h6[1])."\n";//id_fact_emitido
        for ($p=0;$p<$marg_body;$p++) {
            $info_factura.="\n";
        }
        //traer datos factura
        $result_fact_det=datos_fact_det($id_factura);
        $nrows_fact_det=_num_rows($result_fact_det);
        $total_final=0;
        $lineas=6;
        $cuantos=1;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;
        $total_bonifica=0;
        $info_factura.= chr(27).chr(51)."2";
        $lin=0;
        if ($tipo_dev==0) {
            //$concepto_dev;
            //$descripcion1=substr($concepto_dev,0,$cb_arr[3]);
            //generar 2 lineas del texto del total de la factura
            $lendesc= strlen($concepto_dev);
            $descripcion1= explode("\n", $concepto_dev);

            $concepto_print="";
            $lineadesc = array();

            foreach ($descripcion1 as $desc1) {
                $lineadesc[]=$align->addspright($desc1, $cb_arr[3]);
                $lin=$lin+1;
                $cuantos+=1;
            }
            $info_factura.=$align->addspright(" ", $cb_arr[0]); //margen inicial
      $info_factura.=$align->addspleft("1", $cb_arr[1]); //cantidad
      $info_factura.=$align->addspleft(" ", $cb_arr[2]); //MARG
      $info_factura.=$align->addspright($lineadesc[0], $cb_arr[3]); //descripcion
      $info_factura.=$align->addspleft($monto_dev, $cb_arr[4]);  //precio
      $info_factura.=$align->addspleft("", $cb_arr[5]);  //vta. no sujetas
      $info_factura.=$align->addspleft("", $cb_arr[6]);  //vta. exenta
      $info_factura.=$align->addspleft($monto_dev, $cb_arr[7])."\n";
            for ($k=1;$k<$lin;$k++) {
                $info_factura.=$align->addspright(" ", $cb_arr[0]); //margen inicial
        $info_factura.=$align->addspleft(" ", $cb_arr[1]); //cantidad
        $info_factura.=$align->addspleft(" ", $cb_arr[2]); //MARG
        $info_factura.=$align->addspright($lineadesc[$k], $cb_arr[3])."\n"; //descripcion
            }
            $total_gravado=$monto_dev;
        } else {
            for ($i=0;$i<$nrows_fact_det;$i++) {
                $row_fact_det=_fetch_array($result_fact_det);
                $id_producto =$row_fact_det['id_producto'];
                $unidad=$row_fact_det['unidad'];
                $descripcion =trim($row_fact_det['descripcion']);
                //descripcion presentacion
                $descpre =trim($row_fact_det['descpre']);
                $descpresenta =trim($row_fact_det['descp']);
                $exento=$row_fact_det['exento'];
                $id_factura_detalle =$row_fact_det['id_factura_detalle'];
                $id_prod_serv =$row_fact_det['id_prod_serv'];
                $cantidad =$row_fact_det['cantidad'];
                $precio_venta =$row_fact_det['precio_venta'];
                $subt =$row_fact_det['subtotal'];
                $id_empleado =$row_fact_det['id_empleado'];
                $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
                $cantidad=$cantidad/$unidad;
                $bonificacion  =$row_fact_det['bonificacion'];
                $cant_mas_boni=$cantidad;
                if ($bonificacion>0) {
                    $bonificacion  =  $bonificacion / $unidad;
                    $cant_mas_boni=$cantidad." / ".$bonificacion;
                }
                //linea a linea
                $descripcion1=substr($descripcion, 0, $cb_arr[3]).", ".substr($descpresenta, 0, 10)." ".substr($descpre, 0, 10);
                $lendesc=$cb_arr[3]+10+10;
                $subt=$precio_venta*$cantidad;
                $subt_bonifica=$precio_venta*$bonificacion;
                $precio_unit=sprintf("%.4f", $precio_venta);
                $subtotal=sprintf("%.4f", $subt);
                $subtotal_bonifica=sprintf("%.4f", $subt_bonifica);
                $total_final=$total_final+$subtotal;
                if ($exento==0) {
                    $e_g="G";
                    $precio_sin_iva0 =$row_fact_det['precio_venta']/(1+($iva));
                    $precio_sin_iva =round($row_fact_det['precio_venta']/(1+($iva)), 4);
                    $subt_sin_iva=round($precio_sin_iva0*$cantidad, 4);
                    $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion, 4);
                    $subt_gravado=round($subt_sin_iva, 4);
                    $total_gravado=$subt_sin_iva+$total_gravado;
                } else {
                    $e_g="E";
                    $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                    $precio_sin_iva0 =$row_fact_det['precio_venta'];
                    $subt_sin_iva=$precio_sin_iva0*$cantidad;
                    $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion, 4);
                    $subt_exento=sprintf("%.4f", $subt_sin_iva);
                    $total_exento=$subt_sin_iva+$total_exento;
                }
                $precio_sin_iva_print=round($precio_sin_iva, 4);
                $subt_sin_iva_print=round($subt_sin_iva, 4);
                $psiva=number_format($precio_sin_iva_print, 4);
                $ssiva=number_format($subt_sin_iva_print, 4);

                $info_factura.=$align->addspright(" ", $cb_arr[0]); //margen inicial
        $info_factura.=$align->addspleft($cant_mas_boni, $cb_arr[1]); //cantidad
        $info_factura.=$align->addspleft(" ", $cb_arr[2]); //MARG
        $info_factura.=$align->addspright($descripcion1, $lendesc); //descripcion

        $info_factura.=$align->addspleft($psiva, $cb_arr[4]);  //precio
        $info_factura.=$align->addspleft("", $cb_arr[5]);  //vta. no sujetas
        $info_factura.=$align->addspleft("", $cb_arr[6]);  //vta. exenta
        $info_factura.=$align->addspleft($ssiva, $cb_arr[7])."\n";
                //calulo totales bonificacion
                $total_bonifica+=$subt_boni_sin_iva;
                $cuantos=$cuantos+1;
            }
        }
        $total_value_gravado=sprintf("%.4f", $total_gravado);
        //total de bonificaciones
        $total_bonifica=0;
        $total_bonificacion=sprintf("%.4f", $total_bonifica);
        //restar total gravado - bonificacion  para luego scar IVA
        $subtotal_grav_sinboni= $total_gravado - $total_bonifica ;

        if ($tipo_doc=="CCF") {
            $calc_iva = round(($subtotal_grav_sinboni* $iva), 4);
        } else {
            $calc_iva = 0.0;
        }
        $total_iva_format=sprintf("%.4f", $calc_iva);
        $sub_total=sprintf("%.4f", round($subtotal_grav_sinboni+$calc_iva, 4));
        $total_fin=round($sub_total - $retencion, 4);
        $total_fin_format=sprintf("%.4f", $total_fin);
        $total_final_format=sprintf("%.4f", $total_final);
        $total_value_exento=sprintf("%.4f", $total_exento);
        $subtotal_exento=round($total_exento, 4);
        $total_final_todos=round($sub_total + $subtotal_exento  - $retencion, 4);
        $total_final_print=sprintf("%.4f", $total_final_todos);
        $cadena_salida_txt= getTotalTexto($total_final_print);

        $total_value_fin=sprintf("%.4f", $total_fin);
        //totales y n lineas
        $lineas_faltantes=$lines_body - $cuantos+1;
        if ($lineas_faltantes>0) {
            for ($j=0;$j<$lineas_faltantes;$j++) {
                $info_factura.= "\n";
            }
        }
        $info_factura.= chr(27).chr(50);  //espacio entre lineas 6 x pulgada
        if ($marg_foot>0) {
            for ($j=0;$j<$marg_foot;$j++) {
                $info_factura.= "\n";
            }
        }
        //generar 2 lineas del texto del total de la factura
        $total_txt0 =$align->wordwrap1($cadena_salida_txt, $f1[1], 2);
        $concepto_print="";
        $tmplinea = array();
        $ln=0;
        foreach ($total_txt0 as $total_txt1) {
            $tmplinea[]=$align->addspright($total_txt1, $f1[1]);
            $ln=$ln+1;
        }

        //imprime  total gravado y total texto linea 1
        $info_factura.=$align->addspleft("", $f1[0]).$align->addspright(" ", $f1[1]).$align->addspright("", $f1[2]).$align->addspleft($total_value_gravado, $f1[3])."\n\n";
        // imprime total en texto linea 1
        $info_factura.=$align->addspleft("", $f2[0]).$align->addspright($tmplinea[0], $f2[1]).$align->addspright("", $f2[2]).$align->addspleft($total_iva_format, $f2[3])."\n\n";
        if ($ln>1) {
            $info_factura.=$align->addspleft("", $f3[0]).$align->addspright($tmplinea[0], $f3[1]).$align->addspright("", $f3[2]).$align->addspleft($sub_total, $f3[3])."\n"; //calculo IVA
        } else {
            $info_factura.=$align->addspleft("", $f3[0]).$align->addspright("", $f3[1]).$align->addspright("", $f2[2]).$align->addspleft($sub_total, $f3[3])."\n";
        }
        if ($retencion>0) {
            $info_factura.=$align->addspleft("", $f4[0]).$align->addspright("", $f4[1]).$align->addspright("", $f4[2]).$align->addspleft($retencion, $f4[3])."\n";
        } //ret
        else {
            $info_factura.="\n";
        }

        for ($j=0;$j<4;$j++) {
            $info_factura.= "\n";
        }
        $info_factura.=$align->addspleft("", $f7[0]).$align->addspright("", $f7[1]).$align->addspright("", $f7[2]).$align->addspleft($total_final_print, $f7[3])."\n"; //total final
        // retornar valor generado en funcion
        return ($info_factura);
    }
}
function print_envio($id_factura, $tipo_id, $nombreapecte="", $direccion="")
{
    $id_sucursal=$_SESSION['id_sucursal'];
    $align=new AlignMarginText();
    //traer  posiciones y columnas almacenados en bd
    $val=getMargins('ENV', $id_sucursal);
    $marg_sup   = $val['marg_sup'];
    $h1         = explode(',', $val['h1']);
    $h2         = explode(',', $val['h2']);
    $h3         = explode(',', $val['h3']);
    $h4         = explode(',', $val['h4']);
    $h5         = explode(',', $val['h5']);
    $h6         = explode(',', $val['h6']);
    $h7         = explode(',', $val['h7']);
    $h8         = explode(',', $val['h8']);
    $f1         = explode(',', $val['f1']);
    $f2         = explode(',', $val['f2']);
    $f3         = explode(',', $val['f3']);
    $f4         = explode(',', $val['f4']);
    $f5         = explode(',', $val['f5']);
    $f6         = explode(',', $val['f6']);
    $f7         = explode(',', $val['f7']);
    $f8         = explode(',', $val['f8']);
    $cb_arr     = explode(',', $val['col_body_arr']);
    $marg_body  = $val['marg_body'];
    $marg_foot  = $val['marg_foot'];
    $lines_body = $val['lines_body'];
    // FIN posiciones y columnas
    $id_sucursal=$_SESSION['id_sucursal'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    //Empresa
    $sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $iva=$row_empresa['iva']/100;
    //inicio datos
    //Obtener informacion de tabla Factura
    $result_fact=datos_factura($id_factura);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);
        $id_cliente=$row_fact['id_cliente'];
        $id_factura = $row_fact['id_factura'];
        $id_usuario=$row_fact['id_usuario'];
        $id_vendedor = $row_fact['id_empleado'];
        $extra_nombre=$row_fact['extra_nombre'];
        $credito= $row_fact['credito'];
        $fecha=$row_fact['fecha'];
        $fecha_fact=ed($fecha);
        $numero_doc=trim($row_fact['numero_doc']);
        $total=$row_fact['total'];
        $retencion= $row_fact['retencion'];
        $len_numero_doc=strlen($numero_doc)-4;
        $num_fact=substr($numero_doc, 0, $len_numero_doc);
        $tipo_fact=substr($numero_doc, $len_numero_doc, 4);
        $datos_extra =$row_fact['datos_extra'];
        //Datos del Cliente
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto="";
        $muni="";
        $vtacta = "CONTADO";
        if ($credito==1) {
            $vtacta = "CREDITO";
        }
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nitcte=$row1["nit"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombres=$row1['nombre'];
            $nombreapecte=$row1['nombre'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $direccion=$row1["direccion"];
            $codigocliente=$row1['codcliente'];
            if (isset($id_d) && isset($id_m)) {
                $deptoMuni=getDepartamento($id_d, $id_m);
                $row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];
            }
        }
        if ($nitcte=="") {
            $nitcte=$dui;
        }
        $txt_dui="DUI: ".$dui;
        $nombreVendedor=vendedor($id_vendedor);
        $dir_txt=substr($direccion, 0, 75);
        $total_final=0;
        $imprimir="";
        $info_factura="";
        $espaciado6 = LINEINCH6;
        $info_factura ="";
        $info_factura .= chr(27).chr(54); //spanish latin print chars
    $info_factura .=  $espaciado6; //6 lineas  por  pulgada
    //$hstring .= chr(27).chr(50); //6 lineas  por  pulgada
    for ($s=0;$s<$marg_sup;$s++) {
        $info_factura.="\n";
    }
        list($dd, $mm, $aa)=explode("-", $fecha_fact);
        $info_factura.= $align->addspleft("", $h1[0]).$align->addspcent($dd, $h1[1]).$align->addspcent($mm, $h1[2]).$align->addspcent($aa, $h1[2])."\n\n";
        //Datos encabezado factura
        if ($extra_nombre!="") {
            $nombreapecte=$nombreapecte." (".$extra_nombre.")";
        }
        $info_factura.=$align->addspright("", $h2[0]).$align->addspright($nombreapecte, $h2[1])."\n\n";
        $info_factura.=$align->addspleft("", $h3[0]).$align->addspright($dir_txt, $h3[1]).$align->addspright($txt_dui, $h3[2])."\n\n";
        $info_factura.=$align->addspleft("", $h4[0]).$align->addspright($nitcte, $h4[1]).$align->addspleft("", $h4[2]).$align->addspright($vtacta, $h4[3])."\n\n";//nit o dui
    $info_factura.=$align->addspleft("", $h5[0]).$align->addspright($nombreVendedor, $h5[1]).$align->addspleft("", $h5[2]).$align->addspright($codigocliente, $h5[3])."\n";// condiciones operacion
        for ($p=0;$p<$marg_body;$p++) {
            $info_factura.="\n";
        }
        //traer datos factura
        $result_fact_det=datos_fact_det($id_factura);
        $nrows_fact_det=_num_rows($result_fact_det);
        $total_final=0;
        $lineas=6;
        $cuantos=1;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;
        $total_bonifica=0;
        //$info_factura.= chr(27).chr(48); //Select 8 lines per inch
    $info_factura.= chr(27).chr(51)."2"; //6 lineas  por  pulgada
        for ($i=0;$i<$nrows_fact_det;$i++) {
            $row_fact_det=_fetch_array($result_fact_det);
            $id_producto =$row_fact_det['id_producto'];
            $unidad=$row_fact_det['unidad'];
            $descripcion =trim($row_fact_det['descripcion']);
            //descripcion presentacion
            $descpre =trim($row_fact_det['descpre']);
            $descpresenta =trim($row_fact_det['descp']);
            $exento=$row_fact_det['exento'];
            $id_factura_detalle =$row_fact_det['id_factura_detalle'];
            $id_prod_serv =$row_fact_det['id_prod_serv'];
            $cantidad =$row_fact_det['cantidad'];
            $precio_venta =$row_fact_det['precio_venta'];
            $subt =$row_fact_det['subtotal'];
            $id_empleado =$row_fact_det['id_empleado'];
            $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
            $cantidad=$cantidad/$unidad;
            $bonificacion  =$row_fact_det['bonificacion'];
            $datos_extra =$row_fact['datos_extra'];
            if ($bonificacion>0) {
                $bonificacion  =  $bonificacion / $unidad;
            }
            //linea a linea
            $descripcion1=substr($descripcion, 0, $cb_arr[3]).", ".substr($descpresenta, 0, 10)." ".substr($descpre, 0, 10);
            $lendesc=$cb_arr[3]+10+10;
            $subt=$precio_venta*$cantidad;
            $subt_bonifica=$precio_venta*$bonificacion;
            $precio_unit=sprintf("%.4f", $precio_venta);
            $subtotal=sprintf("%.4f", $subt);
            $subtotal_bonifica=sprintf("%.4f", $subt_bonifica);
            $total_final=$total_final+$subtotal;
            $cantprint=$cantidad-$bonificacion;
            if ($cantprint<0) {
                $cantprint=0;
            }
            if ($exento==0) {
                $e_g="G";
                $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                $precio_sin_iva0 =$row_fact_det['precio_venta'];
                $subt_sin_iva=round($precio_sin_iva0*$cantidad, 4);
                $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion, 4);
                $subt_gravado=round($subt_sin_iva, 4);
                $total_gravado=$subt_sin_iva+$total_gravado;
            } else {
                $e_g="E";
                $precio_sin_iva =round($row_fact_det['precio_venta'], 4);
                $precio_sin_iva0 =$row_fact_det['precio_venta'];
                $subt_sin_iva=$precio_sin_iva0*$cantidad;
                $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion, 4);
                $subt_exento=sprintf("%.4f", $subt_sin_iva);
                $total_exento=$subt_sin_iva+$total_exento;
                $total_gravado=$subt_sin_iva+$total_gravado;
            }
            $precio_sin_iva_print=round($precio_sin_iva, 4);
            $subt_sin_iva_print=round($subt_sin_iva, 4);
            $psiva=number_format($precio_sin_iva_print, 4);
            $ssiva=number_format($subt_sin_iva_print, 4);
            $info_factura.=$align->addspright(" ", $cb_arr[0]); //margen inicial
      $info_factura.=$align->addspleft($cantprint, $cb_arr[1])." "; //cantidad
      $info_factura.=$align->addspright(" ", $cb_arr[2]);
            $info_factura.=$align->addspright($descripcion1, $lendesc); //descripcion
            if ($bonificacion>0) {
                $info_factura.=$align->addspleft($bonificacion, $cb_arr[4]); //bonificacion
            } else {
                $info_factura.=$align->addspleft(" ", $cb_arr[4]); //bonificacion
            }
            $info_factura.=$align->addspleft($psiva, $cb_arr[5]);  //precio
      $info_factura.=$align->addspleft("", $cb_arr[6]);  //vta. no sujetas
      $info_factura.=$align->addspleft("", $cb_arr[7]);  //vta. exenta
      $info_factura.=$align->addspleft($ssiva, $cb_arr[8])."\n";
            //calulo totales bonificacion
            $total_bonifica+= $subt_boni_sin_iva;
            $cuantos=$cuantos+1;
        }
        //restar total gravado - bonificacion  para luego scar IVA
        $subtotal_grav_sinboni  = $total_gravado - $total_bonifica ;
        $calc_iva               = round($iva * $subtotal_grav_sinboni, 4);
        $total_iva_format       = sprintf("%.4f", $calc_iva);
        $total_final_format     = sprintf("%.4f", $total_final);
        $subt_boni_iva          = round($subtotal_grav_sinboni, 4);

        $subt_boni_ivar         = sprintf("%.2f", $subt_boni_iva);

        $cadena_salida_txt= getTotalTexto(sprintf("%.2f", $total));
        $total_value=sprintf("%.4f", $total);
        $total_fin=round($subtotal_grav_sinboni - $retencion, 4);
        $total_fin_format=sprintf("%.4f", $total_fin);

        //$total_fin=$total_exento+$total_gravado;
        $total_value_exento=sprintf("%.4f", $total_exento);
        $total_value_gravado=sprintf("%.4f", $total_gravado);
        $total_value_fin=sprintf("%.4f", $total_fin);
        //total de bonificaciones
        $total_bonificacion=sprintf("%.4f", $total_bonifica);
        //DATOS EXTRA !!!
        if ($datos_extra!="" || !is_string($datos_extra)) {
            $data_ext = json_decode($datos_extra, true);
            foreach ($data_ext as $key => $data1) {
                $info_factura .= $key. " : ". $data1. "\n";
                $info_factura .= $align->addspleft(" ", 8).strtoupper($key. " : ". $data1). "\n";
            }
        }
        //totales y n lineas
        $lineas_faltantes= $lines_body - $cuantos;
        if ($lineas_faltantes>0) {
            for ($j=0;$j<$lineas_faltantes;$j++) {
                $info_factura.= "\n";
            }
        }
        if ($marg_foot>0) {
            for ($k=0;$k<$marg_foot;$k++) {
                $info_factura.= "\n";
            }
        }
        $info_factura.= chr(27).chr(50); //Select 6 lines per inch
        $info_factura.= "\n";
        //generar 2 lineas del texto del total de la factura
        $total_txt0 =$align->wordwrap1($cadena_salida_txt, $f1[1], 2);
        $concepto_print="";
        $tmplinea = array();
        $ln=0;
        foreach ($total_txt0 as $total_txt1) {
            $tmplinea[]=$align->addspright($total_txt1, $f1[1]);
            $ln=$ln+1;
        }
        $subtotal_gravado=round($total_gravado+$calc_iva, 4);
        $subtotal_exento=$total_exento;
        $total_final_todos=round($subtotal_exento+$subtotal_gravado, 4);
        //imprime  total gravado y total texto linea 1
        $info_factura.=$align->addspleft(" ", $f1[0]).$align->addspright(" ", $f1[1]).$align->addspright("", $f1[2]).$align->addspleft($total_value_gravado, $f1[3])."\n";
        $info_factura.=$align->addspleft(" ", $f2[0]).$align->addspright($tmplinea[0], $f2[1])."\n"; //.$align->addspright("BONIF.",$f2[2]).$align->addspleft($total_bonificacion,$f2[3])."\n";
        // imprime total en texto linea 2  y total de bonificacion
        if ($retencion==0) {
            $retencion=" ";
        }
        if ($ln>1) {
            $info_factura.=$align->addspleft(" ", $f3[0]).$align->addspright($tmplinea[1], $f3[1]).$align->addspright(" ", $f3[2]).$align->addspleft($retencion, $f3[3])."\n\n";
        } else {
            // imprime solo Subtotal
            $info_factura.=$align->addspleft(" ", $f3[0]).$align->addspright(" ", $f3[1]).$align->addspright(" ", $f3[2]).$align->addspleft($retencion, $f3[3])."\n\n";
        }
        //$info_factura.="\n";
    $info_factura.=$align->addspleft(" ", $f4[0]).$align->addspright(" ", $f4[1]).$align->addspright(" ", $f4[2]).$align->addspleft($total_fin_format, $f4[3])."\n"; //subtotal descuento bonifica + IVA
    for ($j=0;$j<5;$j++) {
        $info_factura.= "\n";
    }
        $info_factura.=$align->addspleft(" ", $f8[0]).$align->addspright(" ", $f8[1]).$align->addspright(" ", $f8[2]).$align->addspleft($total_fin_format, $f8[3])."\n"; //subtotal descuento bonifica + IVA
        $info_factura.="\n";
        // retornar valor generado en funcion
        return ($info_factura);
    }
}
function print_vale($id_movimiento)
{
    $align=new AlignMarginText();
    $id_sucursal=$_SESSION['id_sucursal'];
    //sucursal
    $sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
    $array_sucursal=_fetch_array($sql_sucursal);
    $nombre_sucursal1=$array_sucursal['descripcion'];
    $empresa=empresa();
    //consulta
    $sql="SELECT  e.id_empleado, e.nombre,
	mc.concepto, mc.valor,mc.fecha,mc.hora,mc.entrada,mc.salida,mc.id_sucursal
	FROM mov_caja AS mc
	JOIN usuario AS e ON(e.id_usuario=mc.id_empleado)
	WHERE  mc.id_movimiento='$id_movimiento'";
    $result=_query($sql);
    $nrow = _num_rows($result);
    $row = _fetch_array($result);
    $id_empleado = $row["id_empleado"];
    $concepto = $row["concepto"];
    $nombre = $row["nombre"];
    $hora= $row["hora"];
    $fecha= $row["fecha"];
    $valor= $row["valor"];
    $entrada= $row["entrada"];
    if ($entrada==1) {
        $tipo="INGRESO";
    } else {
        $tipo="EGRESO";
    }
    $line1=str_repeat("_", 30)."\n";
    $valor= sprintf('%.4f', $valor);
    //Datos
    $esp_init = $align->addspleft(" ", 1);

    $info_factura="";
    $info_factura.=$esp_init.$empresa."\n";
    //$info_factura.=$esp_init."SUCURSAL ".$nombre_sucursal1."\n";
    $info_factura.=$esp_init."VALE # : ".$id_movimiento."\n";
    $info_factura.=$esp_init."FECHA: ".ED($fecha)."\nHORA:".hora($hora)."\n";
    $info_factura.=$esp_init."EMPLEADO: ".$nombre."\n";
    $info_factura.=$esp_init.$tipo."\n";
    $info_factura.=$esp_init."CONCEPTO: ".$concepto."\n";
    $info_factura.=$esp_init."VALOR $: ".$valor."\n";
    $info_factura.="\n";
    $info_factura.="F. ".$line1;
    $info_factura.="\n";
    return ($info_factura);
}
function print_corte($id_corte)
{
    include_once "_core.php";
    $align=new AlignMarginText();
    //EMPRESA
    $id_sucursal=$_SESSION['id_sucursal'];

    $sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $empresa=$row_empresa['descripcion'];
    //$razonsocial=$row_empresa['razon_social'];
    $giro=$row_empresa['giro'];
    $nit=$row_empresa['nit'];
    $nrc=$row_empresa['nrc'];
    // corte
    $qC="SELECT  * FROM controlcaja WHERE  id_corte=$id_corte";
    $rC= _query($qC);
    $rowC=_fetch_array($rC);
    $id_apertura=$rowC['id_apertura'];
    //apertura
    $qAp="SELECT  * FROM apertura_caja WHERE  id_apertura=$id_apertura";
    $rAp= _query($qAp);
    $rowAp=_fetch_array($rAp);
    // $id_apertura=$rowC['id_apertura'];
    $caja = $rowAp["caja"];
    //sucursal
    $sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
    $array_sucursal=_fetch_array($sql_sucursal);
    $nombre_sucursal=$array_sucursal['descripcion'];
    //consulta
    $sql_t=_fetch_array(_query("SELECT controlcaja.id_empleado FROM controlcaja WHERE controlcaja.id_corte=$id_corte"));
    $id_c=$sql_t['id_empleado'];
    $sql="";
    if ($id_c<0) {
        $sql="SELECT c.caja, c.turno, c.cajero, c.tinicio, c.tfinal, c.totalnot, c.texento, c.tgravado,
		c.totalt, c.finicio, c.ffinal, c.totalnof, c.fexento, c.fgravado, c.totalf, c.cfinicio, c.cffinal, c.totalnocf,
		c.cfexento, c.cfgravado, c.totalcf, c.rinicio, c.rfinal, c.totalnor, c.rexento, c.rgravado, c.totalr,
		c.cashinicial, c.vtacontado, c.vtaefectivo, c.vtatcredito, c.totalgral, c.subtotal, c.cashfinal, c.diferencia,
		c.totalnodev, c.totalnoanu, c.depositos, c.vales, c.tarjetas, c.depositon, c.valen, c.tarjetan, c.ingresos,
		c.tcredito, c.ncortex, c.ncortez, c.ncortezm, c.cerrado, c.id_empleado, c.id_sucursal, c.id_apertura,
		c.fecha_corte, c.hora_corte, c.tipo_corte,e.nombre, c.monto_ch, c.tiket, c.turno, c.retencion
		FROM controlcaja AS c
		JOIN usuario AS e ON(e.id_usuario=c.id_empleado)
		WHERE c.id_corte='$id_corte'";
    } else {
        # code...
        $sql="SELECT c.caja, c.turno, c.cajero, c.tinicio, c.tfinal, c.totalnot, c.texento, c.tgravado,
		c.totalt, c.finicio, c.ffinal, c.totalnof, c.fexento, c.fgravado, c.totalf, c.cfinicio, c.cffinal, c.totalnocf,
		c.cfexento, c.cfgravado, c.totalcf, c.rinicio, c.rfinal, c.totalnor, c.rexento, c.rgravado, c.totalr,
		c.cashinicial, c.vtacontado, c.vtaefectivo, c.vtatcredito, c.totalgral, c.subtotal, c.cashfinal, c.diferencia,
		c.totalnodev, c.totalnoanu, c.depositos, c.vales, c.tarjetas, c.depositon, c.valen, c.tarjetan, c.ingresos,
		c.tcredito, c.ncortex, c.ncortez, c.ncortezm, c.cerrado, c.id_empleado, c.id_sucursal, c.id_apertura,
		c.fecha_corte, c.hora_corte, c.tipo_corte,em.nombre, c.monto_ch, c.tiket, c.turno, c.retencion
		FROM controlcaja AS c
		JOIN usuario AS e ON(e.id_usuario=c.id_empleado)
		LEFT JOIN empleado as em on(e.id_empleado=em.id_empleado)
		WHERE c.id_corte='$id_corte'";
    }

    $result=_query($sql);
    $nrow = _num_rows($result);
    $row = _fetch_array($result);
    $id_empleado = $row["id_empleado"];
    $nombre_emp = $row["nombre"];
    $result_emp= datos_empleado($id_c, $id_c);
    list($al, $nombre_emp)=explode('|', $result_emp);
    $hora= $row["hora_corte"];
    $fecha= ED($row["fecha_corte"]);
    $tipo= $row["tipo_corte"];
    $tinicio= $row["tinicio"];
    $tfinal= $row["tfinal"];
    $finicio= $row["finicio"];
    $ffinal= $row["ffinal"];
    $cfinicio= $row["cfinicio"];
    $cffinal= $row["cffinal"];
    $cashini= $row["cashinicial"];
    $vtaefectivo= $row["vtaefectivo"];
    $ingresos= $row["ingresos"];
    $vales= $row["vales"];
    $totalgral= $row["totalgral"];
    $cashfinal= $row["cashfinal"];
    $diferencia= $row["diferencia"];
    $totalnot= $row["totalnot"];
    $totalnof= $row["totalnof"];
    $totalnocf= $row["totalnocf"];
    $monto_ch = $row["monto_ch"];
    //$caja = $row["caja"];
    $tike = $row['tiket'];
    $turno = $row["turno"];
    $retencion= $row['retencion'];

    $sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
    $dats_caja = _fetch_array($sql_caja);
    $fehca = ED($dats_caja["fecha"]);
    $resolucion = $dats_caja["resolucion"];
    $serie = $dats_caja["serie"];
    $desde = $dats_caja["desde"];
    $hasta = $dats_caja["hasta"];

    $texento= sprintf('%.4f', $row["texento"]);
    $tgravado= sprintf('%.4f', $row["tgravado"]);
    $totalt=  sprintf('%.4f', $row["totalt"]);
    $fexento= sprintf('%.4f', $row["fexento"]);
    $fgravado=sprintf('%.4f', $row["fgravado"]);
    $totalf= sprintf('%.4f', $row["totalf"]);
    $cfexento= sprintf('%.4f', $row["cfexento"]);
    $cfgravado=sprintf('%.4f', $row["cfgravado"]);
    $totalcf=sprintf('%.4f', $row["totalcf"]);
    $vtatotales=$totalt+$totalf+$totalcf;
    $vtatotales_print=sprintf('%.4f', $vtatotales);
    $vtaefectivo= sprintf('%.4f', $vtaefectivo);
    $cashini= sprintf('%.4f', $cashini);
    $ingresos= sprintf('%.4f', $ingresos);
    $monto_ch = sprintf('%.4f', $monto_ch);
    $vales=sprintf('%.4f', $vales);
    $cashfinal= sprintf('%.4f', $cashfinal);
    $diferencia= sprintf('%.4f', $diferencia);
    $esp_init=$align->addspleft(" ", 1);
    $esp_init0=$align->addspleft(" ", 1);
    $esp_init1=$align->addspleft(" ", 12);
    $esp_init2=$align->addspleft(" ", 20);
    $line1=str_repeat("_", 46)."\n";
    $info_factura="";
    $tinicio= zfill($tinicio, 7);
    $tfinal= zfill($tfinal, 7);
    $empresa=empresa();
    $row_suc=datos_sucursal($id_sucursal);
    $nitsuc= $row_suc['nit'];
    $nrcsuc= $row_suc['nrc'];
    $cod_giro = $row_suc['cod_act_eco'];
    $girosuc= get_giro($cod_giro);
    $razonsuc= $row_suc['nombre_comercial'];
    if ($tipo=="C") {
        $desc_tipo='CORTE DE CAJA';
    } else {
        $desc_tipo=$tipo;
    }

    $hstring= $esp_init0. $empresa."\n";
    $hstring.= $esp_init0. $razonsuc."\n";
    //$info_factura.=$esp_init0.$razonsocial."\n";
    //dividir texto giro
    $desgiro = $align->wordwrap1("GIRO: ".$girosuc, 40);
    $descgiro="";
    foreach ($desgiro as $lin) {
        $descgiro .= trim($lin). "\n";
    }
    $hstring.=$esp_init0.$descgiro;
    $hstring.=$esp_init0."CORTE TIPO: ".$desc_tipo."\n";
    //$hstring.=$esp_init0."CORTE DE CAJA: ".$id_corte."\n";
    //$hstring.=$line1;
    $hstring.=$esp_init."FECHA: ".$fecha."  HORA:".hora($hora)."\n";
    $hstring.=$esp_init."EMPLEADO: ".$nombre_emp."\n";
    $hstring.=$esp_init."CAJA #: ".$caja. "  TURNO: ".$turno."\n";

    $xdatos["encabezado"] = $hstring;

    $info_factura="$line1";
    if ($tipo=="C") {
        $subtotal=$cashini+$vtatotales+$ingresos+$monto_ch;
        $totalcaja=$subtotal-$vales;
        $subtotal=sprintf('%.4f', $subtotal);
        $totalcaja=sprintf('%.4f', $totalcaja);
        //$info_factura.=$esp_init1."DESDE:      HASTA:"."\n";
        $l0=12;
        $l1=19;
        $tini= $align->addspleft($tinicio, $l0);
        $tfin =$align->addspleft($tfinal, $l0);

        $info_factura.=$esp_init0."TIQUETES:     ".$tini."  ".$tfin."\n";
        $fini=$align->addspleft($finicio, $l0);
        $ffin=$align->addspleft($ffinal, $l0);
        $info_factura.=$esp_init0."FACTURAS:     ".$fini."  ".$ffin."\n";

        $cfinicio=$align->addspleft($cfinicio, $l0);
        $cffinal=$align->addspleft($cffinal, $l0);
        $info_factura.=$esp_init0."FISCALES:     ".$cfinicio."  ".$cffinal."\n";
        $info_factura.="\n";

        $cashini=$align->addspleft($cashini, $l1);
        $info_factura.=" SALDO INICIAL $:      ".$cashini."\n";
        $monto_ch=$align->addspleft($monto_ch, $l1);
        //$info_factura.=" SALDO CAJA CHICA $:   ".$monto_ch."\n";
        $ingresos=$align->addspleft($ingresos, $l1);
        $info_factura.=" (+)INGRESOS $:        ".$ingresos."\n";
        $vtatotales_print=$align->addspleft($vtatotales_print, $l1);
        $info_factura.=" (+) VENTA $:          ".$vtatotales_print."\n";
        $info_factura.=$line1;
        $subtotal=$align->addspleft($subtotal, $l1);
        $info_factura.=" SUBTOTAL $:           ".$subtotal."\n";
        $vales=$align->addspleft($vales, $l1);
        $info_factura.=" (-) VALES $:          ".$vales."\n";
        $info_factura.=$line1;
        $totalcaja=$align->addspleft($totalcaja, $l1);
        $info_factura.=" TOTAL CAJA $:         ".$totalcaja."\n";
        $info_factura.="\n";
        $reten=$align->addspleft(sprintf('%.4f', $retencion), $l1);
        $info_factura.=" (-) RETENCION $:      ".$reten."\n";
        $sql_dev="SELECT sum(t_devolucion) as total FROM devoluciones_corte WHERE id_corte='$id_corte' AND tipo!='CCF'";
        $result_dev =_query($sql_dev);
        $nrow_dev = _num_rows($result_dev);
        if ($nrow_dev>0) {
            $l1=19;
            $row_dev = _fetch_array($result_dev);
            $devs = $align->addspleft(sprintf('%.4f', $row_dev['total']), $l1);
            $info_factura.=" (-) DEVOLUCIONES $:   ".$devs."\n";
        }
        $sql_dev="SELECT id_dev as id_devolucion, id_corte, n_devolucion, t_devolucion,afecta,tipo
    FROM devoluciones_corte WHERE id_corte='$id_corte' AND tipo='CCF'";
        $result_dev =_query($sql_dev);
        $nrow_dev = _num_rows($result_dev);
        if ($nrow_dev>0) {
            $info_factura.=$esp_init0."(-)NOTAS DE CREDITO :"."\n";
            $info_factura.=$esp_init0."  NUMERO   DOC     AFECTA      TOTAL"."\n";
            for ($j=0;$j<$nrow_dev;$j++) {
                $row_dev = _fetch_array($result_dev);

                $n_devolucion=str_pad($row_dev['n_devolucion'], 8, " ", STR_PAD_LEFT);
                $t_devolucion=str_pad(number_format($row_dev['t_devolucion'], 2, ".", ""), 11, " ", STR_PAD_LEFT);
                $afecta=str_pad($row_dev['afecta'], 11, " ", STR_PAD_LEFT);
                $tipo=$row_dev['tipo'];

                $info_factura.=" ".$n_devolucion."   ".$tipo.$afecta.$t_devolucion."\n";
                //$info_factura.=$esp_init0."TOTAL   :".$sp1.$total_docs."\n";
            }
        }

        $info_factura.=$line1;
        $cashfinal=$align->addspleft($cashfinal, $l1);
        $info_factura.=$esp_init0."EFECTIVO $:          ".$cashfinal."\n";
        $diferencia1=$align->addspleft($diferencia, $l1);
        $info_factura.=$esp_init0."DIFERENCIA $:        ".$diferencia1."\n";

        $sql_detail = _query("SELECT producto.descripcion , to_corte_producto.id_producto,to_corte_producto.id
      FROM to_corte_producto JOIN producto ON producto.id_producto = to_corte_producto.id_producto WHERE id_corte = $id_corte ");

        if (_num_rows($sql_detail)>0) {
            $info_factura.=str_pad("\nMOVIMIENTOS DE PRODUCTO", 36, " ", STR_PAD_BOTH)."\n\n";

            while ($ro = _fetch_array($sql_detail)) {
                // code...
                $info_factura.=str_pad($ro['descripcion'], 36, " ", STR_PAD_BOTH)."\n";
                //$info_factura.=" CANTIDAD      ANTERIOR       ACTUAL"."\n";

                $sql_det = _query("SELECT * FROM to_corte_producto_detalle where id_ref = $ro[id]");

                $o=0;
                $e=0;
                $s=0;
                $f=0;
                while ($row =_fetch_array($sql_det)) {
                    // code...

                    if ($o==0) {
                        // code...
                        $info_factura.=str_pad("INICIAL: ".round($row['stock_anterior'], 2), 36, " ", STR_PAD_RIGHT)."\n";
                        $info_factura.="  ENTRADA        SALIDA       ACTUAL"."\n";
                    }
                    $t="S";
                    if ($row['stock_anterior']<$row['stock_actual']) {
                        // code...
                        $t="E";

                        $e = $e +round($row['cantidad'], 2);
                        $info_factura.=
                                 str_pad(round($row['cantidad'], 2), 9, " ", STR_PAD_LEFT)
                                .str_pad("-", 14, " ", STR_PAD_LEFT)
                                .str_pad(round($row['stock_actual'], 2), 13, " ", STR_PAD_LEFT)
                                ."\n";
                        $f = round($row['stock_actual'], 2);
                    } else {
                        // code...
                        $s = $s +round($row['cantidad'], 2);
                        $info_factura.=
                             str_pad("-", 9, " ", STR_PAD_LEFT)
                            .str_pad(round($row['cantidad'], 2), 14, " ", STR_PAD_LEFT)
                            .str_pad(round($row['stock_actual'], 2), 13, " ", STR_PAD_LEFT)
                            ."\n";

                        $f = round($row['stock_actual'], 2);
                    }
                    $o++;
                }

                $info_factura.=
                     str_pad("", 36, "-", STR_PAD_LEFT)."\n";
                $info_factura.=
                     str_pad(round($e, 2), 9, " ", STR_PAD_LEFT)
                    .str_pad(round($s, 2), 14, " ", STR_PAD_LEFT)
                    .str_pad(round($f, 2), 13, " ", STR_PAD_LEFT)
                    ."\n";
                $info_factura.="\n";
            }
        }
    }

    if ($tipo=="X" || $tipo=="Z") {
        //listar devoluciones
        /*$sql_dev="SELECT id_devolucion, id_corte, n_devolucion, t_devolucion FROM devoluciones WHERE id_corte='$id_corte'";
        $result_dev =_query($sql_dev);
        $nrow_dev = _num_rows($result_dev);*/

        #$info_factura.=$esp_init."TIQUETE # ".$tike."\n\n";
        $subtotal=$cashini+$vtaefectivo+$ingresos;
        $totalcaja=$subtotal-$vales;
        $tot_exent=$texento+$fexento+$cfexento;
        $tot_grav=$tgravado+$fgravado+$cfgravado;
        $tot_fin=$totalt+$totalf+$totalcf;
        $tot_exent=sprintf('%.4f', $tot_exent);
        $tot_grav=sprintf('%.4f', $tot_grav);
        $tot_fin=sprintf('%.4f', $tot_fin);
        $subtotal=sprintf('%.4f', $subtotal);
        $totalcaja=sprintf('%.4f', $totalcaja);
        $esp_init1 = "       ";
        $esp_init0 = "";
        $info_factura.=$esp_init1."        EXEN.       GRAV.       TOTAL"."\n";
        $info_factura.=$line1;
        $n=12;
        $texento  = $align->addspleft($texento, $n);
        $tgravado = $align->addspleft($tgravado, $n);
        $totalt   = $align->addspleft($totalt, $n);

        $info_factura.=$esp_init0."TIQUETES: ".$texento." ".$tgravado." ".$totalt."\n";
        $fexento  = $align->addspleft($fexento, $n);
        $fgravado = $align->addspleft($fgravado, $n);
        $totalf   = $align->addspleft($totalf, $n);
        $info_factura.=$esp_init0."FACTURAS: ".$fexento." ".$fgravado." ".$totalf."\n";
        $cfexento = $align->addspleft($cfexento, $n);
        $cfgravado=$align->addspleft($cfgravado, $n);
        $totalcf=$align->addspleft($totalcf, $n);
        $info_factura.=$esp_init0."FISCALES: ".$cfexento." ".$cfgravado." ".$totalcf."\n";
        $info_factura.=$line1;
        $tot_exent = $align->addspleft($tot_exent, $n);
        $tot_grav  = $align->addspleft($tot_grav, $n);
        $tot_fin   = $align->addspleft($tot_fin, $n);

        $info_factura.=$esp_init0."TOTAL $ : ".$tot_exent." ".$tot_grav." ".$tot_fin."\n";
        $info_factura.="\n";

        $info_factura.=$esp_init1."      INICIO      FINAL      TOTAL"."\n";
        $info_factura.=$line1;
        $n=11;
        $total_docs=$totalnot+$totalnof+$totalnocf;
        $tinicio  = $align->addspleft($tinicio, $n);
        $tfinal   = $align->addspleft($tfinal, $n);
        $totalnot = $align->addspleft($totalnot, $n);
        $info_factura.=$esp_init0."TIQUETES:  ".$tinicio." ".$tfinal." ".$totalnot."\n";
        $finicio = $align->addspleft($finicio, $n);
        $ffinal  = $align->addspleft($ffinal, $n);
        $totalnof= $align->addspleft($totalnof, $n);
        $info_factura.=$esp_init0."FACTURAS:  ".$finicio." ".$ffinal." ".$totalnof."\n";
        $cfinicio  = $align->addspleft($cfinicio, $n);
        $cffinal   = $align->addspleft($cffinal, $n);
        $totalnocf = $align->addspleft($totalnocf, $n);
        $info_factura.=$esp_init0."FISCALES:  ".$cfinicio." ".$cffinal." ".$totalnocf."\n";
        $info_factura.=$line1;
        $total_docs = $align->addspleft($total_docs, 35);
        $info_factura.=$esp_init0."TOTAL:     ".$total_docs."\n";
        $info_factura.="\n";
    }
    $info_factura.="\n";
    $xdatos["cuerpo"] = 	$info_factura;
    $xdatos["pie"] = 	"";
    return $xdatos;
}
function head($alias)
{
    $sql_hf = "SELECT * FROM config_pos WHERE alias_tipodoc = '$alias'";
    $res_hf = _query($sql_hf);
    $row_hf = _fetch_array($res_hf);
    $hstring="";
    $hstring .=  FONT_A;
    if ($row_hf['header1'] != '') {
        $hstring .= chr(13) . $row_hf['header1'] . "\n";
    }
    if ($row_hf['header2'] != '') {
        $hstring .= chr(13) . $row_hf['header2'] . "\n";
    }
    if ($row_hf['header3'] != '') {
        $hstring .= chr(13) . $row_hf['header3'] . "\n";
    }
    if ($row_hf['header4'] != '') {
        $hstring .= chr(13) . $row_hf['header4'] . "\n";
    }
    if ($row_hf['header5'] != '') {
        $hstring .= chr(13) . $row_hf['header5'] . "\n";
    }
    if ($row_hf['header6'] != '') {
        $hstring .= chr(13) . $row_hf['header6'] . "\n";
    }
    if ($row_hf['header7'] != '') {
        $hstring .= chr(13) . $row_hf['header7'] . "\n";
    }
    if ($row_hf['header8'] != '') {
        $hstring .= chr(13) . $row_hf['header8'] . "\n";
    }
    if ($row_hf['header9'] != '') {
        $hstring .= chr(13) . $row_hf['header9'] . "\n";
    }
    if ($row_hf['header10'] != '') {
        $hstring .= chr(13) . $row_hf['header10'] . "\n";
    }
    return $hstring;
}
function foot($alias)
{
    $sql_hf = "SELECT * FROM config_pos WHERE alias_tipodoc = '$alias'";
    $res_hf = _query($sql_hf);
    $row_hf = _fetch_array($res_hf);
    $pstring =  CENTER_P;//chr(27) . chr(97) . chr(1); //Center align;
    if ($row_hf['footer1'] != '') {
        $pstring = chr(13) . $row_hf['footer1'] . "\n";
    }
    if ($row_hf['footer2'] != '') {
        $pstring .= chr(13) . $row_hf['footer2'] . "\n";
    }
    if ($row_hf['footer3'] != '') {
        $pstring .= chr(13) . $row_hf['footer3'] . "\n";
    }
    if ($row_hf['footer4'] != '') {
        $pstring .= chr(13) . $row_hf['footer4'] . "\n";
    }
    if ($row_hf['footer5'] != '') {
        $pstring .= chr(13) . $row_hf['footer5'] . "\n";
    }
    if ($row_hf['footer6'] != '') {
        $pstring .= chr(13) . $row_hf['footer6'] . "\n";
    }
    if ($row_hf['footer7'] != '') {
        $pstring .= chr(13) . $row_hf['footer7'] . "\n";
    }
    if ($row_hf['footer8'] != '') {
        $pstring .= chr(13) . $row_hf['footer8'] . "\n";
    }
    if ($row_hf['footer9'] != '') {
        $pstring .= chr(13) . $row_hf['footer9'] . "\n";
    }
    if ($row_hf['footer10'] != '') {
        $pstring .= chr(13) . $row_hf['footer10'] . "\n";
    }
    return $pstring;
}
//imprimir barcodes on ZPL
function print_bcode_old($id_producto, $qty, $tipo_etiq)
{
    $iva=getIVA();
    $id_sucursal=$_SESSION['id_sucursal'];
    $config_dir =getConfigDir($id_sucursal);
    $row_dir_print=_fetch_array($config_dir);
    $leftmarginlabel = $row_dir_print['leftmarginlabel'];
    $empresa = empresa();
    $empresa = quitar_tildes($empresa);
    $row=getProducto($id_producto);
    $id_prod=$row['id_producto'];
    $descripcion= quitar_tildes($row['descripcion']);
    $lndesc= strlen(trim($descripcion));
    $barcode=trim($row['barcode']);
    $nombre=$row['nombre'];
    $iva=getIVA();
    $pre = round($row['precio'] * (1+$iva), 2);
    $precio=sprintf("%.2f", $pre);
    $id_presentacion=$row['id_presentacion'];
    $descpre=$row['descpre'];
    $string="";
    $string.="^XA^MD12^XZ";
    for ($i=0;$i<$qty ;$i++) {
        $posx=	$leftmarginlabel;
        $posy=20;//x,y posicion
        $string.="^XA";
        $string.="^CF0,25";
        $string.="^FO".$posx.",".$posy."^FD".$empresa."^FS";
        $string.="^CF0,30";
        $string.="^BY2,1";
        $posx+=7;
        $posy+=30;
        $string.="^FO".$posx.",".$posy."^BY2";
        $string.="^BCN,75,Y,N";
        $string.="^FD".$barcode."^FS";
        $posx-=7;
        $posy+=106;
        $string.="^CF0,18";
        $string.="^FO".$posx.",".$posy."^FD".$descripcion."^FS";
        $string.="^CF0,28";
        $posx+=260;
        $string.="^FO".$posx.",".$posy."^FD"." $ ".$precio."^FS";
        $posy+=22;
        $posx-=260;
        $string.="^CF0,18";
        $string.="^FO".$posx.",".$posy."^FD".$descpre." ".$nombre."^FS";
        //$posx=30;
        //$posy+=5;
        //$string.="^CF0,30";
        //$string.="^FO".$posx.",".$posy."^FD"."$".$precio."^FS";
        $string.="^XZ";
    }
    return ($string);
}
//imprimir barcodes on ZPL
//imprimir barcodes on ZPL
function print_bcode($id_producto, $qty, $tipo_etiq, $precio_sel=0, $nombpresenta='', $id_presentacion=-1)
{
    $id_sucursal=$_SESSION['id_sucursal'];
    $config_dir =getConfigDir($id_sucursal);
    $row_dir_print=_fetch_array($config_dir);
    $leftmarginlabel = $row_dir_print['leftmarginlabel'];
    $empresa = empresa();
    $empresa = quitar_tildes($empresa);
    $row=getProductoPrese($id_producto, $id_presentacion);
    $id_prod=$row['id_producto'];
    $descripcion= quitar_tildes($row['descripcion']);
    //$iva=getIVA();
    //$pre = round($precio_sel * (1+$iva), 2);
    //$precio_sel_iva=sprintf("%.2f", $pre);
    $barcode=trim($row['barcode']);
    $nombre=$row['nombre'];
    $precio_sel=sprintf("%.2f", $precio_sel);
    $id_presentacion=$row['id_presentacion'];
    $descpre=$row['descpre'];
    $string="";
    for ($i=0;$i<$qty ;$i++) {
        $posx=	$leftmarginlabel;
        $posx+=41;
        $posy=9;//x,y posicion
        //comentar para quitar linea
        $string.="^XA";
        $string.="^FO".$posx.",".$posy."^GB14,1,1^FS";
        //fin comentar para quitar linea
        $posx-=35;
        $posy+=1;
        $string.="^CF0,25";
        $string.="^FO".$posx.",".$posy."^FD".$empresa."^FS";
        $string.="^CF0,30";
        $string.="^BY2,1";
        $posy+=30;

        $len= strlen(trim("".$barcode));

        if ($len<12 || $len>13) {
            $string.="^FO".$posx.",".$posy."^BY2,2";
            $string.="^BCN,80,Y,N,N";
            $string.="^FD".$barcode."^FS";
        }
        if ($len==13) { //EAN 13
            $string.="^FO".$posx.",".$posy."^BY3";
            $string.="^BEN,80,Y,N,N";
            $string.="^FD".$barcode."^FS";
        }
        if ($len==12) { //UPCA
            $string.="^FO".$posx.",".$posy."^BY3";
            $string.="^BUN,80,Y,N";
            $string.="^FD".$barcode."^FS";
        }
        $posx-=5;
        $posy+=105;
        $string.="^CF0,18";
        $string.="^FO".$posx.",".$posy."^FD".$descripcion."^FS";
        $posy+=20;
        $string.="^FO".$posx.",".$posy."^FD".$descpre." ".$nombpresenta." $ ".$precio_sel."^FS";

        $string.="^XZ";
    }
    return ($string);
}
function print_bcodeSet($tipo_etiq)
{
    //change media type thermal transfer or direct thermal
    $mt ="^MTT";
    if ($tipo_etiq=='TD') {
        $mt ="^MTD";
    }
    $string="";
    $posx=30;
    $posy=10;//x,y posicion
    $string.="^XA";
    $string.=$mt."^JUS";
    $string.="^XZ";
    return ($string);
}
//get productoy presentacion
function getProductoPrese($id_producto, $id_presentacion=-1)
{
    $sql="SELECT p.id_producto, p.barcode, p.descripcion,
	pp.precio,	pp.precio1,	pp.precio2, pp.id_presentacion,
	pp.descripcion as descpre, pr.nombre
	FROM producto AS p, presentacion_producto AS pp, presentacion AS pr
	WHERE  p.id_producto=pp.id_producto
	AND pp.id_presentacion=pr.id_presentacion
	AND p.id_producto='$id_producto'
	";
    if ($id_presentacion!=-1) {
        $sql.=" AND pp.id_presentacion='$id_presentacion'";
    }
    $res=_query($sql);
    $n=_num_rows($res);
    $result="";
    if ($n>0) {
        $result= _fetch_array($res);
    }
    return $result;
}
//imprimir abono a creditos
function print_abonos($id_abono_historial)
{
    $align=new AlignMarginText();
    $id_sucursal=$_SESSION['id_sucursal'];
    $line1 = str_repeat("_", 46) . "\n";
    $empresa=empresa();
    $alias= 'TIK';

    //Sucursal
    $row_suc=datos_sucursal($id_sucursal);
    $nitsuc= $row_suc['nit'];
    $nrcsuc= $row_suc['nrc'];
    $girosuc= $row_suc['giro'];
    $razonsuc= $row_suc['razon_social'];
    //detalles
    $result_fact=getDatosHistoAbono($id_abono_historial);
    $nrows_fact=_num_rows($result_fact);
    $det_ticket = "";
    $espacio = " ";
    $margen_izq1 =$align->leftmargin($espacio, 1);
    $margen_izq2 =$align->leftmargin($espacio, 4);
    $esp_init = $margen_izq1;
    $total      = 0;
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);

        $id_cliente = $row_fact['id_cliente'];
        $id_apertura = $row_fact['id_apertura'];
        $arr_abono_creditos= $row_fact['arr_abono_creditos'];
        $row_ap=getDatosAperturaNoVigente($id_apertura);
        $id_usuario = $row_ap['id_empleado'];
        $id_vendedor= $id_usuario;
        $caja       = $row_ap['caja'];
        $turno=$row_ap['turno'];

        $total      = $row_fact['abono'];
        $fecha      =$row_fact['fecha'];
        $hora       =$row_fact['hora'];
        $cuotas      = $row_fact['cuotas']; //para ver si es credito por cuotas

        $fecha_fact=ed($fecha);
        $numero_doc="";


        $dats_caja = getCaja($caja);
        $fehca = ED($dats_caja["fecha"]);
        $resolucion = $dats_caja["resolucion"];
        $serie = $dats_caja["serie"];
        $desde = $dats_caja["desde"];
        $hasta = $dats_caja["hasta"];
        $cajero= getCajero($id_usuario);
        $nombrecaja=$dats_caja["nombre"];
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto="";
        $muni="";
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nitcte=$row1["nit"];
            $nrccte=$row1["nrc"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombreapecte=$row1['nombre'];
            $direccion=$row1['direccion'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $codigocliente=$row1['codcliente'];
            if (isset($id_d) && isset($id_m)) {
                $deptoMuni=getDepartamento($id_d, $id_m);
                $row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];
            }
        }
        //dividir texto giro
        $desgiro = $align->wordwrap1("GIRO: ".$girosuc, 40);
        $descgiro="";
        foreach ($desgiro as $lin) {
            $descgiro .= trim($lin). "\n";
            //$descgiro .= $align->onelineleft($lin,40, 1, $espacio). "\n";
        }
        $nombreVendedor=vendedor($id_vendedor);
        $len_numero_doc=strlen($numero_doc)-4;

        $date1 = new DateTime($fecha." ".$hora);
        $hora1= $date1->format("g"). ':' .$date1->format("i"). ' ' .$date1->format("A");
        $fecha1 = $date1->format("d"). '/' .$date1->format("m"). '/' .$date1->format("Y");
        $tiq = zfill($id_abono_historial, 10);
        $hstring  = CENTER_P;//chr(27) . chr(97) . chr(1); //Center
      $hstring .= DOUBLEFONT_P;// chr(27) . chr(33) . chr(16); //FONT double size pos
      $hstring .= $empresa."\n";
        $hstring .= FONT_A; //FONT a medium size
        $hstring .= $razonsuc."\n";
        $hstring .= $descgiro;
        $hstring .= "NIT :  ".$nitsuc." NRC :".$nrcsuc."\n";
        $hstring .= "RESOLUCION:  ".$resolucion."\n";
        $hstring .= " DEL ".$desde." AL ".$hasta."\n";
        $hstring .= " SERIE ".$serie."\n";
        $hstring .= " FECHA RESOLUCION ".$fehca."\n";
        $hstring .= " #: " . $tiq . "\n";
        $hstring .= " FECHA: " .	$fecha1 . " HORA:" . $hora1 . "\n";
        $hstring .= " CAJERO: ". $cajero . "\n";
        $hstring .= " CAJA : ".$nombrecaja. "  TURNO: ".$turno."\n";
        $hstring .= " ABONO A CREDITO \n";
        $hstring .= " CLIENTE:".$nombreapecte."\n";
        //$hstring .=head($alias);
        $hstring .=  LEFT_P;
        $th = chr(13) . " ID. CREDITO   CUOTA  MONTO ABONADO $" . "\n";
        $det_ticket  = SPANISH ;
        $det_ticket .= chr(13) . $line1 . "\n"; // Print text Lin
      $det_ticket .= FONT_B; //FONT B small size
      $det_ticket .=  $th;
        $det_ticket .= FONT_A; //FONT a medium size
        $det_ticket .= chr(13) . $line1. "\n";

        $total_final=0;
        $lineas=6;
        $cuantos=0;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;
        $tmpItems= array();
        $wdesc = 40;
        $det_ticket .=  FONT_B;//$_font_b; //FONT B small size

        $id_venta_cuotas = array();

        if ($arr_abono_creditos!="" || !is_string($arr_abono_creditos)) {
            $arr_abono_credito = json_decode($arr_abono_creditos, true);
            foreach ($arr_abono_credito as $arr2) {
                foreach ($arr2 as  $key => $val) {
                    if ($key=='id_credito') {
                        array_push($id_venta_cuotas, $val);
                        $id = $align->rightaligner($val, $espacio, 15);
                        $det_ticket  .= "     ".strtoupper($val). " ";
                    }
                    if ($key=='ncuota') {
                        $det_ticket  .=  "  CUOTA No. :".$val." ";
                    }
                    if ($key=='abonado') {
                        $val =" $ ".sprintf("%.2f", $val);
                        $subt = $align->rightaligner($val, $espacio, 15);
                        $det_ticket  .=   $subt;
                    }
                    if ($key=='valorcuota') {
                        $val =" $ ".sprintf("%.2f", $val);
                        $subt = $align->rightaligner($val, $espacio, 15);
                        $det_ticket  .=   $subt;
                    }
                    if ($key=='abonar') {
                        $val =" $ ".sprintf("%.2f", $val);
                        $subt = $align->rightaligner($val, $espacio, 15);
                        $det_ticket  .=   $subt;
                    }
                }
                $det_ticket  .=  "\n";
            }
        }
        if ($cuotas=='1') {
            $det_ticket  .= getDetalleVenta($id_venta_cuotas);
        }



        $det_ticket .= FONT_A;
        $det_ticket .= chr(13) . $line1;

        $totales =  DOUBLEFONT_P; //FONT DOUBLE
    $totales .= RIGHT_P;  //Right align
    $totals = "  TOTAL   $ " .number_format($total, 2, ".", ","). "  " . "\n";
        $lentot = strlen($totals);
        $totales .= $totals;
        $totales .= FONT_A;  //FONT A
        $totales .= str_repeat("_", $lentot) . "\n";
        $logo = getLogoSuc($id_sucursal);
        $uri=getUrl().$logo;
        $totales   .= CENTER_P; //center align
        $pstring ="";
        $pstring  .= CENTER_P; //center align
    $pstring .= FONT_A; //FONT A
    $total_letras = CENTER_P;
        $total_letras .= getTotalTexto(number_format($total, 2, ".", ","));

        $xdatos["encabezado"] = $hstring;
        $xdatos["totales"] = $totales;
        $xdatos["cuerpo"] = $det_ticket;
        $xdatos["pie"] = $pstring;
        $xdatos["total_letras"] = $total_letras;
        //$xdatos["img"] = $uri ;
        $xdatos["img"] = $uri ;
        return $xdatos;
    }
}
function print_ticket_cuotas($id_factura)
{
    $align=new AlignMarginText();
    $id_sucursal=$_SESSION['id_sucursal'];
    $line1 = str_repeat("_", 46) . "\n";
    $empresa=empresa();
    $alias= 'TIK';

    //Sucursal
    $row_suc=datos_sucursal($id_sucursal);
    $nitsuc= $row_suc['nit'];
    $nrcsuc= $row_suc['nrc'];
    $girosuc= $row_suc['giro'];
    $razonsuc= $row_suc['razon_social'];
    //detalles
    $result_fact=datos_factura($id_factura);
    $nrows_fact=_num_rows($result_fact);
    $det_ticket = "";
    $espacio = " ";
    $margen_izq1 =$align->leftmargin($espacio, 1);
    $margen_izq2 =$align->leftmargin($espacio, 4);
    $esp_init = $margen_izq1;
    $total      = 0;
    if ($nrows_fact>0) {
        $row_fact=_fetch_array($result_fact);
        $id_cliente = $row_fact['id_cliente'];
        $id_factura = $row_fact['id_factura'];
        $id_usuario = $row_fact['id_usuario'];
        $id_vendedor= $row_fact['id_empleado'];
        $total      = $row_fact['total'];
        $fecha=$row_fact['fecha'];
        $hora=$row_fact['hora'];
        $caja=$row_fact['caja'];
        $turno=$row_fact['turno'];
        $fecha_fact=ed($fecha);
        $numero_doc=trim($row_fact['numero_doc']);
        $total=$row_fact['total'];
        $descuent=$row_fact['descuento'];
        $porcentaje=$row_fact['porcentaje'];

        $total_efectivo = $row_fact['total_efectivo'];
        $total_tarjeta  = $row_fact['total_tarjeta'];
        $resPago  = getPagoXFactura($id_factura, "VAL");
        $nrowPago = _num_rows($resPago);
        $datos_extra = "";
        if ($nrowPago>0) {
            $rowPago   =  _fetch_array($resPago);
            $datos_extra = $rowPago['datos_extra'];
        }
        $dats_caja = getCaja($caja);
        $fehca = ED($dats_caja["fecha"]);
        $resolucion = $dats_caja["resolucion"];
        $serie = $dats_caja["serie"];
        $desde = $dats_caja["desde"];
        $hasta = $dats_caja["hasta"];
        $cajero= getCajero($id_usuario);
        $nombrecaja=$dats_caja["nombre"];
        $resultCte=datos_clientes($id_cliente);
        $count=_num_rows($resultCte);
        $depto="";
        $muni="";
        if ($count > 0) {
            $row1=_fetch_array($resultCte);
            $nitcte=$row1["nit"];
            $nrccte=$row1["nrc"];
            $dui=$row1["dui"];
            $telefono1=$row1["telefono1"];
            $girocte=$row1["giro"];
            $nombreapecte=$row1['nombre'];
            $direccion=$row1['direccion'];
            $id_d=$row1['depto'];
            $id_m=$row1['municipio'];
            $codigocliente=$row1['codcliente'];
            if (isset($id_d) && isset($id_m)) {
                $deptoMuni=getDepartamento($id_d, $id_m);
                $row_d=_fetch_array($deptoMuni);
                $depto=$row_d['ndepto'];
                $muni =$row_d['nmuni'];
            }
        }
        //dividir texto giro
        $desgiro = $align->wordwrap1("GIRO: ".$girosuc, 40);
        $descgiro="";
        foreach ($desgiro as $lin) {
            $descgiro .= trim($lin). "\n";
            //$descgiro .= $align->onelineleft($lin,40, 1, $espacio). "\n";
        }
        $nombreVendedor=vendedor($id_vendedor);
        $len_numero_doc=strlen($numero_doc)-4;
        $tiq=substr($numero_doc, 0, $len_numero_doc);
        $date1 = new DateTime($fecha." ".$hora);
        $hora1= $date1->format("g"). ':' .$date1->format("i"). ' ' .$date1->format("A");
        $fecha1 = $date1->format("d"). '/' .$date1->format("m"). '/' .$date1->format("Y");
        //$tiq = zfill($corr, 10);
      $hstring  = CENTER_P;//chr(27) . chr(97) . chr(1); //Center
      $hstring .= DOUBLEFONT_P;// chr(27) . chr(33) . chr(16); //FONT double size pos
      $hstring .= $empresa."\n";
        $hstring .= FONT_A; //FONT a medium size
        $hstring .= $razonsuc."\n";
        $hstring .= $descgiro;
        $hstring .= "NIT :  ".$nitsuc." NRC :".$nrcsuc."\n";
        $hstring .= "RESOLUCION:  ".$resolucion."\n";
        $hstring .= " DEL ".$desde." AL ".$hasta."\n";
        $hstring .= " SERIE ".$serie."\n";
        $hstring .= " FECHA RESOLUCION ".$fehca."\n";
        $hstring .= " TICKET #: " . $tiq . "\n";
        $hstring .= " FECHA: " .	$fecha1 . " HORA:" . $hora1 . "\n";
        $hstring .= " VENDEDOR: ".$nombreVendedor."\n";
        $hstring .= " CAJERO: ". $cajero . "\n";
        $hstring .= " CAJA : ".$nombrecaja. "  TURNO: ".$turno."\n";
        $hstring .=head($alias);
        $hstring .= " VENTA AL CREDITO POR CUOTAS \n";
        $hstring .= " CLIENTE:".$nombreapecte."\n";
        $hstring .=  LEFT_P;
        $th = chr(13) . "CANT.    DESCRIPCION                     " . "\n";
        $det_ticket =  SPANISH;//
      $det_ticket .= chr(13) . $line1 . "\n"; // Print text Lin
      $det_ticket .= FONT_B; //FONT B small size
      $det_ticket .=  $th;
        $det_ticket .= FONT_A; //FONT a medium size
        $det_ticket .= chr(13) . $line1. "\n";

        $total_final=0;
        $lineas=6;
        $cuantos=0;
        $subt_exento=0;
        $subt_gravado=0;
        $total_exento=0;
        $total_gravado=0;
        $tmpItems= array();
        $wdesc = 60;
        $desc = "";
        $ln=0;

        $det_ticket .=  FONT_B;//$_font_b; //FONT B small size
        //Obtener informacion de tabla Factura_detalle y producto o servicio
        $result_fact_det=datos_fact_det($id_factura);
        $nrows_fact_det=_num_rows($result_fact_det);
        for ($i=0;$i<$nrows_fact_det;$i++) {
            $row_fact_det=_fetch_array($result_fact_det);
            $id_producto =$row_fact_det['id_producto'];
            $descripcion =$row_fact_det['descripcion'];
            //descripcion presentacion
            $id_presentacion =$row_fact_det['id_presentacion'];
            $descpre =$row_fact_det['descpre'];
            $nombre_pre =$row_fact_det['descp'];
            $descpresenta =$row_fact_det['descripcion_pr'];
            $exento=$row_fact_det['exento'];
            $id_factura_detalle =$row_fact_det['id_factura_detalle'];
            $id_prod_serv =$row_fact_det['id_prod_serv'];
            $cantidad =$row_fact_det['cantidad'];
            $precio_venta =$row_fact_det['precio_venta'];
            $descuento =$row_fact_det['descuento'];
            $subt=$row_fact_det['subtotal'];
            $unidad=$row_fact_det['unidad'];
            //$subt = $subt - $descuento;
            $id_empleado =$row_fact_det['id_empleado'];
            $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];

            $cantidad=$cantidad/$unidad;
            $descripcion1 = $descripcion." ".$nombre_pre;
            $descfinal    = $align->addspright($descripcion1, 36);
            $descripts = $align->wordwrap1($descripcion1, $wdesc);
            $ln=count($descripts);
            $tmplinea = array();
            $precio_unit=sprintf("%.4f", $precio_venta);
            $subtotal=sprintf("%.4f", $subt);
            $total_final=$total_final+$subtotal;
            $cant = $align->rightaligner($cantidad, $espacio, 6);
            $det_ticket .= $cant  ." ".$descripts[0]. "\n";
            $sp =  $align->leftmargin(" ", 6);
            for ($j=1;$j<$ln;$j++) {
                $det_ticket .= $sp  ." ".$descripts[$j]. "\n";
            }
            //$det_ticket .= $sp  ." ".$desc. "\n";
        }
        $rowvta=getVentaCuotas($id_factura);
        $id_venta_cuota = $rowvta['id_venta_cuota'];
        $rowcuotas=getCuotaMinMax($id_venta_cuota);
        //$cant = $align->rightaligner($cantidad, $espacio,4);
        $prima =$align->rightaligner($rowvta['prima'], $espacio, 12);
        $saldo = round($total - $prima, 2);
        $saldoini = $align->rightaligner($saldo, $espacio, 12);
        $valorcuota =$align->rightaligner($rowvta['valorcuota'], $espacio, 12);
        $det_ticket .= FONT_A;
        $det_ticket .= chr(13) . $line1. "\n";
        $det_ticket .= $sp  ."NUMERO CUOTAS     : ".$rowvta['numerocuotas']. "\n";
        $det_ticket .= $sp  ."C/CUOTA VENCE  EL : ".$rowvta['diavence']." DE C/MES". "\n";
        $det_ticket .= $sp  ."PRIMERA CUOTA, EL : ".ED($rowcuotas['inifecha']). "\n";
        $det_ticket .= $sp  ."ULTIMA CUOTA,  EL : ".ED($rowcuotas['finfecha']). "\n";
        $det_ticket .= $sp  ."VALOR CUOTA       $ ".$valorcuota. "\n";
        $det_ticket .= $sp  ."PRIMA             $ ".$prima. "\n";
        $det_ticket .= $sp  ."SALDO PENDIENTE   $ ".$saldoini. "\n";
        $det_ticket .= chr(13) . $line1;
        $totales =  DOUBLEFONT_P; //FONT DOUBLE
    $totales .= RIGHT_P;  //Right align
    $totals = "  TOTAL   $ " .number_format($total, 2, ".", ","). "  " . "\n";
        $lentot = strlen($totals);
        $totales .= $totals;
        $totales .= FONT_A;  //FONT A
        $totales .= str_repeat("_", $lentot) . "\n";
        $logo = getLogoSuc($id_sucursal);
        $uri=getUrl().$logo;
        $totales   .= CENTER_P; //center align
        $resPagoefec  = getPagoXFactura($id_factura, "CON");
        $nrowPagoefec = _num_rows($resPagoefec);
        $pstring ="";
        if ($nrowPagoefec>0) {
            $rowPagoEfec   =  _fetch_array($resPagoefec);
            $cambioEfec = $rowPagoEfec['datos_extra'];
            //mostrar cambio
            if ($cambioEfec!="" || !is_string($cambioEfec)) {
                $data_extEfect = json_decode($cambioEfec, true);
                foreach ($data_extEfect as $key => $data) {
                    //if($key=='cambio' && $data!='0')
                    $pstring  .= $align->addspleft(" ", 8).strtoupper($key. " : ". $data). "\n";
                }
            }
        }
        //pie
        $pstring .=foot($alias);
        if ($total_efectivo>0) {
            $totales .="PAGO EFECTIVO: ". $total_efectivo. "\n";
        }
        if ($total_tarjeta>0) {
            $totales .= "PAGO TARJETA: ".$total_tarjeta. "\n";
        }
        for ($n=0;$n<4;$n++) {
            $pstring .= "\n";
        }
        $pstring  .= CENTER_P; //center align
    $pstring .= FONT_A; //FONT A
    $total_letras = CENTER_P;
        $total_letras .= getTotalTexto(number_format($total, 2, ".", ","));
        $xdatos["encabezado"] = $hstring;
        $xdatos["totales"] = $totales;
        $xdatos["cuerpo"] = $det_ticket;
        $xdatos["pie"] = $pstring;
        $xdatos["total_letras"] = $total_letras;
        //$xdatos["img"] = $uri ;
        $xdatos["img"] = $uri ;
        return $xdatos;
    }
}
function getDetalleVenta($id_venta_cuotas)
{
    $det_ticket ="";
    $align=new AlignMarginText();
    foreach ($id_venta_cuotas as $id_venta_cuota) {
        $q="SELECT id_factura,saldo,numerocuotas FROM venta_cuota 
    WHERE  id_venta_cuota='$id_venta_cuota'";
        $r=_query($q);
        $row=_fetch_row($r);
        $id_factura=$row[0];
        $saldo=$row[1];
        $numerocuotas=$row[2];
        //var_dump($id_factura);
        $sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
    producto.codigo,
    presentacion.nombre as descp,
    presentacion.descripcion AS descripcion_pr,
    presentacion_producto.descripcion AS descpre,
    presentacion_producto.unidad,
    presentacion_producto.id_pp as id_presentacion,
    factura_detalle.*
    FROM factura_detalle
    JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
    JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_pp
    JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
    WHERE  factura_detalle.id_factura='$id_factura'
    ";
        $result_fact_det=_query($sql_fact_det);
        //	return $result_fact_det;
        //$result_fact_det = getDetalleVenta($id_venta_cuota);
        $wdesc=60;
        $total_final=0;
        $espacio=" ";
        $nrows_fact_det=_num_rows($result_fact_det);
        $det_ticket .= "     SALDO : $ ".$saldo. "\n";
        $det_ticket .= "     CUOTAS :  ".$numerocuotas. "\n";
        for ($s=0;$s<$nrows_fact_det;$s++) {
            $row_fact_det=_fetch_array($result_fact_det);
            $id_producto =$row_fact_det['id_producto'];
            $descripcion =$row_fact_det['descripcion'];
            //descripcion presentacion
            $id_presentacion =$row_fact_det['id_presentacion'];
            $descpre =$row_fact_det['descpre'];
            $nombre_pre =$row_fact_det['descp'];
            $descpresenta =$row_fact_det['descripcion_pr'];
            $exento=$row_fact_det['exento'];
            $id_factura_detalle =$row_fact_det['id_factura_detalle'];
            $id_prod_serv =$row_fact_det['id_prod_serv'];
            $cantidad =$row_fact_det['cantidad'];
            $precio_venta =$row_fact_det['precio_venta'];
            $descuento =$row_fact_det['descuento'];
            $subt=$row_fact_det['subtotal'];
            $unidad=$row_fact_det['unidad'];
            //$subt = $subt - $descuento;
            $id_empleado =$row_fact_det['id_empleado'];
            $tipo_prod_serv =$row_fact_det['tipo_prod_serv'];

            $cantidad=$cantidad/$unidad;
            $descripcion1 = $descripcion." ".$nombre_pre;
            $descfinal    = $align->addspright($descripcion1, 36);
            $descripts = $align->wordwrap1($descripcion1, $wdesc);
            $ln=count($descripts);
            $tmplinea = array();
            $precio_unit=sprintf("%.4f", $precio_venta);
            $subtotal=sprintf("%.4f", $subt);
            $total_final=$total_final+$subtotal;
            $cant = $align->rightaligner($cantidad, $espacio, 6);
            $det_ticket .= $cant  ." ".$descripts[0]. "\n";
            $sp =  $align->leftmargin(" ", 6);
            for ($p=1;$p<$ln;$p++) {
                $det_ticket .= $sp  ." ".$descripts[$p]. "\n";
            }
        }
    }
    return  $det_ticket ;
}
