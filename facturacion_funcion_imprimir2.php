<?php
include('AlignMarginText.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');
function getMargins($alias){
  $q="SELECT  id, alias,marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10,
  marg_body,marg_foot, cols_body, col_body_arr, lines_body,
  f1, f2, f3, f4, f5, f6, f7, f8, f9, f10
  FROM margen_cols_form
  WHERE alias='$alias'";
  $res=_query($q);
  $datos = _fetch_array($res);
  return $datos;
}
function setPrinterCode(){
  $arraySetPrint = ['lineinch6' => chr(27).chr(50),
  'lineinch8' => chr(27).chr(48),
  'cmode'=>chr(15), //condensed
  'ncmode'=>chr(18), //no condensed
  'doublefont'=> chr(27).chr(33).chr(41), // font double elite
  'elite12'=> chr(27).chr(77), //Select elite width (12 cpi)
  'sanserif'=> chr(27).chr(107).chr(49), //san serif
  'spanish'=> chr(27).chr(54), //spanish latin print chars
 ];
  return $arraySetPrint;
}
function print_fact($id_factura,$nitcte='',$nombreapecte=''){
  $align=new AlignMarginText();
  //traer  posiciones y columnas almacenados en bd
  $val=getMargins('COF');
  $marg_sup   = $val['marg_sup'];
  $h1         = explode(',',$val['h1']);
  $h2         = explode(',',$val['h2']);
  $h3         = explode(',',$val['h3']);
  $h4         = explode(',',$val['h4']);
  $h5         = explode(',',$val['h5']);
  $h6         = explode(',',$val['h6']);
  $h7         = explode(',',$val['h7']);
  $h8         = explode(',',$val['h8']);
  $f1         = explode(',',$val['f1']);
  $f2         = explode(',',$val['f2']);
  $f3         = explode(',',$val['f3']);
  $f4         = explode(',',$val['f4']);
  $f5         = explode(',',$val['f5']);
  $f6         = explode(',',$val['f6']);
  $f7         = explode(',',$val['f7']);
  $f8         = explode(',',$val['f8']);
  $cb_arr     = explode(',',$val['col_body_arr']);
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
	if($nrows_fact>0){
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
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		//Datos del Cliente
    $resultCte=datos_clientes($id_cliente);
		$count=_num_rows($resultCte);
    $depto="";
    $muni="";
    $vtacta = "CONTADO";
    if($credito==1){
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
            if(isset($id_d) && isset($id_m)){
              $deptoMuni=getDepartamento($id_d,$id_m);
              $row_d=_fetch_array($deptoMuni);
              $depto=$row_d['ndepto'];
              $muni =$row_d['nmuni'];
            }
		}
    if ($nitcte==""){
      $nitcte=$dui;
    }
    $txt_dui="DUI: ".$dui;
    $nombreVendedor=vendedor($id_vendedor);
		$dir_txt=substr($direccion,0,75);
		$total_final=0;
		$imprimir="";
    $info_factura="";
    $hstring .= chr(27).chr(54); //spanish latin print chars
    $hstring .= chr(27).chr(50); //6 lineas  por  pulgada

    for($s=0;$s<$marg_sup;$s++){
      $info_factura.="\n";
    }
    list($dd,$mm,$aa)=explode("-",$fecha_fact);
    $info_factura.= $align->addspleft("",$h1[0]).$align->addspcent($dd,$h1[1]).$align->addspcent($mm,$h1[2]).$align->addspcent($aa,$h1[2])."\n\n";
    //Datos encabezado factura
    if($extra_nombre!=""){
      $nombreapecte=$nombreapecte." (".$extra_nombre.")";
    }
    $info_factura.=$align->addspright("",$h2[0]).$align->addspright($nombreapecte,$h2[1])."\n\n";
    $info_factura.=$align->addspleft("",$h3[0]).$align->addspright($dir_txt,$h3[1]).$align->addspright($txt_dui,$h3[2])."\n\n";
    $info_factura.=$align->addspleft("",$h4[0]).$align->addspright($nitcte,$h4[1]).$align->addspleft("",$h4[2]).$align->addspright($vtacta,$h4[3])."\n\n";//nit o dui
    $info_factura.=$align->addspleft("",$h5[0]).$align->addspright($nombreVendedor,$h5[1]).$align->addspleft("",$h5[2]).$align->addspright($codigocliente,$h5[3])."\n";// condiciones operacion
		for($p=0;$p<$marg_body;$p++){
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
		for($i=0;$i<$nrows_fact_det;$i++){
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
      if($bonificacion>0){
        $bonificacion  =  $bonificacion / $unidad;
      }
			//linea a linea
			$descripcion1=substr($descripcion,0,$cb_arr[3]).", ".substr($descpresenta,0,10)." ".substr($descpre,0,10);
      $lendesc=$cb_arr[3]+10+10;
			$descrip=$align->texto_espacios($descripcion1,$lendesc);
			$subt=$precio_venta*$cantidad;
      $subt_bonifica=$precio_venta*$bonificacion;
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
      $subtotal_bonifica=sprintf("%.4f",$subt_bonifica);
			$total_final=$total_final+$subtotal;
      $cantprint=$cantidad-$bonificacion;
      if ($cantprint<0){
        $cantprint=0;
      }
			if ($exento==0){
				$e_g="G";
        $precio_sin_iva =round($row_fact_det['precio_venta'],4);
				$precio_sin_iva0 =$row_fact_det['precio_venta'];
				$subt_sin_iva=round($precio_sin_iva0*$cantidad,4);
        $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion,4);
				$subt_gravado=round($subt_sin_iva,4);
				$total_gravado=$subt_sin_iva+$total_gravado;
			}
			else{
				$e_g="E";
				$precio_sin_iva =round($row_fact_det['precio_venta'],4);
				$precio_sin_iva0 =$row_fact_det['precio_venta'];
				$subt_sin_iva=$precio_sin_iva0*$cantidad;
        $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion,4);
				$subt_exento=sprintf("%.4f",$subt_sin_iva);
				$total_exento=$subt_sin_iva+$total_exento;
        $total_gravado=$subt_sin_iva+$total_gravado;
			}
      $precio_sin_iva_print=round($precio_sin_iva,4);
			$subt_sin_iva_print=round($subt_sin_iva,4);
      $psiva=number_format($precio_sin_iva_print,4);
      $ssiva=number_format($subt_sin_iva_print,4);
      $info_factura.=$align->addspright(" ",$cb_arr[0]); //margen inicial
      $info_factura.=$align->addspleft($cantprint,$cb_arr[1])." "; //cantidad
      $info_factura.=$align->addspright(" ",$cb_arr[2]);
      $info_factura.=$align->addspright($descrip,$lendesc); //descripcion
      if ($bonificacion>0){
        $info_factura.=$align->addspleft($bonificacion,$cb_arr[4]); //bonificacion
      }else{
        $info_factura.=$align->addspleft(" ",$cb_arr[4]); //bonificacion
      }
      $info_factura.=$align->addspleft($psiva,$cb_arr[5]);  //precio
      $info_factura.=$align->addspleft("",$cb_arr[6]);  //vta. no sujetas
      $info_factura.=$align->addspleft("",$cb_arr[7]);  //vta. exenta
      $info_factura.=$align->addspleft($ssiva,$cb_arr[8])."\n";
      //calulo totales bonificacion
      $total_bonifica+= $subt_boni_sin_iva;
			$cuantos=$cuantos+1;
		}
    //restar total gravado - bonificacion  para luego scar IVA
    $subtotal_grav_sinboni  = $total_gravado - $total_bonifica ;
  	$calc_iva               = round($iva * $subtotal_grav_sinboni ,4);
  	$total_iva_format       = sprintf("%.4f",$calc_iva);
  	$total_final_format     = sprintf("%.4f",$total_final);
    $subt_boni_iva          = round($subtotal_grav_sinboni,4);

    $subt_boni_ivar         = sprintf("%.2f",$subt_boni_iva);
  	list($entero,$decimal)  = explode('.',$subt_boni_ivar);
    if ($entero>0)
  	 $enteros_txt=num2letras($entero);
     else
     $enteros_txt="CERO ";
  	if($entero=='100' && $decimal=='00'){
  		$enteros_txt="CIEN ";
  	}
  	if(strlen($decimal)==1){
  		$decimales_txt=$decimal."0";
  	}
  	else{
  		$decimales_txt=$decimal;
  	}
  	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
  	$total_value=sprintf("%.4f",$total);
    $total_fin=round($subtotal_grav_sinboni - $retencion ,4);
    $total_fin_format=sprintf("%.4f",$total_fin);

  	//$total_fin=$total_exento+$total_gravado;
  	$total_value_exento=sprintf("%.4f",$total_exento);
  	$total_value_gravado=sprintf("%.4f",$total_gravado);
  	$total_value_fin=sprintf("%.4f",$total_fin);
    //total de bonificaciones
    $total_bonificacion=sprintf("%.4f",$total_bonifica);
  	//totales y n lineas
  	$lineas_faltantes= $lines_body - $cuantos;
    if ($lineas_faltantes>0){
    	for($j=0;$j<$lineas_faltantes;$j++){
    		$info_factura.= "\n";
    	}
    }
    if ($marg_foot>0){
      for($k=0;$k<$marg_foot;$k++){
        $info_factura.= "\n";
      }
    }
    $info_factura.= chr(27).chr(50); //Select 6 lines per inch
    $info_factura.= "\n";
  	//generar 2 lineas del texto del total de la factura
  	$total_txt0 =$align->wordwrap1($cadena_salida_txt,$f1[1],2);
  	$concepto_print="";
  	$tmplinea = array();
  	$ln=0;
  	foreach($total_txt0 as $total_txt1){
  		$tmplinea[]=$align->addspright($total_txt1,$f1[1]);
  		$ln=$ln+1;
  	}
  	$subtotal_gravado=round($total_gravado+$calc_iva,4);
  	$subtotal_exento=$total_exento;
  	$total_final_todos=round($subtotal_exento+$subtotal_gravado,4);
    //imprime  total gravado y total texto linea 1
  	//$info_factura.=$align->addspleft("",$f1[0]).$align->addspright($tmplinea[0],$f1[1]).$align->addspright("",$f1[2]).$align->addspleft($total_value_gravado,$f1[3])."\n";
    $info_factura.=$align->addspleft(" ",$f1[0]).$align->addspright(" ",$f1[1]).$align->addspright("",$f1[2]).$align->addspleft($total_value_gravado,$f1[3])."\n";
    $info_factura.=$align->addspleft(" ",$f2[0]).$align->addspright($tmplinea[0],$f2[1]).$align->addspright("BONIF.",$f2[2]).$align->addspleft($total_bonificacion,$f2[3])."\n";
    // imprime total en texto linea 2  y total de bonificacion
    if($retencion==0)
      $retencion=" ";
    if($ln>1){
      $info_factura.=$align->addspleft(" ",$f3[0]).$align->addspright($tmplinea[1],$f3[1]).$align->addspright(" ",$f3[2]).$align->addspleft($retencion,$f3[3])."\n\n";
    }
    else{
       // imprime solo Subtotal
        $info_factura.=$align->addspleft(" ",$f3[0]).$align->addspright(" ",$f3[1]).$align->addspright(" ",$f3[2]).$align->addspleft($retencion,$f3[3])."\n\n";
    }
    //$info_factura.="\n";
    $info_factura.=$align->addspleft(" ",$f4[0]).$align->addspright(" ",$f4[1]).$align->addspright(" ",$f4[2]).$align->addspleft($total_fin_format,$f4[3])."\n"; //subtotal descuento bonifica + IVA
    for($j=0;$j<5;$j++){
      $info_factura.= "\n";
    }
    $info_factura.=$align->addspleft(" ",$f8[0]).$align->addspright(" ",$f8[1]).$align->addspright(" ",$f8[2]).$align->addspleft($total_fin_format,$f8[3])."\n"; //subtotal descuento bonifica + IVA
  	$info_factura.="\n";
  	// retornar valor generado en funcion
  	return ($info_factura);
  }
}
function print_ccf($id_fact,$tipo_id,$nitcte,$nrccte,$nombreapecte){
  $align=new AlignMarginText();
  $val=getMargins('CCF');
  // posiciones y columnas
  $marg_sup   = $val['marg_sup'];
  $h1         = explode(',',$val['h1']);
  $h2         = explode(',',$val['h2']);
  $h3         = explode(',',$val['h3']);
  $h4         = explode(',',$val['h4']);
  $h5         = explode(',',$val['h5']);
  $h6         = explode(',',$val['h6']);
  $h7         = explode(',',$val['h7']);
  $h8         = explode(',',$val['h8']);
  $f1         = explode(',',$val['f1']);
  $f2         = explode(',',$val['f2']);
  $f3         = explode(',',$val['f3']);
  $f4         = explode(',',$val['f4']);
  $f5         = explode(',',$val['f5']);
  $f6         = explode(',',$val['f6']);
  $f7         = explode(',',$val['f7']);
  $f8         = explode(',',$val['f8']);
  $cb_arr     = explode(',',$val['col_body_arr']);
  $marg_body = $val['marg_body'];
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
	$razonsocial=$row_empresa['razon_social'];
	$giro_empresa=$row_empresa['giro'];
	$iva=$row_empresa['iva']/100;
	//inicio datos
	$info_factura=chr(13);
	//Obtener informacion de tabla Factura
  $result_fact=datos_factura($id_factura);
  $nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
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
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
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
    $depto="";
    $muni="";
    $vtacta="CONTADO";
    if($credito==1){
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
            if(isset($id_d) && isset($id_m)){
              $deptoMuni=getDepartamento($id_d,$id_m);
              $row_d=_fetch_array($deptoMuni);
              $depto=$row_d['ndepto'];
              $muni =$row_d['nmuni'];
            }
		}
    //$resultVendedor=vendedor($id_vendedor);
    $nombreVendedor=vendedor($id_vendedor);
		$dir_txt=$direccion;
    $txt_dui="DUI: ".$dui;
		$total_final=0;
		$imprimir="";
    $info_factura="";
		//Datos encabezado factura
		$info_factura.= chr(27).chr(54); //spanish latin print chars
    $info_factura.= chr(27).chr(50); //6 lineas  por  pulgada
    for($s=0;$s<$marg_sup;$s++){
      $info_factura.="\n";
    }
    $info_factura.=$align->addspleft("",$h1[0]).$align->addspright($fecha_fact,$h1[1]);
    for($a=0;$a<3;$a++){
      $info_factura.="\n";
    }
    if($extra_nombre!=""){
      $nombreapecte=$nombreapecte." (".$extra_nombre.")";
    }
    $info_factura.=$align->addspright("",$h2[0]).$align->addspright($nombreapecte,$h2[1])."\n";
    $info_factura.=$align->addspleft("",$h3[0]).$align->addspright($dir_txt,$h3[1])."\n\n";
		$info_factura.=$align->addspleft("",$h4[0]).$align->addspright($girocte,$h4[1])."\n";// giro
		$info_factura.=$align->addspleft("",$h5[0]).$align->addspright($depto,$h5[1]);
    $info_factura.=$align->addspleft("",$h5[2]).$align->addspright($nitcte,$h5[3]);
    $info_factura.=$align->addspleft("",$h5[4]).$align->addspright($nrccte,$h5[5])."\n\n";//nit
    $info_factura.=$align->addspleft("",$h6[0]).$align->addspleft($vtacta,$h6[1])."\n";// condiciones operacion y venta a cuenta: CREDITO o CONTADO
    $info_factura.=$align->addspleft("",$h7[0]).$align->addspright($txt_dui,$h7[1])."\n\n";//nota remision ant. y fecha nota remision
    $info_factura.=$align->addspleft("",$h8[0]).$align->addspright($nombreVendedor,$h8[1]).$align->addspleft(" ",$h8[2]).$align->addspright($codigocliente,$h8[3])."\n";//vendedor y codcliente
	  // $info_factura.=$giro_cte1."\n";
		for($p=0;$p<$marg_body;$p++){
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
		for($i=0;$i<$nrows_fact_det;$i++){
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
      if($bonificacion>0){
        $bonificacion  =  $bonificacion / $unidad;
      }
			//linea a linea
			$descripcion1=substr($descripcion,0,$cb_arr[3]).", ".substr($descpresenta,0,10)." ".substr($descpre,0,10);
      $lendesc=$cb_arr[3]+10+10;
			$descrip=$align->texto_espacios($descripcion1,$lendesc);
			$subt=$precio_venta*$cantidad;
      $subt_bonifica=$precio_venta*$bonificacion;
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
      $subtotal_bonifica=sprintf("%.4f",$subt_bonifica);
			$total_final=$total_final+$subtotal;

      $cantprint=$cantidad-$bonificacion;
      if ($cantprint<0){
        $cantprint=0;
      }
			if ($exento==0){
				$e_g="G";
				$precio_sin_iva0 =$row_fact_det['precio_venta']/(1+($iva));
				$precio_sin_iva =round($row_fact_det['precio_venta']/(1+($iva)),4);
        //$precio_sin_iva =round($row_fact_det['precio_venta'],4);
				$subt_sin_iva=round($precio_sin_iva*$cantidad,4);
        $subt_boni_sin_iva=round($precio_sin_iva * $bonificacion,4);
				$subt_gravado=round($subt_sin_iva,4);
				$total_gravado=$subt_sin_iva+$total_gravado;
			}
			else{
				$e_g="E";
				$precio_sin_iva =round($row_fact_det['precio_venta'],4);
				$precio_sin_iva0 =$row_fact_det['precio_venta'];
				$subt_sin_iva=$precio_sin_iva0*$cantidad;
        $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion,4);
				$subt_exento=sprintf("%.4f",$subt_sin_iva);
				$total_exento=$subt_sin_iva+$total_exento;
			}
      $precio_sin_iva_print=round($precio_sin_iva,4);
			$subt_sin_iva_print=round($subt_sin_iva,4);
      $psiva=number_format($precio_sin_iva_print,4);
      $ssiva=number_format($subt_sin_iva_print,4);
      $info_factura.=$align->addspright("",$cb_arr[0]); //margen inicial
      $info_factura.=$align->addspleft($cantprint,$cb_arr[1])." "; //cantidad
      $info_factura.=$align->addspright(" ",$cb_arr[2]);
      $info_factura.=$align->addspright($descrip,$lendesc); //descripcion
      if ($bonificacion>0){
        $info_factura.=$align->addspleft($bonificacion,$cb_arr[4]); //bonificacion
      }else{
        $info_factura.=$align->addspleft(" ",$cb_arr[4]); //bonificacion
      }

      $info_factura.=$align->addspleft($psiva,$cb_arr[5]);  //precio
      $info_factura.=$align->addspleft("",$cb_arr[6]);  //vta. no sujetas
      $info_factura.=$align->addspleft("",$cb_arr[7]);  //vta. exenta
      $info_factura.=$align->addspleft($ssiva,$cb_arr[8])."\n";
      //calulo totales bonificacion
      $total_bonifica+=$subt_boni_sin_iva;
			$cuantos=$cuantos+1;
		}
    //calculos para impresion, Para corregir calculo de iva y retencion 09 dic 2021
    $total_value_gravado=sprintf("%.4f",$total_gravado); //1) aparece como sumas en CCF
    $total_bonificacion=sprintf("%.4f",$total_bonifica); //2) aparece como Desc. Bonificacion en CCF
    $subtotal_grav_sinboni=$total_gravado - $total_bonifica ;
    $calc_iva=round(($subtotal_grav_sinboni*$iva)  ,4); //3) calculo  IVA En  CCF
    $total_iva_format=sprintf("%.4f",$calc_iva); //3) formateo IVA aparece como  IVA En  CCF
    //   $subt_boni_iva=round($subtotal_grav_sinboni ,4) // originlmete se habia dejado asi pero no se escribia lasuma de IVA en CCF;
    $subt_boni_iva=sprintf("%.2f",round(($subtotal_grav_sinboni+$calc_iva) ,2));  //4)  aparece como  Sub-Total En  CCF
    $retencion=sprintf("%.4f",$retencion);
    //$retencion --> //5)  aparece como RETENCION en CCF (Valor guardado al crear CCF)
    // $sujetas no aplicado sujetas, 6)  aparece como Ventas no sujetas en CCF
    // $exentas no aplicado exentas, 7)  aparece como Ventas exentas en CCF
    $total_exento_sinboni=$total_exento - $total_bonifica;
    $total_value_exento=sprintf("%.4f",$total_exento_sinboni);  //7) aparece como Ventas exentas en CCF
    $total_fin=round(($subtotal_grav_sinboni - $retencion + $calc_iva),2);
    $total_fin_format=sprintf("%.2f",$total_fin); //8)  aparece como TOTAL en CCF

  	list($entero,$decimal)=explode('.',  $total_fin_format);
  	$enteros_txt=num2letras($entero);
  	if($entero=='100' && $decimal=='00'){
  		$enteros_txt="CIEN";
  	}
  	if(strlen($decimal)==1){
  		$decimales_txt=$decimal."0";
  	}
  	else{
  		$decimales_txt=$decimal;
  	}
  	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";


  	//totales y n lineas
  	$lineas_faltantes=$lines_body - $cuantos+1;
    if ($lineas_faltantes>0){
    	for($j=0;$j<$lineas_faltantes;$j++){
    		$info_factura.= "\n";
    	}
    }
    $info_factura.= chr(27).chr(50);  //espacio entre lineas 6 x pulgada
    if ($marg_foot>0){
      for($k=0;$k<$marg_foot;$k++){
        $info_factura.= "\n";
      }
    }
  	//generar 2 lineas del texto del total de la factura
  	$total_txt0 =$align->wordwrap1($cadena_salida_txt,$f1[1],2);
  	$concepto_print="";
  	$tmplinea = array();
  	$ln=0;
  	foreach($total_txt0 as $total_txt1){
  		$tmplinea[]=$align->addspright($total_txt1,$f1[1]);
  		$ln=$ln+1;
  	}

    //imprime  total gravado y total texto linea 1
  	$info_factura.=$align->addspleft(" ",$f1[0]).$align->addspright($tmplinea[0],$f1[1]).$align->addspright("",$f1[2]).$align->addspleft($total_value_gravado,$f1[3])."\n\n";
  	// imprime total en texto linea 2  y total de bonificacion
    if($ln>1){
       // imprime totales linea 2  en letras y  Subtotal numero
      $info_factura.=$align->addspleft(" ",$f2[0]).$align->addspright($tmplinea[1],$f2[1]).$align->addspright("",$f2[2]).$align->addspleft($total_bonificacion,$f2[3])."\n\n";
    }
    else{
       // imprime solo Subtotal
      $info_factura.=$align->addspleft(" ",$f2[0]).$align->addspright(" ",$f2[1]).$align->addspright("",$f2[2]).$align->addspleft($total_bonificacion,$f2[3])."\n\n";
    }

    $info_factura.=$align->addspleft(" ",$f3[0]).$align->addspright(" ",$f3[1]).$align->addspright(" ",$f3[2]).$align->addspleft($total_iva_format,$f3[3])."\n\n"; //calculo IVA
    $info_factura.=$align->addspleft(" ",$f4[0]).$align->addspright(" ",$f4[1]).$align->addspright(" ",$f4[2]).$align->addspleft($subt_boni_iva,$f4[3])."\n\n"; //sums - descuento bonifica + IVA
    if($retencion>0)
      $info_factura.=$align->addspleft(" ",$f5[0]).$align->addspright(" ",$f5[1]).$align->addspright(" ",$f5[2]).$align->addspleft($retencion,$f5[3])."\n\n"; //retencion de IVA
    else
      $info_factura.=$align->addspleft(" ",$f5[0]).$align->addspright(" ",$f5[1]).$align->addspright(" ",$f5[2]).$align->addspleft(" ",$f5[3])."\n\n"; //retencion de IVA , si no tiene nada que imprimir

    for($j=0;$j<3;$j++){
      $info_factura.= "\n";
    }
    $info_factura.=$align->addspleft(" ",$f8[0]).$align->addspright(" ",$f8[1]).$align->addspright(" ",$f8[2]).$align->addspleft(  $total_fin_format,$f8[3])."\n"; //subtotal descuento bonifica + IVA
  	$info_factura.="\n";
  	// retornar valor generado en funcion
  	return ($info_factura);
  }
}
function print_ncr2($id_factura){
  $align=new AlignMarginText();
  $val=getMargins('NCR');
  // posiciones y columnas
  $marg_sup   = $val['marg_sup'];
  $h1         = explode(',',$val['h1']);
  $h2         = explode(',',$val['h2']);
  $h3         = explode(',',$val['h3']);
  $h4         = explode(',',$val['h4']);
  $h5         = explode(',',$val['h5']);
  $h6         = explode(',',$val['h6']);
  $h7         = explode(',',$val['h7']);
  $h8         = explode(',',$val['h8']);
  $f1         = explode(',',$val['f1']);
  $f2         = explode(',',$val['f2']);
  $f3         = explode(',',$val['f3']);
  $f4         = explode(',',$val['f4']);
  $f5         = explode(',',$val['f5']);
  $f6         = explode(',',$val['f6']);
  $f7         = explode(',',$val['f7']);
  $f8         = explode(',',$val['f8']);
  $cb_arr     = explode(',',$val['col_body_arr']);
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
	if($nrows_fact>0){
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
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
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
            if(isset($id_d) && isset($id_m)){
              $deptoMuni=getDepartamento($id_d,$id_m);
              $row_d=_fetch_array($deptoMuni);
              $depto=$row_d['ndepto'];
              $muni =$row_d['nmuni'];
            }
		}

    $nombreVendedor=vendedor($id_vendedor);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
    $info_factura="";
		//Datos encabezado factura
		$info_factura.= chr(27).chr(54); //spanish latin print chars
    $info_factura.= chr(27).chr(50); //6 lineas  por  pulgada
    for($s=0;$s<$marg_sup;$s++){
      $info_factura.="\n";
    }
    list($dd,$mm,$aa)=explode("-",$fecha_fact);
    $info_factura.= $align->addspleft(" ",$h1[0]).$align->addspcent($dd,$h1[1]).$align->addspcent($mm,$h1[2]).$align->addspcent($aa,$h1[2])."\n\n";
    $info_factura.=$align->addspright(" ",$h2[0]).$align->addspright($nombreapecte,$h2[1])."\n";
    $info_factura.=$align->addspleft(" ",$h3[0]).$align->addspright($dir_txt,$h3[1]);
    $info_factura.=$align->addspleft(" ",$h3[2]).$align->addspright($depto,$h3[3])."\n\n";
		$info_factura.=$align->addspleft(" ",$h4[0]).$align->addspright($nitcte,$h4[1]);//nit
    $info_factura.=$align->addspleft(" ",$h4[2]).$align->addspright($girocte,$h4[3]); //giro
    $info_factura.=$align->addspleft(" ",$h4[4]).$align->addspright($nrccte,$h4[5])."\n\n";//nrc
    $info_factura.=$align->addspleft(" ",$h6[0]).$align->addspright($fecha_emision_prev." CCF: ".$num_fact_impresa,$h6[1])."\n";//id_fact_emitido


		for($p=0;$p<$marg_body;$p++){
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
    if($tipo_dev==0){
      //$concepto_dev;
      //$descripcion1=substr($concepto_dev,0,$cb_arr[3]);
      //generar 2 lineas del texto del total de la factura
      $lendesc= strlen ($concepto_dev);
      $descripcion1= explode("\n",$concepto_dev);

      $concepto_print="";
      $lineadesc = array();

      foreach($descripcion1 as $desc1){
        $lineadesc[]=$align->texto_espacios($desc1,$cb_arr[3]);
        $lin=$lin+1;
          $cuantos+=1;
      }

      $info_factura.=$align->addspright(" ",$cb_arr[0]); //margen inicial
      $info_factura.=$align->addspleft("1",$cb_arr[1]); //cantidad
      $info_factura.=$align->addspleft(" ",$cb_arr[2]); //MARG
      $info_factura.=$align->addspright($lineadesc[0],$cb_arr[3]); //descripcion

      $info_factura.=$align->addspleft($monto_dev,$cb_arr[4]);  //precio
      $info_factura.=$align->addspleft("",$cb_arr[5]);  //vta. no sujetas
      $info_factura.=$align->addspleft("",$cb_arr[6]);  //vta. exenta
      $info_factura.=$align->addspleft($monto_dev,$cb_arr[7])."\n";
      for($k=1;$k<$lin;$k++){
        $info_factura.=$align->addspright(" ",$cb_arr[0]); //margen inicial
        $info_factura.=$align->addspleft(" ",$cb_arr[1]); //cantidad
        $info_factura.=$align->addspleft(" ",$cb_arr[2]); //MARG
        $info_factura.=$align->addspright($lineadesc[$k],$cb_arr[3])."\n"; //descripcion
        //$cuantos+=1;
      }
      $total_gravado=$monto_dev;

    }else{
		for($i=0;$i<$nrows_fact_det;$i++){
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
      if($bonificacion>0){
        $bonificacion  =  $bonificacion / $unidad;
        $cant_mas_boni=$cantidad." / ".$bonificacion;
      }
			//linea a linea
			$descripcion1=substr($descripcion,0,$cb_arr[3]).", ".substr($descpresenta,0,10)." ".substr($descpre,0,10);
      $lendesc=$cb_arr[3]+10+10;
			$descrip=$align->texto_espacios($descripcion1,$lendesc);
			$subt=$precio_venta*$cantidad;
      $subt_bonifica=$precio_venta*$bonificacion;
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
      $subtotal_bonifica=sprintf("%.4f",$subt_bonifica);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$precio_sin_iva0 =$row_fact_det['precio_venta']/(1+($iva));
				$precio_sin_iva =round($row_fact_det['precio_venta']/(1+($iva)),4);
				$subt_sin_iva=round($precio_sin_iva0*$cantidad,4);
        $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion,4);
				$subt_gravado=round($subt_sin_iva,4);
				$total_gravado=$subt_sin_iva+$total_gravado;
			}
			else{
				$e_g="E";
				$precio_sin_iva =round($row_fact_det['precio_venta'],4);
				$precio_sin_iva0 =$row_fact_det['precio_venta'];
				$subt_sin_iva=$precio_sin_iva0*$cantidad;
        $subt_boni_sin_iva=round($precio_sin_iva0 * $bonificacion,4);
				$subt_exento=sprintf("%.4f",$subt_sin_iva);
				$total_exento=$subt_sin_iva+$total_exento;
			}
      $precio_sin_iva_print=round($precio_sin_iva,4);
			$subt_sin_iva_print=round($subt_sin_iva,4);
      $psiva=number_format($precio_sin_iva_print,4);
      $ssiva=number_format($subt_sin_iva_print,4);

      $info_factura.=$align->addspright(" ",$cb_arr[0]); //margen inicial
      $info_factura.=$align->addspleft($cant_mas_boni,$cb_arr[1]); //cantidad
      $info_factura.=$align->addspleft(" ",$cb_arr[2]); //MARG
      $info_factura.=$align->addspright($descrip,$lendesc); //descripcion

      $info_factura.=$align->addspleft($psiva,$cb_arr[4]);  //precio
      $info_factura.=$align->addspleft("",$cb_arr[5]);  //vta. no sujetas
      $info_factura.=$align->addspleft("",$cb_arr[6]);  //vta. exenta
      $info_factura.=$align->addspleft($ssiva,$cb_arr[7])."\n";
      //calulo totales bonificacion
      $total_bonifica+=$subt_boni_sin_iva;
			$cuantos=$cuantos+1;
		}

    }
    $total_value_gravado=sprintf("%.4f",$total_gravado);
    //total de bonificaciones
    $total_bonifica=0;
    $total_bonificacion=sprintf("%.4f",$total_bonifica);
    //restar total gravado - bonificacion  para luego scar IVA
    $subtotal_grav_sinboni= $total_gravado - $total_bonifica ;

    if ($tipo_doc=="CCF"){

       $calc_iva = round(( $subtotal_grav_sinboni* $iva),4);
    }else{
       $calc_iva = 0.0;
    }
  	$total_iva_format=sprintf("%.4f",$calc_iva);
    $sub_total=sprintf("%.4f",round($subtotal_grav_sinboni+$calc_iva,4));

    $total_fin=round($sub_total - $retencion ,4);
    $total_fin_format=sprintf("%.4f",$total_fin);
  	$total_final_format=sprintf("%.4f",$total_final);

  	$total_value_exento=sprintf("%.4f",$total_exento);
    $subtotal_exento=round($total_exento,4);
    $total_final_todos=round($sub_total + $subtotal_exento  - $retencion ,4);
    $total_final_print=sprintf("%.4f",$total_final_todos);

    list($entero,$decimal)=explode('.', $total_final_print);
  	$enteros_txt=num2letras($entero);
  	if($entero=='100' && $decimal=='00'){
  		$enteros_txt="CIEN";
  	}
  	if(strlen($decimal)==1){
  		$decimales_txt=$decimal."0";
  	}
  	else{
  		$decimales_txt=$decimal;
  	}
  	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
  	$total_value_fin=sprintf("%.4f",$total_fin);


  	//totales y n lineas
  	$lineas_faltantes=$lines_body - $cuantos+1;
    if ($lineas_faltantes>0){
    	for($j=0;$j<$lineas_faltantes;$j++){
    		$info_factura.= "\n";
    	}
    }
    $info_factura.= chr(27).chr(50);  //espacio entre lineas 6 x pulgada
    if ($marg_foot>0){
      for($j=0;$j<$marg_foot;$j++){
        $info_factura.= "\n";
      }
    }

  	//generar 2 lineas del texto del total de la factura
  	$total_txt0 =$align->wordwrap1($cadena_salida_txt,$f1[1],2);
  	$concepto_print="";
  	$tmplinea = array();
  	$ln=0;
  	foreach($total_txt0 as $total_txt1){
  		$tmplinea[]=$align->addspright($total_txt1,$f1[1]);
  		$ln=$ln+1;
  	}

    //imprime  total gravado y total texto linea 1
  	$info_factura.=$align->addspleft("",$f1[0]).$align->addspright(" ",$f1[1]).$align->addspright("",$f1[2]).$align->addspleft( $total_value_gravado,$f1[3])."\n\n";
  	// imprime total en texto linea 1
    $info_factura.=$align->addspleft("",$f2[0]).$align->addspright($tmplinea[0],$f2[1]).$align->addspright("",$f2[2]).$align->addspleft($total_iva_format,$f2[3])."\n\n";

    if($ln>1){
        $info_factura.=$align->addspleft("",$f3[0]).$align->addspright($tmplinea[0],$f3[1]).$align->addspright("",$f3[2]).$align->addspleft($sub_total,$f3[3])."\n"; //calculo IVA
    }else{
      $info_factura.=$align->addspleft("",$f3[0]).$align->addspright("",$f3[1]).$align->addspright("",$f2[2]).$align->addspleft($sub_total,$f3[3])."\n";
    }
    if($retencion>0)
      $info_factura.=$align->addspleft("",$f4[0]).$align->addspright("",$f4[1]).$align->addspright("",$f4[2]).$align->addspleft($retencion,$f4[3])."\n"; //ret
    else
    $info_factura.="\n";

    for($j=0;$j<4;$j++){
      $info_factura.= "\n";
    }
    $info_factura.=$align->addspleft("",$f7[0]).$align->addspright("",$f7[1]).$align->addspright("",$f7[2]).$align->addspleft( $total_final_print,$f7[3])."\n"; //total final
  	// retornar valor generado en funcion
  	return ($info_factura);
  }
}

