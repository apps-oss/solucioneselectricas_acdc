<?php
include("_core.php");
function initial()
{
  $title = 'Administrar Lecturas diarias por Bomba';
  include_once "_headers.php";
  $_PAGE ['title'] = $title;
  include_once "header.php";
  include_once "main_menu.php";
  $id_sucursal=$_SESSION['id_sucursal'];
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user, $filename);
  //$fecha_actual=date("Y-m-d");
  $id_apertura  = $_REQUEST['id_apertura'];
  $fecha_actual = $_REQUEST['fecha'];
  if (isset($_REQUEST['pend'])){
    $pend = $_REQUEST['pend'];
  }
  else{
    $pend = 0;
  }
  ?>
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2"></div>
  </div>
  <div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <?php
          if ($links!='NOT' || $admin=='1') {
             ?>
             <div class="ibox-title">
         			<h5><?php echo $title; ?></h5>
         		</div>
            <div class="ibox-content">
            <!--section-->
                <div class="row">
                  <div class="col-md-1">
  										<div class="form-group has-info">
  											<label>Fecha:</label>
  											<input type='text' placeholder='Fecha' class='form-control'
                        id='fecha' name='fecha' value='<?php echo $fecha_actual;?>' readonly>
                        <input type='hidden' id='id_usuario' name='id_usuario' value='<?= $id_user;?>' >
                        <input type='hidden' id='id_apertura' name='id_usuario' value='<?= $id_apertura;?>' >
  										</div>
                  </div>
                  <?php
                  $res_comb =getPrecioBaseCombustibles();
                   while ($row=_fetch_array($res_comb)) {
                        $idcomb=$row['id'];
                        $descripcomb=$row['descripcion'];
                        $preciocomb=$row['precio'];
                        $data_comb= "<div class='col-md-2'>";
                        $data_comb.="<div class='form-group has-info'>";
                        $data_comb.="<label>$ " .$descripcomb."</label>";
                        $data_comb.="<input type='text'  class=' form-control $idcomb'";
                        $data_comb.="id='comb".$idcomb."' name='comb".$idcomb."' value='$preciocomb' readonly>";
                        $data_comb.="</div>";
                        $data_comb.="</div>";
                        echo $data_comb;
                    }
                    $total_dia = sprintf("%.2f",getFacturaCombustible($id_sucursal,$fecha_actual,));

                    ?>
                    <div class="col-md-2">
                      <label>Total Facturado Sistema</label>
                      <input type='text'  class=' form-control' id='total_dia' name='total_dia' value='<?= $total_dia?>' readonly/>
                    </div>
                  <div class="col-md-2 pull-right"><br>

                    <?php if($pend==0){
                        $url_corte="corte_caja_diario.php?aper_id=$id_apertura";
                       }
                      else {
                        $url_corte="corte_caja_pendiente.php?aper_id=$id_apertura";
                      }
                    ?>
                    <a class="btn btn-sm btn-danger pull-right" style="margin-left:1%;" href='<?= $url_corte;?>' id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                    <button type="button" id="submit1" name="submit1" class="btn btn-sm btn-primary pull-right usage"><i class="fa fa-save"></i> F2 Guardar</button>
                    </div>
                </div>
            <div class="row">
              <div class="col-md-12">
							  <div class="table-responsive">
								<table class="table" id='loadtable'>
									<thead class='thead1' style=' background-color:#263238; color:#F0F4C3'>
										<tr>
											<th >Bomba N°</th>
											<th >Combustible</th>
                      <th >LECT.ANT. GAL</th>
											<th >LECT. ACT .GAL</th>
                      <th >GALONES</th>
                      <th >EFECT. INI $</th>
											<th >EFECT. ACT $</th>
                      <th >VENTA $</th>
										</tr>
									</thead>
									<tbody class='tbody1 table' id="inventable">
                    <?php getLecturaBomba($fecha_actual,$id_sucursal,0,$id_apertura) ?>
									</tbody>
                  <tbody class='tbody2' id="footable">
                    <th  colspan=5>Totales</th>
                    <th  colspan=1>Galones</th>
                    <th  colspan=1><input type='text' id='total_galon'  class='form-control decimal' name='total_galon' value='0' readonly></th>
                    <th  colspan=1>$</th>
                    <th  colspan=1><input type='text' id='total_efectivo' class='form-control decimal' name='total_efectivo' value='0' readonly></th>
                  </tbody>
                  <tbody id='tabla_impuestos'>
                    <?php $imp_gas=getImpuestoGas();
                    $count = _num_rows($imp_gas);
                    if ($imp_gas != NULL ){
                      for ($s = 0; $s < $count; $s++) {
                        $row = _fetch_array($imp_gas);
                        $id         = $row["id"];
                        $nombre     = $row["nombre"];
                        $valor      = $row["valor"];
                        $activo     = $row["activo"];
                        $dif        = $row["dif"];
                        $id_imp     ="<input type='hidden' id='id_imp' name='id_imp' value='$id' />";
                        $aplica_dif ="<input type='hidden' id='aplica_dif' name='aplica_dif' value='$dif' />";
                        $tot_imp_gas="<input type='hidden' id='tot_imp_gas' name='tot_imp_gas' value='0' />";
                        $val_imp_gas="<input type='hidden' id='val_imp_gas' name='val_imp_gas' value='0' />";
                        $iname     ="<input type='hidden' id='imp_nombre' name='imp_nombre'' value='$nombre' />";
                        echo "<tr id='$s'>

                            <td class=' desc_imp text-bluegrey'  id='descrip_impuesto' colspan=10>";
                        echo  $id_imp.$iname.$aplica_dif.$val_imp_gas." IMPUESTO POR GALON: $nombre ($valor )</td>
                            <td class='cell100 column30 val_imp text-right text-green'  id='total_impgas'>0.0 </td>
                            </tr>";

                      }
                      echo "<tr hidden >
                          <td class=' desc_imp text-bluegrey'  id='descrip_tot_imp'>
                          <input type='hidden' id='id_imp' name='id_imp' value='-1' />
                          <input type='hidden' id='tot_imp_combust' name='tot_imp_combust' value=0' />
                          TOTALES IMPUESTOS COMBUSTIBLES"."</td>
                          <td class='val_imp text-right text-green'  id='tot_imp_gass'>0.0 </td>
                          </tr>";
                    } ?>
                  </tbody>

                  <tfoot class='tfoot1' id="footable1">
                    <th  colspan=6>Totales</th>
                    <th  colspan=1></th>
                    <th  colspan=2>
                      <input type='hidden' id='gal_diesel' name='gal_diesel' value=0>
                      <input type='hidden' id='gal_regular' name='gal_regular' value=0 >
                      <input type='hidden' id='gal_super' name='gal_super' value=0 >
                      <input type='hidden' id='dinero_diesel' name='dinero_diesel' value=0 >
                      <input type='hidden' id='dinero_regular' name='dinero_regular' value=0 >
                      <input type='hidden' id='dinero_super' name='dinero_super' value=0 >
                    </th>
                      <th  colspan=1>$</th>
                    <th  colspan=1><input type='text' id='total_dinero_final' class='form-control decimal' name='total_dinero_final' value='0' readonly></th>
                  </tfoot>
								</table>
							 </div>
              </div>
              <div class="col-md-6">
                <table class="table" id='loadcomb'>
                  <thead class='thead1'>
                    <tr>

                      <th >COMBUSTIBLE</th>
                      <th >COSTO $</th>
                      <th >PRECIO $</th>
                      <th >GAL. FACT</th>
                      <th >VTA. FACT</th>
                      <th >GAL. LECT</th>
                      <th >VTA. LECT $</th>
                      <th >DIF. GAL</th>

                    </tr>
                  </thead>
                  <tbody class='table' id="combustibles">
                  </tbody>

                  <tfoot class='tfoot2' id="footable2">
                  </tfoot>
                </table>

                </div>
						</div>
          <!--/section-->
          <?php
              include ("footer.php");
              echo "<script src='js/plugins/sweetalert/sweetalert2.all.min.js'></script>";
                    echo "<script src='js/plugins/cellNavigate.js'></script>";
              echo "<script src='js/funciones/lectura_dia.js'></script>";
            } //permiso del script
            else {
              echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
              include ("footer.php");
            }?>
            </div><!--div class='ibox-content'-->
          </div><!--<div class='ibox float-e-margins' -->
        </div><!--div class='col-lg-12'-->
      </div>  <!--div class='row'-->
    </div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
