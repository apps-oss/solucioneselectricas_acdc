<?php
include("_core.php");
function initial()
{
  $title = 'Administrar Lecturas diarias por Bomba';
  include_once "_headers.php";
  $_PAGE ['title'] = $title;
  include_once "header.php";
  $id_sucursal=$_SESSION['id_sucursal'];
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user, $filename);
  $fecha_actual=date("Y-m-d");
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
            <section>
                <div class="row">
                  <div class="col-md-2">
  										<div class="form-group has-info">
  											<label>Fecha:</label>
  											<input type='text' placeholder='Fecha' class='datepicker form-control' id='fecha' name='fecha' value='<?php echo $fecha_actual;?>'>
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
                        $data_comb.="id='comb".$idcomb."' name='fecha' value='$preciocomb' readonly>";
                        $data_comb.="</div>";
                        $data_comb.="</div>";
                        echo $data_comb;
                    }
                    $total_dia = getFacturaCombustible($id_sucursal,$fecha_actual);
                    ?>
                    <div class="col-md-2">
                      <label>total Fact. Combustibles en Sistema</label>
                      <input type='text'  class=' form-control' id='total_dia' name='total_dia' value='<?= $total_dia?>' readonly/>
                    </div>
                  <div class="col-md-2 pull-right"><br>
                    <a class="btn btn-sm btn-danger pull-right" style="margin-left:1%;" href="dashboard.php" id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                    <button type="button" id="submit1" name="submit1" class="btn btn-sm btn-primary pull-right usage"><i class="fa fa-save"></i> F2 Guardar</button>
                    </div>
                </div>
                <div class="row">
							<div class="col-md-12 table-responsive">
								<table class="table" id='loadtable'>
									<thead class='thead1' style=' background-color:#263238; color:#F0F4C3'>
										<tr>
											<th class="col-lg-1">Bomba N°</th>
											<th class="col-lg-1">Combustible</th>
                      <th class="col-lg-1">Lectura Anterior</th>
											<th class="col-lg-1">Lect. Inicial</th>
											<th class="col-lg-1">Lect. Final</th>
                      <th class="col-lg-1">Devol.</th>
                      <th class="col-lg-1">Galones</th>
											<th class="col-lg-1">Efectivo Ini.</th>
											<th class="col-lg-1">Efectivo Fin</th>
                      <th class="col-lg-1">Devol.</th>
											<th class="col-lg-1">Venta</th>
										</tr>
									</thead>
									<tbody class='tbody1' id="inventable">
                    <?php getLecturaBomba($fecha_actual,$id_sucursal,0) ?>
									</tbody>
                  <tfoot class='tfoot1' id="footable">
                    <th class="col-lg-1" colspan=6>Totales</th>
                    <th class="col-lg-1" colspan=1><input type='text' id='total_galon' name='total_galon' value='0' readonly></th>
                    <th class="col-lg-1" colspan=3></th>
                    <th class="col-lg-1" colspan=1><input type='text' id='total_efectivo' name='total_efectivo' value='0' readonly></th>
                  </tbody>
								</table>
							</div>
						</div>
          </section>
          <?php
              include ("footer.php");
              echo "<script src='js/funciones/lectura_dia.js'></script>";
              echo "<script src='js/funciones/util.js'></script>";
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