function print_ticket_dev($id_factura){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
	$datos_empresa=datos_empresa();
	$field= json_decode($datos_empresa, true);
	$nite=$field['nit'];
	$nrce=$field['nrc'];
	$empresa1=$field['empresa'];
	$razonsocial1=$field['razonsocial'];
	$giro1=$field['giro'];
	//Sucursal
	$nombre_sucursal1=datos_sucursal($id_sucursal);
	//inicio datos
	$info_factura="";
	$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|".$nite."|".$nrce."|";
	//Obtener informacion de tabla Factura
	$result_fact=datos_factura($id_factura);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$id_vendedor=$row_fact['id_empleado'];
		$fecha=$row_fact['fecha'];
		$hora=$row_fact['hora'];
		$caja=$row_fact['caja'];
		$turno=$row_fact['turno'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];
		$descuent=$row_fact['descuento'];
		$porcentaje=$row_fact['porcentaje'];
		$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
		$dats_caja = _fetch_array($sql_caja);
		$fehca = ED($dats_caja["fecha"]);
		$resolucion = $dats_caja["resolucion"];
		$serie = $dats_caja["serie"];
		$desde = $dats_caja["desde"];
		$hasta = $dats_caja["hasta"];
		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado usuario y vendedor
		$result_emp= datos_empleado($id_usuario,$id_vendedor);
		list($empleado,$vendedor)=explode('|',$result_emp);
		//Datos del Cliente
		$result=datos_clientes($id_cliente);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$sp2=espacios_izq(" ",12);
		$esp_init=espacios_izq(" ",1);
		$esp_precios=espacios_izq(" ",10);
		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",23);
		$nombre_ape=$align->texto_espacios($nombres,32);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;
		//Datos del cliente
		/*$info_factura.=$esp_init.$empresa1."\n";
		$info_factura.=$esp_init.$razonsocial1."\n";
		$giros = explode(";", $giro1);
		for ($ni = 0; $ni < (count($giros)); $ni++)
		{
			$info_factura.=$esp_init.trim($giros[$ni])."\n";
		}*/
		$info_factura.=$esp_init."NIT :  ".$nite." NRC :".$nrce."\n";
		$info_factura.=$esp_init."RESOLUCION:  ".$resolucion."\n";
		$info_factura.=$esp_init."DEL ".$desde." AL ".$hasta."\n";
		$info_factura.=$esp_init."SERIE ".$serie."\n";
		$info_factura.=$esp_init."FECHA RESOLUCION ".$fehca."\n";
		$info_factura.=$esp_init."DEVOLUCION # ".$num_fact."|";
		$info_factura.=$esp_init."FECHA: ".$fecha_fact." ".hora($hora)."\n";
		$info_factura.=$esp_init."VENDEDOR: ".$vendedor."\n";
		$info_factura.=$esp_init."CAJA : ".$caja. "  TURNO: ".$turno."\n";
		$info_factura.=$esp_init."CLIENTE: ".$nombre_ape."\n|";
		$info_factura.="DESCRIPCION  CANT.  P. UNIT    SUBTOT.\n|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$result_fact_det=datos_fact_det($id_factura);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =$row_fact_det['descripcion'];
			//descripcion presentacion
			$id_presentacion =$row_fact_det['id_presentacion'];
			$descpre =$row_fact_det['descpre'];
			$descpresenta =$row_fact_det['descripcion_pr'];
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$descuento =$row_fact_det['descuento'];
			$subt=$row_fact_det['subtotal'];
			//$subt = $subt - $descuento;
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
			//presentacion producto
			$sql_uus=_fetch_array(_query("SELECT pp.precio, pp.unidad, pp.descripcion, p.nombre  FROM presentacion_producto as pp, presentacion as p WHERE pp.presentacion=p.id_presentacion AND pp.id_presentacion=$id_presentacion"));
			$precio_p=$sql_uus['precio'];
			$unidad_w=$sql_uus['unidad'];
			$desc_pr=$sql_uus['descripcion'];
			$prese_pr=$sql_uus['nombre'];
			$desc_pr_fin = $prese_pr."($desc_pr)";
			$cantidad=$cantidad/$unidad_w;
			//linea a linea
			$descrip=$align->texto_espacios($descripcion,22);
			$descpresenta1=$align->texto_espacios($descpre,7);
			$descpre1=$align->texto_espacios($descpre,30);
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subt);
				$total_gravado=$subt_gravado+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subt);
				$total_exento=$subt_exento+$total_exento;
			}
			$esp_init=len_num($cantidad,8);
			$esp_col2=len_num($precio_unit,6);
			$esp_col3=len_num($subtotal,7);
			$esp_col4=len_num($descuento,11);
			$info_factura.=$descrip.$esp_init.$cantidad."   ".$esp_col2.$precio_unit.$esp_col3.$subtotal."".$e_g."\n";
			$info_factura.="  ".$desc_pr_fin."\n";
			//$info_factura.="PRESENT: ".$descpre1."\n";
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}
	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	//$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);
	$esp_totales=len_num($total_value,8);
	$esp_init2=espacios_izq(" ",25);
	$tt_fin = $total_value_fin - $descuent;
	//$esp_totales=espacios_izq(" ",$sp3);
	$esp_d1=len_num($total_value_gravado,3);
	$esp_d2=len_num($total_value_exento,3);
	$esp_d3=len_num($total_value_fin,3);
	$vals = 3;
	if(strlen($descuent)>3)
	{
		$vals = 2;
	}
	$esp_d4=len_num($descuent,$vals);
	$vals = 3;
	if(strlen($porcentaje)>3)
	{
		$vals = 2;
	}
	$esp_d6=len_num($porcentaje,$vals);
	$esp_d5=len_num($tt_fin,2);
	$info_factura.="|TOTAL GRAVADO    ".$esp_totales."  $ ".$esp_d1.$total_value_gravado."\n";
	$info_factura.="TOTAL EXENTO     ".$esp_totales."  $ ".$esp_d2.$total_value_exento."\n";
	$info_factura.="TOTAL            ".$esp_totales."  $ ".$esp_d3.$total_value_fin."\n";
	$info_factura.="DESCUENTO       ".$esp_totales."".$esp_d6.$porcentaje."%\n";
	$info_factura.="TOTAL DESCUENTO  ".$esp_totales."  $ ".$esp_d4.sprintf("%.4f",$descuent)."\n";
	$info_factura.="DEVUELTO         ".$esp_totales."  $".str_pad(number_format($tt_fin,2,".",""),8," ",STR_PAD_LEFT)."\n";
	$info_factura.="|".$cadena_salida_txt."\n";
	$info_factura.="|"."VENDEDOR: ".$vendedor;
	//$esp=espacios_izq(" ",30);PRODUCTIS
	// retornar valor generado en funcion
	return ($info_factura);
}
function print_fact2($id_fact,$tipo_id){
	$id_sucursal=$_SESSION['id_sucursal'];
	$id_factura=$id_fact;
	$tipo_id=$tipo_id;
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	//Sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	$nombre_sucursal1=$align->texto_espacios($nombre_sucursal,30);
	$empresa1=$align->texto_espacios($empresa,30);
	$razonsocial1=$align->texto_espacios($razonsocial,30);
	$giro1=$align->texto_espacios($giro,30);
	//inicio datos
	$info_factura="";
	$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|";
	//Obtener informacion de tabla Factura
	if($tipo_id=='idfact'){
		$id_factura=$id_fact;
		$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	}
	if($tipo_id=='COF'){
		$numero_docx=$id_fact;
		$sql_fact="SELECT * FROM factura WHERE numero_doc='$numero_docx'";
	}
	if($tipo_id=='CCF'){
		$numero_docx=$id_fact."_CCF";
		$sql_fact="SELECT * FROM factura WHERE numero_doc='$numero_docx'";
	}
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];
		//$nombres=$row_user['apellido']." ".$row_user['nombre'];
		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['apellido']." ".$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$base1=7;
		$col0=1;
		$col1=4;
		$col2=3;
		$col3=13;
		$col4=5;
		$sp1=2;
		$sp_prec=15;
		$sp=espacios_izq(" ",$sp1);
		$sp2=espacios_izq(" ",12);
		$esp_init=espacios_izq(" ",5);
		$esp_precios=espacios_izq(" ",$sp_prec);
		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",45);
		$nombre_ape=$align->texto_espacios($nombres,32);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<5;$h++){
			$imprimir.="\n";
		}
		$info_factura.=$imprimir;
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init2.$diaa."       ".$mess."             ".$anio."|";
		$info_factura.=$esp_init."FACTURA CONSUMIDOR # ".$num_fact."|";
		//Datos del cliente
		$info_factura.=$esp_init."            ".$nombre_ape."|";
		$info_factura.=$esp_init.$direccion."|";
		$info_factura.=$esp_init.$dui."|";
		$info_factura.=$esp_init.$nit."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,factura_detalle.*
		FROM factura_detalle JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		WHERE  factura_detalle.id_factura='$id_factura' AND  factura_detalle.tipo_prod_serv='PRODUCTO'
		UNION ALL
		SELECT  servicio.id_servicio, servicio.descripcion,servicio.exento,factura_detalle.*
		FROM factura_detalle JOIN servicio ON factura_detalle.id_prod_serv=servicio.id_servicio
		WHERE  factura_detalle.id_factura='$id_factura' AND  factura_detalle.tipo_prod_serv='SERVICIO'
		";

		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =$row_fact_det['descripcion'];
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv =$row_fact_det['tipo_prod_serv'];

			//linea por linea de productos
			$descrip=$align->texto_espacios($descripcion,33);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.4f",$subt_sin_iva);
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}

      $col2=2;
			$sp1=len_espacios($cantidad,6);
	 		$esp_col1=espacios_izq(" ",$sp1);
	 		$sp2=len_espacios($precio_unit,8);
	 		$esp_col2=espacios_izq(" ",$sp2);
	 		$sp3=len_espacios($subtotal,9);
	 		$esp_col3=espacios_izq(" ",$sp3);
	 		$esp_desc=espacios_izq(" ",5);
  		if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3." ".$subtotal."\n";
  			}
  			if ($exento==0){
					$sp3=$sp3+8;
					$esp_col3=espacios_izq(" ",$sp3);
  				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3." ".$subtotal."\n";
				}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);

	//totales
	$lineas_faltantes=11-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}

	$info_factura.="\n";
	$info_factura.="\n";
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",30);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 30,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",6);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",52);
  $splentot1=len_espacios($total_value_exento,9);
			$esp_lentot1=espacios_izq(" ",$splentot1);
			$splentot2=len_espacios($total_value_gravado,9);
			$esp_lentot2=espacios_izq(" ",$splentot2);

			//$info_factura.=$esp_totales.$esp_lentot1.$total_value_exento.$esp_lentot2.$total_value_gravado."\n";
			$info_factura.=$esp_totales.$esp_lentot1."   ".$esp_lentot2.$total_value_gravado."\n";
			$linea0=strlen(trim($tmplinea[0]));
			$len_desc=(30-$linea0)+15;
			$esp_totales=espacios_izq(" ",$len_desc);
			$info_factura.=$esp_init.$tmplinea[0]."\n";

			if($ln>1){
						$len_desc=55-strlen(trim($tmplinea[1]));
						$esp_totales=espacios_izq(" ",$len_desc);
						$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2.$total_value_gravado."\n";
					}
					else{
						$esp_totales=espacios_izq(" ",62);
						$info_factura.=$esp_totales.$esp_lentot1.$total_value_gravado."\n";
					}
	/*if($ln==1){
	 $info_factura.="\n";
 }*/
	$esp_totales=espacios_izq(" ",62);
	$info_factura.="\n";
	$info_factura.=$esp_totales.$esp_lentot1.$total_value_exento."\n";
	$info_factura.=$esp_totales.$esp_lentot2.$total_final_format."\n";
	// retornar valor generado en funcion
	return ($info_factura);

}
function print_fact0($id_factura,$tipo_id,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';
	$info_factura="";
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$nombrex = $row_fact['nombre'];
		$direccionx = $row_fact['direccion'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];

		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=" ".$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$esp_init=espacios_izq(" ",10);
		$esp_init2=espacios_izq(" ",53);
		$nombre_ape=$nombrex;//$nombreapecte;
		$dir_txt=$align->texto_espacios($direccionx,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<9;$h++){
			$imprimir.="\n";
		}
		$info_factura.=$imprimir;
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=str_pad("",62," ",STR_PAD_LEFT).str_pad($diaa,11," ",STR_PAD_BOTH).str_pad($mess,16," ",STR_PAD_BOTH).str_pad($anio,11," ",STR_PAD_BOTH)."|";
		//Datos del cliente
		$info_factura.="\n".str_pad("",17," ",STR_PAD_LEFT).$nombre_ape."|";
		$info_factura.=str_pad("",20," ",STR_PAD_LEFT).$direccionx."|";
		for($h=0;$h<4;$h++){
			$info_factura.="\n";
		}

		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.nombre as desprep,
		 presentacion_producto.descripcion AS descpre,   presentacion_producto.unidad,
		 factura_detalle.*
		 FROM factura_detalle
		 JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		 JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_pp
		 JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
		 WHERE  factura_detalle.id_factura='$id_factura'
		 ";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		$info_factura.="\n";
		//$info_factura.="\n";
		//$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada
		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['desprep']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad']/$row_fact_det['unidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='PRODUCTO';

			//linea por linea de productos
			//$descrip=$align->texto_espacios($descripcion,22);
			//$descpresenta1 =$align->texto_espacios($descpre,7);
			$descripcion1=substr($descripcion,0,22).", ".substr($descpresenta,0,7)." ".substr($descpre,0,10);
			$descrip=$align->texto_espacios($descripcion1,35);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.4f",$subt_sin_iva);
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}
			//$precio_sin_iva_print=sprintf("%.4f",$precio_sin_iva);
      $col2=2;
			$espacios1=espacios_izq(" ",1);
			$espacios2=espacios_izq(" ",2);
			$espacios3=espacios_izq(" ",3);
			$espacios4=espacios_izq(" ",4);
			$espacios5=espacios_izq(" ",5);
			$espacios6=espacios_izq(" ",6);
			$espacios7=espacios_izq(" ",7);
			$espacios10=espacios_izq(" ",10);
			$sp1=len_espacios($cantidad,6);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_unit,8);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subtotal,9);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",5);
			//imprimir productos
  		if ($exento==1){
				$info_factura.=str_pad("",4," ",STR_PAD_LEFT).str_pad($cantidad,8," ",STR_PAD_BOTH).str_pad("",3," ",STR_PAD_BOTH).str_pad($descrip,48," ",STR_PAD_RIGHT).str_pad($precio_unit,13," ",STR_PAD_LEFT).str_pad("",10," ",STR_PAD_LEFT).str_pad($subtotal,14," ",STR_PAD_LEFT)."\n";
			}
  		if ($exento==0){
					$info_factura.=str_pad("",4," ",STR_PAD_LEFT).str_pad($cantidad,8," ",STR_PAD_BOTH).str_pad("",3," ",STR_PAD_BOTH).str_pad($descrip,48," ",STR_PAD_RIGHT).str_pad($precio_unit,13," ",STR_PAD_LEFT).str_pad("",10," ",STR_PAD_LEFT).str_pad($subtotal,14," ",STR_PAD_LEFT)."\n";
				}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= "".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);

	//totales
	$lineas_faltantes=12-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
	$info_factura.= chr(27).chr(51)."1";
  //$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
 	//$info_factura.="\n";
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",30);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =$cadena_salida_txt;
	$tmplinea = array();
	$ln=0;

	$info_factura.=str_pad($total_value_gravado,99," ",STR_PAD_LEFT)."\n";//total gravado

	//primera linea  e iva retenido
	$ygg=0;
	if(ceil(strlen($total_txt0))/2 > 41)
	{
			$nom = divtextlin($total_txt0, 41);
			foreach ($nom as $nnon)
			{
				if ($ygg==1) {
					$info_factura.=str_pad("",12," ",STR_PAD_LEFT).str_pad($nnon,41," ",STR_PAD_RIGHT)."\n";
				}
				$ygg++;
			}

	}
	else
	{
		$info_factura.=str_pad("",12," ",STR_PAD_LEFT).str_pad($total_txt0,41," ",STR_PAD_RIGHT)."\n";
	}

	//primera segunda linea  y subtotal
	$ygg=0;
	if(ceil(strlen($total_txt0))/2 > 41)
	{
			$nom = divtextlin($total_txt0, 41);
			foreach ($nom as $nnon)
			{
				if ($ygg==2) {
					$info_factura.=str_pad("",12," ",STR_PAD_LEFT).str_pad($nnon,41," ",STR_PAD_RIGHT).str_pad($total_value_gravado,46," ",STR_PAD_LEFT)."\n";
				}
				$ygg++;
			}

	}
	else
	{
		$info_factura.=str_pad("",12," ",STR_PAD_LEFT).str_pad("",41," ",STR_PAD_RIGHT).str_pad($total_value_gravado,46," ",STR_PAD_LEFT)."\n";
	}


	$info_factura.=str_pad("",100," ",STR_PAD_LEFT)."\n";//total no sujetas

	$info_factura.=str_pad($total_value_exento,99," ",STR_PAD_LEFT)."\n";//total no sujetas
	$info_factura.=str_pad($total_value_fin,99," ",STR_PAD_LEFT)."\n";//total no sujetas


	$info_factura.="|".$total_final_format."\n";
	// retornar valor generado en funcion
	return ($info_factura);

}
function print_envio($id_factura,$tipo_id,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	//Sucursal
	$empresa1=$align->texto_espacios($empresa,30);
	$razonsocial1=$align->texto_espacios($razonsocial,30);
	$giro1=$align->texto_espacios($giro,30);
	//inicio datos
	$info_factura="";
	//Obtener informacion de tabla Factura
	//fecha  arriba 1 linea, direccion 3 esp der , descripcion 2 esp der , qitar prec unit
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];
		//$nombres=$row_user['apellido']." ".$row_user['nombre'];
		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['apellido']." ".$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base

		$esp_init=espacios_izq(" ",12);
		$esp_init2=espacios_izq(" ",76);
		$nombre_ape=$align->texto_espacios($nombres,32);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;
		for($h=0;$h<3;$h++){
			$info_factura.="\n";
		}
		$nombre_ape=$align->texto_espacios($nombreapecte,40);

		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$esp_init2=espacios_izq(" ",60);
		$info_factura.=$esp_init2.$diaa."   ".$mess."   ".$anio."|";
		//$info_factura.="\n";
		//Datos del cliente
		$info_factura.=$esp_init."   ".$nombre_ape."|";
			$info_factura.="\n";
		$info_factura.=$esp_init."   ".$direccion."|";
		$info_factura.=$esp_init2.$dui."|";
		$info_factura.=$esp_init2.$nit."|";

		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.descripcion_pr,
		 presentacion_producto.descripcion AS descpre,
		 factura_detalle.*
		 FROM factura_detalle
		 JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		 JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_pp
		 JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
		 WHERE  factura_detalle.id_factura='$id_factura'
		 ";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		//$info_factura.="\n";
		for ($i = 0; $i<3; $i++) {
		  $info_factura.= chr(10); //Line Feed
		}
		$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='PRODUCTO';
      //agregar query para presentaciones y agregarlo a descripcion
			//linea por linea de productos
			//$descrip=$align->texto_espacios($descripcion,60);
			$descripcion1=substr($descpresenta,0,8).", ".substr($descripcion,0,30)." ".substr($descpre,0,15);
			$descrip=$align->texto_espacios($descripcion1,50);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.4f",$subt_sin_iva);
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}

      $col2=2;
			$esp1=len_espacios($cantidad,6);
			$esp_col1=espacios_izq(" ",$esp1);
			$esp2=len_espacios($precio_venta,8);
			$esp_col2=espacios_izq(" ",$esp2);
			$esp3=len_espacios($subtotal,8);
			$esp_col3=espacios_izq(" ",$esp3);
			$esp_desc=espacios_izq(" ",2);
			$sp1=espacios_izq(" ",1);
			$sp2=espacios_izq(" ",5);
      		$sp3=espacios_izq(" ",3);
			$sp4=espacios_izq(" ",2);
			$sp5=espacios_izq(" ",5);
			$info_factura.=$sp1.$esp_col1.$cantidad.$sp2.$descrip.$sp1.$esp_col3.$subtotal."\n";

			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= "    ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);

	//totales
	$lineas_faltantes=19-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
  $info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada

	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",50);
  $splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);
  //imprimir totales
	$linea0=strlen(trim($tmplinea[0]));
	$len_desc=40-$linea0;
	//$esp_totales=espacios_izq(" ",$len_desc);
	$esp_desc=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",12);
	$espacios=espacios_izq(" ",10);
	$info_factura.="\n";
	$info_factura.="\n";
	$splentot2=len_espacios($total_final_format,10);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$info_factura.=$esp_init.$tmplinea[0].$esp_desc.$espacios.$esp_lentot2.$total_final_format."\n";
	if($ln>1){
				$esp_init=espacios_izq(" ",6);
						$len_desc=76-strlen(trim($tmplinea[1]));
						$esp_totales=espacios_izq(" ",$len_desc);
						$info_factura.=$esp_init.$tmplinea[1]." \n";
						for($x=0;$x<2;$x++){
						 $info_factura.="\n";
					 }
				 }
				 $info_factura.="\n";
				 $esp_totales_g=espacios_izq(" ",83);
				 $esp_totales=espacios_izq(" ",83);
				 for($x=0;$x<1;$x++){
	 			 $info_factura.="\n";
 		 }
	// retornar valor generado en funcion
		return ($info_factura);

}
function print_fact_dia($id_fact,$tipo_id){
	$id_sucursal=$_SESSION['id_sucursal'];
	$id_factura=$id_fact;
	$tipo_id=$tipo_id;
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	//Sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	$nombre_sucursal1=$align->texto_espacios($nombre_sucursal,30);
	$empresa1=$align->texto_espacios($empresa,30);
	$razonsocial1=$align->texto_espacios($razonsocial,30);
	$giro1=$align->texto_espacios($giro,30);
	//inicio datos
	$info_factura="";
	$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|";

	$sql_fact="SELECT * FROM factura_dia WHERE id_factura_dia='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura_dia'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$total=$row_fact['total'];
		$num_fact=$id_factura;
		$numfact=espacios_izq($num_fact,10);

		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['apellido']." ".$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$base1=7;
		$col0=1;
		$col1=4;
		$col2=3;
		$col3=13;
		$col4=5;
		$sp1=2;
		$sp_prec=15;
		$sp=espacios_izq(" ",$sp1);
		$sp2=espacios_izq(" ",12);
		$esp_init=espacios_izq(" ",12);
		$esp_precios=espacios_izq(" ",$sp_prec);
		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",70);
		$nombre_ape=$align->texto_espacios($nombres,32);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<8;$h++){
			$imprimir.="\n";
		}
		$info_factura.=$imprimir;
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init2.$diaa."       ".$mess."           ".$anio."|";
		$info_factura.=$esp_init."FACTURA CONSUMIDOR DIARIA# ".$num_fact."|";
		//Datos del cliente
		$info_factura.=$esp_init."   ".$nombre_ape."|";
		$info_factura.=$esp_init.$direccion."|";
		$info_factura.=$esp_init.$dui."|";
		$info_factura.=$esp_init.$nit."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,factura_detalle_dia.*
		FROM factura_detalle_dia JOIN producto ON factura_detalle_dia.id_producto=producto.id_producto
		WHERE  factura_detalle_dia.id_factura_dia='$id_factura'
		";

		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		$info_factura.="\n";
		//$info_factura.="\n";
		$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada
		//$info_factura.="\n";
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =$row_fact_det['descripcion'];
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factdet_dia'];
			$id_prod_serv =$row_fact_det['id_producto'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];

			//linea por linea de productos
			$descrip=$align->texto_espacios($descripcion,42);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.4f",$subt_sin_iva);
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}

      $col2=2;
			$sp1=len_espacios($cantidad,7);
			$esp_col1=espacios_izq(" ",$sp1);
			$sp2=len_espacios($precio_sin_iva_print,8);
			$esp_col2=espacios_izq(" ",$sp2+4);
			$sp3=len_espacios($subt_sin_iva_print,10);
			$esp_col3=espacios_izq(" ",$sp3+1);
			$esp_desc=espacios_izq(" ",6);
  		if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2."".$precio_unit.$esp_col3.$subtotal."\n";
  			}
  			if ($exento==0){
					$sp3=$sp3+11;
					$esp_col3=espacios_izq(" ",$sp3);
  				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3.$subtotal."\n";
				}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);

	//totales
	$lineas_faltantes=12-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
  $info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada

	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 40,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",56);
  $splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_value_gravado,12);
	$esp_lentot2=espacios_izq(" ",$splentot2);

  //imprimir totales

	$linea0=strlen(trim($tmplinea[0]));
	$len_desc=72-$linea0;
	$esp_totales=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",10);
	$info_factura.="\n";
	$info_factura.="\n";
	$info_factura.=$esp_init.$tmplinea[0].$esp_totales."  ".$esp_lentot2.$total_value_gravado."\n";
	if($ln>1){
				$esp_init=espacios_izq(" ",6);
						$len_desc=76-strlen(trim($tmplinea[1]));
						$esp_totales=espacios_izq(" ",$len_desc);
						$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2." "."\n";
						for($x=0;$x<2;$x++){
						 $info_factura.="\n";
					 }
	}
	else{
	for($x=0;$x<3;$x++){
	 $info_factura.="\n";
 }
 }
	$esp_totales_g=espacios_izq(" ",83);

  $info_factura.=$esp_totales_g."  ".$esp_lentot2.$total_value_gravado."\n";

	$esp_totales=espacios_izq(" ",83);
	for($x=0;$x<2;$x++){
	 $info_factura.="\n";
 }
	$info_factura.=$esp_totales.$esp_lentot2.$total_final_format."\n";
	// retornar valor generado en funcion
	return ($info_factura);

}
function print_ncr($id_factura,$tipo_id,$nombreapecte,$direccion){

	$id_sucursal=$_SESSION['id_sucursal'];
	$align=new AlignMarginText();
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';
	$info_factura="";
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
    $id_dev = $row_fact['id_dev'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];
    //datos devolucion nota credito

    $sql_dev="SELECT id_factura AS id_fact_emitido,monto,concepto,tipo as tipo_dev
    FROM devoluciones WHERE id_dev='$id_dev'";
    $result_dev= _query($sql_dev);
		$rowdev=_fetch_array($result_dev);
    $id_fact_emitido=$rowdev['id_fact_emitido'];
    $tipo_dev=$rowdev['tipo_dev'];
    $monto_dev=$rowdev['monto'];
    $concepto_dev=$rowdev['concepto'];
    //Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$nrc=$row1['nrc'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$esp_init=espacios_izq(" ",13);
		$esp_init2=espacios_izq(" ",65);
		$nombre_ape=$align->texto_espacios($nombreapecte,32);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<8;$h++){
			$info_factura.="\n";
		}
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init2.$diaa."   ".$mess."   ".$anio."|";

		//Datos del cliente
		$info_factura.=$esp_init."".$nombre_ape."|";
		$info_factura.="\n";
		$info_factura.=$esp_init.$direccion."|";
		for($k=0;$k<2;$k++){
			$info_factura.="\n";
		}
		$info_factura.=$esp_init2.$nrc."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio

		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.descripcion as descripcion_pr,
		 presentacion_producto.descripcion AS descpre,
		 factura_detalle.*
		 FROM factura_detalle
		 JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		 JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_pp
		 JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
		 WHERE  factura_detalle.id_factura='$id_factura'
		 ";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		for($k=0;$k<3;$k++){
			$info_factura.=chr(10);
		}

		//$info_factura.="\n";
		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='PRODUCTO';

			//linea por linea de productos
			$descripcion1=substr($descpresenta,0,7).", ".substr($descripcion,0,22)." ".substr($descpre,0,10);
			$descrip=$align->texto_espacios($descripcion1,42);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.4f",$subt_sin_iva);
			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}
			//$precio_sin_iva_print=sprintf("%.4f",$precio_sin_iva);
      $col2=2;
			$espacios1=espacios_izq(" ",1);
			$espacios2=espacios_izq(" ",2);
			$espacios3=espacios_izq(" ",3);
			$espacios4=espacios_izq(" ",4);
			$espacios5=espacios_izq(" ",5);
			$sp1=len_espacios($cantidad,5);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_unit,7);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subtotal,10);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",6);
			//imprimir productos
  		if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2."".$precio_unit.$esp_col3.$subtotal."\n";
  			}
  		if ($exento==0){
				//	$sp3=$sp3+11;
				//	$esp_col3=espacios_izq(" ",$sp3);
					$info_factura.=$espacios2.$esp_col1.$cantidad.$espacios3.$descrip.$espacios1.$esp_col3.$precio_unit.$espacios5.$esp_col4.$subtotal."\n";
  			//	$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3.$subtotal."\n";
				}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);

	//totales
	$lineas_faltantes=15-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
  $info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
 //$info_factura.="\n";
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",56);
  $splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_value_gravado,8);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$len_desc=50-strlen(trim($tmplinea[0]));
	$esp_desc=espacios_izq(" ",$len_desc);
  $esp_init=espacios_izq(" ",5);
	$espacios=espacios_izq(" ",16);

  //imprimir totales
	$esp_totales=espacios_izq(" ",71);
  $info_factura.=$esp_init.$tmplinea[0].$esp_desc.$espacios.$esp_lentot2.$total_value_gravado."\n";
	$esp_init=espacios_izq(" ",7);

	if($ln>1){
				$esp_init=espacios_izq(" ",6);
						$len_desc=68-strlen(trim($tmplinea[1]));
						$esp_totales=espacios_izq(" ",$len_desc);
						$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2." "."\n";
						for($x=0;$x<2;$x++){
						 $info_factura.="\n";
					 }
	}
	$info_factura.="\n";
	$esp_totales_g=espacios_izq(" ",71);
  $info_factura.=$esp_totales_g.$esp_lentot2.$total_value_gravado."\n";
	$esp_totales=espacios_izq(" ",71);
	for($x=0;$x<2;$x++){
	 $info_factura.="\n";
 }
 $splentot2=len_espacios($total_final_format,8);
 $esp_lentot2=espacios_izq(" ",$splentot2);
 $info_factura.=$esp_totales.$esp_lentot2.$total_final_format."\n";
 $info_factura.="|".$esp_totales.$esp_lentot2.$total_final_format."\n";
 // retornar valor generado en funcion
	return ($info_factura);
}
function print_vale($id_movimiento){
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
	//$id_sucursal=$row["id_sucursal"];
	if($entrada==1){
		$tipo="INGRESO";
	}
	else{
		$tipo="EGRESO";
	}
	$line1=str_repeat("_",30)."\n";
  $valor= sprintf('%.4f', $valor);
	//Datos
	$col0=1;		$col1=3; 		$col2=3;
	$col3=6;		$col4=5;		$sp1=2;
	$sp_prec=10;
	$sp=espacios_izq(" ",$sp1);
	$sp2=espacios_izq(" ",12);
	$esp_init=espacios_izq(" ",$col0);
	$esp_precios=espacios_izq(" ",$sp_prec);
	$esp_enc2=espacios_izq(" ",3);
	$esp_init2=espacios_izq(" ",23);
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
function print_corte($id_corte){
	include_once "_core.php";
	//EMPRESA
	$id_sucursal=$_SESSION['id_sucursal'];

	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['descripcion'];
	$razonsocial=$row_empresa['razon_social'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];
	//sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	//consulta
	$sql_t=_fetch_array(_query("SELECT controlcaja.id_empleado FROM controlcaja WHERE controlcaja.id_corte=$id_corte"));
	$id_c=$sql_t['id_empleado'];
	$sql="";
	if($id_c<0) {
		# code...
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
	}
	else {
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

	$result_emp= datos_empleado($id_c,$id_c);
	list($al,$nombre_emp)=explode('|',$result_emp);

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
	$caja = $row["caja"];
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
	$fgravado=sprintf('%.4f',  $row["fgravado"]);
	$totalf= sprintf('%.4f', $row["totalf"]);
	$cfexento= sprintf('%.4f', $row["cfexento"]);
	$cfgravado=sprintf('%.4f',  $row["cfgravado"]);
	$totalcf=sprintf('%.4f',  $row["totalcf"]);
	$vtatotales=$totalt+$totalf+$totalcf;
	$vtatotales_print=sprintf('%.4f', $vtatotales);
	$vtaefectivo= sprintf('%.4f', $vtaefectivo);
	$cashini= sprintf('%.4f', $cashini);
	$ingresos= sprintf('%.4f', $ingresos);
	$monto_ch = sprintf('%.4f', $monto_ch);

	$vales=sprintf('%.4f', $vales);
	$cashfinal= sprintf('%.4f', $cashfinal);
	$diferencia= sprintf('%.4f', $diferencia);
	$esp_init=espacios_izq(" ",1);
	$esp_init0=espacios_izq(" ",1);
	$esp_init1=espacios_izq(" ",12);
	$esp_init2=espacios_izq(" ",20);
	$line1=str_repeat("_",33)."\n";
	$info_factura="";
	$tinicio= zfill($tinicio, 7);
	$tfinal= zfill($tfinal, 7);
	$empresa=empresa();

	if($tipo=="C"){
		$desc_tipo='CORTE DE CAJA';
	}
	else{
		$desc_tipo=$tipo;
	}
	$info_factura.=$esp_init0.$empresa."\n";
	//$info_factura.=$esp_init0.$razonsocial."\n";
	$giros = explode(";", $giro);
	for ($ni = 0; $ni < (count($giros)); $ni++)
	{
		$info_factura.=$esp_init.trim($giros[$ni])."\n";
	}
	//$info_factura.=$esp_init0."SUCURSAL ".$nombre_sucursal."\n";
	$info_factura.=$esp_init0."CORTE TIPO: ".$desc_tipo."\n";
	/*$info_factura.=$esp_init0."RESOLUCION:  ".$resolucion."\n";
	$info_factura.=$esp_init0."DEL ".$desde." AL ".$hasta."\n";
	$info_factura.=$esp_init0."SERIE ".$serie."\n";
	$info_factura.=$esp_init0."FECHA RESOLUCION ".$fehca."\n";*/
	$info_factura.=$esp_init0."CORTE DE CAJA  : ".$id_corte."|";
	$info_factura.=$line1;
	$info_factura.=$esp_init."FECHA: ".$fecha."  HORA:".hora($hora)."\n";
	$info_factura.=$esp_init."EMPLEADO: ".$nombre_emp."\n";
	$info_factura.=$esp_init."CAJA : ".$caja. "  TURNO: ".$turno;


	$info_factura.="\n";
	if($tipo=="C"){
		$subtotal=$cashini+$vtatotales+$ingresos+$monto_ch;
		$totalcaja=$subtotal-$vales;
		$subtotal=sprintf('%.4f', $subtotal);
		$totalcaja=sprintf('%.4f', $totalcaja);
		//$info_factura.=$esp_init1."DESDE:      HASTA:"."\n";
		$info_factura.=$line1;
		$info_factura.=$esp_init0."TIQUETES:     ".$tinicio."   ".$tfinal."\n";
		$info_factura.=$esp_init0."FACTURAS:     ".str_pad($finicio,7," ",STR_PAD_LEFT)."   ".str_pad($ffinal,7," ",STR_PAD_LEFT)."\n";
		$info_factura.=$esp_init0."FISCALES:     ".str_pad($cfinicio,7," ",STR_PAD_LEFT)."   ".str_pad($cffinal,7," ",STR_PAD_LEFT)."\n";
		$info_factura.="\n";

		$n=5;
		$sp1=len_num($cashini,$n);
		$info_factura.=$esp_init0."SALDO INICIAL $:      ".$sp1.$cashini."\n";
		$sp1=len_num($monto_ch,$n);
		$info_factura.=$esp_init0."SALDO CAJA CHICA $:      ".str_pad(number_format($monto_ch,2,".",""),6," ",STR_PAD_LEFT)."\n";
		$sp1=len_num($ingresos,$n);
		$info_factura.=$esp_init0."(+)INGRESOS $:        ".$sp1.$ingresos."\n";
		$sp1=len_num($vtatotales_print,$n);
		$info_factura.=$esp_init0."(+) VENTA $:          ".$sp1.$vtatotales_print."\n";
		$info_factura.=$line1;
		$sp1=len_num($subtotal,$n);
		$info_factura.=$esp_init0."SUBTOTAL $:           ".$sp1.$subtotal."\n";
		$sp1=len_num($vales,$n);
		$info_factura.=$esp_init0."(-) VALES $:          ".$sp1.$vales."\n";
		$info_factura.=$line1;
		$sp1=len_num($totalcaja,$n);
		$info_factura.=$esp_init0."TOTAL CAJA $:         ".$sp1.$totalcaja."\n";
		$info_factura.="\n";
		$sp1=len_num(number_format($retencion,2,".",""),$n);
		$info_factura.=$esp_init0."(-) RETENCION $:    ".str_pad(number_format($retencion,2,".",""),11," ",STR_PAD_LEFT)."\n";


		$sql_dev="SELECT sum(t_devolucion) as total FROM devoluciones_corte WHERE id_corte='$id_corte' AND tipo!='CCF'";
		$result_dev =_query($sql_dev);
		$nrow_dev = _num_rows($result_dev);
		if($nrow_dev>0)
		{
			$row_dev = _fetch_array($result_dev);
			$info_factura.=$esp_init0."(-)DEVOLUCIONES$:   ".str_pad(number_format($row_dev['total'],2,".",""),11," ",STR_PAD_LEFT)."\n";
			/*$info_factura.=$esp_init0."  NUMERO   DOC     AFECTA      TOTAL"."\n";
			for($j=0;$j<$nrow_dev;$j++){

				$row_dev = _fetch_array($result_dev);

				$n_devolucion=str_pad($row_dev['n_devolucion'],8," ",STR_PAD_LEFT);
				$t_devolucion=str_pad(number_format($row_dev['t_devolucion'],2,".",""),11," ",STR_PAD_LEFT);
				$afecta=str_pad($row_dev['afecta'],11," ",STR_PAD_LEFT);
				$tipo=$row_dev['tipo'];

				$info_factura.=" ".$n_devolucion."   ".$tipo.$afecta.$t_devolucion."\n";
				//$info_factura.=$esp_init0."TOTAL   :".$sp1.$total_docs."\n";
			}*/
		}
		$sql_dev="SELECT id_dev as id_devolucion, id_corte, n_devolucion, t_devolucion,afecta,tipo FROM devoluciones_corte WHERE id_corte='$id_corte' AND tipo='CCF'";
		$result_dev =_query($sql_dev);
		$nrow_dev = _num_rows($result_dev);
		if($nrow_dev>0){
			$info_factura.=$esp_init0."(-)NOTAS DE CREDITO :"."\n";
			$info_factura.=$esp_init0."  NUMERO   DOC     AFECTA      TOTAL"."\n";
			for($j=0;$j<$nrow_dev;$j++){

				$row_dev = _fetch_array($result_dev);

				$n_devolucion=str_pad($row_dev['n_devolucion'],8," ",STR_PAD_LEFT);
				$t_devolucion=str_pad(number_format($row_dev['t_devolucion'],2,".",""),11," ",STR_PAD_LEFT);
				$afecta=str_pad($row_dev['afecta'],11," ",STR_PAD_LEFT);
				$tipo=$row_dev['tipo'];

				$info_factura.=" ".$n_devolucion."   ".$tipo.$afecta.$t_devolucion."\n";
				//$info_factura.=$esp_init0."TOTAL   :".$sp1.$total_docs."\n";
			}
		}

		$info_factura.=$line1;
		$sp1=len_num($cashfinal,$n);
		$info_factura.=$esp_init0."EFECTIVO $:           ".$sp1.$cashfinal."\n";
		$sp1=len_num($diferencia,$n);
		$info_factura.=$esp_init0."DIFERENCIA $:         ".$sp1.$diferencia."\n";

		$sql_detail = _query("SELECT producto.descripcion , to_corte_producto.id_producto,to_corte_producto.id FROM to_corte_producto JOIN producto ON producto.id_producto = to_corte_producto.id_producto WHERE id_corte = $id_corte ");

		if (_num_rows($sql_detail)>0) {
			// code...
				$info_factura.=str_pad("\nMOVIMIENTOS DE PRODUCTO",36," ",STR_PAD_BOTH)."\n\n";

				while ($ro = _fetch_array($sql_detail)) {
					// code...
					$info_factura.=str_pad($ro['descripcion'],36," ",STR_PAD_BOTH)."\n";
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
							$info_factura.=str_pad("INICIAL: ".round($row['stock_anterior'],2),36," ",STR_PAD_RIGHT)."\n";
							$info_factura.="  ENTRADA        SALIDA       ACTUAL"."\n";
						}
						$t="S";
						if ($row['stock_anterior']<$row['stock_actual']) {
							// code...
								$t="E";

								$e = $e +round($row['cantidad'],2);
								$info_factura.=
								 str_pad(round($row['cantidad'],2),9," ",STR_PAD_LEFT)
								.str_pad("-",14," ",STR_PAD_LEFT)
								.str_pad(round($row['stock_actual'],2),13," ",STR_PAD_LEFT)
								."\n";
								$f = round($row['stock_actual'],2);
						}
						else {
							// code...
							$s = $s +round($row['cantidad'],2);
							$info_factura.=
							 str_pad("-",9," ",STR_PAD_LEFT)
							.str_pad(round($row['cantidad'],2),14," ",STR_PAD_LEFT)
							.str_pad(round($row['stock_actual'],2),13," ",STR_PAD_LEFT)
							."\n";

							$f = round($row['stock_actual'],2);
						}
						$o++;
					}

					$info_factura.=
					 str_pad("",36,"-",STR_PAD_LEFT)."\n";
					$info_factura.=
					 str_pad(round($e,2),9," ",STR_PAD_LEFT)
					.str_pad(round($s,2),14," ",STR_PAD_LEFT)
					.str_pad(round($f,2),13," ",STR_PAD_LEFT)
					."\n";
					$info_factura.="\n";
				}
		}
	}

	if($tipo=="X" || $tipo=="Z"){
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
		$info_factura.=$esp_init1."       EXEN.  GRAV.  TOTAL"."\n";
		$info_factura.=$line1;
		$n=4;
		$sp1=len_num($texento,$n);
		$sp2=len_num($tgravado,$n);
		$sp3=len_num($totalt,$n);
		$info_factura.=$esp_init0."TIQUETES:".$sp1.$texento."".$sp2.$tgravado."".$sp3.$totalt."\n";
		$sp1=len_num($fexento,$n);
		$sp2=len_num($fgravado,$n);
		$sp3=len_num($totalf,$n);
		$info_factura.=$esp_init0."FACTURAS:".$sp1.$fexento."".$sp2.$fgravado."".$sp3.$totalf."\n";
		$sp1=len_num($cfexento,$n);
		$sp2=len_num($cfgravado,$n);
		$sp3=len_num($totalcf,$n);
		$info_factura.=$esp_init0."FISCALES:".$sp1.$cfexento."".$sp2.$cfgravado."".$sp3.$totalcf."\n";
		$info_factura.=$line1;
		$sp1=len_num($tot_exent,$n);
		$sp2=len_num($tot_grav,$n);
		$sp3=len_num($tot_fin,$n);

		$info_factura.=$esp_init0."TOTAL $ :".$sp1.$tot_exent.$sp2.$tot_grav.$sp3.$tot_fin."\n";
		$info_factura.="\n";

		$info_factura.=$esp_init1."   INICIO   FINAL   TOTAL"."\n";
		$info_factura.=$line1;
		$n=4;
		$total_docs=$totalnot+$totalnof+$totalnocf;
		$sp1=len_num($tinicio,$n);
		$sp2=len_num($tfinal,$n);
		$sp3=len_num($totalnot,$n);
		$info_factura.=$esp_init0."TIQUETES: ".$sp1.$tinicio.$sp2.$tfinal.$sp3.$totalnot."\n";
		$sp1=len_num($finicio,$n);
		$sp2=len_num($ffinal,$n);
		$sp3=len_num($totalnof,$n);
		$info_factura.=$esp_init0."FACTURAS:   ".esp_text($finicio,6)."  ".esp_text($ffinal,6).$sp3.$totalnof."\n";
		$sp1=len_num($cfinicio,$n);
		$sp2=len_num($cffinal,$n);
		$sp3=len_num($totalnocf,$n);
		$info_factura.=$esp_init0."FISCALES:   ".esp_text($cfinicio,6)."  ".esp_text($cffinal,6).$sp3.$totalnocf."\n";
		$info_factura.=$line1;
		$sp1=len_num($total_docs,24);
		$info_factura.=$esp_init0."TOTAL:".$sp1.$total_docs."\n";
		$info_factura.="\n";

  }
  $info_factura.="\n";
  return ($info_factura);

}
function len_num($subtotal,$col3){
		//$col3=5;
	if(strlen($subtotal)<=4)
		$sp3=$col3;
	if(strlen($subtotal)==5)
		$sp3=$col3-1;
	if(strlen($subtotal)==6)
		$sp3=$col3-2;
	if(strlen($subtotal)==7)
		$sp3=$col3-3;
	if(strlen($subtotal)==8)
		$sp3=$col3-4;
	//if(strlen($subtotal)==9)
	//	$sp3=$col3-5;
	$esp_col3=espacios_izq(" ",$sp3);
	return $esp_col3;
}
function texto_espacios($texto,$long){
	$countchars=0;
	$countch=0;
	$texto=trim($texto);
	$len_txt=strlen($texto);
	$latinchars = array( 'ñ','á','é', 'í', 'ó','ú','Ñ','Á','É','Í','Ó','Ú');
    foreach($latinchars as $value){
		$countchars=substr_count($texto,$value);
        $countch= $countchars+$countch;
    }

	if($len_txt<=$long){
	 if($countch>0)
		$n=($long+$countch)-$len_txt;
	 else
		$n=$long-$len_txt;

		$texto_repeat=str_repeat(" ",$n);
		$texto_salida=$texto.$texto_repeat;
	}
	else{
		$long=$long-1;
		$texto_salida=substr($texto,0,$long).".";
	}
	return $texto_salida;
}
function espacios_izq($texto,$long){
	$len_txt=strlen($texto);

	if($len_txt<=$long){

			$alinear='STR_PAD_LEFT';
	 $texto_salida=str_pad($texto, $long, " ",STR_PAD_LEFT );
	}
	else{
	$texto_salida=substr($texto,0,$long);
	}
	return $texto_salida;
}
function cadenaenlineas( $text, $width = '80', $lines = '10', $break = '\n', $cut = 0 ) {
      $wrappedarr = array();
      $wrappedtext = wordwrap( $text, $width, $break , true );
       $wrappedtext = trim( $wrappedtext );
      $arr = explode( $break, $wrappedtext );
     return $arr;
}
function len_espacios($valor,$col){
	$valor=strlen($valor);
	if($valor==1){
		$sp=$col;
	}
	else{
		$sp=$col-($valor-1);
	}
 return $sp;
}
function datos_empresa(){
	//EMPRESA
	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['descripcion'];
	$razonsocial=$row_empresa['razon_social'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];

	$empresa1=$empresa;
	$razonsocial1=$razonsocial;
	$giro1=$giro;
	$arr_emp= array($empresa1,$razonsocial1,$giro1,$nit,$nrc);
	//json_encode(array(2=>"dos", 10=>"diez"));
	$data = array('telefono2' => $row_empresa['telefono2'],'telefono1' => $row_empresa['telefono1'],'direccion' => $row_empresa['direccion'], 'empresa' => $empresa1, 'razonsocial' => $razonsocial1, 'giro' => $giro1,'nit' => $nit,'nrc' => $nrc);
	$datos= json_encode($data);
	return $datos;
}
function datos_sucursal($id_sucursal){
	//Sucursal
  $align=new AlignMarginText();
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	$nombre_sucursal1=$align->texto_espacios($nombre_sucursal,30);
	return $nombre_sucursal1;
}
function datos_factura($id_factura){
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	return $result_fact;
}
function datos_impuesto(){
	//impuestos
	$sql_iva="select iva,monto_retencion1,monto_retencion10,monto_percepcion from monto_impuesto";
	$result_IVA=_query($sql_iva);
	return $result_IVA;

}
function datos_fact_det($id_factura){
  $sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
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
	return $result_fact_det;
}
function datos_clientes($id_cliente){
	//Obtener informacion de tabla Cliente
	$sql="select * from cliente where id_cliente='$id_cliente'";
	$result= _query($sql);
	return $result;
}
function datos_empleado($id_empleado,$id_vendedor){
	//Obtener informacion de tabla Cliente
	$sql="select * from usuario where id_usuario='$id_empleado'";
	$result= _query($sql);
	$row=_fetch_array($result);
	$empleado=$row['nombre'];

	$sql2="select empleado.* from empleado join usuario ON usuario.id_empleado=empleado.id_empleado where id_usuario='$id_vendedor'";
	$result2= _query($sql2);
	$vendedor="";
	if(_num_rows($result2)>0)
	{
		$row2=_fetch_array($result2);
		$vendedor=$row2['nombre'];
	}
	else {
		$vendedor=$empleado;
	}

	$empleado_vendedor=	$empleado."|".$vendedor;
	return $empleado_vendedor;
}
function empresa(){
	//Empresa
	$sql_empresa = "SELECT * FROM sucursal where id_sucursal=$_SESSION[id_sucursal]";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['descripcion'];

	return $empresa;
}

