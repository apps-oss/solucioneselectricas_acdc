<?php
include("_core.php");
function initial()
{
  $title = 'Existencia combustible Diario';
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
  //
  $id_apertura  = $_REQUEST['id_apertura'];
  if(isset($_REQUEST['fecha'])){
      $fecha_actual = $_REQUEST['fecha'];
  }else{
    $fecha_actual=date("Y-m-d");
  }
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
									<thead class='thead1' style=' background-color:#ffffff'>
										<tr>
											<th > N°</th>
											<th >COD. PROD</th>
                      <th class='text-left'>&nbsp;&nbsp;DESCRIPCION</th>
											<th >STOCK</th>
                      <th class='text-left' >CONTEO</th>
                      <th class='text-center' >DIFERENCIA</th>
										</tr>
									</thead>
									<tbody class='tbody1 table' id="inventable">
                    <?php
                    $result= getProdAceiteLub();
                    $count=_num_rows($result);

                    if ($count>0 ){
                        for($i=1;$i<=$count;$i++){

                          $row1=_fetch_array($result);
                          $id_producto=$row1['id_producto'];
                          $row2=getStockByProd($id_producto,$id_sucursal);
                          $invinit=$row2[0];
                          $row3 =getAceiteLubByProd($id_producto,$id_sucursal,$fecha_actual);
                          $invfin=$invinit - $row3[1];
                          $input_exist="<input type='text' class='form-control existencias' id='existen' name='existen' style='min-width: 0;width: auto;'";
                          $input_exist.="value='".$invfin."'>";
                          $diferencia=$invinit-$invfin;
                          echo "<tr>";
                          echo "<td class='text-center'>".$i."</td>";
                          echo "<td class='text-center'>".$row1['id_producto']."</td>";
                          echo "<td class='text-left'>".$row1['descripcion']."</td>";
                          echo "<td class='text-center'>".$invinit."</td>";
                          echo "<td class='text-center' >".$input_exist."</td>";
                          echo "<td class='text-center'style='min-width: 0;width: auto;'>".$diferencia."</td>";
                          echo "</tr>";

                        }
                    }
                     ?>
									</tbody>
                  <tfoot class='tfoot1' id="footable1">
                  </tfoot>
								</table>
							 </div>
              </div>
              <div class="col-md-6">
                </div>
						</div>
          <!--/section-->
          <?php
              include ("footer.php");
              echo "<script src='js/plugins/sweetalert/sweetalert2.all.min.js'></script>";
                    echo "<script src='js/plugins/cellNavigate.js'></script>";
              echo "<script src='js/funciones/lectura_lub_dia.js'></script>";
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
  $id_apertura = $_POST['id_apertura'];
  $hora        = date("H:i:s");
  $id_usuario  = $_SESSION['id_usuario'];
  $id_sucursal = $_SESSION['id_sucursal'];
  if ($cuantos>0) {
      $table= 'lectura_lub_dia';

      $array = json_decode($array_json, true);
      foreach ($array as $fila) {
        $id_producto =  $fila['id_producto'];
        $existencias =  $fila['existencias'];
        $exist_ante  =  $fila['exist_ante'];
        $diferencia  = $exist_ante -  $existencias ;

        $sql = "SELECT * FROM $table
        WHERE id_apertura = '$id_apertura'
        AND id_producto = '$id_producto'
        ";
        $res=_query($sql);
        $valor= 0;
        if(_num_rows($res)==0){
          $data_det = array(
            'id_apertura'    => $id_apertura,
            'id_producto'    => $id_producto,
            'id_sucursal'    => $id_sucursal,
            'id_usuario'     => $id_usuario,
            'fecha'          => $fecha,
            'hora'           => $hora,
            'inv_sistema'    => $exist_ante,
            'conteo'         => $existencias,
            'diferencia'     => $diferencia,
          );
          $insertar = _insert($table, $data_det);
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

function getProdAceiteLub(){
  $sql = " SELECT p.id_producto, p.descripcion,c.id_categoria
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  WHERE c.combustible=0 AND c.pista=1
  ";
  $res=_query($sql);
  return $res;
}
function getStockByProd($id_producto,$id_sucursal){
  $sql="  SELECT  COALESCE(SUM(su.cantidad),0) as cantidad
  FROM stock_ubicacion AS su
    JOIN ubicacion as u ON su.id_ubicacion=u.id_ubicacion
  WHERE su.id_producto='$id_producto'
  AND su.id_sucursal='$id_sucursal'
 and u.descripcion LIKE 'PISTA%';
  ";
  //
  $res=_query($sql);
  if(_num_rows($res)>0){
    $row = _fetch_row($res);
  }
  return $row;
}
function getAceiteLubByProd($id_producto,$id_sucursal,$fecha){
  $sql = "SELECT
  COALESCE(SUM(fd.total),0) as total_venta,COALESCE(SUM(fd.cantidad),0) as cantidad,
  fd.precio_venta
  FROM producto as p JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN factura_detalle AS fd ON fd.id_prod_serv=p.id_producto
  JOIN factura AS f ON f.id_factura=fd.id_factura
  JOIN presentacion_producto AS pp ON fd.id_presentacion=pp.id_pp
  WHERE c.combustible=0
  AND c.pista=1
  AND fd.id_prod_serv='$id_producto'
  AND f.id_sucursal='$id_sucursal'
  AND f.fecha= '$fecha'
  ";
  $res=_query($sql);
  $row = _fetch_row($res);
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
  }
}
?>