<?php
}

function getLecturaBomba($fecha_actual,$id_sucursal,$js=0,$id_apertura){
  $fromJavascript=0;
if($js==1){
  $fromJavascript=1;
}
  $fecha_ante =restar_dias($fecha_actual,1);

  $sql="SELECT * FROM bomba WHERE id_sucursal='$id_sucursal' AND activa=1";
  $res=_query($sql);
  $filas=0; $filas2=0;
  while ($row=_fetch_array($res)) {
    $id=$row['id'];
    $descripcion="<h5 class='text-primary'><strong>".$row['descripcion']."</strong></h5>";
    $n=3;
      $lectComb ="";
    $q_m="SELECT * FROM bomba_manguera WHERE id_bomba='$id'";
    $r=_query($q_m);
    $nr = _num_rows($r);
    if($nr>0){
      for($i=0;$i<$nr;$i++){
        $row1=_fetch_array($r);
        $id_manguera=$row1['id_manguera'];
        $tipo_comb=$row1['combustible'];
        $lectComb .= addRowCombustible($tipo_comb,$fecha_actual,$id,$filas,$descripcion,$i,$fromJavascript,  $id_manguera,$id_apertura);
        $filas++;
      }
    }
  }
  return $lectComb;
}
function getLecturaBombaAnte($fecha_ante,$id_bomba,$id_tipo_combustible,$id_manguera,$id_apertura){
    $sql0 = "SELECT id_lectura,fecha FROM lectura_bomba

    ORDER BY id_lectura DESC LIMIT 1 ";
    //WHERE id_apertura!='$id_apertura'
    $res0=_query($sql0);
    $id_lectura =-1;
    $fecha_lect=$fecha_ante;
    if(_num_rows($res0)>0){
      $row0=_fetch_row($res0);
      $id_lectura=$row0[0];
      $fecha_lect=$row0[1];
    }

    $sql="SELECT fin_combustible,fin_dinero FROM lectura_detalle_bomba
    WHERE id_bomba='$id_bomba'
    AND id_tipo_combustible='$id_tipo_combustible'
      AND id_manguera='$id_manguera'
    AND id_lectura='$id_lectura'
    AND fecha<='$fecha_ante'
    ";

    $res=_query($sql);
    $valor= 0;
    if(_num_rows($res)>0){
      $row=_fetch_row($res);
      $valor=$row[0].",".$row[1];
    }
    return $valor;
}
function addRowCombustible($tipo_comb,$fecha_ante,$id_bomba,$filas,$bomba,$i,$fromJavascript,  $id_manguera,$id_apertura){
    $lect_ante= getLecturaBombaAnte($fecha_ante,$id_bomba,$tipo_comb,$id_manguera,$id_apertura);

    list($lectura_ante,$efect_ante)= explode(",",$lect_ante);
    $lectura_ini="";
    if($lectura_ante>0){
        $lectura_ini=$lectura_ante;
    }
    if($tipo_comb==3){
      $text_color='text-danger';
      $identifier='diesel';
      $descrip_combustible='DIESEL';
      $style='background-color:#B0BEC5';
    }
    if($tipo_comb==2){
      $text_color='text-success';
        $identifier='regular';
        $descrip_combustible='GASOLINA REGULAR';
        $style='background-color:#CFD8DC';
    }
    if($tipo_comb==1){
      $text_color='text-info';
      $identifier='super';
      $descrip_combustible='GASOLINA SUPER';
      $style='background-color:#E0E0E0';
    }
    $input_bomba="<input type='hidden'
        id='id_bomba' name='id_bomba' value='$id_bomba'>";
    $input_idcomb="<input type='hidden'
        id='id_comb' name='id_comb' value='$tipo_comb'>";
    $input_idmanguera="<input type='hidden'
        id='id_manguera' name='id_manguera' value='$id_manguera'>";
    $lect_ante="<input type='text' class='form-control decimal qty $identifier'
        id='ante' name='ante' value='$lectura_ante' style='width:100%;' readonly >";
    $lect_ini="<input type='text' class='form-control decimal qty $identifier combustt'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;'>";
    $descombust="<h5 class='$text_color'><strong> ".$descrip_combustible." </strong></h5>";
    $input_qty="<input type='text'  class='form-control decimal qty  $identifier'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;' >";
    $input_read="<input type='text'  class='form-control decimal qty  $identifier'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;' readonly>";
    $efect_ante="<input type='text' class='form-control decimal qty $identifier'
            id='ante' name='ante' value='$efect_ante' style='width:100%;' readonly >";
    $input_efect="<input type='text'  class='form-control decimal qty efect $identifier'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;' y>";
    $diesel_activo=' readonly ';
    $tr_add  ="<tr  class='fila ' id='tr_".$filas. "' style='$style'>";
    $tr_add .="<td class='bomba'>" .$bomba.$input_bomba.$input_idcomb.$input_idmanguera.'</td>';
    $tr_add .="<td class='combustible'>".$descombust."</td>";
    $tr_add .="<td class='text-primary'>".$lect_ante."</td>";
    $tr_add .="<td class='text-success'>".$lect_ini."</td>";
    $tr_add .="<td class='text-success'>".$input_read."</td>";
    $tr_add .="<td class='text-primary'>".$efect_ante."</td>";
    $tr_add .="<td class='text-success'>".$input_efect."</td>";
    $tr_add .="<td class='text-info'>".$input_read."</td>";
    $tr_add .='</tr>';
    if($fromJavascript==0){
      echo $tr_add;
    }else{
      return $tr_add;
    }
}
function getFacturaCombustible($id_sucursal,$fecha){
    $sql="SELECT COALESCE(SUM(fd.total),0) as total_dia
    FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
    JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
    WHERE c.combustible=1
    AND fd.id_sucursal='$id_sucursal'
    and fd.fecha='$fecha'";
    $res=_query($sql);
    $valor= 0;
    if(_num_rows($res)>0){
      $row=_fetch_row($res);
      $valor=$row[0];
        $valor = round(($valor), 2, PHP_ROUND_HALF_UP);
    }
    return $valor;
}
function actualizar_lectura(){
  $fecha                = $_REQUEST['fecha'];
  $id_sucursal          = $_SESSION['id_sucursal'];
  $fact_fecha           = sprintf("%.2f",getFacturaCombustible($id_sucursal,$fecha));
  $tr_add               = getLecturaBomba($fecha,$id_sucursal,1);
  $xdatos["tr_add"]     = $tr_add;
  $xdatos["fact_fecha"] = $fact_fecha;
  echo json_encode ($xdatos); //Return the JSON Array
}
function insertar(){
  $cuantos         = $_POST['cuantos'];
  $array_json      = $_POST['json_arr'];
  $fecha           = $_POST['fecha'];
  $gal_diesel      = $_POST['gal_diesel'];
  $gal_regular     = $_POST['gal_regular'];
  $gal_super       = $_POST['gal_super'];
  $dinero_diesel   = $_POST['dinero_diesel'];
  $dinero_regular  = $_POST['dinero_regular'];
  $dinero_super    = $_POST['dinero_super'];
  $total_galon     = $_POST['total_galon'];
  $total_venta     = $_POST['total_venta'];
  $total_impuestos = $_POST['total_impuestos'];

  $id_apertura    = $_POST['id_apertura'];
  $hora        = date("H:i:s");
  $id_usuario = $_SESSION['id_usuario'];
  $id_sucursal = $_SESSION['id_sucursal'];
  if ($cuantos>0) {
      $table= 'lectura_bomba';
      $data_lect = array(
        'gal_diesel'     => $gal_diesel,
        'gal_regular'    => $gal_regular,
        'gal_super'      => $gal_super,
        'total_gal'      => $total_galon,
        'dinero_diesel'  => $dinero_diesel,
        'dinero_regular' => $dinero_regular,
        'dinero_super'   => $dinero_super,
        'total_dinero'   => $total_venta,
        'fecha'          => $fecha,
        'hora_corte'     => $hora,
        'id_sucursal'    => $id_sucursal,
        'id_usuario'     => $id_usuario,
        'id_apertura'    => $id_apertura,
        'total_impuestos'=> $total_impuestos,
      );
      $insert0 = _insert($table, $data_lect);
      $id_lectura = _insert_id();
      $table_det= 'lectura_detalle_bomba';
      $array = json_decode($array_json, true);
      foreach ($array as $fila) {
        $id_bomba    = $fila['id_bomba'];
        $id_manguera = $fila['id_manguera'];
        $id_comb     = $fila['id_comb'];
        $lect_ini    = $fila['lect_ini'];
        $lect_fin    = $fila['lect_fin'];
        $devol       = $fila['devol'];
        $galones     = $fila['galones'];
        $efect_ini   = $fila['efect_ini'];
        $efect_fin   = $fila['efect_fin'];
        $efect_devol = $fila['efect_devol'];
        $venta       = $fila['venta'];
        $combustible = $fila['combustible'];
        $sql = "SELECT id, id_bomba, id_tipo_combustible
        FROM $table_det
        WHERE id_bomba = '$id_bomba'
        AND id_manguera = '$id_manguera'
        AND id_lectura = '$id_lectura'
        AND fecha ='$fecha'
        ";
        $res=_query($sql);
        $valor= 0;
        if(_num_rows($res)==0){
          $data_det = array(
            'id_lectura'          => $id_lectura,
            'id_bomba'            => $id_bomba,
            'id_tipo_combustible' => $id_comb,
            'id_manguera'         => $id_manguera,
            'combustible'         => $combustible,
            'fecha'               => $fecha,
            'inicio_combustible'  => $lect_ini,
            'fin_combustible'     => $lect_fin,
            'galones'             => $galones,
            'inicio_dinero'       => $efect_ini,
            'fin_dinero'          => $efect_fin,
            'total_dinero'        => $venta,
            'hora_corte'          => $hora,
            'id_sucursal'         => $id_sucursal,
          );
          $insertar = _insert($table_det, $data_det);
        }
      }
      if($insertar){
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Lecturas: de fecha <strong>'.ed($fecha).'</strong>  guardada con exito !';
        $xdatos['id_lectura']=$id_lectura;
      }else{
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Lecturas: de fecha <strong>'.ed($fecha).'</strong> no guardadas !';
        $xdatos['id_lectura']=-1;
      }
      echo json_encode ($xdatos); //Return the JSON Array
  }
}
//mostrar valores de combustible facturado en sistema
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
//
function fact_apertura(){
  $fecha           = $_POST['fecha'];

  $id_apertura    = $_POST['id_apertura'];
  $lect_head_row=getLecturas($id_apertura);
  //tabla consolidada
  $res2 = getFactDet($id_apertura);
  $count2= _num_rows($res2);
  $total_vf = 0; $total_gal_vf = 0;
  $el_add="";
  for($j=0;$j<$count2;$j++){
    $row2 = _fetch_array($res2);
    $id_prod=round( $row2['id_prod'],2);
    $gal_fact=round($row2['cant_galones'],2);
    $subtotal = round($row2['precio'] * $row2['cant_galones'],2);
    $descripcion =$row2['descripcion'];
    $costo = round($row2['costo'],2);
    $precio = round($row2['precio'],2);
    $el_add.="<tr>";
    $el_add.="<td>".$descripcion."</td>";
    $el_add.="<td>".$costo."</td>";
    $el_add.="<td>".$precio."</td>";
    $el_add.="<td>".$gal_fact."</td>";
    $el_add.="<td>".$subtotal."</td>";


      if ($row2['id_prod']==2){
        $dif=$lect_head_row['gal_super'] -$gal_fact;
        $el_add.="<td>".$lect_head_row['gal_super']."</td>";
        $el_add.="<td>".$lect_head_row['dinero_super']."</td>";
        $el_add.="<td>".$dif."</td>";
      }
      if ($row2['id_prod']==1){
        $dif=$lect_head_row['gal_regular'] -$gal_fact;
        $el_add.="<td>".$lect_head_row['gal_regular']."</td>";
        $el_add.="<td>".$lect_head_row['dinero_regular']."</td>";
        $el_add.="<td>".$dif."</td>";
      }
      if ($row2['id_prod']==3){
        $dif=$lect_head_row['gal_diesel'] -$gal_fact;
        $el_add.="<td>".$lect_head_row['gal_diesel']."</td>";
        $el_add.="<td>".$lect_head_row['dinero_diesel']."</td>";
        $el_add.="<td>".$dif."</td>";
      }



    $el_add.="</tr>";
    /*
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
    }*/

    $total_vf += $subtotal;
    $total_gal_vf += $row2['cant_galones'];
    //$table->printRow();
  }
  echo $el_add;
}
//
function getLecturas($id_apertura){
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


if (!isset($_REQUEST['process']))
{
  initial();
}
if (isset($_REQUEST['process']))
{
  switch ($_REQUEST['process'])
  {
    case 'actualizar_lectura':
    actualizar_lectura();
    break;
    case 'insertar':
    insertar();
    break;
    case 'fact_apertura':
    fact_apertura();
    break;
  }
}
?>