function esp_num($param,$n)
{
	$v=str_pad(number_format($param,2,".",""),$n," ",STR_PAD_LEFT);
	return $v;
}

function esp_text($param,$n)
{
	$v=str_pad($param,$n," ",STR_PAD_LEFT);
	return $v;
}
function vendedor($id_vendedor){

	$sql2="select   CONCAT_WS(' ', nombre, apellido) AS nombre  from empleado  where id_empleado='$id_vendedor'";
	$result2= _query($sql2);
	$vendedor="";
	if(_num_rows($result2)>0)
	{
		$row2=_fetch_array($result2);
		$vendedor=$row2['nombre'];
	}

	return $vendedor;
}
function getDepartamento($id_d,$id_m){
  $sql="SELECT d.nombre_departamento as ndepto,
  m.nombre_municipio as nmuni
  FROM departamento AS d, municipio AS m
  WHERE m.id_departamento_municipio=d.id_departamento
  AND d.id_departamento=$id_d
  AND m.id_municipio=$id_m";
  return _query($sql);
}
function print_ticket($id_factura){
  $align=new AlignMarginText();
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
	$datos_empresa=datos_empresa();
	$field= json_decode($datos_empresa, true);
	$nite=$field['nit'];
	$nrce=$field['nrc'];
	$empresa1=$field['empresa'];
	$razonsocial1=$field['razonsocial'];
	$giro1=$field['giro'];
	$dir_empre = $field['direccion'];
	//Sucursal
	$nombre_sucursal1=datos_sucursal($id_sucursal);
	//inicio datos
	$info_factura="";
	$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|".$nite."|".$nrce."|";
	//Obtener informacion de tabla Factura
	$result_fact=datos_factura($id_factura);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$id_vendedor=$row_fact['id_empleado'];
		$fecha=$row_fact['fecha'];
		$hora=$row_fact['hora'];
		$caja=$row_fact['caja'];
		$turno=$row_fact['turno'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];
		$descuent=$row_fact['descuento'];
		$porcentaje=$row_fact['porcentaje'];
		$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
		$dats_caja = _fetch_array($sql_caja);
		$fehca = ED($dats_caja["fecha"]);
		$resolucion = $dats_caja["resolucion"];
		$serie = $dats_caja["serie"];
		$desde = $dats_caja["desde"];
		$hasta = $dats_caja["hasta"];
		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado usuario y vendedor
		$result_emp= datos_empleado($id_usuario,$id_vendedor);
		list($empleado,$vendedor)=explode('|',$result_emp);
		//Datos del Cliente
		$result=datos_clientes($id_cliente);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$sp2=espacios_izq(" ",12);
		$esp_init=espacios_izq(" ",1);
		$esp_precios=espacios_izq(" ",10);
		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",23);
		$nombre_ape=$align->texto_espacios($nombres,32);
		$dir_txt=$align->texto_espacios($direccion,30);
		$total_final=0;

		$nom = divtextlin($empresa1, 35);
		foreach ($nom as $nnon)
		{
			if($nnon!="")
			{
					$info_factura.=$nnon."\n";
			}
		}

		$nom = divtextlin($dir_empre, 35);
		foreach ($nom as $nnon)
		{
			if($nnon!="")
			{
					$info_factura.=$nnon."\n";
			}
		}

		$nom = divtextlin($giro1, 35);
		foreach ($nom as $nnon)
		{
			if($nnon!="")
			{
					$info_factura.=$nnon."\n";
			}
		}

		$info_factura.=$esp_init."NIT :  ".$nite." NRC :".$nrce."\n";
		$info_factura.=$esp_init."RESOLUCION:  ".$resolucion."\n";
		$info_factura.=$esp_init."DEL ".$desde." AL ".$hasta."\n";
		$info_factura.=$esp_init."SERIE ".$serie."\n";
		$info_factura.=$esp_init."FECHA RESOLUCION ".$fehca."\n";
		$info_factura.=$esp_init."TIQUETE # ".$num_fact."|";
		$info_factura.=$esp_init."FECHA: ".$fecha_fact." ".hora($hora)."\n";
		$info_factura.=$esp_init."VENDEDOR: ".$vendedor."\n";
		$info_factura.=$esp_init."CAJA : ".$caja. "  TURNO: ".$turno."\n";
		$info_factura.=$esp_init."CLIENTE: ".$nombre_ape."|";
		$info_factura.="DESCRIPCION  CANT.  P. UNIT    SUBTOT.\n|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$result_fact_det=datos_fact_det($id_factura);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =$row_fact_det['descripcion'];
			//descripcion presentacion
			$id_presentacion =$row_fact_det['id_presentacion'];
			$descpre =$row_fact_det['descpre'];
			$descpresenta =$row_fact_det['descripcion_pr'];
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$descuento =$row_fact_det['descuento'];
			$subt=$row_fact_det['subtotal'];
			//$subt = $subt - $descuento;
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
			//presentacion producto
			$sql_uus=_fetch_array(_query("SELECT pp.precio, pp.unidad, pp.descripcion, p.nombre  FROM presentacion_producto as pp, presentacion as p WHERE pp.id_presentacion=p.id_presentacion AND pp.id_pp=$id_presentacion"));
			$precio_p=$sql_uus['precio'];
			$unidad_w=$sql_uus['unidad'];
			$desc_pr=$sql_uus['descripcion'];
			$prese_pr=$sql_uus['nombre'];
			$desc_pr_fin = $prese_pr."($desc_pr)";
			$cantidad=$cantidad/$unidad_w;
			//linea a linea
			$descrip=$align->texto_espacios($descripcion,22);
			$descpresenta1=$align->texto_espacios($descpre,7);
			$descpre1=$align->texto_espacios($descpre,30);

			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subt);
				$total_gravado=$subt_gravado+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subt);
				$total_exento=$subt_exento+$total_exento;
			}
			$esp_init=len_num($cantidad,8);
			$esp_col2=len_num($precio_unit,6);
			$esp_col3=len_num($subtotal,7);
			$esp_col4=len_num($descuento,11);
			$info_factura.=str_pad($cantidad,4," ",STR_PAD_LEFT)."  ".str_pad($descrip,18).str_pad($precio_unit,5," ",STR_PAD_LEFT)."  ".str_pad($subtotal,5," ",STR_PAD_LEFT)."\n";
			$info_factura.="      ".$desc_pr_fin."\n";
			//$info_factura.="PRESENT: ".$descpre1."\n";
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.4f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}
	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	//$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.4f",$total);
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.4f",$total_exento);
	$total_value_gravado=sprintf("%.4f",$total_gravado);
	$total_value_fin=sprintf("%.4f",$total_fin);
	$esp_totales=len_num($total_value,8);
	$esp_init2=espacios_izq(" ",25);
	$tt_fin = $total_value_fin - $descuent;
	//$esp_totales=espacios_izq(" ",$sp3);
	$esp_d1=len_num($total_value_gravado,3);
	$esp_d2=len_num($total_value_exento,3);
	$esp_d3=len_num($total_value_fin,3);
	$vals = 3;
	if(strlen($descuent)>3)
	{
		$vals = 2;
	}
	$esp_d4=len_num($descuent,$vals);
	$vals = 3;
	if(strlen($porcentaje)>3)
	{
		$vals = 2;
	}
	$esp_d6=len_num($porcentaje,$vals);
	$esp_d5=len_num($tt_fin,2);
	$info_factura.="|TOTAL GRAVADO".$esp_totales."$ ".$esp_d1.$total_value_gravado."\n";
	$info_factura.="TOTAL EXENTO ".$esp_totales."$ ".$esp_d2.$total_value_exento."\n";
	$info_factura.="TOTAL        ".$esp_totales."$ ".$esp_d3.$total_value_fin."\n";
	/*$info_factura.="DESCUENTO       ".$esp_totales."".$esp_d6.$porcentaje."%\n";
	$info_factura.="TOTAL DESCUENTO  ".$esp_totales."  $ ".$esp_d4.sprintf("%.4f",$descuent)."\n";
	$info_factura.="A PAGAR          ".$esp_totales."  $".str_pad(number_format($tt_fin,2,".",""),8," ",STR_PAD_LEFT)."\n";
	*/
	$info_factura.="|".$cadena_salida_txt."\n";
	$info_factura.="|"."VENDEDOR: ".$vendedor;
	//$esp=espacios_izq(" ",30);PRODUCTIS
	// retornar valor generado en funcion
	return ($info_factura);
}
function printick_pista($id_factura)
{
  $align=new AlignMarginText();
  $id_sucursal=$_SESSION['id_sucursal'];
  //encabezado
  // probar para set imagen to raste print
  // instalar php-imagick en server
  //$img1=base_url(getLogo());
  $line1 = str_repeat("_", 42) . "\n";
  $hstring = "";
  $hstring .= chr(27) . chr(33) . chr(16); //FONT double size
  $hstring .= chr(27) . chr(97) . chr(1); //Center
  $alias= 'TIK';
  $hstring .=head($alias);
  //pie
  $pstring =foot($alias);
  //Sucursal
	$nombre_sucursal1=datos_sucursal($id_sucursal);
  //detalles
  $result_fact=datos_factura($id_factura);
	$nrows_fact=_num_rows($result_fact);
  $det_ticket = "";
  $espacio = " ";
  $margen_izq1 =$align->leftmargin($espacio, 1);
  $margen_izq2 =$align->leftmargin($espacio, 4);
  $esp_init = $margen_izq1;
  $total      = 0;
  if ($nrows_fact>0){
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
    $sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
    $dats_caja = _fetch_array($sql_caja);
    $fehca = ED($dats_caja["fecha"]);
    $resolucion = $dats_caja["resolucion"];
    $serie = $dats_caja["serie"];
    $desde = $dats_caja["desde"];
    $hasta = $dats_caja["hasta"];

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
            if(isset($id_d) && isset($id_m)){
              $deptoMuni=getDepartamento($id_d,$id_m);
              $row_d=_fetch_array($deptoMuni);
              $depto=$row_d['ndepto'];
              $muni =$row_d['nmuni'];
            }
    }
    $nombreVendedor=vendedor($id_vendedor);
    $len_numero_doc=strlen($numero_doc)-4;
		$tiq=substr($numero_doc,0,$len_numero_doc);
    $date1 = new DateTime($fecha." ".$hora);
    $hora1= $date1->format("g"). ':' .$date1->format("i"). ' ' .$date1->format("A");
    $fecha1 = $date1->format("d"). '/' .$date1->format("m"). '/' .$date1->format("Y");
    //$tiq = zfill($corr, 10);
    $hstring .= $esp_init."NIT :  ".$nitcte." NRC :".$nrccte."\n";
    $hstring .= $esp_init."RESOLUCION:  ".$resolucion."\n";
    $hstring .= $esp_init."DEL ".$desde." AL ".$hasta."\n";
    $hstring .= $esp_init."SERIE ".$serie."\n";
    $hstring .= $esp_init."FECHA RESOLUCION ".$fehca."\n";
    $hstring .= chr(13) . " TICKET #: " . $tiq . "\n";
    $hstring .= chr(13) . " FECHA: " .	$fecha1 . " HORA:" . $hora1 . "\n";
    $hstring .= $esp_init."VENDEDOR: ".$nombreVendedor."\n";
		$hstring .= $esp_init."CAJA : ".$caja. "  TURNO: ".$turno."\n";
    $hstring .= chr(13) . $line1 . "\n"; // Print text Lin
    $th = chr(13) . " DESCRIPCION    CANT.    P.U      SUBTOTAL" . "\n";
    $det_ticket .= chr(13) . $th;
    $det_ticket .= chr(13) . $line1. "\n";

  //Obtener informacion de tabla Factura_detalle y producto o servicio
  $result_fact_det=datos_fact_det($id_factura);
  $nrows_fact_det=_num_rows($result_fact_det);
  $total_final=0;
  $lineas=6;
  $cuantos=0;
  $subt_exento=0;
  $subt_gravado=0;
  $total_exento=0;
  $total_gravado=0;
  $tmpItems= array();
  $wdesc = 38;
  for($i=0;$i<$nrows_fact_det;$i++){
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
      //presentacion producto
      /*
      $sql_uus=_fetch_array(_query("SELECT pp.precio, pp.unidad, pp.descripcion, p.nombre
        FROM presentacion_producto as pp, presentacion as p
        WHERE pp.id_presentacion=p.id_presentacion AND pp.id_pp=$id_presentacion"));
      $precio_p=$sql_uus['precio'];
      $unidad_w=$sql_uus['unidad'];
      $desc_pr=$sql_uus['descripcion'];
      $prese_pr=$sql_uus['nombre'];
      $desc_pr_fin = $prese_pr."($desc_pr)";*/
      $cantidad=$cantidad/$unidad;
      $descripcion1=$descripcion." ".$nombre_pre." ".$descpre;
      $descripts = $align->wordwrap1($descripcion1,$wdesc);
    	$tmplinea = array();
    	$ln=0;
      $desc = "";
    	foreach($descripts as $descrip){
    		$descript = $align->addspright($descrip,$wdesc);
        $desc .= $align->onelineleft($descript,$wdesc, 1, $espacio). "\n";
    		$ln=$ln+1;
    	}
      //
      $descpresenta1=$align->texto_espacios($descpre,7);
			$descpre1=$align->texto_espacios($descpre,30);

			$precio_unit=sprintf("%.4f",$precio_venta);
			$subtotal=sprintf("%.4f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.4f",$subt);
				$total_gravado=$subt_gravado+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.4f",$subt);
				$total_exento=$subt_exento+$total_exento;
			}
  		$subtotal=round($subtotal,4);
  		$lntot=8-strlen($subtotal);

      $pre  = $align->rightaligner(number_format($precio_unit,2,".",","), $espacio,10);
      $cant = $align->rightaligner($cantidad, $espacio, 7);
      $subt = $align->rightaligner(number_format($subtotal,2,".",","), $espacio, 12);
      $det_ticket .= chr(27) . chr(33) . chr(1); //FONT B small size
      $det_ticket .= $desc;
      $det_ticket .=  chr(27) . chr(33) . chr(0); //FONT A normal size
      $det_ticket .= $margen_izq1 . $cant . "   X " . $margen_izq1 . $pre . $margen_izq1 . " = " . $subt . "\n\n";

  }

  $impuestoGas=getImpGass($id_factura);
  $n_imp = _num_rows($impuestoGas);
  for ($n=0;$n<$n_imp;$n++){
    $row_imp=_fetch_array($impuestoGas);
    $imp_n = $row_imp['imp_nombre'];
    $tot_imp = $row_imp['total_imp'];
    $total_imp= $align->rightaligner(number_format($tot_imp,2,".",","), $espacio, 12);

  //  $imp_nom    = $align->addspright($imp_n,$wdesc);
    $imp_nombre = $align->onelineleft($imp_n,'32', 1, $espacio);
    $det_ticket .= $margen_izq1 . $imp_nombre . "  " . $margen_izq1 .  $total_imp . "\n\n";

  }
  //
  $det_ticket .= chr(13) . $line1;

  $det_ticket .= chr(27) . chr(33) . chr(0); //FONT A
  $det_ticket .= chr(27) . chr(97) . chr(1); //Center align
  $totales = chr(27) . chr(33) . chr(16); //FONT A
  $totales .= chr(27) . chr(97) . chr(2); //Right align
  $totals = "  TOTAL   $ " .number_format($total,2,".",","). "   " . "\n";
  $lentot = strlen($totals);
  $totales .= $totals;
  $totales .= chr(27) . chr(33) . chr(0); //FONT A
  $l2 = str_repeat("_", $lentot) . "\n";
  $totales .= $l2;
  $logo = getLogo();
  $uri=getUrl().$logo;

  $pstring .= $uri;
  list($entero,$decimal)=explode('.',number_format($total,2,".",","));

  for ($n=0;$n<4;$n++) {
    $pstring .= "\n";
  }
  $pstring  .= chr(27) . chr(97) . chr(1); //center align
  $pstring .= chr(27) . chr(33) . chr(0); //FONT A

  $cajero= getCajero($id_usuario);
  $pstring .= str_repeat("_", 24) . "\n\n";
  $pstring .= $cajero . "\n";

  $total_letras = getTotalTexto(number_format($total,2,".",","));
  $pstring .= chr(27) . chr(33) . chr(0); //FONT A

  $xdatos["encabezado"] = $hstring;
  $xdatos["totales"] = $totales;
  $xdatos["cuerpo"] = $det_ticket;
  $xdatos["pie"] = $pstring;
  $xdatos["total_letras"] = $total_letras;
  $xdatos["img"] = $uri ;
  return $xdatos;
  }
}
function head($alias){
  $sql_hf = "SELECT * FROM config_pos WHERE alias_tipodoc = '$alias'";
  $res_hf = _query($sql_hf);
  $row_hf = _fetch_array($res_hf);
  $hstring="";
  if ($row_hf['header1'] != '')
    $hstring .= chr(13) . $row_hf['header1'] . "\n";
  $hstring .=  chr(27) . chr(33) . chr(0); //FONT A normal size
  if ($row_hf['header2'] != '')
    $hstring .= chr(13) . $row_hf['header2'] . "\n";
  if ($row_hf['header3'] != '')
    $hstring .= chr(13) . $row_hf['header3'] . "\n";
  if ($row_hf['header4'] != '')
    $hstring .= chr(13) . $row_hf['header4'] . "\n";
  if ($row_hf['header5'] != '')
    $hstring .= chr(13) . $row_hf['header5'] . "\n";
  if ($row_hf['header6'] != '')
    $hstring .= chr(13) . $row_hf['header6'] . "\n";
  if ($row_hf['header7'] != '')
    $hstring .= chr(13) . $row_hf['header7'] . "\n";
  if ($row_hf['header8'] != '')
    $hstring .= chr(13) . $row_hf['header8'] . "\n";
  if ($row_hf['header9'] != '')
    $hstring .= chr(13) . $row_hf['header9'] . "\n";
  if ($row_hf['header10'] != '')
    $hstring .= chr(13) . $row_hf['header10'] . "\n";
    return $hstring;
}
function foot($alias){
  $sql_hf = "SELECT * FROM config_pos WHERE alias_tipodoc = '$alias'";
  $res_hf = _query($sql_hf);
  $row_hf = _fetch_array($res_hf);
  $pstring =  chr(27) . chr(97) . chr(1); //Center align;
  if ($row_hf['footer1'] != '')
    $pstring = chr(13) . $row_hf['footer1'] . "\n";
  if ($row_hf['footer2'] != '')
    $pstring .= chr(13) . $row_hf['footer2'] . "\n";
  if ($row_hf['footer3'] != '')
    $pstring .= chr(13) . $row_hf['footer3'] . "\n";
  if ($row_hf['footer4'] != '')
    $pstring .= chr(13) . $row_hf['footer4'] . "\n";
  if ($row_hf['footer5'] != '')
    $pstring .= chr(13) . $row_hf['footer5'] . "\n";
  if ($row_hf['footer6'] != '')
    $pstring .= chr(13) . $row_hf['footer6'] . "\n";
  if ($row_hf['footer7'] != '')
    $pstring .= chr(13) . $row_hf['footer7'] . "\n";
  if ($row_hf['footer8'] != '')
    $pstring .= chr(13) . $row_hf['footer8'] . "\n";
  if ($row_hf['footer9'] != '')
    $pstring .= chr(13) . $row_hf['footer9'] . "\n";
  if ($row_hf['footer10'] != '')
    $pstring .= chr(13) . $row_hf['footer10'] . "\n";
  return $pstring;
}
function getImpGass($id_factura){

    $sql= "SELECT imp_nombre, total_imp
    FROM fact_imp_combust
    WHERE id_factura='$id_factura'";
    $res = _query($sql);
    return $res;
}

?>