function getLecturaBomba($fecha_actual,$id_sucursal,$js=0){
  $fromJavascript=0;
if($js==1){
  $fromJavascript=1;
}
  $fecha_ante =restar_dias($fecha_actual,1);
  $sql="SELECT * FROM bomba WHERE id_sucursal='$id_sucursal' AND activa=1";
  $res=_query($sql);
  $filas=0; $filas2=0;
  $tr_add = "" ;
  while ($row=_fetch_array($res)) {
    $id=$row['id'];
    $descripcion="<h5 class='text-primary'><strong>".$row['descripcion']."</strong></h5>";
    $n=3;
    for($i=0;$i<$n;$i++){
      $diesel_activo=0;
      if($row['diesel']==1){
        $id_manguera=3;
        $combust='DIESEL';
        $style='background-color:#B0BEC5';
        addRowCombustible($id_manguera,$fecha_ante,$id,$filas,$descripcion,$combust,$i,$style,$fromJavascript);
        $i++;
      }
      if($row['regular']==1){
        $id_manguera=2;
        $combust=' G. REGULAR';
        $style='background-color:#CFD8DC';
        addRowCombustible($id_manguera,$fecha_ante,$id,$filas,$descripcion,$combust,$i,$style,$fromJavascript);
        $i++;
      }
      if($row['super']==1){
        $id_manguera=1;
        $combust='G. SUPER';
          $style='background-color:#E0E0E0';
          addRowCombustible($id_manguera,$fecha_ante,$id,$filas,$descripcion,$combust,$i,$style,$fromJavascript);
        $i++;
      }
    }
    $filas++;
  }
}
function getLecturaBombaAnte($fecha_ante,$id_bomba,$id_tipo_combustible){
    $sql="SELECT fin_combustible FROM lectura_detalle_bomba WHERE id_bomba='$id_bomba' AND fecha='$fecha_ante'";
    $res=_query($sql);
    $valor= 0;
    if(_num_rows($res)>0){
      $row=_fetch_row($res);
      $valor=$row[0];
    }
    return $valor;
}
function addRowCombustible($tipo_comb,$fecha_ante,$id_bomba,$filas,$bomba,$descrip_combustible,$i,$style,$fromJavascript){
    $lectura_ante= getLecturaBombaAnte($fecha_ante,$id_bomba,$tipo_comb);
    if($tipo_comb==3){
      $text_color='text-danger';
      $identifier='diesel';
    }
    if($tipo_comb==2){
      $text_color='text-success';
        $identifier='regular';
    }
    if($tipo_comb==1){
      $text_color='text-info';
      $identifier='super';
    }
    $lect_ante="<input type='text'  class='form-control decimal2  diesel'
        id='ante' name='ante' value='$lectura_ante' style='width:100%;' readonly >";
    $descombust="<h5 class='$text_color'><strong> ".$descrip_combustible." </strong></h5>";
    $input_qty="<input type='text'  class='form-control decimal qty  $identifier'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;' >";
    $input_read="<input type='text'  class='form-control decimal qty  $identifier'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;' readonly>";
    $input_devol_efect="<input type='text'  class='form-control decimal qty devol_efect $identifier'
        id='qty_$identifier' name='qty_$identifier' value='' style='width:100%;' y>";
    $diesel_activo=' readonly ';
    $tr_add  ="<tr  class='fila ' id='".$filas. "' style='$style'>";
    $tr_add .="<td class='bomba'>" .$bomba.'</td>';
    $tr_add .="<td class='combustible'>".$descombust."</td>";
    $tr_add .="<td class='text-success'>".$lect_ante."</td>";
    $tr_add .="<td class='text-success'>".$input_qty."</td>";
    $tr_add .="<td class='text-success'>".$input_qty."</td>";
    $tr_add .="<td class='text-success'>".$input_qty."</td>";
    $tr_add .="<td class='text-success'>".$input_read."</td>";
    $tr_add .="<td class='text-success'>".$input_read."</td>";
    $tr_add .="<td class='text-success'>".$input_read."</td>";
    $tr_add .="<td class='text-success'>".$input_devol_efect."</td>";
    $tr_add .="<td class='text-success'>".$input_read."</td>";
    $tr_add .='</tr>';
    if($fromJavascript==0){
      echo $tr_add;
    }else{
      return ($tr_add);
    }

}
function getFacturaCombustible($id_sucursal,$fecha){
    $sql="SELECT COALESCE(SUM(fd.subtotal),0) as total_dia
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
    }
    return $valor;
}
function actualizar_lectura(){
  $fecha                = $_REQUEST['fecha'];
  $id_sucursal          = $_SESSION['id_sucursal'];
  $fact_fecha           = getFacturaCombustible($id_sucursal,$fecha);
  $tr_add               = getLecturaBomba($fecha,$id_sucursal,1);
  $xdatos["tr_add"]     = $tr_add;
  $xdatos["fact_fecha"] = $fact_fecha;
  echo json_encode ($xdatos); //Return the JSON Array
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

  }
}
?>
