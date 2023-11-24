<?php
include_once "_core.php";
function initial()
{
  $title = 'Agregar Vehículo';
  include_once "_headers.php";
	$_PAGE ['title'] = $title;
  $_PAGE ['links'] .= '<link href="css/plugins/upload_file/fileinput.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);

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
                        $select=crear_select("marca", $array1, '-1', "width:100%;");
                        echo $select;?>
											<span>
									</span>
										</div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Tipo Vehiculo</label>
                      <?php
                        $array2=getTipoVehiculo();
                        //crear_select($nombre,$array,$id_valor,$style)
                        $select2=crear_select("tipo_vehiculo", $array2, '-1', "width:100%;");
                        echo $select2;?>
                    </div>
                    <div class="form-group">
                      <label for="vin">VIN</label>
                      <input type="text" class="form-control" name="vin" value="" id="vin">

                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Numero de placa</label>
                      <input type="text" class="form-control" name="placa" value="" id="placa">

                    </div>

                    <div class="form-group">
                      <label for="llantas">No. de Llantas</label>
                      <input type="text" class="form-control" name="llantas" id="llantas">
                    </div>

                    <div class="form-group">
                      <label for="color">color</label>
                      <input type="text" class="form-control" name="color" id="color">
                    </div>
                    <div class="form-group has-info">
                      <label for="mes">Mes de Vencimiento tarjeta </label>
                  <?php
                    $meses=select_meses("mes");
                    echo $meses;?>
                      <span>
                  </span>
                    </div>
									</div>
                  	<div class="col-md-6">
                      <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <?php
                      $id_val=-1;
                      $selectm="modelo";
                      $array3["-1"] = "...";;
                      $select3=crear_select($selectm,$array3,$id_val,"width:100%;");
                      echo $select3;
                      ?>

                      </div>
                      <div class="form-group">
                        <label for="tipo_combustible">Tipo de combustible</label>
                        <?php
                      $id_val=-1;
                      $select4="tipo_combustible";
                      $array4 =  getCombustibles();
                      $select4=crear_select($select4,$array4,$id_val,"width:100%;");
                      echo $select4;
                      ?>
                      </div>
                      <div class="form-group">
                        <label for="year">Año<span class="text-danger">*</span></label>
                                <?php
                                // Sets the top option to be the current year. (IE. the option that is chosen by default).
                                $currently_selected = date('Y')-10;
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
                        <input type="text" class="form-control numeric" name="unidad" id="unidad">
                      </div>
                      <div class="form-group">
                        <label for="capacidad">Descripci&oacute;n Capacidad</label>
                        <input type="text" class="form-control" name="capacidad" value="" id="capacidad">
                        <span>
                    </span>
                      </div>
                      <div class="form-group">
                        <label for="ejes">Ejes</label>
                        <input type="text" class="form-control" name="ejes" id="ejes">
                      </div>
                      </div>
								</div>
							</div>
							<div class="box-footer text-center">
                  <input type="hidden" name="process" id="process" value="insert"><br>
								<button type="submit"  id="submit1" name="submit1"  class="btn btn-primary">Guardar</button>
							</div>
						</div>
					</div>
				</form>

        <?php modalImgVehiculo() ?>
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

function insertar()
{
  $marca=$_POST["marca"];
  $modelo=$_POST["modelo"];
  $tipo_vehiculo=$_POST["tipo_vehiculo"];
  $tipo_combustible=$_POST["tipo_combustible"];
  $vin=$_POST["vin"];
  $placa=$_POST["placa"];
  $llantas=$_POST["llantas"];
  $color=$_POST["color"];
  $mes_vence=$_POST["mes"];
  //$dia_vence=$_POST["dias_mes"];
  $anio=$_POST["year"];
  $unidad=$_POST["unidad"];
  $capacidad=$_POST["capacidad"];
  $ejes=$_POST["ejes"];


  $id_sucursal = $_SESSION["id_sucursal"];
  $sql_exis=_query("SELECT placa FROM vehiculo WHERE placa ='$placa'");
  $num_exis = _num_rows($sql_exis);
  if($num_exis > 0)
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Ya se registro un vehiculo con estos datos!';
  }
  else
  {
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
    $insertar   = _insert($table,$form_data );
    $id = _insert_id();
    if($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro guardado con exito!';
      $xdatos['process']='insert';
      $xdatos['id']=$id;
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser guardado !'._error();
    }
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
function modalImgVehiculo(){
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
														<input type="hidden" name="id_id_p" id="id_id_p">
														<input type="hidden" name="process" id="process" value="insert_img">
												</div>
											</div>
										</div>
									</form>
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
function insert_img()
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
      case 'insert':
      insertar();
      break;
      case 'modelo':
      modelo();
      break;
      case 'dias_mes':
      dias_mes();
      break;
      case 'insert_img':
      insert_img();
      break;
    }
  }
}
?>
