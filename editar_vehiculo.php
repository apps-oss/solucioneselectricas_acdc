<?php
include_once "_core.php";
function initial()
{
  $title = 'Editar Vehículo';
  include_once "_headers.php";
	$_PAGE ['title'] = $title;
  $_PAGE ['links'] .= '<link href="css/plugins/upload_file/fileinput.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
  $id=$_REQUEST["id"];
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links = permission_usr($id_user,$filename);
  $datos = getDataVehiculo($id);
  //$nombre = $datos["nombre"];
  $marca=$datos["marca"];
  $modelo=$datos["modelo"];
  $tipo_vehiculo=$datos["tipo_vehiculo"];
  $tipo_combustible=$datos["tipo_combustible"];
  $vin=$datos["vin"];
  $placa=$datos["placa"];
  $llantas=$datos["llantas"];
  $color=$datos["color"];
  $mes_vence=$datos["mes_vence_tarjeta"];
  $anio=$datos["year"];
  $unidad=$datos["numero_unidad"];
  $capacidad=$datos["capacidad"];
  $ejes=$datos["ejes"];
  $imagen=$datos["imagen"];
  ?>
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2">
    </div>
  </div>
  <div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox">
          <?php
          //permiso del script
          if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
              <h5><?php echo $title; ?></h5>
            </div>
            <div class="ibox-content">
              <div class="row">
      					<div class="col-md-6"></div>
      					<div class="col-md-6">
      						<div class="text text-center">
                    <a id="btn_img" name="btn_img" class="btn btn-primary m-t-n-xs pull-right" style="margin-right:10px; margin-top: 1px;"><i class="fa fa-image"></i> Agregar Imagen</a>
      						</div>
      					</div>
      				</div>
			  <form name="formulario" id="formulario">
					<div class="row">
						<div class="col-md-12">
							<div class="box-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="marca">Marca</label>
                      <?php
                        $array1=getMarcas();
                        $select=crear_select("marca", $array1, $marca, "width:100%;");
                        echo $select;?>
											<span>
									</span>
										</div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Tipo Vehiculo</label>
                      <?php
                        $array2=getTipoVehiculo();
                        $select2=crear_select("tipo_vehiculo", $array2, $tipo_vehiculo, "width:100%;");
                        echo $select2;?>
                    </div>
                    <div class="form-group">
                      <label for="vin">VIN</label>
                      <input type="text" class="form-control" name="vin" value="<?= $vin;?>" id="vin">
                      <span>
                  </span>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Numero de placa</label>
                      <input type="text" class="form-control" name="placa" value="<?= $placa;?>"  id="placa">
                      <span>
                  </span>
                    </div>

                    <div class="form-group">
                      <label for="llantas">No. de Llantas</label>
                      <input type="text" class="form-control" name="llantas" id="llantas" value="<?= $llantas;?>" >
                    </div>

                    <div class="form-group">
                      <label for="color">color</label>
                      <input type="text" class="form-control" name="color" id="color" value="<?= $color;?>" >
                    </div>
                    <div class="form-group has-info">
                      <label for="mes">Mes de Vencimiento tarjeta </label>
                  <?php
                    $meses=select_meses("mes",$mes_vence);
                    echo $meses;?>
                      <span>
                  </span>
                    </div>
									</div>
                  	<div class="col-md-6">
                      <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <?php
                      $selectm="modelo";
                      $array3["-1"] = "...";;
                      $select3=crear_select($selectm,$array3,$modelo,"width:100%;");
                      echo $select3;
                      ?>
                        <span>
                    </span>
                      </div>
                      <div class="form-group">
                        <label for="tipo_combustible">Tipo de combustible</label>
                        <?php
                      $id_val=-1;
                      $select4="tipo_combustible";
                      $array4 =  getCombustibles();
                      $select4=crear_select($select4,$array4,$tipo_combustible,"width:100%;");
                      echo $select4;
                      ?>
                      </div>
                      <div class="form-group">
                        <label for="year">Año<span class="text-danger">*</span></label>
                                <?php
                                // Sets the top option to be the current year. (IE. the option that is chosen by default).
                                $currently_selected = $anio;
                                // Year to start available options at
                                $earliest_year = 1980;
                                // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                                $latest_year = date('Y');
                                ?>
                                <select class="form-control selectt select2" id="year" name="year">
                                  <?php
                                  // Loops over each int[year] from current year, back to the $earliest_year [1987]
                                  foreach ( range( $latest_year, $earliest_year ) as $i ) {
                                    // Prints the option with the next year in range.
                                    echo '<option value="'.$i.'"'.($i == $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
                                  }
                                  ?>
                                </select>
                      </div>
                      <div class="form-group">
                        <label for="unidad">Identificador de Unidad(N&uacute;mero)</label>
                        <input type="text" class="form-control numeric" name="unidad" id="unidad" value="<?= $unidad;?>">
                      </div>
                      <div class="form-group">
                        <label for="capacidad">Descripci&oacute;n Capacidad</label>
                        <input type="text" class="form-control" name="capacidad" value="" id="capacidad" value="<?= $capacidad;?>">
                        <span>
                    </span>
                      </div>
                      <div class="form-group">
                        <label for="ejes">Ejes</label>
                        <input type="text" class="form-control" name="ejes" id="ejes" value="<?= $ejes;?>">
                      </div>
                      </div>
								</div>
							</div>
							<div class="box-footer text-center">
                <input type="hidden" name="id" id="id"  value="<?= $id;?>">
                	<input type="hidden" name="id_id_p" id="id_id_p" value="<?= $id;?>">
                  <input type="hidden" name="process" id="process" value="edited"><br>
								<button type="submit"  id="submit1" name="submit1"  class="btn btn-primary">Guardar</button>
							</div>
						</div>
					</div>
				</form>
          <?php modalImgVehiculo($id,$imagen) ?>
			</div>
          </div>
        </div>
      </div>
    </div>
    <?php
    include_once ("footer.php");
    echo "<script src='js/funciones/vehiculo.js'></script>";
  } //permiso del script
  else
  {
    echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
  }
}
function getDataVehiculo($id=-1){
	$sql="SELECT * FROM vehiculo WHERE id='$id'";
	$result=_query($sql);
  $datos = _fetch_array($result);
  return $datos;
}
function insertar()
{
  $id=$_POST["id"];
  $marca=$_POST["marca"];
  $modelo=$_POST["modelo"];
  $tipo_vehiculo=$_POST["tipo_vehiculo"];
  $tipo_combustible=$_POST["tipo_combustible"];
  $vin=$_POST["vin"];
  $placa=$_POST["placa"];
  $llantas=$_POST["llantas"];
  $color=$_POST["color"];
  $mes_vence=$_POST["mes"];
  $anio=$_POST["year"];
  $unidad=$_POST["unidad"];
  $capacidad=$_POST["capacidad"];
  $ejes=$_POST["ejes"];

  $id_sucursal = $_SESSION["id_sucursal"];


    $table = 'vehiculo';
    $form_data = array(
      'id_marca' => $marca,
      'id_modelo' => $modelo,
      'tipo_vehiculo'=>$tipo_vehiculo,
      'tipo_combustible'=>$tipo_combustible,
      'placa'=>$placa,
      'vin' => $vin,
      'anio'=>$anio,
      'numero_unidad'=>$unidad,
      'llantas'=>$llantas,
      'ejes'=>$ejes,
      'color'=>$color,
      'mes_vence_tarjeta'=>$mes_vence,
      'capacidad'=>$capacidad,
    );
    $where = "id='".$id."'";
    $update = _update($table,$form_data,$where);
    if($update)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro guardado con exito!';
      $xdatos['process']='edited';
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser guardado !'._error();
    }

  echo json_encode($xdatos);
}
function modelo()
{
  $id_marca = $_POST["id_marca"];
  $option = "";
  $sql = _query("SELECT * FROM modelo WHERE id_marca='$id_marca'");
  while($dt=_fetch_array($sql))
  {
    $option .= "<option value='".$dt["id_modelo"]."'>".$dt["modelo"]."</option>";
  }
  echo $option;
}
function dias_mes()
{
  $mes = $_POST["mes"];
  $option = diasXMes($mes);
  echo $option;
}
function modalImgVehiculo($id,$imagen){
	?>
	<div class='modal fade' id='viewVehiculo' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class="modal-header">
					<button type="button" class="close" id='cerrar_ven' data-dismiss="modal"
					aria-hidden="true">&times;</button>
					<h4 class="modal-title">Agregar Imagen</h4>
				</div>
				<div class="modal-body">
					<div class="wrapper wrapper-content  animated fadeInRight">
									<form name="formulario_pro" id="formulario_pro" enctype='multipart/form-data' method="POST">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-info single-line">
														<label>Seleccione Imagen</label>
														<input type="file" name="logo" id="logo" class="file" data-preview-file-type="image">
														<input type="hidden" name="id_id_p" id="id_id_p"  value="<?php echo $id;?>">
														<input type="hidden" name="process" id="process" value="editar_img">
												</div>
											</div>
										</div>
									</form>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-lg-12 center-block">
                        <div class="widget style1 gray-bg text-center">
                          <div class="m-b-md" id='imagen'>
                            <img alt="image" class="img-rounded" src=<?php echo $imagen; ?> width="250px" height="150px" border='1'>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="btnGimg">Guardar</button>
						<button type="button" class="btn btn-default bb" data-dismiss="modal">Cerrar</button>
					</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php
}
function editar_img()
{
		require_once 'class.upload.php';
		$id = $_POST["id_id_p"];
		if ($_FILES["logo"]["name"]!="")
		{
		$foo = new Upload($_FILES['logo'],'es_ES');
		if ($foo->uploaded) {
				$pref = uniqid()."_";
				$foo->file_force_extension = false;
				$foo->no_script = false;
				$foo->file_name_body_pre = $pref;
			 // save uploaded image with no changes
			 $foo->Process('img/vehiculos/');
			 if ($foo->processed)
			 {
				 $query = _query("SELECT imagen FROM vehiculo WHERE id='$id'");
				 $result = _fetch_array($query);
				 $urlb=$result["imagen"];
				 if($urlb!="")
				 {
						 unlink($urlb);
				 }
				$cuerpo=quitar_tildes($foo->file_src_name_body);
				$cuerpo=trim($cuerpo);
				$url = 'img/vehiculos/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
				$table = 'vehiculo';
				$form_data = array (
				'imagen' => $url,
				);
				$where_clause = "id='".$id."'";
				$editar =_update($table, $form_data, $where_clause);
				if($editar)
				{
					 $xdatos['typeinfo']='Success';
					 $xdatos['msg']='Datos guardados correctamente !';
					 $xdatos['process']='edit';
				}
				else
				{
					 $xdatos['typeinfo']='Error';
					 $xdatos['msg']='Error al guardar los dartos!'._error();
				}
			 }
			 else
			 {
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Error al guardar la imagen!';
			 }
		}
		else
		{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al subir la imagen!';
		}
		}
		else
		{
			 $xdatos['typeinfo']='Success';
			 $xdatos['msg']='Datos guardados correctamente !';
			 $xdatos['process']='edit';
		}
		echo json_encode($xdatos);
}

if(!isset($_POST['process']))
{
  initial();
}
else
{
  if(isset($_POST['process']))
  {
    switch ($_POST['process'])
    {
      case 'edited':
      insertar();
      break;
      case 'modelo':
      modelo();
      break;
      case 'dias_mes':
      dias_mes();
      break;
      case 'editar_img':
      editar_img();
      break;
    }
  }
}
?>
